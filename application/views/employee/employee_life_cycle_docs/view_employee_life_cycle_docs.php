<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">

<div class="row">
    <div class="col-sm-12">
        <? //= pr($products) 
        ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 text-right">
                        <button onclick="showAjaxModal('<?= base_url('employee/add_employee_life_cycle_doc/' . $employee['employee_id']) ?>','Add Employee Life Cycle Document','modal-lg')" class="btn btn-success btn-sm" title="Add"><i class="fa fa-plus"></i> Add New</button>
                    </div>
                    <div class="col-sm-12 mt-3">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-custom" id="employee_life_cycle_docs">
                                <thead class=" thead-dark">
                                    <tr>
                                        <th width="1%">S.No</th>
                                        <th>Type</th>
                                        <th>Doc</th>
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

<script type="text/javascript">
    var employee_life_cycle_docs;
    $(document).ready(function() {

        employee_life_cycle_docs = $('#employee_life_cycle_docs').DataTable({
            "ajax": {
                type: 'POST',
                url: '<?= base_url("employee/view_employee_life_cycle_docs_ajax") ?>',
                data: {
                    'employee_id': '<?= $employee['employee_id'] ?>'
                }
            },

            "columns": [{
                    "data": "sno"
                },
                {
                    "data": "type"
                },
                {
                    "data": "doc"
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

    function delete_employee_life_cycle_doc(id) {
        var table = $(event.target).closest('table');
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this data!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: true
        }, function() {

            $.ajax({
                type: 'POST',
                url: '<?= base_url('employee/delete_employee_life_cycle_doc_post') ?>',
                cache: false,
                async: false,
                data: {
                    id: id
                },
                dataType: "html",
                success: function(response) {
                    var obj = $.parseJSON(response);
                    if (obj.status == 1) {
                        toaster('success', obj.msg);
                    } else {
                        toaster('error', obj.msg);
                    }
                    table.DataTable().ajax.reload(null, false);
                },
                error: function(error) {
                    toaster('error', error);
                }
            });
        });
    }
</script>