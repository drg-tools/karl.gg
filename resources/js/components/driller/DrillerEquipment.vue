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
                <ModMatrix />
                <OverclockSelect primaryOrSecondary="Secondary" />
                <!-- TODO: Make a getter to determine if we should show the credits calculator -->
                <!--       It should check for all the if attributes we would need here -->
                <CreditsCalculator
                    v-if="
                        this.$store.state.selectedSecondaryMods != [] ||
                        this.$store.state.setSelectedSecondaryOverclock != ''
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

