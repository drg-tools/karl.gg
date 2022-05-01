<template>
    <div class="equipmentCardContainer">
        <h3 class="equipmentCardTitle">{{ name }}</h3>
        <div>
            <img
                class="w-24 p-4 filter invert"
                :src="`/assets/${icon}.svg`"
                :alt="`${name} icon`"
            />
        </div>

        <div class="modMatrixContainer">
            <div
                v-for="(tier, tierId) in modMatrix"
                :key="tierId"
                class="modMatrixRow"
            >
                <div
                    v-for="(mod, modId) in sortMods(tier)"
                    :key="modId"
                    class="mod"
                    v-tooltip="{
                        content: mod ? getModTooltip(mod) : null,
                        placement: 'right',
                        trigger: 'hover',
                    }"
                >
                    <svg viewBox="0 0 80 50" height="100%" role="img">
                        <desc>{{ mod.text_description }}</desc>
                        <path
                            :class="[
                                isActiveMod(mod)
                                    ? 'previewModOuterActive'
                                    : 'previewModOuter',
                            ]"
                            d="M 0.3679663,25 13.7826,0.609756 H 66.221625 L 79.636259,25 66.221625,49.390244 H 13.7826 L 0.3679663,25"
                        />
                        <g>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                y="10%"
                                viewBox="0 0 80 50"
                                :class="[
                                    isActiveMod(mod)
                                        ? 'previewModIconActive'
                                        : 'previewModIcon',
                                ]"
                                height="80%"
                                preserveAspectRatio="xMidYMid meet"
                                v-html="getIconPath(mod.icon)"
                            ></svg>
                        </g>
                    </svg>
                </div>
            </div>
            <div
                class="overclockContainer mt-2 text-center"
                v-if="overclock"
            >
                                <!-- <desc>{{ overclock.text_description }}</desc> -->

            <OverclockIcon
                    :icon="getIconPath(overclock.icon)"
                    :ocType="overclock.overclock_type"
                    v-tooltip="{
                        content: overclock ? getOverclockTooltip(overclock) : null,
                        placement: 'right',
                        trigger: 'hover'
                        }"
                />
            <div>{{overclock.overclock_name}}</div>
              
            </div>
        </div>
    </div>
</template>

<script>
import groupBy from "lodash-es/groupBy";
import sortBy from "lodash-es/sortBy";
import OverclockIcon from "./OverclockIcon.vue";

export default {
    name: "PreviewCard",
    components: { OverclockIcon },
    props: {
        name: String,
        icon: String,
        mods: Array,
        selectedMods: Array,
        overclock: Object,
    },
    methods: {
        getModTooltip(mod) {
            let description = mod.description
                ? mod.description
                : mod.text_description;
            return `<h3>${mod.mod_name}</h3><br><span>${description}</span>`;
        },
        getOverclockTooltip(oc) {
            if (!oc) {
                return null;
            }
            return `<h3>${oc.overclock_name}</h3><br><span>${oc.text_description}</span>`;
        },
        isActiveMod(mod) {
            return this.selectedMods.includes(mod.id);
        },
        sortMods(mods) {
            return sortBy(mods, "mod_index");
        },
        getIconPath(iconName) {
            return this.$store.getters.getIconByName(iconName);
        },
    },
    computed: {
        modMatrix() {
            return groupBy(this.mods, "mod_tier");
        },
    },
};
</script>

<style scoped>
.equipmentCardContainer {
    display: flex;
    flex-direction: column;
    flex-wrap: nowrap;
    width: 10rem;
    align-items: center;
}

.equipmentCardTitle {
    text-align: center;
    overflow: hidden;
    text-overflow: ellipsis;
}

.overclockContainer {
    display: flex; 
    flex-direction: column;
    align-items: center;
}

.modMatrixContainer {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 1rem;
}

.modMatrixRow {
    display: flex;
    height: 1rem;
    width: 6rem;
    margin-bottom: 0.3rem;
}

.mod {
    width: 2rem;
    height: 100%;
    display: flex;
    justify-content: space-between;
}
</style>
