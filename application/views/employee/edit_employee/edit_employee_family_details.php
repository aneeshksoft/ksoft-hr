<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">

<div class="row">
    <div class="col-sm-12">

        <div class="card">
            <div class="card-header bg-white border-bottom-0">
                <?php $this->load->view("employee/nav_tab", array('_employee_id' => $employee['employee_id'])); ?>
            </div>
            <div class="card-body">
                <form action="" name="employee_family_details" id="employee_family_details" method="POST" enctype="multipart/form-data">
                    <? //= pr($family_details);
                    ?>

                    <div class="row">

                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover border">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="1%">#</th>
                                            <th>Name of Family Member<sup>*</sup></th>
                                            <th>Relationship with the Employee<sup>*</sup></th>
                                            <th>Living Status<sup>*</sup></th>
                                            <th>Gender<sup>*</sup></th>
                                            <th>Date of Birth<sup>*</sup></th>
                                            <th>Occupation<sup>*</sup></th>
                                            <th>Mobile Number<sup>*</sup></th>
                                            <th>Emergency Contact?<sup>*</sup></th>
                                            <th width="1%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="fam_line_items">
                                        <?php if (!empty($family_details)) : ?>
                                            <?php foreach ($family_details as $key => $row) : ?>
                                                <tr>
                                                    <td><?= $key + 1 ?></td>
                                                    <td><?= $row['name'] ?></td>
                                                    <td><?= ucfirst($row['relationship']) ?></td>
                                                    <td><?= ucfirst($row['living_status']) ?></td>
                                                    <td><?= ucfirst($row['gender']) ?></td>
                                                    <td><?= get_date($row['date_of_birth']) ?></td>
                                                    <td><?= $row['occupation'] ?></td>
                                                    <td><?= $row['mobile_number'] ?></td>
                                                    <td><?= ucfirst($row['emergency_contact']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="9"></th>
                                            <th class=" text-center"><button id="add_fam_line_item" type="button" class="btn btn-sm btn-success " title="Add One More Item"><i class="fa fa-plus"></i></button></th>
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

        $("#employee_family_details").validate({
            ignore: ':hidden,:disabled',
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
                        url: '<?= base_url('employee/edit_employee_family_details_post') ?>',
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

        var fam_line_id = $('#fam_line_items tr').length;
        $('#add_fam_line_item').click(function() {
            var sno = $('#fam_line_items tr').length;
            var html = "";
            html += `<tr>               
                    <td class="text-center">` + (sno + 1) + `</td>
                    <td><input class="form-control form-control-sm" type="text" name="name[` + fam_line_id + `]" id="name_` + fam_line_id + `"></td>
                    <td>
                        <select class="form-control form-control-sm" name="relationship[` + fam_line_id + `]" id="relationship_` + fam_line_id + `">
                            <option value="">Select</option>
                            <?php if ($relationships) : ?>
                                <?php foreach ($relationships as $value) : ?>
                                    <option value="<?= $value; ?>"><?= Ucfirst($value); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                    <td>
                        <select class="form-control form-control-sm" name="living_status[` + fam_line_id + `]"  id="living_status_` + fam_line_id + `" onChange="living_status_check(this.value, ` + fam_line_id + `)">
                            <option value="">Select</option>
                            <?php if ($living_status) : ?>
                                <?php foreach ($living_status as $value) : ?>
                                    <option value="<?= $value; ?>"><?= ucfirst($value); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </td>
                    <td><select class="form-control form-control-sm select2" name="gender[` + fam_line_id + `]" id="gender_` + fam_line_id + `">
                            <option value="" >Select</option>
                            <?php if ($genders) : ?>
                                <?php foreach ($genders as $value) : ?>
                                    <option value="<?= $value; ?>"><?= ucfirst($value); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                    </select></td>
                    <td><input class="form-control form-control-sm datepicker" type="text" name="date_of_birth[` + fam_line_id + `]" id="date_of_birth_` + fam_line_id + `"></td>
                    <td>
                    <select class="form-control form-control-sm select2" name="occupation_id[` + fam_line_id + `]" id="occupation_id_` + fam_line_id + `">
                            <option value="" >Select</option>
                            <?php if ($occupations) : ?>
                                <?php foreach ($occupations as $value) : ?>
                                    <option value="<?= $value['occupation_id']; ?>"><?= $value['name']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                    </select>
                    </td>
                    <td><input class="form-control form-control-sm" type="number" name="mobile_number[` + fam_line_id + `]" id="mobile_number_` + fam_line_id + `"></td>
                    <td>                
                        <select class="form-control form-control-sm select2" name="emergency_contact[` + fam_line_id + `]" id="emergency_contact_` + fam_line_id + `">                                                        
                        <?php if ($emergency_contact) : ?>
                                <?php foreach ($emergency_contact as $value) : ?>
                                    <option value="<?= $value; ?>"><?= ucfirst($value); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>    
                        </select>
                    </td>                    
                    <td class=" text-center"><button type="button" class="btn btn-sm btn-danger" onClick="remove_line_item()"  id="remove_line_item_` + fam_line_id + `"   title="Remove"><i class=" fa fa-minus-circle"></i></button></td>
                    </tr>`;

            var fields_valid = true;
            if (fam_line_id >= 1) {
                var fields = ['name_', 'relationship_', 'living_status_', 'gender_', 'date_of_birth_', 'occupation_id_', 'mobile_number_', 'emergency_contact_'];
                var previous_line_id = Number(fam_line_id - 1);
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
                $('#fam_line_items').append(html);

                $("#employee_family_details #name_" + fam_line_id).rules("add", {
                    required: true,
                });

                $("#employee_family_details #relationship_" + fam_line_id).rules("add", {
                    required: true,
                });

                $("#employee_family_details #living_status_" + fam_line_id).rules("add", {
                    required: true,
                });

                $("#employee_family_details #gender_" + fam_line_id).rules("add", {
                    required: true,
                });

                $("#employee_family_details #date_of_birth_" + fam_line_id).rules("add", {
                    required: true,
                });

                $("#employee_family_details #occupation_id_" + fam_line_id).rules("add", {
                    required: true,
                });

                $("#employee_family_details #mobile_number_" + fam_line_id).rules("add", {
                    required: true,
                    number: true,
                    maxlength: 10,
                    minlength: 10
                });

                $("#employee_family_details #emergency_contact_" + fam_line_id).rules("add", {
                    required: true,
                });

                $('.datepicker').datepicker({
                    format: 'dd-mm-yyyy',
                    todayHighlight: true,
                    autoclose: true
                });

                fam_line_id++;
            }
        });

    });

    function living_status_check(value, line_id) {

        if (value == 'deceased') {
            $('#gender_' + line_id).hide();
            $('#date_of_birth_' + line_id).val("").hide();
            $('#occupation_id_' + line_id).val("").hide();
            $('#mobile_number_' + line_id).val("").hide();
            $('#emergency_contact_' + line_id).hide();
        } else {
            $('#gender_' + line_id).val("").show();
            $('#date_of_birth_' + line_id).val("").show();
            $('#occupation_id_' + line_id).show();
            $('#mobile_number_' + line_id).show();
            $('#emergency_contact_' + line_id).show();
        }
    }


    function remove_line_item() {
        event.target.closest("tr").remove();
    }
</script>