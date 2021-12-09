<template>
    <div>
        <vue-editor v-model="message"/>
    </div>
</template>
<script>
import { VueEditor } from "vue2-editor";
// https://github.com/davidroyer/vue2-editor
// https://vuex.vuejs.org/guide/forms.html#two-way-computed-property
export default {
    name: "editorText",
    components: { VueEditor },
    computed: {
        message: {
            get () {
                return this.$store.state.loadoutDescription;
            },
            set (value) {
                this.$store.commit("setLoadoutDescription", value);
            },
        },
    },
};
</script>
