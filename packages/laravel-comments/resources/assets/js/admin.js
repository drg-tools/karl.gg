import Vue from 'vue'
import './util/directives'
import axios from 'axios'
import config from './config'
import VueI18n from 'vue-i18n'
import VueTimeago from 'vue-timeago'
import { HasError, AlertSuccess } from 'vform/src/components/bootstrap5'
import Settings from './components/admin/Settings'
import Dashboard from './components/admin/Dashboard.vue'

Vue.use(VueI18n)
Vue.config.productionTip = false

Vue.component(HasError.name, HasError)
Vue.component(AlertSuccess.name, AlertSuccess)

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
  el: '#app',
  name: 'app',
  i18n,

  components: {
    Settings,
    Dashboard
  },

  methods: {
    logout () {
      axios.post('/comments/admin/logout')
        .then(() => {
          window.location.href = '/'
        })
    },

    toggleNavbar () {
      document.querySelector('.navbar-collapse').classList.toggle('show')
    }
  }
})
