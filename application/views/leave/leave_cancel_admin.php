<?php
$leave = $this->leave->leave_by_id($param2);
if (!empty($leave)) {

    ?>

<div class="row clearfix">
    <div class="col-md-12 col-sm-12">
        <?php if (!empty($leave['leave_split'])) {?>        
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
                usort($leave['leave_split'], function ($a, $b) {
                    return strtotime($a['leave_date']) - strtotime($b['leave_date']);
                });

                foreach ($leave['leave_split'] as $row) {

                ?>
                <tr>
                    <td><?= get_date($row['leave_date'])?></td>
                    <td><?= cancel_status($row['cancel_status'])?></td>
                    <td><?php if ($row['cancel_status'] == 0) { ?><input type="button" class="btn btn-sm btn-warning btnsmt" onclick="cancelLeaveAdmin(<?=$row['leave_application_id']?>)" id="c<?=$row['leave_application_id']?>" value="Cancel"><?php } ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } ?>

    </div>
</div>
<script>
function cancelLeaveAdmin(leave_application_id) {
    $('#c' + leave_application_id).prop('disabled', true).attr('value', 'Processing...');
    if (leave_application_id != "") {

        swal({
            title: "Are you sure?",
            text: "",
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
                    url: base_url + 'leave/leave_cancel_admin_process',
                    cache: false,
                    async: false,
                    data: "leave_application_id=" + leave_application_id,
                    dataType: "html",
                    success: function(response) {
                        var obj = $.parseJSON(response);
                        if (obj.status == 1) {

                            toaster('success', obj.msg);
                            leave_manage_list();
                            $('#modal_ajax').modal('toggle');
                            
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