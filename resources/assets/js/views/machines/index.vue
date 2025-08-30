<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Machines</span>
             <router-link  slot="create" to="machines_report/create"
                        class="btn btn-primar"  title="Report" target="_blank">
                     Report By Criteria &nbsp;&nbsp;&nbsp;<i class="fa fa-download"></i>
           </router-link>
            <router-link slot="create" to="/machines/create" class="btn btn-primary">
                New Machine
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
                resource: '/machines',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Name', name: 'name', sort: true},
                    {title: 'Code', name: 'title', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/machines', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/machines', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set(`machines`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
