<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">seller_statement</span>
            <!-- <router-link slot="create" to="/seller_statement/create" class="btn btn-primary">
                New seller_statement
            </router-link> -->
            <!-- <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)"> -->
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}/edit`)">
                <td class="width-1">{{ props.item.id }}</td>
                <td class="width-2">{{ props.item.name }}</td>
                <td class="width-2">{{ props.item.phone }}</td>
                <td class="width-2">{{ props.item.email }}</td>
                <td class="width-2">{{ props.item.commission }}</td>
                <td class="width-2">{{ props.item.commission_balance }}</td>
                <td class="width-2">{{ moment(props.item.created_at).format("DD-MM-YYYY") }}</td>
            </tr>
        </panel>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/panel.vue'
    import { get } from '../../lib/api'
    import moment from "moment";

    export default {
        components: { Panel },
        data() {
            return {
                moment: moment,
                resource: '/seller_statement',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Person', name: 'person', sort: true},
                    {title: 'Phone', name: 'company', sort: true},
                    {title: 'Email', name: 'email', sort: true},
                    {title: 'Commission', name: 'Commission', sort: true},
                    {title: 'Balance', name: 'Balance', sort: true},
                    {title: 'Created At', name: 'created_at', sort: true}
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/seller_statement', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/seller_statement', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set('seller_statement')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
