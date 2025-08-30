<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{model.name}}</span>
                <div>
                    <router-link :to="`/users`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <router-link :to="`/users/${model.id}/edit`"
                        class="btn" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </router-link>
                    <a @click.stop="deleteModel" class="btn btn-danger">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-12">
                        <div class="bg-grey">
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.name}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-star"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.title}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.email}}</p>
                                </div>
                            </div>
                            <div class="row" v-if="model.telephone">
                                <div class="col col-2">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.telephone}}</p>
                                </div>
                            </div>
                            <div class="row" v-if="model.extension">
                                <div class="col col-2">
                                    <i class="fa fa-phone-square"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.extension}}</p>
                                </div>
                            </div>
                            <div class="row" v-if='model.mobile_number'>
                                <div class="col col-2">
                                    <i class="fa fa-mobile"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.mobile_number}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-lock"></i>
                                </div>
                                <div class="col col-10">
                                    <p>
                                        <span class="status status-accepted" v-if="model.is_admin">Yes</span>
                                        <span class="status status-draft" v-else>No</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="col col-10">
                                    <p>
                                        <span class="status status-paid" v-if="model.is_active">Yes</span>
                                        <span class="status status-draft" v-else>No</span>
                                    </p>
                               </div>
                            </div>
                            <div class="row">
                                <h4 class="col col-12" style="margin:20px 0;padding: 0;">Permissions</h4>
                                <div class="col col-12 section-permission">
                                    <div class="form-group col col-2">
                                        <label class="label-class">Dashboard Tab</label>
                                        <span class="span-class">
                                            <i v-if="model.is_dashboard" class="fa fa-check"></i>
                                            <i v-else class="fa fa-exclamation-triangle"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                                <hr>
                <h4 style="margin-bottom: 20px;">Permissions</h4>
                <!-- Settings Tabs  -->
                <div class="row" style="background: #ded;border: 1px solid;">
                    <div class="col col-12">
                            <div class="form-group">
                                <input disabled  class="permission-title-group" type="checkbox" value="1" v-model="model.is_dashboard">
                                <label>Dashboard Tab</label>

                                <input disabled  class="permission-title-group" type="checkbox" value="1" v-model="model.is_displayoverview_tab">
                                <label>Overview Home</label>

                                <input disabled  class="permission-title-group" type="checkbox" value="1" v-model="model.is_displaysales_tab">
                                <label>Overview Sales</label>

                                <input disabled  class="permission-title-group" type="checkbox" value="1" v-model="model.is_displayaccounting_tab">
                                <label>Overview Accounting</label>

                                <input disabled  class="permission-title-group" type="checkbox" value="1" v-model="model.is_displaystock_tab">
                                <label>Overview Stock</label>

                                <input disabled  class="permission-title-group" type="checkbox" value="1" v-model="model.is_displayproduction_tab">
                                <label>Overview Production</label>
          
                            </div>


                            
                            <div class="form-group">
                                <input disabled  class="permission-title-group" type="checkbox" value="1" v-model="model.is_settings_tab">
                                <label style="float: xleft;margin: 0 20px 0 0;">Settings Tab</label>
                                
                            </div>
                            <div v-if="model.is_settings_tab == 1">
                                <div class="row">
                                  <div class="col col-12">
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_deliverycondition_tab">
                                        <label>Delivery Conditions</label>
                                        <div v-if="model.is_deliverycondition_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_deliverycondition_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_deliverycondition_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_deliverycondition_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_deliverycondition_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_paymentcondition_tab">
                                        <label>Payment Conditions</label>
                                        <div v-if="model.is_paymentcondition_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_paymentcondition_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_paymentcondition_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_paymentcondition_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_paymentcondition_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_exchangerate_tab">
                                        <label>Exchange Rate</label>
                                        <div v-if="model.is_exchangerate_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_exchangerate_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_exchangerate_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_exchangerate_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_exchangerate_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_uom_tab">
                                        <label>U.O.M</label>
                                        <div v-if="model.is_uom_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_uom_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_uom_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_uom_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_uom_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_counters_tab">
                                        <label>Counters</label>
                                        <div v-if="model.is_counters_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_counters_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_counters_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_counters_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_counters_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                   </div>
                                </div>
                                <div class="row">
                                   <div class="col col-12">
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_currencies_tab">
                                        <label>Currencies</label>
                                        <div v-if="model.is_currencies_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_currencies_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_currencies_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_currencies_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_currencies_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_warehouses_tab">
                                        <label>Warehouses</label>
                                        <div v-if="model.is_warehouses_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_warehouses_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_warehouses_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_warehouses_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_warehouses_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_categories_tab">
                                        <label>Categories</label>
                                        <div v-if="model.is_categories_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_categories_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_categories_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_categories_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_categories_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_subcategories_tab">
                                        <label>Sub-Categories</label>
                                        <div v-if="model.is_subcategories_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_subcategories_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_subcategories_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_subcategories_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_subcategories_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                   </div>
                                </div>
                            </div>
                    </div>
                </div>
                <!-- Company TAB -->
                <div class="row" style="background: #ded;border: 1px solid;">
                    <div class="col col-12">
                            <div class="form-group">
                                <input disabled  class="permission-title-group" type="checkbox" value="1" v-model="model.is_company_tab">
                                <label style="float: xleft;margin: 0 20px 0 0;">Company Tab</label>
                                
                            </div>
                            <div v-if="model.is_company_tab == 1">
                                <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_accounts_tab">
                                    <label>Accounts</label>
                                    <div v-if="model.is_accounts_tab">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_accounts_create">
                                        <label>Create</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_accounts_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_accounts_delete">
                                        <label>Delete</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_accounts_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_transferaccounts_tab">
                                    <label>Transfer Accounts</label>
                                    <div v-if="model.is_transferaccounts_tab">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_transferaccounts_create">
                                        <label>Create</label>
                                        <!-- <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_transferaccounts_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_transferaccounts_delete">
                                        <label>Delete</label> -->
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_transferaccounts_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                 <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_deposit_tab">
                                    <label>Deposit</label>
                                    <div v-if="model.is_deposit_tab">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_deposit_create">
                                        <label>Create</label>
                                        <!-- <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_deposit_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_deposit_delete">
                                        <label>Delete</label> -->
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_deposit_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                 <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_returndeposit_tab">
                                    <label>Return Deposit</label>
                                    <div v-if="model.is_returndeposit_tab">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_returndeposit_create">
                                        <label>Create</label>
                                        <!-- <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_returndeposit_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_returndeposit_delete">
                                        <label>Delete</label> -->
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_returndeposit_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                 <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_employees_tab">
                                    <label>Employees</label>
                                    <div v-if="model.is_employees_tab">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_employees_create">
                                        <label>Create</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_employees_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_employees_delete">
                                        <label>Delete</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_employees_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                 <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_payroll_tab">
                                    <label>Payroll</label>
                                    <div v-if="model.is_payroll_tab">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_payroll_create">
                                        <label>Create</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_payroll_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_payroll_delete">
                                        <label>Delete</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_payroll_view">
                                        <label>View</label>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <!-- Sales TAB -->
                <div class="row" style="background: #ded;border: 1px solid;">
                    <div class="col col-12">
                            <div class="form-group">
                                <input disabled  class="permission-title-group" type="checkbox" value="1" v-model="model.is_sales_tab">
                                <label style="float: xleft;margin: 0 20px 0 0;">Sales Tab</label>
                               
                            </div>
                            <div v-if="model.is_sales_tab == 1">
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clients_tab">
                                    <label>Clients</label>
                                    <div v-if="model.is_clients_tab">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clients_create">
                                        <label>Create</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clients_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clients_delete">
                                        <label>Delete</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clients_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_quotations_tab">
                                    <label>Quotations</label>
                                    <div v-if="model.is_quotations_tab">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_quotations_create">
                                        <label>Create</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_quotations_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_quotations_delete">
                                        <label>Delete</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_quotations_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_salesorders_tab">
                                    <label>Sales Orders</label>
                                    <div v-if="model.is_salesorders_tab">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_salesorders_create">
                                        <label>Create</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_salesorders_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_salesorders_delete">
                                        <label>Delete</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_salesorders_view">
                                        <label>View</label>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <!-- Accounting TAB  is_accounting_tab -->
                <div class="row" style="background: #ded;border: 1px solid;">
                    <div class="col col-12">
                            <div class="form-group">
                                <input disabled  class="permission-title-group" type="checkbox" value="1" v-model="model.is_accounting_tab">
                                <label style="float: xleft;margin: 0 20px 0 0;">Accounting Tab</label>
                              
                            </div>
                            <div v-if="model.is_accounting_tab == 1">
                                <div class="row">
                                  <div class="col col-12">
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_advancepayments_tab">
                                        <label>Advance Payments</label>
                                        <div v-if="model.is_advancepayments_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_advancepayments_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_advancepayments_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_advancepayments_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_advancepayments_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_invoices_tab">
                                        <label>Client Invoices</label>
                                        <div v-if="model.is_invoices_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_invoices_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_invoices_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_invoices_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_invoices_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_creditnotes_tab">
                                        <label>Credit Notes</label>
                                        <div v-if="model.is_creditnotes_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_creditnotes_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_creditnotes_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_creditnotes_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_creditnotes_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_debitnotes_tab">
                                        <label>Debit Notes</label>
                                        <div v-if="model.is_debitnotes_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_debitnotes_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_debitnotes_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_debitnotes_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_debitnotes_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clientpayments_tab">
                                        <label>Client Payments</label>
                                        <div v-if="model.is_clientpayments_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clientpayments_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clientpayments_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clientpayments_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clientpayments_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                   </div>
                                </div>
                                <div class="row">
                                   <div class="col col-12">
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clientsoa_tab">
                                        <label>Client S.O.A</label>
                                        <div v-if="model.is_clientsoa_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clientsoa_create">
                                            <label>Create</label>
                                            <!-- <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clientsoa_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clientsoa_delete">
                                            <label>Delete</label> -->
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clientsoa_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendorexpenses_tab">
                                        <label>Vendor Expenses</label>
                                        <div v-if="model.is_vendorexpenses_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendorexpenses_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendorexpenses_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendorexpenses_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendorexpenses_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_bills_tab">
                                        <label>Vendor Bills</label>
                                        <div v-if="model.is_bills_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_bills_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_bills_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_bills_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_bills_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendorpayments_tab">
                                        <label>Vendor Payments</label>
                                        <div v-if="model.is_vendorpayments_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendorpayments_create">
                                            <label>Create</label>
                                            <!-- <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendorpayments_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendorpayments_delete">
                                            <label>Delete</label> -->
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendorpayments_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendorsoa_tab">
                                        <label>Vendor SOA</label>
                                        <div v-if="model.is_vendorsoa_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendorsoa_create">
                                            <label>Create</label>
                                            <!-- <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendorsoa_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendorsoa_delete">
                                            <label>Delete</label> -->
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendorsoa_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                   </div>
                                </div>
                            </div>
                    </div>
                </div>
                <!-- Procurment & Stock Tab is_procurment_tab -->
                <div class="row" style="background: #ded;border: 1px solid;">
                    <div class="col col-12">
                            <div class="form-group">
                                <input disabled  class="permission-title-group" type="checkbox" value="1" v-model="model.is_procurment_tab">
                                <label  style="float: xleft;margin: 0 20px 0 0;">Procurment & Stock Tab</label>
                             
                            </div>
                            <div v-if="model.is_procurment_tab == 1">
                                <div class="row">
                                  <div class="col col-12">
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_products_tab">
                                        <label>Products</label>
                                        <div v-if="model.is_products_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_products_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_products_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_products_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_products_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_receiveorders_tab">
                                        <label>Receive Orders</label>
                                        <div v-if="model.is_receiveorders_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_receiveorders_create">
                                            <label>Create</label>
                                            <!-- <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_receiveorders_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_receiveorders_delete">
                                            <label>Delete</label> -->
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_receiveorders_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendors_tab">
                                        <label>Vendors</label>
                                        <div v-if="model.is_vendors_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendors_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendors_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendors_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_vendors_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_purchaseorders_tab">
                                        <label>Purchase Orders</label>
                                        <div v-if="model.is_purchaseorders_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_purchaseorders_create">
                                            <label>Create</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_purchaseorders_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_purchaseorders_delete">
                                            <label>Delete</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_purchaseorders_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_transfers_tab">
                                        <label>Stock Transfers</label>
                                        <div v-if="model.is_transfers_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_transfers_create">
                                            <label>Create</label>
                                            <!-- <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_transfers_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_transfers_delete">
                                            <label>Delete</label> -->
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_transfers_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                   </div>
                                </div>
                                <div class="row">
                                   <div class="col col-12">
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_productsdivision_tab">
                                        <label>Products Division</label>
                                        <div v-if="model.is_productsdivision_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_productsdivision_create">
                                            <label>Create</label>
                                            <!-- <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_productsdivision_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_productsdivision_delete">
                                            <label>Delete</label> -->
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_productsdivision_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_productsaggregation_tab">
                                        <label>Products Aggregation</label>
                                        <div v-if="model.is_productsaggregation_tab">
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_productsaggregation_create">
                                            <label>Create</label>
                                            <!-- <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_productsaggregation_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_productsaggregation_delete">
                                            <label>Delete</label> -->
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_productsaggregation_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                    <div class="form-group" style="float: left;margin-right: 15px;width: 15%;border-right: 1px solid;">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_stockmovement_tab">
                                        <label>Stock Movement</label>
                                        <div v-if="model.is_stockmovement_tab">
                                            <!-- <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_productsaggregation_edit">
                                            <label>Edit</label>
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_productsaggregation_delete">
                                            <label>Delete</label> -->
                                            <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_stockmovement_view">
                                            <label>View</label>
                                        </div>
                                    </div>
                                   </div>
                                </div>
                                
                            </div>
                    </div>
                </div>
                <!-- Production TAB -->
                <div class="row" style="background: #ded;border: 1px solid;">
                    <div class="col col-12">
                            <div class="form-group">
                                <input disabled  class="permission-title-group" type="checkbox" value="1" v-model="model.is_production_tab">
                                <label style="float: xleft;margin: 0 20px 0 0;">Production Tab</label>
                            
                            </div>
                            <div v-if="model.is_production_tab == 1">
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clients_tab">
                                    <label>Clients</label>
                                    <div v-if="model.is_clients_tab">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clients_create">
                                        <label>Create</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clients_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clients_delete">
                                        <label>Delete</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_clients_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_quotations_tab">
                                    <label>Quotations</label>
                                    <div v-if="model.is_quotations_tab">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_quotations_create">
                                        <label>Create</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_quotations_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_quotations_delete">
                                        <label>Delete</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_quotations_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_salesorders_tab">
                                    <label>Sales Orders</label>
                                    <div v-if="model.is_salesorders_tab">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_salesorders_create">
                                        <label>Create</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_salesorders_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_salesorders_delete">
                                        <label>Delete</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_salesorders_view">
                                        <label>View</label>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>

                <hr>
                <!-- Shipping TAB -->
                <div class="row" style="background: #ded;border: 1px solid;">
                    <div class="col col-12">
                            <div class="form-group">
                                <input disabled  class="permission-title-group" type="checkbox" value="1" v-model="model.is_shipping_tab">
                                <label style="float: xleft;margin: 0 20px 0 0;">Shipping Tab</label>
                               
                            </div>
                            <div v-if="model.is_shipping_tab == 1">
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Define_Shippers_tab">
                                    <label>Define Shippers</label>
                                    <div v-if="model.is_Define_Shippers_tab  == true || model.is_Define_Shippers_tab  == 1 ">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Define_Shippers_create">
                                        <label>Create</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Define_Shippers_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Define_Shippers_delete">
                                        <label>Delete</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Define_Shippers_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shipments_tab">
                                    <label>Create Shipments</label>
                                    <div v-if="model.is_Shipments_tab  == true || model.is_Shipments_tab  == 1 ">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shipments_create">
                                        <label>Create</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shipments_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shipments_delete">
                                        <label>Delete</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shipments_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Receive_Shipments_tab">
                                    <label>Receive_Shipments</label>
                                    <div v-if="model.is_Receive_Shipments_tab  == true || model.is_Receive_Shipments_tab  == 1 ">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Receive_Shipments_create">
                                        <label>Create</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Receive_Shipments_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Receive_Shipments_delete">
                                        <label>Delete</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Receive_Shipments_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shippers_Bills_tab">
                                    <label>Shippers_Bills</label>
                                    <div v-if="model.is_Shippers_Bills_tab  == true || model.is_Shippers_Bills_tab  == 1 ">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shippers_Bills_create">
                                        <label>Create</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shippers_Bills_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shippers_Bills_delete">
                                        <label>Delete</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shippers_Bills_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shippers_Payments_tab">
                                    <label>Shippers_Payments</label>
                                    <div v-if="model.is_Shippers_Payments_tab  == true || model.is_Shippers_Payments_tab  == 1 ">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shippers_Payments_create">
                                        <label>Create</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shippers_Payments_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shippers_Payments_delete">
                                        <label>Delete</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shippers_Payments_view">
                                        <label>View</label>
                                    </div>
                                </div>
                                <div class="form-group" style="float: left;margin-right: 15px;width: 25%;border-right: 1px solid;">
                                    <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shippers_SOA_tab">
                                    <label>Shippers_SOA</label>
                                    <div v-if="model.is_Shippers_SOA_tab  == true || model.is_Shippers_SOA_tab  == 1 ">
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shippers_SOA_create">
                                        <label>Create</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shippers_SOA_edit">
                                        <label>Edit</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shippers_SOA_delete">
                                        <label>Delete</label>
                                        <input disabled class="permission-title-group" type="checkbox" value="1" v-model="model.is_Shippers_SOA_view">
                                        <label>View</label>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                               
                        </div>
                        <hr>
                            </div>
                            </div>
                            <hr>
                            <strong>Email Signature</strong><br><br>
                            <pre>{{model.email_signature}}</pre>
                        </div>
                    </div>
                    <div class="col col-8"></div>
                </div>
            </div>
        </div>
    </div>

