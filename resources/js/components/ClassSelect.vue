<template>

    <!-- TODO: Handle the loading state of class select -->
    <!-- TODO: We currently have a loading state issue if you load the primary before the GQL finishes -->

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
export default {
  name: "ClassSelect",
  methods: {
    setSelectedClass: function (classId) {
      this.$store.dispatch('setSelectedClass', classId);
    },
    getSelected: function () {
      return this.$store.state.selectedClass;
    }
  },
};
</script>