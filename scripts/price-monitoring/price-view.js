var commoditiesCache = [];
var currentCategoryItems = [];

function getApiBase() {
    var path = window.location.pathname;
    var base = '/NegosyoCenter/api/routes.php/';
    
    if (path.indexOf('/NegosyoCenter') !== -1) {
        base = path.substring(0, path.indexOf('/NegosyoCenter')) + '/NegosyoCenter/api/routes.php/';
    }
    
    return window.location.origin + base;
}

function formatPeso(value) {
    var amount = Number(value);
    if (value === null || value === undefined || value === '' || isNaN(amount)) {
        return '-';
    }
    return '\u20B1' + amount.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function loadCategories() {
    fetch(getApiBase() + 'price-monitoring?action=commodity_categories')
        .then(function (response) {
            return response.json();
        })
        .then(function (result) {
            if (result.status !== 'success') {
                throw new Error(result.message || 'Unable to load categories.');
            }

            var grid = $('#categoryGrid');
            grid.empty();

            var categories = result.data || [];

            if (categories.length === 0) {
                grid.html(
                    '<div class="col-12 text-center text-muted">' +
                    '<p>No categories available yet.</p>' +
                    '</div>'
                );
                return;
            }

            categories.forEach(function (category) {
                var catId = category.category_id || category.id;

                var count = commoditiesCache.filter(function (item) {
                    var itemCatId = item.category_id || item.cat_id || item.commodity_category_id;
                    return String(itemCatId) === String(catId);
                }).length;

                var countHtml = count > 0
                    ? '<div class="category-count"><span class="badge">' + count + ' item' + (count > 1 ? 's' : '') + '</span></div>'
                    : '';

                var card = $(
                    '<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">' +
                    '<div class="category-card" tabindex="0" role="button" ' +
                    'data-category-id="' + catId + '" ' +
                    'data-category-name="' + escapeHtml(category.category_name || category.name) + '">' +
                    '<div class="category-icon"><i class="fas fa-box-open"></i></div>' +
                    '<div class="category-name">' + escapeHtml(category.category_name || category.name) + '</div>' +
                    '<div class="category-agency">' + escapeHtml(category.agency_name || '') + '</div>' +
                    countHtml +
                    '</div>' +
                    '</div>'
                );

                grid.append(card);
            });
        })
        .catch(function (error) {
            console.error('[PRICE VIEW] Error loading categories:', error);
            $('#categoryGrid').html(
                '<div class="col-12 text-center text-danger">' +
                '<p>' + (error.message || 'Unable to load categories.') + '</p>' +
                '</div>'
            );
        });
}

function loadCommodities() {
    fetch(getApiBase() + 'commodity?action=public')
        .then(function (response) {
            return response.json();
        })
        .then(function (result) {
            if (result.status !== 'success') {
                throw new Error(result.message || 'Unable to load commodities.');
            }

            commoditiesCache = result.data || [];
            loadCategories();
        })
        .catch(function (error) {
            console.error('[PRICE VIEW] Error loading commodities:', error);
            loadCategories();
        });
}

function renderCommodityList(items) {
    var list = $('#commodityList');
    list.empty();

    if (!items || items.length === 0) {
        var searching = $('#commoditySearch').val().trim() !== '';
        list.append(
            '<div class="commodity-empty">' +
            (searching ? 'No commodities match your search.' : 'No commodities available in this category yet.') +
            '</div>'
        );
        return;
    }

    items.forEach(function (item) {
        var metaParts = [];
        if (item.brand_name) metaParts.push(item.brand_name);
        if (item.unit_of_measure) metaParts.push(item.unit_of_measure);

        var commId = item.commodity_id || item.id;
        var prodName = item.product_name || item.name || '-';
        var srpVal = item.srp || item.price || 0;

        // Store data attributes on row (including SRP for modal pass-through)
        var row = $('<div>', { 
            class: 'commodity-row',
            'data-commodity-id': commId,
            'data-product-name': prodName,
            'data-srp': srpVal,
            'data-unit': item.unit_of_measure || ''
        });

        var info = $('<div>', { class: 'commodity-info' });
        info.append($('<div>', { class: 'commodity-name', text: prodName }));

        if (metaParts.length) {
            info.append($('<div>', { class: 'commodity-meta', text: metaParts.join(' \u00B7 ') }));
        }

        // Action indicator arrow (SRP price display removed from list view)
        var actionContainer = $('<div>', { class: 'commodity-price' });
        actionContainer.append('<small class="text-muted" style="font-weight: normal; font-size: 0.85rem;">View Available Establishments <i class="fas fa-chevron-right ml-1"></i></small>');

        row.append(info);
        row.append(actionContainer);

        list.append(row);
    });
}

function openCategoryModal(categoryId, categoryName) {
    currentCategoryItems = commoditiesCache.filter(function (item) {
        var itemCatId = item.category_id || item.cat_id || item.commodity_category_id;
        return String(itemCatId) === String(categoryId);
    });

    $('#modalCategoryTitle').text(categoryName);
    $('#commoditySearch').val('');
    renderCommodityList(currentCategoryItems);

    $('#categoryCommoditiesModal').modal('show');
}

function openEstablishmentDetailModal(commodityId, productName, srp, unit) {
    $('#modalCommodityTitle').text(productName);
    $('#modalCommoditySubtitle').text(unit ? 'Unit: ' + unit : '');

    var tbody = $('#establishmentListBody');
    tbody.html('<tr><td colspan="3" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin mr-2"></i>Loading establishments...</td></tr>');

    $('#categoryCommoditiesModal').modal('hide');
    $('#establishmentDetailModal').modal('show');

    fetch(getApiBase() + 'price-monitoring?action=commodity_establishments&commodity_id=' + commodityId)
        .then(function(response) { return response.json(); })
        .then(function(result) {
            tbody.empty();
            if (result.status === 'success' && result.data && result.data.length > 0) {
                result.data.forEach(function(est) {
                    var displaySrp = est.srp !== undefined && est.srp !== null ? est.srp : srp;
                    var displayPrevailing = est.prevailing_price !== undefined && est.prevailing_price !== null ? est.prevailing_price : displaySrp;
                    var estName = est.establishment_name || est.name || 'Establishment';

                    var row = $('<tr>');
                    row.append('<td class="pl-4"><strong>' + escapeHtml(estName) + '</strong>' + 
                        (est.branch ? '<br><small class="text-muted">' + escapeHtml(est.branch) + '</small>' : '') + '</td>');
                    row.append('<td class="text-right text-muted">' + formatPeso(displaySrp) + '</td>');
                    row.append('<td class="text-right pr-4 font-weight-bold text-dark">' + formatPeso(displayPrevailing) + '</td>');
                    tbody.append(row);
                });
            } else {
                tbody.html('<tr><td colspan="3" class="text-center text-muted py-4">No establishment details found for this item.</td></tr>');
            }
        })
        .catch(function(err) {
            console.error('Error fetching establishments:', err);
            tbody.html('<tr><td colspan="3" class="text-center text-danger py-4">Failed to load establishment data.</td></tr>');
        });
}

$(document).ready(function () {
    loadCommodities();

    $(document).on('click keydown', '.category-card', function (event) {
        if (event.type === 'keydown' && event.key !== 'Enter' && event.key !== ' ') {
            return;
        }
        if (event.type === 'keydown') {
            event.preventDefault();
        }

        openCategoryModal(
            $(this).data('category-id'),
            $(this).data('category-name')
        );
    });

    $(document).on('click', '.commodity-row', function () {
        var id = $(this).data('commodity-id');
        var name = $(this).data('product-name');
        var srp = $(this).data('srp');
        var unit = $(this).data('unit');

        openEstablishmentDetailModal(id, name, srp, unit);
    });

    $('#btnBackToCommodities').on('click', function () {
        $('#establishmentDetailModal').modal('hide');
        $('#categoryCommoditiesModal').modal('show');
    });

    $('#commoditySearch').on('input', function () {
        var query = $(this).val().trim().toLowerCase();

        if (query === '') {
            renderCommodityList(currentCategoryItems);
            return;
        }

        var filtered = currentCategoryItems.filter(function (item) {
            var haystack = [
                item.product_name || item.name,
                item.brand_name,
                item.unit_of_measure
            ].filter(Boolean).join(' ').toLowerCase();

            return haystack.indexOf(query) !== -1;
        });

        renderCommodityList(filtered);
    });

    $('#categoryCommoditiesModal').on('hidden.bs.modal', function () {
        $('#commoditySearch').val('');
    });
});