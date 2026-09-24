<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">

<div class="row">
    <div class="col-sm-12">
        <?= pr($employee); ?>
        <div class="card">
            <div class="card-body">
                <form action="" name="probation_evaluation_form" id="probation_evaluation_form" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" colspan="3">
                                            PROBATION EVALUATION FORM
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Employee Name: <?= $employee['name'] ?></td>
                                        <td colspan="2">Employee No: <?= $employee['code'] ?></td>
                                    </tr>
                                    <tr>
                                        <td>Designation: <?= $employee['designation'] ?></td>
                                        <td>Band: <?= $employee['band'] ?></td>
                                        <td>Sub-Band: <?= $employee['sub_band'] ?></td>
                                    </tr>
                                    <tr>

                                        <td>Probation Start Date: <?= get_date($employee['created_at']) ?></td>
                                        <td colspan="2">Probation End Date: <?= get_date(addDaysToDate($employee['created_at'], $employee['probation_duration'])) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="1%">#</th>
                                        <th class="text-nowrap">List of Trainings during Probation <sup>*</sup></th>
                                        <th>Facilitator/Trainer <sup>*</sup></th>
                                        <th>No of Hours to be Attended <sup>*</sup></th>
                                        <th class="text-nowrap">Done? (Yes/No) <sup>*</sup></th>
                                        <?php if (empty($probation)) : ?>
                                            <th width="1%">Action</th>
                                        <?php endif; ?>

                                    </tr>
                                </thead>
                                <tbody id="training_line_items">
                                    <?php if (empty($probation) && !empty($probation_trainings_first)) : ?>
                                        <?php foreach ($probation_trainings_first as $key => $training) : ?>
                                            <tr>
                                                <td class="text-center"><?= $key + 1 ?></td>
                                                <td>
                                                    <?= $training['training_name'] ?>
                                                </td>
                                                <td>
                                                    <?= $training['trainer'] ?>
                                                </td>
                                                <td>
                                                    <?= $training['hours_to_be_attended'] ?>
                                                </td>
                                                <td>
                                                    <?= $training['done'] ?>
                                                </td>
                                                <td class=" text-center"><button type="button" class="btn btn-sm btn-danger" onClick="remove_line_item()" id="remove_line_item_` + training_line_id + `" title="Remove"><i class=" fa fa-minus-circle"></i></button></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                                <?php if (empty($probation)) : ?>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5"></th>
                                            <th class=" text-center"><button id="add_training_line_item" type="button" class="btn btn-sm btn-success " title="Add One More Item"><i class="fa fa-plus"></i></button></th>
                                        </tr>
                                    </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="4" class="font-weight-bold">
                                            SECTION A: TO BE COMPLETED BY THE EVALUATOR
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4">
                                            <div class="d-flex flex-column">
                                                <div class="font-weight-bold">Rating Scale:</div>
                                                <div>5 = Excellent</div>
                                                <div>4 = Good</div>
                                                <div>3 = Average</div>
                                                <div>2 = Below Average</div>
                                                <div>1 = Unsatisfactory</div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sl. No</td>
                                        <td>Assessment Parameters</td>
                                        <td>Rating of 1st assessment (on a scale of 1-5)</td>
                                        <td>*Rating of 2nd assessment (on a scale of 1-5)</td>
                                    </tr>
                                    <tr>
                                    <?php if (!empty($rating_parameters)) : ?>
                                        <?php foreach ($rating_parameters as $key => $parameter) : ?>
                                        <td><?= $key+1?></td>
                                        <td>
                                            <span class="font-weight-bold"><?= $parameter['parameter']?></span><br />
                                            <?= $parameter['explantion']?>
                                        </td>
                                        <td>
                                        <?php if (empty($probation)) : ?>
                                            <select class="form-control form-control-sm" name="first_assessment_rating" id="first_assessment_rating">
                                                <option value="">Select</option>
                                                <?php if ($first_assessment_ratings) : ?>
                                                    <?php foreach ($first_assessment_ratings as $rating) : ?>
                                                        <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <?php else: ?>
                                                <?php foreach ($probation_ratings as $rating) : ?>
                                                    <?php if ($rating['probation_id']==$parameter['id']) : ?>
                                                        <?= $rating['first_assessment_rating']?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                              
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" name="second_assessment_rating" id="second_assessment_rating" <?= !empty($probation)?"disabled":"" ?>>
                                                <option value="">Select</option>
                                                <?php if ($second_assessment_ratings) : ?>
                                                    <?php foreach ($second_assessment_ratings as $rating) : ?>
                                                        <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>
                                            <span class="font-weight-bold">Delivery Schedule Compliance</span><br />
                                            Meet the delivery/assigned work as per estimated schedule,
                                            effort and cost.
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" name="first_assessment_rating" id="first_assessment_rating">
                                                <option value="">Select</option>
                                                <?php if ($first_assessment_ratings) : ?>
                                                    <?php foreach ($first_assessment_ratings as $rating) : ?>
                                                        <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" name="second_assessment_rating" id="second_assessment_rating">
                                                <option value="">Select</option>
                                                <?php if ($second_assessment_ratings) : ?>
                                                    <?php foreach ($second_assessment_ratings as $rating) : ?>
                                                        <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>
                                            <span class="font-weight-bold">Technical/Domain Capability</span><br />
                                            Assessment of skill sets based on the role performed
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" name="first_assessment_rating" id="first_assessment_rating">
                                                <option value="">Select</option>
                                                <?php if ($first_assessment_ratings) : ?>
                                                    <?php foreach ($first_assessment_ratings as $rating) : ?>
                                                        <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" name="second_assessment_rating" id="second_assessment_rating">
                                                <option value="">Select</option>
                                                <?php if ($second_assessment_ratings) : ?>
                                                    <?php foreach ($second_assessment_ratings as $rating) : ?>
                                                        <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>
                                            <span class="font-weight-bold">Result Orientation Understands </span><br />
                                            Priorities and stays focused on end results.
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" name="first_assessment_rating" id="first_assessment_rating">
                                                <option value="">Select</option>
                                                <?php if ($first_assessment_ratings) : ?>
                                                    <?php foreach ($first_assessment_ratings as $rating) : ?>
                                                        <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" name="second_assessment_rating" id="second_assessment_rating">
                                                <option value="">Select</option>
                                                <?php if ($second_assessment_ratings) : ?>
                                                    <?php foreach ($second_assessment_ratings as $rating) : ?>
                                                        <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>
                                            <span class="font-weight-bold">Work relationships/ Interpersonal skills</span><br />
                                            How well the employee gets along with others<br />
                                            (Co-workers, reportees, supervisors, and customers.)
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" name="first_assessment_rating" id="first_assessment_rating">
                                                <option value="">Select</option>
                                                <?php if ($first_assessment_ratings) : ?>
                                                    <?php foreach ($first_assessment_ratings as $rating) : ?>
                                                        <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" name="second_assessment_rating" id="second_assessment_rating">
                                                <option value="">Select</option>
                                                <?php if ($second_assessment_ratings) : ?>
                                                    <?php foreach ($second_assessment_ratings as $rating) : ?>
                                                        <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>
                                            <span class="font-weight-bold">Attendance, punctuality and overall conduct</span><br />
                                            Consistent attendance, punctuality, displays behavior that is
                                            in line with the Ksoft code of conduct.
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" name="first_assessment_rating" id="first_assessment_rating">
                                                <option value="">Select</option>
                                                <?php if ($first_assessment_ratings) : ?>
                                                    <?php foreach ($first_assessment_ratings as $rating) : ?>
                                                        <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" name="second_assessment_rating" id="second_assessment_rating">
                                                <option value="">Select</option>
                                                <?php if ($second_assessment_ratings) : ?>
                                                    <?php foreach ($second_assessment_ratings as $rating) : ?>
                                                        <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="font-weight-bold">
                                            Rate Overall Performance on a scale of 1-5
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" name="first_assessment_rating" id="first_assessment_rating">
                                                <option value="">Select</option>
                                                <?php if ($first_assessment_overall_ratings) : ?>
                                                    <?php foreach ($first_assessment_overall_ratings as $rating) : ?>
                                                        <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm" name="second_assessment_rating" id="second_assessment_rating">
                                                <option value="">Select</option>
                                                <?php if ($second_assessment_overall_ratings) : ?>
                                                    <?php foreach ($second_assessment_overall_ratings as $rating) : ?>
                                                        <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                    </tr>

                                </tbody>
                                <caption class="text-center text-dark pt-1"><sup>*</sup>2nd Assessment to be done only in case the probation period is getting extended</caption>
                            </table>

                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="w-25" rowspan="2">
                                            Employment Status post 1st Assessment (Please select one of
                                            the mentioned options)
                                        </td>
                                        <td class="w-50" rowspan="2">
                                            <div class="d-flex justify-content-center">
                                                <div class="align-items-center d-flex flex-column">
                                                    <div class="align-items-center d-flex form-check form-group">
                                                        <input type="checkbox" class="form-check-input" id="id1" />
                                                    </div>
                                                    <div>
                                                        <label class="form-check-label" for="id1">CONFIRMED</label>
                                                    </div>
                                                </div>
                                                <div class="align-items-center d-flex flex-column">
                                                    <div class="align-items-center d-flex form-check form-group">
                                                        <input type="checkbox" class="form-check-input" id="id2" />
                                                    </div>
                                                    <div>
                                                        <label class="form-check-label" for="id2">
                                                            / PROBATION EXTENDED</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">(max 3 months)</div>
                                        </td>
                                        <td class="w-25 text-center" colspan="2">
                                            PROBATION EXTENSION
                                    <tr>
                                        <td class="text-center">START DATE:</td>
                                        <td class="text-center">END DATE:</td>
                                    </tr>
                                    </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            If <span class="font-weight-bold">CONFIRMED</span> , please
                                            provide constructive feedback on the achievement levels of the
                                            employee so far/ specify if any areas of improvement required:
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            If <span class="font-weight-bold">PROBATION EXTENDED</span> ,
                                            the manager should identify specific areas for improvement/
                                            training required/ objectives/target that should be achieved
                                            during the probationary period:
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold" colspan="2">
                                            List of Trainings required during extended probation
                                        </td>
                                        <td class="font-weight-bold">No of Hours to be Attended</td>
                                        <td class="font-weight-bold">Done? (Y/N)</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Manager’s Name:</td>
                                        <td></td>
                                        <td class="font-weight-bold">Employee’s Name:</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Manager’s Signature:</td>
                                        <td></td>
                                        <td class="font-weight-bold">Employee’s Signature:</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Date:</td>
                                        <td></td>
                                        <td class="font-weight-bold">Date:</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="w-75" colspan="2">
                                            Status of employment post Probation Extension/ 2nd Assessment
                                            (Please select one of the mentioned options)
                                        </td>
                                        <td class="w-25" colspan="2">
                                            <div class="d-flex justify-content-center">
                                                <div class="align-items-center d-flex flex-column">
                                                    <div class="align-items-center d-flex form-check form-group">
                                                        <input type="checkbox" class="form-check-input" id="id1" />
                                                    </div>
                                                    <div>
                                                        <label class="form-check-label" for="id1">CONFIRMED</label>
                                                    </div>
                                                </div>
                                                <div class="align-items-center d-flex flex-column">
                                                    <div class="align-items-center d-flex form-check form-group">
                                                        <input type="checkbox" class="form-check-input" id="id2" />
                                                    </div>
                                                    <div>
                                                        <label class="form-check-label" for="id2">
                                                            / NOT CONFIRMED</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            If <span class="font-weight-bold">CONFIRMED</span>, please
                                            provide constructive feedback on the achievement levels of the
                                            employee so far/ specify if any areas of improvement required:
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            If <span class="font-weight-bold">NOT CONFIRMED</span>, please
                                            provide reasons:
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold w-25">Manager’s Name:</td>
                                        <td class="w-25"></td>
                                        <td class="font-weight-bold w-25">Employee’s Name:</td>
                                        <td class="w-25"></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Manager’s Signature:</td>
                                        <td></td>
                                        <td class="font-weight-bold">Employee’s Signature:</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Date:</td>
                                        <td></td>
                                        <td class="font-weight-bold">Date:</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
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
        $('.datepicker').datepicker({
            format: 'dd-M-yyyy',
            todayHighlight: true,
            autoclose: true
        });

        //load one line item on page load if probation is empty
        <?php if (empty($probation)) : ?>
            if ($('#training_line_items tr').length == 0) {
                setTimeout(() => {
                    $('#add_training_line_item').trigger("click");
                    $('#remove_line_item_0').remove();
                }, 500);
            }
        <?php endif; ?>

        $("#probation_evaluation_form").validate({
            // ignore: ':hidden:not(#skill_id)',
            rules: {
                // stage 1 form
                full_name: {
                    required: true,
                    alphaspaces: true
                },
                personal_email: {
                    required: true,
                    email: true
                },
                country_code: {
                    required: true,
                    // minlength: 3,
                    // maxlength: 8,
                    //countrycode: true
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
                how_you_hear_id: {
                    required: true
                },
                how_you_hear_other: {
                    required: function(element) {
                        return ($('#how_you_hear_id').val() == 6);
                    },
                },
                currently_employed: {
                    required: true
                },
                available_joining_id: {
                    required: true
                },
                aadhar_number: {
                    required: true,
                    aadharverify: true
                },
                'aadhar_card[]': {
                    required: true,
                    extension: "png|jpg|jpeg|pdf",
                    maxupload: 3,
                    maxfilesize: 2
                },
                'resume[]': {
                    required: true,
                    extension: "pdf|doc|docx",
                    maxupload: 3,
                    maxfilesize: 2
                },
                expecting_mother: {
                    required: true
                },
                expected_delivery_date: {
                    required: function(element) {
                        return ($('#expecting_mother').val() == 'yes');
                    },
                },
                current_ctc: {
                    required: true
                },
            },
            messages: {

            },
            errorPlacement: function(error, element) {
                if (element.hasClass('select2') && element.next('.select2-container').length) {
                    error.insertAfter(element.next('.select2-container')).addClass('d-block');
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form, e) {
                e.preventDefault();
            }

        });

        var training_line_id = $('#training_line_items tr').length;
        $('#add_training_line_item').click(function() {
            var sno = $('#training_line_items tr').length;
            var html = "";
            html += `<tr>
                    <td class="text-center">` + (sno + 1) + `</td>
                    <td><input type="text" class="form-control form-control-sm" name="training_name[` + training_line_id + `]" id="training_name_` + training_line_id + `">
                    </td>
                    <td><input type="text" class="form-control form-control-sm" name="trainer[` + training_line_id + `]" id="trainer_` + training_line_id + `"></td>
                    <td><input type="text" class="form-control form-control-sm" name="hours_to_be_attended[` + training_line_id + `]" id="hours_to_be_attended_` + training_line_id + `" oninput="lineTotal(` + training_line_id + `)"></td>
                    <td><select class="form-control form-control-sm" name="done[` + training_line_id + `]" id="done_` + training_line_id + `">
                        <option value=""> Select </option>
                        <?php if ($done) : foreach ($done as $val) : ?>
                            <option value="<?= $val ?>"><?= ucfirst($val) ?></option>
                        <?php endforeach;
                        endif; ?>
                        </select>
                    </td>
                    <td class=" text-center"><button type="button" class="btn btn-sm btn-danger" onClick="remove_line_item()"  id="remove_line_item_` + training_line_id + `"   title="Remove"><i class=" fa fa-minus-circle"></i></button></td>
                </tr>`;

            console.log(training_line_id)

            var fields_valid = true;
            if (training_line_id >= 1) {
                var fields = ['training_name_', 'trainer_', 'hours_to_be_attended_', 'done_'];
                var previous_line_id = Number(training_line_id - 1);
                var fields_valid = true;
                fields.forEach(function(field) {
                    console.log(field + previous_line_id)
                    var element = $("#" + field + previous_line_id);
                    console.log(element)
                    if (element.length && !$(element).valid()) {
                        fields_valid = false;
                    }

                });

            }

            if (fields_valid) {

                $('#training_line_items').append(html);
                console.log("training_name_" + training_line_id)

                $("#probation_evaluation_form #training_name_" + training_line_id).rules("add", {
                    required: true,
                });

                $("#probation_evaluation_form #trainer_" + training_line_id).rules("add", {
                    required: true,
                });

                $("#probation_evaluation_form #hours_to_be_attended_" + training_line_id).rules("add", {
                    required: true,
                });

                $("#probation_evaluation_form #done_" + training_line_id).rules("add", {
                    required: true,
                });
                training_line_id++;
            }

        });

        var training_line_id = $('#training_extended_line_items tr').length;
        $('#add_training_extended_line_item').click(function() {
            var sno = $('#training_extended_line_items tr').length;
            var html = "";
            html += `<tr>
                    <td class="text-center">` + (sno + 1) + `</td>
                    <td><input type="text" class="form-control form-control-sm" name="training_name[` + training_line_id + `]" id="training_name_` + training_line_id + `">
                    </td>
                    <td><input type="text" class="form-control form-control-sm" name="trainer[` + training_line_id + `]" id="trainer_` + training_line_id + `"></td>
                    <td><input type="text" class="form-control form-control-sm" name="hours_to_be_attended[` + training_line_id + `]" id="hours_to_be_attended_` + training_line_id + `" oninput="lineTotal(` + training_line_id + `)"></td>
                    <td><select class="form-control form-control-sm" name="done[` + training_line_id + `]" id="done_` + training_line_id + `">
                        <option value=""> Select </option>
                        <?php if ($done) : foreach ($done as $val) : ?>
                            <option value="<?= $val ?>"><?= ucfirst($val) ?></option>
                        <?php endforeach;
                        endif; ?>
                        </select>
                    </td>
                    <td class=" text-center"><button type="button" class="btn btn-sm btn-danger" onClick="remove_line_item()"  id="remove_line_item_` + training_line_id + `"   title="Remove"><i class=" fa fa-minus-circle"></i></button></td>
                </tr>`;

            console.log(training_line_id)

            var fields_valid = true;
            if (training_line_id >= 1) {
                var fields = ['training_name_', 'trainer_', 'hours_to_be_attended_', 'done_'];
                var previous_line_id = Number(training_line_id - 1);
                var fields_valid = true;
                fields.forEach(function(field) {
                    console.log(field + previous_line_id)
                    var element = $("#" + field + previous_line_id);
                    console.log(element)
                    if (element.length && !$(element).valid()) {
                        fields_valid = false;
                    }

                });

            }

            if (fields_valid) {

                $('#training_line_items').append(html);
                console.log("training_name_" + training_line_id)

                $("#probation_evaluation_form #training_name_" + training_line_id).rules("add", {
                    required: true,
                });

                $("#probation_evaluation_form #trainer_" + training_line_id).rules("add", {
                    required: true,
                });

                $("#probation_evaluation_form #hours_to_be_attended_" + training_line_id).rules("add", {
                    required: true,
                });

                $("#probation_evaluation_form #done_" + training_line_id).rules("add", {
                    required: true,
                });
                training_line_id++;
            }

        });
    });

    function remove_line_item() {
        // console.log(event.target.closest("tr"))
        event.target.closest("tr").remove();
    }
</script>