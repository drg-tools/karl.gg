export default {
	selected: false,
	modified: false,
	name: "Flare Gun",
	class: "Support Tool",
	icon: "equipment.S_E_Flare",
	calculateDamage: (stats) => {
		let lightingMinutes;
		let dpsStats = {};
		for (let stat of stats) {
			if (stat.name === "Duration") {
				dpsStats.duration = parseFloat(stat.value);
			} else if (stat.name === "Magazine Size") {
				dpsStats.magazineSize = parseFloat(stat.value);
			} else if (stat.name === "Max Ammo") {
				dpsStats.maxAmmo = parseFloat(stat.value);
			}
		}
		dpsStats.maxAmmo = dpsStats.maxAmmo + dpsStats.magazineSize;

		let lightingSeconds = (dpsStats.maxAmmo) * dpsStats.duration;
		lightingMinutes = lightingSeconds / 60;
		return {
			ex1: lightingMinutes
		};
	},
	baseStats: {
		dmg: { name: "Damage", value: 40 },
		duration: { name: "Duration", value: 75 },
		clip: { name: "Magazine Size", value: 3 },
		ammo: { name: "Max Ammo", value: 12 },
		reload: { name: "Reload Time", value: 2.4 },
		rate: { name: "Rate of Fire", value: 1.0 },
		ex1: { name: "Auto Reload", value: 0, boolean: true }
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
					ammo: { name: "Max Ammo", value: 3 }
				},
				cost: {
					credits: 360,
					bismor: 0,
					croppa: 12,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Thicker Core",
				icon: "Icon_Upgrade_Duration",
				type: "Delay",
				text: "More core material for a longer burn.",
				stats: {
					duration: { name: "Duration", value: 15 }
				},
				cost: {
					credits: 360,
					bismor: 12,
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
				name: "Supercharged Feed Mechanism",
				icon: "Icon_Upgrade_FireRate",
				type: "Rate of Fire",
				text:
					"We overclocked your gun. It fires faster. Don't ask, just enjoy. Also probably don't tell Management, please.",
				stats: {
					rate: { name: "Rate of Fire", value: 3 }
				},
				cost: {
					credits: 700,
					bismor: 0,
					croppa: 0,
					enorPearl: 26,
					jadiz: 0,
					magnite: 0,
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
					clip: { name: "Magazine Size", value: 1 }
				},
				cost: {
					credits: 700,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 26,
					magnite: 0,
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
				text: "Reloads automatically when unequipped for more than 5 seconds.",
				stats: {
					ex1: { name: "Auto Reload", value: 1, boolean: true }
				},
				cost: {
					credits: 920,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 14,
					umanite: 20,
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
					ammo: { name: "Max Ammo", value: 3 }
				},
				cost: {
					credits: 920,
					bismor: 20,
					croppa: 14,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Magnesium Core",
				icon: "Icon_Upgrade_Duration",
				type: "Delay",
				text: "A solid magnesium core is added to the flare, increasing how long it can burn.",
				stats: {
					duration: { name: "Duration", value: 15 }
				},
				cost: {
					credits: 920,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 14,
					magnite: 0,
					umanite: 20,
					err: 0
				}
			}
		]
	]
};
