<template>
  <div class="main">
    <!-- Top Navbar -->
    <TopNavbar 
      :menu-lists="menuLists"
      :logo="logo_name"
      :notifications="notifications"
      :user="user"
      :copyrights="copyrights"
    />

    <div class="main-content">
      <div class="main-view" id="main-view" v-if="installed_at == fiscal_year">
        <transition name="fade" mode="out-in">
          <router-view class="layout" :key="$route.path"></router-view>
        </transition>
      </div>
      <div v-else style="width: 90%;">
        <div class="col col-12" style="margin: 25% auto;">
          <p style="color:red;text-align:center;">
            <strong>License Expired, Feel free and contact Us</strong>
          </p>
          <p style="color:#000;text-align:center;">
            <strong><a :href="`mailto:${license_email}`">{{ license_email }}</a></strong>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TopNavbar from './components/layouts/TopNavbar.vue'

export default {
  components: {
    TopNavbar
  },
  computed: {
    user() { return window.apex.user },
    logo_name() { return window.apex.logo_name },
    fiscal_year() { return window.apex.fiscal_year },
    installed_at() { return window.apex.installed_at },
    notifications() { return window.apex.notifications },
    quick_menus() { return window.apex.quick_menus },
    copyrights() { return window.apex.copyrights },
    license_email() { return window.apex.license_email },
    sidebar_lists() { return window.apex.sidebar_lists },
  },
  data() {
    return {
      menuLists: this.prepareMenuLists(),
      sharedState: window.apex,
    }
  },
  methods: {
    prepareMenuLists() {
      // copy your Sidebar.vue lists logic
      const lists = window.apex.sidebar_lists || []
      const user = window.apex.user
      return lists.filter(item => 
        (item.settings_tab && user.is_settings_tab) ||
        (item.sales_tab && user.is_sales_tab) ||
        (item.procurment_tab && user.is_procurment_tab) ||
        (item.accounting_tab && user.is_accounting_tab) ||
        (item.production_tab && user.is_production_tab) ||
        (item.dashboard_tab && user.is_dashboard) ||
        (item.admin && user.is_admin) ||
        (item.company_tab && user.is_company_tab)
      )
    }
  }
}
</script>

<style>
body {
  font-family: Lato;
}
.main-content {
  padding-top: 64px; /* space for top nav */
}
</style>
