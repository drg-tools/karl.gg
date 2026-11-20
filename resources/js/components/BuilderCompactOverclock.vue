<template>
    <div class="compact-oc-slot">
        <span class="compact-tier-number" aria-hidden="true">OC</span>
        <div class="compact-oc-body">
            <div
                class="compact-oc-grid"
                role="group"
                :aria-label="`${label} overclocks`"
            >
                <button
                    v-for="item in overclocks"
                    :key="item.id"
                    type="button"
                    class="compact-choice compact-oc-choice"
                    :class="[
                        `oc-${type(item)}`,
                        { 'is-selected': selected?.id === item.id },
                    ]"
                    :aria-pressed="selected?.id === item.id"
                    :aria-label="`Overclock: ${item.overclock_name} (${item.overclock_type})`"
                    @click="toggle(item.id)"
                >
                    <span class="compact-octagon" aria-hidden="true"
                        ><img
                            v-if="item.icon"
                            :src="`/assets/mods/optimized/${item.icon}.svg`"
                            alt=""
                            @error="hideImage"
                    /></span>
                    <span class="compact-tip" role="tooltip"
                        ><strong>{{ item.overclock_name }}</strong
                        ><small>{{ item.overclock_type }}</small
                        ><span>{{
                            cleanDescription(item.text_description)
                        }}</span></span
                    >
                </button>
            </div>
            <p
                class="compact-oc-picked"
                :class="selected ? `oc-${type(selected)}` : 'is-empty'"
            >
                <strong>{{ selected?.overclock_name || "No overclock" }}</strong
                ><small v-if="selected">{{ selected.overclock_type }}</small>
            </p>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    overclocks: { type: Array, default: () => [] },
    selected: { type: Object, default: null },
    label: { type: String, default: "Weapon" },
});
const emit = defineEmits(["select"]);

/** Equip an overclock, or take it off when it is already equipped. */
function toggle(id) {
    emit("select", props.selected?.id === id ? null : id);
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
</script>
