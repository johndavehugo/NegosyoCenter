var select2AddSelectors = ['#addIncidentCalamity', '#addIncidentJuridical', '#addIncidentNature'];

function destroySelect2Add() {
    select2AddSelectors.forEach(function (sel) {
        if ($(sel).hasClass('select2-hidden-accessible')) {
            $(sel).select2('destroy');
        }
    });
}

function initSelect2Add() {
    $('#addIncidentCalamity').select2({
        dropdownParent: $('#addIncidentModal'),
        placeholder: 'Search calamity...',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap4'
    });
    $('#addIncidentJuridical').select2({
        dropdownParent: $('#addIncidentModal'),
        placeholder: 'Search business name or entity no...',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap4'
    });
    $('#addIncidentNature').select2({
        dropdownParent: $('#addIncidentModal'),
        placeholder: 'Select nature of damage...',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap4'
    });
}

$(document).on('click', '#btn_add_incident', function () {
    destroySelect2Add();

    // Use .done() not .then() so $.when() can track them correctly
    var loadCalamities = $.getJSON('../../api/routes.php/calamity?action=calamities')
        .done(function (res) {
            var opts = '<option value=""></option>';
            if (res.status === 'success') {
                res.data.forEach(function (c) {
                    opts += '<option value="' + c.id + '">' + c.name + ' (' + c.calamity_type + ')</option>';
                });
            }
            $('#addIncidentCalamity').html(opts);
        });

    var loadBusinesses = $.getJSON('../../api/routes.php/calamity?action=juridicals')
        .done(function (res) {
            var opts = '<option value=""></option>';
            if (res.status === 'success') {
                res.data.forEach(function (b) {
                    opts += '<option value="' + b.id + '">' + b.name + ' — ' + b.entity_no + '</option>';
                });
            }
            $('#addIncidentJuridical').html(opts);
        });

    $.when(loadCalamities, loadBusinesses).done(function () {
        initSelect2Add();
    });
});

$('#addIncidentModal').on('hidden.bs.modal', function () {
    destroySelect2Add();
    document.getElementById('addIncidentForm').reset();
});

function addIncident() {
    var calamityId  = $('#addIncidentCalamity').val();
    var juridicalId = $('#addIncidentJuridical').val();
    var date        = $('#addIncidentDate').val();
    var nature      = $('#addIncidentNature').val();
    var cost        = $('#addIncidentCost').val();
    var status      = $('#addIncidentStatus').val();
    var remarks     = $('#addIncidentRemarks').val();

    if (!calamityId || !juridicalId || !date || !nature || cost === '') {
        Swal.fire('Warning', 'Please fill in all required fields.', 'warning');
        return;
    }

    const data = {
        calamity_id:               calamityId,
        juridical_id:              juridicalId,
        date_occurred:             date,
        nature_of_damage:          nature,
        estimated_cost_of_damages: cost,
        status:                    status,
        remarks:                   remarks,
    };

    fetch('../../api/routes.php/calamity', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire('Success!', res.message, 'success');
            $('#addIncidentModal').modal('hide');
            reloadCalamityTable();
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Network error. Please try again.', 'error');
    });
}
