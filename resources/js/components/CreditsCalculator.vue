<template>
    <div dusk="credits-calculator">
        <span class="text-white text-lg block my-2">Total Costs:</span>
        <p class="costList flex flex-wrap">
            <span
                class="costListItem flex items-center pr-2"
                dusk="credits-cost"
                v-if="creditsCost > 0"
            >
                <img
                    src="/assets/img/20px-Credit.png"
                    alt="Credits"
                    title="Credits"
                />
                <span>{{ creditsCost }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                dusk="bismor-cost"
                v-if="bismorCost > 0"
            >
                <img
                    src="/assets/img/Bismor_icon.png"
                    alt="Bismor"
                    title="Bismor"
                />
                <span>{{ bismorCost }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                dusk="croppa-cost"
                v-if="croppaCost > 0"
            >
                <img
                    src="/assets/img/Croppa_icon.png"
                    alt="Croppa"
                    title="Croppa"
                />
                <span>{{ croppaCost }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                dusk="enor-cost"
                v-if="enorCost > 0"
            >
                <img
                    src="/assets/img/Enor_pearl_icon.png"
                    alt="Enor Pearl"
                    title="Enor Pearl"
                />
                <span>{{ enorCost }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                dusk="jadiz-cost"
                v-if="jadizCost > 0"
            >
                <img
                    src="/assets/img/Jadiz_icon.png"
                    alt="Jadiz"
                    title="Jadiz"
                />
                <span>{{ jadizCost }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                dusk="magnite-cost"
                v-if="magniteCost > 0"
            >
                <img
                    src="/assets/img/Magnite_icon.png"
                    alt="Magnite"
                    title="Magnite"
                />
                <span>{{ magniteCost }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                dusk="umanite-cost"
                v-if="umaniteCost > 0"
            >
                <img
                    src="/assets/img/Umanite_icon.png"
                    alt="Umanite"
                    title="Umanite"
                />
                <span>{{ umaniteCost }}</span>
            </span>
        </p>
    </div>
</template>

<script>

import get from 'lodash-es/get';

export default {
    name: "CreditsCalculator",
    props: {
        overclock: Object,
        selectedMods: {
            type: Array,
            default: () => []
        }
    },
    methods: {
        computeCost(material) {
            const modCost = this.selectedMods.reduce((prev, curr) => prev + curr[material], 0);
            const ocCost = get(this.overclock, material, 0);

            return modCost + ocCost;
        }
    },
    computed: {
        umaniteCost() {
            return this.computeCost('umanite_cost');
        },
        creditsCost() {
            return this.computeCost('credits_cost');
        },
        croppaCost() {
            return this.computeCost('croppa_cost');
        },
        magniteCost() {
            return this.computeCost('magnite_cost');
        },
        jadizCost() {
            return this.computeCost('jadiz_cost');
        },
        enorCost() {
            return this.computeCost('enor_cost');
        },
        bismorCost() {
            return this.computeCost('bismor_cost');
        },
    }
};
</script>
