function exportExcelReport() {
    if (
        !$.fn.DataTable.isDataTable(
            '#tblPriceMonitoring'
        )
    ) {
        Swal.fire(
            'Error',
            'Table is not initialized yet.',
            'error'
        );

        return;
    }

    var table =
        $('#tblPriceMonitoring').DataTable();

    var data =
        table
            .rows({
                search: 'applied'
            })
            .data()
            .toArray();

    if (!data.length) {
        Swal.fire(
            'No Data',
            'There is no data to export.',
            'info'
        );

        return;
    }

    var csvRows = [];

    var headers = [
        'Product Name',
        'Category',
        'Agency',
        'Brand / Unit',
        'SRP (₱)',
        'Prevailing Price (₱)',
        'Status'
    ];

    csvRows.push(
        headers.map(function(value) {
            return '"' +
                String(value)
                    .replace(/"/g, '""') +
                '"';
        }).join(',')
    );

    data.forEach(function(row) {
        var brandUnit =
            row.brand_name || '';

        if (row.unit_of_measure) {
            brandUnit =
                brandUnit
                    ? brandUnit +
                      ' / ' +
                      row.unit_of_measure
                    : row.unit_of_measure;
        }

        var values = [
            row.product_name || '',
            row.category_name || '',
            row.agency_code ||
                row.agency_name ||
                'DOE',
            brandUnit,
            row.srp !== null &&
            row.srp !== undefined
                ? row.srp
                : '',
            row.prevailing_price !== null &&
            row.prevailing_price !== undefined
                ? row.prevailing_price
                : '',
            row.status || 'NO PRICE YET'
        ];

        csvRows.push(
            values.map(function(value) {
                return '"' +
                    String(value)
                        .replace(/"/g, '""') +
                    '"';
            }).join(',')
        );
    });

    var csvData =
        '\uFEFF' +
        csvRows.join('\r\n');

    var blob =
        new Blob(
            [csvData],
            {
                type:
                    'text/csv;charset=utf-8;'
            }
        );

    var url =
        URL.createObjectURL(blob);

    var link =
        document.createElement('a');

    link.href = url;

    link.download =
        'doe-price-report.csv';

    document.body.appendChild(link);

    link.click();

    document.body.removeChild(link);

    URL.revokeObjectURL(url);
}

function logout() {
    window.location.href =
        '../../session.php?logout=1';
}