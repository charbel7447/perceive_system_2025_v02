<template>
    <div class="sidebarx" id="sidebar_reports" style="margin: 0 15px;">
        <div class="sidebar-innerr" v-if="(installed_at == fiscal_year)">
            <div class="sidebar-linkss">
                <ul class="sidebar-list" >
                    <li class="sidebar-break"></li>
                    <li style="padding:5px 10px;">
                        <a ><b>Reports</b></a>
                    </li>

                     <li style="padding:5px 10px;" v-for="(report, index) in reports_views">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;
                        <a :class='`${report.class}`'  :href='"/" + `${report.link}`' target="_self">
                            <b>{{report.name}}</b>
                        </a>
                    </li>

                    <li style="padding:5px 10px;" class="Trial_balance_Report">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/trial_balance_report" target="_self" ><b>Trial Balance Report</b></a>
                    </li>
                    <li style="padding:5px 10px;">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/price_changes_report/create" target="_self" ><b>Price Changes</b></a>
                    </li>
                    
                    <li style="padding:5px 10px;">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/cost_changes_report/create" target="_self" ><b>Cost Changes</b></a>
                    </li>
                    
                    <li style="padding:5px 10px;">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/sales_orders_report/create" target="_self" ><b>Sales Orders</b></a>
                    </li>
                    
                    <li style="padding:5px 10px;">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/invoices_report/create" target="_self" ><b>Sales Invoice</b></a>
                    </li>
                    
                    <li style="padding:5px 10px;">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/quotations_report/create" target="_self" ><b>Quotations</b></a>
                    </li>
                    <li style="padding:5px 10px;">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/receive_orders_report/create" target="_self" ><b>Receive Orders</b></a>
                    </li>
                    <li style="padding:5px 10px;">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/purchase_orders_report/create" target="_self" ><b>Purchase Orders</b></a>
                    </li>
                   

                    <li style="padding:5px 10px;">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/products_report/create" target="_self" ><b>Products</b></a>
                    </li>
               
                    <li style="padding:5px 10px;">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/products_catalogue/create" target="_self" ><b>Products Catalogue Landscape</b></a>
                    </li>
                    <li style="padding:5px 10px;">   
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/products_catalogue/show" target="_self" ><b>Products Catalogue Portrait</b></a>
                    </li>
                    <li style="padding:5px 10px;">   
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/statement" target="_self" ><b>Client SOA</b></a>
                    </li>
                    <li style="padding:5px 10px;">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/clients_balance_report/create" target="_self" ><b>Client Balance</b></a>
                    </li>
                    <!-- <li style="padding:5px 10px;">   
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/clients_balance_report/show" target="_self" ><b>Client Balance Excel</b></a>
                    </li> -->
                    <li style="padding:5px 10px;">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/seller_payments_docs_report/create" target="_self" ><b>Sellers Payments</b></a>
                    </li>
                
                    <li style="padding:5px 10px;">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/payment_options_report/create" target="_self" ><b>Payment Options</b></a>
                    </li>
                

                    
                    <li class="sidebar-break"></li>
                    <li class="import_tools" style="padding:5px 10px;background-color: rgb(136 194 65 / 80%x);color:#fff">
                        <a style="color: #fff;" ><b>Import Tools</b></a>
                    </li>
                    <li style="padding:5px 10px;">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/import_tools" target="_blank" ><b>Import Tools</b></a>
                    </li>
                    <!-- <li class="sidebar-break"></li>
                    <li style="padding:5px 10px;background-color: rgb(136 194 65 / 80%);color:#fff">
                        <a style="color: #fff;" ><b>Backup Tools</b></a>
                    </li>
                    <li style="padding:5px 10px;">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/run_backup" target="_blank" ><b>Run Backup</b></a>
                    </li>
                    <li style="padding:5px 10px;">
                        <i class="fa fa-arrow-right gray"></i>&nbsp;&nbsp;<a href="/backup_list" target="_blank" ><b>Backup List</b></a>
                    </li> -->
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
            reports_views(){
                return window.apex.reports_views
            },
        },
        data() {
            return {
                lists: [
                  
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

.li-report {
    padding: 5px 10px;
    background: #ddddddb8;
    border-bottom: 1px solid;
}
</style>
