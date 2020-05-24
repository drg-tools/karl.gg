export default {
	selected: false,
	modified: false,
	name: "\"Stubby\" Voltaic SMG",
	class: "Submachine Gun",
	icon: "equipment.E_P2_Stubby",
	baseStats: {
		dmg: { name: "Damage", value: 9 },
		ammo: { name: "Max Ammo", value: 420 },
		clip: { name: "Magazine Size", value: 30 },
		rate: { name: "Rate of Fire", value: 11 },
		reload: { name: "Reload Time", value: 2 },
		spread: { name: "Base Spread", value: 100, percent: true },
		ex1: { name: "Electric Damage", value: 0 },
		ex2: { name: "Electrocution %", value: 20, percent: true },
		ex3: { name: "Recoil", value: 100, percent: true },
		ex4: { name: "Weakpoint Damage Bonus", value: 0, percent: true },
		ex5: { name: "Damage vs Electrically Affected", value: 0, percent: true },
		ex6: { name: "Electrocution AoE", value: 0, percent: true },
		ex7: { name: "Turret Arc (10m range)", value: 0, boolean: true },
		ex8: { name: "Turret EM Discharge (5m range)", value: 0, boolean: true }
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
					dmg: { name: "Damage", value: 2 }
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
				name: "Upgraded Capacitors",
				icon: "Icon_Upgrade_Electricity",
				type: "Electricity",
				text: "Better chance to electrocute target",
				stats: {
					ex2: { name: "Electrocution %", value: 30, percent: true }
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
				name: "Expanded Ammo Bags",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "You had to give up some sandwich-storage, but your total ammo capacity is increased!",
				stats: {
					ammo: { name: "Max Ammo", value: 120 }
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
				name: "High Capacity Magazine",
				icon: "Icon_Upgrade_ClipSize",
				type: "Magazine Size",
				text: "The good thing about clips, magazines, ammo drums, fuel tanks... You can always get bigger variants.",
				stats: {
					clip: { name: "Magazine Size", value: 10 }
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
				name: "Recoil Dampener",
				icon: "Icon_Upgrade_Recoil",
				type: "Recoil",
				text:
					"Qaulity engineering, the best lasercut parts, the tender loving care of a dedicated R&D Department. The recoil of your gun is drastically reduced.",
				stats: {
					ex3: { name: "Recoil", value: 50, percent: true, subtract: true }
				},
				cost: {
					credits: 2000,
					bismor: 24,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 15,
					err: 0
				}
			},
			{
				selected: false,
				name: "Improved Gas System",
				icon: "Icon_Upgrade_FireRate",
				type: "Rate of Fire",
				text:
					"We overclocked your gun. It fires faster. Don't ask. Just enjoy. Also probably don't tell Management, please.",
				stats: {
					rate: { name: "Rate of Fire", value: 3 }
				},
				cost: {
					credits: 2800,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 35,
					umanite: 50,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "High Velocity Rounds",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "The good folk in R&D have been busy. The overall damage of your weapon is increased.",
				stats: {
					dmg: { name: "Damage", value: 2 }
				},
				cost: {
					credits: 2800,
					bismor: 0,
					croppa: 0,
					enorPearl: 35,
					jadiz: 50,
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
					ammo: { name: "Max Ammo", value: 120 }
				},
				cost: {
					credits: 2000,
					bismor: 0,
					croppa: 24,
					enorPearl: 0,
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
				name: "Hollow-Point Bullets",
				icon: "Icon_Upgrade_Weakspot",
				type: "Weak Spot Damage",
				text:
					"Hit 'em where it hurts! Literally! We've updated the damage you'll be able to do to any creatures fleshy bits. You're welcome.",
				stats: {
					ex4: { name: "Weakpoint Damage Bonus", value: 30, percent: true }
				},
				cost: {
					credits: 4800,
					bismor: 0,
					croppa: 48,
					enorPearl: 50,
					jadiz: 72,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Conductive Bullets",
				icon: "Icon_Upgrade_Electricity",
				type: "Electricity",
				text: "More damage to targets that are in an electric field",
				stats: {
					ex5: { name: "Damage vs Electrically Affected", value: 30, percent: true }
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
			}
		],
		[
			{
				selected: false,
				name: "Magazine Capacity Tweak",
				icon: "Icon_Upgrade_ClipSize",
				type: "ClipSize",
				text: "Greatly increased magazine capacity",
				stats: {
					clip: { name: "Magazine Size", value: 20 }
				},
				cost: {
					credits: 5600,
					bismor: 64,
					croppa: 70,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 140,
					err: 0
				}
			},
			{
				selected: false,
				name: "Electric Arc",
				icon: "Icon_Upgrade_Electricity",
				type: "Electricity",
				text: "Chance for electrocution to arc from one target to others",
				stats: {
					ex6: { name: "Electrocution AoE", value: 25, percent: true }
				},
				cost: {
					credits: 5600,
					bismor: 64,
					croppa: 140,
					enorPearl: 0,
					jadiz: 70,
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
			name: "Super-Slim Rounds",
			icon: "Icon_Upgrade_ClipSize",
			type: "clean",
			cost: {
				credits: 8550,
				bismor: 90,
				croppa: 130,
				enorPearl: 75,
				jadiz: 0,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "Same power but in a smaller package giving slightly better accuracy and letting you fit a few more rounds in each magazine.",
			stats: {
				clip: { name: "Magazine Size", value: 5 },
				spread: { name: "Base Spread", value: 0.8, percent: true, multiply: true }

			}
		},
		{
			selected: false,
			name: "Well Oiled Machine",
			icon: "Icon_Upgrade_FireRate",
			type: "clean",
			cost: {
				credits: 8400,
				bismor: 0,
				croppa: 65,
				enorPearl: 0,
				jadiz: 95,
				magnite: 140,
				umanite: 0,
				err: 0
			},
			text: "When you need a little more sustained damage.",
			stats: {
				rate: { name: "Rate of Fire", value: 2 },
				reload: { name: "Reload Time", value: 0.2, subtract: true }
			}
		},
		{
			selected: false,
			name: "EM Refire Booster",
			icon: "Icon_Upgrade_FireRate",
			type: "balanced",
			cost: {
				credits: 8300,
				bismor: 60,
				croppa: 0,
				enorPearl: 90,
				jadiz: 135,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "Use the electron circuit of the SMG to boost its fire rate and damage but the accuracy suffers as a result.",
			stats: {
				ex1: { name: "Electric Damage", value: 2 },
				rate: { name: "Rate of Fire", value: 4 },
				spead: { name: "Base Spread", value: 1.5, percent: true, multiply: true }
			}
		},
		{
			selected: false,
			name: "Light-Weight Rounds",
			icon: "Icon_Upgrade_Ammo",
			type: "balanced",
			cost: {
				credits: 8700,
				bismor: 90,
				croppa: 0,
				enorPearl: 0,
				jadiz: 65,
				magnite: 0,
				umanite: 135,
				err: 0
			},
			text: "They don't hit quite as hard, and can't handle fast fire rates but you sure can carry a lot more of them!",
			stats: {
				ammo: { name: "Max Ammo", value: 180 },
				dmg: { name: "Damage", value: 1, subtract: true },
				rate: { name: "Rate of Fire", value: 2, subtract: true }
			}
		},
		{
			selected: false,
			name: "Turret Arc",
			icon: "Icon_Upgrade_Electricity",
			type: "unstable",
			cost: {
				credits: 8350,
				bismor: 100,
				croppa: 135,
				enorPearl: 0,
				jadiz: 0,
				magnite: 0,
				umanite: 60,
				err: 0
			},
			text: "Use the gemini turrests as nodes in an electric arc. Zap! The downside is less ammo and a slower rate of fire.",
			stats: {
				ex7: { name: "Turret Arc (10m range)", value: 1, boolean: true },
				ammo: { name: "Max Ammo", value: 120, subtract: true },
				rate: { name: "Rate of Fire", value: 2, subtract: true }
			}
		},
		{
			selected: false,
			name: "Turret EM Discharge",
			icon: "Icon_Upgrade_AreaDamage",
			type: "unstable",
			cost: {
				credits: 8450,
				bismor: 80,
				croppa: 0,
				enorPearl: 105,
				jadiz: 125,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "Use a turret as the epicenter of an electric explosion! The bullet modifications unfortunately also lower the direct damage and electrocution chance.",
			stats: {
				ex8: { name: "Turret EM Discharge (5m range)", value: 1, boolean: true },
				ex2: { name: "Electrocution %", value: 5, percent: true, subtract: true },
				dmg: { name: "Damage", value: 3, subtract: true }
			}
		}
	]
};
