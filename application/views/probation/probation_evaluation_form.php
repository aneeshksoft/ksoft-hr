<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">

<div class="row">
    <div class="col-sm-12">
        <? //= pr($probation); 
        ?>
        <? //= pr($probation_ratings); 
        ?>

        <div class="card">
            <?php if ((!empty($probation) && $probation['first_assessment_status'] == 1) || !empty($probation['second_assessment_status'])) : ?>
                <div class="card-header bg-white">
                    <div class="row">
                        <div class=" col-sm-12 text-right">
                            <button type="button" class="btn btn-success btn-sm" onclick="print()"><i class="fa fa-print"></i> Print</button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="card-body" id="printArea">
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
                                        <td><b>Employee Name: </b><?= $employee['name'] ?></td>
                                        <td colspan="2"><b>Employee No: </b><?= $employee['code'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><b>Designation: </b><?= $employee['designation'] ?></td>
                                        <td><b>Band: </b><?= $employee['band'] ?></td>
                                        <td><b>Sub-Band: </b><?= $employee['sub_band'] ?></td>
                                    </tr>
                                    <tr>

                                        <td><b>Probation Start Date: </b><?= get_date($employee['created_at']) ?></td>
                                        <td colspan="2"><b>Probation End Date: </b><?= get_date(addDaysToDate($employee['created_at'], $employee['probation_duration'])) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="1%">#</th>
                                        <th class="text-nowrap"><span>List of Trainings during Probation <sup>*</sup></span></th>
                                        <th>Facilitator/Trainer <sup>*</sup></th>
                                        <th>No of Hours to be Attended <sup>*</sup></th>
                                        <th class="text-nowrap">Done? (Yes/No) <sup>*</sup></th>

                                    </tr>
                                </thead>
                                <tbody id="trainings_first_line_items">
                                    <?php if (!empty($probation_trainings_first)) : ?>
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
                                                    <?= ucfirst($training['done']) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                                <?php if (empty($probation)) : ?>
                                    <tfoot>
                                        <tr>
                                            <th colspan="5"></th>
                                            <th class=" text-center"><button id="add_training_first_line_item" type="button" class="btn btn-sm btn-success " title="Add One More Item"><i class="fa fa-plus"></i></button></th>
                                        </tr>
                                    </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th colspan="4">
                                            SECTION A: TO BE COMPLETED BY THE EVALUATOR
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4">
                                            <div><b>Rating Scale:</b></div>
                                            <div>5 = Excellent</div>
                                            <div>4 = Good</div>
                                            <div>3 = Average</div>
                                            <div>2 = Below Average</div>
                                            <div>1 = Unsatisfactory</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Sl. No</th>
                                        <th>Assessment Parameters</th>
                                        <th>Rating of 1st assessment (on a scale of 1-5)<sup>*</sup> </th>
                                        <th>Rating of 2nd assessment (on a scale of 1-5)</th>
                                    </tr>
                                    <?php if (!empty($rating_parameters)) : ?>
                                        <?php foreach ($rating_parameters as $key => $parameter) : ?>
                                            <tr>

                                                <td><?= $key + 1 ?></td>
                                                <td>
                                                    <b><?= $parameter['parameter'] ?></b><br />
                                                    <?= $parameter['explanation'] ?>
                                                </td>
                                                <td>
                                                    <?php if (empty($probation)) : ?>
                                                        <select class="form-control form-control-sm" name="first_assessment_rating[<?= $parameter['id'] ?>]" required>
                                                            <option value="">Select</option>
                                                            <?php if ($first_assessment_ratings) : ?>
                                                                <?php foreach ($first_assessment_ratings as $rating) : ?>
                                                                    <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                    <?php else : ?>

                                                        <?php foreach ($probation_ratings as $rating) : ?>
                                                            <?php if ($rating['parameter_id'] == $parameter['id']) : ?>
                                                                <?= $rating['first_assessment_rating'] ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>

                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (empty($probation) || $probation['first_assessment_status'] == 2 && empty($probation['second_assessment_status'])) : ?>
                                                        <select class="form-control form-control-sm" name="second_assessment_rating[<?= $parameter['id'] ?>]" <?= empty($probation) ? "disabled" : "" ?> required>
                                                            <option value="">Select</option>
                                                            <?php if ($second_assessment_ratings) : ?>
                                                                <?php foreach ($second_assessment_ratings as $rating) : ?>
                                                                    <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </select>
                                                    <?php else : ?>
                                                        <?php foreach ($probation_ratings as $rating) : ?>
                                                            <?php if ($rating['parameter_id'] == $parameter['id']) : ?>
                                                                <?= $rating['second_assessment_rating'] ?>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <tr>
                                        <th colspan="2">
                                            Rate Overall Performance on a scale of 1-5
                                        </th>
                                        <td>
                                            <?php if (empty($probation)) : ?>
                                                <select class="form-control form-control-sm" name="first_assessment_rating_overall" id="first_assessment_rating_overall" required>
                                                    <option value="">Select</option>
                                                    <?php if ($first_assessment_overall_ratings) : ?>
                                                        <?php foreach ($first_assessment_overall_ratings as $rating) : ?>
                                                            <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            <?php else : ?>
                                                <?= $probation['first_assessment_rating_overall'] ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (empty($probation) || $probation['first_assessment_status'] == 2 && empty($probation['second_assessment_status'])) : ?>

                                                <select class="form-control form-control-sm" name="second_assessment_rating_overall" id="second_assessment_rating_overall" required <?= empty($probation) ? "disabled" : "" ?>>
                                                    <option value="">Select</option>
                                                    <?php if ($second_assessment_overall_ratings) : ?>
                                                        <?php foreach ($second_assessment_overall_ratings as $rating) : ?>
                                                            <option value="<?= $rating; ?>"><?= $rating; ?></option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                            <?php else : ?>
                                                <?= $probation['second_assessment_rating_overall'] ?>
                                            <?php endif; ?>

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
                                        <td rowspan="2" class="text-center">
                                            <div class="d-flex justify-content-center text-nowrap">
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" name="first_assessment_status" id="first_assessment_status_1" value="1" <?= !empty($probation) ? ("disabled " . (($probation['first_assessment_status'] == 1) ? "checked" : "")) : "" ?>>
                                                    <label class="form-check-label" for="first_assessment_status_1">CONFIRMED</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" name="first_assessment_status" id="first_assessment_status_2" value="2" <?= !empty($probation) ? ("disabled " . (($probation['first_assessment_status'] == 2) ? "checked" : "")) : "" ?>>
                                                    <label class="form-check-label" for="first_assessment_status_2">PROBATION EXTENDED</label>
                                                </div>
                                            </div>
                                            <div>(max 3 months)</div>
                                        </td>
                                        <th class="text-center" colspan="2">
                                            PROBATION EXTENSION
                                        </th>
                                    </tr>
                                    <tr>
                                        <td class="text-center text-nowrap">

                                            <label for="extention_start_date">START DATE:</label>
                                            <?php if (empty($probation)) : ?>
                                                <input type="text" class=" form-control form-control-sm datepicker" name="extention_start_date" id="extention_start_date">
                                            <?php else : ?>
                                                <div><?= get_date($probation['extention_start_date']) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center text-nowrap">

                                            <label for="extention_end_date">END DATE:</label>
                                            <?php if (empty($probation)) : ?>
                                                <input type="text" class=" form-control form-control-sm datepicker" name="extention_end_date" id="extention_end_date">
                                            <?php else : ?>
                                                <div><?= get_date($probation['extention_end_date']) ?></div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <p>
                                                If <b>CONFIRMED</b> , please
                                                provide constructive feedback on the achievement levels of the
                                                employee so far/ specify if any areas of improvement required:
                                            </p>
                                            <?php if (empty($probation)) : ?>
                                                <textarea class=" form-control form-control-sm" name="first_assessment_status_remark" id="first_assessment_status_remark_1"></textarea>
                                            <?php elseif ($probation['first_assessment_status'] == 1) : ?>
                                                <q><?= $probation['first_assessment_status_remark'];  ?></q>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <p>
                                                If <b>PROBATION EXTENDED</b> ,
                                                the manager should identify specific areas for improvement/
                                                training required/ objectives/target that should be achieved
                                                during the probationary period:
                                            </p>
                                            <?php if (empty($probation)) : ?>
                                                <textarea class=" form-control form-control-sm" name="first_assessment_status_remark" id="first_assessment_status_remark_2"></textarea>
                                            <?php elseif ($probation['first_assessment_status'] == 2) : ?>
                                                <q><?= $probation['first_assessment_status_remark'];  ?></q>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th width="1%">#</th>
                                        <th>List of Trainings required during extended probation</th>
                                        <th>No of Hours to be Attended</th>
                                        <th class="text-nowrap">Done? (Yes/No)</th>
                                    </tr>
                                </thead>
                                <tbody id="training_extended_line_items">
                                    <?php if (!empty($probation_trainings_second)) : ?>
                                        <?php foreach ($probation_trainings_second as $key => $training) : ?>
                                            <tr>
                                                <td class="text-center"><?= $key + 1 ?></td>
                                                <td>
                                                    <?= $training['training_name'] ?>
                                                </td>
                                                <td>
                                                    <?= $training['hours_to_be_attended'] ?>
                                                </td>
                                                <td>
                                                    <?= ucfirst($training['done']) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot>

                                    <tr>

                                        <th colspan="4"></th>
                                        <?php if (!empty($probation)) : ?>
                                            <?php if ($probation['first_assessment_status'] == 2 && empty($probation['second_assessment_status'])) : ?>
                                                <th class=" text-center"><button id="add_training_extended_line_item" type="button" class="btn btn-sm btn-success " title="Add One More Item"><i class="fa fa-plus"></i></button></th>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </tr>

                                </tfoot>


                            </table>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th class="w-50">Manager’s Signature:</th>
                                        <th>Employee’s Signature:</th>
                                    </tr>
                                    <tr>
                                        <th>Date:</th>
                                        <th>Date:</th>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td colspan="2" width="50%">
                                            Status of employment post Probation Extension/ 2nd Assessment
                                            (Please select one of the mentioned options)
                                        </td>
                                        <td colspan="2" class="text-center">
                                            <div class="d-flex justify-content-center text-nowrap">
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" name="second_assessment_status" id="second_assessment_status_1" value="1" <?= !empty($probation['first_assessment_status']) && $probation['second_assessment_status'] == 1 ? "checked" : "" ?>>
                                                    <label class="form-check-label" for="second_assessment_status_1">CONFIRMED</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="radio" class="form-check-input" name="second_assessment_status" id="second_assessment_status_2" value="2" <?= !empty($probation['first_assessment_status']) && $probation['second_assessment_status'] == 2 ? "checked" : "" ?>>
                                                    <label class="form-check-label" for="second_assessment_status_2">NOT CONFIRMED</label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <p>
                                                If <b>CONFIRMED</b>, please
                                                provide constructive feedback on the achievement levels of the
                                                employee so far/ specify if any areas of improvement required:
                                            </p>
                                            <?php if (!empty($probation) && $probation['first_assessment_status'] == 2 && empty($probation['second_assessment_status'])) : ?>
                                                <textarea class=" form-control form-control-sm" name="second_assessment_status_remark" id="second_assessment_status_remark_1"></textarea>
                                            <?php elseif (!empty($probation) && $probation['second_assessment_status'] == 1) : ?>
                                                <q><?= $probation['second_assessment_status_remark'];  ?></q>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <p>
                                                If <b>NOT CONFIRMED</b>, please
                                                provide reasons:
                                            </p>
                                            <?php if (!empty($probation) && $probation['first_assessment_status'] == 2 && empty($probation['second_assessment_status'])) : ?>
                                                <textarea class=" form-control form-control-sm" name="second_assessment_status_remark" id="second_assessment_status_remark_2"></textarea>
                                            <?php elseif (!empty($probation) && $probation['second_assessment_status'] == 2) : ?>
                                                <q><?= $probation['second_assessment_status_remark'];  ?></q>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php if (empty($probation) || ($probation['first_assessment_status'] == 2 && empty($probation['second_assessment_status']))) : ?>
                            <div class="col-sm-12">
                                <div class="mt-4 text-center">
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?= base_url() ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>

<!-- stage 1 form script -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });

        //load one line item on page load if probation is empty
        <?php if (empty($probation)) : ?>
            if ($('#trainings_first_line_items tr').length == 0) {
                setTimeout(() => {
                    $('#add_training_first_line_item').trigger("click");
                    $('#remove_line_item_0').remove();

                }, 500);
            }
        <?php elseif ($probation['first_assessment_status'] == 2 && empty($probation['second_assessment_status'])) : ?>
            if ($('#training_extended_line_items tr').length == 0) {
                setTimeout(() => {
                    $('#add_training_extended_line_item').trigger("click");
                    $('#remove_line_item_extended_0').remove();
                    // $("#training_extended_line_items input,#training_extended_line_items select").prop('disabled', true);

                }, 500);
            }
        <?php endif; ?>
        first_assessment_status_change();
        second_assessment_status_change();

        $("#probation_evaluation_form").validate({
            rules: {
                first_assessment_status: {
                    required: true,
                },
                extention_start_date: {
                    required: true,
                },
                extention_end_date: {
                    required: true,
                },
                first_assessment_status_remark: {
                    required: true,
                },
                second_assessment_status: {
                    required: true
                },
                second_assessment_status_remark: {
                    required: true
                },
            },
            messages: {},
            errorPlacement: function(error, element) {
                $(':disabled').removeClass('error')
                // if ($(element).is(':disabled')) {
                //     console.log('disabled')
                //     element.removeClass('error');
                // }
                if (element.attr("type") == "radio") {
                    element.closest('td').append(error);
                } else {
                    error.insertAfter(element);
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
                        url: '<?= base_url('probation/probation_evaluation_post') ?>',
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
                                $(form).find(':submit').prop('disabled', false).text('Submit');
                            }

                        },
                        error: function(error) {
                            toaster('error', error);
                            $(form).find(':submit').prop('disabled', false).text('Submit');
                        }
                    });

                }, 500);
                return false;

            }

        });

        var training_line_id = $('#trainings_first_line_items tr').length;
        $('#add_training_first_line_item').click(function() {
            var sno = $('#trainings_first_line_items tr').length;
            var html = "";
            html += `<tr>
                    <td class="text-center">` + (sno + 1) + `</td>
                    <td><input type="text" class="form-control form-control-sm" name="training_name[` + training_line_id + `]" id="training_name_` + training_line_id + `">
                    </td>
                    <td><input type="text" class="form-control form-control-sm" name="trainer[` + training_line_id + `]" id="trainer_` + training_line_id + `"></td>
                    <td><input type="number" class="form-control form-control-sm" name="hours_to_be_attended[` + training_line_id + `]" id="hours_to_be_attended_` + training_line_id + `"></td>
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

            // console.log(training_line_id)

            var fields_valid = true;
            if (training_line_id >= 1) {
                var fields = ['training_name_', 'trainer_', 'hours_to_be_attended_', 'done_'];
                var previous_line_id = Number(training_line_id - 1);
                var fields_valid = true;
                fields.forEach(function(field) {
                    // console.log(field + previous_line_id)
                    var element = $("#" + field + previous_line_id);
                    // console.log(element)
                    if (element.length && !$(element).valid()) {
                        fields_valid = false;
                    }

                });

            }

            if (fields_valid) {

                $('#trainings_first_line_items').append(html);
                // console.log("training_name_" + training_line_id)

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

        $('input[type=radio][name=first_assessment_status]').change(function() {
            // console.log("first_assessment_status change")
            first_assessment_status_change(this.value);
            $("#probation_evaluation_form").valid();
        });

        $('input[type=radio][name=second_assessment_status]').change(function() {
            // console.log("second_assessment_status change:" + this.value)
            second_assessment_status_change(this.value);
            $("#probation_evaluation_form").valid();
        });


        var training_line_id_extended = $('#training_extended_line_items tr').length;
        $('#add_training_extended_line_item').click(function() {
            var sno = $('#training_extended_line_items tr').length;
            var html = "";
            html += `<tr>
                    <td class="text-center">` + (sno + 1) + `</td>
                    <td><input type="text" class="form-control form-control-sm" name="training_name_extended[` + training_line_id_extended + `]" id="training_name_extended_` + training_line_id_extended + `">
                    </td>
                    <td><input type="number" class="form-control form-control-sm" name="hours_to_be_attended_extended[` + training_line_id_extended + `]" id="hours_to_be_attended_extended_` + training_line_id_extended + `" ></td>
                    <td><select class="form-control form-control-sm" name="done_extended[` + training_line_id_extended + `]" id="done_extended_` + training_line_id_extended + `">
                        <option value=""> Select </option>
                        <?php if ($done) : foreach ($done as $val) : ?>
                            <option value="<?= $val ?>"><?= ucfirst($val) ?></option>
                        <?php endforeach;
                        endif; ?>
                        </select>
                    </td>
                    <td class=" text-center"><button type="button" class="btn btn-sm btn-danger" onClick="remove_line_item()"  id="remove_line_item_extended_` + training_line_id_extended + `"   title="Remove"><i class=" fa fa-minus-circle"></i></button></td>
                </tr>`;

            // console.log(training_line_id_extended)

            var fields_valid = true;
            if (training_line_id_extended >= 1) {
                var fields = ['training_name_extended_', 'hours_to_be_attended_extended_', 'done_extended_'];
                var previous_line_id = Number(training_line_id_extended - 1);
                var fields_valid = true;
                fields.forEach(function(field) {
                    // console.log(field + previous_line_id)
                    var element = $("#" + field + previous_line_id);
                    // console.log(element)
                    if (element.length && !$(element).valid()) {
                        fields_valid = false;
                    }

                });

            }

            if (fields_valid) {

                $('#training_extended_line_items').append(html);
                // console.log("training_name_extended_" + training_line_id_extended)

                $("#probation_evaluation_form #training_name_extended_" + training_line_id_extended).rules("add", {
                    required: true,
                });

                $("#probation_evaluation_form #hours_to_be_attended_extended_" + training_line_id_extended).rules("add", {
                    required: true,
                });

                $("#probation_evaluation_form #done_extended_" + training_line_id_extended).rules("add", {
                    required: true,
                });
                training_line_id_extended++;
            }




        });
    });

    function remove_line_item() {
        event.target.closest("tr").remove();
    }

    function first_assessment_status_change(value) {

        <?php if (empty($probation)) : ?>
            // console.log(value)
            switch (value) {
                case "1":
                    $("#extention_start_date,#extention_end_date,#add_training_extended_line_item").prop('disabled', true).val('').removeClass('error');
                    $("#first_assessment_status_remark_1").prop('disabled', false).val('');
                    $("#first_assessment_status_remark_2").prop('disabled', true).val('').removeClass('error');
                    $("#training_extended_line_items input,#training_extended_line_items select").prop('disabled', true).val('').removeClass('error');

                    break;
                case "2":
                    $('#extention_start_date').val('<?= get_date(addDaysToDate($employee['confirmation_duedate'], 1)) ?>').prop('disabled', false).prop('readonly', true);
                    $('#extention_end_date').datepicker('setStartDate', $('#extention_start_date').val());
                    $('#extention_end_date').datepicker('setEndDate', moment('<?= get_date(addDaysToDate($employee['confirmation_duedate'], 1)) ?>', "DD-MM-YYYY").add(3, 'M').format('DD-MM-YYYY'));
                    $("#extention_end_date,#add_training_extended_line_item").prop('disabled', false).val('');
                    $("#first_assessment_status_remark_1").prop('disabled', true).val('').removeClass('error');
                    $("#first_assessment_status_remark_2").prop('disabled', false).val('');
                    $("#training_extended_line_items input,#training_extended_line_items select").prop('disabled', false).val('');

                    break;
                default:
                    $("#extention_start_date,#extention_end_date,#add_training_extended_line_item").prop('disabled', true).val('').removeClass('error');
                    $("#first_assessment_status_remark_1").prop('disabled', true).val('').removeClass('error');
                    $("#first_assessment_status_remark_2").prop('disabled', true).val('').removeClass('error');
                    $("#training_extended_line_items input,#training_extended_line_items select").prop('disabled', true).val('').removeClass('error');

            }
        <?php else : ?>
            $('input[type=radio][name=first_assessment_status]').prop('disabled', true);
            $("#first_assessment_status_remark_1").prop('disabled', true);
            $("#first_assessment_status_remark_2").prop('disabled', true);

        <?php endif; ?>
    }

    function second_assessment_status_change(value) {
        <?php if (!empty($probation) && $probation['first_assessment_status'] == 2 && empty($probation['second_assessment_status'])) : ?>
            switch (value) {
                case '1':
                    $("#second_assessment_status_remark_1").prop('disabled', false).val('');
                    $("#second_assessment_status_remark_2").prop('disabled', true).val('').removeClass('error');
                    break;
                case "2":
                    $("#second_assessment_status_remark_1").prop('disabled', true).val('').removeClass('error');
                    $("#second_assessment_status_remark_2").prop('disabled', false).val('');
                    break;
                default:
                    $("#second_assessment_status_remark_1").prop('disabled', true).val('').removeClass('error');
                    $("#second_assessment_status_remark_2").prop('disabled', true).val('').removeClass('error');
            }
        <?php else : ?>
            $('input[type=radio][name=second_assessment_status]').prop('disabled', true);
            $("#second_assessment_status_remark_1").prop('disabled', true).val('');
            $("#second_assessment_status_remark_2").prop('disabled', true).val('');

        <?php endif; ?>
    }

    function print() {
        var content = $('#printArea').html();
        var mywindow = window.open('', 'Probation Evaluation Form', 'height=800, width=1000');
        mywindow.document.write('<html><head><title>Probation Evaluation Form</title>');
        /*optional stylesheet*/ //
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'assets/vendor/bootstrap/css/bootstrap.min.css"">');
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'assets/common/css/main.css"">');
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'assets/common/css/color_skins.css"">');
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'sets/user/css/custom.css"">');
        mywindow.document.write('</head><body >');
        mywindow.document.write(content);
        mywindow.document.write('</body></html>');
        setTimeout(function() {
            mywindow.print();
        }, 2000);
        mywindow.onafterprint = function() {
            mywindow.close();
        }
        mywindow.print();
        // mywindow.close();

    }

    // $.validator.addMethod('greaterThan', function(value, element) {

    //     var dateFrom = $("#valid_from").val();
    //     var dateTo = $('#valid_to').val();

    //     return dateTo > dateFrom;

    // });
    // $.validator.addMethod('greaterThan', function(value, element) {

    //     var dateFrom = $("#valid_from").val();
    //     var dateTo = $('#valid_to').val();

    //     return dateTo > dateFrom;

    // });
</script>