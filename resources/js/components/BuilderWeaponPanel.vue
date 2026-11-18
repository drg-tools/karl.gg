<template>
    <div>
        <div class="section-heading">
            <div>
                <p class="terminal-label">
                    {{ slot === 1 ? "01 / Firepower" : "02 / Backup plan" }}
                </p>
                <h2>
                    {{ slot === 1 ? "Primary weapon" : "Secondary weapon" }}
                </h2>
            </div>
            <span class="section-hint"
                >Choose your weapon. Tune your build.</span
            >
        </div>
        <div
            class="weapon-picker"
            :dusk="slot === 1 ? 'primary-selectors' : 'secondary-selectors'"
            role="group"
            :aria-label="slot === 1 ? 'Primary weapons' : 'Secondary weapons'"
        >
            <button
                v-for="weapon in guns"
                :key="weapon.id"
                type="button"
                class="weapon-choice"
                :class="{ 'is-selected': gun?.id === weapon.id }"
                :aria-pressed="gun?.id === weapon.id"
                :dusk="`weapon-${weapon.id}`"
                @click="$emit('weapon', weapon.id)"
            >
                <img
                    :src="`/assets/${weapon.image}.svg`"
                    alt=""
                    class="weapon-silhouette"
                />
                <span>{{ weapon.name }}</span
                ><small>{{ weapon.gun_class }}</small>
                <span v-if="gun?.id === weapon.id" class="weapon-equipped"
                    >Equipped <span aria-hidden="true">✓</span></span
                >
            </button>
        </div>
        <div v-if="gun" class="weapon-configuration">
            <div class="upgrade-column">
                <div class="subsection-heading">
                    <h3>Weapon upgrades</h3>
                    <span
                        >{{ selectedMods.length }} / {{ tierCount }} tiers</span
                    >
                </div>
                <p class="upgrade-help">
                    A starter setup is selected. Swap any tier to make it yours.
                </p>
                <BuilderModGrid
                    :mods="gun.mods"
                    :selected="selectedIds"
                    @select="$emit('mod', $event)"
                />
                <div class="subsection-heading overclock-heading">
                    <h3>Overclock</h3>
                    <span>Optional</span>
                </div>
                <div
                    class="overclock-options"
                    role="group"
                    aria-label="Overclock"
                >
                    <button
                        type="button"
                        class="overclock-option"
                        :class="{ 'is-selected': !overclock }"
                        :aria-pressed="!overclock"
                        @click="$emit('overclock', null)"
                    >
                        <span class="overclock-marker" aria-hidden="true"
                            >—</span
                        ><span
                            ><strong>Standard issue</strong
                            ><small>No overclock. No trade-offs.</small></span
                        ><span class="upgrade-check" aria-hidden="true">{{
                            !overclock ? "✓" : "+"
                        }}</span>
                    </button>
                    <button
                        v-for="item in gun.overclocks"
                        :key="item.id"
                        type="button"
                        class="overclock-option"
                        :class="{ 'is-selected': overclock?.id === item.id }"
                        :aria-pressed="overclock?.id === item.id"
                        :dusk="`overclock-${item.id}`"
                        @click="$emit('overclock', item.id)"
                    >
                        <span
                            class="overclock-marker"
                            :class="`oc-${String(
                                item.overclock_type || ''
                            ).toLowerCase()}`"
                            aria-hidden="true"
                            >⬡</span
                        >
                        <span
                            ><strong>{{ item.overclock_name }}</strong
                            ><small
                                >{{ item.overclock_type }} ·
                                {{
                                    cleanDescription(item.text_description)
                                }}</small
                            ></span
                        ><span class="upgrade-check" aria-hidden="true">{{
                            overclock?.id === item.id ? "✓" : "+"
                        }}</span>
                    </button>
                </div>
            </div>
            <aside class="weapon-readout" aria-label="Weapon statistics">
                <div class="readout-title">
                    <span class="terminal-label">Live readout</span
                    ><span class="live-dot" aria-hidden="true"></span>
                </div>
                <img
                    :src="`/assets/${gun.image}.svg`"
                    alt=""
                    class="readout-weapon"
                />
                <h3>{{ gun.name }}</h3>
                <p class="readout-subtitle">
                    {{ overclock?.overclock_name || "Standard issue" }}
                </p>
                <div class="combination-code" aria-label="Upgrade combination">
                    {{ combination }}
                </div>
                <div class="stat-columns">
                    <span>Weapon stats</span><span>Base → Build</span>
                </div>
                <MainStatsDisplay
                    :equipment="gun"
                    :mods="selectedMods"
                    :overclock="overclock"
                />
                <CreditsCalculator
                    :selected-mods="selectedMods"
                    :overclock="overclock"
                />
                <details
                    class="advanced-readout"
                    ref="advancedDetails"
                    @toggle="loadMetrics"
                >
                    <summary>
                        Advanced statistics <span aria-hidden="true">↗</span>
                    </summary>
                    <p v-if="metrics?.message" role="status">
                        {{ metrics.message }}
                    </p>
                    <dl v-if="metrics?.data">
                        <template
                            v-for="(value, key) in metricValues(metrics.data)"
                            :key="key"
                            ><dt>{{ key.replaceAll("_", " ") }}</dt>
                            <dd>
                                {{
                                    typeof value === "number"
                                        ? value.toLocaleString(undefined, {
                                              maximumFractionDigits: 2,
                                          })
                                        : value
                                }}
                            </dd></template
                        >
                    </dl>
                    <button
                        v-if="metrics?.failed"
                        type="button"
                        @click="$emit('metrics')"
                        class="text-action"
                    >
                        Try again
                    </button>
                </details>
            </aside>
        </div>
        <div v-else class="empty-slot">
            <span aria-hidden="true">↖</span>
            <h3>Pick your {{ slot === 1 ? "primary" : "secondary" }}.</h3>
            <p>Select a weapon above to explore its upgrades and stats.</p>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import BuilderModGrid from "./BuilderModGrid.vue";
import MainStatsDisplay from "./MainStatsDisplay.vue";
import CreditsCalculator from "./CreditsCalculator.vue";
const props = defineProps({
    slot: Number,
    guns: Array,
    gun: Object,
    selectedIds: Array,
    overclock: Object,
    metrics: Object,
});
const emit = defineEmits(["weapon", "mod", "overclock", "metrics"]);
const selectedMods = computed(
    () =>
        props.gun?.mods.filter((mod) => props.selectedIds.includes(mod.id)) ||
        []
);
const tierCount = computed(
    () => new Set(props.gun?.mods.map((mod) => mod.mod_tier)).size
);
const combination = computed(() => {
    const parts = Array(5).fill("–");
    selectedMods.value.forEach((mod) => {
        parts[mod.mod_tier - 1] = String(mod.mod_index).charCodeAt(0) - 64;
    });
    return parts.join("") + " / " + (props.overclock?.overclock_index || "–");
});
const advancedDetails = ref(null);
watch(
    [() => props.gun?.id, () => props.selectedIds, () => props.overclock?.id],
    () => {
        if (advancedDetails.value?.open) emit("metrics");
    }
);
function cleanDescription(text) {
    return (text || "").replace(/<[^>]*>/g, "").replace(/\\n/g, " ");
}
function metricValues(data) {
    return Object.fromEntries(
        Object.entries(data).filter(
            ([key]) =>
                ![
                    "id",
                    "gun_id",
                    "character_id",
                    "build_combination",
                    "weapon_short_name",
                ].includes(key)
        )
    );
}
function loadMetrics(event) {
    if (event.target.open) emit("metrics");
}
</script>
