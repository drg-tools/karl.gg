export default {
	selected: false,
	modified: false,
	name: "Reinforced power drills",
	class: "support tool",
	icon: "equipment.D_E_Drill",
	calculateDamage: (stats) => {
		let damagePerSecond;
		let totalDamage;
		let rof;
		let dpsStats = {};
		for (let stat of stats) {
			if (stat.name === "Damage") {
				dpsStats.damage = parseFloat(stat.value);
			} else if (stat.name === "Mining Rate") {
				dpsStats.miningRate = parseFloat(stat.value);
			} else if (stat.name === "Max Fuel") {
				dpsStats.maxFuel = parseFloat(stat.value);
			}
		}
		rof = (dpsStats.miningRate / 100) * 1.5;

		totalDamage = parseFloat(dpsStats.damage * (dpsStats.maxFuel * 3)).toFixed(0);

		damagePerSecond = parseFloat(dpsStats.damage * rof).toFixed(2);

		return {
			dpa: totalDamage, // total damage available
			dps: damagePerSecond // damage per second
		};
	},
	baseStats: {
		dmg: { name: "Damage", value: 5 },
		ammo: { name: "Max Fuel", value: 38 },
		rate: { name: "Mining Rate", value: 100, percent: true },
		ex1: { name: "Overheat Duration", value: 8 },
		ex2: { name: "Cooling Rate", value: 2 },
		ex3: { name: "Movement speed while drilling", value: 0, percent: true },
		ex4: { name: "Heat removal on Damage", value: 0 }
	},
	mods: [
		[
			{
				selected: false,
				name: "Barbed Drills",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "More damage inflicted on enemies",
				stats: {
					dmg: { name: "Damage", value: 5 }
				},
				cost: {
					credits: 420,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 10,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Hardened Drill Tips",
				icon: "Icon_Upgrade_Digging",
				type: "Digging",
				text: "Drill faster",
				stats: {
					rate: { name: "Mining rate", value: 50, percent: true },
					ex3: { name: "Movement speed while drilling", value: 10, percent: true }
				},
				cost: {
					credits: 420,
					bismor: 0,
					croppa: 0,
					enorPearl: 10,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Expanded fuel tanks",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "Carries more fuel",
				stats: {
					ammo: { name: "Max Fuel", value: 6 }
				},
				cost: {
					credits: 420,
					bismor: 10,
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
				name: "Magnetic Refrigeration",
				icon: "Icon_Upgrade_TemperatureCoolDown",
				type: "Cooling",
				text: "Faster cooling for the drills when not in action",
				stats: {
					ex2: { name: "Cooling Rate", value: 1 }
				},
				cost: {
					credits: 780,
					bismor: 0,
					croppa: 22,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Streamlined Integrity Check",
				icon: "Icon_Upgrade_TemperatureCoolDown",
				type: "Overheat",
				text: "Can use drills sooner after an overheat",
				stats: {
					ex1: { name: "Overheat Duration", value: 3, subtract: true }
				},
				cost: {
					credits: 720,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 22,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Supercharged Motor",
				icon: "Icon_Upgrade_Digging",
				type: "Digging",
				text: "Drill faster.",
				stats: {
					rate: { name: "Mining rate", value: 50, percent: true },
					ex3: { name: "Movement speed while drilling", value: 10, percent: true }
				},
				cost: {
					credits: 960,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 16,
					magnite: 12,
					umanite: 0,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Increased Tank Pressure",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "Carries more fuel",
				stats: {
					ammo: { name: "Max Fuel", value: 6 }
				},
				cost: {
					credits: 1100,
					bismor: 0,
					croppa: 24,
					enorPearl: 18,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Bloody Cold Drills",
				icon: "Icon_Upgrade_TemperatureCoolDown",
				type: "Cooling",
				text: "Your drills cool when drilling enemies!",
				stats: {
					ex4: { name: "Heat removal on Damage", value: 1 }
				},
				cost: {
					credits: 1100,
					bismor: 24,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 18,
					umanite: 0,
					err: 0
				}
			}
		]
	]
};

