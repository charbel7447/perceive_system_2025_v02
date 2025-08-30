<template >
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Delivery Condition</span>
            <router-link slot="create" to="/deliverycondition/create" class="btn btn-primary">
                New 
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                <td class="width-1">{{ props.item.id }}</td>
                <td class="width-2">{{ props.item.name }}</td>
                <td class="width-2">{{ props.item.created_by }}</td>
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
                resource: '/deliverycondition',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Name', name: 'deliverycondition', sort: true},
                    {title: 'Created by', name: 'created_by', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/deliverycondition', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/deliverycondition', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set('deliverycondition')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
