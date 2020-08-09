export default {
	selected: false,
	modified: false,
	name: "\"Thunderhead\" Heavy Autocannon",
	class: "Heavy Weapon",
	icon: "equipment.G_P2_Thunder",
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
			if (stat.name === "Damage") {
				dpsStats.directDamage = parseFloat(stat.value);
			} else if (stat.name === "Area Damage") {
				dpsStats.areaDamage = parseFloat(stat.value);
			} else if (stat.name === "Magazine Size") {
				dpsStats.magazineSize = parseFloat(stat.value);
			} else if (stat.name === "Top Rate of Fire") {
				dpsStats.rateOfFire = parseFloat(stat.value);
			} else if (stat.name === "Reload Time") {
				dpsStats.reloadTime = parseFloat(stat.value);
			} else if (stat.name === "Max Ammo") {
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
	},
	baseStats: {
		dmg: { name: "Damage", value: 14 },
		ammo: { name: "Max Ammo", value: 440 },
		clip: { name: "Magazine Size", value: 110 },
		rate: { name: "Top Rate of Fire", value: 5.5 },
		reload: { name: "Reload Time", value: 5 },
		ex1: { name: "Area Damage", value: 9 },
		ex2: { name: "Effect Radius", value: 1.4 },
		ex3: { name: "Base Spread", value: 100, percent: true },
		ex4: { name: "Rate of Fire Growth Speed", value: 100, percent: true },
		ex5: { name: "Armor Breaking", value: 100, percent: true },
		ex6: { name: "Movement Speed While Using", value: 50, percent: true },
		ex7: { name: "Top RoF Damage Bonus", value: 0, percent: true },
		ex8: { name: "Impact Fear AoE", value: 0 },
		ex9: { name: "Damage Resistance at Full RoF", value: 0, percent: true },
		ex10: { name: "Neurotoxin Payload", value: 0, boolean: true }
	},
	mods: [
		[
			{
				selected: false,
				name: "Increased Caliber Rounds",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "The good folk in R&D have been busy. The overall damage of your weapon is increased.",
				stats: {
					dmg: { name: "Damage", value: 3 }
				},
				cost: {
					credits: 1200,
					bismor: 25,
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
				name: "High Capacity Magazine",
				icon: "Icon_Upgrade_ClipSize",
				type: "Magazine Size",
				text: "The good thing about clips, magazines, ammo drums, fuel tanks... You can always get bigger variants.",
				stats: {
					clip: { name: "Magazine Size", value: 110 }
				},
				cost: {
					credits: 1200,
					bismor: 0,
					croppa: 0,
					enorPearl: 25,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Expanded Ammo Bags",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "You had to give up some sandwich-space, but your total ammo capacity is increased!",
				stats: {
					ammo: { name: "Max Ammo", value: 220 }
				},
				cost: {
					credits: 1200,
					bismor: 0,
					croppa: 25,
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
				name: "Tighter Barrel Alignment",
				icon: "Icon_Upgrade_Accuracy",
				type: "Accuracy",
				text: "Improved accuracy",
				stats: {
					ex3: { name: "Base Spread", value: 30, percent: true, subtract: true }
				},
				cost: {
					credits: 2000,
					bismor: 0,
					croppa: 24,
					enorPearl: 15,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Improved Gas System",
				icon: "Icon_Upgrade_FireRate",
				type: "Rate of Fire",
				text:
					"We overclocked your gun. It fires faster. Don't ask, just enjoy. Also probably don't tell Management, please.",
				stats: {
					rate: { name: "Top Rate of Fire", value: 1.5 }
				},
				cost: {
					credits: 2000,
					bismor: 0,
					croppa: 0,
					enorPearl: 15,
					jadiz: 24,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Lighter Barrel Assembly",
				icon: "Icon_Upgrade_FireRate",
				type: "Rate of Fire",
				text: "Reach the max rate of fire faster",
				stats: {
					ex4: { name: "Rate of Fire Growth Speed", value: 100, percent: true }
				},
				cost: {
					credits: 2000,
					bismor: 15,
					croppa: 0,
					enorPearl: 24,
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
				name: "Supercharged Feed Mechanism",
				icon: "Icon_Upgrade_FireRate",
				type: "Rate of Fire",
				text:
					"We overclocked your gun. It fires faster. Don't ask, just enjoy. Also probably don't tell Management, please.",
				stats: {
					rate: { name: "Top Rate of Fire", value: 2 }
				},
				cost: {
					credits: 2800,
					bismor: 0,
					croppa: 50,
					enorPearl: 0,
					jadiz: 25,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Loaded Rounds",
				icon: "Icon_Upgrade_AreaDamage",
				type: "Area Damage",
				text: "Increased splash damage",
				stats: {
					ex1: { name: "Area Damage", value: 2 }
				},
				cost: {
					credits: 2000,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 35,
					umanite: 50,
					err: 0
				}
			},
			{
				selected: false,
				name: "High Velocity Rounds",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "The good folk in R&D have been busy. The overall damage of your weapon is increased.",
				stats: {
					dmg: { name: "Damage", value: 4 }
				},
				cost: {
					credits: 2800,
					bismor: 0,
					croppa: 0,
					enorPearl: 35,
					jadiz: 0,
					magnite: 50,
					umanite: 0,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Penerating Rounds",
				icon: "Icon_Upgrade_ArmorBreaking",
				type: "Armor Breaking",
				text:
					"We're proud of this one. Armor shredding. Tear through that high-impact plating of those bug buggers like butter. What could be finer?",
				stats: {
					ex5: { name: "Armor Breaking", value: 400, percent: true }
				},
				cost: {
					credits: 4800,
					bismor: 0,
					croppa: 0,
					enorPearl: 50,
					jadiz: 72,
					magnite: 48,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Shrapnel Rounds",
				icon: "Icon_Upgrade_Area",
				type: "Area of effect",
				text: "Greater splash damage radius",
				stats: {
					ex2: { name: "Effect Radius", value: 0.6 }
				},
				cost: {
					credits: 4800,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 72,
					magnite: 50,
					umanite: 48,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Feedback Loop",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "Increased damage when at max rate of fire",
				stats: {
					ex7: { name: "Top RoF Damage Bonus", value: 20, percent: true }
				},
				cost: {
					credits: 5600,
					bismor: 70,
					croppa: 140,
					enorPearl: 0,
					jadiz: 0,
					magnite: 64,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Suppressive Fire",
				icon: "Icon_Upgrade_ScareEnemies",
				type: "Fear",
				text: "Chance to scare creatures next to a bullet impact",
				stats: {
					ex8: { name: "Impact Fear AoE", value: 1 }
				},
				cost: {
					credits: 5600,
					bismor: 70,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 64,
					umanite: 140,
					err: 0
				}
			},
			{
				selected: false,
				name: "Damage Resistance At Full RoF",
				icon: "Icon_Upgrade_Resistance",
				type: "Resistance",
				text: "Take less damage when at max rate of fire",
				stats: {
					ex9: { name: "Damage Resistance at Full RoF", value: 33, percent: true }
				},
				cost: {
					credits: 5600,
					bismor: 0,
					croppa: 64,
					enorPearl: 70,
					jadiz: 140,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			}
		]
	],
	overclocks: [
		{
			selected: false,
			name: "Composite Drums",
			icon: "Icon_Upgrade_Ammo",
			type: "clean",
			cost: {
				credits: 7850,
				bismor: 0,
				croppa: 135,
				enorPearl: 70,
				jadiz: 0,
				magnite: 105,
				umanite: 0,
				err: 0
			},
			text: "Lighter weight materials means you can carry even more ammo!",
			stats: {
				ammo: { name: "Max Ammo", value: 110 },
				reload: { name: "Reload Time", value: 0.5, subtract: true }
			}
		},
		{
			selected: false,
			name: "Splintering Shells",
			icon: "Icon_Upgrade_Area",
			type: "clean",
			cost: {
				credits: 7300,
				bismor: 0,
				croppa: 95,
				enorPearl: 0,
				jadiz: 125,
				magnite: 65,
				umanite: 0,
				err: 0
			},
			text: "Specially designed shells splinter into smaller pieces increasing the splash damage range. ",
			stats: {
				ex1: { name: "Area Damage", value: 1 },
				ex2: { name: "Effect Radius", value: 0.3 }
			}
		},
		{
			selected: false,
			name: "Carpet Bomber",
			icon: "Icon_Upgrade_AreaDamage",
			type: "balanced",
			cost: {
				credits: 7350,
				bismor: 0,
				croppa: 120,
				enorPearl: 0,
				jadiz: 0,
				magnite: 105,
				umanite: 70,
				err: 0
			},
			text: "A few tweaks here and there and the autocannon can now shoot HE rounds! Direct damage is lower but the increased splash damage and range lets you saturate and area like no other weapon can. ",
			stats: {
				ex1: { name: "Area Damage", value: 3 },
				ex2: { name: "Effect Radius", value: 0.7 },
				dmg: { name: "Damage", value: 6, subtract: true }
			}
		},
		{
			selected: false,
			name: "Combat Mobility",
			icon: "Icon_Upgrade_MovementSpeed",
			type: "balanced",
			cost: {
				credits: 7650,
				bismor: 0,
				croppa: 70,
				enorPearl: 0,
				jadiz: 120,
				magnite: 95,
				umanite: 0,
				err: 0
			},
			text: "A slight reduction in the power of the rounds permits using a smaller chamber and a light-weight backplate with in turn allows extensive weight redistribution. The end result is a weapon that still packs a punch but is easier to handle on the move. ",
			stats: {
				ex6: { name: "Movement Speed While Using", value: 15, percent: true },
				dmg: { name: "Damage", value: 2, subtract: true }
			}
		},
		{
			selected: false,
			name: "Big Bertha",
			icon: "Icon_Upgrade_DamageGeneral",
			type: "unstable",
			cost: {
				credits: 8400,
				bismor: 125,
				croppa: 105,
				enorPearl: 0,
				jadiz: 0,
				magnite: 0,
				umanite: 80,
				err: 0
			},
			text: "Extensive tweaks give a huge bump in raw damage at the cost of ammo capacity and fire rate. ",
			stats: {
				dmg: { name: "Damage", value: 12 },
				clip: { name: "Magazine Size", value: 0.5, multiply: true },
				ammo: { name: "Max Ammo", value: 110, subtract: true },
				ex3: { name: "Base Spread", value: 30, percent: true, subtract: true },
				rate: { name: "Top Rate of Fire", value: 1.5, subtract: true }
			}
		},
		{
			selected: false,
			name: "Neurotoxin Payload",
			icon: "Icon_Overclock_Neuro",
			type: "unstable",
			cost: {
				credits: 8100,
				bismor: 0,
				croppa: 100,
				enorPearl: 0,
				jadiz: 75,
				magnite: 135,
				umanite: 0,
				err: 0
			},
			text: "Channel your inner war criminal by mixing some neurotoxin into the explosive compound. The rounds deal less direct damage and splash damage, but affected bugs move slower and take lots of damage over time.",
			stats: {
				ex10: { name: "Neurotoxin Payload", value: 1, boolean: true },
				dmg: { name: "Damage", value: 3, subtract: true },
				ex1: { name: "Area Damage", value: 6, subtract: true },
				ex2: { name: "Effect Radius", value: 0.3 }
			}
		}
	]
};
