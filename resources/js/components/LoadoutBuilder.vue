<template>
    <form @submit.prevent="save" class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <p>{{ config.method === 'PUT' ? 'Edit your loadout' : 'Create a loadout' }}</p>
            <button type="submit" :disabled="saving || loading || !catalog" class="rounded bg-karl-orange px-5 py-3 font-bold text-gray-900 disabled:opacity-50">
                {{ saving ? 'Saving…' : 'Save Loadout' }}
            </button>
        </div>
        <p v-if="!config.signedIn">You can save anonymously. Sign in before saving to edit your build later.</p>
        <div v-if="error" role="alert" class="rounded border border-red-400 p-4 text-red-200">{{ error }}</div>
        <ul v-if="Object.keys(errors).length" role="alert" class="text-red-200">
            <li v-for="(messages, field) in errors" :key="field">{{ messages.join(' ') }}</li>
        </ul>
        <fieldset :disabled="saving" class="flex flex-col gap-6">
            <legend class="sr-only">Loadout selections</legend>
            <div class="flex flex-wrap gap-3">
                <button v-for="character in config.characters" :key="character.id" type="button" :aria-pressed="character.id === form.character_id" :dusk="character.name.toLowerCase()"
                    class="rounded border px-5 py-3 hover:border-karl-orange" :class="character.id === form.character_id ? 'border-karl-orange text-karl-orange' : 'border-gray-500'" @click="selectClass(character.id)">{{ character.name }}</button>
            </div>
            <p v-if="loading" role="status">Loading equipment…</p>
            <button v-if="!loading && !catalog && form.character_id" type="button" @click="loadCatalog(form.character_id)">Retry loading equipment</button>
            <template v-if="catalog && !loading">
                <div class="grid gap-6 lg:grid-cols-2">
                    <section v-for="slot in [1, 2]" :key="slot" class="flex flex-col gap-4 rounded bg-gray-800 p-4" :dusk="slot === 1 ? 'primary-selectors' : 'secondary-selectors'">
                        <label :for="`weapon-${slot}`" class="font-bold">{{ slot === 1 ? 'Primary weapon' : 'Secondary weapon' }}</label>
                        <select :id="`weapon-${slot}`" v-model.number="weapons[slot]" @change="changeWeapon(slot)" class="w-full rounded bg-gray-900">
                            <option :value="null">Select a weapon</option>
                            <option v-for="gun in catalog.guns.filter(gun => gun.character_slot === slot)" :key="gun.id" :value="gun.id">{{ gun.name }}</option>
                        </select>
                        <template v-if="selectedGun(slot)">
                            <fieldset v-for="(mods, tier) in tiers(selectedGun(slot).mods)" :key="tier" class="flex flex-wrap gap-2">
                                <legend class="py-2">Tier {{ tier }}</legend>
                                <button v-for="mod in mods" :key="mod.id" type="button" :dusk="`mod-${mod.id}`" :aria-pressed="form.mods.includes(mod.id)" :title="mod.text_description"
                                    class="rounded border p-3 text-left" :class="form.mods.includes(mod.id) ? 'border-karl-orange text-karl-orange' : 'border-gray-500'" @click="toggleMod(mod, 'mods', selectedGun(slot).mods)">
                                    {{ mod.mod_name }}
                                </button>
                            </fieldset>
                            <label :for="`overclock-${slot}`">Overclock</label>
                            <select :id="`overclock-${slot}`" v-model.number="overclocks[slot]" @change="syncOverclocks" class="w-full rounded bg-gray-900">
                                <option :value="null">No overclock</option>
                                <option v-for="overclock in selectedGun(slot).overclocks" :key="overclock.id" :value="overclock.id">{{ overclock.overclock_name }}</option>
                            </select>
                            <p v-if="selectedOverclock(slot)">{{ selectedOverclock(slot).text_description }}</p>
                            <p v-for="mod in selectedMods(selectedGun(slot).mods, 'mods')" :key="mod.id" class="text-sm">{{ mod.mod_name }}: {{ mod.text_description }}</p>
                            <MainStatsDisplay :equipment="selectedGun(slot)" :mods="selectedMods(selectedGun(slot).mods, 'mods')" :overclock="selectedOverclock(slot)" />
                            <CreditsCalculator :selected-mods="selectedMods(selectedGun(slot).mods, 'mods')" :overclock="selectedOverclock(slot)" />
                            <button type="button" @click="fetchMetrics(slot)" class="text-left text-karl-orange">Advanced statistics</button>
                            <p v-if="metrics[slot]?.message" role="status">{{ metrics[slot].message }}</p>
                            <dl v-if="metrics[slot]?.data" class="grid grid-cols-2 gap-2 text-sm">
                                <template v-for="(value, key) in metricValues(metrics[slot].data)" :key="key"><dt>{{ key.replaceAll('_', ' ') }}</dt><dd>{{ value }}</dd></template>
                            </dl>
                        </template>
                    </section>
                </div>
                <section v-for="equipment in catalog.equipments" :key="equipment.id" class="rounded bg-gray-800 p-4">
                    <h2 class="text-lg font-bold">{{ equipment.name }}</h2>
                    <fieldset v-for="(mods, tier) in tiers(equipment.equipment_mods)" :key="tier" class="flex flex-wrap gap-2">
                        <legend class="py-2">Tier {{ tier }}</legend>
                        <button v-for="mod in mods" :key="mod.id" type="button" :aria-pressed="form.equipment_mods.includes(mod.id)" :title="mod.text_description"
                            class="rounded border p-3" :class="form.equipment_mods.includes(mod.id) ? 'border-karl-orange text-karl-orange' : 'border-gray-500'" @click="toggleMod(mod, 'equipment_mods', equipment.equipment_mods)">{{ mod.mod_name }}</button>
                    </fieldset>
                    <MainStatsDisplay :equipment="equipment" :mods="selectedMods(equipment.equipment_mods, 'equipment_mods')" />
                    <CreditsCalculator :selected-mods="selectedMods(equipment.equipment_mods, 'equipment_mods')" />
                </section>
                <label for="throwable">Throwable</label>
                <select id="throwable" v-model.number="form.throwable_id" class="rounded bg-gray-900">
                    <option :value="null">No throwable</option>
                    <option v-for="throwable in catalog.throwables" :key="throwable.id" :value="throwable.id">{{ throwable.name }}</option>
                </select>
                <label for="loadout-name">Loadout name</label>
                <input id="loadout-name" v-model="form.name" required maxlength="255" class="rounded bg-gray-900" />
                <label for="loadout-description">Loadout guide (Markdown)</label>
                <textarea id="loadout-description" v-model="form.description" rows="8" maxlength="50000" class="rounded bg-gray-900"></textarea>
                <button type="submit" :disabled="saving" class="rounded bg-karl-orange px-5 py-3 font-bold text-gray-900">{{ saving ? 'Saving…' : 'Save Loadout' }}</button>
            </template>
        </fieldset>
    </form>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import MainStatsDisplay from './MainStatsDisplay.vue';
