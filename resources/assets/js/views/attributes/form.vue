<template>
    <div v-if="show ">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Attribute</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-4">
                    <div class="form-group">
                            <label>
                                Attribute Code
                                <small>(Auto Generated)</small>
                            </label>
                            <div v-if="user.is_admin == 1">
                                <input type="text" class="form-control" v-model="form.number">
                            </div>
                            <div v-else>
                                <span class="form-control">{{form.number}}</span>
                            </div>
                           
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-4">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" v-model="form.description">
                            </textarea>
                            <error-text :error="error.description"></error-text>
                        </div>
                    </div>
                    <div  class="col col-8">
                <table class="item-table" v-if="form.items.length">
                    <thead>
                        <tr>
                            <th>Values</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(v, index) in form.items">
                            
                            <td :class="['width-2', errors(`items.${index}.value`)]">
                                <input type="text" class="form-control" v-model="v.attribute_value">
                                <error-text :error="error[`items.${index}.value`]"></error-text>
                            </td>
                            
                            <td>
                                <button class="item-remove" @click="removeValue(v, index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="item-empty">
                                <button class="btn btn-sm" @click="addNewValue">
                                    Add new Value
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div v-else>
                    <button class="btn btn-success btn-sm" @click="addNewValue">
                        Add new Value
                    </button>
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
            'create': `/api/attributes/create`,
            'edit': `/api/attributes/${to.params.id}/edit`,
            'clone': `/api/attributes/${to.params.id}/edit?mode=clone`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        computed: {
            user() {
                return window.apex.user
            },
        },
        components: { ErrorText, Typeahead, Spinner },
        mixins: [ form ],
        data () {
            return {
                form: {
                    items: [],
                },
                resource: '/attributes',
                store: '/api/attributes',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created attribute!',
                vendorURL: '/api/search/attributes',
            
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/attributes/${this.$route.params.id}`
                this.message = 'You have successfully updated attribute!'
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
            removeValue(v, index) {
                this.form.items.splice(index, 1)
            },
            onValueUpdated(v, index, e) {
                const value = e.target.value

                // value
                Vue.set(this.form.items[index], 'value', value)
                Vue.set(this.form.items[index], 'value_id', value.id)
            },
            addNewValue() {
                this.form.items.push({
                    'value': null,
                  
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
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`attributes ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
