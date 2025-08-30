<template>
  <a
    :href="`/journal_vouchers/${journalId}/`"
    :class="['status', `status-${status}`]"
  >
    {{ clean(status) }}
  </a>
</template>

<script>
export default {
  props: {
    id: {
      type: Number,
      required: true,
    },
    journalId: {
      type: [Number, String],
      required: true,
    },
  },
  computed: {
    status() {
      return this.statusLookup(this.id);
    },
  },
  methods: {
    statusLookup(id) {
      const list = {
        0: 'JV-Created',
        1: 'JV-POSTED',
      };
      return list[id] || 'UNKNOWN';
    },
    clean(str) {
      return str
        .replace(/(\-[a-z])/g, (match) => match.replace('-', ' '))
        .toUpperCase();
    },
  },
};
</script>
