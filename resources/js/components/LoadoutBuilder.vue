<template>
    <div id="loadout-builder">
        <router-view></router-view>
    </div>
</template>

<script>
import ClassSelect from "./ClassSelect.vue";
import MarkdownEditor from "./MarkdownEditor.vue";
import SelectContainer from "./SelectContainer.vue";
export default {
    components: { ClassSelect, MarkdownEditor, SelectContainer },
    mounted() {
        this.$store.commit("setAuthUser", window.authUser);
    },
};
</script>
