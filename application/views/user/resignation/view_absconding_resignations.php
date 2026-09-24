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
                            <th>Employee Code</th>
                            <th>Absconding Date</th>
                            <th>Comment</th>
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
                url: base_url + "resignation/get_all_absconding_resignations_ajax",
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
                    "data": "employee_code"
                },
                {
                    "data": "absconding_date"
                },
                {
                    "data": "comment"
                },
                {
                    "data": "status"
                },
                {
                    "data": "action"
                }
            ],
            "order": [
                [1, 'asc']
            ]
        });

    });


    function cancelResignation(resignation_id) {
        swal({
            title: "Are you sure?",
            text: "Do you want to cancel this?",
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
                        url: base_url + 'resignation/update_absconding_resignation_status',
                        cache: false,
                        async: false,
                        data: "resignation_id=" + resignation_id+"&status=5",
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

    function approveResignation(resignation_id) {
        swal({
            title: "Are you sure?",
            text: "Do you want to approve this and send mail to the employee?",
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
                        url: base_url + 'resignation/update_absconding_resignation_status',
                        cache: false,
                        async: false,
                        data: "resignation_id=" + resignation_id+"&status=2",
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

    function statrtResignationProcess(resignation_id) 
    {

        swal({
            title: "Are you sure?",
            text: "Are  you want to start the main resignation process?",
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
                        url: base_url + 'resignation/update_absconding_resignation_status',
                        cache: false,
                        async: false,
                        data: "resignation_id=" + resignation_id+"&status=4",
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

    function sendAbscondingLetter(resignation_id) 
    {

        swal({
            title: "Send mail?",
            text: "Send absconding mail?",
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
                        url: base_url + 'resignation/send_absconding_letter',
                        cache: false,
                        async: false,
                        data: "resignation_id=" + resignation_id+"&status=3",
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

    //send_absconding_letter($absconding_resignation_id)
</script>