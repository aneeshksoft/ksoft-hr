<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header bg-white">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <strong class="h6">All Leave Types</strong>
                    </div>
                    <div class="col-sm-6 text-right">
                        <button type="button" class="btn btn-success btn-sm" onclick="showAjaxModal('<?= base_url('leave/add_leave_type') ?>', 'Add Leave Type', 'modal-lg')"><i class="fa fa-plus"></i> Add Leave Type</button>
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover dataTable table-custom table-striped m-b-0 c_list" id="leave_type_list">
                        <thead class="thead-dark">
                            <tr>
                                <th>Name</th>
                                <!-- <th>Carry Forward Limit</th> -->
                                <th>Is Sandwich</th>
                                <th>Approval Type</th>
                                <th>Special leave approval needed?</th>
                                <th width="20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header bg-white">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <strong class="h6">Special Leave Approval Head</strong>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form class="form-auth-small" action="" name="special_leave_approval_head" id="special_leave_approval_head" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Special Leave Approval Head<sup>*</sup></label>
                                <select class="form-control show-tick" name="head_id">
                                    <option value="">Select</option>
                                    <?php if ($employees) : ?>
                                        <?php foreach ($employees as $employee) : ?>
                                            <option value="<?= $employee['employee_id']; ?>" <?= (($speacial_leave_approve_head['head_id'] ?? "") == $employee['employee_id']) ? "selected" : "" ?>> <?= $employee['name']; ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 mt-1">
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
<script src="<?= base_url() ?>assets/user/bundles/datatablescripts.bundle.js"></script>
<script src="<?= base_url() ?>assets/user/js/pages/tables/jquery-datatable.js"></script>
<script type="text/javascript">
    var leave_type_list_table;
    $(document).ready(function() {
        leave_type_list_table = $('#leave_type_list').DataTable({
            "ajax": base_url + "leave/leave_type_list_ajax",
            "columns": [{
                    "data": "title"
                },
                // {
                //     "data": "carry_fwd_limit"
                // },
                {
                    "data": "is_sandwich"
                },
                {
                    "data": "type"
                },
                {
                    "data": "special_leave_approval_needed"
                },
                {
                    "data": "action"
                }
            ],
            "order": [
                [0, 'asc']
            ]
        });

    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#special_leave_approval_head").validate({
            rules: {
                head_id: {
                    required: true,
                },
            },
            messages: {},
            errorPlacement: function(error, element) {
                element.closest('.form-group').append(error);
            },
            submitHandler: function(form, e) {
                e.preventDefault();
                $(form).find(':submit').prop('disabled', true).text('Processing...')
                var form_data = new FormData(form);
                // console.log(form_data)
                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url('leave/add_special_leave_approval_head_post') ?>',
                        cache: false,
                        async: false,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            var obj = $.parseJSON(response);
                            if (obj.status == 1) {
                                toaster('success', obj.msg);
                                $(form).find(':submit').prop('disabled', false).text('Update');
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

    })
</script>