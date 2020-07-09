export default {
	selected: false,
	modified: false,
	name: "Zhukov NUK17",
	class: "Submachine Gun",
	icon: "equipment.S_S2_Zhuk",
	calculateDamage: (stats) => {
		let damagePerSecond;
		let damagePerBullet;
		let damagePerMagazine;
		let totalDamage;
		let dpsStats = {};
		for (let stat of stats) {
			if (stat.name === "Damage") {
				dpsStats.damage = parseFloat(stat.value);
			} else if (stat.name === "Combined Clip Size") {
				dpsStats.magazineSize = parseFloat(stat.value);
			} else if (stat.name === "Combined Rate of Fire") {
				dpsStats.rateOfFire = parseFloat(stat.value);
			} else if (stat.name === "Reload Time") {
				dpsStats.reloadTime = parseFloat(stat.value);
			} else if (stat.name === "Max Ammo") {
				dpsStats.maxAmmo = parseFloat(stat.value);
			} else if (stat.name === "Weakpoint Damage Bonus") {
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
	},
	baseStats: {
		dmg: { name: "Damage", value: 11 },
		ammo: { name: "Max Ammo", value: 600 },
		clip: { name: "Combined Clip Size", value: 50 },
		rate: { name: "Combined Rate of Fire", value: 30 },
		reload: { name: "Reload Time", value: 1.8 },
		ex1: { name: "Base Spread", value: 100, percent: true },
		ex2: { name: "Max Penetrations", value: 0 },
		ex4: { name: "Weakpoint Damage Bonus", value: 0, percent: true },
		ex5: { name: "Get in, get out", value: 0, boolean: true },
		ex6: { name: "Damage vs Electrically Affected", value: 0, percent: true },
		ex7: { name: "Cryo Minelets", value: 0, boolean: true },
		ex8: { name: "Embedded Detonators", value: 0, boolean: true },
		ex9: { name: "Movement Speed While Using", value: 100, percent: true }
	},
	mods: [
		[
			{
				selected: false,
				name: "Expanded Ammo Bags",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "You had to give up some sandwich-storage, but your total ammo capacity is increased!",
				stats: {
					ammo: { name: "Max Ammo", value: 75 }
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
				name: "High Velocity Rounds",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "The good folk in R&D have been busy. The overall damage of your weapon is increased.",
				stats: {
					dmg: { name: "Damage", value: 1 }
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
			}
		],
		[
			{
				selected: false,
				name: "High Capacity Magazine",
				icon: "Icon_Upgrade_ClipSize",
				type: "Magazine Size",
				text: "The good thing about clips, magazines, ammo drums, fuel tanks...you can always get bigger variants.",
				stats: {
					clip: { name: "Combined Clip Size", value: 10 }
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
				name: "Supercharged Feed Mechanism",
				icon: "Icon_Upgrade_FireRate",
				type: "Rate of Fire",
				text:
					"We overclocked your gun. It fires faster. Don't ask. Just enjoy. Also probably don't tell Management, please.",
				stats: {
					rate: { name: "Combined Rate of Fire", value: 8 }
				},
				cost: {
					credits: 1800,
					bismor: 0,
					croppa: 12,
					enorPearl: 0,
					jadiz: 18,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Quickfire Ejector",
				icon: "Icon_Upgrade_Speed",
				type: "Reload Speed",
				text:
					"Experience, training, and a couple of under-the-table design \"adjustments\" means your gun can be reloaded significantly faster. ",
				stats: {
					reload: { name: "Reload Time", value: 0.6, subtract: true }
				},
				cost: {
					credits: 2200,
					bismor: 30,
					croppa: 0,
					enorPearl: 20,
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
				name: "Increased Caliber Rounds",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "The good folk in R&D have been busy. The overall damage of your weapon is increased.",
				stats: {
					dmg: { name: "Damage", value: 2 }
				},
				cost: {
					credits: 2200,
					bismor: 0,
					croppa: 20,
					enorPearl: 0,
					jadiz: 30,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Better Weight Balance",
				icon: "Icon_Upgrade_Accuracy",
				type: "Accuracy",
				text: "Base Accuracy Increase",
				stats: {
					ex1: { name: "Base Spread", value: 50, percent: true, subtract: true }
				},
				cost: {
					credits: 1800,
					bismor: 0,
					croppa: 12,
					enorPearl: 0,
					jadiz: 18,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Blowthrough Rounds",
				icon: "Icon_Upgrade_BulletPenetration",
				type: "Blow Through",
				text:
					"Shaped projectiles designed to over-penetrate targets with minimal loss of energy. In other words: Fire straight through several enemies at once!",
				stats: {
					ex2: { name: "Max Penetrations", value: 1 }
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
				name: "Hollow-Point Bullets",
				icon: "Icon_Upgrade_Weakspot",
				type: "Weak Spot Bonus",
				text:
					"Hit em' where it hurts! Literally! We've upped the damage you'll be able to do to any creature's fleshy bits. You're welcome.",
				stats: {
					ex4: { name: "Weakpoint Damage Bonus", value: 30, percent: true }
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
			},
			{
				selected: false,
				name: "Expanded Ammo Bags",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "You had to give up on some sandwich-storage, but your total ammo capacity is increased!",
				stats: {
					ammo: { name: "Max Ammo", value: 150 }
				},
				cost: {
					credits: 3800,
					bismor: 36,
					croppa: 0,
					enorPearl: 0,
					jadiz: 15,
					magnite: 26,
					umanite: 0,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Conductive Bullets",
				icon: "Icon_Upgrade_Electricity",
				type: "Electricity",
				text: "More damage to targets that are in an electric field",
				stats: {
					ex6: { name: "Damage vs Electrically Affected", value: 30, percent: true }
				},
				cost: {
					credits: 4400,
					bismor: 0,
					croppa: 40,
					enorPearl: 0,
					jadiz: 110,
					magnite: 0,
					umanite: 60,
					err: 0
				}
			},
			{
				selected: false,
				name: "Get In, Get Out",
				icon: "Icon_Upgrade_MovementSpeed",
				type: "Movement Speed",
				text: "Temporary movement speed bonus after emptying clip",
				stats: {
					ex5: { name: "Get in, get out", value: 1, boolean: true }
				},
				cost: {
					credits: 4400,
					bismor: 60,
					croppa: 0,
					enorPearl: 110,
					jadiz: 0,
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
			name: "Minimal Magazines",
			icon: "Icon_Upgrade_Speed",
			type: "clean",
			cost: {
				credits: 8450,
				bismor: 130,
				croppa: 100,
				enorPearl: 0,
				jadiz: 70,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "By filling away unnecessary material from the magazines you've made them lighter, and that means they pop out faster when reloading. Also the rounds can move more freely increasing the max rate of fire slightly.",
			stats: {
				rate: { name: "Combined Rate of Fire", value: 2 },
				reload: { name: "Reload Time", value: 0.4, subtract: true }
			}
		},
		{
			selected: false,
			name: "Custom Casings",
			icon: "Icon_Upgrade_ClipSize",
			type: "balanced",
			cost: {
				credits: 7700,
				bismor: 95,
				croppa: 75,
				enorPearl: 140,
				jadiz: 0,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "Fit more of these custom rounds in each magazine but at small loss in raw damage.",
			stats: {
				clip: { name: "Combined Clip Size", value: 30 },
				dmg: { name: "Damage", value: 1, subtract: true }
			}
		},
		{
			selected: false,
			name: "Cryo Minelets",
			icon: "Icon_Upgrade_Cold",
			type: "unstable",
			cost: {
				credits: 7300,
				bismor: 0,
				croppa: 65,
				enorPearl: 0,
				jadiz: 0,
				magnite: 135,
				umanite: 95,
				err: 0
			},
			text: "After impacting terrain, these high-tech bullets convert into cryo-minelets that will super-cool anything that comes close. However they don't last forever and the rounds themselves take more space in the clip and deal less direct damage.",
			stats: {
				ex7: { name: "Cryo Minelets", value: 1, boolean: true },
				dmg: { name: "Damage", value: 1, subtract: true },
				clip: { name: "Combined Clip Size", value: 10, subtract: true }
			}
		},
		{
			selected: false,
			name: "Embedded Detonators",
			icon: "Icon_Overclock_Special_Magazine",
			type: "unstable",
			cost: {
				credits: 7550,
				bismor: 0,
				croppa: 0,
				enorPearl: 0,
				jadiz: 135,
				magnite: 65,
				umanite: 90,
				err: 0
			},
			text: "Special bullets contain micro-explosives that detonate when you reload the weapon at the cost of total ammo and direct damage.",
			stats: {
				ex8: { name: "Embedded Detonators", value: 1, boolean: true },
				dmg: { name: "Damage", value: 3, subtract: true },
				ammo: { name: "Max Ammo", value: 75, subtract: true }
			}
		},
		{
			selected: false,
			name: "Gas Recycling",
			icon: "Icon_Upgrade_DamageGeneral",
			type: "unstable",
			cost: {
				credits: 7800,
				bismor: 0,
				croppa: 0,
				enorPearl: 70,
				jadiz: 105,
				magnite: 125,
				umanite: 0,
				err: 0
			},
			text: "Special hardened bullets combined with rerouting escaping gasses back into the chamber greatly increases the raw damage of the weapon but makes it more difficult to control and removes any bonus to weakpoint hits.",
			stats: {
				dmg: { name: "Damage", value: 5 },
				ex4: { name: "Weakpoint Damage Bonus", value: 0, percent: true, multiply: true },
				ex1: { name: "Base Spread", value: 1.5, percent: true, multiply: true },
				ex9: { name: "Movement Speed While Using", value: 50, percent: true, subtract: true }
			}
		}
	]
};
