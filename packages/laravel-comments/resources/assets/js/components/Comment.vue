<template>
  <div :id="`comment-${comment.id}`" class="comment__item"
    :class="{ ['comment__item--collapsed']: comment.collapsed }">
    <div class="comment__content clearfix" :class="{ 'comment__content--target': target === comment.id }">
      <div class="comment__indicator"></div>

      <div v-if="comment.author_avatar" class="comment__author-avatar">
        <a :href="comment.author_url" target="_blank">
          <img :src="comment.author_avatar" :alt="comment.author_name">
        </a>
      </div>

      <div class="comment__body">
        <div class="comment__header">
          <a v-if="comment.author_url" :href="comment.author_url" target="_blank" class="comment__author-name">
            {{ comment.author_name }}
          </a>
          <span v-else class="comment__author-name">
            {{ comment.author_name }}
          </span>

          <a v-if="parent" :href="`${permalink}#!comment=${parent.id}`" @click.prevent="setTarget(parent)"
            :title="$t('in_reply_to', { name: parent.author_name })" class="comment__author-reply">
            <icon type="share"></icon>
            {{ parent.author_name }}
          </a>

          <a :href="`${permalink}#!comment=${comment.id}`" class="comment__timeago"
            @click.prevent="setTarget(comment)">
            <timeago :since="comment.created_at" :title="comment.created_at"></timeago>
          </a>

          <comment-menu :comment="comment"></comment-menu>
        </div>

        <div v-show="!editComment" class="comment__body__inner">
          <div v-if="!approved" class="comment__pending text-danger">
            {{ $t('awaiting_moderation') }}
          </div>
          <div v-if="comment.reported" class="text-danger pt-1">{{ $t('reported') }}</div>
          <div v-else class="comment__message py-1" v-html="emojify(comment.content_html)"></div>
        </div>

        <div v-if="!editComment && !comment.reported" class="comment__footer">
          <votes v-if="allowVotes" :comment="comment" class="me-1"></votes>

          <button v-if="allowReplies && approved && canPost" @click="reply" type="button" class="btn btn-link p-0">
            {{ $t('reply_button') }}
          </button>
        </div>

        <edit-form v-if="editComment" :comment="editComment" class="mt-2"></edit-form>
      </div>

      <post-form v-if="showReply" :comment="comment" class="mt-2"></post-form>
    </div>

    <div v-if="hasReplies && !comment.reported" class="comment__list comment__list--children">
      <comment v-for="_comment in comment.replies" :key="_comment.id" :comment="_comment" :parent="comment" class="mt-2"></comment>
    </div>
  </div>
</template>

<script>
import config from '@/config'
import { mapGetters } from 'vuex'
import { highlight, emojify } from '@/util'
import Votes from './Votes.vue'
import PostForm from './PostForm.vue'
import EditForm from './EditForm.vue'
import CommentMenu from './CommentMenu.vue'

export default {
  name: 'comment',

  props: ['comment', 'parent'],

  components: {
    Votes,
    PostForm,
    EditForm,
    CommentMenu
  },

  data: () => ({
    showReply: false,
    editComment: null,
    canPost: config.canPost,
    permalink: config.permalink,
    allowVotes: config.allowVotes,
    allowReplies: config.allowReplies
  }),

  computed: {
    ...mapGetters({
      target: 'target'
    }),

    approved () {
      return this.comment.status === 'approved'
    },

    hasReplies () {
      return this.comment.replies.length > 0
    }
  },

  mounted () {
    highlight(this.$el)
  },

  methods: {
    emojify,

    setTarget ({ id }) {
      this.$store.commit('SET_TARGET', id)
    },

    reply () {
      this.showReply = !this.showReply
    },

    closeEdit (data) {
      this.editComment = null

      if (data) {
        highlight(this.$el)
      }
    }
  }
}
</script>
