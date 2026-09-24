<?php
$leave = $this->leave->leave_by_id($param2);
$my_id = $this->session->userdata('employee_id');
//pr($leave);
if(!empty($leave)) {
    //get pending entry
    $res = $this->leave->leave_status_by_head($my_id, $param2);

    //pr($leave);

    ?>

<div class="row clearfix">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <?php /* <h6 class="mb-3">Previous status</h6>
            <ul class="right_chat list-unstyled">
                <?php foreach($leave['detailed_status'] as $row) {?>

                <li class="offline">
                    <a href="javascript:void(0);">
                        <div class="media">
                            <div class="media-body">
                                <span class="name"><?= $row['name']?>&nbsp;&nbsp;-&nbsp;&nbsp;<?= $row['role']?> <small class="float-right"><?= get_date($row['status_changed'])?></small></span>
                                <span class="message"><?= ($row['remarks'] != "") ? $row['remarks'] : "No remarks!"?></span>
                                <div class="mt-2"><?=leave_status_c($row['status'])?></div>
                            </div>
                        </div>
                    </a>
                </li>

                <?php } ?>
            </ul> */?>
            <?php if(!empty($res) && $res['status'] == 'pending') {?>
            <form class="form-auth-small" action="" name="reject_leave" id="reject_leave" method="POST" enctype="multipart/form-data">
                <div class="row clearfix">
                    <?php /* <div class="col-md-12">
                        <div class="alert alert-warning" role="alert">You are <strong>rejecting</strong> this leave request as <strong><?=$res['role']?></strong></div>
                    </div> */?>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea class="form-control" name="remarks" rows="4" cols="30"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">

                        <input type="submit" class="btn btn-lg btn-danger btnsmt" value="Reject" />

                    </div>
                </div>
            </form>
            <?php } ?>
            <?php /* if(empty($leave['active_leaves'])) {?>
                <div class="alert alert-warning" role="alert">Leave Cancelled!</div>
                <?php } */?>
        </div>
    </div>
</div>

<script type="text/javascript">
$("#reject_leave").validate({
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
            text: "Want to reject this leave request?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            confirmButtonText: "Yes, reject it!",
            closeOnConfirm: true
        }, function(isConfirm) {

            if (isConfirm) {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'leave/reject_leave_process',
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
                            $('.btnsmt').prop('disabled', false).attr('value', 'Reject');
                        }

                    },
                    error: function(error) {
                        toaster('error', error);
                        $('.btnsmt').prop('disabled', false).attr('value', 'Reject');
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