export default {
	selected: false,
	modified: false,
	name: "LMG Gun Platform",
	class: "Sentry Gun",
	icon: "equipment.E_E_Sentry",
	calculateDamage: (stats) => {
		let damagePerSecond;
		let totalDamage;
		let dpsStats = {};
		for (let stat of stats) {
			if (stat.name === "Damage") {
				dpsStats.damage = parseFloat(stat.value);
			} else if (stat.name === "Rate of Fire") {
				dpsStats.rateOfFire = parseFloat(stat.value);
			} else if (stat.name === "Carried Amount") {
				dpsStats.carriedAmount = parseFloat(stat.value);
			} else if (stat.name === "Sentry Ammo Capacity") {
				dpsStats.sentryAmmoCapacity = parseFloat(stat.value);
			}
		}

		dpsStats.carriedAmount = dpsStats.carriedAmount + dpsStats.sentryAmmoCapacity;

		damagePerSecond = parseFloat(dpsStats.damage * dpsStats.rateOfFire).toFixed(2);

		totalDamage = parseFloat(dpsStats.damage * dpsStats.carriedAmount).toFixed(0);

		return {
			dps: damagePerSecond, // damage per second
			dpa: totalDamage //total damage available
		};
	},
	baseStats: {
		ammo: { name: "Carried Amount", value: 425 },
		rate: { name: "Construction Time", value: 4 },
		clip: { name: "Sentry Ammo Capacity", value: 90 },
		reload: { name: "Reload Ammo Per Second", value: 45 },
		dmg: { name: "Damage", value: 5 },
		rof: { name: "Rate of Fire", value: 7.5 },
		range: { name: "Max Targeting Range", value: 20 },
		amount: { name: "Number of Sentries", value: 1 },
		ex1: { name: "Armor Breaking", value: 100, percent: true },
		ex2: { name: "Stun Chance", value: 0, percent: true },
		ex3: { name: "Defender System", value: 0, boolean: true },
		ex4: { name: "Hawkeye System", value: 0, boolean: true }
	},
	mods: [
		[
			{
				selected: false,
				name: "Gemini System",
				icon: "Icon_Upgrade_TwoTurrets",
				type: "Special",
				text: "Can deploy two separate sentries at once.",
				stats: {
					rof: { name: "Rate of Fire", value: 2, multiply: true },
					amount: { name: "Number of Sentries", value: 1 }
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
			},
			{
				selected: false,
				name: "LMG MKII",
				icon: "Icon_Upgrade_StrongerTurret",
				type: "Special",
				text: "An upgraded model with more punch, longer range and greater ammo capacity.",
				stats: {
					clip: { name: "Sentry Ammo Capacity", value: 15 },
					dmg: { name: "Damage", value: 2 },
					range: { name: "Max Targeting Range", value: 5 }
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
				name: "Expanded Ammo Bags",
				icon: "Icon_Upgrade_Ammo",
				type: "Total Ammo",
				text: "You had to give up some sandwich-storage, but your total ammo capacity is increased!",
				stats: {
					ammo: { name: "Carried Amount", value: 90 }
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
			},
			{
				selected: false,
				name: "Quick Deploy",
				icon: "Icon_Upgrade_ChargeUp",
				type: "Charge Speed",
				text: "Takes less time to build a sentry.",
				stats: {
					rate: { name: "Construction Time", value: 2, subtract: true }
				},
				cost: {
					credits: 700,
					bismor: 0,
					croppa: 26,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Widemouth Refill Port",
				icon: "Icon_Upgrade_Speed",
				type: "Reload Speed",
				text: "Refill a turret's ammo faster.",
				stats: {
					reload: { name: "Reload Ammo Per Second", value: 45 }
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
				name: "Penetrating Rounds",
				icon: "Icon_Upgrade_ArmorBreaking",
				type: "Armor Breaking",
				text:
					"We're proud of this one. Armor shredding. Tear through that high-impact plating of those big buggers like butter. What could be finer?",
				stats: {
					ex1: { name: "Armor Breaking", value: 300, percent: true }
				},
				cost: {
					credits: 920,
					bismor: 0,
					croppa: 20,
					enorPearl: 0,
					jadiz: 0,
					magnite: 0,
					umanite: 14,
					err: 0
				}
			},
			{
				selected: false,
				name: "Stun",
				icon: "Icon_Upgrade_Stun",
				type: "Stun",
				text: "Hit 'em so hard they can't recover. Every shot has a chance to stop the target in it's tracks.",
				stats: {
					ex2: { name: "Stun Chance", value: 20, percent: true }
				},
				cost: {
					credits: 920,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 20,
					magnite: 14,
					umanite: 0,
					err: 0
				}
			},
			{
				selected: false,
				name: "Expanded Ammo Capacity",
				icon: "Icon_Upgrade_ClipSize",
				type: "Magazine Size",
				text: "The sentry can fire more shots before refilling.",
				stats: {
					clip: { name: "Sentry Ammo Capacity", value: 30 }
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
				name: "Defender System",
				icon: "Icon_Upgrade_DamageGeneral",
				type: "Special",
				text: "A design variation with greatly increased shot power and a focused scan angle.",
				stats: {
					dmg: { name: "Damage", value: 2, multiply: true },
					ex3: { name: "Defender System", value: 1, boolean: true }
				},
				cost: {
					credits: 1000,
					bismor: 0,
					croppa: 0,
					enorPearl: 0,
					jadiz: 0,
					magnite: 20,
					umanite: 32,
					err: 0
				}
			},
			{
				selected: false,
				name: "Hawkeye System",
				icon: "Icon_Upgrade_Accuracy",
				type: "Special",
				text: "Upgraded sensors increase the effective range and target acquisition speed of the LMG and are synchronised with your laser pointer, prioritizing the target you highlight.",
				stats: {
					range: { name: "Max Targeting Range", value: 15 },
					ex4: { name: "Hawkeye System", value: 1, boolean: true }
				},
				cost: {
					credits: 1000,
					bismor: 0,
					croppa: 0,
					enorPearl: 32,
					jadiz: 0,
					magnite: 0,
					umanite: 20,
					err: 0
				}
			}
		]
	]
};
