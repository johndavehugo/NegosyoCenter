function setSelectValue(selector, value) {
    var $sel = $(selector);
    if ($sel.find('option').filter(function () { return $(this).val() === value; }).length) {
        $sel.val(value);
    } else {
        $sel.append('<option value="' + value + '" selected>' + value + '</option>');
    }
}
