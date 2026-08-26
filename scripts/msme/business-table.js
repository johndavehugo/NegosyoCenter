$(function () {

    // Map msme_category values to badge CSS classes
    function categoryBadge(value) {
        if (!value) return '<span class="badge badge-pill msme-badge-unknown">—</span>';
        var map = {
            'micro': 'msme-badge-micro',
            'small': 'msme-badge-small',
            'medium': 'msme-badge-medium',
            'large': 'msme-badge-large'
        };
        var key = value.toString().toLowerCase();
        var cls = map[key] || 'msme-badge-unknown';
        return '<span class="badge badge-pill ' + cls + '">' + value + '</span>';
    }

    // Wrap text in a truncating span with full value as tooltip
    function truncateCell(value, maxWidth) {
        if (!value) return '—';
        var w = maxWidth || 200;
        return '<span class="tbl-cell-truncate" style="max-width:' + w + 'px;" title="' + $('<div/>').text(value).html() + '">' + $('<div/>').text(value).html() + '</span>';
    }

    $('#tblBusiness').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "processing": true,
        "serverSide": true,
        "paging": true,
        "ajax": {
            "url": "../../api/routes.php/business",
            "type": "GET",
        },
        "columns": [
            { "data": "juridical.entity_no" },
            {
                "data": "juridical.name",
                "render": function (data, type, row) {
                    return type === 'display' ? truncateCell(data, 220) : (data || '');
                }
            },
            {
                "data": "juridical.msme_category",
                "render": function (data, type, row) {
                    return type === 'display' ? categoryBadge(data) : (data || '');
                }
            },
            {
                "data": "employer.full_name",
                "render": function (data, type, row) {
                    return type === 'display' ? truncateCell(data, 200) : (data || '');
                }
            },
            {
                "data": null,
                "render": function (data, type, row) {
                    var id = row.juridical.entity_no;
                    return '<div class="d-flex justify-content-center dropdown">' +
                        '<button class="btn btn-text-secondary btn-sm tbl-action-btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Actions">' +
                        '<i class="material-icons" style="font-size:20px;vertical-align:middle;">more_vert</i>' +
                        '</button>' +
                        '<div class="dropdown-menu dropdown-menu-right tbl-action-menu">' +
                        '<a class="dropdown-item" href="business-view.php?id=' + encodeURIComponent(id) + '">' +
                        '<i class="material-icons tbl-action-icon">visibility</i>View' +
                        '</a>' +
                        '<a class="dropdown-item" href="#" onclick="fillUpdateModal(\'' + id + '\')">' +
                        '<i class="material-icons tbl-action-icon">edit</i>Update' +
                        '</a>' +
                        '<a class="dropdown-item" href="#" onclick="fillRenewModal(\'' + id + '\')">' +
                        '<i class="material-icons tbl-action-icon">autorenew</i>Renew' +
                        '</a>' +
                        '<a class="dropdown-item" href="#" onclick="fillStatusModal(\'' + id + '\')">' +
                        '<i class="material-icons tbl-action-icon">toggle_on</i>Change Status' +
                        '</a>' +
                        '</div></div>';
                }
            }
        ],
        "initComplete": function () {
            // Inject Material Icons search icon into the DataTables search input
            var $filter = $('#tblBusiness_filter');
            $filter.find('input')
                .addClass('tbl-search-input')
                .attr('placeholder', 'Search...')
                .before('<i class="material-icons tbl-search-icon">search</i>');
            $filter.find('label').addClass('tbl-search-label');
        }
    });

});
