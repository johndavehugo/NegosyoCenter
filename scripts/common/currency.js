function currencyFormat(value, withSymbol) {
    if (!value || isNaN(value)) return value || '—';
    var opts = { minimumFractionDigits: 2, maximumFractionDigits: 2 };
    if (withSymbol !== false) { opts.style = 'currency'; opts.currency = 'PHP'; }
    return new Intl.NumberFormat('en-PH', opts).format(value);
}

function currencyParse(value) {
    return String(value).replace(/[^\d.-]/g, '');
}

function formatLive($input) {
    var el = $input[0];
    var pos = el.selectionStart;
    var raw = $input.val();
    var digits = currencyParse(raw);

    var parts = digits.split('.');
    var intFormatted = (parts[0] || '0').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    var dec = (parts[1] || '') + '00';
    var formatted = intFormatted + '.' + dec.slice(0, 2);   // .00 always visible

    var digitsBeforeCaret = raw.slice(0, pos).replace(/\D/g, '').length;
    var caretPos = 0, seen = 0;
    while (caretPos < formatted.length && seen < digitsBeforeCaret) {
        if (/\d/.test(formatted[caretPos])) seen++;
        caretPos++;
    }

    if (raw !== formatted) {
        $input.val(formatted);
        el.setSelectionRange(caretPos, caretPos);
    }
}

function bindCurrencyInput($input) {
    $input.on('keydown', function (e) {
        if (e.ctrlKey || e.metaKey || e.altKey) return;
        if (e.key.length > 1) return;
        if (!/[0-9.]/.test(e.key)) e.preventDefault();
    });
    $input.on('input', function () { formatLive($(this)); });
    $input.on('blur', function () {
        var v = currencyParse($(this).val());
        $(this).val(v ? currencyFormat(v, false) : '');
    });
}