<template>
    <div
        class="loadout-workbench"
        :class="{ 'is-compact': builderMode === 'compact' }"
        :style="{ '--class-accent': classDetails.color }"
    >
        <header class="workbench-intro">
            <h1>
                {{ config.method === "PUT" ? "Edit loadout" : "Build a loadout" }}
            </h1>
            <div class="builder-mode" role="group" aria-label="Builder layout">
                <button
                    v-for="mode in builderModes"
                    :key="mode.key"
                    type="button"
                    :class="{ 'is-active': builderMode === mode.key }"
                    :aria-pressed="builderMode === mode.key"
                    :title="mode.hint"
                    @click="builderMode = mode.key"
                >
                    {{ mode.label }}
                </button>
            </div>
        </header>

        <div
            v-if="recoverableDraft"
            class="builder-notice draft-notice"
            role="status"
        >
            <div>
                <strong>Your last build is still here.</strong>
                <p>Pick up where you left off, or start with a clean slate.</p>
            </div>
            <div class="notice-actions">
                <button
                    type="button"
                    class="terminal-button"
                    @click="restoreDraft"
                >
                    Continue draft</button
                ><button
                    type="button"
                    class="text-action"
                    @click="discardDraft"
                >
                    Start fresh
                </button>
            </div>
        </div>
        <div
            v-if="error"
            class="builder-notice error-notice"
            role="alert"
            tabindex="-1"
            ref="errorPanel"
        >
            <div>
                <strong>{{ error }}</strong>
                <ul v-if="Object.keys(errors).length">
                    <li v-for="(messages, field) in errors" :key="field">
                        {{ messages.join(" ") }}
                    </li>
                </ul>
            </div>
            <button
                type="button"
                aria-label="Dismiss error"
                @click="dismissError"
            >
                ×
            </button>
        </div>

        <section
            v-if="!form.character_id || choosingClass"
            class="class-selection"
            aria-label="Choose your class"
        >
            <div class="section-heading">
                <div>
                    <p class="terminal-label">Start here</p>
                    <h2>Who’s going down?</h2>
                </div>
                <button
                    v-if="form.character_id"
                    type="button"
                    class="text-action"
                    @click="cancelClassSelection"
                >
                    Keep {{ currentClass?.name }}
                </button>
            </div>
            <div class="class-roster">
                <button
                    v-for="character in orderedCharacters"
                    :key="character.id"
                    type="button"
                    class="class-choice"
                    :style="{
                        '--roster-color':
                            classInfo[character.name]?.color || '#fc9e00',
                    }"
                    :aria-pressed="character.id === form.character_id"
                    :disabled="saving"
                    @click="requestClass(character.id)"
                >
                    <span class="class-number">{{
                        classInfo[character.name]?.number
                    }}</span
                    ><img
                        :src="`/assets/img/full-${
                            classInfo[character.name]?.letter
                        }_image.png`"
                        alt=""
                    />
                    <span class="class-caption"
                        ><small>{{ classInfo[character.name]?.role }}</small
                        ><strong>{{ character.name }}</strong
                        ><span>{{
                            classInfo[character.name]?.description
                        }}</span></span
                    ><span class="class-arrow" aria-hidden="true"
                        >Select ↗︎</span
                    >
                </button>
            </div>
            <div
                v-if="pendingClass"
                class="builder-notice class-confirmation"
                role="alert"
                ref="classConfirmation"
                tabindex="-1"
            >
                <div>
                    <strong
                        >Switch to
                        {{
                            config.characters.find(
                                (character) => character.id === pendingClass
                            )?.name
                        }}?</strong
                    >
                    <p>
                        Weapon and equipment selections will be reset. Your name
                        and guide will stay.
                    </p>
                </div>
                <div class="notice-actions">
                    <button
                        type="button"
                        class="terminal-button"
                        @click="selectClass(pendingClass)"
                    >
                        Switch class</button
                    ><button
                        type="button"
                        class="text-action"
                        @click="pendingClass = null"
                    >
                        Keep current class
                    </button>
                </div>
            </div>
        </section>

        <template v-if="form.character_id && !choosingClass">
            <div v-if="loading" class="catalog-loading" role="status">
                <span class="loading-spinner" aria-hidden="true"></span>
                <h3>Opening the armory…</h3>
                <p>Loading {{ currentClass?.name }} equipment.</p>
            </div>
            <div v-else-if="!catalog" class="empty-slot">
                <h3>The armory didn’t load.</h3>
                <p>
                    Your selections are safe. Try loading the equipment again.
                </p>
                <button
                    type="button"
                    class="terminal-button"
                    @click="loadCatalog(form.character_id)"
                >
                    Retry loading equipment
                </button>
            </div>
            <BuilderCompactView
                v-else-if="builderMode === 'compact'"
                :builder="builder"
                :config="config"
            />
            <div v-else class="workbench-layout">
                <nav
                    ref="railPanel"
                    class="loadout-rail"
                    aria-label="Loadout sections"
                >
                    <div class="rail-identity">
                        <span class="rail-portrait" aria-hidden="true"
                            ><img
                                :src="`/assets/img/${currentClass?.name}_portrait-128px.png`"
                                alt=""
                        /></span>
                        <div>
                            <strong>{{ currentClass?.name }}</strong>
                            <button
                                type="button"
                                class="text-action"
                                :disabled="saving"
                                @click="choosingClass = true"
                            >
                                Change class
                            </button>
                        </div>
                    </div>
                    <div class="rail-heading">
                        <span class="terminal-label">Your loadout</span
                        ><span>{{ configuredCount }} / 4</span>
                    </div>
                    <button
                        v-for="(section, index) in sections"
                        :key="section.key"
                        type="button"
                        class="rail-item"
                        :class="{
                            'is-active': activeSection === section.key,
                            'is-configured': sectionConfigured(section.key),
                        }"
                        :aria-current="
                            activeSection === section.key ? 'step' : undefined
                        "
                        :disabled="saving"
                        @click="goTo(section.key)"
                    >
                        <span class="rail-number" aria-hidden="true">{{
                            sectionConfigured(section.key) &&
                            activeSection !== section.key
                                ? "✓"
                                : String(index + 1).padStart(2, "0")
                        }}</span
                        ><span class="rail-text"
                            ><strong>{{ section.label }}</strong
                            ><small>{{
                                sectionSummary(section.key)
                            }}</small></span
                        ><span
                            v-if="sectionConfigured(section.key)"
                            class="sr-only"
                            >(configured)</span
                        >
                    </button>
                    <div class="rail-footer">
                        <span class="draft-status">{{
                            draftSaved
                                ? "Draft saved in this browser"
                                : "Every slot is optional"
                        }}</span>
                    </div>
                </nav>
                <section
                    class="workbench-content"
                    ref="contentPanel"
                    tabindex="-1"
                    :aria-busy="saving"
                >
                    <fieldset :disabled="saving" class="workbench-fieldset">
                        <BuilderWeaponPanel
                            v-if="
                                activeSection === 'primary' ||
                                activeSection === 'secondary'
                            "
                            :key="activeSection"
                            :slot="activeWeaponSlot"
                            :guns="
                                catalog.guns.filter(
                                    (gun) =>
                                        gun.character_slot === activeWeaponSlot
                                )
                            "
                            :gun="selectedGun(activeWeaponSlot)"
                            :selected-ids="form.mods"
                            :overclock="selectedOverclock(activeWeaponSlot)"
                            :metrics="metrics[activeWeaponSlot]"
                            :class-name="currentClass?.name"
                            @weapon="chooseWeapon(activeWeaponSlot, $event)"
                            @clear="clearWeapon(activeWeaponSlot)"
                            @mod="
                                selectMod(
                                    $event,
                                    'mods',
                                    selectedGun(activeWeaponSlot).mods
                                )
                            "
                            @overclock="
                                chooseOverclock(activeWeaponSlot, $event)
                            "
                            @metrics="fetchMetrics(activeWeaponSlot)"
                        />
                        <section v-else-if="activeSection === 'equipment'">
                            <div class="section-heading">
                                <div>
                                    <p class="terminal-label">
                                        Step 03 of 05 · {{ currentClass?.name }}
                                    </p>
                                    <h2>Equipment</h2>
                                </div>
                                <span class="section-hint"
                                    >A good dwarf comes prepared.</span
                                >
                            </div>
                            <div
                                v-if="catalog.equipments.length"
                                class="equipment-tabs weapon-picker"
                                :style="{
                                    '--weapons': catalog.equipments.length,
                                }"
                                role="group"
                                aria-label="Equipment"
                            >
                                <button
                                    v-for="equipment in catalog.equipments"
                                    :key="equipment.id"
                                    type="button"
                                    class="weapon-choice"
                                    :class="{
                                        'is-viewing':
                                            activeEquipmentId === equipment.id,
                                        'is-upgraded': selectedMods(
                                            equipment.equipment_mods,
                                            'equipment_mods'
                                        ).length,
                                    }"
                                    :aria-pressed="
                                        activeEquipmentId === equipment.id
                                    "
                                    @click="activeEquipmentId = equipment.id"
                                >
                                    <span class="weapon-silhouette"
                                        ><img
                                            :src="`/assets/${equipment.icon}.svg`"
                                            alt=""
                                    /></span>
                                    <span class="weapon-name">{{
                                        equipment.name
                                    }}</span
                                    ><small>{{
                                        equipmentStatus(equipment)
                                    }}</small>
                                </button>
                            </div>
                            <div
                                v-if="activeEquipment"
                                class="weapon-panel equipment-panel"
                            >
                                <div class="upgrade-column">
                                    <div class="subsection-heading">
                                        <div>
                                            <h3>{{ activeEquipment.name }}</h3>
                                            <p class="upgrade-help">
                                                Optional. Pick any tiers; click
                                                an equipped upgrade to remove
                                                it.
                                            </p>
                                        </div>
                                    </div>
                                    <BuilderModGrid
                                        :mods="activeEquipment.equipment_mods"
                                        :selected="form.equipment_mods"
                                        @preview="equipmentPreview = $event"
                                        @select="
                                            selectMod(
                                                $event,
                                                'equipment_mods',
                                                activeEquipment.equipment_mods
                                            )
                                        "
                                    />
                                </div>
                                <aside
                                    class="weapon-readout"
                                    aria-label="Equipment statistics"
                                >
                                    <div class="readout-title">
                                        <span class="readout-mode">{{
                                            equipmentPreviewUpgrades
                                                ? "Previewing swap"
                                                : "Live readout"
                                        }}</span
                                        ><span>Equipment</span>
                                    </div>
                                    <img
                                        class="readout-weapon equipment-art"
                                        :src="`/assets/${activeEquipment.icon}.svg`"
                                        alt=""
                                    />
                                    <h3>{{ activeEquipment.name }}</h3>
                                    <BuilderStatReadout
                                        :equipment="activeEquipment"
                                        :upgrades="
                                            selectedMods(
                                                activeEquipment.equipment_mods,
                                                'equipment_mods'
                                            )
                                        "
                                        :preview-upgrades="
                                            equipmentPreviewUpgrades
                                        "
                                        :highlight-upgrades="
                                            equipmentHighlightUpgrades
                                        "
                                    /><CreditsCalculator
                                        :selected-mods="
                                            selectedMods(
                                                activeEquipment.equipment_mods,
                                                'equipment_mods'
                                            )
                                        "
                                    />
                                </aside>
                            </div>
                            <div v-else class="empty-slot">
                                <h3>No equipment in this armory.</h3>
                                <p>You can continue to throwables.</p>
                            </div>
                        </section>
                        <section v-else-if="activeSection === 'throwable'">
                            <div class="section-heading">
                                <div>
                                    <p class="terminal-label">
                                        Step 04 of 05 · {{ currentClass?.name }}
                                    </p>
                                    <h2>Throwable</h2>
                                </div>
                                <span class="section-hint"
                                    >One last thing for the swarm.</span
                                >
                            </div>
                            <div
                                class="weapon-picker throwable-picker"
                                :style="{
                                    '--weapons': catalog.throwables.length,
                                }"
                                role="group"
                                aria-label="Throwables"
                            >
                                <button
                                    v-for="throwable in catalog.throwables"
                                    :key="throwable.id"
                                    type="button"
                                    class="weapon-choice"
                                    :class="{
                                        'is-selected':
                                            form.throwable_id === throwable.id,
                                    }"
                                    :aria-pressed="
                                        form.throwable_id === throwable.id
                                    "
                                    @click="form.throwable_id = throwable.id"
                                >
                                    <span class="weapon-silhouette"
                                        ><img
                                            :src="`/assets/${throwable.icon}.svg`"
                                            alt=""
                                    /></span>
                                    <span class="weapon-name">{{
                                        throwable.name
                                    }}</span>
                                </button>
                            </div>
                            <div
                                v-if="selectedThrowable"
                                class="weapon-panel has-readout throwable-panel"
                            >
                                <div class="upgrade-column">
                                    <div class="subsection-heading">
                                        <div>
                                            <h3>
                                                {{ selectedThrowable.name }}
                                            </h3>
                                            <p class="upgrade-help">
                                                Throwables have no upgrade
                                                tiers.
                                            </p>
                                        </div>
                                        <button
                                            type="button"
                                            class="text-action"
                                            @click="form.throwable_id = null"
                                        >
                                            Remove throwable
                                        </button>
                                    </div>
                                    <div class="upgrade-inspection">
                                        <span class="inspection-key"
                                            >Equipped</span
                                        >
                                        <p>
                                            {{
                                                cleanDescription(
                                                    selectedThrowable.description
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <aside
                                    class="weapon-readout"
                                    aria-label="Throwable statistics"
                                >
                                    <div class="readout-title">
                                        <span class="readout-mode"
                                            >Live readout</span
                                        ><span>Throwable</span>
                                    </div>
                                    <img
                                        class="readout-weapon equipment-art"
                                        :src="`/assets/${selectedThrowable.icon}.svg`"
                                        alt=""
                                    />
                                    <h3>{{ selectedThrowable.name }}</h3>
                                    <BuilderStatReadout
                                        :equipment="selectedThrowable"
                                    /><CreditsCalculator
                                        :throwable="selectedThrowable"
                                    />
                                </aside>
                            </div>
                            <div v-else class="empty-slot">
                                <span aria-hidden="true">↖</span>
                                <h3>Pick a throwable.</h3>
                                <p>Optional. You can publish without one.</p>
                            </div>
                        </section>
                        <section v-else class="review-section">
                            <div class="section-heading">
                                <div>
                                    <p class="terminal-label">
                                        Step 05 of 05 · Ready for deployment
                                    </p>
                                    <h2>Review &amp; publish</h2>
                                </div>
                            </div>
                            <form
                                @submit.prevent="save"
                                class="publish-form"
                                novalidate
                            >
                                <div class="review-summary">
                                    <img
                                        :src="`/assets/img/full-${classDetails.letter}_image.png`"
                                        alt=""
                                    />
                                    <div>
                                        <span class="terminal-label"
                                            >{{
                                                currentClass?.name
                                            }}
                                            loadout</span
                                        >
                                        <h3>
                                            {{
                                                form.name ||
                                                "Your next great build."
                                            }}
                                        </h3>
                                        <p>
                                            {{
                                                selectedGun(1)?.name ||
                                                "No primary"
                                            }}<span> + </span
                                            >{{
                                                selectedGun(2)?.name ||
                                                "No secondary"
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <div class="review-kit-grid">
                                    <template
                                        v-for="slot in [1, 2]"
                                        :key="slot"
                                    >
                                        <BuilderKitCard
                                            v-if="selectedGun(slot)"
                                            :label="
                                                slot === 1
                                                    ? 'Primary'
                                                    : 'Secondary'
                                            "
                                            :name="selectedGun(slot).name"
                                            :kind="selectedGun(slot).gun_class"
                                            :image="`/assets/${
                                                selectedGun(slot).image
                                            }.svg`"
                                            :mods="selectedGun(slot).mods"
                                            :selected-ids="form.mods"
                                            :overclock="selectedOverclock(slot)"
                                            has-overclock-slot
                                            :accent="classDetails.color"
                                            action-label="Edit"
                                            @action="
                                                goTo(
                                                    slot === 1
                                                        ? 'primary'
                                                        : 'secondary'
                                                )
                                            "
                                        />
                                        <BuilderKitCard
                                            v-else
                                            :label="
                                                slot === 1
                                                    ? 'Primary'
                                                    : 'Secondary'
                                            "
                                            name="Not chosen"
                                            description="Every slot is optional."
                                            action-label="Choose"
                                            @action="
                                                goTo(
                                                    slot === 1
                                                        ? 'primary'
                                                        : 'secondary'
                                                )
                                            "
                                        />
                                    </template>
                                    <BuilderKitCard
                                        v-for="equipment in upgradedEquipment"
                                        :key="equipment.id"
                                        label="Equipment"
                                        :name="equipment.name"
                                        :image="`/assets/${equipment.icon}.svg`"
                                        :mods="equipment.equipment_mods"
                                        :selected-ids="form.equipment_mods"
                                        :accent="classDetails.color"
                                        action-label="Edit"
                                        @action="
                                            activeEquipmentId = equipment.id;
                                            goTo('equipment');
                                        "
                                    />
                                    <BuilderKitCard
                                        v-if="selectedThrowable"
                                        label="Throwable"
                                        :name="selectedThrowable.name"
                                        :image="`/assets/${selectedThrowable.icon}.svg`"
                                        :description="
                                            selectedThrowable.description
                                        "
                                        :accent="classDetails.color"
                                        action-label="Edit"
                                        @action="goTo('throwable')"
                                    />
                                </div>
                                <div class="publish-fields">
                                    <label for="loadout-name"
                                        >Loadout name
                                        <span>Required</span></label
                                    ><input
                                        id="loadout-name"
                                        ref="nameInput"
                                        v-model="form.name"
                                        @input="clearFieldError('name')"
                                        maxlength="255"
                                        placeholder="e.g. The Haz 5 problem solver"
                                        :aria-invalid="!!errors.name"
                                        :aria-describedby="
                                            errors.name
                                                ? 'name-error'
                                                : undefined
                                        "
                                        autocomplete="off"
                                    />
                                    <p
                                        v-if="errors.name"
                                        id="name-error"
                                        class="field-error"
                                    >
                                        {{ errors.name.join(" ") }}
                                    </p>
                                    <label for="loadout-description"
                                        >Field notes
                                        <span
                                            >Optional · Markdown supported</span
                                        ></label
                                    ><textarea
                                        id="loadout-description"
                                        v-model="form.description"
                                        @input="clearFieldError('description')"
                                        rows="7"
                                        maxlength="50000"
                                        placeholder="What makes this build work? Share your playstyle, synergies, and tips for the team."
                                        :aria-invalid="!!errors.description"
                                        :aria-describedby="
                                            errors.description
                                                ? 'description-error'
                                                : undefined
                                        "
                                    ></textarea>
                                    <p
                                        v-if="errors.description"
                                        id="description-error"
                                        class="field-error"
                                    >
                                        {{ errors.description.join(" ") }}
                                    </p>
                                </div>
                                <div
                                    v-if="!config.signedIn"
                                    class="anonymous-note"
                                >
                                    <span aria-hidden="true">ⓘ</span>
                                    <p>
                                        You’re saving anonymously.
                                        <a :href="config.loginUrl">Sign in</a>
                                        before publishing if you want to edit
                                        this build later. Your draft will stay
                                        in this browser.
                                    </p>
                                </div>
                                <div class="publish-footer">
                                    <p>
                                        Your build gets a permanent link to
                                        share with the team.
                                    </p>
                                    <button
                                        type="submit"
                                        class="terminal-button"
                                        :disabled="saving"
                                    >
                                        {{
                                            saving
                                                ? "Saving build…"
                                                : config.method === "PUT"
                                                ? "Save changes"
                                                : "Publish loadout"
                                        }}
                                        <span aria-hidden="true">↗︎</span>
                                    </button>
                                </div>
                            </form>
                        </section>
                    </fieldset>
                    <footer
                        v-if="activeSection !== 'review'"
                        class="workbench-navigation"
                    >
                        <button
                            type="button"
                            class="text-action"
                            :disabled="saving || activeSection === 'primary'"
                            @click="previousSection"
                        >
                            ← Back
                        </button>
                        <p>
                            {{
                                storageAvailable
                                    ? "Selections save as you go."
                                    : "Keep this tab open to save your build."
                            }}
                        </p>
                        <button
                            type="button"
                            class="terminal-button"
                            :disabled="saving"
                            @click="nextSection"
                        >
                            {{ nextLabel }} <span aria-hidden="true">→</span>
                        </button>
                    </footer>
                </section>
            </div>
        </template>
    </div>
</template>

<script setup>
import BuilderCompactView from "./BuilderCompactView.vue";
import BuilderModGrid from "./BuilderModGrid.vue";
import BuilderWeaponPanel from "./BuilderWeaponPanel.vue";
import { computed, reactive, ref, watch } from "vue";
import BuilderStatReadout from "./BuilderStatReadout.vue";
import BuilderKitCard from "./BuilderKitCard.vue";
import CreditsCalculator from "./CreditsCalculator.vue";
import { useLoadoutBuilder } from "../useLoadoutBuilder";

const { config } = defineProps({ config: { type: Object, required: true } });
const builderState = useLoadoutBuilder(config);
const {
    classInfo,
    sections,
    form,
    catalog,
    loading,
    saving,
    error,
    errors,
    errorPanel,
    contentPanel,
    nameInput,
    activeSection,
    choosingClass,
    pendingClass,
    classConfirmation,
    railPanel,
    activeEquipmentId,
    metrics,
    draftSaved,
    storageAvailable,
    recoverableDraft,
    orderedCharacters,
    currentClass,
    classDetails,
    activeWeaponSlot,
    activeEquipment,
    selectedThrowable,
    configuredCount,
    nextLabel,
    loadCatalog,
    requestClass,
    cancelClassSelection,
    selectClass,
    selectedGun,
    selectedOverclock,
    selectedMods,
    selectMod,
    chooseWeapon,
    clearWeapon,
    chooseOverclock,
    cleanDescription,
    sectionConfigured,
    sectionSummary,
    combination,
    goTo,
    nextSection,
    previousSection,
    fetchMetrics,
    restoreDraft,
    discardDraft,
    clearFieldError,
    dismissError,
    save,
} = builderState;

/** The same builder state, with refs unwrapped, for the compact view. */
const builder = reactive(builderState);

const builderModeKey = "karl:builder-mode";
const builderModes = [
    { key: "guided", label: "Guided", hint: "Step by step, with full upgrade details" },
    { key: "compact", label: "Compact", hint: "Every tree on one screen, icons only" },
];

/**
 * Guided or compact layout. A choice made in this browser wins; otherwise
 * signed-in players start in compact and guests start in guided.
 */
const builderMode = ref(readBuilderMode());
watch(builderMode, (mode) => {
    try {
        localStorage.setItem(builderModeKey, mode);
    } catch {
        /* Storage is optional. */
    }
});

function readBuilderMode() {
    const fallback = config.signedIn ? "compact" : "guided";
    try {
        const stored = localStorage.getItem(builderModeKey);
        return builderModes.some((mode) => mode.key === stored)
            ? stored
            : fallback;
    } catch {
        return fallback;
    }
}

/** Equipment with at least one upgrade chosen, for the review step. */
const upgradedEquipment = computed(
    () =>
        catalog.value?.equipments.filter(
            (equipment) =>
                selectedMods(equipment.equipment_mods, "equipment_mods").length
        ) || []
);

function equipmentStatus(equipment) {
    const chosen = selectedMods(
        equipment.equipment_mods,
        "equipment_mods"
    ).length;
    const total = new Set(equipment.equipment_mods.map((mod) => mod.mod_tier))
        .size;

    return chosen ? `${chosen} / ${total} tiers` : "Not upgraded";
}

/** Equipment upgrade currently being previewed by hover or focus. */
const equipmentPreview = ref(null);
watch([activeEquipmentId, activeSection], () => {
    equipmentPreview.value = null;
});
const activeEquipmentMods = computed(() =>
    activeEquipment.value
        ? selectedMods(activeEquipment.value.equipment_mods, "equipment_mods")
        : []
);
const equipmentPreviewIsEquipped = computed(() =>
    form.equipment_mods.includes(equipmentPreview.value?.id)
);
/** The equipment build with the hovered, unequipped upgrade swapped in. */
const equipmentPreviewUpgrades = computed(() => {
    const hovered = equipmentPreview.value;
    if (!hovered || equipmentPreviewIsEquipped.value) {
        return null;
    }

    return [
        ...activeEquipmentMods.value.filter(
            (mod) => mod.mod_tier !== hovered.mod_tier
        ),
        hovered,
    ];
});
/** The equipment build without the hovered, equipped upgrade. */
const equipmentHighlightUpgrades = computed(() =>
    equipmentPreviewIsEquipped.value
        ? activeEquipmentMods.value.filter(
              (mod) => mod.id !== equipmentPreview.value.id
          )
        : null
);
</script>
