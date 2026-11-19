<template>
    <div class="stat-readout" :class="{ 'is-previewing': previewing }">
        <div class="readout-columns" aria-hidden="true">
            <span>Stat</span><span>Base</span
            ><span>{{ previewing ? "If swapped" : "Build" }}</span>
        </div>
        <dl class="readout-rows">
            <div
                v-for="row in visibleRows"
                :key="row.key"
                class="readout-row"
                :class="{
                    'is-modified': row.modified && !row.previewed,
                    'is-previewed': row.previewed,
                    'is-inactive': row.inactive,
                }"
            >
                <dt>{{ row.name }}</dt>
                <dd class="readout-base">{{ format(row, row.base) }}</dd>
                <dd class="readout-value">
                    {{ format(row, row.previewed ? row.preview : row.value)
                    }}<small v-if="row.previewed"
                        >was {{ format(row, row.value) }} ·
                        {{ difference(row, row.value, row.preview) }}</small
                    ><small v-else-if="row.modified">{{
                        difference(row, row.base, row.value)
                    }}</small>
                </dd>
            </div>
        </dl>
        <button
            v-if="hiddenCount || showInactive"
            type="button"
            class="readout-toggle"
            :aria-expanded="showInactive"
            @click="showInactive = !showInactive"
        >
            {{
                showInactive
                    ? "Hide inactive stats"
                    : `Show ${hiddenCount} inactive ${
                          hiddenCount === 1 ? "stat" : "stats"
                      }`
            }}
            <span aria-hidden="true">{{ showInactive ? "▴" : "▾" }}</span>
        </button>
        <dl v-if="derived.length" class="readout-derived">
            <div v-for="metric in derived" :key="metric.label">
                <dt>{{ metric.label }}</dt>
                <dd>{{ metric.value }}</dd>
            </div>
        </dl>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { calculateDamage, getModifiedStats } from "../statCalculator";

const props = defineProps({
    equipment: { type: Object, required: true },
    upgrades: { type: Array, default: () => [] },
    previewUpgrades: { type: Array, default: null },
});

const showInactive = ref(false);

/**
 * Number value of a calculated stat, tolerant of the string values the
 * shared calculator returns.
 */
function numeric(value) {
    const parsed = parseFloat(value);
    return Number.isFinite(parsed) ? parsed : 0;
}

function calculate(upgrades) {
    return Object.fromEntries(
        getModifiedStats(props.equipment.json_stats || {}, upgrades).stats.map(
            (stat, index) => [
                Object.keys(props.equipment.json_stats || {})[index],
                stat,
            ]
        )
    );
}

const current = computed(() => calculate(props.upgrades));
const preview = computed(() =>
    props.previewUpgrades ? calculate(props.previewUpgrades) : null
);

const rows = computed(() =>
    Object.entries(current.value).map(([key, stat]) => {
        const base = numeric(stat.baseValue ?? stat.value);
        const value = numeric(stat.value);
        const previewValue = preview.value
            ? numeric(preview.value[key]?.value)
            : value;
        const modified = value !== base;
        const previewed = previewValue !== value;

        return {
            key,
            name: stat.name,
            percent: stat.percent,
            boolean: stat.boolean,
            base,
            value,
            preview: previewValue,
            modified,
            previewed,
            inactive: !modified && !previewed && base === 0,
        };
    })
);

const previewing = computed(() => rows.value.some((row) => row.previewed));
const visibleRows = computed(() =>
    rows.value.filter((row) => showInactive.value || !row.inactive)
);
const hiddenCount = computed(
    () => rows.value.length - visibleRows.value.length
);

const derived = computed(() => {
    const damage = calculateDamage(
        Object.values(current.value).map((stat) => ({ ...stat })),
        props.equipment.gun_class
    );
    const usable = (value) =>
        value !== undefined && Number.isFinite(Number(value)) && Number(value);

    return [
        ["Damage per second", damage.dps],
        ["Magazine damage", damage.dpm],
        ["Total damage", damage.dpa],
    ]
        .filter(([, value]) => usable(value))
        .map(([label, value]) => ({
            label,
            value: Number(value).toLocaleString(undefined, {
                maximumFractionDigits: 1,
            }),
        }));
});

function format(row, value) {
    if (row.boolean) {
        return value ? "Yes" : "No";
    }
    const rounded = Math.round(value * 100) / 100;

    return `${Math.abs(rounded) >= 1000 ? rounded.toLocaleString() : rounded}${
        row.percent ? "%" : ""
    }`;
}

function difference(row, from, to) {
    if (row.boolean) {
        return to ? "enabled" : "disabled";
    }
    const change = Math.round((to - from) * 100) / 100;

    return `${change > 0 ? "+" : ""}${change}${row.percent ? "%" : ""}`;
}
</script>
