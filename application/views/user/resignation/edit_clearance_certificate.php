<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<style>
    .error-text {
        color: red;
    }
</style>
<?php //echo json_encode($employee_details)  
?>
<div class="row clearfix">
    <div class="col-sm-12">
        <?php $no_of_days_short = 0;
        if (!empty($resignation->agreed_relieving_date) && !empty($resignation->relieving_date)) {
            $agreed = new DateTime($resignation->agreed_relieving_date);
            $employee_date = new DateTime($resignation->relieving_date);
            $no_of_days_short =  $employee_date->diff($agreed)->format("%r%a");
            $no_of_days_short = $no_of_days_short < 1 ? 0 : $no_of_days_short;
        }

        $show_on_job_clearance_form = false;
        $show_it_clearance_form     = false;
        $show_admin_clearance_form  = false;
        $show_hod_clearance_form    = false;
        $show_hcm_clearance_form    = false;
        $show_cfo_clearance_form    = false;
        $show_final_clearance_form  = false;

        if ($session_employee_id == $employee_details->reporting_manager_id) {
            $show_on_job_clearance_form = true;
            if ($certificate->is_on_job_added == 1 && ($session_employee_details->special_role == 'Clearance' || $session_employee_id == $employee_details->reporting_manager_id)) {
                $show_it_clearance_form     = true;
            }
        } else if ($certificate->is_on_job_added == 1 && ($session_employee_details->special_role == 'Clearance' || $session_employee_id == $employee_details->reporting_manager_id)) {
            $show_it_clearance_form     = true;
        } else if ($certificate->is_it_added == 1 && $session_employee_details->special_role == 'IT Admin') {
            $show_admin_clearance_form     = true;
        } else if ($certificate->is_admin_added == 1 && $session_employee_details->special_role == 'HOD') {
            $show_hod_clearance_form     = true;
        } else if ($certificate->is_hod_added == 1 && $session_employee_details->special_role == 'HCM') {
            $show_hcm_clearance_form     = true;
        } else if ($certificate->is_hcm_added == 1 && $session_employee_details->special_role == 'CFO') {
            $show_cfo_clearance_form     = true;
        } else if ($certificate->is_cfo_added == 1 && $session_employee_details->special_role == 'Accounts') {
            $show_final_clearance_form     = true;
        }

        $sesion_name = $this->session->userdata('name');
        $sesion_email = $this->session->userdata('email');
        $it_clearance_rm_name=($certificate->is_it_added == 1 && $session_employee_id == $employee_details->reporting_manager_id && $certificate->is_it_validation_added != 1) ? $sesion_name : '';
       
        ?>
        <input type="hidden" class="form-control" id="clearance_certificate_id" name="clearance_certificate_id" value="<?= $clearance_certificate_id ?>"></input>
        <input type="hidden" class="form-control" id="resignation_id" name="resignation_id" value="<?= $resignation_id ?>"></input>

        <div class="col-sm-12">
            <div class="card">
                <div class="body">
                    <input type="hidden" class="form-control" id="employee_id" name="employee_id" value="<?= $employee_id ?>"></input>
                    <div class="container-fluid py-5">
                        <h2 class="text-center">CLEARANCE CERTIFICATE</h2>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="col-md-12 mt-3">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th><label><b>Name:</b></label> <label><?= $employee_details->name ?></label></th>
                                                    <th><label><b>Code:</b></label> <label><?= $employee_details->code ?></label></th>
                                                    <th><label><b>Department:</b></label> <label><?= $employee_details->department ?></label></th>

                                                </tr>

                                            </thead>

                                        </table>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12" <?= ($show_on_job_clearance_form) ? '' : 'hidden' ?>>
                                <form action="" name="on_job_clearance_form" id="on_job_clearance_form" method="POST" enctype="multipart/form-data">
                                    <div class="col-md-12">
                                        <table class="table table-bordered">

                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="radio" class="form-check-input" name="employee_type" value="1" <?= (!empty($certificate->employee_type && $certificate->employee_type == 1)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label">Office Based Employee</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="radio" class="form-check-input" name="employee_type" value="2" <?= (!empty($certificate->employee_type && $certificate->employee_type == 2)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label">Home based Employee</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="radio" class="form-check-input" name="employee_type" value="3" <?= (!empty($certificate->employee_type && $certificate->employee_type == 3)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label">Captive Resource / Contract</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12">
                                        <h6>
                                            1. ON-JOB CLEARANCE:
                                            <span>(To be filled by Reporting Manager)</span>
                                        </h6>
                                        <table class="table table-bordered table-hover mt-3" id="table_on_job_clearance">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th>Duties handled by Employee:<sup>*</sup></th>

                                                </tr>
                                            </thead>
                                            <tbody id="line_items_list">
                                                <?php $duty_size = empty($certificate->duties) ? 0 : count($certificate->duties) - 1;
                                                if (empty($certificate->duties)) { ?>
                                                    <tr id='addr0'>
                                                        <td><?= 1 ?></td>
                                                        <td><input type="text" class="form-control" id="employee_duties_0" name="employee_duties[0]"></td>
                                                    </tr>
                                                <?php } else
                                                    for ($i = 0; $i < count($certificate->duties); $i++) {
                                                        $row = $certificate->duties[$i] ?>
                                                    <tr>
                                                        <td><?= $i + 1 ?></td>
                                                        <td><input type="text" class="form-control" id="employee_duties_<?= $i ?>" name="employee_duties[<?= $i ?>]" value="<?= $row->duty ?>"></td>
                                                    </tr>
                                                <?php } ?>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2">
                                                        <button type="button" id='delete_row' class="pull-right btn btn-danger ml-2 action-btn" style="margin-bottom: 20px; margin: right 20px;">Delete Row</button>
                                                        <button type="button" id="add_row" data-id="<?= $duty_size ?>" class="btn btn-success pull-right action-btn" style="margin-bottom: 20px; ">Add Row</button>
                                                    </th>
                                                </tr>

                                            </tfoot>
                                        </table>
                                        <table class="table table-bordered table-hover" id="table_pending_tasks">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th>List of pending tasks:<sup>*</sup></th>

                                                </tr>
                                            </thead>
                                            <tbody id="line_items_list_2">
                                                <?php $duty_size = empty($certificate->pending_tasks) ? 0 : count($certificate->pending_tasks) - 1;
                                                if (empty($certificate->pending_tasks)) { ?>
                                                    <tr id='addr0'>
                                                        <td><?= 1 ?></td>
                                                        <td><input type="text" class="form-control" id="employee_pending_tasks_0" name="employee_pending_tasks[0]"></td>
                                                    </tr>
                                                <?php } else
                                                    for ($i = 0; $i < count($certificate->pending_tasks); $i++) {
                                                        $row = $certificate->pending_tasks[$i] ?>
                                                    <tr>
                                                        <td><?= $i + 1 ?></td>
                                                        <td><input type="text" class="form-control" id="employee_pending_tasks_<?= $i ?>" name="employee_pending_tasks[<?= $i ?>]" value="<?= $row->task ?>"></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2">
                                                        <button type="button" id='delete_row_2' class="pull-right btn btn-danger ml-2 action-btn" style="margin-bottom: 20px; margin: right 20px;">Delete Row</button>
                                                        <button type="button" id="add_row_2" data-id="<?= $duty_size ?>" class="btn btn-success pull-right action-btn" style="margin-bottom: 20px; ">Add Row</button>
                                                    </th>
                                                </tr>

                                            </tfoot>
                                        </table>
                                        <table class="table table-bordered table-hover" id="table_reports">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th>Reports:<sup>*</sup></th>

                                                </tr>
                                            </thead>
                                            <tbody id="line_items_list_3">
                                                <?php $duty_size = empty($certificate->reports) ? 0 : count($certificate->reports) - 1;
                                                if (empty($certificate->reports)) { ?>
                                                    <tr id='addr0'>
                                                        <td><?= 1 ?></td>
                                                        <td><input type="text" class="form-control" id="employee_reports_0" name="employee_reports[0]"></td>
                                                    </tr>
                                                <?php } else
                                                    for ($i = 0; $i < count($certificate->reports); $i++) {
                                                        $row = $certificate->reports[$i] ?>
                                                    <tr>
                                                        <td><?= 1 ?></td>
                                                        <td><input type="text" class="form-control" id="employee_reports_<?= $i ?>" name="employee_reports[<?= $i ?>]" value="<?= $row->report ?>"></td>
                                                    </tr>
                                                <?php } ?>

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2">
                                                        <button type="button" id='delete_row_3' class="pull-right btn btn-danger ml-2 action-btn" style="margin-bottom: 20px; margin: right 20px;">Delete Row</button>
                                                        <button type="button" id="add_row_3" data-id="<?= $duty_size ?>" class="btn btn-success pull-right action-btn" style="margin-bottom: 20px; ">Add Row</button>
                                                    </th>
                                                </tr>

                                            </tfoot>
                                        </table>

                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Assigned Manager Email<sup>*</sup></th>
                                                    <th width="40%">Date:<sup>*</sup></th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input type="email" class="form-control" id="on_job_reporting_manager_name" name="on_job_reporting_manager_name" value="<?= empty($certificate->on_job_reporting_manager_name) ? $sesion_email : $certificate->on_job_reporting_manager_name ?>"></td>
                                                    <td><input readonly data-date-start-date="today" type="text" class="form-control" id="on_job_date" name="on_job_date" data-provide="datepicker" data-date-format="dd-mm-yyyy" data-date-autoclose="true" value="<?= empty($certificate->on_job_date) || $certificate->on_job_date == '0000-00-00' ? date('d-m-Y') : date('d-m-Y', strtotime($certificate->on_job_date)) ?>"></td>

                                                </tr>

                                            </tbody>

                                        </table>

                                    </div>
                                    <div class="mb-5 text-right">
                                        <div class="col-sm-12">
                                            <div class="mt-2 ">
                                                <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-12" id="it_clearance_div" <?= ($show_it_clearance_form) ? '' : 'hidden' ?>>
                                <h6>2. IT CLEARANCE:</h6>
                                <form action="" name="it_clearance_form" id="it_clearance_form" method="POST" enctype="multipart/form-data">
                                    <table class="table table-bordered mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center">Asset List</th>
                                                <th scope="col" class="text-center">
                                                    Asset details<br /><small>(To be filled by IT Rep)</small>
                                                </th>
                                                <th scope="col" class="text-center">
                                                    Action Required<br /><small>(To be filled by Reporting Manager)</small>
                                                </th>
                                                <th scope="col" class="text-center">Validation by IT Rep</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr hidden>
                                                <td></td>
                                                <td><input type="text" id="it_clearance_section_1" name="it_clearance_section_1" class="it_section_1" value="1" hidden /></td>
                                                <td><input type="text" id="it_clearance_section_2" name="it_clearance_section_2" class="it_section_2" value="2" hidden /></td>
                                                <td><input type="text" id="it_clearance_section_3" name="it_clearance_section_3" class="it_section_3" value="3" hidden /></td>
                                            </tr>
                                            <?php
                                            if (!empty($certificate->it_clearance)) for ($i = 0; $i < count($certificate->it_clearance); $i++) {
                                                $row = $certificate->it_clearance[$i]; ?>
                                                <tr>
                                                    <td>Email ID<sup><?= ($i == 0) ? '*' : '' ?></sup> <?= $i + 1 ?></td>
                                                    <td><input type="text" class="form-control it_section_1" id="it_clearance_email_<?= $i ?>" name="it_clearance_email[<?= $i ?>]" value="<?= $row->it_clearance_email ?>" /></td>
                                                    <td>
                                                        <div class="d-flex justify-content-around it_section_2">
                                                            <div class="align-items-center d-flex form-check form-group mr-2 text-center">
                                                                <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_email_delete_<?= $i ?>" name="it_clearance_email_delete[<?= $i ?>]" <?= (!empty($row->it_clearance_email_delete)) ? 'checked' : '' ?> />
                                                                <label class="form-check-label" for="it_clearance_email_delete_<?= $i ?>">Delete</label>
                                                            </div>
                                                            <div>Alias to:<input type="text" class="form-control it_section_2" id="it_clearance_email_alias_to_<?= $i ?>" name="it_clearance_email_alias_to[<?= $i ?>]" alue="<?= $row->it_clearance_email_alias_to ?>" value="<?= $row->it_clearance_email_alias_to ?>" /></div>&nbsp;
                                                            <div>Assign to:<input type="text" class="form-control it_section_2" id="it_clearance_email_assign_to_<?= $i ?>" name="it_clearance_email_assign_to[<?= $i ?>]" value="<?= $row->it_clearance_email_assign_to ?>" value="<?= $row->it_clearance_email_alias_to ?>" /></div>
                                                        </div>
                                                    </td>
                                                    <td><input type="text" class="form-control it_section_3" id="it_clearance_email_validation_<?= $i ?>" name="it_clearance_email_validation[<?= $i ?>]" value="<?= $row->it_clearance_email_validation ?>" /></td>
                                                </tr>

                                            <?php  }
                                            else { ?>
                                                <tr>
                                                    <td>Email ID 1<sup>*</sup></td>
                                                    <td><input type="text" class="form-control it_section_1" id="it_clearance_email_0" name="it_clearance_email[0]" /></td>
                                                    <td>
                                                        <div class="d-flex justify-content-around">
                                                            <div class="align-items-center d-flex form-check form-group mr-2 text-center">
                                                                <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_email_delete_0" name="it_clearance_email_delete[0]" />
                                                                <label class="form-check-label" for="it_clearance_email_delete_0">Delete</label>
                                                            </div>
                                                            <div>Alias to:<input type="text" class="form-control it_section_2" id="it_clearance_email_alias_to_0" name="it_clearance_email_alias_to[0]" /></div>&nbsp;
                                                            <div>Assign to:<input type="text" class="form-control it_section_2" id="it_clearance_email_assign_to_0" name="it_clearance_email_assign_to[0]" /></div>
                                                        </div>
                                                    </td>
                                                    <td><input type="text" class="form-control it_section_3" id="it_clearance_email_validation_0" name="it_clearance_email_validation[0]" /></td>
                                                </tr>
                                                <tr>
                                                    <td>Email ID 2</td>
                                                    <td><input type="text" class="form-control it_section_1" id="it_clearance_email_1" name="it_clearance_email[1]" /></td>
                                                    <td>
                                                        <div class="d-flex justify-content-around">
                                                            <div class="align-items-center d-flex form-check form-group mr-2 text-center">
                                                                <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_email_delete_1" name="it_clearance_email_delete[1]" />
                                                                <label class="form-check-label" for="it_clearance_email_delete_1">Delete</label>
                                                            </div>
                                                            <div>Alias to:<input type="text" class="form-control it_section_2" id="it_clearance_email_alias_to_1" name="it_clearance_email_alias_to[1]" /></div>&nbsp;
                                                            <div>Assign to:<input type="text" class="form-control it_section_2" id="it_clearance_email_assign_to_1" name="it_clearance_email_assign_to[1]" /></div>
                                                        </div>
                                                    </td>
                                                    <td><input type="text" class="form-control it_section_3" id="it_clearance_email_validation_1" name="it_clearance_email_validation[1]" /></td>
                                                </tr>
                                                <tr>
                                                    <td>Email ID 3</td>
                                                    <td><input type="text" class="form-control it_section_1" id="it_clearance_email_2" name="it_clearance_email[2]" /></td>
                                                    <td>
                                                        <div class="d-flex justify-content-around">
                                                            <div class="align-items-center d-flex form-check form-group mr-2 text-center">
                                                                <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_email_delete_2" name="it_clearance_email_delete[2]" />
                                                                <label class="form-check-label" for="it_clearance_email_delete_2">Delete</label>
                                                            </div>
                                                            <div>Alias to:<input type="text" class="form-control it_section_2" id="it_clearance_email_alias_to_2" id="it_clearance_email_alias_to[2]" /></div>&nbsp;
                                                            <div>Assign to:<input type="text" class="form-control it_section_2" id="it_clearance_email_assign_to_2" name="it_clearance_email_assign_to[2]" /></div>
                                                        </div>
                                                    </td>
                                                    <td><input type="text" class="form-control it_section_3" id="it_clearance_email_validation_2" name="it_clearance_email_validation[2]" /></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if (!empty($certificate->it_clearance_ftp)) {
                                                for ($i = 0; $i < count($certificate->it_clearance_ftp); $i++) {
                                                    $row = $certificate->it_clearance_ftp[$i]; ?>
                                                    <tr>
                                                        <td>FTP Account Access<sup><?= ($i == 0) ? '*' : '' ?></sup> <?= $i + 1 ?></td>
                                                        <td><input type="text" class="form-control it_section_1" id="it_clearance_ftp_<?= $i ?>" name="it_clearance_ftp[<?= $i ?>]" value="<?= $row->it_clearance_ftp ?>" /></td>
                                                        <td>
                                                            <div class="d-flex justify-content-around">
                                                                <div class="align-items-center d-flex form-check form-group">
                                                                    <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_ftp_delete_<?= $i ?>" name="it_clearance_ftp_delete[<?= $i ?>]" <?= (!empty($row->it_clearance_ftp_delete)) ? 'checked' : '' ?> />
                                                                    <label class="form-check-label" for="it_clearance_ftp_delete_<?= $i ?>">Delete</label>
                                                                </div>
                                                                <div class="align-items-center d-flex form-check form-group">
                                                                    <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_ftp_reset_password_<?= $i ?>" name="it_clearance_ftp_reset_password[<?= $i ?>]" <?= (!empty($row->it_clearance_ftp_reset_password)) ? 'checked' : '' ?> />
                                                                    <label class="form-check-label" for="it_clearance_ftp_delete_1">Reset password & Reassign<input type="text" class="form-control it_section_2" id="it_clearance_ftp_reset_password_value_<?= $i ?>" name="it_clearance_ftp_reset_password_value[<?= $i ?>]" value="<?= $row->it_clearance_ftp_reset_password_value ?>" /></label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><input type="text" class="form-control it_section_3" id="it_clearance_ftp_validation_<?= $i ?>" id="it_clearance_ftp_validation[<?= $i ?>]" value="<?= $row->it_clearance_ftp_validation ?>" /></td>
                                                    </tr>
                                                <?php }
                                            } else { ?>
                                                <tr>
                                                    <td>FTP Account Access 1<sup>*</sup></td>
                                                    <td><input type="text" class="form-control it_section_1" id="it_clearance_ftp_0" name="it_clearance_ftp[0]" /></td>
                                                    <td>
                                                        <div class="d-flex justify-content-around">
                                                            <div class="align-items-center d-flex form-check form-group">
                                                                <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_ftp_delete_0" name="it_clearance_ftp_delete[0]" />
                                                                <label class="form-check-label" for="it_clearance_ftp_delete_0">Delete</label>
                                                            </div>
                                                            <div class="align-items-center d-flex form-check form-group">
                                                                <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_ftp_reset_password_0" name="it_clearance_ftp_reset_password[0]" />
                                                                <label class="form-check-label">Reset password & Reassign<input type="text" class="form-control it_section_2" id="it_clearance_ftp_reset_password_value_0" name="it_clearance_ftp_reset_password_value[0]" /></label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><input type="text" class="form-control it_section_3" id="it_clearance_ftp_validation_0" id="it_clearance_ftp_validation[0]" /></td>
                                                </tr>
                                                <tr>
                                                    <td>FTP Account Access 2</td>
                                                    <td><input type="text" class="form-control it_section_1" id="it_clearance_ftp_1" id="it_clearance_ftp[1]" /></td>
                                                    <td>
                                                        <div class="d-flex justify-content-around">
                                                            <div class="align-items-center d-flex form-check form-group">
                                                                <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_ftp_delete_1" name="it_clearance_ftp_delete[1]" />
                                                                <label class="form-check-label" for="it_clearance_ftp_delete_1">Delete</label>
                                                            </div>
                                                            <div class="align-items-center d-flex form-check form-group">
                                                                <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_ftp_reset_password_1" name="it_clearance_ftp_reset_password[1]" />
                                                                <label class="form-check-label" for="it_clearance_ftp_delete_1">Reset password & Reassign<input type="text" class="form-control it_section_2" id="it_clearance_ftp_reset_password_value_1" name="it_clearance_ftp_reset_password_value[1]" /></label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><input type="text" class="form-control it_section_3" id="it_clearance_ftp_validation_1" name="it_clearance_ftp_validation[1]" /></td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td>VOIP / Skype Any other access</td>
                                                <td><input type="text" class="form-control it_section_1" id="it_clearance_voip" name="it_clearance_voip" value="<?= $certificate->it_clearance_voip ?>" /></td>
                                                <td>
                                                    <div class="d-flex justify-content-around">
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_voip_delete" name="it_clearance_voip_delete" <?= (!empty($certificate->it_clearance_voip_delete)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label" for="it_clearance_voip_delete">Delete</label>
                                                        </div>
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_voip_reset_password" name="it_clearance_voip_reset_password" <?= (!empty($certificate->it_clearance_voip_reset_password)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label" for="it_clearance_voip_delete">Reset password & Reassign<input type="text" class="form-control it_section_2" id="it_clearance_voip_reset_password_value" name="it_clearance_voip_reset_password_value" value="<?= $certificate->it_clearance_voip_reset_password_value ?>" /></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><input type="text" class="form-control it_section_3" id="it_clearance_voip_validation" name="it_clearance_voip_validation" value="<?= $certificate->it_clearance_voip_validation ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <div>1. Sales Force</div>
                                                        <div>2. matchmyemail</div>
                                                    </div>
                                                </td>
                                                <td><input type="text" class="form-control it_section_1" id="it_clearance_sales_force" name="it_clearance_sales_force" value="<?= $certificate->it_clearance_sales_force ?>" /></td>
                                                <td>
                                                    <div class="d-flex justify-content-around">
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_sales_force_delete" name="it_clearance_sales_force_delete" <?= (!empty($certificate->it_clearance_sales_force_delete)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label" for="it_clearance_sales_force_delete">Delete</label>
                                                        </div>
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_sales_force_empty" name="it_clearance_sales_force_empty" <?= (!empty($certificate->it_clearance_sales_force_empty)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label" for="it_clearance_sales_force_empty">Empty</label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><input type="text" class="form-control it_section_3" id="it_clearance_sales_force_validation" name="it_clearance_sales_force_validation" value="<?= $certificate->it_clearance_sales_force_validation ?>" /></td>

                                            </tr>
                                            <tr>
                                                <td>Data on PC</td>
                                                <td><input type="text" class="form-control it_section_1" id="it_clearance_pc_data" name="it_clearance_pc_data" value="<?= $certificate->it_clearance_pc_data ?>" /></td>
                                                <td>
                                                    <div class="d-flex justify-content-around">
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_pc_data_backup" name="it_clearance_pc_data_backup" <?= (!empty($certificate->it_clearance_pc_data_backup)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label" for="it_clearance_pc_data_backup">Backup</label>
                                                        </div>

                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_pc_data_transfer_to" name="it_clearance_pc_data_transfer_to" <?= (!empty($certificate->it_clearance_pc_data_transfer_to)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label">Transfer to<input type="text" class="form-control it_section_2" id="it_clearance_pc_data_transfer_to_value" name="it_clearance_pc_data_transfer_to_value" value="<?= $certificate->it_clearance_pc_data_transfer_to_value ?>" /></label>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <div>(By Default )</div>
                                                        <div>
                                                            (Name of the individual taking over the responsibility)
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><input type="text" class="form-control it_section_3" id="it_clearance_pc_data_validation" name="it_clearance_pc_data_validation" value="<?= $certificate->it_clearance_pc_data_validation ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>Domain Login</td>
                                                <td><input type="text" class="form-control it_section_1" id="it_clearance_domain_login" name="it_clearance_domain_login" value="<?= $certificate->it_clearance_domain_login ?>" /></td>
                                                <td>
                                                    <div class="align-items-center d-flex form-check form-group">
                                                        <input type="checkbox" class="form-check-input it_section_2" id="it_clearance_domain_login_delete" name="it_clearance_domain_login_delete" <?= (!empty($certificate->it_clearance_domain_login_delete)) ? 'checked' : '' ?> />
                                                        <label class="form-check-label" for="it_clearance_domain_login_delete">Delete</label>
                                                    </div>
                                                </td>
                                                <td><input type="text" class="form-control it_section_3" id="it_clearance_domain_login_validation" name="it_clearance_domain_login_validation" value="<?= $certificate->it_clearance_domain_login_validation ?>" /></td>
                                            </tr>
                                            <tr>

                                                <td>
                                                    System format <br />
                                                    (Laptop/Desktop)
                                                </td>
                                                <td><input type="text" class="form-control it_section_1" id="it_clearance_system_format" name="it_clearance_system_format" value="<?= $certificate->it_clearance_system_format ?>" /></td>
                                                <td>
                                                    <div class="d-flex justify-content-around">
                                                        <div class="d-flex form-check form-group">
                                                            <input type="radio" class="form-check-input it_section_2" value="1" name="it_clearance_is_system_formatted" <?= (!empty($certificate->it_clearance_is_system_formatted && $certificate->it_clearance_is_system_formatted == 1)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label">Yes</label>
                                                        </div>
                                                        <div class="d-flex form-check form-group">
                                                            <input type="radio" class="form-check-input it_section_2" value="2" name="it_clearance_is_system_formatted" <?= (!empty($certificate->it_clearance_is_system_formatted && $certificate->it_clearance_is_system_formatted == 2)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label">No</label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><input type="text" class="form-control it_section_3" id="it_clearance_system_format_validation" name="it_clearance_system_format_validation" value="<?= $certificate->it_clearance_system_format_validation ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    System <br />
                                                    (Laptop/Desktop)
                                                </td>
                                                <td><input type="text" class="form-control it_section_1" id="it_clearance_system" name="it_clearance_system" value="<?= $certificate->it_clearance_system ?>" /></td>
                                                <td>
                                                    <div class="d-flex justify-content-around">
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="checkbox" class="form-check-input it_section_2" name="it_clearance_system_moved_to_stores" <?= (!empty($certificate->it_clearance_system_moved_to_stores)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label" for="it_clearance_system_moved_to_stores">Moved to stores</label>
                                                        </div>
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="checkbox" class="form-check-input it_section_2" name="it_clearance_system_asset_management_entry" <?= (!empty($certificate->it_clearance_system_asset_management_entry)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label" for="it_clearance_system_asset_management_entry">Asset Management Entry</label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><input type="text" class="form-control it_section_3" id="it_clearance_system_validation" name="it_clearance_system_validation" value="<?= $certificate->it_clearance_system_validation ?>" /></td>
                                            </tr>
                                            
                                            <tr>
                                                <td>Reporting Manager’s Comments</td>
                                                <td colspan="3"><input type="text" class="form-control it_section_2" id="it_clearance_reporting_manager_comment" name="it_clearance_reporting_manager_comment" value="<?= $certificate->it_clearance_reporting_manager_comment ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column text-right">
                                                        <div>Name of IT Rep<sup>*</sup></div>
                                                        <div>&nbsp;</div>
                                                        <div>Date<sup>*</sup></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mb-1"><input type="text" class="form-control it_section_1" name="it_clearance_rep_name" value="<?= empty($certificate->it_clearance_rep_name) ? $sesion_name : $certificate->it_clearance_rep_name ?>" /></div>
                                                    <div class="mt-1"><input readonly data-date-start-date="today" type="text" class="form-control it_section_1" name="it_clearance_rep_date" data-provide="datepicker" data-date-format="dd-mm-yyyy" data-date-autoclose="true" value="<?= !empty($certificate->it_clearance_rep_date) && $certificate->it_clearance_rep_date != '0000-00-00' ? date('d-m-Y', strtotime($certificate->it_clearance_rep_date)) : date('d-m-Y') ?>"></div>

                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column text-right">
                                                        <div>Name of Reporting Manager<sup>*</sup></div>
                                                        <div>&nbsp;</div>
                                                        <div>Date<sup>*</sup></div>
                                                    </div>
                                                </td>
                                                
                                                <td>
                                                    <div class="mb-1"><input type="text" class="form-control it_section_2" name="it_clearance_reporting_manager_name" value="<?= empty($certificate->it_clearance_reporting_manager_name) ? $it_clearance_rm_name : $certificate->it_clearance_reporting_manager_name ?>" /></div>
                                                    <div class="mt-1"><input data-date-start-date="today" readonly type="text" class="form-control it_section_2" name="it_clearance_reporting_manager_date" data-provide="datepicker" data-date-format="dd-mm-yyyy" data-date-autoclose="true" value="<?= !empty($certificate->it_clearance_reporting_manager_date) && $certificate->it_clearance_reporting_manager_date != '0000-00-00' ? date('d-m-Y', strtotime($certificate->it_clearance_reporting_manager_date)) : date('d-m-Y') ?>"></div>

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="mb-5 text-right">
                                        <div class="col-sm-12">
                                            <div class="mt-2 ">
                                                <input type="submit" class="btn btn-lg btn-primary btnsmt2" value="Submit" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12" <?= ($show_admin_clearance_form) ? '' : 'hidden' ?>>
                                <h6>3. ADMIN CLEARANCE:</h6>
                                <form action="" name="admin_clearance_form" id="admin_clearance_form" method="POST" enctype="multipart/form-data">
                                    <table class="table table-bordered table-hover mt-3" id="table_admin_clearance">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th colspan="2" width="55%">Asset List (To be filled by Admin Rep):<sup>*</sup></th>
                                                <th colspan="2" width="40%">Asset details:</th>

                                            </tr>
                                        </thead>
                                        <tbody id="line_items_list_4">
                                            <?php if (!empty($certificate->adamin_clearance_duties)) {
                                                $duty_size = empty($certificate->adamin_clearance_duties) ? 0 : count($certificate->adamin_clearance_duties) - 1;
                                                for ($i = 0; $i < count($certificate->adamin_clearance_duties); $i++) {
                                                    $row = $certificate->adamin_clearance_duties[$i] ?>
                                                    <tr>
                                                        <td><?= $i + 1 ?></td>
                                                        <td colspan="2"><input type="text" class="form-control" id="admin_clearance_duties_<?= $i ?>" name="admin_clearance_duties[<?= $i ?>]" value="<?= $row->admin_clearance_duties ?>"></td>
                                                        <td colspan="2">
                                                            <div class="d-flex justify-content-around">
                                                                <div class="align-items-center d-flex form-check form-group">
                                                                    <input type="checkbox" id="admin_clearance_moved_to_store_<?= $i ?>" name="admin_clearance_moved_to_store[<?= $i ?>]" <?= (!empty($row->admin_clearance_moved_to_store)) ? 'checked' : '' ?>>&nbsp;&nbsp;
                                                                    <label class="form-check-label">Moved to stores</label>
                                                                </div>
                                                                <div class="align-items-center d-flex form-check form-group">
                                                                    <input type="checkbox" id="admin_clearance_management_entry_<?= $i ?>" name="admin_clearance_management_entry[<?= $i ?>]" <?= (!empty($row->admin_clearance_management_entry)) ? 'checked' : '' ?>>&nbsp;&nbsp;
                                                                    <label class="form-check-label">Asset Management Entry</label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php }
                                            } else { ?>
                                                <tr id='addr0'>
                                                    <td><?= 1 ?></td>
                                                    <td colspan="2"><input type="text" class="form-control" id="admin_clearance_duties_0" name="admin_clearance_duties[0]"></td>
                                                    <td colspan="2">
                                                        <div class="d-flex justify-content-around">
                                                            <div class="align-items-center d-flex form-check form-group">
                                                                <input type="checkbox" id="admin_clearance_moved_to_store_0" name="admin_clearance_moved_to_store[0]">&nbsp;&nbsp;
                                                                <label class="form-check-label">Moved to stores</label>
                                                            </div>
                                                            <div class="align-items-center d-flex form-check form-group">
                                                                <input type="checkbox" id="admin_clearance_management_entry_0" name="admin_clearance_management_entry[0]">&nbsp;&nbsp;
                                                                <label class="form-check-label">Asset Management Entry</label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>Admin Comments</td>
                                                <td><input type="text" class="form-control" name="admin_comments" value="<?= $certificate->admin_comments ?>" /></td>
                                                <td>
                                                    <div class="d-flex flex-column text-right">
                                                        <div>Name of Reporting Manager<sup>*</sup></div>
                                                        <div>&nbsp;</div>
                                                        <div>Date<sup>*</sup></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mb-1"><input type="text" class="form-control" name="admin_clearance_reporting_manager_name" value="<?= empty($certificate->admin_clearance_reporting_manager_name) ? $sesion_name : $certificate->admin_clearance_reporting_manager_name ?>" /></div>
                                                    <div class="mt-1"><input readonly data-date-start-date="today" type="text" class="form-control" id="admin_clearance_date" name="admin_clearance_date" data-provide="datepicker" data-date-format="dd-mm-yyyy" data-date-autoclose="true" value="<?= !empty($certificate->admin_clearance_date) && $certificate->admin_clearance_date != "0000-00-00" ? date('d-m-Y', strtotime($certificate->admin_clearance_date)) : date('d-m-Y') ?>"></div>

                                                </td>

                                                <th>
                                                    <button type="button" id='delete_row_4' class="pull-right btn btn-danger ml-2 action-btn" style="margin-bottom: 20px; margin: right 20px;">Delete Row</button>
                                                    <button type="button" id="add_row_4" data-id="<?= $duty_size ?>" class="btn btn-success pull-right action-btn" style="margin-bottom: 20px; ">Add Row</button>
                                                </th>
                                            </tr>

                                        </tfoot>
                                    </table>
                                    <div class="mb-5 text-right">
                                        <div class="col-sm-12">
                                            <div class="mt-2 ">
                                                <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12" <?= ($show_hod_clearance_form) ? '' : 'hidden' ?>>
                                <h6>4. HOD VERIFICATION:</h6>
                                <form action="" name="hod_clearance_form" id="hod_clearance_form" method="POST" enctype="multipart/form-data">
                                    <table class="table table-bordered table-hover mt-3" id="table_hod_verification">
                                        <tbody>
                                            <tr>
                                                <td>HOD Comments</td>
                                                <td><input type="text" class="form-control" name="hod_comments" value="<?= $certificate->hod_comments ?>" /></td>
                                                <td>
                                                    <div class="d-flex flex-column text-right">
                                                        <div>Name of Admin Rep<sup>*</sup></div>
                                                        <div>&nbsp;</div>
                                                        <div>Date<sup>*</sup></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mb-1"><input type="text" class="form-control" name="hod_verification_admin_rep_name" value="<?= empty($certificate->hod_verification_admin_rep_name) ? $sesion_name : $certificate->hod_verification_admin_rep_name ?>" /></div>
                                                    <div class="mt-1"><input readonly data-date-start-date="today" type="text" class="form-control" name="hod_verification_date" data-provide="datepicker" data-date-format="dd-mm-yyyy" data-date-autoclose="true" value="<?= !empty($certificate->hod_verification_date) && $certificate->hod_verification_date != "0000-00-00" ? date('d-m-Y', strtotime($certificate->hod_verification_date)) : date('d-m-Y') ?>"></div>

                                                </td>
                                            </tr>

                                        </tbody>
                                        <tfoot>

                                        </tfoot>
                                    </table>
                                    <div class="mb-5 text-right">
                                        <div class="col-sm-12">
                                            <div class="mt-2 ">
                                                <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12" <?= ($show_hcm_clearance_form) ? '' : 'hidden' ?>>
                                <h6>5. HCM CLEARANCE:</h6>
                                <form action="" name="hcm_clearance_form" id="hcm_clearance_form" method="POST" enctype="multipart/form-data">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td colspan="1" scope="col">ID Card<sup>*</sup></td>
                                                <td colspan="3">
                                                    <div class="d-flex justify-content-around">
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="radio" class="form-check-input" name="hcm_clearance_id_card_received" value="1" <?= (!empty($certificate->hcm_clearance_id_card_received) && $certificate->hcm_clearance_id_card_received == 1) ? 'checked' : '' ?> />
                                                            <label>Received</label>
                                                        </div>
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="radio" class="form-check-input" name="hcm_clearance_id_card_received" value="2" <?= (!empty($certificate->hcm_clearance_id_card_received) && $certificate->hcm_clearance_id_card_received == 2) ? 'checked' : '' ?> />
                                                            <label>Not Received
                                                            </label>
                                                        </div>
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="radio" class="form-check-input" name="hcm_clearance_id_card_received" value="3" <?= (!empty($certificate->hcm_clearance_id_card_received) && $certificate->hcm_clearance_id_card_received == 3) ? 'checked' : '' ?> />
                                                            <label>Not issued</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Access Control Deleted Time and Date<sup>*</sup></td>
                                                <td>
                                                    <div class="mt-1 cs-form">
                                                        <input type="time" class="form-control" name="hcm_access_control_deleted_time" value="<?= !empty($certificate->hcm_access_control_deleted_time) ? date('H:i', strtotime($certificate->hcm_access_control_deleted_time)) : '' ?>" />

                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mt-1">
                                                        <input readonly type="text" class="form-control" name="hcm_access_control_deleted_date" data-provide="datepicker" data-date-start-date="today" data-date-format="dd-mm-yyyy" data-date-autoclose="true" value="<?= !empty($certificate->hcm_access_control_deleted_date) ? date('d-m-Y', strtotime($certificate->hcm_access_control_deleted_date)) : '' ?>" />

                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="align-items-center d-flex form-check form-group">
                                                        <input type="checkbox" class="form-check-input" name="hcm_clearance_is_nda" <?= (!empty($certificate->hcm_clearance_is_nda)) ? 'checked' : '' ?> />
                                                        <label class="form-check-label" for="id4">Employee undertaking NDA</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <div class="mb-2">HCM Comments<input type="text" class="form-control mt-1" name="hcm_comments" value="<?= $certificate->hcm_comments ?>" /></div>
                                                        <div class="d-flex justify-content-around">
                                                            <div class="align-items-center d-flex form-check form-group">
                                                                <input type="checkbox" class="form-check-input" name="is_ohrm_deactivated" <?= (!empty($certificate->is_ohrm_deactivated)) ? 'checked' : '' ?> />
                                                                <label class="form-check-label">OHRM Deactivated
                                                                </label>
                                                            </div>
                                                            <div class="align-items-center d-flex form-check form-group">
                                                                <input type="checkbox" class="form-check-input" name="is_pulse_deactivated" <?= (!empty($certificate->is_pulse_deactivated)) ? 'checked' : '' ?> />
                                                                <label class="form-check-label">Pulse Deactivated
                                                                </label>
                                                            </div>
                                                            <div class="align-items-center d-flex form-check form-group">
                                                                <input type="checkbox" class="form-check-input" name="is_timelinq_deactivated" <?= (!empty($certificate->is_timelinq_deactivated)) ? 'checked' : '' ?> />
                                                                <label class="form-check-label">Timelinq Deactivated</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td colspan="2" class="text-right">
                                                    <div class="d-flex flex-column">
                                                        <div>Name of HCM Rep<sup>*</sup></div>
                                                        <div>&nbsp;</div>
                                                        <div>Date<sup>*</sup></div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="mb-1"><input type="text" class="form-control" name="hcm_rep_name" value="<?= empty($certificate->hcm_rep_name) ? $sesion_name : $certificate->hcm_rep_name ?>" /></div>
                                                    <div class="mt-1"><input readonly type="text" class="form-control" name="hcm_clearance_date" data-provide="datepicker" data-date-start-date="today" data-date-format="dd-mm-yyyy" data-date-autoclose="true" value="<?= !empty($certificate->hcm_clearance_date) ? date('d-m-Y', strtotime($certificate->hcm_clearance_date)) : date('d-m-Y') ?>"></div>

                                                </td>

                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                    <div class="mb-5 text-right">
                                        <div class="col-sm-12">
                                            <div class="mt-2 ">
                                                <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-12" <?= ($show_cfo_clearance_form) ? '' : 'hidden' ?>>
                                <h6>6. FINAL APPROVAL :</h6>
                                <form action="" name="cfo_clearance_form" id="cfo_clearance_form" method="POST" enctype="multipart/form-data">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td class="50">President / EVP / CFO</td>
                                                <td class="25">
                                                    <div class="d-flex flex-column text-right">
                                                        <div>Name<sup></sup></div>
                                                        <div>&nbsp;</div>
                                                        <div>Date<sup></sup></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mb-1"><input type="text" class="form-control" name="final_approval_cfo_name" value="<?= empty($certificate->final_approval_cfo_name) ? $sesion_name : $certificate->final_approval_cfo_name ?>" /></div>
                                                    <div class="mt-1"><input readonly type="text" class="form-control" name="final_approval_date" data-provide="datepicker" data-date-start-date="today" data-date-format="dd-mm-yyyy" data-date-autoclose="true" value="<?= !empty($certificate->final_approval_date)  && $certificate->final_approval_date != '0000-00-00' ? date('d-m-Y', strtotime($certificate->final_approval_date)) : date('d-m-Y') ?>" /></div>

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="mb-5 text-right">
                                        <div class="col-sm-12">
                                            <div class="mt-2 ">
                                                <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12" <?= ($show_final_clearance_form) ? '' : 'hidden' ?>>
                                <h6>Final Settlement Details (For Corporate Office Use Only)</h6>
                                <form action="" name="final_clearance_form" id="final_clearance_form" method="POST" enctype="multipart/form-data">
                                    <table class="table table-bordered mt-3">
                                        <tbody>
                                            <tr>
                                                <td width="60%">Date of Resignation</td>
                                                <td><?= !empty($resignation->relieving_date) ? date('d-m-Y', strtotime($resignation->relieving_date)) : ''  ?></td>
                                            </tr>
                                            <tr>
                                                <td>Date of Relieving</td>
                                                <td><?= !empty($resignation->agreed_relieving_date) ? date('d-m-Y', strtotime($resignation->agreed_relieving_date)) : ''  ?></td>
                                            </tr>
                                            <tr>
                                                <td>Notice Period</td>
                                                <td><?= !empty($employee_details->notice_period) ? $employee_details->notice_period : '' ?></td>
                                            </tr>
                                            <tr>
                                                <td>No of days short of notice period</td>
                                                <td><?= $no_of_days_short ?></td>
                                            </tr>
                                            <tr>
                                                <td>PL Balance<sup>*</sup></td>
                                                <td><input type="text" class="form-control amount" name="pl_balance" value="<?= $certificate->pl_balance ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>PL / SL Excess taken to be recovered<sup>*</sup></td>
                                                <td><input type="text" class="form-control amount" name="pl_excess" value="<?= $certificate->pl_excess ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>No. of days Salary to be paid<sup>*</sup></td>
                                                <td><input type="text" class="form-control number" name="no_of_days" value="<?= $certificate->no_of_days ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>Net Salary Payable<sup>*</sup></td>
                                                <td><input type="text" class="form-control amount" name="net_salary" value="<?= $certificate->net_salary ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    PL Encashment (if any) after adjusting the notice period<sup>*</sup>
                                                </td>
                                                <td><input type="text" class="form-control amount" name="pl_encashment" value="<?= $certificate->pl_encashment ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>Eligible for Gratuity<sup>*</sup></td>
                                                <td><input type="text" class="form-control" name="is_gratuity" value="<?= $certificate->is_gratuity ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Any Salary Advance/Loan outstanding against the individual /
                                                    Lost assets / IT proofs (bills)<sup>*</sup>
                                                </td>
                                                <td><input type="text" class="form-control amount" name="salary_advance" value="<?= $certificate->salary_advance ?>" /></td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Final Amount Payable<sup>*</sup></td>
                                                <td><input type="text" class="form-control amount" name="payable_amount" value="<?= $certificate->payable_amount ?>" /></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <h5 class="text-center">Declaration</h5>
                                    <p>
                                        <span>1.</span> I understand that this is in full and final
                                        settlement of all due to me. I agree to abide by the terms of my
                                        appointment and Non-Disclosure Agreement signed and shall not
                                        disclose any Confidential Information including but not limited to
                                        Client Lists or Service offerings to any third party nor solicit any
                                        Client or employee of Ksoft Technologies. I shall also not
                                        engage with any of Ksoft Technologies’s customers or leads wherein I have
                                        worked or interacted with them in a professional capacity for past
                                        12 months or use any Confidential Information for my or a third
                                        party’s benefit.
                                    </p>

                                    <div class="card-body row">

                                        <p>
                                        <div>
                                            <span>
                                                2. I hereby acknowledge the receipt of cheque No
                                            </span>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <input type="text" class="form-control form-control-sm" name="check_no" value="<?= $certificate->check_no ?>" />
                                        </div>
                                        <div>
                                            <span>
                                                dated
                                            </span>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <input readonly type="text" class="form-control form-control-sm" name="check_no_date" data-date-start-date="today" data-provide="datepicker" data-date-format="dd-mm-yyyy" data-date-autoclose="true" value="<?= !empty($certificate->check_no_date) ? date('d-m-Y', strtotime($certificate->check_no_date)) : ''  ?>" readonly />

                                        </div>

                                        <div>
                                            <span>
                                                Drawn on
                                            </span>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <input readonly data-date-start-date="today" type="text" class="form-control form-control-sm" name="check_drawn_date" data-provide="datepicker" data-date-format="dd-mm-yyyy" data-date-autoclose="true" value="<?= !empty($certificate->check_drawn_date) ? date('d-m-Y', strtotime($certificate->check_drawn_date)) : ''  ?>" readonly />
                                        </div>

                                        <div>
                                            <span>
                                                Bank, Bangalore for Rs
                                            </span>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <input type="text" class="form-control form-control-sm" name="check_amount" value="<?= $certificate->check_amount ?>" />

                                        </div>
                                        <div>
                                            <span>
                                                towards salary in full settlement
                                                of my account with the company, I further confirm that there is no
                                                other amount due to and from the company.
                                            </span>
                                        </div>

                                        </p>
                                    </div>

                                    <div class="col-md-12">

                                    </div>
                                    <div class="mb-5 text-right">
                                        <div class="col-sm-12">
                                            <div class="mt-2 ">
                                                <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <div class="row clearfix text-center mb-5" hidden>
                <div class="col-sm-12">
                    <div class="mt-2">
                        <input type="button" class="btn btn-lg btn-primary btnsmt" value="Submit" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url() ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script src="<?= base_url() ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">
    $(function() {
        $('#on_job_clearance_form').validate({
            rules: {

                "employee_duties[0]": {
                    required: true,
                },
                "employee_pending_tasks[0]": {
                    required: true,
                },
                "employee_reports[0]": {
                    required: true,
                },
                "on_job_reporting_manager_name": {
                    required: true,
                    email:true
                },
                "on_job_date": {
                    required: true,
                },

            },
            messages: {

            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback-1');
                if ($(element).closest('td').length == 1) {
                    element.after(error);
                }
                if ($(element).closest('td').length == 0) {
                    element.closest('.form-group').append(error);
                }

            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            errorClass: "error-text",
            submitHandler: function(form, e) {
                e.preventDefault();
                //$('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                //$('.page-loader-wrapper').removeAttr('style');
                var form_data = new FormData(form);
                form_data.append('clearance_certificate_id', '<?= $clearance_certificate_id ?>');
                form_data.append('resignation_id', '<?= $resignation_id ?>');

                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'resignation/add_clearance_certificate_on_job_process',
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
                                    window.location.href = base_url + 'view-resignation';
                                }, 100);
                            } else {
                                toaster('error', obj.msg);
                                $('.btnsmt').prop('disabled', false).attr('value',
                                    'Submit');
                            }

                        },
                        error: function(error) {
                            toaster('error', error);
                            $('.btnsmt').prop('disabled', false).attr('value',
                                'Submit');
                        }
                    });
                }, 500);
                return false;
            }

        });

        $('#it_clearance_form').validate({
            rules: {

                "it_clearance_email[0]": {
                    required: true,
                    email:true,
                },
                "it_clearance_ftp[0]": {
                    required: true,
                },
                "employee_reports[0]": {
                    required: true,
                },
                "it_clearance_rep_name": {
                    required: true,
                },
                "it_clearance_rep_date": {
                    required: true,
                },
                "it_clearance_reporting_manager_name": {
                    required: true,
                },
                "it_clearance_reporting_manager_date": {
                    required: true,
                },

            },
            messages: {

            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback-1');
                if ($(element).closest('td').length == 1) {
                    element.after(error);
                }
                if ($(element).closest('td').length == 0) {
                    element.closest('.form-group').append(error);
                }

            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            errorClass: "error-text",
            submitHandler: function(form, e) {
                e.preventDefault();
                //$('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                $('.btnsmt2').prop('disabled', true).attr('value', 'Processing...');
                //$('.page-loader-wrapper').removeAttr('style');
                var form_data = new FormData(form);
                form_data.append('clearance_certificate_id', '<?= $clearance_certificate_id ?>');
                form_data.append('resignation_id', '<?= $resignation_id ?>');

                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'resignation/update_clearance_certificate_it_clearance_process',
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
                                    window.location.href = base_url + 'view-resignation';
                                }, 100);
                            } else {
                                toaster('error', obj.msg);
                                $('.btnsmt2').prop('disabled', false).attr('value',
                                    'Submit');
                            }

                        },
                        error: function(error) {
                            toaster('error', error);
                            $('.btnsmt2').prop('disabled', false).attr('value',
                                'Submit');
                        }
                    });
                }, 500);
                return false;
            }

        });

        $('#admin_clearance_form').validate({
            rules: {

                "admin_clearance_duties[0]": {
                    required: true,
                },
                // "admin_clearance_moved_to_store[0]": {
                //     required: true,
                // },
                // "admin_clearance_management_entry[0]": {
                //     required: true,
                // },
                "admin_clearance_reporting_manager_name": {
                    required: true,
                },
                "admin_clearance_date": {
                    required: true,
                },

            },
            messages: {

            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback-1');
                if ($(element).closest('td').length == 1) {
                    element.after(error);
                }
                if ($(element).closest('td').length == 0) {
                    element.closest('.form-group').append(error);
                }

            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            errorClass: "error-text",
            submitHandler: function(form, e) {
                e.preventDefault();
                //$('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                //$('.page-loader-wrapper').removeAttr('style');
                var form_data = new FormData(form);
                form_data.append('clearance_certificate_id', '<?= $clearance_certificate_id ?>');
                form_data.append('resignation_id', '<?= $resignation_id ?>');

                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'resignation/update_clearance_certificate_admin_clearance_process',
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
                                    window.location.href = base_url + 'view-resignation';
                                }, 100);
                            } else {
                                toaster('error', obj.msg);
                                $('.btnsmt').prop('disabled', false).attr('value',
                                    'Submit');
                            }

                        },
                        error: function(error) {
                            toaster('error', error);
                            $('.btnsmt').prop('disabled', false).attr('value',
                                'Submit');
                        }
                    });
                }, 500);
                return false;
            }

        });

        $('#hod_clearance_form').validate({
            rules: {

                "hod_verification_admin_rep_name": {
                    required: true,
                },
                "hod_verification_date": {
                    required: true,
                },

            },
            messages: {

            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback-1');
                if ($(element).closest('td').length == 1) {
                    element.after(error);
                }
                if ($(element).closest('td').length == 0) {
                    element.closest('.form-group').append(error);
                }

            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            errorClass: "error-text",
            submitHandler: function(form, e) {
                e.preventDefault();
                //$('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                //$('.page-loader-wrapper').removeAttr('style');
                var form_data = new FormData(form);
                form_data.append('clearance_certificate_id', '<?= $clearance_certificate_id ?>');
                form_data.append('resignation_id', '<?= $resignation_id ?>');

                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'resignation/update_clearance_certificate_hod_clearance_process',
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
                                    window.location.href = base_url + 'view-resignation';
                                }, 100);
                            } else {
                                toaster('error', obj.msg);
                                $('.btnsmt').prop('disabled', false).attr('value',
                                    'Submit');
                            }

                        },
                        error: function(error) {
                            toaster('error', error);
                            $('.btnsmt').prop('disabled', false).attr('value',
                                'Submit');
                        }
                    });
                }, 500);
                return false;
            }

        });
        $('#hcm_clearance_form').validate({
            rules: {

                "hcm_clearance_id_card_received": {
                    required: true,
                },
                "hcm_access_control_deleted_time": {
                    required: true,
                },
                "hcm_access_control_deleted_date": {
                    required: true,
                },

                "hcm_rep_name": {
                    required: true,
                },
                "hcm_clearance_date": {
                    required: true,
                },

            },
            messages: {

            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback-1');
                if ($(element).closest('td').length == 1) {
                    element.after(error);
                }
                if ($(element).closest('td').length == 0) {
                    element.closest('.form-group').append(error);
                }

            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            errorClass: "error-text",
            submitHandler: function(form, e) {
                e.preventDefault();
                //$('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                //$('.page-loader-wrapper').removeAttr('style');
                var form_data = new FormData(form);
                form_data.append('clearance_certificate_id', '<?= $clearance_certificate_id ?>');
                form_data.append('resignation_id', '<?= $resignation_id ?>');

                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'resignation/update_clearance_certificate_hcm_clearance_process',
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
                                    window.location.href = base_url + 'view-resignation';
                                }, 100);
                            } else {
                                toaster('error', obj.msg);
                                $('.btnsmt').prop('disabled', false).attr('value',
                                    'Submit');
                            }

                        },
                        error: function(error) {
                            toaster('error', error);
                            $('.btnsmt').prop('disabled', false).attr('value',
                                'Submit');
                        }
                    });
                }, 500);
                return false;
            }

        });

        $('#cfo_clearance_form').validate({
            rules: {

                "final_approval_cfo_name": {
                    required: true,
                },
                "final_approval_date": {
                    required: true,
                },

            },
            messages: {

            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback-1');
                if ($(element).closest('td').length == 1) {
                    element.after(error);
                }
                if ($(element).closest('td').length == 0) {
                    element.closest('.form-group').append(error);
                }

            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            errorClass: "error-text",
            submitHandler: function(form, e) {
                e.preventDefault();
                //$('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                //$('.page-loader-wrapper').removeAttr('style');
                var form_data = new FormData(form);
                form_data.append('clearance_certificate_id', '<?= $clearance_certificate_id ?>');
                form_data.append('resignation_id', '<?= $resignation_id ?>');

                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'resignation/update_clearance_certificate_cfo_clearance_process',
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
                                    window.location.href = base_url + 'view-resignation';
                                }, 100);
                            } else {
                                toaster('error', obj.msg);
                                $('.btnsmt').prop('disabled', false).attr('value',
                                    'Submit');
                            }

                        },
                        error: function(error) {
                            toaster('error', error);
                            $('.btnsmt').prop('disabled', false).attr('value',
                                'Submit');
                        }
                    });
                }, 500);
                return false;
            }

        });
        $('#final_clearance_form').validate({
            rules: {

                "pl_balance": {
                    required: true,
                },
                "pl_excess": {
                    required: true,
                },
                "no_of_days": {
                    required: true,
                },
                "net_salary": {
                    required: true,
                },
                "pl_encashment": {
                    required: true,
                },
                "is_gratuity": {
                    required: true,
                },
                "salary_advance": {
                    required: true,
                },

                "payable_amount": {
                    required: true,
                },
                "check_no": {
                    required: true,
                },
                "check_no_date": {
                    required: true,
                },
                "check_drawn_date": {
                    required: true,
                },
                "check_amount": {
                    required: true,
                },

            },
            messages: {

            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback-1');
                if ($(element).closest('td').length == 1) {
                    element.after(error);
                }
                if ($(element).closest('td').length == 0) {
                    element.closest('.form-group').append(error);
                }

            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            errorClass: "error-text",
            submitHandler: function(form, e) {
                e.preventDefault();
                //$('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                //$('.page-loader-wrapper').removeAttr('style');
                var form_data = new FormData(form);
                form_data.append('clearance_certificate_id', '<?= $clearance_certificate_id ?>');
                form_data.append('resignation_id', '<?= $resignation_id ?>');

                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'resignation/update_clearance_certificate_final_clearance_process',
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
                                    window.location.href = base_url + 'view-resignation';
                                }, 100);
                            } else {
                                toaster('error', obj.msg);
                                $('.btnsmt').prop('disabled', false).attr('value',
                                    'Submit');
                            }

                        },
                        error: function(error) {
                            toaster('error', error);
                            $('.btnsmt').prop('disabled', false).attr('value',
                                'Submit');
                        }
                    });
                }, 500);
                return false;
            }

        });
        $(document).ready(function() {
            //var k = "";
            $('.it_section_1').attr('disabled', 'disabled');
            $('.it_section_2').attr('disabled', 'disabled');
            $('.it_section_3').attr('disabled', 'disabled');
            if ("<?= $certificate->is_on_job_added == 1 && $session_employee_details->special_role == 'Clearance'  && $certificate->is_it_rm_added != 1  && $certificate->is_it_validation_added != 1 ?>") {
                $('.it_section_1').removeAttr('disabled', 'disabled');
                //$('.it_section_3').removeAttr('disabled', 'disabled');
            } else if ('<?= $certificate->is_it_added == 1 && $session_employee_id == $employee_details->reporting_manager_id && $certificate->is_it_validation_added != 1 ?>')
                $('.it_section_2').removeAttr('disabled', 'disabled');
            else if ("<?= $session_employee_details->special_role == 'Clearance' && $certificate->is_it_rm_added == 1 ?>")
                $('.it_section_3').removeAttr('disabled', 'disabled');
            //initialValidation();
            //customerContactValidation();
        });

        $('#add_row').click(function() {
            var i = $('#add_row').attr('data-id');
            var index = Number(i) + 1;
            var line_item_html = `
            <tr>
            <td >` + (Number(i) + 2) +
                `<td><input type="text" class="form-control" name="employee_duties[` + index + `]" id="employee_duties_` + index + `"   /></td>             
            </tr>`;

            $('#line_items_list').append(line_item_html);
            $("#on_job_clearance_form #employee_duties_" + index).rules("add", {
                required: true,
                messages: {}
            });

            i++;

            $('#add_row').attr('data-id', i);

        });

        $('#delete_row').click(function() {
            //var l = $('#line_items_list tr').length;
            var l = $('#add_row').attr('data-id');
            console.log(l);
            if (l >= 1) {
                $('#table_on_job_clearance tbody tr').last().remove();
                l--;
                $('#add_row').attr('data-id', l);
            }

        });

        $('#add_row_2').click(function() {
            var i = $('#add_row_2').attr('data-id');
            var index = Number(i) + 1;
            var line_item_html = `
            <tr>
            <td >` + (Number(i) + 2) +
                `<td><input type="text" class="form-control" name="employee_pending_tasks[` + index + `]" id="employee_pending_tasks_` + index + `"   /></td>             
            </tr>`;

            $('#line_items_list_2').append(line_item_html);
            $("#on_job_clearance_form #employee_pending_tasks_" + index).rules("add", {
                required: true,
                messages: {}
            });

            i++;

            $('#add_row_2').attr('data-id', i);

        });

        $('#delete_row_2').click(function() {
            //var l = $('#line_items_list tr').length;
            var l = $('#add_row_2').attr('data-id');
            console.log(l);
            if (l >= 1) {
                $('#table_pending_tasks tbody tr').last().remove();
                l--;
                $('#add_row_2').attr('data-id', l);
            }

        });

        $('#add_row_3').click(function() {
            var i = $('#add_row_3').attr('data-id');
            var index = Number(i) + 1;
            var line_item_html = `
            <tr>
            <td >` + (Number(i) + 2) +
                `<td><input type="text" class="form-control" name="employee_reports[` + index + `]" id="employee_reports_` + index + `"   /></td>             
            </tr>`;

            $('#line_items_list_3').append(line_item_html);
            $("#on_job_clearance_form #employee_reports_" + index).rules("add", {
                required: true,
                messages: {}
            });

            i++;

            $('#add_row_3').attr('data-id', i);

        });

        $('#delete_row_3').click(function() {
            //var l = $('#line_items_list tr').length;
            var l = $('#add_row_3').attr('data-id');
            console.log(l);
            if (l >= 1) {
                $('#table_reports tbody tr').last().remove();
                l--;
                $('#add_row_3').attr('data-id', l);
            }

        });

        $('#add_row_4').click(function() {
            var i = $('#add_row_4').attr('data-id');
            var index = Number(i) + 1;
            var line_item_html = `
            <tr>
            <td >` + (Number(i) + 2) +
                `<td colspan="2"><input type="text" class="form-control" name="admin_clearance_duties[` + index + `]" id="admin_clearance_duties_` + index + `"   /></td>   
                <td colspan="2">
                        <div class="d-flex justify-content-around">
                            <div class="align-items-center d-flex form-check form-group">
                                <input type="checkbox"  id="admin_clearance_moved_to_store_[` + index + `]" name="admin_clearance_moved_to_store[` + index + `]">&nbsp;&nbsp;
                                <label class="form-check-label">Moved to stores</label>
                            </div>
                            <div class="align-items-center d-flex form-check form-group">
                                <input type="checkbox" id="admin_clearance_management_entry_[` + index + `]" name="admin_clearance_management_entry[` + index + `]">&nbsp;&nbsp;
                                <label class="form-check-label">Asset Management Entry</label>
                            </div>
                        </div>
                </td>          
            </tr>`;

            $('#line_items_list_4').append(line_item_html);
            $("#admin_clearance_form #admin_clearance_duties_" + index).rules("add", {
                required: true,
                messages: {}
            });
            // $("#admin_clearance_form #admin_clearance_moved_to_store_" + index).rules("add", {
            //     required: true,
            //     messages: {}
            // });
            // $("#admin_clearance_form #admin_clearance_management_entry_" + index).rules("add", {
            //     required: true,
            //     messages: {}
            // });

            i++;

            $('#add_row_4').attr('data-id', i);

        });

        $('#delete_row_4').click(function() {
            //var l = $('#line_items_list tr').length;
            var l = $('#add_row_4').attr('data-id');
            console.log(l);
            if (l >= 1) {
                $('#table_admin_clearance tbody tr').last().remove();
                l--;
                $('#add_row_4').attr('data-id', l);
            }

        });
    });

    //$('body #table_design input[type="text"]').keyup(resizeInput).each(resizeInput);
</script>