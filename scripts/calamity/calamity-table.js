var affectedDataTable = null;
var calamityTable = null;

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

function loadCalamityTable() {
    $.getJSON('../../server-side/calamity-handler.php?draw=1&start=0&length=1000&search[value]=&order[0][column]=0&order[0][dir]=asc&_=' + Date.now())
        .done(function (res) {
            if (calamityTable) {
                calamityTable.clear();
                calamityTable.rows.add(res.data);
                calamityTable.draw(false); // draw(false) = stay on current page
            }
        });
}

$(function () {
    calamityTable = $('#tblCalamityIncidents').DataTable({
        "responsive": true,
        "autoWidth": false,
        "processing": false,
        "serverSide": false,
        "paging": true,
        "lengthChange": true,
        "order": [[2, "desc"]], // default: declaration_date newest first
        "data": [],
        "columns": [
            { "data": "name" },
            { "data": "calamity_type" },
            { "data": "declaration_date" },
            { "data": "affected_count", "orderable": true },
            {
                "data": null,
                "orderable": false,
                "render": function (data, type, row) {
                    var safeName = row.name.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
                    return '<div style="display:flex;justify-content:center;gap:4px;">' +
                        '<button class="btn btn-warning btn-sm" onclick="fillEditCalamity(' + row.id + ')">' +
                        '<i class="fas fa-pen mr-1"></i>Edit</button>' +
                        '<button class="btn btn-info btn-sm btn-outline-info-custom" onclick="viewAffectedBusinesses(' + row.id + ',\'' + safeName + '\')">' +
                        '<i class="fas fa-building mr-1"></i>View Businesses</button>' +
                        '</div>';
                }
            }
        ]
    });

    // Initial load
    loadCalamityTable();
});

function reloadCalamityTable() {
    $.getJSON('../../server-side/calamity-handler.php?draw=1&start=0&length=1000&search[value]=&order[0][column]=0&order[0][dir]=asc&_=' + Date.now())
        .done(function (res) {
            if (calamityTable) {
                calamityTable.clear();
                calamityTable.rows.add(res.data);
                calamityTable.draw(false); // stay on current page after update
            }
        });
}

function viewAffectedBusinesses(calamityId, calamityName) {
    $('#viewCalamityName').text(calamityName || '—');

    if (affectedDataTable) {
        affectedDataTable.ajax.url('../../server-side/calamity-handler.php?action=affected&calamity_id=' + calamityId + '&_=' + Date.now()).load();
    } else {
        affectedDataTable = $('#tblAffectedBusinesses').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "serverSide": true,
            "paging": true,
            "lengthChange": true,
            "ajax": {
                "url": "../../server-side/calamity-handler.php?action=affected&calamity_id=" + calamityId,
                "type": "GET",
                "data": function (d) {
                    d._ = Date.now(); // cache bust
                }
            },
            "columns": [
                { "data": "business_name" },
                { "data": "entity_no" },
                { "data": "owner_full_name" },
                { "data": "declaration_date" },
                { "data": "nature_of_damage" },
                {
                    "data": "estimated_cost_of_damages",
                    "render": function (data) { return formatMoney(data); }
                },
                {
                    "data": "status",
                    "render": function (data) { return statusBadge(data); }
                },
                { "data": "remarks" },
                {
                    "data": null,
                    "orderable": false,
                    "render": function (data, type, row) {
                        return '<div style="display:flex;justify-content:center;">' +
                            '<button class="btn btn-warning btn-sm" onclick="fillUpdateIncident(' + row.id + ')">' +
                            '<i class="fas fa-pen mr-1"></i>Update</button></div>';
                    }
                }
            ]
        });
    }

    $('#viewAffectedBusinessesModal').modal('show');
}
