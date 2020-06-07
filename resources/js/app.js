/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i);
console.log('files', files.keys());
files.keys().map(key => {
    let keyId = key.split('/').pop().split('.')[0];
    console.log(keyId);
    Vue.component(keyId, files(key).default);
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from 'vue';
// import App from './App.vue';
import store from './store';
import Toasted from 'vue-toasted';
import Popover from 'vue-js-popover';
import ApolloClient from "apollo-boost"
import VueApollo from "vue-apollo"

Vue.config.productionTip = false;
Vue.use(Toasted);

Vue.use(Popover);

const apolloProvider = new VueApollo({
    defaultClient: new ApolloClient({
	uri: "/graphql"
    })
});

Vue.use(VueApollo);

const app = new Vue({
    el: '#app',
    store,
    apolloProvider/*,
    render: h => h(App)*/ /* todo: remove renderer overwrite to get back to the php views and place karl components one by one, without App.vue */
});
