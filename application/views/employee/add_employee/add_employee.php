<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">

<div class="row">
    <div class="col-sm-12">

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- stage 1 form -->
                        <form action="" name="stage_1_form" id="stage_1_form" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Full Name<sup>*</sup><span data-toggle="popover" data-content="As per the Aadhar Card"><i class="icon-info"></i></span></label>
                                        <input type="text" class="form-control form-control-sm" name="full_name" id="full_name" onkeyup="getname(this)">
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Standardised Name</label>
                                        <input type="text" class="form-control form-control-sm" name="standardised_name" id="standardised_name" readonly autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-sm-4 col_personal_email">
                                    <div class="form-group">
                                        <label for="personal_email">Personal Email Address</label>
                                        <input type="text" class="form-control form-control-sm" name="personal_email" id="personal_email">
                                    </div>
                                </div>

                                <div class="col-sm-4 col_mobile_number">
                                    <div class="form-group">
                                        <label for="mobile_number">Mobile Number<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm" name="mobile_number" id="mobile_number">
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Gender<sup>*</sup></label>
                                        <select class="form-control form-control-sm" name="gender" id="gender" onchange="delivery_disable()">
                                            <option value="">Select Gender</option>
                                            <?php if ($genders) : ?>
                                                <?php foreach ($genders as $gender) : ?>
                                                    <option value="<?= $gender; ?>"><?= ucfirst($gender); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Nationality<sup>*</sup></label>
                                        <select class="form-control form-control-sm" name="nationality_id" id="nationality_id">
                                            <option value="">Select Nationality</option>
                                            <?php if ($nationality) : ?>
                                                <?php foreach ($nationality as $value) : ?>
                                                    <option value="<?= $value->nationality_id; ?>"> <?= $value->name; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Religion<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm" name="religion" id="religion">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Category<sup>*</sup></label>
                                        <select class="form-control form-control-sm" name="category_id" id="category_id">
                                            <option value="">Select Category</option>
                                            <?php if ($categories) : ?>
                                                <?php foreach ($categories as $cate) : ?>
                                                    <option value="<?= $cate->category_id; ?>"><?= $cate->name; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>How did you hear about the current requirement?<sup>*</sup></label>
                                        <select class="form-control form-control-sm" name="how_you_hear_id" id="how_you_hear_id" onchange="how_hear_disable()">
                                            <option value="">Select applicable</option>
                                            <?php if ($heard) : ?>
                                                <?php foreach ($heard as $hear) : ?>
                                                    <option value="<?= $hear->how_you_hear_id; ?>"><?= $hear->name; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="howhear">If others, please specify.<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm" name="how_you_hear_other" id="how_you_hear_other">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Currently Employed?<sup>*</sup></label>
                                        <select onchange="setcurrentctc(this.value)" class="form-control form-control-sm" name="currently_employed" id="currently_employed">
                                            <option value="">Please Select</option>
                                            <?php if ($employed) : ?>
                                                <?php foreach ($employed as $value) : ?>
                                                    <option value="<?= $value; ?>"><?= ucfirst($value); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12 current_ctcdiv">
                                    <div class="form-group">
                                        <label>Current CTC (LPA / Rupees) <sup>*</sup></label>
                                        <input type="text" name="current_ctc" id="current_ctc" class="form-control current_ctc form-control-sm">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Available for joining?<sup>*</sup></label>
                                        <select class="form-control form-control-sm" name="available_joining_id" id="available_joining_id">
                                            <option value="">Select applicable</option>
                                            <?php if ($joining) : ?>
                                                <?php foreach ($joining as $value) : ?>
                                                    <option value="<?= $value->available_joining_id; ?>"><?= $value->name; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Aadhaar Number<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm aadhar" name="aadhar_number" id="aadhar_number">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Upload Aadhaar<sup>*</sup><span data-toggle="popover" data-content="Upload document in .pdf or .png or .jpeg or .jpg Format"><i class="icon-info"></i></span></label>
                                        <input type="file" class="form-control form-control-sm" name="aadhar_card[]" id="aadhar_card" multiple>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Resume<sup>*</sup><span data-toggle="popover" data-content="Please upload your latest Resume in .doc or .docx or .pdf Format"><i class="icon-info"></i></span></label>
                                        <input type="file" class="form-control form-control-sm" name="resume[]" id="resume" multiple>

                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label class="expectm">Are you an expecting mother?<sup>*</sup></label>
                                        <select class="form-control form-control-sm" name="expecting_mother" id="expecting_mother" onchange="delivery_date_disable()">
                                            <option value=""> Select </option>
                                            <option value="yes"> Yes </option>
                                            <option value="no"> No </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label class="deliverydt">Expected date of delivery<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm datepicker" name="expected_delivery_date" data-date-start-date="0d" id="expected_delivery_date">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12">
                        <!-- stage form 2 -->
                        <form action="" name="stage_2_form" id="stage_2_form" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Date of Birth<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm datepicker" name="date_of_birth" id="date_of_birth">
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Marital Status<sup>*</sup></label>
                                        <select class="form-control form-control-sm" name="marital_status" id="marital_status">
                                            <option value="">Select</option>
                                            <?php if ($marital_status) : ?>
                                                <?php foreach ($marital_status as $marital) : ?>
                                                    <option value="<?= $marital; ?>"><?= ucfirst($marital); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Blood Group<sup>*</sup></label>
                                        <select class="form-control form-control-sm" name="blood_group" id="blood_group">
                                            <option value="">Select</option>
                                            <?php if ($blood_groups) : ?>
                                                <?php foreach ($blood_groups as $bloodGroups) : ?>
                                                    <option value="<?= $bloodGroups; ?>"><?= ucfirst($bloodGroups); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>PAN Number<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm uppercase" name="pan_number" id="pan_number">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>PAN Card<sup>*</sup><span data-toggle="popover" data-content="Upload document in .pdf or .png or .jpeg or .jpg  Format"><i class="icon-info"></i></span></label>
                                        <input type="file" class="form-control form-control-sm" name="pan_card[]" id="pan_card" accept="image/png,image/jpg,image/jpeg,application/pdf" multiple>

                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Latest Passport Size photo<sup>*</sup><span data-toggle="popover" data-content="Please upload the recent passport size photo (Not older than six months), in .jpeg or .png or jpg Format"><i class="icon-info"></i></span></label>
                                        <input type="file" class="form-control form-control-sm" name="latest_photo[]" id="photo">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <hr />
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Do you have a valid passport?<sup>*</sup></label>
                                        <select class="form-control form-control-sm" name="valid_passport" id="valid_passport" onchange="passport_disable()">
                                            <option value="">Select</option>
                                            <?php if ($valid_passport) : ?>
                                                <?php foreach ($valid_passport as $passport) : ?>
                                                    <option value="<?= $passport; ?>"><?= ucfirst($passport); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label class="vpassport ">Passport Number<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm passverify uppercase" name="passport_number" id="passport_number">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label class="vpassport ">Valid Until<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm datepicker" data-date-start-date="0d" name="passport_validity" id="passport_validity">
                                    </div>
                                </div>

                                <div class=" col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label class="vpassport">Do you have Active Business Visa of USA?<sup>*</sup></label>
                                        <select class="form-control form-control-sm" name="business_visa_usa" id="business_visa_usa" onchange="visa_disable()">
                                            <option value="">Select</option>
                                            <?php if ($business_visa_usa) : ?>
                                                <?php foreach ($business_visa_usa as $value) : ?>
                                                    <option value="<?= $value; ?>"><?= ucfirst($value); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label class="vvisa">Visa Valid Until<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm datepicker" data-date-start-date="0d" name="visa_validity" id="visa_validity">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <hr />
                                    <h5>Present Address: </h5>
                                </div>

                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Country<sup>*</sup></label>
                                        <select class="form-control form-control-sm" name="add_per_country_id" onchange="change_state(this.value, 'pre_state')" id="pre_country">
                                            <option value="">Select</option>
                                            <?php if ($countries_items) : ?>
                                                <?php foreach ($countries_items as $value) : ?>
                                                    <option value="<?= $value->country_id; ?>">
                                                        <?= $value->name; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>State<sup>*</sup></label>
                                        <select class="form-control form-control-sm" name="add_per_state_id" id="pre_state" onchange="change_district(this.value, 'pre_district')">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>District<sup>*</sup></label>
                                        <select class="form-control form-control-sm" name="add_per_district_id" id="pre_district">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>PIN Code<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm" name="add_per_pincode" id="add_per_pincode" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Rest of the Address<sup>*</sup><span data-toggle="popover" data-content="Residence Name, Number, Road, Locality"><i class="icon-info"></i></span></label>
                                        <textarea name="add_per_address" maxlength="222" id="add_per_address" class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Present Address Proof<sup>*</sup><span data-toggle="popover" data-content="Landline/Mobile bill/Electricity Bill/Gas Connection Bill for past two months only if its on self/parents/spouse name OR Rental Agreement OR Ration Card in .pdf or .jpeg or .jpg or .png"><i class="icon-info"></i></span></label>
                                        <input type="file" class="form-control form-control-sm" name="pre_address_proof[]" id="pre_address_proof" multiple>

                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <hr />
                                </div>

                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Is Permanent Address same as Present?&nbsp;&nbsp;
                                            <div class="d-inline-block">
                                                <select class="form-control form-control-sm" name="address_same" id="address_same">
                                                    <option value="no">No</option>
                                                    <option value="yes">Yes</option>
                                                </select>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12" id="permanent_address">
                                    <h5>Permanent Address: </h5>
                                    <div class="row">

                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label>Country<sup>*</sup></label>
                                                <select class="form-control form-control-sm" name="pre_country_id" onchange="change_state(this.value, 'pr_state')" id="pr_country">
                                                    <option value="">Select</option>
                                                    <?php if ($countries_items) : ?>
                                                        <?php foreach ($countries_items as $value) : ?>
                                                            <option value="<?= $value->country_id; ?>">
                                                                <?= $value->name; ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label>State<sup>*</sup></label>
                                                <select class="form-control form-control-sm" name="pre_state_id" id="pr_state" onchange="change_district(this.value, 'pr_district')">
                                                    <option value="">Select</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label>District<sup>*</sup></label>
                                                <select class="form-control form-control-sm" name="pre_district_id" id="pr_district">
                                                    <option value="Select"></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label>PIN Code<sup>*</sup></label>
                                                <input type="text" class="form-control form-control-sm" name="pre_pincode" id="pre_pincode" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-12">
                                            <div class="form-group">
                                                <label>Rest of the Address<sup>*</sup><span data-toggle="popover" data-content="Residence Name, Number, Road, Locality"><i class="icon-info"></i></span></label>
                                                <textarea name="pre_address" maxlength="222" id="pre_address" class="form-control form-control-sm"></textarea>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12">
                                    <hr />
                                </div>

                                <!-- Educational Qulification  -->
                                <div class="col-md-12 col-sm-12">
                                    <label>
                                        <h5>Educational Qualifications:</h5>
                                    </label> <span data-toggle="popover" data-content="Please update your educational qualifications starting from SSLC/10th std to Highest Qualification"><i class="icon-info"></i></span>
                                    <span class="pull-right"> <button type="button" id="btn_add_edu" class="btn btn-sm btn-success adline"><i class="fa fa-plus"></i></button></span>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <table class="table table-bordered table-hover table-striped">

                                                <thead>
                                                    <tr>
                                                        <th class="text-center" rowspan="2">Action</th>
                                                        <th rowspan="2">Qualification<sup>*</sup></th>
                                                        <th rowspan="2">Specialization<sup>*</sup></th>
                                                        <th rowspan="2">Institute<sup>*</sup></th>
                                                        <th class="text-center" style="border-bottom:1px solid #dee2e6 !important;" colspan="3">Location of the Institute</th>
                                                        <th rowspan="2">% Marks / Grade<sup>*</sup></th>
                                                        <th rowspan="2">Year of Completion<sup>*</sup></th>
                                                    </tr>
                                                    <tr>
                                                        <th>Country<sup>*</sup></th>
                                                        <th>State<sup>*</sup></th>
                                                        <th>Nearest City<sup>*</sup></th>
                                                    </tr>
                                                </thead>

                                                <tbody id="edu_show">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12">
                                    <hr />
                                </div>

                                <!-- Certification Coursese -->
                                <div class="col-md-12 col-sm-12">
                                    <label>
                                        <h5>Certification Courses:</h5>
                                    </label><span data-toggle="popover" data-content="Course should be of minimum 10 Days Duration"><i class="icon-info"></i></span> <span class="pull-right"><button class="btn btn-sm btn-success adline" type="button" name="" id="btn_add_certification"><i class="fa fa-plus"></i></button></span>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <table class="table table-bordered table-hover table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Action</th>
                                                        <th>Name of the Certification<sup>*</sup></th>
                                                        <th>Specialization<sup>*</sup></th>
                                                        <th>Institute Name<sup>*</sup></th>
                                                        <th>Start Date<sup>*</sup></th>
                                                        <th>End Date<sup>*</sup></th>
                                                        <th>Valid Upto</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="certification_show">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 col-sm-12">
                                    <hr />
                                </div>

                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Do you have Employment History?&nbsp;&nbsp; <div class="d-inline-block">

                                                <select class="form-control form-control-sm" name="employment_history" id="employment_history" onchange="salaryslip_disable()">
                                                    <option value="no">No, I am a Fresher</option>
                                                    <option value="yes">Yes, I do have</option>
                                                </select>

                                            </div></label>
                                    </div>
                                </div>

                                <!-- Employment history line items -->
                                <div class="col-md-12 col-sm-12 " id="employment_history_list">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <label>
                                                <h5>Employment History:</h5>
                                            </label><span data-toggle="popover" data-content="List down the names of all Companies that you worked in the past. State the Company Details in the chronological order"><i class="icon-info"></i></span><span class="pull-right"> <button class="btn btn-sm btn-success adline" type="button" name="" id="add_employment"><i class="fa fa-plus"></i></button></span>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table class="table table-bordered table-hover table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center" rowspan="2">Action</th>
                                                                <th rowspan="2">Company Name<sup>*</sup></th>

                                                                <th class="text-center" style="border-bottom:1px solid #dee2e6 !important;" colspan="3">Work Location</th>
                                                                <th rowspan="2">Start Date<sup>*</sup></th>
                                                                <th rowspan="2">End Date</th>
                                                                <th rowspan="2">Last Designation Held<sup>*</sup></th>
                                                                <th rowspan="2">Responsibilities Handled<sup>*</sup></th>
                                                                <th rowspan="2">Annual CTC (LPA)<sup>*</sup></th>
                                                                <th rowspan="2">Reason For Job Change<sup>*</sup></th>
                                                                <th rowspan="2">Is this experience relevant?<sup>*</sup></th>
                                                            </tr>
                                                            <tr>
                                                                <th>Country<sup>*</sup></th>
                                                                <th>State<sup>*</sup></th>
                                                                <th>Nearest City<sup>*</sup></th>
                                                            </tr>
                                                        </thead>

                                                        <tbody id="employment_show">
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <hr />
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Experience before Ksoft <br />(In months)<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm " name="experience_before_enventure" id="experience_before_enventure" readonly autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label class="emphistory">Salay Slips of last 3 Months<sup>*</sup><span data-toggle="popover" data-content="Upload document in .pdf or .png or .jpeg or .jpg Format"><i class="icon-info"></i></span></label>
                                        <input type="file" class="form-control form-control-sm" name="salary_slip_last3[]" id="salary_slip_last3" multiple>

                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label class="emphistory">Salary Break-up<sup>*</sup><span data-toggle="popover" data-content="Upload any document showing the Salary Break-up. Eg. Latest Salary Revision Letter, Appointment Letter, etc. Upload document in .pdf or .png or .jpeg or .jpg Format"><i class="icon-info"></i></span></label>
                                        <input type="file" class="form-control form-control-sm" name="salary_breakup[]" id="salary_breakup" multiple>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <hr />
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <label>
                                        <h5>Family Details:</h5>
                                    </label><a href="javascript:;" class="has-popover ml-2" data-toggle="popover" data-content="Include Father's Name, Mother's Name, Spouse Name and Children's Name"><i class="icon-info"></i></a><span class="pull-right"> <button type="button" id="btn_add_family" class="btn btn-sm btn-success adline"><i class="fa fa-plus"></i></button> </span>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <table class="table table-bordered table-hover table-striped">
                                                <thead>
                                                    <th class="text-center">Action</th>
                                                    <th>Name of Family Member<sup>*</sup></th>
                                                    <th>Relationship with the Employee<sup>*</sup></th>
                                                    <th>Living Status<sup>*</sup></th>
                                                    <th>Gender<sup>*</sup></th>
                                                    <th>Date of Birth<sup>*</sup></th>
                                                    <th>Occupation<sup>*</sup></th>
                                                    <th>Mobile Number<sup>*</sup></th>
                                                    <th>Emergency Contact?<sup>*</sup></th>
                                                </thead>
                                                <tbody id="family_show">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class=" col-sm-12">
                        <!-- stage form 3 -->
                        <form action="" name="stage_3_form" id="stage_3_form" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Proof of Resignation<sup>*</sup><span data-html="true" data-placement="top" data-toggle="popover" data-content="Upload Resignation Letter/Email in .pdf or .jpeg or .jpg or .png Format"><i class="icon-info"></i></span></label>
                                        <input type="file" class="form-control form-control-sm" name="resignation_proof[]" id="resignation_proof" value="" multiple>

                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Authorization Letter<sup>*</sup><span data-html="true" data-placement="top" data-toggle="popover" data-content="Download Authorization Letter. Fill it appropriately, sign it and upload it, in .pdf Format."><i class="icon-info"></i></span><a href='<?php echo base_url() ?>download/authorization_letter'><img src="<?php echo base_url() ?>assets/downloads/download.png" width="20px" height="20px" target="_blank"></a></label>
                                        <input type="file" class="form-control form-control-sm" name="authorization_letter[]" id="authorization_letter" value="" multiple>
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Proof of Acceptance of Resignation<sup>*</sup><span data-html="true" data-placement="top" data-toggle="popover" data-content="Upload Acceptance of Resignation Letter/Email in .pdf Format"><i class="icon-info"></i></span></label>
                                        <input type="file" class="form-control form-control-sm" name="resignation_accept_proof[]" id="resignation_accept_proof" value="" multiple>

                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>

                    <div class="col-sm-12">
                        <form action="" name="employee_add" id="employee_add" method="POST" enctype="multipart/form-data">
                            <div class="col-sm-12 text-right">
                                <div class="mt-4">
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="<?= base_url() ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

        <!-- stage 1 form script -->
        <script type="text/javascript">
            $(document).ready(function() {
                $('.datepicker').datepicker({
                    format: 'dd-M-yyyy',
                    todayHighlight: true,
                    autoclose: true
                });

                $("#stage_1_form").validate({
                    // ignore: ':hidden:not(#skill_id)',
                    rules: {
                        // stage 1 form
                        full_name: {
                            required: true,
                            alphaspaces: true
                        },
                        personal_email: {
                            required: true,
                            email: true
                        },
                        country_code: {
                            required: true,
                            // minlength: 3,
                            // maxlength: 8,
                            //countrycode: true
                        },
                        mobile_number: {
                            required: true,
                            digits: true,
                            minlength: 10,
                            maxlength: 10
                        },
                        gender: {
                            required: true
                        },
                        nationality_id: {
                            required: true
                        },
                        religion: {
                            required: true
                        },
                        category_id: {
                            required: true
                        },
                        how_you_hear_id: {
                            required: true
                        },
                        how_you_hear_other: {
                            required: function(element) {
                                return ($('#how_you_hear_id').val() == 6);
                            },
                        },
                        currently_employed: {
                            required: true
                        },
                        available_joining_id: {
                            required: true
                        },
                        aadhar_number: {
                            required: true,
                            aadharverify: true
                        },
                        'aadhar_card[]': {
                            required: true,
                            extension: "png|jpg|jpeg|pdf",
                            maxupload: 3,
                            maxfilesize: 2
                        },
                        'resume[]': {
                            required: true,
                            extension: "pdf|doc|docx",
                            maxupload: 3,
                            maxfilesize: 2
                        },
                        expecting_mother: {
                            required: true
                        },
                        expected_delivery_date: {
                            required: function(element) {
                                return ($('#expecting_mother').val() == 'yes');
                            },
                        },
                        current_ctc: {
                            required: true
                        },
                    },
                    messages: {

                    },
                    errorPlacement: function(error, element) {
                        if (element.hasClass('select2') && element.next('.select2-container').length) {
                            error.insertAfter(element.next('.select2-container')).addClass('d-block');
                        } else {
                            element.closest('.form-group').append(error);
                        }
                    },
                    submitHandler: function(form, e) {
                        e.preventDefault();
                    }

                });
            });

            function getname(ths) {
                var fullname = $(ths).val();
                var array = fullname.split(" ");
                var name = "";
                var initials = "";
                array.forEach(function(item) {
                    if (item.length > 2) {
                        name += item;
                        name += " ";
                    } else {
                        initials += item;
                        initials += " ";
                    }
                });

                var final = name + " " + initials;
                final = final.replace(/\s\s+/g, " ").trim();

                final = final.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                });

                $("#standardised_name").val(final);
            }

            /* to disable expected delivery section */

            function delivery_disable() {
                console.log($("#gender").val());
                if ($("#gender").val() != "female") {
                    $("#expecting_mother").val("no").addClass("d-none");
                    $("#expected_delivery_date").val("").addClass("d-none");
                    $(".expectm, .deliverydt").addClass("d-none");
                } else {
                    $(
                        "#expecting_mother, #expected_delivery_date, .expectm, .deliverydt"
                    ).removeClass("d-none");
                }

                delivery_date_disable();
            }

            function delivery_date_disable() {
                if ($("#expecting_mother").val() != "yes") {
                    $("#expected_delivery_date").val("").addClass("d-none");
                    $(".deliverydt").addClass("d-none");
                } else {
                    $("#expected_delivery_date")
                        .val("")
                        .removeClass("d-none")
                        .attr("disabled", false);
                    $(".deliverydt").removeClass("d-none");
                }
            }

            function how_hear_disable() {
                if ($("#how_you_hear_id").val() != 6) {
                    $("#how_you_hear_other").val("").addClass("d-none");
                    $(".howhear").addClass("d-none");
                } else {
                    $("#how_you_hear_other")
                        .val("")
                        .removeClass("d-none")
                        .attr("disabled", false);
                    $(".howhear").removeClass("d-none");
                }
            }

            function setcurrentctc(selval) {
                if (selval != "yes") {
                    $("#current_ctc").val(0);
                    $(".current_ctcdiv").hide();
                } else {
                    $(".current_ctcdiv").show();
                }
            }
        </script>

        <!-- stage 2 form script -->
        <script type="text/javascript">
            $(document).ready(function() {

                //load one line item of education, and employment       
                if ($('#edu_show tr').length == 0) {
                    setTimeout(() => {
                        $('#btn_add_edu').trigger("click");
                        $('#remove_line_0').remove();
                    }, 500);
                }
                if ($('#employment_show tr').length == 0) {
                    setTimeout(() => {
                        $('#add_employment').trigger("click");
                        $('#remove_emp_line_0').remove();
                    }, 500);
                }
                if ($('#family_show tr').length == 0) {
                    setTimeout(() => {
                        $('#btn_add_family').trigger("click");
                        $('#remove_line_family_0').remove();
                    }, 500);
                }

                $("#stage_2_form").validate({
                    // ignore: ':hidden:not(#skill_id)',
                    rules: {
                        // stage 2 form
                        date_of_birth: {
                            required: true
                        },
                        marital_status: {
                            required: true
                        },
                        blood_group: {
                            required: true,
                        },
                        pan_number: {
                            required: true,
                            panverify: true
                        },
                        'pan_card[]': {
                            required: true,
                            extension: "png|jpg|jpeg|pdf",
                            maxupload: 3,
                            maxfilesize: 2
                        },
                        'latest_photo[]': {
                            required: true,
                            extension: "png|jpg|jpeg",
                            maxfilesize: 2
                        },
                        valid_passport: {
                            required: true
                        },
                        passport_number: {
                            required: function(element) {
                                return ($('#valid_passport').val() == 'yes');
                            },
                        },
                        passport_validity: {
                            required: function(element) {
                                return ($('#valid_passport').val() == 'yes');
                            },
                        },
                        business_visa_usa: {
                            required: function(element) {
                                return ($('#valid_passport').val() == 'yes');
                            },
                        },
                        visa_validity: {
                            required: function(element) {
                                return ($('#business_visa_usa').val() == 'yes');
                            },
                        },
                        add_per_country_id: {
                            required: true
                        },
                        add_per_state_id: {
                            required: true
                        },
                        add_per_district_id: {
                            required: true
                        },
                        add_per_pincode: {
                            required: true,
                            minlength: 5,
                            maxlength: 6,
                            number: true
                        },
                        add_per_address: {
                            required: true
                        },
                        pre_country_id: {
                            required: function(element) {
                                return ($('#address_same').val() == 'no');
                            }
                        },
                        pre_state_id: {
                            required: function(element) {
                                return ($('#address_same').val() == 'no');
                            }
                        },
                        pre_district_id: {
                            required: function(element) {
                                return ($('#address_same').val() == 'no');
                            }
                        },
                        pre_pincode: {
                            required: function(element) {
                                return ($('#address_same').val() == 'no');
                            },
                            minlength: 5,
                            maxlength: 6,
                            number: true
                        },
                        pre_address: {
                            required: function(element) {
                                return ($('#address_same').val() == 'no');
                            }
                        },
                        'salary_slip_last3[]': {
                            extension: "png|jpg|jpeg|pdf",
                            maxupload: 3,
                            maxfilesize: 2,
                            required: function(element) {
                                return ($('#employment_history').val() == 'yes');
                            }
                        },
                        'salary_breakup[]': {
                            extension: "png|jpg|jpeg|pdf",
                            maxupload: 3,
                            maxfilesize: 2,
                            required: function(element) {
                                return ($('#employment_history').val() == 'yes');
                            }
                        },
                        experience_before_enventure: {
                            required: true
                        },
                        'pre_address_proof[]': {
                            required: true,
                            extension: "png|jpg|jpeg|pdf",
                            maxupload: 3,
                            maxfilesize: 2
                        }

                    },
                    messages: {

                    },
                    errorPlacement: function(error, element) {
                        console.log(error)
                        if (element.hasClass('select2') && element.next('.select2-container').length) {
                            error.insertAfter(element.next('.select2-container')).addClass('d-block');
                        } else {
                            element.closest('.form-group').append(error);
                        }
                    },
                    submitHandler: function(form, e) {
                        e.preventDefault();
                    }

                });

                $('#address_same').on('change', function() {
                    if ($(this).val() == 'yes') {
                        $('#permanent_address').hide();
                    } else {
                        $('#permanent_address').show();
                    }
                });

                /* Add educational line items */
                var edu_line_id = $('#edu_show tr').length;
                $('#btn_add_edu').on('click', function() {
                    var eduHtml = `<tr>
                    <td><button type="button" id="remove_line_` + edu_line_id + `" onClick="remove_line_item(` + edu_line_id + `)" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i></button><input type="hidden" name="cid[` + edu_line_id + `]" value="` + edu_line_id + `"></td>
                    <td>
                        <select class="form-control form-control-sm edu-required courseselect" name="course_id[` + edu_line_id + `]" onChange="active_specification(this.value,` + edu_line_id + `)" id="course_id_` + edu_line_id + `">
                            <option value="">Select</option>
                            <?php if ($courses) : ?>
                                <?php foreach ($courses as $value) : ?>
                                    <option value="<?= $value->course_id; ?>"><?= $value->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                    <td><input class="form-control form-control-sm edu-required" type="text" name="specialization[` + edu_line_id + `]" id="specialization_` + edu_line_id + `" readonly></td>
                    <td><input class="form-control form-control-sm edu-required" type="text" name="institute_name[` + edu_line_id + `]" id="institute_name_` + edu_line_id + `"></td>
                    <td><select class="form-control form-control-sm edu-required show-tick select2" name="country_id[` + edu_line_id + `]" onchange="change_state(this.value, 'edu_state_id_` + edu_line_id + `')" id="edu_country_id_` + edu_line_id + `">
                            <option value="" >Select</option>
                            <?php if ($countries_items) : ?>
                                <?php foreach ($countries_items as $value) : ?>
                                    <option value="<?= $value->country_id; ?>"><?= $value->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                    </select></td>
                    <td><select class="form-control form-control-sm edu-required" name="state_id[` + edu_line_id + `]" id="edu_state_id_` + edu_line_id + `"><option value="">Select</option></select></td>
                    <td><input class="form-control form-control-sm edu-required" type="text" name="nearest_city[` + edu_line_id + `]" id="nearest_city_` + edu_line_id + `"></td>
                    <td><input class="form-control form-control-sm edu-required" type="text" name="percentage_mark[` + edu_line_id + `]" id="percentage_mark_` + edu_line_id + `"></td>
                    <td><input class="form-control form-control-sm edu-required edu-year" type="text" name="completion_year[` + edu_line_id + `]" id="completion_year_` + edu_line_id + `"></td>                    
                </tr>`;

                    if (edu_line_id >= 1) {
                        if (validation_line_item(edu_line_id, '#course_id_')) {
                            $('#edu_show').append(eduHtml);
                            edu_line_id++;
                        }
                    } else {
                        $('#edu_show').append(eduHtml);
                        edu_line_id++;
                    }

                    //disable courses if selected already
                    // $('.courseselect').each(function() {
                    //     $('.courseselect').not(this).find('option[value="' + this.value + '"]').prop('disabled', true);
                    // });

                    //validate all fields with particular class
                    $("#stage_2_form .edu-required").each(function() {

                        $(this).rules('add', {
                            required: true,
                        });

                    });

                    $("#stage_2_form .edu-year").each(function() {

                        $(this).rules('add', {
                            number: true,
                            maxlength: 4,
                            minlength: 4
                        });

                    });

                });

                /* Add certification line items */
                var cer_line_id = $('#certification_show tr').length;

                $('#btn_add_certification').on('click', function() {
                    var cirtHtml = `<tr>
            <td><button type="button" id="remove_cer_line_` + cer_line_id + `" onClick="remove_line_item(` + cer_line_id + `,'#remove_cer_line_')" class="btn btn-sm btn-danger "><i class="fa fa-minus-circle"></i></button></td>
            <td><input class="form-control form-control-sm cert-required" type="text" name="certification[` + cer_line_id + `]" id="certification_` + cer_line_id + `"></td>
            <td><input class="form-control form-control-sm cert-required" type="text" name="cspecialization[` + cer_line_id + `]" id="cspecialization_` + cer_line_id + `" ></td>
            <td><input class="form-control form-control-sm cert-required" type="text" name="cinstitute_name[` + cer_line_id + `]" id="cinstitute_name_` + cer_line_id + `" ></td>
            <td><input class="form-control form-control-sm datepicker cert-required start_cert_datepicker" data-id=` + cer_line_id + `  type="text" name="start_date[` + cer_line_id + `]" id="start_date_` + cer_line_id + `" ></td>
            <td><input class="form-control form-control-sm datepicker cert-required end_cert_datepicker" data-id=` + cer_line_id + `    type="text" name="end_date[` + cer_line_id + `]" id="end_date_` + cer_line_id + `"></td>
              <td><input class="form-control form-control-sm datepicker" type="text" name="valid_upto[` + cer_line_id + `]" id="valid_upto_` + cer_line_id + `"></td>
            </tr>`;

                    if (cer_line_id >= 1) {
                        if (validation_line_item(cer_line_id, '#certification_')) {
                            $('#certification_show').append(cirtHtml);
                            cer_line_id++;
                        }
                    } else {
                        $('#certification_show').append(cirtHtml);
                        cer_line_id++;
                    }

                    $('.datepicker').datepicker({
                        format: 'dd-M-yyyy',
                        todayHighlight: true,
                        autoclose: true
                    });
                    $('.start_cert_datepicker').datepicker({
                        format: 'dd-M-yyyy',
                        autoclose: true,
                    }).on('changeDate', function(selected) {
                        var minDate = new Date(selected.date.valueOf());
                        var id = $(this).attr('data-id');
                        $('#end_date_' + id).datepicker('setStartDate', minDate);
                    });

                    $('.end_cert_datepicker').datepicker({
                        format: 'dd-M-yyyy',
                        autoclose: true,
                    }).on('changeDate', function(selected) {
                        var minDate = new Date(selected.date.valueOf());
                        var id = $(this).attr('data-id');
                        $('#start_date_' + id).datepicker('setEndDate', minDate);
                    });
                    //validate all fields with particular class
                    $("#stage_2_form .cert-required").each(function() {
                        $(this).rules('add', {
                            required: true,
                        })
                    });

                });

                /* Add employment line items */
                var emplo_line_id = $('#employment_show tr').length;
                $('#add_employment').on('click', function() {
                    var emploHtml = `<tr>
                <td><button type="button" id="remove_emp_line_` + emplo_line_id + `" onClick="remove_line_item(` + emplo_line_id + `,'#remove_emp_line_')" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i></button><input type="hidden" name="eid[` + emplo_line_id + `]" value="` + emplo_line_id + `"></td>
                <td><input class="form-control form-control-sm emp-required" type="text" name="emp_company_name[` + emplo_line_id + `]" id="emp_company_name_` + emplo_line_id + `"></td>
                <td><select class="form-control form-control-sm show-tick select2 emp-required" name="emp_country_id[` + emplo_line_id + `]" onchange="change_state(this.value, 'emp_state_id_` + emplo_line_id + `')" id="emp_country_id_` + emplo_line_id + `">
                            <option value="" >Select</option>
                            <?php if ($countries_items) : ?>
                                <?php foreach ($countries_items as $value) : ?>
                                    <option value="<?= $value->country_id; ?>"><?= $value->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                    </select></td>
                <td><select class="form-control form-control-sm emp-required" name="emp_state_id[` + emplo_line_id + `]" id="emp_state_id_` + emplo_line_id + `"><option value="">Select</option></select></td>
                <td><input class="form-control form-control-sm emp-required" type="text" name="emp_nearest_city[` + emplo_line_id + `]" id="emp_nearest_city_` + emplo_line_id + `" ></td>
                <td><input class="form-control form-control-sm start_datepicker emp-required" data-id="` + emplo_line_id + `" type="text" name="emp_start_date[` + emplo_line_id + `]" id="emp_start_date_` + emplo_line_id + `" ></td>
                <td><input class="form-control form-control-sm end_datepicker emp-required-end" data-id="` + emplo_line_id + `" type="text" name="emp_end_date[` + emplo_line_id + `]" id="emp_end_date_` + emplo_line_id + `"></td>                
                <td><input class="form-control form-control-sm emp-required" type="text" name="emp_last_designation[` + emplo_line_id + `]" id="emp_last_designation_` + emplo_line_id + `" ></td>
                <td><input class="form-control form-control-sm emp-required" type="text" name="emp_responsibilities[` + emplo_line_id + `]" id="emp_responsibilities_` + emplo_line_id + `" ></td>
                <td><input class="form-control form-control-sm emp-required amount" type="text" name="emp_annual_ctc[` + emplo_line_id + `]" id="emp_annual_ctc_` + emplo_line_id + `" ></td>
                <td><input class="form-control form-control-sm emp-required" type="text" name="emp_reason_jobchance[` + emplo_line_id + `]" id="emp_reason_jobchance_` + emplo_line_id + `" ></td>
                <td><select class="form-control form-control-sm emp-required is_this_relevant_exp" name="is_this_relevant_exp[` + emplo_line_id + `]" id="is_this_relevant_exp_` + emplo_line_id + `">
                                                <option value="">Select</option>
                                                <option value="1" >Yes</option>
                                                <option value="2" >No</option>

                                            </select></td>
            </tr>`;

                    if (emplo_line_id >= 1) {
                        if (validation_line_item(emplo_line_id, '#emp_company_name_')) {
                            $('#employment_show').append(emploHtml);
                            emplo_line_id++;
                        }

                        //validate end date when add new line item
                        var prevline = (emplo_line_id - 2);
                        var id = $('#employment_show tr').eq(prevline).find("input[type=text].emp-required-end").attr('id');
                        $('#' + id).rules('add', {
                            required: true,
                        });

                    } else {
                        $('#employment_show').append(emploHtml);
                        emplo_line_id++;
                    }

                    $('.start_datepicker').datepicker({
                        format: 'dd-M-yyyy',
                        autoclose: true,
                    }).on('changeDate', function(selected) {
                        var minDate = new Date(selected.date.valueOf());
                        var id = $(this).attr('data-id');
                        $('#emp_end_date_' + id).datepicker('setStartDate', minDate);
                    });

                    $('.end_datepicker').datepicker({
                        format: 'dd-M-yyyy',
                        autoclose: true,
                    }).on('changeDate', function(selected) {
                        var minDate = new Date(selected.date.valueOf());
                        var id = $(this).attr('data-id');
                        $('#emp_start_date_' + id).datepicker('setEndDate', minDate);
                    });

                    //validate all fields with particular class
                    $("#stage_2_form .emp-required").each(function() {

                        $(this).rules('add', {
                            required: true,
                        });

                    });

                });

                /* Adding family details */
                var family_line_id = $('#family_show tr').length;

                $('#btn_add_family').on('click', function() {
                    var familyHtml = `<tr>
                    <td><input type="hidden" name="fmid[` + family_line_id + `]" value="` + Math.random() + `"/><button type="button" id="remove_line_family_` + family_line_id + `" onClick="remove_line_item(` + family_line_id + `, '#remove_line_family_')" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    <td><input class="form-control form-control-sm fm-required" type="text" name="family_name[` + family_line_id + `]" id="family_name_` + family_line_id + `"></td>
                    <td>
                        <select class="form-control form-control-sm fm-required" name="family_relationship[` + family_line_id + `]" id="family_relationship_` + family_line_id + `">
                            <option value="">Select</option>
                            <?php if ($relationships) : ?>
                                <?php foreach ($relationships as $value) : ?>
                                    <option value="<?= $value; ?>"><?= Ucfirst($value); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                    <td>
                        <select class="form-control form-control-sm fm-required" name="family_living_status[` + family_line_id + `]"  id="family_living_status_` + family_line_id + `" onChange="living_status_check(this.value, ` + family_line_id + `)">
                            <option value="">Select</option>
                            <?php if ($living_status) : ?>
                                <?php foreach ($living_status as $value) : ?>
                                    <option value="<?= $value; ?>"><?= ucfirst($value); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                    <td><select class="form-control form-control-sm show-tick select2 fm-required" name="family_gender[` + family_line_id + `]" id="family_gender_` + family_line_id + `">
                            <option value="" >Select</option>
                            <?php if ($genders) : ?>
                                <?php foreach ($genders as $value) : ?>
                                    <option value="<?= $value; ?>"><?= ucfirst($value); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                    </select></td>
                    <td><input class="form-control form-control-sm datepicker fm-required" data-date-end-date="0d" type="text" name="family_date_of_birth[` + family_line_id + `]" id="family_date_of_birth_` + family_line_id + `"></td>
                    <td>
                    <select class="form-control form-control-sm show-tick select2 fm-required-opt" name="family_occupation[` + family_line_id + `]" id="family_occupation_` + family_line_id + `">
                            <option value="" >Select</option>
                            <?php if ($occupations) : ?>
                                <?php foreach ($occupations as $value) : ?>
                                    <option value="<?= $value->occupation_id; ?>"><?= $value->name; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                    </select>
                    </td>
                    <td><input class="form-control form-control-sm fm-required-opt fm-mob" type="text" name="family_mobile_number[` + family_line_id + `]" id="family_mobile_number_` + family_line_id + `"></td>
                    <td>                
                    <select class="form-control form-control-sm show-tick select2 fm-required" name="family_emergency_contact[` + family_line_id + `]" id="family_emergency_contact_` + family_line_id + `">                                                        
                                                <option value="no">No</option>
                                                <option value="yes">Yes</option>
                                                    
                                            </select>
                    
                    </td>
                </tr>`;

                    if (family_line_id >= 1) {
                        if (validation_line_item(family_line_id, '#family_name_')) {
                            $('#family_show').append(familyHtml);
                            family_line_id++;
                        }

                    } else {
                        $('#family_show').append(familyHtml);
                        family_line_id++;
                    }

                    $('.datepicker').datepicker({
                        format: 'dd-M-yyyy',
                        todayHighlight: true,
                        autoclose: true
                    });

                    $("#stage_2_form .fm-required").each(function() {

                        $(this).rules('add', {
                            required: true,
                        });

                    });

                    $("#stage_2_form .fm-mob").each(function() {

                        $(this).rules('add', {
                            number: true,
                            maxlength: 10,
                            minlength: 10
                        });

                    });

                });
            });

            function passport_disable() {
                if ($("#valid_passport").val() != "yes") {
                    $("#passport_number").val("").addClass("d-none");
                    $("#passport_validity").val("").addClass("d-none");
                    $("#business_visa_usa").val("no").addClass("d-none");
                    $("#visa_validity").val("").addClass("d-none");
                    $(".vpassport").addClass("d-none");
                } else {
                    $("#passport_number").removeClass("d-none").attr("disabled", false);
                    $("#passport_validity").removeClass("d-none").attr("disabled", false);
                    $("#business_visa_usa").removeClass("d-none").attr("disabled", false);
                    $("#visa_validity").removeClass("d-none").attr("disabled", false);
                    $(".vpassport").removeClass("d-none");
                }

                visa_disable();
            }

            function visa_disable() {
                if ($("#business_visa_usa").val() != "yes") {
                    $("#visa_validity").val("").addClass("d-none");
                    $(".vvisa").addClass("d-none");
                } else {
                    $("#visa_validity").removeClass("d-none").attr("disabled", false);
                    $(".vvisa").removeClass("d-none");
                }
            }

            function change_state(country_id, state_field, selected = null) {
                var country_id = country_id;
                $.ajax({
                    type: "post",
                    url: base_url + "user/get_states",
                    data: "country_id=" + country_id,
                    cache: false,
                    async: false,
                    success: function(response) {
                        data = JSON.parse(response);
                        $('#' + state_field).empty();
                        var html = '<option value="">Select</option>';
                        for (i = 0; i < data.length; i++) {
                            if (selected) {
                                if (selected == data[i].state_id) {
                                    html += '<option value="' + data[i].state_id + '" selected>' + data[i].name + '</option>';
                                } else {
                                    html += '<option value="' + data[i].state_id + '">' + data[i].name + '</option>';
                                }
                            } else {
                                html += '<option value="' + data[i].state_id + '">' + data[i].name + '</option>';
                            }
                        }
                        $('#' + state_field).append(html);
                    }
                });

            }

            function change_district(state_id, district_field, selected = null) {
                var state_id = state_id;
                $.ajax({
                    type: "post",
                    url: base_url + "user/get_district",
                    data: "state_id=" + state_id,
                    cache: false,
                    async: false,
                    success: function(response) {
                        data = JSON.parse(response);
                        $('#' + district_field).empty();
                        var html = '<option value="">Select</option>';
                        for (i = 0; i < data.length; i++) {
                            if (selected) {
                                if (selected == data[i].district_id) {
                                    html += '<option value="' + data[i].district_id + '" selected>' + data[i].name + '</option>';
                                } else {
                                    html += '<option value="' + data[i].district_id + '">' + data[i].name + '</option>';
                                }
                            } else {

                                html += '<option value="' + data[i].district_id + '">' + data[i].name + '</option>';
                            }
                        }
                        $('#' + district_field).append(html);
                    }
                });
            }

            function remove_line_item(line_id, id_name = null) {
                if (id_name) {
                    $(id_name + line_id).closest('tr').remove();
                } else {
                    $('#remove_line_' + line_id).closest('tr').remove();
                }
            }

            /* Validataion line item */
            function validation_line_item(line_id, validate_id = null) {

                previous_line_id = line_id - 1;

                if (validate_id) {
                    var item = $(validate_id + previous_line_id).val();
                    if (item == "") {
                        toastr.options.closeButton = true;
                        toastr.options.positionClass = 'toast-top-right';
                        toastr['error']('Please enter required fields');
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    var course = $('#course_id_' + previous_line_id).val();
                    if (course == "") {
                        toastr.options.closeButton = true;
                        toastr.options.positionClass = 'toast-top-right';
                        toastr['error']('Please enter required fields');
                        return false;
                    } else {
                        return true;
                    }
                }
            }

            function salaryslip_disable() {
                if ($("#employment_history").val() == "yes") {
                    $("#employment_history_list").show();
                    $(
                        "#stage_2_form #salary_slip_last3, #stage_2_form #salary_breakup"
                    ).removeClass("d-none");
                    $(".emphistory").removeClass("d-none");
                } else {
                    $("#employment_history_list").hide();
                    $(
                        "#stage_2_form #salary_slip_last3, #stage_2_form #salary_breakup"
                    ).addClass("d-none");
                    $(".emphistory").addClass("d-none");
                }
            }
        </script>

        <!-- stage 3 form script -->
        <script type="text/javascript">
            $(document).ready(function() {

                $("#stage_3_form").validate({
                    // ignore: ':hidden:not(#skill_id)',
                    rules: {
                        'resignation_proof[]': {
                            required: true,
                            extension: "png|jpg|jpeg|pdf",
                            maxupload: 3,
                            maxfilesize: 2
                        },
                        'resignation_accept_proof[]': {
                            required: true,
                            extension: "png|jpg|jpeg|pdf",
                            maxupload: 3,
                            maxfilesize: 2
                        },
                        'authorization_letter[]': {
                            required: true,
                            extension: "pdf",
                            maxupload: 3,
                            maxfilesize: 2
                        }
                    },
                    messages: {

                    },
                    errorPlacement: function(error, element) {
                        if (element.hasClass('select2') && element.next('.select2-container').length) {
                            error.insertAfter(element.next('.select2-container')).addClass('d-block');
                        } else {
                            element.closest('.form-group').append(error);
                        }
                    },
                    submitHandler: function(form, e) {
                        e.preventDefault();
                    }

                });
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function() {

                $("#employee_add").validate({
                    submitHandler: function(form, e) {
                        e.preventDefault();
                        var form_1 = $("#stage_1_form");
                        var form_2 = $("#stage_2_form");
                        var form_3 = $("#stage_3_form");
                        var form_4 = $("#stage_4_form");
                        var form_5 = $("#stage_5_form");
                        // form_5.submit();
                        // form_4.submit();
                        form_3.submit();
                        form_2.submit();
                        form_1.submit();
                        var form_1_valid = form_1.valid();
                        var form_2_valid = form_2.valid();
                        var form_3_valid = form_3.valid();
                        // var form_4_valid = form_4.valid();
                        // var form_5_valid = form_5.valid();

                        if (form_1_valid && form_2_valid && form_3_valid && form_4_valid && form_5_valid) {

                            $(form).find(':submit').prop('disabled', true).text('Processing...');
                            var form_data = $(form_1, form_2,form_3).serialize();
                            // var form_data = new FormData(form);
                            // console.log(form_data)
                            setTimeout(function() {
                                $.ajax({
                                    type: 'POST',
                                    url: '<?= base_url('employee/employee_add_post') ?>',
                                    cache: false,
                                    async: false,
                                    data: form_data,
                                    contentType: false,
                                    processData: false,
                                    success: function(response) {
                                        var obj = $.parseJSON(response);
                                        if (obj.status == 1) {

                                            toaster('success', obj.msg);
                                            setTimeout(function() {
                                                window.location.href = '<?= base_url('employee/view_referred_candidates') ?>';
                                            }, 1000);
                                        } else {
                                            toaster('error', obj.msg);
                                            $(form).find(':submit').prop('disabled', false).text('Submit');
                                        }

                                    },
                                    error: function(error) {
                                        toaster('error', error);
                                        $(form).find(':submit').prop('disabled', false).text('Submit');
                                    }
                                });

                            }, 500);
                        }
                        return false;
                    }

                });

            });
        </script>