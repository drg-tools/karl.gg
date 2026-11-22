/**
 * Google Analytics helpers.
 *
 * Every call is a no-op when the gtag snippet is missing (analytics disabled
 * for the environment, or blocked by the browser), so callers never guard.
 */

function gtag(...args) {
    if (typeof window.gtag === "function") {
        window.gtag(...args);
    }
}

/**
 * Record which layout a reader sees on a page with a view toggle.
 *
 * The user property answers "who uses which view" across sessions, and the
 * event records each showing along with whether it came from the reader's own
 * saved choice or the page's default.
 *
 * @param {"loadout_page"|"builder"} surface
 * @param {string} mode
 * @param {"saved"|"default"} source
 */
export function trackViewModeShown(surface, mode, source) {
    gtag("set", "user_properties", { [`${surface}_view`]: mode });
    gtag("event", "view_mode_shown", {
        surface,
        view_mode: mode,
        view_source: source,
    });
}

/**
 * Record a reader switching layouts.
 *
 * @param {"loadout_page"|"builder"} surface
 * @param {string} from
 * @param {string} to
 */
export function trackViewModeChange(surface, from, to) {
    if (from === to) {
        return;
    }

    gtag("set", "user_properties", { [`${surface}_view`]: to });
    gtag("event", "view_mode_change", {
        surface,
        from_mode: from,
        to_mode: to,
    });
}

/**
 * Record a reader taking a calculator build to the builder.
 *
 * gtag sends with sendBeacon, so the event survives the page navigating away.
 *
 * @param {{ placement: "intro_link"|"handoff_button", characterName: string|null, gunCount: number, equipmentUpgradeCount: number }} handoff
 */
export function trackCalculatorToBuilder({
    placement,
    characterName,
    gunCount,
    equipmentUpgradeCount,
}) {
    gtag("event", "calculator_to_builder", {
        placement,
        character: characterName,
        gun_count: gunCount,
        equipment_upgrade_count: equipmentUpgradeCount,
    });
}
