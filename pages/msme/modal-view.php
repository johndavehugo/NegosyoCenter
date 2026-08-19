<div class="modal fade" id="viewBusinessModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content msme-modal-content">

            <!-- Header -->
            <div class="modal-header msme-modal-header">
                <h5 class="modal-title">
                    <i class="material-icons text-primary align-middle mr-2" style="font-size:22px;">business</i><span id="viewBusinessName">Business Name</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pb-2">

                <!-- Entity reference pills -->
                <div class="d-flex flex-wrap mb-3">
                    <span class="msme-entity-chip mr-2">
                        <i class="material-icons" style="font-size:14px;vertical-align:middle;">tag</i>
                        Business: <strong id="viewEntityNo">—</strong>
                    </span>
                    <span class="msme-entity-chip">
                        <i class="material-icons" style="font-size:14px;vertical-align:middle;">tag</i>
                        Owner: <strong id="viewOwnerEntityNo">—</strong>
                    </span>
                </div>

                <!-- ── Business Info ───────────────────────────────── -->
                <div class="card msme-section-card mb-3">
                    <div class="card-header msme-section-header">
                        <i class="material-icons text-primary align-middle mr-1" style="font-size:18px;">storefront</i>Business Info
                    </div>
                    <div class="card-body pb-1">
                        <div class="row">

                            <!-- Left column -->
                            <div class="col-md-6">
                                <div class="msme-view-field">
                                    <label class="msme-label">
                                        <i class="material-icons msme-view-icon">person</i>Owner
                                    </label>
                                    <p id="viewOwner" class="msme-view-value">—</p>
                                </div>
                                <div class="msme-view-field">
                                    <label class="msme-label">
                                        <i class="material-icons msme-view-icon">payments</i>Capitalization
                                    </label>
                                    <p id="viewCapitalization" class="msme-view-value">—</p>
                                </div>
                                <div class="msme-view-field">
                                    <label class="msme-label">
                                        <i class="material-icons msme-view-icon">assignment_turned_in</i>Application Status
                                    </label>
                                    <p class="msme-view-value mb-0"><span id="viewAppStatus">—</span></p>
                                </div>
                            </div>

                            <!-- Right column -->
                            <div class="col-md-6">
                                <div class="msme-view-field">
                                    <label class="msme-label">
                                        <i class="material-icons msme-view-icon">store</i>Business Status
                                    </label>
                                    <p class="msme-view-value"><span id="viewBusStatus">—</span></p>
                                </div>
                                <div class="msme-view-field">
                                    <label class="msme-label">
                                        <i class="material-icons msme-view-icon">phone</i>Contact No.
                                    </label>
                                    <p id="viewContactNo" class="msme-view-value">—</p>
                                </div>
                                <div class="msme-view-field">
                                    <label class="msme-label">
                                        <i class="material-icons msme-view-icon">email</i>Email
                                    </label>
                                    <p id="viewEmail" class="msme-view-value mb-0">—</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ── Addresses ───────────────────────────────────── -->
                <div class="card msme-section-card mb-3">
                    <div class="card-header msme-section-header">
                        <i class="material-icons text-primary align-middle mr-1" style="font-size:18px;">location_on</i>Addresses
                    </div>
                    <div class="card-body pb-1">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="msme-view-field mb-0">
                                    <label class="msme-label">
                                        <i class="material-icons msme-view-icon">storefront</i>Business Address
                                    </label>
                                    <p id="viewBusAddress" class="msme-view-value mb-0">—</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="msme-view-field mb-0">
                                    <label class="msme-label">
                                        <i class="material-icons msme-view-icon">home</i>Owner Address
                                    </label>
                                    <p id="viewOwnerAddress" class="msme-view-value mb-0">—</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── Classification ──────────────────────────────── -->
                <div class="card msme-section-card mb-0">
                    <div class="card-header msme-section-header">
                        <i class="material-icons text-primary align-middle mr-1" style="font-size:18px;">category</i>Classification
                    </div>
                    <div class="card-body pb-1">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="msme-view-field mb-0">
                                    <label class="msme-label">
                                        <i class="material-icons msme-view-icon">inventory_2</i>Sector / Product Line
                                    </label>
                                    <p id="viewSector" class="msme-view-value mb-0">—</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="msme-view-field mb-0">
                                    <label class="msme-label">
                                        <i class="material-icons msme-view-icon">corporate_fare</i>Enterprise Class
                                    </label>
                                    <p class="msme-view-value mb-0"><span id="viewClassification">—</span></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="msme-view-field mb-0">
                                    <label class="msme-label">
                                        <i class="material-icons msme-view-icon">group</i>Special Sector
                                    </label>
                                    <p id="viewSpecialSector" class="msme-view-value mb-0">—</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="modal-footer msme-modal-footer">
                <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
