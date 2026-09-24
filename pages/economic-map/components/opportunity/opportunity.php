                    <!-- ══ TAB: ECONOMIC OPPORTUNITY MAP ═════════════════ -->
                    <div class="tab-pane fade" id="pane-opportunity" role="tabpanel"
                         aria-labelledby="tab-opportunity">
                        <div class="row">
                            <!-- Investment guidance sidebar -->
                            <div class="col-md-3 mb-3">
                                <div class="card card-raised no-hover stat-pill mb-3" style="border-left-color:#198754!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="oppHighCount">—</div>
                                        <div class="label">High / Very High Areas</div>
                                    </div>
                                </div>
                                <div class="card card-raised no-hover stat-pill mb-3" style="border-left-color:#007bff!important;">
                                    <div class="card-body py-3">
                                        <div class="value" id="oppTotalAreas">—</div>
                                        <div class="label">Barangays Assessed</div>
                                    </div>
                                </div>

                                <div class="card card-raised no-hover">
                                    <div class="card-body d-flex align-items-center justify-content-between px-3 py-3 border-bottom">
                                        <span class="font-weight-bold" style="font-size:.82rem;">Where to Invest &amp; Support</span>
                                    </div>
                                    <div class="card-body py-3">
                                        <div id="oppHighlights"></div>

                                        <hr class="my-2">

                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            How it&rsquo;s calculated
                                        </div>
                                        <ul class="small text-muted mb-0 pl-3" style="line-height:1.6;">
                                            <li><b>Commercial Potential</b> &mdash; MSME Concentration (SCIMS registry)</li>
                                            <li><b>Growth Momentum</b> &mdash; New Registrations</li>
                                            <li><b>Tourism Potential</b> &mdash; Coastal / Island Assets</li>
                                            <li><b>Agriculture Potential</b> &mdash; Land &amp; Production Capacity</li>
                                            <li><b>Livelihood Gap</b> &mdash; High Population, Few Businesses</li>
                                            <li><b>Infrastructure Gap</b> &mdash; Businesses, Limited Infrastructure</li>
                                            <li><b>Sector Diversity Gap</b> &mdash; Underrepresented Industries</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Map -->
                            <div class="col-md-9 mb-3">
                                <div class="card map-card">
                                    <div class="map-card-header">
                                        <span>
                                            <i class="material-icons align-middle mr-1"
                                               style="font-size:17px;color:#4ade80;">lightbulb</i>
                                            Economic Opportunity Map &mdash; investment &amp; development guidance
                                        </span>
                                        <span class="badge badge-pill msme-badge-unknown" id="oppBadge">loading…</span>
                                    </div>
                                    <div id="mapOpportunity"></div>
                                </div>
                            </div>
                        </div>
                        <div class="emap-note mb-3">
                            <i class="material-icons">info</i>
                            Opportunity Levels:
                            <span class="legend-dot" style="background:#198754;"></span>Very High
                            <span class="legend-dot" style="background:#28a745;"></span>High
                            <span class="legend-dot" style="background:#ffc107;"></span>Moderate
                            <span class="legend-dot" style="background:#6c757d;"></span>Low
                            &mdash; Click a Circle to See which Opportunity Drivers Apply.
                        </div>
                    </div>
