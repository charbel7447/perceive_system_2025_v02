<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Sellers</span>
            <router-link slot="create" to="/sellers/create" class="btn btn-primary">
                New Seller
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                <td class="width-1">{{ props.item.id }}</td>
                <td class="width-2">{{ props.item.name }}</td>
                <td class="width-3">{{ props.item.email }}</td>
                <td class="width-3">{{ props.item.commission }}</td>
                <td class="width-3">{{ props.item.commission_balance }}</td>
                <td class="width-1">{{ props.item.created_at }}</td>
            </tr>
        </panel>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/panel.vue'
    import { get } from '../../lib/api'

    export default {
        components: { Panel },
        data() {
            return {
                resource: '/sellers',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Person', name: 'person', sort: true},
                    {title: 'Email', name: 'email', sort: true},
                    {title: 'Commission', name: 'email', sort: true},
                    {title: 'Balance', name: 'email', sort: true},
                    {title: 'Created At', name: 'created_at', sort: true}
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/sellers', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/sellers', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set('sellers')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
