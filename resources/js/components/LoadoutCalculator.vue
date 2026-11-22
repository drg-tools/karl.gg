<template>
    <div class="calculator" :style="{ '--class-accent': calc.color }">
        <header class="calc-intro">
            <div>
                <span class="calc-label">Beta · Unlisted</span>
                <h1>Build calculator</h1>
            </div>
            <p>
                Try upgrade combinations and compare their stats. Picks stay in
                this browser.
                <a href="/build">Build a loadout to save and share →</a>
            </p>
        </header>

        <div class="calc-frame">
            <nav class="calc-classes" aria-label="Class">
                <button
                    v-for="character in config.characters"
                    :key="character.id"
                    type="button"
                    :class="{ 'is-active': calc.state.characterId === character.id }"
                    :style="{ '--tab-accent': classColors[character.name] }"
                    :aria-pressed="calc.state.characterId === character.id"
                    @click="calc.selectCharacter(character.id)"
                >
                    <img
                        :src="`/assets/img/${character.name}_portrait-128px.png`"
                        alt=""
                    />{{ character.name }}
                </button>
            </nav>

            <nav
                v-if="calc.catalog"
                ref="kitNav"
                class="calc-kit"
                aria-label="Item"
            >
                <div
                    v-for="group in kitGroups"
                    :key="group.label"
                    class="calc-kit-group"
                >
                    <span class="calc-kit-label">{{ group.label }}</span>
                    <button
                        v-for="entry in group.items"
                        :key="entry.key"
                        type="button"
                        class="calc-kit-tab"
                        :class="{ 'is-active': isActiveTab(entry) }"
                        :aria-pressed="isActiveTab(entry)"
                        :title="entry.title"
                        @click="calc.selectTab(entry.kind, entry.id)"
                    >
                        <img :src="entry.image" alt="" />{{ entry.label
                        }}<i
                            v-if="entry.touched"
                            class="calc-dot"
                            title="Has upgrades picked"
                        ></i>
                    </button>
                </div>
            </nav>

            <div v-if="calc.error" class="calc-message" role="alert">
                <p>{{ calc.error }}</p>
                <button
                    type="button"
                    class="calc-button"
                    @click="calc.loadCatalog(calc.state.characterId)"
                >
                    Retry
                </button>
            </div>
            <p v-else-if="!calc.catalog" class="calc-message">
                Loading {{ calc.character?.name }} armory…
            </p>

            <div v-else-if="subject" class="calc-columns">
                <div class="calc-col calc-title">
                    <img :src="subject.image" alt="" />
                    <div>
                        <h2>{{ subject.item.name }}</h2>
                        <p>
                            {{ subjectKind
                            }}<template v-if="calc.tiers.length">
                                · {{ calc.code }}</template
                            ><template v-if="calc.overclock">
                                · {{ calc.overclock.overclock_name }}</template
                            >
                        </p>
                    </div>
                </div>

                <section class="calc-col calc-stats-col" aria-label="Statistics">
                    <CalculatorStatTable :calc="calc" />
                    <p v-if="calc.credits" class="calc-footnote">
                        {{ calc.credits.toLocaleString() }} credits to unlock
                        these upgrades
                    </p>
                </section>

                <section class="calc-col calc-tree" aria-label="Upgrades">
                    <template v-if="calc.tiers.length">
                        <div class="calc-codebar">
                            <label>
                                <span class="calc-label">Code</span>
                                <input
                                    class="calc-code"
                                    :value="codeDraft ?? displayCode"
                                    :maxlength="calc.tiers.length"
                                    inputmode="numeric"
                                    autocomplete="off"
                                    :placeholder="'-'.repeat(calc.tiers.length)"
                                    aria-describedby="calc-code-hint"
                                    @input="typeCode($event.target.value)"
                                    @focus="$event.target.select()"
                                    @blur="codeDraft = null"
                                />
                            </label>
                            <span id="calc-code-hint" class="calc-hint"
                                >One digit per tier: 1–3 picks A–C, 0 skips
                                it.</span
                            >
                            <button
                                type="button"
                                class="calc-button"
                                :disabled="!calc.selectedMods.length && !calc.overclock"
                                @click="calc.clearBuild()"
                            >
                                Clear
                            </button>
                        </div>

                        <div
                            v-for="tier in calc.tiers"
                            :key="tier.tier"
                            class="calc-tier"
                            role="group"
                            :aria-label="`Tier ${tier.tier}`"
                            @pointerleave="pointerLeave"
                        >
                            <div class="calc-tier-label">
                                T{{ tier.tier
                                }}<small v-if="tier.level"
                                    >Lv {{ tier.level }}</small
                                >
                            </div>
                            <button
                                v-for="mod in tier.mods"
                                :key="mod.id"
                                type="button"
                                class="calc-option"
                                :class="{
                                    'is-selected': calc.isEquipped({
                                        kind: 'mod',
                                        item: mod,
                                    }),
                                }"
                                :aria-pressed="
                                    calc.isEquipped({ kind: 'mod', item: mod })
                                "
                                @pointerdown="lastPointer = $event.pointerType"
                                @pointerenter="pointerEnter($event, 'mod', mod)"
                                @focus="keyboardFocus($event, 'mod', mod)"
                                @blur="calc.unhover()"
                                @click="pick('mod', mod)"
                            >
                                <span class="calc-hex"
                                    ><img
                                        :src="`/assets/mods/optimized/${mod.icon}.svg`"
                                        alt=""
                                /></span>
                                <span class="calc-option-name"
                                    ><b>{{ mod.mod_index }}</b
                                    >{{ mod.mod_name }}</span
                                >
                            </button>
                        </div>

                        <div
                            v-if="subject.overclocks.length"
                            class="calc-overclocks"
                            role="group"
                            aria-label="Overclock"
                        >
                            <div class="calc-tier-label">OC</div>
                            <div
                                class="calc-overclock-list"
                                @pointerleave="pointerLeave"
                            >
                                <button
                                    v-for="item in subject.overclocks"
                                    :key="item.id"
                                    type="button"
                                    class="calc-overclock"
                                    :class="[
                                        `is-${item.overclock_type?.toLowerCase()}`,
                                        {
                                            'is-selected':
                                                calc.overclock?.id === item.id,
                                        },
                                    ]"
                                    :aria-pressed="calc.overclock?.id === item.id"
                                    @pointerdown="lastPointer = $event.pointerType"
                                    @pointerenter="
                                        pointerEnter($event, 'overclock', item)
                                    "
                                    @focus="keyboardFocus($event, 'overclock', item)"
                                    @blur="calc.unhover()"
                                    @click="pick('overclock', item)"
                                >
                                    <span class="calc-oct"
                                        ><img
                                            :src="`/assets/mods/optimized/${item.icon}.svg`"
                                            alt=""
                                    /></span>
                                    <span class="calc-option-name"
                                        >{{ item.overclock_name
                                        }}<small>{{
                                            item.overclock_type
                                        }}</small></span
                                    >
                                </button>
                            </div>
                        </div>
                    </template>

                    <template v-else-if="subject.kind === 'throwable'">
                        <div class="calc-throwables" role="group" aria-label="Throwable">
                            <button
                                v-for="item in calc.throwables"
                                :key="item.id"
                                type="button"
                                class="calc-throwable"
                                :class="{ 'is-selected': subject.item.id === item.id }"
                                :aria-pressed="subject.item.id === item.id"
                                @click="calc.selectThrowable(item.id)"
                            >
                                <img :src="`/assets/${item.icon}.svg`" alt="" />{{
                                    item.name
                                }}
                            </button>
                        </div>
                        <p class="calc-about">
                            {{ upgradeDescription(subject.item) }}
                        </p>
                        <p class="calc-hint">Throwables have no upgrade tiers.</p>
                    </template>

                    <p v-else class="calc-hint">This item has no upgrades.</p>

                    <!-- Phones: what the last tap changed, kept in view while
                         picking upgrades. -->
                    <div
                        v-if="isNarrow && calc.tiers.length"
                        class="calc-dock"
                        :class="{ 'is-collapsed': dockCollapsed }"
                    >
                        <div class="calc-dock-bar">
                            <dl class="calc-dock-figures">
                                <div
                                    v-for="metric in calc.derived"
                                    :key="metric.label"
                                >
                                    <dt :title="metric.label">
                                        {{ dockLabels[metric.label] || metric.label }}
                                    </dt>
                                    <dd>
                                        {{ figure(metric.value)
                                        }}<small
                                            v-if="dockNote(metric)"
                                            :class="dockNote(metric).tone"
                                            :title="
                                                calc.hasBaseline
                                                    ? 'Difference from the pinned build'
                                                    : 'Difference from no upgrades'
                                            "
                                            >{{ dockNote(metric).text }}</small
                                        >
                                    </dd>
                                </div>
                                <div v-if="!calc.derived.length">
                                    <dt>Build</dt>
                                    <dd>{{ calc.code }}</dd>
                                </div>
                            </dl>
                            <button
                                type="button"
                                class="calc-dock-toggle"
                                :aria-expanded="!dockCollapsed"
                                :aria-label="
                                    dockCollapsed
                                        ? 'Show upgrade details'
                                        : 'Hide upgrade details'
                                "
                                @click="dockCollapsed = !dockCollapsed"
                            >
                                {{ dockCollapsed ? "Details ▴" : "Hide ▾" }}
                            </button>
                        </div>
                        <CalculatorInspector
                            v-show="!dockCollapsed"
                            :calc="calc"
                            compact
                        />
                    </div>
                </section>

                <aside
                    v-if="calc.tiers.length"
                    class="calc-col calc-notes"
                    aria-label="Details and comparison"
                >
                    <CalculatorInspector v-if="!isNarrow" :calc="calc" />

                    <section class="calc-pin">
                        <span class="calc-label">Compare</span>
                        <template v-if="calc.hasBaseline">
                            <p>
                                Pinned <b>{{ pinLabel }}</b>. Every change now
                                shows its difference from it.
                            </p>
                            <div class="calc-actions">
                                <button
                                    type="button"
                                    class="calc-button"
                                    @click="calc.pinBuild()"
                                >
                                    Re-pin current
                                </button>
                                <button
                                    type="button"
                                    class="calc-button"
                                    title="Load the pinned build and pin the current one"
                                    @click="calc.swapWithPin()"
                                >
                                    Swap
                                </button>
                                <button
                                    type="button"
                                    class="calc-button"
                                    @click="calc.unpin()"
                                >
                                    Unpin
                                </button>
                            </div>
                        </template>
                        <template v-else>
                            <p>
                                Pin this build, then try alternatives. The stats
                                gain a pinned column and the difference for every
                                stat.
                            </p>
                            <button
                                type="button"
                                class="calc-button is-primary"
                                @click="calc.pinBuild()"
                            >
                                Pin current build
                            </button>
                        </template>
                    </section>

                    <section>
                        <span class="calc-label">Equipped</span>
                        <ul class="calc-equipped">
                            <li
                                v-for="tier in calc.tiers"
                                :key="tier.tier"
                                :class="{ 'is-empty': !modAt(tier.tier) }"
                            >
                                <span class="calc-equipped-tier"
                                    >T{{ tier.tier }}</span
                                >
                                <span v-if="modAt(tier.tier)"
                                    ><b
                                        >{{ modAt(tier.tier).mod_index }} ·
                                        {{ modAt(tier.tier).mod_name }}</b
                                    >{{ upgradeDescription(modAt(tier.tier)) }}</span
                                >
                                <span v-else><b>Nothing picked</b></span>
                            </li>
                            <li v-if="calc.overclock">
                                <span class="calc-equipped-tier">OC</span>
                                <span
                                    ><b
                                        :class="`is-${calc.overclock.overclock_type?.toLowerCase()}`"
                                        >{{ calc.overclock.overclock_name }}</b
                                    >{{ upgradeDescription(calc.overclock) }}</span
                                >
                            </li>
                        </ul>
                    </section>
                </aside>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    computed,
    nextTick,
    onBeforeUnmount,
    onMounted,
    reactive,
    ref,
    watch,
} from "vue";
import CalculatorInspector from "./CalculatorInspector.vue";
import CalculatorStatTable from "./CalculatorStatTable.vue";
import {
    classColors,
    upgradeDescription,
    useCalculator,
} from "../useCalculator";

