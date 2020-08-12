/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('@fortawesome/fontawesome-free');
require('@fortawesome/fontawesome-svg-core');
require('@fortawesome/free-solid-svg-icons');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => {
    let keyId = key.split('/').pop().split('.')[0];
    Vue.component(keyId, files(key).default);
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from 'vue';
import store from './store';
import {Apollo} from './apollo';
import Toasted from 'vue-toasted';
import VPopover from 'vue-js-popover';
import VModal from 'vue-js-modal';
import VueApollo from 'vue-apollo';
import VueClipboard from 'vue-clipboard2'
import Vuelidate from 'vuelidate'

Vue.use(VueClipboard);
Vue.config.productionTip = false;
Vue.use(Toasted);
Vue.use(VModal);
Vue.use(VueApollo);
Vue.use(VPopover, {tooltip: true});
Vue.use(Vuelidate)
// credit to https://stackoverflow.com/questions/35070271/vue-js-components-how-to-truncate-the-text-in-the-slot-element-in-a-component
// for this filter
/** Vue Filters Start */
Vue.filter('truncate', function (text, length, suffix) {
    if (text.length > length) {
        return text.substring(0, length) + suffix;
    } else {
        return text;
    }
});
/** Vue Filters End */


const apolloProvider = new VueApollo({
    defaultClient: Apollo
});

Vue.prototype.$userId = document.querySelector("meta[name='user-id']").getAttribute('content');

const app = new Vue({
    el: '#app',
    store,
    apolloProvider
});
