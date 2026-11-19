<template>
  <div class="comment__votes">
    <button @click="upvote" type="button" class="comment__vote-btn comment__upvote"
      :class="{ 'comment--voted': comment.voted === 'up' }" :title="$t('upvote')"
      :aria-pressed="comment.voted === 'up'">
      <icon type="chevron-up"></icon>
      <span class="comment__upvotes">{{ comment.upvotes || 0 }}</span>
    </button>

    <button @click="downvote" type="button" class="comment__vote-btn comment__downvote"
      :class="{ 'comment--voted': comment.voted === 'down' }" :title="$t('downvote')"
      :aria-pressed="comment.voted === 'down'">
      <icon type="chevron-down"></icon>
      <span class="comment__downvotes">{{ comment.downvotes || 0 }}</span>
    </button>
  </div>
</template>

<script>
import config from '@/config'

export default {
  props: ['comment'],

  methods: {
    upvote () {
      if (!config.loggedIn) {
        return alert(this.$t('login_required2'))
      }

      this.$store.dispatch('upvote', this.comment)
    },

    downvote () {
      if (!config.loggedIn) {
        return alert(this.$t('login_required2'))
      }

      this.$store.dispatch('downvote', this.comment)
    }
  }
}
</script>
