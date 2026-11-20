<template>
  <div class="comment__votes">
    <button @click="vote('upvote')" :disabled="voting" type="button" class="comment__vote-btn comment__upvote"
      :class="{ 'comment--voted': comment.voted === 'up' }" :title="$t('upvote')"
      :aria-pressed="comment.voted === 'up'">
      <icon type="chevron-up"></icon>
      <span class="comment__upvotes">{{ comment.upvotes || 0 }}</span>
    </button>

    <button @click="vote('downvote')" :disabled="voting" type="button" class="comment__vote-btn comment__downvote"
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

  data: () => ({
    voting: false
  }),

  methods: {
    async vote (action) {
      if (!config.loggedIn) {
        return alert(this.$t('login_required2'))
      }

      if (this.voting) {
        return
      }

      this.voting = true

      try {
        await this.$store.dispatch(action, this.comment)
      } catch (error) {
        alert(this.$t(error.response?.status === 403 ? 'error_403' : 'error_500'))
      } finally {
        this.voting = false
      }
    }
  }
}
</script>
