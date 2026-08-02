async function ensureRegions(selector) {
    if ($(selector + ' option').length >= 2) return;
    var data = await $.getJSON('../../server-side/address-api.php', { action: 'regions' });
    var opts = '<option value="" hidden>Select Region</option>';
    data.forEach(function (r) {
        opts += '<option value="' + r.regionName + '" data-code="' + r.code + '">' + r.regionName + ' — ' + r.name + '</option>';
    });
    $(selector).html(opts);
}

async function prefillAddress(prefix, addr) {
    try {
        var r = await $.getJSON('../../server-side/address-api.php', {
            action: 'addresses',
            region: addr.region || '',
            province: addr.province || '',
            city: addr.city || '',
            barangay: addr.barangay || ''
        });

        var regOpts = '<option value="" hidden>Select Region</option>';
        r.regions.forEach(function (x) {
            regOpts += '<option value="' + x.regionName + '" data-code="' + x.code + '">' + x.regionName + ' — ' + x.name + '</option>';
        });
        $('#' + prefix + 'Region').html(regOpts);
        setSelectValue('#' + prefix + 'Region', addr.region);

        var provOpts = '<option value="" hidden>Select Province</option>';
        r.provinces.forEach(function (x) {
            provOpts += '<option value="' + x.name + '" data-code="' + x.code + '">' + x.name + '</option>';
        });
        $('#' + prefix + 'Province').html(provOpts);
        setSelectValue('#' + prefix + 'Province', addr.province);

        var cityOpts = '<option value="" hidden>Select City</option>';
        r.places.forEach(function (x) {
            cityOpts += '<option value="' + x.name + '" data-code="' + x.code + '" data-type="' + x.type + '">' + x.name + '</option>';
        });
        $('#' + prefix + 'City').html(cityOpts);
        setSelectValue('#' + prefix + 'City', addr.city);

        var brgyOpts = '<option value="" hidden>Select Barangay</option>';
        r.barangays.forEach(function (x) {
            brgyOpts += '<option value="' + x.name + '" data-code="' + x.code + '">' + x.name + '</option>';
        });
        $('#' + prefix + 'Barangay').html(brgyOpts);
        setSelectValue('#' + prefix + 'Barangay', addr.barangay);
    } catch (e) {
        console.log('Address prefill failed:', e);
    }
}