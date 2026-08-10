function exportExcelReport() {
    var table = $('#tblPriceMonitoring').DataTable();

    if (!table) {
        Swal.fire('Error', 'Table is not initialized yet.', 'error');
        return;
    }

    var data = table.rows({ search: 'applied' }).data().toArray();

    if (!data.length) {
        Swal.fire('No data', 'There is no data to export.', 'info');
        return;
    }

    var headers = [
        'Product Name',
        'Category',
        'Agency',
        'Brand / Unit',
        'SRP (₱)',
        'Prevailing Price (₱)',
        'Status'
    ];

    var rows = [headers.join(',')];

    data.forEach(function (row) {
        var brandUnit = row.brand_name || '';

        if (row.unit_of_measure) {
            brandUnit = brandUnit
                ? brandUnit + ' / ' + row.unit_of_measure
                : row.unit_of_measure;
        }

        rows.push([
            row.product_name || '',
            row.category_name || '',
            row.agency_code || '',
            brandUnit,
            row.srp || '',
            row.prevailing_price || '',
            row.status || ''
        ].map(function (value) {
            return '"' + String(value).replace(/"/g, '""') + '"';
        }).join(','));
    });

    var blob = new Blob(
        [rows.join('\n')],
        { type: 'text/csv;charset=utf-8;' }
    );

    var url = URL.createObjectURL(blob);
    var link = document.createElement('a');

    link.href = url;
    link.download = 'dti-price-report.csv';

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    URL.revokeObjectURL(url);
}

function logout() {
    window.location.href = '../../session.php?logout=1';
}