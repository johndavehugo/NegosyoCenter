// ─────────────────────────────────────────────────────────────────────────────
// commodity-update.js
// Handles the Edit Commodity modal — two tabs:
//   Tab 1  Commodity Info       (fields + multi-select establishments)
//   Tab 2  Establishment Prices (inline editable table, single Save All button)
//
// Establishments stored as pipe-encoded comma string:
//   "Store A|120.00|110.00,Store B|135.00|130.00"
//   format: name|srp|prevailing_price
// Plain name-only entries ("Store A,Store B") are also supported (legacy).
// ─────────────────────────────────────────────────────────────────────────────

var _editCommodityId  = null;
var _editCommoditySrp = null;

// ── Parse / serialise helpers ─────────────────────────────────────────────────

function parseEstablishments(raw) {
    if (!raw || !raw.trim()) return [];
    return raw.split(',').map(function (chunk) {
        var parts = chunk.split('|');
        return {
            name:             (parts[0] || '').trim(),
            srp:              parts[1] !== undefined ? parts[1].trim() : '',
            prevailing_price: parts[2] !== undefined ? parts[2].trim() : ''
        };
    }).filter(function (e) { return e.name !== ''; });
}

function serialiseEstablishments(arr) {
    return arr.map(function (e) {
        return [e.name, e.srp || '', e.prevailing_price || ''].join('|');
    }).join(',');
}

// ── Escape helpers ────────────────────────────────────────────────────────────

