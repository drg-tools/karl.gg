<template>
  <dropdown class="comments__order float-end">
    <template slot="toggle">
      {{ $t('sort_by', { option: $t(`sort_option_${order}`) }) }}
    </template>
    <template slot="items">
      <button v-for="option in options" @click="select(option)" :key="option" class="dropdown-item" type="button">
        {{ $t(`sort_option_${option}`) }}
      </button>
    </template>
  </dropdown>
</template>

<script>
import config from '@/config'
import { mapGetters } from 'vuex'

export default {
  computed: mapGetters({
    order: 'order'
  }),

  data: () => ({
    options: config.orderOptions
  }),

  methods: {
    select (order) {
      this.$store.dispatch('fetch', { order, page: 1 })
    }
  }
}
</script>
