<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Settings</span>
                <div>
                    <spinner v-if="isProcessing"></spinner>
                    <div class="btn-group" v-else>
                        <button :disabled="isProcessing" @click="save" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-4">
                        <h3>Account</h3>
                    </div>
                    <div class="col col-8">
                        <div class="row">
                            <div class="col col-8">
                                <div class="form-group">
                                    <label>App Title</label>
                                    <input type="text" class="form-control" v-model="form.app_title">
                                    <error-text :error="error.app_title"></error-text>
                                </div>
                            </div>
                        </div>
                        <!-- 🔹 Update Section -->
                        <div class="form-group mt-3">
                            <label>System Version</label>
                            <div class="d-flex align-items-center justify-content-between p-2 border rounded bg-light">
                                <div>
                                    <span class="badge bg-primary">
                                        Current: {{ update.current }}
                                    </span>
                                    <span v-if="update.isAvailable" class="badge bg-warning text-dark ms-2">
                                        Latest: {{ update.latest }}
                                    </span>
                                    <span v-else class="badge bg-success ms-2">
                                        Up to Date
                                    </span>
                                </div>
                                <div v-if="update.isAvailable">
                                  <!-- Update box -->
                                    <div v-if="update.isAvailable" class="update-box">
                                    <button class="btn btn-warning" @click="showUpdateConfirm = true">
                                        Update Available (v{{ update.latest }})
                                    </button>

                                    <!-- Version Description Button -->
<div class="version-description-section">
  <button class="btn btn-info" @click="fetchVersionDescription = !fetchVersionDescription">
    {{ fetchVersionDescription ? 'Hide Version Details' : 'View Version Details' }}
  </button>

  <div v-if="fetchVersionDescription" class="version-description">
    <pre>{{ versionDescription }}</pre>
  </div>
