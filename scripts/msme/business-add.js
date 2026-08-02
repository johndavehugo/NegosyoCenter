var stepper;

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