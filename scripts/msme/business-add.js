var stepper;

function loadCities(prefix, code, parentType) {
    $('#' + prefix + 'City').html('<option value="" hidden>Select City</option>');
    $.getJSON('../../server-side/address-api.php', { action: 'cities', code: code, parent: parentType })
        .done(function (data) {
            var opts = '<option value="" hidden>Select City</option>';
            data.forEach(function (c) {
                opts += '<option value="' + c.name + '" data-code="' + c.code + '">' + c.name + '</option>';
            });
            $('#' + prefix + 'City').html(opts);
        });
}

function loadBarangays(prefix, code) {
    $('#' + prefix + 'Barangay').html('<option value="" hidden>Select Barangay</option>');
    $.getJSON('../../server-side/address-api.php', { action: 'barangays', code: code })
        .done(function (data) {
            var opts = '<option value="" hidden>Select Barangay</option>';
            data.forEach(function (b) {
                opts += '<option value="' + b.name + '" data-code="' + b.code + '">' + b.name + '</option>';
            });
            $('#' + prefix + 'Barangay').html(opts);
        });
}

$(document).on('change', '#addBusRegion, #addEmpRegion', function() {
    var prefix = this.id === 'addBusRegion' ? 'addBus' : 'addEmp';
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

$(document).on('change', '#addBusProvince, #addEmpProvince', function() {
    var prefix = this.id === 'addBusProvince' ? 'addBus' : 'addEmp';
    var provCode = $(this).find(':selected').data('code');
    $('#' + prefix + 'Barangay').html('<option value="" hidden>Select Barangay</option>');
    if (provCode) loadCities(prefix, provCode, 'province');
});

$(document).on('change', '#addBusCity, #addEmpCity', function() {
    var prefix = this.id === 'addBusCity' ? 'addBus' : 'addEmp';
    var cityCode = $(this).find(':selected').data('code');
    if (cityCode) loadBarangays(prefix, cityCode);
});

$('#addCustomerModal').on('shown.bs.modal', function() {
    stepper = new Stepper($('.bs-stepper')[0]);

    if ($('#addBusRegion option').length < 2) {
        $.getJSON('../../server-side/address-api.php', { action: 'regions' })
            .done(function (data) {
                var opts = '<option value="" hidden>Select Region</option>';
                data.forEach(function (r) {
                    opts += '<option value="' + r.regionName + '" data-code="' + r.code + '">' + r.regionName + ' — ' + r.name + '</option>';
                });
                $('#addBusRegion, #addEmpRegion').html(opts);
            });
    }
});

$('#addCustomerModal').on('hidden.bs.modal', function() {
    if (stepper) stepper.reset();
});