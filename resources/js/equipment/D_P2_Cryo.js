export default {
	selected: false,
	modified: false,
	name: "Cryo Cannon",
	class: "Heavy Weapon",
	icon: "equipment.D_P2_Cryo",
	calculateDamage: (stats) => {
		let damagePerSecond;
		let totalDamage;
		let rof;
		let dpsStats = {};
		for (let stat of stats) {
			if (stat.name === "Damage") {
				dpsStats.damage = parseFloat(stat.value);
			} else if (stat.name === "Flow Rate") {
				dpsStats.flowRate = parseFloat(stat.value);
			} else if (stat.name === "Tank Capacity") {
				dpsStats.tankCapacity = parseFloat(stat.value);
			}
		}

		rof = (dpsStats.flowRate / 100) * 8;

		damagePerSecond = parseFloat(dpsStats.damage * rof).toFixed(2);

		totalDamage = parseFloat(dpsStats.damage * dpsStats.tankCapacity).toFixed(0);

		return {
			dps: damagePerSecond, // damage per second
			dpa: totalDamage // total damage available
		};
	},
	baseStats: {
		dmg: { name: "Damage", value: 6 },
		clip: { name: "Tank Capacity", value: 400 },
		rate: { name: "Chargeup Time", value: 0.5 },
		reload: { name: "Repressurization Delay", value: 1 },
		ex1: { name: "Cold Stream Reach", value: 10 },
		ex2: { name: "Freezing Power", value: 8 },
		ex3: { name: "Pressure Drop Rate", value: 100, percent: true },
		ex4: { name: "Pressure Gain Rate", value: 100, percent: true },
		ex5: { name: "Frozen Targets can Shatter", value: 0, boolean: true },
		ex6: { name: "Area Cold Damage", value: 0, boolean: true },
		ex7: { name: "Flow Rate", value: 100, percent: true },
		ex8: { name: "Ice Spear", value: 0, boolean: true },
		ex9: { name: "Snowball", value: 0, boolean: true }
	},
	mods: [
		[
			{
				selected: false,
				name: "Larger Pressure Chamber",
				icon: "Icon_Upgrade_ClipSize",
				type: "Magazine Size",
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
				text: "Lets you shoot for a longer time before needing to refill the preassure chamber.",
				stats: {
					ex3: {
						name: "Pressure Drop Rate",
						value: 0.5,
						multiply: true
					}
				}
			},
			{
				selected: false,
				name: "Faster Turbine Spinup",
				icon: "Icon_Upgrade_ChargeUp",
				type: "Charge Speed",
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
				text: "Start freezing things as soon as you pull the trigger with a near-instant turbine response",
				stats: {
					rate: {
						name: "Chargeup Time",
						value: 0.4,
						subtract: true
					}
				}
			},
			{
				selected: false,
				name: "Stronger Cooling Unit",
				icon: "Icon_Upgrade_Cold",
				type: "Cold",
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
				text: "Freeze things at a faster rate.",
				stats: {
					ex2: {
						name: "Freezing Power",
						value: 1
					}
				}
			}
		],
		[
			{
				selected: false,
				name: "Larger Reserve Tank",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
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
				text: "You had to give up some sandwich-storage, but your total ammo capacity is increased!",
				stats: {
					clip: { name: "Tank Capacity", value: 50 }
				}
			},
			{
				selected: false,
				name: "Overclocked Ejection Turbine",
				icon: "Icon_Upgrade_Distance",
				type: "Reach",
				cost: {
					credits: 2800,
					bismor: 0,
					croppa: 0,
					enorPearl: 35,
					jadiz: 0,
					magnite: 50,
					umanite: 0,
					err: 0
				},
				text: "Longer reach",
				stats: {
					ex1: { name: "Cold Stream Reach", value: 5 }
				}
			},
			{
				selected: false,
				name: "Bypassed Integrity Check",
				icon: "Icon_Upgrade_TemperatureCoolDown",
				type: "Overheat",
				cost: {
					credits: 2000,
					bismor: 15,
					croppa: 24,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				},
				text:
					"If you completely empty the pressure chamber, the weapon will immediately start repressurizing, safety be banned",
				stats: {
					reload: { name: "Repressurization Delay", value: 0, multiply: true }
				}
			}
		],
		[
			{
				selected: false,
				name: "Improved Pump",
				icon: "Icon_Upgrade_ChargeUp",
				type: "Charge Speed",
				cost: {
					credits: 2800,
					bismor: 0,
					croppa: 50,
					enorPearl: 0,
					jadiz: 35,
					magnite: 0,
					umanite: 0,
					err: 0
				},
				text: "Refill the pressure chamber faster.",
				stats: {
					ex4: { name: "Pressure Gain Rate", value: 1.7, multiply: true }
				}
			},
			{
				selected: false,
				name: "Increased Flow Volume",
				icon: "Icon_Upgrade_FireRate",
				type: "Rate of Fire",
				cost: {
					credits: 2800,
					bismor: 50,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 35,
					err: 0
				},
				text:
					"We overclocked your gun. It fires faster. Don't ask, just enjoy. Also probably don't tell Management, please.",
				stats: {
					ex7: { name: "Flow Rate", value: 20, percent: true }
				}
			}
		],
		[
			{
				selected: false,
				name: "Hard Mixture",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
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
				text: "Promotes ice-crystal formation in the stream resulting in more direct damage.",
				stats: {
					dmg: { name: "Damage", value: 3 }
				}
			},
			{
				selected: false,
				name: "Supercooling Mixture",
				icon: "Icon_Upgrade_Cold",
				type: "Cold",
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
				text: "Improves the heat capacity of the stream so you can freeze things at a faster rate.",
				stats: {
					ex2: { name: "Freezing Power", value: 1 }
				}
			},
			{
				selected: false,
				name: "Larger Reserve Tank",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				cost: {
					credits: 4800,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 72,
					magnite: 50,
					umanite: 48,
					err: 0
				},
				text: "You had to give up some sandwich-storage, but your total ammo capacity is increased!",
				stats: {
					clip: { name: "Tank Capacity", value: 150 }
				}
			}
		],
		[
			{
				selected: false,
				name: "Fragile",
				icon: "Icon_Upgrade_Explosion",
				type: "Special",
				text: "There is a chance that frozen targets will spontaneously shatter!",
				stats: {
					ex5: { name: "Frozen Targets can Shatter", value: 1, boolean: true }
				},
				cost: {
					credits: 5600,
					bismor: 64,
					croppa: 70,
					enorPearl: 0,
					jadiz: 0,
					magnite: 140,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Cold Radiance",
				icon: "Icon_Upgrade_Cold",
				type: "Cold",
				text: "Any enemy that strays within 5m of you while you are firing the Cryo Cannon will start to freeze.",
				stats: {
					ex6: { name: "Area Cold Damage", value: 1, boolean: true }
				},
				cost: {
					credits: 5600,
					bismor: 0,
					croppa: 0,
					enorPearl: 140,
					jadiz: 70,
					magnite: 0,
					umanite: 64,
					err: 0
				}
			}
		]
	],
	overclocks: [
		{
			selected: false,
			name: "Improved Thermal Efficiency",
			icon: "Icon_Upgrade_ClipSize",
			type: "clean",
			cost: {
				credits: 8350,
				bismor: 0,
				croppa: 125,
				enorPearl: 70,
				jadiz: 0,
				magnite: 110,
				umanite: 0,
				err: 0
			},
			text: "Get all you can from your fuel and lose pressure slower from the main chamber.",
			stats: {
				clip: { name: "Tank Capacity", value: 25 },
				ex3: { name: "Pressure Drop Rate", value: 0.75, multiply: true }
			}
		},
		{
			selected: false,
			name: "Perfectly Tuned Cooler",
			icon: "Icon_Upgrade_Cold",
			type: "clean",
			cost: {
				credits: 8750,
				bismor: 110,
				croppa: 0,
				enorPearl: 0,
				jadiz: 0,
				magnite: 65,
				umanite: 130,
				err: 0
			},
			text: "Freeze things faster, no strings attached.",
			stats: {
				ex2: { name: "Freezing Power", value: 1 }
			}
		},
		{
			selected: false,
			name: "Flow Rate Expansion",
			icon: "Icon_Upgrade_Duration",
			type: "balanced",
			cost: {
				credits: 8900,
				bismor: 0,
				croppa: 0,
				enorPearl: 70,
				jadiz: 100,
				magnite: 125,
				umanite: 0,
				err: 0
			},
			text: "A low volume high pressure chamber paired with a high power pump improve the overall function rate of the weapon but lower the max duration of sustained flow.",
			stats: {
				ex3: { name: "Pressure Drop Rate", value: 2.25, multiply: true },
				ex4: { name: "Pressure Gain Rate", value: 2.7, multiply: true },
				ex7: { name: "Flow Rate", value: 10, percent: true }
			}
		},
		{
			selected: false,
			name: "Ice Spear",
			icon: "Icon_Upgrade_ProjectileSpeed",
			type: "balanced",
			cost: {
				credits: 8950,
				bismor: 0,
				croppa: 0,
				enorPearl: 60,
				jadiz: 130,
				magnite: 0,
				umanite: 110,
				err: 0
			},
			text: "Pressing the reload button dumps all the fluid in the chamber directly into the turbine, flash freezing it and launching an ice projectile. Side effects include an increased recharge delay and of course the large amount of fuel used.",
			stats: {
				reload: { name: "Repressurization Delay", value: 1 },
				ex8: { name: "Ice Spear", value: 1, boolean: true }
			}
		},
		{
			selected: false,
			name: "Ice Storm",
			icon: "Icon_Upgrade_DamageGeneral",
			type: "unstable",
			cost: {
				credits: 7200,
				bismor: 0,
				croppa: 0,
				enorPearl: 130,
				jadiz: 0,
				magnite: 105,
				umanite: 75,
				err: 0
			},
			text: "A change in the fuel mixture along with some heavy cooler unit tweaks means that you'll be doing less freezing and more killing with razor-sharp ice shards. However, the capacity of the pressure chamber suffers.",
			stats: {
				dmg: { name: "Damage", value: 2, multiply: true },
				ex2: { name: "Freezing Power", value: 3, subtract: true },
				clip: { name: "Tank Capacity", value: 50, subtract: true },
				ex3: { name: "Pressure Drop Rate", value: 1.5, multiply: true }
			}
		},
		{
			selected: false,
			name: "Snowball",
			icon: "Icon_Upgrade_Area",
			type: "unstable",
			cost: {
				credits: 8400,
				bismor: 0,
				croppa: 70,
				enorPearl: 0,
				jadiz: 90,
				magnite: 0,
				umanite: 130,
				err: 0
			},
			text: "Pressing the reload button shoots all the cryofuel in the chamber at once as an AoE cryo-projectile. Besides the very large amount of fuel consumed the operation overloads the whole system resulting in a much longer delay before regaining pressure and the modifications restrict the total amount of fuel you can carry.",
			stats: {
				clip: { name: "Tank Capacity", value: 100, subtract: true },
				reload: { name: "Repressurization Delay", value: 1 },
				ex9: { name: "Snowball", value: 1, boolean: true }
			}
		}
	]
};