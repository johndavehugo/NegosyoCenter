function loadCities(prefix, code, parentType) {
    $('#' + prefix + 'City').html('<option value="" hidden>Select City</option>');
    $.getJSON('../../server-side/address-api.php', { action: 'cities', code: code, parent: parentType })
        .done(function (data) {
            var opts = '<option value="" hidden>Select City</option>';
            data.forEach(function (c) {
                opts += '<option value="' + c.name + '" data-code="' + c.code + '" data-type="' + c.type + '">' + c.name + '</option>';
            });
            $('#' + prefix + 'City').html(opts);
        });
}

function loadBarangays(prefix, code, type) {
    $('#' + prefix + 'Barangay').html('<option value="" hidden>Select Barangay</option>');
    $.getJSON('../../server-side/address-api.php', { action: 'barangays', code: code, type: type })
        .done(function (data) {
            var opts = '<option value="" hidden>Select Barangay</option>';
            data.forEach(function (b) {
                opts += '<option value="' + b.name + '" data-code="' + b.code + '">' + b.name + '</option>';
            });
            $('#' + prefix + 'Barangay').html(opts);
        });
}

$(document).on('change', '#addBusRegion, #addEmpRegion, #updBusRegion, #updEmpRegion',  function() {
    var prefix = this.id.replace('Region', '');
    var regionCode = $(this).find(':selected').data('code');

    $('#' + prefix + 'Province').html('<option value="" hidden>Select Province</option>');
    $('#' + prefix + 'City').html('<option value="" hidden>Select City</option>');
    $('#' + prefix + 'Barangay').html('<option value="" hidden>Select Barangay</option>');

    if (!regionCode) return;

    $.getJSON('../../server-side/address-api.php', { action: 'provinces', code: regionCode })
        .done(function (data) {
            if (data.length) {
                var opts = '<option value="" hidden>Select Province</option>';
                data.forEach(function (p) {
                    opts += '<option value="' + p.name + '" data-code="' + p.code + '">' + p.name + '</option>';
                });
                $('#' + prefix + 'Province').html(opts);
            } else {
                loadCities(prefix, regionCode, 'region');
            }
        });
});

$(document).on('change', '#addBusProvince, #addEmpProvince, #updBusProvince, #updEmpProvince', function() {
    var prefix = this.id.replace('Province', '');
    var provCode = $(this).find(':selected').data('code');

    $('#' + prefix + 'Barangay').html('<option value="" hidden>Select Barangay</option>');
    if (provCode) loadCities(prefix, provCode, 'province');
});

$(document).on('change', '#addBusCity, #addEmpCity, #updBusCity, #updEmpCity', function() {
    var prefix = this.id.replace('City', '');
    var cityCode = $(this).find(':selected').data('code');
    var type = $(this).find(':selected').data('type') || 'city';

    if (cityCode) loadBarangays(prefix, cityCode, type);
});