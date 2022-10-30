/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

// Updated, per this article: https://nono.ma/window-vue-use-is-not-a-function
window.Vue = require("vue").default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component(
    "passport-clients",
    require("./components/passport/Clients.vue").default
);

Vue.component(
    "passport-authorized-clients",
    require("./components/passport/AuthorizedClients.vue").default
);

Vue.component(
    "passport-personal-access-tokens",
    require("./components/passport/PersonalAccessTokens.vue").default
);

const files = require.context("./", true, /\.vue$/i);
files.keys().map((key) => {
    let keyId = key.split("/").pop().split(".")[0];
    Vue.component(keyId, files(key).default);
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from "vue";
import store from "./store";
import { Apollo } from "./apollo";
import Toasted from "vue-toasted";
import VPopover from "vue-js-popover";
import VTooltip from "v-tooltip";
import VModal from "vue-js-modal";
import VueApollo from "vue-apollo";
import Vuelidate from "vuelidate";
import VueRouter from "vue-router";
import VueMarkdownEditor from "@kangc/v-md-editor";
import "@kangc/v-md-editor/lib/style/base-editor.css";
import vuepressTheme from "@kangc/v-md-editor/lib/theme/vuepress.js";
import "@kangc/v-md-editor/lib/theme/style/vuepress.css";
import enUS from "@kangc/v-md-editor/lib/lang/en-US";
import VMdPreview from "@kangc/v-md-editor/lib/preview";
import "@kangc/v-md-editor/lib/style/preview.css";
import githubTheme from "@kangc/v-md-editor/lib/theme/github.js";
import "@kangc/v-md-editor/lib/theme/style/github.css";

// Prism
import Prism from "prismjs";
// highlight code
import "prismjs/components/prism-json";

VueMarkdownEditor.use(vuepressTheme, {
    Prism,
});
VueMarkdownEditor.lang.use("en-US", enUS);

Vue.use(VueMarkdownEditor);
VMdPreview.use(githubTheme);
Vue.use(VMdPreview);

Vue.config.productionTip = false;
Vue.use(Toasted);
Vue.use(VModal);
Vue.use(VueApollo);
Vue.use(VPopover, { tooltip: true });
Vue.use(VTooltip);
Vue.use(Vuelidate);
Vue.use(VueRouter);
// credit to https://stackoverflow.com/questions/35070271/vue-js-components-how-to-truncate-the-text-in-the-slot-element-in-a-component
// for this filter
/** Vue Filters Start */
Vue.filter("truncate", function (text, length, suffix) {
    if (text.length > length) {
        return text.substring(0, length) + suffix;
    } else {
        return text;
    }
});
/** Vue Filters End */

// 1. Define route components.
// These can be imported from other files
import LoadoutBuilder from "./components/LoadoutBuilder.vue";
import LoadoutContainer from "./components/LoadoutContainer.vue";
import SelectContainer from "./components/SelectContainer.vue";
import PrimaryBuilder from "./components/PrimaryBuilder.vue";
import SecondaryBuilder from "./components/SecondaryBuilder.vue";
import EquipmentBuilder from "./components/EquipmentBuilder.vue";
import ThrowableBuilder from "./components/ThrowableBuilder.vue";

// 2. Define some routes
// Each route should map to a component. The "component" can
// either be an actual component constructor created via
// `Vue.extend()`, or just a component options object.
// We'll talk about nested routes later.
const routes = [
    {
        path: "",
        component: LoadoutBuilder,
        children: [
            {
                path: "",
                component: LoadoutContainer,
                children: [
                    { path: "", component: SelectContainer },
                    { path: "/primary-builder", component: PrimaryBuilder },
                    { path: "/secondary-builder", component: SecondaryBuilder },
                    { path: "/equipment-builder", component: EquipmentBuilder },
                    { path: "/throwable-builder", component: ThrowableBuilder },
                ],
            },
        ],
    },
];

// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
const router = new VueRouter({
    routes, // short for `routes: routes`,
});

const apolloProvider = new VueApollo({
    defaultClient: Apollo,
});

const app = new Vue({
    el: "#app",
    store,
    router,
    apolloProvider,
}).$mount("#app");
