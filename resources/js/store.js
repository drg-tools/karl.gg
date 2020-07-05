import Vue from 'vue';
import Vuex from 'vuex';
// imports for driller equipment
import D_E_Armor from './equipment/D_E_Armor.js';
import D_E_Satchel from './equipment/D_E_Satchel.js';
import D_E_Satchel_SVG from './assets/D_E_Satchel.js';
import D_E_Drill from './equipment/D_E_Drill.js';
import D_E_Drill_SVG from './assets/D_E_Drill.js';
import D_P1_CRSPR from './equipment/D_P1_CRSPR.js';
import D_P1_CRSPR_SVG from './assets/D_P1_CRSPR.js';
import D_P2_Cryo from './equipment/D_P2_Cryo.js';
import D_P2_Cryo_SVG from './assets/D_P2_Cryo.js';
import D_S1_Subata from './equipment/D_S1_Subata.js';
import D_S1_Subata_SVG from './assets/D_S1_Subata.js';
import D_S2_Plasma from './equipment/D_S2_Plasma.js';
import D_S2_Plasma_SVG from './assets/D_S2_Plasma.js';
// imports for engineer equipment
import E_E_Armor from './equipment/E_E_Armor.js';
import E_E_Sentry from './equipment/E_E_Sentry.js';
import E_E_Sentry_SVG from './assets/E_E_Sentry.js';
import E_E_Platform from './equipment/E_E_Platform.js';
import E_E_Platform_SVG from './assets/E_E_Platform.js';
import E_P1_Warthog from './equipment/E_P1_Warthog.js';
import E_P1_Warthog_SVG from './assets/E_P1_Warthog.js';
import E_P2_Stubby from './equipment/E_P2_Stubby.js';
import E_P2_Stubby_SVG from './assets/E_P2_Stubby.js';
import E_S1_PGL from './equipment/E_S1_PGL.js';
import E_S1_PGL_SVG from './assets/E_S1_PGL.js';
import E_S2_Breach from './equipment/E_S2_Breach.js';
import E_S2_Breach_SVG from './assets/E_S2_Breach.js';
// imports for gunner equipment
import G_E_Armor from './equipment/G_E_Armor.js';
import G_E_Shield from './equipment/G_E_Shield.js';
import G_E_Shield_SVG from './assets/G_E_Shield.js';
import G_E_Zipline from './equipment/G_E_Zipline.js';
import G_E_Zipline_SVG from './assets/G_E_Zipline.js';
import G_P1_Lead from './equipment/G_P1_Lead.js';
import G_P1_Lead_SVG from './assets/G_P1_Lead.js';
import G_P2_Thunder from './equipment/G_P2_Thunder.js';
import G_P2_Thunder_SVG from './assets/G_P2_Thunder.js';
import G_S1_Bulldog from './equipment/G_S1_Bulldog.js';
import G_S1_Bulldog_SVG from './assets/G_S1_Bulldog.js';
import G_S2_Burst from './equipment/G_S2_Burst.js';
import G_S2_Burst_SVG from './assets/G_S2_Burst.js';
// imports for scout equipment
import S_E_Armor from './equipment/S_E_Armor.js';
import S_E_Flare from './equipment/S_E_Flare.js';
import S_E_Flare_SVG from './assets/S_E_Flare.js';
import S_E_Grapling from './equipment/S_E_Grapling.js';
import S_E_Grapling_SVG from './assets/S_E_Grapling.js';
import S_P1_GK2 from './equipment/S_P1_GK2.js';
import S_P1_GK2_SVG from './assets/S_P1_GK2.js';
import S_P2_M1000 from './equipment/S_P2_M1000.js';
import S_P2_M1000_SVG from './assets/S_P2_M1000.js';
import S_S1_Jury from './equipment/S_S1_Jury.js';
import S_S1_Jury_SVG from './assets/S_S1_Jury.js';
import S_S2_Zhuk from './equipment/S_S2_Zhuk.js';
import S_S2_Zhuk_SVG from './assets/S_S2_Zhuk.js';
// imports for robot equipment
import R_P1_Bosco from './equipment/R_P1_Bosco.js';
import R_P1_Bosco_SVG from './assets/R_P1_Bosco.js';
// imports for shared equipment
import X_E_Armor_SVG from './assets/X_E_Armor.js';

