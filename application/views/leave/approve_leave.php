<?php
$leave = $this->leave->leave_by_id($param2);
$my_id = $this->session->userdata('employee_id');
//pr($leave);
if(!empty($leave)) {
    //get pending entry
    $res = $this->leave->leave_status_by_head($my_id, $param2);
    $availability = $this->leave->checkLeaveAvailability($leave['employee_id']);
    //pr($availability);
    //pr($res);
    //pr($leave['active_leaves']);
    ?>

<div class="row clearfix">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-3">Leave Availability</h6>
                    <ul class="right_chat list-unstyled">
                        <li class="offline">
                            <div class="media">
                                <div class="media-body">
                                    <span class="name ">PL available: <strong class="badge badge-success" style="margin-left: 3px !important;"><?= $availability['pl_balance']?> days</strong></span>
                                    <span class="name mt-1">SL available: <strong class="badge badge-danger" style="margin-left: 3px !important;"><?= $availability['sl_balance']?> days</strong></span>
                                    <span class="name mt-1">CO available: <strong class="badge badge-info"><?= $availability['co']?> days</strong></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="mb-3">Leave Details</h6>
                    <ul class="right_chat list-unstyled">
                        <li class="offline">
                            <div class="media">
                                <div class="media-body">
                                    <span class="name">Leave type: <strong class="badge badge-success" style="margin-left: 16px !important;font-size:17px;padding:8px 6px 8px;"><?= $leave['title']?></strong></span>
                                    <span class="name mt-1">Day type: <strong style="margin-left: 30px !important;"><?= is_halfday($leave['is_halfday']);?></strong></span>
                                    <?php if($leave['is_halfday']==1){?>
                                    <span class="name mt-1">FN/AN: <strong class="badge badge-info" style="margin-left: 41px !important;"><?= $leave['fn_an'] ?? '-'?></strong></span>
                                    <?php } ?>
                                    <span class="name mt-1">Applied Days: <strong class="badge badge-danger"><?= trimDecimal($leave['total_count'])?> days</strong></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <?php if(!empty($res) && $res['status'] == 'pending' && !empty($leave['active_leaves'])) {?>
            <form class="form-auth-small" action="" name="approve_leave" id="approve_leave" method="POST" enctype="multipart/form-data">
                <div class="row clearfix">
                    <?php /* <div class="col-md-12">
                        <div class="alert alert-warning" role="alert">You are <strong>approving</strong> this leave request as <strong><?=$res['role']?></strong></div>
        </div> */?>

        <div class="col-md-12 col-sm-12">
            <div class="form-group">
                <label>Remarks</label>
                <textarea class="form-control" name="remarks" rows="4" cols="30"></textarea>
            </div>
        </div>
        <div class="col-sm-12">

            <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Approve" />

        </div>
    </div>
    </form>
    <?php } ?>

    <?php if(empty($leave['active_leaves'])) {?>
    <div class="alert alert-warning" role="alert">Leave Cancelled!</div>
    <?php }?>

</div>
</div>
</div>

<script type="text/javascript">
$("#approve_leave").validate({
    rules: {

    },
    messages: {

    },
    submitHandler: function(form, e) {

        e.preventDefault();

        $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
        var leave_application_status_id = '<?= $res['leave_application_status_id']?>';
        var group_id = '<?= $res['group_id']?>';
        var form_data = new FormData(form);
        form_data.append('leave_application_status_id', leave_application_status_id);
        form_data.append('group_id', group_id);

        swal({
            title: "Are you sure?",
            text: "Want to approve this leave request?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            confirmButtonText: "Yes, approve it!",
            closeOnConfirm: true
        }, function(isConfirm) {

            if (isConfirm) {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'leave/approve_leave_process',
                    cache: false,
                    async: false,
                    data: form_data,
                    contentType: false,
                    processData: false,
                    success: function(response) {

                        var obj = $.parseJSON(response);
                        if (obj.status == 1) {
                            toaster('success', obj.msg);
                            //$('#leave_application_list').DataTable().ajax.reload();
                            leave_application_list();
                            $('#modal_ajax').modal('toggle');
                        } else {
                            toaster('error', obj.msg);
                            $('.btnsmt').prop('disabled', false).attr('value', 'Approve');
                        }

                    },
                    error: function(error) {
                        toaster('error', error);
                        $('.btnsmt').prop('disabled', false).attr('value', 'Approve');
                    }

                });

            } else {
                $('#modal_ajax').modal('toggle');
            }
        });
        return false;
    }
});
</script>

<?php } else {
    $this->load->view('theme/user/notfound');
} ?>