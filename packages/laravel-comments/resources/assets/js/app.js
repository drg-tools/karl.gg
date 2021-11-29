import Vue from 'vue'
import VueI18n from 'vue-i18n'
import VueTimeago from 'vue-timeago'
import 'iframe-resizer/js/iframeResizer.contentWindow'

import './components'
import './util/directives'
import store from './store'
import config from './config'

Vue.use(VueI18n)
Vue.config.productionTip = false

const i18n = new VueI18n({
  locale: config.locale,
  messages: {
    [config.locale]: config.translations
  }
})

Vue.use(VueTimeago, {
  name: 'timeago',
  locale: config.locale,
  locales: {
    [config.locale]: config.translations.timeago
  }
})

new Vue({
  store,
  i18n,
  el: '#app',
  name: 'app'
})
