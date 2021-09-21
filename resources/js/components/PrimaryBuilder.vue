<template>
  <div class="flex justify-evenly my-5">
    <div class="flex flex-row">
      <router-link
        to="/"
        class="
          inline-flex
          items-center
          text-center
          px-4
          py-2
          border border-transparent
          text-sm
          font-medium
          rounded-md
          shadow-sm
          text-white
          bg-orange-500
          hover:bg-orange-700
          focus:outline-none
          focus:ring-2 focus:ring-offset-2 focus:ring-orange-500
          w-full
          md:w-auto
          h-12
        "
        >Back
      </router-link>
    </div>

    <!-- driller -->
    <div class="equipmentSelectContainer">
      <div class="primariesContainer gap-2">
        <!-- TODO: Refresh Primaries on fresh class Selection -->
        <!-- TODO: Load folder of components based on selected class, make this guy clean -->
        <!-- TODO: Make class components for each folder, import that here based on selected class -->
        <div class="flex flex-row">
          <PrimaryFlamethrower />
        </div>
      </div>

      <!-- TODO: hover tooltips instead of the icon holder at the bottom -->
      <div class="flex flex-row">
        <div class="flex flex-col">
        <ModMatrix />
        <OverclockSelect />
        <CreditsCalculator v-if="this.$store.state.selectedPrimaryMods != []" v-bind:mods="this.$store.state.selectedPrimaryMods" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PrimaryFlamethrower from "./driller/Primary_Flamethrower.vue";
import ModMatrix from "./ModMatrix.vue";
import OverclockSelect from "./OverclockSelect.vue";
import CreditsCalculator from "./CreditsCalculator.vue";

export default {
  name: "PrimaryBuilder",
  components: { PrimaryFlamethrower, ModMatrix, OverclockSelect, CreditsCalculator },

  methods: {},
};
</script>

<style scoped>
/* todo: proper styles for this stuff, it's messy atm */
h4 {
  white-space: nowrap;
}

.equipmentActive {
  background-color: #fc9e00;
  color: #352e1e;
}

.equipment {
  background-color: #111927;
  color: #352e1e;
}

.equipmentIcon {
  fill: #ada195;
}

.equipmentIconActive {
  fill: #fffbff;
}

h4 {
  margin: 0;
  white-space: pre-wrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.largeText {
  font-size: 1.4rem;
}

.equipmentText {
  flex: auto;
  padding-left: 0.5rem;
  padding-right: 1rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
  background-color: #4b5563;
  color: #ada195;
}

.equipmentTextActive {
  flex: auto;
  padding-left: 0.5rem;
  padding-right: 1rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
  background-color: #111927;
  color: #fffbff;
}

.weaponSelectContainer {
  flex: auto;
  display: flex;
  cursor: pointer;
}

.weaponSelectContainer:hover .equipmentText {
  color: #fffbff;
}

.weaponSelectContainer:hover .equipmentIcon {
  fill: #fffbff;
}

.flexboxWeaponSelect {
  display: flex;
  align-items: center;
  height: 4rem;
}

.flexboxWeaponSelect > svg {
  margin: 0.5rem;
}
</style>