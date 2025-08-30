<template>
    <div>
        <div class="col-md-12 row" style="margin: 0;padding: 0;">
            <div style="text-align: left;" class="col-md-3">
                <strong class="form-controlz" style="">Show Filter</strong>
                <input type="checkbox" :value="1" v-model="showFilter" style="width: 10px;
    top: 2px;
    position: relative;
    left: 10px;"><br> <br>
            </div>
         

            <div style="text-align: right;margin: 0;padding: 0;" class="col-md-9">
                <router-link slot="create" to="download_product_table"  class="btn btn-primar"  title="Report" target="_blank">
                Download Products &nbsp;&nbsp;&nbsp;<i class="fa fa-file-excel-o"></i>
           </router-link>
                <router-link style="border: none;max-height: 29px;" slot="create" to="products_report/create"
                        class="btn btn-primary"  title="Report" target="_blank">
                     Report By Criteria &nbsp;&nbsp;&nbsp;<i class="fa fa-file-pdf-o"></i>
                </router-link>
                <router-link style="border: none;max-height: 29px;"  slot="create" to="products_report/show"
                                class="btn btn-primar"  title="Report" target="_blank">
                            Report By Criteria &nbsp;&nbsp;&nbsp;<i class="fa fa-file-excel-o"></i>
                </router-link>
                <router-link style="border: none;max-height: 29px;background:darkslateblue" slot="create" to="products_catalogue/create"
                        class="btn btn-primary"  title="Report" target="_blank">
                     Lands Catalogue Label &nbsp;&nbsp;&nbsp;<i class="fa fa-file-pdf-o"></i>
                </router-link>
                <router-link style="border: none;max-height: 29px;background:darkslateblue" slot="create" to="products_catalogue/show"
                        class="btn btn-primary"  title="Report" target="_blank">
                     Portrait Catalogue Label &nbsp;&nbsp;&nbsp;<i class="fa fa-file-pdf-o"></i>
                </router-link>
            </div>
        </div>
<form @submit.prevent="search">
<table class="table table-item table-items item items" v-if="showFilter == 1">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Vendor</th>
            <th>Category</th>
            <th>Minimum Price</th>
            <th>Maximum Price</th>
            <th>Quantity greater than</th>
            <th>Status</th>
            <th  v-if="product_dropdown_1">{{product_dropdown_1}}</th>
            <th  v-if="product_dropdown_2">{{product_dropdown_2}}</th>
        </tr>
    </thead>
    <tbody>
        
        <tr>
            <td>
                <input class="form-control"  v-model="product_name" type="text" placeholder="product name...">
            </td>
            <td>
                <select class="form-control"  v-model="vendor_id" type="text" placeholder="vendor_id...">
                    <option v-for="vend in pvendors" v-value="vend.id">{{vend.id}} {{vend.name}}</option>
                </select>
            </td>
            <td>
                <select class="form-control"  v-model="category" >
                    <option v-for="cat in pcategories" >{{cat.id}} <span>{{ cat.name }}</span></option>
                </select>
            </td>
            <td>
                <input class="form-control"  v-model="price_min" type="text" placeholder="price_min...">
            </td>
            <td>
                <input class="form-control"  v-model="price_max" type="text" placeholder="price_max...">
            </td>
            <td>
                <input class="form-control"  v-model="quantity_greater" type="text" placeholder="quanitty greater than...">
            </td>
            <td>
                <select class="form-control"  v-model="status" >
                    <option :value="1">Active</option>
                    <option :value="2">In-Active</option>
                </select>
            </td>
            <td v-if="product_dropdown_1">
                <select class="form-control"  v-model="product_dropdown1" >
                    <option v-for="product_dropdown1 in product_dropdown_1_array" ><span>{{ product_dropdown1.name }}</span></option>
                </select>
            </td>
            <td v-if="product_dropdown_2">
                <select class="form-control"  v-model="product_dropdown2" >
                    <option v-for="product_dropdown2 in product_dropdown_2_array" ><span>{{ product_dropdown2.name }}</span></option>
                </select>
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5" style="text-align: right;">
                <button class="btn btn-primary" type="submit">Filter</button>
            </th>
            <th style="text-align: left;">
                <a class="btn btn-primary" href="/products">Clear</a>
            </th>
        </tr>
    </tfoot>
