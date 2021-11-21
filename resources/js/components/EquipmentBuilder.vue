<template>
    <div class="flex sm:flex-nowrap flex-wrap my-5 gap-8">

        <div class="sm:w-1/2 w-full">
            <ClassEquipment/>
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

export default {
    name: "EquipmentBuilder",
    components: {ClassEquipment, MainStatsDisplay},
    computed: {
        ...mapGetters([
            "selectedEquipmentDetails",
            "selectedEquipmentMods"
        ]),
    },
};
</script>

