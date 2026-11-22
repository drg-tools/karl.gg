<template>
    <section
        class="calc-inspector"
        :class="{ 'is-compact': compact }"
        aria-live="polite"
    >
        <template v-if="target">
            <div class="calc-kicker">
                <span v-if="target.kind === 'mod'" class="calc-label"
                    >Tier {{ target.item.mod_tier }} ·
                    {{ target.item.mod_index }}</span
                >
                <span v-else class="calc-label"
                    >OC ·
                    <span
                        :class="`is-${target.item.overclock_type?.toLowerCase()}`"
                        >{{ target.item.overclock_type }}</span
                    ></span
                >
                <span
                    class="calc-label"
                    :class="equipped ? 'is-equipped' : 'is-preview'"
                    >{{ equipped ? "Equipped" : "Not equipped" }}</span
                >
            </div>
            <h3 :title="upgradeName(target.item)">
                {{ upgradeName(target.item) }}
            </h3>
            <p
                ref="description"
                class="calc-description"
                :class="{ 'is-clamped': !expanded }"
            >
                {{ upgradeDescription(target.item) }}
            </p>
            <button
                type="button"
                class="calc-link"
                :class="{ 'is-hidden': !truncated && !hiddenEffects && !expanded }"
                :aria-expanded="expanded"
                @click="expanded = !expanded"
            >
                {{
                    expanded
                        ? "Less"
                        : hiddenEffects
                        ? `More (+${hiddenEffects} stats)`
                        : "More"
                }}
            </button>
            <ul v-if="effects.length">
                <li v-for="effect in shownEffects" :key="effect.name">
                    <span :title="effect.name">{{ effect.name }}</span
                    ><span
                        >{{ effect.from }} →
                        <b :class="effect.good ? 'is-good' : 'is-bad'">{{
                            effect.to
                        }}</b></span
                    >
                </li>
            </ul>
            <p v-if="target.item.credits_cost && !compact" class="calc-cost">
                {{ target.item.credits_cost.toLocaleString() }} credits
            </p>
        </template>
        <p v-else-if="compact" class="calc-hint">
            Tap an upgrade to equip it and see what it changes. Tap it again to
            take it off.
        </p>
        <p v-else class="calc-hint">
            Hover or focus any upgrade to read its full effect here. The stats
            column previews the swap.
        </p>
    </section>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { upgradeDescription, upgradeName } from "../useCalculator";

const props = defineProps({
    calc: { type: Object, required: true },
    /** The phone dock: shorter text, fewer rows, no cost line. */
    compact: { type: Boolean, default: false },
});

/** The last upgrade hovered, or the equipped overclock before any hover. */
const target = computed(() => {
    if (props.calc.state.inspected?.item) return props.calc.state.inspected;
    if (props.calc.overclock)
        return { kind: "overclock", item: props.calc.overclock };
    return null;
});
const equipped = computed(() => props.calc.isEquipped(target.value));
const effects = computed(() => props.calc.effectOf(target.value));

/*
 * The panel keeps one height while hovering: descriptions are cut to three
 * lines and stat changes to four rows, and "More" shows the rest.
 */
const effectLimit = props.compact ? 3 : 4;
const shownEffects = computed(() =>
    expanded.value ? effects.value : effects.value.slice(0, effectLimit)
);
const hiddenEffects = computed(() =>
    Math.max(0, effects.value.length - effectLimit)
);
const description = ref(null);
const expanded = ref(false);
const truncated = ref(false);
watch(
    () => target.value?.item,
    () => {
        expanded.value = false;
        truncated.value =
            !!description.value &&
            description.value.scrollHeight > description.value.clientHeight + 1;
    },
    { flush: "post", immediate: true }
);
</script>
