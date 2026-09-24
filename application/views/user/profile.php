<?php
if (!empty($employee_details)) {
    $countries = get_countries();
    //pr($employee_details['roles']);
    //pr($employee_details);
?>
    <div class="row clearfix profilepage_2 blog-page">
        <div class="col-lg-3 col-md-12">
            <div class="card profile-header">
                <div class="body">
                    <div class="profile-image"> <img src="<?= getUserImage($employee_details['profile_photo']) ?>" class="rounded-circle" alt="<?= $employee_details['name'] ?>"> </div>
                    <div>
                        <h6 class="m-b-1"><strong><?= $employee_details['name'] ?></strong></h6>
                        <div>Code: <strong><?= $employee_details['code'] ?></strong></div>
                        <div class="mt-1"><?= employee_status_c($employee_details['status']) ?></div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="header">
                    <h2>Assigned Roles</h2>
                </div>
                <div class="body pt-0">
                    <?php if (!empty($employee_details['roles'])) {
                        foreach ($employee_details['roles'] as $row) { ?>
                            <span class="badge badge-primary"><?= $row['name'] ?></span>
                        <?php }
                    } else { ?>
                        -
                    <?php } ?>
                </div>
            </div>
            <div class="card">
                <div class="header">
                    <h2>Reporting Heads</h2>

                </div>
                <div class="body pt-0">

                    <?php if (!empty($employee_details['reporting_manager_id'])) { ?>
                        <small class="text-muted"><?= $employee_details['reporting_manager_role'] ?></small>
                        <p><?= $employee_details['reporting_manager'] ?></p>
                    <?php
                    } else { ?>
                        -
                    <?php } ?>
                </div>
            </div>
            <div class="sticky-top profile-menu">
                <div class="card">
                    <div class="body">
                        <ul class="nav">
                            <li>
                                <a href="#general" class="scrollLink"><i class="fa fa-circle text-danger"></i>General Details</a>
                            </li>
                            <li>
                                <a href="#educational" class="scrollLink"><i class="fa fa-circle text-info"></i>Educational Details</a>
                            </li>
                            <li>
                                <a href="#insurance" class="scrollLink"><i class="fa fa-circle text-dark"></i>Insurance Details</a>
                            </li>
                            <li>
                                <a href="#account" class="scrollLink"><i class="fa fa-circle text-primary"></i>Account Details</a>
                            </li>
                            <!-- <li>
                                <a href="#leave" class="scrollLink"><i class="fa fa-circle text-primary"></i>Leaves Available</a>
                            </li>
                            <li>
                                <a href="#leaveh" class="scrollLink"><i class="fa fa-circle text-primary"></i>Leaves History</a>
                            </li> -->
                            <li>
                                <a href="#password" class="scrollLink"><i class="fa fa-circle text-warning"></i>Change Password</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-12">
            <div class="card" id="general">
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-hover m-b-0">
                            <tbody>
                                <tr>
                                    <td width="25%">Code:</td>
                                    <td><?= $employee_details['code'] ?></td>
                                </tr>
                                <tr>
                                    <td>Status:</td>
                                    <td><?= employee_status_n($employee_details['status']) ?></td>
                                </tr>
                                <tr>
                                    <td>Name:</td>
                                    <td><?= $employee_details['name'] ?></td>
                                </tr>
                                <tr>
                                    <td>Mobile No.:</td>
                                    <td><?= $employee_details['mobile_number'] ?? "-" ?></td>
                                </tr>
                                <tr>
                                    <td>Email:</td>
                                    <td><?= $employee_details['email'] ?? "-" ?></td>
                                </tr>
                                <tr>
                                    <td>Present Address:</td>
                                    <td><?= $employee_details['pre_address'] ?></td>
                                </tr>
                                <tr>
                                    <td>District:</td>
                                    <td><?= $employee_details['pre_district'] ?></td>
                                </tr>
                                <tr>
                                    <td>State:</td>
                                    <td><?= $employee_details['pre_state'] ?></td>
                                </tr>
                                <tr>
                                    <td>Country:</td>
                                    <td><?= $employee_details['pre_address'] ?></td>
                                </tr>
                                <tr>
                                    <td>PIN Code:</td>
                                    <td><?= $employee_details['pre_pincode'] ?></td>
                                </tr>
                                <tr>
                                    <td>Permanent address:</td>
                                    <td><?= $employee_details['address'] ?></td>
                                </tr>
                                <tr>
                                    <td>District:</td>
                                    <td><?= $employee_details['per_district'] ?></td>
                                </tr>
                                <tr>
                                    <td>State:</td>
                                    <td><?= $employee_details['per_state'] ?></td>
                                </tr>
                                <tr>
                                    <td>Country:</td>
                                    <td><?= $employee_details['per_country'] ?></td>
                                </tr>
                                <tr>
                                    <td>PIN Code:</td>
                                    <td><?= $employee_details['pincode'] ?></td>
                                </tr>
                                <tr>
                                    <td>Nationality:</td>
                                    <td><?= $employee_details['nationality'] ?></td>
                                </tr>
                                <tr>
                                    <td>Date of birth:</td>
                                    <td><?= get_date($employee_details['date_of_birth']) ?></td>
                                </tr>
                                <tr>
                                    <td>Age:</td>
                                    <td><?= $employee_details['age'] ?></td>
                                </tr>
                                <tr>
                                    <td>Gender:</td>
                                    <td><?= $employee_details['gender'] ?></td>
                                </tr>
                                <tr>
                                    <td>Religion:</td>
                                    <td><?= $employee_details['religion'] ?></td>
                                </tr>
                                <tr>
                                    <td>Category:</td>
                                    <td><?= $employee_details['category'] ?></td>
                                </tr>
                                <tr>
                                    <td>Marital Status:</td>
                                    <td><?= $employee_details['marital_status'] ?></td>
                                </tr>
                                <tr>
                                    <td>Blood Group:</td>
                                    <td><?= $employee_details['blood_group'] ?></td>
                                </tr>

                                <tr>
                                    <td>Date of join:</td>
                                    <td><?= $employee_details['date_of_join'] ?></td>
                                </tr>
                                <tr>
                                    <td>Last worked date:</td>
                                    <td><?= $employee_details['last_work_date'] ?? "-" ?></td>
                                </tr>
                                <tr>
                                    <td>Department:</td>
                                    <td><?= $employee_details['department'] ?></td>
                                </tr>
                                <tr>
                                    <td>Designation:</td>
                                    <td><?= $employee_details['designation'] ?></td>
                                </tr>
                                <tr>
                                    <td>Band:</td>
                                    <td><?= $employee_details['band'] ?? "-" ?></td>
                                </tr>
                                <tr>
                                    <td>Sub Band:</td>
                                    <td><?= $employee_details['sub_band'] ?? "-" ?></td>
                                </tr>
                                <tr>
                                    <td>Probation Status:</td>
                                    <td><?= probation_status($employee_details['probation_status']) ?></td>
                                </tr>

                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card" id="educational">
                <div class="header">
                    <h2>Educational Details</h2>
                </div>
                <div class="body pt-0">
                    <?php if (!empty($employee_details['education'])) { ?>
                        <div class="table-responsive">
                            <table class="table table-hover m-b-0">
                                <thead>
                                    <th>Qualification</th>
                                    <th>Year</th>
                                    <th>Institution</th>
                                    <th>Document(s)</th>
                                </thead>
                                <tbody>
                                    <?php foreach ($employee_details['education'] as $row) { ?>
                                        <tr>
                                            <td><?= ($row['name'] != "") ? $row['name'] : "-"; ?></td>
                                            <td><?= ($row['year'] != "") ? $row['year'] : "-" ?></td>
                                            <td><?= ($row['institution'] != "") ? $row['institution'] : "-" ?></td>
                                            <td>
                                                <?php if (!empty($row['docs'])) { ?>
                                                    <div class="align-items-center d-flex">
                                                        <div class="mb-0 mr-2">Documents:</div>
                                                        <ul class="list-unstyled team-info margin-0">
                                                            <?php foreach ($row['docs'] as $row1) { ?>
                                                                <li><a href="<?= base_url() ?>assets/uploads/user_docs/<?= $row['employee_id'] . '/education/' . $row1['doc_path'] ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Click to open"><img src="<?= base_url() ?>assets/common/preview.svg" alt="Avatar" /></a></li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        -
                    <?php } ?>
                </div>
            </div>
            <div class="card" id="insurance">
                <div class="header">
                    <h2>Insurance Details</h2>
                </div>
                <div class="body pt-0">
                    <?php if (!empty($employee_details['insurance'])) { ?>
                        <div class="table-responsive">
                            <table class="table table-hover m-b-0">
                                <thead>
                                    <th>Provider</th>
                                    <th>Cost</th>
                                    <th>Valid upto</th>
                                    <th>Document(s)</th>
                                </thead>
                                <tbody>
                                    <?php foreach ($employee_details['insurance'] as $row) { ?>
                                        <tr>
                                            <td><?= ($row['company'] != "") ? $row['company'] : "-" ?></td>
                                            <td><?= ($row['cost'] != "") ? (CURRENCY . ' ' . $row['cost']) : "-" ?></td>
                                            <td><?= ($row['expiry'] != "") ? get_date($row['expiry']) : "-" ?></td>
                                            <td>
                                                <?php if (!empty($row['docs'])) { ?>
                                                    <div class="align-items-center d-flex">
                                                        <div class="mb-0 mr-2">Documents:</div>
                                                        <ul class="list-unstyled team-info margin-0">
                                                            <?php foreach ($row['docs'] as $row1) { ?>
                                                                <li><a href="<?= base_url() ?>assets/uploads/user_docs/<?= $row['employee_id'] . '/insurance/' . $row1['doc_path'] ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Click to open"><img src="<?= base_url() ?>assets/common/preview.svg" alt="Avatar" /></a></li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        -
                    <?php } ?>
                </div>
            </div>
            <div class="card" id="account">
                <div class="header">
                    <h2>Account Details</h2>
                </div>
                <div class="body pt-0">
                    <?php if (!empty($employee_details['bank_details'])) { ?>
                        <div class="table-responsive">
                            <table class="table table-hover m-b-0">
                                <thead>
                                    <th>Bank Name</th>
                                    <th>Account Number</th>
                                    <th>IBAN Number</th>
                                    <th>Swift Code</th>
                                    <th>Document(s)</th>
                                </thead>
                                <tbody>
                                    <?php foreach ($employee_details['bank_details'] as $row) { ?>
                                        <tr>
                                            <td><?= ($row['bank_name'] != "") ? $row['bank_name'] : "-" ?></td>
                                            <td><?= ($row['account_number'] != "") ? $row['account_number'] : "-" ?></td>
                                            <td><?= ($row['iban_number'] != "") ? $row['iban_number'] : "-" ?></td>
                                            <td><?= ($row['swift_code'] != "") ? $row['swift_code'] : "-" ?></td>
                                            <td>
                                                <?php if (!empty($row['docs'])) { ?>
                                                    <div class="align-items-center d-flex">
                                                        <ul class="list-unstyled team-info margin-0">
                                                            <?php foreach ($row['docs'] as $row1) { ?>
                                                                <li><a href="<?= base_url() ?>assets/uploads/user_docs/<?= $row['employee_id'] . '/bank/' . $row1['doc_path'] ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Click to open"><img src="<?= base_url() ?>assets/common/preview.svg" alt="Avatar" /></a></li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                <?php } else { ?>
                                                    -
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        -
                    <?php } ?>
                </div>
            </div>

            <div class="card" id="password">
                <div class="header">
                    <h2>Change Password</h2>
                </div>
                <div class="body pt-0">
                    <form class="form-auth-small" action="" name="change_password" id="change_password" method="POST" enctype="multipart/form-data">
                        <div class="row clearfix">
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label>New Password<sup>*</sup></label>
                                    <input type="text" class="form-control" name="password">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label>Confirm Password<sup>*</sup></label>
                                    <input type="password" class="form-control" name="confirm_password">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="mt-4">
                                    <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        $("#change_password").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 3
                },
                confirm_password: {
                    required: true,
                    minlength: 3,
                    equalTo: '[name="password"]'
                }
            },
            messages: {
                password: {
                    required: "Please enter password"
                },
                confirm_password: {
                    required: "Please confirm password",
                    equalTo: "Password mismatch"
                }
            },
            submitHandler: function(form, e) {

                e.preventDefault();

                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');

                var code = '<?= $employee_details['code'] ?>';
                var form_data = new FormData(form);
                form_data.append('code', code);

                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'user/change_password',
                        cache: false,
                        async: false,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            var obj = $.parseJSON(response);
                            if (obj.status == 1) {
                                toaster('success', obj.msg);
                                $('input[name="password"]').val("");
                                $('input[name="confirm_password"]').val("");
                                $('.btnsmt').prop('disabled', false).attr('value', 'Submit');

                            } else {
                                toaster('error', obj.msg);
                                $('.btnsmt').prop('disabled', false).attr('value', 'Submit');
                            }
                        },
                        error: function(error) {
                            toaster('error', error);
                            $('.btnsmt').prop('disabled', false).attr('value', 'Submit');
                        }
                    });
                }, 500);
                return false;
            }
        });
    </script>
<?php } else {
    $this->load->view('theme/user/notfound');
} ?>