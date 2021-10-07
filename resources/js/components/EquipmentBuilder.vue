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
      <div class="secondariesContainer gap-2">
        <!-- TODO: Refresh Primaries on fresh class Selection -->
        <!-- TODO: Load folder of components based on selected class, make this guy clean -->
        <!-- TODO: Make class components for each folder, import that here based on selected class -->
        <div class="flex flex-row">
          <SecondarySubata />
        </div>
      </div>

      <!-- TODO: hover tooltips instead of the icon holder at the bottom -->
      <div class="flex flex-row">
        <div class="flex flex-col">
        <ModMatrix/>
        <OverclockSelect primaryOrSecondary="Secondary" />
        <!-- TODO: Make a getter to determine if we should show the credits calculator -->
        <!--       It should check for all the if attributes we would need here -->
        <CreditsCalculator v-if="this.$store.state.selectedSecondaryMods != [] || this.$store.state.setSelectedSecondaryOverclock != '' " />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SecondarySubata from "./driller/Secondary_Subata.vue";
import ModMatrix from "./ModMatrix.vue";
import OverclockSelect from "./OverclockSelect.vue";
import CreditsCalculator from "./CreditsCalculator.vue";

export default {
  name: "SecondaryBuilder",
  components: { SecondarySubata, ModMatrix, OverclockSelect, CreditsCalculator },

};
</script>

