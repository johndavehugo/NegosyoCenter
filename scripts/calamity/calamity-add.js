$(document).on('click', '#btn_add_incident', function () {
    if ($('#addIncidentCalamity option').length < 2) {
        $.getJSON('../../api/routes.php/calamity?action=calamities')
            .done(function (res) {
                var opts = '<option value="" hidden>Select Calamity</option>';
                if (res.status === 'success') {
                    res.data.forEach(function (c) {
                        opts += '<option value="' + c.id + '">' + c.name + ' (' + c.calamity_type + ')</option>';
                    });
                }
                $('#addIncidentCalamity').html(opts);
            });
    }
    if ($('#addIncidentJuridical option').length < 2) {
        $.getJSON('../../api/routes.php/calamity?action=juridicals')
            .done(function (res) {
                var opts = '<option value="" hidden>Select Business</option>';
                if (res.status === 'success') {
                    res.data.forEach(function (b) {
                        opts += '<option value="' + b.id + '">' + b.name + ' — ' + b.entity_no + '</option>';
                    });
                }
                $('#addIncidentJuridical').html(opts);
            });
    }
});

$('#addIncidentModal').on('hidden.bs.modal', function () {
    document.getElementById('addIncidentForm').reset();
});

function addIncident() {
    const data = {
        calamity_id:               $('select[name=addIncidentCalamity]').val(),
        juridical_id:              $('select[name=addIncidentJuridical]').val(),
        date_occurred:             $('input[name=addIncidentDate]').val(),
        nature_of_damage:          $('select[name=addIncidentNature]').val(),
        estimated_cost_of_damages: $('input[name=addIncidentCost]').val(),
        status:                    $('select[name=addIncidentStatus]').val(),
        remarks:                   $('textarea[name=addIncidentRemarks]').val(),
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
            $('#tblCalamityIncidents').DataTable().ajax.reload();
            document.getElementById('addIncidentForm').reset();
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Network error', 'error');
    });
}
