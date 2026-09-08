$(function () {

    // Reset fields when Add modal opens
    $('#btn_add_establishment').on('click', function () {
        $('#establishment_name').val('');
        $('#establishment_branch').val('');
        $('#establishment_address').val('');
    });

    $('#btnSaveEstablishment').on('click', function () {
        const name = String($('#establishment_name').val() || '').trim();
        const branch = String($('#establishment_branch').val() || '').trim();
        const address = String($('#establishment_address').val() || '').trim();

        if (!name) {
            Swal.fire('Required Field', 'Please enter the Establishment Name.', 'warning');
            return;
        }

        const data = {
            name: name,
            branch: branch,
            address: address
        };

        $.ajax({
            url: '../../api/routes.php/establishment',
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(data),
            success: function (response) {
                if (response.status !== 'success') {
                    Swal.fire('Error', response.message || 'Unable to save establishment.', 'error');
                    return;
                }

                Swal.fire('Success', response.message || 'Establishment added successfully.', 'success');

                $('#addEstablishmentModal').modal('hide');

                $('#establishment_name').val('');
                $('#establishment_branch').val('');
                $('#establishment_address').val('');

                if (typeof refreshEstablishmentTable === 'function') {
                    refreshEstablishmentTable();
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);

                let message = 'Unable to save establishment.';
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.message) message = errorResponse.message;
                } catch (e) {
                    console.error('Could not parse server response.');
                }

                Swal.fire('Error', message, 'error');
            }
        });
    });

});
