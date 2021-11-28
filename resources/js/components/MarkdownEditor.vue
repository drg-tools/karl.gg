<template>
    <editor
        ref="toastuiEditor"
        initialEditType="wysiwyg"
        :options="editorOptions"
        @blur="debounceInput"
    />
</template>

<script>
import "@toast-ui/editor/dist/toastui-editor.css";
import { mapGetters } from "vuex";
import { Editor } from "@toast-ui/vue-editor";
import debounce from 'lodash-es/debounce';

export default {
    name: "MarkdownEditor",
    props: ["stateDescription"],
    components: {
        editor: Editor,
    },
    methods: {
        debounceInput: debounce(function (e) {
            let md = this.$refs.toastuiEditor.invoke("getMarkdown");
            // Commit our markdown text to the store after a 1000ms (1s) delay
            this.setLoadoutDescription(md);
        }, 1000),
        setLoadoutDescription: function (loadoutDescription) {
            this.$store.commit("setLoadoutDescription", loadoutDescription);
        },
    },
    computed: {
        ...mapGetters([
            "getLoadoutDescription",
        ]),
    },
    watch: {
        stateDescription() {
            // Watch the state description to catch the onload
            // Potential issue: This will fire everytime our debounce fires
            //      Meaning: When we stop typing for 1000ms, we will commit to the store & refresh to description here
            // This is because we are watching for the state description, and the state description is our main prop
            this.$refs.toastuiEditor.invoke('setMarkdown', this.getLoadoutDescription);
        }
    },
    data() {
        return {
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
