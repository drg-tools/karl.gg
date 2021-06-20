<template>
  <div class="classSelectContainer my-2">
    <h1>Select class:</h1>
    <div class="grid grid-cols-2 sm:grid-cols-4 w-full mb-4">
      <div
        v-on:click="setSelectedClass(3)"
        class="classSelect px-4 py-2"
        :class="[getSelected() === 3 ? 'classSelectActive' : '']"
        v-bind:key="3"
      >
        <img src="/assets/img/50px-D_icon-hex.png" class="classIcon" />
        <h2>Driller</h2>
      </div>
      <div
        v-on:click="setSelectedClass(1)"
        class="classSelect px-4 py-2"
        :class="[getSelected() === 1 ? 'classSelectActive' : '']"
        v-bind:key="1"
      >
        <img src="/assets/img/50px-E_icon-hex.png" class="classIcon" />
        <h2>Engineer</h2>
      </div>
      <div
        v-on:click="setSelectedClass(4)"
        class="classSelect px-4 py-2"
        :class="[getSelected() === 4 ? 'classSelectActive' : '']"
        v-bind:key="4"
      >
        <img src="/assets/img/50px-G_icon-hex.png" class="classIcon" />
        <h2>Gunner</h2>
      </div>
      <div
        v-on:click="setSelectedClass(2)"
        class="classSelect px-4 py-2"
        :class="[getSelected() === 2 ? 'classSelectActive' : '']"
        v-bind:key="2"
      >
        <img src="/assets/img/50px-S_icon-hex.png" class="classIcon" />
        <h2>Scout</h2>
      </div>
    </div>
  </div>
</template>

<script>


const charToModId = { A: 0, B: 1, C: 2 };

export default {
  name: "ClassSelect",
  data() {
    return {
      sharedState: this.$store.state,
    };
  },
  methods: {
    // I need to select a class
    // I should be able to click on a class and select that. Let's keep that in our shared store
    // Other components may want to know what our selection is
    setSelectedClass: function (classId) {
      this.$store.commit('setSelectedClass', classId);
    },
    getSelected: function () {
      return this.sharedState.selectedClass;
    }

    // We may need to store a user's loadout on all classes, but use selected class to know which one to save.
  },
};
</script>