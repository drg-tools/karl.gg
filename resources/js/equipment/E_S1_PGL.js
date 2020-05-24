export default {
	selected: false,
	modified: false,
	name: "Deepcore 40mm PGL",
	class: "Heavy Weapon",
	icon: "equipment.E_S1_PGL",
	calculateDamage: (stats) => {
		let directDamagePerSecond;
		let areaDamagePerSecond;
		let damagePerSecond;
		let directDamagePerBullet;
		let areaDamagePerBullet;
		let damagePerBullet;
		let directDamagePerMagazine;
		let areaDamagePerMagazine;
		let damagePerMagazine;
		let totalDirectDamage;
		let totalAreaDamage;
		let totalDamage;
		let dpsStats = {};
		for (let stat of stats) {
			if (stat.name === "Direct Damage") {
				dpsStats.directDamage = parseFloat(stat.value);
			} else if (stat.name === "Area Damage") {
				dpsStats.areaDamage = parseFloat(stat.value);
			} else if (stat.name === "Clip Size") {
				dpsStats.magazineSize = parseFloat(stat.value);
			} else if (stat.name === "Rate of Fire") {
				dpsStats.rateOfFire = parseFloat(stat.value);
			} else if (stat.name === "Reload Time") {
				dpsStats.reloadTime = parseFloat(stat.value);
			} else if (stat.name === "Max Ammo") {
				dpsStats.maxAmmo = parseFloat(stat.value);
			}
		}
		dpsStats.damage = dpsStats.directDamage + dpsStats.areaDamage;

		let timeToEmpty = dpsStats.magazineSize / dpsStats.rateOfFire;
		let damageTime = timeToEmpty + dpsStats.reloadTime;

		directDamagePerMagazine = dpsStats.directDamage * dpsStats.magazineSize;
		areaDamagePerMagazine = dpsStats.areaDamage * dpsStats.magazineSize;
		damagePerMagazine = dpsStats.damage * dpsStats.magazineSize;

		directDamagePerSecond = parseFloat(directDamagePerMagazine / damageTime).toFixed(2);
		areaDamagePerSecond = parseFloat(areaDamagePerMagazine / damageTime).toFixed(2);
		damagePerSecond = parseFloat(damagePerMagazine / damageTime).toFixed(2);

		directDamagePerBullet = dpsStats.directDamage;
		areaDamagePerBullet = dpsStats.areaDamage;
		damagePerBullet = dpsStats.damage;

		totalDirectDamage = dpsStats.directDamage * dpsStats.maxAmmo;
		totalAreaDamage = dpsStats.areaDamage * dpsStats.maxAmmo;
		totalDamage = dpsStats.damage * dpsStats.maxAmmo;

		return {
			dps: `${damagePerSecond} (Direct: ${directDamagePerSecond} / Area: ${areaDamagePerSecond})`, // damage per second
			dpb: `${damagePerBullet} (Direct: ${directDamagePerBullet} / Area: ${areaDamagePerBullet})`, // damage per bullet
			dpa: `${totalDamage} (Direct: ${totalDirectDamage} / Area: ${totalAreaDamage})` // total damage available
		};
	},
	baseStats: {
		dmg: { name: "Area Damage", value: 110 },
		ammo: { name: "Max Ammo", value: 8 },
		clip: { name: "Clip Size", value: 1 },
		rate: { name: "Rate of Fire", value: 2 },
		reload: { name: "Reload Time", value: 2 },
		ex1: { name: "Effect Radius", value: 2.5 },
		ex2: { name: "Armor Breaking", value: 50, percent: true },
		ex3: { name: "Fear Factor", value: 100, percent: true },
		ex4: { name: "Projectile Velocity", value: 100, percent: true },
		ex5: { name: "% Converted to Fire", value: 0, percent: true },
		ex6: { name: "Stun Chance", value: 0, percent: true },
		ex7: { name: "Proximity Trigger", value: 0, boolean: true },
		ex8: { name: "Direct Damage", value: 0 },
		ex10: { name: "RJ250 Compound", value: 0, boolean: true }
	},
	mods: [
		[
			{
				selected: false,
				name: "Fragmentary Shell",
				icon: "Icon_Upgrade_Area",
				type: "Area of effect",
				text: "Damage radius increase",
				stats: {
					ex1: { name: "Effect Radius", value: 1 }
				},
				cost: {
					credits: 1000,
					bismor: 20,
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
				name: "Extra Ammo",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "Expanded Ammo Bags",
				stats: {
					ammo: { name: "Max Ammo", value: 2 }
				},
				cost: {
					credits: 1000,
					bismor: 0,
					croppa: 0,
					enorPearl: 20,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "HE Compound",
				icon: "Icon_Upgrade_AreaDamage",
				type: "Area Damage",
				text: "The good folk in R&D have been busy. The overall damage of your weapon is increased.",
				stats: {
					dmg: { name: "Area Damage", value: 15 }
				},
				cost: {
					credits: 1000,
					bismor: 20,
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
				name: "Expanded Ammo Bags",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "Expanded Ammo Bags",
				stats: {
					ammo: { name: "Max Ammo", value: 3 }
				},
				cost: {
					credits: 1800,
					bismor: 0,
					croppa: 18,
					enorPearl: 0,
					jadiz: 12,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Larger Payload",
				icon: "Icon_Upgrade_AreaDamage",
				type: "Area Damage",
				text: "More bang for the buck! Increases the damage done within the Area of Effect!",
				stats: {
					dmg: { name: "Area Damage", value: 20 }
				},
				cost: {
					credits: 1800,
					bismor: 18,
					croppa: 0,
					enorPearl: 12,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "High Velocity Grenades",
				icon: "Icon_Upgrade_ProjectileSpeed",
				type: "Projectile Speed",
				text:
					"We souped up the ejection mechanisms of your gun, so the projectiles are now fired at a much higher velocity.",
				stats: {
					ex4: { name: "Projectile Velocity", value: 180, percent: true }
				},
				cost: {
					credits: 1800,
					bismor: 0,
					croppa: 20,
					enorPearl: 0,
					jadiz: 18,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Incendiary Compound",
				icon: "Icon_Upgrade_Heat",
				type: "Heat",
				text: "50% damage converted to heat. This will reduce direct Damage, but will set enemies on fire.",
				stats: {
					ex5: { name: "% Converted to Fire", value: 50, percent: true }
				},
				cost: {
					credits: 2200,
					bismor: 0,
					croppa: 20,
					enorPearl: 0,
					jadiz: 30,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Pressure Wave",
				icon: "Icon_Upgrade_ArmorBreaking",
				type: "Armor Breaking",
				text:
					"We're proud of this one. Armor shredding. Tear through that high-impact plating of those bug buggers like butter. What could be finer?",
				stats: {
					ex2: { name: "Armor Breaking", value: 500, percent: true }
				},
				cost: {
					credits: 2200,
					bismor: 0,
					croppa: 0,
					enorPearl: 20,
					jadiz: 0,
					magnite: 30,
					umanite: 0,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Homebrew Explosive",
				icon: "Icon_Overclock_ChangeOfHigherDamage",
				type: "Randomized Damage",
				text: "More damage on average but it's a bit inconsistent with a spread from 80% to 140%",
				stats: {
					dmg: { name: "Area Damage", value: 1.1, multiply: true }
				},
				cost: {
					credits: 3800,
					bismor: 36,
					croppa: 0,
					enorPearl: 0,
					jadiz: 15,
					magnite: 25,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Nails + Tape",
				icon: "Icon_Upgrade_Area",
				type: "Area of effect",
				text:
					"Fire in the hole! The Area of Effect is increased. (We advise keeping the term \"safe distance\" close to your heart)",
				stats: {
					ex1: { name: "Effect Radius", value: 1.5 }
				},
				cost: {
					credits: 3800,
					bismor: 0,
					croppa: 25,
					enorPearl: 15,
					jadiz: 36,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Concussive Blast",
				icon: "Icon_Upgrade_Stun",
				type: "Stun",
				text: "Stuns creatures within the blast radius",
				stats: {
					ex6: { name: "Stun Chance", value: 100, percent: true }
				},
				cost: {
					credits: 3800,
					bismor: 0,
					croppa: 0,
					enorPearl: 36,
					jadiz: 25,
					magnite: 15,
					umanite: 0,
					err: 0
				}
			}
		],
		[
			{
				selected: false,
				name: "Proximity Trigger",
				icon: "Icon_Upgrade_Special",
				type: "Special",
				text:
					"Launched grenades will only detonate when they are in close proximity to an enemy or after the projectile comes to a complete stop. Note: The trigger takes a moment to arm, indicated by a green light, and until then the grenade functions as usual.",
				stats: {
					ex7: { name: "Proximity Trigger", value: 1, boolean: true }
				},
				cost: {
					credits: 4400,
					bismor: 40,
					croppa: 60,
					enorPearl: 0,
					jadiz: 0,
					magnite: 110,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Spiky Grenade",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Damage",
				text: "Deals damage on direct impact",
				stats: {
					ex8: { name: "Direct Damage", value: 60 }
				},
				cost: {
					credits: 4400,
					bismor: 0,
					croppa: 60,
					enorPearl: 0,
					jadiz: 40,
					magnite: 0,
					umanite: 110,
					err: 0
				}
			}
		]
	],
	overclocks: [
		{
			selected: false,
			name: "Clean Sweep",
			icon: "Icon_Upgrade_Area",
			type: "clean",
			cost: {
				credits: 8100,
				bismor: 105,
				croppa: 0,
				enorPearl: 70,
				jadiz: 0,
				magnite: 0,
				umanite: 135,
				err: 0
			},
			text: "Increases the explosion radius and damage without any unwanted effects.",
			stats: {
				dmg: { name: "Area Damage", value: 10 },
				ex1: { name: "Effect Radius", value: 0.5 }
			}
		},
		{
			selected: false,
			name: "Pack Rat",
			icon: "Icon_Upgrade_Ammo",
			type: "clean",
			cost: {
				credits: 7950,
				bismor: 80,
				croppa: 0,
				enorPearl: 105,
				jadiz: 0,
				magnite: 120,
				umanite: 0,
				err: 0
			},
			text: "You found a way to pack away two more rounds somewhere",
			stats: {
				ammo: { name: "Max Ammo", value: 2 }
			}
		},
		{
			selected: false,
			name: "Compact Rounds",
			icon: "Icon_Upgrade_Ammo",
			type: "balanced",
			cost: {
				credits: 7900,
				bismor: 120,
				croppa: 0,
				enorPearl: 100,
				jadiz: 0,
				magnite: 0,
				umanite: 70,
				err: 0
			},
			text: "Smaller and lighter rounds means more rounds in the pocket at the cost of the explosion's effective radius and damage",
			stats: {
				ammo: { name: "Max Ammo", value: 4 },
				dmg: { name: "Area Damage", value: 10, subtract: true },
				ex1: { name: "Effect Radius", value: 0.5, subtract: true }
			}
		},
		{
			selected: false,
			name: "RJ250 Compound",
			icon: "Icon_Overclock_ExplosionJump",
			type: "balanced",
			cost: {
				credits: 8800,
				bismor: 65,
				croppa: 0,
				enorPearl: 110,
				jadiz: 0,
				magnite: 0,
				umanite: 120,
				err: 0
			},
			text: "Trade raw damage for the ability to use explosions to move yourself and your teammates.",
			stats: {
				ex10: { name: "RJ250 Compound", value: 1, boolean: true },
				dmg: { name: "Area Damage", value: 25, subtract: true }
			}
		},
		{
			selected: false,
			name: "Fat Boy",
			icon: "Icon_Upgrade_AreaDamage",
			type: "unstable",
			cost: {
				credits: 8300,
				bismor: 120,
				croppa: 0,
				enorPearl: 70,
				jadiz: 0,
				magnite: 105,
				umanite: 0,
				err: 0
			},
			text: "Big and deadly and dirty. Too bad plutonium is so heavy that you can only take a few rounds with you. And remember to take care with the fallout.",
			stats: {
				dmg: { name: "Area Damage", value: 4, multiply: true },
				ex1: { name: "Effect Radius", value: 1 },
				ammo: { name: "Max Ammo", value: 0.3, multiply: true },
				ex4: { name: "Projectile Velocity", value: 0.7, percent: true, multiply: true }
			}
		},
		{
			selected: false,
			name: "Hyper Propellant",
			icon: "Icon_Upgrade_ProjectileSpeed",
			type: "unstable",
			cost: {
				credits: 8950,
				bismor: 0,
				croppa: 90,
				enorPearl: 0,
				jadiz: 70,
				magnite: 130,
				umanite: 0,
				err: 0
			},
			text: "New super-high velocity projectiles trade explosive range for raw damage in a tight area. The larger rounds also limit the total amount you can carry.",
			stats: {
				ex8: { name: "Direct Damage", value: 250 },
				ex4: { name: "Projectile Velocity", value: 350, percent: true },
				ex1: { name: "Effect Radius", value: 0.3, multiply: true }
			}
		}
	]
};
