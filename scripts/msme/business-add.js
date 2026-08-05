var stepper;

$('#addBusinessModal').on('shown.bs.modal', function () {
    stepper = new Stepper($('.bs-stepper')[0]);

    if (!$('#ownerSearch').data('select2')) {
        $('#ownerSearch').select2({
            placeholder: 'Search owner by name or entity no',
            allowClear: true,
            minimumInputLength: 1,
            dropdownParent: $('#addBusinessModal'),
            ajax: {
                url: '../../api/routes.php/employer/search',
                dataType: 'json',
                data: function (params) { return { q: params.term }; },
                processResults: function (data) {
                    return {
                        results: (data.data || []).map(function (o) {
                            return { id: o.entity_no, text: o.last_name + ', ' + o.first_name + ' (' + o.entity_no + ')', owner: o };
                        })
                    };
                }
            }
        });
    }

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

$('#addBusinessModal').on('hidden.bs.modal', function () {
    if (stepper) stepper.reset();
});


$('#ownerSearch').on('select2:select', function (e) {
    var o = e.params.data.owner;
    $('#ownerSummaryName').text(o.last_name + ', ' + o.first_name + ' ' + (o.middle_name || ''));
    $('#ownerSummaryEntity').text(o.entity_no);
    $('#ownerSummaryCategory').text(o.special_category || '—');
    $('#ownerSummaryRegion').text(o.region || '—');
    $('#ownerSummaryProvince').text(o.province || '—');
    $('#ownerSummaryCity').text(o.city || '—');
    $('#ownerSummaryBarangay').text(o.barangay || '—');
    $('#ownerSummaryStreet').text(o.street || '—');
    $('#ownerSummarySubdivision').text(o.subdivision || '—');
    $('#ownerSummaryUpblb').text(o.upblb_num || '—');
    $('#ownerSummary').show();
});


function addBusiness() {

    var selOwner = $('#ownerSearch').select2('data')[0];
    if (!selOwner || !selOwner.owner || !selOwner.owner.id) {
        alert('Please search and select an owner first.');
        return;
    }

    const data = {
        employer_id: selOwner.owner.id,
        //Business
        juri_name: $('input[name=addBusinessName]').val(),
        juri_entity_no: $('input[name=addBusEntityNo]').val(),
        line_of_industry: $('select[name=addIndustry]').val(),
        capitalization: $('input[name=addCapitalization]').val(),
        contact_no: $('input[name=addContactNo]').val(),
        contact_email: $('input[name=addEmail]').val(),


        //juridical address
        juri_region: $('select[name=addBusRegion]').val(),
        juri_province: $('select[name=addBusProvince]').val(),
        juri_city: $('select[name=addBusCity]').val(),
        juri_barangay: $('select[name=addBusBarangay]').val(),
        juri_street: $('input[name=addBusStreet]').val(),
        juri_subdivision: $('input[name=addBusSubdivision]').val(),
        juri_upblb_num: $('input[name=addBusUpblb]').val(),


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