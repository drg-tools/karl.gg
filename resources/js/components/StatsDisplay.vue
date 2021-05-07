<template>
    <div v-if="dataReady" class="statsDisplay">
        <h1 class="equipmentTitle allCaps">{{ equipment.name }}</h1>
        <h2 class="equipmentSubTitle allCaps">{{ equipment.class }}</h2>
        <div class="statsBaseContainer">
            <div v-for="(stat, statId) in calcStats.stats" :key="statId" class="statsContainer">
                <span class="statsText" :class="[stat.inactive ? 'inactiveStat' : '']">{{ stat.name }}:</span>
                <div class="statsValueContainer">
					<span class="statsValue fixedWidth" :class="[stat.inactive ? 'inactiveStat' : '']">
						{{ stat.baseValue }}<span v-if="stat.percent">%</span>
					</span>
                    <span class="statsModifier fixedWidth">{{ stat.modifier }}</span>
                    <span class="statsValue fixedWidth"
                          :class="[stat.modified ? 'modifiedStat' : stat.inactive ? 'inactiveStat' : '']">
						{{ stat.value }}<span v-if="stat.percent">%</span>
					</span>
                </div>
            </div>
            <!-- todo: different color for dps numbers -->
            <span v-if="calcStats.dps && calcStats.dps !== 'NaN'" class="text-white text-lg block my-2">DPS: {{ calcStats.dps }}</span>
            <span v-if="!!calcStats.dps && calcStats.dps !== 'NaN'" class="inactiveStat">
				<i>Theoretical</i> damage per second, ignoring armor break and weakspot bonuses.
			</span>

            <span v-if="calcStats.dpb" class="text-white text-lg block my-2">Damage per shot: {{ calcStats.dpb }}</span> <!-- damage per bullet -->

            <span v-if="calcStats.wpd" class="text-white text-lg block my-2">Weakpoint Damage: {{ `(1x: ${calcStats.wpd} / 2x: ${(calcStats.wpd * 2).toFixed(2)}
                / 3x: ${(calcStats.wpd * 3).toFixed(2)})` }}</span>
            <span v-if="calcStats.wpd" class="inactiveStat">
				<i>Important,</i> 1x damage is applied to praetorians and oppressors. 2x damage is applied to all grunts and most enemies. 3x is applied to bulks, breeders, mactera, etc.
			</span>

            <span v-if="calcStats.dpm" class="text-white text-lg block my-2">Magazine damage: {{ calcStats.dpm }}<span v-if="calcStats.tte"> / Time to empty mag: {{ calcStats.tte }}S</span>
            </span>
            <span v-if="calcStats.dpm" class="inactiveStat">
				<i>Theoretical</i> damage per magazine and how long it takes to empty it.
			</span>

            <span v-if="calcStats.dpa" class="text-white text-lg block my-2">Total damage: {{ calcStats.dpa }}</span>
            <span v-if="calcStats.dpa" class="inactiveStat">
				<i>Theoretical</i> total damage available with initial ammunition.
			</span>

            <span v-if="calcStats.ex1" class="text-white text-lg block my-2">Total lighting time: {{ calcStats.ex1 }} minutes</span>
            <span v-if="calcStats.ex1" class="inactiveStat">
				<i>Theoretical</i> total lighting time available with initial ammunition.
			</span>

            <span v-if="calcStats.dpsplasma && calcStats.dpsplasma !== 'NaN'" class="text-white text-lg block my-2">Normal shot DPS: {{ calcStats.dpsplasma }}
                <br/>Charged shot DPS: {{ calcStats.dpscharged }}</span>
            <span v-if="!!calcStats.dpsplasma && calcStats.dpsplasma !== 'NaN'" class="inactiveStat">
				<i>Theoretical</i> damage per second without taking overheat into account, ignoring armor break and weakspot bonuses.
			</span>

            <span v-if="calcStats.dpbplasma" class="text-white text-lg block my-2">Damage per normal shot: {{ calcStats.dpbplasma }}
                <br/>Damage per charged shot: {{ calcStats.dpbcharged}}</span>

            <span v-if="calcStats.dpaplasma" class="text-white text-lg block my-2">Total damage for normal shots: {{ calcStats.dpaplasma }}
                <br/>Total damage for charged shots: {{ calcStats.dpacharged }}</span>
            <span v-if="calcStats.dpaplasma" class="inactiveStat">
				<i>Theoretical</i> total damage available with initial ammunition.
			</span>

            <!--todo: add numbers for weakspot damage to all stats-->
            <span v-if="calcStats.visible" class="text-white text-lg block my-2">Total Costs:</span>
            <p class="costList flex flex-wrap">
				<span class="costListItem flex items-center pr-2" v-if="calcStats.cost.credits > 0">
					<img src="/assets/img/20px-Credit.png" alt="Credits" title="Credits"/>
					<span>{{ calcStats.cost.credits }}</span>
				</span>
                <span class="costListItem flex items-center pr-2" v-if="calcStats.cost.bismor > 0">
					<img src="/assets/img/Bismor_icon.png" alt="Bismor" title="Bismor"/>
					<span>{{ calcStats.cost.bismor }}</span>
				</span>
                <span class="costListItem flex items-center pr-2" v-if="calcStats.cost.croppa > 0">
					<img src="/assets/img/Croppa_icon.png" alt="Croppa" title="Croppa"/>
					<span>{{ calcStats.cost.croppa }}</span>
				</span>
                <span class="costListItem flex items-center pr-2" v-if="calcStats.cost.enorPearl > 0">
					<img src="/assets/img/Enor_pearl_icon.png" alt="Enor Pearl" title="Enor Pearl"/>
					<span>{{ calcStats.cost.enorPearl }}</span>
				</span>
                <span class="costListItem flex items-center pr-2" v-if="calcStats.cost.jadiz > 0">
					<img src="/assets/img/Jadiz_icon.png" alt="Jadiz" title="Jadiz"/>
					<span>{{ calcStats.cost.jadiz }}</span>
				</span>
                <span class="costListItem flex items-center pr-2" v-if="calcStats.cost.magnite > 0">
					<img src="/assets/img/Magnite_icon.png" alt="Magnite" title="Magnite"/>
					<span>{{ calcStats.cost.magnite }}</span>
				</span>
                <span class="costListItem flex items-center pr-2" v-if="calcStats.cost.umanite > 0">
					<img src="/assets/img/Umanite_icon.png" alt="Umanite" title="Umanite"/>
					<span>{{ calcStats.cost.umanite }}</span>
				</span>
            </p>
        </div>
    </div>
