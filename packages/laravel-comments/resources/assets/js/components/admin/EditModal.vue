<template>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div v-if="comment" class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ $t('edit_comment_modal_title') }}</h5>
          <button type="button" class="btn-close" @click="close" aria-label="Close"></button>
        </div>
        <form @submit.prevent="save" @keydown="form.errors.clear($event.target.name)">
          <div class="modal-body">
            <alert-success :form="form" :message="$t('comment_saved')"></alert-success>

            <!-- Author Name -->
            <div class="mb-3" :class="{ 'has-danger': form.errors.has('author_name') }">
              <label for="author_name">{{ $t('author_name') }}</label>
              <input v-model="form.author_name" type="text" name="author_name" id="author_name" class="form-control" required>
              <has-error :form="form" field="author_name"></has-error>
            </div>

            <!-- Author Email -->
            <div class="mb-3" :class="{ 'has-danger': form.errors.has('author_email') }">
              <label for="author_email">{{ $t('author_email') }}</label>
              <input v-model="form.author_email" type="email" name="author_email" id="author_email" class="form-control" required>
              <has-error :form="form" field="author_email"></has-error>
            </div>

            <!-- Author Url -->
            <div class="mb-3" :class="{ 'has-danger': form.errors.has('author_url') }">
              <label for="author_url">{{ $t('author_url') }}</label>
              <input v-model="form.author_url" type="text" name="author_url" id="author_url" class="form-control">
              <has-error :form="form" field="author_url"></has-error>
            </div>

            <!-- Comment Status -->
            <div class="mb-3">
              <label for="comment_status">{{ $t('comment_status') }}</label>
              <select v-model="form.status" id="comment_status" name="status" class="form-select">
                <option v-for="status in ['pending', 'approved', 'spam', 'trash']" :value="status" :key="status">
                  {{ $t(`status_${status}`) }}
                </option>
              </select>
              <has-error :form="form" field="status"></has-error>
            </div>

            <!-- Comment Content -->
            <div class="mb-3 mb-0 clearfix" :class="{ 'has-danger': form.errors.has('content') }">
              <editor v-model="form.content" @submit="save" :placeholder="$t('comment_content')"></editor>
              <has-error :form="form" field="content"></has-error>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" :disabled="form.busy" class="btn btn-success">
              {{ $t('modal_save_changes') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import Form from 'vform'
import Editor from '../Editor.vue'

export default {
  components: {
    Editor
  },

  data: () => ({
    comment: null,

    form: new Form({
      author_name: '',
      author_email: '',
      author_url: '',
      status: '',
      content: ''
    })
  }),

  methods: {
    /**
     * Save the comment.
     */
    save () {
      this.form.patch(`/comments/${this.comment.id}`)
        .then(() => this.$emit('updated'))
    },

    /**
     * Show the edit modal.
     *
     * @param {Object} comment
     */
    show (comment) {
      this.comment = comment

      Object.keys(this.form.data()).forEach(key => {
        this.form[key] = comment[key]
      })

      const backdrop = document.createElement('div')
      backdrop.className = 'modal-backdrop fade show'
      document.body.appendChild(backdrop)

      this.$el.style.display = 'block'
      document.body.classList.add('modal-open')
      this.$el.classList.add('show')
    },

    /**
     * Close the edit modal.
     */
    close () {
      this.$el.classList.remove('show')
      document.body.classList.remove('modal-open')
      this.$el.style.display = 'none'

      const backdrop = document.querySelector('.modal-backdrop')
      backdrop.parentNode.removeChild(backdrop)

      this.form.reset()
      this.comment = null
      window.location.hash = ''
    }
  }
}
</script>
