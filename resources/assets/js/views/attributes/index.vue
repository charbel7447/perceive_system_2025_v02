<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Attributes List</span>
            
            <router-link  slot="create" to="/attributes/create" class="btn btn-primary">
                New attribute
            </router-link>
            
              <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                    <td class="">{{ props.item.id }}</td>
                    <td class="">{{ props.item.number }}</td>
                    <td class="">{{ props.item.description | trim(80) }}</td>
                    <td class="" >
                        <span v-for="(v, index) in props.item.items">
                        <li>{{ v.attribute_value }}</li>
                        </span>
                    </td>
                    <td class="">{{ moment(props.item.created_at).format("DD-MM-YYYY") }}</td>
                    <td class="">{{ props.item.created_by }}</td>
                    
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
               resource: '/attributes',
               heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Code', name: 'id', sort: true},
                    {title: 'Description', name: 'description', sort: true},
                    {title: 'Values', name: 'values', sort: true},
                    {title: 'Created At', name: 'created_at', sort: true},
                    {title: 'Created By', name: 'created_by', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/attributes', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/attributes', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422 {{ props.item.id }} ${props.item.id}
        },
        methods: {
              deleteModel() {
                const r = confirm("Are you sure!")
                if(r != true) { return }
                this.$bar.start()
                byMethod('delete', `/api/attributes/${this.props.item.id}`)
                    .then(({data}) => {
                        if(data.deleted) {
                            this.$router.push('/attributes')
                            this.$message.success(`You have successfully deleted attributes!`)
                        }
                        this.$bar.finish()
                    })
                    .catch((err) => {})
            },
            setData(res) {
                this.$title.set('List Of Attributes')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
