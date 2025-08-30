<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{settings()->get('app_title')}}</title>
        <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ url('/')}}/css/bootstrap.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<script src="https://cdn.tailwindcss.com"></script>

    
    </head>
    <body>
        <div id="root"></div>
<?php
    use Spatie\Activitylog\Models\Activity;
    if(auth()->user()->is_admin == 1){
        $activity = Activity::orderBy('created_at','desc')->get();
    }else{
        $activity = "[]";
    }
?>
    </body>
    <script type="text/javascript">
        window.apex = {
            app_name: "{{settings()->get('app_title')}}",
            logo_name: "/uploads/{{settings()->get('uploaded_logo')}}",
            base_currency: "{{settings()->get('currency_id')}}",
            display_vat:"{{settings()->get('display_vat')}}",
            product_dropdown_1: "{{settings()->get('product_dropdown_1')}}",
            product_dropdown_2: "{{settings()->get('product_dropdown_2')}}",
            client_dropdown_1: "{{settings()->get('client_dropdown_1')}}",
            client_dropdown_2: "{{settings()->get('client_dropdown_2')}}",
            first_currency: "{{ App\Currency::where('id','=','1')->value('code')}}",
            second_currency: "{{ App\Currency::where('id','=','2')->value('code')}}",
            first_currency_decimal: "{{ App\Currency::where('id','=','1')->value('decimal_place')}}",
            second_currency_decimal: "{{ App\Currency::where('id','=','2')->value('decimal_place')}}",
            working_days: "{{settings()->get('working_days')}}", 
            installed_at: "{{settings()->get('starting_date')}}",
            fiscal_year: "{{\Carbon\Carbon::today()->year}}",
            company_name:"{{settings()->get('company_name')}}",
            company_type: "{{settings()->get('company_type')}}",
            display_exchange_rate: "{{settings()->get('display_exchange_rate')}}",
            display_vat_rate: "{{settings()->get('display_vat_rate')}}",
            disable_second_currency: "{{settings()->get('disable_second_currency')}}",
            invoices_available_qty:"{{settings()->get('invoices_available_qty')}}",
            notifications: {!! $notifications !!},
            quick_menus: {!! $quick_menus !!},
            dashboard_views:  {!! $dashboard_views !!},
            reports_views:  {!! $reports_views !!},
            release_note: {!! $release_note !!},
            sidebar_lists:  {!! json_encode($sidebar_lists) !!},
            app_color: "{{settings()->get('app_color')}}",
            nav_color: "{{settings()->get('nav_color')}}",
            copyrights: "{{settings()->get('copyrights')}}",
            license_email: "{{settings()->get('license_email')}}",
            global_vat_percentage: "{{settings()->get('global_vat_percentage')}}",
            activity: {!! $activity !!},
            user: {
                id: {{auth()->user()->id}},
                name: "{{auth()->user()->name}}",
                is_admin: {{auth()->user()->is_admin}},
                is_dashboard: {{auth()->user()->is_dashboard}},
                is_settings_tab: {{auth()->user()->is_settings_tab}},
                is_procurment_tab: {{auth()->user()->is_procurment_tab}},
                is_sales_tab: {{auth()->user()->is_sales_tab}},
                is_accounting_tab: {{auth()->user()->is_accounting_tab}},
                is_company_tab: {{auth()->user()->is_company_tab}},
                is_deliverycondition_tab: {{auth()->user()->is_deliverycondition_tab}},
                is_paymentcondition_tab: {{auth()->user()->is_paymentcondition_tab}},
                is_exchangerate_tab: {{auth()->user()->is_exchangerate_tab}},
                is_production_tab: {{auth()->user()->is_production_tab}},
                is_uom_tab: {{auth()->user()->is_uom_tab}},
                is_counters_tab: {{auth()->user()->is_counters_tab}},
                is_currencies_tab: {{auth()->user()->is_currencies_tab}},
                is_warehouses_tab: {{auth()->user()->is_warehouses_tab}},
                is_categories_tab: {{auth()->user()->is_categories_tab}},
                is_subcategories_tab: {{auth()->user()->is_subcategories_tab}},
                is_accounts_tab: {{auth()->user()->is_accounts_tab}},
                is_transferaccounts_tab: {{auth()->user()->is_transferaccounts_tab}},
                is_deposit_tab: {{auth()->user()->is_deposit_tab}},
                is_returndeposit_tab: {{auth()->user()->is_returndeposit_tab}},
                is_employees_tab: {{auth()->user()->is_employees_tab}},
                is_payroll_tab: {{auth()->user()->is_payroll_tab}},
                is_clients_tab: {{auth()->user()->is_clients_tab}},
                is_quotations_tab: {{auth()->user()->is_quotations_tab}},
                is_salesorders_tab: {{auth()->user()->is_salesorders_tab}},

                is_advancepayments_tab: {{auth()->user()->is_advancepayments_tab}},
                is_invoices_tab: {{auth()->user()->is_invoices_tab}},
                is_creditnotes_tab: {{auth()->user()->is_creditnotes_tab}},
                is_debitnotes_tab: {{auth()->user()->is_debitnotes_tab}},
                is_clientpayments_tab: {{auth()->user()->is_clientpayments_tab}},
                is_clientsoa_tab: {{auth()->user()->is_clientsoa_tab}},
                is_vendorexpenses_tab: {{auth()->user()->is_vendorexpenses_tab}},
                is_bills_tab: {{auth()->user()->is_bills_tab}},
                is_vendorpayments_tab: {{auth()->user()->is_vendorpayments_tab}},
                is_vendorsoa_tab: {{auth()->user()->is_vendorsoa_tab}},

                is_products_tab: {{auth()->user()->is_products_tab}},
                is_receiveorders_tab: {{auth()->user()->is_receiveorders_tab}},
                is_vendors_tab: {{auth()->user()->is_vendors_tab}},
                is_purchaseorders_tab: {{auth()->user()->is_purchaseorders_tab}},
                is_transfers_tab: {{auth()->user()->is_transfers_tab}},
                is_productsdivision_tab: {{auth()->user()->is_productsdivision_tab}},
                is_productsaggregation_tab: {{auth()->user()->is_productsaggregation_tab}},

                is_displayoverview_tab: {{auth()->user()->is_displayoverview_tab}},
                is_displaysales_tab: {{auth()->user()->is_displaysales_tab}},
                is_displayaccounting_tab: {{auth()->user()->is_displayaccounting_tab}},
                is_displaystock_tab: {{auth()->user()->is_displaystock_tab}},
                is_displayproduction_tab: {{auth()->user()->is_displaystock_tab}},
            }
        }
    </script>
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">

