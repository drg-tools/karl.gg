export default {
	selected: true,
	modified: false,
	name: "Deepcore GK2",
	class: "Assault Rifle",
	icon: "equipment.S_P1_GK2",
	baseStats: {
		dmg: { name: "Damage", value: 15 },
		ammo: { name: "Max Ammo", value: 350 },
		clip: { name: "Magazine Size", value: 25 },
		rate: { name: "Rate of Fire", value: 7 },
		reload: { name: "Reload Time", value: 1.8 },
		ex1: { name: "Weakpoint Stun Duration", value: 1.5 },
		ex2: { name: "Weakpoint Stun Chance", value: 10, percent: true },
		ex3: { name: "Base Spread", value: 100, percent: true },
		ex4: { name: "Recoil", value: 100, percent: true },
		ex5: { name: "Weakpoint Damage Bonus", value: 10, percent: true },
		ex6: { name: "Armor Breaking", value: 100, percent: true },
		ex7: { name: "Battle Frenzy", value: 0, boolean: true },
		ex8: { name: "Battle Cool", value: 0, boolean: true },
		ex9: { name: "Bonus Damage to Afflicted Targets", value: 0, percent: true },
		ex10: { name: "Spread Recovery Speed", value: 100, percent: true },
		ex11: { name: "Electric Reload (100% chance)", value: 0, boolean: true }
	},
	mods: [
		[
			{
				selected: false,
				name: "Gyro Stabilisation",
				icon: "Icon_Upgrade_Accuracy",
				type: "Accuracy",
				text: "Base accuracy improvement with pin-point accuracy on first shot",
				stats: {
					ex3: { name: "Base Spread", value: 100, percent: true, subtract: true }
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
				name: "Supercharged Feed Mechanism",
				icon: "Icon_Upgrade_FireRate",
				type: "Rate of Fire",
				text:
					"We overclocked your gun. It fires faster. Don't ask, just enjoy. Also probably don't tell Management, please.",
				stats: {
					rate: { name: "Rate of Fire", value: 2 }
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
				name: "Increased Caliber Rounds",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "The good folk in R&D have been busy. The overall damage of your weapon is increased.",
				stats: {
					dmg: { name: "Damage", value: 2 }
				},
				cost: {
					credits: 2000,
					bismor: 0,
					croppa: 0,
					enorPearl: 24,
					jadiz: 0,
					magnite: 15,
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
					ammo: { name: "Max Ammo", value: 100 }
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
			}
		],
		[
			{
				selected: false,
				name: "Floating Barrel",
				icon: "Icon_Upgrade_Recoil",
				type: "Accuracy",
				text:
					"Sweet, sweet optimization. We called in a few friends and managed to significantly improve the stability of this gun.",
				stats: {
					ex4: { name: "Recoil", value: 50, percent: true, subtract: true }
				},
				cost: {
					credits: 2800,
					bismor: 50,
					croppa: 25,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Improved Propellant",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "The good folk in R&D have been busy. The overall damage of your weapon is increased.",
				stats: {
					dmg: { name: "Damage", value: 1 }
				},
				cost: {
					credits: 2800,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 35,
					magnite: 50,
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
					clip: { name: "Magazine Size", value: 10 }
				},
				cost: {
					credits: 2800,
					bismor: 35,
					croppa: 50,
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
				name: "Hollow-Point Bullets",
				icon: "Icon_Upgrade_Weakspot",
				type: "Weak Spot Bonus",
				text:
					"Hit 'em where it hurts! Literally! We've upped the damage you'll be able to do to any creatures fleshy bits. You're welcome.",
				stats: {
					ex5: { name: "Weakpoint Damage Bonus", value: 20, percent: true }
				},
				cost: {
					credits: 4800,
					bismor: 72,
					croppa: 50,
					enorPearl: 0,
					jadiz: 48,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Penetrating Rounds",
				icon: "Icon_Upgrade_ArmorBreaking",
				type: "Armor Breaking",
				text:
					"We're proud of this one. Armor shredding. Tear through that high-impact plating of those big buggers like butter. What could be finer?",
				stats: {
					ex6: { name: "Armor Breaking", value: 500, percent: true }
				},
				cost: {
					credits: 4800,
					bismor: 0,
					croppa: 72,
					enorPearl: 48,
					jadiz: 50,
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
					rate: { name: "Rate of Fire", value: 2 }
				},
				cost: {
					credits: 4800,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 50,
					magnite: 48,
					umanite: 72,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Battle Frenzy",
				icon: "Icon_Upgrade_MovementSpeed",
				type: "Movement Speed",
				text: "Move faster for a short time after killing an enemy",
				stats: {
					ex7: { name: "Battle Frenzy", value: 1, boolean: true }
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
				name: "Battle Cool",
				icon: "Icon_Upgrade_Accuracy",
				type: "Accuracy",
				text: "Killing an enemy increases accuracy",
				stats: {
					ex8: { name: "Battle Cool", value: 1, boolean: true }
				},
				cost: {
					credits: 5600,
					bismor: 0,
					croppa: 140,
					enorPearl: 0,
					jadiz: 70,
					magnite: 64,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Stun",
				icon: "Icon_Upgrade_Stun",
				type: "Stun",
				text: "Increased chance to stun the target",
				stats: {
					ex2: { name: "Weakpoint Stun Chance", value: 30, percent: true }
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
			}
		]
	],
	overclocks: [
		{
			selected: false,
			name: "Compact Ammo",
			icon: "Icon_Upgrade_ClipSize",
			type: "clean",
			cost: {
				credits: 7250,
				bismor: 125,
				croppa: 0,
				enorPearl: 80,
				jadiz: 105,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "Stuff a few more of these compact rounds into each magazine and they have a bit less recoil as well.",
			stats: {
				clip: { name: "Magazine Size", value: 5 },
				ex4: { name: "Recoil", value: 0.7, percent: true, multiply: true }
			}
		},
		{
			selected: false,
			name: "Gas Rerouting",
			icon: "Icon_Upgrade_FireRate",
			type: "clean",
			cost: {
				credits: 7800,
				bismor: 0,
				croppa: 60,
				enorPearl: 0,
				jadiz: 125,
				magnite: 105,
				umanite: 0,
				err: 0
			},
			text: "Increases the weapon's rate of fire without affecting performance and helps with magazine ejection as well.",
			stats: {
				rate: { name: "Rate of Fire", value: 1 },
				reload: { name: "Reload Time", value: 0.3, subtract: true }
			}
		},
		{
			selected: false,
			name: "Homebrew Powder",
			icon: "Icon_Overclock_ChangeOfHigherDamage",
			type: "clean",
			cost: {
				credits: 8100,
				bismor: 95,
				croppa: 0,
				enorPearl: 0,
				jadiz: 140,
				magnite: 0,
				umanite: 65,
				err: 0
			},
			text: "More damage on average but it's a bit inconsistent.",
			stats: {
				dmg: { name: "Damage", value: 1.1, multiply: true }
			}
		},
		{
			selected: false,
			name: "Overclocked Firing Mechanism",
			icon: "Icon_Upgrade_FireRate",
			type: "balanced",
			cost: {
				credits: 7950,
				bismor: 95,
				croppa: 0,
				enorPearl: 120,
				jadiz: 0,
				magnite: 65,
				umanite: 0,
				err: 0
			},
			text: "Fires more bullets faster and it kicks like a mule.",
			stats: {
				rate: { name: "Rate of Fire", value: 3 },
				ex4: { name: "Recoil", value: 2.5, percent: true, multiply: true }
			}
		},
		{
			selected: false,
			name: "Bullets of Mercy",
			icon: "Icon_Upgrade_DamageGeneral",
			type: "balanced",
			cost: {
				credits: 8100,
				bismor: 90,
				croppa: 80,
				enorPearl: 0,
				jadiz: 0,
				magnite: 125,
				umanite: 0,
				err: 0
			},
			text: "Put suffering bugs out of their missery with a damage bonus against afflicted enemies.",
			stats: {
				ex9: { name: "Bonus Damage to Afflicted Targets", value: 33, percent: true },
				clip: { name: "Magazine Size", value: 5, subtract: true }
			}
		},
		{
			selected: false,
			name: "AI Stability Engine",
			icon: "Icon_Upgrade_Aim",
			type: "unstable",
			cost: {
				credits: 8250,
				bismor: 0,
				croppa: 60,
				enorPearl: 125,
				jadiz: 0,
				magnite: 0,
				umanite: 100,
				err: 0
			},
			text: "It's like it knows what you are going to do before you do it, compensating for all recoil and bullet spread but the system requires a lower rate of fire and the modified firing chamber reduces overall damage.",
			stats: {
				ex4: { name: "Recoil", value: 0, percent: true, multiply: true },
				ex10: { name: "Spread Recovery Speed", value: 9, percent: true, multiply: true },
				dmg: { name: "Damage", value: 1, subtract: true },
				rate: { name: "Rate of Fire", value: 2, subtract: true }
			}
		},
		{
			selected: false,
			name: "Electrifying Reload",
			icon: "Icon_Overclock_Special_Magazine",
			type: "unstable",
			cost: {
				credits: 7750,
				bismor: 105,
				croppa: 0,
				enorPearl: 0,
				jadiz: 0,
				magnite: 65,
				umanite: 135,
				err: 0
			},
			text: "Embedded capacitors have a chance to electrocute targets from the inside when you reload. Probability of electrocution increases with the number of hits. However all that tech reduces raw damage of the bullets and takes up some space in the magazines.",
			stats: {
				ex11: { name: "Electric Reload (100% chance)", value: 1, boolean: true },
				dmg: { name: "Damage", value: 3, subtract: true },
				clip: { name: "Magazine Size", value: 5, subtract: true }
			}
		}
	]
};
