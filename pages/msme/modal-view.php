<div class="modal fade" id="viewBusinessModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content modal-ncims">

            <!-- Header -->
            <div class="modal-header modal-ncims-header">
                <div class="d-flex align-items-center">
                    <div class="modal-icon-circle bg-blue mr-3">
                        <i class="fas fa-store"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0" id="viewBusinessName">Business Name</h5>
                        <small style="color:rgba(255,255,255,.65);">Business Record Details</small>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <!-- Body -->
            <div class="modal-body modal-ncims-body">
                <div class="row">

                    <!-- Left column -->
                    <div class="col-md-6">
                        <div class="modal-info-item">
                            <span class="modal-info-icon bg-blue"><i class="fas fa-user"></i></span>
                            <div>
                                <div class="modal-info-label">Owner</div>
                                <div class="modal-info-value" id="viewOwner">—</div>
                            </div>
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-icon bg-purple"><i class="fas fa-file-alt"></i></span>
                            <div>
                                <div class="modal-info-label">Application Status</div>
                                <div class="modal-info-value" id="viewAppStatus">—</div>
                            </div>
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-icon bg-orange"><i class="fas fa-coins"></i></span>
                            <div>
                                <div class="modal-info-label">Capitalization</div>
                                <div class="modal-info-value" id="viewCapitalization">—</div>
                            </div>
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-icon bg-green"><i class="fas fa-circle"></i></span>
                            <div>
                                <div class="modal-info-label">Business Status</div>
                                <div class="modal-info-value" id="viewBusStatus">—</div>
                            </div>
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-icon" style="background:#17a2b8;"><i class="fas fa-phone"></i></span>
                            <div>
                                <div class="modal-info-label">Contact No.</div>
                                <div class="modal-info-value" id="viewContactNo">—</div>
                            </div>
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-icon" style="background:#6f42c1;"><i class="fas fa-envelope"></i></span>
                            <div>
                                <div class="modal-info-label">Email</div>
                                <div class="modal-info-value" id="viewEmail">—</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right column -->
                    <div class="col-md-6">
                        <div class="modal-info-item">
                            <span class="modal-info-icon bg-blue"><i class="fas fa-map-marker-alt"></i></span>
                            <div>
                                <div class="modal-info-label">Business Address</div>
                                <div class="modal-info-value" id="viewBusAddress">—</div>
                            </div>
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-icon bg-orange"><i class="fas fa-home"></i></span>
                            <div>
                                <div class="modal-info-label">Owner Address</div>
                                <div class="modal-info-value" id="viewOwnerAddress">—</div>
                            </div>
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-icon bg-green"><i class="fas fa-industry"></i></span>
                            <div>
                                <div class="modal-info-label">Business Sector / Product Line</div>
                                <div class="modal-info-value" id="viewSector">—</div>
                            </div>
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-icon bg-blue"><i class="fas fa-tags"></i></span>
                            <div>
                                <div class="modal-info-label">Enterprise Classification</div>
                                <div class="modal-info-value" id="viewClassification">—</div>
                            </div>
                        </div>
                        <div class="modal-info-item">
                            <span class="modal-info-icon bg-purple"><i class="fas fa-star"></i></span>
                            <div>
                                <div class="modal-info-label">Special Sector Classification</div>
                                <div class="modal-info-value" id="viewSpecialSector">—</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer modal-ncims-footer">
                <button type="button" class="btn btn-ncims-success" id="btnImportLocal" onclick="importToLocal()">
                    <i class="fas fa-download mr-2"></i>Import to Local
                </button>
                <button type="button" class="btn btn-ncims-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Close
                </button>
            </div>

        </div>
    </div>
</div>