<style>
    .invoice-cost-price {color: yellow !important; }


</style>

<!-- Settings Tab-->
<style>
    .invoice-cost-price {color: yellow !important; }
</style>
@if(auth()->user()->is_admin == 0)
    <style>
        .Sellers, .Seller_SOA, .Seller_Payments, .Raw_Material_Type , .Damaged_Deteriorate {display:none !important;}
    </style>
@endif

@if(auth()->user()->is_products_edit == 0)
    <style>
        .product_vendors, .product_purchase_order, .product_sales_order, .product_profit_value, .product_cost_price {display:none !important;}
    </style>
@endif


@if(auth()->user()->is_trialbalance_tab == 0)
    <style>
        .Trial_balance_Report {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_journalvoucher_tab == 0)
    <style>
        .Journal_Vouchers {display:none !important;}
    </style>
@endif


@if(auth()->user()->is_Define_Shippers_tab == 0)
    <style>
        .Define_Shippers {display:none !important;}
    </style>
@endif

@if(auth()->user()->is_Shipments_tab == 0)
    <style>
        .Shipments {display:none !important;}
    </style>
@endif

@if(auth()->user()->is_Receive_Shipments_tab == 0)
    <style>
        .Receive_Shipments {display:none !important;}
    </style>
@endif

@if(auth()->user()->is_Shippers_Bills_tab == 0)
    <style>
        .Shippers_Bills {display:none !important;}
    </style>
@endif

@if(auth()->user()->is_Shippers_Payments_tab == 0)
    <style>
        .Shippers_Payments {display:none !important;}
    </style>
