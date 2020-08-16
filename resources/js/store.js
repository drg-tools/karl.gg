import Vue from 'vue';
import Vuex from 'vuex';
import moment from 'moment';

Vue.use(Vuex);

const idToChar = ['A', 'B', 'C'];
const charToId = {A: 0, B: 1, C: 2};
const characterIdToChar = ['', 'E', 'S', 'D', 'G'];
const charToCharacterId = {E: 1, S: 2, D: 3, G: 4};

/* todo: put helper functions into their own module */
const groupByEquipment = (mods, overclocks, equipment_mods, state) => {
    let primaryWeapons = [];
    let secondaryWeapons = [];
    let equipments = [];
    for (let element of [...mods, ...overclocks]) {
        if (element.gun.character_slot === 1) {
            let primaryWeaponIndex = primaryWeapons.findIndex(weapon => weapon.id === element.gun.id);
            if (primaryWeaponIndex < 0) {
                let length = primaryWeapons.push({
                    name: element.gun.name,
                    id: element.gun.id,
                    icon: state.missingBackendWeaponData[element.gun.id].icon,
                    mods: [],
                    overclocks: [],
                    modString: []
                });
                primaryWeaponIndex = length - 1;
            }
            delete element.gun;
            if (element.mod_name) {
                primaryWeapons[primaryWeaponIndex].mods.push(element);
                primaryWeapons[primaryWeaponIndex].modString[element.mod_tier - 1] = element.mod_index;
            } else if (element.overclock_name) {
                primaryWeapons[primaryWeaponIndex].overclocks.push(element);
                primaryWeapons[primaryWeaponIndex].modString[5] = element.overclock_index;
            }
        } else if (element.gun.character_slot === 2) {
            let secondaryWeaponIndex = secondaryWeapons.findIndex(weapon => weapon.id === element.gun.id);
            if (secondaryWeaponIndex < 0) {
                let length = secondaryWeapons.push({
                    name: element.gun.name,
                    id: element.gun.id,
                    icon: state.missingBackendWeaponData[element.gun.id].icon,
                    mods: [],
                    overclocks: [],
                    modString: []
                });
                secondaryWeaponIndex = length - 1;
            }
            delete element.gun;
            if (element.mod_name) {
                secondaryWeapons[secondaryWeaponIndex].mods.push(element);
                secondaryWeapons[secondaryWeaponIndex].modString[element.mod_tier - 1] = element.mod_index;
            } else if (element.overclock_name) {
                secondaryWeapons[secondaryWeaponIndex].overclocks.push(element);
                secondaryWeapons[secondaryWeaponIndex].modString[5] = element.overclock_index;
            }
        }
    }
    for (let equipment_mod of equipment_mods) {
        let equipmentIndex = equipments.findIndex(equipment => equipment.id === equipment_mod.equipment.id);
        if (equipmentIndex < 0) {
            let length = equipments.push({
                name: equipment_mod.equipment.name,
                id: equipment_mod.equipment.id,
                icon: equipment_mod.equipment.icon,
                mods: [],
                overclocks: [],
                modString: []
            });
            equipmentIndex = length - 1;
        }
        delete equipment_mod.gun;
        if (equipment_mod.mod_name) {
            equipments[equipmentIndex].mods.push(equipment_mod);
            equipments[equipmentIndex].modString[equipment_mod.mod_tier - 1] = equipment_mod.mod_index;
        }
    }

    return {primaryWeapons: primaryWeapons, secondaryWeapons: secondaryWeapons, equipments: equipments};
};
const transformMods = (items) => {
    for (let item of items) {
        // rename equipment_mods
        if (item.equipment_mods) {
            item.mods = item.equipment_mods;
            delete item.equipment_mods;
        }
        if (item.overclocks) {
            item.overclocks = item.overclocks.map(overclock => {
                overclock.cost = {
                    credits: overclock.credits_cost,
                    bismor: overclock.bismor_cost,
                    croppa: overclock.croppa_cost,
                    enorPearl: overclock.enor_pearl_cost,
                    jadiz: overclock.jadiz_cost,
                    magnite: overclock.magnite_cost,
                    umanite: overclock.umanite_cost,
                    err: 0
                };
                delete overclock.credits_cost;
                delete overclock.bismor_cost;
                delete overclock.croppa_cost;
                delete overclock.enor_pearl_cost;
                delete overclock.jadiz_cost;
                delete overclock.magnite_cost;
                delete overclock.umanite_cost;
                return overclock;
            });
        }
        if (item.mods) {
            let newMods = [];
            for (let mod of item.mods) {
                mod.selected = false;
                mod.cost = {
                    credits: mod.credits_cost,
                    bismor: mod.bismor_cost,
                    croppa: mod.croppa_cost,
                    enorPearl: mod.enor_pearl_cost,
                    jadiz: mod.jadiz_cost,
                    magnite: mod.magnite_cost,
                    umanite: mod.umanite_cost,
                    err: 0
                };
                delete mod.credits_cost;
                delete mod.bismor_cost;
                delete mod.croppa_cost;
                delete mod.enor_pearl_cost;
                delete mod.jadiz_cost;
                delete mod.magnite_cost;
                delete mod.umanite_cost;

                if (!newMods[mod.mod_tier - 1]) {
                    newMods[mod.mod_tier - 1] = [];
                }
                newMods[mod.mod_tier - 1][charToId[mod.mod_index]] = mod;
            }
            item.mods = newMods;
        }
    }
    return items;
};

