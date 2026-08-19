// Loads category options into the target dropdown
function loadCategoryOptions(selectId) {
    return new Promise(function (resolve, reject) {
        $.getJSON('../../api/routes.php/price-monitoring?action=commodity_categories&agency_id=3')
            .done(function (res) {
                var opts = '<option value="" hidden>Select Category</option>';
                if (res.status === 'success' && Array.isArray(res.data)) {
                    res.data.forEach(function (c) {
                        opts += `<option value="${c.category_id}">${c.category_name}</option>`;
                    });
                }
                $('#' + selectId).html(opts);
                resolve();
            })
            .fail(reject);
    });
}

$(document).on('click', '.btn-edit', function () {
    var row = $('#tblCommodity').DataTable().row($(this).closest('tr')).data();
    if (!row || !row.id) {
        Swal.fire('Error', 'Unable to retrieve Commodity ID.', 'error');
        return;
    }

    loadCategoryOptions('updateCommodityCategory')
        .then(function () {
            $('#updateCommodityId').val(row.id);
            $('#updateCommodityProductName').val(row.product_name);
            $('#updateCommodityCategory').val(row.category_id);
            $('#updateCommodityBrand').val(row.brand_name);
            $('#updateCommodityUnit').val(row.unit_of_measure);
            $('#updateCommodityModal').appendTo('body').modal('show');
        })
        .catch(function (err) {
            console.error(err);
            Swal.fire('Error', 'Failed to load categories.', 'error');
        });
});

// Reset form when modal is closed
$('#updateCommodityModal').on('hidden.bs.modal', function () {
    document.getElementById('updateCommodityForm').reset();
});

// Sends the PUT request to save updates
function updateCommodity() {
    const data = {
        id: $('#updateCommodityId').val(),
        product_name: $('#updateCommodityProductName').val().trim(),
        category_id: $('#updateCommodityCategory').val(),
        brand_name: $('#updateCommodityBrand').val().trim(),
        unit_of_measure: $('#updateCommodityUnit').val().trim()
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
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire('Success!', res.message || 'Commodity updated successfully.', 'success');
            $('#updateCommodityModal').modal('hide');
            $('#tblCommodity').DataTable().ajax.reload(null, false);
        } else {
            Swal.fire('Error', res.message || 'Unable to update commodity.', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Network error', 'error');
    });
}
