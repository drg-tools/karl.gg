<template>

    <div>

        <div class="mb-4">
            <BackButton/>
        </div>

        <div class="flex gap-4 mb-4" dusk="equipment-selectors">
            <div class="w-1/3" v-for="(equipment, index) in loadoutClassEquipment" :key="equipment.id">
                <EquipmentSelector :equipment="equipment" :equipment-slot="index + 1"
                                   :set-selected="!getSelectedEquipmentId && index === 0"/>
            </div>
        </div>

        <!-- TODO: hover tooltips instead of the icon holder at the bottom -->
        <div class="flex flex-col">
            <ModMatrix
                v-if="getSelectedEquipmentId"
                :itemId="getSelectedEquipmentId"
                itemType="equipment"
            />

            <CreditsCalculator
                v-if="getSelectedEquipmentId !== ''"
                :selected-mods="selectedEquipmentMods"
            />
        </div>
    </div>
</template>
<script>
import EquipmentSelector from "./EquipmentSelector";
import ModMatrix from "./ModMatrix";
import OverclockSelect from "./OverclockSelect";
import CreditsCalculator from "./CreditsCalculator";

import {mapGetters} from 'vuex'
import BackButton from "./BackButton";


export default {
    name: "ClassEquipment",
    components: {
        EquipmentSelector,
        ModMatrix,
        OverclockSelect,
        CreditsCalculator,
        BackButton
    },
    computed: {
        ...mapGetters([
            'loadoutClassEquipment',
            'getSelectedEquipmentId',
            'selectedEquipmentMods'
        ])
    }
};
</script>

