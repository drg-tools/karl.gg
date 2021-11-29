<template>
  <div class="comment__votes d-inline">
    <span class="comment__upvotes">
      {{ comment.upvotes || '' }}
    </span>

    <button @click="upvote" type="button" class="comment__vote-btn comment__upvote btn btn-link"
      :class="{ 'comment--voted': comment.voted === 'up' }" :title="$t('upvote')">
      <icon type="chevron-up"></icon>
    </button>

    <span class="comment__votes__sep"></span>

    <span class="comment__downvotes">
      {{ comment.downvotes || '' }}
    </span>

    <button @click="downvote" type="button" class="comment__vote-btn comment__downvote btn btn-link"
      :class="{ 'comment--voted': comment.voted === 'down' }" :title="$t('downvote')">
      <icon type="chevron-down"></icon>
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
