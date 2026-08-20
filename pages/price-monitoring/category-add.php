<form id="addCategoryForm" method="POST">
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="background-color: #343a40; color: white;">

                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="addCategoryName" name="name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label> Agency <span class="text-danger">*</span></label>
                                <select class="form-control" id="addAgencyType" name="agency_id">
                                    <option value="" hidden>Select Type</option>
                                    <option value="1">Department of Trade Industry (DTI)</option>
                                    <option value="2">Department of Agriculture (DA)</option>
                                    <option value="3">Department of Energy (DOE)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    

                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-success" id="btnSaveCategory"
                            onclick="addCategory()">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>


                </div>
            </div>
        </div>
</form>