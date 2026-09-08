function viewBusiness(entityNo) {

    // Returns a badge HTML string for status-type fields
    function statusBadge(value, type) {
        if (!value || value === '—') return '<span class="badge badge-pill msme-badge-unknown">—</span>';

        var key = value.toString().toLowerCase().trim();
        var cls;

        if (type === 'app') {
            // Application status mapping
            var appMap = {
                'new':       'msme-badge-status-new',
                'approved':  'msme-badge-status-approved',
                'renewed':   'msme-badge-status-approved',
                'pending':   'msme-badge-status-pending',
                'rejected':  'msme-badge-status-rejected',
                'expired':   'msme-badge-status-rejected',
                'cancelled': 'msme-badge-status-rejected'
            };
            cls = appMap[key] || 'msme-badge-unknown';

        } else if (type === 'bus') {
            // Business status mapping
            var busMap = {
                'active':   'msme-badge-status-active',
                'inactive': 'msme-badge-status-inactive',
                'closed':   'msme-badge-status-closed',
                'suspended':'msme-badge-status-inactive'
            };
            cls = busMap[key] || 'msme-badge-unknown';

        } else if (type === 'class') {
            // Enterprise classification — reuse existing table badge classes
            var classMap = {
                'micro':  'msme-badge-micro',
                'small':  'msme-badge-small',
                'medium': 'msme-badge-medium',
                'large':  'msme-badge-large'
            };
            cls = classMap[key] || 'msme-badge-unknown';
        }

        return '<span class="badge badge-pill ' + cls + '">' + value + '</span>';
    }

    $.getJSON('../../api/routes.php/business/' + entityNo, function(res) {
        if (res.status === 'success' && res.data) {
            var d = res.data;
            var j = d.juridical || {};
            var e = d.employer  || {};

            $('#viewBusinessName').text(j.name || '—');
            $('#viewEntityNo').text(j.entity_no || '—');
            $('#viewOwnerEntityNo').text(e.entity_no || '—');

            // Plain text fields
            $('#viewOwner').text(e.full_name || '—');
            $('#viewCapitalization').text(currencyFormat(j.capitalization || '—'));
            $('#viewContactNo').text(j.contact_no || '—');
            $('#viewEmail').text(j.contact_email || '—');
            $('#viewSector').text(j.line_of_industry || '—');
            $('#viewSpecialSector').text(e.special_category || '—');
            $('#viewBusAddress').text(
                [j.street, j.subdivision, j.barangay, j.city, j.province].filter(Boolean).join(', ') || '—'
            );
            $('#viewOwnerAddress').text(
                [e.street, e.subdivision, e.barangay, e.city, e.province].filter(Boolean).join(', ') || '—'
            );

            // Badge fields
            $('#viewAppStatus').html(statusBadge(j.registration_type || '—', 'app'));
            $('#viewBusStatus').html(statusBadge(j.business_status   || '—', 'bus'));
            $('#viewClassification').html(statusBadge(j.msme_category || '—', 'class'));

        } else {
            App.alert({
                icon: 'warning',
                title: 'Not Found',
                text: 'This business record could not be loaded.'
            });
            return;
        }

        $('#viewBusinessModal').modal('show');
    });
}
