<template>
    <div>
        <v-md-editor v-model="message" height="400px" left-toolbar="undo redo clear h bold italic strikethrough quote ul ol table hr link image" ></v-md-editor>
    </div>
</template>
<script>

export default {
    name: "editorText",
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
