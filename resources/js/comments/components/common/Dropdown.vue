<template>
  <div class="comments-dropdown" @keydown.esc="close">
    <button ref="toggle" class="comments-dropdown__toggle" type="button" :aria-label="label"
      aria-haspopup="menu" :aria-expanded="open" @click="open = !open">
      <slot name="toggle"></slot>
    </button>

    <div v-if="open" class="comments-dropdown__menu" role="menu" @click="close">
      <slot name="items"></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: 'dropdown',

  props: {
    label: String
  },

  data: () => ({
    open: false
  }),

  watch: {
    open (open) {
      document[open ? 'addEventListener' : 'removeEventListener']('click', this.closeOnOutsideClick)
    }
  },

  beforeUnmount () {
    document.removeEventListener('click', this.closeOnOutsideClick)
  },

  methods: {
    close () {
      if (this.open) {
        this.open = false
        this.$refs.toggle.focus()
      }
    },

    closeOnOutsideClick (event) {
      if (!this.$el.contains(event.target)) {
        this.open = false
      }
    }
  }
}
</script>
