import { computed, reactive, ref, watch } from "vue";
import { calculateDamage, getModifiedStats } from "./statCalculator";

const storageKey = "karl:calculator:v1";
const gunTierLevels = [1, 4, 8, 12, 16];

/**
 * Stats where a smaller number is the better result. Listed by exact name:
 * a pattern such as /heat/ would also catch "Heat", the burn damage a
 * flamethrower deals, where more is better.
 */
const lowerIsBetter = new Set(
    [
        "Base Spread",
        "Max Spread",
        "Spread per Shot",
        "Recoil",
        "Horizontal Recoil",
        "Vertical Recoil",
        "Reload Time",
        "Spinup Time",
        "Charge Time",
        "Chargeup Time",
        "Charge Up Time",
        "Wind up time",
        "Cooldown",
        "Cooldown Delay",
        "Power Attack Cooldown",
        "Recharge Time",
        "Heat Generation",
        "Normal Shot Heat Generation",
        "Charged Shot Heat Generation",
        "Heat per Shot",
        "Heat Buildup When Charged",
        "Overheat Duration",
        "Ammo Swap Time",
        "Construction Time",
        "Time to Acquire a Lock-On",
        "Total Time to Fire All Bullets",
        "Charged Shot Cost",
        "Focused Shot Ammo Cost",
        "Pressure Drop Rate",
        "Self-Slow Duration",
        "Cloud Deploy Delay",
        "Cloud Deploy Time",
        "Plasma Expansion Delay",
        "Regeneration Delay",
        "Repressurization Delay",
        "Override Delay Before Reloading",
        "Explosion Delay after Triggering",
        "Delay between Detonations",
        "Delay between Volleys during Burst",
        "Initial Arming Time",
        "Arming Time after Explosion",
        "Arming Time between Bursts",
    ].map((name) => name.toLowerCase())
);

export const classColors = {
    Engineer: "#eb745d",
    Scout: "#68aaf6",
    Driller: "#ebc64d",
    Gunner: "#9cba6f",
};

export function cleanDescription(text) {
    return (text || "")
        .replace(/<[^>]*>/g, "")
        .replace(/\\n|\n/g, " ")
        .replace(/\s+/g, " ")
        .trim();
}

export function upgradeDescription(upgrade) {
    return cleanDescription(upgrade.text_description || upgrade.description);
}

export function upgradeName(upgrade) {
    return upgrade.mod_name || upgrade.overclock_name || upgrade.name;
}

export function formatStat(row, value) {
    if (row.boolean) {
        return value ? "Yes" : "No";
    }
    const rounded = Math.round(value * 100) / 100;

    return `${Math.abs(rounded) >= 1000 ? rounded.toLocaleString() : rounded}${
        row.percent ? "%" : ""
    }`;
}

export function statDifference(row, from, to) {
    if (row.boolean) {
        return to ? "on" : "off";
    }
    const change = Math.round((to - from) * 100) / 100;

    return `${change > 0 ? "+" : ""}${change}${row.percent ? "%" : ""}`;
}

export function isImprovement(statName, from, to) {
    return lowerIsBetter.has(String(statName).toLowerCase())
        ? to < from
        : to > from;
}

function numeric(value) {
    const parsed = parseFloat(value);
    return Number.isFinite(parsed) ? parsed : 0;
}

function readStorage() {
    try {
        return JSON.parse(localStorage.getItem(storageKey)) || {};
    } catch {
        return {};
    }
}

/**
 * State for the standalone build calculator: every weapon, equipment and
 * throwable of a class in tabs, picks remembered per item, hover previews and
 * a pinned build to compare against. Nothing is saved to the server; picks
 * stay in this browser.
 */
