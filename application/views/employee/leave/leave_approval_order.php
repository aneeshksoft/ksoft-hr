<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/nestable/jquery-nestable.css" />
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <form class="form-auth-small" action="" name="leave_approval_order" id="leave_approval_order" method="POST" enctype="multipart/form-data">
            <div class="card mb-3">
                <div class="body">
                    <div class="row clearfix">

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Head<sup>*</sup></label>
                                <select class="form-control" name="head_id">
                                    <option value="">Select</option>
                                    <?php if (!empty($employees)) : ?>
                                        <?php foreach ($employees as $employee) : ?>
                                            <option value="<?= $employee['employee_id'] ?>"><?= $employee['name'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group mt-4">
                                <button type="submit" name="submit" class="btn btn-lg btn-primary">Save</button>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="header">
                <div>Drag to change approval order!</div>
            </div>
            <div class="body">
                <?php if (!empty($order)) : ?>
                    <div class="dd dd4">
                        <ol class="dd-list">

                            <?php foreach ($order as $row) { ?>
                                <li class="dd-item" data-employee_id="<?= $row['employee_id'] ?>" data-head_id="<?= $row['head_id'] ?>" data-approval_order_id="<?= $row['approval_order_id'] ?>">
                                    <div class="dd-handle bg-primary">

                                        <span class=" mr-4">
                                            <?= $row['position'] ?>
                                        </span>
                                        <span class="">
                                            <?= $row['head'] ?>
                                        </span>
                                    </div>
                                    <i class="icon-close rdelete" onclick="delete_leave_approval_head('<?= $row['approval_order_id'] ?>')"></i>
                                </li>
                            <?php } ?>
                        </ol>
                    </div>
                <?php else : ?>
                    <p class="text-center text-muted">Nothing Found!</p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/vendor/nestable/jquery.nestable.min.js"></script>
<script type="text/javascript">
    $(function() {

        $('.dd').nestable();
        $('.dd').on('change', function() {
            var $this = $(this);
            var serializedData = window.JSON.stringify($($this).nestable('serialize'));
            changeOrder(serializedData);
        });

        $("#leave_approval_order").validate({
            rules: {
                head_id: {
                    required: true
                }
            },
            messages: {},
            submitHandler: function(form, e) {

                e.preventDefault();
                $(form).find(':submit').prop('disabled', true).text('Processing...')
                var form_data = new FormData(form);
                form_data.append('employee_id', '<?= $employee_id ?>');
                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'employee/leave_approval_order_process',
                        cache: false,
                        async: false,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            var obj = $.parseJSON(response);
                            if (obj.status == 1) {
                                toaster('success', obj.msg);
                                showAjaxModal('<?= base_url('employee/leave_approval_order/' . $employee_id) ?>', 'Leave Approval Order', 'modal-lg')
                            } else {
                                toaster('error', obj.msg);
                                $(form).find(':submit').prop('disabled', false).text('Save');
                            }

                        },
                        error: function(error) {
                            toaster('error', error);
                            $(form).find(':submit').prop('disabled', false).text('Save');
                        }
                    });
                }, 500);
                return false;
            }
        });

    });

    function changeOrder(serializedData) {
        $.ajax({
            type: 'POST',
            url: base_url + 'employee/arrange_leave_approval_order',
            cache: false,
            async: false,
            data: 'data=' + serializedData,
            dataType: "html",
            success: function(response) {
                var obj = $.parseJSON(response);
                if (obj.status == 1) {
                    toaster('success', obj.msg);
                    showAjaxModal('<?= base_url('employee/leave_approval_order/' . $employee_id) ?>', 'Leave Approval Order', 'modal-lg')

                }
                // else {
                //     toaster('error', obj.msg);
                // }
            },
            error: function(error) {
                toaster('error', error);
            }
        });
    }

    function delete_leave_approval_head(approval_order_id) {
        var table = $(event.target).closest('table');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this data!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: true
        }, function() {

            $.ajax({
                type: 'POST',
                url: '<?= base_url('employee/delete_leave_approval_order_process') ?>',
                cache: false,
                async: false,
                data: {
                    approval_order_id: approval_order_id
                },
                dataType: "html",
                success: function(response) {
                    var obj = $.parseJSON(response);
                    if (obj.status == 1) {
                        toaster('success', obj.msg);
                        showAjaxModal('<?= base_url('employee/leave_approval_order/' . $employee_id) ?>', 'Leave Approval Order', 'modal-lg')

                    } else {
                        toaster('error', obj.msg);
                    }
                },
                error: function(error) {
                    toaster('error', "Something went wrong!");
                },
            });
        });
    }
</script>