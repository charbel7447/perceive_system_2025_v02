<template>
    <div>
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Brands</span>
            <router-link  slot="create" to="/brands/create" class="btn btn-primary">
                New Brand
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                <td class="width-1">{{ props.item.id }}</td>
                <td class="width-1" v-if="props.item.images">
                    <img width="200" :src=" '/assets/uploads/media-uploader/'+props.item.images.path" onerror="this.src='/images/placeholder.png'"  />
                </td>
                <td class="width-1" v-else>
                    <img width="200"  :src="this.src='/images/placeholder.png'"  />
                </td>
                <td class="width-2">{{ props.item.title }}</td>
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
                resource: '/brands',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Name', name: 'unit', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/brands', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/brands', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set('Brands')
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
