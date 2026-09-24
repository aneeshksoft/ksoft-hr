<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                       
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover dataTable table-custom table-striped m-b-0 c_list" id="leave_application_list">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                &nbsp;
                                            </th>
                                            <th>Name</th>   
                                            <th>Applied Date</th>
                                            <th>Leave Type</th>
                                            <th>Leave Date</th>
                                            <th>Reason</th>
                                            <th>Status</th>
                                            <th>Air Ticket</th>
                                            <th>Status Changed</th>
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
$(document).ready(function(){
        
          

        var table = $('#leave_application_list').DataTable({
            "ajax": base_url+"user/leave_application_status_ajax",
            "columnDefs": [
                { className: "text-nowrap", "targets": [1,4] }
            ],
            "columns": [
                { "data": "profile_photo"},
                { "data": "name" },
                { "data": "created_at" },
                { "data": "leave_type" },
                { "data": "date" },
                { "data": "remarks" },
                { "data": "status" },
                { "data": "airticket" },
                { "data": "status_changed" },
                { "data": "action" }
            ],
            "order": [[3, 'desc']]
        });
         
});
</script>