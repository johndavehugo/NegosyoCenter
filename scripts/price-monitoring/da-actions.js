'use strict';

var DA_ACTIONS_CONFIG = {
    agencyId: '2',
    agencyCode: 'DA',
    exportFileName: 'da-price-report.csv'
};

function daActionEscape(value) {
    if (value === null || value === undefined) {
        return '';
    }

    return String(value)
        .replace(/"/g, '""');
}

function daActionGetTable() {
    if (
        $.fn.DataTable &&
        $.fn.DataTable.isDataTable('#tblPriceMonitoring')
    ) {
        return $('#tblPriceMonitoring').DataTable();
    }

    return null;
}

function daActionShowMessage(
    icon,
    title,
    text
) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: icon,
            title: title,
            text: text
        });

        return;
    }

    alert(
        title +
        '\n\n' +
        text
    );
}

function daActionReloadTable() {
    if (
        typeof window.daTable !== 'undefined' &&
        window.daTable
    ) {
        window.daTable.ajax.reload(
            null,
            false
        );

        return;
    }

    if (
        typeof loadDAPriceData === 'function'
    ) {
        loadDAPriceData();
        return;
    }

    if (
        typeof loadPriceData === 'function'
    ) {
        loadPriceData();
        return;
    }

    var table = daActionGetTable();

    if (table) {
        if (
            table.ajax &&
            typeof table.ajax.reload === 'function'
        ) {
            table.ajax.reload(
                null,
                false
            );
        }
    }
}

function daActionBuildCsvValue(value) {
    return '"' +
        daActionEscape(value) +
        '"';
}

function daActionGetBrandUnit(row) {
    var brand =
        row.brand_name ||
        '';

    var unit =
        row.unit_of_measure ||
        '';

    if (brand && unit) {
        return brand +
            ' / ' +
            unit;
    }

    return brand || unit;
}

function exportExcelReport() {
    var table = daActionGetTable();

    if (!table) {
        daActionShowMessage(
            'error',
            'Error',
            'The price monitoring table is not initialized.'
        );

        return;
    }

    var rows =
        table
            .rows({
                search: 'applied'
            })
            .data()
            .toArray();

    if (!rows.length) {
        daActionShowMessage(
            'info',
            'No Data',
            'There is no data to export.'
        );

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

    var csvRows = [
        headers
            .map(daActionBuildCsvValue)
            .join(',')
    ];

    rows.forEach(function(row) {
        var values = [
            row.product_name || '',
            row.category_name || '',
            row.agency_code ||
                row.agency_name ||
                DA_ACTIONS_CONFIG.agencyCode,
            daActionGetBrandUnit(row),
            row.srp || '',
            row.prevailing_price || '',
            row.status || ''
        ];

        csvRows.push(
            values
                .map(daActionBuildCsvValue)
                .join(',')
        );
    });

    var csvContent =
        '\uFEFF' +
        csvRows.join('\r\n');

    var blob = new Blob(
        [csvContent],
        {
            type: 'text/csv;charset=utf-8;'
        }
    );

    var url =
        URL.createObjectURL(blob);

    var link =
        document.createElement('a');

    link.href = url;

    link.download =
        DA_ACTIONS_CONFIG.exportFileName;

    document.body.appendChild(link);

    link.click();

    document.body.removeChild(link);

    URL.revokeObjectURL(url);
}

function logout() {
    window.location.href =
        '../../session.php?logout=1';
}

$(document).on(
    'click',
    '#btnExportExcel, .btn-export-excel',
    function(e) {
        e.preventDefault();
        exportExcelReport();
    }
);