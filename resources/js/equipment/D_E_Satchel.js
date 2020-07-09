export default {
	selected: false,
	modified: false,
	name: "Satchel Charge",
	class: "High Explosive",
	icon: "equipment.D_E_Satchel",
	baseStats: {
		dmg: { name: "Area Damage", value: 375 },
		radius: { name: "Damage Radius", value: 4.5 },
		ammo: { name: "Carried Amount", value: 2 },
		diameter: { name: "Carve Diameter", value: 6.2 },
		ex1: { name: "Extra Fear Radius", value: 0 },
		ex2: { name: "Extra Stagger Radius", value: 0 },
		ex3: { name: "Can be picked up", value: 0, boolean: true },
		ex4: { name: "Unstable explosives", value: 0, boolean: true }
	},
	mods: [
		[
			{
				selected: false,
				name: "Fragmentary Shell",
				icon: "Icon_Upgrade_Area",
				type: "Area of Effect",
				text: "Larger damage radius.",
				stats: {
					radius: { name: "Damage Radius", value: 3 }
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
				name: "Extra Satchel Charge",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "Can carry one more explosive pack.",
				stats: {
					ammo: { name: "Carried Amount", value: 1 }
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
				name: "Bigger Charge",
				icon: "Icon_Upgrade_AreaDamage",
				type: "Area Damage",
				text: "More Damage.",
				stats: {
					dmg: { name: "Area Damage", value: 250 }
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
				name: "Kill Switch",
				icon: "Icon_Upgrade_Special",
				type: "Special",
				text: "Disarm and pick up an unused charge.",
				stats: {
					ex3: { name: "Can be picked up", value: 1, boolean: true }
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
			}
		],
		[
			{
				selected: false,
				name: "Extra Satchel Charge",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "Can carry one more explosive pack.",
				stats: {
					ammo: { name: "Carried Amount", value: 1 }
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
				name: "Volatile compound",
				icon: "Icon_Upgrade_AreaDamage",
				type: "Area Damage",
				text: "New more powerful experimental explosives but can detonate if damaged.",
				stats: {
					dmg: { name: "Area Damage", value: 250 },
					ex4: { name: "Unstable explosives", value: 1, boolean: true }
				},
				cost: {
					credits: 920,
					bismor: 20,
					croppa: 0,
					enorPearl: 14,
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
				name: "Big Bang",
				icon: "Icon_Upgrade_ScareEnemies",
				type: "Fear",
				text: "Chance to scare enemies far from the blast.",
				stats: {
					ex1: { name: "Extra Fear Radius", value: 10 }
				},
				cost: {
					credits: 1000,
					bismor: 0,
					croppa: 0,
					enorPearl: 20,
					jadiz: 0,
					magnite: 32,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Concussive Blast",
				icon: "Icon_Upgrade_Stun",
				type: "Stun",
				text: "Staggers creatures far from the blast.",
				stats: {
					ex2: { name: "Extra Stagger Radius", value: 10 }
				},
				cost: {
					credits: 1000,
					bismor: 0,
					croppa: 32,
					enorPearl: 0,
					jadiz: 20,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Rock Mover",
				icon: "Icon_Upgrade_Digging",
				type: "Digging",
				text: "Blast carves a much larger area.",
				stats: {
					diameter: { name: "Carve Diameter", value: 9 }
				},
				cost: {
					credits: 1000,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 32,
					umanite: 20,
					err: 0
				}
			}
		]
	]
};
