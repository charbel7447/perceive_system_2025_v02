<?php

namespace App\Services;

use App\JournalVoucher\JournalVoucher;
use App\JournalVoucher\JournalVoucherMovement as JournalMovement;
use App\JournalVoucher\JournalFlowMapping;
use App\JournalVoucher\Item as JournalVoucherItem;
use App\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Exception;
use Auth;

class JournalService
{
    /**
     * Create a journal voucher from given document data and type.
     *
     * @param  array   $documentData
     * @param  string  $type
     * @return JournalVoucher|null
     */
    public function create_journal_voucher(array $documentData, string $type): ?JournalVoucher
    {
        DB::beginTransaction();

        try {
            // Fetch flow mapping JSON from DB
            $flow = JournalFlowMapping::where('process', $type)->value('mappings');
            if (is_string($flow)) {
                $flow = json_decode($flow, true);
            }
            if (!$flow || empty($flow)) {
                throw new Exception("No journal mapping found for type: $type");
            }

            // Map document types including credit_note
            $types = [
                'invoice' => 1,
                'bill' => 2,
                'purchase_order' => 3,
                'sales_order' => 4,
                'credit_note' => 6,
            ];
            $type_id = $types[$type] ?? 5;

            // throw new \Exception ($type);

            // Calculate total for credit_note with multi-currency logic
            if ($type === 'credit_note' || $type === 'debit_note' || $type === 'advance_payment') {
                if($type === 'credit_note'){
                    $manual_type = 'credit_notes';
                }
                if($type === 'debit_note'){
                    $manual_type = 'debit_notes';
                }
                if($type === 'advance_payment'){
                    $manual_type = 'advance_payments';
                }

                $amount_received = floatval($documentData['amount_received'] ?? 0);
                $amount_received_lbp = floatval($documentData['amount_received_lbp'] ?? 0);
                $exchange_rate = floatval($documentData['exchangerate'] ?? 1);
                $vat_received = 0;
                if($documentData['vat_received_usd'] > 0){
                    $vat_received += floatval($documentData['vat_received_usd'] ?? 1);
                }
                if($documentData['vat_received_lbp'] > 0){
                    $vat_received += floatval(($documentData['vat_received_lbp'] / ($exchange_rate ?: 1))  ?? 1);
                }
                
                $calculated_total = $amount_received + ($amount_received_lbp / ($exchange_rate ?: 1));
                $sub_total = $calculated_total - $vat_received;

            } else if($type === 'expenses'){
                $manual_type = 'expenses';
                $expense_id = $documentData['id'];
                $calculated_total  = 0;
            } else if($type === 'receipt_voucher'){
                $manual_type = 'receipt_vouchers';
                $receipt_voucher_id = $documentData['id'];
                $calculated_total  = 0;
            }else if($type === 'payment_voucher'){
                $manual_type = 'payment_vouchers';
                $payment_voucher_id = $documentData['id'];
                $calculated_total  = 0;
            }

            
            else if ($type === 'client_payment'){
                $manual_type = 'client_payments';

                $amount_received = floatval($documentData['amount_received'] ?? 0);
                $amount_received_usd = floatval($documentData['amount_received_usd'] ?? 0);
                $amount_received_lbp = floatval($documentData['amount_received_lbp'] ?? 0);
                $amount_received_lbprate = floatval($documentData['amount_received_lbprate'] ?? 1);
                $calculated_total = $amount_received_usd + $amount_received_lbp + $amount_received_lbprate;

            }else if ($type === 'vendor_payment'){
                $manual_type = 'vendor_payments';
                         
                $amount_paid = floatval($documentData['amount_paid'] ?? 0);
                $amount_paid_lbp = floatval($documentData['amount_paid_lbp'] ?? 0);
                $amount_paid_usd = floatval($documentData['amount_paid_usd'] ?? 0);
                $amount_paid_lbprate = floatval($documentData['amount_paid_lbprate'] ?? 1);
                $calculated_total = $amount_paid_lbp + $amount_paid_usd + $amount_paid_lbprate;
            }else {
                $calculated_total = floatval($documentData['total'] ?? 0);
            }

            // Create Journal Voucher
            $voucher = JournalVoucher::create([
                'number'              => counter()->next('journal_vouchers'),
                'document_type'       => $type_id,
                'manual_type'         => $manual_type ?? null,
                'document_id'         => $documentData['id'] ?? null,
                'document_number'     => $documentData['number'] ?? null,
                'document_total'      => $calculated_total,
                'document_currency_id'=> $documentData['currency_id'] ?? null,
                'date'                => $documentData['date'] ?? now(),
                'user_id'             => auth()->id(),
                'created_by'          => Auth::user()->name,
                'year_date'           => now()->format('Y'),
                'currency_id'         => $documentData['currency_id'] ?? null,
                'currency_name'       => $documentData['currency_name'] ?? null,
                'exchange_rate'       => $documentData['exchangerate'] ?? 1,
                'vat_rate'            => $documentData['vatrate'] ?? 0,
                'reference'           => $documentData['number'] ?? null,
                'total_debit'         => 0,
                'total_credit'        => 0,
                'status_id'           => 1,
            ]);

            counter()->increment('journal_vouchers');

            // Prepare field values based on document type
            $fieldValues = [];

            if ($type === 'credit_note' || $type === 'debit_note' || $type === 'advance_payment') {
                // Override to use calculated total and amounts for credit note
                if($type === 'credit_note'){
                    \App\CreditNote\CreditNote::where('id','=',$documentData['id'])->update(['journal_id' => $voucher->id]);
                }
                if($type === 'debit_note'){
                    \App\DebitNote\DebitNote::where('id','=',$documentData['id'])->update(['journal_id' => $voucher->id]);
                }
                if($type === 'advance_payment'){
                    \App\AdvancePayment\AdvancePayment::where('id','=',$documentData['id'])->update(['journal_id' => $voucher->id]);
                }
                $fieldValues = [
                    'Total Amount'  => $calculated_total,
                    'Sub Total' => $sub_total,
                    'Amount Received LBP' => $amount_received_lbp,
                    'VAT' => $vat_received,
                    // Add any other fields you want to include if mapped in flow
                ];
            }else if($type === 'expenses'){
                 // Override to use calculated total and amounts for expenses
                \App\Expense\Expense::where('id','=',$documentData['id'])->update(['journal_id' => $voucher->id]);
                $expense_debit_items = \App\Expense\Item::where('expense_id','=',$expense_id)->where('debit','>',0)->get();
                $expense_debit_items_vat = \App\Expense\Item::where('expense_id','=',$expense_id)->where('debit_vat','>',0)->get();
                $expense_credit_items = \App\Expense\Item2::where('expense_id','=',$expense_id)->get();
                foreach($expense_debit_items as $debit0){
                        $model0 = new JournalVoucherItem;
                        $model0->journal_voucher_id = $voucher->id;
                        $model0->account_id = $debit0->account_receivable_id;
                        $model0->account_code = $debit0->account_receivable_number;
                        $model0->account_name_en = $debit0->account_receivable_name;
                        $model0->account_name_ar =  ChartOfAccount::where('id','=',$debit0->account_receivable_id)->value('name_ar');
                        $model0->description = $debit0->description;
                        $model0->debit = $debit0->debit;
                        $model0->save();
                }
                foreach($expense_debit_items_vat as $debitvat){
                        $model1 = new JournalVoucherItem;
                        $model1->journal_voucher_id = $voucher->id;
                        $model1->account_id = $debitvat->account_receivable_debit_vat_id;
                        $model1->account_code = $debitvat->account_receivable_debit_vat_code;
                        $model1->account_name_en = $debitvat->account_receivable_debit_vat_name;
                        $model1->account_name_ar = ChartOfAccount::where('id','=',$debitvat->account_receivable_debit_vat_id)->value('name_ar');
                        $model1->description = $debitvat->description;
                        $model1->debit = $debitvat->debit_vat;
                        $model1->save();
                }

                foreach($expense_credit_items as $credit){
                    $model2 = new JournalVoucherItem;
                    $model2->journal_voucher_id = $voucher->id;
                    $model2->account_id = $credit->account_payable_id;
                    $model2->account_code = $credit->account_payable_number;
                    $model2->account_name_en = $credit->account_payable_name;
                    $model2->account_name_ar =  ChartOfAccount::where('id','=',$credit->account_payable_id)->value('name_ar');
                    $model2->description = $credit->description;
                    $model2->credit = $credit->debit;
                    $model2->save();
                }

               
               
            }else if($type === 'receipt_voucher'){
                 // Override to use calculated total and amounts for expenses
                $client_id = \App\ReceiptVoucher\ReceiptVoucher::where('id','=',$documentData['id'])->value('client_id');
                $client_account_id = \App\Client::where('id','=',$client_id)->value('account_code');
                $chart_credit_id = ChartOfAccount::where('code','=',$client_account_id)->value('id');

                \App\ReceiptVoucher\ReceiptVoucher::where('id','=',$documentData['id'])->update(['journal_id' => $voucher->id]);
                $rv_items = \App\ReceiptVoucher\Item::where('receipt_voucher_id','=',$receipt_voucher_id)->where('debit','>',0)->get();
                $rv_items_vat = \App\ReceiptVoucher\Item::where('receipt_voucher_id','=',$receipt_voucher_id)->where('debit_vat','>',0)->get();
                foreach($rv_items as $debit0){
                        $model0 = new JournalVoucherItem;
                        $model0->journal_voucher_id = $voucher->id;
                        $model0->account_id = $debit0->account_receivable_id;
                        $model0->account_code = $debit0->account_receivable_number;
                        $model0->account_name_en = $debit0->account_receivable_name;
                        $model0->account_name_ar =  ChartOfAccount::where('id','=',$debit0->account_receivable_id)->value('name_ar');
                        $model0->description = $debit0->description;
                        $model0->debit = $debit0->debit_usd;
                        $model0->save();
                }
                foreach($rv_items as $debit0){
                        $credit_chart = ChartOfAccount::findorfail($chart_credit_id);

                        $model0 = new JournalVoucherItem;
                        $model0->journal_voucher_id = $voucher->id;
                        $model0->account_id = $credit_chart->id;
                        $model0->account_code = $credit_chart->code;
                        $model0->account_name_en = $credit_chart->name_en;
                        $model0->account_name_ar =  ChartOfAccount::where('id','=',$credit_chart->id)->value('name_ar');
                        $model0->description = $debit0->description;
                        $model0->credit = $debit0->debit_usd;
                        $model0->save();
                }
                foreach($rv_items_vat as $debitvat){
                        $model1 = new JournalVoucherItem;
                        $model1->journal_voucher_id = $voucher->id;
                        $model1->account_id = $debitvat->account_receivable_debit_vat_id;
                        $model1->account_code = $debitvat->account_receivable_debit_vat_code;
                        $model1->account_name_en = $debitvat->account_receivable_debit_vat_name;
                        $model1->account_name_ar = ChartOfAccount::where('id','=',$debitvat->account_receivable_debit_vat_id)->value('name_ar');
                        $model1->description = $debitvat->description;
                        $model1->debit = $debitvat->debit_vat;
                        $model1->save();
                }
                foreach($rv_items_vat as $debitvat){
                        $credit_chart = ChartOfAccount::findorfail($chart_credit_id);
                        $model0 = new JournalVoucherItem;
                        $model0->journal_voucher_id = $voucher->id;
                        $model0->account_id = $credit_chart->id;
                        $model0->account_code = $credit_chart->code;
                        $model0->account_name_en = $credit_chart->name_en;
                        $model0->account_name_ar =  ChartOfAccount::where('id','=',$credit_chart->id)->value('name_ar');
                        $model0->description = $debitvat->description;
                        $model0->credit = $debitvat->debit_vat;
                        $model0->save();
                }
            }else if($type === 'payment_voucher'){
                 // Override to use calculated total and amounts for expenses
                $vendor_id = \App\PaymentVoucher\PaymentVoucher::where('id','=',$documentData['id'])->value('vendor_id');
                $vendor_account_id = \App\Vendor::where('id','=',$vendor_id)->value('account_code');
                $chart_debit_id = ChartOfAccount::where('code','=',$vendor_account_id)->value('id');

                \App\PaymentVoucher\PaymentVoucher::where('id','=',$documentData['id'])->update(['journal_id' => $voucher->id]);
                $rv_items = \App\PaymentVoucher\Item::where('payment_voucher_id','=',$payment_voucher_id)->where('debit','>',0)->get();
                $rv_items_vat = \App\PaymentVoucher\Item::where('payment_voucher_id','=',$payment_voucher_id)->where('debit_vat','>',0)->get();
                foreach($rv_items as $debit0){
                        $model0 = new JournalVoucherItem;
                        $model0->journal_voucher_id = $voucher->id;
                        $model0->account_id = $debit0->account_receivable_id;
                        $model0->account_code = $debit0->account_receivable_number;
                        $model0->account_name_en = $debit0->account_receivable_name;
                        $model0->account_name_ar =  ChartOfAccount::where('id','=',$debit0->account_receivable_id)->value('name_ar');
                        $model0->description = $debit0->description;
                        $model0->credit = $debit0->debit_usd;
                        $model0->save();
                }
                foreach($rv_items as $debit0){
                        $credit_chart = ChartOfAccount::findorfail($chart_debit_id);

                        $model0 = new JournalVoucherItem;
                        $model0->journal_voucher_id = $voucher->id;
                        $model0->account_id = $credit_chart->id;
                        $model0->account_code = $credit_chart->code;
                        $model0->account_name_en = $credit_chart->name_en;
                        $model0->account_name_ar =  ChartOfAccount::where('id','=',$credit_chart->id)->value('name_ar');
                        $model0->description = $debit0->description;
                        $model0->debit = $debit0->debit_usd;
                        $model0->save();
                }
                foreach($rv_items_vat as $debitvat){
                        $model1 = new JournalVoucherItem;
                        $model1->journal_voucher_id = $voucher->id;
                        $model1->account_id = $debitvat->account_receivable_debit_vat_id;
                        $model1->account_code = $debitvat->account_receivable_debit_vat_code;
                        $model1->account_name_en = $debitvat->account_receivable_debit_vat_name;
                        $model1->account_name_ar = ChartOfAccount::where('id','=',$debitvat->account_receivable_debit_vat_id)->value('name_ar');
                        $model1->description = $debitvat->description;
                        $model1->credit = $debitvat->debit_vat;
                        $model1->save();
                }
                foreach($rv_items_vat as $debitvat){
                        $credit_chart = ChartOfAccount::findorfail($chart_debit_id);
                        $model0 = new JournalVoucherItem;
                        $model0->journal_voucher_id = $voucher->id;
                        $model0->account_id = $credit_chart->id;
                        $model0->account_code = $credit_chart->code;
                        $model0->account_name_en = $credit_chart->name_en;
                        $model0->account_name_ar =  ChartOfAccount::where('id','=',$credit_chart->id)->value('name_ar');
                        $model0->description = $debitvat->description;
                        $model0->debit = $debitvat->debit_vat;
                        $model0->save();
                }
            }else if ($type === 'client_payment'){
                 \App\ClientPayment\ClientPayment::where('id','=',$documentData['id'])->update(['journal_id' => $voucher->id]);

                  $fieldValues = [
                    'Total Amount'  => $calculated_total,
                    'Amount Received' => $amount_received_usd,
                    'Amount Received LBP' => $amount_received_lbp,
                    'VAT' =>  $amount_received_lbprate,
                ];
            }else if ($type === 'vendor_payment'){
                \App\VendorPayment\VendorPayment::where('id','=',$documentData['id'])->update(['journal_id' => $voucher->id]);
                         
                $fieldValues = [
                    'Total Amount'  => $calculated_total,
                    'Amount Received' => $amount_paid_usd,
                    'Amount Received LBP' => $amount_paid_lbp,
                    'VAT' =>  $amount_paid_lbprate,
                ];
            }else {
                if ($type === 'invoice'){
                        \App\Invoice\Invoice::where('id','=',$documentData['id'])
                        ->update(['journal_id' => $voucher->id]);
                }
                if ($type === 'sales_order'){
                        \App\SalesOrder\SalesOrder::where('id','=',$documentData['id'])
                        ->update(['journal_id' => $voucher->id]);
                }
                if ($type === 'purchase_order'){
                        \App\PurchaseOrder\PurchaseOrder::where('id','=',$documentData['id'])
                        ->update(['journal_id' => $voucher->id]);
                }
                if ($type === 'bill'){
                        \App\Bill\Bill::where('id','=',$documentData['id'])
                        ->update(['journal_id' => $voucher->id]);
                }

                
                $fieldValues = [
                    'Sub Total'     => floatval($documentData['sub_total'] ?? 0),
                    'Total Amount'  => floatval($documentData['total'] ?? 0),
                    'Discount'      => floatval($documentData['discount'] ?? 0),
                    'Charges'       => floatval($documentData['shipping'] ?? 0),
                ];

                // VAT = Total - SubTotal - Charges + Discount
                $fieldValues['VAT'] = round(
                    $fieldValues['Total Amount'] - $fieldValues['Sub Total'] - $fieldValues['Charges'] + $fieldValues['Discount'],
                    2
                );
            }

            $items = [];
            $totalDebit = 0;
            $totalCredit = 0;

            foreach ($flow as $row) {
                $amount = $fieldValues[$row['field']] ?? 0;

                if (abs($amount) < 0.0001) {
                    continue;
                }

                $account = ChartOfAccount::find($row['account_id']);
                if (!$account) {
                    throw new Exception("Account not found for ID: {$row['account_id']}");
                }

                $debit = $row['type'] === 'debit' ? $amount : 0;
                $credit = $row['type'] === 'credit' ? $amount : 0;

                $item = [
                    'journal_voucher_id' => $voucher->id,
                    'account_id'         => $account->id,
                    'account_code'       => $account->code,
                    'account_name_en'    => $account->name_en,
                    'account_name_ar'    => $account->name_ar,
                    'description'        => "{$row['field']} for Doc #" . ($documentData['number'] ?? ''),
                    'debit'              => $debit,
                    'credit'             => $credit,
                ];

                JournalVoucherItem::create($item);
                $items[] = $item;

                $totalDebit += $debit;
                $totalCredit += $credit;
            }

            $voucher->update([
                'total_debit'  => $totalDebit,
                'total_credit' => $totalCredit,
            ]);

            $documentNames = [
                1 => 'Sales Invoice',
                2 => 'Purchase Invoice (Vendor Bill)',
                3 => 'Purchase Order',
                4 => 'Sales Order',
                5 => 'Manual Journal Entry (No linked doc)',
                6 => 'Credit Note',
                7 => 'Debit Note',
                8 => 'Advance Payment',
                9 => 'Vendor Expenses',
                10 => 'Client Payment',
                11 => 'Vendor Payment',
            ];

            // Create Journal Movement (inside the function)
            JournalMovement::create([
                'journal_voucher_id'   => $voucher->id,
                'number'               => $voucher->number,
                'currency_id'          => $voucher->currency_id,
                'currency_name'        => $voucher->currency_name,
                'document_type'        => $voucher->document_type,
                'document_id'          => $voucher->document_id,
                'document_number'      => $voucher->document_number,
                'document_date'        => $voucher->date,
                'document_total'       => $voucher->document_total,
                'document_currency_id' => $voucher->document_currency_id,
                'date'                 => $voucher->date,
                'total_debit'          => $totalDebit,
                'total_credit'         => $totalCredit,
                'exchange_rate'        => $voucher->exchange_rate,
                'vat_rate'             => $voucher->vat_rate,
                'reference'            => $voucher->reference,
                'user_id'              => $voucher->user_id,
                'created_by'           => Auth::user()->name,
                'year_date'            => $voucher->year_date,
                'status_id'            => $voucher->status_id ?? null,
                'terms'                => $voucher->terms ?? null,
                'document_name'        => $documentNames[$voucher->document_type] ?? null,
                'type'                 => 'Automatically by ' . $documentNames[$voucher->document_type] . '#' . $voucher->number,
                'movement_date'        => now(),
                'items'                => json_encode($items),
            ]);

            DB::commit();
            return $voucher;

        } catch (Exception $e) {
            DB::rollBack();
            throw new \Exception("Journal creation failed: " . $e->getMessage(), 0, $e);
        }
    }

