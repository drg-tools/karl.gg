<template>
    <div class="weapon-panel" :class="{ 'has-readout': gun }">
        <div class="weapon-main">
            <div class="section-heading">
                <div>
                    <p class="terminal-label">
                        Step 0{{ slot }} of 05
                        <template v-if="className"> · {{ className }}</template>
                    </p>
                    <h2>
                        {{ slot === 1 ? "Primary weapon" : "Secondary weapon" }}
                    </h2>
                </div>
                <span class="section-hint"
                    >{{ guns.length }} weapons<button
                        v-if="gun"
                        type="button"
                        class="text-action"
                        @click="$emit('clear')"
                    >
                        Remove weapon
                    </button></span
                >
            </div>
            <div
                class="weapon-picker"
                :style="{ '--weapons': guns.length }"
                role="group"
                :aria-label="
                    slot === 1 ? 'Primary weapons' : 'Secondary weapons'
                "
            >
                <button
                    v-for="weapon in guns"
                    :key="weapon.id"
                    type="button"
                    class="weapon-choice"
                    :class="{ 'is-selected': gun?.id === weapon.id }"
                    :aria-pressed="gun?.id === weapon.id"
                    @click="$emit('weapon', weapon.id)"
                >
                    <span class="weapon-silhouette"
                        ><img :src="`/assets/${weapon.image}.svg`" alt=""
                    /></span>
                    <span class="weapon-name">{{ weapon.name }}</span
                    ><small>{{ weapon.gun_class }}</small>
                </button>
            </div>
            <div v-if="gun" class="upgrade-column">
                <div class="subsection-heading">
                    <div>
                        <h3>Gear modifications</h3>
                        <p class="upgrade-help">
                            Pick any tiers you like. Hover to preview, click
                            to equip, click again to remove.
                        </p>
                    </div>
                    <div class="build-signature">
                        <span
                            class="signature-hexes"
                            :aria-label="`Upgrade combination ${combination}`"
                            ><i
                                v-for="(letter, index) in signature"
                                :key="index"
                                :class="{ 'is-set': letter !== '–' }"
                                aria-hidden="true"
                                >{{ letter }}</i
                            ><i
                                class="signature-overclock"
                                :class="
                                    overclock
                                        ? `is-set oc-${overclockType(
                                              overclock
                                          )}`
                                        : ''
                                "
                                aria-hidden="true"
                                >{{ overclock ? "★" : "·" }}</i
                            ></span
                        ><span class="tier-count"
                            >{{ selectedMods.length }} /
                            {{ tierCount }} tiers</span
                        >
                    </div>
                </div>
                <BuilderModGrid
                    :mods="gun.mods"
                    :selected="selectedIds"
                    @select="$emit('mod', $event)"
                    @preview="preview = $event ? { mod: $event } : null"
                />
                <fieldset class="overclock-tier">
                    <legend class="tier-label">
                        <strong>OC</strong><span>Optional</span>
                    </legend>
                    <div
                        class="overclock-options"
                        role="group"
                        aria-label="Overclock"
                        @mouseleave="preview = null"
                    >
                        <button
                            v-for="item in gun.overclocks"
                            :key="item.id"
                            type="button"
                            class="overclock-option"
                            :class="[
                                overclockClass(item),
                                { 'is-selected': overclock?.id === item.id },
                            ]"
                            :aria-pressed="overclock?.id === item.id"
                            @mouseenter="previewOverclock(item)"
                            @focus="previewOverclock(item)"
                            @blur="preview = null"
                            @click="chooseOverclock(item.id)"
                        >
                            <span class="overclock-octagon" aria-hidden="true"
                                ><img
                                    v-if="item.icon"
                                    :src="`/assets/mods/optimized/${item.icon}.svg`"
                                    alt=""
                                    @error="hideImage"
                            /></span>
                            <span
                                ><strong>{{ item.overclock_name }}</strong
                                ><small>{{ item.overclock_type }}</small></span
                            >
                        </button>
                        <button
                            type="button"
                            class="overclock-option is-standard"
                            :class="{ 'is-selected': !overclock }"
                            :aria-pressed="!overclock"
                            @mouseenter="previewOverclock(null)"
                            @focus="previewOverclock(null)"
                            @blur="preview = null"
                            @click="chooseOverclock(null)"
                        >
                            <span class="overclock-octagon" aria-hidden="true"
                                >—</span
                            >
                            <span
                                ><strong>Standard issue</strong
                                ><small>No overclock</small></span
                            >
                        </button>
                    </div>
                    <div
                        class="overclock-slot"
                        :class="overclockClass(shownOverclock)"
                    >
                        <span class="overclock-octagon" aria-hidden="true"
                            ><img
                                v-if="shownOverclock?.icon"
                                :src="`/assets/mods/optimized/${shownOverclock.icon}.svg`"
                                alt=""
                                @error="hideImage"
                            /><span v-else>+</span></span
                        ><span class="overclock-summary"
                            ><strong>{{
                                shownOverclock?.overclock_name || "Overclock"
                            }}</strong
                            ><span>{{
                                shownOverclock
                                    ? `${
                                          shownOverclock.overclock_type
                                      } · ${cleanDescription(
                                          shownOverclock.text_description
                                      )}`
                                    : `${
                                          gun.overclocks?.length || 0
                                      } available. Every one has trade-offs.`
                            }}</span></span
                        >
                    </div>
                </fieldset>
            </div>
            <div v-else class="empty-slot">
                <span aria-hidden="true">↖</span>
                <h3>Pick your {{ slot === 1 ? "primary" : "secondary" }}.</h3>
                <p>
                    A starter setup is equipped automatically. Swap any tier to
                    make it yours.
                </p>
            </div>
        </div>
        <aside
            v-if="gun"
            class="weapon-readout"
            :class="{ 'is-previewing': previewUpgrades }"
            aria-label="Weapon statistics"
        >
            <div class="readout-title">
                <span class="readout-mode">{{
                    previewUpgrades ? "Previewing swap" : "Live readout"
                }}</span
                ><span>{{ slot === 1 ? "Primary" : "Secondary" }}</span>
            </div>
            <img
                :src="`/assets/${gun.image}.svg`"
                alt=""
                class="readout-weapon"
            />
            <h3>{{ gun.name }}</h3>
            <p class="readout-subtitle">
                <span>{{ gun.gun_class }}</span
                ><span>{{
                    overclock?.overclock_name || "Standard issue"
                }}</span>
            </p>
            <BuilderStatReadout
                :equipment="gun"
                :upgrades="equippedUpgrades"
                :preview-upgrades="previewUpgrades"
                :highlight-upgrades="highlightUpgrades"
            />
            <CreditsCalculator
                :selected-mods="selectedMods"
                :overclock="overclock"
            />
            <div class="readout-legend" aria-hidden="true">
                <span class="is-modified">Modified by build</span
                ><span class="is-previewed">Changes on swap</span>
            </div>
            <details
                class="advanced-readout"
                ref="advancedDetails"
                @toggle="loadMetrics"
            >
                <summary>
                    Advanced statistics
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
</template>

