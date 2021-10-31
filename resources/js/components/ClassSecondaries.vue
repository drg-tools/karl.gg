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
                    bg-orange-500
                    hover:bg-orange-700
                    focus:outline-none
                    focus:ring-2 focus:ring-offset-2 focus:ring-orange-500
                    w-full
                    md:w-auto
                    h-12
                "
            >Back
            </router-link>
        </div>

        <div class="flex">
            <div v-for="(gun, index) in loadoutClassSecondaries" :key="gun.id">
                <div class="max-w-1/2">
                    <SecondaryWeaponSelector :gun="gun" :set-selected="!getSelectedSecondary && index === 0"/>
                </div>
            </div>
        </div>

        <!-- TODO: hover tooltips instead of the icon holder at the bottom -->
        <div class="flex flex-col">
            <ModMatrix
                v-if="this.$store.state.selectedSecondary != ''"
                :itemId="this.$store.state.selectedSecondary"
                :boolEquipment="false"
            />
            <OverclockSelect
                :primary="false"
                v-if="this.$store.state.selectedSecondary != ''"
                :weaponId="this.$store.state.selectedSecondary"
            />

            <CreditsCalculator
                v-if="
                        this.$store.state.selectedSecondaryMods != [] ||
                        this.$store.state.setSelectedSecondaryOverclock != ''
                    "
                itemType="primary"
            />
        </div>
    </div>
</template>
<script>
import ModMatrix from "./ModMatrix";
import OverclockSelect from "./OverclockSelect";
import CreditsCalculator from "./CreditsCalculator";
import SecondaryWeaponSelector from './SecondaryWeaponSelector';

import {mapGetters} from 'vuex'

export default {
    name: "ClassSecondaries",
    components: {
        ModMatrix,
        OverclockSelect,
        CreditsCalculator,
        SecondaryWeaponSelector
    },
    computed: {
        ...mapGetters([
            'loadoutClassSecondaries',
            'getSelectedSecondary'
        ])
    }
};
</script>

