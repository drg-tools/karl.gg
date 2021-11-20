<template>
    <div class="flex my-5 gap-8">

        <div class="w-1/2">
            <ClassEquipment/>
        </div>

        <!-- Stats -->
        <div class="w-1/2">
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

