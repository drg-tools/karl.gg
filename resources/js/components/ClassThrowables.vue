<template>

    <div>

        <div class="mb-4">
            <BackButton />
        </div>

        <!-- TODO: Throwables here -->
        <div class="flex gap-4 mb-4" dusk="primary-selectors">
            <div class="w-1/3" v-for="(gun, index) in loadoutClassPrimaries" :key="gun.id">
                <ThrowableSelector :gun="gun" :set-selected="!getSelectedPrimary && index === 0"/>
            </div>
        </div>

        <!-- TODO: No Mod Matrix or stats selector. Just an image and maybe description with the item-->

        <div class="flex flex-col">
            <!-- TODO: Make sure credits are setup -->
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
import ThrowableSelector from "./ThrowableSelector";
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
    BackButton,
    ThrowableSelector
},
    computed: {
        ...mapGetters([
            'loadoutClassPrimaries', // TODO: Map this to all throwables getters
            'getSelectedPrimary',
            'selectedPrimaryOverclockId',
            'getSelectedPrimaryDetails',
            'selectedPrimaryModIds',
            'selectedPrimaryOverclock',
            'selectedPrimaryMods'
        ]),
        primaryMods() {
            return this.getSelectedPrimaryDetails?.mods;
        }
    }
};
</script>

