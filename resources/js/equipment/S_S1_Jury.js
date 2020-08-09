export default {
	selected: false,
	modified: false,
	name: "Jury-Rigged Boomstick",
	class: "Shotgun",
	icon: "equipment.S_S1_Jury",
	calculateDamage: (stats) => {
		let damagePerSecond;
		let damagePerBullet;
		let totalDamage;
		let magazineDamage;
		let timeToEmpty;
		let damageTime;
		let dpsStats = {};
		for (let stat of stats) {
			if (stat.name === "Damage") {
				dpsStats.damage = parseFloat(stat.value);
			} else if (stat.name === "Rate of Fire") {
				dpsStats.rateOfFire = parseFloat(stat.value);
			} else if (stat.name === "Reload Time") {
				dpsStats.reloadTime = parseFloat(stat.value);
			} else if (stat.name === "Max Ammo") {
				dpsStats.maxAmmo = parseFloat(stat.value);
			} else if (stat.name === "Double Barrel" && stat.value === "1") {
				dpsStats.doubleBarrel = true;
			} else if (stat.name === "Magazine Size") {
				dpsStats.magazineSize = parseFloat(stat.value);
			} else if (stat.name === "Pellets") {
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
	},
	baseStats: {
		dmg: { name: "Damage", value: 12 },
		ammo: { name: "Max Ammo", value: 24 },
		clip: { name: "Magazine Size", value: 2 },
		rate: { name: "Rate of Fire", value: 1.5 },
		reload: { name: "Reload Time", value: 2 },
		ex1: { name: "Pellets", value: 8 },
		ex5: { name: "Front AoE shock wave Damage", value: 20 },
		ex2: { name: "Stun Chance", value: 30, percent: true },
		ex9: { name: "Stun Duration", value: 2.5 },
		ex3: { name: "Max Penetrations", value: 0 },
		ex4: { name: "Armor Breaking", value: 100, percent: true },
		ex6: { name: "Auto Reload", value: 0, boolean: true },
		ex7: { name: "Proximity Fear Chance", value: 0, percent: true },
		ex8: { name: "Damage % as Fire", value: 0, percent: true },
		ex10: { name: "Double Barrel", value: 0, boolean: true },
		ex11: { name: "Shotgun Jump", value: 0, boolean: true },
		ex12: { name: "Base Spread", value: 100, percent: true }
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
					ammo: { name: "Max Ammo", value: 8 }
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
				name: "Double-Sized Buckshot",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "Bigger and heavier handcrafted specialist dwarf buckshot. Accept no substitute.",
				stats: {
					dmg: { name: "Damage", value: 3 }
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
				name: "Double Trigger",
				icon: "Icon_Upgrade_FireRate",
				type: "Rate of Fire",
				text:
					"Tweaked trigger mechanism allows you to unload both barrels in quick succession dealing massive damage to anything in front of you.",
				stats: {
					rate: { name: "Rate of Fire", value: 7.5 }
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
				name: "Quickfire Ejector",
				icon: "Icon_Upgrade_Speed",
				type: "Reload Speed",
				text:
					"Experience, training, and a couple of under-the-table design \"adjustments\" means your gun can be reloaded significantly faster. ",
				stats: {
					reload: { name: "Reload Time", value: 0.7, subtract: true }
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
			}
		],
		[
			{
				selected: false,
				name: "Stun",
				icon: "Icon_Upgrade_Stun",
				type: "Stun",
				text: "Stunned enemies are incapacitated for a longer period of time.",
				stats: {
					ex9: { name: "Stun Duration", value: 2.5 }
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
				name: "Expanded Ammo Bags",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "You had to give up some sandwich-storage, but your total ammo capacity is increased!",
				stats: {
					ammo: { name: "Max Ammo", value: 12 }
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
				name: "High Capacity Shells",
				icon: "Icon_Upgrade_Shotgun_Pellet",
				type: "Pellet Count",
				text:
					"It took some creating thinking, but we finally found out how to pack more buckshot into each shell. Just... Handle with care, they're liable to take your eye out.",
				stats: {
					ex1: { name: "Pellets", value: 3 }
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
			}
		],
		[
			{
				selected: false,
				name: "Super Blowthrough Rounds",
				icon: "Icon_Upgrade_BulletPenetration",
				type: "Blow Through",
				text:
					"Shaped projectiles designed to over-penetrate targets with a minimal loss of energy. In other words: Fire straight through several enemies at once!",
				stats: {
					ex3: { name: "Max Penetrations", value: 3 }
				},
				cost: {
					credits: 3800,
					bismor: 0,
					croppa: 0,
					enorPearl: 36,
					jadiz: 25,
					magnite: 15,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Tungsten Coated Buckshot",
				icon: "Icon_Upgrade_ArmorBreaking",
				type: "Armor Breaking",
				text:
					"We're proud of this one. Armor shredding. Tear through that high-impact plating of those big buggers like butter. What could be finer?",
				stats: {
					ex4: { name: "Armor Breaking", value: 300, percent: true }
				},
				cost: {
					credits: 3800,
					bismor: 0,
					croppa: 25,
					enorPearl: 0,
					jadiz: 15,
					magnite: 0,
					umanite: 36,
					err: 0
				}
			},
			{
				selected: false,
				name: "Improved Blast Wave",
				icon: "Icon_Upgrade_Special",
				type: "Special",
				text:
					"The Shockwave from the blast deals extra damage to any enemies unlucky enough to be in the area extending 4m in front of you.",
				stats: {
					ex5: { name: "Front AoE shock wave Damage", value: 20 }
				},
				cost: {
					credits: 3800,
					bismor: 36,
					croppa: 0,
					enorPearl: 25,
					jadiz: 0,
					magnite: 15,
					umanite: 0,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Auto Reload",
				icon: "Icon_Upgrade_Speed",
				type: "Reload Speed",
				text: "Reloads automatically when unequipped for more than 5 seconds",
				stats: {
					ex6: { name: "Auto Reload", value: 1, boolean: true }
				},
				cost: {
					credits: 4400,
					bismor: 40,
					croppa: 0,
					enorPearl: 60,
					jadiz: 0,
					magnite: 0,
					umanite: 110,
					err: 0
				}
			},
			{
				selected: false,
				name: "Fear the Boomstick",
				icon: "Icon_Upgrade_ScareEnemies",
				type: "Fear",
				text: "Chance to scare nearby creatures whenever you shoot",
				stats: {
					ex7: { name: "Proximity Fear Chance", value: 50, percent: true }
				},
				cost: {
					credits: 4400,
					bismor: 0,
					croppa: 40,
					enorPearl: 0,
					jadiz: 60,
					magnite: 110,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "White Phosphorous Shells",
				icon: "Icon_Upgrade_Heat",
				type: "Heat",
				text: "Convert some of the damage to Fire damage",
				stats: {
					ex8: { name: "Damage % as Fire", value: 50, percent: true }
				},
				cost: {
					credits: 4400,
					bismor: 60,
					croppa: 0,
					enorPearl: 0,
					jadiz: 110,
					magnite: 0,
					umanite: 40,
					err: 0
				}
			}
		]
	],
	overclocks: [
		{
			selected: false,
			name: "Compact Shells",
			icon: "Icon_Upgrade_Ammo",
			type: "clean",
			cost: {
				credits: 8550,
				bismor: 0,
				croppa: 0,
				enorPearl: 0,
				jadiz: 100,
				magnite: 65,
				umanite: 120,
				err: 0
			},
			text: "You can carry a few more of these compact shells in your pockets and they are a bit faster to reload with.",
			stats: {
				ammo: { name: "Max Ammo", value: 6 },
				reload: { name: "Reload Time", value: 0.2, subtract: true }
			}
		},
		{
			selected: false,
			name: "Double Barrel",
			icon: "Icon_Upgrade_FireRate",
			type: "clean",
			cost: {
				credits: 7950,
				bismor: 0,
				croppa: 100,
				enorPearl: 75,
				jadiz: 0,
				magnite: 0,
				umanite: 125,
				err: 0
			},
			text: "Unload both barrels at once, no regrets.",
			stats: {
				ex10: { name: "Double Barrel", value: 1, boolean: true },
				dmg: { name: "Damage", value: 1 }
			}
		},
		{
			selected: false,
			name: "Special Powder",
			icon: "Icon_Overclock_ShotgunJump",
			type: "clean",
			cost: {
				credits: 7050,
				bismor: 95,
				croppa: 125,
				enorPearl: 65,
				jadiz: 0,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "Less like gunpowder and more like rocketfuel, this mixture gives a hell of a kick that you can use to get places.",
			stats: {
				ex11: { name: "Shotgun Jump", value: 1, boolean: true }
			}
		},
		{
			selected: false,
			name: "Stuffed Shells",
			icon: "Icon_Upgrade_Shotgun_Pellet",
			type: "clean",
			cost: {
				credits: 7850,
				bismor: 100,
				croppa: 0,
				enorPearl: 135,
				jadiz: 0,
				magnite: 0,
				umanite: 80,
				err: 0
			},
			text: "With a bit of patience and some luck you can get one more pellet and a few more grains of powder into each shell without affecting the gun's performance or losing an eye in the process.",
			stats: {
				dmg: { name: "Damage", value: 1 },
				ex1: { name: "Pellets", value: 1 }
			}
		},
		{
			selected: false,
			name: "Shaped Shells",
			icon: "Icon_Upgrade_Aim",
			type: "balanced",
			cost: {
				credits: 7700,
				bismor: 95,
				croppa: 0,
				enorPearl: 70,
				jadiz: 135,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "Specially shaped shells result in a tighter shot but the number of pellets is reduced.",
			stats: {
				ex12: { name: "Base Spread", value: 35, percent: true, subtract: true },
				ex1: { name: "Pellets", value: 2, subtract: true }
			}
		},
		{
			selected: false,
			name: "Jumbo Shells",
			icon: "Icon_Upgrade_DamageGeneral",
			type: "unstable",
			cost: {
				credits: 8800,
				bismor: 65,
				croppa: 0,
				enorPearl: 105,
				jadiz: 125,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "These large shells pack a lot more charge for a big increase in damage but they also take up more space so total ammo is limited.",
			stats: {
				dmg: { name: "Damage", value: 8 },
				ammo: { name: "Max Ammo", value: 10, subtract: true },
				reload: { name: "Reload Time", value: 0.5 }
			}
		}
	]
};