const transformLoadouts = (loadouts, state) => {
    return loadouts.map(loadout => {
        let dummyWeapons = {
            D: {
                primaryId: '9',
                secondaryId: '11'
            },
            E: {
                primaryId: '1',
                secondaryId: '3'
            },
            G: {
                primaryId: '13',
                secondaryId: '15'
            },
            S: {
                primaryId: '6',
                secondaryId: '7'
            }
        };

        let charId = loadout.character ? loadout.character.id : loadout.character_id;

        let primaries = loadout.mods ? loadout.mods.find(mod => mod.gun.character_slot === 1) : undefined;
        let secondaries = loadout.mods ? loadout.mods.find(mod => mod.gun.character_slot === 2) : undefined;
        let primaryId = primaries ? primaries.gun.id : dummyWeapons[characterIdToChar[charId]].primaryId;
        let secondaryId = secondaries ? secondaries.gun.id : dummyWeapons[characterIdToChar[charId]].secondaryId;

        return {
            loadoutId: loadout.id,
            name: loadout.name,
            author: loadout.creator ? loadout.creator.name : 'Anonymous',
            classId: characterIdToChar[charId],
            votes: loadout.votes,
            primary: state.missingBackendWeaponData[primaryId].icon,
            secondary: state.missingBackendWeaponData[secondaryId].icon,
            lastUpdate: moment(loadout.updated_at).format('YYYY-MM-DD')
        };
    });
};

