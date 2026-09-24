<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">

<?php
$role=$this->session->userdata('type');
//echo $interview->status;
$show_approve_button = (!empty($interview->status) && $interview->status==1 && $role==1)?true:false;
?>
<div class="row clearfix">
    <div class="col-sm-12">
        <form>
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
                                <td>Your personal Email Id</td>
                                <th><?= !empty($interview->section_1_question_8) ? $interview->section_1_question_8 : '' ?></th>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Date of Resignation</td>
                                <th><?= !empty($resignation->agreed_relieving_date) ? date('d-m-Y', strtotime($resignation->agreed_relieving_date)) : '' ?></th>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Last Working Day</td>
                                <th><?= empty($interview->section_1_question_10) ? '' : date('d-m-Y', strtotime($interview->section_1_question_10)) ?></th>
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
                                <td>Reason for separation</td>
                                <th><?= (empty($interview->section_2_question_1) ? '' : $interview->section_2_question_1) ?></th>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Name of the New Employer or company you are joining</td>
                                <th><?= (empty($interview->section_2_question_2) ? '' : $interview->section_2_question_2) ?></th>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>New CTC or % Hike offered by the new company</td>
                                <th><?= (empty($interview->section_2_question_3) ? '' : $interview->section_2_question_3) ?></th>

                            </tr>
                            <tr>
                                <td>4</td>
                                <td>New position/designation offered by the new company</td>
                                <th><?= (empty($interview->section_2_question_4) ? '' : $interview->section_2_question_4) ?></th>

                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Sequences of events that made you leave Ksoft Technologies</td>
                                <th><?= (empty($interview->section_2_question_5) ? '' : $interview->section_2_question_5) ?></th>

                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Did you try to explore alternate solutions within Ksoft Technologies before you decided to leave Ksoft Technologies? </td>
                                <th><?= (empty($interview->section_2_question_6) ? '' : $interview->section_2_question_6) ?></th>

                            </tr>
                            <tr>
                                <td>7</td>
                                <td>When you informed Ksoft Technologies about your decision to leave, did anyone propose alternates?</td>
                                <th><?= (empty($interview->section_2_question_7) ? '' : $interview->section_2_question_7) ?></th>

                            </tr>
                            <tr>
                                <td>8</td>
                                <td>What are the positive elements you saw at Ksoft Technologies?</td>
                                <th><?= (empty($interview->section_2_question_8) ? '' : $interview->section_2_question_8) ?></th>

                            </tr>
                            <tr>
                                <td>9</td>
                                <td>What did you find most frustrating about your job at Ksoft Technologies?</td>
                                <th><?= (empty($interview->section_2_question_9) ? '' : $interview->section_2_question_9) ?></th>

                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Were there any company polices and procedures that made your work difficult at Ksoft Technologies?</td>
                                <th><?= (empty($interview->section_2_question_10) ? '' : $interview->section_2_question_10) ?></th>

                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Is there anything Ksoft Technologies could have done to prevent you from leaving?</td>
                                <th><?= (empty($interview->section_2_question_11) ? '' : $interview->section_2_question_11) ?></th>

                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Would you consider joining Ksoft Technologies in future (Yes/No)?</td>
                                <th><?= (empty($interview->section_2_question_12) ? '' : $interview->section_2_question_12) ?></th>

                            </tr>
                            <tr>
                                <td>13</td>
                                <td>If your response is No to the previous Question then please specify your reason here.</td>
                                <th><?= (empty($interview->section_2_question_13) ? '' : $interview->section_2_question_13) ?></th>

                            </tr>
                            <tr>
                                <td>14</td>
                                <td>What changes would you like to see at Ksoft Technologies if you were to reconsider joining Ksoft Technologies at some point of time in future?</td>
                                <th><?= (empty($interview->section_2_question_14) ? '' : $interview->section_2_question_14) ?></th>

                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Would you recommend Ksoft Technologies as employer to your friends (Yes/No)</td>
                                <th><?= (empty($interview->section_2_question_15) ? '' : $interview->section_2_question_15) ?></th>

                            </tr>

                        </body>

                    </table>
                    <h5>Feedback about Your Reporting Manager (Please select one of the options from the drop down list provided for each question) </h5>
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
                                <td>Demonstrated fair and equal treatment.</td>
                                <th><?= (empty($interview->section_3_question_1) ? '' : $interview->section_3_question_1) ?></th>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Encouraged participation in decision-making process and listened to suggestions</td>
                                <th><?= (empty($interview->section_3_question_2) ? '' : $interview->section_3_question_2) ?></th>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Provided recognition on the job</td>
                                <th><?= (empty($interview->section_3_question_4) ? '' : $interview->section_3_question_4) ?></th>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Developed cooperation and teamwork and boosted team morale.</td>
                                <th><?= (empty($interview->section_3_question_4) ? '' : $interview->section_3_question_4) ?></th>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Resolved complaints and problems .</td>
                                <th><?= (empty($interview->section_3_question_5) ? '' : $interview->section_3_question_5) ?></th>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Provided Developmental/Training / On-the-job learning Opportunities.</td>
                                <th><?= (empty($interview->section_3_question_6) ? '' : $interview->section_3_question_6) ?></th>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Made expectations clear.</td>
                                <th><?= (empty($interview->section_3_question_7) ? '' : $interview->section_3_question_7) ?></th>
                            </tr>

                            <tr>
                                <td>8</td>
                                <td>Gave adequate and timely feedback.</td>
                                <th><?= (empty($interview->section_3_question_8) ? '' : $interview->section_3_question_8) ?></th>
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
                                <td>Induction.</td>
                                <th><?= (empty($interview->section_4_question_1) ? '' : $interview->section_4_question_1) ?></th>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Meaningful work</td>
                                <th><?= (empty($interview->section_4_question_2) ? '' : $interview->section_4_question_2) ?></th>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Development/Training Opportunities</td>
                                <th><?= (empty($interview->section_4_question_3) ? '' : $interview->section_4_question_3) ?> </th>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Work Environment.</td>
                                <th><?= (empty($interview->section_4_question_4) ? '' : $interview->section_4_question_4) ?></th>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Opportunities for advancement.</td>
                                <th><?= (empty($interview->section_4_question_5) ? '' : $interview->section_4_question_5) ?></th>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Recognition at Work.</td>
                                <th><?= (empty($interview->section_4_question_6) ? '' : $interview->section_4_question_6) ?></th>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Compensation & Benefits.</td>
                                <th><?= (empty($interview->section_4_question_7) ? '' : $interview->section_4_question_7) ?></th>
                            </tr>

                            <tr>
                                <td>8</td>
                                <td>Quality of Supervision.</td>
                                <th><?= (empty($interview->section_4_question_8) ? '' : $interview->section_4_question_8) ?></th>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Co-Workers.</td>
                                <th><?= (empty($interview->section_4_question_9) ? '' : $interview->section_4_question_9) ?></th>
                            </tr>

                            <tr>
                                <td>10</td>
                                <td>Performance Management System.</td>
                                <th><?= (empty($interview->section_4_question_10) ? '' : $interview->section_4_question_10) ?></th>
                            </tr>

                        </body>

                    </table>

                </div>
            </div>
            <div class="col-sm-12 text-center mb-5 mt-2" <?= ($show_approve_button) ? '' : 'hidden' ?>>
                <div class="col-sm-12">
                    <div>
                        <input type="button" class="btn btn-lg btn-primary btnsmt" value="Complete" onclick="updateExitInterview()" />
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

    });

    function updateExitInterview() {
            var interviewId = "<?= $interview->id ?>";
            var resigId = "<?= $interview->resignation_id ?>";
            swal({
                title: "Completed?",
                text: "Exit interview completed?",
                showCancelButton: true,
                confirmButtonColor: "#dc3545",
                confirmButtonText: "Yes",
                closeOnConfirm: false,
                showLoaderOnConfirm: true,

            }, function(reason) {
                if (reason != '') {
                    setTimeout(function() {
                        $.ajax({
                            type: 'POST',
                            url: base_url + 'resignation/update_exit_interview_status',
                            cache: false,
                            async: false,
                            data: "interview_id=" + interviewId+"&resignation_id="+resigId,
                            dataType: "html",
                            success: function(response) {
                                var obj = $.parseJSON(response);
                                if (obj.status == 1) {
                                    toaster('success', obj.msg);
                                    window.location.reload();
                                    //window.location.href = base_url + 'mrf';
                                    //$('#modal_ajax').modal('toggle');
                                } else {
                                    toaster('error', obj.msg);
                                    setTimeout(() => {
                                        //window.location.reload();
                                    }, 1000);
                                }
                            },
                            error: function(error) {
                                toaster('error', error);
                                setTimeout(() => {
                                    //window.location.reload();
                                }, 1000);
                            }
                        });

                    }, 500);
                }

            });
        }

    //$('body #table_design input[type="text"]').keyup(resizeInput).each(resizeInput);
</script>