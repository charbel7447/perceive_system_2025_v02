<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Finished Product Type</span>
             <router-link  slot="create" to="machines_report/create"
                        class="btn btn-primar"  title="Report" target="_blank">
                     Report By Criteria &nbsp;&nbsp;&nbsp;<i class="fa fa-download"></i>
           </router-link>
            <router-link slot="create" to="/finished_product_type/create" class="btn btn-primary">
                New Type
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                    <td class="width-1">{{ props.item.id }}</td>
                    <td class="width-1">{{ props.item.name }}</td>
                    <td class="width-1">{{ props.item.code }}</td>
                </tr>
        </panel>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/panel.vue'
    import { get } from '../../lib/api'

    export default {
        computed: {
            user() {
                return window.apex.user
            },
        },
        components: { Panel },
        data() {
            return {
                resource: '/finished_product_type',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Name', name: 'name', sort: true},
                    {title: 'Code', name: 'title', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/finished_product_type', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/finished_product_type', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set(`finished_product_type`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
