<template>
  <span class="svg-icon align-middle" aria-hidden="true" v-html="icons[`../../icons/${type}.svg`]"></span>
</template>

<style scoped>
.svg-icon :deep(svg) {
  width: 0.75em;
  height: 0.75em;
}
</style>

<script>
const icons = import.meta.glob('../../icons/*.svg', { eager: true, query: '?raw', import: 'default' })

export default {
  name: 'icon',

  data: () => ({ icons }),

  props: {
    type: {
      type: String,
      required: true
    }
  }
}
</script>
