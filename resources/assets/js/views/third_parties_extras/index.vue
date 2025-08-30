<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Third Parties / Extras</span>
            <router-link  slot="create" to="/third_parties_extras/create" class="btn btn-primary">
                New
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                <td class="width-1">{{ props.item.id }}</td>
                <td class="width-2">{{ props.item.number }}</td>
                <td class="width-2">{{ props.item.name }}</td>
                <td class="width-2">{{ props.item.description }}</td>
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
                resource: '/third_parties_extras',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Number', name: 'number', sort: true},
                    {title: 'Name', name: 'name', sort: true},
                    {title: 'Description', name: 'description', sort: true},
                    {title: 'Created by', name: 'created_by', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/third_parties_extras', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/third_parties_extras', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set('Third Parties / Extras')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
