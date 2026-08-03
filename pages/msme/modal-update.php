<?php
require_once __DIR__ . '/../../global/industries.php';
?>

<form id="updateBusinessForm" method="POST">
<div class="modal fade" id="updateBusinessModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content modal-ncims">

            <!-- Header -->
            <div class="modal-header modal-ncims-header">
                <div class="d-flex align-items-center">
                    <div class="modal-icon-circle bg-orange mr-3">
                        <i class="fas fa-pen"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0">Update Business</h5>
                        <small style="color:rgba(255,255,255,.65);">Edit business and owner information</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <!-- Body -->
            <div class="modal-body modal-ncims-body">
                <div class="bs-stepper upd-stepper modal-stepper">

                    <!-- Stepper header -->
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
                        <div class="bs-stepper-line"></div>
                        <div class="step" data-target="#upd-step-address">
                            <button type="button" class="step-trigger" role="tab">
                                <span class="bs-stepper-circle">3</span>
                                <span class="bs-stepper-label">Addresses</span>
                            </button>
                        </div>
                    </div>

                    <div class="bs-stepper-content">

                        <!-- Step 1: Business Info -->
                        <div id="upd-step-business" class="content" role="tabpanel">
                            <div class="modal-section-label">
                                <i class="fas fa-store mr-2"></i>Business Information
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group modal-form-group">
                                        <label>Business Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="updBusinessName" name="updBusinessName">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group modal-form-group">
                                        <label>Entity No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="updBusEntityNo" name="updBusEntityNo" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group modal-form-group">
                                        <label>Capitalization</label>
                                        <input type="number" step="0.01" class="form-control" id="updCapitalization" name="updCapitalization">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group modal-form-group">
                                        <label>Line of Industry</label>
                                        <select class="form-control" id="updIndustry" name="updIndustry">
                                            <option value="" hidden>Select Industry</option>
                                            <?php foreach ($industries as $code => $industry): ?>
                                            <option value="<?= $industry ?>"><?= $code ?> - <?= $industry ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group modal-form-group">
                                        <label>Contact No</label>
                                        <input type="text" class="form-control" id="updContactNo" name="updContactNo">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group modal-form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" id="updEmail" name="updEmail">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-2">
                                <button type="button" class="btn btn-ncims-primary" onclick="updStepper.next()">
                                    Next <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 2: Owner Info -->
                        <div id="upd-step-owner" class="content" role="tabpanel">
                            <div class="modal-section-label">
                                <i class="fas fa-user mr-2"></i>Owner Information
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group modal-form-group">
                                        <label>First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="updFirstName" name="updFirstName">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group modal-form-group">
                                        <label>Middle Name</label>
                                        <input type="text" class="form-control" id="updMiddleName" name="updMiddleName">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group modal-form-group">
                                        <label>Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="updLastName" name="updLastName">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group modal-form-group">
                                        <label>Entity No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="updEmpEntityNo" name="updEmpEntityNo" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group modal-form-group">
                                        <label>Special Category</label>
                                        <select class="form-control" id="updSpecialCategory" name="updSpecialCategory">
                                            <option value="" hidden>Select Special Sector Classification</option>
                                            <option value="4ps Beneficiary">4ps Beneficiary</option>
                                            <option value="Solo Parent">Solo Parent</option>
                                            <option value="Person with Disability">Person with Disability (PWD)</option>
                                            <option value="Young Entrepreneur">Young Entrepreneur</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <button type="button" class="btn btn-ncims-secondary" onclick="updStepper.previous()">
                                    <i class="fas fa-arrow-left mr-1"></i> Previous
                                </button>
                                <button type="button" class="btn btn-ncims-primary" onclick="updStepper.next()">
                                    Next <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Step 3: Addresses -->
                        <div id="upd-step-address" class="content" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="modal-section-label">
                                        <i class="fas fa-building mr-2"></i>Business Address
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Region</label>
                                        <select class="form-control" id="updBusRegion" name="updBusRegion">
                                            <option value="" hidden>Select Region</option>
                                        </select>
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Province</label>
                                        <select class="form-control" id="updBusProvince" name="updBusProvince">
                                            <option value="" hidden>Select Province</option>
                                        </select>
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>City/Municipality <span class="text-danger">*</span></label>
                                        <select class="form-control" id="updBusCity" name="updBusCity">
                                            <option value="" hidden>Select City</option>
                                        </select>
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Barangay <span class="text-danger">*</span></label>
                                        <select class="form-control" id="updBusBarangay" name="updBusBarangay">
                                            <option value="" hidden>Select Barangay</option>
                                        </select>
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Street</label>
                                        <input type="text" class="form-control" id="updBusStreet" name="updBusStreet">
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Subdivision</label>
                                        <input type="text" class="form-control" id="updBusSubdivision" name="updBusSubdivision">
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>UPBLB No</label>
                                        <input type="text" class="form-control" id="updBusUpblb" name="updBusUpblb">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="modal-section-label">
                                        <i class="fas fa-home mr-2"></i>Owner Address
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Region</label>
                                        <select class="form-control" id="updEmpRegion" name="updEmpRegion">
                                            <option value="" hidden>Select Region</option>
                                        </select>
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Province</label>
                                        <select class="form-control" id="updEmpProvince" name="updEmpProvince">
                                            <option value="" hidden>Select Province</option>
                                        </select>
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>City/Municipality <span class="text-danger">*</span></label>
                                        <select class="form-control" id="updEmpCity" name="updEmpCity">
                                            <option value="" hidden>Select City</option>
                                        </select>
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Barangay <span class="text-danger">*</span></label>
                                        <select class="form-control" id="updEmpBarangay" name="updEmpBarangay">
                                            <option value="" hidden>Select Barangay</option>
                                        </select>
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Street</label>
                                        <input type="text" class="form-control" id="updEmpStreet" name="updEmpStreet">
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Subdivision</label>
                                        <input type="text" class="form-control" id="updEmpSubdivision" name="updEmpSubdivision">
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>UPBLB No</label>
                                        <input type="text" class="form-control" id="updEmpUpblb" name="updEmpUpblb">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <button type="button" class="btn btn-ncims-secondary" onclick="updStepper.previous()">
                                    <i class="fas fa-arrow-left mr-1"></i> Previous
                                </button>
                                <button type="button" class="btn btn-ncims-save" id="btnUpdateBusiness" onclick="updateBusiness()">
                                    <i class="fas fa-save mr-2"></i>Save Changes
                                </button>
                            </div>
                        </div>

                    </div><!-- /bs-stepper-content -->
                </div><!-- /bs-stepper -->
            </div><!-- /modal-body -->

        </div>
    </div>
</div>
</form>
