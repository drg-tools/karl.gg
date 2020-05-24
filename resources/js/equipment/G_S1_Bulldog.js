export default {
	selected: false,
	modified: false,
	name: "\"Bulldog\" Heavy Revolver",
	class: "Revolver",
	icon: "equipment.G_S1_Bulldog",
	baseStats: {
		dmg: { name: "Damage", value: 50 },
		ammo: { name: "Max Ammo", value: 28 },
		clip: { name: "Magazine Size", value: 4 },
		rate: { name: "Rate of Fire", value: 2 },
		reload: { name: "Reload Time", value: 2 },
		ex1: { name: "Base Spread", value: 100, percent: true },
		ex2: { name: "Spread Per Shot", value: 100, percent: true },
		ex3: { name: "Max Penetrations", value: 0 },
		ex4: { name: "Stun Chance", value: 50, percent: true },
		ex10: { name: "Stun Duration", value: 1.5 },
		ex5: { name: "Area Damage", value: 0 },
		ex9: { name: "Effect Radius", value: 0 },
		ex6: { name: "Weakpoint Damage Bonus", value: 15, percent: true },
		ex7: { name: "Dead-Eye", value: 0, boolean: true },
		ex8: { name: "Neurotoxin Coating", value: 0, boolean: true },
		ex11: { name: "Chain Hit", value: 0, boolean: true },
		ex12: { name: "Magic Bullets", value: 0, boolean: true },
		ex13: { name: "Recoil", value: 100, percent: true },
		ex14: { name: "Damage Vs Burning", value: 0, percent: true }
	},
	mods: [
		[
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
				name: "Perfect Weight Balance",
				icon: "Icon_Upgrade_Accuracy",
				type: "Accuracy",
				text: "Improved Accuracy",
				stats: {
					ex1: { name: "Base Spread", value: 70, percent: true, subtract: true }
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
				name: "Increased Caliber Rounds",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "The good folk in R&D have been busy. The overall damage of your weapon is increased.",
				stats: {
					dmg: { name: "Damage", value: 1.3, multiply: true }
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
				name: "Floating Barrel",
				icon: "Icon_Upgrade_Accuracy",
				type: "Accuracy",
				text:
					"Sweet, sweet optimization. We called in a few friends and managed to significantly improve the stability of this gun.",
				stats: {
					ex13: { name: "Recoil", value: 0.75, multiply: true },
					ex2: { name: "Spread Per Shot", value: 80, percent: true, subtract: true }
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
				name: "Expanded Ammo Bags",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "Expanded Ammo Bags",
				stats: {
					ammo: { name: "Max Ammo", value: 12 }
				},
				cost: {
					credits: 1800,
					bismor: 0,
					croppa: 0,
					enorPearl: 12,
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
				name: "Super Blowthrough Rounds",
				icon: "Icon_Upgrade_BulletPenetration",
				type: "Blow Through",
				text:
					"Shaped projectiles capable to over-penetrate targets with a mininal loss of energy. In other words: Fire straight through several enemies at once!",
				stats: {
					ex3: { name: "Max Penetrations", value: 3 }
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
				name: "Explosive Rounds",
				icon: "Icon_Upgrade_Explosion",
				type: "Explosion",
				text: "Bullets detonate creating a radius of damage but deals less direct damage",
				stats: {
					dmg: { name: "Damage", value: 0.5, multiply: true },
					ex5: { name: "Area Damage", value: 30 },
					ex9: { name: "Effect Radius", value: 1.5 }
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
				name: "Hollow-Point Bullets",
				icon: "Icon_Upgrade_Weakspot",
				type: "Weak Spot Bonus",
				text:
					"Hit 'em where it hurts! Literally! We've upped the damage you'll be able to do to any creatures fleshy bits. You're welcome.",
				stats: {
					ex6: { name: "Weakpoint Damage Bonus", value: 50, percent: true }
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
			}
		],
		[
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
				name: "High Velocity Rounds",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "The good folk in R&D have been busy. The overall damage of your weapon is increased.",
				stats: {
					dmg: { name: "Damage", value: 15 }
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
				name: "Dead Eye",
				icon: "Icon_Upgrade_Accuracy",
				type: "Accuracy",
				text: "No aim penalty while moving",
				stats: {
					ex7: { name: "Dead-Eye", value: 1, boolean: true }
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
				name: "Neurotoxin Coating",
				icon: "Icon_Upgrade_Special",
				type: "Neurotoxin",
				text: "Chance to poison your target. Affected creatures move slower and take damage over time.",
				stats: {
					ex8: { name: "Neurotoxin Coating", value: 1, boolean: true }
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
			}
		]
	],
	overclocks: [
		{
			selected: false,
			name: "Homebrew Powder",
			icon: "Icon_Overclock_ChangeOfHigherDamage",
			type: "clean",
			cost: {
				credits: 7350,
				bismor: 0,
				croppa: 135,
				enorPearl: 105,
				jadiz: 0,
				magnite: 70,
				umanite: 0,
				err: 0
			},
			text: "More damage on average but it's a bit inconsistent. ",
			stats: {
				dmg: { name: "Damage", value: 1.1, multiply: true }
			}
		},
		{
			selected: false,
			name: "Chain Hit",
			icon: "Icon_Upgrade_Ricoshet",
			type: "clean",
			cost: {
				credits: 7300,
				bismor: 0,
				croppa: 0,
				enorPearl: 80,
				jadiz: 110,
				magnite: 120,
				umanite: 0,
				err: 0
			},
			text: "Any shot that hits a weakspot has a chance to ricochet into a nearby enemy.",
			stats: {
				ex11: { name: "Chain Hit", value: 1, boolean: true }
			}
		},
		{
			selected: false,
			name: "Volatile Bullets",
			icon: "Icon_Upgrade_FireRate",
			type: "balanced",
			cost: {
				credits: 7350,
				bismor: 0,
				croppa: 130,
				enorPearl: 0,
				jadiz: 110,
				magnite: 60,
				umanite: 0,
				err: 0
			},
			text: "Fuel on the fire! These rounds are extremely effective against burning targets but at the cost of direct damage dealt to unaffected creatures.",
			stats: {
				dmg: { name: "Damage", value: 25, subtract: true },
				ex14: { name: "Damage Vs Burning", value: 300, percent: true }
			}
		},
		{
			selected: false,
			name: "Six Shooter",
			icon: "Icon_Upgrade_ClipSize",
			type: "balanced",
			cost: {
				credits: 7750,
				bismor: 120,
				croppa: 60,
				enorPearl: 0,
				jadiz: 0,
				magnite: 100,
				umanite: 0,
				err: 0
			},
			text: "An updated casing profile lets you squeeze one more round into the cylinder and increases the maximum rate of fire, but all that filling and drilling has compromised the pure damage output of the weapon. ",
			stats: {
				clip: { name: "Magazine Size", value: 2 },
				ammo: { name: "Max Ammo", value: 8 },
				rate: { name: "Rate of Fire", value: 4 },
				reload: { name: "Reload Time", value: 0.5 },
				ex1: { name: "Base Spread", value: 1.5, percent: true, multiply: true }
			}
		},
		{
			selected: false,
			name: "Elephant Rounds",
			icon: "Icon_Upgrade_DamageGeneral",
			type: "unstable",
			cost: {
				credits: 7300,
				bismor: 0,
				croppa: 0,
				enorPearl: 140,
				jadiz: 0,
				magnite: 90,
				umanite: 65,
				err: 0
			},
			text: "Heavy tweaking has made it possible to use modified autocannon rounds in the revolver! The damage is crazy but so is the recoil and you can't carry very many rounds. Also only 3 rounds fit in the gun and reload time is a bit slower but base accuracy is improved.",
			stats: {
				dmg: { name: "Damage", value: 2, multiply: true },
				ammo: { name: "Max Ammo", value: 13, subtract: true },
				clip: { name: "Magazine Size", value: 1, subtract: true },
				reload: { name: "Reload Time", value: 0.5 },
				ex13: { name: "Recoil", value: 2.5, percent: true, multiply: true },
				ex1: { name: "Base Spread", value: 0.5, percent: true, multiply: true },
				ex2: { name: "Spread Per Shot", value: 71, percent: true }
			}
		},
		{
			selected: false,
			name: "Magic Bullets",
			icon: "Icon_Upgrade_Ricoshet",
			type: "unstable",
			cost: {
				credits: 8750,
				bismor: 0,
				croppa: 105,
				enorPearl: 0,
				jadiz: 0,
				magnite: 130,
				umanite: 75,
				err: 0
			},
			text: "Smaller bouncy bullets ricochet off hard surfaces and hit nearby enemies like magic and you can carry a few more due to their compact size. However the overall damage of the weapon is reduced. ",
			stats: {
				ex12: { name: "Magic Bullets", value: 1, boolean: true },
				ammo: { name: "Max Ammo", value: 8 },
				dmg: { name: "Damage", value: 20, subtract: true }
			}
		}
	]
};
