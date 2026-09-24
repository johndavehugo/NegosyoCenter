                    <!-- ══ TAB: ECONOMIC HOTSPOT MAP ══════════════════════ -->
                    <div class="tab-pane fade show active" id="pane-hotspot" role="tabpanel"
                         aria-labelledby="tab-hotspot">
                        <div class="row">
                            <!-- Stats sidebar -->
                            <div class="col-md-3 mb-3">
                                <div class="card card-raised no-hover stat-pill mb-3">
                                    <div class="card-body py-3">
                                        <div class="label">Registered MSMEs</div>
                                        <div class="value" id="hotspotTotal">—</div>
                                    </div>
                                </div>
                                <div class="card card-raised no-hover stat-pill mb-3" style="border-left-color:#dc3545!important;">
                                    <div class="card-body py-3">
                                        <div class="label">Top Barangay</div>
                                        <div class="value" id="hotspotTopBrgy">—</div>
                                    </div>
                                </div>
                                <div class="card card-raised no-hover stat-pill mb-3" style="border-left-color:#28a745!important;">
                                    <div class="card-body py-3">
                                        <div class="label">Barangays with Businesses</div>
                                        <div class="value" id="hotspotWithBusiness">—</div>
                                    </div>
                                </div>
                                <div class="card card-raised no-hover mb-3">
                                    <div class="card-body d-flex align-items-center justify-content-between px-3 py-3 border-bottom">
                                        <span class="font-weight-bold" style="font-size:.82rem;">Barangay Ranking</span>
                                        <span class="badge badge-pill msme-badge-unknown" id="hotspotRankBadge">Top to Lowest</span>
                                    </div>
                                    <div class="card-body py-3">
                                        <div class="mb-2" style="font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;">
                                            Top Barangay to Lowest
                                        </div>
                                        <div class="modern-dd" id="hotspotRankDD">
                                            <button type="button" class="modern-dd-btn" id="hotspotRankBtn">
                                                <i class="material-icons">emoji_events</i>
                                                <span id="hotspotRankBtnText">Loading ranking…</span>
                                                <i class="material-icons modern-dd-chevron">expand_more</i>
                                            </button>
                                            <div class="modern-dd-panel d-none" id="hotspotRankPanel">
                                                <div class="modern-dd-search">
                                                    <i class="material-icons">Search</i>
                                                    <input type="text" id="hotspotRankSearch"
                                                           placeholder="Search Barangay…"
                                                           autocomplete="off">
                                                </div>
                                                <div class="modern-dd-list" id="hotspotRanking"></div>
                                            </div>
                                        </div>
                                        <div id="hotspotRankDetail" class="rank-detail d-none"></div>
                                        <div class="small text-muted mt-2" style="line-height:1.5;">
                                            Select a Barangay to Fly to it on the Map.
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-raised no-hover mt-3" id="hotspotLocationsCard">
                                    <div class="card-body py-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="font-weight-bold" style="font-size:.82rem;">Registered MSME Locations</span>
                                            <span class="badge badge-pill msme-badge-unknown" id="hotspotLocationsBadge">None</span>
                                        </div>
                                        <div class="small text-muted mt-2" id="hotspotLocationsStatus">
                                            Select a hotspot or barangay ranking to load its registered MSMEs.
                                        </div>
                                        <div class="small text-muted mt-1" style="font-size:.68rem;">
                                            Pins use registry address records; exact GPS coordinates may not be available.
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="hotspotLocationsClear" disabled>
                                            <i class="material-icons" style="font-size:16px;vertical-align:middle;">clear</i>
                                            Clear locations
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Map -->
                            <div class="col-md-9 mb-3">
                                <div class="card map-card">
                                    <div class="map-card-header">
                                        <span>
                                            <i class="material-icons align-middle mr-1"
                                               style="font-size:17px;color:#ef4444;">local_fire_department</i>
                                            Economic Hotspot Map &mdash; business concentration per barangay
                                        </span>
                                        <span class="badge badge-pill msme-badge-unknown" id="hotspotBadge">loading…</span>
                                    </div>
                                    <div id="mapHotspot"></div>
                                </div>
                            </div>
                        </div>
                        <div class="emap-note mb-3">
                            <i class="material-icons">Info</i>
                            Hotspots reflect the number of registered MSMEs per barangay from the
                             <b>SCIMS Registry</b> (vamosmobile.app). Larger, Darker Circles indicate Higher Business Concentration.<br>
                             Select a hotspot circle or barangay ranking to view individual registered MSME locations.
                        </div>
                    </div>