@endif

@if(auth()->user()->is_shipping_tab == 0)
    <style>
        .Shipping {display:none !important;}
    </style>
@endif


@if(auth()->user()->is_Shippers_SOA_tab == 0)
    <style>
        .Shippers_SOA {display:none !important;}
    </style>
@endif


@if(auth()->user()->is_deliverycondition_tab == 0)
    <style>
        .Delivery_Condition {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_paymentcondition_tab == 0)
    <style>
        .Payment_Condition {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_exchangerate_tab == 0)
    <style>
        .Exchange_Rate {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_uom_tab == 0)
    <style>
        .UOM {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_counters_tab == 0)
    <style>
        .Counters {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_currencies_tab == 0)
    <style>
        .Currencies {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_warehouses_tab == 0)
    <style>
        .Warehouses {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_categories_tab == 0)
    <style>
        .Categories {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_subcategories_tab == 0)
    <style>
        .Sub_Categories {display:none !important;}
    </style>   
@endif

<!-- Company Tab-->
@if(auth()->user()->is_accounts_tab == 0)
    <style>
        .Accounts {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_transferaccounts_tab == 0)
    <style>
        .Transfer_Accounts {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_deposit_tab == 0)
    <style>
        .Deposit {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_returndeposit_tab == 0)
    <style>
        .Return_Deposit {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_employees_tab == 0)
    <style>
        .Employees {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_payroll_tab == 0)
    <style>
        .Payroll {display:none !important;}
    </style>
@endif

<!-- Sales Tab -->
@if(auth()->user()->is_clients_tab == 0)
    <style>
        .Clients {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_quotations_tab == 0)
    <style>
        .Quotations {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_salesorders_tab == 0)
    <style>
        .Sales_Orders {display:none !important;}
    </style>
@endif

<!-- Accounting TAB -->
@if(auth()->user()->is_advancepayments_tab == 0)
    <style>
        .Client_Advance_Payments {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_invoices_tab == 0)
    <style>
        .Client_Invoices {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_creditnotes_tab == 0)
    <style>
        .Credit_Notes {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_debitnotes_tab == 0)
    <style>
        .Debit_Notes {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_clientpayments_tab == 0)
    <style>
        .Client_Payments {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_clientsoa_tab == 0)
    <style>
        .Client_SOA {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_vendorexpenses_tab == 0)
    <style>
        .Vendor_Expenses {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_bills_tab == 0)
    <style>
        .Vendor_Bills {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_vendorpayments_tab == 0)
    <style>
        .Vendor_Payments {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_vendorsoa_tab == 0)
    <style>
        .Vendor_SOA {display:none !important;}
    </style>
@endif

<!-- Procurment & Stock Tab -->
@if(auth()->user()->is_products_tab == 0)
    <style>
        .Products {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_receiveorders_tab == 0)
    <style>
        .Receive_Orders {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_vendors_tab == 0)
    <style>
        .Vendors {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_purchaseorders_tab == 0)
    <style>
        .Purchase_Orders {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_transfers_tab == 0)
    <style>
        .Transfers {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_productsdivision_tab == 0)
    <style>
        .Products_Division {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_productsaggregation_tab == 0)
    <style>
        .Products_Aggregation {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_stockmovement_tab == 0)
    <style>
        .Stock_Movement {display:none !important;}
    </style>   
@endif

<!-- overview -->
@if(auth()->user()->is_displayoverview_tab == 0)
    <style>
        .displayoverview {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_displaysales_tab == 0)
    <style>
        .displaysales {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_displayaccounting_tab == 0)
    <style>
        .displayaccounting {display:none !important;}
    </style>
@endif
@if(auth()->user()->is_displaystock_tab == 0)
    <style>
        .displaystock {display:none !important;}
    </style>
@endif

@if(auth()->user()->is_displayproduction_tab == 0)
    <style>
        .displayproduction {display:none !important;}
    </style>
@endif


@if(settings()->get('company_type') == 0)
    <style>
        .Production, .Machines, .Product, .Product_Type, .Job_Orders, .Line_Production, .Packaging, .Delivery_Note, .Attributes
         {display: none !important;}
    </style>
