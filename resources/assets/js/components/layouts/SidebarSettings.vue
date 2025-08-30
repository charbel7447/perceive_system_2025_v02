<template>
    <div class="sidebarx" id="sidebar_settings">
        <div class="sidebar-innerr" v-if="(installed_at == fiscal_year)">
            <div class="sidebar-linkss">
                <ul class="sidebar-list"   v-for="list in activeList">
                    <li class="sidebar-break"></li>
                    <li v-for="link in list.links" :class="link.title" type="button" :data-toggle="link.expand"  style="direction: rtl;">
                        <router-link :to="link.path" class="sidebar-link"  >
                        <i class="fa" :class="link.icon"></i>
                            <div class="sidebar-link_inner">
                                <span class="sidebar-text">{{ link.title }}</span>
                            </div>
                            <!-- <i class="fa fa-caret-right"></i> -->
                        </router-link>
                    </li>
                    
                </ul>
            </div>
        </div>
        <div v-else>
            <div class="col col-12"  style="margin: 25% auto;">
            &nbsp;
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    export default {
        computed: {
            user() {
                return window.apex.user
            },
            installed_at(){
                 return window.apex.installed_at
            },
            fiscal_year(){
                 return window.apex.fiscal_year
            },
             app_color() {
                return window.apex.app_color
            },
            activeList() {
                return this.lists.filter((item) => {
                    if(item.settings_tab){
                        return this.user.is_settings_tab
                    }else if(item.company_tab){
                        return this.user.is_company_tab
                    }else if(item.sales_tab){
                        return this.user.is_sales_tab
                    }else if(item.procurment_tab){
                        return this.user.is_procurment_tab
                    }else if(item.accounting_tab){
                        return this.user.is_accounting_tab
                    }else if(item.admin){
                        return this.user.is_admin
                    }else if(item.dashboard_tab){
                        return this.user.is_dashboard
                    }else if(item.production_tab){
                        return this.user.is_production_tab
                    }else {
                        return 0
                    }
                }) 
            },
        },
        data() {
            return {
                lists: [
                     {
                        dashboard_tab: true,
                        links: [
                            // {path: '/', title: 'Dashboard', icon: 'fa fa-dashboard',fix: 'fix-nav', },
                        ]
                    },
                    {
                        settings_tab: true,
                        links: [
                            {path: '/', title: 'Settings', icon: 'fa fa-dashboard', fix: 'fix-nav', expand: 'collapse'},
                            {path: '/journal_vouchers_flow', title: 'Journal_flow', icon: 'fa fa-bus', list: 'content'},
                            {path: '/third_parties_extras', title: 'Third_Parties_Extras', icon: 'fa fa-bus', list: 'content'},
                            // {path: '/vat_accounts', title: 'vat_accounts', icon: 'fa fa-bus', list: 'content'},
                            {path: '/deliverycondition', title: 'Delivery_Condition', icon: 'fa fa-bus', list: 'content'},
                            {path: '/paymentcondition', title: 'Payment_Condition', icon: 'fa fa-credit-card', list: 'content'},
                            {path: '/payment_options', title: 'Payment_Options', icon: 'fa fa-credit-card', list: 'content'},
                            {path: '/vatrate', title: 'Vat_Rate', icon: 'fa fa-exchange', list: 'content'},
                            {path: '/exchangerate', title: 'Exchange_Rate', icon: 'fa fa-exchange', list: 'content'},
                            {path: '/uom', title: 'UOM', icon: 'fa fa-balance-scale', list: 'content'},
                            {path: '/counters', title: 'Counters', icon: 'fa fa-cogs', list: 'content'},
                            {path: '/currencies', title: 'Currencies', icon: 'fa fa-money ', list: 'content'},
                            // {path: '/warehouses', title: 'Warehouses', icon: 'fa fa-home', list: 'content'},
                            {path: '/categories', title: 'Categories', icon: 'fa fa-list-alt', list: 'content'},
                            {path: '/subcategories', title: 'Sub_Categories', icon: 'fa fa-list-alt', list: 'content'},
                            {path: '/subsubcategories', title: 'Sub_Sub_Categories', icon: 'fa fa-list-alt', list: 'content'},
                            {path: '/brands', title: 'Brands', icon: 'fa fa-list-alt', list: 'content'},
                            {path: '/product_dropdown_1', title: 'product_dropdown_1', icon: 'fa fa-list-alt', list: 'content'},
                            {path: '/product_dropdown_2', title: 'product_dropdown_2', icon: 'fa fa-list-alt', list: 'content'},
                            {path: '/client_dropdown_1', title: 'client_dropdown_1', icon: 'fa fa-list-alt', list: 'content'},
                            {path: '/client_dropdown_2', title: 'client_dropdown_2', icon: 'fa fa-list-alt', list: 'content'},
                            
                        ]
                    },
                    {
                        company_tab: true,
                        links: [
                            {path: '/', title: 'Company', icon: 'fa fa-dashboard', fix: 'fix-nav', expand: 'collapse'},
                           {path: '/accounts', title: 'Accounts', icon: 'fa fa-money', list: 'content'},
                            //  {path: '/transfer_accounts', title: 'Transfer_Accounts', icon: 'fa fa-money', list: 'content'},
                            //  {path: '/deposits', title: 'Deposit', icon: 'fa fa-money', list: 'content'},
                            //  {path: '/return_deposits', title: 'Return_Deposit', icon: 'fa fa-money', list: 'content'},
                             {path: '/employees', title: 'Employees', icon: 'fa fa-money', list: 'content'},
                            // {path: '/payroll', title: 'Payroll', icon: 'fa fa-money', list: 'content'},
                        ]
                    },
                    {
                        procurment_tab: true,
                        links: [
                            {path: '', title: 'Procurment & Stock', icon: 'fa fa-cogs', fix: 'fix-nav', expand: 'collapse'}, 
                            // {path: '/products', title: 'Raw Materials', icon: 'fa fa-cubes', list: 'content'},
                            {path: '/raw_material_type', title: 'Raw_Material_Type', icon: 'fa fa-cubes', list: 'content'},
                            // {path: '/receive_orders', title: 'Receive_Orders', icon: 'fa fa-file-text-o', list: 'content'},
                            // {path: '/vendors', title: 'Vendors', icon: 'fa fa-users', list: 'content'},
                            // {path: '/purchase_orders', title: 'Purchase_Orders', icon: 'fa fa-file-text-o', list: 'content'},
                            // {path: '/transfers', title: 'Transfers', icon: 'fa fa-cubes', list: 'content'},
                            // {path: '/products_division', title: 'Products_Division', icon: 'fa fa-cubes', list: 'content'},
                            // {path: '/products_aggregation', title: 'Products_Aggregation', icon: 'fa fa-cubes', list: 'content'},
                            // {path: '/stock_movement', title: 'Stock_Movement', icon: 'fa fa-cubes', list: 'content'},
                            // {path: '/damaged_deteriorate', title: 'Damaged_Deteriorate', icon: 'fa fa-cubes', list: 'content'},
                            
                            // {path: '/goods_issue', title: 'Goods Issue', icon: 'file-o'}
                        ]
                    },
                    {
                        production_tab: true,
                        links: [
                            {path: '', title: 'Production', icon: 'fa fa-cogs', fix: 'fix-nav', expand: 'collapse'}, 
                            {path: '/machines', title: 'Machines', icon: 'fa fa-cubes', list: 'content'},
                            {path: '/finished_product', title: 'Product', icon: 'fa fa-cubes', list: 'content'},
                            {path: '/finished_product_type', title: 'Product_Type', icon: 'fa fa-cubes', list: 'content'},
                            {path: '/job_order', title: 'Job_Orders', icon: 'fa fa-cubes', list: 'content'},
                            {path: '/line_production', title: 'Line_Production', icon: 'fa fa-cubes', list: 'content'},
                            {path: '/packaging', title: 'Packaging', icon: 'fa fa-cubes', list: 'content'},
                            {path: '/delivery_note', title: 'Delivery_Note', icon: 'fa fa-cubes', list: 'content'},
                            {path: '/attributes', title: 'Attributes', icon: 'fa fa-cubes', list: 'content'},
                            // {path: '/machine_attributes', title: 'Machine Attributes', icon: 'fa fa-cubes', list: 'content'},
                            // {path: '/goods_issue', title: 'Goods Issue', icon: 'file-o'}
                        ]
                    },
                    {
                        admin: true,
                        links: [
                            {path: '', title: 'Administrator', icon: 'fa fa-cogs', fix: 'fix-nav', expand: 'collapse'},
                            {path: '/settings', title: 'Settings', icon: 'fa fa-cogs', list: 'content'},
                            {path: '/users', title: 'Users', icon:'fa fa-users', list: 'content'},
                        ]
                    }
                ]
            }
        }
    }
</script>
<style >
/* .Dashboard > a {
    color: #fff !important;
}
.sidebar-list li:nth-child(2) {
  background-color: #3da5ff ;
}
.sidebar-list li:nth-child(2) > a {
  color:#fff;
}
.sidebar-links ul:nth-child(1) > li > a {
  background-color: #233c46 ;
} */

.content {
  display: none;
}

.fix-nav {
    display: block !important;
}
</style>
