<template>
  <form @submit.prevent="post" @keydown="form.errors.clear($event.target.name)" class="comment__post-form">
    <div v-if="expanded && !loggedIn" class="comment__guest-fields">
      <div class="comments-field">
        <input v-model="form.author_name" type="text" name="author_name" class="comments-input"
          :class="{ 'is-invalid': form.errors.has('author_name') }"
          :placeholder="$t('author_name')" :aria-label="$t('author_name')" autocomplete="name" required>
        <field-error :form="form" field="author_name"></field-error>
      </div>

      <div class="comments-field">
        <input v-model="form.author_email" type="email" name="author_email" class="comments-input"
          :class="{ 'is-invalid': form.errors.has('author_email') }"
          :placeholder="$t('author_email')" :aria-label="$t('author_email')" autocomplete="email" required>
        <field-error :form="form" field="author_email"></field-error>
      </div>

      <div class="comments-field">
        <input v-model="form.author_url" type="url" name="author_url" class="comments-input"
          :class="{ 'is-invalid': form.errors.has('author_url') }"
          :placeholder="$t('author_url')" :aria-label="$t('author_url')" autocomplete="url">
        <field-error :form="form" field="author_url"></field-error>
      </div>
    </div>

    <div class="comments-field">
      <editor v-if="expanded" v-model="form.content" :invalid="form.errors.has('content')" @submit="post"></editor>
      <textarea v-else @focus="expanded = true" class="comments-input comment__prompt"
        :placeholder="$t('leave_a_comment')" :aria-label="$t('leave_a_comment')"></textarea>
      <field-error :form="form" field="content"></field-error>
    </div>

    <div v-if="expanded" class="comments-actions">
      <button type="submit" class="comments-button" :disabled="form.busy || form.content.length === 0">
        {{ $t('post_button') }}
      </button>
      <button type="button" class="comments-button is-secondary" @click="close">
        {{ $t('cancel_button') }}
      </button>
    </div>
  </form>
</template>

<script>
import Form from 'vform'
import config from '@/config'
import { authorRecaller } from '@/util'
import Editor from './Editor.vue'

export default {
  props: ['comment'],

  components: {
    Editor
  },

  data: () => ({
    form: new Form({
      author_name: authorRecaller.get('name'),
      author_email: authorRecaller.get('email'),
      author_url: authorRecaller.get('url'),
      content: '',
      page_id: config.pageId,
      permalink: config.permalink
    }),

    expanded: false,
    loggedIn: config.loggedIn
  }),

  beforeMount () {
    if (this.comment) {
      this.expanded = true
    }
  },

  methods: {
    post () {
      if (this.comment) {
        this.form.parent_id = this.comment.id
      }

      this.form.post('/comments')
        .then(this.onSuccess)
        .catch(this.onError)
    },

    onSuccess ({ data }) {
      this.close()

      this.rememberAuthor()

      this.$store.commit('ADD_COMMENT', data)
    },

    onError (error) {
      if (error.response && [401, 403, 404, 500].includes(error.response.status)) {
        this.form.errors.set({ content: this.$t(`error_${error.response.status}`) })
      }
    },

    close () {
      this.expanded = false
      this.form.content = ''

      if (this.$parent.reply) {
        this.$parent.reply()
      }
    },

    rememberAuthor () {
      if (this.form.author_name) {
        authorRecaller.put({
          name: this.form.author_name,
          email: this.form.author_email,
          url: this.form.author_url
        })
      }
    }
  }
}
</script>
