export default {
	selected: false,
	modified: false,
	name: "M1000 Classic",
	class: "Semi-Automatic Rifle",
	icon: "equipment.S_P2_M1000",
	baseStats: {
		dmg: { name: "Damage", value: 50 },
		ammo: { name: "Max Ammo", value: 96 },
		clip: { name: "Clip Size", value: 8 },
		rate: { name: "Rate of Fire", value: 4 },
		reload: { name: "Reload Time", value: 2.5 },
		ex1: { name: "Focus Speed", value: 100, percent: true },
		ex2: { name: "Focused Shot Damage Bonus", value: 100, percent: true },
		ex3: { name: "Recoil", value: 100, percent: true },
		ex4: { name: "Max Penetrations", value: 0 },
		ex5: { name: "Weakpoint Damage Bonus", value: 10, percent: true },
		ex6: { name: "Armor Breaking", value: 30, percent: true },
		ex7: { name: "Focused Shot Stun Chance", value: 0, percent: true },
		ex9: { name: "Focus Shot Fear", value: 0, boolean: true },
		ex8: { name: "Focus Mode Movement Speed", value: 30, percent: true },
		ex10: { name: "Focus Shot Kill AoE Fear", value: 0, boolean: true },
		ex11: { name: "Focus Shot Hover", value: 0, boolean: true },
		ex12: { name: "Electrocuting Focus Shots", value: 0, boolean: true }
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
					ammo: { name: "Max Ammo", value: 32 }
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
				name: "Increased Caliber Rounds",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "The good folk in R&D have been busy. The overall damage of your weapon is increased.",
				stats: {
					dmg: { name: "Damage", value: 10 }
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
			}
		],
		[
			{
				selected: false,
				name: "Fast-Charging Coils",
				icon: "Icon_Upgrade_ChargeUp",
				type: "Accuracy",
				text: "Focus faster when holding down the trigger.",
				stats: {
					ex1: { name: "Focus Speed", value: 60, percent: true }
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
				name: "Better Weight Balance",
				icon: "Icon_Upgrade_Recoil",
				type: "Recoil",
				text: "Improved hip-shot accuracy.",
				stats: {
					ex3: { name: "Recoil", value: 0.5, multiply: true }
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
			}
		],
		[
			{
				selected: false,
				name: "Killer Focus",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "Greater focus damage bonus",
				stats: {
					ex2: { name: "Focused Shot Damage Bonus", value: 25, percent: true }
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
			},
			{
				selected: false,
				name: "Extended Clip",
				icon: "Icon_Upgrade_ClipSize",
				type: "Magazine Size",
				text: "The good thing about clips, magazines, ammo drums, fuel tanks... You can always get bigger variants.",
				stats: {
					clip: { name: "Clip Size", value: 6 }
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
					ex4: { name: "Max Penetrations", value: 3 }
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
				name: "Hollow-Point Bullets",
				icon: "Icon_Upgrade_Weakspot",
				type: "Weak Spot Bonus",
				text:
					"Hit 'em where it hurts! Literally! We've upped the damage you'll be able to do to any creatures fleshy bits. You're welcome.",
				stats: {
					ex5: { name: "Weakpoint Damage", value: 25, percent: true }
				},
				cost: {
					credits: 4800,
					bismor: 72,
					croppa: 48,
					enorPearl: 50,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Kinetic Energy Penetrator",
				icon: "Icon_Upgrade_ArmorBreaking",
				type: "Armor Breaking",
				text:
					"We're proud of this one. Armor shredding. Tear through that high-impact plating of those big buggers like butter. What could be finer?",
				stats: {
					ex6: { name: "Armor Breaking", value: 220, percent: true }
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
			}
		],
		[
			{
				selected: false,
				name: "Hitting Where it Hurts",
				icon: "Icon_Upgrade_Stun",
				type: "Stun",
				text: "Focused shots stagger the target",
				stats: {
					ex7: { name: "Focused Shot Stun Chance", value: 100, percent: true }
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
				name: "Precision Terror",
				icon: "Icon_Upgrade_ScareEnemies",
				type: "Fear",
				text: "Killing your target with a focused shot to the weakspot will send nearby creatures fleeing with terror!",
				stats: {
					ex10: { name: "Focus Shot Kill AoE Fear", value: 1, boolean: true }
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
				name: "Killing Machine",
				icon: "Icon_Upgrade_Speed",
				type: "Reload Speed",
				text: "You can perform a lightning fast reload right after killing an enemy.",
				stats: {
					ex10: { name: "Focus Shot Kill AoE Fear", value: 1, boolean: true }
				},
				cost: {
					credits: 5600,
					bismor: 0,
					croppa: 0,
					enorPearl: 140,
					jadiz: 0,
					magnite: 70,
					umanite: 64,
					err: 0
				}
			}
		]
	],
	overclocks: [
		{
			selected: false,
			name: "Hoverclock",
			icon: "Icon_Overclock_Slowdown",
			type: "clean",
			cost: {
				credits: 7350,
				bismor: 105,
				croppa: 135,
				enorPearl: 0,
				jadiz: 65,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "Your movement slows down for a few seconds while using focus mode in the air.",
			stats: {
				ex11: { name: "Focus Shot Hover", value: 1, boolean: true }
			}
		},
		{
			selected: false,
			name: "Minimal Clips",
			icon: "Icon_Upgrade_Ammo",
			type: "clean",
			cost: {
				credits: 8200,
				bismor: 0,
				croppa: 0,
				enorPearl: 95,
				jadiz: 130,
				magnite: 75,
				umanite: 0,
				err: 0
			},
			text: "Make space for more ammo and speed up reloads by getting rid of dead weight on the clips.",
			stats: {
				ammo: { name: "Max Ammo", value: 16 },
				reload: { name: "Reload Time", value: 0.2, subtract: true }
			}
		},
		{
			selected: false,
			name: "Active Stability System",
			icon: "Icon_Upgrade_MovementSpeed",
			type: "balanced",
			cost: {
				credits: 8150,
				bismor: 90,
				croppa: 0,
				enorPearl: 0,
				jadiz: 0,
				magnite: 70,
				umanite: 135,
				err: 0
			},
			text: "Focus without slowing down but the power drain from the coils lowers the power of the focused shots.",
			stats: {
				ex8: { name: "Focus Mode Movement Speed", value: 70, percent: true },
				ex2: { name: "Focused Shot Damage Bonus", value: 25, percent: true, subtract: true }
			}
		},
		{
			selected: false,
			name: "Hipster",
			icon: "Icon_Upgrade_Aim",
			type: "balanced",
			cost: {
				credits: 8900,
				bismor: 0,
				croppa: 125,
				enorPearl: 105,
				jadiz: 0,
				magnite: 0,
				umanite: 80,
				err: 0
			},
			text: "A rebalancing of weight distribution, enlarged vents and a reshaped grip result in a rifle that is more controllable when hip-firing in quick succession but at the cost of pure damage output.",
			stats: {
				ammo: { name: "Max Ammo", value: 1.75, multiply: true },
				rate: { name: "Rate of Fire", value: 3 },
				ex3: { name: "Recoil", value: 0.5, multiply: true },
				dmg: { name: "Damage", value: 0.6, multiply: true }
			}
		},
		{
			selected: false,
			name: "Electrocuting Focus Shots",
			icon: "Icon_Upgrade_Electricity",
			type: "unstable",
			cost: {
				credits: 8850,
				bismor: 120,
				croppa: 95,
				enorPearl: 0,
				jadiz: 0,
				magnite: 0,
				umanite: 75,
				err: 0
			},
			text: "Embedded capacitors in a copper core carry the electric charge from the EM coils used for focus shots and will electrocute the target at the cost of a reduced focus shot damage bonus.",
			stats: {
				ex12: { name: "Electrocuting Focus Shots", value: 1, boolean: true },
				ex2: { name: "Focused Shot Damage Bonus", value: 25, percent: true, subtract: true }
			}
		},
		{
			selected: false,
			name: "Supercooling Chamber",
			icon: "Icon_Upgrade_DamageGeneral",
			type: "unstable",
			cost: {
				credits: 8500,
				bismor: 0,
				croppa: 0,
				enorPearl: 90,
				jadiz: 130,
				magnite: 70,
				umanite: 0,
				err: 0
			},
			text: "Take the M1000'S focus mode to the extreme by supercooling the rounds before firing to improve their acceleration through the coils, but the extra coolant in the clips limits how much ammo you can bring.",
			stats: {
				ex2: { name: "Focused Shot Damage Bonus", value: 125, percent: true },
				ammo: { name: "Max Ammo", value: 0.635, multiply: true },
				ex1: { name: "Focus Speed", value: 0.5, percent: true, multiply: true },
				ex8: { name: "Focus Mode Movement Speed", value: 0, percent: true, multiply: true }
			}
		}
	]
};
