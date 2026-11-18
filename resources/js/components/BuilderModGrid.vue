<template>
    <div class="upgrade-matrix">
        <fieldset
            v-for="(tierMods, tier) in tiers"
            :key="tier"
            class="upgrade-tier"
        >
            <legend>
                <span>{{ String(tier).padStart(2, "0") }}</span> Tier
                {{ tier }}
                <span class="tier-status">{{
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
                    :class="{ 'is-selected': selected.includes(mod.id) }"
                    :aria-pressed="selected.includes(mod.id)"
                    :dusk="`mod-${mod.id}`"
                    @click="$emit('select', mod)"
                >
                    <span class="upgrade-option-top"
                        ><img
                            v-if="mod.icon"
                            :src="`/assets/mods/optimized/${mod.icon}.svg`"
                            alt=""
                            @error="hideImage"
                        /><span class="upgrade-check" aria-hidden="true">{{
                            selected.includes(mod.id) ? "✓" : "+"
                        }}</span></span
                    >
                    <strong>{{ mod.mod_name }}</strong>
                    <span class="upgrade-description">{{
                        cleanDescription(
                            mod.text_description || mod.description
                        )
                    }}</span>
                </button>
            </div>
        </fieldset>
    </div>
</template>

<script setup>
import { computed } from "vue";
const props = defineProps({
    mods: { type: Array, default: () => [] },
    selected: { type: Array, default: () => [] },
});
defineEmits(["select"]);
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
function cleanDescription(text) {
    return (text || "").replace(/<[^>]*>/g, "").replace(/\\n/g, " ");
}
function hideImage(event) {
    event.target.hidden = true;
}
</script>
