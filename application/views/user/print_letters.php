<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                       
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover dataTable table-custom table-striped m-b-0 c_list" id="print_letters">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                &nbsp;
                                            </th>
                                            <th>Name</th>
                                            <th>Employee Code</th>                                          
                                            <th>Designation & Department</th>
                                            <th>Letter Type</th>
                                            <th>Ref. No.</th>
                                            <th>Generated Date</th>                                           
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
$(document).ready(function(){
        var table = $('#print_letters').DataTable({
            "ajax": base_url+"user/print_letters_ajax",
            "columnDefs": [
                { className: "text-nowrap", "targets": [1,4,5] }
            ],
            "columns": [
                { "data": "profile_photo"},
                { "data": "name" },
                { "data": "code" },
                { "data": "designation" },
                { "data": "letter_type" },
                { "data": "refno" },
                { "data": "generated_date" },                
                { "data": "action" }
            ],
            "order": [[1, 'asc']]
        });
         
});
</script>