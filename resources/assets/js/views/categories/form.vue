<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{title}} Main Category</span>
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
                            <label>
                                Description
                            </label>
                            <input type="text" class="form-control" v-model="form.description">
                            <error-text :error="error.description"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                Order
                            </label>
                            <select class="form-control" v-model="form.order">
                                <option :value="xx" v-for="xx in 100">{{ xx }}</option>
                            </select>
                            <error-text :error="error.order"></error-text>
                        </div>
                        <div class="form-group">
                            <label>
                                Status
                            </label>
                            <select class="form-control" v-model="form.status">
                                <option value="draft" >In-Active</option>
                                <option value="publish" >Active</option>
                            </select>
                            <error-text :error="error.order"></error-text>
                        </div>

                        <!-- <div class="form-group">
                            <label>
                                Parrent Category
                            </label>
                            <typeahead :initial="form.parent"
                                :url="categoryURL"
                                @input="onCategoryUpdate"
                            >
                            </typeahead>
                            <error-text :error="error.parent_id"></error-text>
                        </div> -->
                        <div class="form-group">
                            <label>
                                Category Code
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
                <hr>
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
            'create': `/api/categories/create`,
            'edit': `/api/categories/${to.params.id}/edit`,
            'clone': `/api/categories/${to.params.id}/edit?mode=clone`,
        }

        return (urls[to.meta.mode] || urls['create'])
    }

    export default {
        computed: {
            user() {
                return window.apex.user
            },
        },
        components: { ErrorText, Typeahead },
        mixins: [ form ],
        data () {
            return {
                resource: '/categories',
                store: '/api/categories',
                method: 'POST',
                title: 'Create',
                message: 'You have successfully created Category!',
                categoryURL: '/api/search/categories',
            }
        },
        created() {
            if(this.mode === 'edit') {
                this.store = `/api/categories/${this.$route.params.id}`
                this.message = 'You have successfully updated Category!'
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
            onCategoryUpdate(e) {
                const parent = e.target.value

                Vue.set(this.form, 'parent_id', parent.id)
                Vue.set(this.form, 'parent', parent)
            },
            setData(res) {
                Vue.set(this.$data, 'form', res.data.form)
                this.$title.set(`Category ${this.title}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
