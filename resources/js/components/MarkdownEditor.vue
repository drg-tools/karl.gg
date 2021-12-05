<template>
    <editor
        ref="toastuiEditor"
        @blur="onEditorBlur"
        @load="onEditorLoad"
        initialEditType="wysiwyg"
        :initialValue="editorText"
        :options="editorOptions"
    />
</template>

<script>
import "@toast-ui/editor/dist/toastui-editor.css";

import { Editor } from "@toast-ui/vue-editor";

export default {
    name: "MarkdownEditor",

    components: {
        editor: Editor,
    },
    beforeDestroy() {
        this.unsubscribe();
    },
    methods: {
        setLoadoutDescription: function (loadoutDescription) {
            this.$store.commit("setLoadoutDescription", loadoutDescription);
        },
        onEditorLoad() {
            // implement your code
            this.unsubscribe = this.$store.subscribe((mutation, state) => {
                if (mutation.type === "setLoadoutDescription") {
                    this.editorText = state.loadoutDescription;
                    this.$refs.toastuiEditor.invoke('setMarkdown', this.editorText)
                }
            });
        },
        onEditorBlur() {
            // We need to convert our editor text to readable markdown
            let md = this.$refs.toastuiEditor.invoke("getMarkdown");
            // Commit our markdown text to the store when you click out of the editor
            this.setLoadoutDescription(md);
        },
    },
    data() {
        return {
            editorText: "",
            unsubscribe: "",
            editorOptions: {
                hideModeSwitch: false,
                useDefaultHTMLSanitizer: true,
                usageStatistics: false,
                toolbarItems: [
                    "heading",
                    "bold",
                    "italic",
                    "strike",
                    "divider",
                    "hr",
                    "quote",
                    "divider",
                    "ul",
                    "ol",
                    "task",
                    "indent",
                    "outdent",
                    "divider",
                    "table",
                    "link",
                    "divider",
                ],
            },
        };
    },
};
</script>
