<?php
// Auth::loginUsingId(1);
// var_dump(request()->path());

use App\Support\Settings;
use App\Http\Controllers\CustomQueryController;
use App\Http\Controllers\ChartOfAccountsController;
use App\Http\Controllers\JournalVoucherController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\StockCountController;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\GeneralLedgerController;
use App\Http\Controllers\ProfitLossController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductLotsController;
use App\Http\Controllers\SettingsController;

Route::get('/api/settings/updateSystemLive', [SettingsController::class, 'updateSystemLive']);

Route::prefix('dashboard')->group(function () {
    // Blade that renders inside your iframe when a custom dashboard is clicked
    Route::get('/custom_layout/{id}', [DashboardController::class, 'customLayout'])
        ->name('dashboard.custom_layout')
        ->middleware('auth');

    // API: load/save layout JSON for this user_dashboard
    Route::get('/custom-layout/{id}', [DashboardController::class, 'getCustomLayout'])
        ->middleware('auth');
    Route::post('/custom-layout/save', [DashboardController::class, 'saveCustomLayout'])
        ->middleware('auth');

    // API: widgets library (searchable drawer list)
    Route::get('/widgets', [DashboardController::class, 'getWidgetsLibrary'])
        ->middleware('auth');

    Route::get('/custom/widget/{id}', [DashboardController::class, 'data']);

    Route::get('/custom-layout/{dashboard}/export-pdf', [DashboardController::class, 'exportPdf'])
    ->name('dashboard.export-pdf');

    Route::get('/custom_layout/{id}/delete', [DashboardController::class, 'customLayoutDelete'])
        ->middleware('auth');
});

Route::prefix('system')->group(function () {
    Route::get('/profit_loss', [ProfitLossController::class, 'index'])->name('profit_loss.index');
    Route::get('/profit_loss/export-excel', [ProfitLossController::class, 'exportExcel'])->name('profit_loss.exportExcel');
    Route::get('/profit_loss/export-pdf', [ProfitLossController::class, 'exportPdf'])->name('profit_loss.exportPdf');
    Route::get('/profit_loss/account_docs/{account}', [ProfitLossController::class, 'accountDocs']);

});

Route::get('/api/scan_barcode/get_info', 'ProductController@get_info');
Route::get('/api/scan_barcode/get_info_purchase', 'ProductController@get_info_purchase');


Route::get('product_lots/lots/{id}/delete', [ProductLotsController::class, 'deleteLot']);

Route::get('/system/chart_of_accounts', [ChartOfAccountsController::class, 'list']);
Route::get('/system/journal_vouchers_movement', [JournalVoucherController::class, 'movement']);
Route::get('/system/journal_vouchers_movement/export', [JournalVoucherController::class, 'export_excel']);
Route::get('/system/journal_vouchers_flow', [JournalVoucherController::class, 'flow']);
Route::post('/system/journal_vouchers_flow', [JournalVoucherController::class, 'storeFlow'])->name('journal-flow.store');

Route::get('/system/stock_movement', [StockMovementController::class, 'list']);
Route::get('/stock_movement/export', [StockMovementController::class, 'export'])->name('stock_movement.export');

Route::get('/system/stock_count', [StockCountController::class, 'index']);
Route::get('/stock-count', [StockCountController::class, 'index'])->name('stock_count.index');
Route::post('/stock-count', [StockCountController::class, 'store'])->name('stock_count.store');
Route::get('/stock-count/{id}', [StockCountController::class, 'show'])->name('stock_count.show');
Route::post('/stock-count/{id}/scan-ajax', [StockCountController::class, 'scanAjax'])->name('stock_count.scan.ajax');
Route::post('/stock-count/{id}/confirm-ajax/{productId}', [StockCountController::class, 'confirmAjax'])->name('stock_count.confirm.ajax');
Route::post('/stock-count/{stockCount}/submit', [StockCountController::class, 'submit'])->name('stock_count.submit');
 // NEW: variance report + CSV export
Route::get('/stock-count/{id}/variance', [StockCountController::class, 'variance'])->name('stock_count.variance');
Route::get('/stock-count/{id}/variance/export', [StockCountController::class, 'exportVarianceCsv'])->name('stock_count.variance.export');


Route::get('/system/balance_sheet', [BalanceSheetController::class, 'index'])->name('balance.sheet');
Route::get('/system/general_ledger', [GeneralLedgerController::class, 'index'])
    ->name('general_ledger.index');
    Route::get('/system/general_ledger/export/excel', [GeneralLedgerController::class, 'exportExcel'])->name('general_ledger.exportExcel');
Route::get('/system/general_ledger/export/pdf', [GeneralLedgerController::class, 'exportPdf'])->name('general_ledger.exportPdf');
 
Route::get('/journal_vouchers/{id}/details', [JournalVoucherController::class, 'details']);


