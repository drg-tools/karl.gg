import Vue from 'vue'
import { HasError } from 'vform/src/components/bootstrap5'
import Icon from './common/Icon.vue'
import Comments from './Comments.vue'
import Dropdown from './common/Dropdown.vue'

Vue.component(Icon.name, Icon)
Vue.component(Dropdown.name, Dropdown)
Vue.component(Comments.name, Comments)
Vue.component(HasError.name, HasError)