// imports for modification icons
import Icon_Upgrade_Distance from './assets/mods/Icon_Upgrade_Distance.js';
import Icon_Upgrade_FireRate from './assets/mods/Icon_Upgrade_FireRate.js';
import Icon_Upgrade_Fuel from './assets/mods/Icon_Upgrade_Fuel.js';
import Class_level_icon from './assets/mods/Class_level_icon.js';
import Icon_Upgrade_Accuracy from './assets/mods/Icon_Upgrade_Accuracy.js';
import Icon_Upgrade_Aim from './assets/mods/Icon_Upgrade_Aim.js';
import Icon_Upgrade_Ammo from './assets/mods/Icon_Upgrade_Ammo.js';
import Icon_Upgrade_Angle from './assets/mods/Icon_Upgrade_Angle.js';
import Icon_Upgrade_Area from './assets/mods/Icon_Upgrade_Area.js';
import Icon_Upgrade_AreaDamage from './assets/mods/Icon_Upgrade_AreaDamage.js';
import Icon_Upgrade_ArmorBreaking from './assets/mods/Icon_Upgrade_ArmorBreaking.js';
import Icon_Upgrade_BulletPenetration from './assets/mods/Icon_Upgrade_BulletPenetration.js';
import Icon_Upgrade_Capacity from './assets/mods/Icon_Upgrade_Capacity.js';
import Icon_Upgrade_ChargeUp from './assets/mods/Icon_Upgrade_ChargeUp.js';
import Icon_Upgrade_ClipSize from './assets/mods/Icon_Upgrade_ClipSize.js';
import Icon_Upgrade_Cold from './assets/mods/Icon_Upgrade_Cold.js';
import Icon_Upgrade_DamageGeneral from './assets/mods/Icon_Upgrade_DamageGeneral.js';
import Icon_Upgrade_DefenseOne from './assets/mods/Icon_Upgrade_DefenseOne.js';
import Icon_Upgrade_Digging from './assets/mods/Icon_Upgrade_Digging.js';
import Icon_Upgrade_Duration from './assets/mods/Icon_Upgrade_Duration.js';
import Icon_Upgrade_Electricity from './assets/mods/Icon_Upgrade_Electricity.js';
import Icon_Upgrade_Explosion from './assets/mods/Icon_Upgrade_Explosion.js';
import Icon_Upgrade_Explosive from './assets/mods/Icon_Upgrade_Explosive.js';
import Icon_Upgrade_Explosive_Resistance from './assets/mods/Icon_Upgrade_Explosive_Resistance.js';
import Icon_Upgrade_FallDamageResistance from './assets/mods/Icon_Upgrade_FallDamageResistance.js';
import Icon_Upgrade_Fire_Resistance from './assets/mods/Icon_Upgrade_Fire_Resistance.js';
import Icon_Upgrade_Flare_01 from './assets/mods/Icon_Upgrade_Flare_01.js';
import Icon_Upgrade_Heat from './assets/mods/Icon_Upgrade_Heat.js';
import Icon_Upgrade_MovementSpeed from './assets/mods/Icon_Upgrade_MovementSpeed.js';
import Icon_Upgrade_PoisonResistance from './assets/mods/Icon_Upgrade_PoisonResistance.js';
import Icon_Upgrade_Poison_Resistance from './assets/mods/Icon_Upgrade_Poison_Resistance.js';
import Icon_Upgrade_ProjectileSpeed from './assets/mods/Icon_Upgrade_ProjectileSpeed.js';
import Icon_Upgrade_Recoil from './assets/mods/Icon_Upgrade_Recoil.js';
import Icon_Upgrade_Resistance from './assets/mods/Icon_Upgrade_Resistance.js';
import Icon_Upgrade_Revive from './assets/mods/Icon_Upgrade_Revive.js';
import Icon_Upgrade_Ricoshet from './assets/mods/Icon_Upgrade_Ricoshet.js';
import Icon_Upgrade_ScareEnemies from './assets/mods/Icon_Upgrade_ScareEnemies.js';
import Icon_Upgrade_Shot from './assets/mods/Icon_Upgrade_Shot.js';
import Icon_Upgrade_Shotgun_Pellet from './assets/mods/Icon_Upgrade_Shotgun_Pellet.js';
import Icon_Upgrade_Speed from './assets/mods/Icon_Upgrade_Speed.js';
import Icon_Upgrade_SpeedUp from './assets/mods/Icon_Upgrade_SpeedUp.js';
import Icon_Upgrade_Sticky from './assets/mods/Icon_Upgrade_Sticky.js';
import Icon_Upgrade_Stun from './assets/mods/Icon_Upgrade_Stun.js';
import Icon_Upgrade_TemperatureCoolDown from './assets/mods/Icon_Upgrade_TemperatureCoolDown.js';
import Icon_Upgrade_Weakspot from './assets/mods/Icon_Upgrade_Weakspot.js';
import Icon_Upgrade_Bosco_Rocket_Upgrade from './assets/mods/Icon_Upgrade_Bosco_Rocket_Upgrade.js';
import Icon_Upgrade_PlusOne from './assets/mods/Icon_Upgrade_PlusOne.js';
import Icon_Upgrade_Light_ExtraLife from './assets/mods/Icon_Upgrade_Light_ExtraLife.js';
import Icon_Upgrade_Light_Intensity from './assets/mods/Icon_Upgrade_Light_Intensity.js';
import Icon_Upgrade_Special from './assets/mods/Icon_Upgrade_Special.js';
import Icon_Upgrade_StrongerTurret from './assets/mods/Icon_Upgrade_StrongerTurret.js';
import Icon_Upgrade_TwoTurrets from './assets/mods/Icon_Upgrade_TwoTurrets.js';
import Icon_Overclock_ChangeOfHigherDamage from './assets/mods/Icon_Overclock_ChangeOfHigherDamage.js';
import Icon_Overclock_ExplosionJump from './assets/mods/Icon_Overclock_ExplosionJump.js';
import Icon_Overclock_ForthAndBack_Linecutter from './assets/mods/Icon_Overclock_ForthAndBack_Linecutter.js';
import Icon_Overclock_Neuro from './assets/mods/Icon_Overclock_Neuro.js';
import Icon_Overclock_ShotgunJump from './assets/mods/Icon_Overclock_ShotgunJump.js';
import Icon_Overclock_Slowdown from './assets/mods/Icon_Overclock_Slowdown.js';
import Icon_Overclock_SmallBullets from './assets/mods/Icon_Overclock_SmallBullets.js';
import Icon_Overclock_Special_Magazine from './assets/mods/Icon_Overclock_Special_Magazine.js';
import Icon_Overclock_Spinning_Linecutter from './assets/mods/Icon_Overclock_Spinning_Linecutter.js';

