(() => {
    const province = document.getElementById('provinsi');
    const city = document.getElementById('kabkota');

    if (!province || !city) return;

    const groups = [...city.querySelectorAll('optgroup[data-provinsi]')];

    const filterCities = () => {
        const selectedProvince = province.value;
        const selectedOption = city.options[city.selectedIndex];
        const selectedGroup = selectedOption?.parentElement;

        if (selectedGroup?.dataset?.provinsi && selectedGroup.dataset.provinsi !== selectedProvince) {
            city.selectedIndex = 0;
        }

        for (const group of groups) {
            const isAvailable = !selectedProvince || group.dataset.provinsi === selectedProvince;

            group.hidden = !isAvailable;
            group.disabled = !isAvailable;
        }
    };

    province.addEventListener('change', filterCities);
    filterCities();
})();
