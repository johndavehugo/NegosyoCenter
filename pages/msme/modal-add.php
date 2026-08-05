<?php

require_once __DIR__ . '/../../global/industries.php';

?>

<form id="addBusinessForm" method="POST">
    <div class="modal fade" id="addBusinessModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" style="background-color: #343a40; color: white;">

                <div class="modal-header border-secondary">
                    <h5 class="modal-title"><i class="fas fa-store mr-2 text-info"></i>Add Business</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
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
                            <div id="step-business" class="content" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Business Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="addBusinessName"
                                                name="addBusinessName">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Entity No <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="addBusEntityNo"
                                                name="addBusEntityNo">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Capitalization</label>
                                            <input type="number" step="0.01" class="form-control" id="addCapitalization"
                                                name="addCapitalization">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Line of Industry</label>
                                            <select class="form-control" id="addIndustry" name="addIndustry">
                                                <option value="" hidden>Select Industry</option>
                                                <?php foreach ($industries as $code => $industry): ?>
                                                    <option value="<?= $industry ?>"><?= $code ?> - <?= $industry ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Contact No</label>
                                            <input type="text" class="form-control" id="addContactNo"
                                                name="addContactNo">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" id="addEmail" name="addEmail">
                                        </div>
                                    </div>
                                </div>

                                <div class="card bg-transparent border-secondary mb-3">
                                    <div class="card-header border-secondary">
                                        <i class="fas fa-map-marker-alt text-info mr-2"></i>Business Address
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Region</label>
                                                    <select class="form-control" id="addBusRegion" name="addBusRegion">
                                                        <option value="" hidden>Select Region</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Province</label>
                                                    <select class="form-control" id="addBusProvince"
                                                        name="addBusProvince">
                                                        <option value="" hidden>Select Province</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>City/Municipality</label>
                                                    <select class="form-control" id="addBusCity" name="addBusCity">
                                                        <option value="" hidden>Select City</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Barangay</label>
                                                    <select class="form-control" id="addBusBarangay"
                                                        name="addBusBarangay">
                                                        <option value="" hidden>Select Barangay</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group"><label>Street</label><input type="text"
                                                        class="form-control" id="addBusStreet" name="addBusStreet">
                                                </div>
                                                <div class="form-group"><label>Subdivision</label><input type="text"
                                                        class="form-control" id="addBusSubdivision"
                                                        name="addBusSubdivision"></div>
                                                <div class="form-group"><label>UPBLB No</label><input type="text"
                                                        class="form-control" id="addBusUpblb" name="addBusUpblb"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <span></span>
                                    <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                                </div>
                            </div>

                            <div id="step-owner" class="content" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Search Owner</label>
                                            <select class="form-control" id="ownerSearch"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-outline-info btn-block"
                                                onclick="openOwnerModal()">
                                                <i class="fas fa-user-plus mr-1"></i>Add New Owner
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="card bg-transparent border-secondary mb-3" id="ownerSummary"
                                    style="display: none;">
                                    <div class="card-header border-secondary">
                                        <i class="fas fa-user text-info mr-2"></i>Selected Owner
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6"> <!-- LEFT: identity -->
                                                <p class="mb-1"><strong>Name:</strong> <span
                                                        id="ownerSummaryName">—</span></p>
                                                <p class="mb-1"><strong>Entity No:</strong> <span
                                                        id="ownerSummaryEntity">—</span></p>
                                                <p class="mb-0"><strong>Special Category:</strong> <span
                                                        id="ownerSummaryCategory">—</span></p>
                                            </div>
                                            <div class="col-md-6"> <!-- RIGHT: address -->
                                                <p class="mb-1"><strong>Region:</strong> <span
                                                        id="ownerSummaryRegion">—</span></p>
                                                <p class="mb-1"><strong>Province:</strong> <span
                                                        id="ownerSummaryProvince">—</span></p>
                                                <p class="mb-1"><strong>City:</strong> <span
                                                        id="ownerSummaryCity">—</span></p>
                                                <p class="mb-1"><strong>Barangay:</strong> <span
                                                        id="ownerSummaryBarangay">—</span></p>
                                                <p class="mb-1"><strong>Street:</strong> <span
                                                        id="ownerSummaryStreet">—</span></p>
                                                <p class="mb-1"><strong>Subdivision:</strong> <span
                                                        id="ownerSummarySubdivision">—</span></p>
                                                <p class="mb-0"><strong>UPBLB No:</strong> <span
                                                        id="ownerSummaryUpblb">—</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="stepper.previous()">Previous</button>
                                    <button type="button" class="btn btn-success" id="btnSaveBusiness"
                                        onclick="addBusiness()">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</form>