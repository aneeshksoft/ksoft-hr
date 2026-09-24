<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <form class="form-auth-small" action="" name="leave_application_list_form" id="leave_application_list_form" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status" id="status" tabindex="0">
                                    <option value="all">All</option>
                                    <option value="pending" selected>Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="mt-4">
                                <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />
                                <input type="reset" class="btn btn-lg btn-danger" value="Cancel">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-12">
        <div class="card">

            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover dataTable table-custom table-striped m-b-0 c_list" id="leave_application_list">
                        <thead class="thead-dark">
                            <tr>
                                <th>Name</th>
                                <!-- <th>Designation & Department</th> -->
                                <th>Applied Date</th>
                                <th>Leave Type</th>
                                <th>Is Half Day</th>
                                <th>FN/AN</th>
                                <th>Leave Reason</th>
                                <th>Leave Date</th>
                                
                                <!-- <th>Leave Count</th> -->
                                <th>Leave Balance, <br/>inclusive of this request</th>
                                <th>Approve/Reject Reason</th>
                                <th>Approve/Reject Status</th>
                                <!-- <th>Status Changed</th> -->
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

<script src="<?= base_url() ?>assets/user/bundles/datatablescripts.bundle.js"></script>
<script src="<?= base_url() ?>assets/user/js/pages/tables/jquery-datatable.js"></script>
<script src="<?= base_url() ?>assets/user/js/pages/ui/dialogs.js"></script>
<script type="text/javascript">
var table;
$(document).ready(function() {

    leave_application_list();
    table = $('#leave_application_list').DataTable({
        //"ajax": base_url + "leave/leave_application_list_ajax",      
        "columnDefs": [{
            className: "text-nowrap",
            "targets": [0, 1,6]
        }],
        "columns": [{
                "data": "name"
            },
            // {
            //     "data": "designation"
            // },
            {
                "data": "created_at"
            },
            {
                "data": "leave_type"
            },
            {
                "data": "is_halfday"
            },
            {
                "data": "fn_an"
            },
            {
                "data": "leave_reason"
            },
            {
                "data": "date"
            },
            // { "data": "leave_count" },
            {
                "data": "totdays"
            },
            {
                "data": "remarks"
            },
            {
                "data": "status"
            },
            // { "data": "status_changed" },
            {
                "data": "action"
            }
        ],
        "order": []
    });

    $("#leave_application_list_form").validate({
        rules: {},
        messages: {},
        submitHandler: function(form, e) {

            e.preventDefault();

            leave_application_list();

            return false;
        }
    });

});

function leave_application_list() {
    $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
    var status = $('#status').val();
    
    setTimeout(function() {
        $.ajax({
            type: 'POST',
            url: base_url + "leave/leave_application_list_ajax",
            cache: false,
            async: false,
            data: "status=" + status,            
            success: function(response) {
                var obj = $.parseJSON(response);
                table.clear();
                table.rows.add(obj).draw(false);
                $('.btnsmt').prop('disabled', false).attr('value', 'Submit');
            },
            error: function(error) {
                toaster('error', error);
                $('.btnsmt').prop('disabled', false).attr('value', 'Submit');
            }
        });
    }, 500);
}
</script>