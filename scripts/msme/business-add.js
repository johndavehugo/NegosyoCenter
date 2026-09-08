var stepper;

address.cascade('#addBusRegion', '#addBusProvince', '#addBusCity', '#addBusBarangay');
address.cascade('#addEmpRegion', '#addEmpProvince', '#addEmpCity', '#addEmpBarangay');

bindCurrencyInput($('#addCapitalization'));

$('#addBusinessModal').on('shown.bs.modal', function () {
    stepper = new Stepper($('.bs-stepper')[0]);

    if (!$('#scimsBusSearch').data('select2')) {
        $('#scimsBusSearch').select2({
            placeholder: 'Search business name',
            allowClear: true,
            minimumInputLength: 1,
            dropdownParent: $('#addBusinessModal'),
            ajax: {
                url: '../../server-side/business-handler.php',
                dataType: 'json',
                delay: 300,
                data: function (params) { return { action: 'scims_businesses', q: params.term }; },
                processResults: function (data) {
                    return {
                        results: (data.data || []).map(function (o) {
                            return { id: o.juri_name, text: o.juri_name + ' (' + o.juri_entity_no + ')', business: o };
                        })
                    };
                }
            }
        });
    }

});

$('#addBusinessModal').on('hidden.bs.modal', function () {
    if (stepper) stepper.reset();
    document.getElementById('addBusinessForm').reset();

    $('#scimsBusSearch').val(null).trigger('change');

    ['addBusProvince', 'addBusCity', 'addBusBarangay',
        'addEmpProvince', 'addEmpCity', 'addEmpBarangay'].forEach(function (id) {
            var $sel = $('#' + id);
            $sel.find('option').not('[value=""]').remove();
            $sel.val('').trigger('change');
        });

    $('#addBusinessModal').data('scimsBusiness', null);
});


$('#scimsBusSearch').on('select2:select', function (e) {
    $('#addBusinessModal').data('scimsBusiness', e.params.data.business);
    var b = e.params.data.business;

    //Business
    $('#addBusinessName').val(b.juri_name || '');
    $('#addBusEntityNo').val(b.juri_entity_no || '');
    $('#addContactNo').val(b.juri_contact_no || '');
    $('#addEmail').val(b.juri_contact_email || '');
    $('#addBusStreet').val(b.juri_street || '');
    $('#addBusSubdivision').val(b.juri_subdivision || '');
    $('#addBusUpblb').val(b.juri_upblb_num || '');
    $('#addIndustry').val(b.line_of_industry || '');

    //Owner
    $('#addOwnerName').val(b.emp_name || '');
    $('#addEmpEntityNo').val(b.emp_entity_no || '');
    $('#addEmpStreet').val(b.emp_street || '');
    $('#addEmpSubdivision').val(b.emp_subdivision || '');
    $('#addEmpUpblb').val(b.emp_upblb_num || '');

    if (b.juri_region || b.juri_province || b.juri_city || b.juri_barangay) {
        address.prefill('#addBusRegion', '#addBusProvince', '#addBusCity', '#addBusBarangay',
            { region: b.juri_region, province: b.juri_province, city: b.juri_city, barangay: b.juri_barangay });
    }
    if (b.emp_region || b.emp_province || b.emp_city || b.emp_barangay) {
        address.prefill('#addEmpRegion', '#addEmpProvince', '#addEmpCity', '#addEmpBarangay',
            { region: b.emp_region, province: b.emp_province, city: b.emp_city, barangay: b.emp_barangay });
    }
});


function addBusiness() {
    var b = $('#addBusinessModal').data('scimsBusiness') || {};
    const data = {

        //employer address
        emp_region: $('select[name=addEmpRegion]').val(),
        emp_province: $('select[name=addEmpProvince]').val(),
        emp_city: $('select[name=addEmpCity]').val(),
        emp_barangay: $('select[name=addEmpBarangay]').val(),
        emp_street: $('input[name=addEmpStreet]').val(),
        emp_subdivision: $('input[name=addEmpSubdivision]').val(),
        emp_upblb_num: $('input[name=addEmpUpblb]').val(),
        emp_address_id: b.emp_address_id,


        //employer
        emp_name: $('input[name=addOwnerName]').val(),
        emp_entity_no: $('input[name=addEmpEntityNo]').val(),
        emp_special_category: $('select[name=addSpecialCategory]').val(),
        emp_id: b.emp_id,

        //juridical address
        juri_region: $('select[name=addBusRegion]').val(),
        juri_province: $('select[name=addBusProvince]').val(),
        juri_city: $('select[name=addBusCity]').val(),
        juri_barangay: $('select[name=addBusBarangay]').val(),
        juri_street: $('input[name=addBusStreet]').val(),
        juri_subdivision: $('input[name=addBusSubdivision]').val(),
        juri_upblb_num: $('input[name=addBusUpblb]').val(),
        juri_address_id: b.juri_address_id,

        //juridical
        juri_name: $('input[name=addBusinessName]').val(),
        juri_entity_no: $('input[name=addBusEntityNo]').val(),
        juri_line_of_industry: $('select[name=addIndustry]').val(),
        juri_capitalization: currencyParse($('input[name=addCapitalization]').val()),
        juri_contact_no: $('input[name=addContactNo]').val(),
        juri_contact_email: $('input[name=addEmail]').val(),

    };

    fetch('../../api/routes.php/business', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(r => r.json())
        .then(res => {
            if (res.status === 'success') {
                var name = $('input[name=addBusinessName]').val() || 'Business';
                $('#addBusinessModal').modal('hide');
                $('#tblBusiness').DataTable().ajax.reload();
                App.toast({
                    icon: 'success',
                    title: 'Business Added',
                    text: name + ' has been registered successfully.'
                });
            } else {
                App.alert({
                    icon: 'error',
                    title: 'Could Not Save',
                    text: res.message || 'An error occurred while saving the business record.'
                });
            }
        })
        .catch(err => {
            console.error(err);
            App.alert({
                icon: 'error',
                title: 'Request Failed',
                text: 'A network error occurred. Please check your connection and try again.'
            });
        });
};