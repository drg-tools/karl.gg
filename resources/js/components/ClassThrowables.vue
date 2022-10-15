<template>

    <div>

        <div class="mb-4">
            <BackButton />
        </div>

        <!-- TODO: Throwables here -->
        <div class="flex gap-4 mb-4" dusk="throwable-selectors">
            <div class="w-1/3" v-for="(throwable, index) in loadoutClassThrowables" :key="throwable.id">
                <ThrowableSelector :throwable="throwable" :set-selected="!getSelectedThrowableId && index === 0"/>
            </div>
        </div>

        <!-- TODO: No Mod Matrix or stats selector. Just an image and maybe description with the item-->

        <div class="flex flex-col">
            <!-- TODO: Make sure credits are setup -->
            <CreditsCalculator
                v-if="
                        getSelectedThrowableId !== ''
                    "
                :throwable="getSelectedThrowableDetails"

            />
        </div>
    </div>
</template>
<script>
import ThrowableSelector from "./ThrowableSelector";
import CreditsCalculator from "./CreditsCalculator";

import {mapGetters} from 'vuex'
import BackButton from "./BackButton";


export default {
    name: "ClassThrowables",
    components: {
    CreditsCalculator,
    BackButton,
    ThrowableSelector
},
    computed: {
        ...mapGetters([
            'loadoutClassThrowables', 
            'getSelectedThrowableId',
            'getSelectedThrowableDetails',
        ]),
    }
};
</script>

