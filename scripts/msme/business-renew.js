function fillRenewModal(id) {
           fetch('../../api/routes.php/business/' + id)
               .then(response => response.json())
               .then(data => {
                   if (data.status === 'success') {
                       const business = data.data;
                       $('#renewBusName').val(business.juridical.name);
                       $('#renewBusEntityNo').val(business.juridical.entity_no);
                       $('#renewRegType').val(business.juridical.registration_type);                       
                       $('#renewBusinessModal').modal('show');
                   } else {
                       alert(data.message);
                   }
               })
               .catch(error => {
                   alert('Error: ' + error);
               });
       }


       function renewBusiness() {
            const data = {
                juri_entity_no: $('#renewBusEntityNo').val(),
            };

            fetch('../../api/routes.php/business/renew', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    $('#renewBusinessModal').modal('hide');
                    $('#tblBusiness').DataTable().ajax.reload();
                }
            })
            .catch(error => {
                alert('Error: ' + error);
            });
        }