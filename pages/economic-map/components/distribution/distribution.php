                    <!-- ══ TAB: MSME DISTRIBUTION MAP ═════════════════════ -->
                    <div class="tab-pane fade" id="pane-distribution" role="tabpanel"
                         aria-labelledby="tab-distribution">
                        <div class="row">
                            <!-- Filters / legend / breakdown -->
                            <div class="col-md-3 mb-3">
                                <div class="card card-raised no-hover">
                                    <div class="card-body d-flex align-items-center justify-content-between px-3 py-3 border-bottom">
                                        <span class="font-weight-bold" style="font-size:.82rem;">Distribution by Sector</span>
                                        <span class="badge badge-pill msme-badge-unknown" id="distTotal">0 MSMEs</span>
                                    </div>
                                    <div class="card-body py-3">
                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            Filter by Sector
                                        </div>
                                        <div class="sector-search mb-2">
                                            <i class="material-icons">Search</i>
                                            <input type="text" id="distSectorSearch"
                                                   placeholder="Search Sector…"
                                                   autocomplete="off">
                                        </div>
                                        <button class="sector-all-btn active" data-cat="all">
                                            <i class="material-icons">apps</i>
                                            <span>All Sectors</span>
                                        </button>
                                        <div id="distChips" class="sector-list mb-3">
                                            <!-- sector rows injected by JS -->
                                        </div>

                                        <hr class="my-2">

                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            Legend
                                        </div>
                                        <div id="distLegend" class="small"></div>

                                        <hr class="my-2">

                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            Sector totals
                                        </div>
                                        <div id="distCategoryTotals" class="small"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Map + pie chart -->
                            <div class="col-md-9 mb-3 d-flex flex-column">
                                <div class="card map-card">
                                    <div class="map-card-header">
                                        <span>
                                            <i class="material-icons align-middle mr-1"
                                               style="font-size:17px;color:#60a5fa;">pie_chart</i>
                                            MSME Distribution Map &mdash; Dominant Sector per Barangay
                                        </span>
                                        <span class="badge badge-pill msme-badge-unknown" id="distBadge">loading…</span>
                                    </div>
                                    <div id="mapDistribution"></div>
                                </div>

                                <!-- ── Sector pie chart (exactly below the map, stretches to align with sector totals) ── -->
                                <div class="card card-raised no-hover mt-3 mb-0 flex-grow-1 d-flex flex-column">
                                    <div class="card-body d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
                                        <div>
                                            <span class="font-weight-bold">Sector Share</span>
                                            <small class="text-muted ml-2">All Barangays Combined</small>
                                        </div>
                                        <span class="badge badge-pill msme-badge-unknown" id="distPieBadge">loading…</span>
                                    </div>
                                    <div class="card-body px-4 py-3 flex-grow-1 d-flex flex-column justify-content-center">
                                        <div class="row align-items-center flex-grow-1">
                                            <!-- Doughnut -->
                                            <div class="col-md-5 d-flex justify-content-center mb-4 mb-md-0 pr-md-1">
                                                <div style="position:relative;width:340px;max-width:100%;height:500px;">
                                                    <canvas id="distPieChart"></canvas>
                                                </div>
                                            </div>
                                            <!-- Legend list -->
                                            <div class="col-md-7 pl-md-1 dist-pie-legend-col">
                                                <div id="distPieLegend"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="emap-note mb-3">
                            <i class="material-icons">Info</i>
                            Each circle is coloured by the dominant MSME sector in the barangay; click a circle to see
                            the full sector breakdown. Use the sector filter to focus on a specific industry.
                            Business counts come from the <b>SCIMS Registry</b>.
                        </div>
                    </div>
