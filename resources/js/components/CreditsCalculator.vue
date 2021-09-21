<template>
    <div>
        <span class="text-white text-lg block my-2">Total Costs:</span>
        <p class="costList flex flex-wrap">
            <span
                class="costListItem flex items-center pr-2"
                v-if="costObject.creditsCost > 0"
            >
                <img
                    src="/assets/img/20px-Credit.png"
                    alt="Credits"
                    title="Credits"
                />
                <span>{{ costObject.creditsCost }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                v-if="costObject.bismorCost > 0"
            >
                <img
                    src="/assets/img/Bismor_icon.png"
                    alt="Bismor"
                    title="Bismor"
                />
                <span>{{ costObject.bismorCost }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                v-if="costObject.croppaCost > 0"
            >
                <img
                    src="/assets/img/Croppa_icon.png"
                    alt="Croppa"
                    title="Croppa"
                />
                <span>{{ costObject.croppaCost }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                v-if="costObject.enorCost > 0"
            >
                <img
                    src="/assets/img/Enor_pearl_icon.png"
                    alt="Enor Pearl"
                    title="Enor Pearl"
                />
                <span>{{ costObject.enorCost }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                v-if="costObject.jadizCost > 0"
            >
                <img
                    src="/assets/img/Jadiz_icon.png"
                    alt="Jadiz"
                    title="Jadiz"
                />
                <span>{{ costObject.jadizCost }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                v-if="costObject.magniteCost > 0"
            >
                <img
                    src="/assets/img/Magnite_icon.png"
                    alt="Magnite"
                    title="Magnite"
                />
                <span>{{ costObject.magniteCost }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                v-if="costObject.umaniteCost > 0"
            >
                <img
                    src="/assets/img/Umanite_icon.png"
                    alt="Umanite"
                    title="Umanite"
                />
                <span>{{ costObject.umaniteCost }}</span>
            </span>
        </p>
    </div>
    <!--  -->
</template>

<script>
export default {
    name: "CreditsCalculator",
    props: {
        mods: Array,
    },
    data: function () {
        return {
            costObject: "",
        };
    },
    created() {
        this.computeSumOfCost();
    },
    updated() {
        this.$nextTick(function () {
            // Code that will run only after the
            // entire view has been re-rendered
            this.computeSumOfCost();
        });
    },

    methods: {
        computeSumOfCost() {
            // let creditsCost = this.mods
            //     .map((mod) => mod.credits_cost)
            //     .reduce((prev, curr) => prev + curr, 0);
            // let magniteCost = this.mods
            //     .map((mod) => mod.magnite_cost)
            //     .reduce((prev, curr) => prev + curr, 0);
            // let bismorCost = this.mods
            //     .map((mod) => mod.bismor_cost)
            //     .reduce((prev, curr) => prev + curr, 0);
            // let umaniteCost = this.mods
            //     .map((mod) => mod.umanite_cost)
            //     .reduce((prev, curr) => prev + curr, 0);
            // let enorCost = this.mods
            //     .map((mod) => mod.enor_pearl_cost)
            //     .reduce((prev, curr) => prev + curr, 0);
            // let jadizCost = this.mods
            //     .map((mod) => mod.jadiz_cost)
            //     .reduce((prev, curr) => prev + curr, 0);

            // let costObject = {
            //     creditsCost: creditsCost,
            //     magniteCost: magniteCost,
            //     bismorCost: bismorCost,
            //     umaniteCost: umaniteCost,
            //     enorCost: enorCost,
            //     jadizCost: jadizCost,
            // };
            // console.log(this.mods);
            // console.log(costObject);
            // this.costObject = costObject;
            console.log("sum of cost ran");
            console.log(this.$store.getters.getSelectedModCosts);
        },
    },
};
</script>