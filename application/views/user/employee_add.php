<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/dropify/css/dropify.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css">
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <form class="form-auth-small" action="" name="employee_add" id="employee_add" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Employee Code<sup>*</sup></label>
                                <input type="text" class="form-control" name="code" autofocus>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Password<sup>*</sup></label>
                                <input type="text" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Employee Name<sup>*</sup></label>
                                <input type="text" class="form-control" name="name">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Status<sup>*</sup></label>
                                <select class="form-control show-tick" name="status">
                                    <option value="">Select Status</option>
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="leave">Leave</option>
                                    <option value="resigned">Resigned</option>
                                    <option value="noticeperiod">Notice Period</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Department<sup>*</sup></label>
                                <select class="form-control show-tick" name="department_id">
                                    <option value="">Select Department</option>
                                    <?php foreach ($departments as $row) { ?>
                                        <option value="<?= $row['department_id'] ?>"><?= $row['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Designation<sup>*</sup></label>
                                <select class="form-control show-tick" name="designation_id">
                                    <option value="">Select Designation</option>
                                    <?php foreach ($designations as $row) { ?>
                                        <option value="<?= $row['designation_id'] ?>"><?= $row['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <label>Role<sup>*</sup></label>
                            <div class="form-group mselect">
                                <select id="multiselect3-all" class="multiselect multiselect-custom form-control" name="role_id[]" multiple="multiple">
                                    <?php foreach ($roles as $row) { ?>
                                        <option value="<?= $row['role_id'] ?>" <?= ($row['role_id'] == '1') ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Date of birth</label>
                                <input type="text" name="date_of_birth" class="form-control" onchange="calcAge(this)">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Age</label>
                                <input type="text" class="form-control number" name="age" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Date of join<sup>*</sup></label>
                                <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="date_of_join" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Last worked date</label>
                                <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="last_work_date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Current address</label>
                                <textarea class="form-control" name="current_address" rows="4" cols="30"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Permanent address</label>
                                <textarea class="form-control" name="permanent_address" rows="4" cols="30"></textarea>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Local Mob. No.</label>
                                <input type="text" class="form-control number" name="phone_current">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Home Phone</label>
                                <input type="text" class="form-control number" name="phone_home">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Office Phone</label>
                                <input type="text" class="form-control number" name="phone_office">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Official Email Address</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Personal Email Address</label>
                                <input type="email" class="form-control" name="personal_email">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Nationality</label>
                                <input type="text" class="form-control" name="nationality">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <?php $countries = get_countries(); ?>
                                <label>Country</label>
                                <select class="form-control show-tick" name="country">
                                    <option value="">Select country</option>
                                    <?php
                                    foreach ($countries as $key => $value) {
                                    ?>
                                        <option value="<?= $key ?>"><?= $value ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label>Overtime</label>
                                <select class="form-control show-tick" name="overtime">
                                    <option value="yes">YES</option>
                                    <option value="no">NO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label>Basic pay</label>
                                <input type="text" class="form-control amount" name="basic_pay">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label>HRA</label>
                                <input type="text" class="form-control amount" name="hra">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label>Transport allowance</label>
                                <input type="text" class="form-control amount" name="transport">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label>Special allowance</label>
                                <input type="text" class="form-control amount" name="special_allowance">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label>Other allowance</label>
                                <input type="text" class="form-control amount" name="others">
                            </div>
                        </div>

                    </div>

                    <div class="row clearfix">
                        <div class="col-12">
                            <label>Profile Photo</label>
                            <input type="file" name="file_name" id="file_name" class="dropify">
                            <div class="mt-3"></div>
                        </div>

                        <div class="col-sm-12">
                            <div class="mt-4">
                                <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Save & Continue" />
                                <input type="reset" class="btn btn-lg btn-danger" value="Cancel">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?= base_url() ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script src="<?= base_url() ?>assets/vendor/dropify/js/dropify.min.js"></script>
<script src="<?= base_url() ?>assets/user/js/pages/forms/dropify.js"></script>
<script type="text/javascript">
    $(function() {

        $('#multiselect3-all').multiselect({
            includeSelectAllOption: false,
        });

        $('input[name="date_of_birth"]').datepicker({
            todayHighlight: true,
            autoclose: true,
            format: 'dd/mm/yyyy',
            startView: 2
        });

        $("#employee_add").validate({
            ignore: ':hidden:not("#multiselect3-all")',
            errorPlacement: function(error, element) {
                if (element.attr("name") == "role_id[]")
                    error.insertAfter(".btn-group");
                else
                    error.insertAfter(element);
            },
            rules: {
                code: {
                    required: true,
                    remote: {
                        url: "user/checkDupCode",
                        type: "post"
                    }
                },
                date_of_join: {
                    required: true
                },
                password: {
                    required: true
                },
                name: {
                    required: true
                },
                status: {
                    required: true
                },
                department_id: {
                    required: true
                },
                designation_id: {
                    required: true
                },
                age: {
                    max: 100,
                    min: 10
                },
                email: {
                    email: true,
                    remote: {
                        url: "user/checkDupEmail",
                        type: "post"
                    }
                },
                'role_id[]': {
                    required: true
                },
            },
            messages: {
                code: {
                    required: "Please enter employee code",
                    remote: "Employee code already exist"
                },
                date_of_join: "Please enter date of join",
                password: "Please enter password",
                name: "Please enter employee name",
                status: "Please choose status",
                department_id: "Please choose department",
                designation_id: "Please choose designation",
                email: {
                    email: "Invalid email format",
                    remote: "Email already exist"
                },
                'role_id[]': {
                    required: "Please choose role"
                }
            },
            submitHandler: function(form, e) {
                e.preventDefault();
                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');

                var form_data = new FormData(form);
                //append files
                var file = document.getElementById('file_name').files[0];
                if (file) {
                    form_data.append('file_name', file);
                }

                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'user/employee_add_process',
                        cache: false,
                        async: false,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {

                            var obj = $.parseJSON(response);
                            if (obj.status == 1) {
                                showRegistrationMessage(obj.code);
                            } else {
                                toaster('error', obj.msg);
                                $('.btnsmt').prop('disabled', false).attr('value', 'Save & Continue');
                            }

                        },
                        error: function(error) {
                            toaster('error', error);
                            $('.btnsmt').prop('disabled', false).attr('value', 'Save & Continue');
                        }
                    });
                }, 500);
                return false;
            }
        });

    });
</script>