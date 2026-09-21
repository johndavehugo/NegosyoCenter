var select2AddSelectors = ['#addIncidentCalamity', '#addIncidentJuridical'];

var businessCache = {};
var businessDetails = {};

function getTotalDamage() {
    var total = 0;
    Object.keys(businessDetails).forEach(function (id) {
        var val = parseFloat(businessDetails[id].cost);
        if (!isNaN(val)) {
            total += val;
        }
    });
    return total;
}

function updateTotalDamage() {
    $('#affectedBusinessesTotal').text(Number(getTotalDamage()).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
}

function destroySelect2Add() {
    select2AddSelectors.forEach(function (sel) {
        if ($(sel).hasClass('select2-hidden-accessible')) {
            $(sel).select2('destroy');
        }
    });
}

// Re-runs the open dropdown query (keeping the typed term) so a freshly
// picked business immediately vanishes from the search list — and an
// unpicked one reappears. No-op when the dropdown is closed.
function refreshOpenJuridicalResults($sel) {
    var s2 = $sel.data('select2');
    if (!s2 || typeof s2.isOpen !== 'function' || !s2.isOpen()) {
        return;
    }
    var $search = (s2.dropdown && s2.dropdown.$search) ? s2.dropdown.$search : $();
    if (!$search.length) {
        return;
    }
    $search.trigger('input');
    $search.focus();
}

function initSelect2Add() {
    $('#addIncidentCalamity').select2({
        dropdownParent: $('#addIncidentModal'),
        placeholder: 'Search calamity...',
        allowClear: true,
        width: '100%',
        theme: 'bootstrap4'
    });
    $('#addIncidentJuridical').select2({
        dropdownParent: $('#addIncidentModal'),
        placeholder: 'Search business name or entity no...',
        allowClear: true,
        multiple: true,
        minimumInputLength: 0,
        closeOnSelect: false,
        width: '100%',
        theme: 'bootstrap4',
        templateResult: function (data) {
            if (!data.id) {
                return data.text;
            }
            var selected = $('#addIncidentJuridical').val() || [];
            if (selected.indexOf(data.id) !== -1) {
                return null;
            }
            // Rich row: business name + entity no • owner
            var info = businessCache[data.id] || {};
            var name = info.name || data.text;
            var subParts = [];
            if (info.entity_no) subParts.push(info.entity_no);
            if (info.owner_full_name) subParts.push(info.owner_full_name);
            var $el = $('<span><span class="biz-opt-name"></span></span>');
            $el.find('.biz-opt-name').text(name);
            if (subParts.length) {
                $el.append($('<span class="biz-opt-sub"></span>').text(subParts.join(' • ')));
            }
            return $el;
        },
        templateSelection: function (data) {
            if (!data.id) {
                return data.text;
            }
            var info = businessCache[data.id] || {};
            var label = info.name || data.text;
            if (info.entity_no) {
                label += ' — ' + info.entity_no;
            }
            return label;
        },
        ajax: {
            url: '../../api/routes.php/calamity?action=juridical_search',
            dataType: 'json',
            delay: 300,
            data: function (params) {
                return { q: params.term || '' };
            },
            processResults: function (res) {
                if (res.status !== 'success') {
                    return { results: [] };
                }
                var selected = $('#addIncidentJuridical').val() || [];
                var results = [];
                res.data.forEach(function (b) {
                    var id = String(b.id);
                    if (selected.indexOf(id) !== -1) {
                        return;
                    }
                    businessCache[id] = {
                        name: b.name,
                        entity_no: b.entity_no,
                        owner_full_name: b.owner_full_name
                    };
                    results.push({ id: id, text: b.name + ' — ' + b.entity_no });
                });
                return { results: results };
            }
        }
    }).off('change.bizAff').on('change.bizAff', function () {
        renderAffectedBusinesses();
        refreshOpenJuridicalResults($(this));
    }).off('select2:select.bizAff').on('select2:select.bizAff', function (e) {
        var data = (e.params && e.params.data) || {};
        var id = String(data.id || '');
        // Guarantee the picked business is known (normally already cached
        // by processResults) so the table row renders immediately.
        if (id && !businessCache[id] && data.text !== undefined) {
            var parts = String(data.text).split(' — ');
            businessCache[id] = {
                name: parts[0] || data.text,
                entity_no: parts[1] || '',
                owner_full_name: ''
            };
        }
        // 1) Show it in Selected Affected Businesses right away.
        renderAffectedBusinesses();
        // 2) Instantly drop the picked row from the open search list
        // (the background refresh below then reloads a clean list).
        var s2 = $(this).data('select2');
        if (id && s2 && typeof s2.isOpen === 'function' && s2.isOpen() &&
            s2.dropdown && s2.dropdown.$dropdown) {
            var label = (businessCache[id] && businessCache[id].name) || '';
            s2.dropdown.$dropdown
                .find('li.select2-results__option--highlighted')
                .filter(function () {
                    return label && $(this).text().indexOf(label) !== -1;
                })
                .remove();
        }
        refreshOpenJuridicalResults($(this));
        // keep typing focus inside the open dropdown
        var $search = (s2 && s2.dropdown && s2.dropdown.$search) ? s2.dropdown.$search : $();
        if ($search.length) {
            $search.focus();
        }
    }).off('select2:unselect.bizAff').on('select2:unselect.bizAff', function () {
        renderAffectedBusinesses();
        refreshOpenJuridicalResults($(this));
    });
}

$(document).on('click', '#btn_add_incident', function () {
    destroySelect2Add();

    // Use .done() not .then() so $.when() can track them correctly
    var loadCalamities = $.getJSON('../../api/routes.php/calamity?action=calamities')
        .done(function (res) {
            var opts = '<option value=""></option>';
            if (res.status === 'success') {
                res.data.forEach(function (c) {
                    opts += '<option value="' + c.id + '">' + c.name + ' (' + c.calamity_type + ')</option>';
                });
            }
            $('#addIncidentCalamity').html(opts);
        });

    $.when(loadCalamities).done(function () {
        initSelect2Add();
    });
});

$('#addIncidentModal').on('hidden.bs.modal', function () {
    destroySelect2Add();
    document.getElementById('addIncidentForm').reset();
    $('#affectedBusinessesTable tbody').empty();
    $('#affectedBusinessesEmpty').show();
    businessDetails = {};
    updateTotalDamage();
});

function getBusinessDetail(id, field) {
    return businessDetails[id] ? (businessDetails[id][field] || '') : '';
}

function renderAffectedBusinesses() {
    var selected = $('#addIncidentJuridical').select2('data');
    var tbody = $('#affectedBusinessesTable tbody');
    tbody.empty();

    selected.forEach(function (item, index) {
        var info = businessCache[item.id] || {};
        var displayName = info.name || (item.text ? String(item.text).split(' — ')[0] : '');
        var $tr = $('<tr>');
        $tr.append('<td>' + (index + 1) + '</td>');
        $tr.append('<td>' + displayName + '</td>');
        $tr.append('<td>' + (info.entity_no || '') + '</td>');
        $tr.append('<td>' + (info.owner_full_name || '') + '</td>');
        $tr.append(
            '<td>' +
                '<input type="date" class="form-control form-control-sm date-input" data-id="' + item.id + '" value="' + getBusinessDetail(item.id, 'date_occurred') + '">' +
            '</td>'
        );
        $tr.append(
            '<td>' +
                '<select class="form-control form-control-sm nature-select" data-id="' + item.id + '">' +
                    '<option value=""></option>' +
                    '<option value="PARTIAL">Partial</option>' +
                    '<option value="TOTAL">Total</option>' +
                '</select>' +
            '</td>'
        );
        $tr.append(
            '<td>' +
                '<select class="form-control form-control-sm status-select" data-id="' + item.id + '">' +
                    '<option value="PENDING_VERIFICATION">Pending Verification</option>' +
                    '<option value="VERIFIED">Verified</option>' +
                    '<option value="AID_RELEASED">Aid Released</option>' +
                '</select>' +
            '</td>'
        );
        $tr.append(
            '<td>' +
                '<input type="number" step="0.01" min="0" class="form-control form-control-sm damage-input" ' +
                    'data-id="' + item.id + '" value="' + getBusinessDetail(item.id, 'cost') + '" placeholder="0.00">' +
            '</td>'
        );
        $tr.append(
            '<td>' +
                '<input type="text" class="form-control form-control-sm remarks-input" data-id="' + item.id + '" value="' + getBusinessDetail(item.id, 'remarks') + '" placeholder="Remarks">' +
            '</td>'
        );
        $tr.append(
            '<td style="text-align:center;">' +
                '<button type="button" class="btn-remove-business" title="Remove" data-id="' + item.id + '">' +
                    '<i class="fas fa-trash-alt"></i>' +
                '</button>' +
            '</td>'
        );
        tbody.append($tr);

        $tr.find('.nature-select').val(getBusinessDetail(item.id, 'nature_of_damage') || '');
        $tr.find('.status-select').val(getBusinessDetail(item.id, 'status') || 'VERIFIED');
    });

    $('#affectedBusinessesEmpty').toggle(selected.length === 0);
    updateTotalDamage();
}

function setBusinessField(id, field, value) {
    if (!businessDetails[id]) {
        businessDetails[id] = { cost: '', date_occurred: '', nature_of_damage: '', status: 'VERIFIED', remarks: '' };
    }
    businessDetails[id][field] = value;
}

$(document).on('input', '#affectedBusinessesTable .damage-input', function () {
    setBusinessField($(this).data('id'), 'cost', $(this).val());
    updateTotalDamage();
});

$(document).on('input', '#affectedBusinessesTable .date-input', function () {
    setBusinessField($(this).data('id'), 'date_occurred', $(this).val());
});

$(document).on('change', '#affectedBusinessesTable .nature-select', function () {
    setBusinessField($(this).data('id'), 'nature_of_damage', $(this).val());
});

$(document).on('change', '#affectedBusinessesTable .status-select', function () {
    setBusinessField($(this).data('id'), 'status', $(this).val());
});

$(document).on('input', '#affectedBusinessesTable .remarks-input', function () {
    setBusinessField($(this).data('id'), 'remarks', $(this).val());
});

$(document).on('click', '#affectedBusinessesTable .btn-remove-business', function () {
    var id = String($(this).data('id'));
    var select = $('#addIncidentJuridical');
    var current = select.val() || [];
    select.val(current.filter(function (v) { return String(v) !== id; })).trigger('change');
    delete businessDetails[id];
    updateTotalDamage();
});

function addIncident() {
    var calamityId   = $('#addIncidentCalamity').val();
    var juridicalIds = $('#addIncidentJuridical').val() || [];

    if (!calamityId || juridicalIds.length === 0) {
        Swal.fire('Warning', 'Please select a calamity and at least one affected business.', 'warning');
        return;
    }

    var dates = [];
    var natures = [];
    var statuses = [];
    var costs = [];
    var remarks = [];

    for (var i = 0; i < juridicalIds.length; i++) {
        var id = juridicalIds[i];
        var d = businessDetails[id] || {};

        if (!d.date_occurred) {
            Swal.fire('Warning', 'Please enter the date of occurrence for every affected business.', 'warning');
            return;
        }
        if (!d.nature_of_damage) {
            Swal.fire('Warning', 'Please select the nature of damage for every affected business.', 'warning');
            return;
        }
        if (d.cost === undefined || d.cost === '' || isNaN(parseFloat(d.cost)) || parseFloat(d.cost) < 0) {
            Swal.fire('Warning', 'Please enter the estimated damage for every affected business.', 'warning');
            return;
        }

        dates.push(d.date_occurred);
        natures.push(d.nature_of_damage);
        statuses.push(d.status || 'VERIFIED');
        costs.push(parseFloat(d.cost));
        remarks.push(d.remarks || '');
    }

    const data = {
        calamity_id:               calamityId,
        juridical_ids:             juridicalIds,
        date_occurred:             dates,
        nature_of_damage:          natures,
        status:                    statuses,
        estimated_cost_of_damages: costs,
        remarks:                   remarks,
    };

    fetch('../../api/routes.php/calamity', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire('Success!', res.message, 'success');
            $('#addIncidentModal').modal('hide');
            reloadCalamityTable();
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Network error. Please try again.', 'error');
    });
}
