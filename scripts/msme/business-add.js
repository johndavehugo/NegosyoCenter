var stepper;

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
    var type = $(this).find(':selected').data('type') || 'city';

    if (cityCode) loadBarangays(prefix, cityCode, type);
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
                juri_entity_no:                    $('input[name=addBusEntityNo]').val(),
                line_of_industry:                  $('select[name=addIndustry]').val(),
                capitalization:                    $('input[name=addCapitalization]').val(),
                contact_no:                        $('input[name=addContactNo]').val(),
                contact_email:                     $('input[name=addEmail]').val(),
                
                //Owner
                employer_first_name:               $('input[name=addFirstName]').val(),
                employer_middle_name:              $('input[name=addMiddleName]').val(),
                employer_last_name:                $('input[name=addLastName]').val(),
                employer_entity_no:                $('input[name=addEmpEntityNo]').val(),
                special_category:                  $('select[name=addSpecialCategory]').val(),

                //juridical address
                juri_region:                       $('select[name=addBusRegion]').val(),
                juri_province:                     $('select[name=addBusProvince]').val(),
                juri_city:                         $('select[name=addBusCity]').val(),
                juri_barangay:                     $('select[name=addBusBarangay]').val(),
                juri_street:                       $('input[name=addBusStreet]').val(),
                juri_subdivision:                  $('input[name=addBusSubdivision]').val(),
                juri_upblb_num:                    $('input[name=addBusUpblb]').val(),

                //Owner address
                employer_region:                   $('select[name=addEmpRegion]').val(),
                employer_province:                 $('select[name=addEmpProvince]').val(),
                employer_city:                     $('select[name=addEmpCity]').val(),
                employer_barangay:                 $('select[name=addEmpBarangay]').val(),
                employer_street:                   $('input[name=addEmpStreet]').val(),
                employer_subdivision:              $('input[name=addEmpSubdivision]').val(),
                employer_upblb_num:                $('input[name=addEmpUpblb]').val(),
               

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