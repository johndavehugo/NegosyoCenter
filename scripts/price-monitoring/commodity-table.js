// commodity-table.js
$(function () {

    var _allEstablishments = []; // { id, name } cached once

    // ── Escape helpers ────────────────────────────────────────────────────────
    function escHtml(s) { return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
    function escAttr(s) { return String(s||'').replace(/"/g,'&quot;'); }

    // ── Load categories into Add modal ────────────────────────────────────────
    function loadCategories() {
        $.getJSON('../../api/routes.php/price-monitoring?action=commodity_categories&agency_id=3', function (res) {
            if (res.status !== 'success') return;
            var html = '<option value="">-- Select Category --</option>';
            (res.data || []).forEach(function (c) {
                html += '<option value="' + c.category_id + '">' + c.category_name + '</option>';
            });
            $('#category_id').html(html);
        });
    }

    // ── Load all establishments once, then init both Select2 pickers ─────────
    function loadEstablishments(callback) {
        $.ajax({
            url: '../../api/routes.php/business',
            type: 'GET',
            data: { length: -1 },
            dataType: 'json',
            success: function (res) {
                _allEstablishments = [];
                if (res.status === 'success') {
                    (res.data || []).forEach(function (item) {
                        var name = item.juridical && item.juridical.name ? item.juridical.name : '';
                        if (name) _allEstablishments.push(name);
                    });
                }
                window._allEstablishments = _allEstablishments;
                if (callback) callback();
            }
        });
    }

    // ── Init a single-select Select2 picker for adding establishments ─────────
    function initEstSelect2(selectId, tableBodyId, countBadgeId, emptyMsgId) {
        var $sel = $('#' + selectId);

        // Populate options (exclude already-added)
        function refreshOptions() {
            var added = getAddedNames(tableBodyId);
            $sel.empty().append('<option value=""></option>');
            _allEstablishments.forEach(function (name) {
                if (added.indexOf(name) === -1) {
                    $sel.append('<option value="' + escAttr(name) + '">' + escHtml(name) + '</option>');
                }
            });
            $sel.trigger('change.select2');
        }

        $sel.select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: 'Search and select an establishment to add…',
            allowClear: true,
            dropdownParent: $sel.closest('.modal')
        });

        // On select — append a row and reset the dropdown
        $sel.on('select2:select', function (e) {
            var name = e.params.data.text;
            appendEstRow(tableBodyId, countBadgeId, emptyMsgId, name, '', '');
            // Remove the chosen option so it can't be double-added
            $sel.find('option[value="' + escAttr(name) + '"]').remove();
            $sel.val(null).trigger('change');
            updateCountBadge(tableBodyId, countBadgeId, emptyMsgId);
        });

        // Expose refresh so we can call it when modal opens
        $sel.data('refreshOptions', refreshOptions);
    }

    // ── Returns array of names already in a table body ────────────────────────
    function getAddedNames(tableBodyId) {
        var names = [];
        $('#' + tableBodyId + ' tr').each(function () { names.push($(this).data('est-name')); });
        return names;
    }

    // ── Append a row to the establishment table ───────────────────────────────
    function appendEstRow(tableBodyId, countBadgeId, emptyMsgId, name, srpVal, prevVal) {
        var tbody  = $('#' + tableBodyId);
        var rowNum = tbody.find('tr').length + 1;

        var $row = $(
            '<tr data-est-name="' + escAttr(name) + '">' +
            '<td class="text-center">' + rowNum + '</td>' +
            '<td>' + escHtml(name) + '</td>' +
            '<td>' +
                '<div class="input-group input-group-sm">' +
                    '<div class="input-group-prepend"><span class="input-group-text" style="font-size:.78rem;">₱</span></div>' +
                    '<input type="number" step="0.01" min="0" class="form-control est-price-input est-srp-input"' +
                           ' value="' + escAttr(srpVal) + '" placeholder="0.00">' +
                '</div>' +
            '</td>' +
            '<td>' +
                '<div class="input-group input-group-sm">' +
                    '<div class="input-group-prepend"><span class="input-group-text" style="font-size:.78rem;">₱</span></div>' +
                    '<input type="number" step="0.01" min="0" class="form-control est-price-input est-prev-input"' +
                           ' value="' + escAttr(prevVal) + '" placeholder="0.00">' +
                '</div>' +
            '</td>' +
            '<td class="text-center">' +
                '<button type="button" class="btn-remove-est" title="Remove">' +
                    '<i class="fas fa-times"></i>' +
                '</button>' +
            '</td>' +
            '</tr>'
        );

        // Remove button
        $row.find('.btn-remove-est').on('click', function () {
            var removedName = $row.data('est-name');
            $row.remove();
            renumberRows(tableBodyId);
            updateCountBadge(tableBodyId, countBadgeId, emptyMsgId);
            // Put the name back into the dropdown
            var $sel = tableBodyId === 'addEstTableBody' ? $('#addEstSelect') : $('#editEstSelect');
            $sel.append('<option value="' + escAttr(removedName) + '">' + escHtml(removedName) + '</option>');
            $sel.trigger('change.select2');
        });

        tbody.append($row);
        updateCountBadge(tableBodyId, countBadgeId, emptyMsgId);
    }

    // Expose for use in commodity-update.js
    window.appendEstRow = appendEstRow;
    window.getAddedNames = getAddedNames;

    // ── Re-number rows after a removal ───────────────────────────────────────
    function renumberRows(tableBodyId) {
        $('#' + tableBodyId + ' tr').each(function (i) {
            $(this).find('td:first').text(i + 1);
        });
    }

    // ── Update count badge and empty message ──────────────────────────────────
    function updateCountBadge(tableBodyId, countBadgeId, emptyMsgId) {
        var count = $('#' + tableBodyId + ' tr').length;
        if (count > 0) {
            $('#' + countBadgeId).text(count).show();
            $('#' + emptyMsgId).hide();
        } else {
            $('#' + countBadgeId).hide();
            $('#' + emptyMsgId).show();
        }
    }

    // ── Collect rows from a table body → pipe-encoded string ─────────────────
    function collectEstRows(tableBodyId) {
        var rows = [];
        $('#' + tableBodyId + ' tr').each(function () {
            var $r = $(this);
            rows.push({
                name: $r.data('est-name'),
                srp:  $r.find('.est-srp-input').val().trim(),
                prev: $r.find('.est-prev-input').val().trim()
            });
        });
        return rows;
    }

    // ── DataTable ─────────────────────────────────────────────────────────────
    const table = $('#tblCommodity').DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        processing: true,
        destroy: true,
        ajax: {
            url: '../../api/routes.php/commodity',
            type: 'GET',
            dataSrc: function (res) {
                if (res.status === 'success') return res.data || [];
                Swal.fire('Error', res.message || 'Unable to load commodities.', 'error');
                return [];
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire('Error', 'Failed to connect to commodity API.', 'error');
            }
        },
        columns: [
            { data: 'id' },
            { data: 'product_name' },
            { data: 'category_name' },
            { data: 'brand_name' },
            { data: 'unit_of_measure' },
            { data: 'srp', render: function (d) { return d ? '₱' + parseFloat(d).toLocaleString('en-PH',{minimumFractionDigits:2}) : 'N/A'; } },
            { data: 'prevailing_price', render: function (d) { return d ? '₱' + parseFloat(d).toLocaleString('en-PH',{minimumFractionDigits:2}) : 'N/A'; } },
            { data: 'Establishments', render: function (d) {
                if (!d) return '—';
                var names = d.split(',').map(function (c) { return c.split('|')[0].trim(); }).filter(Boolean);
                return names.length ? names.join(', ') : '—';
            }},
            { data: 'agency_name' },
            { data: null, orderable: false, searchable: false, render: function (d, t, row) {
                return '<button class="btn btn-sm btn-primary btn-edit mr-1" data-id="' + row.id + '">Edit</button>' +
                       '<button class="btn btn-sm btn-danger btn-delete" data-id="' + row.id + '">Delete</button>';
            }}
        ]
    });

    // Expose table globally
    window._commodityTable = table;

    // ── Init both Select2 pickers after establishments load ───────────────────
    loadCategories();
    loadEstablishments(function () {
        initEstSelect2('addEstSelect', 'addEstTableBody', 'addEstTableCount', 'addEstTableEmpty');
        initEstSelect2('editEstSelect', 'editEstTableBody', 'editEstTableCount', 'editEstTableEmpty');
    });

    // ── Open Add modal — reset ────────────────────────────────────────────────
    $('#btn_add_commodity').on('click', function () {
        $('#product_name, #brand_name, #unit_of_measure').val('');
        $('#category_id').val('');
        $('#addEstTableBody').empty();
        $('#addEstTableCount').hide();
        $('#addEstTableEmpty').show();
        // Refresh dropdown options (all unchecked)
        var refresh = $('#addEstSelect').data('refreshOptions');
        if (refresh) refresh();
        else { $('#addEstSelect').val(null).trigger('change'); }
    });

    // ── Save Commodity (Add) ──────────────────────────────────────────────────
    $('#btnSaveCommodity').on('click', function () {
        var productName   = $('#product_name').val().trim();
        var categoryId    = $('#category_id').val() || '';
        var brandName     = $('#brand_name').val().trim();
        var unitOfMeasure = $('#unit_of_measure').val().trim();

        if (!productName)   { Swal.fire('Required', 'Please enter the Commodity Name.', 'warning'); return; }
        if (!categoryId)    { Swal.fire('Required', 'Please select a Category.', 'warning'); return; }
        if (!unitOfMeasure) { Swal.fire('Required', 'Please enter the Unit of Measure.', 'warning'); return; }

        var estRows = collectEstRows('addEstTableBody');
        var estField = estRows.map(function (r) { return [r.name, r.srp, r.prev].join('|'); }).join(',');
        var firstSrp  = estRows.length && estRows[0].srp  ? estRows[0].srp  : '';
        var firstPrev = estRows.length && estRows[0].prev ? estRows[0].prev : '';

        $.ajax({
            url: '../../api/routes.php/commodity',
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({
                product_name:     productName,
                category_id:      categoryId,
                brand_name:       brandName,
                unit_of_measure:  unitOfMeasure,
                srp:              firstSrp,
                prevailing_price: firstPrev,
                Establishments:   estField
            }),
            success: function (res) {
                if (res.status !== 'success') { Swal.fire('Error', res.message || 'Unable to save.', 'error'); return; }
                Swal.fire('Success', res.message || 'Commodity added.', 'success');
                $('#addCommodityModal').modal('hide');
                table.ajax.reload(null, false);
            },
            error: function (xhr) {
                var msg = 'Unable to save commodity.';
                try { var e = JSON.parse(xhr.responseText); if (e.message) msg = e.message; } catch(_) {}
                Swal.fire('Error', msg, 'error');
            }
        });
    });

    // ── Reset Add modal on close ──────────────────────────────────────────────
    $('#addCommodityModal').on('hidden.bs.modal', function () {
        $('#product_name, #brand_name, #unit_of_measure').val('');
        $('#category_id').val('');
        $('#addEstTableBody').empty();
        $('#addEstTableCount').hide();
        $('#addEstTableEmpty').show();
    });

    // ── Delete ────────────────────────────────────────────────────────────────
    $(document).on('click', '.btn-delete', function () {
        var id = $(this).data('id');
        Swal.fire({ title:'Delete Commodity?', text:'This cannot be undone.', icon:'warning',
            showCancelButton:true, confirmButtonColor:'#d33', cancelButtonColor:'#3085d6', confirmButtonText:'Yes, delete it!'
        }).then(function (result) {
            if (!result.isConfirmed) return;
            fetch('../../api/routes.php/commodity?id=' + encodeURIComponent(id), { method:'DELETE' })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.status === 'success') { Swal.fire('Deleted!', res.message || 'Deleted.', 'success'); table.ajax.reload(null, false); }
                    else Swal.fire('Error', res.message || 'Unable to delete.', 'error');
                })
                .catch(function () { Swal.fire('Error', 'Network error.', 'error'); });
        });
    });

});
