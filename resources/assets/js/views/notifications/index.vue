<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Notifications</span>
            <tr slot-scope="props" >
                <td class="width-1">{{ props.item.id }}</td>
                <td class="width-2">{{ props.item.number }}</td>
                <td class="width-2">{{ props.item.description }}</td>
                <td class="width-2">{{ props.item.document_type }}</td>
                <td class="width-2">{{ props.item.created_by }}</td>
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
                resource: '/notifications',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Number', name: 'unit', sort: true},
                    {title: 'Description', name: 'unit', sort: true},
                    {title: 'Document', name: 'unit', sort: true},
                    {title: 'Created by', name: 'created_by', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/notifications', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/notifications', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set('Unit Of Measurment')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
