<template>
  <form @submit.prevent="post" @keydown="form.errors.clear($event.target.name)" class="comment__post-form">
    <div v-if="expanded && !loggedIn" class="row">
      <div class="col-sm-8 col-md-5 col-lg-4 col-xl-3">
        <template v-if="!loggedIn">
          <div class="mb-3" :class="{ 'has-danger': form.errors.has('author_name') }">
            <input v-model="form.author_name" type="text" name="author_name"
              class="form-control" :placeholder="$t('author_name')" required>
            <has-error :form="form" field="author_name"></has-error>
          </div>

          <div class="mb-3" :class="{ 'has-danger': form.errors.has('author_email') }">
            <input v-model="form.author_email" type="email" name="author_email"
              class="form-control" :placeholder="$t('author_email')" required>
            <has-error :form="form" field="author_email"></has-error>
          </div>

          <div class="mb-3" :class="{ 'has-danger': form.errors.has('author_url') }">
            <input v-model="form.author_url" type="text" name="author_url"
              class="form-control" :placeholder="$t('author_url')">
            <has-error :form="form" field="author_url"></has-error>
          </div>
        </template>
      </div>
    </div>

    <div v-if="expanded && requiresCaptcha" class="mb-3"
      :class="{ 'has-danger': form.errors.has('captcha') }">
      <recaptcha ref="recaptcha"></recaptcha>
      <has-error :form="form" field="captcha"></has-error>
    </div>

    <div class="mb-3" :class="{ 'has-danger': form.errors.has('content') }">
      <editor v-if="expanded" v-model="form.content" @submit="post"></editor>
      <textarea v-else @focus="expanded = true" class="form-control"
        :placeholder="$t('leave_a_comment')"></textarea>
      <has-error :form="form" field="content"></has-error>
    </div>

    <div v-if="expanded" class="mb-3 clearfix">
      <button type="submit" class="btn btn-light" :disabled="form.busy || form.content.length === 0">
        {{ $t('post_button') }}
      </button>
      <button type="button" class="btn btn-secondary" @click="close">
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
import Recaptcha from './common/Recaptcha'

export default {
  props: ['comment'],

  components: {
    Editor,
    Recaptcha
  },

  data: () => ({
    form: new Form({
      author_name: authorRecaller.get('name'),
      author_email: authorRecaller.get('email'),
      author_url: authorRecaller.get('url'),
      captcha: '',
      content: '',
      page_id: config.pageId,
      commentable_id: config.commentableId,
      commentable_type: config.commentableType,
      permalink: config.permalink
    }),

    expanded: false,
    loggedIn: config.loggedIn,
    maxLength: config.maxLength
  }),

  beforeMount () {
    if (this.comment) {
      this.expanded = true
    }
  },

  computed: {
    requiresCaptcha () {
      return (!this.loggedIn && config.captchaGuest) || (this.loggedIn && config.captchaAuth)
    }
  },

  methods: {
    post () {
      if (this.requiresCaptcha) {
        this.form.captcha = this.$refs.recaptcha.getResponse()
      }

      if (this.comment) {
        this.form.parent_id = this.comment ? this.comment.id : null
        this.form.root_id = this.comment ? (this.comment.root_id || this.comment.id) : null
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
