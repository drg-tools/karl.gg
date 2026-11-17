const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');

const root = path.resolve(__dirname, '../..');
const source = fs.readFileSync(path.join(root, 'resources/js/components/MainStatsDisplay.vue'), 'utf8');
const script = source.match(/<script>([\s\S]*?)<\/script>/)[1]
    .replace("import clone from 'lodash-es/clone';", 'const clone = (value) => [...value];')
    .replace('export default', 'globalThis.component =');
const context = vm.createContext({});
vm.runInContext(script, context);
const data = JSON.parse(fs.readFileSync(path.join(root, 'database/seeders/game_data.json'), 'utf8'));
const crossbow = data.weapons.find((weapon) => weapon.gun_class === 'Crossbow');

function calculate(equipment, mods = [], overclock = null) {
    return context.component.computed.calcStats.call({
        baseStats: equipment.json_stats,
        equipmentClass: equipment.gun_class,
        mods,
        overclock,
    });
}

test('Boltshark shows direct and area damage per bolt and total ammo damage', () => {
    const result = calculate(crossbow);
    assert.equal(result.dpb, '165.00');
    assert.equal(result.dpa, '3465.00');
    assert.equal(result.dps, undefined);
    assert.equal(result.tte, undefined);
});

test('Boltshark upgrades and overclocks never produce NaN or Infinity', () => {
    for (const upgrade of [...crossbow.mods, ...crossbow.overclocks]) {
        const result = calculate(crossbow, [upgrade]);
        assert.ok(Number.isFinite(Number(result.dpb)), upgrade.mod_name || upgrade.overclock_name);
        assert.ok(Number.isFinite(Number(result.dpa)));
        assert.ok(!JSON.stringify(result).includes('NaN'));
        assert.ok(!JSON.stringify(result).includes('Infinity'));
    }
});

test('conventional magazine weapons retain their damage calculations', () => {
    const result = calculate({ gun_class: 'Rifle', json_stats: {
        damage: { name: 'Damage', value: 10 },
        magazine: { name: 'Magazine Size', value: 20 },
        ammo: { name: 'Max Ammo', value: 100 },
        rate: { name: 'Rate of Fire', value: 10 },
        reload: { name: 'Reload Time', value: 2 },
    } });
    assert.equal(result.dps, '50.00');
    assert.equal(result.dpm, 200);
    assert.equal(result.dpa, '1200.00');
});
