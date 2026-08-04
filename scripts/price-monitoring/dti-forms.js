var priceMonitoringReferences = {
    agencies: [],
    commodities: []
};

function getPriceApiUrl(query) {
    var pathBase = window.location.pathname.split('/pages/')[0];
    if (!pathBase) {
        pathBase = window.location.pathname.split('/').slice(0, 2).join('/');
    }
    return window.location.origin + pathBase + '/api/routes.php/price' + (query || '');
}

function renderAgencyOptions(agencies) {
    var html = '<option value="">-- Select Agency --</option>';
    agencies.forEach(function (agency) {
        var label = agency.code || agency.name || 'Agency #' + agency.id;
        html += '<option value="' + agency.id + '">' + label + '</option>';
    });
    $('#add_monitored_by_agency_id, #edit_monitored_by_agency_id').html(html);
}

function renderCommodityOptions(commodities) {
    var html = '<option value="">-- Select Commodity --</option>';
    commodities.forEach(function (commodity) {
        var label = commodity.product_name || 'Commodity #' + commodity.id;
        if (commodity.category_name) {
            label += ' (' + commodity.category_name + ')';
        }
        html += '<option value="' + commodity.id + '">' + label + '</option>';
    });
    $('#add_commodity_id, #edit_commodity_id').html(html);
}

function loadReferenceData() {
    Promise.all([
        fetch(getPriceApiUrl('?action=agencies')).then(function (response) { return response.json(); }),
        fetch(getPriceApiUrl('?action=commodities')).then(function (response) { return response.json(); })
    ])
    .then(function (responses) {
        var agencies = responses[0].data || [];
        var commodities = responses[1].data || [];

        priceMonitoringReferences.agencies = agencies;
        priceMonitoringReferences.commodities = commodities;

        renderAgencyOptions(agencies);
        renderCommodityOptions(commodities);

        $('#add_monitored_by_agency_id, #add_commodity_id, #edit_monitored_by_agency_id, #edit_commodity_id').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    })
    .catch(function (error) {
        console.error('Unable to load reference data:', error);
    });
}

function resetAddForm() {
    $('#formAddPrice')[0].reset();
    $('#add_monitored_by_agency_id, #add_commodity_id').val('').trigger('change');
}

function resetEditForm() {
    $('#formEditPrice')[0].reset();
    $('#edit_monitored_by_agency_id, #edit_commodity_id').val('').trigger('change');
}

function openEditModal(id) {
    fetch(getPriceApiUrl('/' + id))
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Server returned status ' + response.status);
            }
            return response.json();
        })
        .then(function (result) {
            if (result.status !== 'success') {
                return Swal.fire('Error', result.message || 'Unable to load entry.', 'error');
            }

            var row = result.data;
            $('#edit_entry_id').val(row.id);
            $('#edit_monitored_by_agency_id').val(row.monitored_by_agency_id).trigger('change');
            $('#edit_commodity_id').val(row.commodity_id).trigger('change');
            $('#edit_prevailing_price').val(row.prevailing_price);
            $('#edit_status').val(row.status);

            $('#modalEditPrice').modal('show');
        })
        .catch(function (error) {
            console.error('Error loading price entry:', error);
            Swal.fire('Error', 'Unable to fetch entry details.', 'error');
        });
}

function deletePrice(id) {
    Swal.fire({
        title: 'Delete entry?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then(function (result) {
        if (!result.isConfirmed) {
            return;
        }

        fetch(getPriceApiUrl('/' + id), {
            method: 'DELETE'
        })
        .then(function (response) { return response.json(); })
        .then(function (result) {
            if (result.status === 'success') {
                Swal.fire('Deleted', result.message, 'success');
                if (typeof loadPriceData === 'function') {
                    loadPriceData();
                }
            } else {
                Swal.fire('Error', result.message || 'Unable to delete entry.', 'error');
            }
        })
        .catch(function (error) {
            console.error('Delete failed:', error);
            Swal.fire('Error', 'Network error during delete.', 'error');
        });
    });
}

function submitAddPrice(event) {
    event.preventDefault();

    var payload = {
        commodity_id: $('#add_commodity_id').val(),
        monitored_by_agency_id: $('#add_monitored_by_agency_id').val(),
        prevailing_price: $('#formAddPrice').find('[name=prevailing_price]').val(),
        status: $('#formAddPrice').find('[name=status]').val()
    };

    fetch(getPriceApiUrl(), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(function (response) { return response.json(); })
    .then(function (result) {
        if (result.status === 'success') {
            Swal.fire('Saved', result.message, 'success');
            $('#modalAddPrice').modal('hide');
            resetAddForm();
            if (typeof loadPriceData === 'function') {
                loadPriceData();
            }
        } else {
            Swal.fire('Error', result.message || 'Unable to save entry.', 'error');
        }
    })
    .catch(function (error) {
        console.error('Add request failed:', error);
        Swal.fire('Error', 'Network error while saving entry.', 'error');
    });
}

function submitEditPrice(event) {
    event.preventDefault();

    var payload = {
        id: $('#edit_entry_id').val(),
        commodity_id: $('#edit_commodity_id').val(),
        monitored_by_agency_id: $('#edit_monitored_by_agency_id').val(),
        prevailing_price: $('#edit_prevailing_price').val(),
        status: $('#edit_status').val()
    };

    fetch(getPriceApiUrl(), {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(function (response) { return response.json(); })
    .then(function (result) {
        if (result.status === 'success') {
            Swal.fire('Updated', result.message, 'success');
            $('#modalEditPrice').modal('hide');
            resetEditForm();
            if (typeof loadPriceData === 'function') {
                loadPriceData();
            }
        } else {
            Swal.fire('Error', result.message || 'Unable to update entry.', 'error');
        }
    })
    .catch(function (error) {
        console.error('Update request failed:', error);
        Swal.fire('Error', 'Network error while updating entry.', 'error');
    });
}

$(function () {
    loadReferenceData();
    $('#formAddPrice').on('submit', submitAddPrice);
    $('#formEditPrice').on('submit', submitEditPrice);
});
