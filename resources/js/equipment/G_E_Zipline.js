export default {
	selected: false,
	modified: false,
	name: "Zipline Gun",
	class: "Support Tool",
	icon: "equipment.G_E_Zipline",
	baseStats: {
		range: { name: "Max Range", value: 30 },
		ammo: { name: "Max Ammo", value: 3 },
		reload: { name: "Reload Time", value: 1.5 },
		angle: { name: "Max Angle", value: 30 },
		speed: { name: "Movement Speed", value: 250 },
		ex1: { name: "Fall Damage Reduction", value: 0, boolean: true }
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
					ammo: { name: "Max Ammo", value: 1 }
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
				name: "Upgraded Connection Joint",
				icon: "Icon_Upgrade_Angle",
				type: "Angle",
				text: "Increases the operational angle of the zipline",
				stats: {
					angle: { name: "Max Angle", value: 5 }
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
				name: "Reinforced Anchor",
				icon: "Icon_Upgrade_Distance",
				type: "Reach",
				text: "Zipline can span a greater distance",
				stats: {
					range: { name: "Max Range", value: 10 }
				},
				cost: {
					credits: 420,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 10,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Reinforced Cable",
				icon: "Icon_Upgrade_Distance",
				type: "Reach",
				text: "Zipline can span a greater distance",
				stats: {
					range: { name: "Max Range", value: 10 }
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
				name: "Disconnection Protection",
				icon: "Icon_Upgrade_FallDamageResistance",
				type: "Fall Damage Resistance",
				text: "Take less damage if you fall off the zipline",
				stats: {
					ex1: { name: "Fall Damage Reduction", value: 1, boolean: true }
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
			},
			{
				selected: false,
				name: "Increased Motor Traction",
				icon: "Icon_Upgrade_MovementSpeed",
				type: "Movement Speed",
				text: "Can reach a higher top speed on the zipline",
				stats: {
					speed: { name: "Movement Speed", value: 75 }
				},
				cost: {
					credits: 960,
					bismor: 0,
					croppa: 12,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 16,
					err: 0
				}
			}
		]
	]
};
