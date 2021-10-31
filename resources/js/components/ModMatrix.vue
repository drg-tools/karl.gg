<template>
    <div class="modSelection">
        <!-- TODO: Selected items for each mod matrix -->
        <!-- TODO: Overclocks and selected OC's -->
        <!-- TODO: Props for Mods and OC's? -->

        <!-- Tier Row Start -->
        <div
            v-for="(mod, modTier) in modMatrixTiers"
            class="tierContainer"
            :key="modTier"
        >
            <h2 class="text-white">
                Tier {{ modTier }}
                <!-- If we're not on the first tier, show the level indicator -->
                <p v-if="modTier > 1" class="levelIndicator text-gray-500">
                    Level {{ modTier * 4 }}
                </p>
            </h2>

            <!-- Mod Sub Container Start -->
            <!-- TODO: Call Stats here -->
            <div
                class="tierSubContainer"
                :class="[
                    mod.length === 1
                        ? ''
                        : mod.length === 2
                        ? 'tierBackgroundGradientHalf'
                        : 'tierBackgroundGradient',
                ]"
            >
                <!-- Each Mod start -->
                <div
                    v-for="(modData, modIndex) in mod"
                    :key="modIndex"
                    class="modDisplay"
                    v-on:click="selectMod(modData.id, modData.mod_tier)"
                >
                    <svg
                        viewBox="0 0 80 50"
                        height="100%"
                        class="mod"
                        :class="[
                            modSelected(modData.id)
                                ? 'modBackgroundActive'
                                : 'modBackground',
                        ]"
                    >
                        <path
                            d="M 0.3679663,25 13.7826,0.609756 H 66.221625 L 79.636259,25 66.221625,49.390244 H 13.7826 L 0.3679663,25"
                        />
                        <g>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                y="10%"
                                viewBox="0 0 80 50"
                                :class="[
                                    modSelected(modData.id)
                                        ? 'modIconActive'
                                        : 'modIcon',
                                ]"
                                height="80%"
                                preserveAspectRatio="xMidYMid meet"
                                v-html="getIconPath(modData.icon)"
                            ></svg>
                        </g>
                    </svg>
                </div>
                <!-- End individual mod -->
                <!-- If we don't have 3 mods, put a fake one on the end -->
                <div v-if="mod.length === 2" class="pseudoModDisplay"></div>
            </div>
            <!-- End Mod Sub Container -->
        </div>
        <!-- End Tier Row -->
    </div>
</template>

<!--todo: reset button-->
<script>
export default {
    name: "ModMatrix",
    props: ["itemId", "boolEquipment"],
    data() {
        return {
            itemMods: "",
        };
    },
    created() {
        // fetch the data when the view is created and the data is
        // already being observed
        this.checkMods();
    },
    methods: {
        // todo: this should be props or something
        checkMods() {
            this.itemMods = this.$store.getters.getModsForMatrix(
                this.itemId,
                this.boolEquipment
            );
        },
        getIconPath(iconName) {
            return this.$store.getters.getIconByName(iconName);
        },
        selectMod(modId, modTier) {

            // TODO: primary is handling secondary, that can just be for all "gun mods"
            if (this.boolEquipment) {
                this.$store.dispatch('setSelectedEquipmentMod', {
                    modId,
                    modTier,
                });
            } else {
                this.$store.dispatch('setSelectedPrimaryMod', {
                    selectedModId: modId,
                    selectedModTier: modTier,
                });
            }
        },
        modSelected(modId) {

            if (this.boolEquipment) {
                return this.$store.getters.getIsEquipmentModSelected(modId);
            } else {
                return this.$store.getters.getIsGunModSelected(modId);
            }
        },
    },
    computed: {
        modMatrixTiers() {
            return _.groupBy(this.itemMods, "mod_tier");
        },
    },
    watch: {
        itemId: function (newVal, oldVal) {
            // watch it
            console.log("Prop changed: ", newVal, " | was: ", oldVal);
            this.checkMods();
        },
    },
};
</script>
