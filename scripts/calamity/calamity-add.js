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
            return data.text;
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
    }).on('change', function () {
        renderAffectedBusinesses();
    }).on('select2:select', function () {
        var s2 = $(this).data('select2');
        if (s2 && s2.$search.length) {
            s2.$search.trigger('input').trigger('focus');
        }
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
        var $tr = $('<tr>');
        $tr.append('<td>' + (index + 1) + '</td>');
        $tr.append('<td>' + (item.text ? item.text.split(' — ')[0] : '') + '</td>');
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
    var id = $(this).data('id');
    var select = $('#addIncidentJuridical');
    var current = select.val() || [];
    select.val(current.filter(function (v) { return v !== id; })).trigger('change');
    delete businessDetails[id];
    updateTotalDamage();
});

function addIncident() {
    var calamityId   = $('#addIncidentCalamity').val();
    var juridicalIds = $('#addIncidentJuridical').val() || [];

    if (!calamityId || juridicalIds.length === 0) {
        App.alert({
            icon: 'warning',
            title: 'Incomplete Selection',
            text: 'Please select a calamity and at least one affected business.'
        });
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
            App.alert({
                icon: 'warning',
                title: 'Missing Date',
                text: 'Please enter the date of occurrence for every affected business.'
            });
            return;
        }
        if (!d.nature_of_damage) {
            App.alert({
                icon: 'warning',
                title: 'Missing Nature of Damage',
                text: 'Please select the nature of damage for every affected business.'
            });
            return;
        }
        if (d.cost === undefined || d.cost === '' || isNaN(parseFloat(d.cost)) || parseFloat(d.cost) < 0) {
            App.alert({
                icon: 'warning',
                title: 'Missing Damage Cost',
                text: 'Please enter the estimated damage cost for every affected business.'
            });
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
            $('#addIncidentModal').modal('hide');
            reloadCalamityTable();
            App.toast({
                icon: 'success',
                title: 'Incident Added',
                text: res.message || 'The incident has been recorded successfully.'
            });
        } else {
            App.alert({
                icon: 'error',
                title: 'Could Not Save',
                text: res.message || 'An error occurred while saving the incident.'
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
}
