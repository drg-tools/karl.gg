<template>
    <div>
        <vue-editor v-model="value" />
    </div>
</template>
<script>
import { VueEditor } from "vue2-editor";
// https://github.com/davidroyer/vue2-editor
// https://vuex.vuejs.org/guide/forms.html#two-way-computed-property
export default {
    name: "editorText",
    components: { VueEditor },
    data() {
        return {
            content: "<h1>Some initial content</h1>",
        };
    },
    computed: {
        value: {
            get: function () {
                return this.$store.getters.getLoadoutDescription;
            },
            set: function (value) {
                this.$store.commit("setLoadoutDescription", value);
            },
        },
    },
};
</script>
