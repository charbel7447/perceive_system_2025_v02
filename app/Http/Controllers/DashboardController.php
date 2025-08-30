<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invoice\Invoice;
use App\Invoice\Item as InvoiceItem;
use App\PurchaseOrder\PurchaseOrder;
use App\SalesOrder\SalesOrder;
use App\Quotation\Quotation;
use App\Warehouse;
use App\Category;
use App\Bill\Bill;
use App\Vendor;
use App\Client;
use DB;
use Carbon\Carbon;
use App\ClientPayment\ClientPayment;
use App\VendorPayment\VendorPayment;
use App\Product\Product;
use App\UserDashboard;
use Auth;
use App\ReportWidget;
use App\Services\DashboardService;
use PDF;

class DashboardController extends Controller
{

    public function storeCustom(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        
        $model = new UserDashboard();
        $model->user_id =  auth()->id();
        $model->name = $request->name;
        $model->save();


        return response()->json([
            'message' => 'Dashboard created',
            'dashboard' => $model->name
        ]);
    }

// Render the custom dashboard blade
    public function customLayout($id)
    {
        $dashboard = UserDashboard::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('custom_layout', ['dashboard_id' => $dashboard->id]);
    }

    // Return saved layout JSON
    public function getCustomLayout($id)
    {
        $dashboard = UserDashboard::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $layout = $dashboard->widgets ?? [];

        if (is_array($layout)) {
            $ids = collect($layout)->pluck('lib_id')->filter()->unique()->values();
            $libs = ReportWidget::whereIn('id', $ids)->get()->keyBy('id');

            $layout = collect($layout)->map(function ($row) use ($libs) {
                if (!empty($row['lib_id']) && $libs->has($row['lib_id'])) {
                    $w = $libs->get($row['lib_id']);
                    $row['lib'] = ['name' => $w->name, 'description' => $w->description];
                } else {
                    $row['lib'] = ['name'=>'Widget', 'description'=>''];
                }
                return $row;
            })->values();
        }

        return response()->json(['layout' => $layout]);
    }

