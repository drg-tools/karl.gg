<template>
    <section class="calc-inspector" aria-live="polite">
        <template v-if="target">
            <div class="calc-kicker">
                <span v-if="target.kind === 'mod'" class="calc-label"
                    >Tier {{ target.item.mod_tier }} ·
                    {{ target.item.mod_index }}</span
                >
                <span v-else class="calc-label"
                    >Overclock ·
                    <span
                        :class="`is-${target.item.overclock_type?.toLowerCase()}`"
                        >{{ target.item.overclock_type }}</span
                    ></span
                >
                <span
                    class="calc-label"
                    :class="equipped ? 'is-equipped' : 'is-preview'"
                    >{{ equipped ? "Equipped" : "Preview" }}</span
                >
            </div>
            <h3>{{ upgradeName(target.item) }}</h3>
            <p>{{ upgradeDescription(target.item) }}</p>
            <ul v-if="effects.length">
                <li v-for="effect in effects" :key="effect.name">
                    <span>{{ effect.name }}</span
                    ><span
                        >{{ effect.from }} →
                        <b :class="effect.good ? 'is-good' : 'is-bad'">{{
                            effect.to
                        }}</b></span
                    >
                </li>
            </ul>
            <p v-if="target.item.credits_cost" class="calc-cost">
                {{ target.item.credits_cost.toLocaleString() }} credits
            </p>
        </template>
        <p v-else class="calc-hint">
            Hover or focus any upgrade to read its full effect here. The stats
            column previews the swap.
        </p>
    </section>
</template>

<script setup>
import { computed } from "vue";
import { upgradeDescription, upgradeName } from "../useCalculator";

const props = defineProps({ calc: { type: Object, required: true } });

/** The hovered upgrade, or the equipped overclock when nothing is hovered. */
const target = computed(() => {
    if (props.calc.state.hover?.item) return props.calc.state.hover;
    if (props.calc.overclock)
        return { kind: "overclock", item: props.calc.overclock };
    return null;
});
const equipped = computed(() => props.calc.isEquipped(target.value));
const effects = computed(() => props.calc.effectOf(target.value));
</script>
