<template>
    <div class="calc-stats">
        <dl v-if="calc.derived.length" class="calc-derived">
            <div v-for="metric in calc.derived" :key="metric.label">
                <dt>{{ metric.label }}</dt>
                <dd>
                    {{ figure(showPreview(metric) ? metric.preview : metric.value)
                    }}<small :class="note(metric).tone">{{
                        note(metric).text
                    }}</small>
                </dd>
            </div>
        </dl>

        <table class="calc-table">
            <thead>
                <tr>
                    <th scope="col">Stat</th>
                    <th scope="col">Base</th>
                    <th v-if="calc.hasBaseline" scope="col">Pinned</th>
                    <th scope="col">{{ calc.previewing ? "Swap" : "Build" }}</th>
                    <th scope="col" class="calc-delta">
                        {{ comparing ? "vs pin" : "Δ" }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="row in calc.visibleRows"
                    :key="row.key"
                    :class="{
                        'is-modified': row.modified && !row.previewed,
                        'is-previewed': row.previewed,
                        'is-inactive': row.inactive,
                    }"
                >
                    <th scope="row">{{ row.name }}</th>
                    <td class="calc-base">{{ formatStat(row, row.base) }}</td>
                    <td v-if="calc.hasBaseline" class="calc-base">
                        {{ formatStat(row, row.baseline) }}
                    </td>
                    <td class="calc-value">
                        {{
                            formatStat(row, row.previewed ? row.preview : row.value)
                        }}
                    </td>
                    <td class="calc-delta">
                        <span
                            v-if="row.previewed"
                            :class="tone(row, row.value, row.preview)"
                            >{{ statDifference(row, row.value, row.preview) }}</span
                        >
                        <span
                            v-else-if="row.highlighted"
                            class="is-highlight"
                            title="What the hovered upgrade adds to this build"
                            >{{ statDifference(row, row.without, row.value) }}</span
                        >
                        <template v-else-if="calc.hasBaseline">
                            <span
                                v-if="row.baselineDiff"
                                :class="tone(row, row.baseline, row.value)"
                                >{{
                                    statDifference(row, row.baseline, row.value)
                                }}</span
                            >
                        </template>
                        <span v-else-if="row.modified" class="is-changed">{{
                            statDifference(row, row.base, row.value)
                        }}</span>
                    </td>
                </tr>
            </tbody>
        </table>
        <button
            v-if="calc.hiddenCount || calc.state.showInactive"
            type="button"
            class="calc-link"
            :aria-expanded="calc.state.showInactive"
            @click="calc.state.showInactive = !calc.state.showInactive"
        >
            {{
                calc.state.showInactive
                    ? "Hide inactive stats"
                    : `Show ${calc.hiddenCount} inactive ${
                          calc.hiddenCount === 1 ? "stat" : "stats"
                      }`
            }}
        </button>
    </div>
</template>

<script setup>
import { computed } from "vue";
import {
    formatStat,
    isImprovement,
    statDifference,
} from "../useCalculator";

const props = defineProps({ calc: { type: Object, required: true } });

const comparing = computed(
    () => props.calc.hasBaseline && !props.calc.previewing
);

function showPreview(metric) {
    return (
        props.calc.previewing &&
        metric.preview !== null &&
        metric.preview !== metric.value
    );
}

/**
 * The line under a damage figure. It is always rendered, empty or not, so
 * hovering never changes the height of the stats column.
 */
function note(metric) {
    if (showPreview(metric)) {
        return {
            text: `${change(metric.value, metric.preview)} swap`,
            tone: metric.preview > metric.value ? "is-good" : "is-bad",
        };
    }
    if (
        props.calc.hasBaseline &&
        metric.baseline !== null &&
        metric.baseline !== metric.value
    ) {
        return {
            text: `${change(metric.baseline, metric.value)} vs pin`,
            tone: metric.value > metric.baseline ? "is-good" : "is-bad",
        };
    }
    return { text: "\u00a0", tone: "" };
}

function tone(row, from, to) {
    if (row.boolean) return "is-changed";
    return isImprovement(row.name, from, to) ? "is-good" : "is-bad";
}

function figure(value) {
    return value === null
        ? "—"
        : Number(value).toLocaleString(undefined, { maximumFractionDigits: 1 });
}

function change(from, to) {
    const difference = Math.round((to - from) * 10) / 10;
    return `${difference > 0 ? "+" : ""}${difference.toLocaleString()}`;
}
</script>
