export default {
	selected: true,
	modified: false,
	name: "APD-B317",
	class: "All-Purpose Drone",
	icon: "equipment.R_P1_Bosco",
	baseStats: {
		dmg: { name: "Damage", value: 4 },
		light: { name: "Light Radius", value: 10 },
		revive: { name: "Revive", value: 2 },
		ex1: { name: "Mining Expert", value: 0, percent: true },
		ex2: { name: "Rocket Attacks", value: 0 },
		ex3: { name: "Rocket Area Damage", value: 0 },
		ex4: { name: "Rocket Effect Radius", value: 0 },
		ex5: { name: "Rocket Regeneration Time", value: 0 },
		ex6: { name: "Rocket Fear Factor", value: 0, percent: true },
		ex7: { name: "Rocket Armor Breaking", value: 100, percent: true },
		ex8: { name: "Cryo Rocket", value: 0, boolean: true },
		ex9: { name: "Electrocution Chance", value: 0, percent: true }
	},
	mods: [
		[
			{
				selected: false,
				name: "High Velocity Rounds",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "The overall damage of its weapon is increased.",
				stats: {
					dmg: { name: "Damage", value: 2 }
				},
				cost: {
					credits: 400,
					bismor: 0,
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
				name: "Extra Revive",
				icon: "Icon_Upgrade_PlusOne",
				type: "Revive",
				text: "One extra revive kit to bring back to action an incapacitated dwarf.",
				stats: {
					revive: { name: "Revive", value: 1 }
				},
				cost: {
					credits: 400,
					bismor: 0,
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
				name: "Mining Expert",
				icon: "Icon_Upgrade_Digging",
				type: "Digging",
				text: "Improves Bosco's capabilites to mine minerals, dirt, or any kind of terrain.",
				stats: {
					ex1: { name: "Mining Expert", value: 100, percent: true }
				},
				cost: {
					credits: 400,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 25,
					umanite: 0,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Rockets",
				icon: "Icon_Upgrade_Bosco_Rocket_Upgrade",
				type: "Rockets",
				text:
					"A secondary weapon that can shoot explosive rockets. Bosco's AI is not ready to handle this kind of weaponary, use the Laser Pointer for manual activation.",
				stats: {
					ex2: { name: "Rocket Attacks", value: 2 },
					ex3: { name: "Rocket Area Damage", value: 120 },
					ex4: { name: "Rocket Effect Radius", value: 2.5 },
					ex5: { name: "Rocket Regeneration Time", value: 90 }
				},
				cost: {
					credits: 1200,
					bismor: 0,
					croppa: 0,
					enorPearl: 20,
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
				name: "Let There Be Light",
				icon: "Icon_Upgrade_Light_Intensity",
				type: "Light",
				text: "A significant lumens increase that can make the difference in some of the darkest caves.",
				stats: {
					light: { name: "Light Radius", value: 20 }
				},
				cost: {
					credits: 1000,
					bismor: 0,
					croppa: 5,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 10,
					err: 0
				}
			},
			{
				selected: false,
				name: "Extra Revive",
				icon: "Icon_Upgrade_PlusOne",
				type: "Revive",
				text: "One extra revive kit to bring back to action an incapacitated dwarf.",
				stats: {
					revive: { name: "Revive", value: 1 }
				},
				cost: {
					credits: 1000,
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
				name: "Extra Rocket",
				icon: "Icon_Upgrade_Ammo",
				type: "Ammo",
				text: "Increases the total amount of rockets Bosco can fire before regenerating.",
				stats: {
					ex2: { name: "Rocket Attacks", value: 1 }
				},
				cost: {
					credits: 1000,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 10,
					umanite: 0,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Fear The Rocket",
				icon: "Icon_Upgrade_ScareEnemies",
				type: "Fear",
				text: "There is a chance to scare enemies away from the explosion of the rockets.",
				stats: {
					ex6: { name: "Rocket Fear Factor", value: 50, percent: true }
				},
				cost: {
					credits: 1800,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 30,
					err: 0
				}
			},
			{
				selected: false,
				name: "High Frequency Explosion",
				icon: "Icon_Upgrade_ArmorBreaking",
				type: "Armor Breaking",
				text: "Higher chance for the rocket to shatter armor.",
				stats: {
					ex7: { name: "Rocket Armor Breaking", value: 600, percent: true }
				},
				cost: {
					credits: 1800,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 30,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Cryo Explosion",
				icon: "Icon_Upgrade_Cold",
				type: "Cold",
				text:
					"Adds cryogenic liquid to the explosion of the rocket. Cold slows down enemies and can eventually freeze them. The rocket deals almost no damage, but the area of effect increases.",
				stats: {
					ex8: { name: "Cryo Rocket", value: 1, boolean: true },
					ex3: { name: "Rocket Area Damage", value: 20, subtract: true },
					ex4: { name: "Rocket Effect Radius", value: 1 }
				},
				cost: {
					credits: 1800,
					bismor: 0,
					croppa: 0,
					enorPearl: 30,
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
				name: "Bigger Explosion",
				icon: "Icon_Upgrade_Area",
				type: "Area",
				text: "Increased area of effect for the explosion of the rocket.",
				stats: {
					ex4: { name: "Rocket Effect Radius", value: 2 }
				},
				cost: {
					credits: 2200,
					bismor: 0,
					croppa: 20,
					enorPearl: 0,
					jadiz: 0,
					magnite: 30,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Fast Regeneration",
				icon: "Icon_Upgrade_ChargeUp",
				type: "Charge Speed",
				text: "It takes half the time for the rockets to be ready to shoot again.",
				stats: {
					ex5: { name: "Rocket Regeneration Time", value: 50, subtract: true }
				},
				cost: {
					credits: 2200,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 20,
					magnite: 0,
					umanite: 30,
					err: 0
				}
			},
			{
				selected: false,
				name: "Overcharged Rounds",
				icon: "Icon_Upgrade_Electricity",
				type: "Electrocution",
				text: "Bullets coming out of the primary weapon have a chance to electrocute.",
				stats: {
					ex9: { name: "Electrocution Chance", value: 30, percent: true }
				},
				cost: {
					credits: 5600,
					bismor: 70,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 64,
					umanite: 140,
					err: 0
				}
			}
		]
	]
};
