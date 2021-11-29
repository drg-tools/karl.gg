<script>
import config from '@/config'
import { mapGetters } from 'vuex'
import { scrollTo } from '@/util'
import Base from './common/Pagination.vue'

export default {
  extends: Base,

  computed: mapGetters({
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
        .then(() => this.scrollTo(0))
    },

    /**
     * @param {Number} position
     */
    scrollTo (position) {
      if (window.parentIFrame) {
        window.parentIFrame.sendMessage({
          type: 'scroll', position
        })
      } else {
        scrollTo(position)
      }
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
