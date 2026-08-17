function viewBusiness(entityNo) {
$.getJSON('../../api/routes.php/business/' + entityNo, function(res) {
if (res.status === 'success' && res.data) {
    var d = res.data;
    var j = d.juridical || {};
    var e = d.employer || {};
    $('#viewBusinessName').text(j.name || '—');
    $('#viewOwner').text(e.full_name || '—');
    $('#viewAppStatus').text(j.registration_type || '—');
    $('#viewCapitalization').text(j.capitalization || '—');
    $('#viewBusStatus').text(j.business_status || '—');
    $('#viewContactNo').text(j.contact_no || '—');
    $('#viewEmail').text(j.contact_email || '—');
    $('#viewBusAddress').text([j.street, j.subdivision, j.barangay, j.city, j.province].filter(Boolean).join(', ') || '—');
    $('#viewOwnerAddress').text([e.street, e.subdivision, e.barangay, e.city, e.province].filter(Boolean).join(', ') || '—');
    $('#viewSector').text(j.line_of_industry || '—');
    $('#viewClassification').text(j.msme_category || '—');
    $('#viewSpecialSector').text(e.special_category || '—');
    $('#viewEntityNo').text(j.entity_no || '—');
    $('#viewOwnerEntityNo').text(e.entity_no || '—');
    $('#viewOwnerAddress').text([e.street, e.subdivision, e.barangay, e.city, e.province].filter(Boolean).join(', ') || '—');
} else {
    alert('Business not found.');
}
$('#viewBusinessModal').modal('show');
});
}