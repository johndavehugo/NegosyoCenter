<form id="updateCategoryForm" method="POST">
<div class="modal fade" id="updateCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="background-color: #343a40; color: white;">

            <div class="modal-header border-secondary">
                <h5 class="modal-title">Update Category</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="updateCategoryId" name="updateCategoryId">
                
                <div class="form-group">
                    <label>Category Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="updateCategoryName" name="updateCategoryName" placeholder="Enter category name" required>
                </div>

                <div class="form-group">
                    <label>Agency <span class="text-danger">*</span></label>
                    <select class="form-control" id="updateCategoryAgency" name="updateCategoryAgency" required>
                        <option value="" hidden>Select Agency</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="btnUpdateCategory" onclick="updateCategory()">Save Changes</button>
            </div>

        </div>
    </div>
</div>
</form>