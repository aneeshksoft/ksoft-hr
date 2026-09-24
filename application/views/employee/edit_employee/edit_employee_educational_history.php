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
                <form action="" name="employee_edu_history" id="employee_edu_history" method="POST" enctype="multipart/form-data">
                    <? //= pr($educational_history);
                    ?>

                    <div class="row">

                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover border">
                                    <thead>
                                        <tr>
                                            <th class="text-center" rowspan="2" width="1%">#</th>
                                            <th rowspan="2">Qualification<sup>*</sup></th>
                                            <th rowspan="2">Specialization<sup>*</sup></th>
                                            <th rowspan="2">Institute<sup>*</sup></th>
                                            <th class="text-center" style="border-bottom:1px solid #dee2e6 !important;" colspan="3">Location of the Institute</th>
                                            <th rowspan="2">% Marks / Grade<sup>*</sup></th>
                                            <th rowspan="2" width="1%">Year of Completion<sup>*</sup></th>
                                            <th rowspan="2">Marks Card<sup>*</sup></th>
                                            <th rowspan="2" width="1%"></th>
                                        </tr>
                                        <tr>
                                            <th>Country<sup>*</sup></th>
                                            <th>State<sup>*</sup></th>
                                            <th>Nearest City<sup>*</sup></th>
                                        </tr>
                                    </thead>
                                    <tbody id="edu_line_items">
                                        <?php if (!empty($educational_history)) : ?>
                                            <?php foreach ($educational_history as $key => $row) : ?>
                                                <tr>
                                                    <td><?= $key + 1 ?></td>
                                                    <td><?= $row['course'] ?></td>
                                                    <td><?= $row['specialization'] ?? '-' ?></td>
                                                    <td><?= $row['institute_name'] ?></td>
                                                    <td><?= $row['country'] ?></td>
                                                    <td><?= $row['state'] ?></td>
                                                    <td><?= $row['nearest_city'] ?></td>
                                                    <td><?= $row['percentage_mark'] ?></td>
                                                    <td><?= $row['completion_year'] ?></td>
                                                    <td>
                                                        <?php if ($row['mark_card']) :
                                                            $user_docs = base_url('assets/uploads/user_docs/candidates_docs/' . $row['candidate_id'] . '/');
                                                            $files = array_map(fn ($file) => $user_docs . $file, explode(',', $row['mark_card']))
                                                        ?>
                                                            <?php foreach ($files as $key => $file) : ?>
                                                                <a href=<?= $file ?> class="mr-1" target="_blank"><span class="badge badge-default">View</span></a>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>

                                                    </td>
                                                    <td class=" text-center">
                                                        <?php {/*?>
                                                        <button type="button" class="btn btn-sm btn-danger" onClick="delete_line_item(<?= $row['education_id'] ?>)" title="Delete"><i class=" fa fa-trash-o"></i></button>
                                                        <?*/
                                                        } ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="10"></th>
                                            <th class=" text-center"><button id="add_edu_line_item" type="button" class="btn btn-sm btn-success " title="Add One More Item"><i class="fa fa-plus"></i></button></th>
                                        </tr>
                                    </tfoot>
                                </table>
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

        $('.select2').select2({
            width: '100%'
        }).on('change', function() {
            $(this).valid();
        });

        $("#employee_edu_history").validate({
            ignore: ':disabled',
            rules: {



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
                $(form).find(':submit').prop('disabled', true).text('Processing...')
                var form_data = new FormData(form);
                form_data.append('employee_id', '<?= $employee['employee_id'] ?>');
                // console.log(form_data)
                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url('employee/edit_employee_educational_history_post') ?>',
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

        var edu_line_id = $('#edu_line_items tr').length;
        $('#add_edu_line_item').click(function() {
            var sno = $('#edu_line_items tr').length;
            var html = "";
            html += `<tr>               
                    <td class="text-center">` + (sno + 1) + `</td>
                    <td>
                        <select class="form-control form-control-sm" name="course_id[` + edu_line_id + `]" onChange="onchange_course(this.value,` + edu_line_id + `)" id="course_id_` + edu_line_id + `">
                            <option value="">Select</option>
                            <?php if ($courses) : ?>
                                <?php foreach ($courses as $value) : ?>
                                    <option value="<?= $value['course_id'] ?>"><?= $value['name']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                    <td><input class="form-control form-control-sm" type="text" name="specialization[` + edu_line_id + `]" id="specialization_` + edu_line_id + `" disabled></td>
                    <td><input class="form-control form-control-sm" type="text" name="institute_name[` + edu_line_id + `]" id="institute_name_` + edu_line_id + `"></td>
                    <td><select class="form-control form-control-sm select2" name="country_id[` + edu_line_id + `]" onchange="change_state(this.value, 'edu_state_id_` + edu_line_id + `')" id="edu_country_id_` + edu_line_id + `">
                            <option value="" >Select</option>
                            <?php if ($countries) : ?>
                                <?php foreach ($countries as $value) : ?>
                                    <option value="<?= $value['country_id']; ?>"><?= $value['name']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                    </select></td>
                    <td><select class="form-control form-control-sm" name="state_id[` + edu_line_id + `]" id="edu_state_id_` + edu_line_id + `"><option value="">Select</option></select></td>
                    <td><input class="form-control form-control-sm" type="text" name="nearest_city[` + edu_line_id + `]" id="nearest_city_` + edu_line_id + `"></td>
                    <td><input class="form-control form-control-sm" type="text" name="percentage_mark[` + edu_line_id + `]" id="percentage_mark_` + edu_line_id + `"></td>
                    <td><input class="form-control form-control-sm" type="number" name="completion_year[` + edu_line_id + `]" id="completion_year_` + edu_line_id + `"></td>                    
                    <td><input class="form-control form-control-sm" type="file" name="mark_card_` + edu_line_id + `[` + edu_line_id + `]" id="mark_card_` + edu_line_id + `" multiple></td>                    
                    <td class=" text-center"><button type="button" class="btn btn-sm btn-danger" onClick="remove_line_item()"  id="remove_line_item_` + edu_line_id + `"   title="Remove"><i class=" fa fa-minus-circle"></i></button></td>
                    </tr>`;

            // console.log(edu_line_id)

            var fields_valid = true;
            if (edu_line_id >= 1) {
                var fields = ['course_id_', 'specialization_', 'institute_name_', 'edu_country_id_', 'edu_state_id_', 'nearest_city_', 'percentage_mark_', 'completion_year_'];
                var previous_line_id = Number(edu_line_id - 1);
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

                $('#edu_line_items').append(html);
                // console.log("training_name_" + edu_line_id)

                $("#employee_edu_history #course_id_" + edu_line_id).rules("add", {
                    required: true,
                });

                $("#employee_edu_history #specialization_" + edu_line_id).rules("add", {
                    required: true,
                });

                $("#employee_edu_history #institute_name_" + edu_line_id).rules("add", {
                    required: true,
                });

                $("#employee_edu_history #edu_country_id_" + edu_line_id).rules("add", {
                    required: true,
                });
                $("#employee_edu_history #edu_state_id_" + edu_line_id).rules("add", {
                    required: true,
                });
                $("#employee_edu_history #nearest_city_" + edu_line_id).rules("add", {
                    required: true,
                });
                $("#employee_edu_history #percentage_mark_" + edu_line_id).rules("add", {
                    required: true,
                });
                $("#employee_edu_history #completion_year_" + edu_line_id).rules("add", {
                    required: true,
                    number: true,
                    maxlength: 4,
                    minlength: 4
                });
                $("#employee_edu_history #mark_card_" + edu_line_id).rules("add", {
                    required: true,
                    extension: "png|jpg|jpeg|pdf",
                    maxupload: 3,
                    maxfilesize: 2
                });


                edu_line_id++;
            }




        });

    });

    function onchange_course(value, line_id) {
        // console.log(value, line_id)
        // console.log($('#specialization_' + line_id))
        if (value > 2) {
            $('#specialization_' + line_id).prop('disabled', false);
        } else {
            $('#specialization_' + line_id).prop('disabled', true).val("");
        }
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

    function remove_line_item() {
        event.target.closest("tr").remove();
    }
</script>