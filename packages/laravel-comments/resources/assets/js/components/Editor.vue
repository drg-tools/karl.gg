<template>
  <div class="comment__editor">
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <span @click="hidePreview" :class="{ active: !preview }" class="nav-link">
          {{ $t('write_tab') }}
        </span>
      </li>
      <li class="nav-item">
        <span @click="renderPreview" :class="{ active: preview }" class="nav-link" >
          {{ $t('preview_tab') }}
        </span>
      </li>
    </ul>

    <div v-show="!preview" class="comment__write">
      <textarea
        required
        v-autosize
        wrap="hard"
        name="content"
        ref="textarea"
        class="form-control"
        v-model="content"
        :placeholder="placeholder"
        @keyup.ctrl.enter="$emit('submit')"
      ></textarea>

      <div v-if="maxLength" class="comment__counter float-end mt-1">
        {{ counter }}
      </div>
    </div>

    <div v-show="preview" ref="preview" class="comment__preview comment__message py-2"></div>
  </div>
</template>

<script>
import api from '@/api'
import config from '@/config'
import { length, limit } from 'stringz'
import { emojify, highlight } from '@/util'

export default {
  props: {
    value: {
      required: true
    },
    placeholder: {
      default () {
        return this.$t('your_comment')
      }
    }
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
    value (val) {
      if (this.content !== val) {
        this.content = val
      }
    },

    content (val) {
      if (val === '' || !config.maxLength) {
        this.$emit('input', val)
        return
      }

      if (length(val) > config.maxLength) {
        this.content = limit(this.content, config.maxLength)
      }

      this.$emit('input', this.content)
    }
  },

  created () {
    this.content = this.value
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
          preview.innerHTML = data.length > 0 ? emojify(data) : this.$t('no_preview')

          this.$nextTick(() => highlight(preview))

          this.prevContent = this.content
        })
        .catch(() => {
          this.prevent.innerHTML = 'Could not render preview.'
        })
    }
  }
}
</script>
