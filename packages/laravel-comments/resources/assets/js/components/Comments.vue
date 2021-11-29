<template>
  <div class="comments">
    <div class="clearfix mb-2">
      <order-dropdown></order-dropdown>

      <div class="comments__total">
        {{ $tc('comments_count', total === 0 ? 0 : (total === 1 ? 1 : 2), { count: total }) }}
      </div>
    </div>

    <post-form v-if="canPost" class="mt-1"></post-form>
    <div v-else class="alert alert-warning" v-html="$t('login_required')"></div>

    <div v-if="loading" class="comments__spinner"></div>

    <transition-group tag="div" name="fade" class="comment__list" :class="{ ['comment__list--loading']: loading }">
      <comment v-for="comment in comments" :key="comment.id" :comment="comment"></comment>
    </transition-group>

    <pagination></pagination>
  </div>
</template>

<script>
import echo from '@/echo'
import config from '@/config'
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

  data: () => ({
    canPost: config.canPost
  }),

  computed: mapGetters({
    page: 'page',
    total: 'total',
    target: 'target',
    loading: 'loading',
    comments: 'comments'
  }),

  created () {
    const data = config.data.comments

    if (data.comments.length === 0 && data.total > 0) {
      this.$store.dispatch('fetch', { page: 1 })
    } else {
      this.$store.commit('SET_COMMENTS', config.data.comments)
    }

    if (config.broadcast && echo) {
      this.listen()
    }
  },

  mounted () {
    if (this.target) {
      setTimeout(() => {
        const comment = document.getElementById(`comment-${this.target}`)
        if (comment) {
          this.scrollTo(comment.offsetTop)
        }
      }, 100)
    }
  },

  methods: {
    listen () {
      echo.channel(`page_${config.pageHash}`)
        .listen('BroadcastCommentWasPosted', e => {
          this.$store.commit('ADD_COMMENT', e.comment)
        })
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
    }
  }
}
</script>
