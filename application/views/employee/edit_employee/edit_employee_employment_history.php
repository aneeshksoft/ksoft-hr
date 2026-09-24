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
                <form action="" name="employee_emp_history" id="employee_emp_history" method="POST" enctype="multipart/form-data">
                    <? //= pr($employment_history);
                    ?>

                    <div class="row">

                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover border">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center" rowspan="2" width="1%">#</th>
                                            <th rowspan="2">Company Name<sup>*</sup></th>
                                            <th class="text-center" style="border-bottom:1px solid #dee2e6 !important;" colspan="3">Work Location</th>
                                            <th rowspan="2" class="text-nowrap">Start Date<sup>*</sup></th>
                                            <th rowspan="2" class="text-nowrap">End Date</th>
                                            <th rowspan="2">Last Designation Held<sup>*</sup></th>
                                            <th rowspan="2">Responsibilities Handled<sup>*</sup></th>
                                            <th rowspan="2">Annual CTC (LPA)<sup>*</sup></th>
                                            <th rowspan="2">Reason For Job Change<sup>*</sup></th>
                                            <th rowspan="2">Is this experience relevant?<sup>*</sup></th>
                                            <th rowspan="2" width="1%"></th>
                                        </tr>
                                        <tr>
                                            <th>Country<sup>*</sup></th>
                                            <th>State<sup>*</sup></th>
                                            <th>Nearest City<sup>*</sup></th>
                                        </tr>
                                    </thead>
                                    <tbody id="emp_line_items">
                                        <?php if (!empty($employment_history)) : ?>
                                            <?php foreach ($employment_history as $key => $row) : ?>
                                                <tr>
                                                    <td><?= $key + 1 ?></td>
                                                    <td><?= $row['company_name'] ?></td>
                                                    <td><?= $row['country'] ?></td>
                                                    <td><?= $row['state'] ?></td>
                                                    <td><?= $row['nearest_city'] ?></td>
                                                    <td class="text-nowrap"><?= get_date($row['start_date']) ?></td>
                                                    <td class="text-nowrap"><?= get_date($row['end_date']) ?></td>
                                                    <td><?= $row['last_designation'] ?></td>
                                                    <td><?= $row['responsibilities'] ?></td>
                                                    <td><?= $row['annual_ctc'] ?></td>
                                                    <td><?= $row['reason_jobchance'] ?></td>
                                                    <td><?= $row['is_this_relevant_exp'] == 1 ? 'Yes' : ($row['is_this_relevant_exp'] == 2 ? "No" : "") ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="12"></th>
                                            <th class=" text-center"><button id="add_emp_line_item" type="button" class="btn btn-sm btn-success " title="Add One More Item"><i class="fa fa-plus"></i></button></th>
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

        $("#employee_emp_history").validate({
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
                        url: '<?= base_url('employee/edit_employee_employment_history_post') ?>',
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

        var emp_line_id = $('#emp_line_items tr').length;
        $('#add_emp_line_item').click(function() {
            var sno = $('#emp_line_items tr').length;
            var html = "";
            html += `<tr>               
                    <td class="text-center">` + (sno + 1) + `</td>
                    <td><input class="form-control form-control-sm" type="text" name="company_name[` + emp_line_id + `]" id="company_name_` + emp_line_id + `"></td>
                    <td>
                        <select class="form-control form-control-sm select2" name="country_id[` + emp_line_id + `]" onchange="change_state(this.value, 'state_id_` + emp_line_id + `')" id="country_id_` + emp_line_id + `">
                            <option value="" >Select</option>
                            <?php if ($countries) : ?>
                                <?php foreach ($countries as $value) : ?>
                                    <option value="<?= $value['country_id']; ?>"><?= $value['name']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                    <td><select class="form-control form-control-sm" name="state_id[` + emp_line_id + `]" id="state_id_` + emp_line_id + `"><option value="">Select</option></select></td>
                    <td><input class="form-control form-control-sm" type="text" name="nearest_city[` + emp_line_id + `]" id="nearest_city_` + emp_line_id + `"></td>
                    <td><input class="form-control form-control-sm datepicker" type="text" name="start_date[` + emp_line_id + `]" id="start_date_` + emp_line_id + `" ></td>
                    <td><input class="form-control form-control-sm datepicker" type="text" name="end_date[` + emp_line_id + `]" id="end_date_` + emp_line_id + `"></td>
                    <td><input class="form-control form-control-sm" type="text" name="last_designation[` + emp_line_id + `]" id="last_designation_` + emp_line_id + `" ></td>
                <td><input class="form-control form-control-sm" type="text" name="responsibilities[` + emp_line_id + `]" id="responsibilities_` + emp_line_id + `" ></td>
                <td><input class="form-control form-control-sm amount" type="number" name="annual_ctc[` + emp_line_id + `]" id="annual_ctc_` + emp_line_id + `" ></td>
                <td><input class="form-control form-control-sm" type="text" name="reason_jobchance[` + emp_line_id + `]" id="reason_jobchance_` + emp_line_id + `" ></td>
                <td>
                    <select class="form-control form-control-sm" name="is_this_relevant_exp[` + emp_line_id + `]" id="is_this_relevant_exp_` + emp_line_id + `">
                        <option value="">Select</option>
                        <option value="1" >Yes</option>
                        <option value="2" >No</option>
                    </select>
                </td>                    
                <td class=" text-center"><button type="button" class="btn btn-sm btn-danger" onClick="remove_line_item()"  id="remove_line_item_` + emp_line_id + `"   title="Remove"><i class=" fa fa-minus-circle"></i></button></td>
                </tr>`;

            var fields_valid = true;
            if (emp_line_id >= 1) {
                var fields = ['company_name_', 'country_id_', 'state_id_', 'nearest_city_', 'start_date_', 'end_date_', 'last_designation_', 'responsibilities_', 'annual_ctc_', 'reason_jobchance_', 'is_this_relevant_exp_'];
                var previous_line_id = Number(emp_line_id - 1);
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
            // console.log(emp_line_id)

            if (fields_valid) {
                $('#emp_line_items').append(html);

                $("#employee_emp_history #company_name_" + emp_line_id).rules("add", {
                    required: true,
                });
                $("#employee_emp_history #country_id_" + emp_line_id).rules("add", {
                    required: true,
                });
                $("#employee_emp_history #state_id_" + emp_line_id).rules("add", {
                    required: true,
                });
                $("#employee_emp_history #nearest_city_" + emp_line_id).rules("add", {
                    required: true,
                });

                $("#employee_emp_history #start_date_" + emp_line_id).rules("add", {
                    required: true,
                });
                // $("#employee_emp_history #end_date_" + emp_line_id).rules("add", {
                //     required: true,
                // });
                $("#employee_emp_history #last_designation_" + emp_line_id).rules("add", {
                    required: true,
                });
                $("#employee_emp_history #responsibilities_" + emp_line_id).rules("add", {
                    required: true,
                });
                $("#employee_emp_history #annual_ctc_" + emp_line_id).rules("add", {
                    required: true,
                });
                $("#employee_emp_history #reason_jobchance_" + emp_line_id).rules("add", {
                    required: true,
                });
                $("#employee_emp_history #is_this_relevant_exp_" + emp_line_id).rules("add", {
                    required: true,
                });

                $('.datepicker').datepicker({
                    format: 'dd-mm-yyyy',
                    todayHighlight: true,
                    autoclose: true
                });

                var line_id = emp_line_id;
                $('#employee_emp_history #start_date_' + emp_line_id).datepicker({
                    format: 'dd-mm-yyyy',
                    todayHighlight: true,
                    autoclose: true
                }).on('changeDate', function() {
                    var StartDate = $(this).val();
                    // console.log(line_id)
                    $('#end_date_' + line_id).datepicker('setStartDate', StartDate);
                });
                $('#employee_emp_history #end_date_' + emp_line_id).datepicker({
                    format: 'dd-mm-yyyy',
                    todayHighlight: true,
                    autoclose: true
                }).on('changeDate', function() {
                    var StartDate = $(this).val();
                    $('#start_date_' + line_id).datepicker('setEndDate', StartDate);
                });

                emp_line_id++;
            }
        });

    });

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