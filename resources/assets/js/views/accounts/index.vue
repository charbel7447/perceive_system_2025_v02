<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Accounts</span>
            <router-link style="display:none;" slot="create" to="/accounts/create" class="btn btn-primary">
                New Account
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                <td class="width-1">{{ props.item.id }}</td>
                <td class="width-2">{{ props.item.name }}</td>
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
                resource: '/accounts',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Name', name: 'unit', sort: true},
                    {title: 'Balance', name: 'balance', sort: true},
                    {title: 'Currency', name: 'balance', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/accounts', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/accounts', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set('Accounts')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
