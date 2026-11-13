const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

const province = {
    value: '',
    addEventListener(eventName, callback) {
        assert.equal(eventName, 'change');
        this.onChange = callback;
    },
};

const jakartaGroup = { dataset: { provinsi: 'dki-jakarta' }, hidden: false, disabled: false };
const westJavaGroup = { dataset: { provinsi: 'jawa-barat' }, hidden: false, disabled: false };
const city = {
    selectedIndex: 0,
    options: [
        { parentElement: null },
        { parentElement: jakartaGroup },
        { parentElement: westJavaGroup },
    ],
    querySelectorAll(selector) {
        assert.equal(selector, 'optgroup[data-provinsi]');
        return [jakartaGroup, westJavaGroup];
    },
};
city.options[0].parentElement = city;

const elements = { provinsi: province, kabkota: city };
const document = { getElementById: (id) => elements[id] ?? null };
const script = fs.readFileSync('public/js/region-filter.js', 'utf8');

vm.runInNewContext(script, { document });

assert.equal(jakartaGroup.disabled, false, 'cities remain available before choosing a province');
assert.equal(westJavaGroup.disabled, false, 'cities remain available before choosing a province');

province.value = 'jawa-barat';
province.onChange();
assert.equal(westJavaGroup.hidden, false);
assert.equal(westJavaGroup.disabled, false, 'the selected province city options stay enabled');
assert.equal(jakartaGroup.hidden, true);
assert.equal(jakartaGroup.disabled, true);

city.selectedIndex = 2;
province.value = 'dki-jakarta';
province.onChange();
assert.equal(city.selectedIndex, 0, 'changing province clears a city from the previous province');
assert.equal(jakartaGroup.disabled, false);
assert.equal(westJavaGroup.disabled, true);

console.log('Region filter behavior passed.');
