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
                msme.alert({
                    icon: 'error',
                    title: 'Could Not Load Record',
                    text: data.message || 'The business record could not be retrieved.'
                });
            }
        })
        .catch(error => {
            console.error(error);
            msme.alert({
                icon: 'error',
                title: 'Request Failed',
                text: 'A network error occurred. Please check your connection and try again.'
            });
        });
}

function updateBusiness() {
    const busData = {
        //Business
        juri_entity_no: $('#updBusEntityNo').val(),
        juri_name: $('#updBusinessName').val(),
        line_of_industry: $('#updIndustry').val(),
        capitalization: currencyParse($('#updCapitalization').val()),
        contact_no: $('#updContactNo').val(),
        contact_email: $('#updEmail').val(),

        //Owner
        employer_entity_no: $('#updEmpEntityNo').val(),
        employer_full_name: $('#updOwnerName').val(),
        special_category: $('#updSpecialCategory').val(),

        //Juridical Address
        juri_region: $('#updBusRegion').val(),
        juri_province: $('#updBusProvince').val(),
        juri_city: $('#updBusCity').val(),
        juri_barangay: $('#updBusBarangay').val(),
        juri_street: $('#updBusStreet').val(),
        juri_subdivision: $('#updBusSubdivision').val(),
        juri_upblb_num: $('#updBusUpblb').val(),

        //Owner Address
        employer_region: $('#updEmpRegion').val(),
        employer_province: $('#updEmpProvince').val(),
        employer_city: $('#updEmpCity').val(),
        employer_barangay: $('#updEmpBarangay').val(),
        employer_street: $('#updEmpStreet').val(),
        employer_subdivision: $('#updEmpSubdivision').val(),
        employer_upblb_num: $('#updEmpUpblb').val()
    };

    fetch('../../api/routes.php/business', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(busData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                var name = $('#updBusinessName').val() || 'Business';
                $('#updateBusinessModal').modal('hide');
                $('#tblBusiness').DataTable().ajax.reload();
                msme.toast({
                    icon: 'success',
                    title: 'Record Updated',
                    text: 'Changes to ' + name + ' have been saved.'
                });
            } else {
                msme.alert({
                    icon: 'error',
                    title: 'Could Not Update',
                    text: data.message || 'An error occurred while saving the changes.'
                });
            }
        })
        .catch(error => {
            console.error(error);
            msme.alert({
                icon: 'error',
                title: 'Request Failed',
                text: 'A network error occurred. Please check your connection and try again.'
            });
        });
}