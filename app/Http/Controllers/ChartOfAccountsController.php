<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ChartOfAccount;
use DB;
use Auth;
use App\ClassesCode;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;
use App\JournalVoucher\TrialBalanceExport;

class ChartOfAccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    { 
        $data['classes'] = ClassesCode::with('accounts')->get();
         return view('chart_of_accounts', $data);
    }

    public function index()
    { 
        $user = auth()->user();
        if ($user->is_accounts_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => ChartOfAccount::with(['currency','classes','vat_account'])->search()
            ]);
        }
    }
    
public function exportTrialBalance(Request $request)
{
    return Excel::download(
        new TrialBalanceExport($request->from_date, $request->to_date, $request->account_id),
        'trial_balance_' . now()->format('Ymd_His') . '.xlsx'
    );
}


    public function trial_balance_report(Request $request)
{
    $query = DB::table('journal_voucher_items as jvi')
        ->join('journal_vouchers as jv', 'jvi.journal_voucher_id', '=', 'jv.id')
        ->join('chart_accounts as ca', 'jvi.account_id', '=', 'ca.id')
        ->join('chart_classes as cc', 'ca.class_code', '=', 'cc.code')
        ->select(
            'jvi.account_id as account_id',
            'ca.code as account_code',
            'ca.name_en as account_name_en',
            'ca.name_ar as account_name_ar',
            'cc.name_en as class_name_en',
            'cc.name_ar as class_name_ar',
            DB::raw('SUM(jvi.debit) as total_debit'),
            DB::raw('SUM(jvi.credit) as total_credit'),
            DB::raw('SUM(jvi.debit - jvi.credit) as balance')
        )
        ->where('jv.status_id','=',2)
        ->groupBy('jvi.account_id', 'ca.code', 'ca.name_en', 'ca.name_ar', 'cc.name_en', 'cc.name_ar')
        ->orderBy('cc.code')
        ->orderBy('ca.code');

    // Optional filters
    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('jv.date', [$request->from_date, $request->to_date]);
    }

    if ($request->filled('account_id')) {
        $query->where('jvi.account_id', $request->account_id);
    }

    $trialBalances = $query->get();

    return view('reports.trial_balance', compact('trialBalances'));
}

     
    public function chart_classes()
    {
        $results = ClassesCode::orderBy('code')
            ->when(request('q'), function($query) {
                $query->where('name_en', 'like', '%'.request('q').'%');
            })
            ->limit(12)
            ->get();

        return api([
            'results' => $results
        ]);
    }

    public function search()
    {
        $results = ChartOfAccount::with('currency','vat_account')
            ->orderBy('name_en')
            ->when(request('q'), function($query) {
                $query->where('code', 'like', '%'.request('q').'%')
                    ->orWhere('name_en', 'like', '%'.request('q').'%')
                    ->orWhere('name_ar', 'like', '%'.request('q').'%');
            })
            ->limit(12)
            ->get();

        return api([
            'results' => $results
        ]);
    }

    public function ledger_account_payables()
    {
        $results = ChartOfAccount::with('currency','vat_account')
        ->whereRaw("LEFT(code, 1) BETWEEN '4' AND '4'")  // Add this line to filter by first char of code
            ->orderBy('name_en')
            ->when(request('q'), function($query) {
                $query->where('code', 'like', '%'.request('q').'%')
                    ->orWhere('name_en', 'like', '%'.request('q').'%')
                    ->orWhere('name_ar', 'like', '%'.request('q').'%');
            })
            ->limit(12)
            ->get();

        return api([
            'results' => $results
        ]);
    }


public function search_601()
{
    $results = ChartOfAccount::with('currency','vat_account')
        ->whereRaw("LEFT(code, 1) BETWEEN '6' AND '6'")  // Add this line to filter by first char of code
        ->orderBy('name_en')
        ->when(request('q'), function($query) {
            $query->where('code', 'like', '%'.request('q').'%')
                ->orWhere('name_en', 'like', '%'.request('q').'%')
                ->orWhere('name_ar', 'like', '%'.request('q').'%');
        })
        ->limit(12)
        ->get();

    return api([
        'results' => $results
    ]);
}

        public function search_461()
    {
    $results = ChartOfAccount::with('currency','vat_account')
        ->whereRaw("LEFT(code, 1) BETWEEN '4' AND '4'")  // Add this line to filter by first char of code
        ->orderBy('name_en')
        ->when(request('q'), function($query) {
            $query->where('code', 'like', '%'.request('q').'%')
                ->orWhere('name_en', 'like', '%'.request('q').'%')
                ->orWhere('name_ar', 'like', '%'.request('q').'%');
        })
        ->limit(12)
        ->get();

    return api([
        'results' => $results
    ]);
}
    
        public function ledger_vat_accounts()
    {
    $results = ChartOfAccount::with('currency','vat_account')
        ->whereRaw("LEFT(code, 3) BETWEEN '114' AND '114'")  // Add this line to filter by first char of code
        ->orderBy('name_en')
        ->when(request('q'), function($query) {
            $query->where('code', 'like', '%'.request('q').'%')
                ->orWhere('name_en', 'like', '%'.request('q').'%')
                ->orWhere('name_ar', 'like', '%'.request('q').'%');
        })
        ->limit(12)
        ->get();

    return api([
        'results' => $results
    ]);
}

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            $form = array_merge([
            'name' => '',
            'company' => '',
            'email' => '',
            'work_phone' => '',
            'phone' => '',
            'vat_status' => '',
            'billing_address' => '',
            'shipping_address' => ''
        ],
            currency()->defaultToArray()
        );

        return api([
            'form' => $form
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->is_accounts_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $request->validate([
                'currency_id' => 'required',
                'name' => 'nullable',
                'balance' => 'nullable',
            ]);

            $model = new ChartOfAccount();
            $model->fill($request->all());
            $model->save();
            return api([
                'saved' => true,
                'id' => $model->id
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
        if ($user->is_accounts_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $account = ChartOfAccount::with('currency','classes','vat_account')->findOrFail($id);
            return api([
                'data' => $account,
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        if ($user->is_accounts_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'form' => ChartOfAccount::with('currency','classes','vat_account')->findOrFail($id)
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->is_accounts_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $model = ChartOfAccount::findOrFail($id);

            $request->validate([
                'currency_id' => 'required',
                'name' => 'nullable',
                'balance' => 'nullable',
            ]);

            $model->fill($request->all());
            $model->save();

            return api([
                'saved' => true,
                'id' => $model->id
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
