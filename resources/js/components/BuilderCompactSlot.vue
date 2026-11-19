<template>
    <section class="compact-card compact-slot" :aria-label="label">
        <header class="compact-slot-head">
            <div>
                <span class="compact-label">{{ label }}</span>
                <strong>{{ active?.name || emptyName }}</strong>
            </div>
            <button
                v-if="active && clearable"
                type="button"
                class="text-action compact-clear"
                @click="$emit('clear')"
            >
                Clear
            </button>
            <label v-if="active && mods.length" class="compact-code">
                <span class="sr-only">{{ active.name }} upgrade code</span
                ><input
                    :value="codeDraft ?? signature"
                    :maxlength="tierCount"
                    spellcheck="false"
                    autocomplete="off"
                    title="Type a code like BACAB or 21312"
                    @focus="startCode"
                    @input="typeCode"
                    @blur="codeDraft = null"
                    @keydown.enter.prevent="$event.target.blur()"
            /></label>
        </header>
        <div class="compact-picker" role="group" :aria-label="`${label} choices`">
            <button
                v-for="item in items"
                :key="item.id"
                type="button"
                class="compact-pick"
                :class="{
                    'is-selected': active?.id === item.id,
                    'is-marked': markedIds.includes(item.id),
                }"
                :aria-pressed="active?.id === item.id"
                :aria-label="item.name"
                :title="item.name"
                @click="$emit('pick', item.id)"
            >
                <img :src="item.image" alt="" />
            </button>
        </div>
        <template v-if="active && mods.length">
            <div class="compact-slot-body">
                <div class="compact-tree-column">
                    <BuilderCompactTree
                        :mods="mods"
                        :selected="selectedIds"
                        :label="active.name"
                        @select="$emit('mod', $event)"
                    />
                    <BuilderCompactOverclock
                        v-if="overclocks"
                        :overclocks="overclocks"
                        :selected="overclock"
                        :label="active.name"
                        @select="$emit('overclock', $event)"
                    />
                </div>
                <dl
                    class="compact-deltas"
                    :aria-label="`Stats changed by this ${label.toLowerCase()} build`"
                >
                    <p v-if="!changedStats.length" class="compact-muted">
                        Stock stats
                    </p>
                    <div v-for="row in changedStats" :key="row.key">
                        <dt>{{ row.name }}</dt>
                        <dd>
                            <s>{{ row.from }}</s> <em>{{ row.to }}</em>
                        </dd>
                    </div>
                </dl>
            </div>
            <details class="compact-all-stats">
                <summary>All stats</summary>
                <BuilderStatReadout :equipment="active" :upgrades="upgrades" />
            </details>
        </template>
        <p v-else-if="!active" class="compact-empty">
            {{ emptyHint }}
        </p>
    </section>
</template>

<script setup>
import { computed, ref } from "vue";
import BuilderCompactOverclock from "./BuilderCompactOverclock.vue";
import BuilderCompactTree from "./BuilderCompactTree.vue";
import BuilderStatReadout from "./BuilderStatReadout.vue";
import { getModifiedStats } from "../statCalculator";

const props = defineProps({
    label: { type: String, required: true },
    emptyName: { type: String, default: "None" },
    emptyHint: { type: String, default: "Pick one above." },
    /** Picker choices as { id, name, image }. */
    items: { type: Array, default: () => [] },
    /** The chosen weapon or equipment, with its json_stats. */
    active: { type: Object, default: null },
    mods: { type: Array, default: () => [] },
    selectedIds: { type: Array, default: () => [] },
    /** Overclocks to offer, or null when this slot has none. */
    overclocks: { type: Array, default: null },
    overclock: { type: Object, default: null },
    markedIds: { type: Array, default: () => [] },
    clearable: { type: Boolean, default: false },
});
const emit = defineEmits(["pick", "mod", "overclock", "code", "clear"]);

/** What the player is typing in the code box, while it has focus. */
const codeDraft = ref(null);

const selectedMods = computed(() =>
    props.mods.filter((mod) => props.selectedIds.includes(mod.id))
);
const upgrades = computed(() => [
    ...selectedMods.value,
    ...(props.overclock ? [props.overclock] : []),
]);
const tierCount = computed(
    () => new Set(props.mods.map((mod) => mod.mod_tier)).size || 5
);

/** The equipped tiers as letters, e.g. "BAC––". */
const signature = computed(() => {
    const letters = Array(tierCount.value).fill("–");
    selectedMods.value.forEach((mod) => {
        letters[mod.mod_tier - 1] = mod.mod_index;
    });

    return letters.join("");
});

/** Only the stats this build actually moves away from stock. */
const changedStats = computed(() => {
    const base = props.active?.json_stats || {};
    const keys = Object.keys(base);

    return getModifiedStats(base, upgrades.value)
        .stats.map((stat, index) => ({
            key: keys[index],
            name: stat.name,
            boolean: stat.boolean,
            percent: stat.percent,
            base: numeric(stat.baseValue ?? stat.value),
            value: numeric(stat.value),
        }))
        .filter((row) => row.value !== row.base)
        .map((row) => ({
            key: row.key,
            name: row.name,
            from: format(row, row.base),
            to: format(row, row.value),
        }));
});

function startCode() {
    codeDraft.value = signature.value.replace(/–/g, "");
}

function typeCode(event) {
    codeDraft.value = event.target.value.toUpperCase();
    emit("code", codeDraft.value);
}

function numeric(value) {
    const parsed = parseFloat(value);

    return Number.isFinite(parsed) ? parsed : 0;
}

function format(row, value) {
    if (row.boolean) {
        return value ? "Yes" : "No";
    }

    return `${Math.round(value * 100) / 100}${row.percent ? "%" : ""}`;
}
</script>
