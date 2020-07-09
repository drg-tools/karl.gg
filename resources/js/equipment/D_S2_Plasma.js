export default {
	selected: false,
	modified: false,
	name: "Experimental Plasma Charger",
	class: "Pistol",
	icon: "equipment.D_S2_Plasma",
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
		/*
		Maximum heat is how much you can fire your weapon before it overheat and you need it to cooldown before using it again.
		By default charged shot reach 100% heat directly. The maximum heat value is 1 or 100% and cannot be changed.

		Cooling Rate is how fast your weapon cool down the accumulated heat (be it overheated or not).
		The base value is 0.4 heat per second so you’ll recover from overheating (100% heat) in 100% / 0.4 = 2.5 seconds

		Charge Speed is how long in second it take you to prepare a charged shot. No heat is generated while charging a projectile.

		Heat Buildup When Charged is, just as the name implies, how much heat is generated per second while holding the charge
		(i.e. when the projectile is fully charged). The base value is 2, which means that if you hold a charged projectile
		starting from 0 heat you will overheat after 0.5 seconds.
		*/
		for (let stat of stats) {
			if (stat.name === "Damage") {
				dpsStats.directDamage = parseFloat(stat.value);
			} else if (stat.name === "Charged Damage") {
				dpsStats.chargeDamage = parseFloat(stat.value);
			} else if (stat.name === "Charged Area Damage") {
				dpsStats.chargeAreaDamage = parseFloat(stat.value);
			} else if (stat.name === "Charged Shot Ammo Use") {
				dpsStats.chargeAmmoUse = parseFloat(stat.value);
			} else if (stat.name === "Charge Speed") {
				dpsStats.chargeSpeed = parseFloat(stat.value);
			} else if (stat.name === "Rate of Fire") {
				dpsStats.rateOfFire = parseFloat(stat.value);
			} else if (stat.name === "Reload Time") {
				dpsStats.reloadTime = parseFloat(stat.value);
			} else if (stat.name === "Battery Capacity") {
				dpsStats.maxAmmo = parseFloat(stat.value);
			} else if (stat.name === "Cooling Rate") {
				dpsStats.coolingRate = parseFloat(stat.value);
				dpsStats.cooldownTime = 2.5 * dpsStats.coolingRate / 100;
			}
		}
		// get damage time for single and charge shots
		// todo: all calculations without taking overheating into account at the moment (dpsStats.cooldownTime ignored)
		// todo: check with overcharger overclock
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
	},
	baseStats: {
		dmg: { name: "Damage", value: 20 },
		clip: { name: "Battery Capacity", value: 120 },
		rate: { name: "Rate of Fire", value: 7.0 },
		reload: { name: "Cooling Rate", value: 100, percent: true },
		ex1: { name: "Charged Damage", value: 60 },
		ex2: { name: "Charged Area Damage", value: 60 },
		ex3: { name: "Charged Effect Radius", value: 2 },
		ex4: { name: "Charged Shot Ammo Use", value: 8 },
		ex5: { name: "Charge Speed", value: 0.8 },
		ex6: { name: "Heat Buildup When Charged", value: 100, percent: true },
		ex7: { name: "Normal Projectile Velocity", value: 100, percent: true },
		ex8: { name: "Thin Containment Field", value: 0, boolean: true },
		ex9: { name: "Flying Nightmare", value: 0, boolean: true },
		ex10: { name: "No Charged Shot Insta-Overheat", value: 0, boolean: true },
		ex11: { name: "Plasma Burn", value: 0, boolean: true },
		ex12: { name: "Normal Shot Heat Generation", value: 100, percent: true },
		ex13: { name: "Persistent Plasma", value: 0, boolean: true }
	},
	mods: [
		[
			{
				selected: false,
				name: "Increased Particle Density",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "Improves the damage caused by the normal shots.",
				stats: {
					dmg: { name: "Damage", value: 5 }
				},
				cost: {
					credits: 1000,
					bismor: 0,
					croppa: 0,
					enorPearl: 20,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Larger Battery",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "The good thing about clips, magazines, ammo drums, fuel tanks... You can always get bigger variants.",
				stats: {
					clip: { name: "Battery Capacity", value: 24 }
				},
				cost: {
					credits: 1000,
					bismor: 20,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Higher Charged Plasma Energy",
				icon: "Icon_Upgrade_AreaDamage",
				type: "Damage",
				text: "Increases the direct damage for the charged projectile.",
				stats: {
					ex1: { name: "Charged Damage", value: 15 },
					ex2: { name: "Charged Area Damage", value: 15 }
				},
				cost: {
					credits: 1000,
					bismor: 20,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Expanded Plasma Splash",
				icon: "Icon_Upgrade_Area",
				type: "Area of effect",
				text: "Greater damage radius for the charged projectile explosion",
				stats: {
					ex3: { name: "Charged Effect Radius", value: 1 }
				},
				cost: {
					credits: 1800,
					bismor: 0,
					croppa: 18,
					enorPearl: 0,
					jadiz: 12,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Charged Plasma Accelerator",
				icon: "Icon_Upgrade_ProjectileSpeed",
				type: "Projectile Speed",
				text: "Significantly increases the movement speed of both of the EPCs projectiles.",
				stats: {
					ex7: { name: "Normal Projectile Velocity", value: 25, percent: true }
				},
				cost: {
					credits: 1800,
					bismor: 18,
					croppa: 0,
					enorPearl: 12,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Reactive Shockwave",
				icon: "Icon_Upgrade_AreaDamage",
				type: "Area Damage",
				text: "More bang for the buck! Increases the damage done within the Area of Effect!",
				stats: {
					ex1: { name: "Charged Damage", value: 15 },
					ex2: { name: "Charged Area Damage", value: 15 }
				},
				cost: {
					credits: 1800,
					bismor: 18,
					croppa: 12,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Improved Charge Efficiency",
				icon: "Icon_Upgrade_Fuel",
				type: "Energy Consumption",
				text: "A charged shot uses less energy",
				stats: {
					ex4: { name: "Charged Shot Ammo Use", value: 2, subtract: true }
				},
				cost: {
					credits: 2200,
					bismor: 0,
					croppa: 0,
					enorPearl: 20,
					jadiz: 0,
					magnite: 30,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Crystal Capacitors",
				icon: "Icon_Upgrade_ChargeUp",
				type: "Charge Speed",
				text: "Prepare a charged shot much faster.",
				stats: {
					ex5: { name: "Charge Speed", value: 2.5, multiply: true }
				},
				cost: {
					credits: 2200,
					bismor: 0,
					croppa: 30,
					enorPearl: 0,
					jadiz: 20,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Tweaked Radiator",
				icon: "Icon_Upgrade_TemperatureCoolDown",
				type: "Cooling",
				text:
					"Increases the rate at which the weapon sheds heat, letting you shoot more rounds before overheating and also recovering faster from an overheat.",
				stats: {
					reload: { name: "Cooling Rate", value: 50, percent: true }
				},
				cost: {
					credits: 3800,
					bismor: 0,
					croppa: 25,
					enorPearl: 15,
					jadiz: 0,
					magnite: 0,
					umanite: 36,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Heat Shield",
				icon: "Icon_Upgrade_TemperatureCoolDown",
				type: "Cooling",
				text: "Reduces how fast the weapon overheats when holding a charged shot.",
				stats: {
					ex6: { name: "Heat Buildup When Charged", value: 60, subtract: true }
				},
				cost: {
					credits: 3800,
					bismor: 0,
					croppa: 0,
					enorPearl: 36,
					jadiz: 25,
					magnite: 0,
					umanite: 15,
					err: 0
				}
			},
			{
				selected: false,
				name: "High Density Battery",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "The good thing about clips, magazines, ammo drums, fuel tanks... You can always get bigger variants.",
				stats: {
					clip: { name: "Battery Capacity", value: 24 }
				},
				cost: {
					credits: 3800,
					bismor: 15,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Flying Nightmare",
				icon: "Icon_Upgrade_Special",
				type: "Special",
				text: "The charged projectile deals damage to nearby enemies while it flies but takes longer to charge up.",
				stats: {
					ex9: { name: "Flying Nightmare", value: 1, boolean: true },
					ex5: { name: "Charge Speed", value: 0.7, multiply: true }
				},
				cost: {
					credits: 4400,
					bismor: 0,
					croppa: 40,
					enorPearl: 0,
					jadiz: 110,
					magnite: 60,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Thin Containment Field",
				icon: "Icon_Upgrade_Special",
				type: "Special",
				text:
					"A weaker containment field takes less energy to create thus producing less heat for Charged Shots. Be aware that any high-energy impact will destabilize the Charged Projectile causing a large area implosion.",
				stats: {
					ex10: { name: "No Charged Shot Insta-Overheat", value: 1, boolean: true },
					ex12: { name: "Normal Shot Heat Generation", value: 0.8, percent: true, multiply: true },
					ex8: { name: "Thin Containment Field", value: 1, boolean: true }
				},
				cost: {
					credits: 4400,
					bismor: 40,
					croppa: 60,
					enorPearl: 0,
					jadiz: 0,
					magnite: 110,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Plasma Burn",
				icon: "Icon_Upgrade_Heat",
				type: "Heat",
				text:
					"A modified containment field causes regular shots to heat up the target proportionally to the amount of damage dealt.",
				stats: {
					ex11: { name: "Plasma Burn", value: 1, boolean: true }
				},
				cost: {
					credits: 4400,
					bismor: 0,
					croppa: 0,
					enorPearl: 110,
					jadiz: 60,
					magnite: 40,
					umanite: 0,
					err: 0
				}
			}
		]
	],
	overclocks: [
		{
			selected: false,
			name: "Energy Rerouting",
			icon: "Icon_Upgrade_ChargeUp",
			type: "clean",
			cost: {
				credits: 7300,
				bismor: 130,
				croppa: 0,
				enorPearl: 0,
				jadiz: 100,
				magnite: 0,
				umanite: 65,
				err: 0
			},
			text: "A masterwork of engineering that improves charge speed and energy efficiency without affecting overall performance!",
			stats: {
				clip: { name: "Battery Capacity", value: 16 },
				ex5: { name: "Charge Speed", value: 1.5, multiply: true }
			}
		},
		{
			selected: false,
			name: "Magnetic Cooling Unit",
			icon: "Icon_Upgrade_TemperatureCoolDown",
			type: "clean",
			cost: {
				credits: 8900,
				bismor: 0,
				croppa: 95,
				enorPearl: 0,
				jadiz: 80,
				magnite: 0,
				umanite: 125,
				err: 0
			},
			text: "A high-tech solution to Cleanly improve the cooling rate increasing the number of slots that can be fired before overheating and also the speed of recovery from an overheat as well as how long a charge can be held.",
			stats: {
				reload: { name: "Cooling Rate", value: 25, percent: true },
				ex6: { name: "Heat Buildup When Charged", value: 30, percent: true, subtract: true }
			}
		},
		{
			selected: false,
			name: "Heat Pipe",
			icon: "Icon_Upgrade_Fuel",
			type: "balanced",
			cost: {
				credits: 7450,
				bismor: 60,
				croppa: 0,
				enorPearl: 0,
				jadiz: 95,
				magnite: 0,
				umanite: 125,
				err: 0
			},
			text: "By channeling exhaust heat back into the charge chamber a shot can be charged using less energy. This does however make the weapon less efficient at dissipating heat from normal shots.",
			stats: {
				ex4: { name: "Charged Shot Ammo Use", value: 2, subtract: true },
				ex5: { name: "Charge Speed", value: 1.3, multiply: true },
				ex12: { name: "Normal Shot Heat Generation", value: 1.5, percent: true, multiply: true }
			}
		},
		{
			selected: false,
			name: "Heavy Hitter",
			icon: "Icon_Upgrade_DamageGeneral",
			type: "balanced",
			cost: {
				credits: 8100,
				bismor: 140,
				croppa: 0,
				enorPearl: 0,
				jadiz: 0,
				magnite: 60,
				umanite: 105,
				err: 0
			},
			text: "Some extensive tweaking to how the shots are prepared can increase the pure damage of the weapon but at the cost of a lower projectile velocity and a reduced battery size.",
			stats: {
				dmg: { name: "Damage", value: 1.6, multiply: true },
				clip: { name: "Battery Capacity", value: 32, subtract: true },
				ex12: { name: "Normal Shot Heat Generation", value: 1.5, percent: true, multiply: true }
			}
		},
		{
			selected: false,
			name: "Overcharger",
			icon: "Icon_Upgrade_DamageGeneral",
			type: "unstable",
			cost: {
				credits: 7050,
				bismor: 120,
				croppa: 95,
				enorPearl: 60,
				jadiz: 0,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "Pushing the EPC to the limit will give you a significant increase in charge shot damage and a boost in the size of the explosion but at the cost of thermal efficiency and energy consumption.",
			stats: {
				ex1: { name: "Charged Damage", value: 1.5, multiply: true },
				ex2: { name: "Charged Area Damage", value: 1.5, multiply: true },
				ex3: { name: "Charged Effect Radius", value: 0.6 },
				ex4: { name: "Charged Shot Ammo Use", value: 1.5, multiply: true },
				reload: { name: "Cooling Rate", value: 25, percent: true, subtract: true }
			}
		},
		{
			selected: false,
			name: "Persistent Plasma",
			icon: "Icon_Upgrade_Duration",
			type: "unstable",
			cost: {
				credits: 8150,
				bismor: 0,
				croppa: 75,
				enorPearl: 0,
				jadiz: 130,
				magnite: 95,
				umanite: 0,
				err: 0
			},
			text: "By changing how the plasma is layered within the charged projectile a slow and persistent discharge can be achieved upon impact. However this does reduce the instance damage done.",
			stats: {
				ex13: { name: "Persistent Plasma", value: 1, boolean: true },
				ex1: { name: "Charged Damage", value: 20, subtract: true },
				ex2: { name: "Charged Area Damage", value: 20, subtract: true }
			}
		}
	]
};