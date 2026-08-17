var updStepper;

address.cascade('#updBusRegion', '#updBusProvince', '#updBusCity', '#updBusBarangay');
address.cascade('#updEmpRegion', '#updEmpProvince', '#updEmpCity', '#updEmpBarangay');

$('#updateBusinessModal').on('shown.bs.modal', function() {
    updStepper = new Stepper($('.upd-stepper')[0]);
});



$('#updateBusinessModal').on('hidden.bs.modal', function() {
    if (updStepper) updStepper.reset();
});



async function fillUpdateModal(id) {
    fetch('../../api/routes.php/business/' + id)
        .then(response => response.json())
        .then(async data => {
            if (data.status === 'success') {
                const business = data.data;
                $('#updBusinessName').val(business.juridical.name);
                $('#updBusEntityNo').val(business.juridical.entity_no);
                $('#updCapitalization').val(business.juridical.capitalization);
                setSelectValue('#updIndustry', business.juridical.line_of_industry);
                $('#updContactNo').val(business.juridical.contact_no);
                $('#updEmail').val(business.juridical.contact_email);

                $('#updFirstName').val(business.employer.first_name);
                $('#updMiddleName').val(business.employer.middle_name);
                $('#updLastName').val(business.employer.last_name);
                $('#updEmpEntityNo').val(business.employer.entity_no);
                setSelectValue('#updSpecialCategory', business.employer.special_category);

                
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
                alert(data.message);
            }
        })
        .catch(error => {
            alert('Error: ' + error);
        });
}


function updateBusiness() {
            const busData = {
                //Business
                juri_entity_no: $('#updBusEntityNo').val(),
                juri_name: $('#updBusinessName').val(),
                juri_entity_no: $('#updBusEntityNo').val(),
                line_of_industry: $('#updIndustry').val(),
                capitalization: $('#updCapitalization').val(),
                contact_no: $('#updContactNo').val(),
                contact_email: $('#updEmail').val(),

                //Owner
                employer_entity_no: $('#updEmpEntityNo').val(),
                employer_first_name: $('#updFirstName').val(),
                employer_middle_name: $('#updMiddleName').val(),
                employer_last_name: $('#updLastName').val(),
                employer_entity_no: $('#updEmpEntityNo').val(),
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
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(busData)
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    $('#updateBusinessModal').modal('hide');
                    $('#tblBusiness').DataTable().ajax.reload();
                }
            })
            .catch(error => {
                alert('Error: ' + error);
            });
        }
               
    