// imports for robot equipment
import R_P1_Bosco_SVG from "./assets/R_P1_Bosco.js";
// imports for shared equipment
import X_E_Armor_SVG from "./assets/X_E_Armor.js";

// imports for modification icons
import Icon_Upgrade_Distance from "./assets/mods/Icon_Upgrade_Distance.js";
import Icon_Upgrade_FireRate from "./assets/mods/Icon_Upgrade_FireRate.js";
import Icon_Upgrade_Fuel from "./assets/mods/Icon_Upgrade_Fuel.js";
import Class_level_icon from "./assets/mods/Class_level_icon.js";
import Icon_Upgrade_Accuracy from "./assets/mods/Icon_Upgrade_Accuracy.js";
import Icon_Upgrade_Aim from "./assets/mods/Icon_Upgrade_Aim.js";
import Icon_Upgrade_Ammo from "./assets/mods/Icon_Upgrade_Ammo.js";
import Icon_Upgrade_Angle from "./assets/mods/Icon_Upgrade_Angle.js";
import Icon_Upgrade_Area from "./assets/mods/Icon_Upgrade_Area.js";
import Icon_Upgrade_AreaDamage from "./assets/mods/Icon_Upgrade_AreaDamage.js";
import Icon_Upgrade_ArmorBreaking from "./assets/mods/Icon_Upgrade_ArmorBreaking.js";
import Icon_Upgrade_BulletPenetration from "./assets/mods/Icon_Upgrade_BulletPenetration.js";
import Icon_Upgrade_Capacity from "./assets/mods/Icon_Upgrade_Capacity.js";
import Icon_Upgrade_ChargeUp from "./assets/mods/Icon_Upgrade_ChargeUp.js";
import Icon_Upgrade_ClipSize from "./assets/mods/Icon_Upgrade_ClipSize.js";
import Icon_Upgrade_Cold from "./assets/mods/Icon_Upgrade_Cold.js";
import Icon_Upgrade_DamageGeneral from "./assets/mods/Icon_Upgrade_DamageGeneral.js";
import Icon_Upgrade_DefenseOne from "./assets/mods/Icon_Upgrade_DefenseOne.js";
import Icon_Upgrade_Digging from "./assets/mods/Icon_Upgrade_Digging.js";
import Icon_Upgrade_Duration from "./assets/mods/Icon_Upgrade_Duration.js";
import Icon_Upgrade_Duration_Old from "./assets/mods/Icon_Upgrade_Duration_Old.js";
import Icon_Upgrade_Electricity from "./assets/mods/Icon_Upgrade_Electricity.js";
import Icon_Upgrade_Explosion from "./assets/mods/Icon_Upgrade_Explosion.js";
import Icon_Upgrade_Explosive from "./assets/mods/Icon_Upgrade_Explosive.js";
import Icon_Upgrade_Explosive_Resistance from "./assets/mods/Icon_Upgrade_Explosive_Resistance.js";
import Icon_Upgrade_FallDamageResistance from "./assets/mods/Icon_Upgrade_FallDamageResistance.js";
import Icon_Upgrade_Fire_Resistance from "./assets/mods/Icon_Upgrade_Fire_Resistance.js";
import Icon_Upgrade_Flare_01 from "./assets/mods/Icon_Upgrade_Flare_01.js";
import Icon_Upgrade_Heat from "./assets/mods/Icon_Upgrade_Heat.js";
import Icon_Upgrade_MovementSpeed from "./assets/mods/Icon_Upgrade_MovementSpeed.js";
import Icon_Upgrade_PoisonResistance from "./assets/mods/Icon_Upgrade_PoisonResistance.js";
import Icon_Upgrade_Poison_Resistance from "./assets/mods/Icon_Upgrade_Poison_Resistance.js";
import Icon_Upgrade_ProjectileSpeed from "./assets/mods/Icon_Upgrade_ProjectileSpeed.js";
import Icon_Upgrade_Recoil from "./assets/mods/Icon_Upgrade_Recoil.js";
import Icon_Upgrade_Resistance from "./assets/mods/Icon_Upgrade_Resistance.js";
import Icon_Upgrade_Revive from "./assets/mods/Icon_Upgrade_Revive.js";
import Icon_Upgrade_Ricoshet from "./assets/mods/Icon_Upgrade_Ricoshet.js";
import Icon_Upgrade_ScareEnemies from "./assets/mods/Icon_Upgrade_ScareEnemies.js";
import Icon_Upgrade_Shot from "./assets/mods/Icon_Upgrade_Shot.js";
import Icon_Upgrade_Shotgun_Pellet from "./assets/mods/Icon_Upgrade_Shotgun_Pellet.js";
import Icon_Upgrade_Speed from "./assets/mods/Icon_Upgrade_Speed.js";
import Icon_Upgrade_SpeedUp from "./assets/mods/Icon_Upgrade_SpeedUp.js";
import Icon_Upgrade_Sticky from "./assets/mods/Icon_Upgrade_Sticky.js";
import Icon_Upgrade_Stun from "./assets/mods/Icon_Upgrade_Stun.js";
import Icon_Upgrade_TemperatureCoolDown from "./assets/mods/Icon_Upgrade_TemperatureCoolDown.js";
import Icon_Upgrade_Weakspot from "./assets/mods/Icon_Upgrade_Weakspot.js";
import Icon_Upgrade_Bosco_Rocket_Upgrade from "./assets/mods/Icon_Upgrade_Bosco_Rocket_Upgrade.js";
import Icon_Upgrade_PlusOne from "./assets/mods/Icon_Upgrade_PlusOne.js";
import Icon_Upgrade_Light from "./assets/mods/Icon_Upgrade_Light.js";
import Icon_Upgrade_Light_ExtraLife from "./assets/mods/Icon_Upgrade_Light_ExtraLife.js";
import Icon_Upgrade_Light_Intensity from "./assets/mods/Icon_Upgrade_Light_Intensity.js";
import Icon_Upgrade_Special from "./assets/mods/Icon_Upgrade_Special.js";
import Icon_Upgrade_StrongerTurret from "./assets/mods/Icon_Upgrade_StrongerTurret.js";
import Icon_Upgrade_TwoTurrets from "./assets/mods/Icon_Upgrade_TwoTurrets.js";
import Icon_Overclock_ChangeOfHigherDamage from "./assets/mods/Icon_Overclock_ChangeOfHigherDamage.js";
import Icon_Overclock_ExplosionJump from "./assets/mods/Icon_Overclock_ExplosionJump.js";
import Icon_Overclock_ForthAndBack_Linecutter from "./assets/mods/Icon_Overclock_ForthAndBack_Linecutter.js";
import Icon_Overclock_LastShellHigherDamage from "./assets/mods/Icon_Overclock_LastShellHigherDamage.js";
import Icon_Overclock_Neuro from "./assets/mods/Icon_Overclock_Neuro.js";
import Icon_Overclock_ShotgunJump from "./assets/mods/Icon_Overclock_ShotgunJump.js";
import Icon_Overclock_Slowdown from "./assets/mods/Icon_Overclock_Slowdown.js";
import Icon_Overclock_SmallBullets from "./assets/mods/Icon_Overclock_SmallBullets.js";
import Icon_Overclock_Special_Magazine from "./assets/mods/Icon_Overclock_Special_Magazine.js";
import Icon_Overclock_Spinning_Linecutter from "./assets/mods/Icon_Overclock_Spinning_Linecutter.js";
import Icon_Upgrade_Arperture_Extension from "./assets/mods/Icon_Upgrade_Arperture_Extension.js";

