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
                <form action="" name="employee_cert_history" id="employee_cert_history" method="POST" enctype="multipart/form-data">
                    <? //= pr($certification_history);
                    ?>

                    <div class="row">

                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover border">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="1%">#</th>
                                            <th>Name of the Certification<sup>*</sup></th>
                                            <th>Specialization<sup>*</sup></th>
                                            <th>Institute Name<sup>*</sup></th>
                                            <th>Start Date<sup>*</sup></th>
                                            <th>End Date<sup>*</sup></th>
                                            <th>Valid Upto</th>
                                            <th width="1%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="cert_line_items">
                                        <?php if (!empty($certification_history)) : ?>
                                            <?php foreach ($certification_history as $key => $row) : ?>
                                                <tr>
                                                    <td><?= $key + 1 ?></td>
                                                    <td><?= $row['certification'] ?></td>
                                                    <td><?= $row['specialization'] ?></td>
                                                    <td><?= $row['institute_name'] ?></td>
                                                    <td><?= get_date($row['start_date']) ?></td>
                                                    <td><?= get_date($row['end_date']) ?></td>
                                                    <td><?= get_date($row['valid_upto']) ?></td>
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
                                            <th colspan="7"></th>
                                            <th class=" text-center"><button id="add_cert_line_item" type="button" class="btn btn-sm btn-success " title="Add One More Item"><i class="fa fa-plus"></i></button></th>
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

        $("#employee_cert_history").validate({
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
                        url: '<?= base_url('employee/edit_employee_certification_history_post') ?>',
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

        var cert_line_id = $('#cert_line_items tr').length;
        $('#add_cert_line_item').click(function() {
            var sno = $('#cert_line_items tr').length;
            var html = "";
            html += `<tr>               
                    <td class="text-center">` + (sno + 1) + `</td>
                    <td><input class="form-control form-control-sm" type="text" name="certification[` + cert_line_id + `]" id="certification_` + cert_line_id + `"></td>
                    <td><input class="form-control form-control-sm" type="text" name="specialization[` + cert_line_id + `]" id="specialization_` + cert_line_id + `" ></td>
                    <td><input class="form-control form-control-sm" type="text" name="institute_name[` + cert_line_id + `]" id="institute_name_` + cert_line_id + `" ></td>
                    <td><input class="form-control form-control-sm datepicker" type="text" name="start_date[` + cert_line_id + `]" id="start_date_` + cert_line_id + `" ></td>
                    <td><input class="form-control form-control-sm datepicker" type="text" name="end_date[` + cert_line_id + `]" id="end_date_` + cert_line_id + `"></td>
                    <td><input class="form-control form-control-sm datepicker" type="text" name="valid_upto[` + cert_line_id + `]" id="valid_upto_` + cert_line_id + `"></td>
                    <td class=" text-center"><button type="button" class="btn btn-sm btn-danger" onClick="remove_line_item()"  id="remove_line_item_` + cert_line_id + `"   title="Remove"><i class=" fa fa-minus-circle"></i></button></td>
                    </tr>`;

            var fields_valid = true;
            if (cert_line_id >= 1) {
                var fields = ['certification_', 'specialization_', 'institute_name_', 'start_date_', 'end_date_', 'valid_upto_'];
                var previous_line_id = Number(cert_line_id - 1);
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
            console.log(cert_line_id)

            if (fields_valid) {
                $('#cert_line_items').append(html);

                $("#employee_cert_history #certification_" + cert_line_id).rules("add", {
                    required: true,
                });

                $("#employee_cert_history #specialization_" + cert_line_id).rules("add", {
                    required: true,
                });

                $("#employee_cert_history #institute_name_" + cert_line_id).rules("add", {
                    required: true,
                });

                $("#employee_cert_history #start_date_" + cert_line_id).rules("add", {
                    required: true,
                });
                $("#employee_cert_history #end_date_" + cert_line_id).rules("add", {
                    required: true,
                });
                // $("#employee_cert_history #valid_upto_" + cert_line_id).rules("add", {
                //     required: true,
                // });

                $('.datepicker').datepicker({
                    format: 'dd-mm-yyyy',
                    todayHighlight: true,
                    autoclose: true
                });

                var line_id = cert_line_id;
                $('#employee_cert_history #start_date_' + cert_line_id).datepicker({
                    format: 'dd-mm-yyyy',
                    todayHighlight: true,
                    autoclose: true
                }).on('changeDate', function() {
                    var StartDate = $(this).val();
                    console.log(line_id)
                    console.log($('#end_date_' + line_id).datepicker('setStartDate', StartDate));
                    $('#end_date_' + line_id).datepicker('setStartDate', StartDate);
                });
                $('#employee_cert_history #end_date_' + cert_line_id).datepicker({
                    format: 'dd-mm-yyyy',
                    todayHighlight: true,
                    autoclose: true
                }).on('changeDate', function() {
                    var StartDate = $(this).val();
                    $('#start_date_' + line_id).datepicker('setEndDate', StartDate);
                });

                cert_line_id++;
            }
        });

    });

    function remove_line_item() {
        event.target.closest("tr").remove();
    }
</script>