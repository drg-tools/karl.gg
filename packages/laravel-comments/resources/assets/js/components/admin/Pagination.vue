<script>
import Base from '@/components/common/Pagination.vue'

export default {
  extends: Base,

  props: ['pagination', 'page-id', 'commentable-id', 'commentable-type'],

  methods: {
    /**
     * Get page url.
     *
     * @param  {Number} page
     * @return {String}
     */
    getUrl (page) {
      let url = `?page=${page}`

      if (this.pageId) {
        url += `&page_id=${this.pageId}`
      } else if (this.commentableId && this.commentableType) {
        url += `&commentable_id=${this.commentableId}&commentable_type=${this.commentableType}`
      }

      return url
    }
  }
}
</script>
