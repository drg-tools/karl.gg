import Vue from 'vue'
import autosize from 'autosize'

Vue.directive('autosize', {
  bind (el) {
    autosize(el)
  },

  unbind (el) {
    autosize.destroy(el)
  }
})
