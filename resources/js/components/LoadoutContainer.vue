<template>
  <div id="loadout-container">
    <LoadoutBuilderActions />    
    <ClassSelect />
    <router-view v-if="this.$store.state.selectedClass != ''"></router-view>


    <h2>Description</h2>
    <MarkdownEditor />
    
  </div>
</template>

<script>
import LoadoutBuilderActions from "./LoadoutBuilderActions.vue";
import ClassSelect from "./ClassSelect.vue";
import MarkdownEditor from "./MarkdownEditor.vue";
import SelectContainer from "./SelectContainer.vue";
export default {
  components: { LoadoutBuilderActions, ClassSelect, MarkdownEditor, SelectContainer },
};
</script>
