<template>
  <div id="loadout-builder">
    <LoadoutName />
    <ClassSelect />
    <h2>Description</h2>
    <MarkdownEditor
      :guide.sync="description"
      :loadoutDescription="loadoutDescription"
      class="guideField"
    ></MarkdownEditor>

    <!-- todo: show loadout name to the left of there buttons if build belongs to user, show 'new loadout' if not -->
  </div>
</template>

<script>
import ClassSelect from "./ClassSelect.vue";
import MarkdownEditor from "./MarkdownEditor.vue";
export default {
  components: { ClassSelect, MarkdownEditor },

  data() {
    return {
      sharedState: this.$store.state,
    };
  },
  
};
</script>
