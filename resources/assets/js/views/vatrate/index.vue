<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Vat Rate</span>
            <router-link slot="create" to="/vatrate/create" class="btn btn-primary">
                New
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                    <td>{{ props.item.id }}</td>
                    
                
                    <td class="width-2">{{ props.item.value2 }}</td>
                    <td class="width-2">{{ props.item.currency1.code }}</td>
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
                resource: '/vatrate',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Vat Rate', name: 'currency1', sort: true},
                    {title: 'Vat Currency Y', name: 'currency2', sort: true},
                    {title: 'Vat Date', name: 'exchangedate', sort: true},
                    {title: 'Created By', name: 'created_by', sort: true}
                    
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/vatrate', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/vatrate', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set('Vat Rate')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