Route::get('/system/sales_dashboard', [DashboardController::class, 'sales_dashboard']);
Route::get('/system/accounting_dashboard', [DashboardController::class, 'accounting_dashboard']);
Route::get('/system/procurment_dashboard', [DashboardController::class, 'procurment_dashboard']);


Route::get('/system/trial_balance_report', [ChartOfAccountsController::class, 'trial_balance_report'])->name('reports.trial_balance');
Route::get('/reports/trial-balance/export', [ChartOfAccountsController::class, 'exportTrialBalance'])->name('reports.trial_balance.export');


Route::get('/system/custom_query', [CustomQueryController::class, 'showForm'])->name('custom.query.form');
Route::post('/system/custom_query', [CustomQueryController::class, 'runQuery'])->name('custom.query');
Route::post('/system/custom_query_save', [CustomQueryController::class, 'saveQuery'])->name('save.query');

Route::post('/system/custom_query_export', [CustomQueryController::class, 'export'])->name('custom.query.export');
Route::get('/system/custom_query_delete/{id}', [CustomQueryController::class, 'deleteQuery']);

Route::get('category_tree_view', 'CategoryTreeController@manageCategory');
Route::get('settings/{npm}', 'SettingsController@npm');
Route::get('settings/{migrate}', 'SettingsController@migrate');
Route::get('settings/{upgrade}', 'SettingsController@upgrade');

Route::get('/products_history/{client_id}/products_history/{product}', 'ProductHistoryController@products_history');
Route::get('/products_purchase_history/{vendor_id}/products_purchase_history/{product}', 'ProductHistoryController@products_purchase_history');

Route::get('download_client_table', 'ClientController@download_client_table');
Route::get('download_product_table', 'ProductController@download_product_table');


Route::get('api/release_note/{products}/mark/1', 'PageController@read_release');


Route::post('/import_tools/perceive_system/products_import','ImportProductsController@products_import');
Route::post('/import_tools/perceive_system/clients_import','ImportProductsController@clients_import');

Route::post('/import_tools/perceive_system/products_import2','ImportProductsController@products_import2');

Route::post('/import_tools/perceive_system/products_import_images','ImportProductsController@products_import_images');

Route::post('/import_tools/perceive_system/suppliers_import','ImportProductsController@suppliers_import');

Route::post('/import_tools/perceive_system/client_balances_import','ImportProductsController@client_balances_import');

Route::post('/import_tools/perceive_system/suppliers_items','ImportProductsController@suppliers_items');


Route::resource('import_tools', 'ImportProductsController');

Route::get('minimum_stock', 'StockReportsController@minimum_stock');

Route::get('api/v1/categories', 'CategoryController@get_categories');

Route::get('api/products/{products}/mark/1', 'ProductController@markAs1');
Route::get('api/products/{products}/mark/2', 'ProductController@markAs2');

Route::get('/perceive/clear_license', 'SettingsController@clear_license');
Route::get('/perceive/update_license', 'SettingsController@update_license');
Route::get('/perceive/clear_db', 'SettingsController@clear_db');
Route::get('/perceive/update_original_price', 'ProductController@update_original_price');
Route::get('/perceive/update_clients_balance', 'ClientController@update_clients_balance');
Route::get('/perceive/clear_login_history', 'SettingsController@clear_login_history');
Route::get('/perceive/reset_on_hold_qty', 'SettingsController@reset_on_hold_qty');
Route::get('/perceive/reset_last_payment_date', 'SettingsController@reset_last_payment_date');
Route::get('/perceive/clear_log', 'SettingsController@clear_log');

Route::get('/perceive/set_products_default_vendor', 'ProductController@set_products_default_vendor');
Route::get('/perceive/update_current_stock_web', 'ProductController@update_current_stock_web');


Route::get('/cleareverything', function () {
    $clearcache = Artisan::call('cache:clear');
    echo $clearcache."Cache cleared<br>";

    $clearview = Artisan::call('view:clear');
    echo "View cleared<br>";

    $clearconfig = Artisan::call('config:cache');
    echo "Config cleared<br>";

});

Route::get('/run_backup', function () {
    $clearcache = Artisan::call('backup:run');
    echo $clearcache."Backup Success<br>";

});

Route::get('/backup_list', function () {
    return view('home.backup_list');
    dd(
    array_filter(Storage::disk('local')->allfiles(), function ($item) {
        //only png's
        return strpos($item, '.zip');
     })
    );

});




Route::post('/upload', [SettingsController::class, 'upload'])->name('upload');

Route::get('statement/report/{statement}', 'KabbouchiController@export');
Route::get('statement/inreport/{statement}', 'KabbouchiController@inexport');
Route::get('statement/inreportpdf/{statement}', 'KabbouchiController@inexportpdf');
Route::get('statement/inreportexcel/{statement}', 'KabbouchiController@inexportexcel');

