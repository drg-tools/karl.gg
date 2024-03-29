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
                    v-for="(modData, modIndex) in sortMods(mod)"
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
import groupBy from 'lodash-es/groupBy';
import sortBy from 'lodash-es/sortBy';
import TierLevelText from "./TierLevelText";

export default {
    name: "ModMatrix",
    props: ["itemId", "itemType", "mods"],
    components: {
        TierLevelText
    },
    methods: {
        getModTooltip(mod) {
            let description = mod.description ? mod.description : mod.text_description;
            return `<h3>${mod.mod_name}</h3><br><span>${description}</span>`;
        },
        getIconPath(iconName) {
            return this.$store.getters.getIconByName(iconName);
        },
        selectMod(modId, modTier) {
            // TODO: Set Selected item here
            // Probably clear last selected item as well
            // Then on the component itself need to setup the view
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
        sortMods(mods) {
            return sortBy(mods, 'mod_index');
        }
    },
    computed: {
        modMatrixTiers() {
            return groupBy(this.mods, "mod_tier");
        },
    },
};
</script>
