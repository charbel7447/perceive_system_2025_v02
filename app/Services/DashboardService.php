<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Invoice\Invoice;
use App\Expense\Expense;
class DashboardService
{
    public static function getWidgetData($widgetId)
    {
        switch ($widgetId) {
            case 1: return self::totalSales();
            case 2: return self::totalPurchases();
            case 3: return self::profitLossPieChart();
            default:
                return ['type' => 'html', 'html' => 'No data'];
        }
    }

    // Total Sales (table)
// App/Services/DashboardService.php
private static function totalSales()
{
    $statusMap = [
        1  => 'Draft',
        2  => 'Confirmed',
        3  => 'Partially Paid',
        4  => 'Paid',
        5  => 'Void',
        7  => 'Adjusted',
        9  => 'Reopen',
        10 => 'Returned',
    ];

    // Map each status to a Tailwind color class
    $statusColor = [
        'Draft'          => 'bg-gray-200 text-gray-800',
        'Confirmed'      => 'bg-blue-200 text-blue-800',
        'Partially Paid' => 'bg-yellow-200 text-yellow-800',
        'Paid'           => 'bg-green-200 text-green-800',
        'Void'           => 'bg-red-200 text-red-800',
        'Adjusted'       => 'bg-purple-200 text-purple-800',
        'Reopen'         => 'bg-indigo-200 text-indigo-800',
        'Returned'       => 'bg-pink-200 text-pink-800',
        'Unknown'        => 'bg-gray-100 text-gray-700',
    ];

    $rows = \App\Invoice\Invoice::with(['client','currency'])
        ->orderBy('date', 'desc')
        ->take(10)
        ->get()
        ->map(function($inv) use ($statusMap, $statusColor) {
            $status = $statusMap[$inv->status_id] ?? 'Unknown';
            $colorClass = $statusColor[$status] ?? 'bg-gray-100 text-gray-700';

            return [
                'id'         => $inv->id,
                'date'       => $inv->date,
                'inv_number' => $inv->number,
                'client'     => ['text' => $inv->client->name ?? 'N/A'],
                'total'      => $inv->total,
                'created_by' => $inv->created_by,
                // Wrap status in a span with Tailwind classes
                'status'     => "<span class='px-2 py-1 rounded-full $colorClass'>$status</span>",
            ];
        });

    return [
        'type'    => 'table',
        'columns' => ['ID','Date','inv_number','Client','Total','Created By','Status'],
        'rows'    => $rows,
    ];
}


    private static function totalPurchases()
    {
          $statusMap = [
            1  => 'Draft',
            2  => 'SENT',
            3  => 'CONFIRMED',
            4  => 'BILLED',
            5  => 'CANCELLED',
            6  => 'CLOSED',
            7  => 'RECEIVED',
            8  => 'PARTIALLY_RECEIVED',
            9  => 'Reopen',
        ];

           

        // Map each status to a Tailwind color class
        $statusColor = [
            'Draft'          => 'bg-gray-200 text-gray-800',
            'SENT'      => 'bg-blue-200 text-blue-800',
            'CONFIRMED' => 'bg-yellow-200 text-yellow-800',
            'BILLED'           => 'bg-green-200 text-green-800',
            'CANCELLED'           => 'bg-red-200 text-red-800',
            'CLOSED'       => 'bg-purple-200 text-purple-800',
            'RECEIVED'         => 'bg-indigo-200 text-indigo-800',
            'PARTIALLY_RECEIVED'       => 'bg-pink-200 text-pink-800',
            'Unknown'        => 'bg-gray-100 text-gray-700',
        ];


        $rows = \App\PurchaseOrder\PurchaseOrder::with(['vendor','currency'])
            ->orderBy('date', 'desc')
            ->take(10)
            ->get(['id','date','number','vendor_id','total','created_by','status_id'])
            ->map(function($p) use ($statusMap, $statusColor) {
            $status = $statusMap[$p->status_id] ?? 'Unknown';
            $colorClass = $statusColor[$status] ?? 'bg-gray-100 text-gray-700';
                return [
                    'id' => $p->id,
                    'date' => $p->date,
                    'po_number' => $p->number,
                    'supplier' => $p->vendor->company,
                    'total' => $p->total,
                    'created_by' => $p->created_by,
                     // Wrap status in a span with Tailwind classes
                    'status'     => "<span class='px-2 py-1 rounded-full $colorClass'>$status</span>",
                ];
            });

        return [
            'type' => 'table',
            'columns' => ['ID','Date','po_number','Supplier','Total','Created By','Status'],
            'rows' => $rows
        ];
    }


    private static function profitLossPieChart($from = null, $to = null, $accountId = null)
{
    // Relevant classes for P&L: Revenue(4), Expenses(5), Other Income(6), Other Expenses(7)
    $classCodes = ['4','5','6','7'];

    // Load classes with accounts, optional account filter
    $classes = \App\ClassesCode::whereIn('code', $classCodes)
        ->with(['accounts' => function($q) use ($accountId) {
            if ($accountId) {
                $q->where('id', $accountId);
            }
        }])->orderBy('code')
        ->get();

    $totalIncome = 0.0;
    $totalExpense = 0.0;

    foreach ($classes as $class) {
        $classCode = $class->code;

        foreach ($class->accounts as $account) {
            $sum = \App\JournalVoucher\Item::query()
                ->where('account_id', $account->id)
                ->whereHas('journalVoucher', function($q) use ($from, $to) {
                    if ($from && $to) $q->whereBetween('date', [$from, $to]);
                    $q->where('status_id', 2); // Only posted vouchers
                })
                ->selectRaw('COALESCE(SUM(debit),0) as t_debit, COALESCE(SUM(credit),0) as t_credit')
                ->first();

            $debit  = (float) ($sum->t_debit ?? 0);
            $credit = (float) ($sum->t_credit ?? 0);

            // Income classes: credit - debit, Expenses: debit - credit
            if (in_array($classCode, ['4','6'])) {
                $amount = $credit - $debit;
                $totalIncome += $amount;
            } else {
                $amount = $debit - $credit;
                $totalExpense += $amount;
            }
        }
    }

    $netProfit = $totalIncome - $totalExpense;

    return [
        'type' => 'chart',
        'chart' => [
            'type' => 'pie',
            'data' => [
                'labels' => ['Income', 'Expenses', 'Net Profit'],
                'datasets' => [[
                    'data' => [$totalIncome, $totalExpense, $netProfit],
                    'backgroundColor' => ['#34D399', '#F87171', '#60A5FA'],
                ]],
            ],
            'options' => [
                'plugins' => [
                    'legend' => ['position' => 'bottom'],
                ]
            ]
        ]
    ];
}


    
}