</template>
<!--todo: show most cost effective upgrade in tier (most change %)-->
<script>
    import store from '../store';

    const _calculateDamage = stats => {
        let damageWords = ['Damage', 'Area Damage', 'Electric Damage', 'Direct Damage'];
        let magazineSizeWords = ['Tank Size', 'Magazine Size', 'Clip Size', 'Combined Clip Size'];
        let ammoWords = ['Max Ammo', 'Max Fuel'];
        let rateOfFireWords = ['Rate of Fire', 'Combined Rate of Fire'];
        let reloadTimeWords = ['Reload Time'];
        let pelletsWords = ['Pellets'];
        let weakPointWords = ['Weakpoint Damage Bonus'];
        let dpsStats = {};
        for (let stat of stats) {
            if (damageWords.includes(stat.name)) {
                if (dpsStats.damage) {
                    // there is a damage stat already, add the second one to it (probably area damage)
                    dpsStats.damage += parseFloat(stat.value);
                } else {
                    dpsStats.damage = parseFloat(stat.value);
                }
            } else if (magazineSizeWords.includes(stat.name) && !dpsStats.magazineSize) {
                dpsStats.magazineSize = parseFloat(stat.value);
            } else if (rateOfFireWords.includes(stat.name) && !dpsStats.rateOfFire) {
                dpsStats.rateOfFire = parseFloat(stat.value);
            } else if (reloadTimeWords.includes(stat.name) && !dpsStats.reloadTime) {
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

        let weakpointDamage = (dpsStats.damage * (1 + (dpsStats.weakPoint / 100))).toFixed(2);

        return {
            tte: (dpsStats.magazineSize / dpsStats.rateOfFire).toFixed(2),
            wpd: isNaN(weakpointDamage) ? undefined : weakpointDamage,
            dps: parseFloat(damagePerSecond).toFixed(2),
            dpm: magazineDamage,
            dpa: dpsStats.damage * (dpsStats.maxAmmo)
        };
    };

    const precisionCalc = a => {
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
            let precisionTemp = statsPrecision > upgradePrecision ? statsPrecision : upgradePrecision;
            precision = precision > precisionTemp ? precision : precisionTemp;

            if (precision > 1) {
                precision = 1;
            }

            if (upgradeElement.multiply) {
                upgradesToMultiply.push(upgradeElement);
            } else {
                // calculation here
                if (upgradeElement.subtract) {
                    modifiedValue = (parseFloat(modifiedValue) - parseFloat(upgradeElement.value)).toFixed(precision);
                } else {
                    modifiedValue = (parseFloat(modifiedValue) + parseFloat(upgradeElement.value)).toFixed(precision);
                }
            }
        }
        if (upgradesToMultiply.length > 0) {
            for (let upgradetoMultiply of upgradesToMultiply) {
                // calculation here
                modifiedValue = (parseFloat(modifiedValue) * parseFloat(upgradetoMultiply.value)).toFixed(precision);
            }
        }
        /* todo: homebrew explosive is getting too many digits :( */
        modifiedValue = (parseFloat(modifiedValue)).toFixed(precision);
        let difference = (parseFloat(modifiedValue) - parseFloat(originalValue)).toFixed(precision);
        if (stat.name === "Magazine Size") {
            // make sure magazine size is always an even number
            difference = Math.ceil(difference);
            modifiedValue = Math.floor(modifiedValue);
        }
        stat.modifier = `${difference < 0 ? '' : '+'}${difference}${stat.percent ? '%' : ''}`;
        stat.modified = true;
        stat.value = modifiedValue;
        stat.baseValue = originalValue;
        return stat;
    };

    const getModifiedStats = (baseStats, selectedUpgrades) => {
        // loop trough all selected upgrades
        let upgradesForEachStat = new Map();
        let costs = [];
        for (let upgrade of selectedUpgrades) {
            costs.push(upgrade.cost);

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
                let upgradedStat = calculateUpgradesForStat(workingStat, upgradesForStat);
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

        return {stats: stats, costs: costs};
    };

    export default {
        name: 'StatsDisplay',
        computed: {
            dataReady() {
                return store.state.loadoutCreator.dataReady;
            },
            selectedClassId: function () {
                return store.state.loadoutCreator.selectedClassId;
            },
            selectedEquipmentId: function () {
                return store.state.loadoutCreator.selectedEquipmentId;
            },
            selectedEquipmentType: function () {
                return store.state.loadoutCreator.selectedEquipmentType;
            },
            equipment: function () {
                return store.getters.getEquipmentByClassTypeId({
                    selectedClassId: this.selectedClassId,
                    selectedEquipmentId: this.selectedEquipmentId,
                    selectedEquipmentType: this.selectedEquipmentType
                });
            },
            baseStats: function () {
                return this.equipment.json_stats;
            },
            calcStats: function () {
                let visible = false;
                let mods = this.equipment.mods;
                let overclocks = this.equipment.overclocks;
                let aSelectedUpgrades = mods.reduce(
                    (array, tierArray) => {
                        array.push(...tierArray.filter(mod => mod.selected));
                        return array;
                    },
                    []
                );

                if (overclocks) {
                    for (let overclock of overclocks) {
                        if (overclock.selected) {
                            aSelectedUpgrades.push(overclock);
                        }
                    }
                }

                let results = getModifiedStats(this.baseStats, aSelectedUpgrades);
                let stats = results.stats;
                let costs = results.costs;
                // only look for temporary calc damage function if weapon, not for equipment
                let calculateDamage = this.equipment.eq_type ? undefined : store.state.missingBackendWeaponData[this.selectedEquipmentId].calculateDamage;

                let damage = calculateDamage ? calculateDamage(stats) : _calculateDamage(stats);

                let totalCost = {
                    credits: 0,
                    bismor: 0,
                    croppa: 0,
                    enorPearl: 0,
                    jadiz: 0,
                    magnite: 0,
                    umanite: 0,
                    err: 0
                };
                for (let cost of costs) {
                    totalCost.credits += cost.credits;
                    totalCost.bismor += cost.bismor;
                    totalCost.croppa += cost.croppa;
                    totalCost.enorPearl += cost.enorPearl;
                    totalCost.jadiz += cost.jadiz;
                    totalCost.magnite += cost.magnite;
                    totalCost.umanite += cost.umanite;
                    totalCost.err += cost.err;
                }

                if (this.equipment.eq_type === 'Armor' || this.equipment.eq_type === 'Support Tool') {
                    return {
                        stats: stats,
                        cost: totalCost,
                        visible: visible
                    };
                }
                return {
                    stats: stats,
                    cost: totalCost,
                    visible: visible,
                    tte: damage.tte ? damage.tte : undefined,
                    wpd: damage.wpd ? damage.wpd : undefined,
                    dps: damage.dps ? damage.dps : undefined,
                    dpb: damage.dpb ? damage.dpb : undefined,
                    dpm: damage.dpm ? damage.dpm : undefined,
                    dpa: damage.dpa ? damage.dpa : undefined,
                    ex1: damage.ex1 ? damage.ex1 : undefined,
                    dpsplasma: damage.dpsplasma ? damage.dpsplasma : undefined,
                    dpscharged: damage.dpscharged ? damage.dpscharged : undefined,
                    dpbplasma: damage.dpbplasma ? damage.dpbplasma : undefined,
                    dpbcharged: damage.dpbcharged ? damage.dpbcharged : undefined,
                    dpaplasma: damage.dpaplasma ? damage.dpaplasma : undefined,
                    dpacharged: damage.dpacharged ? damage.dpacharged : undefined
                };
            }
        }
    };
</script>

<style scoped>
    .statsDisplay {
        font-family: "Avenir", Helvetica, Arial, sans-serif;
    }

    h2 {
        /*text-transform: uppercase;*/
        /*font-size: 1rem;*/
        /*font-weight: normal;*/
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .statsDisplay {
        flex: 1;
        height: 100%;
        width: 100%;
        padding-right: 1rem;

        display: flex;
        flex-flow: column;
        align-items: center;
    }

    @media (max-width: 770px) {
        .statsDisplay {
            flex: 0 0 100%;
            order: 2;
            padding: 0;
        }
    }

    .equipmentTitle {
        font-size: 1.5rem;
        text-align: center;
        color: #fc9e00;
        margin-bottom: 0;
    }

    .equipmentSubTitle {
        text-align: center;
        color: #fffbff;
        font-size: 1rem;
        margin-top: 0;
    }

    .statsBaseContainer {
        width: 100%;
    }

    .statsContainer {
        display: flex;
        width: 100%;
    }

    .fixedWidth {
        width: 33%;
    }

    .statsValueContainer {
        width: 45%;
        display: flex;
        justify-content: flex-end;
    }

    .statsText {
        width: 55%;
        color: rgba(255, 251, 255, 1);
        line-height: 1.2rem;
    }

    .statsValue {
        color: #fc9e00;
        text-align: right;
    }

    .statsModifier {
        text-align: right;
        color: #fccc00;
    }

    .modifiedStat {
        color: #fccc00;
    }

    .inactiveStat {
        color: rgba(107, 114, 128, var(--tw-text-opacity));
    }
</style>
