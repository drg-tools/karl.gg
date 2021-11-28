<template>
    <editor
        ref="toastuiEditor"
        @blur="onEditorBlur"
        @load="onEditorLoad"
        initialEditType="wysiwyg"
        :options="editorOptions"
    />
</template>

<script>
import "@toast-ui/editor/dist/toastui-editor.css";
import {mapGetters} from "vuex";
import { Editor } from "@toast-ui/vue-editor";

export default {
    name: "MarkdownEditor",
    props: ["stateDescription"],
    components: {
        editor: Editor,
    },
    // beforeDestroy() {
    //     this.unsubscribe();
    // },
    methods: {
        setLoadoutDescription: function (loadoutDescription) {
            console.log('setLoadoutDescription ran in component');
            this.$store.commit("setLoadoutDescription", loadoutDescription);
        },
        onEditorLoad() {
            console.log('oneditorload ran');
            console.log(this.stateDescription);
            // implement your code
            // this.unsubscribe = this.$store.subscribe((mutation, state) => {
            //     if (mutation.type === "setLoadoutDescription") {
            //         this.editorText = state.loadoutDescription;
            //         this.$refs.toastuiEditor.invoke('setMarkdown', this.editorText)
            //     }
            // });
            // this.editorText = state.loadoutDescription;
            // this.$refs.toastuiEditor.invoke('setMarkdown', this.editorText)
        },
        onEditorBlur() {
            console.log('oneditor blur ran')
            // We need to convert our editor text to readable markdown
            let md = this.$refs.toastuiEditor.invoke("getMarkdown");
            // Commit our markdown text to the store when you click out of the editor
            this.setLoadoutDescription(md);
        },
    },
    computed: {
        ...mapGetters([
            "getLoadoutDescription",
        ]),
    },
    watch: {
        stateDescription() {
            console.log('watch stateDescription  ran')
            // We need to convert our editor text to readable markdown
            console.log('getter');
            console.log(this.getLoadoutDescription);
            // let md = this.$refs.toastuiEditor.invoke("getMarkdown");
            // console.log(md);
            // Commit our markdown text to the store when you click out of the editor
            this.setLoadoutDescription(this.getLoadoutDescription);
        }
    },
    data() {
        return {
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
