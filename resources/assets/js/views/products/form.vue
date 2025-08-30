<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Product</span>

                <div class="btn-group">
                    <button :disabled="isProcessing" @click="save" class="btn btn-primary">
                        Save
                    </button>
                    <router-link :disabled="isProcessing" :to="`${resource}/${$route.params.id}`"
                        class="btn" v-if="isEdit">
                        Cancel
                    </router-link>
                </div>

            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-4">
                        <div class="form-group">
                            <label>
                                Item Code
                                <small>(Auto Generated)</small>
                            </label>
                            <!-- <span class="form-control">{{form.code}}</span> -->
                            <input  type="text" class="form-control" v-model="form.code">
                        </div>
                        <div class="form-group">
                            <label>
                                minimum_stock
                            </label>
                            <input required type="text" class="form-control" v-model="form.minimum_stock">
                            <error-text :error="error.minimum_stock"></error-text>
                        </div>
                        <div class="form-group product_cost_price" style="display:non">
                            <label>
                                Cost Price
                            </label>
                            <input type="text" class="form-control" v-model="form.sale_price">
                            <error-text :error="error.sale_price"></error-text>
                        </div>
                         <div class="form-group ">
                            <label>
                                Unit Price
                            </label>
                            <input type="text" class="form-control" v-model="form.unit_price">
                            <error-text :error="error.unit_price"></error-text>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label>
                                original_price
                            </label>
                            <input type="text" class="form-control" v-model="form.original_price">
                            <error-text :error="error.original_price"></error-text>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label>
                                Unit Price
                            </label>
                            <input type="text" class="form-control" v-model="form.unitprice">
                            <error-text :error="error.unitprice"></error-text>
                        </div>
                       
                        <div class="form-group">
                            <label>
                                Class A Price
                            </label>
                            <input type="text" class="form-control" v-model="form.class_a_price">
                            <error-text :error="error.class_a_price"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                Class B Price
                            </label>
                            <input type="text" class="form-control" v-model="form.class_b_price">
                            <error-text :error="error.class_b_price"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                Class C Price
                            </label>
                            <input type="text" class="form-control" v-model="form.class_c_price">
                            <error-text :error="error.class_c_price"></error-text>
                        </div>
                        <div class="form-group product_profit_value">
                            <label>
                                Profit %
                            </label>
                            <input type="text" class="form-control" v-model="form.rating_value">
                        </div>
                        <div class="form-group">
                                    <label>Adjust Classes:
                                        <small> (($request->class_a_price * $request->rating_value) / $old_rating_value)</small>
                                    </label>
                                    <select class="form-control" v-model="form.adjsut_classes">
                                    <option :value="0">No</option>
                                    <option :value="1">Yes</option>
                                </select>
                        </div>
                        <div class="form-group">
                            <label>Currency</label>
                            <typeahead :initial="form.currency"
                                :url="currencyURL"
                                @input="onCurrencyUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.currency_id"></error-text>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label>
                                WEIGHT/BOX
                            </label>
                            <input type="text" class="form-control" v-model="form.weight_box">
                            <error-text :error="error.weight_box"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                UPC
                            </label>
                            <input type="text" class="form-control" v-model="form.upc_number">
                            <error-text :error="error.upc_number"></error-text>
                        </div>
                        <div class="form-group"  style="display: none;">
                            <label>
                               Items Per Box
                            </label>
                            <input type="text" class="form-control" v-model="form.item_box">
                        </div>
                        
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea required class="form-control" v-model="form.description">
                            </textarea>
                            <error-text :error="error.description"></error-text>
                        </div>
                        <div class="form-group" v-if="company_type == 1 || company_type == 2">
                                    <label>Product Type:</label>
                                    <select class="form-control" v-model="form.product_type">
                                    <option value="0">Raw Material</option>
                                    <option value="1">Semi Finish</option>
                                    <option value="2">Finish Product</option>
                                </select>
                        </div>
                        <div class="form-group">
                            <label>
                                Manage Stock
                            </label>
                            <input type="text" class="form-control" v-model="form.current_stock">
                            <error-text :error="error.current_stock"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                               1 lot Qty <small>(Ex: 1 Rice Bag = 5 {{ form.unit }})</small>
                            </label>
                            <input type="text" class="form-control" v-model="form.lot_qty">
                            <error-text :error="error.lot_qty"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                Location
                            </label>
                            <input type="text" class="form-control" v-model="form.location">
                            <error-text :error="error.location"></error-text>
                        </div>
                        <div class="form-group"  style="display: none;">
                            <label>
                                CT/BOX
                            </label>
                            <input type="text" class="form-control" v-model="form.ct_box">
                            <error-text :error="error.ct_box"></error-text>
                        </div>
                        <div class="form-group"  style="display: none;">
                            <label>
                                VOLUME/BOX
                            </label>
                            <input type="text" class="form-control" v-model="form.volume_box">
                            <error-text :error="error.volume_box"></error-text>
                        </div>
                        <div class="form-group"  style="display: none;">
                            <label>
                                tax_name
                            </label>
                            <input type="text" class="form-control" v-model="form.tax_name">
                            <error-text :error="error.tax_name"></error-text>
                        </div>
                        <div class="form-group"  style="display: none;">
                            <label>
                                Product Rating %
                            </label>
                            <input type="text" class="form-control" v-model="form.product_rating">
                        </div>
                        <div class="form-group"  style="display: none;">
                            <label>Top Search</label>
                            <select class="form-control" v-model="form.top_search">
                                <option :value="0">No</option>
                                <option :value="1">Yes</option>
                            </select>
                        </div>
                        <div class="form-group"  style="display: none;">
                            <label>Is New</label>
                            <select class="form-control" v-model="form.new">
                                <option :value="0">No</option>
                                <option :value="1">Yes</option>
                            </select>
                        </div>
                        <div class="form-group" style="display: none;">
                            <label>Is Featured</label>
                            <select class="form-control" v-model="form.featured">
                                <option :value="0">No</option>
                                <option :value="1">Yes</option>
                            </select>
                        </div>
                        <div class="form-group"  style="display: none;">
                            <label>Is BestSeller</label>
                            <select class="form-control" v-model="form.best_selling">
                                <option :value="0">No</option>
                                <option :value="1">Yes</option>
                            </select>
                        </div>
                        <div class="form-group"  style="display: none;">
                            <label>Is Deal Of The Day</label>
                            <select class="form-control" v-model="form.deal_of_the_day">
                                <option :value="0">No</option>
                                <option :value="1">Yes</option>
                            </select>
                        </div>
                        <div class="form-group"  style="display: none;">
                            <label>Deal End Date</label>
                            <input type="date" class="form-control" v-model="form.deal_date">
                        </div>
                        <div class="form-group"  style="display: none;">
                            <label>Special Price For Web</label>
                            <input type="text" class="form-control" v-model="form.special_price">
                        </div>
                        
                      
                    </div>
                     <div class="col col-4">
                        <div class="form-group">
                            <label>UOM</label>
                            <typeahead :initial="form.uom"
                                :url="uomURL"
                                @input="onUomUpdate" required
                            >
                            </typeahead>
                            <error-text :error="error.uom_id"></error-text>
                            <input style="display: none;" type="text" class="form-control" v-model="form.uom_unit" />
                            <input style="display: none;" type="text" class="form-control" v-model="form.uom_id" />
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" v-model="form.status">
                                <option value="publish">Active</option>
                                <option value="disabled">In-Active</option>
                            </select>
                        </div>
                        <div class="form-group" v-if="product_dropdown_1">
                            <label  class="red">{{product_dropdown_1}}</label>
                            <typeahead :initial="form.product_dropdown_1"
                                :url="productdropdown1URL"
                                @input="onproductdropdown1Update" required
                            >
                            </typeahead>
                            <error-text :error="error.uom_id"></error-text>
                            <input style="display: none;" type="text" class="form-control" v-model="form.uom" />
                            <input style="display: none;" type="text" class="form-control" v-model="form.unit" />
                            <input style="display: none;" type="text" class="form-control" v-model="form.uom_id" />
                        </div>
                        <div class="form-group" v-if="product_dropdown_2">
                            <label  class="red">{{product_dropdown_2}}</label>
                            <typeahead :initial="form.product_dropdown_2"
                                :url="productdropdown2URL"
                                @input="onproductdropdown2Update" required
                            >
                            </typeahead>
                            <error-text :error="error.uom_id"></error-text>
                            <input style="display: none;" type="text" class="form-control" v-model="form.uom" />
                            <input style="display: none;" type="text" class="form-control" v-model="form.unit" />
                            <input style="display: none;" type="text" class="form-control" v-model="form.uom_id" />
                        </div>
                        <div class="form-group">
                            <label>Brand</label>
                            <typeahead :initial="form.brand"
                                :url="brandURL"
                                @input="onBrandUpdate" required
                            >
                            </typeahead>
                            <error-text :error="error.brand_id"></error-text>
                            <input style="display: none;" type="text" class="form-control" v-model="form.brand_id" />
                        </div>
                        <div class="form-group"  style="display: none;">
                            <label>
                                Size
                            </label>
                            <input type="text" class="form-control" v-model="form.size">
                            <error-text :error="error.size"></error-text>
                        </div>
                        <div class="form-group">
                            <label>Warehouses</label>
                            <typeahead :initial="form.warehouse"
                                :url="warehouseURL"
                                @input="onWarehouseUpdate" required
                            >
                            </typeahead>
                            <error-text :error="error.warehouse_id"></error-text>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <typeahead :initial="form.category"
                                :url="categoryURL"
                                @input="onCategoryUpdate" required
                            >
                            </typeahead>
                            <error-text :error="error.category_id"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                Sub Category
                            </label>
                            <typeahead :initial="form.sub_category"
                                :url="subcategoryURL"
                                :params="{category_id: form.category_id}"
                                @input="onSubCategoryUpdate" required
                            >
                            </typeahead>
                            <error-text :error="error.sub_category_id"></error-text>
                            <input style="display: none;" type="text" class="form-control" v-model="form.sub_category_id" />
                            <input style="display: none;" type="text" class="form-control" v-model="form.sub_categoryid" />
                        </div>
                          <div class="form-group" style="display:non">
                            <label>
                               Sub Sub Category
                            </label>
                            <typeahead :initial="form.sub_sub_category"
                                :url="subsubcategoryURL"
                                :params="{sub_categoryid: form.sub_categoryid}"
                                @input="onSubSubCategoryUpdate" 
                            >
                            </typeahead>
                            <input style="display:none;" type="text" class="form-control" v-model="form.sub_sub_categoryid">
                            <error-text :error="error.sub_sub_category_id"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                warehouse_qty
                            </label>
                            <input type="text" class="form-control" v-model="form.warehouse_qty">
                            <error-text :error="error.warehouse_qty"></error-text>
                        </div>
                        <div class="form-group"  style="display: none;">
                            <label>
                                tax_rate
                            </label>
                            <input type="text" class="form-control" v-model="form.tax_rate">
                            <error-text :error="error.tax_rate"></error-text>
                        </div>
                        <div class="col row">
                            <div class="col col-6">
                                <div class="form-group">
                                    <label>
                                        (1) Nb Boxes
                                    </label>
                                    <input type="text" class="form-control" v-model="form.nb_boxes_1">
                                    <error-text :error="error.tax_rate"></error-text>
                                </div>
                            </div>
                            <div class="col col-6">
                                <div class="form-group">
                                    <label>
                                        (1) Volume Price <small>1 box will be:</small>
                                    </label>
                                    <input type="text" class="form-control" v-model="form.nb_boxes_1_price">
                                    <error-text :error="error.tax_rate"></error-text>
                                </div>
                            </div>
                        </div>
                        <div class="col row">
                            <div class="col col-6">
                                <div class="form-group">
                                    <label>
                                        (2) Nb Boxes
                                    </label>
                                    <input type="text" class="form-control" v-model="form.nb_boxes_2">
                                    <error-text :error="error.tax_rate"></error-text>
                                </div>
                            </div>
                            <div class="col col-6">
                                <div class="form-group">
                                    <label>
                                        (2) Volume Price <small>1 box will be:</small>
                                    </label>
                                    <input type="text" class="form-control" v-model="form.nb_boxes_2_price">
                                    <error-text :error="error.tax_rate"></error-text>
                                </div>
                            </div>
                        </div>
                        <div class="col row">
                            <div class="col col-6">
                                <div class="form-group">
                                    <label>
                                        (3) Nb Boxes
                                    </label>
                                    <input type="text" class="form-control" v-model="form.nb_boxes_3">
                                    <error-text :error="error.tax_rate"></error-text>
                                </div>
                            </div>
                            <div class="col col-6">
                                <div class="form-group">
                                    <label>
                                        (3) Volume Price <small>1 box will be:</small>
                                    </label>
                                    <input type="text" class="form-control" v-model="form.nb_boxes_3_price">
                                    <error-text :error="error.tax_rate"></error-text>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col col-4" style="display:none;">
                        <div class="form-group">
                            <strong>Inventory</strong>
                        </div>
                        <div class="form-check">
                            <label>
                                <input type="checkbox" v-model="form.current_stock">
                                Track Inventory for this Item
                            </label>
                            <error-text :error="error.current_stock"></error-text>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-md-12">
                    <strong class="form-controlz" style="float: left;width: 7%;padding: 2px  0;;">Show Filter</strong><br>
                    <input type="checkbox" :value="1" v-model="showFilter" style="width: 10px;top: 5px;
    position: relative;
    left: -25px;">
                </div>
            <div class="row col-md-12" v-if="showFilter == 1">
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field1</label>
                            <input type="text" class="form-control" v-model="form.field1">
                            <error-text :error="error.field1"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field2</label>
                            <input type="text" class="form-control" v-model="form.field2">
                            <error-text :error="error.field2"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field3</label>
                            <input type="text" class="form-control" v-model="form.field3">
                            <error-text :error="error.field3"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field4</label>
                            <input type="text" class="form-control" v-model="form.field4">
                            <error-text :error="error.field4"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field5</label>
                            <input type="text" class="form-control" v-model="form.field5">
                            <error-text :error="error.field5"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field6</label>
                            <input type="text" class="form-control" v-model="form.field6">
                            <error-text :error="error.field6"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field7</label>
                            <input type="text" class="form-control" v-model="form.field7">
                            <error-text :error="error.field7"></error-text>
                        </div>
                    </div>
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Field8</label>
                            <input type="text" class="form-control" v-model="form.field8">
                            <error-text :error="error.field8"></error-text>
                        </div>
                    </div>
                    <div class="col col-6">
                        <div class="form-group">
                            <label>Field9</label>
                            <input type="text" class="form-control" v-model="form.field9">
                            <error-text :error="error.field9"></error-text>
                        </div>
                    </div>
                    <div class="col col-6">
                        <div class="form-group">
                            <label>Field10</label>
                            <input type="text" class="form-control" v-model="form.field10">
                            <error-text :error="error.field10"></error-text>
                        </div>
                    </div>
                </div>

                <hr>
                <table class=" product_vendors item-table">
                    <thead>
                        <tr>
                            <th>Base UOM</th>
                            <th>Base Qty</th>
                            <th>Converted UOM</th>
                            <th>Converted Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(v, index) in form.conversions">
                            <td :class="['width-3', errors(`items.${index}.vendor_id`)]">
                               <span class="form-control">{{ form.unit }}</span>
                            </td>
                            <td :class="['width-3', errors(`items.${index}.vendor_id`)]">
                               <input disabled type="text" class="form-control" v-model="base_qty">
                            </td>
                            <td :class="['width-3', errors(`items.${index}.uom_id`)]">
                                 <typeahead :initial="v.converted_uom" :trim="80"
                                        @input="onUomConversionUpdated(v, index, $event)"
                                        :url="uomURL"
                                    >
                                    </typeahead>
                                    <error-text :error="error[`items.${index}.uom_id`]"></error-text>
                                    <input style="display: none;" type="text" class="form-control" v-model="v.converted_uom_id">
                                    <input style="display: none;" type="text" class="form-control" v-model="v.converted_uom_code">
                                    <input style="display: none;" type="text" class="form-control" v-model="v.converted_uom_unit">
                                </td>
                            <td :class="['width-3', errors(`items.${index}.vendor_id`)]">
                               <input  type="text" class="form-control" v-model="v.converted_qty">
                            </td>
                            <td>
                                <button class="item-remove" @click="removeConversion(v, index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="item-empty">
                                <button class="btn btn-sm" @click="addConversion">
                                    Add Conversion Factor
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
               
