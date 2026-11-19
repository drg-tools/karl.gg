/**
 * Shared stat maths for weapon, equipment and throwable readouts.
 */
const _calculateDamage = (stats) => {
    let damageWords = [
        "Damage",
        "Area Damage",
        "Electric Damage",
        "Direct Damage",
    ];
    let magazineSizeWords = [
        "Tank Size",
        "Magazine Size",
        "Clip Size",
        "Combined Clip Size",
    ];
    let ammoWords = ["Max Ammo", "Max Fuel"];
    let rateOfFireWords = ["Rate of Fire", "Combined Rate of Fire"];
    let reloadTimeWords = ["Reload Time"];
    let pelletsWords = ["Pellets"];
    let weakPointWords = ["Weakpoint Damage Bonus"];
    let dpsStats = {};
    for (let stat of stats) {
        if (damageWords.includes(stat.name)) {
            if (dpsStats.damage) {
                // there is a damage stat already, add the second one to it (probably area damage)
                dpsStats.damage += parseFloat(stat.value);
            } else {
                dpsStats.damage = parseFloat(stat.value);
            }
        } else if (
            magazineSizeWords.includes(stat.name) &&
            !dpsStats.magazineSize
        ) {
            dpsStats.magazineSize = parseFloat(stat.value);
        } else if (
            rateOfFireWords.includes(stat.name) &&
            !dpsStats.rateOfFire
        ) {
            dpsStats.rateOfFire = parseFloat(stat.value);
        } else if (
            reloadTimeWords.includes(stat.name) &&
            !dpsStats.reloadTime
        ) {
            dpsStats.reloadTime = parseFloat(stat.value);
        } else if (pelletsWords.includes(stat.name)) {
            dpsStats.damage = dpsStats.damage * parseFloat(stat.value);
        } else if (ammoWords.includes(stat.name)) {
            dpsStats.maxAmmo = parseFloat(stat.value);
        } else if (weakPointWords.includes(stat.name)) {
            dpsStats.weakPoint = parseFloat(stat.value);
        }
    }
    dpsStats.maxAmmo = dpsStats.maxAmmo + dpsStats.magazineSize;
    let timeToEmpty = dpsStats.magazineSize / dpsStats.rateOfFire;
    let damageTime = timeToEmpty + dpsStats.reloadTime; // sequence of shooting and reloading for calculating dps
    let magazineDamage = dpsStats.damage * dpsStats.magazineSize;
    let damagePerSecond = magazineDamage / damageTime;
    let weakpointDamage = (
        dpsStats.damage *
        (1 + dpsStats.weakPoint / 100)
    ).toFixed(2);
    return {
        tte: (dpsStats.magazineSize / dpsStats.rateOfFire).toFixed(2),
        wpd: isNaN(weakpointDamage) ? undefined : weakpointDamage,
        dps: parseFloat(damagePerSecond).toFixed(2),
        dpm: magazineDamage,
        dpa: (dpsStats.damage * dpsStats.maxAmmo).toFixed(2),
    };
};
const precisionCalc = (a) => {
    if (!isFinite(a)) return 0;
    var e = 1,
        p = 0;
    while (Math.round(a * e) / e !== a) {
        e *= 10;
        p++;
    }
    return p;
};
const calculateUpgradesForStat = (stat, upgradesForStat) => {
    // loop trough upgradesForStat and calculate
    // if multiply is true, put in temp array and loop trough all multiplications in the end, after normal calc is done
    let upgradesToMultiply = [];
    let modifiedValue = stat.value;
    let originalValue = stat.value;
    let statsPrecision = precisionCalc(modifiedValue);
    let precision = precisionCalc(originalValue);
    for (let upgradeElement of upgradesForStat) {
        let upgradePrecision = precisionCalc(upgradeElement.value);
        let precisionTemp =
            statsPrecision > upgradePrecision
                ? statsPrecision
                : upgradePrecision;
        precision = precision > precisionTemp ? precision : precisionTemp;
        if (precision > 1) {
            precision = 1;
        }
        if (upgradeElement.multiply) {
            upgradesToMultiply.push(upgradeElement);
        } else {
            // calculation here
            if (upgradeElement.subtract) {
                modifiedValue = (
                    parseFloat(modifiedValue) - parseFloat(upgradeElement.value)
                ).toFixed(precision);
            } else {
                modifiedValue = (
                    parseFloat(modifiedValue) + parseFloat(upgradeElement.value)
                ).toFixed(precision);
            }
        }
    }
    if (upgradesToMultiply.length > 0) {
        for (let upgradetoMultiply of upgradesToMultiply) {
            // calculation here
            modifiedValue = (
                parseFloat(modifiedValue) * parseFloat(upgradetoMultiply.value)
            ).toFixed(precision);
        }
    }
    /* todo: homebrew explosive is getting too many digits :( */
    modifiedValue = parseFloat(modifiedValue).toFixed(precision);
    let difference = (
        parseFloat(modifiedValue) - parseFloat(originalValue)
    ).toFixed(precision);
    if (stat.name === "Magazine Size") {
        // make sure magazine size is always an even number
        difference = Math.ceil(difference);
        modifiedValue = Math.floor(modifiedValue);
    }
    stat.modifier = `${difference < 0 ? "" : "+"}${difference}${
        stat.percent ? "%" : ""
    }`;
    stat.modified = true;
    stat.value = modifiedValue;
    stat.baseValue = originalValue;
    return stat;
};
const getModifiedStats = (baseStats, selectedUpgrades) => {
    // loop trough all selected upgrades
    let upgradesForEachStat = new Map();

    for (let upgrade of selectedUpgrades) {
        // loop trough stats of upgrade
        for (let statKey in upgrade.json_stats) {
            let statContent = upgrade.json_stats[statKey];
            let tempContent = [];
            if (upgradesForEachStat.has(statKey)) {
                tempContent = upgradesForEachStat.get(statKey);
            }
            tempContent.push(statContent);
            upgradesForEachStat.set(statKey, tempContent);
        }
    }

    // group all upgrades by stat
    // loop trough groups and calculate new value for each stat
    // return stats
    let stats = [];
    for (let stat in baseStats) {
        let workingStat = Object.assign({}, baseStats[stat]);
        if (upgradesForEachStat.has(stat)) {
            let upgradesForStat = upgradesForEachStat.get(stat);
            let upgradedStat = calculateUpgradesForStat(
                workingStat,
                upgradesForStat
            );
            stats.push(upgradedStat);
        } else {
            if (workingStat.value === 0) {
                workingStat.inactive = true;
            }
            workingStat.baseValue = workingStat.value;
            stats.push(workingStat);
        }
    }
    // stats is array of objects:
    /*
		baseValue: 120
		modified: true
		modifier: "+24"
		name: "Battery Capacity"
		value: "144"
		...also
		*/
    return { stats: stats };
};
export { _calculateDamage as calculateDamage, getModifiedStats };
