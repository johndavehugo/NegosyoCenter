window.address = (function () {
    var base = '../../server-side/address-handler.php';
    var wired = {};

    function ph(text) { return '<option value="" hidden>' + text + '</option>'; }

    function ensureRegions(regionSel) {
        if ($(regionSel + ' option').length >= 2) return;
        $.getJSON(base, { action: 'regions' }).done(function (data) {
            var opts = ph('Select Region');
            data.forEach(function (r) {
                opts += '<option value="' + r.name + '" data-psgc-id="' + r.psgc_id + '">' + r.name + '</option>';
            });
            $(regionSel).html(opts);
        });
    }

    function buildOptions(items, selectedPsgcId, typeAware) {
        var opts = '';
        var found = false;
        (items || []).forEach(function (x) {
            var selected = x.psgc_id === selectedPsgcId ? ' selected' : '';
            if (x.psgc_id === selectedPsgcId) found = true;
            opts += '<option value="' + x.name + '" data-psgc-id="' + x.psgc_id + '"'
                + (typeAware ? ' data-type="' + x.geographical_level + '"' : '') + selected + '>' + x.name + '</option>';
        });
        return { html: opts, found: found };
    }

    function appendMissing($sel, item, typeAware) {
        if (!item) return;
        if ($sel.find('option[data-psgc-id="' + item.psgc_id + '"]').length > 0) return;
        $sel.append('<option value="' + item.name + '" data-psgc-id="' + item.psgc_id + '"'
            + (typeAware ? ' data-type="' + item.geographical_level + '"' : '') + ' selected>' + item.name + '</option>');
    }

    function loadCities(citySel, barangaySel, parentPsgcId, parentType) {
        $(citySel).html(ph('Select City'));
        $.getJSON(base, { action: 'cities', psgc_id: parentPsgcId, parent: parentType })
            .done(function (data) {
                $(citySel).html(ph('Select City') + buildOptions(data, '', true).html);
            });
    }

    function loadBarangays(barangaySel, cityPsgcId) {
        $(barangaySel).html(ph('Select Barangay'));
        $.getJSON(base, { action: 'barangays', psgc_id: cityPsgcId })
            .done(function (data) {
                $(barangaySel).html(ph('Select Barangay') + buildOptions(data, '').html);
            });
    }

    function cascade(regionSel, provinceSel, citySel, barangaySel) {
        var key = regionSel + provinceSel + citySel + barangaySel;
        if (wired[key]) return;
        wired[key] = true;

        ensureRegions(regionSel);

        $(regionSel).on('change', function () {
            var regionPsgcId = $(this).find(':selected').data('psgc-id');
            $(provinceSel).html(ph('Select Province'));
            $(citySel).html(ph('Select City'));
            $(barangaySel).html(ph('Select Barangay'));
            if (!regionPsgcId) return;

            $.getJSON(base, { action: 'provinces', psgc_id: regionPsgcId })
                .done(function (data) {
                    if (data.length) {
                        $(provinceSel).html(ph('Select Province') + buildOptions(data, '').html);
                    } else {
                        loadCities(citySel, barangaySel, regionPsgcId, 'region');
                    }
                });
        });

        $(provinceSel).on('change', function () {
            var provPsgcId = $(this).find(':selected').data('psgc-id');
            $(barangaySel).html(ph('Select Barangay'));
            if (provPsgcId) loadCities(citySel, barangaySel, provPsgcId, 'province');
        });

        $(citySel).on('change', function () {
            var cityPsgcId = $(this).find(':selected').data('psgc-id');
            if (cityPsgcId) loadBarangays(barangaySel, cityPsgcId);
        });
    }

    function prefill(regionSel, provinceSel, citySel, barangaySel, addr) {
        ensureRegions(regionSel);
        $.getJSON(base, {
            action: 'resolve',
            region: addr.region || '',
            province: addr.province || '',
            city: addr.city || '',
            barangay: addr.barangay || ''
        }).done(function (r) {
            if (r.region) {
                var found = false;
                $(regionSel + ' option').each(function () {
                    if ($(this).attr('data-psgc-id') === r.region.psgc_id) {
                        $(this).prop('selected', true);
                        found = true;
                    }
                });
                if (!found) appendMissing($(regionSel), r.region);
            }

            var prov = buildOptions(r.provinces, r.province ? r.province.psgc_id : '');
            $(provinceSel).html(ph('Select Province') + prov.html);
            appendMissing($(provinceSel), r.province);

            var city = buildOptions(r.places, r.city ? r.city.psgc_id : '', true);
            $(citySel).html(ph('Select City') + city.html);
            appendMissing($(citySel), r.city, true);

            var brgy = buildOptions(r.barangays, r.barangay ? r.barangay.psgc_id : '');
            $(barangaySel).html(ph('Select Barangay') + brgy.html);
            appendMissing($(barangaySel), r.barangay);
        }).fail(function () {
            console.log('Address prefill failed');
        });
    }

    return { cascade: cascade, prefill: prefill };
})();