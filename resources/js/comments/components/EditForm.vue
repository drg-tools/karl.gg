<template>
  <form @submit.prevent="save" @keydown="form.errors.clear($event.target.name)" class="comment__edit-form">
    <div class="comments-field">
      <editor v-model="form.content" :invalid="form.errors.has('content')" @submit="save"></editor>
      <field-error :form="form" field="content"></field-error>
    </div>

    <div class="comments-actions">
      <button type="submit" class="comments-button" :disabled="form.busy || form.content.length === 0">
        {{ $t('update_button') }}
      </button>

      <button type="button" class="comments-button is-secondary" @click="close">
        {{ $t('cancel_button') }}
      </button>
    </div>
  </form>
</template>

<script>
import Form from 'vform'
import Editor from './Editor.vue'

export default {
  props: ['comment'],

  components: {
    Editor
  },

  data: () => ({
    form: new Form({
      content: ''
    })
  }),

  created () {
    this.form.content = this.comment.content
  },

  methods: {
    save () {
      this.form.patch(`/comments/${this.comment.id}`)
        .then(this.onSuccess)
        .catch(this.onError)
    },

    onSuccess ({ data }) {
      this.$store.commit('UPDATE_COMMENT', { data, comment: this.comment })

      this.$nextTick(() => this.$parent.closeEdit(data))
    },

    onError ({ response: { status }}) {
      if (status === 403) {
        this.$store.commit('UPDATE_COMMENT', {
          comment: this.comment,
          data: { can_update: false }
        })
        this.form.errors.set({ content: this.$t('error_edit') })
      } else if ([401, 404, 500].includes(status)) {
        this.form.errors.set({ content: this.$t(`error_${status}`) })
      }
    },

    close () {
      this.$parent.closeEdit()
    }
  }
}
</script>