import CreditsCalculator from './CreditsCalculator.vue';

const { config } = defineProps({ config: { type: Object, required: true } });
const form = reactive({ name: '', description: '', character_id: null, throwable_id: null, mods: [], overclocks: [], equipment_mods: [], ...config.initial });
const weapons = reactive({ 1: null, 2: null });
const overclocks = reactive({ 1: null, 2: null });
const catalog = ref(null);
const loading = ref(false);
const saving = ref(false);
const error = ref('');
const errors = ref({});
const metrics = reactive({});
const catalogs = new Map();
let catalogRequest = 0;

async function request(url, options = {}) {
    const response = await fetch(url, {
        credentials: 'same-origin', ...options,
        headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, ...options.headers },
    });
    const body = await response.json().catch(() => ({}));
    if (!response.ok) {
        const failure = new Error(response.status === 419 ? 'Your session expired. Reload this page before saving.' : body.message || 'The request failed. Please try again.');
        failure.errors = body.errors || {};
        throw failure;
    }
    return body;
}

async function loadCatalog(id) {
    const requestId = ++catalogRequest;
    loading.value = true;
    error.value = '';
    try {
        if (!catalogs.has(id)) catalogs.set(id, (await request(config.characters.find(character => character.id === id).url)).data);
        if (requestId !== catalogRequest) return;
        catalog.value = catalogs.get(id);
        for (const slot of [1, 2]) {
            const gun = catalog.value.guns.find(gun => gun.character_slot === slot && (gun.mods.some(mod => form.mods.includes(mod.id)) || gun.overclocks.some(overclock => form.overclocks.includes(overclock.id))));
            weapons[slot] = gun?.id || null;
            overclocks[slot] = gun?.overclocks.find(overclock => form.overclocks.includes(overclock.id))?.id || null;
        }
    } catch (failure) {
        if (requestId === catalogRequest) error.value = failure.message;
    } finally {
        if (requestId === catalogRequest) loading.value = false;
    }
}
function selectClass(id) {
    if (form.character_id === id) return;
    Object.assign(form, { character_id: id, mods: [], overclocks: [], equipment_mods: [], throwable_id: null });
    catalog.value = null;
    metrics[1] = metrics[2] = null;
    loadCatalog(id);
}
function selectedGun(slot) { return catalog.value?.guns.find(gun => gun.id === weapons[slot]); }
function selectedOverclock(slot) { return selectedGun(slot)?.overclocks.find(overclock => overclock.id === overclocks[slot]); }
function tiers(mods) {
    return [...mods].sort((a, b) => a.mod_tier - b.mod_tier || String(a.mod_index).localeCompare(String(b.mod_index))).reduce((groups, mod) => { (groups[mod.mod_tier] ||= []).push(mod); return groups; }, {});
}
function selectedMods(mods, field) { return mods.filter(mod => form[field].includes(mod.id)); }
function toggleMod(mod, field, available) {
    const selected = form[field].includes(mod.id);
    const sameTier = available.filter(item => item.mod_tier === mod.mod_tier).map(item => item.id);
    form[field] = form[field].filter(id => !sameTier.includes(id));
    if (!selected) form[field].push(mod.id);
    metrics[1] = metrics[2] = null;
}
function changeWeapon(slot) {
    const slotMods = catalog.value.guns.filter(gun => gun.character_slot === slot).flatMap(gun => gun.mods.map(mod => mod.id));
    form.mods = form.mods.filter(id => !slotMods.includes(id));
    overclocks[slot] = null;
    syncOverclocks();
}
function syncOverclocks() {
    form.overclocks = [overclocks[1], overclocks[2]].filter(Boolean);
    metrics[1] = metrics[2] = null;
}
async function fetchMetrics(slot) {
    const gun = selectedGun(slot);
    const combo = Array(6).fill('-');
    selectedMods(gun.mods, 'mods').forEach(mod => { combo[mod.mod_tier - 1] = mod.mod_index; });
    combo[5] = selectedOverclock(slot)?.overclock_index || '-';
    const selection = JSON.stringify([weapons[slot], form.mods, overclocks[slot]]);
    metrics[slot] = { message: 'Loading statistics…' };
    try {
        const body = await request(`${config.metricsUrl}?${new URLSearchParams({ gun_id: gun.id, combo: combo.join('') })}`);
        if (selection !== JSON.stringify([weapons[slot], form.mods, overclocks[slot]])) return;
        metrics[slot] = body.data ? { data: body.data } : { message: 'Advanced statistics are not available for this combination.' };
    } catch (failure) { metrics[slot] = { message: failure.message }; }
}
function metricValues(data) { return Object.fromEntries(Object.entries(data).filter(([key]) => !['id', 'gun_id', 'character_id', 'build_combination'].includes(key))); }
async function save() {
    if (saving.value || loading.value || !catalog.value) return;
    if ([1, 2].some(slot => selectedGun(slot) && !selectedMods(selectedGun(slot).mods, 'mods').length)) {
        error.value = 'Select at least one mod for each selected weapon before saving.';
        return;
    }
    saving.value = true;
    error.value = '';
    errors.value = {};
    try {
        const body = await request(config.saveUrl, { method: config.method, body: JSON.stringify(form) });
        window.location.assign(body.url);
    } catch (failure) { error.value = failure.message; errors.value = failure.errors || {}; }
    finally { saving.value = false; }
}
onMounted(() => { if (form.character_id) loadCatalog(form.character_id); });
</script>
