<template>
    <div class="flex sm:flex-nowrap flex-wrap my-5 gap-8">

        <div class="sm:w-1/2 w-full">
            <ClassEquipment/>
            <SelectedItemDisplay />
        </div>

        <!-- Stats -->
        <div class="sm:w-1/2 w-full">
            <MainStatsDisplay
                :equipment="selectedEquipmentDetails"
                :mods="selectedEquipmentMods"
            />
        </div>
    </div>
</template>

<script>
import ClassEquipment from "./ClassEquipment";
import {mapGetters} from "vuex";
import MainStatsDisplay from "./MainStatsDisplay";
import SelectedItemDisplay from "./SelectedItemDisplay";

export default {
    name: "EquipmentBuilder",
    components: {ClassEquipment, MainStatsDisplay, SelectedItemDisplay},
    computed: {
        ...mapGetters([
            "selectedEquipmentDetails",
            "selectedEquipmentMods"
        ]),
    },
};
</script>

