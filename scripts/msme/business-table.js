$(function () {
            $('#tblBusiness').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "processing": true,
                "serverSide": true,
                "paging": true,
                "lengthChange": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "ajax": {
                    "url": "../../api/routes.php/business",
                    "type": "GET",
                },
                "columns": [
                    { "data": "juridical.entity_no"},
                    { "data": "juridical.name" },
                    { "data": "juridical.msme_category" },
                    { "data": "employer.full_name" },
                    { 
                        "data": null,
                        "render": function(data, type, row) {
                            return '<div style="display: flex; justify-content: center" class="dropdown"><button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</button>' +
                                '<div class="dropdown-menu">' +
                                '<a class="dropdown-item" href="#" onclick="viewBusiness(\'' + row.juridical.entity_no + '\')">View</a>' +
                                '<a class="dropdown-item" href="#" onclick="fillUpdateModal(\'' + row.juridical.entity_no + '\')">Update</a>' +
                                '<a class="dropdown-item" href="#" onclick="fillRenewModal(\'' + row.juridical.entity_no + '\')">Renew</a>' +
                                '</div></div>';
                        }
                    }
                ]
            })
        });