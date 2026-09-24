                    <!-- ══ TAB: ECONOMIC RISK MAP ════════════════════════ -->
                    <div class="tab-pane fade" id="pane-risk" role="tabpanel"
                         aria-labelledby="tab-risk">
                        <div class="row">
                            <!-- Summary / formula sidebar -->
                            <div class="col-md-3 mb-3">
                                <div class="card card-raised no-hover stat-pill mb-3" style="border-left-color:#dc3545!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="riskCriticalCount">—</div>
                                        <div class="label">Critical Risk Areas</div>
                                    </div>
                                </div>
                                <div class="card card-raised no-hover stat-pill mb-3" style="border-left-color:#fd7e14!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="riskHighCount">—</div>
                                        <div class="label">High Risk Areas</div>
                                    </div>
                                </div>
                                <div class="card card-raised no-hover stat-pill mb-3" style="border-left-color:#6c757d!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="riskTotalAreas">—</div>
                                        <div class="label">Barangays Assessed</div>
                                    </div>
                                </div>

                                <div class="card card-raised no-hover mb-3">
                                    <div class="card-body py-3">
                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            Calamity event
                                        </div>
                                        <select class="form-control form-control-sm" id="riskCalamity">
                                            <option value="" disabled selected>Select Calamity</option>
                                        </select>
                                        <div class="small text-muted mt-1">
                                            Risk is recomputed using the historical damage of the selected event (from Calamity Monitoring).
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-raised no-hover">
                                    <div class="card-body py-3">
                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            How it&rsquo;s calculated
                                        </div>
                                        <p class="small mb-2" style="line-height:1.5;">
                                            <b>Economic Risk = Business Exposure &times; Hazard Level &times; Historical Damage</b>
                                        </p>
                                        <ul class="small text-muted mb-0 pl-3" style="line-height:1.6;">
                                            <li><b>Exposure</b> &mdash; Number of MSMEs in the Area (SCIMS Registry)</li>
                                            <li><b>Hazard</b> &mdash; LGU-Assessed Flood/Hazard Rating</li>
                                            <li><b>Damage</b> &mdash; Prior Calamity Losses Recorded in Calamity Monitoring</li>
                                        </ul>
                                        <hr class="my-2">
                                        <div class="small text-muted">
                                            Areas with high MSME concentration in flood-prone zones with a history of
                                            damage score highest &mdash; pinpointing where a disaster would cause the
                                            greatest economic disruption.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Map -->
                            <div class="col-md-9 mb-3">
                                <div class="card map-card">
                                    <div class="map-card-header">
                                        <span>
                                            <i class="material-icons align-middle mr-1"
                                               style="font-size:17px;color:#f87171;">shield</i>
                                            Economic Risk Map &mdash; linked to Calamity Monitoring
                                        </span>
                                        <span class="badge badge-pill msme-badge-unknown" id="riskBadge">loading…</span>
                                    </div>
                                    <div id="mapRisk"></div>
                                </div>
                            </div>
                        </div>
                        <div class="emap-note mb-3">
                            <i class="material-icons">info</i>
                            Risk levels:
                            <span class="legend-dot" style="background:#dc3545;"></span>Critical
                            <span class="legend-dot" style="background:#fd7e14;"></span>High
                            <span class="legend-dot" style="background:#ffc107;"></span>Moderate
                            <span class="legend-dot" style="background:#28a745;"></span>Low
                            &mdash; Click a Circle for the Full Risk Breakdown.
                        </div>
                    </div>
