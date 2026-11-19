import {
    reactive,
    ref,
    computed,
    watch,
    onMounted,
    onBeforeUnmount,
    nextTick,
} from "vue";

export function useLoadoutBuilder(config) {
    const classInfo = {
        Engineer: {
            letter: "E",
            color: "#eb745d",
            role: "Fortify & defend",
            number: "01",
            description: "Build a better plan.",
        },
        Scout: {
            letter: "S",
            color: "#68aaf6",
            role: "Light & mobility",
            number: "02",
            description: "Leave no cave unexplored.",
        },
        Driller: {
            letter: "D",
            color: "#ebc64d",
            role: "Excavate & eliminate",
            number: "03",
            description: "Make your own way.",
        },
        Gunner: {
            letter: "G",
            color: "#9cba6f",
            role: "Firepower & protection",
            number: "04",
            description: "Hold the line.",
        },
    };
    const sections = [
        { key: "primary", label: "Primary weapon" },
        { key: "secondary", label: "Secondary weapon" },
        { key: "equipment", label: "Equipment" },
        { key: "throwable", label: "Throwable" },
        { key: "review", label: "Review & publish" },
    ];
    const form = reactive({
        name: "",
        description: "",
        character_id: null,
        throwable_id: null,
        mods: [],
        overclocks: [],
        equipment_mods: [],
        ...config.initial,
    });
    const weapons = reactive({ 1: null, 2: null });
    const overclocks = reactive({ 1: null, 2: null });
    const catalog = ref(null);
    const loading = ref(false);
    const saving = ref(false);
    const error = ref("");
    const errors = ref({});
    const errorPanel = ref(null);
    const contentPanel = ref(null);
    const nameInput = ref(null);
    const activeSection = ref("primary");
    const choosingClass = ref(false);
    const pendingClass = ref(null);
    const classConfirmation = ref(null);
    const railPanel = ref(null);
    const activeEquipmentId = ref(null);
    const metrics = reactive({});
    const catalogs = new Map();
    let catalogRequest = 0;
    let draftTimer;
    let navigatingAway = false;
    let restoreWeapons = null;
    const draftKey = `karl:loadout-draft:v1:${window.location.pathname}`;
    const draftSaved = ref(false);
    const storageAvailable = ref(true);
    const recoverableDraft = ref(null);
    const dirty = ref(false);
    const orderedCharacters = computed(() =>
        [...config.characters].sort((a, b) =>
            (classInfo[a.name]?.number || "").localeCompare(
                classInfo[b.name]?.number || ""
            )
        )
    );
    const currentClass = computed(() =>
        config.characters.find(
            (character) => character.id === form.character_id
        )
    );
    const classDetails = computed(
        () => classInfo[currentClass.value?.name] || { color: "#fc9e00" }
    );
    const activeWeaponSlot = computed(() =>
        activeSection.value === "secondary" ? 2 : 1
    );
    const activeEquipment = computed(() =>
        catalog.value?.equipments.find(
            (equipment) => equipment.id === activeEquipmentId.value
        )
    );
    const selectedThrowable = computed(() =>
        catalog.value?.throwables.find(
            (throwable) => throwable.id === form.throwable_id
        )
    );
    const configuredCount = computed(
        () =>
            sections.filter(
                (section) =>
                    section.key !== "review" && sectionConfigured(section.key)
            ).length
    );
    const nextLabel = computed(
        () =>
            ({
                primary: "Secondary weapon",
                secondary: "Equipment",
                equipment: "Throwables",
                throwable: "Review loadout",
            }[activeSection.value])
    );

    async function request(url, options = {}) {
        const response = await fetch(url, {
            credentials: "same-origin",
            ...options,
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
                ...options.headers,
            },
        });
        const body = await response.json().catch(() => ({}));
        if (!response.ok) {
            const failure = new Error(
                response.status === 419
                    ? "Your session expired. Reload this page, then continue your saved draft."
                    : body.message ||
                      "Something interrupted the request. Please try again."
            );
            failure.errors = body.errors || {};
            throw failure;
        }
        return body;
    }
    async function loadCatalog(id) {
        const requestId = ++catalogRequest;
        loading.value = true;
        error.value = "";
        try {
            if (!catalogs.has(id))
                catalogs.set(
                    id,
                    (
                        await request(
                            config.characters.find(
                                (character) => character.id === id
                            ).url
                        )
                    ).data
                );
            if (requestId !== catalogRequest) return;
            catalog.value = catalogs.get(id);
            for (const slot of [1, 2]) {
                const gun = catalog.value.guns.find(
                    (gun) =>
                        gun.character_slot === slot &&
                        (restoreWeapons?.[slot] === gun.id ||
                            gun.mods.some((mod) =>
                                form.mods.includes(mod.id)
                            ) ||
                            gun.overclocks.some((overclock) =>
                                form.overclocks.includes(overclock.id)
                            ))
                );
                weapons[slot] = gun?.id || null;
                overclocks[slot] =
                    gun?.overclocks.find((overclock) =>
                        form.overclocks.includes(overclock.id)
                    )?.id || null;
            }
            restoreWeapons = null;
            activeEquipmentId.value = catalog.value.equipments[0]?.id || null;
        } catch (failure) {
            if (requestId === catalogRequest) showError(failure.message);
        } finally {
            if (requestId === catalogRequest) loading.value = false;
        }
    }
    async function requestClass(id) {
        if (recoverableDraft.value) discardDraft();
        if (form.character_id === id) {
            choosingClass.value = false;
            pendingClass.value = null;
            return;
        }
        if (
            form.mods.length ||
            form.overclocks.length ||
            form.equipment_mods.length ||
            form.throwable_id ||
            weapons[1] ||
            weapons[2]
        ) {
            pendingClass.value = id;
            await nextTick();
            classConfirmation.value?.focus();
            classConfirmation.value?.scrollIntoView({
                behavior: scrollBehavior(),
                block: "center",
            });
            return;
        }
        selectClass(id);
    }
    function cancelClassSelection() {
        choosingClass.value = false;
        pendingClass.value = null;
    }
    function selectClass(id) {
        Object.assign(form, {
            character_id: id,
            mods: [],
            overclocks: [],
            equipment_mods: [],
            throwable_id: null,
        });
        weapons[1] = weapons[2] = overclocks[1] = overclocks[2] = null;
        catalog.value = null;
        metrics[1] = metrics[2] = null;
        pendingClass.value = null;
        choosingClass.value = false;
        activeSection.value = "primary";
        loadCatalog(id);
    }
    function selectedGun(slot) {
        return catalog.value?.guns.find((gun) => gun.id === weapons[slot]);
    }
    function selectedOverclock(slot) {
        return selectedGun(slot)?.overclocks.find(
            (overclock) => overclock.id === overclocks[slot]
        );
    }
    function selectedMods(mods, field) {
        return mods.filter((mod) => form[field].includes(mod.id));
    }
    /**
     * Tiers fill from 1 upward: a tier opens once the tier below it has an
     * upgrade, and removing an upgrade also clears every tier above it.
     */
    function tierUnlocked(tier, field, available) {
        return (
            tier <= 1 ||
            available.some(
                (item) =>
                    item.mod_tier === tier - 1 && form[field].includes(item.id)
            )
        );
    }
    function selectMod(mod, field, available) {
        if (form[field].includes(mod.id)) {
            const cleared = available
                .filter((item) => item.mod_tier >= mod.mod_tier)
                .map((item) => item.id);
            form[field] = form[field].filter((id) => !cleared.includes(id));
            metrics[1] = metrics[2] = null;
            return;
        }
        if (!tierUnlocked(mod.mod_tier, field, available)) return;
        const sameTier = available
            .filter((item) => item.mod_tier === mod.mod_tier)
            .map((item) => item.id);
        form[field] = [
            ...form[field].filter((id) => !sameTier.includes(id)),
            mod.id,
        ];
        metrics[1] = metrics[2] = null;
    }
    function chooseWeapon(slot, id) {
        if (weapons[slot] === id) return;
        const slotMods = catalog.value.guns
            .filter((gun) => gun.character_slot === slot)
            .flatMap((gun) => gun.mods.map((mod) => mod.id));
        form.mods = form.mods.filter((id) => !slotMods.includes(id));
        weapons[slot] = id;
        chooseOverclock(slot, null);
    }
    function clearWeapon(slot) {
        const gun = selectedGun(slot);
        if (!gun) return;
        const gunMods = gun.mods.map((mod) => mod.id);
        form.mods = form.mods.filter((id) => !gunMods.includes(id));
        weapons[slot] = null;
        chooseOverclock(slot, null);
    }
    function chooseOverclock(slot, id) {
        overclocks[slot] = id;
        form.overclocks = [overclocks[1], overclocks[2]].filter(Boolean);
        metrics[slot] = null;
    }
    function cleanDescription(text) {
        return (text || "").replace(/<[^>]*>/g, "").replace(/\\n/g, " ");
    }
    function sectionConfigured(key) {
        if (key === "primary" || key === "secondary")
            return !!selectedGun(key === "primary" ? 1 : 2);
        if (key === "equipment") return form.equipment_mods.length > 0;
        if (key === "throwable") return !!form.throwable_id;
        return false;
    }
    function sectionSummary(key) {
        if (key === "primary" || key === "secondary") {
            const gun = selectedGun(key === "primary" ? 1 : 2);
            if (!gun) return "Choose a weapon";
            return selectedMods(gun.mods, "mods").length
                ? gun.name
                : `${gun.name} · needs tier 1`;
        }
        if (key === "equipment")
            return form.equipment_mods.length
                ? `${form.equipment_mods.length} ${
                      form.equipment_mods.length === 1 ? "upgrade" : "upgrades"
                  } equipped`
                : "Tools & armor";
        if (key === "throwable")
            return selectedThrowable.value?.name || "Your finishing touch";
        return form.name || "Name it. Share it.";
    }
    function combination(slot) {
        const gun = selectedGun(slot);
        if (!gun) return "—";
        const parts = Array(5).fill("–");
        selectedMods(gun.mods, "mods").forEach((mod) => {
            parts[mod.mod_tier - 1] = String(mod.mod_index).charCodeAt(0) - 64;
        });
        return (
            parts.join("") +
            " / " +
            (selectedOverclock(slot)?.overclock_index || "–")
        );
    }
    function scrollBehavior() {
        return window.matchMedia("(prefers-reduced-motion: reduce)").matches
            ? "auto"
            : "smooth";
    }
    async function goTo(section) {
        activeSection.value = section;
        await nextTick();
        const activeButton = railPanel.value?.querySelector(".is-active");
        const firstButton = railPanel.value?.querySelector(".rail-item");
        if (activeButton && firstButton)
            railPanel.value.scrollLeft =
                activeButton.offsetLeft - firstButton.offsetLeft;
        contentPanel.value?.focus({ preventScroll: true });
        contentPanel.value?.scrollIntoView({
            behavior: scrollBehavior(),
            block: "start",
        });
    }
    function nextSection() {
        goTo(
            sections[
                sections.findIndex(
                    (section) => section.key === activeSection.value
                ) + 1
            ].key
        );
    }
    function previousSection() {
        const index = sections.findIndex(
            (section) => section.key === activeSection.value
        );
        if (index > 0) goTo(sections[index - 1].key);
    }
    function dismissError() {
        error.value = "";
        errors.value = {};
    }
    function clearFieldError(field) {
        delete errors.value[field];
    }
    async function showError(message) {
        error.value = message;
        await nextTick();
        errorPanel.value?.focus();
    }
    async function fetchMetrics(slot) {
        const gun = selectedGun(slot);
        if (!gun) return;
        const combo = Array(6).fill("-");
        selectedMods(gun.mods, "mods").forEach((mod) => {
            combo[mod.mod_tier - 1] = mod.mod_index;
        });
        combo[5] = selectedOverclock(slot)?.overclock_index || "-";
        const selection = JSON.stringify([
            form.character_id,
            weapons[slot],
            form.mods,
            overclocks[slot],
        ]);
        metrics[slot] = { message: "Loading statistics…" };
        try {
            const body = await request(
                `${config.metricsUrl}?${new URLSearchParams({
                    gun_id: gun.id,
                    combo: combo.join(""),
                })}`
            );
            if (
                selection !==
                JSON.stringify([
                    form.character_id,
                    weapons[slot],
                    form.mods,
                    overclocks[slot],
                ])
            )
                return;
            metrics[slot] = body.data
                ? { data: body.data }
                : {
                      message:
                          "Advanced statistics are not available for this combination.",
                  };
        } catch (failure) {
            if (
                selection ===
                JSON.stringify([
                    form.character_id,
                    weapons[slot],
                    form.mods,
                    overclocks[slot],
                ])
            )
                metrics[slot] = { message: failure.message, failed: true };
        }
    }
    function persistDraft() {
        if (
            recoverableDraft.value ||
            !dirty.value ||
            !form.character_id ||
            navigatingAway
        )
            return;
        try {
            sessionStorage.setItem(
                draftKey,
                JSON.stringify({ form, weapons, section: activeSection.value })
            );
            draftSaved.value = true;
        } catch {
            draftSaved.value = false;
            storageAvailable.value = false;
        }
    }
    function readDraft() {
        try {
            const data = JSON.parse(sessionStorage.getItem(draftKey));
            if (
                data?.form &&
                config.characters.some(
                    (character) => character.id === data.form.character_id
                ) &&
                ["mods", "overclocks", "equipment_mods"].every(
                    (key) =>
                        Array.isArray(data.form[key]) &&
                        data.form[key].every(Number.isInteger)
                )
            )
                recoverableDraft.value = data;
        } catch {
            storageAvailable.value = false;
        }
    }
    function restoreDraft() {
        const data = recoverableDraft.value;
        recoverableDraft.value = null;
        Object.assign(form, {
            name: data.form.name || "",
            description: data.form.description || "",
            character_id: data.form.character_id,
            throwable_id: data.form.throwable_id || null,
            mods: data.form.mods,
            overclocks: data.form.overclocks,
            equipment_mods: data.form.equipment_mods,
        });
        restoreWeapons = data.weapons;
        activeSection.value = sections.some(
            (section) => section.key === data.section
        )
            ? data.section
            : "primary";
        choosingClass.value = false;
        dirty.value = true;
        draftSaved.value = true;
        loadCatalog(form.character_id);
    }
    function discardDraft() {
        recoverableDraft.value = null;
        try {
            sessionStorage.removeItem(draftKey);
        } catch {
            /* Storage is optional. */
        }
    }
    function beforeUnload(event) {
        persistDraft();
        if (dirty.value && !navigatingAway && !draftSaved.value) {
            event.preventDefault();
            event.returnValue = "";
        }
    }
    watch(
        [form, activeSection],
        () => {
            dirty.value = true;
            draftSaved.value = false;
            clearTimeout(draftTimer);
            draftTimer = setTimeout(persistDraft, 250);
        },
        { deep: true }
    );
    async function save() {
        if (saving.value || loading.value || !catalog.value) return;
        errors.value = {};
        error.value = "";
        if (!form.name.trim()) {
            errors.value = {
                name: ["Give your loadout a name before publishing."],
            };
            await nextTick();
            nameInput.value?.focus();
            return;
        }
        const incomplete = [1, 2]
            .map((slot) => selectedGun(slot))
            .find((gun) => gun && !selectedMods(gun.mods, "mods").length);
        if (incomplete) {
            await showError(
                `Choose at least a tier 1 upgrade for ${incomplete.name}, or pick a different weapon.`
            );
            return;
        }
        saving.value = true;
        persistDraft();
        try {
            const body = await request(config.saveUrl, {
                method: config.method,
                body: JSON.stringify(form),
            });
            navigatingAway = true;
            clearTimeout(draftTimer);
            try {
                sessionStorage.removeItem(draftKey);
            } catch {
                /* Storage is optional. */
            }
            window.location.assign(body.url);
        } catch (failure) {
            errors.value = failure.errors || {};
            await showError(failure.message);
        } finally {
            saving.value = false;
        }
    }
    onMounted(() => {
        readDraft();
        if (form.character_id) loadCatalog(form.character_id);
        window.addEventListener("beforeunload", beforeUnload);
    });
    onBeforeUnmount(() => {
        clearTimeout(draftTimer);
        persistDraft();
        window.removeEventListener("beforeunload", beforeUnload);
    });

    return {
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
        tierUnlocked,
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
    };
}
