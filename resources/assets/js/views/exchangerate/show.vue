<template>
    <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{model.text}}</span>
                <div>
                    <router-link :to="`/exchangerate`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <a @click.stop="deleteModel" class="btn btn-danger">
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
                                    <i class="fa fa-percent"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.value1 || '-'}} - - - {{model.currency_name || '-'}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-mobile"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.value2 || '-'}} - - - - {{model.currency1_name || '-'}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-money"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.exchangedate || '-'}}</p>
                                </div>
                            </div>
                            <hr>
                         
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
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/exchangerate/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/exchangerate/${to.params.id}`)
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
                byMethod('delete', `/api/exchangerate/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/exchangerate')
                            this.$message.success(`You have successfully deleted exchangerate!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`exchangerate - ${this.model.text}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
