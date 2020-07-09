export default {
	selected: true,
	modified: false,
	name: "\"Lead Storm\" Powered Minigun",
	class: "Heavy Weapon",
	icon: "equipment.G_P1_Lead",
	calculateDamage: (stats) => {
		let damagePerSecond;
		let totalDamage;
		let dpsStats = {};
		// todo: minigun should include spinup time in dps, aswell as cooling rate! -> spinup time + how much damage can be done until overheated.
		for (let stat of stats) {
			if (stat.name === "Damage") {
				dpsStats.damage = parseFloat(stat.value);
			} else if (stat.name === "Rate of Fire") {
				dpsStats.rateOfFire = parseFloat(stat.value);
			} else if (stat.name === "Max Ammo") {
				dpsStats.maxAmmo = parseFloat(stat.value);
			}
		}

		damagePerSecond = parseFloat(dpsStats.damage * dpsStats.rateOfFire / 2).toFixed(2);

		totalDamage = parseFloat(dpsStats.damage * dpsStats.maxAmmo / 2).toFixed(0);

		return {
			dps: `${damagePerSecond} (Burst until overheated)`, // damage per second
			dpa: totalDamage // total damage available
		};
	},
	baseStats: {
		dmg: { name: "Damage", value: 10 },
		ammo: { name: "Max Ammo", value: 2400 },
		rate: { name: "Rate of Fire", value: 30 },
		reload: { name: "Cooling Rate", value: 1.5 },
		ex1: { name: "Spinup Time", value: 0.7 },
		ex2: { name: "Spindown Time", value: 3 },
		ex3: { name: "Base Spread", value: 100, percent: true },
		ex4: { name: "Armor Breaking", value: 100, percent: true },
		ex5: { name: "Stun Chance", value: 30, percent: true },
		ex11: { name: "Stun Duration", value: 1 },
		ex6: { name: "Max Penetrations", value: 0 },
		ex7: { name: "Max Stabilization Damage Bonus", value: 0, percent: true },
		ex8: { name: "Critical Overheat", value: 0, boolean: true },
		ex9: { name: "Heat Removed on Kill", value: 0, boolean: true },
		ex10: { name: "Hot Bullets", value: 0, percent: true },
		ex12: { name: "Movement Speed While Using", value: 50, percent: true },
		ex13: { name: "Burning Hell", value: 0, boolean: true },
		ex14: { name: "Heat Generation", value: 100, percent: true },
		ex15: { name: "Ricochet chance on bullets", value: 0, boolean: true }
	},
	mods: [
		[
			{
				selected: false,
				name: "Magnetic Refrigeration",
				icon: "Icon_Upgrade_TemperatureCoolDown",
				type: "Cooling",
				text: "Cooling Rate",
				stats: {
					reload: { name: "Cooling Rate", value: 1.5 }
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
			},
			{
				selected: false,
				name: "Improved Motor",
				icon: "Icon_Upgrade_FireRate",
				type: "Rate of Fire",
				text: "Increased rate of fire and faster gyro stabilization",
				stats: {
					rate: { name: "Rate of Fire", value: 4 }
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
				name: "Improved Platform Stability",
				icon: "Icon_Upgrade_Accuracy",
				type: "Accuracy",
				text: "Increased Accuracy",
				stats: {
					ex3: { name: "Base Spread", value: 80, percent: true, subtract: true }
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
			}
		],
		[
			{
				selected: false,
				name: "Oversized Drum",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "Expanded Ammo Bags",
				stats: {
					ammo: { name: "Max Ammo", value: 600 }
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
			},
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
					credits: 2000,
					bismor: 0,
					croppa: 24,
					enorPearl: 0,
					jadiz: 15,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Hardened Rounds",
				icon: "Icon_Upgrade_ArmorBreaking",
				type: "Armor Breaking",
				text: "Improved armor breaking",
				stats: {
					ex4: { name: "Armor Breaking", value: 200, percent: true }
				},
				cost: {
					credits: 2800,
					bismor: 0,
					croppa: 35,
					enorPearl: 0,
					jadiz: 50,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Stun Duration",
				icon: "Icon_Upgrade_Stun",
				type: "Stun",
				text: "Stunned enemies are incapacitated for a longer period of time",
				stats: {
					ex11: { name: "Stun Duration", value: 1 }
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
			},
			{
				selected: false,
				name: "Blowthrough Rounds",
				icon: "Icon_Upgrade_BulletPenetration",
				type: "Blow Through",
				text: "Shaped bullets capable of passing through a target!",
				stats: {
					ex6: { name: "Max Penetrations", value: 1 }
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
				name: "Variable Chamber Pressure",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "Damage increase when fully stabilized",
				stats: {
					ex7: { name: "Max Stabilization Damage Bonus", value: 15, percent: true }
				},
				cost: {
					credits: 4800,
					bismor: 0,
					croppa: 72,
					enorPearl: 50,
					jadiz: 48,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Lighter Barrel Assembly",
				icon: "Icon_Upgrade_ChargeUp",
				type: "Charge Speed",
				text: "Start killing things sooner with a shorter spinup time",
				stats: {
					ex1: { name: "Spinup Time", value: 0.4, subtract: true }
				},
				cost: {
					credits: 4800,
					bismor: 48,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 72,
					umanite: 50,
					err: 0
				}
			},
			{
				selected: false,
				name: "Magnetic Bearings",
				icon: "Icon_Upgrade_Special",
				type: "Special",
				text: "Barrels keep spinning for a longer time after firing, keeping the gun stable for longer.",
				stats: {
					ex2: { name: "Spindown Time", value: 3 }
				},
				cost: {
					credits: 4800,
					bismor: 50,
					croppa: 72,
					enorPearl: 0,
					jadiz: 0,
					magnite: 48,
					umanite: 0,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Aggressive Venting",
				icon: "Icon_Upgrade_Explosion",
				type: "Explosion",
				text: "Burn everything around you and send enemies running when the minigun overheats.",
				stats: {
					ex8: { name: "Critical Overheat", value: 1, boolean: true }
				},
				cost: {
					credits: 5600,
					bismor: 140,
					croppa: 64,
					enorPearl: 70,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Cold As The Grave",
				icon: "Icon_Upgrade_TemperatureCoolDown",
				type: "Cooling",
				text: "Every kill cools the gun",
				stats: {
					ex9: { name: "Heat Removed on Kill", value: 1, boolean: true }
				},
				cost: {
					credits: 5600,
					bismor: 0,
					croppa: 0,
					enorPearl: 70,
					jadiz: 140,
					magnite: 64,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Hot Bullets",
				icon: "Icon_Upgrade_Heat",
				type: "Heat",
				text: "Rounds fired when the heat meter is red will burn the target",
				stats: {
					ex10: { name: "Hot Bullets", value: 50, percent: true }
				},
				cost: {
					credits: 5600,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 140,
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
			name: "A Little More Oomph!",
			icon: "Icon_Upgrade_DamageGeneral",
			type: "clean",
			cost: {
				credits: 8700,
				bismor: 120,
				croppa: 0,
				enorPearl: 0,
				jadiz: 0,
				magnite: 95,
				umanite: 75,
				err: 0
			},
			text: "Get the most out of each shot without compromising any of the gun's systems. ",
			stats: {
				dmg: { name: "Damage", value: 1 },
				ex1: { name: "Spinup Time", value: 0.2, subtract: true }
			}
		},
		{
			selected: false,
			name: "Thinned Drum Walls ",
			icon: "Icon_Upgrade_TemperatureCoolDown",
			type: "clean",
			cost: {
				credits: 7650,
				bismor: 0,
				croppa: 75,
				enorPearl: 125,
				jadiz: 95,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "Stuff more bullets into the ammo drum by thinning the material in non-critical areas. ",
			stats: {
				ammo: { name: "Max Ammo", value: 300 },
				reload: { name: "Cooling Rate", value: 0.5 }
			}
		},
		{
			selected: false,
			name: "Burning Hell",
			icon: "Icon_Upgrade_Heat",
			type: "balanced",
			cost: {
				credits: 8700,
				bismor: 0,
				croppa: 110,
				enorPearl: 0,
				jadiz: 0,
				magnite: 140,
				umanite: 65,
				err: 0
			},
			text: "Turn the area just infront of the minigun into an even worse place by venting all the combustion gasses forward. However, it does overheat rather quickly. ",
			stats: {
				ex13: { name: "Burning Hell", value: 1, boolean: true },
				ex14: { name: "Heat Generation", value: 150, percent: true }
			}
		},
		{
			selected: false,
			name: "Compact Feed Mechanism",
			icon: "Icon_Upgrade_Ammo",
			type: "balanced",
			cost: {
				credits: 7450,
				bismor: 70,
				croppa: 95,
				enorPearl: 0,
				jadiz: 0,
				magnite: 130,
				umanite: 0,
				err: 0
			},
			text: "More space left for ammo at the cost of a reduced rate of fire.",
			stats: {
				ammo: { name: "Max Ammo", value: 800 },
				rate: { name: "Rate of Fire", value: 4, subtract: true }
			}
		},
		{
			selected: false,
			name: "Exhaust Vectoring",
			icon: "Icon_Upgrade_DamageGeneral",
			type: "balanced",
			cost: {
				credits: 7400,
				bismor: 140,
				croppa: 95,
				enorPearl: 0,
				jadiz: 0,
				magnite: 65,
				umanite: 0,
				err: 0
			},
			text: "Increases damage at a cost to accuracy.",
			stats: {
				dmg: { name: "Damage", value: 2 },
				ex3: { name: "Base Spread", value: 2.5, percent: true, multiply: true }
			}
		},
		{
			selected: false,
			name: "Bullet Hell",
			icon: "Icon_Upgrade_Ricoshet",
			type: "unstable",
			cost: {
				credits: 7600,
				bismor: 0,
				croppa: 0,
				enorPearl: 105,
				jadiz: 0,
				magnite: 140,
				umanite: 75,
				err: 0
			},
			text: "Special bullets that ricochet off all surfaces and even enemies going on to hit nearby targets. However they deal less damage and are less accurate overall.",
			stats: {
				dmg: { name: "Damage", value: 3, subtract: true },
				ex3: { name: "Base Spread", value: 6, percent: true, multiply: true },
				ex15: { name: "Bullet Hell", value: 1, boolean: true }
			}
		},
		{
			selected: false,
			name: "Lead Storm",
			icon: "Icon_Upgrade_DamageGeneral",
			type: "unstable",
			cost: {
				credits: 8800,
				bismor: 0,
				croppa: 0,
				enorPearl: 130,
				jadiz: 100,
				magnite: 65,
				umanite: 0,
				err: 0
			},
			text: "Pushing things to the limit this overclock greatly increases damage output but the kickback makes it almost impossible to move.",
			stats: {
				dmg: { name: "Damage", value: 4 },
				ex12: { name: "Movement Speed While Using", value: 0, percent: true, multiply: true },
				ex5: { name: "Stun Chance", value: 0, percent: true, multiply: true },
				ex11: { name: "Stun Duration", value: 0, multiply: true }
			}
		}
	]
};
