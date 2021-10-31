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
            <div v-for="(gun, index) in loadoutClassPrimaries" :key="gun.id">
                <div class="max-w-1/2">
                    <PrimaryWeaponSelector :gun="gun" :set-selected="!getSelectedPrimary && index === 0"/>
                </div>
            </div>
        </div>

        <!-- TODO: hover tooltips instead of the icon holder at the bottom -->
        <div class="flex flex-col">
            <ModMatrix
                v-if="this.$store.state.selectedPrimary != ''"
                :itemId="this.$store.state.selectedPrimary"
                :boolEquipment="false"
            />
            <OverclockSelect
                :primary="true"
                v-if="this.$store.state.selectedPrimary != ''"
                :weaponId="this.$store.state.selectedPrimary"
            />

            <CreditsCalculator
                v-if="
                        this.$store.state.selectedPrimaryMods != [] ||
                        this.$store.state.setSelectedPrimaryOverclock != ''
                    "
                itemType="primary"
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
            'getSelectedPrimary'
        ])
    }
};
</script>

