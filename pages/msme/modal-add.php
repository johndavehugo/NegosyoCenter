<?php

require_once __DIR__ . '/../../global/industries.php';

?>

<form id="addBusinessForm" method="POST">
    <div class="modal fade" id="addBusinessModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content msme-modal-content">

                <div class="modal-header msme-modal-header">
                    <h5 class="modal-title">
                        <i class="material-icons text-primary align-middle mr-2"
                            style="font-size:22px;">storefront</i>Add Business
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="bs-stepper">
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
                        </div>

                        <div class="bs-stepper-content">
                            <!-- Step 1: Business Info -->
                            <div id="step-business" class="content" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="msme-label">Search Business</label>
                                            <select class="form-control msme-input" id="scimsBusSearch"></select>
                                            <small class="form-text text-muted">Pick a business to prefill the
                                                details.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">Business Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control msme-input" id="addBusinessName"
                                                name="addBusinessName">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">
                                                Entity No <span class="text-danger">*</span>
                                                <span class="msme-locked-badge ml-1">
                                                    <i class="material-icons"
                                                        style="font-size:11px;vertical-align:middle;">lock</i>
                                                    Auto-filled
                                                </span>
                                            </label>
                                            <input type="text" class="form-control msme-input msme-input-locked"
                                                id="addBusEntityNo" name="addBusEntityNo" readonly tabindex="-1"
                                                title="Auto-filled from SCIMS — cannot be edited">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">Capitalization</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text msme-input-prefix">₱</span>
                                                </div>
                                                <input type="text" inputmode="decimal" class="form-control msme-input"
                                                    id="addCapitalization" name="addCapitalization">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">Line of Industry</label>
                                            <select class="form-control msme-input" id="addIndustry" name="addIndustry">
                                                <option value="" hidden>Select Industry</option>
                                                <?php foreach ($industries as $industry): ?>
                                                    <option value="<?= strtoupper($industry) ?>">
                                                        <?= strtoupper($industry) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">Contact No</label>
                                            <input type="text" class="form-control msme-input" id="addContactNo"
                                                name="addContactNo">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">Email</label>
                                            <input type="email" class="form-control msme-input" id="addEmail"
                                                name="addEmail">
                                        </div>
                                    </div>
                                </div>

                                <div class="card msme-section-card mb-3">
                                    <div class="card-header msme-section-header">
                                        <i class="material-icons text-primary align-middle mr-1"
                                            style="font-size:18px;">location_on</i>Business Address
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="msme-label">Region</label>
                                                    <select class="form-control msme-input" id="addBusRegion"
                                                        name="addBusRegion">
                                                        <option value="" hidden>Select Region</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="msme-label">Province</label>
                                                    <select class="form-control msme-input" id="addBusProvince"
                                                        name="addBusProvince">
                                                        <option value="" hidden>Select Province</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="msme-label">City/Municipality</label>
                                                    <select class="form-control msme-input" id="addBusCity"
                                                        name="addBusCity">
                                                        <option value="" hidden>Select City</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="msme-label">Barangay</label>
                                                    <select class="form-control msme-input" id="addBusBarangay"
                                                        name="addBusBarangay">
                                                        <option value="" hidden>Select Barangay</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="msme-label">Street</label>
                                                    <input type="text" class="form-control msme-input" id="addBusStreet"
                                                        name="addBusStreet">
                                                </div>
                                                <div class="form-group">
                                                    <label class="msme-label">Subdivision</label>
                                                    <input type="text" class="form-control msme-input"
                                                        id="addBusSubdivision" name="addBusSubdivision">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label class="msme-label">UPBLB No</label>
                                                    <input type="text" class="form-control msme-input" id="addBusUpblb"
                                                        name="addBusUpblb">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <span></span>
                                    <button type="button" class="btn btn-raised-primary d-flex align-items-center"
                                        onclick="stepper.next()">
                                        Next<i class="material-icons ml-1" style="font-size:18px;">arrow_forward</i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Owner Info -->
                            <div id="step-owner" class="content" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">Owner Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control msme-input" id="addOwnerName"
                                                name="addOwnerName">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">
                                                Entity No <span class="text-danger">*</span>
                                                <span class="msme-locked-badge ml-1">
                                                    <i class="material-icons"
                                                        style="font-size:11px;vertical-align:middle;">lock</i>
                                                    Auto-filled
                                                </span>
                                            </label>
                                            <input type="text" class="form-control msme-input msme-input-locked"
                                                id="addEmpEntityNo" name="addEmpEntityNo" readonly tabindex="-1"
                                                title="Auto-filled from SCIMS — cannot be edited">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="msme-label">Special Category</label>
                                            <select class="form-control msme-input" id="addSpecialCategory"
                                                name="addSpecialCategory">
                                                <option value="" hidden>Select Special Sector Classification</option>
                                                <option value="None">None</option>
                                                <option value="4ps Beneficiary">4ps Beneficiary</option>
                                                <option value="Solo Parent">Solo Parent</option>
                                                <option value="Person with Disability">Person with Disability (PWD)
                                                </option>
                                                <option value="Young Entrepreneur">Young Entrepreneur</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="card msme-section-card mb-3">
                                    <div class="card-header msme-section-header">
                                        <i class="material-icons text-primary align-middle mr-1"
                                            style="font-size:18px;">location_on</i>Owner Address
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="msme-label">Region</label>
                                                    <select class="form-control msme-input" id="addEmpRegion"
                                                        name="addEmpRegion">
                                                        <option value="" hidden>Select Region</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="msme-label">Province</label>
                                                    <select class="form-control msme-input" id="addEmpProvince"
                                                        name="addEmpProvince">
                                                        <option value="" hidden>Select Province</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="msme-label">City/Municipality</label>
                                                    <select class="form-control msme-input" id="addEmpCity"
                                                        name="addEmpCity">
                                                        <option value="" hidden>Select City</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="msme-label">Barangay</label>
                                                    <select class="form-control msme-input" id="addEmpBarangay"
                                                        name="addEmpBarangay">
                                                        <option value="" hidden>Select Barangay</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="msme-label">Street</label>
                                                    <input type="text" class="form-control msme-input" id="addEmpStreet"
                                                        name="addEmpStreet">
                                                </div>
                                                <div class="form-group">
                                                    <label class="msme-label">Subdivision</label>
                                                    <input type="text" class="form-control msme-input"
                                                        id="addEmpSubdivision" name="addEmpSubdivision">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label class="msme-label">UPBLB No</label>
                                                    <input type="text" class="form-control msme-input" id="addEmpUpblb"
                                                        name="addEmpUpblb">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-text-secondary d-flex align-items-center"
                                        onclick="stepper.previous()">
                                        <i class="material-icons mr-1" style="font-size:18px;">arrow_back</i>Previous
                                    </button>
                                    <button type="button" class="btn btn-raised-success d-flex align-items-center"
                                        id="btnSaveBusiness" onclick="addBusiness()">
                                        <i class="material-icons mr-1" style="font-size:18px;">save</i>Save
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