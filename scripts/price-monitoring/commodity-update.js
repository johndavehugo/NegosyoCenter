// commodity-update.js

var _editCommodityId  = null;
var _editCommoditySrp = null;

// ── Parse / serialise ─────────────────────────────────────────────────────────

function parseEstablishments(raw) {
    if (!raw || !raw.trim()) return [];
    return raw.split(',').map(function (chunk) {
        var parts = chunk.split('|');
        return { name: (parts[0]||'').trim(), srp: (parts[1]||'').trim(), prev: (parts[2]||'').trim() };
    }).filter(function (e) { return e.name !== ''; });
}

function serialiseEstablishments(rows) {
    return rows.map(function (r) { return [r.name, r.srp||'', r.prev||''].join('|'); }).join(',');
}

// ── Escape ────────────────────────────────────────────────────────────────────
function escapeHtml(s) { return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
function escapeAttr(s) { return String(s||'').replace(/"/g,'&quot;'); }

// ── Category dropdown ─────────────────────────────────────────────────────────
function loadCategoryOptions(selectId) {
    return new Promise(function (resolve, reject) {
        $.getJSON('../../api/routes.php/price-monitoring?action=commodity_categories&agency_id=3')
            .done(function (res) {
                var opts = '<option value="" hidden>Select Category</option>';
                if (res.status === 'success') {
                    res.data.forEach(function (c) {
                        opts += '<option value="' + c.category_id + '">' + c.category_name + '</option>';
                    });
                }
                $('#' + selectId).html(opts);
                resolve();
            }).fail(reject);
    });
}

// ── Populate Edit table with pre-existing establishments ──────────────────────
function populateEditEstTable(estEntries) {
    var tbody = $('#editEstTableBody');
    tbody.empty();
    $('#editEstTableCount').hide();
    $('#editEstTableEmpty').show();

    // Wait until _allEstablishments + appendEstRow are ready (loaded by commodity-table.js)
    var attempts = 0;
    var interval = setInterval(function () {
        attempts++;
        if (typeof window.appendEstRow === 'function' && typeof window._allEstablishments !== 'undefined') {
            clearInterval(interval);

            // Rebuild the editEstSelect options (exclude already-added names)
            var addedNames = estEntries.map(function (e) { return e.name; });
            var $sel = $('#editEstSelect');
            $sel.empty().append('<option value=""></option>');
            window._allEstablishments.forEach(function (name) {
                if (addedNames.indexOf(name) === -1) {
                    $sel.append('<option value="' + escapeAttr(name) + '">' + escapeHtml(name) + '</option>');
                }
            });
            $sel.val(null).trigger('change');

            // Append rows
            estEntries.forEach(function (e) {
                window.appendEstRow('editEstTableBody', 'editEstTableCount', 'editEstTableEmpty', e.name, e.srp, e.prev);
            });

        } else if (attempts > 30) {
            clearInterval(interval);
        }
    }, 100);
}

// ── Establishment Prices tab (Tab 2) — mirrors data from Tab 1 table ──────────
function loadEstablishmentPricesTab() {
    var rows = [];
    $('#editEstTableBody tr').each(function () {
        var $r = $(this);
        rows.push({
            name: $r.data('est-name'),
            srp:  $r.find('.est-srp-input').val().trim(),
            prev: $r.find('.est-prev-input').val().trim()
        });
    });

    var tbody   = $('#estPriceTableBody');
    var saveBtn = $('#btnSaveAllPrices');

    if (rows.length === 0) {
        $('#estPriceEmpty').show();
        $('#estPriceTableWrap').hide();
        $('#estPriceBadge').hide();
        saveBtn.prop('disabled', true);
        return;
    }

    $('#estPriceEmpty').hide();
    $('#estPriceTableWrap').show();
    $('#estPriceBadge').text(rows.length).show();
    saveBtn.prop('disabled', false);

    // Fetch saved per-establishment prices to pre-fill
    tbody.html('<tr><td colspan="3" class="text-center text-muted py-3"><i class="fas fa-spinner fa-spin mr-1"></i>Loading…</td></tr>');

    fetch('../../api/routes.php/price-monitoring?action=commodity_establishments&commodity_id=' + _editCommodityId)
        .then(function (r) { return r.json(); })
        .then(function (result) {
            var saved = {};
            if (result.status === 'success') {
                result.data.forEach(function (row) {
                    saved[(row.establishment_name||'').trim()] = { srp: row.srp, prevailing_price: row.prevailing_price };
                });
            }

            tbody.empty();
            rows.forEach(function (r) {
                var s       = saved[r.name] || {};
                // Prefer Tab 1 inputs if filled, else saved API values
                var srpVal  = r.srp  !== '' ? r.srp  : (s.srp  !== undefined && s.srp  !== null ? s.srp  : '');
                var prevVal = r.prev !== '' ? r.prev : (s.prevailing_price !== undefined && s.prevailing_price !== null ? s.prevailing_price : '');
                var srpFmt  = srpVal  !== '' ? parseFloat(srpVal).toFixed(2)  : '';
                var prevFmt = prevVal !== '' ? parseFloat(prevVal).toFixed(2) : '';

                tbody.append(
                    '<tr data-est-name="' + escapeAttr(r.name) + '">' +
                    '<td class="pl-3" style="font-size:.875rem;">' + escapeHtml(r.name) + '</td>' +
                    '<td><div class="input-group input-group-sm">' +
                        '<div class="input-group-prepend"><span class="input-group-text" style="font-size:.78rem;padding:4px 8px;">₱</span></div>' +
                        '<input type="number" step="0.01" min="0" class="form-control est-price-inp est-srp-input" value="' + escapeAttr(srpFmt) + '" placeholder="0.00">' +
                    '</div></td>' +
                    '<td><div class="input-group input-group-sm">' +
                        '<div class="input-group-prepend"><span class="input-group-text" style="font-size:.78rem;padding:4px 8px;">₱</span></div>' +
                        '<input type="number" step="0.01" min="0" class="form-control est-price-inp est-prev-input" value="' + escapeAttr(prevFmt) + '" placeholder="0.00">' +
                    '</div></td>' +
                    '</tr>'
                );
            });
        })
        .catch(function () {
            tbody.empty();
            rows.forEach(function (r) {
                tbody.append(
                    '<tr data-est-name="' + escapeAttr(r.name) + '">' +
                    '<td class="pl-3">' + escapeHtml(r.name) + '</td>' +
                    '<td><div class="input-group input-group-sm"><div class="input-group-prepend"><span class="input-group-text" style="font-size:.78rem;padding:4px 8px;">₱</span></div><input type="number" step="0.01" min="0" class="form-control est-price-inp est-srp-input" placeholder="0.00"></div></td>' +
                    '<td><div class="input-group input-group-sm"><div class="input-group-prepend"><span class="input-group-text" style="font-size:.78rem;padding:4px 8px;">₱</span></div><input type="number" step="0.01" min="0" class="form-control est-price-inp est-prev-input" placeholder="0.00"></div></td>' +
                    '</tr>'
                );
            });
        });
}

// ── Save All Prices ───────────────────────────────────────────────────────────
function saveAllEstPrices() {
    var allRows = [];
    $('#estPriceTableBody tr').each(function () {
        var $r = $(this);
        allRows.push({ name: $r.data('est-name'), srp: $r.find('.est-srp-input').val().trim(), prev: $r.find('.est-prev-input').val().trim() });
    });

    if (allRows.length === 0) { Swal.fire('Nothing to Save', 'No establishment rows found.', 'info'); return; }

    var $btn = $('#btnSaveAllPrices');
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Saving…');

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
            Establishments:   serialiseEstablishments(allRows)
        })
    })
    .then(function (r) { return r.json(); })
    .then(function (res) {
        $btn.prop('disabled', false).html('<i class="material-icons mr-1" style="font-size:18px;">save</i>Save All Prices');
        if (res.status === 'success') {
            $('#estPriceTableBody tr').addClass('est-row-saved');
            setTimeout(function () { $('#estPriceTableBody tr').removeClass('est-row-saved'); }, 2000);
            Swal.fire({ icon:'success', title:'Saved!', text:'All establishment prices updated.', timer:1400, showConfirmButton:false });
            if ($.fn.DataTable.isDataTable('#tblCommodity')) $('#tblCommodity').DataTable().ajax.reload(null, false);
        } else {
            Swal.fire('Error', res.message || 'Unable to save prices.', 'error');
        }
    })
    .catch(function (err) {
        console.error(err);
        $btn.prop('disabled', false).html('<i class="material-icons mr-1" style="font-size:18px;">save</i>Save All Prices');
        Swal.fire('Error', 'Network error. Please try again.', 'error');
    });
}

