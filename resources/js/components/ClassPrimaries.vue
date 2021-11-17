<template>

    <div>

        <div class="mb-4">
            <BackButton />
        </div>

        <div class="flex gap-4 mb-4" dusk="primary-selectors">
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
import BackButton from "./BackButton";


export default {
    name: "ClassPrimaries",
    components: {
        PrimaryWeaponSelector,
        ModMatrix,
        OverclockSelect,
        CreditsCalculator,
        BackButton
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

