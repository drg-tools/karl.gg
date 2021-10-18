<template>
    <!-- driller -->
    <div class="equipmentSelectContainer">
        <div class="secondariesContainer gap-2">
            <!-- TODO: Make these spaced evenly -->
            <div class="flex flex-row content-evenly">
                <EquipmentPowerDrill />
                <EquipmentSatchelCharge />
                <EquipmentDrillSuit />
            </div>
        </div>

        <!-- TODO: hover tooltips instead of the icon holder at the bottom -->
        <div class="flex flex-row">
            <div class="flex flex-col">
                <ModMatrix
                    v-if="this.$store.state.currentlySelectedEquipment != ''"
                    v-bind:itemId="this.$store.state.currentlySelectedEquipment"
                    :boolEquipment="true"
                />

                <CreditsCalculator
                    v-if="
                        this.$store.state.selectedEquipment1Mods != [] ||
                        this.$store.state.selectedEquipment2Mods != [] ||
                        this.$store.state.selectedEquipment3Mods != []
                    "
                />
            </div>
        </div>
    </div>
</template>
<script>
import EquipmentPowerDrill from "./equipment/Equipment_Power_Drill.vue";
import EquipmentSatchelCharge from "./equipment/Equipment_Satchel_Charge.vue";
import EquipmentDrillSuit from "./equipment/Equipment_Drill_Suit.vue";
import ModMatrix from "../ModMatrix.vue";
import OverclockSelect from "../OverclockSelect.vue";
import CreditsCalculator from "../CreditsCalculator.vue";

export default {
    name: "DrillerPrimaries",
    components: {
        EquipmentPowerDrill,
        EquipmentSatchelCharge,
        EquipmentDrillSuit,
        ModMatrix,
        OverclockSelect,
        CreditsCalculator,
    },
};
</script>