    // Optional helper method for journal movement creation, if needed elsewhere
    public function create_journal_movement(JournalVoucher $voucher, array $data, array $flow): void
    {
        $fieldValues = [
            'Sub Total'     => floatval($data['sub_total'] ?? 0),
            'Total Amount'  => floatval($data['total'] ?? 0),
            'Discount'      => floatval($data['discount'] ?? 0),
            'Charges'       => floatval($data['shipping'] ?? 0),
        ];

        $fieldValues['VAT'] = round(
            ($fieldValues['Total Amount'] - $fieldValues['Sub Total'] - $fieldValues['Charges'] + $fieldValues['Discount']),
            2
        );

        foreach ($flow as $row) {
            $amount = $fieldValues[$row['field']] ?? 0;

            if (abs($amount) < 0.0001) continue;

            $account = ChartOfAccount::find($row['account_id']);

            JournalMovement::create([
                'journal_voucher_id' => $voucher->id,
                'account_id'         => $row['account_id'],
                'account_code'       => $account->code ?? null,
                'account_name_en'    => $account->name_en ?? null,
                'account_name_ar'    => $account->name_ar ?? null,
                'description'        => "{$row['field']} for Doc #" . ($data['number'] ?? ''),
                'debit'              => $row['type'] === 'debit'  ? $amount : 0,
                'credit'             => $row['type'] === 'credit' ? $amount : 0,
            ]);
        }
    }
}
