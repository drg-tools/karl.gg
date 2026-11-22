<template>
    <div ref="matrix" class="upgrade-matrix">
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
                <div
                    v-for="mod in tierMods"
                    :key="mod.id"
                    class="upgrade-option-wrap"
                    :class="{ 'has-info': truncated[mod.id] }"
                >
                    <button
                        type="button"
                        class="upgrade-option"
                        :class="{ 'is-selected': selected.includes(mod.id) }"
                        :aria-pressed="selected.includes(mod.id)"
                        :aria-label="mod.mod_name"
                        :aria-describedby="`${descriptionId}-${mod.id}`"
                        @mouseenter="$emit('preview', mod)"
                        @focus="$emit('preview', mod)"
                        @click="$emit('select', mod)"
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
                            ><span class="upgrade-index">{{
                                mod.mod_index
                            }}</span
                            ><strong>{{ mod.mod_name }}</strong
                            ><span
                                class="upgrade-effect"
                                :data-mod-id="mod.id"
                                aria-hidden="true"
                                >{{ description(mod) }}</span
                            ></span
                        >
                        <span
                            :id="`${descriptionId}-${mod.id}`"
                            class="sr-only"
                            >{{ description(mod) }}</span
                        >
                    </button>
                    <button
                        v-if="truncated[mod.id]"
                        type="button"
                        class="upgrade-info"
                        :aria-label="`Full description of ${mod.mod_name}`"
                        :aria-expanded="openMod?.id === mod.id"
                        :aria-controls="popoverId"
                        @click="toggleInfo(mod, $event)"
                    >
                        <svg
                            viewBox="0 0 12 12"
                            width="12"
                            height="12"
                            aria-hidden="true"
                        >
                            <path
                                d="M2.5 4.5 6 8l3.5-3.5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.6"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </fieldset>
        <div
            v-if="openMod"
            :id="popoverId"
            ref="popover"
            class="upgrade-popover"
            role="dialog"
            :aria-label="openMod.mod_name"
            :style="popoverPosition"
        >
            <strong
                >T{{ openMod.mod_tier }} · {{ openMod.mod_index }} ·
                {{ openMod.mod_name }}</strong
            >
            <p>{{ description(openMod) }}</p>
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
    useId,
    watch,
} from "vue";

const props = defineProps({
    mods: { type: Array, default: () => [] },
    selected: { type: Array, default: () => [] },
});
const emit = defineEmits(["select", "preview"]);
const descriptionId = useId();
const popoverId = useId();
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

const matrix = ref(null);
const popover = ref(null);
/** Mods whose one-line description is cut off, keyed by mod id. */
const truncated = reactive({});
/** The mod whose full description is open in the popover. */
const openMod = ref(null);
const popoverPosition = ref({});
let infoTrigger = null;
let resizeObserver = null;

/** Marks each description that does not fit its clamped lines. */
function measureTruncation() {
    matrix.value
        ?.querySelectorAll(".upgrade-effect[data-mod-id]")
        .forEach((element) => {
            truncated[element.dataset.modId] =
                element.scrollHeight > element.clientHeight + 1;
        });
}

/**
 * Opens the popover under an option, aligned to whichever edge keeps it
 * inside the grid, or closes it when it is already open for that mod.
 */
function toggleInfo(mod, event) {
    if (openMod.value?.id === mod.id) {
        closeInfo();
        return;
    }
    infoTrigger = event.currentTarget;
    const option = infoTrigger.parentElement.getBoundingClientRect();
    const grid = matrix.value.getBoundingClientRect();
    const top = `${option.bottom - grid.top + 4}px`;
    popoverPosition.value =
        option.left - grid.left > grid.width / 2
            ? { top, right: `${grid.right - option.right}px` }
            : { top, left: `${option.left - grid.left}px` };
    openMod.value = mod;
}

function closeInfo({ restoreFocus = false } = {}) {
    if (restoreFocus) {
        infoTrigger?.focus();
    }
    openMod.value = null;
    infoTrigger = null;
}

function closeOnOutsideClick(event) {
    if (
        openMod.value &&
        !popover.value?.contains(event.target) &&
        !event.target.closest(".upgrade-info")
    ) {
        closeInfo();
    }
}

function closeOnEscape(event) {
    if (event.key === "Escape" && openMod.value) {
        closeInfo({ restoreFocus: true });
    }
}

function leaveTier(event) {
    if (!event.currentTarget.contains(event.relatedTarget)) {
        emit("preview", null);
    }
}

function description(mod) {
    return cleanDescription(mod.text_description || mod.description);
}

function cleanDescription(text) {
    return (text || "").replace(/<[^>]*>/g, "").replace(/\\n/g, " ");
}

function hideImage(event) {
    event.target.hidden = true;
}

watch(
    () => props.mods,
    async () => {
        closeInfo();
        await nextTick();
        measureTruncation();
    }
);

onMounted(() => {
    measureTruncation();
    resizeObserver = new ResizeObserver(measureTruncation);
    resizeObserver.observe(matrix.value);
    document.addEventListener("click", closeOnOutsideClick);
    document.addEventListener("keydown", closeOnEscape);
});

onBeforeUnmount(() => {
    resizeObserver?.disconnect();
    document.removeEventListener("click", closeOnOutsideClick);
    document.removeEventListener("keydown", closeOnEscape);
});
</script>
