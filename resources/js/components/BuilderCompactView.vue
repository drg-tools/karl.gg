<template>
    <form class="compact-builder" novalidate @submit.prevent="builder.save()">
        <div class="compact-topbar">
            <span class="compact-portrait" aria-hidden="true"
                ><img
                    :src="`/assets/img/${builder.currentClass?.name}_portrait-128px.png`"
                    alt=""
            /></span>
            <div class="compact-class">
                <strong>{{ builder.currentClass?.name }}</strong>
                <button
                    type="button"
                    class="text-action"
                    :disabled="builder.saving"
                    @click="builder.choosingClass = true"
                >
                    Change
                </button>
            </div>
            <label class="compact-name">
                <span class="sr-only">Loadout name (required)</span
                ><input
                    :ref="(element) => (builder.nameInput = element)"
                    v-model="builder.form.name"
                    maxlength="255"
                    placeholder="Name your loadout"
                    autocomplete="off"
                    :aria-invalid="!!builder.errors.name"
                    :aria-describedby="
                        builder.errors.name ? 'compact-name-error' : undefined
                    "
                    @input="builder.clearFieldError('name')"
            /></label>
            <button
                type="submit"
                class="terminal-button compact-save"
                :disabled="builder.saving"
            >
                {{
                    builder.saving
                        ? "Saving…"
                        : config.method === "PUT"
                        ? "Save changes"
                        : "Publish"
                }}
            </button>
        </div>
        <p
            v-if="builder.errors.name"
            id="compact-name-error"
            class="field-error"
        >
            {{ builder.errors.name.join(" ") }}
        </p>

        <div class="compact-slots">
            <BuilderCompactSlot
                v-for="slot in [1, 2]"
                :key="slot"
                :label="slot === 1 ? 'Primary' : 'Secondary'"
                empty-name="No weapon"
                clearable
                :items="
                    builder.catalog.guns
                        .filter((gun) => gun.character_slot === slot)
                        .map((gun) => pickable(gun, gun.image))
                "
                :active="builder.selectedGun(slot)"
                :mods="builder.selectedGun(slot)?.mods || []"
                :selected-ids="builder.form.mods"
                :overclocks="builder.selectedGun(slot)?.overclocks || []"
                :overclock="builder.selectedOverclock(slot)"
                @pick="builder.chooseWeapon(slot, $event)"
                @clear="builder.clearWeapon(slot)"
                @mod="
                    builder.selectMod(
                        $event,
                        'mods',
                        builder.selectedGun(slot).mods
                    )
                "
                @code="
                    builder.applyCombination(
                        $event,
                        'mods',
                        builder.selectedGun(slot).mods
                    )
                "
                @overclock="builder.chooseOverclock(slot, $event)"
            />
            <BuilderCompactSlot
                label="Equipment"
                empty-name="Choose a tool"
                empty-hint="Pick a tool to upgrade. Dots mark upgraded ones."
                :items="
                    builder.catalog.equipments.map((equipment) =>
                        pickable(equipment, equipment.icon)
                    )
                "
                :active="viewedEquipment"
                :mods="viewedEquipment?.equipment_mods || []"
                :selected-ids="builder.form.equipment_mods"
                :marked-ids="upgradedEquipmentIds"
                @pick="viewEquipment"
                @mod="
                    builder.selectMod(
                        $event,
                        'equipment_mods',
                        viewedEquipment.equipment_mods
                    )
                "
                @code="
                    builder.applyCombination(
                        $event,
                        'equipment_mods',
                        viewedEquipment.equipment_mods
                    )
                "
            />
            <BuilderCompactSlot
                label="Throwable"
                empty-name="No throwable"
                clearable
                :items="
                    builder.catalog.throwables.map((throwable) =>
                        pickable(throwable, throwable.icon)
                    )
                "
                :active="builder.selectedThrowable"
                @pick="builder.form.throwable_id = $event"
                @clear="builder.form.throwable_id = null"
            />
        </div>

        <details class="compact-card compact-notes" :open="!!builder.form.description">
            <summary>
                Field notes
                <span class="compact-muted">Optional · Markdown supported</span>
            </summary>
            <label class="sr-only" for="compact-description">Field notes</label>
            <textarea
                id="compact-description"
                v-model="builder.form.description"
                rows="6"
                maxlength="50000"
                placeholder="Why this build? Synergies, playstyle, when to swap."
                :aria-invalid="!!builder.errors.description"
                @input="builder.clearFieldError('description')"
            ></textarea>
            <p v-if="builder.errors.description" class="field-error">
                {{ builder.errors.description.join(" ") }}
            </p>
        </details>

        <footer class="compact-footer">
            <span v-for="slot in [1, 2]" :key="slot">
                <template v-if="builder.selectedGun(slot)"
                    ><strong>{{ builder.selectedGun(slot).name }}</strong>
                    <code>{{
                        code(builder.selectedGun(slot), "mods")
                    }}</code>
                    {{
                        builder.selectedOverclock(slot)?.overclock_name ||
                        "No OC"
                    }}</template
                >
            </span>
            <span v-if="!config.signedIn" class="compact-muted"
                >Saving anonymously ·
                <a :href="config.loginUrl">Sign in</a> to edit
                later</span
            >
        </footer>
    </form>
</template>

<script setup>
import { computed, ref } from "vue";
import BuilderCompactSlot from "./BuilderCompactSlot.vue";

const { builder, config } = defineProps({
    builder: { type: Object, required: true },
    config: { type: Object, required: true },
});

/** Equipment that has at least one upgrade, flagged in the picker. */
const upgradedEquipmentIds = computed(() =>
    builder.catalog.equipments
        .filter((equipment) =>
            equipment.equipment_mods.some((mod) =>
                builder.form.equipment_mods.includes(mod.id)
            )
        )
        .map((equipment) => equipment.id)
);

/**
 * The tool whose tree is open. Nothing is open until one is picked, like
 * the weapon slots, except an existing build opens its first upgraded tool.
 */
const viewedEquipmentId = ref(upgradedEquipmentIds.value[0] ?? null);
const viewedEquipment = computed(() =>
    builder.catalog.equipments.find(
        (equipment) => equipment.id === viewedEquipmentId.value
    )
);

/** Picking the open tool again closes it. */
function viewEquipment(id) {
    viewedEquipmentId.value = viewedEquipmentId.value === id ? null : id;
}

/** A picker choice: the item's id and name, with its silhouette. */
function pickable(item, image) {
    return { id: item.id, name: item.name, image: `/assets/${image}.svg` };
}

/** Equipped tiers as letters, e.g. "CAAB", for a weapon or equipment. */
function code(item, field = "equipment_mods") {
    const mods = item.mods || item.equipment_mods;
    const letters = Array(new Set(mods.map((mod) => mod.mod_tier)).size).fill(
        "–"
    );
    mods.filter((mod) => builder.form[field].includes(mod.id)).forEach(
        (mod) => {
            letters[mod.mod_tier - 1] = mod.mod_index;
        }
    );

    return letters.join("");
}
</script>