</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get, byMethod } from '../../lib/api'
    export default {
        data() {
            return {
                show: false,
                model: {
                    currency: {}
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/users/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/users/${to.params.id}`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            deleteModel() {
                const r = confirm("Are you sure!")
                if(r != true) { return }
                this.$bar.start()
                byMethod('delete', `/api/users/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/users')
                            this.$message.success(`You have successfully deleted user!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`Users - ${this.model.name}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
<style >
   .custom-per {
       float: left;
    width: 20%;
    height: 150px;
    margin: 10px 0 0 0;
    border-right: 1px solid #ded;
    padding: 10px 20px;
   }
  .custom-per > p {
       margin: 5px 0;
   }
   .custom-per > h4 {
       /* background-color: #F44336; */
    color: #222d32;
    padding: 5px 0;
    /* margin: 0 -5px; */
    margin-bottom: 15px;
   } 
   .label-class{
       float: left;
   }
   .span-class{
       margin: 0 20px;
   }
   .sub-level-permission {
       margin: 0 70px 0 200px;
       color: black;float: left;
   }
   .sub-level2-permission {
       margin: 0 0 0 400px;
       color: black;
   }
   .sub-level3-permission {
        float: left;
        color: black;
        border: 1px solid;
        padding: 10px 0 0px 35px;
        margin-bottom: 10px
   }
   .section-permission{
    border: 2px solid #00cb8f99;
    margin: 10px 0;
    background: #ded;
    color: red;
    padding: 0 !important;
   }
   .settings-level-1 {
    float: left;
    width: 100%;
    border-bottom: 1px solid #fff;
   }
   input[type="checkbox" i]:disabled {
    background-color: red;    float: left;
}
</style>