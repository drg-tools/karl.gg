<template>
    <div class="upgrade-matrix">
        <fieldset
            v-for="(tierMods, tier) in tiers"
            :key="tier"
            class="upgrade-tier"
        >
            <legend>
                <span>{{ String(tier).padStart(2, "0") }}</span> Tier {{ tier
                }}<span class="tier-status">{{
                    tierMods.some((mod) => selected.includes(mod.id))
                        ? "Equipped"
                        : "Choose an upgrade"
                }}</span>
            </legend>
            <div
                class="upgrade-options"
                :style="{ '--options': tierMods.length }"
            >
                <button
                    v-for="mod in tierMods"
                    :key="mod.id"
                    type="button"
                    class="upgrade-option"
                    :class="{
                        'is-selected': selected.includes(mod.id),
                        'is-inspected': inspected[tier] === mod.id,
                    }"
                    :aria-pressed="selected.includes(mod.id)"
                    :aria-label="mod.mod_name"
                    :aria-describedby="`${descriptionId}-${mod.id}`"
                    :dusk="`mod-${mod.id}`"
                    @mouseenter="inspected[tier] = mod.id"
                    @focus="inspected[tier] = mod.id"
                    @click="selectUpgrade(mod)"
                >
                    <span class="upgrade-hex" aria-hidden="true"
                        ><img
                            v-if="mod.icon"
                            :src="`/assets/mods/optimized/${mod.icon}.svg`"
                            alt=""
                            @error="hideImage"
                        /><span v-else>{{ mod.mod_index }}</span></span
                    >
                    <span class="upgrade-label"
                        ><span class="upgrade-index"
                            >{{ mod.mod_index }}
                            <span aria-hidden="true">/</span>
                            {{
                                selected.includes(mod.id)
                                    ? "Equipped"
                                    : "Upgrade"
                            }}</span
                        ><strong>{{ mod.mod_name }}</strong></span
                    >
                    <span :id="`${descriptionId}-${mod.id}`" class="sr-only">{{
                        cleanDescription(
                            mod.text_description || mod.description
                        )
                    }}</span>
                </button>
            </div>
            <div
                v-if="detailFor(tier, tierMods)"
                class="upgrade-inspection"
                :dusk="`tier-details-${tier}`"
            >
                <span class="inspection-key" aria-hidden="true">{{
                    detailFor(tier, tierMods).mod_index
                }}</span>
                <p>
                    {{
                        cleanDescription(
                            detailFor(tier, tierMods).text_description ||
                                detailFor(tier, tierMods).description
                        ) || "No additional effects listed."
                    }}
                </p>
            </div>
        </fieldset>
    </div>
</template>

<script setup>
import { computed, reactive, useId } from "vue";
const props = defineProps({
    mods: { type: Array, default: () => [] },
    selected: { type: Array, default: () => [] },
});
const emit = defineEmits(["select"]);
const inspected = reactive({});
const descriptionId = useId();
const tiers = computed(() =>
    [...props.mods]
        .sort(
            (a, b) =>
                a.mod_tier - b.mod_tier ||
                String(a.mod_index).localeCompare(String(b.mod_index))
        )
        .reduce((groups, mod) => {
            (groups[mod.mod_tier] ||= []).push(mod);
            return groups;
        }, {})
);
function detailFor(tier, mods) {
    return (
        mods.find((mod) => mod.id === inspected[tier]) ||
        mods.find((mod) => props.selected.includes(mod.id)) ||
        mods[0]
    );
}
function selectUpgrade(mod) {
    inspected[mod.mod_tier] = mod.id;
    emit("select", mod);
}
function cleanDescription(text) {
    return (text || "").replace(/<[^>]*>/g, "").replace(/\\n/g, " ");
}
function hideImage(event) {
    event.target.hidden = true;
}
</script>
