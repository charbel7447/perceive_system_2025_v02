<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{model.text}}</span>
                <div>
                    <router-link :to="`/currencies`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <router-link :to="`/currencies/${model.id}/edit`"
                        class="btn" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </router-link>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-8">
                        <div class="bg-grey">
                            <div class="row">
                                <div class="col col-4">
                                    <i class="fa fa-check">
                                        <strong class="red">Currency Code</strong>
                                    </i>
                                </div>
                                <div class="col col-8">
                                    <p>{{model.code}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-4">
                                    <i class="fa fa-check">
                                         <strong class="red">Currency Name</strong>
                                    </i>
                                </div>
                                <div class="col col-8">
                                    <p>{{model.name}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-4">
                                    <i class="fa fa-check">
                                         <strong class="red">Currency Decimal</strong>
                                    </i>
                                </div>
                                <div class="col col-8">
                                    <p>{{model.decimal_place}}</p>
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
            get(`/api/currencies/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/currencies/${to.params.id}`)
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
                byMethod('delete', `/api/currencies/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/currencies')
                            this.$message.success(`You have successfully deleted currencies!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                Vue.set(this.$data, 'stats', res.data.stats)
                this.$title.set(`currencies - ${this.model.text}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
<style>
.red {color:red;margin: 0 20px;}
</style>