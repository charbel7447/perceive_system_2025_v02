<template>
    <div v-if="show">
        <div class="panel" v-if="model.items.length">
            <div class="panel-heading">
                <span class="panel-title">#{{model.number}} -- {{model.description}}</span>
                <div>
                    <router-link :to="`/attributes`" class="btn" title="Go Back">
                        <i class="fa fa-arrow-left"></i>
                    </router-link>
                    <router-link :to="`/attributes/${model.id}/edit`" 
                        class="btn" title="Edit">
                        <i class="fa fa-pencil"></i>
                    </router-link>
                    <a @click.stop="deleteModel" class="btn btn-danger">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
            </div>
            <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Attribute</th>
                            <th>Value</th>
                         
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in model.items">

                            
                            <td class="width-6">
                                <router-link :to="`/attributes/${item.attribute_id}`">
                                    {{model.description}}
                                </router-link>
                            </td>
                            <td class="width-2">{{item.attribute_value}}</td>
                         
                        </tr>
                    </tbody>
                </table>
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
        data() {
            return {
                show: false,
                model: {
                    currency: {},
                    items: []
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/attributes/${to.params.id}`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/attributes/${to.params.id}`)
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
                byMethod('delete', `/api/attributes/${this.model.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/attributes')
                            this.$message.success(`You have successfully deleted quotation!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.data)
                this.$title.set(`Attributes - ${this.model.description}`)
                this.$bar.finish()
                this.show = true
            }
        }
    }
</script>
