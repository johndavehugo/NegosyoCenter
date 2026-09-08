var commoditiesCache = [];
var currentCategoryItems = [];

function getApiBase() {
    var base = window.location.pathname.split('/pages/')[0];
    return window.location.origin + base + '/api/routes.php/';
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
                var count = commoditiesCache.filter(function (item) {
                    return String(item.category_id) === String(category.category_id);
                }).length;

                var countHtml = count > 0
                    ? '<div class="category-count"><span class="badge">' + count + ' item' + (count > 1 ? 's' : '') + '</span></div>'
                    : '';

                var card = $(
                    '<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">' +
                    '<div class="category-card" tabindex="0" role="button" ' +
                    'data-category-id="' + category.category_id + '" ' +
                    'data-category-name="' + escapeHtml(category.category_name) + '">' +
                    '<div class="category-icon"><i class="fas fa-box-open"></i></div>' +
                    '<div class="category-name">' + escapeHtml(category.category_name) + '</div>' +
                    '<div class="category-agency">' + escapeHtml(category.agency_name || '') + '</div>' +
                    countHtml +
                    '</div>' +
                    '</div>'
                );

                grid.append(card);
            });
        })
        .catch(function (error) {
            console.error('[PRICE VIEW] Error:', error);
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
            console.error('[PRICE VIEW] Error:', error);
            loadCategories();
        });
}

function renderCommodityList(items) {
    var list = $('#commodityList');
    list.empty();

    if (items.length === 0) {
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

        var row = $('<div>', { class: 'commodity-row' });
        var info = $('<div>', { class: 'commodity-info' });

        info.append($('<div>', { class: 'commodity-name', text: item.product_name || '-' }));

        if (metaParts.length) {
            info.append($('<div>', { class: 'commodity-meta', text: metaParts.join(' \u00B7 ') }));
        }

        row.append(info);
        row.append($('<div>', { class: 'commodity-price', text: formatPeso(item.srp) }));

        list.append(row);
    });
}

function openCategoryModal(categoryId, categoryName) {
    currentCategoryItems = commoditiesCache.filter(function (item) {
        return String(item.category_id) === String(categoryId);
    });

    $('#modalCategoryTitle').text(categoryName);
    $('#commoditySearch').val('');
    renderCommodityList(currentCategoryItems);

    $('#categoryCommoditiesModal').modal('show');
}

$(document).ready(function () {
    loadCategories();
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

    $('#commoditySearch').on('input', function () {
        var query = $(this).val().trim().toLowerCase();

        if (query === '') {
            renderCommodityList(currentCategoryItems);
            return;
        }

        var filtered = currentCategoryItems.filter(function (item) {
            var haystack = [
                item.product_name,
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