function escapeHtml(str) {
    return String(str || '')
        .replace(/&/g, '&amp;').replace(/</g, '&lt;')
        .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
function escapeAttr(str) { return String(str || '').replace(/"/g, '&quot;'); }

// ── Dropdown loaders ──────────────────────────────────────────────────────────

function loadCategoryOptions(selectId) {
    return new Promise(function (resolve, reject) {
        $.getJSON('../../api/routes.php/price-monitoring?action=commodity_categories&agency_id=3')
            .done(function (res) {
                var opts = '<option value="" hidden>Select Category</option>';
                if (res.status === 'success' && Array.isArray(res.data)) {
                    res.data.forEach(function (c) {
                        opts += '<option value="' + c.category_id + '">' + c.category_name + '</option>';
                    });
                }
                $('#' + selectId).html(opts);
                resolve();
            })
            .fail(reject);
    });
}

function loadEstablishmentOptionsForEdit(selectId, selectedNames) {
    return new Promise(function (resolve, reject) {
        $.getJSON('../../api/routes.php/business', { length: -1 })
            .done(function (res) {
                var opts = '';
                if (res.status === 'success' && Array.isArray(res.data)) {
                    res.data.forEach(function (item) {
                        var name = item.juridical && item.juridical.name ? item.juridical.name : '';
                        if (name) opts += '<option value="' + name + '">' + name + '</option>';
                    });
                }
                $('#' + selectId).html(opts);

                if (!$('#' + selectId).hasClass('select2-hidden-accessible')) {
                    $('#' + selectId).select2({
                        theme: 'bootstrap4',
                        width: '100%',
                        placeholder: '-- Select Establishments --',
                        allowClear: true,
                        dropdownParent: $('#updateCommodityModal')
                    });
                }

                $('#' + selectId).val(selectedNames).trigger('change');
                resolve();
            })
            .fail(reject);
    });
}

// ── Establishment Prices tab ──────────────────────────────────────────────────

function loadEstablishmentPricesTab() {
    if (!_editCommodityId) return;

    var selectedNames = $('#updateCommodityEstablishments').val() || [];
    var tbody         = $('#estPriceTableBody');
    var emptyState    = $('#estPriceEmpty');
    var tableWrap     = $('#estPriceTableWrap');
    var badge         = $('#estPriceBadge');
    var saveBtn       = $('#btnSaveAllPrices');

    if (selectedNames.length === 0) {
        emptyState.show();
        tableWrap.hide();
        badge.hide();
        saveBtn.prop('disabled', true);
        return;
    }

    emptyState.hide();
    tableWrap.show();
    badge.text(selectedNames.length).show();
    saveBtn.prop('disabled', false);

    tbody.html(
        '<tr><td colspan="3" class="text-center text-muted py-3">' +
        '<i class="fas fa-spinner fa-spin mr-1"></i>Loading prices…</td></tr>'
    );

    fetch('../../api/routes.php/price-monitoring?action=commodity_establishments&commodity_id=' + _editCommodityId)
        .then(function (r) { return r.json(); })
        .then(function (result) {
            var saved = {};
            if (result.status === 'success' && Array.isArray(result.data)) {
                result.data.forEach(function (row) {
                    var key = (row.establishment_name || '').trim();
                    if (key) saved[key] = { srp: row.srp, prevailing_price: row.prevailing_price };
                });
            }
            renderEstablishmentPriceRows(selectedNames, saved);
        })
        .catch(function () { renderEstablishmentPriceRows(selectedNames, {}); });
}

function renderEstablishmentPriceRows(names, saved) {
    var tbody = $('#estPriceTableBody');
    tbody.empty();

    names.forEach(function (name) {
        var s       = saved[name] || {};
        var srpVal  = (s.srp !== undefined && s.srp !== null) ? s.srp : (_editCommoditySrp || '');
        var prevVal = (s.prevailing_price !== undefined && s.prevailing_price !== null) ? s.prevailing_price : '';

        var srpFmt  = srpVal  !== '' ? parseFloat(srpVal).toFixed(2)  : '';
        var prevFmt = prevVal !== '' ? parseFloat(prevVal).toFixed(2) : '';

        // No Action column — single Save All button at footer handles all rows
        tbody.append(
            '<tr data-est-name="' + escapeAttr(name) + '">' +
            '<td class="pl-3"><span style="font-size:.875rem;">' + escapeHtml(name) + '</span></td>' +
            '<td>' +
                '<div class="input-group input-group-sm">' +
                    '<div class="input-group-prepend"><span class="input-group-text" style="font-size:.78rem;padding:4px 8px;">₱</span></div>' +
                    '<input type="number" step="0.01" min="0" class="form-control est-price-input est-srp-input" ' +
                           'value="' + escapeAttr(srpFmt) + '" placeholder="0.00">' +
                '</div>' +
            '</td>' +
            '<td>' +
                '<div class="input-group input-group-sm">' +
                    '<div class="input-group-prepend"><span class="input-group-text" style="font-size:.78rem;padding:4px 8px;">₱</span></div>' +
                    '<input type="number" step="0.01" min="0" class="form-control est-price-input est-prev-input" ' +
                           'value="' + escapeAttr(prevFmt) + '" placeholder="0.00">' +
                '</div>' +
            '</td>' +
            '</tr>'
        );
    });
}

// ── Save All Prices (single button, Edit modal Tab 2) ─────────────────────────

function saveAllEstPrices() {
    var allRows = [];
    $('#estPriceTableBody tr').each(function () {
        var $r = $(this);
        allRows.push({
            name:             $r.data('est-name'),
            srp:              $r.find('.est-srp-input').val().trim(),
            prevailing_price: $r.find('.est-prev-input').val().trim()
        });
    });

    if (allRows.length === 0) {
        Swal.fire('Nothing to Save', 'No establishment rows found.', 'info');
        return;
    }

    var newEstField = serialiseEstablishments(allRows);

    var $btn = $('#btnSaveAllPrices');
    $btn.prop('disabled', true).html(
        '<i class="fas fa-spinner fa-spin mr-1"></i>Saving…'
    );

    fetch('../../api/routes.php/commodity', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id:               _editCommodityId,
            product_name:     $('#updateCommodityProductName').val().trim(),
            category_id:      $('#updateCommodityCategory').val(),
            brand_name:       $('#updateCommodityBrand').val().trim(),
            unit_of_measure:  $('#updateCommodityUnit').val().trim(),
            srp:              $('#updateCommoditySrp').val(),
            prevailing_price: $('#updateCommodityPrevailingPrice').val(),
            Establishments:   newEstField
        })
    })
    .then(function (r) { return r.json(); })
    .then(function (res) {
        $btn.prop('disabled', false).html(
            '<i class="material-icons mr-1" style="font-size:18px;">save</i>Save All Prices'
        );
        if (res.status === 'success') {
            // Flash all rows green
            $('#estPriceTableBody tr').addClass('est-row-saved');
            setTimeout(function () {
                $('#estPriceTableBody tr').removeClass('est-row-saved');
            }, 2000);
            Swal.fire({ icon: 'success', title: 'Saved!', text: 'All establishment prices updated.', timer: 1400, showConfirmButton: false });
            if ($.fn.DataTable.isDataTable('#tblCommodity')) {
                $('#tblCommodity').DataTable().ajax.reload(null, false);
            }
        } else {
            Swal.fire('Error', res.message || 'Unable to save prices.', 'error');
        }
    })
    .catch(function (err) {
        console.error(err);
        $btn.prop('disabled', false).html(
            '<i class="material-icons mr-1" style="font-size:18px;">save</i>Save All Prices'
        );
        Swal.fire('Error', 'Network error. Please try again.', 'error');
    });
}

// ── Open Edit modal ───────────────────────────────────────────────────────────