@endif











</html>
<script>
function showHideSideBar() {
    var x = document.getElementById("sidebar");
    var y = document.getElementById("main-view");
    if ( (x.style.display === "none") && (y.style.width === "100%")) {
      x.style.display = "block";
      y.style.width = "87%";
    } else {
      x.style.display = "none";
      y.style.width = "100%";
    }
  }
</script>

{{-- <script src="{{url('/')}}/vendors/jquery/dist/jquery.min.js"></script> --}}

<!-- Bootstrap Core JavaScript -->
{{-- <script src="{{url('/')}}/vendors/bootstrap/dist/js/bootstrap.min.js"></script> --}}


<!-- FeatherIcons JavaScript -->
{{-- <script src="{{url('/')}}/dist/js/feather.min.js"></script> --}}

<!-- Toggles JavaScript -->
{{-- <script src="{{url('/')}}/vendors/jquery-toggles/toggles.min.js"></script> --}}
{{-- <script src="{{url('/')}}/dist/js/toggle-data.js"></script> --}}


<!-- Init JavaScript -->
{{-- <script src="{{url('/')}}/dist/js/init.js"></script> --}}

<?php $back_color = settings()->get('app_color'); ?>
<?php $front_color = settings()->get('text_color'); ?>
<?php $nav_color = settings()->get('nav_color'); ?>

<style >
         .hover\:bg-gray-200:hover {
    --tw-bg-opacity: 1;
    color: #fff !important;
    background-color: <?php echo $nav_color ?> !important;
}
.bg-gray-300 {color: #fff !important;
    background-color: <?php echo $nav_color ?> !important;}

    .Dashboard > a {
        color: <?php echo $front_color ?> ;
    }

    .import_tools {
        background-color: <?php echo $back_color ?> ;
    }
    .sidebar-list li:nth-child(2) {
      background-color: <?php echo $back_color ?> ;
    }
    .sidebar-list li:nth-child(2) > a {
      color: <?php echo $front_color ?> ;
    }
    /* .sidebar-links ul:nth-child(1) > li > a {
        background-color: <?php echo $back_color ?> ;
    } */

    .top_nav_bg_color {
        background-color: <?php echo $nav_color ?> !important;
    }
        

    .hk-wrapper .hk-navbar.navbar-dark {
        background-color: <?php echo $nav_color ?> !important;
    }

    .document {
         -webkit-box-shadow: 0 1px 3px  <?php echo $nav_color ?> !important;
        box-shadow: 0 1px 3px  <?php echo $nav_color ?> !important;
    }

    .btn-primary, .btn-success {
        background-color: <?php echo $back_color ?> !important;
        background: <?php echo $back_color ?> !important;
        -webkit-box-shadow: 0 1px  <?php echo $back_color ?> !important;
        box-shadow: 0 1px  <?php echo $back_color ?> !important;
    }
    .fa-bars, .toggle, .btn-primary, .btn-success  {
        color: <?php echo $front_color ?> !important;
    }

    .link_shortcut{
        background: #000;
        padding: 5px;
        color: #fff;
    }

    .link_shortcut_2{
        background: #000;
        padding: 5px;
        color: #fff;
            top: -10px;
    position: relative;
    }

    .quick-form-div {
        text-align:center;
        color:#000;
        width: 25%;
        background: #f8f8f8;
    }
    .quick-form-div > ul{
        display: inline-flex;
    }
    .quick-form-div > ul > li > a{
        color:#000;
        line-height: 1.5;
        font-size:11px !important;
    }
    .quick-form-div > ul > li {
        /* margin: 5px 10px; */
        margin: 4px 2px;
    }
    .quick-form-div > h4 {
        font-size: 10px;
    }

    
    

    .panel  {
        -webkit-box-shadow: 0 1px 3px  <?php echo $back_color ?> !important;
        box-shadow: 0 1px 3px  <?php echo $back_color ?> !important;
    }

    
    hr {
    height: 10px !important;
    }
   
    .max-w-9xl {
            max-width: 90rem !important;
    }
    
</style>