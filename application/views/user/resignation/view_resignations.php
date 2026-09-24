<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">

            <div class="body">

                <table class="table table-hover dataTable table-custom table-striped m-b-0 c_list" id="employee_list">
                    <thead class="thead-dark">
                        <tr>
                            <th>Sno</th>
                            <th>Employee Name</th>
                            <th>Employee Email</th>
                            <th>Relieving Date</th>
                            <th>Reason</th>
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
<script src="<?= base_url() ?>assets/user/bundles/datatablescripts.bundle.js"></script>
<script src="<?= base_url() ?>assets/user/js/pages/tables/jquery-datatable.js"></script>
<script src="<?= base_url() ?>assets/user/js/pages/ui/dialogs.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        var table = $('#employee_list').DataTable({
            "ajax": {
                type: 'POST',
                url: base_url + "resignation/get_all_resignations_ajax",
                data: {
                    'status': '<?= 1 ?>'
                }
            },
            "columnDefs": [{
                className: "text-nowrap",
                "targets": [1, 4]
            }],
            "columns": [
                {
                    "data": "sno"
                },
                {
                    "data": "employee_name"
                },
                {
                    "data": "employee_email"
                },
                {
                    "data": "relieving_date"
                },
                {
                    "data": "reason"
                },
                {
                    "data": "status"
                },
                {
                    "data": "action"
                }
            ],
            "order": [
                [0, 'asc']
            ]
        });

    });

    function deleteResignation(resignation_id) {
        swal({
            title: "Are you sure?",
            text: "Do yo want to delete this?",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            confirmButtonText: "Yes",
            closeOnConfirm: false,
            showLoaderOnConfirm: true,

        }, function(reason) {
            if (reason != '') {
                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'delete-resignation',
                        cache: false,
                        async: false,
                        data: "resignation_id=" + resignation_id,
                        dataType: "html",
                        success: function(response) {
                            var obj = $.parseJSON(response);
                            if (obj.status == 1) {
                                toaster('success', obj.msg);
                                window.location.reload();
                                //window.location.href = base_url + 'mrf';
                                //$('#modal_ajax').modal('toggle');
                            } else {
                                toaster('error', obj.msg);
                                setTimeout(() => {
                                    //window.location.reload();
                                }, 1000);
                            }
                        },
                        error: function(error) {
                            toaster('error', error);
                            setTimeout(() => {
                                //window.location.reload();
                            }, 1000);
                        }
                    });

                }, 500);
            }


        });
    }

    function cancelResignation(resignation_id) {
        swal({
            title: "Are you sure?",
            text: "Do yo want to cancel this?",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            confirmButtonText: "Yes",
            closeOnConfirm: false,
            showLoaderOnConfirm: true,

        }, function(reason) {
            if (reason != '') {
                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'resignation/cancel_resignation',
                        cache: false,
                        async: false,
                        data: "resignation_id=" + resignation_id,
                        dataType: "html",
                        success: function(response) {
                            var obj = $.parseJSON(response);
                            if (obj.status == 1) {
                                toaster('success', obj.msg);
                                window.location.reload();
                                //window.location.href = base_url + 'mrf';
                                //$('#modal_ajax').modal('toggle');
                            } else {
                                toaster('error', obj.msg);
                                setTimeout(() => {
                                    //window.location.reload();
                                }, 1000);
                            }
                        },
                        error: function(error) {
                            toaster('error', error);
                            setTimeout(() => {
                                //window.location.reload();
                            }, 1000);
                        }
                    });

                }, 500);
            }


        });
    }
</script>