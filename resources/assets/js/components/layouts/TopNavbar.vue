<template>
  <nav class="top_nav_bg_color bg-gray-800 text-white shadow fixed w-full z-50">
    <div class="max-w-9xl mx-auto px-4">
      <div class="flex justify-between h-16 items-center">

        <!-- Left: Logo -->
        <div class="flex items-center flex-shrink-0 mr-auto pl-4x bg-white h-12 rounded">
          <a href="/" target="_top">
            <img 
              :src="logo" 
              alt="Logo" 
              class="h-10 w-auto cursor-pointer" 
            />
          </a>
        </div>

        <!-- Center: Desktop Menu -->
        <div class="hidden md:flex space-x-6 items-center flex-1 justify-center">
          <!-- Sidebar Menu -->
          <div
            v-for="item in activeList"
            :key="item.id"
            class="relative"
            @mouseenter="hoveredItem = item.id"
            @mouseleave="hoveredItem = null"
          >
            <button  class="flex items-center px-3 py-2 rounded hover:bg-gray-700">
              {{ item.title }}
              &nbsp;<i class="fa fa-caret-down ml-1" v-if="item.links && item.links.length"></i>
            </button>

            <!-- Dropdown -->
            <ul
              v-show="item.links && item.links.length && hoveredItem === item.id"
              class="absolute left-0 mt-2 w-48 bg-white text-black rounded shadow-lg z-50 transition-opacity duration-200"
              style="margin-top:0%;min-width:260px;left:-10%;"
            >
              <template v-for="child in item.links">
                <dropdown-item
                  :key="child.id"
                  :item="child"
                  :current-path="currentPath"
                />
              </template>
            </ul>
          </div>

          <!-- Quick Menus -->
          <div class="flex space-x-2 items-center">
            <button
              v-for="(quick, index) in quickMenus"
              :key="index"
              class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-sm"
              @click="openQuickMenu(quick)"
            >
              {{ quick.name }}
            </button>
          </div>
        </div>

        <!-- Right: User Dropdown -->
        <div
          class="relative ml-auto pr-4"
          @mouseenter="userOpen = true"
          @mouseleave="userOpen = false"
        >
          <button class="flex items-center px-3 py-2 rounded hover:bg-gray-700">
            <span>{{ user.name }}</span>
            &nbsp;<i class="fa fa-caret-down ml-1"></i>
          </button>
          <ul
            v-show="userOpen"
            class="absolute right-0 mt-2 w-48 bg-white text-black rounded shadow-lg z-50 transition-opacity duration-200"
            style="margin-top:0%;"
          >
            <li>
              <router-link
                to="/notifications"
                class="block px-4 py-2 hover:bg-gray-200"
              >Notifications</router-link>
            </li>
            <li>
              <router-link
                to="/personal-settings"
                class="block px-4 py-2 hover:bg-gray-200"
              >Personal Settings</router-link>
            </li>
            <li>
              <a href="/logout" class="block px-4 py-2 hover:bg-gray-200">Logout</a>
            </li>
          </ul>
        </div>

        <!-- Mobile Hamburger -->
        <div class="md:hidden flex items-center">
          <button @click="mobileOpen = !mobileOpen">
            <i class="fa fa-bars text-xl"></i>
          </button>
        </div>

      </div>
    </div>
    <!-- (Mobile Menu unchanged) -->
  </nav>
</template>

<script>
const DropdownItem = {
  name: 'DropdownItem',
  props: ['item', 'currentPath'],
  data() {
    return {
      open: false
    };
  },
  template: `
    <li @mouseenter="open = true" @mouseleave="open = false" class="relative">
      <router-link
        v-if="!item.links || item.links.length === 0"
        :to="item.path || '#'"
        class="block px-4 py-2 hover:bg-gray-200"
        :class="currentPath === item.path ? 'bg-gray-300 font-semibold' : ''"
      >
        <i class="fa" :class="item.icon + ' mr-2'"></i> {{ item.title }}
      </router-link>

      <div v-else>
        <button class="flex justify-betweenx items-center w-full px-4 py-2 hover:bg-gray-200">
          <i class="fa" :class="item.icon + ' mr-2'"></i> {{ item.title }}
          <i class="fa fa-caret-right absolute top-1 right-2"></i>
        </button>

        <ul
          v-show="open" style="min-width: 245px;"
          class="absolute left-full top-0 mt-0 w-48 bg-white text-black rounded shadow-lg z-50 transition-all duration-200"
        >
          <dropdown-item
            v-for="child in item.links"
            :key="child.id"
            :item="child"
            :current-path="currentPath"
          />
        </ul>
      </div>
    </li>
  `,
  components: { DropdownItem: null }
};
DropdownItem.components.DropdownItem = DropdownItem;

const MobileDropdownItem = {
  name: 'MobileDropdownItem',
  props: ['item', 'currentPath'],
  data() { return { open: false }; },
  template: `
    <li>
      <div v-if="item.links && item.links.length">
        <button @click="open = !open" class="w-full text-left px-6 py-2 hover:bg-gray-500 flex justify-between items-center">
          {{ item.title }}
          <i class="fa" :class="open ? 'fa-caret-up' : 'fa-caret-down'"></i>
        </button>
        <ul v-show="open" class="bg-gray-500">
          <mobile-dropdown-item v-for="child in item.links" :key="child.id" :item="child" :current-path="currentPath" />
        </ul>
      </div>
      <router-link v-else :to="item.path || '#'" class="block px-6 py-2 hover:bg-gray-500" :class="currentPath === item.path ? 'bg-gray-500 font-semibold' : ''">
        <i class="fa" :class="item.icon + ' mr-2'"></i> {{ item.title }}
      </router-link>
    </li>
  `,
  components: { MobileDropdownItem: null }
};
MobileDropdownItem.components.MobileDropdownItem = MobileDropdownItem;

export default {
  name: 'TopNavbar',
  components: { DropdownItem, MobileDropdownItem },
  props: {
    logo: { type: String, required: true },
    quickMenus: { type: Array, default: () => [] },
    user: { type: Object, default: () => ({ name: 'Guest', avatar: null }) }
  },
  data() {
    return {
      mobileOpen: false,
      userMobileOpen: false,
      hoveredItem: null,
      lists: window.apex.sidebar_lists || [],
      userOpen: false,
      mobileOpenItems: {}
    };
  },
  computed: {
    currentPath() { return this.$route.path; },
    activeList() { return this.lists || []; }
  },
  methods: {
    toggleMobile(id) { this.$set(this.mobileOpenItems, id, !this.mobileOpenItems[id]); },
    openQuickMenu(item) { window.open(item.link, item.type || '_blank', "toolbar=no,width=1224,height=800,resizable"); }
  }
};
</script>

<style scoped>
.navbar-logo { height: 40px; width: auto; display: block; }
</style>
