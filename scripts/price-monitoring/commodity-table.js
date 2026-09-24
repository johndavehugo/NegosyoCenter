$(function () {

    loadCategories();
    loadEstablishmentsFromMSME();

    // Initialize Select2 on the Add modal establishment multi-select
    $('#establishments').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: '-- Select Establishments --',
        allowClear: true,
        dropdownParent: $('#addCommodityModal')
    });

    const table = $('#tblCommodity').DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        processing: true,
        destroy: true,
        ajax: {
            url: '../../api/routes.php/commodity',
            type: 'GET',
            dataSrc: function (response) {
                if (response.status === 'success') return response.data || [];
                Swal.fire('Error', response.message || 'Unable to load commodities.', 'error');
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
            {
                data: 'srp',
                render: function (data) {
                    return data ? '₱' + parseFloat(data).toLocaleString('en-PH', { minimumFractionDigits: 2 }) : 'N/A';
                }
            },
            {
                data: 'prevailing_price',
                render: function (data) {
                    return data ? '₱' + parseFloat(data).toLocaleString('en-PH', { minimumFractionDigits: 2 }) : 'N/A';
                }
            },
            {
                data: 'Establishments',
                render: function (data) {
                    if (!data) return '—';
                    // Strip pipe-encoded prices, show only names
                    var names = data.split(',').map(function (chunk) {
                        return chunk.split('|')[0].trim();
                    }).filter(Boolean);
                    return names.length ? names.join(', ') : '—';
                }
            },
            { data: 'agency_name' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-primary btn-edit" data-id="${row.id}">Edit</button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${row.id}">Delete</button>
                    `;
                }
            }
        ]
    });

    // ── Reset Add modal on open ───────────────────────────────────────────────
    $('#btn_add_commodity').on('click', function () {
        $('#product_name').val('');
        $('#category_id').val('');
        $('#brand_name').val('');
        $('#unit_of_measure').val('');
        $('#establishments').val(null).trigger('change');
        $('#addEstPriceTableBody').empty();
        $('#addEstPriceEmpty').show();
        $('#addEstPriceTableWrap').hide();
        $('#addEstBadge').hide();
        // Always start on Tab 1
        $('#add-tab-info-link').tab('show');
    });

    // ── "Next: Set Prices" button — validate then switch to Tab 2 ────────────
    $('#btnNextToEstPrices').on('click', function () {
        var productName   = $('#product_name').val().trim();
        var categoryId    = $('#category_id').val();
        var unitOfMeasure = $('#unit_of_measure').val().trim();

        if (!productName) {
            Swal.fire('Required Field', 'Please enter the Commodity Name.', 'warning');
            return;
        }
        if (!categoryId) {
            Swal.fire('Required Field', 'Please select a Category.', 'warning');
            return;
        }
        if (!unitOfMeasure) {
            Swal.fire('Required Field', 'Please enter the Unit of Measure.', 'warning');
            return;
        }

        // Switch to Tab 2 — tab shown event will render the table
        $('#add-tab-est-link').tab('show');
    });

    // ── Render establishment price rows when Add Tab 2 is shown ──────────────
    $('#add-tab-est-link').on('shown.bs.tab', function () {
        var selectedNames = $('#establishments').val() || [];
        var tbody         = $('#addEstPriceTableBody');
        var emptyState    = $('#addEstPriceEmpty');
        var tableWrap     = $('#addEstPriceTableWrap');
        var badge         = $('#addEstBadge');

        if (selectedNames.length === 0) {
            emptyState.show();
            tableWrap.hide();
            badge.hide();
            return;
        }

        emptyState.hide();
        tableWrap.show();
        badge.text(selectedNames.length).show();

        // Only rebuild rows for names not already in the table
        var existingNames = [];
        tbody.find('tr').each(function () {
            existingNames.push($(this).data('est-name'));
        });

        // Remove rows for de-selected names
        tbody.find('tr').each(function () {
            var n = $(this).data('est-name');
            if (selectedNames.indexOf(n) === -1) $(this).remove();
        });

        // Add rows for newly selected names
        selectedNames.forEach(function (name) {
            if (existingNames.indexOf(name) !== -1) return; // already there
            var row = $(
                '<tr data-est-name="' + escAttr(name) + '">' +
                '<td class="pl-3"><span style="font-size:.875rem;">' + escHtml(name) + '</span></td>' +
                '<td>' +
                    '<div class="input-group input-group-sm">' +
                        '<div class="input-group-prepend"><span class="input-group-text" style="font-size:.78rem;padding:4px 8px;">₱</span></div>' +
                        '<input type="number" step="0.01" min="0" class="form-control est-price-input est-srp-input" placeholder="0.00">' +
                    '</div>' +
                '</td>' +
                '<td>' +
                    '<div class="input-group input-group-sm">' +
                        '<div class="input-group-prepend"><span class="input-group-text" style="font-size:.78rem;padding:4px 8px;">₱</span></div>' +
                        '<input type="number" step="0.01" min="0" class="form-control est-price-input est-prev-input" placeholder="0.00">' +
                    '</div>' +
                '</td>' +
                '</tr>'
            );
            tbody.append(row);
        });

        badge.text(tbody.find('tr').length);
    });

    // ── "Back" button on Add Tab 2 ────────────────────────────────────────────
    $('#btnBackToInfo').on('click', function () {
        $('#add-tab-info-link').tab('show');
    });

    // ── Delete ────────────────────────────────────────────────────────────────
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete Commodity?',
            text: 'This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (!result.isConfirmed) return;
            fetch('../../api/routes.php/commodity?id=' + encodeURIComponent(id), { method: 'DELETE' })
                .then(r => r.json())
                .then(res => {
                    if (res.status === 'success') {
                        Swal.fire('Deleted!', res.message || 'Commodity deleted successfully.', 'success');
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire('Error', res.message || 'Unable to delete commodity.', 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Network error.', 'error'));
        });
    });

    // ── Save Commodity (Add) ──────────────────────────────────────────────────
    $('#btnSaveCommodity').on('click', function () {
        const productName   = $('#product_name').val().trim();
        const categoryId    = $('#category_id').val() || '';
        const brandName     = $('#brand_name').val().trim();
        const unitOfMeasure = $('#unit_of_measure').val().trim();

        if (!productName) { Swal.fire('Required Field', 'Please enter the Commodity Name.', 'warning'); return; }
        if (!categoryId)  { Swal.fire('Required Field', 'Please select a Category.', 'warning'); return; }
        if (!unitOfMeasure) { Swal.fire('Required Field', 'Please enter the Unit of Measure.', 'warning'); return; }

        // Collect establishment prices from Tab 2 table (pipe-encoded format)
        var estRows = [];
        $('#addEstPriceTableBody tr').each(function () {
            var $r   = $(this);
            var name = $r.data('est-name');
            var srp  = $r.find('.est-srp-input').val().trim();
            var prev = $r.find('.est-prev-input').val().trim();
            if (name) estRows.push({ name: name, srp: srp, prevailing_price: prev });
        });

        // Build Establishments field
        var establishments = '';
        if (estRows.length > 0) {
            establishments = estRows.map(function (e) {
                return [e.name, e.srp || '', e.prevailing_price || ''].join('|');
            }).join(',');
        } else {
            // No Tab 2 visited — just store selected names
            var sel = $('#establishments').val() || [];
            establishments = sel.join(',');
        }

        // Use the first row's SRP/prevailing as the commodity-level defaults
        var commoditySrp      = estRows.length > 0 && estRows[0].srp      ? estRows[0].srp      : '';
        var commodityPrevailing = estRows.length > 0 && estRows[0].prevailing_price ? estRows[0].prevailing_price : '';

        const commodityData = {
            product_name:     productName,
            category_id:      categoryId,
            brand_name:       brandName,
            unit_of_measure:  unitOfMeasure,
            srp:              commoditySrp,
            prevailing_price: commodityPrevailing,
            Establishments:   establishments
        };

        $.ajax({
            url: '../../api/routes.php/commodity',
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(commodityData),
            success: function (response) {
                if (response.status !== 'success') {
                    Swal.fire('Error', response.message || 'Unable to save commodity.', 'error');
                    return;
                }
                Swal.fire('Success', response.message || 'Commodity added successfully.', 'success');
                $('#addCommodityModal').modal('hide');
                table.ajax.reload(null, false);
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                let message = 'Unable to save commodity.';
                try { const e = JSON.parse(xhr.responseText); if (e.message) message = e.message; } catch (_) {}
                Swal.fire('Error', message, 'error');
            }
        });
    });

    // ── Reset Add modal on close ──────────────────────────────────────────────
    $('#addCommodityModal').on('hidden.bs.modal', function () {
        $('#product_name').val('');
        $('#category_id').val('');
        $('#brand_name').val('');
        $('#unit_of_measure').val('');
        $('#establishments').val(null).trigger('change');
        $('#addEstPriceTableBody').empty();
        $('#addEstPriceEmpty').show();
        $('#addEstPriceTableWrap').hide();
        $('#addEstBadge').hide();
        $('#add-tab-info-link').tab('show');
    });

    // ── Load categories ───────────────────────────────────────────────────────
    function loadCategories() {
        $.ajax({
            url: '../../api/routes.php/price-monitoring?action=commodity_categories&agency_id=3',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.status !== 'success') {
                    Swal.fire('Error', response.message || 'Unable to load DOE categories.', 'error');
                    return;
                }
                let html = '<option value="">-- Select Category --</option>';
                $.each(response.data || [], function (i, item) {
                    html += `<option value="${item.category_id}">${item.category_name}</option>`;
                });
                $('#category_id').html(html);
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire('Error', 'Unable to load DOE categories.', 'error');
            }
        });
    }

    // ── Load establishments ───────────────────────────────────────────────────
    function loadEstablishmentsFromMSME() {
        $.ajax({
            url: '../../api/routes.php/business',
            type: 'GET',
            data: { length: -1 },
            dataType: 'json',
            success: function (response) {
                if (response.status !== 'success') {
                    Swal.fire('Error', response.message || 'Unable to load establishments.', 'error');
                    return;
                }
                let html = '';
                $.each(response.data || [], function (i, item) {
                    const name = item.juridical && item.juridical.name ? item.juridical.name : '';
                    if (name) html += `<option value="${name}">${name}</option>`;
                });
                $('#establishments').html(html).trigger('change');
                $('#updateCommodityEstablishments').html(html);
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                Swal.fire('Error', 'Unable to load establishments.', 'error');
            }
        });
    }

    // ── Tiny escape helpers (used in row building above) ─────────────────────
    function escHtml(str) {
        return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function escAttr(str) { return String(str || '').replace(/"/g,'&quot;'); }

});
