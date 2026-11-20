<template>
  <dropdown class="comments__order" label="Sort comments">
    <template #toggle>
      {{ $t('sort_by', { option: $t(`sort_option_${order}`) }) }}
    </template>
    <template #items>
      <button v-for="option in options" @click="select(option)" :key="option" class="comments-dropdown__item" role="menuitem" type="button">
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
