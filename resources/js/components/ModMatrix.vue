<template>
    <div class="modSelection">

        <!-- Tier Row Start -->
        <div
            v-for="(mod, modTier) in modMatrixTiers"
            class="tierContainer"
            :key="modTier"
        >
            <TierLevelText :item-type="itemType" :mod-tier="modTier"/>

            <!-- Mod Sub Container Start -->
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
                    :dusk="`mod-${modData.id}`"
                    v-on:click="selectMod(modData.id, modData.mod_tier)"
                    v-tooltip="{
                        content: modData ? getModTooltip(modData) : null,
                        placement: 'right',
                        trigger: 'hover'
                        }"
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

<script>
export default {
    name: "ModMatrix",
    props: ["itemId", "itemType"],
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
                this.$route.path === "/equipment-builder" // TODO: refactor
            );
        },
        getModTooltip(mod) {
            let description = mod.description ? mod.description : mod.text_description;
            return `<h3>${mod.mod_name}</h3><br><span>${description}</span>`;
        },
        getIconPath(iconName) {
            return this.$store.getters.getIconByName(iconName);
        },
        selectMod(modId, modTier) {

            // TODO: primary is handling secondary, that can just be for all "gun mods"
            if (this.$route.path === "/equipment-builder") {
                this.$store.dispatch('setSelectedEquipmentMod', {
                    modId,
                    modTier,
                });
            } else if (this.$route.path === "/primary-builder") {
                this.$store.dispatch('setSelectedPrimaryMod', {
                    selectedModId: modId,
                    selectedModTier: modTier,
                });
            } else {
                this.$store.dispatch('setSelectedSecondaryMod', {
                    selectedModId: modId,
                    selectedModTier: modTier,
                });
            }
        },
        modSelected(modId) {

            if (this.$route.path === "/equipment-builder") {
                return this.$store.getters.getIsEquipmentModSelected(modId);
            } else if (this.$route.path === "/primary-builder") {
                return this.$store.getters.getIsPrimaryModSelected(modId);
            } else {
                return this.$store.getters.getIsSecondaryModSelected(modId);
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
            this.checkMods();
        },
    },
};
</script>
