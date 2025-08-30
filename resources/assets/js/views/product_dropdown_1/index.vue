<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">{{product_dropdown_1}} DropDown Table</span>
            <router-link  slot="create" to="/product_dropdown_1/create" class="btn btn-primary">
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
        components: { Panel },
        computed: {
            user() {
                return window.apex.user
            },
            company_type() {
                return window.apex.company_type
            },
            product_dropdown_1(){
                return window.apex.product_dropdown_1
            },
            product_dropdown_2(){
                return window.apex.product_dropdown_2
            },
        },
        data() {
            return {
                resource: '/product_dropdown_1',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Name', name: 'unit', sort: true},
                    {title: 'Created by', name: 'created_by', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/product_dropdown_1', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/product_dropdown_1', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set('DropDown Table')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
