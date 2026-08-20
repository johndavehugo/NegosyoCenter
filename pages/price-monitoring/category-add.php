<form id="addCategoryForm" method="POST">
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content msme-modal-content">

                <div class="modal-header msme-modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="material-icons text-primary mr-2" style="font-size:22px;">category</i>
                        Add Category
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="msme-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control msme-input"
                                       id="addCategoryName" name="name"
                                       placeholder="Enter category name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label class="msme-label">Agency <span class="text-danger">*</span></label>
                                <select class="form-control msme-input"
                                        id="addAgencyType" name="agency_id">
                                    <option value="" hidden>Select Agency</option>
                                    <option value="1">Department of Trade Industry (DTI)</option>
                                    <option value="2">Department of Agriculture (DA)</option>
                                    <option value="3">Department of Energy (DOE)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer msme-modal-footer">
                    <button type="button" class="btn btn-text-secondary"
                            data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-raised-success d-flex align-items-center"
                            id="btnSaveCategory" onclick="addCategory()">
                        <i class="material-icons mr-1" style="font-size:18px;">save</i>Save
                    </button>
                </div>

            </div>
        </div>
    </div>
</form>
