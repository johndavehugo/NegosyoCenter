/* =============================================================
   business-view.js (Consolidated)
   Smart business viewer that handles both:
   1. Navigation from msme.php table (redirects to page-view.php)
   2. Full page functionality for page-view.php (loads and displays data)
   
   Context detection determines which functionality to initialize.
   ============================================================= */

(function () {

    /* ── Context Detection ─────────────────────────────────── */
    
    var currentPath = window.location.pathname;
    var isViewPage = currentPath.includes('page-view.php') || currentPath.includes('business-view.php');
    var isMainPage = currentPath.includes('msme.php');

    /* ── Shared Helpers ────────────────────────────────────── */

    function getParam(name) {
        return new URLSearchParams(window.location.search).get(name) || '';
    }

    function dash(value) {
        return (value && String(value).trim() !== '') ? String(value).trim() : '—';
    }

    function addressLine(parts) {
        var filtered = (parts || []).filter(function (p) { return p && p.trim() !== ''; });
        return filtered.length ? filtered.join(', ') : '—';
    }

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

    /* ── Data Population ───────────────────────────────────── */

    function populate(data) {
        var j = data.juridical || {};
        var e = data.employer  || {};

        /* Left panel */
        $('#bvBusinessName').text(dash(j.name));
        $('#bvEntityNo').text(dash(j.entity_no));
        $('#bvOwner').text(dash(e.full_name));
        $('#bvCapitalization').text(currencyFormat(j.capitalization || ''));
        $('#bvContactNo').text(dash(j.contact_no));
        $('#bvEmail').text(dash(j.contact_email));
        $('#bvBusStatus').html(statusBadge(j.business_status   || '', 'bus'));
        $('#bvAppStatus').html(statusBadge(j.registration_type || '', 'app'));

        /* Address cards */
        $('#bvBusAddressShort').text(addressLine([
            j.street, j.subdivision, j.barangay, j.city, j.province
        ]));
        $('#bvOwnerAddressShort').text(addressLine([
            e.street, e.subdivision, e.barangay, e.city, e.province
        ]));

        /* Classification section - populate input fields */
        $('#bvClassificationInput').val(j.msme_category || '').trigger('change');
        $('#bvSectorInput').val(j.line_of_industry || '');
        $('#bvSpecialSectorInput').val(e.special_category || ''); // Text input for special sector

        /* Owner Details section - populate input fields */
        $('#bvOwnerEntityNo').text(dash(e.entity_no));
        $('#bvOwnerNameInput').val(e.full_name || '');
        
        // Handle Special Category dropdown - map text value to dropdown options
        var specialCat = e.special_category || '';
        if (specialCat) {
            // Try to match the value with dropdown options
            var $specialCategorySelect = $('#bvSpecialCategoryInput');
            var optionExists = $specialCategorySelect.find('option[value="' + specialCat + '"]').length > 0;
            
            if (optionExists) {
                $specialCategorySelect.val(specialCat);
            } else {
                // If exact match not found, try partial matching
                var matchedOption = '';
                $specialCategorySelect.find('option').each(function() {
                    var optionValue = $(this).val();
                    if (optionValue && specialCat.toLowerCase().includes(optionValue.toLowerCase().split('-')[0])) {
                        matchedOption = optionValue;
                        return false; // Break the loop
                    }
                });
                $specialCategorySelect.val(matchedOption);
            }
        } else {
            $('#bvSpecialCategoryInput').val('');
        }
        
        $('#bvOwnerRegionInput').val(e.region || '');
        $('#bvOwnerProvinceInput').val(e.province || '');
        $('#bvOwnerCityInput').val(e.city || '');
        $('#bvOwnerBarangayInput').val(e.barangay || '');
        $('#bvOwnerStreetInput').val(e.street || '');
        $('#bvOwnerSubdivisionInput').val(e.subdivision || '');

        /* Business Address section - populate input fields */
        $('#bvBusRegionInput').val(j.region || '');
        $('#bvBusProvinceInput').val(j.province || '');
        $('#bvBusCityInput').val(j.city || '');
        $('#bvBusBarangayInput').val(j.barangay || '');
        $('#bvBusStreetInput').val(j.street || '');
        $('#bvBusSubdivisionInput').val(j.subdivision || '');
        $('#bvBusUpblbInput').val(j.upblb_num || '');

        /* Update page title if on view page */
        if (isViewPage) {
            document.title = dash(j.name) + ' — Negosyo Center';
        }
    }

    /* ── Menu Navigation ───────────────────────────────────── */

    function initMenuNav() {
        /* Menu item click → show content card */
        $('#bvMenuList').off('click', '.bv-menu-item').on('click', '.bv-menu-item', function () {
            var target = $(this).data('target');
            var title = $(this).data('title'); // Use data-title attribute instead of parsing text

            $('#bvMenuList').fadeOut(100, function () {
                $(this).addClass('d-none');
                $('#bvContentTitle').text(title);
                $('.bv-content-section').addClass('d-none');
                $('#' + target).removeClass('d-none');
                $('#bvContentCard').removeClass('d-none').hide().fadeIn(100);
            });
        });

        /* Back button → return to menu list */
        $('#bvContentBack').off('click').on('click', function () {
            $('#bvContentCard').fadeOut(100, function () {
                $(this).addClass('d-none');
                $('.bv-content-section').addClass('d-none');
                $('#bvMenuList').removeClass('d-none').hide().fadeIn(100);
            });
        });
    }

    /* ── Navigation Mode (for msme.php) ────────────────────── */

    function initNavigationMode() {
        console.log('Business View: Navigation mode initialized');
        
        /* Public navigation function for table buttons */
        window.viewBusiness = function (entityNo) {
            console.log('viewBusiness called with entityNo:', entityNo);
            var targetUrl = '/NegosyoCenter/pages/msme/page-view.php?id=' + encodeURIComponent(entityNo);
            console.log('Navigating to:', targetUrl);
            window.location.href = targetUrl;
        };
    }

    /* ── Page Mode (for page-view.php) ─────────────────────── */

    function initPageMode() {
        console.log('Business View: Page mode initialized');

        $(function () {
            var entityNo = getParam('id');

            if (!entityNo) {
                /* No id param — show not-found state immediately */
                $('#bvLoading').hide();
                $('#bvNotFound').removeClass('d-none');
                return;
            }

            initMenuNav();

            var fetchStart = Date.now();
            var MIN_LOADING_MS = 800; // Show loading for at least this long (normal speed)

            /* API path relative to the page location */
            $.getJSON('../../api/routes.php/business/' + encodeURIComponent(entityNo))
                .done(function (res) {
                    var elapsed = Date.now() - fetchStart;
                    var remaining = Math.max(0, MIN_LOADING_MS - elapsed);

                    setTimeout(function () {
                        if (res.status === 'success' && res.data) {
                            populate(res.data);

                            /* Hide loading, reveal main layout */
                            $('#bvLoading').hide();
                            $('#bvMain').removeClass('d-none');
                        } else {
                            $('#bvLoading').hide();
                            $('#bvNotFound').removeClass('d-none');
                        }
                    }, remaining);
                })
                .fail(function () {
                    var elapsed = Date.now() - fetchStart;
                    var remaining = Math.max(0, MIN_LOADING_MS - elapsed);

                    setTimeout(function () {
                        $('#bvLoading').hide();
                        if (typeof App !== 'undefined' && App.alert) {
                            App.alert({
                                icon:  'error',
                                title: 'Request Failed',
                                text:  'Could not load business data. Please check your connection and try again.'
                            });
                        } else {
                            alert('Could not load business data. Please check your connection and try again.');
                        }
                        $('#bvNotFound').removeClass('d-none');
                    }, remaining);
                });
        });
    }

    /* ── Save Functions (Enhanced with validation) ──────────── */

    function validateSection(sectionType) {
        var isValid = true;
        var fields = [];

        if (sectionType === 'classification') {
            fields = [
                { id: '#bvClassificationInput', name: 'Enterprise Class' },
                { id: '#bvSectorInput', name: 'Sector / Product Line' }
            ];
        } else if (sectionType === 'owner') {
            fields = [
                { id: '#bvOwnerNameInput', name: 'Owner Full Name' },
                { id: '#bvOwnerRegionInput', name: 'Owner Region' },
                { id: '#bvOwnerProvinceInput', name: 'Owner Province' },
                { id: '#bvOwnerCityInput', name: 'Owner City' },
                { id: '#bvOwnerBarangayInput', name: 'Owner Barangay' }
            ];
        } else if (sectionType === 'address') {
            fields = [
                { id: '#bvBusRegionInput', name: 'Business Region' },
                { id: '#bvBusProvinceInput', name: 'Business Province' },
                { id: '#bvBusCityInput', name: 'Business City' },
                { id: '#bvBusBarangayInput', name: 'Business Barangay' }
            ];
        }

        // Clear previous validation states
        fields.forEach(function(field) {
            $(field.id).removeClass('is-valid is-invalid');
        });

        // Validate each field
        fields.forEach(function(field) {
            var value = $(field.id).val().trim();
            if (!value) {
                $(field.id).addClass('is-invalid');
                isValid = false;
            } else {
                $(field.id).addClass('is-valid');
            }
        });

        return isValid;
    }

    function updateProgress(percentage) {
        $('#progressBar').css('width', percentage + '%');
    }

    function showSectionLoading(sectionId) {
        var section = $('#' + sectionId);
        if (section.find('.bv-section-loading').length === 0) {
            section.css('position', 'relative').append(
                '<div class="bv-section-loading">' +
                '<div class="spinner"></div>' +
                '</div>'
            );
        }
    }

    function hideSectionLoading(sectionId) {
        $('#' + sectionId + ' .bv-section-loading').remove();
    }

    function saveSection(sectionType) {
        console.log('Save section:', sectionType);
        
        // Validate required fields
        if (!validateSection(sectionType)) {
            // Show validation error toast
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Please fill in all required fields marked with *',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000
                });
            }
            return;
        }
        
        // Get button and section elements
        var button = event.target.closest('button');
        var sectionId = 'bvSection' + sectionType.charAt(0).toUpperCase() + sectionType.slice(1);
        var originalText = button.innerHTML;
        
        // Show loading state
        button.disabled = true;
        button.innerHTML = '<div class="spinner-border spinner-border-sm mr-2" role="status"></div>Saving...';
        showSectionLoading(sectionId);
        updateProgress(25);
        
        // Simulate save operation with realistic timing
        setTimeout(function() {
            updateProgress(50);
            
            // Collect data based on section type
            var data = {};
            
            if (sectionType === 'classification') {
                data = {
                    classification: $('#bvClassificationInput').val(),
                    sector: $('#bvSectorInput').val(),
                    specialSector: $('#bvSpecialSectorInput').val()
                };
            } else if (sectionType === 'owner') {
                data = {
                    ownerName: $('#bvOwnerNameInput').val(),
                    specialCategory: $('#bvSpecialCategoryInput').val(),
                    address: {
                        region: $('#bvOwnerRegionInput').val(),
                        province: $('#bvOwnerProvinceInput').val(),
                        city: $('#bvOwnerCityInput').val(),
                        barangay: $('#bvOwnerBarangayInput').val(),
                        street: $('#bvOwnerStreetInput').val(),
                        subdivision: $('#bvOwnerSubdivisionInput').val()
                    }
                };
            } else if (sectionType === 'address') {
                data = {
                    address: {
                        region: $('#bvBusRegionInput').val(),
                        province: $('#bvBusProvinceInput').val(),
                        city: $('#bvBusCityInput').val(),
                        barangay: $('#bvBusBarangayInput').val(),
                        street: $('#bvBusStreetInput').val(),
                        subdivision: $('#bvBusSubdivisionInput').val(),
                        upblb: $('#bvBusUpblbInput').val()
                    }
                };
            }
            
            updateProgress(75);
            console.log('Data to save:', data);
            
            // Simulate API processing time
            setTimeout(function() {
                updateProgress(100);
                hideSectionLoading(sectionId);
                
                // Show success feedback
                button.innerHTML = '<i class="material-icons mr-2" style="font-size:18px;">check_circle</i>Saved Successfully!';
                button.classList.remove('btn-enhanced-save');
                button.classList.add('btn-enhanced-success');
                
                // Show success toast
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Changes Saved',
                        text: 'Your ' + sectionType + ' information has been updated successfully.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
                
                // Reset button after 3 seconds
                setTimeout(function() {
                    button.innerHTML = originalText;
                    button.classList.remove('btn-enhanced-success');
                    button.classList.add('btn-enhanced-save');
                    button.disabled = false;
                    updateProgress(0);
                }, 3000);
                
            }, 800); // Additional processing time
            
        }, 1200); // Initial save time
    }

    /* ── Smart Initialization ──────────────────────────────── */

    $(function () {
        if (isViewPage) {
            initPageMode();
            // Make save function globally accessible for onclick handlers
            window.saveSection = saveSection;
            
            // Ensure text rendering is correct after page load
            setTimeout(function() {
                // Force repaint of label elements
                $('.bv-field-label').each(function() {
                    var $this = $(this);
                    var text = $this.text();
                    if (text) {
                        $this.text(text.trim());
                    }
                });
                
                // Ensure section headers are properly displayed
                $('.bv-section-header h6').each(function() {
                    var $this = $(this);
                    var text = $this.text();
                    if (text) {
                        $this.text(text.trim());
                    }
                });
            }, 100);
            
        } else if (isMainPage) {
            initNavigationMode();
        } else {
            console.log('Business View: Unknown context, initializing navigation mode as fallback');
            initNavigationMode();
        }
    });

}());