</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- 🔹 End Update Section -->

                        <div class="row">
                            <div class="col col-8">
                                <div class="form-group">
                                    <label>First Currency</label>
                                    <typeahead :initial="form.currency"
                                        :url="currencyURL"
                                        @input="onCurrencyUpdate"
                                    >
                                    </typeahead>
                                    <error-text :error="error.currency_id"></error-text>
                                </div>
                                <div class="form-group" v-if="form.disable_second_currency == 0">
                                    <label>Global VAT %</label>
                                    <input type="text" class="form-control" v-model="form.global_vat_percentage" />
                                </div>
                                <div class="form-group" v-if="form.disable_second_currency == 0">
                                    <label>Second Currency</label>
                                    <span class="form-control">{{second_currency}}</span>
                                </div>
                                <div class="form-group">
                                    <label>Disable Second Currency</label>
                                    <select class="form-control permission-select" v-model="form.disable_second_currency">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                </div>
                                <div class="form-group">
                                    <label>Display Exchange Rate</label>
                                    <select class="form-control permission-select" v-model="form.display_exchange_rate">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                </div>
                                <div class="form-group">
                                    <label>Display Vat Rate</label>
                                    <select class="form-control permission-select" v-model="form.display_vat_rate">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-8">
                                <div class="form-group">
                                    <label>Display Second Currency Vat as Line with Exchange Rate</label>
                                    <select class="form-control permission-select" v-model="form.display_vat">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-8">
                                <div class="form-group">
                                    <label style="display: flow-root;">Make Invoices With Available Quantities <span style="color:red;">Only</span></label>
                                    <select class="form-control permission-select" style="color: red;font-weight: bold;" v-model="form.invoices_available_qty">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Download Updates</label>
                                    <router-link  slot="create" to="settings/upgrade"
                                        class="btn btn-primar"  title="Report" target="_blank">
                                        Upgrade&nbsp;&nbsp;&nbsp;<i class="fa fa-terminal"></i>
                                    </router-link>
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Update Views</label>
                                    <router-link  slot="create" to="settings/npm"
                                        class="btn btn-primar"  title="Report" target="_blank">
                                        Update&nbsp;&nbsp;&nbsp;<i class="fa fa-terminal"></i>
                                    </router-link>
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Update Database</label>
                                    <router-link  slot="create" to="settings/migrate"
                                        class="btn btn-primar"  title="Report" target="_blank">
                                        Update&nbsp;&nbsp;&nbsp;<i class="fa fa-terminal"></i>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-3" style="display:none;">
                                <div class="form-group">
                                    <label>Clear License</label>
                                    <router-link  slot="create" to="/perceive/clear_license"
                                        class="btn btn-primar"  title="Report" target="_blank">
                                        Update&nbsp;&nbsp;&nbsp;<i class="fa fa-terminal"></i>
                                    </router-link>
                                </div>
                            </div>
                            <div class="col col-3" style="display:none;">
                                <div class="form-group">
                                    <label>Update License</label>
                                    <router-link  slot="create" to="/perceive/update_license"
                                        class="btn btn-primar"  title="Report" target="_blank">
                                        Update&nbsp;&nbsp;&nbsp;<i class="fa fa-terminal"></i>
                                    </router-link>
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Clear DB Reports </label>
                                    <router-link  slot="create" to="/perceive/clear_db"
                                        class="btn btn-primar"  title="Report" target="_blank">
                                        Clear&nbsp;&nbsp;&nbsp;<i class="fa fa-terminal"></i>
                                    </router-link>
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Update Clients Balance</label>
                                    <router-link  slot="create" to="/perceive/update_clients_balance"
                                        class="btn btn-primar"  title="Report" target="_blank">
                                        Update&nbsp;&nbsp;&nbsp;<i class="fa fa-terminal"></i>
                                    </router-link>
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Clear Login History</label>
                                    <router-link  slot="create" to="/perceive/clear_login_history"
                                        class="btn btn-primar"  title="Report" target="_blank">
                                        Update&nbsp;&nbsp;&nbsp;<i class="fa fa-terminal"></i>
                                    </router-link>
                                </div>
                            </div>
                            
                            
                            
                        </div>
                        <div class="row">
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Reset on hold qty</label>
                                    <router-link  slot="create" to="/perceive/reset_on_hold_qty"
                                        class="btn btn-primar"  title="Report" target="_blank">
                                        Reset&nbsp;&nbsp;&nbsp;<i class="fa fa-terminal"></i>
                                    </router-link>
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Reset Clients Last Payment Date</label>
                                    <router-link  slot="create" to="/perceive/reset_last_payment_date"
                                        class="btn btn-primar"  title="Report" target="_blank">
                                        Reset&nbsp;&nbsp;&nbsp;<i class="fa fa-terminal"></i>
                                    </router-link>
                                </div>
                            </div>
                            <div class="col col-5">
                                <div class="form-group">
                                    <label>Clear System, Web & Mobile Log (Invoices,cart, orders etc)</label>
                                    <router-link style="color:red;font-weight: bold;"  slot="create" to="/perceive/clear_log"
                                        class="btn btn-primar"  title="Report" target="_blank">
                                        Clear Log&nbsp;&nbsp;&nbsp;<i class="fa fa-warning"></i>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col col-8">
                                <div class="form-group">
                                    <label>Company Type:</label>
                                    <select class="form-control" v-model="form.company_type">
                                    <option value="0">Re-Seller/Distributor</option>
                                    <option value="1">Production</option>
                                    <!-- <option value="2">Both</option> -->
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-8">
                                <div class="form-group">
                                    <label>App Color: <small>(#233c46, #d7251e, red, green)</small></label>
                                   <input type="text" class="form-control" v-model="form.app_color" />
                                </div>
                            </div>
                            <div class="col col-8">
                                <div class="form-group">
                                    <label>Nav Bar Color: <small>(#233c46, #d7251e, red, green)</small></label>
                                   <input type="text" class="form-control" v-model="form.nav_color" />
                                </div>
                            </div>
                            <div class="col col-8">
                                <div class="form-group">
                                    <label>Text Color:  <small>(#233c46, #d7251e, red, green)</small></label>
                                   <input type="text" class="form-control" v-model="form.text_color" />
                                </div>
                            </div>
                            <div class="col col-8">
                                <div class="form-group">
                                    <label>Copy Rights Label:  <small>(© Copyright 2022. All Rights Reserved)</small></label>
                                   <input disabled type="text" class="form-control" v-model="form.copyrights" />
                                </div>
                            </div>
                            <div class="col col-8">
                                <div class="form-group">
                                    <label>License Email:  <small></small></label>
                                   <input disabled type="text" class="form-control" v-model="form.license_email" />
                                </div>
                            </div>
                        </div>
                         <div class="row" style="display:non;">
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Upload Logo / Menu Image</label>
                                    <image-upload-preview width="200" height="150"
                                        :preview="form.uploaded_logo"
                                        @ready="onReadyFile('uploaded_logo_file', $event)"
                                        @remove="onRemoveFile('uploaded_logo')" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col col-2">
                        <h5>Documents Additional Fields</h5>
                    </div>
                    <div class="col col-10">
                        <div class="row">
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Quotations Field Name 1:</label>
                                    <input type="text" class="form-control" v-model="form.quotation_field_1">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Quotations Field Name 2:</label>
                                    <input type="text" class="form-control" v-model="form.quotation_field_2">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Quotations Field Name 3:</label>
                                    <input type="text" class="form-control" v-model="form.quotation_field_3">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Quotations Field Name 4:</label>
                                    <input type="text" class="form-control" v-model="form.quotation_field_4">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Sales Order Field Name 1:</label>
                                    <input type="text" class="form-control" v-model="form.sales_order_field_1">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Sales Order Field Name 2:</label>
                                    <input type="text" class="form-control" v-model="form.sales_order_field_2">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Sales Order Field Name 3:</label>
                                    <input type="text" class="form-control" v-model="form.sales_order_field_3">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Sales Order Field Name 4:</label>
                                    <input type="text" class="form-control" v-model="form.sales_order_field_4">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Invoice Field Name 1:</label>
                                    <input type="text" class="form-control" v-model="form.invoice_field_1">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Invoice Field Name 2:</label>
                                    <input type="text" class="form-control" v-model="form.invoice_field_2">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Invoice Field Name 3:</label>
                                    <input type="text" class="form-control" v-model="form.invoice_field_3">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Invoice Field Name 4:</label>
                                    <input type="text" class="form-control" v-model="form.invoice_field_4">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Purchase Order Field Name 1:</label>
                                    <input type="text" class="form-control" v-model="form.purchase_order_field_1">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Purchase Order Field Name 2:</label>
                                    <input type="text" class="form-control" v-model="form.purchase_order_field_2">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Purchase Order Field Name 3:</label>
                                    <input type="text" class="form-control" v-model="form.purchase_order_field_3">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Purchase Order Field Name 4:</label>
                                    <input type="text" class="form-control" v-model="form.purchase_order_field_4">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Bill Field Name 1:</label>
                                    <input type="text" class="form-control" v-model="form.bill_field_1">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Bill Field Name 2:</label>
                                    <input type="text" class="form-control" v-model="form.bill_field_2">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Bill Field Name 3:</label>
                                    <input type="text" class="form-control" v-model="form.bill_field_3">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Bill Field Name 4:</label>
                                    <input type="text" class="form-control" v-model="form.bill_field_4">
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Product DropDown Field 1:</label>
                                    <input type="text" class="form-control" v-model="form.product_dropdown_1">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>Product DropDown Field 2:</label>
                                    <input type="text" class="form-control" v-model="form.product_dropdown_2">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>client DropDown Field 1:</label>
                                    <input type="text" class="form-control" v-model="form.client_dropdown_1">
                                </div>
                            </div>
                            <div class="col col-3">
                                <div class="form-group">
                                    <label>client DropDown Field 2:</label>
                                    <input type="text" class="form-control" v-model="form.client_dropdown_2">
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
               
                <hr>
                <div class="row">
                    <div class="col col-4">
                        <h3>Notifications & Email</h3>
                    </div>
                    <div class="col col-8">
                        <div class="row">
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Quotations Notifications:</label>
                                    <select class="form-control" v-model="form.quotations_notification">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Quotations Email Flow:</label>
                                    <select class="form-control" v-model="form.quotations_email">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Sales Orders Notifications:</label>
                                    <select class="form-control" v-model="form.sales_orders_notification">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Sales Orders Email Flow:</label>
                                    <select class="form-control" v-model="form.sales_orders_email">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Invoices Notifications:</label>
                                    <select class="form-control" v-model="form.invoices_notification">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Invoices Email Flow:</label>
                                    <select class="form-control" v-model="form.invoices_email">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Purchase Orders Notifications:</label>
                                    <select class="form-control" v-model="form.purchase_orders_notification">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Purchase Orders Email Flow:</label>
                                    <select class="form-control" v-model="form.purchase_orders_email">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Bills Notifications: <small>(Supplier Invoice)</small></label>
                                    <select class="form-control" v-model="form.bills_notification">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Bills Email Flow: <small>(Supplier Invoice)</small></label>
                                    <select class="form-control" v-model="form.bills_email">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col col-4">
                        <h3>Company</h3>
                    </div>
                    <div class="col col-8">
                        <div class="row">
                            <div class="col col-8">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" v-model="form.company_name">
                                    <error-text :error="error.company_name"></error-text>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-8">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea class="form-control" v-model="form.company_address"></textarea>
                                    <error-text :error="error.company_address"></error-text>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-8">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control" v-model="form.company_email">
                                    <error-text :error="error.company_email"></error-text>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-8">
                                <div class="form-group">
                                    <label>Website</label>
                                    <input type="text" class="form-control" v-model="form.company_website">
                                    <error-text :error="error.company_website"></error-text>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-6">
                                <div class="form-group">
                                    <label>Telephone</label>
                                    <input type="text" class="form-control" v-model="form.company_telephone">
                                    <error-text :error="error.company_telephone"></error-text>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col col-4">
                        <h3>Email</h3>
                    </div>
                    <div class="col col-8">
                        <div class="row">
                            <div class="col col-6">
                                <div class="form-group">
                                    <label>Sent From Name</label>
                                    <input type="text" class="form-control" v-model="form.sent_from_name">
                                    <error-text :error="error.sent_from_name"></error-text>
                                </div>
                            </div>
                            <div class="col col-6">
                                <div class="form-group">
                                    <label>Sent From Email</label>
                                    <input type="text" class="form-control" v-model="form.sent_from_email" />
                                    <error-text :error="error.sent_from_email"></error-text>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-8">
                                <div class="form-group">
                                    <label>
                                        Global BCC Email
                                        <small>Optional</small>
                                    </label>
                                    <input type="text" class="form-control" v-model="form.global_bcc_email">
                                    <error-text :error="error.global_bcc_email"></error-text>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-12">
                                <div class="form-group">
                                    <label>Header Line 1</label>
                                    <input type="text" class="form-control" v-model="form.header_line_1">
                                    <error-text :error="error.header_line_1"></error-text>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-12">
                                <div class="form-group">
                                    <label>Header Line 2</label>
                                    <input type="text" class="form-control" v-model="form.header_line_2">
                                    <error-text :error="error.header_line_2"></error-text>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-12">
                                <div class="form-group">
                                    <label>Header Line 3</label>
                                    <input type="text" class="form-control" v-model="form.header_line_3">
                                    <error-text :error="error.header_line_3"></error-text>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-12">
                                <div class="form-group">
                                    <label>Footer Line 1</label>
                                    <input type="text" class="form-control" v-model="form.footer_line_1">
                                    <error-text :error="error.footer_line_1"></error-text>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-12">
                                <div class="form-group">
                                    <label>Footer Line 2</label>
                                    <input type="text" class="form-control" v-model="form.footer_line_2">
                                    <error-text :error="error.footer_line_2"></error-text>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-12">
                                <div class="form-group">
                                    <label>Footer Line 3</label>
                                    <input type="text" class="form-control" v-model="form.footer_line_3">
                                    <error-text :error="error.footer_line_3"></error-text>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col col-12">
                                <div class="form-group">
                                    <label>Extra Line</label>
                                    <input type="text" class="form-control" v-model="form.extra_line">
                                    <error-text :error="error.extra_line"></error-text>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col col-4">
                        <h3>Dashboard</h3>
                    </div>
                    <div class="col col-8">
                        <div class="row">
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Total Clients Due</label>
                                    <select class="form-control" v-model="form.box_1">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Total Clients Payments</label>
                                    <select class="form-control" v-model="form.box_2">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Saled Items</label>
                                    <select class="form-control" v-model="form.box_3">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Sales Qty</label>
                                    <select class="form-control" v-model="form.box_4">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Total Clients Debit</label>
                                    <select class="form-control" v-model="form.box_5">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Open Sales Orders</label>
                                    <select class="form-control" v-model="form.box_6">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Unpaid Invoices</label>
                                    <select class="form-control" v-model="form.box_7">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>TOTAL INVOICES AMOUNT</label>
                                    <select class="form-control" v-model="form.box_8">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>TOTAL VENDOR DUE</label>
                                    <select class="form-control" v-model="form.box_9">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>1 Account Balance</label>
                                    <select class="form-control" v-model="form.box_10">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>2 Account Balance</label>
                                    <select class="form-control" v-model="form.box_12">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>3 Account Balance</label>
                                    <select class="form-control" v-model="form.box_13">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>TOTAL INVOICES AMOUNT</label>
                                    <select class="form-control" v-model="form.box_14">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>TOTAL REVENUE(Revenue - Expense)</label>
                                    <select class="form-control" v-model="form.box_15">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Income vs Expense</label>
                                    <select class="form-control" v-model="form.chart_1">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Accounts</label>
                                    <select class="form-control" v-model="form.chart_2">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col col-4">
                        <h3>Document</h3>
                    </div>
                    <div class="col col-8">
                        <div class="row">
                            <div class="col col-6">
                                <div class="form-group">
                                    <label>Upload Header / Login & Documents Image</label>
                                    <image-upload-preview width="600" height="150"
                                        :preview="form.header"
                                        @ready="onReadyFile('header_file', $event)" @remove="onRemoveFile('header')" />
                                </div>
                            </div>
                        </div>
                        <div class="row" style="display:none;">
                            <div class="col col-4">
                                <div class="form-group">
                                    <label>Upload Footer</label>
                                    <image-upload-preview width="600" height="150"
                                        :preview="form.footer"
                                        @ready="onReadyFile('footer_file', $event)" @remove="onRemoveFile('footer')" />
                                </div>
                            </div>
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
                </div>
            </div>
        </div>
<!-- 🔹 Update Confirmation Modal -->
       <!-- Update Confirmation Modal -->
<!-- Update Modal -->
<!-- Update Modal -->
  <!-- Update Modal -->
  <div v-if="showUpdateConfirm" class="modal-overlay">
    <div class="modal">
      <h4>Update System</h4>

      <p>
        Current version: {{ update.current }}<br>
        Latest version: {{ update.latest }}
      </p>

      <div v-if="!updateFinished">
        <p>Update steps:</p>
        <ul>
          <li :class="{ running: step === 1 }">Pull latest code from GitHub</li>
          <li :class="{ running: step === 2 }">Install composer dependencies</li>
          <li :class="{ running: step === 3 }">Run migrations</li>
          <li :class="{ running: step === 4 }">Build frontend assets (npm)</li>
        </ul>
      </div>

      <div v-if="!updateFinished && step">
  <p>Current Step: <strong>{{ step }}</strong></p>
</div>
<pre>{{ updateOutput }}</pre>

      <div v-if="updateOutput">
        <h5>Update Logs:</h5>
        <pre>{{ updateOutput }}</pre>
      </div>

      <div class="modal-actions">
        <button v-if="!updateFinished" class="btn btn-success" :disabled="isProcessing" @click="performUpdate">
          Update Now
        </button>
        <button v-else class="btn btn-primary" @click="showUpdateConfirm = false">
          Finish
        </button>
        <button v-if="!updateFinished" class="btn btn-secondary" :disabled="isProcessing" @click="showUpdateConfirm = false">
          Cancel
        </button>
      </div>
    </div>
  </div>
  </div>
        <!-- 🔹 End Modal -->

  
</template>
<script type="text/javascript">
import Vue from 'vue'
import ImageUploadPreview from '../../components/form/ImageUploadPreview.vue'
import ErrorText from '../../components/form/ErrorText.vue'
import Typeahead from '../../components/form/Typeahead.vue'
import Spinner from '../../components/loading/Spinner.vue'
import { get } from '../../lib/api'
import { form } from '../../lib/mixins'

function initializeUrl(to) {
    let urls = { 'create': `/api/settings` }
    return (urls[to.meta.mode] || urls['create'])
}

export default {
    computed: {
        second_currency() {
            return window.apex.second_currency
        },
        update_description(){
                return window.apex.update_description
        },
    },
    components: { ErrorText, Typeahead, ImageUploadPreview, Spinner },
    mixins: [form],
    data() {
        return {
            store: '/api/settings',
            method: 'POST',
            message: 'You have successfully updated settings!',
            currencyURL: '/api/search/currencies',
            form: {},
            error: {},
            show: false,
            isProcessing: false,
            message: '',
            update: {
                current: '',
                latest: '',
                isAvailable: false
            },
            showUpdateConfirm: false,
      updateOutput: '',
      updateFinished: false,
      step: 0,
      versionDescription: '',
    fetchVersionDescription: false
    
        }
    },
    beforeRouteEnter(to, from, next) {
        get(initializeUrl(to))
            .then(res => {
                next(vm => vm.setData(res))
            })
    },
    beforeRouteUpdate(to, from, next) {
        this.show = false
        get(initializeUrl(to))
            .then(res => {
                this.setData(res)
                next()
            })
    },
    watch: {
  // Fetch content when modal is opened
  fetchVersionDescription(newVal) {
    if (newVal && !this.versionDescription) {
      this.getVersionDescription();
    }
  }
},

    methods: {
    setData(res) {
      Vue.set(this.$data, 'form', res.data.form)
      this.update.current = res.data.version.current
      this.update.latest = res.data.version.latest
      this.update.isAvailable = res.data.version.is_update_available
      this.show = true
    },

async performUpdate() {
  this.isProcessing = true;
  this.updateOutput = '';
  this.updateFinished = false;
  this.step = '';

  try {
    const response = await fetch('/api/settings/updateSystemLive');
    const reader = response.body.getReader();
    const decoder = new TextDecoder();

    while (true) {
      const { done, value } = await reader.read();
      if (done) break;

      const chunk = decoder.decode(value, { stream: true });
      const lines = chunk.split("\n").filter(Boolean);

      lines.forEach(line => {
        try {
          const obj = JSON.parse(line);
          this.step = obj.step;
          this.updateOutput += obj.line + "\n";

          this.$nextTick(() => {
            const pre = this.$el.querySelector('pre');
            if (pre) pre.scrollTop = pre.scrollHeight;
          });
        } catch (e) {
          // ignore JSON parse errors
        }
      });
    }

    // Extract last line for version update
    const lastLine = this.updateOutput.split("\n").reverse().find(l => l.includes('System updated to version:'));
    if (lastLine) {
      this.update.current = lastLine.replace('✅ System updated to version: ', '');
      this.update.isAvailable = false;
    }

    this.updateFinished = true;
    this.step = '';
  } catch (err) {
    this.updateOutput += "\n❌ Update failed! See console.";
    console.error(err);
    this.updateFinished = true;
    this.step = '';
  } finally {
    this.isProcessing = false;
  }
    this.save();
   },
async getVersionDescription() {
    try {
      const res = await fetch(this.update_description);
      let text = await res.text();

      // Highlight the latest version (current update)
      const currentVersion = this.update.latest;
      const regex = new RegExp(`^(version\\s+${currentVersion})`, 'm');
      text = text.replace(regex, `<span class="latest-version">$1</span>`);

      this.versionDescription = text;
    } catch (err) {
      this.versionDescription = '❌ Failed to load version details.';
      console.error(err);
    }
  },
       

        onCurrencyUpdate(e) {
            const currency = e.target.value
            Vue.set(this.form, 'currency_id', currency.id)
            Vue.set(this.form, 'currency', currency)
        },

        onReadyFile(name, e) {
            const file = e.target.value
            Vue.set(this.form, name, file)
        },

        onRemoveFile(file) {
            Vue.set(this.form, file, null)
        },

        save() {
            this.submitMultipartForm(this.form, (data) => {
                this.success()
                window.scroll(0, 1)
                this.$bar.start()
                this.endProcessing()
                this.$bar.finish()
            })
        }
    }
}
</script>
<style scoped>
.update-box {
  margin-top: 20px;
}
.modal-overlay {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
}
.modal {
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  max-width: 600px;
  width: 90%;
}
.modal-actions {
  margin-top: 15px;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}
pre {
  background: #f5f5f5;
  padding: 10px;
  margin-top: 10px;
  overflow-x: auto;
  max-height: 300px;
  white-space: pre-wrap;
}
ul li.running {
  font-weight: bold;
  color: #0a58ca;
}
.running { font-weight: bold; color: #2b6cb0; }
.version-description-section {
  margin-top: 15px;
}

.version-description-section button {
  margin-bottom: 10px;
}

.version-description {
  max-height: 250px;
  overflow-y: auto;
  background: #f7f7f7;
  border: 1px solid #ccc;
  padding: 10px;
  font-family: monospace;
  white-space: pre-wrap;
  line-height: 1.4;
  border-radius: 6px;
  box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
}

.version-description pre {
  margin: 0;
}

.version-description .latest-version {
  display: block;
  font-weight: bold;
  color: #1a73e8; /* Blue color for emphasis */
  margin-bottom: 5px;
}


</style>