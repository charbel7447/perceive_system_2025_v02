<template>
    <div v-if="show" style="padding: 10px;">
                          <iframe style="height: 100%;width: 100%;" src="/system/stock_movement" > </iframe>
                    </div>
</template>
<script type="text/javascript">
    import { get } from '../../lib/api'
    export default {
        computed:{
            base_currency() {
                return window.apex.base_currency
            },
            first_currency(){
                return window.apex.first_currency
            },
            second_currency(){
                return window.apex.second_currency
            },
            first_currency_decimal(){
                 return window.apex.first_currency_decimal
            },
            second_currency_decimal(){
                 return window.apex.second_currency_decimal
            },
            installed_at(){
                 return window.apex.installed_at
            },
            disable_second_currency(){
                 return window.apex.disable_second_currency
            },
            logo_name() {
                return window.apex.logo_name
            },
            company_name() {
                return window.apex.company_name
            },
            company_type() {
                return window.apex.company_type
            },
            app_color() {
                return window.apex.app_color
            },
            license_email() {
                return window.apex.license_email
            },
            nav_color() {
                return window.apex.nav_color
            },
        },
        data() {
            return {
                show: false,
                model: {
                    currency: {}
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/trial_balance_report`)
                .then(res => {
                    next(vm => vm.setData(res))
                })
                // catch 422
        },
        beforeRouteUpdate (to, from, next) {
            this.show = false
            get(`/api/trial_balance_report`)
                .then(res => {
                    this.setData(res)
                    next()
                })
                //catch 422
        },
        methods: {
            setData(res) {
                this.$title.set('Stock Movement')
                this.model = res.data.data
    
                
                this.$bar.finish()
                this.show = true
            },
            
        }
    }
</script>
<style>
    .ac {text-align:center;}
</style>