const { config } = defineProps({ config: { type: Object, required: true } });

/** Calculator state, with refs unwrapped for templates and child components. */
const calc = reactive(useCalculator(config));
const subject = computed(() => calc.subject);
const subjectKind = computed(() => {
    if (!subject.value) return "";
    if (subject.value.kind === "gun") return subject.value.item.gun_class;
    if (subject.value.kind === "equipment")
        return subject.value.item.eq_type || "Equipment";
    return "Throwable";
});

/** Weapon names without manufacturer prefixes, so six tabs fit in a row. */
function shortName(name) {
    const nickname = name.match(/"([^"]+)"/);
    if (nickname) return nickname[1];
    return name
        .replace(/^(Deepcore|Experimental|Jury-Rigged|Corrosive|Colette)\s+/i, "")
        .split(" ")
        .slice(0, 2)
        .join(" ");
}

function hasPicks(key) {
    const picks = calc.picksFor(key);
    return !!picks && (Object.keys(picks.tiers).length > 0 || !!picks.overclock);
}

const kitGroups = computed(() => {
    const gunEntry = (gun) => ({
        key: `gun:${gun.id}`,
        kind: "gun",
        id: gun.id,
        label: shortName(gun.name),
        title: gun.name,
        image: `/assets/${gun.image}.svg`,
        touched: hasPicks(`gun:${gun.id}`),
    });
    const groups = [
        { label: "Primary", items: calc.guns(1).map(gunEntry) },
        { label: "Secondary", items: calc.guns(2).map(gunEntry) },
        {
            label: "Equipment",
            items: calc.equipments.map((item) => ({
                key: `equipment:${item.id}`,
                kind: "equipment",
                id: item.id,
                label: item.name,
                title: item.name,
                image: `/assets/${item.icon}.svg`,
                touched: hasPicks(`equipment:${item.id}`),
            })),
        },
    ];
    if (calc.throwables.length) {
        const shown =
            calc.throwables.find(
                (item) => item.id === calc.state.throwables[calc.state.characterId]
            ) || calc.throwables[0];
        groups.push({
            label: "Throwable",
            items: [
                {
                    key: "throwable",
                    kind: "throwable",
                    id: null,
                    label: shown.name,
                    title: "Throwables",
                    image: `/assets/${shown.icon}.svg`,
                    touched: false,
                },
            ],
        });
    }
    return groups.filter((group) => group.items.length);
});

