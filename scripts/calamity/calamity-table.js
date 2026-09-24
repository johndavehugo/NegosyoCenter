var affectedDataTable = null;
var calamityTable = null;

function statusBadge(status) {
    var map = {
        'PENDING_VERIFICATION': { cls: 'msme-badge-status-pending',  label: 'Pending Verification' },
        'VERIFIED':             { cls: 'msme-badge-status-approved',  label: 'Verified'             },
        'AID_RELEASED':         { cls: 'msme-badge-status-active',    label: 'Aid Released'         }
    };
    var entry = map[status] || { cls: 'msme-badge-unknown', label: status ? status.replace(/_/g, ' ') : '—' };
    return '<span class="badge badge-pill ' + entry.cls + '">' + entry.label + '</span>';
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
            {
                "data": "calamity_type",
                "render": function (data) {
                    if (!data) return '—';
                    var key = data.toLowerCase();
                    var cls = 'cal-type-' + (['typhoon','flood','earthquake','fire','landslide'].indexOf(key) !== -1 ? key : 'other');
                    return '<span class="cal-type-badge ' + cls + '">' + data.charAt(0).toUpperCase() + data.slice(1).toLowerCase() + '</span>';
                }
            },
            { "data": "declaration_date" },
            { "data": "affected_count", "orderable": true },
            {
                "data": null,
                "orderable": false,
                "render": function (data, type, row) {
                    var safeName = row.name.replace(/\\/g, '\\\\').replace(/'/g, "\\'");
                    return '<div class="d-flex justify-content-center dropdown">' +
                        '<button class="btn btn-text-secondary btn-sm tbl-action-btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Actions">' +
                        '<i class="material-icons" style="font-size:20px;vertical-align:middle;">more_vert</i>' +
                        '</button>' +
                        '<div class="dropdown-menu dropdown-menu-right tbl-action-menu">' +
                        '<a class="dropdown-item" href="#" onclick="event.preventDefault();fillEditCalamity(' + row.id + ');return false;">' +
                        '<i class="material-icons tbl-action-icon">edit</i>Edit Calamity' +
                        '</a>' +
                        '<a class="dropdown-item" href="#" onclick="event.preventDefault();viewAffectedBusinesses(' + row.id + ',\'' + safeName + '\');return false;">' +
                        '<i class="material-icons tbl-action-icon">domain</i>View Businesses' +
                        '</a>' +
                        '</div></div>';
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
                        return '<div class="d-flex justify-content-center dropdown">' +
                            '<button class="btn btn-text-secondary btn-sm tbl-action-btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Actions">' +
                            '<i class="material-icons" style="font-size:20px;vertical-align:middle;">more_vert</i>' +
                            '</button>' +
                            '<div class="dropdown-menu dropdown-menu-right tbl-action-menu">' +
                            '<a class="dropdown-item" href="#" onclick="event.preventDefault();fillUpdateIncident(' + row.id + ');return false;">' +
                            '<i class="material-icons tbl-action-icon">edit_note</i>Update' +
                            '</a>' +
                            '<a class="dropdown-item text-danger" href="#" onclick="event.preventDefault();deleteAffectedBusiness(' + row.affected_id + ');return false;">' +
                            '<i class="material-icons tbl-action-icon" style="color:#dc3545;">delete_outline</i>Delete' +
                            '</a>' +
                            '</div></div>';
                    }
                }
            ]
        });
    }

    $('#viewAffectedBusinessesModal').modal('show');
}

function deleteAffectedBusiness(affectedId) {
    App.confirm({
        icon: 'warning',
        danger: true,
        title: 'Remove Affected Business?',
        text: 'This affected business will be removed from the calamity incident.',
        confirmButtonText: 'Yes, Remove',
        cancelButtonText: 'Cancel'
    }).then(function (result) {
        if (!result.isConfirmed) return;

        fetch('../../api/routes.php/calamity?id=' + encodeURIComponent(affectedId), {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(r => r.json())
        .then(res => {
            if (res.status === 'success') {
                if (affectedDataTable) {
                    affectedDataTable.ajax.reload();
                }
                reloadCalamityTable();
                App.toast({
                    icon: 'success',
                    title: 'Record Removed',
                    text: res.message || 'The affected business has been removed.'
                });
            } else {
                App.alert({
                    icon: 'error',
                    title: 'Could Not Remove',
                    text: res.message || 'An error occurred while removing the record.'
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
    });
}