export function useCalculator(config) {
    const stored = readStorage();
    const catalogs = reactive({});
    const loading = ref(false);
    const error = ref("");
    const state = reactive({
        characterId:
            config.characters.find(
                (character) => character.id === stored.characterId
            )?.id ?? config.characters[0]?.id,
        /** Open tab per class: { kind: "gun" | "equipment" | "throwable", id } */
        tabs: stored.tabs || {},
        /** Picks per item key ("gun:13"): { tiers: { 1: modId }, overclock: id } */
        picks: stored.picks || {},
        /** Throwable shown per class. */
        throwables: stored.throwables || {},
        hover: null,
        /** Last upgrade hovered or focused; stays after the pointer leaves. */
        inspected: null,
        baseline: null,
        showInactive: false,
    });

    const character = computed(() =>
        config.characters.find((item) => item.id === state.characterId)
    );
    const catalog = computed(() => catalogs[state.characterId] || null);
    const color = computed(() => classColors[character.value?.name] || "#fc9e00");

    const guns = (slot) =>
        (catalog.value?.guns || []).filter((gun) => gun.character_slot === slot);
    const equipments = computed(() => catalog.value?.equipments || []);
    const throwables = computed(() => catalog.value?.throwables || []);

    const tab = computed(() => {
        const saved = state.tabs[state.characterId];
        if (saved && findItem(saved)) return saved;
        const firstGun = guns(1)[0];
        return firstGun ? { kind: "gun", id: firstGun.id } : null;
    });

    function findItem({ kind, id }) {
        if (!catalog.value) return null;
        if (kind === "gun")
            return catalog.value.guns.find((gun) => gun.id === id) || null;
        if (kind === "equipment")
            return equipments.value.find((item) => item.id === id) || null;
        if (kind === "throwable") return throwables.value[0] || null;
        return null;
    }

    const throwable = computed(
        () =>
            throwables.value.find(
                (item) => item.id === state.throwables[state.characterId]
            ) ||
            throwables.value[0] ||
            null
    );

    /** The item on screen, with its upgrade options. */
    const subject = computed(() => {
        if (!tab.value) return null;
        const { kind } = tab.value;
        if (kind === "throwable") {
            return throwable.value
                ? {
                      kind,
                      key: `throwable:${throwable.value.id}`,
                      item: throwable.value,
                      mods: [],
                      overclocks: [],
                      image: `/assets/${throwable.value.icon}.svg`,
                  }
                : null;
        }
        const item = findItem(tab.value);
        if (!item) return null;

        return kind === "gun"
            ? {
                  kind,
                  key: `gun:${item.id}`,
                  item,
                  mods: item.mods,
                  overclocks: item.overclocks,
                  image: `/assets/${item.image}.svg`,
              }
            : {
                  kind,
                  key: `equipment:${item.id}`,
                  item,
                  mods: item.equipment_mods,
                  overclocks: [],
                  image: `/assets/${item.icon}.svg`,
              };
    });

    const picks = computed(
        () =>
            (subject.value && state.picks[subject.value.key]) || {
                tiers: {},
                overclock: null,
            }
    );

    const tiers = computed(() => {
        if (!subject.value) return [];
        const byTier = {};
        for (const mod of subject.value.mods) {
            (byTier[mod.mod_tier] ||= []).push(mod);
        }

        return Object.keys(byTier)
            .map(Number)
            .sort((a, b) => a - b)
            .map((tier) => ({
                tier,
                level:
                    subject.value.kind === "gun"
                        ? gunTierLevels[tier - 1]
                        : null,
                mods: byTier[tier].sort((a, b) =>
                    a.mod_index.localeCompare(b.mod_index)
                ),
            }));
    });

    const selectedMods = computed(() =>
        Object.values(picks.value.tiers)
            .map((id) => subject.value.mods.find((mod) => mod.id === id))
            .filter(Boolean)
            .sort((a, b) => a.mod_tier - b.mod_tier)
    );
    const overclock = computed(
        () =>
            subject.value?.overclocks.find(
                (item) => item.id === picks.value.overclock
            ) || null
    );
    const equipped = computed(() => [
        ...selectedMods.value,
        ...(overclock.value ? [overclock.value] : []),
    ]);

    function isEquipped(target) {
        if (!target) return false;
        if (target.kind === "mod")
            return selectedMods.value.some((mod) => mod.id === target.item.id);

        return (target.item?.id ?? null) === (overclock.value?.id ?? null);
    }

    /** The build with the hovered upgrade swapped in. */
    const previewUpgrades = computed(() => {
        const target = state.hover;
        if (!target || isEquipped(target)) return null;
        if (target.kind === "mod") {
            return [
                ...selectedMods.value.filter(
                    (mod) => mod.mod_tier !== target.item.mod_tier
                ),
                target.item,
                ...(overclock.value ? [overclock.value] : []),
            ];
        }

        return [...selectedMods.value, ...(target.item ? [target.item] : [])];
    });
    /** The build without the hovered, equipped upgrade. */
    const withoutUpgrades = computed(() => {
        const target = state.hover;
        if (!target?.item || !isEquipped(target)) return null;
        return equipped.value.filter((item) => item.id !== target.item.id);
    });

    const hasBaseline = computed(
        () => !!state.baseline && state.baseline.key === subject.value?.key
    );
    const baselineUpgrades = computed(() => {
        if (!hasBaseline.value) return null;
        const { modIds, overclockId } = state.baseline;
        return [
            ...subject.value.mods.filter((mod) => modIds.includes(mod.id)),
            ...subject.value.overclocks.filter(
                (item) => item.id === overclockId
            ),
        ];
    });

    function calculate(upgrades) {
        const baseStats = subject.value.item.json_stats || {};
        const keys = Object.keys(baseStats);
        return Object.fromEntries(
            getModifiedStats(baseStats, upgrades).stats.map((stat, index) => [
                keys[index],
                stat,
            ])
        );
    }

    const rows = computed(() => {
        if (!subject.value) return [];
        const current = calculate(equipped.value);
        const preview = previewUpgrades.value
            ? calculate(previewUpgrades.value)
            : null;
        const without = withoutUpgrades.value
            ? calculate(withoutUpgrades.value)
            : null;
        const baseline = baselineUpgrades.value
            ? calculate(baselineUpgrades.value)
            : null;

        return Object.entries(current).map(([key, stat]) => {
            const base = numeric(stat.baseValue ?? stat.value);
            const value = numeric(stat.value);
            const row = {
                key,
                name: stat.name,
                percent: stat.percent,
                boolean: stat.boolean,
                base,
                value,
                preview: preview ? numeric(preview[key]?.value) : value,
                without: without ? numeric(without[key]?.value) : value,
                baseline: baseline ? numeric(baseline[key]?.value) : null,
            };
            row.modified = row.value !== row.base;
            row.previewed = row.preview !== row.value;
            row.highlighted = row.without !== row.value;
            row.baselineDiff =
                row.baseline !== null && row.baseline !== row.value;
            // Ignore hover state here, so previews never add or remove rows.
            row.inactive =
                !row.modified && !row.baselineDiff && row.base === 0;

            return row;
        });
    });
    const visibleRows = computed(() =>
        rows.value.filter((row) => state.showInactive || !row.inactive)
    );
    const hiddenCount = computed(
        () => rows.value.length - visibleRows.value.length
    );
    const previewing = computed(() => !!previewUpgrades.value);

    function damageFor(upgrades) {
        if (!upgrades) return null;
        let damage = {};
        try {
            damage = calculateDamage(
                Object.values(calculate(upgrades)).map((stat) => ({ ...stat })),
                subject.value.item.gun_class
            );
        } catch {
            /* Items without magazine maths have no damage figures. */
        }
        const usable = (value) =>
            value !== undefined &&
            Number.isFinite(Number(value)) &&
            Number(value)
                ? Number(value)
                : null;

        return [
            ["Damage per second", usable(damage.dps)],
            ["Magazine damage", usable(damage.dpm)],
            ["Total damage", usable(damage.dpa)],
        ];
    }
    const derived = computed(() => {
        if (!subject.value) return [];
        const current = damageFor(equipped.value);
        const preview = damageFor(previewUpgrades.value);
        const baseline = damageFor(baselineUpgrades.value);
        const bare = damageFor([]);

        return current
            .map(([label, value], index) => ({
                label,
                value,
                preview: preview?.[index][1] ?? null,
                baseline: baseline?.[index][1] ?? null,
                base: bare?.[index][1] ?? null,
            }))
            .filter((metric) => metric.value !== null);
    });

    const credits = computed(() =>
        equipped.value.reduce((total, item) => total + (item.credits_cost || 0), 0)
    );

    /** Upgrade code, one digit per tier: 1–3 for A–C, "-" for none. */
    const code = computed(() => {
        const digits = Array(tiers.value.length).fill("-");
        for (const mod of selectedMods.value) {
            digits[mod.mod_tier - 1] = mod.mod_index.charCodeAt(0) - 64;
        }
        return digits.join("");
    });

    /** Stat-by-stat effect of one upgrade on the current build. */
    function effectOf(target) {
        if (!subject.value || !target?.item) return [];
        const { kind, item } = target;
        const without = equipped.value.filter((upgrade) =>
            kind === "mod"
                ? upgrade.mod_tier !== item.mod_tier || !upgrade.mod_name
                : !upgrade.overclock_name
        );
        const before = calculate(without);
        const after = calculate([...without, item]);

        return Object.keys(after)
            .filter(
                (key) =>
                    numeric(before[key].value) !== numeric(after[key].value)
            )
            .map((key) => {
                const row = after[key];
                const from = numeric(before[key].value);
                const to = numeric(row.value);
                return {
                    name: row.name,
                    from: formatStat(row, from),
                    to: formatStat(row, to),
                    good: isImprovement(row.name, from, to),
                };
            });
    }

    function ensurePicks() {
        const key = subject.value.key;
        state.picks[key] ||= { tiers: {}, overclock: null };
        // Lets the builder handoff pick the weapon changed most recently.
        state.picks[key].changedAt = Date.now();
        return state.picks[key];
    }

    function hasPicks(key) {
        const found = state.picks[key];
        return (
            !!found &&
            (Object.keys(found.tiers).length > 0 || !!found.overclock)
        );
    }

    /**
     * What "Use in builder" carries over for this class. A loadout holds one
     * weapon per slot: the open weapon wins its slot, otherwise the weapon
     * with picks that changed most recently. Every equipment pick goes along.
     */
    const handoff = computed(() => {
        if (!catalog.value) return null;
        const open = tab.value?.kind === "gun" ? tab.value.id : null;
        const chosenGuns = [1, 2]
            .map((slot) => {
                const slotGuns = guns(slot);
                const opened = slotGuns.find((gun) => gun.id === open);
                if (opened) return opened;
                return (
                    slotGuns
                        .filter((gun) => hasPicks(`gun:${gun.id}`))
                        .sort(
                            (a, b) =>
                                (state.picks[`gun:${b.id}`].changedAt || 0) -
                                (state.picks[`gun:${a.id}`].changedAt || 0)
                        )[0] || null
                );
            })
            .filter(Boolean);
        const gunPicks = chosenGuns.map(
            (gun) => state.picks[`gun:${gun.id}`] || { tiers: {} }
        );
        const equipmentPicks = equipments.value
            .map((item) => state.picks[`equipment:${item.id}`])
            .filter(Boolean);
        const chosenThrowable = state.throwables[state.characterId] ?? null;

        const params = new URLSearchParams({ class: state.characterId });
        const list = (key, ids) => {
            if (ids.length) params.set(key, ids.join(","));
        };
        list("guns", chosenGuns.map((gun) => gun.id));
        list("mods", gunPicks.flatMap((found) => Object.values(found.tiers)));
        list(
            "overclocks",
            gunPicks.map((found) => found.overclock).filter(Boolean)
        );
        list(
            "equipment_mods",
            equipmentPicks.flatMap((found) => Object.values(found.tiers))
        );
        if (chosenThrowable) params.set("throwable", chosenThrowable);

        const equipmentCount = equipmentPicks.reduce(
            (total, found) => total + Object.keys(found.tiers).length,
            0
        );

        return {
            url: `${config.builderUrl}?${params}`,
            guns: chosenGuns.map((gun) => gun.name),
            equipmentCount,
        };
    });

    async function loadCatalog(id) {
        if (catalogs[id]) return;
        const url = config.characters.find((item) => item.id === id)?.url;
        loading.value = true;
        error.value = "";
        try {
            const response = await fetch(url, {
                headers: { Accept: "application/json" },
            });
            if (!response.ok) throw new Error(response.statusText);
            const body = await response.json();
            catalogs[id] = body.data || body;
        } catch {
            error.value = "The armory could not be loaded. Try again.";
        } finally {
            loading.value = false;
        }
    }

    function selectCharacter(id) {
        state.characterId = id;
        state.hover = state.inspected = null;
        loadCatalog(id);
    }
    function selectTab(kind, id = null) {
        state.tabs[state.characterId] = { kind, id };
        state.hover = state.inspected = null;
    }
    function selectThrowable(id) {
        state.throwables[state.characterId] = id;
        state.hover = state.inspected = null;
    }
    function toggleMod(mod) {
        const current = ensurePicks();
        if (current.tiers[mod.mod_tier] === mod.id) {
            delete current.tiers[mod.mod_tier];
        } else {
            current.tiers[mod.mod_tier] = mod.id;
        }
    }
    function toggleOverclock(id) {
        const current = ensurePicks();
        current.overclock = current.overclock === id ? null : id;
    }
    /** Set every tier from a code such as "21312"; 0 or "-" skips a tier. */
    function applyCode(text) {
        const current = ensurePicks();
        current.tiers = {};
        String(text)
            .replace(/[^0-9-]/g, "")
            .slice(0, tiers.value.length)
            .split("")
            .forEach((digit, index) => {
                const tier = tiers.value[index];
                const mod = tier?.mods[Number(digit) - 1];
                if (mod) current.tiers[tier.tier] = mod.id;
            });
    }
    function clearBuild() {
        const current = ensurePicks();
        current.tiers = {};
        current.overclock = null;
    }
    function pinBuild() {
        state.baseline = {
            key: subject.value.key,
            modIds: selectedMods.value.map((mod) => mod.id),
            overclockId: overclock.value?.id ?? null,
            code: code.value,
            overclockName: overclock.value?.overclock_name || null,
        };
    }
    /** Pin the current build and load the pinned one, to flip between two. */
    function swapWithPin() {
        const pinned = state.baseline;
        pinBuild();
        const current = ensurePicks();
        current.tiers = {};
        subject.value.mods
            .filter((mod) => pinned.modIds.includes(mod.id))
            .forEach((mod) => {
                current.tiers[mod.mod_tier] = mod.id;
            });
        current.overclock = pinned.overclockId;
    }
    function unpin() {
        state.baseline = null;
    }
    function hover(kind, item) {
        state.hover = state.inspected = { kind, item };
    }
    function unhover() {
        state.hover = null;
    }

    watch(
        () => [
            state.characterId,
            state.tabs,
            state.picks,
            state.throwables,
        ],
        () => {
            try {
                localStorage.setItem(
                    storageKey,
                    JSON.stringify({
                        characterId: state.characterId,
                        tabs: state.tabs,
                        picks: state.picks,
                        throwables: state.throwables,
                    })
                );
            } catch {
                /* Storage is optional. */
            }
        },
        { deep: true }
    );

    loadCatalog(state.characterId);

    return {
        state,
        loading,
        error,
        character,
        catalog,
        color,
        guns,
        equipments,
        throwables,
        tab,
        subject,
        tiers,
        selectedMods,
        overclock,
        rows,
        visibleRows,
        hiddenCount,
        previewing,
        hasBaseline,
        derived,
        credits,
        code,
        isEquipped,
        effectOf,
        hasPicks,
        handoff,
        loadCatalog,
        selectCharacter,
        selectTab,
        selectThrowable,
        toggleMod,
        toggleOverclock,
        applyCode,
        clearBuild,
        pinBuild,
        swapWithPin,
        unpin,
        hover,
        unhover,
    };
}
