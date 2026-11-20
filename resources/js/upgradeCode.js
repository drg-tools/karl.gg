export function upgradeCode(mods, tierCount) {
    const choices = Array(tierCount).fill("–");
    for (const mod of mods) {
        choices[mod.mod_tier - 1] = mod.mod_index.charCodeAt(0) - 64;
    }

    return choices.join("");
}
