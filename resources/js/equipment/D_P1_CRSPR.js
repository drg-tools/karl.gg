export default {
	selected: true,
	modified: false,
	name: "CRSPR Flamethrower",
	class: "Heavy Weapon",
	icon: "equipment.D_P1_CRSPR",
	calculateDamage: (stats) => {
		let damagePerSecond;
		let totalDamage;
		let rof;
		let dpsStats = {};
		for (let stat of stats) {
			if (stat.name === "Damage") {
				dpsStats.damage = parseFloat(stat.value);
			} else if (stat.name === "Fuel Flow Rate") {
				dpsStats.flowRate = parseFloat(stat.value);
			} else if (stat.name === "Max Fuel") {
				dpsStats.maxFuel = parseFloat(stat.value);
			} else if (stat.name === "Tank Size") {
				dpsStats.tankSize = parseFloat(stat.value);
			}
		}

		dpsStats.maxFuel = dpsStats.maxFuel + dpsStats.tankSize;

		rof = (dpsStats.flowRate / 100) * 6;

		damagePerSecond = parseFloat(dpsStats.damage * rof).toFixed(2);

		totalDamage = parseFloat(dpsStats.damage * dpsStats.maxFuel).toFixed(0);

		return {
			tte: (dpsStats.tankSize / rof).toFixed(2),
			dpm: dpsStats.tankSize * dpsStats.damage,
			dps: damagePerSecond, // damage per second
			dpa: totalDamage // total damage available
		};
	},
	baseStats: {
		dmg: { name: "Damage", value: 10 },
		ex11: { name: "Heat", value: 10 },
		ammo: { name: "Max Fuel", value: 300 },
		clip: { name: "Tank Size", value: 50 },
		rate: { name: "Fuel Flow Rate", value: 100, percent: true },
		reload: { name: "Reload Time", value: 3 },
		ex1: { name: "Increased Sticky Flame Damage", value: 0, boolean: true },
		ex2: { name: "Sticky Flame Burn", value: 0 },
		ex3: { name: "Sticky Flame Slowdown", value: 0 },
		ex4: { name: "Sticky Flame Duration", value: 2 },
		ex5: { name: "Fear Factor", value: 0, percent: true },
		ex6: { name: "Flame Reach", value: 10 },
		ex7: { name: "Area Heat", value: 0 },
		ex9: { name: "Killed Targets Explode %", value: 0, percent: true },
		ex10: { name: "Movement Speed While Using", value: 100, percent: true }
	},
	mods: [
		[
			{
				selected: false,
				name: "High Capacity Tanks",
				icon: "Icon_Upgrade_ClipSize",
				type: "Magazine Size",
				cost: {
					credits: 1200,
					bismor: 0,
					croppa: 0,
					enorPearl: 25,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				},
				text: "The good thing about clips, magazines, ammo drums, fuel tanks... you can always get bigger variants.",
				stats: {
					clip: { name: "Tank Size", value: 25 }
				}
			},
			{
				selected: false,
				name: "High Pressure Ejector",
				icon: "Icon_Upgrade_Distance",
				type: "Reach",
				cost: {
					credits: 1200,
					bismor: 25,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				},
				text: "Increases the range of the flame for long distance incineration.",
				stats: {
					ex6: { name: "Flame Reach", value: 5 }
				}
			}
		],
		[
			// 1/0
			{
				selected: false,
				name: "Unfiltered Fuel",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				cost: {
					credits: 2000,
					bismor: 0,
					croppa: 24,
					enorPearl: 15,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				},
				text: "The good folk in R&D have been busy. The overall damage of your weapon is increased.",
				stats: {
					dmg: { name: "Damage", value: 5 }
				}
			}, // 1/1
			{
				selected: false,
				name: "Triple Filtered Fuel",
				icon: "Icon_Upgrade_Heat",
				type: "Heat",
				cost: {
					credits: 2000,
					bismor: 0,
					croppa: 0,
					enorPearl: 15,
					jadiz: 24,
					magnite: 0,
					umanite: 0,
					err: 0
				},
				text: "Fire it up! You set things ablaze much faster. Time to watch the world burn...!",
				stats: {
					ex11: { name: "Heat", value: 10 }
				}
			}, // 1/2
			{
				selected: false,
				name: "Sticky flame duration",
				icon: "Icon_Upgrade_Duration",
				type: "Delay",
				cost: {
					credits: 2000,
					bismor: 15,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 24,
					umanite: 0,
					err: 0
				},
				text: "Sticky flames duration increase.",
				stats: {
					ex4: { name: "Sticky Flame Duration", value: 3 }
				}
			}
		],
		[
			// 2/0
			{
				selected: false,
				name: "Oversized Valves",
				icon: "Icon_Upgrade_FireRate",
				type: "Rate of Fire",
				cost: {
					credits: 1200,
					bismor: 0,
					croppa: 25,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				},
				text: "Increased fuel consumption rate for more damage.",
				stats: {
					rate: { name: "Fuel Flow Rate", value: 30, percent: true }
				}
			},
			{
				selected: false,
				name: "Sticky Flame Slowdown",
				icon: "Icon_Upgrade_Sticky",
				type: "Slowdown",
				cost: {
					credits: 2800,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 50,
					magnite: 0,
					umanite: 35,
					err: 0
				},
				text: "Creatures moving through sticky flames are slowed.",
				stats: {
					ex3: { name: "Sticky Flame Slowdown", value: 1, boolean: true }
				}
			}, // 2/2
			{
				selected: false,
				name: "More Fuel",
				icon: "Icon_Upgrade_Ammo",
				type: "total ammo",
				cost: {
					credits: 2800,
					bismor: 0,
					croppa: 35,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 50,
					err: 0
				},
				text: "Max Ammo +75",
				stats: {
					ammo: { name: "Max Fuel", value: 75 }
				}
			}
		],
		[
			// 3/0
			{
				selected: false,
				name: "It Burns!",
				icon: "Icon_Upgrade_ScareEnemies",
				type: "Fear",
				cost: {
					credits: 5600,
					bismor: 64,
					croppa: 0,
					enorPearl: 70,
					jadiz: 0,
					magnite: 140,
					umanite: 0,
					err: 0
				},
				text: "A chance that your target will flee in terror for every second that it is in your flame.",
				stats: {
					ex5: { name: "Fear Factor", value: 13, percent: true }
				}
			},
			{
				selected: false,
				name: "Sticky flame duration",
				icon: "Icon_Upgrade_Duration",
				type: "Delay",
				cost: {
					credits: 4800,
					bismor: 0,
					croppa: 0,
					enorPearl: 50,
					jadiz: 72,
					magnite: 48,
					umanite: 0,
					err: 0
				},
				text: "Sticky flames duration increase.",
				stats: {
					ex4: { name: "Sticky Flame Duration", value: 3 }
				}
			},
			{
				selected: false,
				name: "More Fuel",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				cost: {
					credits: 4800,
					bismor: 72,
					croppa: 48,
					enorPearl: 50,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				},
				text: "Max Ammo +75",
				stats: {
					ammo: { name: "Max Fuel", value: 75 }
				}
			}
		],
		[
			{
				selected: false,
				name: "Heat Radiance",
				icon: "Icon_Upgrade_Heat",
				type: "Heat",
				cost: {
					credits: 5600,
					bismor: 70,
					croppa: 140,
					enorPearl: 0,
					jadiz: 0,
					magnite: 64,
					umanite: 0,
					err: 0
				},
				text: "Heat things up in a 5m radius around you.",
				stats: {
					ex7: { name: "Area Heat", value: 10 }
				}
			},
			{
				selected: false,
				name: "Targets Explode",
				icon: "Icon_Upgrade_Explosion",
				type: "explosion",
				cost: {
					credits: 5600,
					bismor: 0,
					croppa: 64,
					enorPearl: 70,
					jadiz: 140,
					magnite: 0,
					umanite: 0,
					err: 0
				},
				text: "Targets killed from direct damage have a chance to explode.",
				stats: {
					ex9: { name: "Killed Targets Explode %", value: 50, percent: true }
				}
			}
		]
	],
	overclocks: [
		{
			selected: false,
			name: "Lighter Tanks",
			icon: "Icon_Upgrade_Ammo",
			type: "clean",
			cost: {
				credits: 7500,
				bismor: 125,
				croppa: 75,
				enorPearl: 0,
				jadiz: 0,
				magnite: 0,
				umanite: 90,
				err: 0
			},
			text: "Lighter weight gear means more fuel and sandwiches.",
			stats: {
				ammo: { name: "Max Fuel", value: 75 }
			}
		},
		{
			selected: false,
			name: "Sticky Additive",
			icon: "Icon_Upgrade_Duration",
			type: "clean",
			cost: {
				credits: 0,
				bismor: 0,
				croppa: 0,
				enorPearl: 0,
				jadiz: 0,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "Special additive compound extends the Sticky Flame duration.",
			stats: {
				dmg: { name: "Damage", value: 1 },
				ex4: { name: "Sticky Flame Duration", value: 1 }
			}
		},
		{
			selected: false,
			name: "Compact Feed Valves",
			icon: "Icon_Upgrade_ClipSize",
			type: "balanced",
			cost: {
				credits: 0,
				bismor: 0,
				croppa: 0,
				enorPearl: 0,
				jadiz: 0,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "The smaller mechanism leaves room to increase tank capacity at the cost of operational range.",
			stats: {
				clip: { name: "Tank Size", value: 25 },
				ex6: { name: "Flame Reach", value: 2, subtract: true }
			}
		},
		{
			selected: false,
			name: "Fuel Stream Diffuser",
			icon: "Icon_Upgrade_Distance",
			type: "balanced",
			cost: {
				credits: 0,
				bismor: 0,
				croppa: 0,
				enorPearl: 0,
				jadiz: 0,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "Increases operational range but decreases the fuel flow rate.",
			stats: {
				ex6: { name: "Flame Reach", value: 5 },
				rate: { name: "Fuel Flow Rate", value: 20, percent: true, subtract: true }
			}
		},
		{
			selected: false,
			name: "Face Melter",
			icon: "Icon_Upgrade_DamageGeneral",
			type: "unstable",
			cost: {
				credits: 0,
				bismor: 0,
				croppa: 0,
				enorPearl: 0,
				jadiz: 0,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "This crazy bit of tweaking will give a boost in damage but at the cost of both mobility and fuel.",
			stats: {
				dmg: { name: "Damage", value: 2 },
				rate: { name: "Fuel Flow Rate", value: 30, percent: true },
				ammo: { name: "Max Fuel", value: 75, subtract: true },
				ex10: { name: "Movement Speed While Using", value: 50, percent: true, subtract: true }
			}
		},
		{
			selected: false,
			name: "Sticky Fuel",
			icon: "Icon_Upgrade_Duration",
			type: "unstable",
			cost: {
				credits: 0,
				bismor: 0,
				croppa: 0,
				enorPearl: 0,
				jadiz: 0,
				magnite: 0,
				umanite: 0,
				err: 0
			},
			text: "Special fues mixture extends the duration and damage of Sticky Flames but at the cost of tank capacity and total fuel.",
			stats: {
				ex1: { name: "Increased Sticky Flame Damage", value: 1, boolean: true },
				ex4: { name: "Sticky Flame Duration", value: 6 },
				clip: { name: "Tank Size", value: 25, subtract: true },
				ammo: { name: "Max Fuel", value: 75, subtract: true }
			}
		}
	]
};
