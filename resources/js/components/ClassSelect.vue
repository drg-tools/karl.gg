<template>
  <div class="classSelectContainer my-2">
    <h1>Select class:</h1>
    <div class="grid grid-cols-2 sm:grid-cols-4 w-full mb-4">
      <div
        v-on:click="setSelectedClass('driller')"
        class="classSelect px-4 py-2"
        :class="[getSelected() === 'driller' ? 'classSelectActive' : '']"
        v-bind:key="'driller'"
      >
        <img src="/assets/img/50px-D_icon-hex.png" class="classIcon" />
        <h2>Driller</h2>
      </div>
      <div
        v-on:click="setSelectedClass('engineer')"
        class="classSelect px-4 py-2"
        :class="[getSelected() === 'engineer' ? 'classSelectActive' : '']"
        v-bind:key="'engineer'"
      >
        <img src="/assets/img/50px-E_icon-hex.png" class="classIcon" />
        <h2>Engineer</h2>
      </div>
      <div
        v-on:click="setSelectedClass('gunner')"
        class="classSelect px-4 py-2"
        :class="[getSelected() === 'gunner' ? 'classSelectActive' : '']"
        v-bind:key="'gunner'"
      >
        <img src="/assets/img/50px-G_icon-hex.png" class="classIcon" />
        <h2>Gunner</h2>
      </div>
      <div
        v-on:click="setSelectedClass('scout')"
        class="classSelect px-4 py-2"
        :class="[getSelected() === 'scout' ? 'classSelectActive' : '']"
        v-bind:key="'scout'"
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
    setSelectedClass: function (className) {
      this.$store.dispatch('setSelectedClass', className);
    },
    getSelected: function () {
      return this.$store.state.selectedClass;
    }
  },
};
</script>