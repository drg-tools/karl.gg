<template>
    <div>

        <div class="mb-4">
            <BackButton />
        </div>

        <div class="flex gap-4 mb-4" dusk="secondary-selectors">
            <div class="w-1/3" v-for="(gun, index) in loadoutClassSecondaries" :key="gun.id">
                <SecondaryWeaponSelector :gun="gun" :set-selected="!getSelectedSecondary && index === 0"/>
            </div>
        </div>

        <!-- TODO: hover tooltips instead of the icon holder at the bottom -->
        <div class="flex flex-col">
            <ModMatrix
                v-if="getSelectedSecondary !== ''"
                :itemId="getSelectedSecondary"
                itemType="secondary"
            />
            <OverclockSelect
                v-if="getSelectedSecondary !== ''"
                :primary="false"
                :overclocks="getSelectedSecondaryDetails.overclocks"
                :selected-id="selectedSecondaryOverclockId"
            />

            <CreditsCalculator
                v-if="
                        selectedSecondaryModIds.length === 0 ||
                        getSelectedSecondary !== ''
                    "
                :overclock="selectedSecondaryOverclock"
                :selected-mods="selectedSecondaryMods"
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
import BackButton from "./BackButton";

export default {
    name: "ClassSecondaries",
    components: {
        ModMatrix,
        OverclockSelect,
        CreditsCalculator,
        SecondaryWeaponSelector,
        BackButton
    },
    computed: {
        ...mapGetters([
            'loadoutClassSecondaries',
            'getSelectedSecondary',
            'getSelectedSecondaryDetails',
            'selectedSecondaryModIds',
            'selectedSecondaryOverclockId',
            'selectedSecondaryOverclock',
            'selectedSecondaryMods'
        ])
    }
};
</script>

