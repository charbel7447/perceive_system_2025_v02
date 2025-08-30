<template>
  <div v-if="show" style="height: 100%; width: 100%; padding: 10px;">
    <iframe
      v-if="iframeSrc"
      :src="iframeSrc"
      style="height: 100%; width: 100%; border: none;"
    ></iframe>
    <div v-else>Loading...</div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      show: false,
      iframeSrc: ''
    }
  },
  mounted() {
    this.setIframe()
  },
  watch: {
    '$route.path'() {
      this.setIframe()
    }
  },
  methods: {
    setIframe() {
      // Remove leading slash
      const link = this.$route.path.replace(/^\//, '')
      // Build backend URL
      this.iframeSrc = `/system/general_view/${link}`
      this.show = true
      // Optional: set page title dynamically
      this.$title.set(link.replace(/_/g, ' ').toUpperCase())
    }
  }
}
</script>

<style scoped>
/* Optional styling */
</style>