<script setup>
import { computed, ref, watch } from "vue";
import BuilderModGrid from "./BuilderModGrid.vue";
import BuilderStatReadout from "./BuilderStatReadout.vue";
import CreditsCalculator from "./CreditsCalculator.vue";

const props = defineProps({
    slot: Number,
    guns: Array,
    gun: Object,
    selectedIds: Array,
    overclock: Object,
    metrics: Object,
    className: { type: String, default: "" },
});
const emit = defineEmits(["weapon", "mod", "overclock", "metrics", "clear"]);

/** @type {import('vue').Ref<{mod?: object, overclock?: object|null}|null>} */
const preview = ref(null);

const selectedMods = computed(
    () =>
        props.gun?.mods.filter((mod) => props.selectedIds.includes(mod.id)) ||
        []
);
const equippedUpgrades = computed(() => [
    ...selectedMods.value,
    ...(props.overclock ? [props.overclock] : []),
]);
/** Whether the hovered upgrade or overclock is already part of the build. */
const previewIsEquipped = computed(() => {
    if (!preview.value) {
        return false;
    }
    if (preview.value.mod) {
        return props.selectedIds.includes(preview.value.mod.id);
    }

    return (
        (preview.value.overclock?.id ?? null) === (props.overclock?.id ?? null)
    );
});
const previewUpgrades = computed(() => {
    if (!preview.value || previewIsEquipped.value) {
        return null;
    }
    if (preview.value.mod) {
        const mod = preview.value.mod;

        return [
            ...selectedMods.value.filter(
                (selected) => selected.mod_tier !== mod.mod_tier
            ),
            mod,
            ...(props.overclock ? [props.overclock] : []),
        ];
    }

    return [
        ...selectedMods.value,
        ...(preview.value.overclock ? [preview.value.overclock] : []),
    ];
});
/** The build without the hovered equipped upgrade, to show what it changes. */
const highlightUpgrades = computed(() => {
    if (!previewIsEquipped.value) {
        return null;
    }
    if (preview.value.mod) {
        return [
            ...selectedMods.value.filter(
                (mod) => mod.id !== preview.value.mod.id
            ),
            ...(props.overclock ? [props.overclock] : []),
        ];
    }

    return props.overclock ? selectedMods.value : null;
});
const shownOverclock = computed(() =>
    preview.value && "overclock" in preview.value
        ? preview.value.overclock
        : props.overclock
);
const tierCount = computed(
    () => new Set(props.gun?.mods.map((mod) => mod.mod_tier)).size
);
const signature = computed(() => {
    const letters = Array(tierCount.value || 5).fill("–");
    selectedMods.value.forEach((mod) => {
        letters[mod.mod_tier - 1] = mod.mod_index;
    });

    return letters;
});
const combination = computed(
    () =>
        signature.value.join("") +
        " / " +
        (props.overclock?.overclock_name || "no overclock")
);
const advancedDetails = ref(null);

watch(
    [() => props.gun?.id, () => props.selectedIds, () => props.overclock?.id],
    () => {
        if (advancedDetails.value?.open) emit("metrics");
    }
);
watch(
    () => props.gun?.id,
    () => {
        preview.value = null;
    }
);

function previewOverclock(item) {
    preview.value = { overclock: item };
}

function chooseOverclock(id) {
    emit("overclock", id);
}

function overclockType(item) {
    return String(item?.overclock_type || "").toLowerCase();
}

function overclockClass(item) {
    return item ? `oc-${overclockType(item)}` : "";
}

function cleanDescription(text) {
    return (text || "").replace(/<[^>]*>/g, "").replace(/\\n/g, " ");
}

function hideImage(event) {
    event.target.hidden = true;
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
