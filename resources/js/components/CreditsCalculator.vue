<template>
    <div>
        <span v-if="calcStats.visible" class="text-white text-lg block my-2"
            >Total Costs:</span
        >
        <p class="costList flex flex-wrap">
            <span
                class="costListItem flex items-center pr-2"
                v-if="calcStats.cost.credits > 0"
            >
                <img
                    src="/assets/img/20px-Credit.png"
                    alt="Credits"
                    title="Credits"
                />
                <span>{{ calcStats.cost.credits }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                v-if="calcStats.cost.bismor > 0"
            >
                <img
                    src="/assets/img/Bismor_icon.png"
                    alt="Bismor"
                    title="Bismor"
                />
                <span>{{ calcStats.cost.bismor }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                v-if="calcStats.cost.croppa > 0"
            >
                <img
                    src="/assets/img/Croppa_icon.png"
                    alt="Croppa"
                    title="Croppa"
                />
                <span>{{ calcStats.cost.croppa }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                v-if="calcStats.cost.enorPearl > 0"
            >
                <img
                    src="/assets/img/Enor_pearl_icon.png"
                    alt="Enor Pearl"
                    title="Enor Pearl"
                />
                <span>{{ calcStats.cost.enorPearl }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                v-if="calcStats.cost.jadiz > 0"
            >
                <img
                    src="/assets/img/Jadiz_icon.png"
                    alt="Jadiz"
                    title="Jadiz"
                />
                <span>{{ calcStats.cost.jadiz }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                v-if="calcStats.cost.magnite > 0"
            >
                <img
                    src="/assets/img/Magnite_icon.png"
                    alt="Magnite"
                    title="Magnite"
                />
                <span>{{ calcStats.cost.magnite }}</span>
            </span>
            <span
                class="costListItem flex items-center pr-2"
                v-if="calcStats.cost.umanite > 0"
            >
                <img
                    src="/assets/img/Umanite_icon.png"
                    alt="Umanite"
                    title="Umanite"
                />
                <span>{{ calcStats.cost.umanite }}</span>
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
    methods: {
        setSelectedClass: function (classId) {
            this.$store.dispatch("setSelectedClass", classId);
        },
        getSelected: function () {
            return this.$store.state.selectedClass;
        },
        computeSumOfCost() {
            let creditsCost = mods
                .map((mod) => mod.credits_cost)
                .reduce((prev, curr) => prev + curr, 0);
            let magniteCost = mods
                .map((mod) => mod.magnite_cost)
                .reduce((prev, curr) => prev + curr, 0);
            let bismorCost = mods
                .map((mod) => mod.bismor_cost)
                .reduce((prev, curr) => prev + curr, 0);
            let umaniteCost = mods
                .map((mod) => mod.umanite_cost)
                .reduce((prev, curr) => prev + curr, 0);
            let enorCost = mods
                .map((mod) => mod.enor_pearl_cost)
                .reduce((prev, curr) => prev + curr, 0);
            let jadizCost = mods
                .map((mod) => mod.jadiz_cost)
                .reduce((prev, curr) => prev + curr, 0);
        },
    },
};
</script>