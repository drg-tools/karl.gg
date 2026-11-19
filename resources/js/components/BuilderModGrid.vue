<template>
    <div class="upgrade-matrix">
        <fieldset
            v-for="(tierMods, tier) in tiers"
            :key="tier"
            class="upgrade-tier"
        >
            <legend class="tier-label">
                <strong>T{{ tier }}</strong
                ><span v-if="tierLevels[tier - 1]"
                    >LV {{ tierLevels[tier - 1] }}</span
                ><span class="sr-only">{{
                    tierMods.some((mod) => selected.includes(mod.id))
                        ? "Equipped"
                        : "Choose an upgrade"
                }}</span>
            </legend>
            <div
                class="upgrade-options"
                :style="{ '--options': tierMods.length }"
                @mouseleave="$emit('preview', null)"
                @focusout="leaveTier"
            >
                <button
                    v-for="mod in tierMods"
                    :key="mod.id"
                    type="button"
                    class="upgrade-option"
                    :class="{
                        'is-selected': selected.includes(mod.id),
                        'is-inspected':
                            inspected[tier] === mod.id &&
                            !selected.includes(mod.id),
                    }"
                    :aria-pressed="selected.includes(mod.id)"
                    :aria-label="mod.mod_name"
                    :aria-describedby="`${descriptionId}-${mod.id}`"
                    :dusk="`mod-${mod.id}`"
                    @mouseenter="inspect(mod)"
                    @focus="inspect(mod)"
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
                            >{{ mod.mod_index
                            }}<template v-if="selected.includes(mod.id)">
                                · Equipped</template
                            ></span
                        ><strong>{{ mod.mod_name }}</strong
                        ><span class="upgrade-effect" aria-hidden="true">{{
                            cleanDescription(
                                mod.text_description || mod.description
                            )
                        }}</span></span
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
                :class="{ 'is-preview': isPreview(tier, tierMods) }"
                :dusk="`tier-details-${tier}`"
            >
                <span class="inspection-key"
                    >{{
                        selected.includes(detailFor(tier, tierMods).id)
                            ? "Equipped"
                            : isPreview(tier, tierMods)
                            ? "Preview"
                            : "Not equipped"
                    }}
                    · {{ detailFor(tier, tierMods).mod_index }}</span
                >
                <p>
                    {{
                        cleanDescription(
                            detailFor(tier, tierMods).text_description ||
                                detailFor(tier, tierMods).description
                        ) || "No additional effects listed."
                    }}
                </p>
                <span
                    v-if="effects(detailFor(tier, tierMods), tierMods).length"
                    class="inspection-effects"
                    ><span
                        v-for="effect in effects(
                            detailFor(tier, tierMods),
                            tierMods
                        )"
                        :key="effect.name"
                        class="effect-chip"
                        >{{ effect.name }} <s>{{ effect.from }}</s
                        ><span aria-hidden="true">→</span
                        ><span class="sr-only"> to </span>{{ effect.to }}</span
                    ></span
                >
            </div>
        </fieldset>
    </div>
</template>

<script setup>
import { computed, reactive, useId } from "vue";
import { getModifiedStats } from "../statCalculator";

const props = defineProps({
    mods: { type: Array, default: () => [] },
    selected: { type: Array, default: () => [] },
    baseStats: { type: Object, default: null },
    extraUpgrades: { type: Array, default: () => [] },
});
const emit = defineEmits(["select", "preview"]);
const inspected = reactive({});
const descriptionId = useId();
const tierLevels = [1, 4, 8, 12, 16];
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
const selectedMods = computed(() =>
    props.mods.filter((mod) => props.selected.includes(mod.id))
);

function detailFor(tier, mods) {
    return (
        mods.find((mod) => mod.id === inspected[tier]) ||
        mods.find((mod) => props.selected.includes(mod.id)) ||
        mods[0]
    );
}

function isPreview(tier, mods) {
    const mod = detailFor(tier, mods);

    return inspected[tier] === mod.id && !props.selected.includes(mod.id);
}

function inspect(mod) {
    inspected[mod.mod_tier] = mod.id;
    emit("preview", props.selected.includes(mod.id) ? null : mod);
}

function leaveTier(event) {
    if (!event.currentTarget.contains(event.relatedTarget)) {
        emit("preview", null);
    }
}

function selectUpgrade(mod) {
    inspected[mod.mod_tier] = mod.id;
    emit("preview", null);
    emit("select", mod);
}

/**
 * Stat changes a mod makes: what it adds on top of the rest of the build
 * when equipped, or what swapping to it would change when it is not.
 *
 * @return {Array<{name: string, from: string, to: string}>}
 */
function effects(mod, tierMods) {
    if (!props.baseStats) {
        return [];
    }
    const others = selectedMods.value.filter(
        (selectedMod) => !tierMods.includes(selectedMod)
    );
    const before = props.selected.includes(mod.id)
        ? [...others, ...props.extraUpgrades]
        : [...selectedMods.value, ...props.extraUpgrades];
    const after = [...others, mod, ...props.extraUpgrades];
    const from = getModifiedStats(props.baseStats, before).stats;
    const to = getModifiedStats(props.baseStats, after).stats;

    return to
        .map((stat, index) => ({ stat, previous: from[index] }))
        .filter(
            ({ stat, previous }) =>
                parseFloat(stat.value) !== parseFloat(previous.value)
        )
        .map(({ stat, previous }) => ({
            name: stat.name,
            from: formatStat(stat, previous.value),
            to: formatStat(stat, stat.value),
        }));
}

function formatStat(stat, value) {
    if (stat.boolean) {
        return parseFloat(value) ? "Yes" : "No";
    }

    return `${parseFloat(value)}${stat.percent ? "%" : ""}`;
}

function cleanDescription(text) {
    return (text || "").replace(/<[^>]*>/g, "").replace(/\\n/g, " ");
}

function hideImage(event) {
    event.target.hidden = true;
}
</script>
