<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<style>
    .error-text {
        color: red;
    }
</style>
<?php
$show_submit_button = false;
$session_employee_id = $this->session->userdata('employee_id');
if ($session_employee_id == $employee_id) {
    $show_submit_button = true;
}
?>
<div class="row clearfix">
    <div class="col-sm-12">
        <?php ?>
        <form action="" name="exit_interview_form" id="exit_interview_form" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="body">
                    <table class="table table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <h4>Exit Interview Form-Employee</h4>
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <h5>Employee Information</h5>
                    <table class="table table-bordered table-hover" width="100%">
                        <thead>

                            <tr>
                                <th width="5%">No.</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>

                        <body>
                            <tr>
                                <td>1</td>
                                <td>Employee Code</td>
                                <th><?= $employee_details->code ?></th>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Name</td>
                                <th><?= $employee_details->name ?></th>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Designation</td>
                                <th><?= $employee_details->designation ?></th>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Reporting Manager Name</td>
                                <th><?= $employee_details->reporting_manager_name ?></th>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Work Location </td>
                                <th><?= $employee_details->work_location ?></th>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Your Permanent Address</td>
                                <th><?= $employee_details->permanent_address ?></th>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Your Contact Number</td>
                                <th><?= $employee_details->phone_current ?></th>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>Your personal Email Id<sup>*</sup></td>
                                <th><input type="text" class="form-control" id="section_1_question_8" name="section_1_question_8" value="<?= !empty($interview->section_1_question_8) ? $interview->section_1_question_8 : '' ?>"></input></th>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Date of Resignation</td>
                                <th><?= !empty($resignation->agreed_relieving_date) ? date('d-m-Y', strtotime($resignation->agreed_relieving_date)) : '' ?></th>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Last Working Day<sup>*</sup></td>
                                <th><input type="text" readonly data-date-start-date="today" class="form-control" id="section_1_question_10" name="section_1_question_10" data-provide="datepicker" data-date-format="dd-mm-yyyy" data-date-autoclose="true" value="<?= empty($interview->section_1_question_10) ? '' : date('d-m-Y', strtotime($interview->section_1_question_10)) ?>"></input></th>
                            </tr>
                        </body>
                    </table>
                </div>
            </div>
            <h5>Feedback about Ksoft Technologies</h5>
            <div class="card">
                <div class="body">
                    <input type="hidden" class="form-control" id="resignation_id" name="resignation_id" value="<?= $resignation_id ?>"></input>
                    <input type="hidden" class="form-control" id="employee_id" name="employee_id" value="<?= $employee_id ?>"></input>
                    <input type="hidden" class="form-control" name="id" value="<?= $exit_interview_id ?>"></input>
                    <table class="table table-bordered table-hover" width="100%">
                        <thead>

                            <tr>
                                <th width="5%">No.</th>
                                <th width="40%">Question</th>
                                <th>Answer</th>
                            </tr>
                        </thead>

                        <body>
                            <tr>
                                <td>1</td>
                                <td>Reason for separation<sup>*</sup></td>
                                <th><select class="form-control show-tick" name="section_2_question_1">
                                        <option value="">Select Reason</option>
                                        <?php foreach ($reasons as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_2_question_1) && $interview->section_2_question_1 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select></th>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Name of the New Employer or company you are joining<sup>*</sup></td>
                                <th><input type="text" class="form-control" id="section_2_question_2" name="section_2_question_2" value="<?= (empty($interview->section_2_question_2) ? '' : $interview->section_2_question_2) ?>"></input></th>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>New CTC or % Hike offered by the new company<sup>*</sup></td>

                                <th><input type="text" class="form-control" id="section_2_question_3" name="section_2_question_3" value="<?= (empty($interview->section_2_question_3) ? '' : $interview->section_2_question_3) ?>"></input></th>

                            </tr>
                            <tr>
                                <td>4</td>
                                <td>New position/designation offered by the new company<sup>*</sup></td>

                                <th><input type="text" class="form-control" id="section_2_question_4" name="section_2_question_4" value="<?= (empty($interview->section_2_question_4) ? '' : $interview->section_2_question_4) ?>"></input></th>

                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Sequences of events that made you leave Ksoft Technologies<sup>*</sup></td>

                                <th><input type="text" class="form-control" id="section_2_question_5" name="section_2_question_5" value="<?= (empty($interview->section_2_question_5) ? '' : $interview->section_2_question_5) ?>"></input></th>

                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Did you try to explore alternate solutions within Ksoft Technologies before you decided to leave Ksoft Technologies?<sup>*</sup> </td>

                                <th><input type="text" class="form-control" id="section_2_question_6" name="section_2_question_6" value="<?= (empty($interview->section_2_question_6) ? '' : $interview->section_2_question_6) ?>"></input></th>

                            </tr>
                            <tr>
                                <td>7</td>
                                <td>When you informed Ksoft Technologies about your decision to leave, did anyone propose alternates?<sup>*</sup></td>

                                <th><input type="text" class="form-control" id="section_2_question_7" name="section_2_question_7" value="<?= (empty($interview->section_2_question_7) ? '' : $interview->section_2_question_7) ?>"></input></th>

                            </tr>
                            <tr>
                                <td>8</td>
                                <td>What are the positive elements you saw at Ksoft Technologies?<sup>*</sup></td>

                                <th><input type="text" class="form-control" id="section_2_question_8" name="section_2_question_8" value="<?= (empty($interview->section_2_question_8) ? '' : $interview->section_2_question_8) ?>"></input></th>

                            </tr>
                            <tr>
                                <td>9</td>
                                <td>What did you find most frustrating about your job at Ksoft Technologies?<sup>*</sup></td>

                                <th><input type="text" class="form-control" id="section_2_question_9" name="section_2_question_9" value="<?= (empty($interview->section_2_question_9) ? '' : $interview->section_2_question_9) ?>"></input></th>

                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Were there any company polices and procedures that made your work difficult at Ksoft Technologies?<sup>*</sup></td>

                                <th><input type="text" class="form-control" id="section_2_question_10" name="section_2_question_10" value="<?= (empty($interview->section_2_question_10) ? '' : $interview->section_2_question_10) ?>"></input></th>

                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Is there anything Ksoft Technologies could have done to prevent you from leaving?<sup>*</sup></td>

                                <th><input type="text" class="form-control" id="section_2_question_11" name="section_2_question_11" value="<?= (empty($interview->section_2_question_11) ? '' : $interview->section_2_question_11) ?>"></input></th>

                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Would you consider joining Ksoft Technologies in future (Yes/No)?<sup>*</sup></td>

                                <th>
                                    <select class="form-control show-tick" id="section_2_question_12" name="section_2_question_12">

                                        <option value="1" <?= ($interview->section_2_question_12 == 1) ? 'selected' : '' ?>>Yes</option>
                                        <option value="2" <?= ($interview->section_2_question_12 == 2) ? 'selected' : '' ?>>No</option>

                                    </select>
                                    
                                </th>

                            </tr>
                            <tr>
                                <td>13</td>
                                <td>If your response is No to the previous Question then please specify your reason here.<sup>*</sup></td>

                                <th><input type="text" class="form-control" id="section_2_question_13" name="section_2_question_13" value="<?= (empty($interview->section_2_question_13) ? '' : $interview->section_2_question_13) ?>"></input></th>

                            </tr>
                            <tr>
                                <td>14</td>
                                <td>What changes would you like to see at Ksoft Technologies if you were to reconsider joining Ksoft Technologies at some point of time in future?<sup>*</sup></td>

                                <th><input type="text" class="form-control" id="section_2_question_14" name="section_2_question_14" value="<?= (empty($interview->section_2_question_14) ? '' : $interview->section_2_question_14) ?>"></input></th>

                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Would you recommend Ksoft Technologies as employer to your friends (Yes/No)<sup>*</sup></td>

                                <th><select class="form-control show-tick" id="section_2_question_15" name="section_2_question_15">

                                        <option value="1" <?= ($interview->section_2_question_15 == 1) ? 'selected' : '' ?>>Yes</option>
                                        <option value="2" <?= ($interview->section_2_question_15 == 2) ? 'selected' : '' ?>>No</option>

                                    </select>
                                </th>

                            </tr>

                        </body>

                    </table>
                    <h5>Feedback about Your Reporting Manager (Please select one of the options from the drop down list provided for each question) </h5>
                    <table class="table table-bordered table-hover mt-" width="100%">
                        <thead>

                            <tr>
                                <th width="5%">No.</th>
                                <th width="40%">Question</th>
                                <th>Answer</th>
                            </tr>
                        </thead>

                        <body>
                            <tr>
                                <td>1</td>
                                <td>Demonstrated fair and equal treatment<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_3_question_1" name="section_3_question_1">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_3_question_1) && $interview->section_3_question_1 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Encouraged participation in decision-making process and listened to suggestions<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_3_question_2" name="section_3_question_2">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_3_question_2) && $interview->section_3_question_2 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Provided recognition on the job<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_3_question_3" name="section_3_question_3">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_3_question_3) && $interview->section_3_question_3 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Developed cooperation and teamwork and boosted team morale<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_3_question_4" name="section_3_question_4">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_3_question_4) && $interview->section_3_question_4 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Resolved complaints and problems.<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_3_question_5" name="section_3_question_5">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_3_question_5) && $interview->section_3_question_5 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Provided Developmental/Training / On-the-job learning Opportunities.<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_3_question_6" name="section_3_question_6">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_3_question_6) && $interview->section_3_question_6 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Made expectations clear.<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_3_question_7" name="section_3_question_7">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_3_question_7) && $interview->section_3_question_7 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>

                            <tr>
                                <td>8</td>
                                <td>Gave adequate and timely feedback.<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_3_question_8" name="section_3_question_8">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_3_question_8) && $interview->section_3_question_8 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>

                        </body>

                    </table>
                    <h5>Feedback about Ksoft Technologies Processes (Please select one of the options from the drop down list provided for each question) </h5>
                    <table class="table table-bordered table-hover mt-2" width="100%">
                        <thead>

                            <tr>
                                <th width="5%">No.</th>
                                <th width="40%">Question</th>
                                <th>Answer</th>
                            </tr>
                        </thead>

                        <body>
                            <tr>
                                <td>1</td>
                                <td>Induction.<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_4_question_1" name="section_4_question_1">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_4_question_1) && $interview->section_4_question_1 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Meaningful work.<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_4_question_2" name="section_4_question_2">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_4_question_2) && $interview->section_4_question_2 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Development/Training Opportunities.<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_4_question_3" name="section_4_question_3">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_4_question_3) && $interview->section_4_question_3 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Work Environment.<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_4_question_4" name="section_4_question_4">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_4_question_4) && $interview->section_4_question_4 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Opportunities for advancement.<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_4_question_5" name="section_4_question_5">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_4_question_5) && $interview->section_4_question_5 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Recognition at Work.<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_4_question_6" name="section_4_question_6">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_4_question_6) && $interview->section_4_question_6 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Compensation & Benefits.<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_4_question_7" name="section_4_question_7">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_4_question_7) && $interview->section_4_question_7 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>

                            <tr>
                                <td>8</td>
                                <td>Quality of Supervision.<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_4_question_8" name="section_4_question_8">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_4_question_8) && $interview->section_4_question_8 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Co-Workers.<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_4_question_9" name="section_4_question_9">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_4_question_9) && $interview->section_4_question_9 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>

                            <tr>
                                <td>10</td>
                                <td>Performance Management System.<sup>*</sup></td>
                                <th><select class="form-control show-tick" id="section_4_question_10" name="section_4_question_10">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>" <?= (!empty($interview->section_4_question_10) && $interview->section_4_question_10 == $row['id']) ? 'selected' : '' ?>><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>

                        </body>

                    </table>

                </div>
            </div>
            <div class="row clearfix text-center mb-5" <?= ($show_submit_button) ? '' : 'hidden' ?>>
                <div class="col-sm-12">
                    <div class="mt-2">
                        <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />
                        <input type="reset" class="btn btn-lg btn-danger" value="Reset">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?= base_url() ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script src="<?= base_url() ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">
    $(function() {

        $('#exit_interview_form').validate({
            rules: {

                "section_1_question_8": {
                    required: true,
                },
                "section_1_question_10": {
                    required: true,
                },

                "section_2_question_1": {
                    required: true,
                },
                "section_2_question_2": {
                    required: true,
                },
                "section_2_question_3": {
                    required: true,
                },
                "section_2_question_4": {
                    required: true,
                },
                "section_2_question_5": {
                    required: true,
                },
                "section_2_question_6": {
                    required: true,
                },
                "section_2_question_7": {
                    required: true,
                },
                "section_2_question_8": {
                    required: true,
                },
                "section_2_question_9": {
                    required: true,
                },
                "section_2_question_10": {
                    required: true,
                },
                "section_2_question_11": {
                    required: true,
                },
                "section_2_question_12": {
                    required: true,
                },
                "section_2_question_13": {
                    required: true,
                },
                "section_2_question_14": {
                    required: true,
                },
                "section_2_question_15": {
                    required: true,
                },
                "section_3_question_1": {
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
                element.after(error);

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
                //form_data.append('type', '');

                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'resignation/add_exit_interview_process',
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
    });

    //$('body #table_design input[type="text"]').keyup(resizeInput).each(resizeInput);
</script>