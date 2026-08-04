function loadCalamityOptions(selectId) {
    return new Promise(function (resolve, reject) {
        $.getJSON('../../api/routes.php/calamity?action=calamities')
            .done(function (res) {
                var opts = '<option value="" hidden>Select Calamity</option>';
                if (res.status === 'success') {
                    res.data.forEach(function (c) {
                        opts += '<option value="' + c.id + '">' + c.name + ' (' + c.calamity_type + ')</option>';
                    });
                }
                $('#' + selectId).html(opts);
                resolve();
            })
            .fail(reject);
    });
}

function loadJuridicalOptions(selectId) {
    return new Promise(function (resolve, reject) {
        $.getJSON('../../api/routes.php/calamity?action=juridicals')
            .done(function (res) {
                var opts = '<option value="" hidden>Select Business</option>';
                if (res.status === 'success') {
                    res.data.forEach(function (b) {
                        opts += '<option value="' + b.id + '">' + b.name + ' — ' + b.entity_no + '</option>';
                    });
                }
                $('#' + selectId).html(opts);
                resolve();
            })
            .fail(reject);
    });
}

function fillUpdateIncident(incidentId) {
    $('#updateIncidentId').val(incidentId);

    Promise.all([
        loadCalamityOptions('updateIncidentCalamity'),
        loadJuridicalOptions('updateIncidentJuridical')
    ]).then(function () {
        $.getJSON('../../server-side/calamity-handler.php?action=detail&incident_id=' + incidentId, function(res) {
            if (res.status === 'success' && res.data) {
                var d = res.data;
                $('#updateIncidentCalamity').val(d.calamity_id);
                $('#updateIncidentJuridical').val(d.juridical_id);
                $('#updateIncidentDate').val(d.date_occurred);
                $('#updateIncidentNature').val(d.nature_of_damage);
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

function updateIncident() {
    const data = {
        id:                        $('input[name=updateIncidentId]').val(),
        calamity_id:               $('select[name=updateIncidentCalamity]').val(),
        juridical_id:              $('select[name=updateIncidentJuridical]').val(),
        date_occurred:             $('input[name=updateIncidentDate]').val(),
        nature_of_damage:          $('select[name=updateIncidentNature]').val(),
        estimated_cost_of_damages: $('input[name=updateIncidentCost]').val(),
        status:                    $('select[name=updateIncidentStatus]').val(),
        remarks:                   $('textarea[name=updateIncidentRemarks]').val(),
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
