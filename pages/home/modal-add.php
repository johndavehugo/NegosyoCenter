<?php

$industries = [
    "A" => "Agriculture",
    "B" => "Fishing",
    "C" => "Mining and Quarrying",
    "D" => "Manufacturing",
    "E" => "Electricity, Gas, and Water Supply",
    "F" => "Construction",
    "G" => "Wholesale and Retail Trade",
    "H" => "Hotels and Restaurants",
    "I" => "Transport, Storage, and Communication",
    "J" => "Financial Intermediation",
    "K" => "Real Estate, Renting, and Business Activities",
    "L" => "Public Administration and Defense",
    "M" => "Education",
    "N" => "Health and Social Worker",
    "O" => "Other Community, Social, and Personal Service Activities",
    "P" => "Activities of Private Households as Employers...",
    "Q" => "Extra-territorial Organizations and Bodies",
];

$regions = [
    "NCR" => "National Capital Region",
    "CAR" => "Cordillera Administrative Region",
    "REGION I" => "Ilocos Region",
    "REGION II" => "Cagayan Valley",
    "REGION III" => "Central Luzon",
    "REGION IV-A" => "CALABARZON",
    "REGION IV-B" => "MIMAROPA",
    "REGION V" => "Bicol Region",
    "REGION VI" => "Western Visayas",
    "NIR" => "Negros Island Region",
    "REGION VII" => "Central Visayas",
    "REGION VIII" => "Eastern Visayas",
    "REGION IX" => "Zamboanga Peninsula",
    "REGION X" => "Northern Mindanao",
    "REGION XI" => "Davao Region",
    "REGION XII" => "SOCCSKSARGEN",
    "REGION XIII" => "Caraga",
    "BARMM" => "Bangsamoro Autonomous Region in Muslim Mindanao",
];

$provinces_NCR = [
    "City of Caloocan",
    "City of Las Piñas",
    "City of Makati",
    "City of Malabon ",
    "City of Mandaluyong",
    "City of Manila",
    "Tondo I/II",
    "Binondo",
    "Quiapo",
    "City of Marikina",
    "City of Muntinlupa",
    "City of Navotas",
    "City of Parañaque",
    "City of Pasay",
    "City of Pasig",
    "City of Quezon",
    "City of San Juan",
    "City of Taguig",
    "City of Valenzuela",
    "Municipality of Pateros",
];

?>





<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content" style="background-color: #343a40; color: white;">

                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Add Business</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="bs-stepper">
                        <div class="bs-stepper-header" role="tablist">
                            <div class="step" data-target="#step-business">
                                <button type="button" class="step-trigger" role="tab">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">Business Info</span>
                                </button>
                            </div>
                            <div class="bs-stepper-line"></div>
                            <div class="step" data-target="#step-owner">
                                <button type="button" class="step-trigger" role="tab">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">Owner Info</span>
                                </button>
                            </div>
                            <div class="bs-stepper-line"></div>
                            <div class="step" data-target="#step-address">
                                <button type="button" class="step-trigger" role="tab">
                                    <span class="bs-stepper-circle">3</span>
                                    <span class="bs-stepper-label">Addresses</span>
                                </button>
                            </div>
                        </div>

                        <div class="bs-stepper-content">
                            <div id="step-business" class="content" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Business Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="addBusinessName">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Line of Industry</label>
                                            <select class="form-control" id="addIndustry">
                                                <option value="" hidden>Select Industry</option>
                                                <?php foreach ($industries as $code => $industry):?>
                                                <option value="<?=$industry?>"><?=$code ?> - <?=$industry?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>                                                      
                                <div class="row">                                   
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Capitalization</label>
                                            <input type="number" step="0.01" class="form-control" id="addCapitalization">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Special Category</label>
                                            <select class="form-control" id="addSpecialCategory">
                                                <option value="" hidden>Select Special Sector Classification</option>
                                                <option value="4ps Beneficiary">4ps Beneficiary</option>
                                                <option value="Solo Parent">Solo Parent</option>
                                                <option value="Person with Disability">Person with Disability (PWD)</option>
                                                <option value="Young Entrepreneur">Young Entrepreneur</option>
                                            </select>
                                        </div>
                                    </div>  
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Contact No</label>
                                            <input type="text" class="form-control" id="addContactNo">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control" id="addEmail">
                                        </div>
                                    </div>
                                </div>                                                                                               
                                <button type="button" class="btn btn-primary float-right" onclick="stepper.next()">Next</button>
                            </div>

                            <div id="step-owner" class="content" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="addFirstName">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Middle Name</label>
                                            <input type="text" class="form-control" id="addMiddleName">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="addLastName">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary float-left" onclick="stepper.previous()">Previous</button>
                                <button type="button" class="btn btn-primary float-right" onclick="stepper.next()">Next</button>
                            </div>

                            <div id="step-address" class="content" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-info">Business Address</h6>
                                        <div class="form-group"><label>UPBLB No</label><input type="text" class="form-control" id="addBusUpblb"></div>
                                        <div class="form-group"><label>Street</label><input type="text" class="form-control" id="addBusStreet"></div>
                                        <div class="form-group"><label>Subdivision</label><input type="text" class="form-control" id="addBusSubdivision"></div>
                                        <div class="form-group"><label>Barangay <span class="text-danger">*</span></label><input type="text" class="form-control" id="addBusBarangay"></div>
                                        <div class="form-group"><label>City <span class="text-danger">*</span></label><input type="text" class="form-control" id="addBusCity"></div>
                                        <div class="form-group"><label>Province <span class="text-danger">*</span></label><input type="text" class="form-control" id="addBusProvince"></div>
                                        <div class="form-group"><label>Region</label><input type="text" class="form-control" id="addBusRegion"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-info">Owner Address</h6>
                                        <div class="form-group"><label>UPBLB No</label><input type="text" class="form-control" id="addEmpUpblb"></div>
                                        <div class="form-group"><label>Street</label><input type="text" class="form-control" id="addEmpStreet"></div>
                                        <div class="form-group"><label>Subdivision</label><input type="text" class="form-control" id="addEmpSubdivision"></div>
                                        <div class="form-group"><label>Barangay <span class="text-danger">*</span></label><input type="text" class="form-control" id="addEmpBarangay"></div>
                                        <div class="form-group"><label>City <span class="text-danger">*</span></label><input type="text" class="form-control" id="addEmpCity"></div>
                                        <div class="form-group"><label>Province <span class="text-danger">*</span></label><input type="text" class="form-control" id="addEmpProvince"></div>
                                        <div class="form-group"><label>Region</label><input type="text" class="form-control" id="addEmpRegion"></div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-secondary float-left" onclick="stepper.previous()">Previous</button>
                                <button type="button" class="btn btn-success float-right" id="btnSaveBusiness">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>