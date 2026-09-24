<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <form class="form-auth-small" action="" name="add_leave_type" id="add_leave_type" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Leave Type<sup>*</sup></label>
                                <input type="text" class="form-control" name="title" autofocus>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Approval Type<sup>*</sup></label>
                                <select class="form-control show-tick" name="type" onchange="onChangeApprovalType(this.value)">
                                    <option value="">Select</option>
                                    <option value="hierarchy">HIERARCHY</option>
                                    <option value="direct">DIRECT</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Is Sandwich<sup>*</sup></label>
                                <select class="form-control show-tick" name="is_sandwich">
                                    <option value="">Select</option>
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 col_special_leave_approval_needed">
                            <div class="form-group">
                                <label>Special leave approval needed?<sup>*</sup></label>
                                <select class="form-control show-tick" name="special_leave_approval_needed" id="special_leave_approval_needed" readonly>
                                    <!-- <option value="">Select</option> -->
                                    <!-- <option value="0">No</option> -->
                                    <option value="1" selected>Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 text-right">
                            <div class="mt-4">
                                <button type="submit" name="submit" class="btn btn-primary">Save</button>
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
        onChangeApprovalType();
        $("#add_leave_type").validate({
            rules: {
                title: {
                    required: true,
                    remote: {
                        url: "leave/checkLeaveTypeExist",
                        type: "post",
                    }
                },
                type: {
                    required: true,
                },
                is_sandwich: {
                    required: true,
                },
                special_leave_approval_needed: {
                    required: true,
                }
            },
            messages: {
                title: {
                    required: "Please enter leave type name",
                    remote: "Already exist"
                },
            },
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
                        url: '<?= base_url('leave/leave_type_process') ?>',
                        cache: false,
                        async: false,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            var obj = $.parseJSON(response);
                            if (obj.status == 1) {
                                toaster('success', obj.msg);
                                leave_type_list_table.ajax.reload();
                                $('#modal_ajax').modal('toggle');
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

    })

    function onChangeApprovalType(val) {

        var approvalType = val;
        console.log(approvalType == "direct")
        if (approvalType == "direct") {
            $(".col_special_leave_approval_needed").show();
            // $(".special_leave_approval_needed").val('');
        } else {
            $(".col_special_leave_approval_needed").hide();
            // $(".special_leave_approval_needed").val('');
        }

    }
</script>