// ── Open Edit modal ───────────────────────────────────────────────────────────
$(document).on('click', '.btn-edit', function () {
    var row = $('#tblCommodity').DataTable().row($(this).closest('tr')).data();
    if (!row || !row.id) { Swal.fire('Error', 'Unable to retrieve Commodity ID.', 'error'); return; }

    _editCommodityId  = row.id;
    _editCommoditySrp = row.srp || null;

    var estEntries = parseEstablishments(row.Establishments || '');

    $('#tab-info-link').tab('show');
    $('#estPriceBadge').hide();
    $('#btnSaveAllPrices').prop('disabled', false);
    $('#updateCommoditySubtitle').text(row.product_name || '');

    loadCategoryOptions('updateCommodityCategory')
        .then(function () {
            $('#updateCommodityId').val(row.id);
            $('#updateCommodityProductName').val(row.product_name);
            $('#updateCommodityCategory').val(row.category_id);
            $('#updateCommodityBrand').val(row.brand_name);
            $('#updateCommodityUnit').val(row.unit_of_measure);
            $('#updateCommoditySrp').val(row.srp !== null && row.srp !== undefined ? row.srp : '');
            $('#updateCommodityPrevailingPrice').val(row.prevailing_price !== null && row.prevailing_price !== undefined ? row.prevailing_price : '');
            populateEditEstTable(estEntries);
            $('#updateCommodityModal').appendTo('body').modal('show');
        })
        .catch(function (err) {
            console.error(err);
            Swal.fire('Error', 'Failed to load form data.', 'error');
        });
});

