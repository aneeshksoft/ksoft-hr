<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<style>
    .checkbox-container:has(> input:disabled:checked)::before {
        width: 15px;
        height: 15px;
        font-size: 9px;
        background: #8585cf;
        display: inline-block;
        border: 1px solid #000000;
        border-radius: 3px;
        color: white;
        position: relative;
        content: "✔";
        text-align: center;
        float: left;
        margin: 3px 4px 0 5px;
    }

    .checkbox-container input:disabled:checked {
        display: none
    }
</style>
<?php //echo json_encode($employee_details)  
?>
<div class="row clearfix">
    <div class="col-sm-12">
        <?php $no_of_days_short = 0;
        if (!empty($resignation->agreed_relieving_date) && !empty($resignation->relieving_date)) {
            $agreed             = new DateTime($resignation->agreed_relieving_date);
            $employee_date      = new DateTime($resignation->relieving_date);
            $no_of_days_short   =  $employee_date->diff($agreed)->format("%r%a");
            $no_of_days_short   = $no_of_days_short < 1 ? 0 : $no_of_days_short;
        }
        $show_on_job_clearance_form = true;
        $show_it_clearance_form     = true;
        $show_admin_clearance_form  = true;
        $show_hod_clearance_form    = true;
        $show_hcm_clearance_form    = true;
        $show_cfo_clearance_form    = true;
        $show_final_clearance_form  = true;
        //echo json_encode($certificate->duties);

        ?>
        <input type="hidden" class="form-control" id="clearance_certificate_id" name="clearance_certificate_id" value="<?= $clearance_certificate_id ?>"></input>

        <div class="col-sm-12">
            <div class="card">
                <div class="body">
                    <input type="hidden" class="form-control" id="employee_id" name="employee_id" value="<?= $employee_id ?>"></input>
                    <div class="container-fluid py-5" id="mainDiv">
                        <h2 class="text-center">CLEARANCE CERTIFICATE</h2>
                        <div class="row">

                            <div class="col-md-12">
                                <form action="" name="on_job_clearance_form" id="on_job_clearance_form" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" class="form-control" id="resignation_id" name="resignation_id" value="<?= $resignation_id ?>"></input>
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Name: <label><b><?= $employee_details->name ?></b></label></th>
                                                    <th scope="col">EMP Code: <label><b><?= $employee_details->code ?></b></label></th>
                                                    <th scope="col">Department: <label><b><?= $employee_details->department ?></b></label></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="radio" class="form-check-input" name="employee_type" value="1" disabled <?= (!empty($certificate->employee_type && $certificate->employee_type == 1)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label">Office Based Employee</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="radio" class="form-check-input" name="employee_type" value="2" disabled <?= (!empty($certificate->employee_type && $certificate->employee_type == 2)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label">Home based Employee</label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="radio" class="form-check-input" name="employee_type" value="3" disabled <?= (!empty($certificate->employee_type && $certificate->employee_type == 3)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label">Captive Resource / Contract</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12" <?= ($show_on_job_clearance_form) ? '' : 'hidden' ?>>
                                        <h6>
                                            1. ON-JOB CLEARANCE:
                                            <span>(To be filled by Reporting Manager)</span>
                                        </h6>
                                        <table class="table table-bordered table-hover" id="table_on_job_clearance">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th>Duties handled by Employee:</th>

                                                </tr>
                                            </thead>
                                            <tbody id="line_items_list">
                                                <?php for ($i = 0; $i < count($certificate->duties); $i++) {
                                                    $row = $certificate->duties[$i] ?>
                                                    <tr>
                                                        <td><?= $i + 1 ?></td>
                                                        <td><label><?= $row->duty ?></label></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>

                                        </table>
                                        <table class="table table-bordered table-hover" id="table_pending_tasks">
                                            <thead>

                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th>List of pending tasks:</th>

                                                </tr>
                                            </thead>
                                            <tbody id="line_items_list_2">
                                                <?php for ($i = 0; $i < count($certificate->pending_tasks); $i++) {
                                                    $row = $certificate->pending_tasks[$i] ?>
                                                    <tr>
                                                        <td><?= $i + 1 ?></td>
                                                        <td><label><?= $row->task ?></label></td>
                                                    </tr>
                                                <?php } ?>

                                            </tbody>

                                        </table>
                                        <table class="table table-bordered table-hover" id="table_reports">
                                            <thead>
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th>Reports:</th>

                                                </tr>
                                            </thead>
                                            <tbody id="line_items_list_3">
                                                <?php for ($i = 0; $i < count($certificate->reports); $i++) {
                                                    $row = $certificate->reports[$i] ?>
                                                    <tr>
                                                        <td><?= $i + 1 ?></td>
                                                        <td><label><?= $row->report ?></label></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>

                                        </table>

                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Signature</th>
                                                    <th width="40%">Date:</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><label><?= $certificate->on_job_reporting_manager_name ?></label> </td>
                                                    <td></td>
                                                    <td><label><?= (!empty($certificate->on_job_date) && '0000-00-00' != $certificate->on_job_date) ? date('d-m-Y', strtotime($certificate->on_job_date)) : ''   ?></label> </td>
                                                </tr>

                                            </tbody>

                                        </table>

                                    </div>

                                </form>
                            </div>

                            <div class="col-md-12" id="it_clearance_div" <?= ($show_it_clearance_form) ? '' : 'hidden' ?>>
                                <h6>2. IT CLEARANCE:</h6>
                                <form action="" name="it_clearance_form" id="it_clearance_form" method="POST" enctype="multipart/form-data">
                                    <table class="table table-bordered">
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

                                            <?php if (!empty($certificate->it_clearance)) for ($i = 0; $i < count($certificate->it_clearance); $i++) {
                                                $row = $certificate->it_clearance[$i]; ?>

                                                <tr>
                                                    <td>Email ID <?= $i + 1 ?>:</td>
                                                    <td><label><?= $row->it_clearance_email ?></label></td>
                                                    <td>
                                                        <div class="d-flex justify-content-around">
                                                            <div class="align-items-center d-flex form-check form-group mr-2 text-center">
                                                                <input type="checkbox" class="form-check-input" disabled <?= (!empty($row->it_clearance_email_delete)) ? 'checked' : '' ?> />
                                                                <label class="form-check-label" for="it_clearance_email_delete_0">Delete</label>
                                                            </div>
                                                            <div>Alias to:<label> <?= $row->it_clearance_email_alias_to  ?></label></div>
                                                            <div>Assign to:<label><?= $row->it_clearance_email_assign_to ?></label></div>
                                                        </div>
                                                    </td>
                                                    <td> <label><?= $row->it_clearance_email_validation ?></label></td>
                                                </tr>

                                            <?php  } ?>
                                            <?php if (!empty($certificate->it_clearance_ftp)) for ($i = 0; $i < count($certificate->it_clearance_ftp); $i++) {
                                                $row = $certificate->it_clearance_ftp[$i]; ?>
                                                <tr>
                                                    <td>FTP Account Access <?= $i + 1 ?>:</td>
                                                    <td><label><?= $row->it_clearance_ftp ?></label></td>
                                                    <td>
                                                        <div class="d-flex justify-content-around">
                                                            <div class="align-items-center d-flex form-check form-group">
                                                                <input type="checkbox" class="form-check-input" disabled <?= (!empty($row->it_clearance_ftp_delete)) ? 'checked' : '' ?> />
                                                                <label class="form-check-label">Delete</label>
                                                            </div>
                                                            <div class="align-items-center d-flex form-check form-group">
                                                                <input type="checkbox" class="form-check-input" disabled <?= (!empty($row->it_clearance_ftp_reset_password)) ? 'checked' : '' ?> />
                                                                <label class="form-check-label">Reset password & Reassign</br>
                                                                    <label><?= $row->it_clearance_ftp_reset_password_value ?></label></label>

                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><label><?= $row->it_clearance_ftp_validation ?></label></td>
                                                </tr>
                                            <?php } ?>

                                            <tr>
                                                <td>VOIP / Skype Any other access:</td>
                                                <td><label><?= $certificate->it_clearance_voip ?></label></td>

                                                <td>
                                                    <div class="d-flex justify-content-around">
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="checkbox" class="form-check-input" disabled <?= (!empty($certificate->it_clearance_voip_delete)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label" for="it_clearance_voip_delete">Delete</label>
                                                        </div>
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="checkbox" class="form-check-input" disabled <?= (!empty($certificate->it_clearance_voip_reset_password)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label" for="it_clearance_voip_delete">Reset password & Reassign</br><span><?= $certificate->it_clearance_voip_reset_password_value ?></span></label>

                                                        </div>
                                                    </div>
                                                </td>
                                                <td><label><?= $certificate->it_clearance_voip_validation ?></label></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <div>1. Sales Force</div>
                                                        <div>2. matchmyemail</div>
                                                    </div>
                                                </td>
                                                <td><label><?= $certificate->it_clearance_sales_force ?></label>
                                                <td>
                                                    <div class="d-flex justify-content-around">
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="checkbox" class="form-check-input" id="it_clearance_sales_force_delete" name="it_clearance_sales_force_delete" disabled <?= (!empty($certificate->it_clearance_sales_force_delete)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label" for="it_clearance_sales_force_delete">Delete</label>
                                                        </div>
                                                        <div class="align-items-center d-flex form-check form-group checkbox-container">
                                                            <input type="checkbox" class="form-check-input" id="it_clearance_sales_force_empty" name="it_clearance_sales_force_empty" disabled <?= (!empty($certificate->it_clearance_sales_force_empty)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label" for="it_clearance_sales_force_empty">Empty</label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <label><?= $certificate->it_clearance_sales_force_validation ?></label>
                                                </td>

                                            </tr>
                                            <tr>

                                                <td>Data on PC</td>
                                                <td><label><?= $certificate->it_clearance_pc_data ?></label></td>
                                                <td>
                                                    <div class="d-flex justify-content-around">
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="checkbox" class="form-check-input" id="it_clearance_pc_data_backup" name="it_clearance_pc_data_backup" disabled <?= (!empty($certificate->it_clearance_pc_data_backup)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label" for="it_clearance_pc_data_backup">Backup</label>
                                                        </div>

                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="checkbox" class="form-check-input" id="it_clearance_pc_data_transfer_to" name="it_clearance_pc_data_transfer_to" disabled <?= (!empty($certificate->it_clearance_pc_data_transfer_to)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label">Transfer to</br><span><?= $certificate->it_clearance_pc_data_transfer_to_value ?></span></label>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <div>(By Default )</div>
                                                        <div>
                                                            (Name of the individual taking over the responsibility)
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><label><?= $certificate->it_clearance_pc_data_validation ?></label></td>
                                            </tr>
                                            <tr>
                                                <td>Domain Login:</td>
                                                <td><label><?= $certificate->it_clearance_domain_login ?></label></td>
                                                <td>
                                                    <div class="align-items-center d-flex form-check form-group">
                                                        <input type="checkbox" class="form-check-input" id="it_clearance_domain_login_delete" name="it_clearance_domain_login_delete" disabled <?= (!empty($certificate->it_clearance_domain_login_delete)) ? 'checked' : '' ?> />
                                                        <label class="form-check-label" for="it_clearance_domain_login_delete">Delete</label>
                                                    </div>
                                                </td>
                                                <td><label><?= $certificate->it_clearance_domain_login_validation ?></label></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    System format <br />
                                                    (Laptop/Desktop)
                                                </td>
                                                <td><label><?= $certificate->it_clearance_system_format ?></label>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-around">
                                                        <div class="d-flex form-check form-group">
                                                            <input type="radio" class="form-check-input" name="it_clearance_is_system_formatted" disabled <?= (!empty($certificate->it_clearance_is_system_formatted && $certificate->it_clearance_is_system_formatted == 1)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label">Yes</label>
                                                        </div>
                                                        <div class="d-flex form-check form-group">
                                                            <input type="radio" class="form-check-input" name="it_clearance_is_system_formatted" disabled <?= (!empty($certificate->it_clearance_is_system_formatted && $certificate->it_clearance_is_system_formatted == 2)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label">No</label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><label><?= $certificate->it_clearance_system_format_validation ?></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    System <br />
                                                    (Laptop/Desktop)
                                                </td>
                                                <td><label><?= $certificate->it_clearance_system ?></label>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-around">
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="checkbox" class="form-check-input" name="it_clearance_system_moved_to_stores" disabled <?= (!empty($certificate->it_clearance_system_moved_to_stores)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label" for="it_clearance_system_moved_to_stores">Moved to stores</label>
                                                        </div>
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="checkbox" class="form-check-input" name="it_clearance_system_asset_management_entry" disabled <?= (!empty($certificate->it_clearance_system_asset_management_entry)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label" for="it_clearance_system_asset_management_entry">Asset Management Entry</label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <label><?= $certificate->it_clearance_system_validation ?></label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Reporting Manager’s Comments:</td>
                                                <td> <label><?= $certificate->it_clearance_reporting_manager_comment ?></label></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column text-right">
                                                        <div>Name of IT Rep:</div>
                                                        <div>Signature</div>
                                                        <div>Date:</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mb-1"><label><?= empty($certificate->it_clearance_rep_name) ? '-' : $certificate->it_clearance_rep_name ?></label></div>
                                                    <div></div>
                                                    <div class="mt-1"><label><?= !empty($certificate->it_clearance_rep_date) && '0000-00-00' != $certificate->it_clearance_rep_date ? date('d-m-Y', strtotime($certificate->it_clearance_rep_date)) : '' ?></label></div>

                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column text-right">
                                                        <div>Name of Reporting Manager:</div>
                                                        <div>Signature</div>
                                                        <div>Date:</div>
                                                    </div>
                                                </td>
                                                <td>

                                                    <div class="mb-1"><label><?= empty($certificate->it_clearance_reporting_manager_name) ? '-' : $certificate->it_clearance_reporting_manager_name ?></label></div>
                                                    <div class="mt-1"><label><?= !empty($certificate->it_clearance_reporting_manager_date) ? date('d-m-Y', strtotime($certificate->it_clearance_reporting_manager_date)) : '' ?></label></div>

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </form>
                            </div>
                            <div class="col-md-12" <?= ($show_admin_clearance_form) ? '' : 'hidden' ?>>
                                <h6>3. ADMIN CLEARANCE:</h6>
                                <form action="" name="admin_clearance_form" id="admin_clearance_form" method="POST" enctype="multipart/form-data">
                                    <table class="table table-bordered table-hover" id="table_admin_clearance">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th colspan="2" width="55%">Asset List (To be filled by Admin Rep):</th>
                                                <th colspan="2" width="40%">Asset details:</th>

                                            </tr>
                                        </thead>
                                        <tbody id="line_items_list_4">
                                            <?php for ($i = 0; $i < count($certificate->adamin_clearance_duties); $i++) {
                                                $row = $certificate->adamin_clearance_duties[$i] ?>
                                                <tr id='addr0'>
                                                    <td><?= $i + 1 ?></td>
                                                    <td colspan="2"><label><?= $row->admin_clearance_duties ?></label>
                                                    </td>
                                                    <td colspan="2">
                                                        <div class="d-flex justify-content-around">
                                                            <div class="align-items-center d-flex form-check form-group">
                                                                <input type="checkbox" id="admin_clearance_moved_to_store_0" name="admin_clearance_moved_to_store[0]" disabled <?= (!empty($row->admin_clearance_moved_to_store)) ? 'checked' : '' ?>>&nbsp;&nbsp;
                                                                <label class="form-check-label">Moved to stores</label>
                                                            </div>
                                                            <div class="align-items-center d-flex form-check form-group">
                                                                <input type="checkbox" disabled <?= (!empty($row->admin_clearance_management_entry)) ? 'checked' : '' ?>>&nbsp;&nbsp;
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
                                                <td><label><?= $certificate->admin_comments ?></label>
                                                <td colspan="2">
                                                    <div class="d-flex flex-column text-right">
                                                        <div>Name of Reporting Manager:</div>
                                                        <div>&nbsp;</div>
                                                        <div>Date:</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mb-1"><label><?= $certificate->admin_clearance_reporting_manager_name ?></label></div>
                                                    <div class="mt-1"><label><?= !empty($certificate->admin_clearance_date) ? date('d-m-Y', strtotime($certificate->admin_clearance_date)) : '' ?></label></div>

                                                </td>
                                            </tr>

                                        </tfoot>
                                    </table>

                                </form>
                            </div>

                            <div class="col-md-12" <?= ($show_hod_clearance_form) ? '' : 'hidden' ?>>
                                <h6>4. HOD VERIFICATION:</h6>
                                <form action="" name="hod_clearance_form" id="hod_clearance_form" method="POST" enctype="multipart/form-data">
                                    <table class="table table-bordered table-hover" id="table_hod_verification">
                                        <tbody>
                                            <tr>
                                                <td>HOD Comments</td>
                                                <td><label><?= $certificate->hod_comments ?></label></td>
                                                <td>
                                                    <div class="d-flex flex-column text-right">
                                                        <div>Name of HOD:</div>
                                                        <div>Signature</div>
                                                        <div>Date:</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mb-1"><label><?= $certificate->hod_verification_admin_rep_name ?></label></div>
                                                    <div></div>
                                                    <div class="mt-1"><label><?= !empty($certificate->hod_verification_date) && '0000-00-00' != $certificate->hod_verification_date ? date('d-m-Y', strtotime($certificate->hod_verification_date)) : '' ?></label></div>

                                                </td>
                                            </tr>

                                        </tbody>
                                        <tfoot>

                                        </tfoot>
                                    </table>

                                </form>
                            </div>
                            <div class="col-md-12" <?= ($show_hcm_clearance_form) ? '' : 'hidden' ?>>
                                <h6>5. HCM CLEARANCE:</h6>
                                <form action="" name="hcm_clearance_form" id="hcm_clearance_form" method="POST" enctype="multipart/form-data">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <td colspan="1" scope="col">ID Card</td>
                                                <td colspan="3">
                                                    <div class="d-flex justify-content-around">
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="radio" class="form-check-input" name="hcm_clearance_id_card_received" value="1" disabled <?= (!empty($certificate->hcm_clearance_id_card_received && $certificate->hcm_clearance_id_card_received == 1)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label">Received</label>
                                                        </div>
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="radio" class="form-check-input" name="hcm_clearance_id_card_received" value="2" disabled <?= (!empty($certificate->hcm_clearance_id_card_received && $certificate->hcm_clearance_id_card_received == 2)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label">Not Received
                                                            </label>
                                                        </div>
                                                        <div class="align-items-center d-flex form-check form-group">
                                                            <input type="radio" class="form-check-input" name="hcm_clearance_id_card_received" value="3" disabled <?= (!empty($certificate->hcm_clearance_id_card_received && $certificate->hcm_clearance_id_card_received == 3)) ? 'checked' : '' ?> />
                                                            <label class="form-check-label">Not issued</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Access Control Deleted Time and Date:</td>
                                                <td>
                                                    <div class="mt-1 cs-form">
                                                        <label><?= !empty($certificate->hcm_access_control_deleted_time) ? date('H:i', strtotime($certificate->hcm_access_control_deleted_time)) : '' ?></label>

                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mt-1">
                                                        <label><?= !empty($certificate->hcm_access_control_deleted_date) && '0000-00-00' != $certificate->hcm_access_control_deleted_date ? date('d-m-Y', strtotime($certificate->hcm_access_control_deleted_date)) : '' ?></label>

                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="align-items-center d-flex form-check form-group">
                                                        <input type="checkbox" class="form-check-input" name="hcm_clearance_is_nda" disabled <?= (!empty($certificate->hcm_clearance_is_nda)) ? 'checked' : '' ?> />
                                                        <label class="form-check-label" for="id4">Employee undertaking NDA</label>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <div class="mb-2">HCM Comments: <label><?= $certificate->hcm_comments ?></label></div>
                                                        <div class="d-flex justify-content-around">
                                                            <div class="align-items-center d-flex form-check form-group">
                                                                <input type="checkbox" class="form-check-input" name="is_ohrm_deactivated" disabled <?= (!empty($certificate->is_ohrm_deactivated)) ? 'checked' : '' ?> />
                                                                <label class="form-check-label">OHRM Deactivated
                                                                </label>
                                                            </div>
                                                            <div class="align-items-center d-flex form-check form-group">
                                                                <input type="checkbox" class="form-check-input" name="is_pulse_deactivated" disabled <?= (!empty($certificate->is_pulse_deactivated)) ? 'checked' : '' ?> />
                                                                <label class="form-check-label">Pulse Deactivated
                                                                </label>
                                                            </div>
                                                            <div class="align-items-center d-flex form-check form-group">
                                                                <input type="checkbox" class="form-check-input" name="is_timelinq_deactivated" disabled <?= (!empty($certificate->is_timelinq_deactivated)) ? 'checked' : '' ?> />
                                                                <label class="form-check-label">Timelinq Deactivated</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td colspan="2" class="text-right">
                                                    <div class="d-flex flex-column">
                                                        <div>Name of HCM Rep:</div>
                                                        <div>Signature:</div>
                                                        <div>Date:</div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="mb-1"><label><?= $certificate->hcm_rep_name ?></label></div>
                                                    <div></div>
                                                    <div class="mt-1"><label><?= !empty($certificate->hcm_clearance_date) && '0000-00-00' != $certificate->hcm_clearance_date ? date('d-m-Y', strtotime($certificate->hcm_clearance_date)) : '' ?></label>
                                                    </div>

                                                </td>

                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>

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
                                                        <div>Name</div>
                                                        <div>Signature:</div>
                                                        <div>Date:</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mb-1"><label><?= $certificate->final_approval_cfo_name ?></label></div>
                                                    <div></div>
                                                    <div class="mt-1"><label><?= !empty($certificate->final_approval_date && '0000-00-00' != $certificate->final_approval_date) ? date('d-m-Y', strtotime($certificate->final_approval_date)) : '' ?></label>

                                                    </div>

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </form>
                            </div>
                            <div class="col-md-12" <?= ($show_final_clearance_form) ? '' : 'hidden' ?>>
                                <h6>Final Settlement Details (For Corporate Office Use Only)</h6>
                                <form action="" name="final_clearance_form" id="final_clearance_form" method="POST" enctype="multipart/form-data">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td width="60%">Date of Resignation</td>
                                                <td><label><?= !empty($resignation->relieving_date) ? date('d-m-Y', strtotime($resignation->relieving_date)) : ''  ?></label></td>
                                            </tr>
                                            <tr>
                                                <td>Date of Relieving</td>
                                                <td><label><?= !empty($resignation->agreed_relieving_date && '0000-00-00' != $resignation->agreed_relieving_date) ? date('d-m-Y', strtotime($resignation->agreed_relieving_date)) : ''  ?></label></td>
                                            </tr>
                                            <tr>
                                                <td>Notice Period</td>
                                                <td><label><?= !empty($employee_details->notice_period) ? $employee_details->notice_period : '' ?></label></td>
                                            </tr>
                                            <tr>
                                                <td>No of days short of notice period</td>
                                                <td><label><?= $no_of_days_short ?></label></td>
                                            </tr>
                                            <tr>
                                                <td>PL Balance</td>
                                                <td><label><?= $certificate->pl_balance ?></label></td>
                                            </tr>
                                            <tr>
                                                <td>PL / SL Excess taken to be recovered</td>
                                                <td><label><?= $certificate->pl_excess ?></label></td>
                                            </tr>
                                            <tr>
                                                <td>No. of days Salary to be paid</td>
                                                <td><label><?= $certificate->no_of_days ?></label></td>
                                            </tr>
                                            <tr>
                                                <td>Net Salary Payable</td>
                                                <td><label><?= $certificate->net_salary ?></label></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    PL Encashment (if any) after adjusting the notice period
                                                </td>
                                                <td><label><?= $certificate->pl_encashment ?></label></td>
                                            </tr>
                                            <tr>
                                                <td>Eligible for Gratuity</td>
                                                <td><label><?= $certificate->is_gratuity ?></label></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Any Salary Advance/Loan outstanding against the individual /
                                                    Lost assets / IT proofs (bills)
                                                </td>
                                                <td><label><?= $certificate->salary_advance ?></label></td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">Final Amount Payable</td>
                                                <td><label><?= $certificate->payable_amount ?></label></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="col-md-12">
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
                                        <p>
                                            <span>2.</span> I hereby acknowledge the receipt of cheque
                                            No <label><?= $certificate->check_no ?></label> dated <label><?= !empty($certificate->check_no_date && '0000-00-00' != $certificate->check_no_date) ? date('d-m-Y', strtotime($certificate->check_no_date)) : ''  ?></label> Drawn on <label><?= !empty($certificate->check_drawn_date && '0000-00-00' != $certificate->check_drawn_date) ? date('d-m-Y', strtotime($certificate->check_drawn_date)) : ''  ?></label>
                                            Bank, Bangalore for Rs <label><?= $certificate->check_amount ?></label> towards salary in full settlement
                                            of my account with the company, I further confirm that there is no
                                            other amount due to and from the company.
                                        </p>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <div class="mb-5 text-right">
                <div class="col-sm-12">
                    <div class="mt-2 ">
                        <input type="button" class="btn btn-lg btn-primary btnsmt" value="Print" onclick="printCertificate()" />
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

                // "section_2_question_1": {
                //     required: true,
                // },
                // "section_2_question_2": {
                //     required: true,
                // },
                // "section_2_question_3": {
                //     required: true,
                // },
                // "section_2_question_4": {
                //     required: true,
                // },
                // "section_2_question_5": {
                //     required: true,
                // },
                // "section_2_question_6": {
                //     required: true,
                // },
                // "section_2_question_7": {
                //     required: true,
                // },

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
            submitHandler: function(form, e) {
                e.preventDefault();
                //$('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                //$('.page-loader-wrapper').removeAttr('style');
                var form_data = new FormData(form);
                //form_data.append('type', '');

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
                                    //window.location.href = base_url + 'view-resignation';
                                }, 2000);
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

                // "section_2_question_1": {
                //     required: true,
                // },
                // "section_2_question_2": {
                //     required: true,
                // },
                // "section_2_question_3": {
                //     required: true,
                // },
                // "section_2_question_4": {
                //     required: true,
                // },
                // "section_2_question_5": {
                //     required: true,
                // },
                // "section_2_question_6": {
                //     required: true,
                // },
                // "section_2_question_7": {
                //     required: true,
                // },

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
            submitHandler: function(form, e) {
                e.preventDefault();
                //$('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                //$('.page-loader-wrapper').removeAttr('style');
                var form_data = new FormData(form);
                form_data.append('clearance_certificate_id', '<?= $clearance_certificate_id ?>');

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
                                    //window.location.href = base_url + 'view-resignation';
                                }, 2000);
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

        $('#admin_clearance_form').validate({
            rules: {

                // "section_2_question_1": {
                //     required: true,
                // },
                // "section_2_question_2": {
                //     required: true,
                // },
                // "section_2_question_3": {
                //     required: true,
                // },
                // "section_2_question_4": {
                //     required: true,
                // },
                // "section_2_question_5": {
                //     required: true,
                // },
                // "section_2_question_6": {
                //     required: true,
                // },
                // "section_2_question_7": {
                //     required: true,
                // },

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
            submitHandler: function(form, e) {
                e.preventDefault();
                //$('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                //$('.page-loader-wrapper').removeAttr('style');
                var form_data = new FormData(form);
                form_data.append('clearance_certificate_id', '<?= $clearance_certificate_id ?>');

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
                                    //window.location.href = base_url + 'view-resignation';
                                }, 2000);
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

                // "section_2_question_1": {
                //     required: true,
                // },
                // "section_2_question_2": {
                //     required: true,
                // },
                // "section_2_question_3": {
                //     required: true,
                // },
                // "section_2_question_4": {
                //     required: true,
                // },
                // "section_2_question_5": {
                //     required: true,
                // },
                // "section_2_question_6": {
                //     required: true,
                // },
                // "section_2_question_7": {
                //     required: true,
                // },

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
            submitHandler: function(form, e) {
                e.preventDefault();
                //$('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                //$('.page-loader-wrapper').removeAttr('style');
                var form_data = new FormData(form);
                form_data.append('clearance_certificate_id', '<?= $clearance_certificate_id ?>');

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
                                    //window.location.href = base_url + 'view-resignation';
                                }, 2000);
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

                // "section_2_question_1": {
                //     required: true,
                // },
                // "section_2_question_2": {
                //     required: true,
                // },
                // "section_2_question_3": {
                //     required: true,
                // },
                // "section_2_question_4": {
                //     required: true,
                // },
                // "section_2_question_5": {
                //     required: true,
                // },
                // "section_2_question_6": {
                //     required: true,
                // },
                // "section_2_question_7": {
                //     required: true,
                // },

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
            submitHandler: function(form, e) {
                e.preventDefault();
                //$('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                //$('.page-loader-wrapper').removeAttr('style');
                var form_data = new FormData(form);
                form_data.append('clearance_certificate_id', '<?= $clearance_certificate_id ?>');

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
                                    //window.location.href = base_url + 'view-resignation';
                                }, 2000);
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

                // "section_2_question_1": {
                //     required: true,
                // },
                // "section_2_question_2": {
                //     required: true,
                // },
                // "section_2_question_3": {
                //     required: true,
                // },
                // "section_2_question_4": {
                //     required: true,
                // },
                // "section_2_question_5": {
                //     required: true,
                // },
                // "section_2_question_6": {
                //     required: true,
                // },
                // "section_2_question_7": {
                //     required: true,
                // },

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
            submitHandler: function(form, e) {
                e.preventDefault();
                //$('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                //$('.page-loader-wrapper').removeAttr('style');
                var form_data = new FormData(form);
                form_data.append('clearance_certificate_id', '<?= $clearance_certificate_id ?>');

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
                                    //window.location.href = base_url + 'view-resignation';
                                }, 2000);
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

                // "section_2_question_1": {
                //     required: true,
                // },
                // "section_2_question_2": {
                //     required: true,
                // },
                // "section_2_question_3": {
                //     required: true,
                // },
                // "section_2_question_4": {
                //     required: true,
                // },
                // "section_2_question_5": {
                //     required: true,
                // },
                // "section_2_question_6": {
                //     required: true,
                // },
                // "section_2_question_7": {
                //     required: true,
                // },

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
            submitHandler: function(form, e) {
                e.preventDefault();
                //$('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                //$('.page-loader-wrapper').removeAttr('style');
                var form_data = new FormData(form);
                form_data.append('clearance_certificate_id', '<?= $clearance_certificate_id ?>');

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
                                    //window.location.href = base_url + 'view-resignation';
                                }, 2000);
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
            // $("#clearance_certificate_form #employee_duties_" + i).rules("add", {
            //     required: true,
            //     messages: {}
            // });

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
            // $("#clearance_certificate_form #employee_duties_" + i).rules("add", {
            //     required: true,
            //     messages: {}
            // });

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
            // $("#clearance_certificate_form #employee_duties_" + i).rules("add", {
            //     required: true,
            //     messages: {}
            // });

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
                                <input type="checkbox" id="admin_clearance_moved_to_store_0" name="admin_clearance_moved_to_store[` + index + `]">&nbsp;&nbsp;
                                <label class="form-check-label">Moved to stores</label>
                            </div>
                            <div class="align-items-center d-flex form-check form-group">
                                <input type="checkbox" id="admin_clearance_management_entry_0" name="admin_clearance_management_entry[` + index + `]">&nbsp;&nbsp;
                                <label class="form-check-label">Asset Management Entry</label>
                            </div>
                        </div>
                </td>          
            </tr>`;

            $('#line_items_list_4').append(line_item_html);
            // $("#clearance_certificate_form #admin_employee_duties_" + i).rules("add", {
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

    function printCertificate() {
        var printContents = document.getElementById('mainDiv').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

    //$('body #table_design input[type="text"]').keyup(resizeInput).each(resizeInput);
</script>