    // Save layout positions + sizes
    public function saveCustomLayout(Request $request)
    {
        $validated = $request->validate([
            'dashboard_id' => 'required|integer',
            'layout' => 'required|array',
            'layout.*.x' => 'required|integer|min:0',
            'layout.*.y' => 'required|integer|min:0',
            'layout.*.w' => 'required|integer|min:1|max:12',
            'layout.*.h' => 'required|integer|min:1',
            'layout.*.lib_id' => 'nullable|integer',
        ]);

        $dashboard = UserDashboard::where('id', $validated['dashboard_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $dashboard->widgets = $validated['layout'];
        $dashboard->save();

        return response()->json(['status' => 'ok']);
    }

    // Widgets library (for drawer)
    public function getWidgetsLibrary()
    {
        $widgets = ReportWidget::orderBy('name')->get(['id', 'name', 'description']);

        if ($widgets->isEmpty()) {
            $widgets = collect([
                ['id'=>1,'name'=>'Total Sales','description'=>'Sales this month'],
                ['id'=>2,'name'=>'Total Purchases','description'=>'Purchases this month'],
                ['id'=>3,'name'=>'Accounts Receivable','description'=>'Clients Due'],
                ['id'=>4,'name'=>'Accounts Payable','description'=>'Supplier Payable'],
                ['id'=>5,'name'=>'Cash in Hand','description'=>'Available cash'],
                ['id'=>6,'name'=>'Bank Balance','description'=>'Current bank balance'],
            ]);
        }

        return response()->json(['widgets'=>$widgets]);
    }

    // Fetch widget data for a specific widget
        // Return widget data for Vue/GridStack
 public function data($widgetId)
{
    $widget = ReportWidget::findOrFail($widgetId);

    $data = DashboardService::getWidgetData($widgetId);

    return response()->json([
        'type' => $data['type'] ?? 'html',
        'rows' => $data['rows'] ?? [],
        'columns' => $data['columns'] ?? [],
        'chart' => $data['chart'] ?? null,
        'html' => $data['html'] ?? $widget->description,
    ]);
}

 public function exportPdf($dashboardId)
    {
        $dashboard = UserDashboard::findOrFail($dashboardId);

        // Decode widgets JSON
        // $widgetsJson = json_decode($dashboard->widgets, true) ?: [];

        $widgetsJson = $dashboard->widgets; // already an array

        // Load widget data from service
        $widgetsData = collect($widgetsJson)->map(function ($w) {
            $data = DashboardService::getWidgetData($w['lib_id']);

            // If chart, convert to Base64 image via QuickChart.io
            if ($data['type'] === 'chart') {
                $chartConfig = [
                    'type' => $data['chart']['type'],
                    'data' => $data['chart']['data'],
                    'options' => $data['chart']['options'] ?? [],
                ];
                $chartUrl = "https://quickchart.io/chart?c=" . urlencode(json_encode($chartConfig));
                $data['chart_base64'] = 'data:image/png;base64,' . base64_encode(file_get_contents($chartUrl));
            }

            return array_merge($w, ['data' => $data]);
        });

        $pdf = PDF::loadView('dashboards.pdf', [
            'dashboard' => $dashboard,
            'widgets'   => $widgetsData,
        ])->setPaper('a4', 'landscape');

        return $pdf->download("dashboard-{$dashboard->id}.pdf");
    }

     public function customLayoutDelete($dashboardId)
    {
        $dashboard = UserDashboard::findOrFail($dashboardId)->delete();
        return back();
    }
     

    public function sales_dashboard()
    {
        $model = [
            'display_sales' => 1,
            'currency' => 'USD',
            'top_clients'=> Client::with(['currency'])->orderBy('created_at', 'desc')->limit(10)->get(),
            'top_saled_products'=> SalesOrder::with(['items','items.uomd','items.productd','clientd'])->orderBy('date', 'desc')->limit(5)->where('status_id','>','2')->get(),
            'top_quotations'=> Quotation::with(['currency','items','items.uomd','items.productd','clientd'])->orderBy('date', 'desc')->limit(5)->get(),
            'top_sales_order'=> SalesOrder::with(['currency','items','items.uomd','items.productd','clientd'])->orderBy('date', 'desc')->limit(5)->get(),
           
        ];

        return view('dashboards.sales', compact('model'));
    }

    public function accounting_dashboard()
    {
        $topInvoices = Invoice::with(['currency','client','items','items.uomd','items.productd'])->whereIn('status_id', [2, 3,4,6])->orderby('date','desc')->limit(5)->get();

        $topBills = Bill::with(['vendor','currency','items','items.uomd','items.productd'])->orderBy('date', 'desc')->limit(10)->get();


    return view('dashboards.accounting', compact('topInvoices', 'topBills'));
    }

    public function procurment_dashboard()
    {
        $top_vendors =Vendor::with(['currency'])->orderBy('created_at', 'desc')->limit(10)->get();
        $all_warehouses =Warehouse::with(['productd'])->orderBy('id', 'desc')->get();
        $wh_product =Product::with(['items'])->where('current_stock','>',0)->limit(10)->get();
        $all_products =Product::with(['items','uom','warehoused','categoryd','sub_categoryd'])->where('current_stock','>',0)->orderBy('id', 'desc')->limit(10)->get();
        $top_purchase_order =PurchaseOrder::with(['currency','items','items.uom','items.productd','vendord'])->orderBy('date', 'desc')->limit(5)->get();
        $vendorExpenseData = $top_vendors->map(fn($v) => ['label' => $v->company, 'value' => $v->total_expense])->toArray();
        $stockByWarehouse = $wh_product->groupBy('warehouse_id')->map(function ($items, $key) {
            return [
                'label' => 'Warehouse ' . $key,
                'value' => $items->sum('current_stock')
            ];
        })->values()->toArray();

        return view('dashboards.procurement', compact(
        'top_vendors',
        'all_warehouses',
        'wh_product',
        'top_purchase_order',
        'all_products',
        'vendorExpenseData',
        'stockByWarehouse'
        ));
    }

    public function index()
    {
        $now = Carbon::today();
        $user = auth()->user();
        // if ($user->is_admin == 0){
        //     return response()->json(['error' => 'Forbidden.'], 403);
        // //    return view('docs/error');
        //     }else{
        $data = [
            'accounts_receivable' => Invoice::whereIn('status_id', [2, 3])->sum(DB::raw('total - amount_paid ')),
            'total_revenue' => Client::sum('total_revenue'),
            'total_debit' => Client::sum('unused_credit'),
            'total_invoice' => Invoice::whereIn('status_id', [2, 3,4])->sum('total'),
            'open_sales_orders' => SalesOrder::whereIn('status_id', [3])->count(),
            'unpaid_invoices' => Invoice::whereIn('status_id', [2, 3])->count(),
            'accounts_payable' => Bill::whereIn('status_id', [1, 2])->sum(DB::raw('total - amount_paid')),
            'total_expense' => Vendor::sum('total_expense'),
            'open_purchase_orders' => PurchaseOrder::whereIn('status_id', [3])->count(),
            'unpaid_bills' => Bill::whereIn('status_id', [1, 2])->count(),
            'top_unpaid_invoices' => Invoice::with(['currency'])->whereIn('status_id', [2, 3])->limit(5)->orderBy('due_date')->get(['id', 'number','total', 'due_date','date', 'status_id', DB::raw('(total - amount_paid) as due_amount'), 'currency_id']),
          
         
            'total_saled_item'=> DB::table('invoices')->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')->where('invoices.status_id','>',1)->select('product_id')->distinct('product_id')->count('product_id'),
            'total_saled_item_qty'=> DB::table('invoices')->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')->where('invoices.status_id','>',1)->sum('qty'),
            
        
            'top_clients_payments'=> ClientPayment::with(['currency'])->orderBy('created_at', 'desc')->limit(10)->get(),
            'top_vendors_payments'=> VendorPayment::with(['currency'])->orderBy('created_at', 'desc')->limit(10)->get(),

           
            
            'balanceUSD' => DB::table('accounts')->where('currency_id','=','1')->value('balance'),
            'balanceLBP' => DB::table('accounts')->where('currency_id','=','2')->value('balance'),
            'balanceEURO' => DB::table('accounts')->where('currency_id','=','3')->value('balance'),

            'balanceUSDName' => DB::table('accounts')->where('currency_id','=','1')->value('name'),
            'balanceLBPName' => DB::table('accounts')->where('currency_id','=','2')->value('name'),
            'balanceEUROName' => DB::table('accounts')->where('currency_id','=','3')->value('name'),

            'balanceCurrency1' => DB::table('currencies')->where('id','=','1')->value('code'),
            'balanceCurrency2' => DB::table('currencies')->where('id','=','2')->value('code'),
            'balanceCurrency3' => DB::table('currencies')->where('id','=','3')->value('code'),
            'today_year' => $now->year,
            'display_overview' => 3,
            'display_sales' => 0,
            'display_accounting' => 0,
            'display_stock' => 0, 
            'display_production' => 0, 
            'selected_year' => $now->year,

            'balanceUSD_now' => DB::table('account_items')->where('account_id','=','1')->where('date',$now->year)->sum('amount'),
            'balanceLBP_now' => DB::table('account_items')->where('account_id','=','2')->where('date',$now->year)->sum('amount'),
            'balanceEURO_now' => DB::table('account_items')->where('account_id','=','3')->where('date',$now->year)->sum('amount'),

            'balanceUSD_now_1' => DB::table('account_items')->where('account_id','=','1')->where('date',$now->year-1)->sum('amount'),
            'balanceLBP_now_1' => DB::table('account_items')->where('account_id','=','2')->where('date',$now->year-1)->sum('amount'),
            'balanceEURO_now_1' => DB::table('account_items')->where('account_id','=','3')->where('date',$now->year-1)->sum('amount'),

            'balanceUSD_now_2' => DB::table('account_items')->where('account_id','=','1')->where('date',$now->year-2)->sum('amount'),
            'balanceLBP_now_2' => DB::table('account_items')->where('account_id','=','2')->where('date',$now->year-2)->sum('amount'),
            'balanceEURO_now_2' => DB::table('account_items')->where('account_id','=','3')->where('date',$now->year-2)->sum('amount'),

            'accounts_receivable_now' => Invoice::whereIn('status_id', [2, 3])->where('year_date','=',$now->year)->sum(DB::raw('total - amount_paid ')),
            'accounts_receivable_now_1' => Invoice::whereIn('status_id', [2, 3])->where('year_date','=',$now->year-1)->sum(DB::raw('total - amount_paid ')),
            'accounts_receivable_now_2' => Invoice::whereIn('status_id', [2, 3])->where('year_date','=',$now->year-2)->sum(DB::raw('total - amount_paid ')),
            

            'client_payment' => ClientPayment::sum('amount_received'),
            'client_payment_now' => ClientPayment::where('year_date','=',$now->year)->sum('amount_received'),
            'client_payment_now_1' => ClientPayment::where('year_date','=',$now->year-1)->sum('amount_received'),
            'client_payment_now_2' => ClientPayment::where('year_date','=',$now->year-2)->sum('amount_received'),

            'total_saled_item_now'=> DB::table('invoices')->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')->where('invoices.status_id','>',1)->where('year_date','=',$now->year)->select('product_id')->distinct('product_id')->count('product_id'),
            'total_saled_item_qty_now'=> DB::table('invoices')->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')->where('invoices.status_id','>',1)->where('year_date','=',$now->year)->sum('qty'),

            'total_saled_item_now_1'=> DB::table('invoices')->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')->where('invoices.status_id','>',1)->where('year_date','=',$now->year-1)->select('product_id')->distinct('product_id')->count('product_id'),
            'total_saled_item_qty_now_1'=> DB::table('invoices')->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')->where('invoices.status_id','>',1)->where('year_date','=',$now->year-1)->sum('qty'),

            'total_saled_item_now_2'=> DB::table('invoices')->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')->where('invoices.status_id','>',1)->where('year_date','=',$now->year-2)->select('product_id')->distinct('product_id')->count('product_id'),
            'total_saled_item_qty_now_2'=> DB::table('invoices')->join('invoice_items', 'invoices.id', '=', 'invoice_items.invoice_id')->where('invoices.status_id','>',1)->where('year_date','=',$now->year-2)->sum('qty'),

            'open_sales_orders_now' => SalesOrder::whereIn('status_id', [3])->where('year_date','=',$now->year)->count(),
            'open_sales_orders_now_1' => SalesOrder::whereIn('status_id', [3])->where('year_date','=',$now->year-1)->count(),
            'open_sales_orders_now_2' => SalesOrder::whereIn('status_id', [3])->where('year_date','=',$now->year-2)->count(),

            'unpaid_invoices_now' => Invoice::whereIn('status_id', [2, 3])->where('year_date','=',$now->year)->count(),
            'unpaid_invoices_now_1' => Invoice::whereIn('status_id', [2, 3])->where('year_date','=',$now->year-1)->count(),
            'unpaid_invoices_now_2' => Invoice::whereIn('status_id', [2, 3])->where('year_date','=',$now->year-2)->count(),


            'total_invoice_now' => Invoice::whereIn('status_id', [2, 3,4])->where('year_date','=',$now->year)->sum('total'),
            'total_invoice_now_1' => Invoice::whereIn('status_id', [2, 3,4])->where('year_date','=',$now->year-1)->sum('total'),
            'total_invoice_now_2' => Invoice::whereIn('status_id', [2, 3,4])->where('year_date','=',$now->year-2)->sum('total'),

            'accounts_payable_now' => Bill::whereIn('status_id', [1, 2])->where('year_date','=',$now->year)->sum(DB::raw('total - amount_paid')),
            'accounts_payable_now_1' => Bill::whereIn('status_id', [1, 2])->where('year_date','=',$now->year-1)->sum(DB::raw('total - amount_paid')),
            'accounts_payable_now_2' => Bill::whereIn('status_id', [1, 2])->where('year_date','=',$now->year-2)->sum(DB::raw('total - amount_paid')),


            'box_1' => DB::table('settings')->where('key','=','box_1')->value('value'),
            'box_2' => DB::table('settings')->where('key','=','box_3')->value('value'),
            'box_3' => DB::table('settings')->where('key','=','box_3')->value('value'),
            'box_4' => DB::table('settings')->where('key','=','box_4')->value('value'),
            'box_5' => DB::table('settings')->where('key','=','box_5')->value('value'),
            'box_6' => DB::table('settings')->where('key','=','box_6')->value('value'),
            'box_7' => DB::table('settings')->where('key','=','box_7')->value('value'),
            'box_8' => DB::table('settings')->where('key','=','box_8')->value('value'),
            'box_9' => DB::table('settings')->where('key','=','box_9')->value('value'),
            'box_10' => DB::table('settings')->where('key','=','box_10')->value('value'),
            'box_11' => DB::table('settings')->where('key','=','box_11')->value('value'),
            'box_12' => DB::table('settings')->where('key','=','box_12')->value('value'),
            'box_13' => DB::table('settings')->where('key','=','box_13')->value('value'),
            'box_14'=> DB::table('settings')->where('key','=','box_14')->value('value'),
            'box_15'=> DB::table('settings')->where('key','=','box_15')->value('value'),
            'chart_1' => DB::table('settings')->where('key','=','chart_1')->value('value'),
            'chart_2' => DB::table('settings')->where('key','=','chart_2')->value('value'),
 
        ];

        $data =  array_merge($data, currency()->defaultToArray());
        $dashboards = UserDashboard::where('user_id', auth()->id())->get();
        return api([
            'data' => $data,
            'user_dashboards' => $dashboards,
            'income_expense_chart' => $this->getIncomeExpenseData(),
            'accounts_chart' => $this->getAccountsChart()
        ]);
    // }
    }

    protected function getAccountsChart()
    {
       

        $balanceUSDName = DB::table('accounts')->where('currency_id','=','1')->value('name');
        $balanceLBPName = DB::table('accounts')->where('currency_id','=','2')->value('name');
        $balanceEUROName = DB::table('accounts')->where('currency_id','=','3')->value('name');

        $start =  Carbon::now();
        $end = $start->copy()->subDays(28);
        $labels = $this->getDateRange($start, $end);
        return [
            'labels_account' => [
                $balanceUSDName,
                $balanceLBPName,
            ],
            'datasets_account' => [
                [
                    'labels_account' => $balanceUSDName,
                    'data' => $this->getUSDData(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1
                ],
                [
                    'labels_account' => $balanceLBPName,
                    'data' => $this->getLBPData(),
                    'backgroundColor' => 'red',
                    'borderColor' => 'rgb(153, 102, 255)',
                    'borderWidth' => 2
                ]
            ]
        ];
    }

    protected function getUSDData()
    {
        $balanceUSD = DB::table('accounts')->where('currency_id','=','1')->value('balance');
       
            return array_values([$balanceUSD]);
    }

    protected function getLBPData()
    {
        $balanceLBP = DB::table('accounts')->where('currency_id','=','2')->value('balance');
            return array_values([$balanceLBP]);
    }

    protected function getIncomeExpenseData()
    {
        // 28 days
        $start =  Carbon::now();
        $end = $start->copy()->subDays(28);
        $labels = $this->getDateRange($start, $end);
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'income',
                    'data' => $this->getIncomeData($start, $end, $labels),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1
                ],
                [
                    'label' => 'Expense',
                    'data' => $this->getExpenseData($start, $end, $labels),
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'borderColor' => 'rgb(153, 102, 255)',
                    'borderWidth' => 1
                ]
            ]
        ];
    }