<hr>
                <table class="item-table" v-if="form.taxes.length" style="">
                    <thead>
                        <tr>
                            <th>Tax Name</th>
                            <th>Rate</th>
                            <th>Tax Authority</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(v, index) in form.taxes">
                            <td :class="['width-4', errors(`items.${index}.name`)]">
                                <input  type="text" class="form-control" v-model="v.name">
                                <error-text :error="error[`items.${index}.name`]"></error-text>
                            </td>
                            <td :class="['width-2', errors(`items.${index}.rate`)]">
                                <input type="text" class="form-control" v-model="v.rate" required>
                                <error-text :error="error[`items.${index}.rate`]"></error-text>
                            </td>
                            <td :class="['width-6', errors(`items.${index}.tax_authority`)]">
                                <input type="text" class="form-control" v-model="v.tax_authority">
                                <error-text :error="error[`items.${index}.tax_authority`]"></error-text>
                            </td>
                            <td>
                                <button class="item-remove" @click="removeTax(v, index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot style="display:none">
                        <tr>
                            <td class="item-empty">
                                <button class="btn btn-sm" @click="addNewTax">
                                    Add new Tax
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div v-else >
                    <button class="btn btn-success btn-sm" @click="addNewTax">
                        Add Tax
                    </button>
                </div>
                <hr>
                <table class=" product_vendors item-table" v-if="form.items.length">
                    <thead>
                        <tr>
                            <th>Vendor</th>
                            <th>Reference</th>
                            <th>Supplier Item Nb</th>
                            <th>Unit Price</th>
                            <!-- <th>Tax Name</th>
                            <th>Tax Rate</th> -->
                            <th>Currency</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(v, index) in form.items">
                            <td :class="['width-3', errors(`items.${index}.vendor_id`)]">
                                <typeahead :initial="v.vendor" :trim="80"
                                    @input="onVendorUpdated(v, index, $event)"
                                    :url="vendorURL" required
                                >
                                </typeahead>
                                <error-text :error="error[`items.${index}.vendor_id`]"></error-text>
                            </td>
                            <td :class="['width-2', errors(`items.${index}.reference`)]">
                                <input  type="text" class="form-control" v-model="v.reference">
                                <error-text :error="error[`items.${index}.reference`]"></error-text>
                            </td>
                            <td :class="['width-2', errors(`items.${index}.supplier_item_nb`)]">
                                <input  type="text" class="form-control" v-model="v.supplier_item_nb">
                                <error-text :error="error[`items.${index}.supplier_item_nb`]"></error-text>
                            </td>
                            <td :class="['width-2', errors(`items.${index}.price`)]">
                                <input required type="text" class="form-control" v-model="v.price">
                                <error-text :error="error[`items.${index}.price`]"></error-text>
                            </td>
                            <!-- <td :class="['width-1', errors(`items.${index}.tax_name`)]">
                                <input type="text" class="form-control" v-model="v.tax_name">
                                <error-text :error="error[`items.${index}.tax_name`]"></error-text>
                            </td>
                            <td :class="['width-1', errors(`items.${index}.tax_rate`)]">
                                <input type="text" class="form-control" v-model="v.tax_rate">
                                <error-text :error="error[`items.${index}.tax_rate`]"></error-text>
                            </td> -->
                            <td :class="['width-3', errors(`items.${index}.currency_id`)]">
                                <typeahead :initial="v.currency" :trim="80"
                                    @input="onVendorCurrencyUpdated(v, index, $event)"
                                    :url="currencyURL"
                                >
                                </typeahead>
                                 <error-text :error="error[`items.${index}.currency_id`]"></error-text>
                            </td>
                            <td>
                                <button class="item-remove" @click="removeVendor(v, index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="item-empty">
                                <button class="btn btn-sm" @click="addNewVendor">
                                    Add new Vendor
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div v-else>
                    <button class="btn btn-success btn-sm" @click="addNewVendor">
                        Add new Vendor
                    </button>
                </div>

                <hr>

            </div>
            <div class="panel-footer">
                <spinner v-if="isProcessing"></spinner>
                <div class="btn-group" v-else>
                    <button :disabled="isProcessing" @click="saveAndStay" class="btn btn-primary">
                        Save and Stay
                    </button>
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
            'create': `/api/products/create`,
            'edit': `/api/products/${to.params.id}/edit`,
            'clone': `/api/products/${to.params.id}/edit?mode=clone`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        computed: {
            user() {
                return window.apex.user
            },
            company_type() {
                return window.apex.company_type
            },
            product_dropdown_1(){
                return window.apex.product_dropdown_1
            },
            product_dropdown_2(){
                return window.apex.product_dropdown_2
            },
        },
        components: { ErrorText, Typeahead, Spinner },
        mixins: [ form ],
        data () {
            return {
                form: {
                    items: [],
                    values: [],
                    taxes: [],
                    conversions: []
                },
                base_qty: 1,
                showFilter: 0,
                resource: '/products',
                store: '/api/products',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created product!',
                currencyURL: '/api/search/currencies',
                vendorURL: '/api/search/vendors',
                attributesURL: '/api/search/attributes',
                attributesValueURL: '/api/search/attributesvalue',
                uomURL: '/api/search/uom',
                categoryURL: '/api/search/categoriesall',
                warehouseURL: '/api/search/warehouses',
                subcategoryURL: '/api/search/subcategoriesall',
                subsubcategoryURL: '/api/search/subsubcategoriesall',
                rawmaterialtypeURL: '/api/search/raw_material_type',
                brandURL: '/api/search/brands',
                productdropdown1URL: '/api/search/product_dropdown_1',
                productdropdown2URL: '/api/search/product_dropdown_2',
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/products/${this.$route.params.id}`
                this.message = 'You have successfully updated product!'
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
            removeVendor(v, index) {
                this.form.items.splice(index, 1)
            },
            removeTax(v, index) {
                this.form.taxes.splice(index, 1)
            },
            removeAttribute(v, index) {
                this.form.values.splice(index, 1)
            },
            removeConversion(v, index) {
                this.form.conversions.splice(index, 1)
            },

            onUomConversionUpdated(v, index, e) {
                const converted_uom = e.target.value

                // uom
                Vue.set(this.form.conversions[index], 'converted_uom', converted_uom)
                Vue.set(this.form.conversions[index], 'converted_uom_id', converted_uom.id)
                Vue.set(this.form.conversions[index], 'converted_uom_code', converted_uom.unit)
                Vue.set(this.form.conversions[index], 'converted_uom_unit', converted_uom.unit)
            },

            onVendorUpdated(v, index, e) {
                const vendor = e.target.value

                // vendor
                Vue.set(this.form.items[index], 'vendor', vendor)
                Vue.set(this.form.items[index], 'vendor_id', vendor.id)

                // currency
                Vue.set(this.form.items[index], 'currency', vendor.currency)
                Vue.set(this.form.items[index], 'currency_id', vendor.currency.id)
            },
            onAttributeUpdated(x, index, e) {
               const attribute = e.target.value

                // attribute
                Vue.set(this.form.values[index], 'attribute', attribute)
                Vue.set(this.form.values[index], 'attribute_id', attribute.id)

                // attribute value
                Vue.set(this.form.values[index], 'attributes', attribute.items)
                
            },
            onVendorCurrencyUpdated(v, index, e) {
                const currency = e.target.value

                // currency
                Vue.set(this.form.items[index], 'currency', currency)
                Vue.set(this.form.items[index], 'currency_id', currency.id)
            },
            addNewVendor() {
                this.form.items.push({
                    'vendor_id': null,
                    'vendor': null,
                    'reference': 'reference',
                    'price': 0,
                    'tax_name': 'Vat',
                    'tax_rate': 5,
                    'currency_id': null,
                    'currency': null
                })
            },
            addConversion() {
                this.form.conversions.push({
                    'base_uom': 1,
                    'base_qty': 1,
                })
            },
            addNewTax() {
                this.form.taxes.push({
                    'name': 'Vat',
                    'rate': 5,
                    'tax_authority': 'tax_authority'
                })
            },
            addNewAttribute() {
                this.form.values.push({
                    'attribute_id': null,
                    'attribute': null,
                    'attribute_value': null,
                })
            },
            save() {
                this.submit((data) => {
                    this.success()
                    this.$router.push(`${this.resource}/${data.id}`)
                })
            },
            saveAndNew() {
                this.submit((data) => {
                    const id = Math.random().toString(36).substring(7)
                    this.endProcessing()
                    this.success()
                    this.$router.push(`${this.resource}/create?new=${id}`)
                })
            },
            saveAndStay() {
                this.submit((data) => {
                    this.endProcessing()
                    this.success()
                    this.$router.push(`${this.resource}/${data.id}/edit`)
                })
            },
            onCurrencyUpdate(e) {
                const currency = e.target.value

                Vue.set(this.form, 'currency_id', currency.id)
                Vue.set(this.form, 'currency', currency)
            },
            onUomUpdate(e) {
                const uom = e.target.value

                Vue.set(this.form, 'uom_id', uom.id)
                Vue.set(this.form, 'uom', uom)
                Vue.set(this.form, 'uom_unit', uom.unit)
            },
            onBrandUpdate(e) {
                const brand = e.target.value

                Vue.set(this.form, 'brand_id', brand.id)
                Vue.set(this.form, 'brand', brand)
            },

            onproductdropdown1Update(e) {
                const product_dropdown_1 = e.target.value

                Vue.set(this.form, 'product_dropdown_1_id', product_dropdown_1.id)
                Vue.set(this.form, 'product_dropdown_1', product_dropdown_1)
            },

            onproductdropdown2Update(e) {
                const product_dropdown_2 = e.target.value

                Vue.set(this.form, 'product_dropdown_2_id', product_dropdown_2.id)
                Vue.set(this.form, 'product_dropdown_2', product_dropdown_2)
            },

            onWarehouseUpdate(e) {
                const warehouse = e.target.value

                Vue.set(this.form, 'warehouse_id', warehouse.id)
                Vue.set(this.form, 'warehouse', warehouse)
            },
            onRawMaterialTypeUpdate(e) {
                const raw_material_type = e.target.value

                Vue.set(this.form, 'raw_material_type_id', raw_material_type.id)
                Vue.set(this.form, 'raw_material_type', raw_material_type)
            },

            
            onCategoryUpdate(e) {
                const category = e.target.value

                Vue.set(this.form, 'category_id', category.id)
                Vue.set(this.form, 'category', category)
            },
            onSubCategoryUpdate(e) {
                const sub_category = e.target.value
                Vue.set(this.form, 'sub_category_id', sub_category.id)
                Vue.set(this.form, 'sub_categoryid', sub_category.id)
                Vue.set(this.form, 'sub_category', sub_category)
                Vue.set(this.form, 'sub_category_code', sub_category.number)
            },
            onSubSubCategoryUpdate(e) {
                const sub_sub_category = e.target.value
                Vue.set(this.form, 'sub_sub_category_id', sub_sub_category.id)
                Vue.set(this.form, 'sub_sub_category', sub_sub_category)
                Vue.set(this.form, 'sub_sub_categoryid', sub_sub_category.id)
                
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Product ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
