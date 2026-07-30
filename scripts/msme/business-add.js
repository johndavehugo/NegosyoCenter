var stepper;


$('#addCustomerModal').on('shown.bs.modal', function() {
    stepper = new Stepper($('.bs-stepper')[0]);
});
$('#addCustomerModal').on('hidden.bs.modal', function() {
    if (stepper) stepper.reset();
});

