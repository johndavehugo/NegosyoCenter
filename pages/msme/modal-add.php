<?php
require_once __DIR__ . '/../../global/industries.php';
?>

<form id="addBusinessForm" method="POST">
<div class="modal fade" id="addBusinessModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content modal-ncims">

            <!-- Header -->
            <div class="modal-header modal-ncims-header">
                <div class="d-flex align-items-center">
                    <div class="modal-icon-circle bg-blue mr-3">
                        <i class="fas fa-plus"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0">Add New Business</h5>
                        <small style="color:rgba(255,255,255,.65);">Complete all three steps to register a new MSME</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <!-- Body -->
            <div class="modal-body modal-ncims-body">
                <div class="bs-stepper modal-stepper">

                    <!-- Stepper header -->
                    <div class="bs-stepper-header" role="tablist">
                        <div class="step" data-target="#step-business">
                            <button type="button" class="step-trigger" role="tab">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">Business Info</span>
                            </button>
                        </div>
                        <div class="bs-stepper-line"></div>
                        <div class="step" data-target="#step-owner">
                            <button type="button" class="step-trigger" role="tab">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Owner Info</span>
                            </button>
                        </div>
                        <div class="bs-stepper-line"></div>
                        <div class="step" data-target="#step-address">
                            <button type="button" class="step-trigger" role="tab">
                                <span class="bs-stepper-circle">3</span>
                                <span class="bs-stepper-label">Addresses</span>
                            </button>
                        </div>
                    </div>

                    <div class="bs-stepper-content">

                        <!-- ── Step 1: Business Info ── -->
                        <div id="step-business" class="content" role="tabpanel">
                            <div class="modal-section-label">
                                <i class="fas fa-store mr-2"></i>Business Information
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group modal-form-group">
                                        <label>Business Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="addBusinessName" name="addBusinessName"
                                               placeholder="Enter business name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group modal-form-group">
                                        <label>Entity No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="addBusEntityNo" name="addBusEntityNo"
                                               placeholder="e.g. BUS-2026-0001">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group modal-form-group">
                                        <label>Capitalization</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text modal-input-prefix">₱</span>
                                            </div>
                                            <input type="number" step="0.01" class="form-control" id="addCapitalization"
                                                   name="addCapitalization" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group modal-form-group">
                                        <label>Line of Industry</label>
                                        <select class="form-control" id="addIndustry" name="addIndustry">
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
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text modal-input-prefix">
                                                    <i class="fas fa-phone fa-xs"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="addContactNo"
                                                   name="addContactNo" placeholder="09XX-XXX-XXXX">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group modal-form-group">
                                        <label>Email</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text modal-input-prefix">
                                                    <i class="fas fa-envelope fa-xs"></i>
                                                </span>
                                            </div>
                                            <input type="email" class="form-control" id="addEmail"
                                                   name="addEmail" placeholder="email@example.com">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-2">
                                <button type="button" class="btn btn-ncims-primary" onclick="stepper.next()">
                                    Next <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- ── Step 2: Owner Info ── -->
                        <div id="step-owner" class="content" role="tabpanel">
                            <div class="modal-section-label">
                                <i class="fas fa-user mr-2"></i>Owner Information
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group modal-form-group">
                                        <label>First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="addFirstName"
                                               name="addFirstName" placeholder="First name">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group modal-form-group">
                                        <label>Middle Name</label>
                                        <input type="text" class="form-control" id="addMiddleName"
                                               name="addMiddleName" placeholder="Middle name">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group modal-form-group">
                                        <label>Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="addLastName"
                                               name="addLastName" placeholder="Last name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group modal-form-group">
                                        <label>Entity No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="addEmpEntityNo"
                                               name="addEmpEntityNo" placeholder="Owner entity number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group modal-form-group">
                                        <label>Special Category</label>
                                        <select class="form-control" id="addSpecialCategory" name="addSpecialCategory">
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
                                <button type="button" class="btn btn-ncims-secondary" onclick="stepper.previous()">
                                    <i class="fas fa-arrow-left mr-1"></i> Previous
                                </button>
                                <button type="button" class="btn btn-ncims-primary" onclick="stepper.next()">
                                    Next <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- ── Step 3: Addresses ── -->
                        <div id="step-address" class="content" role="tabpanel">
                            <div class="row">
                                <!-- Business Address -->
                                <div class="col-md-6">
                                    <div class="modal-section-label">
                                        <i class="fas fa-building mr-2"></i>Business Address
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Region</label>
                                        <select class="form-control" id="addBusRegion" name="addBusRegion">
                                            <option value="" hidden>Select Region</option>
                                        </select>
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Province</label>
                                        <select class="form-control" id="addBusProvince" name="addBusProvince">
                                            <option value="" hidden>Select Province</option>
                                        </select>
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>City/Municipality <span class="text-danger">*</span></label>
                                        <select class="form-control" id="addBusCity" name="addBusCity">
                                            <option value="" hidden>Select City</option>
                                        </select>
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Barangay <span class="text-danger">*</span></label>
                                        <select class="form-control" id="addBusBarangay" name="addBusBarangay">
                                            <option value="" hidden>Select Barangay</option>
                                        </select>
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Street</label>
                                        <input type="text" class="form-control" id="addBusStreet"
                                               name="addBusStreet" placeholder="Street name">
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Subdivision</label>
                                        <input type="text" class="form-control" id="addBusSubdivision"
                                               name="addBusSubdivision" placeholder="Subdivision">
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>UPBLB No</label>
                                        <input type="text" class="form-control" id="addBusUpblb"
                                               name="addBusUpblb" placeholder="UPBLB number">
                                    </div>
                                </div>

                                <!-- Owner Address -->
                                <div class="col-md-6">
                                    <div class="modal-section-label">
                                        <i class="fas fa-home mr-2"></i>Owner Address
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Region</label>
                                        <select class="form-control" id="addEmpRegion" name="addEmpRegion">
                                            <option value="" hidden>Select Region</option>
                                        </select>
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Province</label>
                                        <select class="form-control" id="addEmpProvince" name="addEmpProvince">
                                            <option value="" hidden>Select Province</option>
                                        </select>
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>City/Municipality <span class="text-danger">*</span></label>
                                        <select class="form-control" id="addEmpCity" name="addEmpCity">
                                            <option value="" hidden>Select City</option>
                                        </select>
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Barangay <span class="text-danger">*</span></label>
                                        <select class="form-control" id="addEmpBarangay" name="addEmpBarangay">
                                            <option value="" hidden>Select Barangay</option>
                                        </select>
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Street</label>
                                        <input type="text" class="form-control" id="addEmpStreet"
                                               name="addEmpStreet" placeholder="Street name">
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>Subdivision</label>
                                        <input type="text" class="form-control" id="addEmpSubdivision"
                                               name="addEmpSubdivision" placeholder="Subdivision">
                                    </div>
                                    <div class="form-group modal-form-group">
                                        <label>UPBLB No</label>
                                        <input type="text" class="form-control" id="addEmpUpblb"
                                               name="addEmpUpblb" placeholder="UPBLB number">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-2">
                                <button type="button" class="btn btn-ncims-secondary" onclick="stepper.previous()">
                                    <i class="fas fa-arrow-left mr-1"></i> Previous
                                </button>
                                <button type="button" class="btn btn-ncims-save" id="btnSaveBusiness" onclick="addBusiness()">
                                    <i class="fas fa-save mr-2"></i>Save Business
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