</table>
</form>
        <panel ref="panel" :heading="heading" :resource="resource">
            
            <span slot="title">Products</span>
            <router-link slot="create" to="/products/create" class="btn btn-primary">
                New 
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                    <td>{{ props.item.id }}</td>
                    <td> <img width="60" :src=" '/uploads/'+ props.item.thumbnail" onerror="this.src='/images/placeholder.png'"  /></td>
                    <!-- <td v-html="props.item.barcode"></td> -->
                    <td>{{ props.item.description | trim(80) }}</td>
                    <td>
                        <span class="red"><strong>({{ props.item.category.number}})</strong></span>{{ props.item.category.name}}
                     - 
                     <!-- <span class="red"><strong>({{ props.item.sub_category.number}})</strong></span>{{ props.item.sub_category.name}} -->
                    </td>
                    <td>
                        <li v-if="props.item.items" v-for="vend in props.item.items"><span v-if="vend.vendor">{{vend.vendor.company | null}}</span></li>
                    </td> 
                   <td v-if="props.item.min_qty >= props.item.current_stock">
                            <span style="color:red;">Minimum Stock Reached </span> <strong style="color:red;">{{props.item.current_stock}}
                            </strong>
                    </td>
                     <td v-if="props.item.min_qty < props.item.current_stock">
                           Available Stock: <strong style="color:red;">{{props.item.current_stock}} 
                        </strong>
                    </td>
                    <td>
                        <li v-for="vend in props.item.items">{{vend.price | formatMoney(vend.currency)}}</li>
                    </td>
                    <td>
                        <a v-if="props.item.status != 'publish'"  :href="'/api/products/' +props.item.id+'/mark/1'">
                            <label class="switch" v-if="props.item.status != 'publish'">
                                <span class="slider round"></span>
                            </label>
                        </a>
                        <a v-if="props.item.status == 'publish'"  :href="'/api/products/' +props.item.id+'/mark/2'">
                            <label class="switch" v-if="props.item.status == 'publish'">
                                <span class="slider active-slider round"></span>
                            </label>
                        </a>
                        <!-- <a v-if="props.item.status != 'publish'"  :href="'/api/products/' +props.item.id+'/mark/1'">Active</a> -->
                        <!-- <a v-if="props.item.status == 'publish'" :href="'/api/products/' +props.item.id+'/mark/2'">InActive</a> -->
                    </td>
                    <!-- <td>{{ props.item.updated_by || props.item.created_by}}</td>  -->
                </tr>
        </panel>
        <product-filter v-if="showModal" @close="showModal = false"></product-filter>
    </div>
</template>
<script type="text/javascript">
import Vue from 'vue'
    import Panel from '../../components/search/panel.vue'
    import { get } from '../../lib/api'
    import ProductFilter from '../../components/modals/ProductFilter.vue'
    import Typeahead from '../../components/form/Typeahead.vue'

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
        components: { Panel,ProductFilter, Typeahead },
        data() {
            return {
                pvendors: [],
                pcategories: [],
                product_dropdown_1_array: [],
                product_dropdown_2_array: [],
                product_dropdown1: 0,
                product_dropdown2: 0,
                quantity_greater: 0,
                price_max: 1000,
                price_min: 1,
                product_name: 0,
                category: 0,
                vendor_id: null,
                showFilter: 1 ,
                showModal: false,
                resource: '/products',
                vendorURL: '/api/search/vendors',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Item Code', name: 'code', sort: true},
                    // {title: 'Barcode', name: 'code', sort: true},
                    {title: 'Description', name: 'description', sort: true},
                    {title: 'Category', name: 'description', sort: true},
                    {title: 'Vendor', name: 'description', sort: true},
                    {title: 'Stock', name: 'description', sort: true},
                    {title: 'Vendor Price', name: 'unit_price', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/products', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/products', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        mounted() {
            this.loadOptions();
        },
        methods: {
            loadOptions() {
                get('/api/search/vendors_pr') .then(pvendors => {
                    this.pvendors = (pvendors.data);
                });
                get('/api/search/categories_pr') .then(pcategories => {
                    this.pcategories = (pcategories.data);
                });
                get('/api/search/product_dropdown1') .then(product_dropdown_1_array => {
                    this.product_dropdown_1_array = (product_dropdown_1_array.data);
                });
                get('/api/search/product_dropdown2') .then(product_dropdown_2_array => {
                    this.product_dropdown_2_array = (product_dropdown_2_array.data);
                });
            },
            search() {
                get(`/api/products/filter?product_name=${this.product_name}&vendor_id=${this.vendor_id}&category=${this.category}&price_min=${this.price_min}&price_max=${this.price_max}&quantity_greater=${this.quantity_greater}&status=${this.status}&product_dropdown1=${this.product_dropdown1}&product_dropdown2=${this.product_dropdown2}`);
                this.$router.push(`products/filter?product_name=${this.product_name}&vendor_id=${this.vendor_id}&category=${this.category}&price_min=${this.price_min}&price_max=${this.price_max}&quantity_greater=${this.quantity_greater}&status=${this.status}&product_dropdown1=${this.product_dropdown1}&product_dropdown2=${this.product_dropdown2}`);
            },
            onVendorUpdated(e) {
                // const vendor = e.target.value

                Vue.set(this.vendor, 1)
            },
            setData(res) {
                this.$title.set(`Products`)
                this.$refs.panel.setData(res)
                
            }
        }
    }
</script>
<style>
.red {color:red;}
</style>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 36px;
  height: 24px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.active-slider::before{
    left: 16px !important;
}
.active-slider {
    background: green;
}
</style>