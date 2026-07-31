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

$('#addBusinessModal').on('shown.bs.modal', function() {
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

$('#addBusinessModal').on('hidden.bs.modal', function() {
    if (stepper) stepper.reset();
});


function addBusiness() {
            const data = {
                //Business
                juri_name:                         $('input[name=addBusinessName]').val(),
                juri_entity_no:                    $('input[name=addEntityNo]').val(),
                line_of_industry:                  $('select[name=addIndustry]').val(),
                capitalization:                    $('input[name=addCapitalization]').val(),
                contact_no:                        $('input[name=addContactNo]').val(),
                contact_email:                     $('input[name=email]').val(),
                
                //Owner
                special_category:                  $('select[name=addSpecialCategory]').val(),
                employer_first_name:                      $('input[name=addFirstName]').val(),
                employer_middle_name:                  $('input[name=addMiddleName]').val(),
                employer_last_name:               $('input[name=addLastName]').val(),

                //juridical address
                juri_region:                    $('textarea[name=addBusRegion]').val()
            };

            fetch('../../api/routes.php/business', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(res => {
                if (res.status === 'success') {
                    alert(res.message);
                    $('#addBusinessModal').modal('hide');                                   
                    $('#tblBusiness').DataTable().ajax.reload();                           
                    document.getElementById('addBusinessForm').reset();
                } else {
                    alert('Error: ' + res.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Network error');
            });
        };