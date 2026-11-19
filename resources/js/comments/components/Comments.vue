<template>
  <div class="comments">
    <div class="comments__toolbar">
      <p class="comments__total">
        {{ $t('comments_count', total) }}
      </p>

      <order-dropdown></order-dropdown>
    </div>

    <post-form></post-form>

    <div v-if="loading && comments.length === 0" class="comments__spinner" role="status"></div>

    <transition-group tag="div" name="fade" class="comment__list" :class="{ ['comment__list--loading']: loading }">
      <comment v-for="comment in comments" :key="comment.id" :comment="comment"></comment>
    </transition-group>

    <pagination></pagination>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { scrollTo } from '@/util'
import Comment from './Comment.vue'
import PostForm from './PostForm.vue'
import Pagination from './Pagination.vue'
import OrderDropdown from './OrderDropdown.vue'

export default {
  name: 'comments',

  components: {
    Comment,
    PostForm,
    Pagination,
    OrderDropdown
  },

  computed: mapGetters({
    total: 'total',
    target: 'target',
    loading: 'loading',
    comments: 'comments'
  }),

  created () {
    const target = this.target

    this.$store.dispatch('fetch', { target }).then(() => {
      if (target) {
        this.$store.commit('SET_TARGET', target)
        this.$nextTick(() => {
          const comment = document.getElementById(`comment-${target}`)

          if (comment) {
            scrollTo(comment)
          }
        })
      }
    })
  }
}
</script>
