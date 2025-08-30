<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Machine</span>
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
                            <label>Code</label>
                            <input type="text" class="form-control" v-model="form.code">
                            <error-text :error="error.code"></error-text>
                        </div>
                    </div>
                </div>
                <hr>

                <table class="item-table">
                           <thead>
                              <tr>
                                 <th class="ac red">ID#:</th>
                                 <th class="al red">Setting Name</th>
                                 <th class="al red">Setting Comment</th>
                                 <button class="btn btn-sm quick-save-roll" @click="addSettings">
                                 + Setting
                                 </button>
                              </tr>
                           </thead>
                           <tbody>
                              <tr  v-for="(x, index) in form.settings">
                                 <td style="width:50px">
                                    <input style="background: #3c5d7e !important;color: #fff !important;margin:0px;" disabled type="text" class="form-control"  v-model="x.settings_id">
                                 </td>
                                 <td class="col col-3 width-3">
                                    <input  type="text" class="form-control"  v-model="x.settings_name">
                                 </td>
                                 <td class="col col-8 width-8">
                                    <input  type="text" class="form-control"  v-model="x.settings_comment">
                                 </td>
                              </tr>
                           </tbody>
                        </table>

                
            </div>
            <div class="panel-footer">
                <div class="btn-group">
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
    import { get, byMethod } from '../../lib/api'
    import { form } from '../../lib/mixins'

    function initializeUrl (to) {
        let urls = {
            'create': `/api/machines/create`,
            'edit': `/api/machines/${to.params.id}/edit`,
            'clone': `/api/machines/${to.params.id}/edit?mode=clone`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        computed: {
            user() {
                return window.apex.user
            },
            RollID(){
               return this.form.settings.reduce((carry, item) => {
                       return (Number(item.settings_id))
                     //  return carry + 1
                      
               }, 0)
           },
        },
        components: { ErrorText, Typeahead },
        mixins: [ form ],
        data () {
            return {
                resource: '/machines',
                store: '/api/machines',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created vendor!',
                currencyURL: '/api/search/currencies'
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/machines/${this.$route.params.id}`
                this.message = 'You have successfully updated vendor!'
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

            addSettings() {
               this.form.settings.push({
                   'settings_id': this.RollID + 1,
                   'settings_name':'setting_name',
               })
         
          
           },

            copyShippingAddress() {
                this.form.shipping_address = this.form.billing_address
            },
            onCurrencyUpdate(e) {
                const currency = e.target.value
                Vue.set(this.form, 'currency_id', currency.id)
                Vue.set(this.form, 'currency', currency)
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`machines ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
