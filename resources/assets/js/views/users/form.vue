<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} User</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name">
                            <error-text :error="error.name"></error-text>
                        </div>
                        <div class="form-group">
                            <label>Job Title</label>
                            <input type="text" class="form-control" v-model="form.title">
                            <error-text :error="error.title"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>
                                Mobile Number
                                <small>(optional)</small>
                            </label>
                            <input type="text" class="form-control" v-model="form.mobile_number">
                            <error-text :error="error.mobile_number"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                Telephone
                                <small>(optional)</small>
                            </label>
                            <input type="text" class="form-control" v-model="form.telephone">
                            <error-text :error="error.telephone"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                Extension
                                <small>(optional)</small>
                            </label>
                            <input type="text" class="form-control" v-model="form.extension">
                            <error-text :error="error.extension"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>
                                Is Admin
                            </label>
                            <select class="form-control" v-model="form.is_admin">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <error-text :error="error.is_admin"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                Is Active
                            </label>
                            <select class="form-control" v-model="form.is_active">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <error-text :error="error.is_active"></error-text>
                        </div>
                         <div class="form-group">
                            <label>
                                Manager
                            </label>
                            <typeahead required :initial="form.manager"
                                :url="userURL"
                                @input="onManagerUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.manager_id"></error-text>
                        </div>
                          
                    </div>
                </div>
                <hr>
                <hr>
                <h4 style="margin-bottom: 20px;">Permissions</h4>
                <!-- Settings Tabs  -->
                <div class="row" style="background: #ddd;border: 1px solid;">
                    <div class="col col-12">
                            <div class="form-group">
                                <input  class="permission-title-group" type="checkbox" value="1" v-model="form.is_dashboard">
                                <label>Dashboard Tab</label>

                                <input  class="permission-title-group" type="checkbox" value="1" v-model="form.is_displayoverview_tab">
                                <label>Overview Home</label>

                                <input  class="permission-title-group" type="checkbox" value="1" v-model="form.is_displaysales_tab">
                                <label>Overview Sales</label>

                                <input  class="permission-title-group" type="checkbox" value="1" v-model="form.is_displayaccounting_tab">
                                <label>Overview Accounting</label>

                                <input  class="permission-title-group" type="checkbox" value="1" v-model="form.is_displaystock_tab">
                                <label>Overview Stock</label>

                                <input  class="permission-title-group" type="checkbox" value="1" v-model="form.is_displayproduction_tab">
                                <label>Overview Production</label>
          
                            </div>


                            
                            <div class="form-group">
                                <input  class="permission-title-group" type="checkbox" value="1" v-model="form.is_settings_tab">
                                <label style="float: left;margin: 0 20px 0 0;">Settings Tab</label>
                                <button v-if="form.is_settings_tab == 1" class="btn btn-sm" @click="checkSettings">
                                        Check All
                                </button>
                                <button v-if="form.is_settings_tab == 1" class="btn btn-sm" @click="uncheckSettings">
                                        Un-Check All
                                </button>
                            </div>
                            <div v-if="form.is_settings_tab == 1">
                                <div class="row">
                                  <div class="col col-12">
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_deliverycondition_tab">
                                        <label>Delivery Conditions</label>
                                        <div v-if="form.is_deliverycondition_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_deliverycondition_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_deliverycondition_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_deliverycondition_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_deliverycondition_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_paymentcondition_tab">
                                        <label>Payment Conditions</label>
                                        <div v-if="form.is_paymentcondition_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_paymentcondition_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_paymentcondition_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_paymentcondition_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_paymentcondition_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_exchangerate_tab">
                                        <label>Exchange Rate</label>
                                        <div v-if="form.is_exchangerate_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_exchangerate_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_exchangerate_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_exchangerate_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_exchangerate_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_uom_tab">
                                        <label>U.O.M</label>
                                        <div v-if="form.is_uom_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_uom_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_uom_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_uom_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_uom_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_counters_tab">
                                        <label>Counters</label>
                                        <div v-if="form.is_counters_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_counters_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_counters_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_counters_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_counters_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                   </div>
                                </div>
                                <div class="row">
                                   <div class="col col-12">
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_currencies_tab">
                                        <label>Currencies</label>
                                        <div v-if="form.is_currencies_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_currencies_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_currencies_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_currencies_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_currencies_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_warehouses_tab">
                                        <label>Warehouses</label>
                                        <div v-if="form.is_warehouses_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_warehouses_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_warehouses_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_warehouses_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_warehouses_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_categories_tab">
                                        <label>Categories</label>
                                        <div v-if="form.is_categories_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_categories_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_categories_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_categories_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_categories_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_subcategories_tab">
                                        <label>Sub-Categories</label>
                                        <div v-if="form.is_subcategories_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_subcategories_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_subcategories_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_subcategories_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_subcategories_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                   </div>
                                </div>
                            </div>
                    </div>
                </div>
                <!-- Company TAB -->
                <div class="row" style="background: #ddd;border: 1px solid;">
                    <div class="col col-12">
                            <div class="form-group">
                                <input  class="permission-title-group" type="checkbox" value="1" v-model="form.is_company_tab">
                                <label style="float: left;margin: 0 20px 0 0;">Company Tab</label>
                                <button v-if="form.is_company_tab == 1" class="btn btn-sm" @click="checkCompany">
                                        Check All
                                </button>
                                <button v-if="form.is_company_tab == 1" class="btn btn-sm" @click="uncheckCompany">
                                        Un-Check All
                                </button>
                            </div>
                            <div v-if="form.is_company_tab == 1">
                                <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_accounts_tab">
                                    <label>Accounts</label>
                                    <div v-if="form.is_accounts_tab">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_accounts_create">
                                        <label>Create</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_accounts_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_accounts_delete">
                                        <label>Delete</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_accounts_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_transferaccounts_tab">
                                    <label>Transfer Accounts</label>
                                    <div v-if="form.is_transferaccounts_tab">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_transferaccounts_create">
                                        <label>Create</label>
                                        <!-- <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_transferaccounts_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_transferaccounts_delete">
                                        <label>Delete</label> -->
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_transferaccounts_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                 <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_deposit_tab">
                                    <label>Deposit</label>
                                    <div v-if="form.is_deposit_tab">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_deposit_create">
                                        <label>Create</label>
                                        <!-- <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_deposit_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_deposit_delete">
                                        <label>Delete</label> -->
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_deposit_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                 <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_returndeposit_tab">
                                    <label>Return Deposit</label>
                                    <div v-if="form.is_returndeposit_tab">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_returndeposit_create">
                                        <label>Create</label>
                                        <!-- <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_returndeposit_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_returndeposit_delete">
                                        <label>Delete</label> -->
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_returndeposit_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                 <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_employees_tab">
                                    <label>Employees</label>
                                    <div v-if="form.is_employees_tab">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_employees_create">
                                        <label>Create</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_employees_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_employees_delete">
                                        <label>Delete</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_employees_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                 <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_payroll_tab">
                                    <label>Payroll</label>
                                    <div v-if="form.is_payroll_tab">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_payroll_create">
                                        <label>Create</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_payroll_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_payroll_delete">
                                        <label>Delete</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_payroll_view">
                                        <label>View</label>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <!-- Sales TAB -->
                <div class="row" style="background: #ddd;border: 1px solid;">
                    <div class="col col-12">
                            <div class="form-group">
                                <input  class="permission-title-group" type="checkbox" value="1" v-model="form.is_sales_tab">
                                <label style="float: left;margin: 0 20px 0 0;">Sales Tab</label>
                                <button v-if="form.is_sales_tab == 1" class="btn btn-sm" @click="checkSales">
                                        Check All
                                </button>
                                <button v-if="form.is_sales_tab == 1" class="btn btn-sm" @click="uncheckSales">
                                        Un-Check All
                                </button>
                            </div>
                            <div v-if="form.is_sales_tab == 1">
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clients_tab">
                                    <label>Clients</label>
                                    <div v-if="form.is_clients_tab">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clients_create">
                                        <label>Create</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clients_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clients_delete">
                                        <label>Delete</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clients_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_quotations_tab">
                                    <label>Quotations</label>
                                    <div v-if="form.is_quotations_tab">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_quotations_create">
                                        <label>Create</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_quotations_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_quotations_delete">
                                        <label>Delete</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_quotations_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_salesorders_tab">
                                    <label>Sales Orders</label>
                                    <div v-if="form.is_salesorders_tab">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_salesorders_create">
                                        <label>Create</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_salesorders_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_salesorders_delete">
                                        <label>Delete</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_salesorders_view">
                                        <label>View</label>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <!-- Accounting TAB  is_accounting_tab -->
                <div class="row" style="background: #ddd;border: 1px solid;">
                    <div class="col col-12">
                            <div class="form-group">
                                <input  class="permission-title-group" type="checkbox" value="1" v-model="form.is_accounting_tab">
                                <label style="float: left;margin: 0 20px 0 0;">Accounting Tab</label>
                                <button v-if="form.is_accounting_tab == 1" class="btn btn-sm" @click="checkAccounting">
                                        Check All
                                </button>
                                <button v-if="form.is_accounting_tab == 1" class="btn btn-sm" @click="uncheckAccounting">
                                        Un-Check All
                                </button>
                            </div>
                            <div v-if="form.is_accounting_tab == 1">
                                <div class="row">
                                  <div class="col col-12">
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_advancepayments_tab">
                                        <label>Advance Payments</label>
                                        <div v-if="form.is_advancepayments_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_advancepayments_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_advancepayments_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_advancepayments_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_advancepayments_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_invoices_tab">
                                        <label>Client Invoices</label>
                                        <div v-if="form.is_invoices_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_invoices_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_invoices_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_invoices_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_invoices_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_creditnotes_tab">
                                        <label>Credit Notes</label>
                                        <div v-if="form.is_creditnotes_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_creditnotes_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_creditnotes_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_creditnotes_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_creditnotes_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_debitnotes_tab">
                                        <label>Debit Notes</label>
                                        <div v-if="form.is_debitnotes_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_debitnotes_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_debitnotes_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_debitnotes_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_debitnotes_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clientpayments_tab">
                                        <label>Client Payments</label>
                                        <div v-if="form.is_clientpayments_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clientpayments_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clientpayments_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clientpayments_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clientpayments_view">
                                            <label>View</label>
                                        </div>
                                    </div>

                                     <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_trialbalance_tab">
                                        <label>Trial Balance</label>
                                    </div>
                                   </div>
                                </div>
                                <div class="row">
                                   <div class="col col-12">
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clientsoa_tab">
                                        <label>Client S.O.A</label>
                                        <div v-if="form.is_clientsoa_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clientsoa_create">
                                            <label>Create</label>
                                            <!-- <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clientsoa_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clientsoa_delete">
                                            <label>Delete</label> -->
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clientsoa_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendorexpenses_tab">
                                        <label>Vendor Expenses</label>
                                        <div v-if="form.is_vendorexpenses_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendorexpenses_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendorexpenses_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendorexpenses_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendorexpenses_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_bills_tab">
                                        <label>Vendor Bills</label>
                                        <div v-if="form.is_bills_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_bills_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_bills_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_bills_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_bills_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendorpayments_tab">
                                        <label>Vendor Payments</label>
                                        <div v-if="form.is_vendorpayments_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendorpayments_create">
                                            <label>Create</label>
                                            <!-- <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendorpayments_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendorpayments_delete">
                                            <label>Delete</label> -->
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendorpayments_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendorsoa_tab">
                                        <label>Vendor SOA</label>
                                        <div v-if="form.is_vendorsoa_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendorsoa_create">
                                            <label>Create</label>
                                            <!-- <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendorsoa_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendorsoa_delete">
                                            <label>Delete</label> -->
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendorsoa_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_journalvoucher_tab">
                                        <label>Journal Voucher</label>
                                        <div v-if="form.is_journalvoucher_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_journalvoucher_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_journalvoucher_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_journalvoucher_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_journalvoucher_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                   
                                   </div>
                                </div>
                            </div>
                    </div>
                </div>
                <!-- Procurment & Stock Tab is_procurment_tab -->
                <div class="row" style="background: #ddd;border: 1px solid;">
                    <div class="col col-12">
                            <div class="form-group">
                                <input  class="permission-title-group" type="checkbox" value="1" v-model="form.is_procurment_tab">
                                <label  style="float: left;margin: 0 20px 0 0;">Procurment & Stock Tab</label>
                                <button v-if="form.is_procurment_tab == 1" class="btn btn-sm" @click="checkProcurment">
                                        Check All
                                </button>
                                <button v-if="form.is_procurment_tab == 1" class="btn btn-sm" @click="uncheckProcurment">
                                        Un-Check All
                                </button>
                            </div>
                            <div v-if="form.is_procurment_tab == 1">
                                <div class="row">
                                  <div class="col col-12">
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_products_tab">
                                        <label>Products</label>
                                        <div v-if="form.is_products_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_products_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_products_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_products_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_products_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_receiveorders_tab">
                                        <label>Receive Orders</label>
                                        <div v-if="form.is_receiveorders_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_receiveorders_create">
                                            <label>Create</label>
                                            <!-- <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_receiveorders_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_receiveorders_delete">
                                            <label>Delete</label> -->
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_receiveorders_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendors_tab">
                                        <label>Vendors</label>
                                        <div v-if="form.is_vendors_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendors_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendors_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendors_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_vendors_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_purchaseorders_tab">
                                        <label>Purchase Orders</label>
                                        <div v-if="form.is_purchaseorders_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_purchaseorders_create">
                                            <label>Create</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_purchaseorders_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_purchaseorders_delete">
                                            <label>Delete</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_purchaseorders_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_transfers_tab">
                                        <label>Stock Transfers</label>
                                        <div v-if="form.is_transfers_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_transfers_create">
                                            <label>Create</label>
                                            <!-- <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_transfers_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_transfers_delete">
                                            <label>Delete</label> -->
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_transfers_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                   </div>
                                </div>
                                <div class="row">
                                   <div class="col col-12">
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_productsdivision_tab">
                                        <label>Products Division</label>
                                        <div v-if="form.is_productsdivision_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_productsdivision_create">
                                            <label>Create</label>
                                            <!-- <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_productsdivision_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_productsdivision_delete">
                                            <label>Delete</label> -->
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_productsdivision_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_productsaggregation_tab">
                                        <label>Products Aggregation</label>
                                        <div v-if="form.is_productsaggregation_tab">
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_productsaggregation_create">
                                            <label>Create</label>
                                            <!-- <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_productsaggregation_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_productsaggregation_delete">
                                            <label>Delete</label> -->
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_productsaggregation_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_stockmovement_tab">
                                        <label>Stock Movement</label>
                                        <div v-if="form.is_stockmovement_tab">
                                            <!-- <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_productsaggregation_edit">
                                            <label>Edit</label>
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_productsaggregation_delete">
                                            <label>Delete</label> -->
                                            <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_stockmovement_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                   </div>
                                </div>
                                
                            </div>
                    </div>
                </div>
                <!-- Production TAB -->
                <div class="row" style="background: #ddd;border: 1px solid;">
                    <div class="col col-12">
                            <div class="form-group">
                                <input  class="permission-title-group" type="checkbox" value="1" v-model="form.is_production_tab">
                                <label style="float: left;margin: 0 20px 0 0;">Production Tab</label>
                                <button v-if="form.is_production_tab == 1" class="btn btn-sm" @click="checkProduction">
                                        Check All
                                </button>
                                <button v-if="form.is_production_tab == 1" class="btn btn-sm" @click="uncheckProduction">
                                        Un-Check All
                                </button>
                            </div>
                            <div v-if="form.is_production_tab == 1">
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clients_tab">
                                    <label>Clients</label>
                                    <div v-if="form.is_clients_tab">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clients_create">
                                        <label>Create</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clients_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clients_delete">
                                        <label>Delete</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_clients_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_quotations_tab">
                                    <label>Quotations</label>
                                    <div v-if="form.is_quotations_tab">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_quotations_create">
                                        <label>Create</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_quotations_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_quotations_delete">
                                        <label>Delete</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_quotations_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_salesorders_tab">
                                    <label>Sales Orders</label>
                                    <div v-if="form.is_salesorders_tab">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_salesorders_create">
                                        <label>Create</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_salesorders_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_salesorders_delete">
                                        <label>Delete</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_salesorders_view">
                                        <label>View</label>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

                <hr>
                <!-- Shipping TAB -->
                <div class="row" style="background: #ddd;border: 1px solid;">
                    <div class="col col-12">
                            <div class="form-group">
                                <input  class="permission-title-group" type="checkbox" value="1" v-model="form.is_shipping_tab">
                                <label style="float: left;margin: 0 20px 0 0;">Shipping Tab</label>
                                <button v-if="form.is_shipping_tab == 1" class="btn btn-sm" @click="checkShipping">
                                        Check All
                                </button>
                                <button v-if="form.is_shipping_tab == 1" class="btn btn-sm" @click="uncheckShipping">
                                        Un-Check All
                                </button>
                            </div>
                            <div v-if="form.is_shipping_tab == 1">
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Define_Shippers_tab">
                                    <label>Define Shippers</label>
                                    <div v-if="form.is_Define_Shippers_tab  == true || form.is_Define_Shippers_tab  == 1 ">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Define_Shippers_create">
                                        <label>Create</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Define_Shippers_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Define_Shippers_delete">
                                        <label>Delete</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Define_Shippers_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shipments_tab">
                                    <label>Create Shipments</label>
                                    <div v-if="form.is_Shipments_tab  == true || form.is_Shipments_tab  == 1 ">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shipments_create">
                                        <label>Create</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shipments_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shipments_delete">
                                        <label>Delete</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shipments_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Receive_Shipments_tab">
                                    <label>Receive_Shipments</label>
                                    <div v-if="form.is_Receive_Shipments_tab  == true || form.is_Receive_Shipments_tab  == 1 ">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Receive_Shipments_create">
                                        <label>Create</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Receive_Shipments_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Receive_Shipments_delete">
                                        <label>Delete</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Receive_Shipments_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shippers_Bills_tab">
                                    <label>Shippers_Bills</label>
                                    <div v-if="form.is_Shippers_Bills_tab  == true || form.is_Shippers_Bills_tab  == 1 ">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shippers_Bills_create">
                                        <label>Create</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shippers_Bills_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shippers_Bills_delete">
                                        <label>Delete</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shippers_Bills_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shippers_Payments_tab">
                                    <label>Shippers_Payments</label>
                                    <div v-if="form.is_Shippers_Payments_tab  == true || form.is_Shippers_Payments_tab  == 1 ">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shippers_Payments_create">
                                        <label>Create</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shippers_Payments_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shippers_Payments_delete">
                                        <label>Delete</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shippers_Payments_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shippers_SOA_tab">
                                    <label>Shippers_SOA</label>
                                    <div v-if="form.is_Shippers_SOA_tab  == true || form.is_Shippers_SOA_tab  == 1 ">
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shippers_SOA_create">
                                        <label>Create</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shippers_SOA_edit">
                                        <label>Edit</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shippers_SOA_delete">
                                        <label>Delete</label>
                                        <input class="permission-title-group" type="checkbox" value="1" v-model="form.is_Shippers_SOA_view">
                                        <label>View</label>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" v-model="form.email">
                            <error-text :error="error.email"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>
                                Password
                                <small v-if="mode == 'edit'">Optional</small>
                            </label>
                            <input type="password" class="form-control" v-model="form.password">
                            <error-text :error="error.password"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>
                                Confirm Password
                                <small v-if="mode == 'edit'">Optional</small>
                            </label>
                            <input type="password" class="form-control" v-model="form.password_confirmation">
                            <error-text :error="error.password_confirmation"></error-text>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-4">
                        <div class="form-group">
                            <label>
                                <span>Email Signature</span>
                                <a @click.stop="generate">Generate</a>
                            </label>
                            <textarea class="form-control" v-model="form.email_signature">
                            </textarea>
                            <error-text :error="error.email_signature"></error-text>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <spinner v-if="isProcessing"></spinner>
                <div class="btn-group" v-else>
                    <button :disabled="isProcessing" @click="save" class="btn btn-primary">
                        Save
                    </button>
                    <button :disabled="isProcessing" v-if="!isEdit"
                        @click="saveAndNew" class="btn btn-secondary">
                        Save and New
                    </button>
                    <router-link :disabled="isProcessing" :to="`${resource}/${$route.params.id}`"
                        class="btn" v-if="isEdit">
                        Cancel
                    </router-link>
                    <router-link :disabled="isProcessing" :to="`${resource}`"
                        class="btn" v-else>
                        Cancel
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import ErrorText from '../../components/form/ErrorText.vue'
    import Typeahead from '../../components/form/Typeahead.vue'
    import Spinner from '../../components/loading/Spinner.vue'
    import { get, byMethod } from '../../lib/api'
    import { form } from '../../lib/mixins'

    function initializeUrl (to) {
        let urls = {
            'create': `/api/users/create`,
            'edit': `/api/users/${to.params.id}/edit`,
            'clone': `/api/users/${to.params.id}/edit?mode=clone`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        components: { ErrorText, Typeahead, Spinner },
        mixins: [ form ],
        data () {
            return {
                resource: '/users',
                store: '/api/users',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created user!',
                currencyURL: '/api/search/currencies',
                userURL: '/api/search/users'
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/users/${this.$route.params.id}`
                this.message = 'You have successfully updated user!'
                this.method = 'PUT'
                this.title = 'Edit'
            }
        },
        beforeRouteEnter(to, from, next) {

            get(initializeUrl(to))
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false

            get(initializeUrl(to))
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            save() {
                this.submit((data) => {
                    this.success()
                    if(this.form.id === window.apex.user.id) {
                        window.apex.user.name = this.form.name
                    }
                    this.$router.push(`${this.resource}/${data.id}`)
                })
            },
             onManagerUpdate(e) {
                const manager = e.target.value
                // vendor
                Vue.set(this.form, 'manager', manager)
                Vue.set(this.form, 'manager_id', manager.id)
            },
            checkSettings(){
                this.form.is_deliverycondition_tab = 1
                this.form.is_paymentcondition_tab = 1
                this.form.is_exchangerate_tab = 1
                this.form.is_uom_tab = 1
                this.form.is_counters_tab = 1
                this.form.is_currencies_tab = 1
                this.form.is_warehouses_tab = 1
                this.form.is_categories_tab = 1
                this.form.is_subcategories_tab = 1
                this.form.is_deliverycondition_create = 1
                this.form.is_deliverycondition_edit = 1
                this.form.is_deliverycondition_delete = 1
                this.form.is_deliverycondition_view = 1
                this.form.is_paymentcondition_create = 1
                this.form.is_paymentcondition_edit = 1
                this.form.is_paymentcondition_delete = 1
                this.form.is_paymentcondition_view = 1
                this.form.is_exchangerate_create = 1
                this.form.is_exchangerate_edit = 1
                this.form.is_exchangerate_delete = 1
                this.form.is_exchangerate_view = 1
                this.form.is_uom_create = 1
                this.form.is_uom_edit = 1
                this.form.is_uom_delete = 1
                this.form.is_uom_view = 1
                this.form.is_counters_create = 1
                this.form.is_counters_edit = 1
                this.form.is_counters_delete = 1
                this.form.is_counters_view = 1
                this.form.is_currencies_create = 1
                this.form.is_currencies_edit = 1
                this.form.is_currencies_delete = 1
                this.form.is_currencies_view = 1
                this.form.is_warehouses_create = 1
                this.form.is_warehouses_edit = 1
                this.form.is_warehouses_delete = 1
                this.form.is_warehouses_view = 1
                this.form.is_categories_create = 1
                this.form.is_categories_edit = 1
                this.form.is_categories_delete = 1
                this.form.is_categories_view = 1
                this.form.is_subcategories_create = 1
                this.form.is_subcategories_edit = 1
                this.form.is_subcategories_delete = 1
                this.form.is_subcategories_view = 1
            },
            uncheckSettings(){
                this.form.is_deliverycondition_tab = 0
                this.form.is_paymentcondition_tab = 0
                this.form.is_exchangerate_tab = 0
                this.form.is_uom_tab = 0
                this.form.is_counters_tab = 0
                this.form.is_currencies_tab = 0
                this.form.is_warehouses_tab = 0
                this.form.is_categories_tab = 0
                this.form.is_subcategories_tab = 0
                this.form.is_deliverycondition_create = 0
                this.form.is_deliverycondition_edit = 0
                this.form.is_deliverycondition_delete = 0
                this.form.is_deliverycondition_view = 0
                this.form.is_paymentcondition_create = 0
                this.form.is_paymentcondition_edit = 0
                this.form.is_paymentcondition_delete = 0
                this.form.is_paymentcondition_view = 0
                this.form.is_exchangerate_create = 0
                this.form.is_exchangerate_edit = 0
                this.form.is_exchangerate_delete = 0
                this.form.is_exchangerate_view = 0
                this.form.is_uom_create = 0
                this.form.is_uom_edit = 0
                this.form.is_uom_delete = 0
                this.form.is_uom_view = 0
                this.form.is_counters_create = 0
                this.form.is_counters_edit = 0
                this.form.is_counters_delete = 0
                this.form.is_counters_view = 0
                this.form.is_currencies_create = 0
                this.form.is_currencies_edit = 0
                this.form.is_currencies_delete = 0
                this.form.is_currencies_view = 0
                this.form.is_warehouses_create = 0
                this.form.is_warehouses_edit = 0
                this.form.is_warehouses_delete = 0
                this.form.is_warehouses_view = 0
                this.form.is_categories_create = 0
                this.form.is_categories_edit = 0
                this.form.is_categories_delete = 0
                this.form.is_categories_view = 0
                this.form.is_subcategories_create = 0
                this.form.is_subcategories_edit = 0
                this.form.is_subcategories_delete = 0
                this.form.is_subcategories_view = 0
            },
            checkCompany() {
               this.form.is_accounts_tab = 1,
               this.form.is_transferaccounts_tab = 1,
               this.form.is_deposit_tab = 1,
               this.form.is_returndeposit_tab = 1,
               this.form.is_employees_tab = 1,
               this.form.is_payroll_tab = 1,
               this.form.is_accounts_create = 1,
               this.form.is_accounts_edit = 1,
               this.form.is_accounts_delete = 1,
               this.form.is_accounts_view = 1,
               this.form.is_transferaccounts_create = 1,
               this.form.is_transferaccounts_edit = 1,
               this.form.is_transferaccounts_delete = 1,
               this.form.is_transferaccounts_view = 1,
               this.form.is_deposit_create = 1,
               this.form.is_deposit_edit = 1,
               this.form.is_deposit_delete = 1,
               this.form.is_deposit_view = 1,
               this.form.is_returndeposit_create = 1,
               this.form.is_returndeposit_edit = 1,
               this.form.is_returndeposit_delete = 1,
               this.form.is_returndeposit_view = 1,
               this.form.is_employees_create = 1,
               this.form.is_employees_edit = 1,
               this.form.is_employees_delete = 1,
               this.form.is_employees_view = 1,
               this.form.is_payroll_create = 1,
               this.form.is_payroll_edit = 1,
               this.form.is_payroll_delete = 1,
               this.form.is_payroll_view = 1,
               this.form.is_journalvoucher_tab = 1,
               this.form.is_journalvoucher_create = 1,
               this.form.is_journalvoucher_edit = 1,
               this.form.is_journalvoucher_delete = 1,
               this.form.is_journalvoucher_view = 1
               
            },
            uncheckCompany() {
               this.form.is_accounts_tab = 0,
               this.form.is_transferaccounts_tab = 0,
               this.form.is_deposit_tab = 0,
               this.form.is_returndeposit_tab = 0,
               this.form.is_employees_tab = 0,
               this.form.is_payroll_tab = 0,
               this.form.is_accounts_create = 0,
               this.form.is_accounts_edit = 0,
               this.form.is_accounts_delete = 0,
               this.form.is_accounts_view = 0,
               this.form.is_transferaccounts_create = 0,
               this.form.is_transferaccounts_edit = 0,
               this.form.is_transferaccounts_delete = 0,
               this.form.is_transferaccounts_view = 0,
               this.form.is_deposit_create = 0,
               this.form.is_deposit_edit = 0,
               this.form.is_deposit_delete = 0,
               this.form.is_deposit_view = 0,
               this.form.is_returndeposit_create = 0,
               this.form.is_returndeposit_edit = 0,
               this.form.is_returndeposit_delete = 0,
               this.form.is_returndeposit_view = 0,
               this.form.is_employees_create = 0,
               this.form.is_employees_edit = 0,
               this.form.is_employees_delete = 0,
               this.form.is_employees_view = 0,
               this.form.is_payroll_create = 0,
               this.form.is_payroll_edit = 0,
               this.form.is_payroll_delete = 0,
               this.form.is_payroll_view = 0,
               this.form.is_journalvoucher_tab = 0,
               this.form.is_journalvoucher_create = 0,
               this.form.is_journalvoucher_edit = 0,
               this.form.is_journalvoucher_delete = 0,
               this.form.is_journalvoucher_view = 0
            },
            checkSales() {
               this.form.is_clients_tab = 1,
               this.form.is_quotations_tab = 1,
               this.form.is_salesorders_tab = 1,
               this.form.is_clients_create = 1,
               this.form.is_clients_edit = 1,
               this.form.is_clients_delete = 1,
               this.form.is_clients_view = 1,
               this.form.is_quotations_create = 1,
               this.form.is_quotations_edit = 1,
               this.form.is_quotations_delete = 1,
               this.form.is_quotations_view = 1,
               this.form.is_salesorders_create = 1,
               this.form.is_salesorders_edit = 1,
               this.form.is_salesorders_delete = 1,
               this.form.is_salesorders_view = 1
            },
            uncheckSales() {
               this.form.is_clients_tab = 0,
               this.form.is_quotations_tab = 0,
               this.form.is_salesorders_tab = 0,
               this.form.is_clients_create = 0,
               this.form.is_clients_edit = 0,
               this.form.is_clients_delete = 0,
               this.form.is_clients_view = 0,
               this.form.is_quotations_create = 0,
               this.form.is_quotations_edit = 0,
               this.form.is_quotations_delete = 0,
               this.form.is_quotations_view = 0,
               this.form.is_salesorders_create = 0,
               this.form.is_salesorders_edit = 0,
               this.form.is_salesorders_delete = 0,
               this.form.is_salesorders_view = 0
            },

            checkProduction(){
               this.form.is_production_tab = 1
          
            },
            uncheckProduction(){
               this.form.is_production_tab = 0
            },
            
            checkAccounting(){
               this.form.is_advancepayments_tab = 1,
               this.form.is_invoices_tab = 1,
               this.form.is_creditnotes_tab = 1,
               this.form.is_debitnotes_tab = 1,
               this.form.is_clientpayments_tab = 1,
               this.form.is_clientsoa_tab = 1,
               this.form.is_vendorexpenses_tab = 1,
               this.form.is_bills_tab = 1,
               this.form.is_vendorpayments_tab = 1,
               this.form.is_vendorsoa_tab = 1,
               this.form.is_advancepayments_create = 1,
               this.form.is_advancepayments_edit = 1,
               this.form.is_advancepayments_delete = 1,
               this.form.is_advancepayments_view = 1,
               this.form.is_invoices_create = 1,
               this.form.is_invoices_edit = 1,
               this.form.is_invoices_delete = 1,
               this.form.is_invoices_view = 1,
               this.form.is_creditnotes_create = 1,
               this.form.is_creditnotes_edit = 1,
               this.form.is_creditnotes_delete = 1,
               this.form.is_creditnotes_view = 1,
               this.form.is_debitnotes_create = 1,
               this.form.is_debitnotes_edit = 1,
               this.form.is_debitnotes_delete = 1,
               this.form.is_debitnotes_view = 1,
               this.form.is_clientpayments_create = 1,
               this.form.is_clientpayments_edit = 1,
               this.form.is_clientpayments_delete = 1,
               this.form.is_clientpayments_view = 1,
               this.form.is_clientsoa_create = 1,
               this.form.is_clientsoa_edit = 1,
               this.form.is_clientsoa_delete = 1,
               this.form.is_clientsoa_view = 1,
               this.form.is_vendorexpenses_create = 1,
               this.form.is_vendorexpenses_edit = 1,
               this.form.is_vendorexpenses_delete = 1,
               this.form.is_vendorexpenses_view = 1,
               this.form.is_bills_create = 1,
               this.form.is_bills_edit = 1,
               this.form.is_bills_delete = 1,
               this.form.is_bills_view = 1,
               this.form.is_vendorpayments_create = 1,
               this.form.is_vendorpayments_edit = 1,
               this.form.is_vendorpayments_delete = 1,
               this.form.is_vendorpayments_view = 1,
               this.form.is_vendorsoa_create = 1,
               this.form.is_vendorsoa_edit = 1,
               this.form.is_vendorsoa_delete = 1,
               this.form.is_vendorsoa_view = 1
            },
            uncheckAccounting(){
               this.form.is_advancepayments_tab = 0,
               this.form.is_invoices_tab = 0,
               this.form.is_creditnotes_tab = 0,
               this.form.is_debitnotes_tab = 0,
               this.form.is_clientpayments_tab = 0,
               this.form.is_clientsoa_tab = 0,
               this.form.is_vendorexpenses_tab = 0,
               this.form.is_bills_tab = 0,
               this.form.is_vendorpayments_tab = 0,
               this.form.is_vendorsoa_tab = 0,
               this.form.is_advancepayments_create = 0,
               this.form.is_advancepayments_edit = 0,
               this.form.is_advancepayments_delete = 0,
               this.form.is_advancepayments_view = 0,
               this.form.is_invoices_create = 0,
               this.form.is_invoices_edit = 0,
               this.form.is_invoices_delete = 0,
               this.form.is_invoices_view = 0,
               this.form.is_creditnotes_create = 0,
               this.form.is_creditnotes_edit = 0,
               this.form.is_creditnotes_delete = 0,
               this.form.is_creditnotes_view = 0,
               this.form.is_debitnotes_create = 0,
               this.form.is_debitnotes_edit = 0,
               this.form.is_debitnotes_delete = 0,
               this.form.is_debitnotes_view = 0,
               this.form.is_clientpayments_create = 0,
               this.form.is_clientpayments_edit = 0,
               this.form.is_clientpayments_delete = 0,
               this.form.is_clientpayments_view = 0,
               this.form.is_clientsoa_create = 0,
               this.form.is_clientsoa_edit = 0,
               this.form.is_clientsoa_delete = 0,
               this.form.is_clientsoa_view = 0,
               this.form.is_vendorexpenses_create = 0,
               this.form.is_vendorexpenses_edit = 0,
               this.form.is_vendorexpenses_delete = 0,
               this.form.is_vendorexpenses_view = 0,
               this.form.is_bills_create = 0,
               this.form.is_bills_edit = 0,
               this.form.is_bills_delete = 0,
               this.form.is_bills_view = 0,
               this.form.is_vendorpayments_create = 0,
               this.form.is_vendorpayments_edit = 0,
               this.form.is_vendorpayments_delete = 0,
               this.form.is_vendorpayments_view = 0,
               this.form.is_vendorsoa_create = 0,
               this.form.is_vendorsoa_edit = 0,
               this.form.is_vendorsoa_delete = 0,
               this.form.is_vendorsoa_view = 0
            },
            checkProcurment() {
                this.form.is_products_tab = 1,
                this.form.is_receiveorders_tab = 1,
                this.form.is_vendors_tab = 1,
                this.form.is_purchaseorders_tab = 1,
                this.form.is_transfers_tab = 1,
                this.form.is_productsdivision_tab = 1,
                this.form.is_productsaggregation_tab = 1,
                this.form.is_products_create = 1,
                this.form.is_products_edit = 1,
                this.form.is_products_delete = 1,
                this.form.is_products_view = 1,
                this.form.is_receiveorders_create = 1,
                this.form.is_receiveorders_edit = 1,
                this.form.is_receiveorders_delete = 1,
                this.form.is_receiveorders_view = 1,
                this.form.is_vendors_create = 1,
                this.form.is_vendors_edit = 1,
                this.form.is_vendors_delete = 1,
                this.form.is_vendors_view = 1,
                this.form.is_purchaseorders_create = 1,
                this.form.is_purchaseorders_edit = 1,
                this.form.is_purchaseorders_delete = 1,
                this.form.is_purchaseorders_view = 1,
                this.form.is_transfers_create = 1,
                this.form.is_transfers_edit = 1,
                this.form.is_transfers_delete = 1,
                this.form.is_transfers_view = 1,
                this.form.is_productsdivision_create = 1,
                this.form.is_productsdivision_edit = 1,
                this.form.is_productsdivision_delete = 1,
                this.form.is_productsdivision_view = 1,
                this.form.is_productsaggregation_create = 1,
                this.form.is_productsaggregation_edit = 1,
                this.form.is_productsaggregation_delete = 1,
                this.form.is_productsaggregation_view = 1                  
            },
            uncheckProcurment() {
                this.form.is_products_tab = 0,
                this.form.is_receiveorders_tab = 0,
                this.form.is_vendors_tab = 0,
                this.form.is_purchaseorders_tab = 0,
                this.form.is_transfers_tab = 0,
                this.form.is_productsdivision_tab = 0,
                this.form.is_productsaggregation_tab = 0,
                this.form.is_products_create = 0,
                this.form.is_products_edit = 0,
                this.form.is_products_delete = 0,
                this.form.is_products_view = 0,
                this.form.is_receiveorders_create = 0,
                this.form.is_receiveorders_edit = 0,
                this.form.is_receiveorders_delete = 0,
                this.form.is_receiveorders_view = 0,
                this.form.is_vendors_create = 0,
                this.form.is_vendors_edit = 0,
                this.form.is_vendors_delete = 0,
                this.form.is_vendors_view = 0,
                this.form.is_purchaseorders_create = 0,
                this.form.is_purchaseorders_edit = 0,
                this.form.is_purchaseorders_delete = 0,
                this.form.is_purchaseorders_view = 0,
                this.form.is_transfers_create = 0,
                this.form.is_transfers_edit = 0,
                this.form.is_transfers_delete = 0,
                this.form.is_transfers_view = 0,
                this.form.is_productsdivision_create = 0,
                this.form.is_productsdivision_edit = 0,
                this.form.is_productsdivision_delete = 0,
                this.form.is_productsdivision_view = 0,
                this.form.is_productsaggregation_create = 0,
                this.form.is_productsaggregation_edit = 0,
                this.form.is_productsaggregation_delete = 0,
                this.form.is_productsaggregation_view = 0
            },
            checkShipping() {
                this.form.is_shipping_tab = 1,
                this.form.is_Define_Shippers_tab = 1,
                this.form.is_Define_Shippers_create = 1,
                this.form.is_Define_Shippers_edit = 1,
                this.form.is_Define_Shippers_delete = 1,
                this.form.is_Define_Shippers_view = 1,
                                                    
                this.form.is_Shipments_tab = 1,
                this.form.is_Shipments_create = 1,
                this.form.is_Shipments_edit = 1,
                this.form.is_Shipments_delete = 1,
                this.form.is_Shipments_view = 1,
                                                        
                this.form.is_Receive_Shipments_tab = 1,
                this.form.is_Receive_Shipments_create = 1,
                this.form.is_Receive_Shipments_edit = 1,
                this.form.is_Receive_Shipments_delete = 1,
                this.form.is_Receive_Shipments_view = 1,
                                                        
                this.form.is_Shippers_Bills_tab = 1,
                this.form.is_Shippers_Bills_create = 1,
                this.form.is_Shippers_Bills_edit = 1,
                this.form.is_Shippers_Bills_delete = 1,
                this.form.is_Shippers_Bills_view = 1,
                                                        
                this.form.is_Shippers_Payments_tab = 1,
                this.form.is_Shippers_Payments_create = 1,
                this.form.is_Shippers_Payments_edit = 1,
                this.form.is_Shippers_Payments_delete = 1,
                this.form.is_Shippers_Payments_view = 1,
                                                        
                this.form.is_Shippers_SOA_tab = 1,
                this.form.is_Shippers_SOA_create = 1,
                this.form.is_Shippers_SOA_edit = 1,
                this.form.is_Shippers_SOA_delete = 1,
                this.form.is_Shippers_SOA_view = 1                
            },
            uncheckShipping() {
            //shipments
            this.form.is_shipping_tab = 0,
            this.form.is_Define_Shippers_tab = 0,
            this.form.is_Define_Shippers_create = 0,
            this.form.is_Define_Shippers_edit = 0,
            this.form.is_Define_Shippers_delete = 0,
            this.form.is_Define_Shippers_view = 0,
                                                
            this.form.is_Shipments_tab = 0,
            this.form.is_Shipments_create = 0,
            this.form.is_Shipments_edit = 0,
            this.form.is_Shipments_delete = 0,
            this.form.is_Shipments_view = 0,
                                                    
            this.form.is_Receive_Shipments_tab = 0,
            this.form.is_Receive_Shipments_create = 0,
            this.form.is_Receive_Shipments_edit = 0,
            this.form.is_Receive_Shipments_delete = 0,
            this.form.is_Receive_Shipments_view = 0,
                                                    
            this.form.is_Shippers_Bills_tab = 0,
            this.form.is_Shippers_Bills_create = 0,
            this.form.is_Shippers_Bills_edit = 0,
            this.form.is_Shippers_Bills_delete = 0,
            this.form.is_Shippers_Bills_view = 0,
                                                    
            this.form.is_Shippers_Payments_tab = 0,
            this.form.is_Shippers_Payments_create = 0,
            this.form.is_Shippers_Payments_edit = 0,
            this.form.is_Shippers_Payments_delete = 0,
            this.form.is_Shippers_Payments_view = 0,
                                                    
            this.form.is_Shippers_SOA_tab = 0,
            this.form.is_Shippers_SOA_create = 0,
            this.form.is_Shippers_SOA_edit = 0,
            this.form.is_Shippers_SOA_delete = 0,
            this.form.is_Shippers_SOA_view = 0
            },


            saveAndNew() {
                this.submit((data) => {
                    const id = Math.random().toString(36).substring(7)
                    this.endProcessing()
                    this.success()
                    if(this.form.id === window.apex.user.id) {
                        window.apex.user.name = this.form.name
                    }
                    this.$router.push(`${this.resource}/create?new=${id}`)
                })
            },
            generate() {
                this.form.email_signature = `Best Regards\n\n${this.form.name}\n${this.form.title}, ${this.form.company}\nEmail: ${this.form.email}\nTel: ${this.form.telephone} Ext: ${this.form.extension}\nMob: ${this.form.mobile_number}`
            },
            onCurrencyUpdate(e) {
                const currency = e.target.value
                Vue.set(this.form, 'currency_id', currency.id)
                Vue.set(this.form, 'currency', currency)
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Users ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
<style>
.permission-form {
    width: 15%;float: left;padding: 0 15px;
}
.permission-label {
    float: left;
    color: #fff;
    width: 30%;
    /* margin: 1px 0; */
    text-align: center;
    background: #00BCD4;
    padding: 8px 0px 8px 4px;
}
.permission-select {
        width: 70%;
}
.permission-title-group {
    float: left;
    margin: 0 10px 0 0px;
}
</style>