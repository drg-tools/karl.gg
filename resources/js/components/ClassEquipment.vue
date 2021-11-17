<template>

    <div class="w-1/2">

        <div class="mb-4">
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
                    bg-orange-700
                    hover:bg-orange-900
                    focus:outline-none
                    focus:ring-2 focus:ring-offset-2 focus:ring-orange-500
                    w-full
                    md:w-auto
                    h-12
                "
            >
                <i class="fas fa-chevron-left"></i>
            </router-link>
        </div>

        <div class="flex gap-4 mb-4">
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


export default {
    name: "ClassEquipment",
    components: {
        EquipmentSelector,
        ModMatrix,
        OverclockSelect,
        CreditsCalculator,
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