// ── Tab 2 shown → load price rows ────────────────────────────────────────────
$('#tab-est-link').on('shown.bs.tab', function () {
    loadEstablishmentPricesTab();
});

// ── Reset modal on close ──────────────────────────────────────────────────────
$('#updateCommodityModal').on('hidden.bs.modal', function () {
    document.getElementById('updateCommodityForm').reset();
    $('#editEstTableBody').empty();
    $('#editEstTableCount').hide();
    $('#editEstTableEmpty').show();
    $('#estPriceTableBody').empty();
    $('#estPriceBadge').hide();
    $('#updateCommoditySubtitle').text('');
    _editCommodityId  = null;
    _editCommoditySrp = null;
});

// ── Save Commodity Info (Tab 1) ───────────────────────────────────────────────
function updateCommodity() {
    var rows = [];
    $('#editEstTableBody tr').each(function () {
        var $r = $(this);
        rows.push({ name: $r.data('est-name'), srp: $r.find('.est-srp-input').val().trim(), prev: $r.find('.est-prev-input').val().trim() });
    });

    var data = {
        id:               $('#updateCommodityId').val(),
        product_name:     $('#updateCommodityProductName').val().trim(),
        category_id:      $('#updateCommodityCategory').val(),
        brand_name:       $('#updateCommodityBrand').val().trim(),
        unit_of_measure:  $('#updateCommodityUnit').val().trim(),
        srp:              $('#updateCommoditySrp').val(),
        prevailing_price: $('#updateCommodityPrevailingPrice').val(),
        Establishments:   serialiseEstablishments(rows)
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
            Swal.fire({ icon:'success', title:'Saved!', text:'Commodity info updated.', timer:1400, showConfirmButton:false })
                .then(function () {
                    $('#tab-est-link').tab('show');
                    if ($.fn.DataTable.isDataTable('#tblCommodity')) $('#tblCommodity').DataTable().ajax.reload(null, false);
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
