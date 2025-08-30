<template>
    <div v-if="user.is_employee_view == 1 || user.is_admin == 1">
        <panel ref="panel" :heading="heading" :resource="resource">
            <span slot="title">Employees</span>
             <router-link  v-if="user.is_employee_create == 1  || user.is_admin == 1" slot="create" to="employees_report/create"
                        class="btn btn-primar"  title="Report" target="_blank">
                     Report By Criteria &nbsp;&nbsp;&nbsp;<i class="fa fa-download"></i>
           </router-link>
            <router-link v-if="user.is_employee_create == 1  || user.is_admin == 1" slot="create" to="/employees/create" class="btn btn-primary">
                New Employee
            </router-link>
            <tr slot-scope="props" @click="$router.push(`${resource}/${props.item.id}`)">
                    <td class="width-1">{{ props.item.id }}</td>
                    <td class="width-1">{{ props.item.name }}</td>
                    <td class="width-1">{{ props.item.title }}</td>
                    <td class="width-2">{{ props.item.company }}</td>
                    <td class="width-2">{{ props.item.email }}</td>
                    <td class="width-1">{{ props.item.salary }}&nbsp;{{ props.item.currency.code }}</td>
                    <td class="width-1">{{ props.item.mobile_number }}</td>
                    <td class="width-1">{{ props.item.deposit }}&nbsp;{{ props.item.currency.code }}</td>
                </tr>
        </panel>
    </div>
    <div v-else>
        <div class="col col-12" v-if="user.is_employee_view != 1" style="margin: 25% auto;">
        <p style="color:red;text-align:center;"><strong>You don't Have Permission, Contact your system administrator</strong></p>
    </div>
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
                resource: '/employees',
                heading: [
                    {title: 'ID', name: 'id', sort: true},
                    {title: 'Name', name: 'name', sort: true},
                    {title: 'Title', name: 'title', sort: true},
                    {title: 'Company', name: 'company', sort: true},
                    {title: 'Email', name: 'email', sort: true},
                    {title: 'Salary', name: 'Salary', sort: true},
                    {title: 'Mobile_number', name: 'mobile_number', sort: true},
                    {title: 'Deposit', name: 'mobile_number', sort: true},
                ]
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/employees', to.query)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            get('/api/employees', to.query)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set(`Employees`)
                this.$refs.panel.setData(res)
            }
        }
    }
</script>
