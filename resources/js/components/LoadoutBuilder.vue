<template>
  <div id="loadout-builder">
    <div class="form-group" :class="{ 'form-group--error': $v.name.$error }">
      <label class="form__label">Name</label>
      <input
        class="
          text-gray-900
          shadow-sm
          outline-none
          block
          w-full
          sm:text-sm
          border-gray-300
          rounded-md
          p-2
          mb-2
        "
        v-model.trim="name"
        @input="setName($event.target.value)"
        placeholder="Karl's amazing loadout"
      />
    </div>
    <div v-if="$v.name.$error">
        <div class="error" v-if="!$v.name.required">Field is required</div>
        <div class="error" v-if="!$v.name.maxLength">
        Max {{ $v.name.$params.maxLength.max }} characters.
        </div>
    </div>
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
import { required, maxLength } from "vuelidate/lib/validators";
import MarkdownEditor from "./MarkdownEditor.vue";
export default {
  components: { ClassSelect, MarkdownEditor },

  data() {
    return {
      sharedState: this.$store.state,
      name: '',
      description: "",
      loadoutDescription: "",
      messageTitle: "",
      messageText: "",
      creatorId: "",
    };
  },
  validations: {
    name: {
      required,
      maxLength: maxLength(255),
    },
  },

  methods: {
    setName(value) {
      this.name = value;
      this.$v.name.$touch();
    },
  },
};
</script>
