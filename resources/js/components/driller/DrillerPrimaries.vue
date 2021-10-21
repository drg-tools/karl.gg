<template>
    <!-- driller -->
    <div class="equipmentSelectContainer">
        <div class="primariesContainer">
            <!-- TODO: Refresh Primaries on fresh class Selection -->
            <!-- TODO: Load folder of components based on selected class, make this guy clean -->
            <!-- TODO: Make class components for each folder, import that here based on selected class -->
            <div class="flex flex-row">
                <PrimaryFlamethrower />
                <PrimaryCryo />
            </div>
        </div>

        <!-- TODO: hover tooltips instead of the icon holder at the bottom -->
        <div class="flex flex-row">
            <div class="flex flex-col">
                <ModMatrix
                    v-if="this.$store.state.selectedPrimary != ''"
                    :itemId="this.$store.state.selectedPrimary"
                    :boolEquipment="false"
                />
                <OverclockSelect
                    :primary="true"
                    v-if="this.$store.state.selectedPrimary != ''"
                    :weaponId="this.$store.state.selectedPrimary"
                />

                <CreditsCalculator
                    v-if="
                        this.$store.state.selectedPrimaryMods != [] ||
                        this.$store.state.setSelectedPrimaryOverclock != ''
                    "
                />
            </div>
        </div>
    </div>
</template>
<script>
import PrimaryFlamethrower from "./primaries/Primary_Flamethrower.vue";
import PrimaryCryo from "./primaries/Primary_Cryo.vue";
import ModMatrix from "../ModMatrix.vue";
import OverclockSelect from "../OverclockSelect.vue";
import CreditsCalculator from "../CreditsCalculator.vue";

export default {
    name: "DrillerPrimaries",
    components: {
        PrimaryFlamethrower,
        PrimaryCryo,
        ModMatrix,
        OverclockSelect,
        CreditsCalculator,
    },
};
</script>

