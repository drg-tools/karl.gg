import { createApp } from 'vue'

import Comments from './components/Comments.vue'
import configureApp from './configureApp'
import { mountElement } from './config'
import store from './store'

if (mountElement) {
  configureApp(createApp(Comments)).use(store).mount(mountElement)
}