Vue.use(Vuex);

const idToChar = ['A', 'B', 'C'];
const charToId = {A: 0, B: 1, C: 2};

export default new Vuex.Store({
    state: {
        loadedFromLink: false,
        loadedOverclockFromLink: false,
        loadout: {
            selectedClassId: 'D',
            selectedEquipmentId: 'P1',
            chosenPrimaryId: 'P1',
            chosenSecondaryId: 'S1',
            P1: [],
            P2: [],
            S1: [],
            S2: [],
            E1: [],
            E2: [],
            E3: []
        },
        selected: {
            class: 'D',
            equipment: 'P1',
            slot1: 0,
            slot2: 0,
            slot3: 0,
            slot4: 0,
            slot5: 0,
            overclock: 0
        },
        dataParts: {},
        hovered: {},
        testJS: D_P1_CRSPR_SVG,
        icons: {
            equipment: {
                X_E_Armor: X_E_Armor_SVG,
                D_E_Satchel: D_E_Satchel_SVG,
                D_E_Drill: D_E_Drill_SVG,
                D_P1_CRSPR: D_P1_CRSPR_SVG,
                D_P2_Cryo: D_P2_Cryo_SVG,
                D_S1_Subata: D_S1_Subata_SVG,
                D_S2_Plasma: D_S2_Plasma_SVG,
                E_E_Sentry: E_E_Sentry_SVG,
                E_E_Platform: E_E_Platform_SVG,
                E_P1_Warthog: E_P1_Warthog_SVG,
                E_P2_Stubby: E_P2_Stubby_SVG,
                E_S1_PGL: E_S1_PGL_SVG,
                E_S2_Breach: E_S2_Breach_SVG,
                G_E_Shield: G_E_Shield_SVG,
                G_E_Zipline: G_E_Zipline_SVG,
                G_P1_Lead: G_P1_Lead_SVG,
                G_P2_Thunder: G_P2_Thunder_SVG,
                G_S1_Bulldog: G_S1_Bulldog_SVG,
                G_S2_Burst: G_S2_Burst_SVG,
                S_E_Flare: S_E_Flare_SVG,
                S_E_Grapling: S_E_Grapling_SVG,
                S_P1_GK2: S_P1_GK2_SVG,
                S_P2_M1000: S_P2_M1000_SVG,
                S_S1_Jury: S_S1_Jury_SVG,
                S_S2_Zhuk: S_S2_Zhuk_SVG,
                R_P1_Bosco: R_P1_Bosco_SVG
            },
            mods: {
                Icon_Upgrade_Distance: Icon_Upgrade_Distance,
                Icon_Upgrade_FireRate: Icon_Upgrade_FireRate,
                Icon_Upgrade_Fuel: Icon_Upgrade_Fuel,
                Class_level_icon: Class_level_icon,
                Icon_Upgrade_Accuracy: Icon_Upgrade_Accuracy,
                Icon_Upgrade_Aim: Icon_Upgrade_Aim,
                Icon_Upgrade_Ammo: Icon_Upgrade_Ammo,
                Icon_Upgrade_Angle: Icon_Upgrade_Angle,
                Icon_Upgrade_Area: Icon_Upgrade_Area,
                Icon_Upgrade_AreaDamage: Icon_Upgrade_AreaDamage,
                Icon_Upgrade_ArmorBreaking: Icon_Upgrade_ArmorBreaking,
                Icon_Upgrade_BulletPenetration: Icon_Upgrade_BulletPenetration,
                Icon_Upgrade_Capacity: Icon_Upgrade_Capacity,
                Icon_Upgrade_ChargeUp: Icon_Upgrade_ChargeUp,
                Icon_Upgrade_ClipSize: Icon_Upgrade_ClipSize,
                Icon_Upgrade_Cold: Icon_Upgrade_Cold,
                Icon_Upgrade_DamageGeneral: Icon_Upgrade_DamageGeneral,
                Icon_Upgrade_DefenseOne: Icon_Upgrade_DefenseOne,
                Icon_Upgrade_Digging: Icon_Upgrade_Digging,
                Icon_Upgrade_Duration: Icon_Upgrade_Duration,
                Icon_Upgrade_Electricity: Icon_Upgrade_Electricity,
                Icon_Upgrade_Explosion: Icon_Upgrade_Explosion,
                Icon_Upgrade_Explosive: Icon_Upgrade_Explosive,
                Icon_Upgrade_Explosive_Resistance: Icon_Upgrade_Explosive_Resistance,
                Icon_Upgrade_FallDamageResistance: Icon_Upgrade_FallDamageResistance,
                Icon_Upgrade_Fire_Resistance: Icon_Upgrade_Fire_Resistance,
                Icon_Upgrade_Flare_01: Icon_Upgrade_Flare_01,
                Icon_Upgrade_Heat: Icon_Upgrade_Heat,
                Icon_Upgrade_MovementSpeed: Icon_Upgrade_MovementSpeed,
                Icon_Upgrade_PoisonResistance: Icon_Upgrade_PoisonResistance,
                Icon_Upgrade_Poison_Resistance: Icon_Upgrade_Poison_Resistance,
                Icon_Upgrade_ProjectileSpeed: Icon_Upgrade_ProjectileSpeed,
                Icon_Upgrade_Recoil: Icon_Upgrade_Recoil,
                Icon_Upgrade_Resistance: Icon_Upgrade_Resistance,
                Icon_Upgrade_Revive: Icon_Upgrade_Revive,
                Icon_Upgrade_Ricoshet: Icon_Upgrade_Ricoshet,
                Icon_Upgrade_ScareEnemies: Icon_Upgrade_ScareEnemies,
                Icon_Upgrade_Shot: Icon_Upgrade_Shot,
                Icon_Upgrade_Shotgun_Pellet: Icon_Upgrade_Shotgun_Pellet,
                Icon_Upgrade_Speed: Icon_Upgrade_Speed,
                Icon_Upgrade_SpeedUp: Icon_Upgrade_SpeedUp,
                Icon_Upgrade_Sticky: Icon_Upgrade_Sticky,
                Icon_Upgrade_Stun: Icon_Upgrade_Stun,
                Icon_Upgrade_TemperatureCoolDown: Icon_Upgrade_TemperatureCoolDown,
                Icon_Upgrade_Weakspot: Icon_Upgrade_Weakspot,
                Icon_Upgrade_Bosco_Rocket_Upgrade: Icon_Upgrade_Bosco_Rocket_Upgrade,
                Icon_Upgrade_PlusOne: Icon_Upgrade_PlusOne,
                Icon_Upgrade_Light_ExtraLife: Icon_Upgrade_Light_ExtraLife,
                Icon_Upgrade_Light_Intensity: Icon_Upgrade_Light_Intensity,
                Icon_Upgrade_Special: Icon_Upgrade_Special,
                Icon_Upgrade_StrongerTurret: Icon_Upgrade_StrongerTurret,
                Icon_Upgrade_TwoTurrets: Icon_Upgrade_TwoTurrets,
                Icon_Overclock_ChangeOfHigherDamage: Icon_Overclock_ChangeOfHigherDamage,
                Icon_Overclock_ExplosionJump: Icon_Overclock_ExplosionJump,
                Icon_Overclock_ForthAndBack_Linecutter: Icon_Overclock_ForthAndBack_Linecutter,
                Icon_Overclock_Neuro: Icon_Overclock_Neuro,
                Icon_Overclock_ShotgunJump: Icon_Overclock_ShotgunJump,
                Icon_Overclock_Slowdown: Icon_Overclock_Slowdown,
                Icon_Overclock_SmallBullets: Icon_Overclock_SmallBullets,
                Icon_Overclock_Special_Magazine: Icon_Overclock_Special_Magazine,
                Icon_Overclock_Spinning_Linecutter: Icon_Overclock_Spinning_Linecutter
            }
        },
        tree: {
            D: {
                P1: D_P1_CRSPR,
                P2: D_P2_Cryo,
                S1: D_S1_Subata,
                S2: D_S2_Plasma,
                E1: D_E_Satchel,
                E2: D_E_Drill,
                E3: D_E_Armor
            },
            E: {
                P1: E_P1_Warthog,
                P2: E_P2_Stubby,
                S1: E_S1_PGL,
                S2: E_S2_Breach,
                E1: E_E_Sentry,
                E2: E_E_Platform,
                E3: E_E_Armor
            },
            G: {
                P1: G_P1_Lead,
                P2: G_P2_Thunder,
                S1: G_S1_Bulldog,
                S2: G_S2_Burst,
                E1: G_E_Shield,
                E2: G_E_Zipline,
                E3: G_E_Armor
            },
            S: {
                P1: S_P1_GK2,
                P2: S_P2_M1000,
                S1: S_S1_Jury,
                S2: S_S2_Zhuk,
                E1: S_E_Flare,
                E2: S_E_Grapling,
                E3: S_E_Armor
            },
            R: {
                P1: R_P1_Bosco
            }
        }
    },
    mutations: {
        /* todo: new select for mods and ocs */
        selectLoadoutClass: (state, indices) => {
            state.loadout.selectedClassId = indices.classId;
        },
        selectLoadoutEquipment: (state, indices) => {
            state.loadout.selectedEquipmentId = indices.equipmentId;
            if (indices.equipmentId.includes('P')) {
                state.loadout.chosenPrimaryId = indices.equipmentId;
            } else if (indices.equipmentId.includes('S')) {
                state.loadout.chosenSecondaryId = indices.equipmentId;
            }
        },
        selectLoadoutMods: (state, indices) => {
            console.log('new mod selection', indices);
            state.loadout[indices.equipmentId][indices.tierId] = idToChar[indices.chosenMod];
            console.log('equipment', state.loadout);
        },
        selectLoadoutOverclocks: (state, indices) => {
            console.log('new overclock selection', indices);
            state.loadout[indices.equipmentId][5] = indices.chosenOverclock;
            console.log('equipment', state.loadout);
        },
        /* :todo */

        /* old store mutations */
        selectClass: (state, indizes) => {
            state.selected.class = indizes.classID;
            let selectedEquipmentId = state.selected.equipment;
            if (indizes.classID === 'R') {
                // select bosco
                state.selected.equipment = 'P1';
                state.tree[indizes.classID]['P1'].selected = true;
                return;
            }

            for (let equipment in state.tree[indizes.classID]) {
                state.tree[indizes.classID][equipment].selected = equipment === selectedEquipmentId;
            }
        },
        selectEquipment: (state, indizes) => {
            state.selected.equipment = indizes.equipID;
            state.tree[indizes.classID][indizes.equipID].selected = true;
        },
        deSelectOtherEquipments: (state, indizes) => {
            for (let equipment in state.tree[indizes.classID]) {
                if (equipment !== indizes.equipID) {
                    state.tree[indizes.classID][equipment].selected = false;
                }
            }
        },
        selectModification: (state, indizes) => {
            // keep track in url
            if (!state.dataParts[indizes.classID]) {
                state.dataParts[indizes.classID] = {};
            }
            if (!state.dataParts[indizes.classID][indizes.equipID]) {
                state.dataParts[indizes.classID][indizes.equipID] = [];
            }
            state.dataParts[indizes.classID][indizes.equipID][indizes.tierID] = indizes.modID;

            state.tree[indizes.classID][indizes.equipID].modified = true;
            // set state
            if (indizes.tierID === 'overclock') {
                state.dataParts[indizes.classID][indizes.equipID][5] = indizes.modID;
                return (state.tree[indizes.classID][indizes.equipID].overclocks[indizes.modID].selected = true);
            } else {
                return (state.tree[indizes.classID][indizes.equipID].mods[indizes.tierID][indizes.modID].selected = true);
            }
        },
        deSelectOtherModifications: (state, indizes) => {
            if (indizes.tierID === 'overclock') {
                let tierOfModification = state.tree[indizes.classID][indizes.equipID].overclocks;
                let modIdClicked = parseInt(indizes.modID);
                for (let mod in tierOfModification) {
                    let modIdInLoop = parseInt(mod);
                    if (modIdInLoop !== modIdClicked) {
                        state.tree[indizes.classID][indizes.equipID].overclocks[modIdInLoop].selected = false;
                    }
                }
            } else {
                let tierOfModification = state.tree[indizes.classID][indizes.equipID].mods[indizes.tierID];
                let modIdClicked = parseInt(indizes.modID);
                for (let mod in tierOfModification) {
                    let modIdInLoop = parseInt(mod);
                    if (modIdInLoop !== modIdClicked) {
                        state.tree[indizes.classID][indizes.equipID].mods[indizes.tierID][modIdInLoop].selected = false;
                    }
                }
            }
        },
        deSelectAllModifications: (state, indizes) => {
            if (indizes.tierID === 'overclock') {
                // keep track in url
                state.dataParts[indizes.classID][indizes.equipID][indizes.tierID] = undefined;

                // set state
                let tierOfModification = state.tree[indizes.classID][indizes.equipID].overclocks;
                for (let mod in tierOfModification) {
                    let modIdInLoop = parseInt(mod);
                    state.tree[indizes.classID][indizes.equipID].overclocks[modIdInLoop].selected = false;
                }
            } else {
                // keep track in url
                state.dataParts[indizes.classID][indizes.equipID][indizes.tierID] = undefined;

                // set state
                let tierOfModification = state.tree[indizes.classID][indizes.equipID].mods[indizes.tierID];
                for (let mod in tierOfModification) {
                    let modIdInLoop = parseInt(mod);
                    state.tree[indizes.classID][indizes.equipID].mods[indizes.tierID][modIdInLoop].selected = false;
                }
            }

        },
        addToHovered: (state, indizes) => {
            if (indizes.tierID === 'overclock') {
                state.hovered = state.tree[indizes.classID][indizes.equipID].overclocks[indizes.modID];
            } else {
                state.hovered = state.tree[indizes.classID][indizes.equipID].mods[indizes.tierID][indizes.modID];
            }

            let hoveredStatKey = Object.keys(state.hovered.stats)[0];
            let hoveredStat = state.hovered.stats[hoveredStatKey];
            let baseStat = state.tree[indizes.classID][indizes.equipID].baseStats[hoveredStatKey];
            let increase = 0;
            if (baseStat.value !== 0) {
                increase = (hoveredStat.value / baseStat.value) * 100;
            }
            state.hovered.increase = increase;
        },

        loadFromLink: (state, data) => {
            state.dataParts = data;
            state.loadedFromLink = true;

            for (let [classId, equipments] of Object.entries(data)) {
                for (let [equipmentId, mods] of Object.entries(equipments)) {
                    state.tree[classId][equipmentId].modified = true;
                    for (let tierId in mods) {
                        if (parseInt(mods[tierId]) >= 0) {
                            if (state.tree[classId][equipmentId].mods[tierId]) {
                                state.tree[classId][equipmentId].mods[tierId][mods[tierId]].selected = true;
                            } else {
                                state.tree[classId][equipmentId].overclocks[mods[tierId]].selected = true;
                                state.selected.overclock = mods[tierId];
                                state.loadedOverclockFromLink = true;
                            }
                        } else if (mods[tierId] === 'focus') {
                            state.selected.class = classId;
                            state.selected.equipment = equipmentId;
                            for (let equipment in state.tree[classId]) {
                                state.tree[classId][equipment].selected = equipment === equipmentId;
                            }
                            // focus
                        }
                    }
                }
            }
        }
    },
    actions: {}
});
