<template>
  <base-pagination :pagination="pagination" :get-url="getUrl" :on-click="onClick" />
</template>

<script>
import config, { mountElement } from '@/config'
import { mapGetters } from 'vuex'
import { scrollTo } from '@/util'
import BasePagination from './common/Pagination.vue'

export default {
  components: { BasePagination },

  computed: mapGetters({
    page: 'page',
    pagination: 'pagination'
  }),

  methods: {
    /**
     * @param {Event} e
     * @param {Number} page
     */
    onClick (e, page) {
      e.preventDefault()

      if (!page || page === this.page) {
        return
      }

      this.$store.dispatch('fetch', { page })
        .then(() => scrollTo(mountElement))
    },

    /**
     * @param  {Number} page
     * @return {String}
     */
    getUrl (page) {
      return `${config.permalink}#!page=${page}`
    }
  }
}
</script>