Route::get('statement/cpreport/{statement}', 'KabbouchiController@cpexport');
Route::get('statement/cpreportexcel/{statement}', 'KabbouchiController@cpexportexcel');

Route::get('statement/reportqty/{statement}', 'KabbouchiController@exportreportqty');

Route::get('statement/poreport/{statement}', 'KabbouchiController@poexport');
Route::get('statement/poreportexcel/{statement}', 'KabbouchiController@poexportexcel');

 


Route::post('login', 'PageController@login')
    ->middleware('guest');
Route::get('logout', 'PageController@logout')
    ->middleware('auth');
Route::get('uploads/{filename}', 'PageController@showAttachment')
    ->middleware('auth');
Route::group(['prefix' => 'api', 'middleware' => 'auth'], function() {

      Route::get('/dashboard/custom', [DashboardController::class, 'index']);
    Route::post('dashboard/custom', [DashboardController::class, 'storeCustom']);

    Route::resource('custom_query', 'CustomQueryController');
    Route::resource('finish_product_image', 'FinishProductImageController');

Route::resource('chart_of_accounts', 'ChartOfAccountsController');
   
    Route::resource('product_lots', 'ProductLotsController');
    
    // Route::resource('products_history', 'ProductHistoryController');

    // Route::post('products/filter', 'ProductController@filter');
    Route::get('products/filter', 'ProductController@filter');
    Route::get('search/vendors_pr', 'ProductController@vendors_pr');
    Route::get('search/categories_pr', 'ProductController@categories_pr');

    Route::get('clients/filter', 'ClientController@filter');

    Route::get('sales_orders/filter', 'SalesOrderController@filter');
    Route::get('invoices/filter', 'InvoiceController@filter');

    Route::get('search/city', 'ClientController@city');
    Route::get('search/state', 'ClientController@state');
    Route::get('search/zip', 'ClientController@zip');
    
    Route::get('search/client_dropdown1', 'ClientController@client_dropdown1');
    Route::get('search/client_dropdown2', 'ClientController@client_dropdown2');
    Route::get('search/client_seller', 'ClientController@client_seller');

    Route::get('search/product_dropdown1', 'ProductController@product_dropdown1');
    Route::get('search/product_dropdown2', 'ProductController@product_dropdown2');

    

    Route::get('dashboard', 'DashboardController@index');
   
    Route::get('personal_settings', 'SettingsController@showPersonal');
    Route::post('personal_settings', 'SettingsController@storePersonal');

    Route::resource('finished_product_type', 'FinishProductTypeController');
    Route::resource('finished_product', 'FinishProductController');
    Route::resource('attributes', 'AttributesController');
    Route::resource('machine_attributes', 'MachineAttributesController');
    
    Route::resource('raw_material_type', 'RawMaterialTypeController');
    Route::resource('job_order', 'JobOrdersController');
    Route::resource('damaged_deteriorate', 'DamagedController');

    Route::resource('notifications', 'NotificationsController');
    

    Route::resource('customer_returns', 'CustomerReturnsController');
    Route::resource('product_image', 'ProductImageController');
    Route::resource('customer_returns_report', 'CustomerReturnsReportController');

    Route::resource('clients', 'ClientController');
    Route::resource('products', 'ProductController');
    
    

    Route::resource('vendors', 'VendorController');
    Route::resource('shippers', 'ShipperController');
    

    Route::resource('vatrate', 'VatRateController');

    Route::resource('sellers', 'SellersController');
    Route::resource('seller_payments', 'SellerPaymentsController');
    Route::resource('seller_payments_docs', 'SellerPaymentsDocsController');
    
    Route::put('products/lots/{id}', [ProductLotsController::class, 'updateLot']);
   
    
    Route::post('seller_payments_invoices/{seller_payments}/apply', 'SellerPaymentsController@applyPayments');

    Route::resource('machines', 'MachinesController');

    Route::resource('deliverycondition', 'DeliveryConditionsController');
    Route::resource('paymentcondition', 'PaymentConditionsController');
    Route::resource('payment_options', 'PaymentOptionsController');
    Route::resource('exchangerate', 'ExchangeRateController');
    Route::resource('uom', 'UomController');
    Route::resource('accounts', 'AccountController');
    Route::resource('brands', 'BrandController');

    Route::resource('employees', 'EmployeesController');
    Route::resource('employees_report', 'EmployeeReportController');
    Route::resource('payroll', 'PayrollsController');
    Route::resource('deposits', 'DepositsController');
    Route::resource('return_deposits', 'ReturnDepositsController');

    Route::resource('warehouses_report_criteria', 'WarehousesReportCriteriaController');
    Route::resource('categories', 'CategoryController');
    Route::resource('warehouses', 'WarehouseController');
    Route::resource('subcategories', 'SubCategoryController');
    Route::resource('subsubcategories', 'SubSubCategoryController');
    

    Route::resource('product_dropdown_1', 'ProductDropDown1Controller');
    Route::resource('product_dropdown_2', 'ProductDropDown2Controller');

    Route::resource('client_dropdown_1', 'ClientDropDown1Controller');
    Route::resource('client_dropdown_2', 'ClientDropDown2Controller');

    Route::resource('stock_movement', 'StockMovementController');
    
    
    Route::resource('statement', 'KabbouchiController');

    Route::resource('vendor_statement', 'VendorStatementController');
    Route::resource('shipper_statement', 'ShipperStatementController');

    Route::resource('seller_statement', 'SellerStatementController');

    Route::post('quotations/{quotation}/mark', 'QuotationController@markAs');
    Route::resource('quotations', 'QuotationController');


    Route::post('advance_payment_invoices/{advance_payment}/apply', 'AdvancePaymentController@applyInvoices');
    Route::resource('advance_payments', 'AdvancePaymentController');

    Route::post('sales_orders/{sales_order}/mark', 'SalesOrderController@markAs');
    Route::resource('sales_orders', 'SalesOrderController');

    Route::post('invoices/{invoice}/mark', 'InvoiceController@markAs');
    Route::resource('invoices', 'InvoiceController');

    Route::resource('third_parties_extras', 'ThirdPartiesExtrasController');  
    Route::resource('vat_accounts', 'VatAccountController');  

    

    Route::post('journal_vouchers/{journal_voucher}/mark', 'JournalVoucherController@markAs');
    Route::resource('journal_vouchers', 'JournalVoucherController');

    


    Route::resource('invoices_report', 'InvoiceReportController');
    Route::resource('purchase_orders_report', 'PurchaseOrderReportController');
    Route::resource('quotations_report', 'QuotationReportController');
    Route::resource('sales_orders_report', 'SalesOrderReportController');
    Route::resource('advance_payments_report', 'AdvancePaymentReportController');
    Route::resource('credit_notes_report', 'CreditNoteReportController');
    Route::resource('debit_notes_report', 'DebitNoteReportController');
    Route::resource('client_payments_report', 'ClientPaymentReportController');
    Route::resource('expenses_report', 'ExpensesReportController');
    Route::resource('vendor_payments_report', 'VendorPaymentsReportController');
    Route::resource('vendor_bills_report', 'VendorBillsReportController');
    Route::resource('receive_orders_report', 'ReceiveOrderReportController');
    

    
    Route::post('receipt_vouchers_invoices/{receipt_voucher}/apply', 'ReceiptVoucherController@applyInvoices');
    Route::resource('receipt_vouchers', 'ReceiptVoucherController');
    
    Route::post('payment_vouchers_bills/{payment_voucher}/apply', 'PaymentVoucherController@applyInvoices');
    Route::resource('payment_vouchers', 'PaymentVoucherController');

    Route::resource('payment_options_report', 'PaymentOptionsReportController');

    Route::resource('container_orders_report', 'ContainerOrderReportController');
    
    Route::resource('products_report', 'ProductReportController');
    Route::resource('products_catalogue', 'ProductCatalogueReportController');
    
    Route::resource('seller_payments_docs_report', 'SellerPaymentReportController');
    Route::resource('clients_balance_report', 'ClientsBalanceReportController');

    Route::resource('price_changes_report', 'PriceChangesReportController');
    Route::resource('cost_changes_report', 'CostChangesReportController');
    


    Route::resource('transfer_accounts', 'TransferAccountsController');
    Route::resource('transfers', 'TransferController');
    Route::resource('products_division', 'ProductsDivisionController');
    Route::resource('products_aggregation', 'ProductsAggregationController');

    Route::resource('counters', 'CounterController');
    Route::resource('currencies', 'CurrenciesController');

    Route::post('credit_notes_invoices/{credit_note}/apply', 'CreditNotesController@applyInvoices');
    Route::post('credit_notes/{credit_note}/mark', 'CreditNotesController@markAs');

    Route::resource('credit_notes', 'CreditNotesController');

    Route::post('debit_notes_invoices/{debit_note}/apply', 'DebitNotesController@applyInvoices');
    Route::post('debit_notes/{debit_note}/mark', 'DebitNotesController@markAs');
    Route::resource('debit_notes', 'DebitNotesController');

    Route::resource('client_payments', 'ClientPaymentController');

    Route::resource('expenses', 'ExpenseController');

    Route::post('purchase_orders/{purchase_order}/mark', 'PurchaseOrderController@markAs');
    Route::resource('purchase_orders', 'PurchaseOrderController');

    Route::post('container_orders/{container_order}/mark', 'ContainerOrderController@markAs');
    Route::resource('container_orders', 'ContainerOrderController');

    Route::resource('receive_orders', 'ReceiveOrderController');
    Route::post('receive_orders/{bill}/mark', 'ReceiveOrderController@markAs');

    Route::resource('goods_issue', 'GoodsIssueController');

    Route::resource('container_receive_orders', 'ContainerReceiveOrderController');

    Route::post('bills/{bill}/mark', 'BillController@markAs');
    Route::resource('bills', 'BillController');

    Route::post('shipper_bills/{shipper_bills}/mark', 'ShipperBillController@markAs');
    Route::resource('shipper_bills', 'ShipperBillController');

    

    Route::post('notifications/mark', 'NotificationsController@markAs');

    Route::resource('vendor_payments', 'VendorPaymentController');
    Route::resource('shipper_payments', 'ShipperPaymentController');
    
    
    Route::group(['prefix' => 'search'], function() {
        
        Route::get('finished_product', 'FinishProductController@search');
        Route::get('finished_product_type', 'FinishProductTypeController@search');
        Route::get('attributes', 'AttributesController@search');
        Route::get('machine_attributes', 'MachineAttributesController@search');
        
        Route::get('raw_material_type', 'RawMaterialTypeController@search');
  


        Route::get('vatrate', 'VatRateController@search');
        
        Route::get('clients', 'ClientController@search');
        Route::get('clients1', 'ClientController@search1');
        Route::get('invoices', 'InvoiceController@search');

        Route::get('invoices_sent', 'InvoiceController@invoices_sent');
        Route::get('invoices_confirmed', 'InvoiceController@invoices_confirmed');
        
        Route::get('vendor_bills_confirmed', 'BillController@vendor_bills_confirmed');
        
        Route::get('vendor_bills_sent', 'BillController@vendor_bills_sent');
        Route::get('vendor_bills_sent_expenses', 'BillController@vendor_bills_sent_expenses');
        Route::get('purchase_orders_sent', 'PurchaseOrderController@purchase_orders_sent');
        Route::get('sales_orders_sent', 'SalesOrderController@sales_orders_sent');
        Route::get('third_parties_extras', 'ThirdPartiesExtrasController@search');           

        Route::get('vendors', 'VendorController@search');
        Route::get('shippers', 'ShipperController@search');

        Route::get('chart_classes', 'ChartOfAccountsController@chart_classes');
        Route::get('ledger_account', 'ChartOfAccountsController@search');

        Route::get('ledger_account_601', 'ChartOfAccountsController@search_601');
        Route::get('ledger_account_461', 'ChartOfAccountsController@search_461');
        Route::get('ledger_vat_accounts', 'ChartOfAccountsController@ledger_vat_accounts');
        Route::get('ledger_account_payables', 'ChartOfAccountsController@ledger_account_payables');
        

        Route::get('sales_orders', 'SalesOrderController@search');
        
        Route::get('search', 'PaymentOptionsController@search');
        Route::get('cp_payment_options', 'PaymentOptionsController@cp_payment_options');
        Route::get('ad_payment_options', 'PaymentOptionsController@ad_payment_options');
        Route::get('cn_payment_options', 'PaymentOptionsController@cn_payment_options');
        Route::get('dn_payment_options', 'PaymentOptionsController@dn_payment_options');
        Route::get('vp_payment_options', 'PaymentOptionsController@vp_payment_options');
        Route::get('sp_payment_options', 'PaymentOptionsController@sp_payment_options');
        

        Route::get('notifications', 'NotificationsController@search');
        
        
        Route::get('QuotationProducts', 'ProductController@QuotationProducts');
        Route::get('SalesProducts', 'ProductController@SalesProducts');
        Route::get('InvoicesProducts', 'ProductController@InvoicesProducts');
        // $comany_type = Settings::where('key','=','company_type')->value('value');
        $comany_type = DB::table('settings')->where('key','=','company_type')->value('value');
        if($comany_type == 0){
            Route::get('products', 'ProductController@search');
        }elseif($comany_type == 1){
            Route::get('products', 'FinishProductController@search');
        }elseif($comany_type == 2){
            Route::get('products', 'FinishProductController@searchboth');
        }

        Route::get('price_changes_products', 'ProductController@price_changes_products');
        

        Route::get('vendor_products', 'ProductController@search');
        
        Route::get('vendor_products2', 'ProductController@vendor_products2');
        
        Route::get('product_dropdown_1', 'ProductController@product_dropdown_1');
        Route::get('product_dropdown_2', 'ProductController@product_dropdown_2');
        
        Route::get('client_dropdown_1', 'ClientController@client_dropdown_1');
        Route::get('client_dropdown_2', 'ClientController@client_dropdown_2');

        Route::get('products_bill', 'ProductController@products_bill');
        
        Route::get('ContainerProducts', 'ProductController@ContainerProducts');
        
        Route::get('products_aggregation', 'ProductController@products_aggregation');
        Route::get('productsa', 'ProductController@searcha');
        
        Route::get('menu', 'MenuController@search');
        
        Route::get('currencies', 'CurrencyController@search');
        Route::get('purchase_orders', 'PurchaseOrderController@search');
        Route::get('container_purchase_orders', 'PurchaseOrderController@container_purchase_orders');
        

        Route::get('machines', 'MachinesController@search');

         Route::get('deliverycondition', 'DeliveryConditionsController@search');
         Route::get('paymentcondition', 'PaymentConditionsController@search');
         Route::get('exchangerate', 'ExchangeRateController@search');
         Route::get('uom', 'UomController@search');
         Route::get('uom_po', 'UomController@uom_po');
         Route::get('brands', 'BrandController@search');
         Route::get('users', 'Settings\UserController@search');
         

        Route::get('subcategories', 'SubCategoryController@search');
        Route::get('categoriesall', 'CategoryController@searchall');
        Route::get('subcategoriesall', 'SubCategoryController@searchall');
        Route::get('subsubcategoriesall', 'SubSubCategoryController@searchall');
        Route::get('categories', 'CategoryController@search');
        Route::get('warehouses', 'WarehouseController@search');
        Route::get('subcategories', 'SubCategoryController@search');

        Route::get('accounts', 'AccountController@search');
        Route::get('employees', 'EmployeesController@search');
        Route::get('payroll', 'PayrollsController@search');

        Route::get('advance_payment_invoices/{advance_payment}', 'AdvancePaymentController@showInvoices');
        Route::get('seller_payments_invoices/{seller_payments}', 'SellerPaymentsController@showPaymentApply');

        Route::get('receipt_vouchers_invoices/{receipt_voucher}', 'ReceiptVoucherController@showInvoices');
        Route::get('payment_vouchers_bills/{payment_voucher}', 'PaymentVoucherController@showInvoices');
        
        Route::get('sellers', 'SellersController@search');
        

        Route::get('debit_notes_invoices/{debit_note}', 'DebitNotesController@showInvoices');
        Route::get('credit_notes_invoices/{credit_note}', 'CreditNotesController@showInvoices');
        
    });

    // admin routes
    Route::group(['middleware' => 'admin'], function() {
        Route::get('settings', 'SettingsController@show');
        Route::post('settings', 'SettingsController@store');

        Route::resource('users', 'Settings\UserController');
    });

    Route::group(['prefix' => 'email'], function() {
        Route::get('quotations/{quotation}', 'EmailController@showQuotation');
        Route::post('quotations/{quotation}', 'EmailController@sendQuotation');

        Route::get('advance_payments/{advance_payment}', 'EmailController@showAdvancePayment');
        Route::post('advance_payments/{advance_payment}', 'EmailController@sendAdvancePayment');

        Route::get('sales_orders/{sales_order}', 'EmailController@showSalesOrder');
        Route::post('sales_orders/{sales_order}', 'EmailController@sendSalesOrder');

        Route::get('invoices/{invoice}', 'EmailController@showInvoice');
        Route::post('invoices/{invoice}', 'EmailController@sendInvoice');

        Route::get('client_payments/{client_payment}', 'EmailController@showClientPayment');
        Route::post('client_payments/{client_payment}', 'EmailController@sendClientPayment');

        Route::get('purchase_orders/{purchase_order}', 'EmailController@showPurchaseOrder');
        Route::post('purchase_orders/{purchase_order}', 'EmailController@sendPurchaseOrder');
    });

    Route::group(['prefix' => 'mini/'], function() {
        Route::get('clients/invoices/{client}', 'ClientController@showInvoices');
        Route::get('clients/quotations/{client}', 'ClientController@showQuotations');
        Route::get('clients/sales_orders/{client}', 'ClientController@showSalesOrders');

        Route::get('products/sales_orders/{product}', 'ProductController@showSalesOrders');
        Route::get('products/purchase_orders/{product}', 'ProductController@showPurchaseOrders');
        Route::get('products/invoices/{product}', 'ProductController@showInvoices');
        Route::get('products/lots/{product}', 'ProductLotsController@showLots');

        Route::get('sellers/sales_orders_seller/{seller}', 'SellersController@showSalesOrdersSellers');
        Route::get('sellers/seller_payments/{seller}', 'SellerPaymentsController@showPayments');
        Route::get('sellers/seller_payments_docs/{seller}', 'SellerPaymentsDocsController@showPayments');
        

        Route::get('clients/advance_payments/{client}', 'ClientController@showAdvancePayments');
        Route::get('clients/payments/{client}', 'ClientController@showPayments');
        Route::get('clients/credit_notes/{client}', 'ClientController@showCreditNotes');
        

        Route::get('receipt_vouchers/invoices/{receipt_voucher}', 'ReceiptVoucherController@MinishowInvoices');
        Route::get('payment_vouchers/bills/{payment_voucher}', 'PaymentVoucherController@MinishowInvoices');
        
        

        Route::get('vendors/expenses/{vendor}', 'VendorController@showExpenses');
        Route::get('vendors/payments/{vendor}', 'VendorController@showPayments');
        Route::get('vendors/bills/{vendor}', 'VendorController@showBills');
        Route::get('vendors/purchase_orders/{vendor}', 'VendorController@showPurchaseOrders');
        Route::get('vendors/products/{product}', 'VendorController@showProducts');
        Route::get('vendors/receive_orders/{vendor}', 'VendorController@showRecevieOrders');
        // Route::get('products/quotations/{product}', 'ProductController@showQuotations');
        // Route::get('products/invoices/{product}', 'ProductController@showInvoices');
        Route::get('categories/products/{product}', 'CategoryController@showProducts');
        Route::get('subcategories/products/{product}', 'SubCategoryController@showProducts');
        
        Route::get('warehouses/products/{product}', 'WarehouseController@showProducts');
    });
});

