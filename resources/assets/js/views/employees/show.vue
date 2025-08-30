<template>
    <div v-if="show && user.is_employee_view == 1  || user.is_admin == 1">
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">{{model.text}}</span>
                <div>
                    <router-link :to="`/employees`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <router-link :to="`/employees/${model.id}/edit`"
                        class="btn" title="Edit" v-if="user.is_employee_edit == 1  || user.is_admin == 1">
                        <i class="fa fa-pencil"></i>
                    </router-link>
                    <a @click.stop="deleteModel" class="btn btn-danger" v-if="user.is_employee_delete == 1  || user.is_admin == 1">
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
                                    <p>{{model.company}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.name}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.email}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.telephone || '-'}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-mobile"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.mobile_number || '-'}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-2">
                                    <i class="fa fa-money"></i>
                                </div>
                                <div class="col col-10">
                                    <p>{{model.extension}}</p>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                    <div class="col col-8">
                        <div class="stats">
                            
                        </div>
                        <div class="stats">
                           
                        </div>
                    </div>
                     
                </div>
            </div>
        </div>
    </div>
    <div v-else>
    <div class="col col-12" v-if="user.is_employee_view != 1" style="margin: 25% auto;">
        <p style="color:red;text-align:center;"><strong>You don't Have Permission, Contact your system administrator</strong></p>
    </div></div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get, byMethod } from '../../lib/api'
    import MiniPanel from '../../components/search/MiniPanel.vue'
    export default {
        computed: {
            user() {
                return window.apex.user
            },
        },
        components: {MiniPanel,},
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
            get(`/api/employees/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/employees/${to.params.id}`)
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
                byMethod('delete', `/api/employees/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/employees')
                            this.$message.success(`You have successfully deleted vendor!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                Vue.set(this.$data, 'stats', res.data.stats)
                this.$title.set(`Employees - ${this.model.text}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
