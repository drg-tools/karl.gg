import Vue from 'vue';
import Vuex from 'vuex';
import moment from 'moment';
import VuexPersist from 'vuex-persist';

// imports for driller equipment
import D_E_Satchel_SVG from './assets/D_E_Satchel.js';
import D_E_Drill_SVG from './assets/D_E_Drill.js';
import D_P1_CRSPR_SVG from './assets/D_P1_CRSPR.js';
import D_P2_Cryo_SVG from './assets/D_P2_Cryo.js';
import D_S1_Subata_SVG from './assets/D_S1_Subata.js';
import D_S2_Plasma_SVG from './assets/D_S2_Plasma.js';
// imports for engineer equipment
import E_E_Sentry_SVG from './assets/E_E_Sentry.js';
import E_E_Platform_SVG from './assets/E_E_Platform.js';
import E_P1_Warthog_SVG from './assets/E_P1_Warthog.js';
import E_P2_Stubby_SVG from './assets/E_P2_Stubby.js';
import E_S1_PGL_SVG from './assets/E_S1_PGL.js';
import E_S2_Breach_SVG from './assets/E_S2_Breach.js';
// imports for gunner equipment
import G_E_Shield_SVG from './assets/G_E_Shield.js';
import G_E_Zipline_SVG from './assets/G_E_Zipline.js';
import G_P1_Lead_SVG from './assets/G_P1_Lead.js';
import G_P2_Thunder_SVG from './assets/G_P2_Thunder.js';
import G_S1_Bulldog_SVG from './assets/G_S1_Bulldog.js';
import G_S2_Burst_SVG from './assets/G_S2_Burst.js';
// imports for scout equipment
import S_E_Flare_SVG from './assets/S_E_Flare.js';
import S_E_Grapling_SVG from './assets/S_E_Grapling.js';
import S_P1_GK2_SVG from './assets/S_P1_GK2.js';
import S_P2_M1000_SVG from './assets/S_P2_M1000.js';
import S_S1_Jury_SVG from './assets/S_S1_Jury.js';
import S_S2_Zhuk_SVG from './assets/S_S2_Zhuk.js';
// imports for robot equipment
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
import Icon_Upgrade_Duration_Old from './assets/mods/Icon_Upgrade_Duration_Old.js';
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




const characterIdToChar = ['', 'E', 'S', 'D', 'G'];
const charToCharacterId = { E: 1, S: 2, D: 3, G: 4 };

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
    },
})
