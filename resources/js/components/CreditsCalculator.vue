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
        itemType: String,
    },
    data: function () {
        return {
            costObject: "",
        };
    },
    created() {
        this.computeSumOfCost();
        this.unsubscribe = this.$store.subscribe((mutation, state) => {
            // TODO: make this handle equipment as well as primary and secondary
            if (
                mutation.type === "setSelectedPrimary" ||
                mutation.type === "setSelectedPrimaryMod" ||
                mutation.type === "setSelectedPrimaryOverclock" ||
                mutation.type === "setSelectedSecondary" ||
                mutation.type === "setSelectedSecondaryMod" ||
                mutation.type === "setSelectedSecondaryOverclock"
            ) {
                console.log(`Updating costs`);
                this.computeSumOfCost();
            }
        });
    },
    beforeDestroy() {
        this.unsubscribe();
    },
    methods: {
        computeSumOfCost() {
            console.log("sum of cost ran");
            this.costObject = this.$store.getters.getSelectedModCosts(this.itemType);
        },
    },
};
</script>