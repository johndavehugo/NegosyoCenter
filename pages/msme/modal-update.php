<?php

require_once __DIR__ . '/../../global/industries.php';

?>

<form id="updateBusinessForm" method="POST">
    <div class="modal fade" id="updateBusinessModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content msme-modal-content">

                <div class="modal-header msme-modal-header">
                    <h5 class="modal-title">
                        <i class="material-icons text-primary align-middle mr-2" style="font-size:22px;">storefront</i>Update Business
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="bs-stepper upd-stepper">
                        <div class="bs-stepper-header" role="tablist">
                            <div class="step" data-target="#upd-step-business">
                                <button type="button" class="step-trigger" role="tab">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">Business Info</span>
                                </button>
                            </div>
                            <div class="bs-stepper-line"></div>
                            <div class="step" data-target="#upd-step-owner">
                                <button type="button" class="step-trigger" role="tab">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">Owner Info</span>
                                </button>
                            </div>
                        </div>

                        <div class="bs-stepper-content">
                            <!-- Step 1: Business Info -->
                            <div id="upd-step-business" class="content" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">Business Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control msme-input" id="updBusinessName" name="updBusinessName">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">Entity No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control msme-input" id="updBusEntityNo" name="updBusEntityNo" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">Capitalization</label>
                                            <input type="number" step="0.01" class="form-control msme-input" id="updCapitalization" name="updCapitalization">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">Line of Industry</label>
                                            <select class="form-control msme-input" id="updIndustry" name="updIndustry">
                                                <option value="" hidden>Select Industry</option>
                                                <?php foreach ($industries as $industry): ?>
                                                    <option value="<?= strtoupper($industry) ?>"><?= strtoupper($industry) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">Contact No</label>
                                            <input type="text" class="form-control msme-input" id="updContactNo" name="updContactNo">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">Email</label>
                                            <input type="email" class="form-control msme-input" id="updEmail" name="updEmail">
                                        </div>
                                    </div>
                                </div>

                                <div class="card msme-section-card mb-3">
                                    <div class="card-header msme-section-header">
                                        <i class="material-icons text-primary align-middle mr-1" style="font-size:18px;">location_on</i>Business Address
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="msme-label">Region</label>
                                                    <select class="form-control msme-input" id="updBusRegion" name="updBusRegion">
                                                        <option value="" hidden>Select Region</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="msme-label">Province</label>
                                                    <select class="form-control msme-input" id="updBusProvince" name="updBusProvince">
                                                        <option value="" hidden>Select Province</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="msme-label">City/Municipality <span class="text-danger">*</span></label>
                                                    <select class="form-control msme-input" id="updBusCity" name="updBusCity">
                                                        <option value="" hidden>Select City</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="msme-label">Barangay <span class="text-danger">*</span></label>
                                                    <select class="form-control msme-input" id="updBusBarangay" name="updBusBarangay">
                                                        <option value="" hidden>Select Barangay</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="msme-label">Street</label>
                                                    <input type="text" class="form-control msme-input" id="updBusStreet" name="updBusStreet">
                                                </div>
                                                <div class="form-group">
                                                    <label class="msme-label">Subdivision</label>
                                                    <input type="text" class="form-control msme-input" id="updBusSubdivision" name="updBusSubdivision">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label class="msme-label">UPBLB No</label>
                                                    <input type="text" class="form-control msme-input" id="updBusUpblb" name="updBusUpblb">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <span></span>
                                    <button type="button" class="btn btn-raised-primary d-flex align-items-center" onclick="updStepper.next()">
                                        Next<i class="material-icons ml-1" style="font-size:18px;">arrow_forward</i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Owner Info -->
                            <div id="upd-step-owner" class="content" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">Owner Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control msme-input" id="updOwnerName" name="updOwnerName">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">Entity No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control msme-input" id="updEmpEntityNo" name="updEmpEntityNo" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">Special Category</label>
                                            <select class="form-control msme-input" id="updSpecialCategory" name="updSpecialCategory">
                                                <option value="" hidden>Select Special Sector Classification</option>
                                                <option value="4ps Beneficiary">4ps Beneficiary</option>
                                                <option value="Solo Parent">Solo Parent</option>
                                                <option value="Person with Disability">Person with Disability (PWD)</option>
                                                <option value="Young Entrepreneur">Young Entrepreneur</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="card msme-section-card mb-3">
                                    <div class="card-header msme-section-header">
                                        <i class="material-icons text-primary align-middle mr-1" style="font-size:18px;">location_on</i>Owner Address
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="msme-label">Region</label>
                                                    <select class="form-control msme-input" id="updEmpRegion" name="updEmpRegion">
                                                        <option value="" hidden>Select Region</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="msme-label">Province</label>
                                                    <select class="form-control msme-input" id="updEmpProvince" name="updEmpProvince">
                                                        <option value="" hidden>Select Province</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="msme-label">City/Municipality <span class="text-danger">*</span></label>
                                                    <select class="form-control msme-input" id="updEmpCity" name="updEmpCity">
                                                        <option value="" hidden>Select City</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="msme-label">Barangay <span class="text-danger">*</span></label>
                                                    <select class="form-control msme-input" id="updEmpBarangay" name="updEmpBarangay">
                                                        <option value="" hidden>Select Barangay</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="msme-label">Street</label>
                                                    <input type="text" class="form-control msme-input" id="updEmpStreet" name="updEmpStreet">
                                                </div>
                                                <div class="form-group">
                                                    <label class="msme-label">Subdivision</label>
                                                    <input type="text" class="form-control msme-input" id="updEmpSubdivision" name="updEmpSubdivision">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label class="msme-label">UPBLB No</label>
                                                    <input type="text" class="form-control msme-input" id="updEmpUpblb" name="updEmpUpblb">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-text-secondary d-flex align-items-center" onclick="updStepper.previous()">
                                        <i class="material-icons mr-1" style="font-size:18px;">arrow_back</i>Previous
                                    </button>
                                    <button type="button" class="btn btn-raised-primary d-flex align-items-center" onclick="updateBusiness()">
                                        <i class="material-icons mr-1" style="font-size:18px;">save</i>Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>