    protected function getDateRange($startDate, $endDate)
    {
        $dates = [];
        $start = $startDate->copy();
        $end = $endDate->copy();
        while ($end->lte($start)) {
            $dates[] = $end->copy()->format('M d');
            $end->addDay(3);
        }

        return $dates;
    }

    protected function getIncomeData($start, $end, $label)
    {
        $default = collect($label)->mapWithKeys(function($item) {
            return [$item => 0];
        });

        $cp = DB::table('client_payments')
            ->whereBetween('payment_date', [$end, $start])
            ->select('amount_received', 'payment_date')
            ->get();

        $ap = DB::table('advance_payments')
            ->whereBetween('payment_date', [$end, $start])
            ->select('amount_received', 'payment_date')
            ->get();

        $data = $ap->merge($cp)->groupBy('payment_date');

        $keyed = $default->mapWithKeys(function($key, $item) use ($data) {
            // get
            $date = Carbon::parse($item);

            $dates = [
                $date->copy()->format('Y-m-d'),
                $date->copy()->addDay(1)->format('Y-m-d'),
                $date->copy()->addDay(2)->format('Y-m-d')
            ];

            $sum = 0;
            foreach($dates as $key) {
                $items = $data->get($key);
                if(!is_null($items)) {
                    foreach($items as $item) {
                        $sum += $item->amount_received;
                    }
                }
            }

            return [$date->copy()->format('Y-m-d') => $sum];
        });

        return array_values($keyed->all());
    }

    protected function getExpenseData($start, $end, $label)
    {
        $default = collect($label)->mapWithKeys(function($item) {
            return [$item => 0];
        });

        $cp = DB::table('vendor_payments')
            ->whereBetween('payment_date', [$end, $start])
            ->select('amount_paid', 'payment_date')
            ->get();

        $ap = DB::table('expenses')
            ->whereBetween('payment_date', [$end, $start])
            ->select('amount_paid', 'payment_date')
            ->get();

        $data = $ap->merge($cp)->groupBy('payment_date');

        $keyed = $default->mapWithKeys(function($key, $item) use ($data) {
            // get
            $date = Carbon::parse($item);

            $dates = [
                $date->copy()->format('Y-m-d'),
                $date->copy()->addDay(1)->format('Y-m-d'),
                $date->copy()->addDay(2)->format('Y-m-d')
            ];

            $sum = 0;
            foreach($dates as $key) {
                $items = $data->get($key);
                if(!is_null($items)) {
                    foreach($items as $item) {
                        $sum += $item->amount_paid;
                    }
                }
            }

            return [$date->copy()->format('Y-m-d') => $sum];
        });

        return array_values($keyed->all());
    }

}