function isActiveTab(entry) {
    return (
        calc.tab?.kind === entry.kind &&
        (entry.kind === "throwable" || calc.tab?.id === entry.id)
    );
}

const codeDraft = ref(null);
const displayCode = computed(() =>
    calc.code.replace(/-/g, "0").replace(/0+$/, "")
);
function typeCode(value) {
    codeDraft.value = value;
    calc.applyCode(value.padEnd(calc.tiers.length, "0"));
}
watch(
    () => subject.value?.key,
    () => {
        codeDraft.value = null;
    }
);

/*
 * Hover previews follow a mouse or keyboard focus only. Changing the page on a
 * touch "hover" makes mobile Safari treat the first tap as a hover and drop
 * the click, so touch input skips previews and inspects the tapped upgrade.
 */
const lastPointer = ref("mouse");

function pointerEnter(event, kind, item) {
    if (event.pointerType === "mouse") calc.hover(kind, item);
}
function pointerLeave(event) {
    if (event.pointerType === "mouse") calc.unhover();
}
function keyboardFocus(event, kind, item) {
    if (event.target.matches(":focus-visible")) calc.hover(kind, item);
}
function pick(kind, item) {
    if (kind === "mod") calc.toggleMod(item);
    else calc.toggleOverclock(item.id);

    if (lastPointer.value === "mouse") return;
    lastPointer.value = "mouse";
    // On touch, show the tapped upgrade in the inspector if it is now equipped.
    if (calc.isEquipped({ kind, item })) calc.hover(kind, item);
    else calc.unhover();
}

