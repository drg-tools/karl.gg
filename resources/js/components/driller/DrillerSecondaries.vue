<template>
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
</template>
<script>
import SecondarySubata from "./secondaries/Secondary_Subata.vue";
import ModMatrix from "../ModMatrix.vue";
import OverclockSelect from "../OverclockSelect.vue";
import CreditsCalculator from "../CreditsCalculator.vue";

export default {
    name: "DrillerPrimaries",
    components: {
        SecondarySubata,
        ModMatrix,
        OverclockSelect,
        CreditsCalculator,
    },
};
</script>

