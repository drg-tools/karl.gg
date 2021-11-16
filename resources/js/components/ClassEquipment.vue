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
            <
            </router-link>
        </div>

        <div class="flex">
            <div v-for="(equipment, index) in loadoutClassEquipment" :key="equipment.id">
                <div class="max-w-1/2">
                    <EquipmentSelector :equipment="equipment" :equipment-slot="index + 1"
                                       :set-selected="!getSelectedEquipment && index === 0"/>
                </div>
            </div>
        </div>

        <!-- TODO: hover tooltips instead of the icon holder at the bottom -->
        <div class="flex flex-col">
            <ModMatrix
                v-if="getSelectedEquipment"
                :itemId="getSelectedEquipment"
                itemType="equipment"
            />

            <CreditsCalculator
                v-if="
                        this.$store.state.selectedEquipmentMods != []
                    "
                itemType="equipment"
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
            'getSelectedEquipment'
        ])
    }
};
</script>

