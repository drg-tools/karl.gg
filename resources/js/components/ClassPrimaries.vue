<template>

    <div>

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
            <div class="w-1/3" v-for="(gun, index) in loadoutClassPrimaries" :key="gun.id">
                <PrimaryWeaponSelector :gun="gun" :set-selected="!getSelectedPrimary && index === 0"/>
            </div>
        </div>

        <!-- TODO: hover tooltips instead of the icon holder at the bottom -->
        <div class="flex flex-col">
            <ModMatrix
                v-if="getSelectedPrimary !== ''"
                :itemId="getSelectedPrimary"
                itemType="primary"
            />
            <OverclockSelect
                v-if="getSelectedPrimary !== ''"
                :primary="true"
                :overclocks="getSelectedPrimaryDetails.overclocks"
                :selected-id="selectedPrimaryOverclockId"
            />

            <CreditsCalculator
                v-if="
                        selectedPrimaryModIds.length === 0 ||
                        getSelectedPrimary !== ''
                    "
                :overclock="selectedPrimaryOverclock"
                :selected-mods="selectedPrimaryMods"
                itemType="secondary"
            />
        </div>
    </div>
</template>
<script>
import PrimaryWeaponSelector from "./PrimaryWeaponSelector";
import ModMatrix from "./ModMatrix";
import OverclockSelect from "./OverclockSelect";
import CreditsCalculator from "./CreditsCalculator";

import {mapGetters} from 'vuex'


export default {
    name: "ClassPrimaries",
    components: {
        PrimaryWeaponSelector,
        ModMatrix,
        OverclockSelect,
        CreditsCalculator,
    },
    computed: {
        ...mapGetters([
            'loadoutClassPrimaries',
            'getSelectedPrimary',
            'selectedPrimaryOverclockId',
            'getSelectedPrimaryDetails',
            'selectedPrimaryModIds',
            'selectedPrimaryOverclock',
            'selectedPrimaryMods'
        ])
    }
};
</script>

