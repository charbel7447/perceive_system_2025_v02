<template>
   <div v-if="show">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{model.name}}</span>
                <div>
                    <router-link :to="`/chart_of_accounts`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <router-link :to="`/chart_of_accounts/${model.id}/edit`"
                        class="btn" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </router-link>
                    <!-- <a @click.stop="deleteModel"  v-if="user.is_uom_delete == 1" class="btn btn-danger">
                        <i class="fa fa-trash"></i>
                    </a> -->
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-8">
                        <div class="bg-grey">
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-dollar"><strong>Account code</strong></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.code}}</p>
                                </div>
                                  <div class="col col-2">
                                    <i class="fa fa-dollar"><strong>Name En</strong></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.name_en}}</p>
                                </div>
                                  <div class="col col-2">
                                    <i class="fa fa-dollar"><strong>Name Ar</strong></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.name_ar}}</p>
                                </div>
                                 <div class="col col-2">
                                    <i class="fa fa-dollar"><strong>Account Balance</strong> </i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.balance}}</p>
                                </div>
                                 <div class="col col-2">
                                    <i class="fa fa-dollar"><strong>Account Currency</strong></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.currency.code}}</p>
                                </div>
                                 <div class="col col-2">
                                    <i class="fa fa-dollar"><strong>Account Class</strong></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.classes.name_en}} - ({{model.classes.name_ar}})</p>
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
            get(`/api/chart_of_accounts/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/chart_of_accounts/${to.params.id}`)
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
                byMethod('delete', `/api/chart_of_accounts/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/chart_of_accounts')
                            this.$message.success(`You have successfully deleted chart_of_accounts!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                Vue.set(this.$data, 'stats', res.data.stats)
                this.$title.set(`Chart Of Accounts - ${this.model.text}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
