<template>
    <div v-if="show">
    <!-- <div v-if="show && (installed_at == model.today_year)"> -->
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">
                    <button v-bind:style="{ 'background-color': app_color,margin: '0 1px'}"  class="btn displayoverview" style="background-color: #3da5ff;color: #fff;" @click="DisplayOverview">
                        Overview 
                    </button>
                     <button
      v-for="(dashboard, index) in dashboard_views"
      :key="dashboard.id"
       :style="{
            backgroundColor: selected_dashboard_id === dashboard.id ? '#2f4f4f !important' : app_color || '#3da5ff',
            color: '#fff', margin: '0 1px'
        }"
      :class="'btn btn-primary ' + dashboard.class"
      @click="dashboardView(dashboard.id)"
    >
      {{ dashboard.name }}
    </button>

     <!-- User Dashboards -->
 <!-- User Dashboards -->
          <button
            v-for="dashboard in user_dashboards"
            :key="dashboard.id"
            :style="{ backgroundColor: selected_dashboard_id === dashboard.id ? '#2f4f4f' : app_color, color: '#fff' }"
            class="btn px-4 py-2 rounded mr-1"
            @click="dashboardCustomView(dashboard.id)"
          >
            {{ dashboard.name }}
          </button>

          <!-- Add Custom Dashboard -->
          <button
            class="btn btn-success px-4 py-2 rounded"
            style="margin-left: 5px; font-weight: bold;"
            @click="createCustomDashboard"
          >
            + Add Dashboard
          </button>
                </span>
                <div class="row" style="padding: 0 1%;"  v-if="model.display_overview == 1 || model.display_overview == 3">
                    <select class="form-control year-select " v-model="model.selected_year"  v-bind:style="{ 'background-color': nav_color}">
                        <option :value="model.today_year" selected>{{model.today_year}}</option>
                        <option :value="model.today_year-1">{{model.today_year-1}}</option>
                        <option :value="model.today_year-2">{{model.today_year-2}}</option>
                        <option value="all">All</option>
                    </select>
                </div>
            </div>
            <div class="panel-overview displayoverview" v-if="model.display_overview == 1 || model.display_overview == 3">
                <!--  -->
                <div class="row" style="padding:30px;">
                    <div class="col-md-3" v-if="model.box_1 == 1">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart report-box__icon text-theme-10"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> 
                                        <div class="ml-auto">
                                            <div class="report-box__indicator tooltip cursor-pointer tooltipstered" v-bind:style="{ 'background-color': nav_color}" style="padding: 6px;"><i class="fa fa-arrow-up"></i></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year">{{model.accounts_receivable_now | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-1">{{model.accounts_receivable_now_1 | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-2">{{model.accounts_receivable_now_2 | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == 'all'">{{model.accounts_receivable | formatMoney(model.currency, false)}}</div>
                                    <div class="text-base text-gray-600 mt-1">Total Clients Due</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3" v-if="model.box_2 == 1">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart report-box__icon text-theme-10"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> 
                                        <div class="ml-auto">
                                            <div class="report-box__indicator tooltip cursor-pointer tooltipstered" v-bind:style="{ 'background-color': app_color}" style="padding: 6px;"><i class="fa fa-arrow-up"></i></div>
                                        </div> 
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year">{{model.client_payment_now | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-1">{{model.client_payment_now_1 | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-2">{{model.client_payment_now_2 | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == 'all'">{{model.client_payment | formatMoney(model.currency, false)}}</div>
                                    <!-- <div class="text-3xl font-bold leading-8 mt-6">{{model.total_revenue - model.total_debit | formatMoney(model.currency, false)}}</div> -->
                                    <div class="text-base text-gray-600 mt-1">Total Clients Payments</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3" v-if="model.box_3 == 1">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart report-box__icon text-theme-10"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> 
                                        <div class="ml-auto">
                                            <div class="report-box__indicator tooltip cursor-pointer tooltipstered" v-bind:style="{ 'background-color': app_color}" style="padding: 6px;"><i class="fa fa-arrow-up"></i></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year">{{model.total_saled_item_now}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-1">{{model.total_saled_item_now_1}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-2">{{model.total_saled_item_now_2}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == 'all'">{{model.total_saled_item}}</div>
                                    <div class="text-base text-gray-600 mt-1">Saled Items</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="model.box_4 == 1">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart report-box__icon text-theme-10"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> 
                                        <div class="ml-auto">
                                            <div class="report-box__indicator tooltip cursor-pointer tooltipstered" v-bind:style="{ 'background-color': app_color}" style="padding: 6px;"><i class="fa fa-arrow-up"></i></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year">{{model.total_saled_item_qty_now}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-1">{{model.total_saled_item_qty_now_1}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-2">{{model.total_saled_item_qty_now_2}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == 'all'">{{model.total_saled_item_qty}}</div>
                                    <div class="text-base text-gray-600 mt-1">Sales Qty</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- row 2 -->
 
                <div class="row" style="padding:30px;">
                    <div class="col-md-3" v-if="model.box_5 == 1" style="display:non">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart report-box__icon text-theme-10"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> 
                                        <div class="ml-auto">
                                            <div class="report-box__indicator tooltip cursor-pointer tooltipstered" v-bind:style="{ 'background-color': app_color}" style="padding: 6px;"><i class="fa fa-arrow-up"></i></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6">{{model.total_debit | formatMoney(model.currency, false)}}</div>
                                    <div class="text-base text-gray-600 mt-1">Total Clients Debit</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="model.box_6 == 1">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i style="font-size: 24px;" class="fa fa fa-file-text-o feather feather-shopping-cart report-box__icon text-theme-10"></i> 
                                        <div class="ml-auto">
                                            <div class="report-box__indicator tooltip cursor-pointer tooltipstered" v-bind:style="{ 'background-color': nav_color}" style="padding: 6px;"><i class="fa fa-arrow-up"></i></div>
                                        </div> 
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year">{{model.open_sales_orders_now}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-1">{{model.open_sales_orders_now_1}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-2">{{model.open_sales_orders_now_2}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == 'all'">{{model.open_sales_orders}}</div>
                                    <div class="text-base text-gray-600 mt-1">Open SalesOrders</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="model.box_7 == 1">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i style="font-size: 24px;" class="fa fa fa-file-text-o feather feather-shopping-cart report-box__icon text-theme-10"></i> 
                                        <div class="ml-auto">
                                            <div class="report-box__indicator tooltip cursor-pointer tooltipstered" v-bind:style="{ 'background-color': app_color}" style="padding: 6px;"><i class="fa fa-arrow-up"></i></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year">{{model.unpaid_invoices_now}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-1">{{model.unpaid_invoices_now_1}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-2">{{model.unpaid_invoices_now_2}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == 'all'">{{model.unpaid_invoices}}</div>
                                    <div class="text-base text-gray-600 mt-1">Unpaid Invoices</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="model.box_8 == 1">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart report-box__icon text-theme-10"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> 
                                        <div class="ml-auto">
                                            <div class="report-box__indicator tooltip cursor-pointer tooltipstered"  v-bind:style="{ 'background-color': app_color}" style="padding: 6px;" ><i class="fa fa-arrow-up"></i></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year">{{model.total_invoice_now | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-1">{{model.total_invoice_now_1 | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-2">{{model.total_invoice_now_2 | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == 'all'">{{model.total_invoice | formatMoney(model.currency, false)}}</div>
                                    <div class="text-base text-gray-600 mt-1">TOTAL INVOICES AMOUNT</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="model.box_9 == 1"> 
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart report-box__icon text-theme-10"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> 
                                        <div class="ml-auto">
                                            <div class="report-box__indicator tooltip cursor-pointer tooltipstered" v-bind:style="{ 'background-color': nav_color}" style="padding: 6px;"><i class="fa fa-arrow-up"></i></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year">{{model.accounts_payable_now | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-1">{{model.accounts_payable_now_1 | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-2">{{model.accounts_payable_now_2 | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == 'all'">{{model.accounts_payable | formatMoney(model.currency, false)}}</div>
                                    <div class="text-base text-gray-600 mt-1">TOTAL VENDOR DUE</div>
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="col-md-3" v-if="model.box_13 == 1">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart report-box__icon text-theme-10"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> 
                                        <div class="ml-auto">
                                            <div class="report-box__indicator tooltip cursor-pointer tooltipstered" v-bind:style="{ 'background-color': app_color}" style="padding: 6px;"><i class="fa fa-arrow-up"></i></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year">{{model.total_invoice_now | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-1">{{model.total_invoice_now_1 | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-2">{{model.total_invoice_now_2 | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == 'all'">{{model.total_invoice | formatMoney(model.currency, false)}}</div>
                                    <div class="text-base text-gray-600 mt-1">TOTAL INVOICES AMOUNT</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- row 3 -->
 
                <div class="row" style="padding:30px;">
                    <div class="col-md-4" v-if="model.box_10 == 1">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart report-box__icon text-theme-10"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> 
                                        <div class="ml-auto">
                                            <div class="report-box__indicator tooltip cursor-pointer tooltipstered" v-bind:style="{ 'background-color': app_color}" style="padding: 6px;"><i class="fa fa-arrow-up"></i></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year">{{model.balanceCurrency1}} {{model.balanceUSD_now | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-1">{{model.balanceCurrency1}} {{model.balanceUSD_now_1 | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-2">{{model.balanceCurrency1}} {{model.balanceUSD_now_2 | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == 'all'">{{model.balanceCurrency1}} {{model.balanceUSD | formatMoney(model.currency, false)}}</div>
                                    <div class="text-base text-gray-600 mt-1">{{model.balanceUSDName}} Balance</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="model.box_11 == 1">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart report-box__icon text-theme-10"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> 
                                        <div class="ml-auto">
                                            <div class="report-box__indicator tooltip cursor-pointer tooltipstered" v-bind:style="{ 'background-color': app_color}" style="padding: 6px;"><i class="fa fa-arrow-up"></i></div>
                                        </div> 
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year">{{model.balanceCurrency2}} {{model.balanceLBP_now | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-1">{{model.balanceCurrency2}} {{model.balanceLBP_now_1 | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-2">{{model.balanceCurrency2}} {{model.balanceLBP_now_2 | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == 'all'">{{model.balanceCurrency2}} {{model.balanceLBP | formatMoney(model.currency, false)}}</div>
                                    <div class="text-base text-gray-600 mt-1">{{model.balanceLBPName}} Balance</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3" v-if="model.box_12 == 1">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart report-box__icon text-theme-10"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> 
                                        <div class="ml-auto">
                                            <div class="report-box__indicator tooltip cursor-pointer tooltipstered" v-bind:style="{ 'background-color': app_color}" style="padding: 6px;"><i class="fa fa-arrow-up"></i></div>
                                        </div> 
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year">{{model.balanceCurrency3}} {{model.balanceEURO_now | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-1">{{model.balanceCurrency3}} {{model.balanceEURO_now_1 | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == model.today_year-2">{{model.balanceCurrency3}} {{model.balanceEURO_now_2 | formatMoney(model.currency, false)}}</div>
                                    <div class="text-3xl font-bold leading-8 mt-6" v-if="model.selected_year == 'all'">{{model.balanceCurrency3}} {{model.balanceEURO | formatMoney(model.currency, false)}}</div>
                                    <div class="text-base text-gray-600 mt-1">{{model.balanceEUROName}} Balance</div>
                                </div>
                            </div>
                        </div>
                    </div>

                   
                    <div class="col-md-4" v-if="model.box_14 == 1">
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart report-box__icon text-theme-10"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg> 
                                        <div class="ml-auto">
                                            <div class="report-box__indicator tooltip cursor-pointer tooltipstered" v-bind:style="{ 'background-color': app_color}" style="padding: 6px;"><i class="fa fa-arrow-up"></i></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6">{{model.total_revenue - model.total_expense | formatMoney(model.currency, false)}}</div>
                                    <div class="text-base text-gray-600 mt-1" style="font-size: 13px;">TOTAL REVENUE<small style="">(Revenue - Expense)</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                   
                 
                
            </div>
        </div>
        <div class="row" style="padding:0 15px;">
            <div class="col col-6 displayoverview"  v-if="model.display_overview == 1 || model.display_overview == 3">
                <div class="panel"  v-if="model.chart_1 == 1">
                    <div class="panel-heading">
                        <span class="panel-title">Income vs Expense</span>
                    </div>
                    <div class="panel-body">
                        <line-chart :datasets="datasets" :labels="labels"></line-chart>
                    </div>
                </div>
            </div>
            <div class="col col-6 displayoverview"  v-if="model.display_overview == 1 || model.display_overview == 3">
                <div class="panel" v-if="model.chart_2 == 1">
                    <div class="panel-heading">
                        <span class="panel-title">Accounts</span>
                    </div>
                    <div class="panel-body">
                        <pie-chart :datasets_account="datasets_account" :labels_account="labels_account"></pie-chart>
                    </div>
                </div>
            </div>
        </div>
       
           <!-- Iframe Views -->
        <div v-for="(dashboard, index) in dashboard_views" :key="dashboard.id">
        <iframe
            v-if="selected_dashboard_id === dashboard.id"
            :src="dashboard.link"
            style="width: 100%;min-height: 900px !important;"
            frameborder="0"
        ></iframe>
        </div>
      

            <!-- Custom Dashboard iframe -->
    <div v-if="showCustomDashboard">
      <iframe
        :src="`/dashboard/custom_layout/${userDashboardId}`"
        style="width: 100%; min-height: 900px; border: none;"
      ></iframe>
    </div>

        <div v-if="model.display_overview == 0">
        <div class="col col-12"  style="margin: 35% auto 0 auto;">
            <p style="color:red;text-align:center;">
                <a href="/">
                        <img src="images/perceive.png" class="navbar-lo" style="width:20%;">
                </a>
            </p>
            
            <p style="color:red;text-align:center;"><strong>Licensed For {{company_name}}, Feel free and contact Us</strong></p>
            <p style="color:#000;text-align:center;"><strong><a v-bind:href=" `mailto:${license_email}` ">{{license_email}}</a></strong></p>
        </div>
    </div>
    </div>
   
    
    <!-- <div v-else>
        <div class="col col-12"  style="margin: 25% auto;">
            <p style="color:red;text-align:center;"><strong>License Expired, Feel free and contact Us</strong></p>
            <p style="color:#000;text-align:center;"><strong><a href="mailto:it@propack.me">it@propack.me</a></strong></p>
        </div>
    </div> -->
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get, byMethod } from '../../lib/api'
    import LineChart from '../../components/charts/Line.vue'
    import PieChart from '../../components/charts/Pie.vue'
    export default {
        computed:{
            base_currency() {
                return window.apex.base_currency
            },
            first_currency(){
                return window.apex.first_currency
            },
            second_currency(){
                return window.apex.second_currency
            },
            first_currency_decimal(){
                 return window.apex.first_currency_decimal
            },
            second_currency_decimal(){
                 return window.apex.second_currency_decimal
            },
            installed_at(){
                 return window.apex.installed_at
            },
            disable_second_currency(){
                 return window.apex.disable_second_currency
            },
            logo_name() {
                return window.apex.logo_name
            },
            company_name() {
                return window.apex.company_name
            },
            company_type() {
                return window.apex.company_type
            },
            app_color() {
                return window.apex.app_color
            },
            license_email() {
                return window.apex.license_email
            },
            nav_color() {
                return window.apex.nav_color
            },
            dashboard_views(){
                return window.apex.dashboard_views
            },
        },
        components: {LineChart,PieChart},
        data() {
            return {
                show: false,
                      showCustomDashboard: false,
      selected_dashboard_id: null,
      userDashboardId: null,
                datasets: [],
                labels: [],
                datasets_account: [],
                labels_account: [],
                 user_dashboards: [],
                model: {
                    top_unpaid_invoices: [],
                    top_sales_order: [],
                    top_purchase_order: [],
                    currency: {}
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/dashboard`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/dashboard`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
              DisplayOverview() {
      this.model.display_overview = 1;
      this.selected_dashboard_id = null;
      this.showCustomDashboard = false;
    },
    dashboardView(id) {
      this.selected_dashboard_id = id;
      this.model.display_overview = 0;
      this.showCustomDashboard = false;
    },
    dashboardCustomView(id) {
      this.userDashboardId = id;
       this.model.display_overview = 0;
      this.showCustomDashboard = true;
      this.selected_dashboard_id = null;
    },
            
    async createCustomDashboard() {
      const name = prompt("Enter dashboard name:");
      if (!name) return;
      try {
        const res = await byMethod('post', `/api/dashboard/custom`, { name });
        const newDashboard = res.data.dashboard;
        this.user_dashboards.push(newDashboard);
        this.dashboardCustomView(newDashboard.id);
      } catch (error) {
        alert("Failed to create dashboard.");
        console.error(error);
      }
    },
            setData(res) {
                this.$title.set('Dashboard')
                Vue.set(this.$data, 'model', res.data.data)
                Vue.set(this.$data, 'labels', res.data.income_expense_chart.labels)
                Vue.set(this.$data, 'datasets', res.data.income_expense_chart.datasets)
                Vue.set(this.$data, 'labels_account', res.data.accounts_chart.labels_account)
                Vue.set(this.$data, 'datasets_account', res.data.accounts_chart.datasets_account)
                
                Vue.set(this.$data, 'user_dashboards', res.data.user_dashboards || []);


                this.$bar.finish()
                this.show = true
            },
            
        }
    }
</script>
<style>
    .year-select {
        color: #fff;
        border-radius: 7px;
        margin: 0px 20px;
    }
</style>