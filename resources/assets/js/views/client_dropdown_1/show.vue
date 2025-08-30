<template>
   <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{client_dropdown_1}} {{model.text}}</span>
                <div>
                    <router-link :to="`/client_dropdown_1`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <router-link :to="`/client_dropdown_1/${model.id}/edit`"
                        class="btn" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </router-link>
                    <a @click.stop="deleteModel"  class="btn btn-danger">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-4">
                        <div class="bg-grey">
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-building"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.name}}</p>
                                </div>
                            </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get, byMethod } from '../../lib/api'
    export default {
        computed: {
            user() {
                return window.apex.user
            },
            company_type() {
                return window.apex.company_type
            },
            client_dropdown_1(){
                return window.apex.client_dropdown_1
            },
            client_dropdown_2(){
                return window.apex.client_dropdown_2
            },
        },
        components: {},
        data() {
            return {
                show: false,
               
                model: {
                    currency: {}
                },
                stats: {}
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/client_dropdown_1/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/client_dropdown_1/${to.params.id}`)
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
                byMethod('delete', `/api/client_dropdown_1/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/client_dropdown_1')
                            this.$message.success(`You have successfully deleted client_dropdown_1!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                Vue.set(this.$data, 'stats', res.data.stats)
                this.$title.set(`DropDown Table - ${this.model.text}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
