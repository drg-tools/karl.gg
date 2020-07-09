export default {
	selected: false,
	modified: false,
	name: "Platform Gun",
	class: "support Tool",
	icon: "equipment.E_E_Platform",
	baseStats: {
		clip: { name: "Clip Size", value: 4 },
		ammo: { name: "Max Ammo", value: 16 },
		rate: { name: "Rate of Fire", value: 1 },
		reload: { name: "Reload Time", value: 2 },
		ex1: { name: "Less fall damage", value: 0, boolean: true },
		ex2: { name: "Bug repellent", value: 0, boolean: true }
	},
	mods: [
		[
			{
				selected: false,
				name: "Supercharged Feed Mechanism",
				icon: "Icon_Upgrade_FireRate",
				type: "Rate of Fire",
				text:
					"We overclocked your gun. It fires faster. Don't ask. Just enjoy. Also probably don't tell Management, please.",
				stats: {
					rate: { name: "Rate of Fire", value: 1 }
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
			},
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
				name: "High Capacity Magazine",
				icon: "Icon_Upgrade_ClipSize",
				type: "Magazine Size",
				text: "The good thing about clips, magazines, ammo drums, fuel tanks... you can always get bigger variants.",
				stats: {
					clip: { name: "Clip Size", value: 4 }
				},
				cost: {
					credits: 420,
					bismor: 0,
					croppa: 10,
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
				name: "Plastcrete MKII",
				icon: "Icon_Upgrade_FallDamageResistance",
				type: "Fall Damage Resistance",
				text: "Shock-absorbing compound reduces fall damage",
				stats: {
					ex1: { name: "Less fall damage", value: 1, boolean: true }
				},
				cost: {
					credits: 780,
					bismor: 0,
					croppa: 0,
					enorPearl: 22,
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
				name: "Expanded Ammo Bags",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "You had to give up some sandwich-storage, but your total ammo capacity is increased!",
				stats: {
					ammo: { name: "Max Ammo", value: 8 }
				},
				cost: {
					credits: 960,
					bismor: 0,
					croppa: 16,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 12,
					err: 0
				}
			},
			{
				selected: false,
				name: "Repellant Additive",
				icon: "Icon_Upgrade_Special",
				type: "Special",
				text: "Enemies will avoid waling on the Plastcrete Foam whenever possible.",
				stats: {
					ex2: { name: "Bug repellent", value: 1, boolean: true }
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
		]
	]
};
