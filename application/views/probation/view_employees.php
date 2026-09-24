<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="row">
    <div class="col-sm-12">
        <? //= pr($products) 
        ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-custom" id="employees_list">
                                <thead class=" thead-dark">
                                    <tr>
                                        <th width="1%">S.No</th>
                                        <th>Name</th>
                                        <th>Employee Code</th>
                                        <th>Designation</th>
                                        <th>Department</th>
                                        <th>Probation Status</th>
                                        <th width="1%">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="<?= base_url() ?>assets/user/bundles/datatablescripts.bundle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
    var employees_list;
    $(document).ready(function() {

        employees_list = $('#employees_list').DataTable({
            "ajax": {
                type: 'POST',
                url: '<?= base_url("probation/view_employees_ajax") ?>',
            },

            "columns": [{
                    "data": "sno"
                },
                {
                    "data": "name"
                },
                {
                    "data": "code"
                },
                {
                    "data": "designation"
                },
                {
                    "data": "department"
                },
                {
                    "data": "probation_status"
                },
                {
                    "data": "action",
                    "orderable": false
                }
            ],
            "order": [
                [0, 'asc']
            ],
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            responsive: true
        });

    });
</script>