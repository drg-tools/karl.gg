<template>
    <div class="compact-tree" :class="{ 'is-small': small }">
        <div
            v-for="(tierMods, tier) in tiers"
            :key="tier"
            class="compact-tier"
            :class="{ 'is-locked': locked(tier) }"
            role="group"
            :aria-label="`${label} tier ${tier}`"
        >
            <span class="compact-tier-number" aria-hidden="true">{{
                tier
            }}</span>
            <button
                v-for="mod in tierMods"
                :key="mod.id"
                type="button"
                class="compact-choice"
                :class="{ 'is-selected': selected.includes(mod.id) }"
                :aria-pressed="selected.includes(mod.id)"
                :aria-label="`Tier ${tier} ${mod.mod_index}: ${mod.mod_name}`"
                :disabled="locked(tier)"
                @click="$emit('select', mod)"
            >
                <span class="compact-hex" aria-hidden="true"
                    ><img
                        v-if="mod.icon"
                        :src="`/assets/mods/optimized/${mod.icon}.svg`"
                        alt=""
                        @error="hideImage"
                    /><i>{{ mod.mod_index }}</i></span
                >
                <span class="compact-tip" role="tooltip"
                    ><strong>{{ mod.mod_name }}</strong
                    ><small>Tier {{ tier }} · {{ mod.mod_index }}</small
                    ><span>{{
                        cleanDescription(
                            mod.text_description || mod.description
                        )
                    }}</span></span
                >
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    mods: { type: Array, default: () => [] },
    selected: { type: Array, default: () => [] },
    label: { type: String, default: "Upgrade" },
    small: { type: Boolean, default: false },
});
defineEmits(["select"]);

/** Upgrades grouped by tier, each tier ordered A, B, C. */
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

/** A tier stays locked until the tier above it has an upgrade. */
function locked(tier) {
    const previous = tiers.value[tier - 1];

    return (
        Number(tier) > 1 &&
        !!previous &&
        !previous.some((mod) => props.selected.includes(mod.id))
    );
}

function cleanDescription(text) {
    return (text || "").replace(/<[^>]*>/g, "").replace(/\\n/g, " ");
}

function hideImage(event) {
    event.target.hidden = true;
}
</script>
