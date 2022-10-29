<template>
    <div class="flex sm:flex-nowrap flex-wrap my-5 gap-8">
        <div class="sm:w-1/2 w-full">
            <ClassThrowables />
            <div v-if="getSelectedThrowableDetails"
                        class="
                            border-transparent border-2
                            p-4
                            hover:border-karl-orange
                        "
                    >
                        <img
                        class="w-24 p-4 filter invert mx-auto"
                        :src="`/assets/${getSelectedThrowableDetails.icon}.svg`" :alt="`${getSelectedThrowableDetails.name} icon`"/>

                        <div class="text-gray-300">{{getSelectedThrowableDetails.name}}</div>
                        <div class="text-gray-300">{{getSelectedThrowableDetails.description}}</div>

                    </div>
        </div>
        <div class="sm:w-1/2 w-full">
            <ThrowableStatsDisplay
                :throwable="getSelectedThrowableDetails"
            />
            </div>
    </div>
</template>


<script>
import ClassThrowables from "./ClassThrowables";
import ThrowableStatsDisplay from "./ThrowableStatsDisplay";

import { mapGetters } from "vuex";

export default {
    name: "ThrowableBuilder",
    components: { ClassThrowables, ThrowableStatsDisplay },
    computed: {
        ...mapGetters([
            "getSelectedThrowableId",
            "getSelectedThrowableDetails",
        ]),
    },
};
</script>
