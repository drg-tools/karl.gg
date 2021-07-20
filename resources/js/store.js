import Vue from 'vue';
import Vuex from 'vuex';
import VuexPersist from 'vuex-persist';
import IconList from './IconsList';

Vue.use(Vuex);

// Persist storage while you still browse with us
// TODO: Clear items when a new loadout is submitted
const vuexPersist = new VuexPersist({
    key: 'karl-gg',
    storage: window.sessionStorage
})


export default new Vuex.Store({
    plugins: [vuexPersist.plugin],
    state: {
        selectedClass: '', // We may need to store a user's loadout on all classes, but use selected class to know which one to save.
        loadoutName: '',
        loadoutDescription: '',
        icons: {
            primaries: {
                D_P1_CRSPR: IconList.D_P1_CRSPR_SVG,
                D_P2_Cryo: IconList.D_P2_Cryo_SVG,
                E_P1_Warthog: IconList.E_P1_Warthog_SVG,
                E_P2_Stubby: IconList.E_P2_Stubby_SVG,
                G_P1_Lead: IconList.G_P1_Lead_SVG,
                G_P2_Thunder: IconList.G_P2_Thunder_SVG,
                S_P1_GK2: IconList.S_P1_GK2_SVG,
                S_P2_M1000: IconList.S_P2_M1000_SVG,
            },
            secondaries: {
                D_S1_Subata: IconList.D_S1_Subata_SVG,
                D_S2_Plasma: IconList.D_S2_Plasma_SVG,
                E_S1_PGL: IconList.E_S1_PGL_SVG,
                E_S2_Breach: IconList.E_S2_Breach_SVG,
                G_S1_Bulldog: IconList.G_S1_Bulldog_SVG,
                G_S2_Burst: IconList.G_S2_Burst_SVG,
                S_S1_Jury: IconList.S_S1_Jury_SVG,
                S_S2_Zhuk: IconList.S_S2_Zhuk_SVG,
            },
            equipment: {
                X_E_Armor: IconList.X_E_Armor_SVG,
                D_E_Satchel: IconList.D_E_Satchel_SVG,
                D_E_Drill: IconList.D_E_Drill_SVG,
                E_E_Sentry: IconList.E_E_Sentry_SVG,
                E_E_Platform: IconList.E_E_Platform_SVG,
                G_E_Shield: IconList.G_E_Shield_SVG,
                G_E_Zipline: IconList.G_E_Zipline_SVG, 
                S_E_Flare: IconList.S_E_Flare_SVG,
                S_E_Grapling: IconList.S_E_Grapling_SVG,
                R_P1_Bosco: IconList.R_P1_Bosco_SVG
            },
            mods: {
                Icon_Upgrade_Distance: IconList.Icon_Upgrade_Distance,
                Icon_Upgrade_FireRate: IconList.Icon_Upgrade_FireRate,
                Icon_Upgrade_Fuel: IconList.Icon_Upgrade_Fuel,
                Class_level_icon: IconList.Class_level_icon,
                Icon_Upgrade_Accuracy: IconList.Icon_Upgrade_Accuracy,
                Icon_Upgrade_Aim: IconList.Icon_Upgrade_Aim,
                Icon_Upgrade_Ammo: IconList.Icon_Upgrade_Ammo,
                Icon_Upgrade_Angle: IconList.Icon_Upgrade_Angle,
                Icon_Upgrade_Area: IconList.Icon_Upgrade_Area,
                Icon_Upgrade_AreaDamage: IconList.Icon_Upgrade_AreaDamage,
                Icon_Upgrade_ArmorBreaking: IconList.Icon_Upgrade_ArmorBreaking,
                Icon_Upgrade_BulletPenetration: IconList.Icon_Upgrade_BulletPenetration,
                Icon_Upgrade_Capacity: IconList.Icon_Upgrade_Capacity,
                Icon_Upgrade_ChargeUp: IconList.Icon_Upgrade_ChargeUp,
                Icon_Upgrade_ClipSize: IconList.Icon_Upgrade_ClipSize,
                Icon_Upgrade_Cold: IconList.Icon_Upgrade_Cold,
                Icon_Upgrade_DamageGeneral: IconList.Icon_Upgrade_DamageGeneral,
                Icon_Upgrade_DefenseOne: IconList.Icon_Upgrade_DefenseOne,
                Icon_Upgrade_Digging: IconList.Icon_Upgrade_Digging,
                Icon_Upgrade_Duration: IconList.Icon_Upgrade_Duration,
                Icon_Upgrade_Duration_Old: IconList.Icon_Upgrade_Duration_Old,
                Icon_Upgrade_Electricity: IconList.Icon_Upgrade_Electricity,
                Icon_Upgrade_Explosion: IconList.Icon_Upgrade_Explosion,
                Icon_Upgrade_Explosive: IconList.Icon_Upgrade_Explosive,
                Icon_Upgrade_Explosive_Resistance: IconList.Icon_Upgrade_Explosive_Resistance,
                Icon_Upgrade_FallDamageResistance: IconList.Icon_Upgrade_FallDamageResistance,
                Icon_Upgrade_Fire_Resistance: IconList.Icon_Upgrade_Fire_Resistance,
                Icon_Upgrade_Flare_01: IconList.Icon_Upgrade_Flare_01,
                Icon_Upgrade_Heat: IconList.Icon_Upgrade_Heat,
                Icon_Upgrade_MovementSpeed: IconList.Icon_Upgrade_MovementSpeed,
                Icon_Upgrade_PoisonResistance: IconList.Icon_Upgrade_PoisonResistance,
                Icon_Upgrade_Poison_Resistance: IconList.Icon_Upgrade_Poison_Resistance,
                Icon_Upgrade_ProjectileSpeed: IconList.Icon_Upgrade_ProjectileSpeed,
                Icon_Upgrade_Recoil: IconList.Icon_Upgrade_Recoil,
                Icon_Upgrade_Resistance: IconList.Icon_Upgrade_Resistance,
                Icon_Upgrade_Revive: IconList.Icon_Upgrade_Revive,
                Icon_Upgrade_Ricoshet: IconList.Icon_Upgrade_Ricoshet,
                Icon_Upgrade_ScareEnemies: IconList.Icon_Upgrade_ScareEnemies,
                Icon_Upgrade_Shot: IconList.Icon_Upgrade_Shot,
                Icon_Upgrade_Shotgun_Pellet: IconList.Icon_Upgrade_Shotgun_Pellet,
                Icon_Upgrade_Speed: IconList.Icon_Upgrade_Speed,
                Icon_Upgrade_SpeedUp: IconList.Icon_Upgrade_SpeedUp,
                Icon_Upgrade_Sticky: IconList.Icon_Upgrade_Sticky,
                Icon_Upgrade_Stun: IconList.Icon_Upgrade_Stun,
                Icon_Upgrade_TemperatureCoolDown: IconList.Icon_Upgrade_TemperatureCoolDown,
                Icon_Upgrade_Weakspot: IconList.Icon_Upgrade_Weakspot,
                Icon_Upgrade_Bosco_Rocket_Upgrade: IconList.Icon_Upgrade_Bosco_Rocket_Upgrade,
                Icon_Upgrade_PlusOne: IconList.Icon_Upgrade_PlusOne,
                Icon_Upgrade_Light_ExtraLife: IconList.Icon_Upgrade_Light_ExtraLife,
                Icon_Upgrade_Light_Intensity: IconList.Icon_Upgrade_Light_Intensity,
                Icon_Upgrade_Special: IconList.Icon_Upgrade_Special,
                Icon_Upgrade_StrongerTurret: IconList.Icon_Upgrade_StrongerTurret,
                Icon_Upgrade_TwoTurrets: IconList.Icon_Upgrade_TwoTurrets,
                Icon_Overclock_ChangeOfHigherDamage: IconList.Icon_Overclock_ChangeOfHigherDamage,
                Icon_Overclock_ExplosionJump: IconList.Icon_Overclock_ExplosionJump,
                Icon_Overclock_ForthAndBack_Linecutter: IconList.Icon_Overclock_ForthAndBack_Linecutter,
                Icon_Overclock_Neuro: IconList.Icon_Overclock_Neuro,
                Icon_Overclock_ShotgunJump: IconList.Icon_Overclock_ShotgunJump,
                Icon_Overclock_Slowdown: IconList.Icon_Overclock_Slowdown,
                Icon_Overclock_SmallBullets: IconList.Icon_Overclock_SmallBullets,
                Icon_Overclock_Special_Magazine: IconList.Icon_Overclock_Special_Magazine,
                Icon_Overclock_Spinning_Linecutter: IconList.Icon_Overclock_Spinning_Linecutter
            }
        }, // End Icons

    },
    mutations: {
        setSelectedClass(state, newValue) {
            state.selectedClass = newValue
        },
        clearSelectedClass(state) {
            state.selectedClass = ''
        },
        setLoadoutName(state, newValue) {
            state.loadoutName = newValue
        },
        clearLoadoutName(state) {
            state.LoadoutName = ''
        },
        setLoadoutDescription(state, newValue) {
            state.loadoutDescription = newValue
        },
        clearLoadoutDescription(state) {
            state.LoadoutDescription = ''
        },
    },
})
