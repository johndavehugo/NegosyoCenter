var stepper;


$('#addCustomerModal').on('shown.bs.modal', function() {
    stepper = new Stepper($('.bs-stepper')[0]);

    if ($('#addBusRegion option').length < 2) {
        $.getJSON('../../server-side/address-api.php', { action: 'regions' })
            .done(function (data) {
                var opts = '<option value="" hidden>Select Region</option>';
                data.forEach(function (r) {
                    opts += '<option value="' + r.regionName + '">' + r.regionName + ' — ' + r.name + '</option>';
                });
                $('#addBusRegion, #addEmpRegion').html(opts);
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.log('Region fetch failed:', textStatus, errorThrown);
            });
    }
});



$('#addCustomerModal').on('hidden.bs.modal', function() {
    if (stepper) stepper.reset();
});