function modAt(tier) {
    return calc.selectedMods.find((mod) => mod.mod_tier === tier);
}

/*
 * Phones get one column, so the details panel moves into a dock that stays
 * at the bottom of the screen while the upgrade tree scrolls under it.
 */
const narrowQuery = window.matchMedia("(max-width: 760px)");
const isNarrow = ref(narrowQuery.matches);
const dockCollapsed = ref(false);
function syncNarrow(event) {
    isNarrow.value = event.matches;
}
onMounted(() => narrowQuery.addEventListener("change", syncNarrow));
onBeforeUnmount(() => narrowQuery.removeEventListener("change", syncNarrow));

/** Scroll the item strip sideways so the open item is in view. */
const kitNav = ref(null);
function revealActiveTab() {
    const nav = kitNav.value;
    const active = nav?.querySelector(".calc-kit-tab.is-active");
    if (!nav || !active || nav.scrollWidth <= nav.clientWidth) return;
    const navBox = nav.getBoundingClientRect();
    const tabBox = active.getBoundingClientRect();
    if (tabBox.left >= navBox.left && tabBox.right <= navBox.right) return;
    nav.scrollTo({
        left: nav.scrollLeft + tabBox.left - navBox.left - 24,
        behavior: "smooth",
    });
}
watch(
    () => [calc.catalog, subject.value?.key],
    () => nextTick(revealActiveTab),
    { immediate: true }
);

function figure(value) {
    return value === null
        ? "—"
        : Number(value).toLocaleString(undefined, { maximumFractionDigits: 1 });
}

/** Short names so three figures fit across a phone. */
const dockLabels = {
    "Damage per second": "DPS",
    "Magazine damage": "Magazine",
    "Total damage": "Total",
};

/** Change shown beside a dock figure: from the pin, or from the bare item. */
function dockNote(metric) {
    const from = calc.hasBaseline ? metric.baseline : metric.base;
    if (from === null || from === undefined || from === metric.value) return null;
    const difference = Math.round((metric.value - from) * 10) / 10;
    return {
        text: `${difference > 0 ? "+" : ""}${difference.toLocaleString()}`,
        tone: difference > 0 ? "is-good" : "is-bad",
    };
}

const pinLabel = computed(() => {
    const pinned = calc.state.baseline;
    if (!pinned) return "";
    return [pinned.code, pinned.overclockName].filter(Boolean).join(" + ");
});
</script>
