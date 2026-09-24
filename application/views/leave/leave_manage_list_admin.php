<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<div class="row clearfix">    
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover dataTable table-custom table-striped m-b-0 c_list" id="leave_manage_list">
                        <thead class="thead-dark">
                            <tr>
                                <th width="16%">Name</th>                                
                                <th>Created At</th>
                                <th>Type</th>
                                <th>Is Half Day</th>
                                <th>FN/AN</th>                                
                                <th width="13%">Date</th>
                                <th width="26%">Remarks</th>                               
                                <th>Status</th>
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

    leave_manage_list();
    table = $('#leave_manage_list').DataTable({
        
        "columnDefs": [{
            className: "text-nowrap",
            "targets": [5]
        }],

        "columns": [{
                "data": "name"
            },           
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
                "data": "date"
            },                      
            {
                "data": "remarks"
            },
            {
                "data": "status"
            },            
            {
                "data": "action"
            }
        ],
        "order": []
    });

    $("#leave_manage_list_form").validate({
        rules: {},
        messages: {},
        submitHandler: function(form, e) {

            e.preventDefault();

            leave_manage_list();

            return false;
        }
    });

});

function leave_manage_list() {

    $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
   
    setTimeout(function() {
        $.ajax({
            type: 'POST',
            url: base_url + "leave/leave_manage_admin_list_ajax",
            cache: false,
            async: false,
            data: "",            
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