/* =============================================================
   business-view-page.js
   Powers pages/msme/business-view.php
   — reads ?id= from the URL, fetches the business record,
     populates both panels, and handles the menu navigation.
   ============================================================= */

(function () {

    /* ── Helpers ───────────────────────────────────────────── */

    function getParam(name) {
        return new URLSearchParams(window.location.search).get(name) || '';
    }

    function dash(value) {
        return (value && String(value).trim() !== '') ? value : '—';
    }

    function addressLine(parts) {
        var filled = parts.filter(function (p) { return p && p.trim() !== ''; });
        return filled.length ? filled.join(', ') : '—';
    }

    /* Badge builders — mirrors logic in business-view.js (modal version) */
    function statusBadge(value, type) {
        if (!value || value === '—') {
            return '<span class="badge badge-pill msme-badge-unknown">—</span>';
        }
        var key = value.toString().toLowerCase().trim();
        var cls;

        if (type === 'app') {
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
            var busMap = {
                'active':    'msme-badge-status-active',
                'inactive':  'msme-badge-status-inactive',
                'closed':    'msme-badge-status-closed',
                'suspended': 'msme-badge-status-inactive'
            };
            cls = busMap[key] || 'msme-badge-unknown';
        } else if (type === 'class') {
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

    /* ── Populate page with API data ───────────────────────── */

    function populate(data) {
        var j = data.juridical || {};
        var e = data.employer  || {};

        /* ── Left panel ── */
        $('#bvBusinessName').text(dash(j.name));
        $('#bvEntityNo').text(dash(j.entity_no));
        $('#bvOwner').text(dash(e.full_name));
        $('#bvCapitalization').text(currencyFormat(j.capitalization || ''));
        $('#bvContactNo').text(dash(j.contact_no));
        $('#bvEmail').text(dash(j.contact_email));
        $('#bvBusStatus').html(statusBadge(j.business_status   || '—', 'bus'));
        $('#bvAppStatus').html(statusBadge(j.registration_type || '—', 'app'));

        /* Address card */
        $('#bvBusAddressShort').text(addressLine([
            j.street, j.subdivision, j.barangay, j.city, j.province
        ]));
        $('#bvOwnerAddressShort').text(addressLine([
            e.street, e.subdivision, e.barangay, e.city, e.province
        ]));

        /* ── Right panel — Classification section ── */
        $('#bvClassification').html(statusBadge(j.msme_category || '—', 'class'));
        $('#bvSector').text(dash(j.line_of_industry));
        $('#bvSpecialSector').text(dash(e.special_category));

        /* ── Right panel — Owner Details section ── */
        $('#bvOwnerEntityNo').text(dash(e.entity_no));
        $('#bvOwnerName').text(dash(e.full_name));
        $('#bvSpecialCategory').text(dash(e.special_category));
        $('#bvOwnerRegion').text(dash(e.region));
        $('#bvOwnerProvince').text(dash(e.province));
        $('#bvOwnerCity').text(dash(e.city));
        $('#bvOwnerBarangay').text(dash(e.barangay));
        $('#bvOwnerStreet').text(dash(e.street));
        $('#bvOwnerSubdivision').text(dash(e.subdivision));

        /* ── Right panel — Business Address section ── */
        $('#bvBusRegion').text(dash(j.region));
        $('#bvBusProvince').text(dash(j.province));
        $('#bvBusCity').text(dash(j.city));
        $('#bvBusBarangay').text(dash(j.barangay));
        $('#bvBusStreet').text(dash(j.street));
        $('#bvBusSubdivision').text(dash(j.subdivision));
        $('#bvBusUpblb').text(dash(j.upblb_num));

        /* Update page title */
        document.title = dash(j.name) + ' — Negosyo Center';
    }

    /* ── Menu navigation (mirrors ViewTemplate index.js) ───── */

    function initMenuNav() {
        /* Menu item click → show content card */
        $('#bvMenuList').on('click', '.bv-menu-item', function () {
            var target = $(this).data('target');
            var title  = $(this).find('.d-flex').text().trim();

            $('#bvMenuList').fadeOut(100, function () {
                $(this).addClass('d-none');

                $('#bvContentTitle').text(title);

                /* Hide all sections, reveal the chosen one */
                $('.bv-content-section').addClass('d-none');
                $('#' + target).removeClass('d-none');

                $('#bvContentCard').removeClass('d-none').hide().fadeIn(100);
            });
        });

        /* Back button → return to menu list */
        $('#bvContentBack').on('click', function () {
            $('#bvContentCard').fadeOut(100, function () {
                $(this).addClass('d-none');
                $('.bv-content-section').addClass('d-none');
                $('#bvMenuList').removeClass('d-none').hide().fadeIn(100);
            });
        });
    }

    /* ── Bootstrap ─────────────────────────────────────────── */

    $(function () {
        var entityNo = getParam('id');

        if (!entityNo) {
            /* No id param — show not-found state immediately */
            $('#bvNotFound').removeClass('d-none');
            return;
        }

        initMenuNav();

        /* API path relative to the page location:
           pages/msme/business-view.php → ../../api/routes.php */
        $.getJSON('../../api/routes.php/business/' + encodeURIComponent(entityNo))
            .done(function (res) {
                if (res.status === 'success' && res.data) {
                    populate(res.data);

                    /* Reveal main layout */
                    $('#bvMain').removeClass('d-none');
                } else {
                    $('#bvNotFound').removeClass('d-none');
                }
            })
            .fail(function () {
                App.alert({
                    icon:  'error',
                    title: 'Request Failed',
                    text:  'Could not load business data. Please check your connection and try again.'
                });
                $('#bvNotFound').removeClass('d-none');
            });
    });

}());
