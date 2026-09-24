<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">

<div class="row clearfix">
    <div class="col-sm-12">
        <?php ?>
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

                <table class="table table-bordered table-hover" width="100%">
                    <thead>
                        <tr>
                            <th colspan="3">
                                <h5>Employee Information</h5>
                            </th>
                        </tr>
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
                            <td>Project Name</td>
                            <th><?= $employee_details->code ?></th>
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
                            <th><input type="text" class="form-control" id="section_1_question_8" name="section_1_question_8"></input></th>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>Date of Resignation</td>
                            <th><?= !empty($resignation->agreed_relieving_date) ? date('d-m-Y', strtotime($resignation->agreed_relieving_date)) : '' ?></th>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>Last Working Day</td>
                            <th><input type="text" class="form-control" id="section_1_question_10" name="section_1_question_10"></input></th>
                        </tr>
                    </body>
                </table>
            </div>
        </div>
        <form action="" name="exit_interview_form" id="exit_interview_form" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="body">
                    <input type="hidden" class="form-control" id="resignation_id" name="resignation_id" value="<?= $resignation_id ?>"></input>
                    <input type="hidden" class="form-control" id="employee_id" name="employee_id" value="<?= $employee_id ?>"></input>
                    <table class="table table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th colspan="3">
                                    <h5>Feedback about Ksoft Technologies</h5>
                                </th>
                            </tr>
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
                                <th><select class="form-control show-tick" name="section_2_question_1">
                                        <option value="">Select Reason</option>
                                        <?php foreach ($reasons as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select></th>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Name of the New Employer or company you are joining</td>
                                <th><input type="text" class="form-control" id="section_2_question_2" name="section_2_question_2"></input></th>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>New CTC or % Hike offered by the new company</td>

                                <th><input type="text" class="form-control" id="section_2_question_3" name="section_2_question_3"></input></th>

                            </tr>
                            <tr>
                                <td>4</td>
                                <td>New position/designation offered by the new company</td>

                                <th><input type="text" class="form-control" id="section_2_question_4" name="section_2_question_4"></input></th>

                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Sequences of events that made you leave Ksoft Technologies</td>

                                <th><input type="text" class="form-control" id="section_2_question_5" name="section_2_question_5"></input></th>

                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Did you try to explore alternate solutions within Ksoft Technologies before you decided to leave Ksoft Technologies? </td>

                                <th><input type="text" class="form-control" id="section_2_question_6" name="section_2_question_6"></input></th>

                            </tr>
                            <tr>
                                <td>7</td>
                                <td>When you informed Ksoft Technologies about your decision to leave, did anyone propose alternates?</td>

                                <th><input type="text" class="form-control" id="section_2_question_7" name="section_2_question_7"></input></th>

                            </tr>
                            <tr>
                                <td>8</td>
                                <td>What are the positive elements you saw at Ksoft Technologies?</td>

                                <th><input type="text" class="form-control" id="section_2_question_8" name="section_2_question_8"></input></th>

                            </tr>
                            <tr>
                                <td>9</td>
                                <td>what did you find most frustrating about your job at Ksoft Technologies?</td>

                                <th><input type="text" class="form-control" id="section_2_question_9" name="section_2_question_9"></input></th>

                            </tr>
                            <tr>
                                <td>10</td>
                                <td>Were there any company polices and procedures that made your work difficult at Ksoft Technologies?</td>

                                <th><input type="text" class="form-control" id="section_2_question_10" name="section_2_question_10"></input></th>

                            </tr>
                            <tr>
                                <td>11</td>
                                <td>Is there anything Ksoft Technologies could have done to prevent you from leaving?</td>

                                <th><input type="text" class="form-control" id="section_2_question_11" name="section_2_question_11"></input></th>

                            </tr>
                            <tr>
                                <td>12</td>
                                <td>Would you consider joining Ksoft Technologies in future (Yes/No)?</td>

                                <th><input type="text" class="form-control" id="section_2_question_12" name="section_2_question_12"></input></th>

                            </tr>
                            <tr>
                                <td>13</td>
                                <td>If your response is No to the previous Question then please specify your reason here.</td>

                                <th><input type="text" class="form-control" id="section_2_question_13" name="section_2_question_13"></input></th>

                            </tr>
                            <tr>
                                <td>14</td>
                                <td>What changes would you like to see at Ksoft Technologies if you were to reconsider joining Ksoft Technologies at some point of time in future?</td>

                                <th><input type="text" class="form-control" id="section_2_question_14" name="section_2_question_14"></input></th>

                            </tr>
                            <tr>
                                <td>15</td>
                                <td>Would you recommend Ksoft Technologies as employer to your friends (Yes/No)</td>

                                <th><input type="text" class="form-control" id="section_2_question_15" name="section_2_question_15"></input></th>

                            </tr>

                        </body>

                    </table>
                    <table class="table table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th colspan="3">
                                    <h5>Feedback about Your Reporting Manager (Please select one of the options from the drop down list provided for each question) </h5>
                                </th>

                            </tr>
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
                                <th><select class="form-control show-tick" id="section_3_question_1" name="section_3_question_1">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Encouraged participation in decision-making process and listened to suggestions</td>
                                <th><select class="form-control show-tick" id="section_3_question_2" name="section_3_question_2">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Provided recognition on the job</td>
                                <th><select class="form-control show-tick" id="section_3_question_3" name="section_3_question_3">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Developed cooperation and teamwork and boosted team morale.</td>
                                <th><select class="form-control show-tick" id="section_3_question_4" name="section_3_question_4">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Resolved complaints and problems .</td>
                                <th><select class="form-control show-tick" id="section_3_question_5" name="section_3_question_5">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Provided Developmental/Training / On-the-job learning Opportunities.</td>
                                <th><select class="form-control show-tick" id="section_3_question_6" name="section_3_question_6">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Made expectations clear.</td>
                                <th><select class="form-control show-tick" id="section_3_question_7" name="section_3_question_7">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>

                            <tr>
                                <td>8</td>
                                <td>Gave adequate and timely feedback.</td>
                                <th><select class="form-control show-tick" id="section_3_question_8" name="section_3_question_8">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>

                        </body>

                    </table>
                    <table class="table table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th colspan="3">
                                    <h5>Feedback about Ksoft Technologies Processes (Please select one of the options from the drop down list provided for each question) </h5>
                                </th>

                            </tr>
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
                                <th><select class="form-control show-tick" id="section_4_question_1" name="section_4_question_1">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Meaningful work</td>
                                <th><select class="form-control show-tick" id="section_4_question_2" name="section_4_question_2">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Development/Training Opportunities</td>
                                <th><select class="form-control show-tick" id="section_4_question_3" name="section_4_question_3">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Work Environment.</td>
                                <th><select class="form-control show-tick" id="section_4_question_4" name="section_4_question_4">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Opportunities for advancement.</td>
                                <th><select class="form-control show-tick" id="section_4_question_5" name="section_4_question_5">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Recognition at Work.</td>
                                <th><select class="form-control show-tick" id="section_4_question_6" name="section_4_question_6">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Compensation & Benefits.</td>
                                <th><select class="form-control show-tick" id="section_4_question_7" name="section_4_question_7">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>

                            <tr>
                                <td>8</td>
                                <td>Quality of Supervision.</td>
                                <th><select class="form-control show-tick" id="section_4_question_8" name="section_4_question_8">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>Co-Workers.</td>
                                <th><select class="form-control show-tick" id="section_4_question_9" name="section_4_question_9">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>

                            <tr>
                                <td>10</td>
                                <td>Performance Management System.</td>
                                <th><select class="form-control show-tick" id="section_4_question_10" name="section_4_question_10">
                                        <?php foreach ($ratings as $row) { ?>
                                            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </th>
                            </tr>

                        </body>

                    </table>

                </div>
            </div>
            <div class="row clearfix text-center mb-5">
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

        $(document).ready(function() {
            //initialValidation();
            //customerContactValidation();
        });
    });

    //$('body #table_design input[type="text"]').keyup(resizeInput).each(resizeInput);
</script>