<template>
    <div ref="root" class="compact-oc-slot" @keydown.esc.stop="close(true)">
        <span class="compact-tier-number" aria-hidden="true">OC</span>
        <button
            ref="trigger"
            type="button"
            class="compact-oc-trigger"
            :class="selected ? `oc-${type(selected)}` : 'is-empty'"
            aria-haspopup="dialog"
            :aria-expanded="open"
            :aria-label="`${label} overclock: ${
                selected?.overclock_name || 'none'
            }`"
            @click="open ? close() : show()"
        >
            <span class="compact-octagon" aria-hidden="true"
                ><img
                    v-if="selected?.icon"
                    :src="`/assets/mods/optimized/${selected.icon}.svg`"
                    alt=""
                    @error="hideImage"
                /><template v-else>+</template></span
            ><span class="compact-oc-trigger-text"
                ><strong>{{ selected?.overclock_name || "Overclock" }}</strong
                ><small>{{
                    selected?.overclock_type || `${overclocks.length} available`
                }}</small></span
            >
        </button>
        <div
            v-if="open"
            ref="panel"
            class="compact-oc-panel"
            role="dialog"
            :aria-label="`Choose ${label} overclock`"
        >
            <button
                v-for="item in overclocks"
                :key="item.id"
                type="button"
                class="compact-oc-option"
                :class="[
                    `oc-${type(item)}`,
                    { 'is-selected': selected?.id === item.id },
                ]"
                :aria-pressed="selected?.id === item.id"
                @click="choose(item.id)"
            >
                <span class="compact-octagon" aria-hidden="true"
                    ><img
                        v-if="item.icon"
                        :src="`/assets/mods/optimized/${item.icon}.svg`"
                        alt=""
                        @error="hideImage"
                /></span>
                <span class="compact-oc-option-text"
                    ><strong>{{ item.overclock_name }}</strong
                    ><small>{{ item.overclock_type }}</small></span
                >
                <span class="compact-tip" role="tooltip">{{
                    cleanDescription(item.text_description)
                }}</span>
            </button>
            <button
                type="button"
                class="compact-oc-option is-none"
                :class="{ 'is-selected': !selected }"
                :aria-pressed="!selected"
                @click="choose(null)"
            >
                <span class="compact-octagon" aria-hidden="true">—</span>
                <span class="compact-oc-option-text"
                    ><strong>No overclock</strong></span
                >
            </button>
        </div>
    </div>
</template>

<script setup>
import { nextTick, onBeforeUnmount, ref } from "vue";

const props = defineProps({
    overclocks: { type: Array, default: () => [] },
    selected: { type: Object, default: null },
    label: { type: String, default: "Weapon" },
});
const emit = defineEmits(["select"]);

const open = ref(false);
const root = ref(null);
const trigger = ref(null);
const panel = ref(null);

async function show() {
    open.value = true;
    document.addEventListener("pointerdown", closeOutside);
    await nextTick();
    (
        panel.value?.querySelector(".is-selected") ||
        panel.value?.querySelector("button")
    )?.focus();
}

function close(returnFocus = false) {
    if (!open.value) return;
    open.value = false;
    document.removeEventListener("pointerdown", closeOutside);
    if (returnFocus) trigger.value?.focus();
}

function closeOutside(event) {
    if (!root.value?.contains(event.target)) close();
}

function choose(id) {
    emit("select", id);
    close(true);
}

function type(item) {
    return String(item?.overclock_type || "").toLowerCase();
}

function cleanDescription(text) {
    return (text || "").replace(/<[^>]*>/g, "").replace(/\\n/g, " ");
}

function hideImage(event) {
    event.target.hidden = true;
}

onBeforeUnmount(() =>
    document.removeEventListener("pointerdown", closeOutside)
);
</script>
