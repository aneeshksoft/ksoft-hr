<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <? //= pr($employee); 
            ?>
            <div class="card-body">
                <form action="" name="head_assignment" id="head_assignment" method="POST" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Reporting Manger<sup>*</sup></label>
                                <select class="form-control" name="reporting_manager_id" id="reporting_manager_id">
                                    <option value="">Select</option>
                                    <?php if ($reporting_managers) : ?>
                                        <?php foreach ($reporting_managers as $reporting_manager) : ?>:
                                            <option value="<?= $reporting_manager['employee_id']; ?>" <?=($reporting_manager['employee_id']==$employee['reporting_manager_id'])?'selected':""?>><?= $reporting_manager['name'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <?php if ($employee['probation_status'] == 0) : ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Special Role<sup>*</sup></label>
                                    <select class="form-control" name="special_role" id="special_role">
                                        <option value="">Select</option>
                                        <?php if ($special_roles) : ?>
                                            <?php foreach ($special_roles as $role) : ?>
                                                <option value="<?= $role; ?>" <?=($role==$employee['special_role'])?'selected':""?>><?= ucfirst($role); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="col-sm-12 mt-1 text-right">
                            <div class="mt-4">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // console.log($('#reporting_manager_id'))
        $('#reporting_manager_id').select2({
            dropdownParent: $('#modal_ajax'),
            width: '100%'
        }).on('change', function() {
            $(this).valid();
        });

        $("#head_assignment").validate({
            rules: {
                reporting_manager_id: {
                    required: true
                },
                special_role: {
                    required: true
                },
            },
            messages: {

            },
            errorPlacement: function(error, element) {
                element.closest('.form-group').append(error);
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
                        url: '<?= base_url('employee/reporting_head_post') ?>',
                        cache: false,
                        async: false,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            var obj = $.parseJSON(response);
                            if (obj.status == 1) {

                                employees_list.ajax.reload();
                                $('#modal_ajax').modal('toggle');
                                toaster('success', obj.msg);
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
    });
</script>