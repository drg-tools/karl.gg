<template>
    <article class="kit-card" :style="accent ? { '--kit-accent': accent } : {}">
        <div class="kit-card__head">
            <div>
                <span class="kit-card__eyebrow"
                    >{{ label
                    }}<template v-if="kind"> · {{ kind }}</template></span
                >
                <h3 class="kit-card__name">{{ name }}</h3>
            </div>
            <img v-if="image" class="kit-card__art" :src="image" alt="" />
            <button
                v-if="actionLabel"
                type="button"
                class="kit-card__action"
                @click="$emit('action')"
            >
                {{ actionLabel }}
            </button>
        </div>
        <div v-if="tiers.length" class="kit-card__body">
            <div class="mod-tree" aria-hidden="true">
                <div
                    v-for="row in tiers"
                    :key="row.tier"
                    class="mod-tree__row"
                    :class="{ 'is-empty': !row.chosen }"
                >
                    <span
                        v-for="mod in row.mods"
                        :key="mod.id"
                        class="mod-tree__hex"
                        :class="{ 'is-selected': row.chosen?.id === mod.id }"
                        :title="`T${row.tier} ${mod.mod_index} · ${mod.mod_name}`"
                        ><img
                            v-if="mod.icon"
                            :src="`/assets/mods/optimized/${mod.icon}.svg`"
                            alt=""
                    /></span>
                </div>
                <span
                    v-if="hasOverclockSlot"
                    class="mod-tree__overclock"
                    :class="overclockClass"
                    :title="overclock?.overclock_name || 'No overclock'"
                    ><img
                        v-if="overclock?.icon"
                        :src="`/assets/mods/optimized/${overclock.icon}.svg`"
                        alt=""
                    /><template v-else>—</template></span
                >
            </div>
            <ol class="kit-choices">
                <li v-for="row in chosenRows" :key="row.tier">
                    <span class="kit-choices__key"
                        >T{{ row.tier }} · {{ row.chosen.mod_index }}</span
                    ><span
                        ><strong>{{ row.chosen.mod_name }}</strong
                        ><small>{{
                            clean(
                                row.chosen.text_description ||
                                    row.chosen.description
                            )
                        }}</small></span
                    >
                </li>
                <li v-if="openTiers" class="is-empty">
                    <span class="kit-choices__key">—</span
                    ><span
                        ><strong>{{ openTiers }}</strong></span
                    >
                </li>
                <li v-if="overclock" :class="overclockClass">
                    <span class="kit-choices__key is-overclock">OC</span
                    ><span
                        ><strong>{{ overclock.overclock_name }}</strong
                        ><small
                            >{{ overclock.overclock_type }} ·
                            {{ clean(overclock.text_description) }}</small
                        ></span
                    >
                </li>
            </ol>
        </div>
        <p v-else-if="description" class="kit-card__description">
            {{ clean(description) }}
        </p>
        <slot />
    </article>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    label: { type: String, required: true },
    name: { type: String, required: true },
    kind: { type: String, default: "" },
    image: { type: String, default: "" },
    mods: { type: Array, default: () => [] },
    selectedIds: { type: Array, default: () => [] },
    overclock: { type: Object, default: null },
    hasOverclockSlot: { type: Boolean, default: false },
    description: { type: String, default: "" },
    accent: { type: String, default: "" },
    actionLabel: { type: String, default: "" },
});
defineEmits(["action"]);

/** Upgrade rows, one per tier, with the chosen upgrade (if any). */
const tiers = computed(() => {
    const grouped = {};
    [...props.mods]
        .sort(
            (a, b) =>
                a.mod_tier - b.mod_tier ||
                String(a.mod_index).localeCompare(String(b.mod_index))
        )
        .forEach((mod) => (grouped[mod.mod_tier] ||= []).push(mod));

    return Object.entries(grouped).map(([tier, mods]) => ({
        tier: Number(tier),
        mods,
        chosen: mods.find((mod) => props.selectedIds.includes(mod.id)),
    }));
});
const chosenRows = computed(() => tiers.value.filter((row) => row.chosen));
const openTiers = computed(() => {
    const open = tiers.value.filter((row) => !row.chosen);
    if (!open.length) return "";
    if (open.length === 1) return `Tier ${open[0].tier} left open`;

    return `Tiers ${open[0].tier}–${open[open.length - 1].tier} left open`;
});
const overclockClass = computed(() =>
    props.overclock
        ? `kit-oc-${String(props.overclock.overclock_type).toLowerCase()}`
        : ""
);

function clean(text) {
    return (text || "").replace(/<[^>]*>/g, "").replace(/\\n/g, " ");
}
</script>
