<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Exchange Rate</span>
            <router-link slot="create" to="/exchangerate/create" class="btn btn-primary">
                New
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                    <td>{{ props.item.id }}</td>
                    <td class="width-2">{{ props.item.currency.code}}</td>
                    <td class="width-2">{{ props.item.currency1.code }}</td>
                    <td class="width-2">{{ props.item.value1 }}</td>
                    <td class="width-2">{{ props.item.value2 }}</td>
                    <td class="width-2">{{ props.item.exchangedate }}</td>
                    <td class="width-2">{{ props.item.created_by }}</td>
                    
                    <td class="width-2">
                        <status :id="props.item.status_id"></status>
                    </td>
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
                resource: '/exchangerate',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Base Currency X', name: 'currency1', sort: true},
                    {title: 'Second Currency Y', name: 'currency2', sort: true},
                    {title: 'Base Currency value', name: 'value1', sort: true},
                    {title: 'Second Currency value', name: 'value2', sort: true},
                    {title: 'Exchange Date', name: 'exchangedate', sort: true},
                    {title: 'Created By', name: 'created_by', sort: true}
                    
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/exchangerate', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/exchangerate', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set('Exchange Rate')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
