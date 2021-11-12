<template>
  
  <div v-if="getLoadingStatus">
    
  </div>
  <div v-else class="classSelectContainer my-2">
    <h1>Select class:</h1>
    <div class="grid grid-cols-2 sm:grid-cols-4 w-full mb-4">
      <div
        v-on:click="setSelectedClass(3)"
        class="classSelect px-4 py-2"
        v-bind:class="[getSelectedClass() == 3 ? 'classSelectActive' : '']"
        v-bind:key="3"
      >
        <img src="/assets/img/50px-D_icon-hex.png" class="classIcon" />
        <h2>Driller</h2>
      </div>
      <div
        v-on:click="setSelectedClass(1)"
        class="classSelect px-4 py-2"
        v-bind:class="[getSelectedClass() == 1 ? 'classSelectActive' : '']"
        v-bind:key="1"
      >
        <img src="/assets/img/50px-E_icon-hex.png" class="classIcon" />
        <h2>Engineer</h2>
      </div>
      <div
        v-on:click="setSelectedClass(4)"
        class="classSelect px-4 py-2"
        v-bind:class="[getSelectedClass() == 4 ? 'classSelectActive' : '']"
        v-bind:key="4"
      >
        <img src="/assets/img/50px-G_icon-hex.png" class="classIcon" />
        <h2>Gunner</h2>
      </div>
      <div
        v-on:click="setSelectedClass(2)"
        class="classSelect px-4 py-2"
        v-bind:class="[getSelectedClass() == 2 ? 'classSelectActive' : '']"
        v-bind:key="2"
      >
        <img src="/assets/img/50px-S_icon-hex.png" class="classIcon" />
        <h2>Scout</h2>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "ClassSelect",
  created() {
    // Default select Driller to smooth out UX
    if (this.$store.state.selectedClass === "") {
      this.setSelectedClass(3);
    }
  },
  methods: {
    setSelectedClass: function (classId) {
      this.$store.dispatch("setSelectedClass", classId);
    },
  },
  computed: {
    ...mapGetters(["getLoadingStatus", "getSelectedClass"]),
  },
};
</script>