<form method="POST" id="addOwnerForm">
<div class="modal fade" id="ownerAddModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color: #343a40; color: white;">

            <div class="modal-header border-secondary">
                <h5 class="modal-title"><i class="fas fa-user-plus mr-2 text-info"></i>Add Owner</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label><i class="fas fa-cloud-download-alt mr-1 text-info"></i>Import from SCIMS</label>
                    <select class="form-control" id="scimsOwnerSearch"></select>
                    <small class="form-text text-muted">Search SCIMS owner, then review/edit before saving.</small>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="addFirstName" name="addFirstName">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Middle Name</label>
                            <input type="text" class="form-control" id="addMiddleName" name="addMiddleName">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="addLastName" name="addLastName">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Entity No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="addEmpEntityNo" name="addEmpEntityNo">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Special Category</label>
                            <select class="form-control" id="addSpecialCategory" name="addSpecialCategory">
                                <option value="" hidden disabled selected>Select Special Sector Classification</option>
                                <option value="None">None</option>
                                <option value="4ps Beneficiary">4ps Beneficiary</option>
                                <option value="Solo Parent">Solo Parent</option>
                                <option value="Person with Disability">Person with Disability (PWD)</option>
                                <option value="Young Entrepreneur">Young Entrepreneur</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card bg-transparent border-secondary mb-3">
                    <div class="card-header border-secondary">
                        <i class="fas fa-map-marker-alt text-info mr-2"></i>Owner Address
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Region</label>
                                    <select class="form-control" id="addEmpRegion" name="addEmpRegion">
                                        <option value="" hidden>Select Region</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Province</label>
                                    <select class="form-control" id="addEmpProvince" name="addEmpProvince">
                                        <option value="" hidden>Select Province</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>City/Municipality</label>
                                    <select class="form-control" id="addEmpCity" name="addEmpCity">
                                        <option value="" hidden>Select City</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Barangay</label>
                                    <select class="form-control" id="addEmpBarangay" name="addEmpBarangay">
                                        <option value="" hidden>Select Barangay</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"><label>Street</label><input type="text" class="form-control" id="addEmpStreet" name="addEmpStreet"></div>
                                <div class="form-group"><label>Subdivision</label><input type="text" class="form-control" id="addEmpSubdivision" name="addEmpSubdivision"></div>
                                <div class="form-group"><label>UPBLB No</label><input type="text" class="form-control" id="addEmpUpblb" name="addEmpUpblb"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="addEmployer()">Save Owner</button>
            </div>

        </div>
    </div>
</div>
</form>