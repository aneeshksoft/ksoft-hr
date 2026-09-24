<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<?php
$user_docs = base_url('assets/uploads/user_docs/candidates_docs/' . $employee['employee_id'] . '/');

?>
<div class="row">
    <div class="col-sm-12">

        <div class="card">
            <div class="card-header bg-white border-bottom-0">
                <?php $this->load->view("employee/nav_tab", array('_employee_id' => $employee['employee_id'])); ?>
            </div>
            <div class="card-body">
                <form action="" name="edit_employee" id="edit_employee" method="POST" enctype="multipart/form-data">
                    <? //= pr($employee);
                    ?>

                    <div class="row">

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" class="form-control form-control-sm" disabled value="<?= $employee['code'] ?>">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control form-control-sm" name="name" id="name" value="<?= $employee['name'] ?>">
                            </div>
                        </div>

                        <div class="col-sm-4 col_personal_email">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="text" class="form-control form-control-sm" name="email" id="email" value="<?= $employee['email'] ?>">
                            </div>
                        </div>

                        <div class="col-sm-4 col_mobile_number">
                            <div class="form-group">
                                <label for="mobile_number">Mobile Number<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm" name="mobile_number" id="mobile_number" value="<?= $employee['mobile_number'] ?>">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Gender<sup>*</sup></label>
                                <select class="form-control form-control-sm" name="gender" id="gender">
                                    <option value="">Select Gender</option>
                                    <?php if ($genders) : ?>
                                        <?php foreach ($genders as $gender) : ?>
                                            <option value="<?= $gender; ?>" <?= ($gender == $employee['gender']) ? "selected" : "" ?>><?= ucfirst($gender); ?></option>
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
                                            <option value="<?= $value['nationality_id'] ?>" <?= ($value['nationality_id'] == $employee['nationality_id']) ? "selected" : "" ?>> <?= $value['name']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Religion<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm" name="religion" id="religion" value="<?= $employee['religion'] ?>">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Category<sup>*</sup></label>
                                <select class="form-control form-control-sm" name="category_id" id="category_id">
                                    <option value="">Select Category</option>
                                    <?php if ($categories) : ?>
                                        <?php foreach ($categories as $cat) : ?>
                                            <option value="<?= $cat['category_id']; ?>" <?= ($cat['category_id'] == $employee['category_id']) ? "selected" : "" ?>><?= $cat['name']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Aadhaar Number<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm" name="aadhar_number" id="aadhar_number" value="<?= $employee['aadhar_number'] ?>">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Upload Aadhaar<sup>*</sup><span data-toggle="popover" data-content="Upload document in .pdf or .png or .jpeg or .jpg Format"><i class="icon-info"></i></span></label>
                                <input type="file" class="form-control form-control-sm" name="aadhar_card[]" id="aadhar_card" multiple>
                                <?= display_images($employee['aadhar_card'], $employee['employee_id']); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Date of Birth<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm datepicker" name="date_of_birth" id="date_of_birth" value="<?= get_date($employee['date_of_birth']) ?>">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Marital Status<sup>*</sup></label>
                                <select class="form-control form-control-sm" name="marital_status" id="marital_status">
                                    <option value="">Select</option>
                                    <?php if ($marital_status) : ?>
                                        <?php foreach ($marital_status as $marital) : ?>
                                            <option value="<?= $marital; ?>" <?= ($marital == $employee['marital_status']) ? "selected" : "" ?>><?= ucfirst($marital); ?>
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
                                            <option value="<?= $bloodGroups; ?>" <?= ($bloodGroups == $employee['blood_group']) ? "selected" : "" ?>><?= ucfirst($bloodGroups); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>PAN Number<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm" name="pan_number" id="pan_number" value="<?= $employee['pan_number'] ?>">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>PAN Card<sup>*</sup><span data-toggle="popover" data-content="Upload document in .pdf or .png or .jpeg or .jpg  Format"><i class="icon-info"></i></span></label>
                                <input type="file" class="form-control form-control-sm" name="pan_card[]" id="pan_card" accept="image/png,image/jpg,image/jpeg,application/pdf" multiple>
                                <?= display_images($employee['pan_card'], $employee['employee_id']); ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Latest Passport Size photo<sup>*</sup><span data-toggle="popover" data-content="Please upload the recent passport size photo (Not older than six months), in .jpeg or .png or jpg Format"><i class="icon-info"></i></span></label>
                                <input type="file" class="form-control form-control-sm" name="latest_photo[]" id="photo">
                                <?= display_images($employee['photo'], $employee['employee_id']); ?>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <hr />
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Do you have a valid passport?<sup>*</sup></label>
                                <select class="form-control form-control-sm" name="valid_passport" id="valid_passport" onchange="onchange_valid_passport(this.value)">
                                    <option value="">Select</option>
                                    <?php if ($valid_passport) : ?>
                                        <?php foreach ($valid_passport as $passport) : ?>
                                            <option value="<?= $passport; ?>" <?= (($passport == $employee['valid_passport']) ? 'selected' : ''); ?>><?= ucfirst($passport); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 valid_passport">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Passport Number<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm passverify uppercase" name="passport_number" id="passport_number" value="<?= $employee['passport_number'] ?>">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Valid Until<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm datepicker" data-date-start-date="0d" name="passport_validity" id="passport_validity" value="<?= get_date($employee['passport_validity']) ?>">
                                    </div>
                                </div>
                                <div class="col-sm-12"></div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Do you have Active Business Visa of USA?<sup>*</sup></label>
                                        <select class="form-control form-control-sm" name="business_visa_usa" id="business_visa_usa" onchange="onchange_business_visa_usa(this.value)">
                                            <option value="">Select</option>
                                            <?php if ($business_visa_usa) : ?>
                                                <?php foreach ($business_visa_usa as $value) : ?>
                                                    <option value="<?= $value; ?>" <?= (($value == $employee['business_visa_usa']) ? 'selected' : ''); ?>><?= ucfirst($value); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 business_visa_usa">
                                    <div class="form-group">
                                        <label class="vvisa">Visa Valid Until<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm datepicker" data-date-start-date="0d" name="visa_validity" id="visa_validity" value="<?= get_date($employee['visa_validity']) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <hr />
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <h5>Present Address: </h5>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Country<sup>*</sup></label>
                                        <select class="form-control form-control-sm select2" name="pre_country_id" id="pre_country_id" onchange="change_state(this.value,'pre_state_id')">
                                            <option value="">Select</option>
                                            <?php if ($countries) : ?>
                                                <?php foreach ($countries as $value) : ?>
                                                    <option value="<?= $value['country_id']; ?>"><?= $value['name']; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>State<sup>*</sup></label>
                                        <select class="form-control form-control-sm select2" name="pre_state_id" id="pre_state_id" onchange="change_district(this.value,'pre_district_id')">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>District<sup>*</sup></label>
                                        <select class="form-control form-control-sm select2" name="pre_district_id" id="pre_district_id">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>PIN Code<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm" name="pre_pincode" id="pre_pincode" value="<?= $employee['pre_pincode'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Rest of the Address<sup>*</sup><span data-toggle="popover" data-content="Residence Name, Number, Road, Locality"><i class="icon-info"></i></span></label>
                                        <textarea name="pre_address" maxlength="222" id="pre_address" class="form-control form-control-sm"><?= $employee['pre_address'] ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Present Address Proof<sup>*</sup><a href="javascript:;" class="has-popover ml-2" data-toggle="popover" data-content="Landline/Mobile bill/Electricity Bill/Gas Connection Bill for past two months only if its on self/parents/spouse name OR Rental Agreement OR Ration Card in .pdf or .jpeg or .jpg or .png"><i class="icon-info"></i></a></label>
                                        <input type="file" class="form-control form-control-sm " name="pre_address_proof[]" id="pre_address_proof" value="" multiple>
                                        <?= display_images($employee['pre_address_proof'], $employee['employee_id']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <hr />
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Is Permanent Address same as Present?&nbsp;&nbsp;
                                    <div class="d-inline-block">
                                        <select class="form-control form-control-sm" name="address_same" id="address_same" onchange="handle_address_same_change()">
                                            <option value="">Select</option>
                                            <?php if ($address_same) : ?>
                                                <?php foreach ($address_same as $value) : ?>
                                                    <option value="<?= $value; ?>"><?= ucfirst($value) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
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
                                        <select class="form-control form-control-sm select2" name="country_id" id="country_id" onchange="change_state(this.value,'state_id')">
                                            <option value="">Select</option>
                                            <?php if ($countries) : ?>
                                                <?php foreach ($countries as $value) : ?>
                                                    <option value="<?= $value['country_id']; ?>">
                                                        <?= $value['name']; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>State<sup>*</sup></label>
                                        <select class="form-control form-control-sm select2" name="state_id" id="state_id" onchange="change_district(this.value,'district_id')">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>District<sup>*</sup></label>
                                        <select class="form-control form-control-sm select2" name="district_id" id="district_id">
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>PIN Code<sup>*</sup></label>
                                        <input type="text" class="form-control form-control-sm" name="pincode" id="pincode" value="<?= $employee['pincode'] ?>">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Rest of the Address<sup>*</sup><span data-toggle="popover" data-content="Residence Name, Number, Road, Locality"><i class="icon-info"></i></span></label>
                                        <textarea name="address" maxlength="222" id="address" class="form-control form-control-sm"><?= $employee['address'] ?></textarea>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <hr />
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>WFH/Office/Hybrid<sup>*</sup></label>
                                <select class="form-control form-control-sm select2" name="wfh_or_office" id="wfh_or_office">
                                    <option value="">Select</option>
                                    <?php if ($wfh_or_office) : ?>
                                        <?php foreach ($wfh_or_office as $value) : ?>
                                            <option value="<?= $value; ?>"><?= ucfirst($value); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Department<sup>*</sup></label>
                                <select class="form-control form-control-sm select2" name="department_id" id="department_id" onchange="change_sub_departments(this.value)">
                                    <option value="">Select</option>
                                    <?php if ($departments) : ?>
                                        <?php foreach ($departments as $department) : ?>
                                            <option value="<?= $department['department_id']; ?>"><?= $department['name']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Sub Department<sup>*</sup></label>
                                <select class="form-control form-control-sm select2" name="sub_department_id" id="sub_department_id">
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Designation<sup>*</sup></label>
                                <select class="form-control form-control-sm select2" name="designation_id" id="designation_id">
                                    <option value="">Select</option>
                                    <?php if ($designations) : ?>
                                        <?php foreach ($designations as $designation) : ?>
                                            <option value="<?= $designation['designation_id']; ?>"><?= $designation['name']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Work Location<sup>*</sup></label>
                                <select class="form-control form-control-sm select2" name="work_location_id" id="work_location_id">
                                    <option value="">Select</option>
                                    <?php if ($work_locations) : ?>
                                        <?php foreach ($work_locations as $work_location) : ?>
                                            <option value="<?= $work_location['work_location_id']; ?>"><?= $work_location['name']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Shift<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm" name="shift" id="shift" value="<?= $employee['shift'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Vacancy Type<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm" name="vacancy_type" id="vacancy_type" value="<?= $employee['vacancy_type'] ?>">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Band<sup>*</sup></label>
                                <select class="form-control form-control-sm select2" name="band" id="band">
                                    <option value="">Select</option>
                                    <?php if ($bands) : ?>
                                        <?php foreach ($bands as $band) : ?>
                                            <option value="<?= $band; ?>"><?= $band; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Sub Band<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm" name="sub_band" id="sub_band" value="<?= $employee['sub_band'] ?>">
                            </div>
                        </div>


                        <div class="col-sm-12 text-right">
                            <div class="mt-4">
                                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                            </div>
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
        // set values on page load

        $('#valid_passport').val('<?= $employee['valid_passport'] ?>').trigger('change');
        $('#business_visa_usa').val('<?= $employee['business_visa_usa'] ?>').trigger('change');
        $('#pre_country_id').val('<?= $employee['pre_country_id'] ?>').trigger('change');
        $('#pre_state_id').val('<?= $employee['pre_state_id'] ?>').trigger('change');
        $('#pre_district_id').val('<?= $employee['pre_district_id'] ?>').trigger('change');
        $('#address_same').val('<?= $employee['address_same'] ?? 'no' ?>').trigger('change');
        $('#country_id').val('<?= $employee['country_id'] ?>').trigger('change');
        $('#state_id').val('<?= $employee['state_id'] ?>').trigger('change');
        $('#district_id').val('<?= $employee['district_id'] ?>').trigger('change');
        $('#wfh_or_office').val('<?= $employee['wfh_or_office'] ?>').trigger('change');
        $('#department_id').val('<?= $employee['department_id'] ?>').trigger('change');
        $('#sub_department_id').val('<?= $employee['sub_department_id'] ?>').trigger('change');
        $('#designation_id').val('<?= $employee['designation_id'] ?>').trigger('change');
        $('#work_location_id').val('<?= $employee['work_location_id'] ?>').trigger('change');
        $('#band').val('<?= $employee['band'] ?>').trigger('change');

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });

        $('.select2').select2({
            width: '100%'
        }).on('change', function() {
            $(this).valid();
        });

        $("#edit_employee").validate({
            // ignore: ':hidden:not(#skill_id)',
            rules: {
                // stage 1 form
                name: {
                    required: true,
                    alphaspaces: true
                },
                email: {
                    required: true,
                    email: true
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
                date_of_birth: {
                    required: true
                },
                marital_status: {
                    required: true
                },
                blood_group: {
                    required: true
                },
                pan_number: {
                    required: true
                },
                "pan_card[]": {
                    required: function(element) {
                        if ('<?= $employee['pan_card'] ?>') {
                            return false;
                        }
                        return true;
                    },
                    extension: "png|jpg|jpeg|pdf",
                    maxupload: 3,
                    maxfilesize: 2
                },
                "latest_photo[]": {
                    required: function(element) {
                        if ('<?= $employee['photo'] ?>') {
                            return false;
                        }
                        return true;
                    },
                    extension: "png|jpg|jpeg|pdf",
                    maxupload: 3,
                    maxfilesize: 2
                },
                valid_passport: {
                    required: true
                },
                passport_number: {
                    required: true
                },
                passport_validity: {
                    required: true
                },
                business_visa_usa: {
                    required: true
                },
                visa_validity: {
                    required: true
                },

                pre_country_id: {
                    required: true
                },
                pre_state_id: {
                    required: true
                },
                pre_district_id: {
                    required: true
                },
                pre_pincode: {
                    required: true,
                    minlength: 5,
                    maxlength: 6,
                    digits: true
                },
                pre_address: {
                    required: true
                },
                "pre_address_proof[]": {
                    required: function(element) {
                        if ('<?= $employee['photo'] ?>') {
                            return false;
                        }
                        return true;
                    },
                    extension: "png|jpg|jpeg|pdf",
                    maxupload: 3,
                    maxfilesize: 2
                },

                address_same: {
                    required: true
                },
                country_id: {
                    required: true
                },
                state_id: {
                    required: true
                },
                district_id: {
                    required: true
                },
                pincode: {
                    required: true,
                    minlength: 5,
                    maxlength: 6,
                    digits: true
                },
                address: {
                    required: true
                },
                wfh_or_office: {
                    required: true
                },
                department_id: {
                    required: true
                },
                sub_department_id: {
                    required: true
                },
                designation_id: {
                    required: true
                },
                work_location_id: {
                    required: true
                },
                shift: {
                    required: true
                },
                vacancy_type: {
                    required: true
                },
                band: {
                    required: true
                },
                sub_band: {
                    required: true
                },



            },
            // messages: {

            // },
            errorPlacement: function(error, element) {
                if (element.hasClass('select2') && element.next('.select2-container').length) {
                    error.insertAfter(element.next('.select2-container')).addClass('d-block');
                } else {
                    element.closest('.form-group').append(error);
                }
            },
            submitHandler: function(form, e) {
                e.preventDefault();
                $(form).find(':submit').prop('disabled', true).text('Processing...')
                var form_data = new FormData(form);
                form_data.append('employee_id', '<?= $employee['employee_id'] ?>');
                // console.log(form_data)
                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url('employee/edit_employee_post') ?>',
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
                                    window.location.reload();
                                }, 1000);
                            } else {
                                toaster('error', obj.msg);
                                $(form).find(':submit').prop('disabled', false).text('Update');
                            }

                        },
                        error: function(error) {
                            toaster('error', error);
                            $(form).find(':submit').prop('disabled', false).text('Update');
                        }
                    });

                }, 500);
                return false;
            }

        });

        // $('#address_same').on('change', function() {
        //     console.log($(this).val())
        //     if ($(this).val() == 'yes') {
        //         $('#permanent_address').hide();
        //     } else {
        //         $('#permanent_address').show();
        //     }
        // });
    });

    function onchange_valid_passport(value) {
        if (value == 'yes') {
            $('.valid_passport').show();
        } else {
            $('.valid_passport').hide();
        }
    }

    function onchange_business_visa_usa(value) {
        if (value == 'yes') {
            $('.business_visa_usa').show();
        } else {
            $('.business_visa_usa').hide();
        }
    }

    function handle_address_same_change() {
        var val = $('#address_same').val();
        // console.log(val)
        if (val == 'yes') {
            $('#permanent_address').hide();
        } else {
            $('#permanent_address').show();
        }
    }

    function change_sub_departments(department_id) {
        var department_id = department_id;
        $.ajax({
            type: "post",
            url: base_url + "employee/get_sub_departments",
            data: "department_id=" + department_id,
            cache: false,
            async: false,
            success: function(response) {
                data = JSON.parse(response);
                $('#sub_department_id').empty();
                var html = '<option value="">Select</option>';
                for (i = 0; i < data.length; i++) {

                    html += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                }
                $('#sub_department_id').append(html);
            }
        });


    }

    function change_state(country_id, state_field) {
        var country_id = country_id;
        $.ajax({
            type: "post",
            url: base_url + "employee/get_states",
            data: "country_id=" + country_id,
            cache: false,
            async: false,
            success: function(response) {
                data = JSON.parse(response);
                $('#' + state_field).empty();
                var html = '<option value="">Select</option>';
                for (i = 0; i < data.length; i++) {

                    html += '<option value="' + data[i].state_id + '">' + data[i].name + '</option>';
                }
                $('#' + state_field).append(html);
            }
        });



    }

    function change_district(state_id, district_field, selected = null) {
        var state_id = state_id;
        $.ajax({
            type: "post",
            url: base_url + "employee/get_district",
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
</script>