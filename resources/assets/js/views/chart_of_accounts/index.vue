<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Chart Of Accounts</span>
            <router-link  slot="create" to="/chart_of_accounts/create" class="btn btn-primary">
                New
            </router-link>
            <router-link  slot="create" to="/chart_of_accounts/list" class="btn btn-secondary">
              Hierarchical
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                <td class="width-1">{{ props.item.id }}</td>
                <td class="width-2">{{ props.item.code }}</td>
                <td class="width-2">{{ props.item.classes.name_en }}</td>
                <td class="width-2">{{ props.item.name_en }}</td>
                <td class="width-2">{{ props.item.name_ar }}</td>
                <td class="width-2">{{ props.item.balance }}</td>
                <td class="width-2">{{ props.item.currency.code }}</td>
            </tr>
        </panel>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/panel.vue'
    import { get } from '../../lib/api'

    export default {
        components: { Panel },
         computed: {
            user() {
                return window.apex.user
            },
        },
        data() {
            return {
                resource: '/chart_of_accounts',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Code', name: 'code', sort: true},
                    {title: 'Class', name: 'class_code', sort: true},
                    {title: 'Name En', name: 'name_en', sort: true},
                    {title: 'Name Ar', name: 'name_ar', sort: true},
                    {title: 'Balance', name: 'balance', sort: true},
                    {title: 'Currency', name: 'currency_id', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/chart_of_accounts', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/chart_of_accounts', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set('Chart Of Accounts')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
