<?php
$leave = $this->leave->leave_by_id($param2);
if(!empty($leave)) {
    //pr($leave);
    $leave_type_details = $this->common_model->selectOne('leave_types', array('leave_type_id' => $leave['leave_type_id']), '*');
    //pr($leave_type_details);

    //to check only creator can cancel leave
    $session_id = $this->session->userdata('employee_id');

    //to prevent cancelling holiday leave indepentanly
    $holidays_qry = "select holiday_date from holidays where holiday_date between '".date('Y')."-01-01' and '".date('Y')."-12-31'";
    $holidays_resp = $this->db->query($holidays_qry)->result_array();
    $ignore_dates = array();

    if(!empty($holidays_resp)) {
        $ignore_dates = array_map(function ($item) { return $item['holiday_date']; }, $holidays_resp);
    }
    ?>

<div class="row clearfix">
    <div class="col-md-12 col-sm-12">

        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-7">
                        <small class="text-small">Overall Leave Status</small>
                        <h5 class="m-t-0"><?= ucfirst($leave['status'])?></h5>
                    </div>
                </div>
            </div>
        </div>

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
        </ul>
        <?php if(!empty($leave['leave_split'])) {?>
        <h6 class="mb-3">Leave Details:</h6>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col" width="140">Cancel Status</th>
                    <th scope="col" width="125">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                usort($leave['leave_split'], function($a, $b) {
                    return strtotime($a['leave_date']) - strtotime($b['leave_date']);
                });
                foreach($leave['leave_split'] as $row) {

                    $days = day_diff_sign($row['leave_date'], date('Y-m-d'));

                    $isWorkingDay = false;
                    $dayOfWeek = date('N', strtotime($row['leave_date']));
                    if($dayOfWeek < 6 &&  !in_array($row['leave_date'], $ignore_dates)) {
                        $isWorkingDay = true;
                    }

                    ?>
                <tr>
                    <td><?= get_date($row['leave_date'])?></td>
                    <td><?= cancel_status($row['cancel_status'])?></td>
                    <td><?php if($days > 0 && $isWorkingDay && $session_id == $leave['employee_id'] && $row['cancel_status']==0) { ?><input type="button" class="btn btn-sm btn-warning btnsmt" onclick="cancelLeave(<?=$row['leave_application_id']?>)" id="c<?=$row['leave_application_id']?>" value="Cancel"><?php } ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } ?>

    </div>
</div>
<script>
function cancelLeave(leave_application_id) {
    $('#c' + leave_application_id).prop('disabled', true).attr('value', 'Processing...');
    if (leave_application_id != "") {

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this data!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            confirmButtonText: "Yes, cancel it!",
            closeOnConfirm: true,
            showLoaderOnConfirm: true
        }, function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'leave/leave_cancel_process',
                    cache: false,
                    async: false,
                    data: "leave_application_id=" + leave_application_id,
                    dataType: "html",
                    success: function(response) {
                        var obj = $.parseJSON(response);
                        if (obj.status == 1) {

                            toaster('success', obj.msg);
                            $('#leave_application_list').DataTable().ajax.reload();
                            $('#modal_ajax').modal('toggle');
                            // setTimeout(function() {
                            //     window.location.reload();
                            // }, 2000);

                        } else {
                            toaster('error', obj.msg);
                            $('#c' + leave_application_id).prop('disabled', false).attr('value', 'Cancel');
                        }
                    },
                    error: function(error) {
                        toaster('error', error);
                        $('#c' + leave_application_id).prop('disabled', false).attr('value', 'Cancel');
                    }
                });
            } else {
                $('#c' + leave_application_id).prop('disabled', false).attr('value', 'Cancel');
            }
        });

    } else {
        toaster('error', 'Invalid data!');
        $('#c' + leave_application_id).prop('disabled', false).attr('value', 'Cancel');
    }
}
</script>
<?php } else {
    $this->load->view('theme/user/notfound');
} ?>