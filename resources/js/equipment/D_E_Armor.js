export default {
	selected: false,
	modified: false,
	name: "Heavy Drill Suit",
	class: "Armor",
	icon: "equipment.X_E_Armor",
	baseStats: {
		shield: { name: "Shield", value: 25 },
		health: { name: "Health", value: 110 },
		fire: { name: "Fire Resistance", value: 0, percent: true },
		delay: { name: "Regeneration Delay", value: 7 },
		rate: { name: "Regeneration Rate", value: 100, percent: true },
		revive: { name: "Revive Invulnerability", value: 3 },
		carry: { name: "Carry Capacity", value: 40 },
		ex1: { name: "AoE Damage On Shield Break", value: 0, boolean: true },
		ex2: { name: "AoE Stun On Shield Break", value: 0, boolean: true }
	},
	mods: [
		[
			{
				selected: false,
				name: "Improved Generator",
				icon: "Icon_Upgrade_Duration",
				type: "Delay",
				text: "Shield begins to regenerate sooner.",
				stats: {
					delay: { name: "Regeneration Delay", value: 1, subtract: true }
				},
				cost: {
					credits: 1400,
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
				name: "Boosted Converter",
				icon: "Icon_Upgrade_ChargeUp",
				type: "Charge Speed",
				text: "Shield regenerates at a much faster rate but after a longer initial delay",
				stats: {
					delay: { name: "Regeneration Delay", value: 2 },
					rate: { name: "Regeneration Rate", value: 100, percent: true }
				},
				cost: {
					credits: 1400,
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
				name: "Bigger Mineral Bag",
				icon: "Icon_Upgrade_Capacity",
				type: "Capacity",
				text: "You can collect more of each mineral before needing to deposit.",
				stats: {
					carry: { name: "Carry Capacity", value: 5 }
				},
				cost: {
					credits: 1400,
					bismor: 0,
					croppa: 0,
					enorPearl: 10,
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
				name: "Overcharger",
				icon: "Icon_Upgrade_Resistance",
				type: "Resistance",
				text: "Your shield can absorb more damage before breaking",
				stats: {
					shield: { name: "Shield", value: 5 }
				},
				cost: {
					credits: 1800,
					bismor: 0,
					croppa: 0,
					enorPearl: 15,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Healthy",
				icon: "Icon_Upgrade_Resistance",
				type: "Resistance",
				text: "Max health increased",
				stats: {
					health: { name: "Health", value: 20 }
				},
				cost: {
					credits: 1800,
					bismor: 0,
					croppa: 15,
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
				name: "Temperature Insulation",
				icon: "Icon_Upgrade_Fire_Resistance",
				type: "Fire Resistance",
				text: "For those who prefer it medium rare. Flames will inflict half as much damage.",
				stats: {
					fire: { name: "Fire Resistance", value: 50, percent: true }
				},
				cost: {
					credits: 2060,
					bismor: 0,
					croppa: 12,
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
				name: "Shockwave",
				icon: "Icon_Upgrade_AreaDamage",
				type: "Area Damage",
				text: "Your shield breaks violently, damaging all enemies around you in the process.",
				stats: {
					ex1: { name: "AoE Damage On Shield Break", value: 1, boolean: true }
				},
				cost: {
					credits: 2150,
					bismor: 0,
					croppa: 0,
					enorPearl: 14,
					jadiz: 0,
					magnite: 22,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Static Discharge",
				icon: "Icon_Upgrade_Electricity",
				type: "Electricity",
				text: "Whenever your shield breaks it releases a static discharge that has a chance to stun nearby enemies.",
				stats: {
					ex2: { name: "AoE Stun On Shield Break", value: 1, boolean: true }
				},
				cost: {
					credits: 2150,
					bismor: 22,
					croppa: 0,
					enorPearl: 0,
					jadiz: 14,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Breathing Room",
				icon: "Icon_Upgrade_Revive",
				type: "Resistance",
				text: "Temporary invulnerability after being revived.",
				stats: {
					revive: { name: "Revive Invulnerability", value: 3 }
				},
				cost: {
					credits: 2150,
					bismor: 14,
					croppa: 0,
					enorPearl: 22,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			}
		]
	]
};
