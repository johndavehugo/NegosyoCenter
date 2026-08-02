$(function () {
            $('#tblBusiness').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "paging": true,
                "lengthChange": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "ajax": {
                    "url": "../../server-side/business-handler.php",
                    "type": "GET",
                },
                "columns": [
                    { "data": "juridical.entity_no"},
                    { "data": "juridical.name" },
                    { "data": "juridical.msme_category" },
                    { "data": "employer.full_name" },
                    { 
                        "data": null,
                        "render": function(data, type, row) {
                            return '<div style="display: flex; justify-content: center" class="dropdown"><button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>' +
                                '<div class="dropdown-menu">' +
                                '<a class="dropdown-item" href="#" onclick="viewBusiness(\'' + row.juridical.entity_no + '\')">View</a>' +
                                '<a class="dropdown-item" href="#" onclick="fillUpdateModal(\'' + row.juridical.entity_no + '\')">Update</a>' +
                                '<a class="dropdown-item" href="#" onclick="renewBusiness(\'' + row.id + '\')">Renew</a>' +
                                '</div></div>';
                        }
                    }
                ]
            })
        });



function viewBusiness(entityNo) {
$.getJSON('../../server-side/business-handler.php', { action: 'detail', entity_no: entityNo }, function(res) {
if (res.status === 'success' && res.data) {
    var d = res.data;
    var j = d.juridical || {};
    var e = d.employer || {};
    $('#viewBusinessName').text(j.name || '—');
    $('#viewOwner').text(e.full_name || '—');
    $('#viewAppStatus').text(j.registration_type || '—');
    $('#viewCapitalization').text(j.capitalization || '—');
    $('#viewBusStatus').text(j.business_status || '—');
    $('#viewContactNo').text(j.contact_no || '—');
    $('#viewEmail').text(j.contact_email || '—');
    $('#viewBusAddress').text([j.street, j.subdivision, j.barangay, j.city, j.province].filter(Boolean).join(', ') || '—');
    $('#viewOwnerAddress').text([e.street, e.subdivision, e.barangay, e.city, e.province].filter(Boolean).join(', ') || '—');
    $('#viewSector').text(j.line_of_industry || '—');
    $('#viewClassification').text(j.msme_category || '—');
    $('#viewSpecialSector').text(e.special_category || '—');
} else {
    alert('Business not found.');
}
if (d.id) {
    $('#btnImportLocal').prop('disabled', true).text('Already in Local');
} else {
    $('#btnImportLocal').prop('disabled', false).data('entity-no', entityNo).html('<i class="fas fa-download mr-1"></i>Import to Local');
}
$('#viewBusinessModal').modal('show');
});
}

function importToLocal() {
    var entityNo = $('#btnImportLocal').data('entity-no');
    if (!entityNo) return;

    $.post('../../server-side/business-handler.php', {
        action: 'import',
        entity_no: entityNo
    }, function(res) {
        if (res.status === 'success') {
        Swal.fire('Imported!', 'Business has been saved to local database.', 'success');
        $('#viewBusinessModal').modal('hide');
        $('#tblBusiness').DataTable().ajax.reload();
        } else {
            Swal.fire('Error', res.message || 'Import failed.', 'error');
        }
    }, 'json');
}