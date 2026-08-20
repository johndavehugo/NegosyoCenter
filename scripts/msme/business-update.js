var updStepper;

address.cascade('#updBusRegion', '#updBusProvince', '#updBusCity', '#updBusBarangay');
address.cascade('#updEmpRegion', '#updEmpProvince', '#updEmpCity', '#updEmpBarangay');

bindCurrencyInput($('#updCapitalization'));

$('#updateBusinessModal').on('shown.bs.modal', function () {
    updStepper = new Stepper($('.upd-stepper')[0]);
});

$('#updateBusinessModal').on('hidden.bs.modal', function () {
    if (updStepper) updStepper.reset();
    document.getElementById('updateBusinessForm').reset();

    ['updBusProvince', 'updBusCity', 'updBusBarangay',
        'updEmpProvince', 'updEmpCity', 'updEmpBarangay'].forEach(function (id) {
            var $sel = $('#' + id);
            $sel.find('option').not('[value=""]').remove();
            $sel.val('').trigger('change');
        });
});

async function fillUpdateModal(id) {
    fetch('../../api/routes.php/business/' + id)
        .then(response => response.json())
        .then(async data => {
            if (data.status === 'success') {
                const business = data.data;
                $('#updBusinessName').val(business.juridical.name);
                $('#updBusEntityNo').val(business.juridical.entity_no);
                $('#updCapitalization').val(business.juridical.capitalization ? currencyFormat(business.juridical.capitalization, false) : '');
                $('#updIndustry').val(business.juridical.line_of_industry);
                $('#updContactNo').val(business.juridical.contact_no);
                $('#updEmail').val(business.juridical.contact_email);

                $('#updOwnerName').val(business.employer.full_name);
                $('#updEmpEntityNo').val(business.employer.entity_no);
                $('#updSpecialCategory').val(business.employer.special_category);

                $('#updBusStreet').val(business.juridical.street);
                $('#updBusSubdivision').val(business.juridical.subdivision);
                $('#updBusUpblb').val(business.juridical.upblb_num);

                $('#updEmpStreet').val(business.employer.street);
                $('#updEmpSubdivision').val(business.employer.subdivision);
                $('#updEmpUpblb').val(business.employer.upblb_num);

                $('#updateBusinessModal').modal('show');
                await address.prefill('#updBusRegion', '#updBusProvince', '#updBusCity', '#updBusBarangay', business.juridical);
                await address.prefill('#updEmpRegion', '#updEmpProvince', '#updEmpCity', '#updEmpBarangay', business.employer);
            } else {
                ncSwal.error('Could Not Load Record', data.message || 'The business record could not be fetched. Please try again.');
            }
        })
        .catch(error => {
            console.error(error);
            ncSwal.error('Network Error', 'Could not reach the server. Please check your connection and try again.');
        });
}

function updateBusiness() {
    var busName = $('#updBusinessName').val().trim();

    const busData = {
        // Business
        juri_entity_no:  $('#updBusEntityNo').val(),
        juri_name:       busName,
        line_of_industry: $('#updIndustry').val(),
        capitalization: $('#updCapitalization').val(),
        contact_no: $('#updContactNo').val(),
        contact_email: $('#updEmail').val(),

        // Owner
        employer_entity_no:  $('#updEmpEntityNo').val(),
        employer_full_name:  $('#updOwnerName').val(),
        special_category:    $('#updSpecialCategory').val(),

        // Juridical address
        juri_region:      $('#updBusRegion').val(),
        juri_province:    $('#updBusProvince').val(),
        juri_city:        $('#updBusCity').val(),
        juri_barangay:    $('#updBusBarangay').val(),
        juri_street:      $('#updBusStreet').val(),
        juri_subdivision: $('#updBusSubdivision').val(),
        juri_upblb_num:   $('#updBusUpblb').val(),

        // Owner address
        employer_region:      $('#updEmpRegion').val(),
        employer_province:    $('#updEmpProvince').val(),
        employer_city:        $('#updEmpCity').val(),
        employer_barangay:    $('#updEmpBarangay').val(),
        employer_street:      $('#updEmpStreet').val(),
        employer_subdivision: $('#updEmpSubdivision').val(),
        employer_upblb_num:   $('#updEmpUpblb').val()
    };

    // Show loading while request is in flight
    ncSwal.loading('Updating business...');

    fetch('../../api/routes.php/business', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(busData)
    })
        .then(response => response.json())
        .then(data => {
            ncSwal.close();
            if (data.status === 'success') {
                $('#updateBusinessModal').modal('hide');
                $('#tblBusiness').DataTable().ajax.reload();
                // Success toast — non-blocking, auto-dismisses
                ncSwal.toast(
                    'success',
                    'Business Updated',
                    busName ? '<strong>' + busName + '</strong> has been updated.' : 'Business details updated successfully.'
                );
            } else {
                ncSwal.error('Update Failed', data.message || 'No changes were saved. Please check the details and try again.');
            }
        })
        .catch(error => {
            ncSwal.close();
            console.error(error);
            ncSwal.error('Network Error', 'Could not reach the server. Please check your connection and try again.');
        });
}
