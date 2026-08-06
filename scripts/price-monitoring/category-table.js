$(function () {

    $('#tblCategories').DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        processing: true,

        ajax: {
            url: "../../api/routes.php/price-monitoring?action=commodity_categories",
            type: "GET",
            dataSrc: function (response) {
                if (response.status === "success") {
                    return response.data;
                }
                return [];
            }
        },

        columns: [
            { data: "category_id" },
            { data: "category_name" },
            { data: "agency_name" },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                        <button
                            class="btn btn-warning btn-sm btn-edit"
                            data-id="${row.category_id}"
                            title="Edit Category">
                            <i class="fas fa-edit"></i>
                        </button>

                        <button
                            class="btn btn-danger btn-sm btn-delete"
                            data-id="${row.category_id}"
                            title="Delete Category">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                }
            }
        ]
    });

});