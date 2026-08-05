function openOwnerModal() {
    $('#ownerAddModal').modal('show');
}

$('#ownerAddModal').on('shown.bs.modal', function () {
    if (!$('#scimsOwnerSearch').data('select2')) {
        $('#scimsOwnerSearch').select2({
            placeholder: 'Search SCIMS owner by name or entity no',
            dropdownParent: $('#ownerAddModal'),
            minimumInputLength: 1,
            ajax: {
                url: '../../api/routes.php/scims/employers',
                dataType: 'json',
                data: function (p) { return { q: p.term }; },
                processResults: function (data) {
                    return { results: (data.data || []).map(function (o) {
                        var name = [o.first_name, o.middle_name, o.last_name].filter(Boolean).join(' ');
                        return { id: o.entity_no, text: name + ' (' + o.entity_no + ')', owner: o };
                    }) };
                }
            }
        });
    }
});

$('#scimsOwnerSearch').on('select2:select', function (e) {
    var o = e.params.data.owner;
    $('input[name=addFirstName]').val(o.first_name);
    $('input[name=addMiddleName]').val(o.middle_name);
    $('input[name=addLastName]').val(o.last_name);
    $('input[name=addEmpEntityNo]').val(o.entity_no);
    $('input[name=addEmpStreet]').val(o.street);
    $('input[name=addEmpSubdivision]').val(o.subdivision);
    prefillAddress('addEmp', { region: o.region, province: o.province, city: o.city, barangay: o.barangay });
});

function addEmployer() {
            const employer = {
                entity_no :             $('input[name=addEmpEntityNo]').val(),
                first_name:             $('input[name=addFirstName]').val(),
                middle_name:            $('input[name=addMiddleName]').val(),
                last_name:              $('input[name=addLastName]').val(),
                special_category:       $('select[name=addSpecialCategory]').val(),

                //Owner address
                region:                   $('select[name=addEmpRegion]').val(),
                province:                 $('select[name=addEmpProvince]').val(),
                city:                     $('select[name=addEmpCity]').val(),
                barangay:                 $('select[name=addEmpBarangay]').val(),
                street:                   $('input[name=addEmpStreet]').val(),
                subdivision:              $('input[name=addEmpSubdivision]').val(),
                upblb_num:                $('input[name=addEmpUpblb]').val(),
            };

            fetch('../../api/routes.php/employer', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(employer)
            })
            .then(r => r.json())
            .then(res => {
                if (res.status === 'success') {
                    alert(res.message);
                    $('#ownerAddModal').modal('hide');
                    $('#scimsOwnerSearch').val(null).trigger('change');
                    document.getElementById('addOwnerForm').reset();
                } else {
                    alert('Error: ' + res.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Network error');
            });
        };