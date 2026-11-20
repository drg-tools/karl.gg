import autosize from 'autosize'

export default {
  mounted (el) {
    autosize(el)
  },

  unmounted (el) {
    autosize.destroy(el)
  }
}
