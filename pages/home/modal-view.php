<div class="modal fade" id="viewBusinessModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background-color: #343a40; color: white;">

            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="viewBusinessName">Business Name</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <!-- Left column -->
                    <div class="col-md-6">
                        <p><strong>Owner:</strong> <span id="viewOwner">—</span></p>
                        <p><strong>Application Status:</strong> <span id="viewAppStatus">—</span></p>
                        <p><strong>Capitalization:</strong> <span id="viewCapitalization">—</span></p>
                        <p><strong>Business Status:</strong> <span id="viewBusStatus">—</span></p>
                        <p><strong>Contact No.:</strong> <span id="viewContactNo">—</span></p>
                        <p><strong>Email:</strong> <span id="viewEmail">—</span></p>
                    </div>
                    <!-- Right column -->
                    <div class="col-md-6">
                        <p><strong>Business Address:</strong> <span id="viewBusAddress">—</span></p>
                        <p><strong>Owner Address:</strong> <span id="viewOwnerAddress">—</span></p>
                        <p><strong>Business Sector / Product Line:</strong> <span id="viewSector">—</span></p>
                        <p><strong>Enterprise Classification:</strong> <span id="viewClassification">—</span></p>
                        <p><strong>Special Sector Classification:</strong> <span id="viewSpecialSector">—</span></p>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-success" id="btnImportLocal" onclick="importToLocal()">
                    <i class="fas fa-download mr-1"></i>Import to Local
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

                </div>
            </div>
        </div>