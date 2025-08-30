<template >
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Currencies</span>
              <router-link slot="create" to="/currencies/create" class="btn btn-primary">
               Add New
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                <td class="width-1">{{ props.item.id }}</td>
                <td class="width-2">{{ props.item.code }}</td>
                <td class="width-2">{{ props.item.name }}</td>
                <td class="width-2">{{ props.item.decimal_place }}</td>
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
                resource: '/currencies',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Code', name: 'currencies', sort: true},
                    {title: 'Name', name: 'currencies', sort: true},
                    {title: 'Decimal', name: 'currencies', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/currencies', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/currencies', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set('currencies')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