$(document).on('click', '.btn-edit', function () {
    var row = $('#tblCommodity').DataTable().row($(this).closest('tr')).data();
    if (!row || !row.id) {
        Swal.fire('Error', 'Unable to retrieve Commodity ID.', 'error');
        return;
    }

    _editCommodityId  = row.id;
    _editCommoditySrp = row.srp || null;

    var estEntries    = parseEstablishments(row.Establishments || '');
    var selectedNames = estEntries.map(function (e) { return e.name; });

    $('#tab-info-link').tab('show');
    $('#estPriceBadge').hide();
    $('#btnSaveAllPrices').prop('disabled', false);
    $('#updateCommoditySubtitle').text(row.product_name || '');

    loadCategoryOptions('updateCommodityCategory')
        .then(function () {
            return loadEstablishmentOptionsForEdit('updateCommodityEstablishments', selectedNames);
        })
        .then(function () {
            $('#updateCommodityId').val(row.id);
            $('#updateCommodityProductName').val(row.product_name);
            $('#updateCommodityCategory').val(row.category_id);
            $('#updateCommodityBrand').val(row.brand_name);
            $('#updateCommodityUnit').val(row.unit_of_measure);
            $('#updateCommoditySrp').val(row.srp !== null && row.srp !== undefined ? row.srp : '');
            $('#updateCommodityPrevailingPrice').val(
                row.prevailing_price !== null && row.prevailing_price !== undefined ? row.prevailing_price : ''
            );
            $('#updateCommodityModal').appendTo('body').modal('show');
        })
        .catch(function (err) {
            console.error(err);
            Swal.fire('Error', 'Failed to load form data.', 'error');
        });
});

// ── Switch to Establishment Prices tab → load table ───────────────────────────

$('#tab-est-link').on('shown.bs.tab', function () {
    loadEstablishmentPricesTab();
});

// ── Reset modal on close ──────────────────────────────────────────────────────

$('#updateCommodityModal').on('hidden.bs.modal', function () {
    document.getElementById('updateCommodityForm').reset();
    $('#updateCommodityEstablishments').val(null).trigger('change');
    $('#updateCommoditySubtitle').text('');
    $('#estPriceTableBody').empty();
    $('#estPriceBadge').hide();
    _editCommodityId  = null;
    _editCommoditySrp = null;
});

// ── Save Commodity Info (Tab 1 button) ────────────────────────────────────────

function updateCommodity() {
    var selectedNames = $('#updateCommodityEstablishments').val() || [];

    // Preserve any prices already in Tab 2 rows (if visited)
    var existingRows = [];
    $('#estPriceTableBody tr').each(function () {
        var $r = $(this);
        existingRows.push({
            name:             $r.data('est-name'),
            srp:              $r.find('.est-srp-input').val().trim(),
            prevailing_price: $r.find('.est-prev-input').val().trim()
        });
    });

    var estField;
    if (existingRows.length > 0) {
        var existingNames = existingRows.map(function (r) { return r.name; });
        // Add newly selected names not yet in the table
        selectedNames.forEach(function (n) {
            if (existingNames.indexOf(n) === -1) existingRows.push({ name: n, srp: '', prevailing_price: '' });
        });
        // Remove de-selected names
        existingRows = existingRows.filter(function (r) { return selectedNames.indexOf(r.name) !== -1; });
        estField = serialiseEstablishments(existingRows);
    } else {
        estField = selectedNames.join(',');
    }

    var data = {
        id:               $('#updateCommodityId').val(),
        product_name:     $('#updateCommodityProductName').val().trim(),
        category_id:      $('#updateCommodityCategory').val(),
        brand_name:       $('#updateCommodityBrand').val().trim(),
        unit_of_measure:  $('#updateCommodityUnit').val().trim(),
        srp:              $('#updateCommoditySrp').val(),
        prevailing_price: $('#updateCommodityPrevailingPrice').val(),
        Establishments:   estField
    };

    if (!data.product_name || !data.category_id || !data.unit_of_measure) {
        Swal.fire('Warning', 'Please fill in all required fields.', 'warning');
        return;
    }

    fetch('../../api/routes.php/commodity', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(function (r) { return r.json(); })
    .then(function (res) {
        if (res.status === 'success') {
            Swal.fire({ icon: 'success', title: 'Saved!', text: 'Commodity info updated.', timer: 1400, showConfirmButton: false })
                .then(function () {
                    $('#tab-est-link').tab('show');
                    $('#tblCommodity').DataTable().ajax.reload(null, false);
                });
        } else {
            Swal.fire('Error', res.message || 'Unable to update commodity.', 'error');
        }
    })
    .catch(function (err) {
        console.error(err);
        Swal.fire('Error', 'Network error', 'error');
    });
}
