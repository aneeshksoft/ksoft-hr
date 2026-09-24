<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">

            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover dataTable table-custom table-striped m-b-0 c_list" id="additional_leave">
                        <thead class="thead-dark">
                            <tr>
                                <th>Name</th>
                                <th>Allotted Date</th>
                                <th>Allotted Leave</th>
                                <th>Leave Type</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url()?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/user/bundles/datatablescripts.bundle.js"></script>
<script src="<?= base_url() ?>assets/user/js/pages/tables/jquery-datatable.js"></script>
<script src="<?= base_url() ?>assets/user/js/pages/ui/dialogs.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    var table = $('#additional_leave').DataTable({
        "ajax": base_url + "leave/additional_leave_list_ajax",
        "columnDefs": [{
            className: "text-nowrap",
            "targets": [1, 4]
        }],
        "columns": [{
                "data": "name"
            },
            {
                "data": "allotted_date"
            },
            {
                "data": "allotted_leave"
            },
            {
                "data": "leave_type"
            },
            {
                "data": "remarks"
            },
            {
                "data": "action"
            }
        ],
        "order": []
    });

});

function deleteLeaveAllocation(id,employee_id) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this data!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false,
    }, function() {

        $.ajax({
            type: 'POST',
            url: base_url + 'leave/deleteAdditionalLeave',
            cache: false,
            async: false,
            data: "id=" + id+"&employee_id="+employee_id,
            dataType: "html",
            success: function(response) {
                var obj = $.parseJSON(response);
                if (obj.status == 1) {
                    swal("Deleted!", "Additional leaves deleted!", "success");
                    $('#additional_leave').DataTable().ajax.reload();
                } else {
                    swal("Cancelled", "Something went wrong!", "error");
                }
            },
            error: function(error) {
                swal("Cancelled", "Something went wrong!", "error");
            }
        });
    });
}
</script>