<form id="updateCategoryForm" method="POST">
    <div class="modal fade" id="updateCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content msme-modal-content">

                <div class="modal-header msme-modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="material-icons text-primary mr-2" style="font-size:22px;">edit</i>
                        Update Category
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="updateCategoryId" name="updateCategoryId">

                    <div class="form-group">
                        <label class="msme-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control msme-input"
                               id="updateCategoryName" name="updateCategoryName"
                               placeholder="Enter category name" required>
                    </div>

                    <div class="form-group mb-0">
                        <label class="msme-label">Agency <span class="text-danger">*</span></label>
                        <select class="form-control msme-input"
                                id="updateCategoryAgency" name="updateCategoryAgency" required>
                            <option value="" hidden>Select Agency</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer msme-modal-footer">
                    <button type="button" class="btn btn-text-secondary"
                            data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-raised-primary d-flex align-items-center"
                            id="btnUpdateCategory" onclick="updateCategory()">
                        <i class="material-icons mr-1" style="font-size:18px;">save</i>Save Changes
                    </button>
                </div>

            </div>
        </div>
    </div>
</form>
