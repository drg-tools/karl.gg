import keyBy from "lodash-es/keyBy";

/**
 * Function that will take a gun and selected overclock and build the Build Metrics index from it.
 *
 * Build Metric index is defined as a 6 character string with:
 * - the first 5 characters as A through C representing the position of the mod in the tier
 * - the last character as 1 through 6 representing the selected overclock for the build
 *
 * For example, if you have the 1st mod selected on 1st tier and 3rd mod selected on the 2nd tier it would look
 * something like this: { tier: 1, position: 1, tier: 2, position: 3}
 *
 * This would be represented in the build metrics table as AC----
 *
 * @param gun
 * @param overclockId
 * @param selectedMods
 * @returns {*}
 */
export const buildComboIndexFromGun = (gun, overclockId, selectedMods) => {
    // Initialize array with 6 spots all filled with dashes
    let byTier = new Array(6).fill("-");

    // Get all mods of gun keyed by mod id
    const modsByCombo = keyBy(gun?.mods, "id");

    // Get selected overclock object
    const overclock = gun?.overclocks.find((o) => o.id === overclockId);

    // For each mod selected in the tree, update our array. The index is represented by the tier - 1.
    selectedMods.forEach(
        (m) =>
            (byTier[parseInt(m.selectedModTier) - 1] =
                modsByCombo[m.selectedModId]?.mod_index)
    );
    byTier[5] = overclock ? overclock.overclock_index : "-";

    return byTier.join("");
};