const IconList = {
    R_P1_Bosco_SVG,
    X_E_Armor_SVG,
    Icon_Upgrade_Distance,
    Icon_Upgrade_FireRate,
    Icon_Upgrade_Fuel,
    Class_level_icon,
    Icon_Upgrade_Accuracy,
    Icon_Upgrade_Aim,
    Icon_Upgrade_Ammo,
    Icon_Upgrade_Angle,
    Icon_Upgrade_Area,
    Icon_Upgrade_AreaDamage,
    Icon_Upgrade_ArmorBreaking,
    Icon_Upgrade_Arperture_Extension,
    Icon_Upgrade_BulletPenetration,
    Icon_Upgrade_Capacity,
    Icon_Upgrade_ChargeUp,
    Icon_Upgrade_ClipSize,
    Icon_Upgrade_Cold,
    Icon_Upgrade_DamageGeneral,
    Icon_Upgrade_DefenseOne,
    Icon_Upgrade_Digging,
    Icon_Upgrade_Duration,
    Icon_Upgrade_Duration_Old,
    Icon_Upgrade_Electricity,
    Icon_Upgrade_Explosion,
    Icon_Upgrade_Explosive,
    Icon_Upgrade_Explosive_Resistance,
    Icon_Upgrade_FallDamageResistance,
    Icon_Upgrade_Fire_Resistance,
    Icon_Upgrade_Flare_01,
    Icon_Upgrade_Heat,
    Icon_Upgrade_MovementSpeed,
    Icon_Upgrade_PoisonResistance,
    Icon_Upgrade_Poison_Resistance,
    Icon_Upgrade_ProjectileSpeed,
    Icon_Upgrade_Recoil,
    Icon_Upgrade_Resistance,
    Icon_Upgrade_Revive,
    Icon_Upgrade_Ricoshet,
    Icon_Upgrade_ScareEnemies,
    Icon_Upgrade_Shot,
    Icon_Upgrade_Shotgun_Pellet,
    Icon_Upgrade_Speed,
    Icon_Upgrade_SpeedUp,
    Icon_Upgrade_Sticky,
    Icon_Upgrade_Stun,
    Icon_Upgrade_TemperatureCoolDown,
    Icon_Upgrade_Weakspot,
    Icon_Upgrade_Bosco_Rocket_Upgrade,
    Icon_Upgrade_PlusOne,
    Icon_Upgrade_Light,
    Icon_Upgrade_Light_ExtraLife,
    Icon_Upgrade_Light_Intensity,
    Icon_Upgrade_Special,
    Icon_Upgrade_StrongerTurret,
    Icon_Upgrade_TwoTurrets,
    Icon_Overclock_ChangeOfHigherDamage,
    Icon_Overclock_ExplosionJump,
    Icon_Overclock_ForthAndBack_Linecutter,
    Icon_Overclock_LastShellHigherDamage,
    Icon_Overclock_Neuro,
    Icon_Overclock_ShotgunJump,
    Icon_Overclock_Slowdown,
    Icon_Overclock_SmallBullets,
    Icon_Overclock_Special_Magazine,
    Icon_Overclock_Spinning_Linecutter,
};

export default IconList;
