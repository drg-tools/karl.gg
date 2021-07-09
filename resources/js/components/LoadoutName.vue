<template>
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
       
    <div v-if="$v.name.$error">
        <div class="error" v-if="!$v.name.required">Field is required</div>
        <div class="error" v-if="!$v.name.maxLength">
        Max {{ $v.name.$params.maxLength.max }} characters.
        </div>
    </div>
    </div>
</template>

<script>
import { required, maxLength } from "vuelidate/lib/validators";

export default {
  name: "ClassSelect",
  data() {
    return {
      name: this.$store.state.loadoutName
    };
  },
  validations: {
    name: {
      required,
      maxLength: maxLength(255),
    },
  },
  methods: {
    setLoadoutName: function (loadoutDescription) {
      this.$store.commit('setLoadoutName', loadoutDescription);
    },
    setName(value) {
      this.name = value;
      this.setLoadoutName(value); // Commit name to store when validating
      this.$v.name.$touch();
    },
  },
};
</script>