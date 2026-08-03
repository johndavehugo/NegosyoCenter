var affectedDataTable = null;

function statusBadge(status) {
    var badge = 'secondary';
    if (status === 'PENDING_VERIFICATION') badge = 'warning';
    else if (status === 'VERIFIED') badge = 'info';
    else if (status === 'AID_RELEASED') badge = 'success';
    return '<span class="badge badge-' + badge + '">' + status.replace(/_/g, ' ') + '</span>';
}

function formatMoney(amount) {
    return Number(amount).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' });
}

$(function () {
            $('#tblCalamityIncidents').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "paging": true,
                "lengthChange": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "ajax": {
                    "url": "../../server-side/calamity-handler.php",
                    "type": "GET",
                },
                "columns": [
                    { "data": "name" },
                    { "data": "calamity_type" },
                    { "data": "declaration_date" },
                    { "data": "affected_count" },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<div style="display: flex; justify-content: center"><button type="button" class="btn btn-info btn-sm btn-outline-info-custom" onclick="viewAffectedBusinesses(' + row.id + ',\'' + row.name + '\')">' +
                                '<i class="fas fa-building mr-1"></i>View Businesses</button></div>';
                        }
                    }
                ]
            })
        });

function viewAffectedBusinesses(calamityId, calamityName) {
    $('#viewCalamityName').text(calamityName || '—');

    if (affectedDataTable) {
        affectedDataTable.ajax.url('../../server-side/calamity-handler.php?action=affected&calamity_id=' + calamityId).load();
    } else {
        affectedDataTable = $('#tblAffectedBusinesses').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "paging": true,
            "lengthChange": true,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "ajax": {
                "url": "../../server-side/calamity-handler.php?action=affected&calamity_id=" + calamityId,
                "type": "GET",
            },
            "columns": [
                { "data": "business_name" },
                { "data": "entity_no" },
                { "data": "owner_full_name" },
                { "data": "date_occurred" },
                { "data": "nature_of_damage" },
                {
                    "data": "estimated_cost_of_damages",
                    "render": function(data, type, row) {
                        return formatMoney(data);
                    }
                },
                {
                    "data": "status",
                    "render": function(data, type, row) {
                        return statusBadge(data);
                    }
                },
                { "data": "remarks" },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return '<div style="display: flex; justify-content: center"><button type="button" class="btn btn-warning btn-sm" onclick="fillUpdateIncident(' + row.id + ')">' +
                            '<i class="fas fa-pen mr-1"></i>Update</button></div>';
                    }
                }
            ]
        });
    }

    $('#viewAffectedBusinessesModal').modal('show');
}
