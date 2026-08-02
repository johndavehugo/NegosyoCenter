var updStepper;

$('#updateBusinessModal').on('shown.bs.modal', function() {
    updStepper = new Stepper($('.upd-stepper')[0]);
    ensureRegions('#updBusRegion');
    ensureRegions('#updEmpRegion');
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
                await prefillAddress('updBus', business.juridical);
                await prefillAddress('updEmp', business.employer);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            alert('Error: ' + error);
        });
}
               
    