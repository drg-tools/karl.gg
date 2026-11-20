import { createI18n } from 'vue-i18n'
import config from './config'
import Icon from './components/common/Icon.vue'
import Dropdown from './components/common/Dropdown.vue'
import FieldError from './components/common/FieldError.vue'
import Timeago from './components/common/Timeago.vue'
import autosize from './util/directives'

export const i18n = createI18n({
  legacy: false,
  locale: config.locale,
  messages: { [config.locale]: config.translations }
})

export default function configureApp (app) {
  return app.use(i18n)
    .component('field-error', FieldError)
    .component('icon', Icon)
    .component('dropdown', Dropdown)
    .component('timeago', Timeago)
    .directive('autosize', autosize)
}
