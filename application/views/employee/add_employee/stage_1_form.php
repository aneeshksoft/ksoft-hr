<form action="" name="stage_1_form" id="stage_1_form" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <!-- stage 1 form -->
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
                                        <select onchange="setcurrentctc(this.value)" class="form-control form-control-sm" name="currently_employed">
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
                                        <select class="form-control form-control-sm" name="available_joining_id">
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