<template>
    <div>
          <router-link style="display:none" slot="create" to="/docs/categoriesreports"
                        class="btn btn-primar" title="Report" target="_blank">
                        <i class="fa fa-download"></i>
           </router-link>

            <a style="display:none" slot="create" href="docs/categoriesreports" target="_blank" class="btn" title="View PDF">
                            <i class="fa fa-file-pdf-o"></i>
           </a>
           
           
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Main Categories</span>
            <router-link slot="create" to="/categories/create" class="btn btn-primary" >
                New 
            </router-link>
             <router-link style="" slot="create" to="/category_tree_view"
                        class="btn btn-primar" title="Report" target="_blank">
                        Tree View
           </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                    <td class="width-1">{{ props.item.id }}</td>
                    <td class="width-2">{{ props.item.name }}</td>
                    <td class="width-3">{{ props.item.description }}</td>
                    <td class="width-2">{{ props.item.number }}</td>
                    <td class="width-2">{{ props.item.created_by }}</td>
                    <td class="width-2">{{ moment(props.item.created_at).format("DD-MM-YYYY") }}</td>
                </tr>
        </panel>
    </div>
</template>
<script type="text/javascript">
    import Panel from '../../components/search/panel.vue'
    import { get } from '../../lib/api'
    import moment from "moment";
    export default {
        computed: {
            user() {
                return window.apex.user
            },
        },
        components: { Panel },
        data() {
            return {
                moment: moment,
                resource: '/categories',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Name', name: 'name', sort: true},
                    {title: 'Description', name: 'description', sort: true},
                    {title: 'Category Code', name: 'number', sort: true},
                    {title: 'Created By', name: 'created_by', sort: true},
                    {title: 'Created At', name: 'created_at', sort: true}
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/categories', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/categories', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set(`Categories`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
