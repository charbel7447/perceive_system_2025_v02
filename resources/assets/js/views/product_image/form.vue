<template>
    <div v-if="show">
        <div class="panel">
             <div class="panel-heading d-flex justify-between items-center">
                <span class="panel-title">📎 Upload Product Attachments</span>

                <div class="actions">
                    <spinner v-if="isProcessing" />
                    <div class="btn-group" v-else>
                        <button :disabled="isProcessing" @click="save" class="btn btn-primary">💾 Save</button>
                        <router-link :to="`/products/${$route.params.id}`" class="btn" :disabled="isProcessing">
                            🔙 Back
                        </router-link>
                    </div>
                </div>
                <spinner v-if="isProcessing"></spinner>
                <div class="btn-group" v-else>
                    <button :disabled="isProcessing" @click="save" class="btn btn-primary">
                        Save
                    </button>
                    <router-link :disabled="isProcessing" :to="`/products/${$route.params.id}`"
                        class="btn" v-if="isEdit">
                        Back
                    </router-link>
                    <router-link :disabled="isProcessing" :to="`/products/${$route.params.id}`"
                        class="btn" v-else>
                        Back
                    </router-link>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-6">
                        <div class="form-group">
                            <label>
                                Upload Image
                            </label>
                             <input style="display:none" type="text" v-model="form.id"/>
                            <file-upload @ready="onDocument4"></file-upload>
                            <error-text :error="error.document4"></error-text>
                        </div>
                    </div>
                    <div class="col col-6">
                        <div class="form-group">
                            <img class="product_image_iframe" style="width: 200px;height: 200px;"  :src=" '/uploads/'+ form.thumbnail">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-6">
                        <div class="form-group">
                            <label>
                                Upload Document 1
                            </label>
                             <input style="display:none" type="text" v-model="form.id"/>
                            <file-upload @ready="onDocument1"></file-upload>
                            <error-text :error="error.document1"></error-text>
                        </div>
                    </div>
                    <div class="col col-6">
                        <div class="form-group">
                            <iframe class="product_image_iframe" style="width: 200px;height: 200px;"  :src=" '/uploads/'+ form.document1">
                            </iframe>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col col-6">
                        <div class="form-group">
                            <label>
                                Upload Document 2
                            </label>
                             <input style="display:none" type="text" v-model="form.id"/>
                            <file-upload @ready="onDocument2"></file-upload>
                            <error-text :error="error.document2"></error-text>
                        </div>
                    </div>
                    <div class="col col-6">
                        <div class="form-group">
                            <iframe class="product_image_iframe" style="width: 200px;height: 200px;"  :src=" '/uploads/'+ form.document2">
                            </iframe>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col col-6">
                        <div class="form-group">
                            <label>
                                Upload Document 3
                            </label>
                             <input style="display:none" type="text" v-model="form.id"/>
                            <file-upload @ready="onDocument3"></file-upload>
                            <error-text :error="error.document3"></error-text>
                        </div>
                    </div>
                    <div class="col col-6">
                        <div class="form-group">
                            <iframe class="product_image_iframe" style="width: 200px;height: 200px;"  :src=" '/uploads/'+ form.document3">
                            </iframe>
                        </div>
                    </div>
                </div>
                <hr>
              
            </div>
            <div class="panel-footer">
                <spinner v-if="isProcessing"></spinner>
                <div class="btn-group" v-else>
                    <button :disabled="isProcessing" @click="save" class="btn btn-primary">
                        Save
                    </button>
                    <router-link :disabled="isProcessing" :to="`/products/${$route.params.id}`"
                        class="btn" v-if="isEdit">
                        Back
                    </router-link>
                    <router-link :disabled="isProcessing" :to="`/products/${$route.params.id}`"
                        class="btn" v-else>
                        Back
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
    import FileUpload from '../../components/form/FileUpload.vue'
    import Spinner from '../../components/loading/Spinner.vue'
    import { get, byMethod } from '../../lib/api'
    import { form } from '../../lib/mixins'

    function initializeUrl (to) {
        let urls = {
            'create': `/api/product_image/create`,
            'edit': `/api/product_image/${to.params.id}/edit`,
            'clone': `/api/product_image/${to.params.id}/edit?mode=clone`,
            'products': `/api/products/${to.params.id}/edit?mode=products`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        components: { ErrorText, Typeahead, Spinner, FileUpload },
        mixins: [ form ],
        data () {
            return {
                resource: '/product_image',
                store: '/api/product_image',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully upload product document!',
                currencyURL: '/api/search/currencies',
                productURL: '/api/search/products',
                vendorURL: '/api/search/vendors'
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
            onDocument(e) {
                Vue.set(this.$data.form, 'document', e.target.value)
            },

            onDocument1(e) {
                Vue.set(this.$data.form, 'document1', e.target.value)
            },
            onDocument2(e) {
                Vue.set(this.$data.form, 'document2', e.target.value)
            },
            onDocument3(e) {
                Vue.set(this.$data.form, 'document3', e.target.value)
            },
            onDocument4(e) {
                Vue.set(this.$data.form, 'document4', e.target.value)
            },

            removeProduct(item, index) {
                if(this.form.items.length > 1) {
                    this.form.items.splice(index, 1)
                }
            },
            onVendorUpdate(e) {
                const vendor = e.target.value

                // vendor
                Vue.set(this.form, 'vendor', vendor)
                Vue.set(this.form, 'vendor_id', vendor.id)
            },
            save() {
                this.submitMultipartForm(this.form, (data) => {
                    this.success()
                    this.$router.push(`${this.resource}/${data.id}`)
                })
                this.endProcessing()
            },
            saveAndNew() {
                this.submitMultipartForm(this.form, (data) => {
                    const id = Math.random().toString(36).substring(7)
                    this.endProcessing()
                    this.success()
                    this.$router.push(`${this.resource}/create?new=${id}`)
                })
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Upload Image ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
<style>
textarea.form-control
{
    height:120px !important;
}
.sidebar {
    display: none;
}
.main-view {
    width: 100% !important;
}
</style>