Route::group(['prefix' => 'docs', 'middleware' => 'auth'], function() {

  
    
    Route::get('quotations/{quotation}', 'QuotationController@pdf');
    Route::get('sales_orders/{sales_order}', 'SalesOrderController@pdf');
    Route::get('invoices/{invoice}', 'InvoiceController@pdf');
    Route::get('advance_payments/{advance_payment}', 'AdvancePaymentController@pdf');
    Route::get('client_payments/{client_payment}', 'ClientPaymentController@pdf');

    Route::get('statement/{statement}', 'KabbouchiController@pdf');
    Route::get('statement/report/{statement}', 'KabbouchiController@report');

    Route::get('vendor_statement/{vendor_statement}', 'VendorStatementController@pdf');
    Route::get('shipper_statement/{shipper_statement}', 'ShipperStatementController@pdf');

        Route::get('products_catalogue/excel/show/{products_catalogue}', 'ProductCatalogueReportController@portrait_pdf');
 

    Route::get('seller_statement/{seller_statement}', 'SellerStatementController@pdf');
    
    
    Route::get('journal_vouchers/{journal_voucher}', 'JournalVoucherController@pdf');
    Route::get('receipt_vouchers/{receipt_voucher}', 'ReceiptVoucherController@pdf');
    Route::get('payment_vouchers/{payment_voucher}', 'PaymentVoucherController@pdf');


    Route::get('customer_returns_report/{customer_returns_report}', 'CustomerReturnsReportController@pdf');
    Route::get('customer_returns_report/excel/{customer_returns_report}', 'CustomerReturnsReportController@excel');
    Route::get('customer_returns/{invoice}', 'CustomerReturnsController@pdf');

    Route::get('container_receive_orders/{container_receive_orders}', 'ContainerReceiveOrderController@pdf');
    Route::get('container_orders/{container_orders}', 'ContainerOrderController@pdf');
    Route::get('container_orders/details/{container_orders}', 'ContainerOrderController@pdf_details');
    
    Route::get('credit_notes/{credit_note}', 'CreditNotesController@pdf');
    Route::get('debit_notes/{debit_note}', 'DebitNotesController@pdf');

    Route::get('warehouses/{items}', 'WarehouseController@pdf');
    Route::get('warehouses/{items}/zero_stock', 'WarehouseController@zero_stock');
    
    Route::get('warehousesreports', 'WarehouseController@pdfgeneral');
    Route::get('categories/{items}', 'CategoryController@pdf');
    Route::get('subcategories/{items}', 'SubCategoryController@pdf');
    Route::get('categoriesreports', 'CategoryController@pdfgeneral');
    Route::get('warehouses_report_criteria/{items}', 'WarehousesReportCriteriaController@pdf');

    Route::get('purchase_orders_report/{purchase_orders_report}', 'PurchaseOrderReportController@pdf');
    Route::get('purchase_orders_report/excel/{purchase_orders_report}', 'PurchaseOrderReportController@excel');

    Route::get('quotations_report/{quotations_report}', 'QuotationReportController@pdf');
    Route::get('quotations_report/excel/{quotations_report}', 'QuotationReportController@excel');

    Route::get('sales_orders_report/{sales_orders_report}', 'SalesOrderReportController@pdf');
    Route::get('sales_orders_report/excel/{sales_orders_report}', 'SalesOrderReportController@excel');

    Route::get('invoices_report/{invoices_report}', 'InvoiceReportController@pdf');
    Route::get('invoices_report/excel/{invoices_report}', 'InvoiceReportController@excel');

    Route::get('advance_payments_report/{advance_payments_report}', 'AdvancePaymentReportController@pdf');
    Route::get('advance_payments_report/excel/{advance_payments_report}', 'AdvancePaymentReportController@excel');

    Route::get('credit_notes_report/{credit_notes_report}', 'CreditNoteReportController@pdf');
    Route::get('credit_notes_report/excel/{credit_notes_report}', 'CreditNoteReportController@excel');

    Route::get('debit_notes_report/{debit_notes_report}', 'DebitNoteReportController@pdf');
    Route::get('debit_notes_report/excel/{debit_notes_report}', 'DebitNoteReportController@excel');

    Route::get('client_payments_report/{client_payments_report}', 'ClientPaymentReportController@pdf');
    Route::get('client_payments_report/excel/{client_payments_report}', 'ClientPaymentReportController@excel');

    Route::get('expenses_report/{expenses_report}', 'ExpensesReportController@pdf');
    Route::get('expenses_report/excel/{expenses_report}', 'ExpensesReportController@excel');

    Route::get('vendor_payments_report/{vendor_payments_report}', 'VendorPaymentsReportController@pdf');
    Route::get('vendor_payments_report/excel/{vendor_payments_report}', 'VendorPaymentsReportController@excel');

    Route::get('vendor_bills_report/{vendor_bills_report}', 'VendorBillsReportController@pdf');
    Route::get('vendor_bills_report/excel/{vendor_bills_report}', 'VendorBillsReportController@excel');

    Route::get('receive_orders_report/{receive_orders_report}', 'ReceiveOrderReportController@pdf');
    Route::get('receive_orders_report/excel/{receive_orders_report}', 'ReceiveOrderReportController@excel');

    Route::get('payment_options_report/{payment_options_report}', 'PaymentOptionsReportController@pdf');
    Route::get('payment_options_report/excel/{payment_options_report}', 'PaymentOptionsReportController@excel');

    Route::get('container_orders_report/{container_orders_report}', 'ContainerOrderReportController@pdf');
    Route::get('container_orders_report/excel/{container_orders_report}', 'ContainerOrderReportController@excel');

    Route::get('products_report/{products_report}', 'ProductReportController@pdf');
    Route::get('products_report/excel/{products_report}', 'ProductReportController@excel');

    Route::get('products_catalogue/{products_catalogue}', 'ProductCatalogueReportController@pdf');
    Route::get('products_catalogue/excel/{products_catalogue}', 'ProductCatalogueReportController@portrait_pdf');

    Route::get('seller_payments_docs_report/{seller_payments_docs_report}', 'SellerPaymentReportController@pdf');
    Route::get('seller_payments_docs_report/excel/{seller_payments_docs_report}', 'SellerPaymentReportController@excel');
    

    Route::get('clients_balance_report/{clients_balance_report}', 'ClientsBalanceReportController@pdf');
    Route::get('clients_balance_report/excel/{clients_balance_report}', 'ClientsBalanceReportController@excel');

    Route::get('price_changes_report/{price_changes_report}', 'PriceChangesReportController@pdf');
    Route::get('price_changes_report/excel/{price_changes_report}', 'PriceChangesReportController@excel');

    Route::get('cost_changes_report/{cost_changes_report}', 'CostChangesReportController@pdf');
    Route::get('cost_changes_report/excel/{cost_changes_report}', 'CostChangesReportController@excel');
    

    Route::get('products/{products}', 'ProductController@pdf');
    Route::get('label_barcode/{label_barcode}/', 'ProductController@label_barcode');

    Route::get('receive_label_barcode/{receive_label_barcode}/', 'ProductController@receive_label_barcode');
    Route::get('receive_label_barcode_id/{receive_label_barcode_id}/', 'ProductController@receive_label_barcode_id');
     Route::get('receive_label_barcode_lot_id/{receive_label_barcode_id}/', 'ProductController@receive_label_barcode_lot_id');

    
    Route::get('finished_product/{finished_product}', 'FinishProductController@pdf');
    
    Route::get('products_aggregation/{products_aggregation}', 'ProductsAggregationController@pdf');
    

    Route::get('expenses/{expense}', 'ExpenseController@pdf');
    Route::get('purchase_orders/{purchase_order}', 'PurchaseOrderController@pdf');
    Route::get('bills/{bill}', 'BillController@pdf');
    Route::get('shipper_bills/{shipper_bills}', 'ShipperBillController@pdf');
    
    
    Route::get('shipper_payments/{shipper_payment}', 'ShipperPaymentController@pdf');
    Route::get('vendor_payments/{vendor_payment}', 'VendorPaymentController@pdf');
    Route::get('receive_orders/{purchase_order}', 'ReceiveOrderController@pdf');
    Route::get('goods_issue/{goods_issue}', 'GoodsIssueController@pdf');

    Route::get('payroll/{payroll}', 'PayrollsController@pdf');
    Route::get('employees_report/{employees_report}', 'EmployeeReportController@pdf');

    Route::get('damaged_deteriorate/{damaged_deteriorate}', 'DamagedController@pdf');
    Route::get('seller_payments_docs/{seller_payments_docs}', 'SellerPaymentsDocsController@pdf');

    
});

Route::get('{vue?}', 'PageController@index')->where('vue', '[\/\w\.-]*');

