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
                                    <div class="row clearfix">
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