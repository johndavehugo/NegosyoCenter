var select2UpdateSelectors = ['#updateIncidentCalamity', '#updateIncidentJuridical', '#updateIncidentNature'];

function destroySelect2Update() {
    select2UpdateSelectors.forEach(function (sel) {
        if ($(sel).hasClass('select2-hidden-accessible')) {
            $(sel).select2('destroy');
        }
    });
}

function initSelect2Update() {
    $('#updateIncidentCalamity').select2({
        dropdownParent: $('#updateIncidentModal'),
        placeholder: 'Search calamity...',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap4'
    });

    $('#updateIncidentJuridical').select2({
        dropdownParent: $('#updateIncidentModal'),
        placeholder: 'Search business name or entity no...',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap4'
    });

    $('#updateIncidentNature').select2({
        dropdownParent: $('#updateIncidentModal'),
        placeholder: 'Search nature of damage...',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap4'
    });
}

function fillUpdateIncident(incidentId) {
    $('#updateIncidentId').val(incidentId);
    destroySelect2Update();

    var loadCalamities = $.getJSON('../../api/routes.php/calamity?action=calamities')
        .done(function (res) {
            var opts = '<option value=""></option>';
            if (res.status === 'success') {
                res.data.forEach(function (c) {
                    opts += '<option value="' + c.id + '">' + c.name + ' (' + c.calamity_type + ')</option>';
                });
            }
            $('#updateIncidentCalamity').html(opts);
        });

    var loadBusinesses = $.getJSON('../../api/routes.php/calamity?action=juridicals')
        .done(function (res) {
            var opts = '<option value=""></option>';
            if (res.status === 'success') {
                res.data.forEach(function (b) {
                    opts += '<option value="' + b.id + '">' + b.name + ' — ' + b.entity_no + '</option>';
                });
            }
            $('#updateIncidentJuridical').html(opts);
        });

    $.when(loadCalamities, loadBusinesses).done(function () {
        initSelect2Update();

        $.getJSON('../../api/routes.php/calamity?action=detail&incident_id=' + incidentId)
            .done(function (res) {
                if (res.status === 'success' && res.data) {
                    var d = res.data;
                    $('#updateIncidentCalamity').val(d.calamity_id).trigger('change');
                    $('#updateIncidentJuridical').val(d.juridical_id).trigger('change');
                    $('#updateIncidentDate').val(d.date_occurred);
                    $('#updateIncidentNature').val(d.nature_of_damage).trigger('change');
                    $('#updateIncidentCost').val(d.estimated_cost_of_damages);
                    $('#updateIncidentStatus').val(d.status);
                    $('#updateIncidentRemarks').val(d.remarks || '');
                    $('#updateIncidentModal').modal('show');
                } else {
                    Swal.fire('Error', res.message || 'Incident not found.', 'error');
                }
            });
    });
}

$('#updateIncidentModal').on('hidden.bs.modal', function () {
    destroySelect2Update();
    document.getElementById('updateIncidentForm').reset();
});

function updateIncident() {
    const data = {
        id:                        $('#updateIncidentId').val(),
        calamity_id:               $('#updateIncidentCalamity').val(),
        juridical_id:              $('#updateIncidentJuridical').val(),
        date_occurred:             $('#updateIncidentDate').val(),
        nature_of_damage:          $('#updateIncidentNature').val(),
        estimated_cost_of_damages: $('#updateIncidentCost').val(),
        status:                    $('#updateIncidentStatus').val(),
        remarks:                   $('#updateIncidentRemarks').val(),
    };

    fetch('../../api/routes.php/calamity', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire('Success!', res.message, 'success');
            $('#updateIncidentModal').modal('hide');
            $('#tblCalamityIncidents').DataTable().ajax.reload();
            if (typeof affectedDataTable !== 'undefined' && affectedDataTable) {
                affectedDataTable.ajax.reload();
            }
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Network error', 'error');
    });
}
