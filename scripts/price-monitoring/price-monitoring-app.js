/**
 * price-monitoring-app.js
 * Unified controller for the Price Monitoring SPA page.
 * All five tabs (Price Monitoring, Categories, Commodities, Agencies, Price View)
 * are lazy-initialised on first activation — no page reloads needed.
 */

/* ── Namespace ─────────────────────────────────────────────────────────────── */
window.PM = (function ($) {
    'use strict';

    /* ── API base helper ─────────────────────────────────────────────────── */
    function api(path) {
        var base = window.location.pathname.split('/pages/')[0];
        return window.location.origin + base + '/api/routes.php/' + path;
    }

    /* ── Peso formatter ──────────────────────────────────────────────────── */
    function peso(val) {
        var n = Number(val);
        if (val === null || val === undefined || val === '' || isNaN(n)) return '—';
        return '₱' + n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    /* ── HTML escape ─────────────────────────────────────────────────────── */
    function esc(s) {
        return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
    function escAttr(s) { return String(s || '').replace(/"/g, '&quot;'); }

    /* ════════════════════════════════════════════════════════════════════════
       TAB 1 — PRICE MONITORING
    ════════════════════════════════════════════════════════════════════════ */
    var _pmInit = false;
    var _priceTable = null;

    function initPriceMonitoring() {
        if (_pmInit) return;
        _pmInit = true;

        $('#price_agency').select2({ theme: 'bootstrap4', width: '100%' });
        $('#filter_category').select2({ theme: 'bootstrap4', width: '100%' });

        _priceTable = $('#tblPriceMonitoring').DataTable({
            responsive: true,
            autoWidth: false,
            columns: [
                { data: 'product_name', defaultContent: '—' },
                { data: 'category_name', defaultContent: '—' },
                {
                    data: null,
                    render: function (d, t, row) {
                        var b = row.brand_name || '', u = row.unit_of_measure || '';
                        return (b && u) ? b + ' / ' + u : (b || u || '—');
                    }
                },
                { data: 'establishments_display', defaultContent: '—' },
                {
                    data: null,
                    render: function (d, t, row) { return row.agency_name || row.agency_code || '—'; }
                },
                { data: 'srp', render: function (d) { return d ? peso(d) : '—'; } },
                { data: 'prevailing_price', render: function (d) { return d ? peso(d) : '—'; } },
                {
                    data: 'status',
                    render: function (d) {
                        return d === 'ACTIVE'
                            ? '<span class="badge badge-success">ACTIVE</span>'
                            : '<span class="badge badge-secondary">INACTIVE</span>';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function (d, t, row) {
                        if (!row.id) {
                            return '<button class="btn btn-success btn-sm btn-add-price" data-id="' + row.commodity_id + '"><i class="fas fa-plus"></i></button>';
                        }
                        return '<button class="btn btn-primary btn-sm btn-edit-price" data-id="' + row.id + '"><i class="fas fa-edit"></i></button>';
                    }
                }
            ]
        });

        _loadAgenciesDropdown();

        $('#price_agency').on('change', function () {
            _updateAgencyUI();
            _loadPrices();
        });

        $('#filter_category').on('change', function () {
            _priceTable.column(1).search(this.value).draw();
        });

        /* Price modal — add */
        $(document).on('click', '.btn-add-price', function () {
            var row = _priceTable.row($(this).closest('tr')).data();
            $('#priceForm')[0].reset();
            $('#priceId').val('');
            $('#priceCommodityId').val($(this).data('id'));
            $('#priceSrp').val(row.srp || '');
            $('#pricePrevailingPrice').val(row.prevailing_price || '');
            $('#priceStatus').val('ACTIVE');
            $('#priceModalLabel').text('Add Price / Set Status');
            $('#priceModal').appendTo('body').modal('show');
        });

        /* Price modal — edit */
        $(document).on('click', '.btn-edit-price', function () {
            var row = _priceTable.row($(this).closest('tr')).data();
            if (!row) { Swal.fire('Error', 'Unable to retrieve row data.', 'error'); return; }
            $('#priceForm')[0].reset();
            $('#priceId').val(row.id || 0);
            $('#priceCommodityId').val(row.commodity_id || '');
            $('#priceSrp').val(row.srp != null ? row.srp : '');
            $('#pricePrevailingPrice').val(row.prevailing_price != null ? row.prevailing_price : '');
            $('#priceStatus').val(String(row.status || 'ACTIVE').toUpperCase());
            $('#priceModalLabel').text('Edit SRP, Prevailing Price & Status');
            $('#priceModal').appendTo('body').modal('show');
        });

        $('#priceModal').on('hidden.bs.modal', function () { $('#priceForm')[0].reset(); });
    }

    function _loadAgenciesDropdown() {
        fetch(api('price?action=agencies'))
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.status !== 'success') throw new Error(res.message || 'Unable to load agencies.');
                var $sel = $('#price_agency');
                $sel.empty();
                if (!res.data || !res.data.length) throw new Error('No agencies available.');
                res.data.forEach(function (a) {
                    $sel.append($('<option>', {
                        value: a.id,
                        text: a.code ? a.name + ' (' + a.code + ')' : a.name
                    }));
                });
                $sel.val(res.data[0].id).trigger('change');
            })
            .catch(function (e) { Swal.fire('Error', e.message || 'Unable to load agencies.', 'error'); });
    }

    function _loadPrices() {
        var agencyId = $('#price_agency').val();
        fetch(api('price?agency_id=' + agencyId))
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.status !== 'success') throw new Error(res.message || 'Unable to load price data.');
                var data = res.data || [];
                _priceTable.clear().rows.add(data).draw();
                _loadCategoryFilter(data);
                _updateSummaryCards(data);
            })
            .catch(function (e) { Swal.fire('Error', e.message || 'Unable to load price data.', 'error'); });
    }

    function _loadCategoryFilter(data) {
        var cats = Array.from(new Set(data.map(function (r) { return r.category_name; }).filter(Boolean))).sort();
        var $sel = $('#filter_category');
        $sel.html('<option value="">All Categories</option>');
        cats.forEach(function (c) { $sel.append($('<option>', { value: c, text: c })); });
    }

    function _updateSummaryCards(data) {
        $('#total_monitored').text(data.length);
        $('#total_active').text(data.filter(function (r) { return r.status === 'ACTIVE'; }).length);
        $('#total_inactive').text(data.filter(function (r) { return r.status === 'INACTIVE'; }).length);
    }

    function _updateAgencyUI() {
        var name = $('#price_agency option:selected').text() || 'Agency';
        $('#selected_agency_name').text(name);
    }

    /* Public: save price (add / edit) */
    function savePrice() {
        var id = $('#priceId').val();
        var isEdit = id !== '' && id !== null && id !== '0' && Number(id) !== 0;
        var data = {
            commodity_id: $('#priceCommodityId').val(),
            agency_id: $('#price_agency').val(),
            monitored_by_agency_id: $('#price_agency').val(),
            srp: $('#priceSrp').val(),
            prevailing_price: $('#pricePrevailingPrice').val(),
            status: $('#priceStatus').val()
        };
        if (isEdit) data.id = id;

        fetch('../../api/routes.php/price', {
            method: isEdit ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (res.status === 'success') {
                $('#priceModal').modal('hide');
                Swal.fire('Success!', res.message || 'Price record updated successfully.', 'success')
                    .then(function () { _loadPrices(); });
            } else {
                Swal.fire('Error', res.message || 'Unable to save price.', 'error');
            }
        })
        .catch(function () { Swal.fire('Error', 'Network error', 'error'); });
    }

    /* ════════════════════════════════════════════════════════════════════════
       TAB 2 — CATEGORIES
    ════════════════════════════════════════════════════════════════════════ */
    var _catInit = false;
    var _catTable = null;

    function initCategories() {
        if (_catInit) return;
        _catInit = true;

        _catTable = $('#tblCategories').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            ajax: {
                url: '../../api/routes.php/price-monitoring?action=commodity_categories',
                type: 'GET',
                dataSrc: function (res) {
                    if (res.status === 'success') return res.data || [];
                    Swal.fire('Error', res.message || 'Unable to load categories.', 'error');
                    return [];
                }
            },
            columns: [
                { data: 'category_id' },
                { data: 'category_name' },
                { data: 'agency_name' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (d, t, row) {
                        return '<div class="d-flex justify-content-center" style="gap:6px;">' +
                               '<button class="btn btn-sm btn-cat-edit" data-id="' + row.category_id + '" title="Edit Category" ' +
                               'style="background:#e8f0fe;color:#1a73e8;border:none;border-radius:6px;padding:4px 8px;transition:background .15s;"' +
                               ' onmouseover="this.style.background=\'#c5d8fb\'" onmouseout="this.style.background=\'#e8f0fe\'">' +
                               '<i class="material-icons" style="font-size:16px;vertical-align:middle;">edit</i></button>' +
                               '<button class="btn btn-sm btn-cat-delete" data-id="' + row.category_id + '" title="Delete Category" ' +
                               'style="background:#fce8e8;color:#d93025;border:none;border-radius:6px;padding:4px 8px;transition:background .15s;"' +
                               ' onmouseover="this.style.background=\'#f5c2c2\'" onmouseout="this.style.background=\'#fce8e8\'">' +
                               '<i class="material-icons" style="font-size:16px;vertical-align:middle;">delete</i></button>' +
                               '</div>';
                    }
                }
            ]
        });

        /* Load agencies into Add modal on open */
        $('#addCategoryModal').on('show.bs.modal', function () {
            _loadAgencyOptions('addAgencyType');
        });
        $('#addCategoryModal').on('hidden.bs.modal', function () {
            document.getElementById('addCategoryForm').reset();
        });

        /* Edit */
        $(document).on('click', '.btn-cat-edit', function () {
            var row = _catTable.row($(this).closest('tr')).data();
            if (!row) { Swal.fire('Error', 'Unable to retrieve Category ID.', 'error'); return; }
            _loadAgencyOptions('updateCategoryAgency').then(function () {
                $('#updateCategoryId').val(row.category_id);
                $('#updateCategoryName').val(row.category_name);
                $('#updateCategoryAgency').val(row.agency_id);
                $('#updateCategoryModal').appendTo('body').modal('show');
            });
        });
        $('#updateCategoryModal').on('hidden.bs.modal', function () {
            document.getElementById('updateCategoryForm').reset();
        });

        /* Delete */
        $(document).on('click', '.btn-cat-delete', function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Delete Category?', text: 'This cannot be undone.', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function (result) {
                if (!result.isConfirmed) return;
                fetch('../../api/routes.php/price-monitoring?id=' + encodeURIComponent(id), { method: 'DELETE' })
                    .then(function (r) { return r.json(); })
                    .then(function (res) {
                        if (res.status === 'success') {
                            Swal.fire('Deleted!', res.message || 'Category deleted.', 'success');
                            _catTable.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error', res.message || 'Unable to delete category.', 'error');
                        }
                    })
                    .catch(function () { Swal.fire('Error', 'Network error.', 'error'); });
            });
        });
    }

    function _loadAgencyOptions(selectId) {
        return new Promise(function (resolve, reject) {
            $.getJSON('../../api/routes.php/price?action=agencies')
                .done(function (res) {
                    var opts = '<option value="" hidden>Select Agency</option>';
                    if (res.status === 'success' && Array.isArray(res.data)) {
                        res.data.forEach(function (a) {
                            opts += '<option value="' + a.id + '">' + esc(a.name) + ' (' + esc(a.code || '') + ')</option>';
                        });
                    }
                    $('#' + selectId).html(opts);
                    resolve();
                }).fail(reject);
        });
    }

    /* Public: add category */
    function addCategory() {
        var data = {
            action: 'add_category',
            name: $('#addCategoryName').val().trim(),
            agency_id: $('#addAgencyType').val()
        };
        if (!data.name || !data.agency_id) {
            Swal.fire('Warning', 'Please fill in both Category Name and Agency.', 'warning');
            return;
        }
        fetch('../../api/routes.php/price-monitoring', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (res.status === 'success') {
                Swal.fire('Success!', res.message, 'success');
                $('#addCategoryModal').modal('hide');
                if (_catTable) _catTable.ajax.reload(null, false);
                // Also reload category dropdowns used in commodity modals
                _loadCommodityCategories('category_id');
                _loadCommodityCategories('updateCommodityCategory');
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        })
        .catch(function () { Swal.fire('Error', 'Network error', 'error'); });
    }

    /* Public: update category */
    function updateCategory() {
        var data = {
            action: 'update_category',
            category_id: $('#updateCategoryId').val(),
            name: $('#updateCategoryName').val().trim(),
            agency_id: $('#updateCategoryAgency').val()
        };
        if (!data.name || !data.agency_id) {
            Swal.fire('Warning', 'Please fill in all required fields.', 'warning');
            return;
        }
        fetch('../../api/routes.php/price-monitoring', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (res.status === 'success') {
                Swal.fire('Success!', res.message, 'success');
                $('#updateCategoryModal').modal('hide');
                if (_catTable) _catTable.ajax.reload(null, false);
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        })
        .catch(function () { Swal.fire('Error', 'Network error', 'error'); });
    }

    /* ════════════════════════════════════════════════════════════════════════
       TAB 3 — COMMODITIES
    ════════════════════════════════════════════════════════════════════════ */
    var _comInit = false;
    var _comTable = null;
    var _allEstablishments = [];
    var _editCommodityId = null;

    function initCommodities() {
        if (_comInit) return;
        _comInit = true;

        _comTable = $('#tblCommodity').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            ajax: {
                url: '../../api/routes.php/commodity',
                type: 'GET',
                dataSrc: function (res) {
                    if (res.status === 'success') return res.data || [];
                    Swal.fire('Error', res.message || 'Unable to load commodities.', 'error');
                    return [];
                }
            },
            columns: [
                { data: 'id' },
                { data: 'product_name' },
                { data: 'category_name' },
                { data: 'brand_name', defaultContent: '—' },
                { data: 'unit_of_measure' },
                {
                    data: 'Establishments',
                    render: function (d) {
                        if (!d) return '—';
                        var names = d.split(',').map(function (c) { return c.split('|')[0].trim(); }).filter(Boolean);
                        return names.length ? esc(names.join(', ')) : '—';
                    }
                },
                { data: 'agency_name', defaultContent: '—' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (d, t, row) {
                        return '<div class="d-flex justify-content-center" style="gap:6px;">' +
                               '<button class="btn btn-sm btn-com-edit" data-id="' + row.id + '" title="Edit Commodity" ' +
                               'style="background:#e8f0fe;color:#1a73e8;border:none;border-radius:6px;padding:4px 8px;transition:background .15s;"' +
                               ' onmouseover="this.style.background=\'#c5d8fb\'" onmouseout="this.style.background=\'#e8f0fe\'">' +
                               '<i class="material-icons" style="font-size:16px;vertical-align:middle;">edit</i></button>' +
                               '<button class="btn btn-sm btn-com-delete" data-id="' + row.id + '" title="Delete Commodity" ' +
                               'style="background:#fce8e8;color:#d93025;border:none;border-radius:6px;padding:4px 8px;transition:background .15s;"' +
                               ' onmouseover="this.style.background=\'#f5c2c2\'" onmouseout="this.style.background=\'#fce8e8\'">' +
                               '<i class="material-icons" style="font-size:16px;vertical-align:middle;">delete</i></button>' +
                               '</div>';
                    }
                }
            ]
        });

        window._commodityTable = _comTable; // keep compat ref

        /* Load categories + establishments */
        _loadCommodityCategories('category_id');
        _loadEstablishments(function () {
            _initEstSelect2('addEstSelect', 'addEstTableBody', 'addEstTableCount', 'addEstTableEmpty');
            _initEstSelect2('editEstSelect', 'editEstTableBody', 'editEstTableCount', 'editEstTableEmpty');
        });

        /* Open Add modal — reset */
        $('#btn_add_commodity').on('click', function () {
            _loadCommodityCategories('category_id');
            $('#product_name, #brand_name, #unit_of_measure').val('');
            $('#category_id').val('');
            $('#addEstTableBody').empty();
            $('#addEstTableCount').hide();
            $('#addEstTableEmpty').show();
            var refresh = $('#addEstSelect').data('refreshOptions');
            if (refresh) refresh();
        });

        /* Reset Add modal on close */
        $('#addCommodityModal').on('hidden.bs.modal', function () {
            $('#product_name, #brand_name, #unit_of_measure').val('');
            $('#category_id').val('');
            $('#addEstTableBody').empty();
            $('#addEstTableCount').hide();
            $('#addEstTableEmpty').show();
        });

        /* Edit */
        $(document).on('click', '.btn-com-edit', function () {
            var row = _comTable.row($(this).closest('tr')).data();
            if (!row || !row.id) { Swal.fire('Error', 'Unable to retrieve Commodity ID.', 'error'); return; }
            _editCommodityId = row.id;
            var estEntries = _parseEstablishments(row.Establishments || '');

            $('#tab-info-link').tab('show');
            $('#updateCommoditySubtitle').text(row.product_name || '');

            _loadCommodityCategories('updateCommodityCategory').then(function () {
                $('#updateCommodityId').val(row.id);
                $('#updateCommodityProductName').val(row.product_name);
                $('#updateCommodityCategory').val(row.category_id);
                $('#updateCommodityBrand').val(row.brand_name);
                $('#updateCommodityUnit').val(row.unit_of_measure);
                _populateEditEstTable(estEntries);
                $('#updateCommodityModal').appendTo('body').modal('show');
            });
        });

        /* Reset Edit modal on close */
        $('#updateCommodityModal').on('hidden.bs.modal', function () {
            document.getElementById('updateCommodityForm').reset();
            $('#editEstTableBody').empty();
            $('#editEstTableCount').hide();
            $('#editEstTableEmpty').show();
            $('#updateCommoditySubtitle').text('');
            _editCommodityId = null;
        });

        /* Delete */
        $(document).on('click', '.btn-com-delete', function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Delete Commodity?', text: 'This cannot be undone.', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function (result) {
                if (!result.isConfirmed) return;
                fetch('../../api/routes.php/commodity?id=' + encodeURIComponent(id), { method: 'DELETE' })
                    .then(function (r) { return r.json(); })
                    .then(function (res) {
                        if (res.status === 'success') {
                            Swal.fire('Deleted!', res.message || 'Deleted.', 'success');
                            _comTable.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error', res.message || 'Unable to delete.', 'error');
                        }
                    })
                    .catch(function () { Swal.fire('Error', 'Network error.', 'error'); });
            });
        });
    }

    /* Load category options into a <select> — returns Promise */
    function _loadCommodityCategories(selectId) {
        return new Promise(function (resolve, reject) {
            $.getJSON('../../api/routes.php/price-monitoring?action=commodity_categories')
                .done(function (res) {
                    var html = '<option value="">-- Select Category --</option>';
                    if (res.status === 'success') {
                        (res.data || []).forEach(function (c) {
                            html += '<option value="' + c.category_id + '">' + esc(c.category_name) + '</option>';
                        });
                    }
                    $('#' + selectId).html(html);
                    resolve();
                }).fail(reject);
        });
    }

    /* Load all establishment names from the MSME business API */
    function _loadEstablishments(cb) {
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
                if (cb) cb();
            }
        });
    }

    /* Init a Select2 picker for adding establishments to a table */
    function _initEstSelect2(selectId, tableBodyId, countBadgeId, emptyMsgId) {
        var $sel = $('#' + selectId);

        function refreshOptions() {
            var added = _getAddedNames(tableBodyId);
            $sel.empty().append('<option value=""></option>');
            _allEstablishments.forEach(function (name) {
                if (added.indexOf(name) === -1) {
                    $sel.append('<option value="' + escAttr(name) + '">' + esc(name) + '</option>');
                }
            });
            $sel.trigger('change.select2');
        }

        $sel.select2({
            theme: 'bootstrap4', width: '100%',
            placeholder: 'Search and select an establishment to add…',
            allowClear: true,
            dropdownParent: $sel.closest('.modal')
        });

        $sel.on('select2:select', function (e) {
            var name = e.params.data.text;
            _appendEstRow(tableBodyId, countBadgeId, emptyMsgId, name, '', '');
            $sel.find('option[value="' + escAttr(name) + '"]').remove();
            $sel.val(null).trigger('change');
            _updateCountBadge(tableBodyId, countBadgeId, emptyMsgId);
        });

        $sel.data('refreshOptions', refreshOptions);
    }

    function _getAddedNames(tableBodyId) {
        var names = [];
        $('#' + tableBodyId + ' tr').each(function () { names.push($(this).data('est-name')); });
        return names;
    }

    /* Expose for commodity-update compat */
    window.appendEstRow = _appendEstRow;
    window.getAddedNames = _getAddedNames;

    function _appendEstRow(tableBodyId, countBadgeId, emptyMsgId, name, srpVal, prevVal) {
        var tbody = $('#' + tableBodyId);
        var rowNum = tbody.find('tr').length + 1;

        var $row = $(
            '<tr data-est-name="' + escAttr(name) + '">' +
            '<td class="text-center">' + rowNum + '</td>' +
            '<td>' + esc(name) + '</td>' +
            '<td><div class="input-group input-group-sm"><div class="input-group-prepend"><span class="input-group-text" style="font-size:.78rem;">₱</span></div>' +
            '<input type="number" step="0.01" min="0" class="form-control est-price-input est-srp-input" value="' + escAttr(srpVal) + '" placeholder="0.00"></div></td>' +
            '<td><div class="input-group input-group-sm"><div class="input-group-prepend"><span class="input-group-text" style="font-size:.78rem;">₱</span></div>' +
            '<input type="number" step="0.01" min="0" class="form-control est-price-input est-prev-input" value="' + escAttr(prevVal) + '" placeholder="0.00"></div></td>' +
            '<td class="text-center"><button type="button" class="btn-remove-est" title="Remove"><i class="fas fa-times"></i></button></td>' +
            '</tr>'
        );

        $row.find('.btn-remove-est').on('click', function () {
            var removedName = $row.data('est-name');
            $row.remove();
            _renumberRows(tableBodyId);
            _updateCountBadge(tableBodyId, countBadgeId, emptyMsgId);
            var $sel = tableBodyId === 'addEstTableBody' ? $('#addEstSelect') : $('#editEstSelect');
            $sel.append('<option value="' + escAttr(removedName) + '">' + esc(removedName) + '</option>');
            $sel.trigger('change.select2');
        });

        tbody.append($row);
        _updateCountBadge(tableBodyId, countBadgeId, emptyMsgId);
    }

    function _renumberRows(tableBodyId) {
        $('#' + tableBodyId + ' tr').each(function (i) { $(this).find('td:first').text(i + 1); });
    }

    function _updateCountBadge(tableBodyId, countBadgeId, emptyMsgId) {
        var count = $('#' + tableBodyId + ' tr').length;
        if (count > 0) { $('#' + countBadgeId).text(count).show(); $('#' + emptyMsgId).hide(); }
        else { $('#' + countBadgeId).hide(); $('#' + emptyMsgId).show(); }
    }

    function _collectEstRows(tableBodyId) {
        var rows = [];
        $('#' + tableBodyId + ' tr').each(function () {
            var $r = $(this);
            rows.push({ name: $r.data('est-name'), srp: $r.find('.est-srp-input').val().trim(), prev: $r.find('.est-prev-input').val().trim() });
        });
        return rows;
    }

    function _serialiseEstablishments(rows) {
        return rows.map(function (r) { return [r.name, r.srp || '', r.prev || ''].join('|'); }).join(',');
    }

    function _parseEstablishments(raw) {
        if (!raw || !raw.trim()) return [];
        return raw.split(',').map(function (chunk) {
            var parts = chunk.split('|');
            return { name: (parts[0] || '').trim(), srp: (parts[1] || '').trim(), prev: (parts[2] || '').trim() };
        }).filter(function (e) { return e.name !== ''; });
    }

    function _populateEditEstTable(estEntries) {
        var tbody = $('#editEstTableBody');
        tbody.empty();
        $('#editEstTableCount').hide();
        $('#editEstTableEmpty').show();

        var attempts = 0;
        var iv = setInterval(function () {
            attempts++;
            if (typeof _allEstablishments !== 'undefined') {
                clearInterval(iv);
                var addedNames = estEntries.map(function (e) { return e.name; });
                var $sel = $('#editEstSelect');
                $sel.empty().append('<option value=""></option>');
                _allEstablishments.forEach(function (name) {
                    if (addedNames.indexOf(name) === -1) {
                        $sel.append('<option value="' + escAttr(name) + '">' + esc(name) + '</option>');
                    }
                });
                $sel.val(null).trigger('change');
                estEntries.forEach(function (e) {
                    _appendEstRow('editEstTableBody', 'editEstTableCount', 'editEstTableEmpty', e.name, e.srp, e.prev);
                });
            } else if (attempts > 30) { clearInterval(iv); }
        }, 100);
    }

    /* Public: save commodity (Add) */
    function saveCommodity() {
        var productName = $('#product_name').val().trim();
        var categoryId = $('#category_id').val() || '';
        var brandName = $('#brand_name').val().trim();
        var unitOfMeasure = $('#unit_of_measure').val().trim();

        if (!productName)   { Swal.fire('Required', 'Please enter the Commodity Name.', 'warning'); return; }
        if (!categoryId)    { Swal.fire('Required', 'Please select a Category.', 'warning'); return; }
        if (!unitOfMeasure) { Swal.fire('Required', 'Please enter the Unit of Measure.', 'warning'); return; }

        var estRows = _collectEstRows('addEstTableBody');
        var estField = estRows.map(function (r) { return [r.name, r.srp, r.prev].join('|'); }).join(',');
        var firstSrp  = estRows.length && estRows[0].srp  ? estRows[0].srp  : '';
        var firstPrev = estRows.length && estRows[0].prev ? estRows[0].prev : '';

        $.ajax({
            url: '../../api/routes.php/commodity',
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({
                product_name: productName, category_id: categoryId,
                brand_name: brandName, unit_of_measure: unitOfMeasure,
                srp: firstSrp, prevailing_price: firstPrev, Establishments: estField
            }),
            success: function (res) {
                if (res.status !== 'success') { Swal.fire('Error', res.message || 'Unable to save.', 'error'); return; }
                Swal.fire('Success', res.message || 'Commodity added.', 'success');
                $('#addCommodityModal').modal('hide');
                if (_comTable) _comTable.ajax.reload(null, false);
            },
            error: function (xhr) {
                var msg = 'Unable to save commodity.';
                try { var e = JSON.parse(xhr.responseText); if (e.message) msg = e.message; } catch (_) {}
                Swal.fire('Error', msg, 'error');
            }
        });
    }

    /* Public: update commodity info (Tab 1) */
    function updateCommodity() {
        var rows = _collectEstRows('editEstTableBody');
        var firstSrp  = rows.length && rows[0].srp  ? rows[0].srp  : '';
        var firstPrev = rows.length && rows[0].prev ? rows[0].prev : '';
        var data = {
            id: $('#updateCommodityId').val(),
            product_name: $('#updateCommodityProductName').val().trim(),
            category_id: $('#updateCommodityCategory').val(),
            brand_name: $('#updateCommodityBrand').val().trim(),
            unit_of_measure: $('#updateCommodityUnit').val().trim(),
            srp: firstSrp,
            prevailing_price: firstPrev,
            Establishments: _serialiseEstablishments(rows)
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
                $('#updateCommodityModal').modal('hide');
                Swal.fire({ icon: 'success', title: 'Saved!', text: 'Commodity updated successfully.', timer: 1400, showConfirmButton: false })
                    .then(function () {
                        if (_comTable) _comTable.ajax.reload(null, false);
                    });
            } else {
                Swal.fire('Error', res.message || 'Unable to update commodity.', 'error');
            }
        })
        .catch(function () { Swal.fire('Error', 'Network error', 'error'); });
    }

    /* ════════════════════════════════════════════════════════════════════════
       TAB 4 — AGENCIES
    ════════════════════════════════════════════════════════════════════════ */
    var _agencyInit = false;
    var _agencyTable = null;

    function initAgencies() {
        if (_agencyInit) return;
        _agencyInit = true;

        _agencyTable = $('#tblAgency').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            ajax: {
                url: '../../api/routes.php/agency',
                type: 'GET',
                dataSrc: function (res) {
                    if (res.status === 'success') return res.data || [];
                    Swal.fire('Error', res.message || 'Unable to load agencies.', 'error');
                    return [];
                }
            },
            columns: [
                { data: 'id' },
                { data: 'code' },
                { data: 'name' },
                { data: 'coverage', defaultContent: '—' },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (d, t, row) {
                        return '<div class="d-flex justify-content-center" style="gap:6px;">' +
                               '<button class="btn btn-sm btn-agency-edit" data-id="' + row.id + '" title="Edit Agency" ' +
                               'style="background:#e8f0fe;color:#1a73e8;border:none;border-radius:6px;padding:4px 8px;transition:background .15s;"' +
                               ' onmouseover="this.style.background=\'#c5d8fb\'" onmouseout="this.style.background=\'#e8f0fe\'">' +
                               '<i class="material-icons" style="font-size:16px;vertical-align:middle;">edit</i></button>' +
                               '<button class="btn btn-sm btn-agency-delete" data-id="' + row.id + '" title="Delete Agency" ' +
                               'style="background:#fce8e8;color:#d93025;border:none;border-radius:6px;padding:4px 8px;transition:background .15s;"' +
                               ' onmouseover="this.style.background=\'#f5c2c2\'" onmouseout="this.style.background=\'#fce8e8\'">' +
                               '<i class="material-icons" style="font-size:16px;vertical-align:middle;">delete</i></button>' +
                               '</div>';
                    }
                }
            ]
        });

        /* Reset Add form on close */
        $('#addAgencyModal').on('hidden.bs.modal', function () {
            $('#agency_code, #agency_name, #agency_coverage').val('');
        });

        /* Edit */
        $(document).on('click', '.btn-agency-edit', function () {
            var row = _agencyTable.row($(this).closest('tr')).data();
            if (!row || !row.id) { Swal.fire('Error', 'Unable to retrieve Agency ID.', 'error'); return; }
            $('#updateAgencyId').val(row.id);
            $('#updateAgencyCode').val(row.code);
            $('#updateAgencyName').val(row.name);
            $('#updateAgencyCoverage').val(row.coverage);
            $('#updateAgencyModal').appendTo('body').modal('show');
        });

        $('#updateAgencyModal').on('hidden.bs.modal', function () {
            document.getElementById('updateAgencyForm').reset();
        });

        /* Delete */
        $(document).on('click', '.btn-agency-delete', function () {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Delete Agency?', text: 'This cannot be undone.', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(function (result) {
                if (!result.isConfirmed) return;
                fetch('../../api/routes.php/agency?id=' + encodeURIComponent(id), { method: 'DELETE' })
                    .then(function (r) { return r.json(); })
                    .then(function (res) {
                        if (res.status === 'success') {
                            Swal.fire('Deleted!', res.message || 'Agency deleted.', 'success');
                            _agencyTable.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error', res.message || 'Unable to delete agency.', 'error');
                        }
                    })
                    .catch(function () { Swal.fire('Error', 'Network error.', 'error'); });
            });
        });
    }

    /* Public: save agency (Add) */
    function saveAgency() {
        var code     = String($('#agency_code').val() || '').trim();
        var name     = String($('#agency_name').val() || '').trim();
        var coverage = String($('#agency_coverage').val() || '').trim();

        if (!code) { Swal.fire('Required Field', 'Please enter the Agency Code.', 'warning'); return; }
        if (!name) { Swal.fire('Required Field', 'Please enter the Agency Name.', 'warning'); return; }

        $.ajax({
            url: '../../api/routes.php/agency',
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({ code: code, name: name, coverage: coverage }),
            success: function (res) {
                if (res.status !== 'success') { Swal.fire('Error', res.message || 'Unable to save agency.', 'error'); return; }
                Swal.fire('Success', res.message || 'Agency added successfully.', 'success');
                $('#addAgencyModal').modal('hide');
                $('#agency_code, #agency_name, #agency_coverage').val('');
                if (_agencyTable) _agencyTable.ajax.reload(null, false);
            },
            error: function (xhr) {
                var msg = 'Unable to save agency.';
                try { var e = JSON.parse(xhr.responseText); if (e.message) msg = e.message; } catch (_) {}
                Swal.fire('Error', msg, 'error');
            }
        });
    }

    /* Public: update agency */
    function updateAgency() {
        var data = {
            id: $('#updateAgencyId').val(),
            code: $('#updateAgencyCode').val().trim(),
            name: $('#updateAgencyName').val().trim(),
            coverage: $('#updateAgencyCoverage').val().trim()
        };
        if (!data.code || !data.name) { Swal.fire('Warning', 'Please fill in all required fields.', 'warning'); return; }

        fetch('../../api/routes.php/agency', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(function (r) { return r.json(); })
        .then(function (res) {
            if (res.status === 'success') {
                Swal.fire('Success!', res.message || 'Agency updated successfully.', 'success');
                $('#updateAgencyModal').modal('hide');
                if (_agencyTable) _agencyTable.ajax.reload(null, false);
            } else {
                Swal.fire('Error', res.message || 'Unable to update agency.', 'error');
            }
        })
        .catch(function () { Swal.fire('Error', 'Network error', 'error'); });
    }

    /* ════════════════════════════════════════════════════════════════════════
       TAB ROUTER — lazy init on first activation
    ════════════════════════════════════════════════════════════════════════ */
    $(function () {
        /* Boot the first tab (Price Monitoring) immediately */
        initPriceMonitoring();

        /* ── Sidebar highlight ───────────────────────────────────────────── */
        /* Map each tab pane href → the corresponding sidebar sub-link       */
        var _sidebarLinks = {
            '#pane-pm':          'a.pm-sub-link[data-pm-tab="pm"]',
            '#pane-categories':  'a.pm-sub-link[data-pm-tab="categories"]',
            '#pane-commodities': 'a.pm-sub-link[data-pm-tab="commodities"]',
            '#pane-agencies':    'a.pm-sub-link[data-pm-tab="agencies"]'
        };

        function _highlightSidebarLink(paneHref) {
            $('.nav-treeview a.pm-sub-link').removeClass('active');
            var selector = _sidebarLinks[paneHref];
            if (selector) {
                $(selector).addClass('active');
            }
        }

        /* ── Sidebar sub-link clicks ─────────────────────────────────────── */
        /* When already on this page, switch the tab directly (no navigation) */
        $(document).on('click', 'a.pm-sub-link', function (e) {
            /* Only intercept if we are on price-monitoring.php */
            if (window.location.pathname.indexOf('price-monitoring.php') === -1) {
                /* On a different page — let the href navigate normally,
                   but append ?tab= so we can activate the right tab on load */
                var tab = $(this).data('pm-tab');
                if (tab) {
                    e.preventDefault();
                    window.location.href = $(this).attr('href') + '?tab=' + tab;
                }
                return;
            }
            /* Already on price-monitoring.php — switch tab in-place */
            e.preventDefault();
            var tab = $(this).data('pm-tab');
            var tabMap = {
                'pm':          '#tab-pm',
                'categories':  '#tab-categories',
                'commodities': '#tab-commodities',
                'agencies':    '#tab-agencies'
            };
            if (tab && tabMap[tab]) {
                $(tabMap[tab]).tab('show');
            }
        });

        /* Highlight sidebar + lazy-init on tab switch */
        $('#pmTabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr('href');
            _highlightSidebarLink(target);
            if      (target === '#pane-categories')  initCategories();
            else if (target === '#pane-commodities') initCommodities();
            else if (target === '#pane-agencies')    initAgencies();
        });

        /* ── Deep link on page load ──────────────────────────────────────── */
        /* Support ?tab=categories (from sidebar click on another page)      */
        /* and legacy #categories hash                                        */
        var tabMap = {
            'pm':          '#tab-pm',
            'categories':  '#tab-categories',
            'commodities': '#tab-commodities',
            'agencies':    '#tab-agencies'
        };
        var hashMap = {
            '#pm':          '#tab-pm',
            '#categories':  '#tab-categories',
            '#commodities': '#tab-commodities',
            '#agencies':    '#tab-agencies'
        };

        var urlParams  = new URLSearchParams(window.location.search);
        var tabParam   = urlParams.get('tab');
        var hashParam  = window.location.hash;

        var targetTab = null;
        if (tabParam && tabMap[tabParam]) {
            targetTab = tabMap[tabParam];
        } else if (hashParam && hashMap[hashParam]) {
            targetTab = hashMap[hashParam];
        }

        if (targetTab) {
            $(targetTab).tab('show');
        } else {
            /* Default: highlight Price Monitoring sub-link */
            _highlightSidebarLink('#pane-pm');
        }

        /* Update URL hash when tab changes (no page reload) */
        $('#pmTabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var pane = $(e.target).attr('href').replace('#pane-', '#');
            if (history.replaceState) {
                history.replaceState(null, null, pane);
            }
        });
    });

    /* ── Public API ──────────────────────────────────────────────────────── */
    return {
        savePrice:       savePrice,
        addCategory:     addCategory,
        updateCategory:  updateCategory,
        saveCommodity:   saveCommodity,
        updateCommodity: updateCommodity,
        saveAgency:      saveAgency,
        updateAgency:    updateAgency
    };

}(jQuery));
