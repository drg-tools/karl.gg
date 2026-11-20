<template>
  <div class="comment__editor">
    <div class="comment__tabs" role="tablist">
      <button type="button" role="tab" class="comment__tab" :aria-selected="!preview" @click="hidePreview">
        {{ $t('write_tab') }}
      </button>
      <button type="button" role="tab" class="comment__tab" :aria-selected="preview" @click="renderPreview">
        {{ $t('preview_tab') }}
      </button>
    </div>

    <div v-show="!preview" class="comment__write">
      <textarea
        required
        v-autosize
        wrap="hard"
        name="content"
        ref="textarea"
        class="comments-input"
        :class="{ 'is-invalid': invalid }"
        v-model="content"
        :placeholder="placeholder ?? $t('your_comment')"
        :aria-label="placeholder ?? $t('your_comment')"
        @keydown.ctrl.enter="$emit('submit')"
        @keydown.meta.enter="$emit('submit')"
      ></textarea>

      <div v-if="maxLength" class="comment__counter">
        {{ counter }}
      </div>
    </div>

    <div v-show="preview" ref="preview" class="comment__preview comment__message"></div>
  </div>
</template>

<script>
import api from '@/api'
import config from '@/config'
import { length, limit } from 'stringz'
import { highlight } from '@/util'

export default {
  emits: ['update:modelValue', 'submit'],

  props: {
    modelValue: {
      required: true
    },
    placeholder: String,
    invalid: Boolean
  },

  data: () => ({
    content: '',
    preview: false,
    prevContent: '',
    maxLength: config.maxLength
  }),

  computed: {
    counter () {
      return config.maxLength - (this.content !== '' ? length(this.content) : 0)
    }
  },

  watch: {
    modelValue (val) {
      if (this.content !== val) {
        this.content = val
      }
    },

    content (val) {
      if (val === '' || !config.maxLength) {
        this.$emit('update:modelValue', val)
        return
      }

      if (length(val) > config.maxLength) {
        this.content = limit(this.content, config.maxLength)
      }

      this.$emit('update:modelValue', this.content)
    }
  },

  created () {
    this.content = this.modelValue
  },

  mounted () {
    this.$refs.textarea.focus()
  },

  methods: {
    hidePreview () {
      this.preview = false
      this.$nextTick(() => this.$refs.textarea.focus())
    },

    renderPreview () {
      this.preview = true

      const preview = this.$refs.preview

      if (this.content === '') {
        preview.innerHTML = this.$t('no_preview')
      }

      if (this.prevContent === this.content) {
        return
      }

      preview.innerHTML = this.$t('loading_preview')

      api.preview(this.content)
        .then(({ data }) => {
          preview.innerHTML = data.length > 0 ? data : this.$t('no_preview')

          this.$nextTick(() => highlight(preview))

          this.prevContent = this.content
        })
        .catch(() => {
          preview.textContent = 'Could not render preview.'
        })
    }
  }
}
</script>