export default new Vuex.Store({
    state: {
        loadoutCreator: {
            selectedClassId: 'D',
            selectedEquipmentType: 'primaryWeapons',
            selectedEquipmentId: '9',
            chosenPrimaryId: '9',
            chosenSecondaryId: '11',
            modSelections: {
                primaryWeapons: [],
                secondaryWeapons: [],
                equipments: []
            },
            dataReady: false,
            baseData: {}
        },
        popularDataReady: false,
        popularLoadouts: [],
        browseDataReady: false,
        browseLoadouts: [],
        loadoutDetailDataReady: false,
        loadoutDetails: [],
        myLoadoutsDataReady: false,
        myLoadouts: [],
        /* todo: this is temporary, until backend provides icon and stats calculation */
        missingBackendWeaponData: [
            {
                'id': 0,
                'name': 'dummy'
            },
            {
                'id': '1',
                'name': '"Warthog" Auto 210',
                'icon': 'E_P1_Warthog',
                calculateDamage: (stats) => {
                    let damagePerSecond;
                    let damagePerBullet;
                    let totalDamage;
                    let magazineDamage;
                    let timeToEmpty;
                    let damageTime;
                    let dpsStats = {};
                    for (let stat of stats) {
                        if (stat.name === 'Damage') {
                            dpsStats.damage = parseFloat(stat.value);
                        } else if (stat.name === 'Rate of Fire') {
                            dpsStats.rateOfFire = parseFloat(stat.value);
                        } else if (stat.name === 'Reload Time') {
                            dpsStats.reloadTime = parseFloat(stat.value);
                        } else if (stat.name === 'Max Ammo') {
                            dpsStats.maxAmmo = parseFloat(stat.value);
                        } else if (stat.name === 'Magazine Size') {
                            dpsStats.magazineSize = parseFloat(stat.value);
                        } else if (stat.name === 'Pellets') {
                            dpsStats.pellets = parseFloat(stat.value);
                        }
                    }

                    dpsStats.maxAmmo = dpsStats.maxAmmo + dpsStats.magazineSize;
                    timeToEmpty = dpsStats.magazineSize / dpsStats.rateOfFire;
                    damageTime = timeToEmpty + dpsStats.reloadTime;

                    damagePerBullet = parseFloat(dpsStats.damage * dpsStats.pellets).toFixed(0);
                    magazineDamage = parseFloat(damagePerBullet * dpsStats.magazineSize).toFixed(0);
                    damagePerSecond = parseFloat(magazineDamage / damageTime).toFixed(2);
                    totalDamage = parseFloat(damagePerBullet * dpsStats.maxAmmo).toFixed(0);

                    return {
                        tte: parseFloat(timeToEmpty).toFixed(2),
                        dps: damagePerSecond,
                        dpb: damagePerBullet,
                        dpm: magazineDamage,
                        dpa: totalDamage
                    };
                }
            },
            {
                'id': '2',
                'name': '"Stubby" Voltaic SMG',
                'icon': 'E_P2_Stubby'
            },
            {
                'id': '3',
                'name': 'Deepcore 40MM PGL',
                'icon': 'E_S1_PGL',
                calculateDamage: (stats) => {
                    let directDamagePerSecond;
                    let areaDamagePerSecond;
                    let damagePerSecond;
                    let directDamagePerBullet;
                    let areaDamagePerBullet;
                    let damagePerBullet;
                    let directDamagePerMagazine;
                    let areaDamagePerMagazine;
                    let damagePerMagazine;
                    let totalDirectDamage;
                    let totalAreaDamage;
                    let totalDamage;
                    let dpsStats = {};
                    for (let stat of stats) {
                        if (stat.name === 'Direct Damage') {
                            dpsStats.directDamage = parseFloat(stat.value);
                        } else if (stat.name === 'Area Damage') {
                            dpsStats.areaDamage = parseFloat(stat.value);
                        } else if (stat.name === 'Clip Size') {
                            dpsStats.magazineSize = parseFloat(stat.value);
                        } else if (stat.name === 'Rate of Fire') {
                            dpsStats.rateOfFire = parseFloat(stat.value);
                        } else if (stat.name === 'Reload Time') {
                            dpsStats.reloadTime = parseFloat(stat.value);
                        } else if (stat.name === 'Max Ammo') {
                            dpsStats.maxAmmo = parseFloat(stat.value);
                        }
                    }
                    dpsStats.damage = dpsStats.directDamage + dpsStats.areaDamage;

                    let timeToEmpty = dpsStats.magazineSize / dpsStats.rateOfFire;
                    let damageTime = timeToEmpty + dpsStats.reloadTime;

                    directDamagePerMagazine = dpsStats.directDamage * dpsStats.magazineSize;
                    areaDamagePerMagazine = dpsStats.areaDamage * dpsStats.magazineSize;
                    damagePerMagazine = dpsStats.damage * dpsStats.magazineSize;

                    directDamagePerSecond = parseFloat(directDamagePerMagazine / damageTime).toFixed(2);
                    areaDamagePerSecond = parseFloat(areaDamagePerMagazine / damageTime).toFixed(2);
                    damagePerSecond = parseFloat(damagePerMagazine / damageTime).toFixed(2);

                    directDamagePerBullet = dpsStats.directDamage;
                    areaDamagePerBullet = dpsStats.areaDamage;
                    damagePerBullet = dpsStats.damage;

                    totalDirectDamage = dpsStats.directDamage * dpsStats.maxAmmo;
                    totalAreaDamage = dpsStats.areaDamage * dpsStats.maxAmmo;
                    totalDamage = dpsStats.damage * dpsStats.maxAmmo;

                    return {
                        dps: `${damagePerSecond} (Direct: ${directDamagePerSecond} / Area: ${areaDamagePerSecond})`, // damage per second
                        dpb: `${damagePerBullet} (Direct: ${directDamagePerBullet} / Area: ${areaDamagePerBullet})`, // damage per bullet
                        dpa: `${totalDamage} (Direct: ${totalDirectDamage} / Area: ${totalAreaDamage})` // total damage available
                    };
                }
            },
            {
                'id': '4',
                'name': 'Breach Cutter',
                'icon': 'E_S2_Breach',
                calculateDamage: (stats) => {
                    // nothing :(
                    return {};
                }
            },
            {
                'id': '5',
                'name': 'Deepcore GK2',
                'icon': 'S_P1_GK2'
            },
            {
                'id': '6',
                'name': 'M1000 Classic',
                'icon': 'S_P2_M1000'
            },
            {
                'id': '7',
                'name': 'Jury-Rigged Boomstick',
                'icon': 'S_S1_Jury',
                calculateDamage: (stats) => {
                    let damagePerSecond;
                    let damagePerBullet;
                    let totalDamage;
                    let magazineDamage;
                    let timeToEmpty;
                    let damageTime;
                    let dpsStats = {};
                    for (let stat of stats) {
                        if (stat.name === 'Damage') {
                            dpsStats.damage = parseFloat(stat.value);
                        } else if (stat.name === 'Rate of Fire') {
                            dpsStats.rateOfFire = parseFloat(stat.value);
                        } else if (stat.name === 'Reload Time') {
                            dpsStats.reloadTime = parseFloat(stat.value);
                        } else if (stat.name === 'Max Ammo') {
                            dpsStats.maxAmmo = parseFloat(stat.value);
                        } else if (stat.name === 'Double Barrel' && stat.value === '1') {
                            dpsStats.doubleBarrel = true;
                        } else if (stat.name === 'Magazine Size') {
                            dpsStats.magazineSize = parseFloat(stat.value);
                        } else if (stat.name === 'Pellets') {
                            dpsStats.pellets = parseFloat(stat.value);
                        }
                    }

                    dpsStats.maxAmmo = dpsStats.maxAmmo + dpsStats.magazineSize;

                    dpsStats.maxAmmo = dpsStats.maxAmmo + dpsStats.magazineSize;
                    timeToEmpty = dpsStats.magazineSize / dpsStats.rateOfFire;
                    damageTime = timeToEmpty + dpsStats.reloadTime;

                    damagePerBullet = parseFloat(dpsStats.damage * dpsStats.pellets).toFixed(0);
                    magazineDamage = parseFloat(damagePerBullet * dpsStats.magazineSize).toFixed(0);
                    damagePerSecond = parseFloat(magazineDamage / damageTime).toFixed(2);
                    totalDamage = parseFloat(damagePerBullet * dpsStats.maxAmmo).toFixed(0);

                    if (dpsStats.doubleBarrel) {
                        return {
                            dps: parseFloat(damagePerSecond * 2).toFixed(2),
                            dpb: damagePerBullet * 2,
                            dpm: magazineDamage,
                            dpa: totalDamage
                        };
                    } else {
                        return {
                            dps: parseFloat(damagePerSecond).toFixed(2),
                            dpb: damagePerBullet,
                            dpm: magazineDamage,
                            dpa: totalDamage
                        };
                    }
                }
            },
            {
                'id': '8',
                'name': 'Zhukov NUK17',
                'icon': 'S_S2_Zhuk',
                calculateDamage: (stats) => {
                    let damagePerSecond;
                    let damagePerBullet;
                    let damagePerMagazine;
                    let totalDamage;
                    let dpsStats = {};
                    for (let stat of stats) {
                        if (stat.name === 'Damage') {
                            dpsStats.damage = parseFloat(stat.value);
                        } else if (stat.name === 'Combined Clip Size') {
                            dpsStats.magazineSize = parseFloat(stat.value);
                        } else if (stat.name === 'Combined Rate of Fire') {
                            dpsStats.rateOfFire = parseFloat(stat.value);
                        } else if (stat.name === 'Reload Time') {
                            dpsStats.reloadTime = parseFloat(stat.value);
                        } else if (stat.name === 'Max Ammo') {
                            dpsStats.maxAmmo = parseFloat(stat.value);
                        } else if (stat.name === 'Weakpoint Damage Bonus') {
                            dpsStats.weakPoint = parseFloat(stat.value);
                        }
                    }

                    dpsStats.maxAmmo = dpsStats.maxAmmo + dpsStats.magazineSize;

                    let timeToEmpty = dpsStats.magazineSize / dpsStats.rateOfFire;
                    let damageTime = timeToEmpty + dpsStats.reloadTime;

                    damagePerMagazine = parseFloat(dpsStats.damage * dpsStats.magazineSize / 2).toFixed(0);

                    damagePerSecond = parseFloat(damagePerMagazine / damageTime).toFixed(2);

                    damagePerBullet = parseFloat(dpsStats.damage).toFixed(0);

                    totalDamage = parseFloat(dpsStats.damage * dpsStats.maxAmmo / 2).toFixed(0);

                    return {
                        tte: (dpsStats.magazineSize / dpsStats.rateOfFire).toFixed(2),
                        wpd: parseFloat(dpsStats.damage * (1 + (dpsStats.weakPoint / 100))).toFixed(2), // damage on crit
                        dps: damagePerSecond, // damage per second
                        dpm: damagePerMagazine, // damage per magazine
                        dpa: totalDamage // total damage available
                    };
                }
            },
            {
                'id': '9',
                'name': 'CRSPR Flamethrower',
                'icon': 'D_P1_CRSPR',
                calculateDamage: (stats) => {
                    let damagePerSecond;
                    let totalDamage;
                    let rof;
                    let dpsStats = {};
                    for (let stat of stats) {
                        if (stat.name === 'Damage') {
                            dpsStats.damage = parseFloat(stat.value);
                        } else if (stat.name === 'Fuel Flow Rate') {
                            dpsStats.flowRate = parseFloat(stat.value);
                        } else if (stat.name === 'Max Fuel') {
                            dpsStats.maxFuel = parseFloat(stat.value);
                        } else if (stat.name === 'Tank Size') {
                            dpsStats.tankSize = parseFloat(stat.value);
                        }
                    }

                    dpsStats.maxFuel = dpsStats.maxFuel + dpsStats.tankSize;

                    rof = (dpsStats.flowRate / 100) * 6;

                    damagePerSecond = parseFloat(dpsStats.damage * rof).toFixed(2);

                    totalDamage = parseFloat(dpsStats.damage * dpsStats.maxFuel).toFixed(0);

                    return {
                        tte: (dpsStats.tankSize / rof).toFixed(2),
                        dpm: dpsStats.tankSize * dpsStats.damage,
                        dps: damagePerSecond, // damage per second
                        dpa: totalDamage // total damage available
                    };
                }
            },
            {
                'id': '10',
                'name': 'Cryo Cannon',
                'icon': 'D_P2_Cryo',
                calculateDamage: (stats) => {
                    let damagePerSecond;
                    let totalDamage;
                    let rof;
                    let dpsStats = {};
                    for (let stat of stats) {
                        if (stat.name === 'Damage') {
                            dpsStats.damage = parseFloat(stat.value);
                        } else if (stat.name === 'Flow Rate') {
                            dpsStats.flowRate = parseFloat(stat.value);
                        } else if (stat.name === 'Tank Capacity') {
                            dpsStats.tankCapacity = parseFloat(stat.value);
                        }
                    }

                    rof = (dpsStats.flowRate / 100) * 8;

                    damagePerSecond = parseFloat(dpsStats.damage * rof).toFixed(2);

                    totalDamage = parseFloat(dpsStats.damage * dpsStats.tankCapacity).toFixed(0);

                    return {
                        dps: damagePerSecond, // damage per second
                        dpa: totalDamage // total damage available
                    };
                }
            },
            {
                'id': '11',
                'name': 'Subata 120',
                'icon': 'D_S1_Subata'
            },
            {
                'id': '12',
                'name': 'Experimental Plasma Charger',
                'icon': 'D_S2_Plasma',
                calculateDamage: (stats) => {
                    let directDamagePerSecond;
                    let chargeDirectDamagePerSecond;
                    let chargeAreaDamagePerSecond;
                    let chargeDamagePerSecond;

                    let directDamagePerBullet;
                    let chargeDirectDamagePerBullet;
                    let chargeAreaDamagePerBullet;
                    let chargeDamagePerBullet;

                    let totalDirectDamage;
                    let totalChargeDirectDamage;
                    let totalChargeAreaDamage;
                    let totalChargeDamage;
                    let dpsStats = {};
                    for (let stat of stats) {
                        if (stat.name === 'Damage') {
                            dpsStats.directDamage = parseFloat(stat.value);
                        } else if (stat.name === 'Charged Damage') {
                            dpsStats.chargeDamage = parseFloat(stat.value);
                        } else if (stat.name === 'Charged Area Damage') {
                            dpsStats.chargeAreaDamage = parseFloat(stat.value);
                        } else if (stat.name === 'Charged Shot Ammo Use') {
                            dpsStats.chargeAmmoUse = parseFloat(stat.value);
                        } else if (stat.name === 'Charge Speed') {
                            dpsStats.chargeSpeed = parseFloat(stat.value);
                        } else if (stat.name === 'Rate of Fire') {
                            dpsStats.rateOfFire = parseFloat(stat.value);
                        } else if (stat.name === 'Reload Time') {
                            dpsStats.reloadTime = parseFloat(stat.value);
                        } else if (stat.name === 'Battery Capacity') {
                            dpsStats.maxAmmo = parseFloat(stat.value);
                        } else if (stat.name === 'Cooling Rate') {
                            dpsStats.coolingRate = parseFloat(stat.value);
                            dpsStats.cooldownTime = 2.5 * dpsStats.coolingRate / 100;
                        }
                    }
                    // get damage time for single and charge shots
                    let timePerChargedShot = dpsStats.chargeSpeed;
                    let chargedRateOfFire = 1 / timePerChargedShot;

                    // get single and charge shot damage
                    directDamagePerBullet = parseFloat(dpsStats.directDamage).toFixed(0);
                    chargeDirectDamagePerBullet = parseFloat(dpsStats.chargeDamage).toFixed(0);
                    chargeAreaDamagePerBullet = parseFloat(dpsStats.chargeAreaDamage).toFixed(0);
                    chargeDamagePerBullet = parseFloat(dpsStats.chargeDamage + dpsStats.chargeAreaDamage).toFixed(0);

                    directDamagePerSecond = parseFloat(directDamagePerBullet * dpsStats.rateOfFire).toFixed(2); // direct dps burst until overheated.
                    chargeDirectDamagePerSecond = parseFloat(chargeDirectDamagePerBullet * chargedRateOfFire).toFixed(2);
                    chargeAreaDamagePerSecond = parseFloat(chargeAreaDamagePerBullet * chargedRateOfFire).toFixed(2);
                    chargeDamagePerSecond = parseFloat(chargeDamagePerBullet * chargedRateOfFire).toFixed(2);

                    let chargedShotCapacity = dpsStats.maxAmmo / dpsStats.chargeAmmoUse;

                    totalDirectDamage = parseFloat(directDamagePerBullet * dpsStats.maxAmmo).toFixed(0);
                    totalChargeDirectDamage = parseFloat(chargeDirectDamagePerBullet * chargedShotCapacity).toFixed(0);
                    totalChargeAreaDamage = parseFloat(chargeAreaDamagePerBullet * chargedShotCapacity).toFixed(0);
                    totalChargeDamage = parseFloat(chargeDamagePerBullet * chargedShotCapacity).toFixed(0);
                    return {
                        dpsplasma: `${directDamagePerSecond}`, // damage per second for normal shot
                        dpscharged: `${chargeDamagePerSecond} (Direct: ${chargeDirectDamagePerSecond} / Area: ${chargeAreaDamagePerSecond})`, // damage per second for charged shot
                        dpbplasma: `${directDamagePerBullet}`, // damage per shot
                        dpbcharged: `${chargeDamagePerBullet} (Direct: ${chargeDirectDamagePerBullet} / Area: ${chargeAreaDamagePerBullet})`, // damage per charged shot
                        dpaplasma: `${totalDirectDamage}`, // total damage available for normal shots
                        dpacharged: `${totalChargeDamage} (Direct: ${totalChargeDirectDamage} / Area: ${totalChargeAreaDamage})` // total damage available for charged shots
                    };
                }
            },
            {
                'id': '13',
                'name': '"Lead Storm" Powered Minigun',
                'icon': 'G_P1_Lead',
                calculateDamage: (stats) => {
                    let damagePerSecond;
                    let totalDamage;
                    let dpsStats = {};
                    for (let stat of stats) {
                        if (stat.name === 'Damage') {
                            dpsStats.damage = parseFloat(stat.value);
                        } else if (stat.name === 'Rate of Fire') {
                            dpsStats.rateOfFire = parseFloat(stat.value);
                        } else if (stat.name === 'Max Ammo') {
                            dpsStats.maxAmmo = parseFloat(stat.value);
                        }
                    }

                    damagePerSecond = parseFloat(dpsStats.damage * dpsStats.rateOfFire / 2).toFixed(2);

                    totalDamage = parseFloat(dpsStats.damage * dpsStats.maxAmmo / 2).toFixed(0);

                    return {
                        dps: `${damagePerSecond} (Burst until overheated)`, // damage per second
                        dpa: totalDamage // total damage available
                    };
                }
            },
            {
                'id': '14',
                'name': '"Thunderhead" Heavy Autocannon',
                'icon': 'G_P2_Thunder',
                calculateDamage: (stats) => {
                    let directDamagePerSecond;
                    let areaDamagePerSecond;
                    let damagePerSecond;
                    let directDamagePerBullet;
                    let areaDamagePerBullet;
                    let damagePerBullet;
                    let directDamagePerMagazine;
                    let areaDamagePerMagazine;
                    let damagePerMagazine;
                    let totalDirectDamage;
                    let totalAreaDamage;
                    let totalDamage;
                    let dpsStats = {};
                    for (let stat of stats) {
                        if (stat.name === 'Damage') {
                            dpsStats.directDamage = parseFloat(stat.value);
                        } else if (stat.name === 'Area Damage') {
                            dpsStats.areaDamage = parseFloat(stat.value);
                        } else if (stat.name === 'Magazine Size') {
                            dpsStats.magazineSize = parseFloat(stat.value);
                        } else if (stat.name === 'Top Rate of Fire') {
                            dpsStats.rateOfFire = parseFloat(stat.value);
                        } else if (stat.name === 'Reload Time') {
                            dpsStats.reloadTime = parseFloat(stat.value);
                        } else if (stat.name === 'Max Ammo') {
                            dpsStats.maxAmmo = parseFloat(stat.value);
                        }
                    }

                    dpsStats.maxAmmo = dpsStats.maxAmmo + dpsStats.magazineSize;
                    dpsStats.damage = dpsStats.directDamage + dpsStats.areaDamage;

                    let timeToEmpty = dpsStats.magazineSize / dpsStats.rateOfFire;
                    let damageTime = timeToEmpty + dpsStats.reloadTime;

                    directDamagePerMagazine = dpsStats.directDamage * dpsStats.magazineSize;
                    areaDamagePerMagazine = dpsStats.areaDamage * dpsStats.magazineSize;
                    damagePerMagazine = dpsStats.damage * dpsStats.magazineSize;

                    directDamagePerSecond = parseFloat(directDamagePerMagazine / damageTime).toFixed(2);
                    areaDamagePerSecond = parseFloat(areaDamagePerMagazine / damageTime).toFixed(2);
                    damagePerSecond = parseFloat(damagePerMagazine / damageTime).toFixed(2);

                    directDamagePerBullet = dpsStats.directDamage;
                    areaDamagePerBullet = dpsStats.areaDamage;
                    damagePerBullet = dpsStats.damage;

                    totalDirectDamage = dpsStats.directDamage * dpsStats.maxAmmo;
                    totalAreaDamage = dpsStats.areaDamage * dpsStats.maxAmmo;
                    totalDamage = dpsStats.damage * dpsStats.maxAmmo;

                    return {
                        tte: (dpsStats.magazineSize / dpsStats.rateOfFire).toFixed(2),
                        dps: `${damagePerSecond} (Direct: ${directDamagePerSecond} / Area: ${areaDamagePerSecond})`, // damage per second
                        dpb: `${damagePerBullet} (Direct: ${directDamagePerBullet} / Area: ${areaDamagePerBullet})`, // damage per bullet
                        dpm: `${damagePerMagazine} (Direct: ${directDamagePerMagazine} / Area: ${areaDamagePerMagazine})`, // damage per magazine
                        dpa: `${totalDamage} (Direct: ${totalDirectDamage} / Area: ${totalAreaDamage})` // total damage available
                    };
                }
            },
            {
                'id': '15',
                'name': '"Bulldog" Heavy Revolver',
                'icon': 'G_S1_Bulldog'
            },
            {
                'id': '16',
                'name': 'BRT7 Burst Fire Gun',
                'icon': 'G_S2_Burst',
                calculateDamage: (stats) => {
                    let damagePerSecond;
                    let burstDamage;
                    let damagePerMagazine;
                    let totalDamage;
                    let dpsStats = {};
                    for (let stat of stats) {
                        if (stat.name === 'Damage') {
                            dpsStats.damage = parseFloat(stat.value);
                        } else if (stat.name === 'Burst Size') {
                            dpsStats.burstSize = parseFloat(stat.value);
                        } else if (stat.name === 'Burst Speed') {
                            dpsStats.burstSpeed = parseFloat(stat.value);
                        } else if (stat.name === 'Burst Damage') {
                            dpsStats.burstBonus = parseFloat(stat.value);
                        } else if (stat.name === 'Max Ammo') {
                            dpsStats.maxAmmo = parseFloat(stat.value);
                        } else if (stat.name === 'Magazine Size') {
                            dpsStats.magazineSize = parseFloat(stat.value);
                        } else if (stat.name === 'Rate of Fire') {
                            dpsStats.rateOfFire = parseFloat(stat.value);
                        } else if (stat.name === 'Reload Time') {
                            dpsStats.reloadTime = parseFloat(stat.value);
                        } else if (stat.name === 'Weakpoint Damage Bonus') {
                            dpsStats.weakPoint = parseFloat(stat.value);
                        }
                    }
                    // damage over one burst (3 or 6 bullets used)

                    dpsStats.maxAmmo = dpsStats.maxAmmo + dpsStats.magazineSize;

                    burstDamage = dpsStats.damage * dpsStats.burstSize;
                    if (dpsStats.burstBonus) {
                        burstDamage += dpsStats.burstBonus;
                    }
                    burstDamage = parseFloat(burstDamage).toFixed(0);
                    let burstMagazine = dpsStats.magazineSize / dpsStats.burstSize;
                    // rate of fire is rate of bursts per second (burst speed ignored)
                    let timeToEmpty = burstMagazine / dpsStats.rateOfFire;
                    let damageTime = timeToEmpty + dpsStats.reloadTime;
                    damagePerMagazine = parseFloat(burstDamage * burstMagazine).toFixed(0);
                    damagePerSecond = parseFloat(damagePerMagazine / damageTime).toFixed(2);
                    totalDamage = parseFloat(burstDamage * (dpsStats.maxAmmo / dpsStats.burstSize)).toFixed(0);

                    return {
                        tte: timeToEmpty.toFixed(2),
                        wpd: parseFloat(dpsStats.damage * (1 + (dpsStats.weakPoint / 100))).toFixed(2),
                        dps: damagePerSecond,
                        dpb: burstDamage,
                        dpm: damagePerMagazine,
                        dpa: totalDamage
                    };
                }
            }

        ],
        hovered: {},
    },
    getters: {
        getPrimariesByClass: state => id => {
            return state.loadoutCreator.baseData[id].primaryWeapons;
        },
        getSecondariesByClass: state => id => {
            return state.loadoutCreator.baseData[id].secondaryWeapons;
        },
        getEquipmentsByClass: state => id => {
            return state.loadoutCreator.baseData[id].equipments;
        },
        getThrowablesByClass: state => id => {
            return state.loadoutCreator.baseData[id].throwables;
        },
        getEquipmentByClassTypeId: state => indices => {
            if (indices.selectedEquipmentType === 'primaryWeapons') {
                let primaryWeapons = state.loadoutCreator.baseData[indices.selectedClassId].primaryWeapons;
                let selectedPrimaryWeapon = primaryWeapons.filter(weapon => weapon.id === indices.selectedEquipmentId);
                return selectedPrimaryWeapon[0];
            } else if (indices.selectedEquipmentType === 'secondaryWeapons') {
                let secondaryWeapons = state.loadoutCreator.baseData[indices.selectedClassId].secondaryWeapons;
                let selectedSecondaryWeapon = secondaryWeapons.filter(weapon => weapon.id === indices.selectedEquipmentId);
                return selectedSecondaryWeapon[0];
            } else if (indices.selectedEquipmentType === 'equipments') {
                let equipments = state.loadoutCreator.baseData[indices.selectedClassId].equipments;
                let selectedEquipment = equipments.filter(equipment => equipment.id === indices.selectedEquipmentId);
                return selectedEquipment[0];
            } else {
                return {};
            }
        },
        getAvailableModsByClassTypeId: state => indices => {
            //selectedClassId
            if (indices.selectedEquipmentType === 'primaryWeapons') {
                let primaryWeapons = state.loadoutCreator.baseData[indices.selectedClassId].primaryWeapons;
                let selectedPrimaryWeapon = primaryWeapons.filter(weapon => weapon.id === indices.selectedEquipmentId);
                return selectedPrimaryWeapon[0].mods;
            } else if (indices.selectedEquipmentType === 'secondaryWeapons') {
                let secondaryWeapons = state.loadoutCreator.baseData[indices.selectedClassId].secondaryWeapons;
                let selectedSecondaryWeapon = secondaryWeapons.filter(weapon => weapon.id === indices.selectedEquipmentId);
                return selectedSecondaryWeapon[0].mods;
            } else if (indices.selectedEquipmentType === 'equipments') {
                let equipments = state.loadoutCreator.baseData[indices.selectedClassId].equipments;
                let selectedEquipment = equipments.filter(equipment => equipment.id === indices.selectedEquipmentId);
                return selectedEquipment[0].mods;
            } else {
                return {};
            }
        },
        getAvailableOverclocksByClassTypeId: state => indices => {
            if (indices.selectedEquipmentType === 'primaryWeapons') {
                let primaryWeapons = state.loadoutCreator.baseData[indices.selectedClassId].primaryWeapons;
                let selectedPrimaryWeapon = primaryWeapons.filter(weapon => weapon.id === indices.selectedEquipmentId);
                return selectedPrimaryWeapon[0].overclocks;
            } else if (indices.selectedEquipmentType === 'secondaryWeapons') {
                let secondaryWeapons = state.loadoutCreator.baseData[indices.selectedClassId].secondaryWeapons;
                let selectedSecondaryWeapon = secondaryWeapons.filter(weapon => weapon.id === indices.selectedEquipmentId);
                return selectedSecondaryWeapon[0].overclocks;
            } else {
                return {};
            }
        },
        getLoadoutForUpdate: state => indices => {
            let loadoutCreator = state.loadoutCreator;
            let primaryWeaponMods = loadoutCreator.modSelections.primaryWeapons[state.loadoutCreator.chosenPrimaryId];
            let secondaryWeaponMods = loadoutCreator.modSelections.secondaryWeapons[state.loadoutCreator.chosenSecondaryId];
            let equipments = loadoutCreator.modSelections.equipments;
            let equipmentMods = [];
            for (let equipmentId in equipments) {
                equipmentMods.push(...equipments[equipmentId].modIds);
            }
            let primaryModIds = primaryWeaponMods ? primaryWeaponMods.modIds : [];
            let primaryOverclockId = primaryWeaponMods ? primaryWeaponMods.overclockId : [];
            let secondaryModIds = secondaryWeaponMods ? secondaryWeaponMods.modIds : [];
            let secondaryOverclockId = secondaryWeaponMods ? secondaryWeaponMods.overclockId : [];
            /* todo: select throwable! */
            let dummyThrowable = {
                D: 1,
                E: 4,
                G: 7,
                S: 10
            };

            let mods = [...primaryModIds, ...secondaryModIds].reduce((array, mod) => {
                if (mod !== undefined) {
                    array.push(parseInt(mod));
                }
                return array;
            }, []);
            let overclocks = [primaryOverclockId, secondaryOverclockId].reduce((array, overclock) => {
                if (overclock !== undefined && overclock.length > 0) {
                    array.push(parseInt(overclock));
                }
                return array;
            }, []);
            let equipment_mods = equipmentMods.reduce((array, mod) => {
                if (mod !== undefined) {
                    array.push(parseInt(mod));
                }
                return array;
            }, []);
            return {
                loadout_id: state.loadoutDetails.loadoutId,
                character_id: charToCharacterId[state.loadoutCreator.selectedClassId],
                mods: mods,
                overclocks: overclocks,
                equipment_mods: equipment_mods,
                throwable_id: dummyThrowable[state.loadoutCreator.selectedClassId]
            };
        }
    },
    mutations: {
        setDataReady: (state, indices) => {
            Vue.set(state.loadoutCreator, 'dataReady', indices.ready);
        },
        setPopularDataReady: (state, indices) => {
            Vue.set(state, 'popularDataReady', indices.ready);
        },
        setPopularLoadouts: (state, indices) => {
            let loadouts = transformLoadouts(indices.loadouts, state);
            Vue.set(state, 'popularLoadouts', loadouts);
        },
        setBrowseDataReady: (state, indices) => {
            Vue.set(state, 'browseDataReady', indices.ready);
        },
        setBrowseLoadouts: (state, indices) => {
            let loadouts = transformLoadouts(indices.loadouts, state);
            Vue.set(state, 'browseLoadouts', loadouts);
        },
        setMyLoadouts: (state, indices) => {
            let loadouts = transformLoadouts(indices.loadouts, state);
            Vue.set(state, 'myLoadouts', loadouts);
        },
        setMyLoadoutsDataReady: (state, indices) => {
            Vue.set(state, 'myLoadoutsDataReady', indices.ready);
        },
        setLoadoutDetailDataReady: (state, indices) => {
            Vue.set(state, 'loadoutDetailDataReady', indices.ready);
        },
        setLoadoutDetails: (state, indices) => {
            let loadoutMods = groupByEquipment(indices.loadout.mods, indices.loadout.overclocks, indices.loadout.equipment_mods, state);
            let loadout = {
                classId: characterIdToChar[indices.loadout.character.id],
                created_at: indices.loadout.created_at,
                updated_at: indices.loadout.updated_at,
                lastUpdate: moment(indices.loadout.updated_at).format('YYYY-MM-DD'),
                author: indices.loadout.creator ? indices.loadout.creator.name : 'Anonymous',
                authorId: indices.loadout.creator ? indices.loadout.creator.id : undefined,
                description: indices.loadout.description,
                loadoutId: indices.loadout.id,
                name: indices.loadout.name,
                primaryWeapons: loadoutMods.primaryWeapons,
                secondaryWeapons: loadoutMods.secondaryWeapons,
                equipments: loadoutMods.equipments,
                votes: indices.loadout.votes,
                userVoted: indices.userVoted
            };
            Vue.set(state, 'loadoutDetails', loadout);
        },
        setLoadoutVotedState: (state, indices) => {
            Vue.set(state.loadoutDetails, 'userVoted', indices.userVoted);
            Vue.set(state.loadoutDetails, 'votes', indices.newNumberOfVotes);
        },
        setLoadoutDetailModMatrix: (state, indices) => {
            let generateModMatrix = (baseMods, equipmentItem, isEquipment) => {
                let relevantBaseModsId = baseMods.findIndex(base => {
                    if (isEquipment && base.data.gun) {
                        // we're looking for equipment id but this is a gun
                        return false;
                    }
                    let baseId = base.data.gun ? base.data.gun.id : base.data.equipment.id;
                    return baseId === equipmentItem.id;
                });
                let modMatrix = [];
                let availableModsForItem = baseMods[relevantBaseModsId].data.gun
                                           ? baseMods[relevantBaseModsId].data.gun.mods
                                           : baseMods[relevantBaseModsId].data.equipment.equipment_mods;
                for (let availableMod of availableModsForItem) {
                    if (!modMatrix[availableMod.mod_tier - 1]) {
                        modMatrix[availableMod.mod_tier - 1] = [];
                    }
                    let isModInLoadout = equipmentItem.mods.find(mod => mod.mod_index === availableMod.mod_index && mod.mod_tier === availableMod.mod_tier);
                    modMatrix[availableMod.mod_tier - 1][charToId[availableMod.mod_index]] = !!isModInLoadout;
                }
                return modMatrix;

            };
            if (state.loadoutDetails.primaryWeapons[0]) {
                let primaryWeaponModMatrix = generateModMatrix(indices.baseMods, state.loadoutDetails.primaryWeapons[0]);
                Vue.set(state.loadoutDetails.primaryWeapons[0], 'modMatrix', primaryWeaponModMatrix);
            }
            if (state.loadoutDetails.secondaryWeapons[0]) {
                let secondaryWeaponModMatrix = generateModMatrix(indices.baseMods, state.loadoutDetails.secondaryWeapons[0]);
                Vue.set(state.loadoutDetails.secondaryWeapons[0], 'modMatrix', secondaryWeaponModMatrix);
            }

            for (let equipmentIndex in state.loadoutDetails.equipments) {
                let equipmentModMatrix = generateModMatrix(indices.baseMods, state.loadoutDetails.equipments[equipmentIndex], true);
                Vue.set(state.loadoutDetails.equipments[equipmentIndex], 'modMatrix', equipmentModMatrix);
            }

        },
        selectLoadoutClass: (state, indices) => {
            /* todo: Vue.set */
            state.loadoutCreator.selectedClassId = indices.classId;
            state.loadoutCreator.chosenPrimaryId = indices.chosenPrimaryId;
            state.loadoutCreator.chosenSecondaryId = indices.chosenSecondaryId;

            state.loadoutCreator.selectedEquipmentId = indices.chosenPrimaryId ? indices.chosenPrimaryId : state.loadoutCreator.baseData[indices.classId].primaryWeapons[0].id;
            state.loadoutCreator.selectedEquipmentType = 'primaryWeapons';
        },
        selectLoadoutEquipment: (state, indices) => {
            if (indices.character_slot === 1) {
                state.loadoutCreator.selectedEquipmentType = 'primaryWeapons';
            } else if (indices.character_slot === 2) {
                state.loadoutCreator.selectedEquipmentType = 'secondaryWeapons';
            } else {
                state.loadoutCreator.selectedEquipmentType = 'equipments';
            }
            state.loadoutCreator.selectedEquipmentId = indices.equipmentId;
            if (indices.character_slot === 1) {
                state.loadoutCreator.chosenPrimaryId = indices.equipmentId;
            } else if (indices.character_slot === 2) {
                state.loadoutCreator.chosenSecondaryId = indices.equipmentId;
            }
        },
        selectLoadoutMods: (state, indices) => {
            if (!state.loadoutCreator.modSelections[indices.equipmentType][indices.equipmentId]) {
                // nothing has been selected for this equipment yet, prepare empty object
                state.loadoutCreator.modSelections[indices.equipmentType][indices.equipmentId] = {
                    modString: [],
                    modIds: [],
                    overclockId: undefined
                };
            }
            state.loadoutCreator.modSelections[indices.equipmentType][indices.equipmentId].modString[indices.tierId] = idToChar[indices.chosenMod];
            state.loadoutCreator.modSelections[indices.equipmentType][indices.equipmentId].modIds[indices.tierId] = indices.chosenModId;

            let itemsForEquipmentType = state.loadoutCreator.baseData[indices.classId][indices.equipmentType];
            let selectedEquipment = itemsForEquipmentType.filter(equipment => equipment.id === indices.equipmentId);
            // de-select all
            for (let mod of selectedEquipment[0].mods[indices.tierId]) {
                Vue.set(mod, 'selected', false);
            }
            // select mod
            if (indices.chosenMod >= 0) {
                Vue.set(selectedEquipment[0].mods[indices.tierId][indices.chosenMod], 'selected', true);
            }
        },
        selectLoadoutOverclocks: (state, indices) => {
            if (!state.loadoutCreator.modSelections[indices.equipmentType][indices.equipmentId]) {
                // nothing has been selected for this equipment yet, prepare empty object
                state.loadoutCreator.modSelections[indices.equipmentType][indices.equipmentId] = {
                    modString: [],
                    modIds: [],
                    overclockId: undefined
                };
            }
            state.loadoutCreator.modSelections[indices.equipmentType][indices.equipmentId].modString[5] = indices.chosenOverclock;
            state.loadoutCreator.modSelections[indices.equipmentType][indices.equipmentId].overclockId = indices.chosenOverclockId;

            let itemsForEquipmentType = state.loadoutCreator.baseData[indices.classId][indices.equipmentType];
            let selectedEquipment = itemsForEquipmentType.filter(equipment => equipment.id === indices.equipmentId);

            // de-select all
            for (let overclock of selectedEquipment[0].overclocks) {
                Vue.set(overclock, 'selected', false);
            }

            // select overclock
            if (indices.chosenOverclock >= 0) {
                Vue.set(selectedEquipment[0].overclocks[indices.chosenOverclock], 'selected', true);
            }
        },
        setLoadoutCreatorBaseData: (state, indices) => {
            let classId = indices.classId;
            let baseData = indices.baseData;
            let primaryWeapons = baseData.guns.filter(gun => gun.character_slot === 1);
            let secondaryWeapons = baseData.guns.filter(gun => gun.character_slot === 2);
            Vue.set(state.loadoutCreator.baseData, classId, {
                primaryWeapons: transformMods(primaryWeapons),
                secondaryWeapons: transformMods(secondaryWeapons),
                equipments: transformMods(baseData.equipments),
                throwables: baseData.throwables
            });
        },
        addToHovered: (state, indices) => {
            let currentEquipment = state.loadoutCreator.baseData[indices.classId][indices.equipmentType].filter(equipment => equipment.id === indices.equipmentId)[0];
            if (indices.tierId >= 0) {
                let hoveredMod = currentEquipment.mods[indices.tierId][indices.modId];
                Vue.set(state, 'hovered', hoveredMod);
            } else {
                let hoveredOverclock = currentEquipment.overclocks[indices.overclockId];
                Vue.set(state, 'hovered', hoveredOverclock);
            }

        }
    },
    actions: {}
});
