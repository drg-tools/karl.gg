export default {
	selected: false,
	modified: false,
	name: "Shield Generator",
	class: "Support Tool",
	icon: "equipment.G_E_Shield",
	baseStats: {
		ammo: { name: "Carried Amount", value: 1 },
		radius: { name: "Shield Radius", value: 2.7 },
		duration: { name: "Duration", value: 6 },
		rate: { name: "Shield Regen per second", value: 10 },
		reload: { name: "Recharge Time", value: 12.5 },
		ex1: { name: "Protection Time After Leaving", value: 0, boolean: true }
	},
	mods: [
		[
			{
				selected: false,
				name: "Streamlined Integrity Check",
				icon: "Icon_Upgrade_ChargeUp",
				type: "Charge Speed",
				text: "Shield recharges faster.",
				stats: {
					reload: { name: "Recharge Time", value: 1, subtract: true }
				},
				cost: {
					credits: 360,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 12,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Improved Projector",
				icon: "Icon_Upgrade_Area",
				type: "Area of effect",
				text: "Shield protects a larger area.",
				stats: {
					radius: { name: "Shield Radius", value: 0.5 }
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
			}
		],
		[
			{
				selected: false,
				name: "Fast Charging Capacitors",
				icon: "Icon_Upgrade_ChargeUp",
				type: "Charge Speed",
				text: "Generator recharges faster.",
				stats: {
					reload: { name: "Recharge Time", value: 2, subtract: true }
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
				name: "Larger Capacitors",
				icon: "Icon_Upgrade_Duration",
				type: "Delay",
				text: "Shield lasts longer.",
				stats: {
					duration: { name: "Duration", value: 1.5 }
				},
				cost: {
					credits: 700,
					bismor: 26,
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
				name: "Supercharged Coils",
				icon: "Icon_Upgrade_Area",
				type: "Area of effect",
				text: "Shield protects a larger area.",
				stats: {
					radius: { name: "Shield Radius", value: 0.8 }
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
				name: "Lasting Effect",
				icon: "Icon_Upgrade_Resistance",
				type: "Resistance",
				text: "The shield's protective properties linger for a few seconds after you leave the shield.",
				stats: {
					ex1: { name: "Protection Time After Leaving", value: 1, boolean: true }
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
			},
			{
				selected: false,
				name: "Improved Efficiency",
				icon: "Icon_Upgrade_Duration",
				type: "Delay",
				text: "Shield lasts longer.",
				stats: {
					duration: { name: "Duration", value: 1.5 }
				},
				cost: {
					credits: 920,
					bismor: 0,
					croppa: 0,
					enorPearl: 14,
					jadiz: 0,
					magnite: 0,
					umanite: 20,
					err: 0
				}
			}
		]
	]
};
