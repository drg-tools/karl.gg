<template>
    <div
        class="loadout-workbench"
        :style="{ '--class-accent': classDetails.color }"
    >
        <header class="workbench-intro">
            <div>
                <p class="terminal-label">Karl.gg / Equipment terminal</p>
                <h2>
                    {{
                        config.method === "PUT"
                            ? "Back to the workbench."
                            : "Built for your next drop."
                    }}
                </h2>
                <p>Pick your kit. Find your edge. Rock and stone.</p>
            </div>
            <span class="workbench-badge"
                ><span aria-hidden="true">⬡</span> Loadout builder</span
            >
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
                    dusk="restore-draft"
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
                    :dusk="character.name.toLowerCase()"
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
                    ><span class="class-arrow" aria-hidden="true">↗</span>
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
                        dusk="confirm-class"
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
            <div class="selected-class-strip">
                <div class="selected-class-identity">
                    <img
                        :src="`/assets/img/${currentClass?.name}_portrait-128px.png`"
                        alt=""
                    />
                    <div>
                        <span class="terminal-label">Your class</span
                        ><strong>{{ currentClass?.name }}</strong>
                    </div>
                    <button
                        type="button"
                        class="text-action"
                        dusk="change-class"
                        :disabled="saving"
                        @click="choosingClass = true"
                    >
                        Change <span aria-hidden="true">↗</span>
                    </button>
                </div>
                <span class="draft-status">{{
                    draftSaved
                        ? "Draft saved in this browser"
                        : "Your next great build starts here"
                }}</span>
            </div>
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
            <div v-else class="workbench-layout">
                <nav
                    ref="railPanel"
                    class="loadout-rail"
                    aria-label="Loadout sections"
                >
                    <div class="rail-heading">
                        <span class="terminal-label">Your loadout</span
                        ><span>{{ configuredCount }} / 4</span>
                    </div>
                    <button
                        v-for="(section, index) in sections"
                        :key="section.key"
                        type="button"
                        class="rail-item"
                        :class="{ 'is-active': activeSection === section.key }"
                        :aria-current="
                            activeSection === section.key ? 'step' : undefined
                        "
                        :dusk="`section-${section.key}`"
                        :disabled="saving"
                        @click="goTo(section.key)"
                    >
                        <span class="rail-number">{{
                            String(index + 1).padStart(2, "0")
                        }}</span
                        ><span
                            ><strong>{{ section.label }}</strong
                            ><small>{{
                                sectionSummary(section.key)
                            }}</small></span
                        ><span class="rail-state" aria-hidden="true">{{
                            sectionConfigured(section.key) ? "✓" : "·"
                        }}</span>
                    </button>
                    <div class="rail-footer">
                        <span aria-hidden="true">↳</span> Every slot is
                        optional.<br />Make this build your own.
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
                            @weapon="chooseWeapon(activeWeaponSlot, $event)"
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
                                        03 / Tools of the trade
                                    </p>
                                    <h2>More than firepower.</h2>
                                </div>
                                <span class="section-hint"
                                    >A good dwarf comes prepared.</span
                                >
                            </div>
                            <div
                                v-if="catalog.equipments.length"
                                class="equipment-tabs"
                                role="group"
                                aria-label="Equipment"
                            >
                                <button
                                    v-for="equipment in catalog.equipments"
                                    :key="equipment.id"
                                    :dusk="`equipment-${equipment.id}`"
                                    type="button"
                                    :class="{
                                        'is-selected':
                                            activeEquipmentId === equipment.id,
                                    }"
                                    :aria-pressed="
                                        activeEquipmentId === equipment.id
                                    "
                                    @click="activeEquipmentId = equipment.id"
                                >
                                    <img
                                        :src="`/assets/${equipment.icon}.svg`"
                                        alt=""
                                    />{{ equipment.name }}
                                </button>
                            </div>
                            <div
                                v-if="activeEquipment"
                                class="weapon-configuration"
                            >
                                <div class="upgrade-column">
                                    <div class="subsection-heading">
                                        <h3>{{ activeEquipment.name }}</h3>
                                        <span>Equipment upgrades</span>
                                    </div>
                                    <BuilderModGrid
                                        :mods="activeEquipment.equipment_mods"
                                        :selected="form.equipment_mods"
                                        @select="
                                            selectMod(
                                                $event,
                                                'equipment_mods',
                                                activeEquipment.equipment_mods
                                            )
                                        "
                                    />
                                </div>
                                <aside class="weapon-readout">
                                    <span class="terminal-label"
                                        >Live readout</span
                                    ><img
                                        class="readout-weapon equipment-art"
                                        :src="`/assets/${activeEquipment.icon}.svg`"
                                        alt=""
                                    /><MainStatsDisplay
                                        :equipment="activeEquipment"
                                        :mods="
                                            selectedMods(
                                                activeEquipment.equipment_mods,
                                                'equipment_mods'
                                            )
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
                                        04 / Parting gift
                                    </p>
                                    <h2>Make an entrance.</h2>
                                </div>
                                <span class="section-hint"
                                    >One last thing for the swarm.</span
                                >
                            </div>
                            <div
                                class="throwable-roster"
                                role="group"
                                aria-label="Throwables"
                            >
                                <button
                                    v-for="throwable in catalog.throwables"
                                    :key="throwable.id"
                                    :dusk="`throwable-${throwable.id}`"
                                    type="button"
                                    class="throwable-choice"
                                    :class="{
                                        'is-selected':
                                            form.throwable_id === throwable.id,
                                    }"
                                    :aria-pressed="
                                        form.throwable_id === throwable.id
                                    "
                                    @click="form.throwable_id = throwable.id"
                                >
                                    <img
                                        :src="`/assets/${throwable.icon}.svg`"
                                        alt=""
                                    /><strong>{{ throwable.name }}</strong
                                    ><span>{{
                                        cleanDescription(throwable.description)
                                    }}</span
                                    ><small>{{
                                        form.throwable_id === throwable.id
                                            ? "Equipped ✓"
                                            : "Equip throwable →"
                                    }}</small>
                                </button>
                            </div>
                            <button
                                type="button"
                                class="text-action throwable-skip"
                                @click="form.throwable_id = null"
                            >
                                {{
                                    form.throwable_id
                                        ? "Remove throwable"
                                        : "Continue without a throwable"
                                }}
                            </button>
                            <div
                                v-if="selectedThrowable"
                                class="throwable-readout"
                            >
                                <MainStatsDisplay
                                    :equipment="selectedThrowable"
                                    :mods="[]"
                                /><CreditsCalculator
                                    :throwable="selectedThrowable"
                                />
                            </div>
                        </section>
                        <section v-else class="review-section">
                            <div class="section-heading">
                                <div>
                                    <p class="terminal-label">
                                        05 / Ready for deployment
                                    </p>
                                    <h2>Give this build a name.</h2>
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
                                        <div class="review-codes">
                                            <span
                                                v-for="slot in [1, 2]"
                                                :key="slot"
                                                >{{ combination(slot) }}</span
                                            >
                                        </div>
                                    </div>
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
                                <details class="review-kit">
                                    <summary>
                                        Review your equipment
                                        <span
                                            >{{ configuredCount }}
                                            {{
                                                configuredCount === 1
                                                    ? "slot"
                                                    : "slots"
                                            }}
                                            configured</span
                                        >
                                    </summary>
                                    <button
                                        v-for="section in sections.filter(
                                            (section) =>
                                                section.key !== 'review'
                                        )"
                                        :key="section.key"
                                        type="button"
                                        @click="goTo(section.key)"
                                    >
                                        <strong>{{ section.label }}</strong
                                        ><span>{{
                                            sectionSummary(section.key)
                                        }}</span
                                        ><span aria-hidden="true">↗</span>
                                    </button>
                                </details>
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
                                        dusk="save-loadout"
                                    >
                                        {{
                                            saving
                                                ? "Saving build…"
                                                : config.method === "PUT"
                                                ? "Save changes"
                                                : "Publish loadout"
                                        }}
                                        <span aria-hidden="true">↗</span>
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
                            dusk="next-section"
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
import BuilderModGrid from "./BuilderModGrid.vue";
import BuilderWeaponPanel from "./BuilderWeaponPanel.vue";
import MainStatsDisplay from "./MainStatsDisplay.vue";
import CreditsCalculator from "./CreditsCalculator.vue";
import { useLoadoutBuilder } from "../useLoadoutBuilder";

const { config } = defineProps({ config: { type: Object, required: true } });
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
} = useLoadoutBuilder(config);
</script>
