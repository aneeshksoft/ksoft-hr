<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<div class="row clearfix">    
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">           
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover dataTable table-custom table-striped m-b-0 c_list" id="leave_type_list">
                        <thead class="thead-dark">
                            <tr>
                                <th>Employee</th>
                                <th>Email</th>
                                <th>PL Carry Fwd</th>
                                <!-- <th>PL Total</th>
                                <th>PL Used</th> -->
                                <th>PL Available</th>
                                <!-- <th>SL Total</th>
                                <th>SL Used</th> -->
                                <th>SL Available</th>
                                <th>CO Available</th>
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
<script type="text/javascript">
    
var table;
$(function() {

    table = $('#leave_type_list').DataTable({
        "ajax": base_url + "leave/available_leaves_list_ajax",
        "columns": [{
                "data": "employee"
            },            
            {
                "data": "email"
            },
            {
                "data": "pl_carry_fwd"
            },
            // {
            //     "data": "pl_total"
            // },
            // {
            //     "data": "pl_used"
            // },
            {
                "data": "pl_balance"
            },
            // {
            //     "data": "sl_total"
            // },
            // {
            //     "data": "sl_used"
            // },
            {
                "data": "sl_balance"
            },
            {
                "data": "co"
            }
        ],
        "order": [],
        "paging": false,
        "ordering": false,
    }); 
});
</script>