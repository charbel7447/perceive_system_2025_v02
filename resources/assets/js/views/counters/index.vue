<template >
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Counters</span>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                <td class="width-1">{{ props.item.id }}</td>
                <td class="width-2">{{ props.item.key }}</td>
                <td class="width-2">{{ props.item.prefix }}</td>
                <td class="width-2">{{ props.item.value }}</td>
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
                resource: '/counters',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Key', name: 'counters', sort: true},
                    {title: 'Prefix', name: 'counters', sort: true},
                    {title: 'Value', name: 'counters', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/counters', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/counters', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set('counters')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
