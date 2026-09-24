<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">

<div class="row">
    <div class="col-sm-12">

        <div class="card">
            <div class="card-header bg-white">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <strong class="h6">Add Work Location</strong>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="" name="add_work_location" id="add_work_location" method="POST" enctype="multipart/form-data">
                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="name">Name<sup>*</sup></label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                        </div>
                        <div class="col-sm-2 mt-1">
                            <div class="mt-4">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>


<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header bg-white">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <strong class="h6">Work Locations</strong>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-custom" id="work_locations_table">
                                <thead class=" thead-dark">
                                    <tr>
                                        <th width="1%">#</th>
                                        <th>Name</th>
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
<script src="<?= base_url() ?>assets/user/js/pages/tables/jquery-datatable.js"></script>

<script>
    $(document).ready(function() {
        $("#add_work_location").validate({
            rules: {
                name: {
                    required: true
                },
            },
            messages: {
                name: {
                    required: "Name is required"
                },
            },
            errorPlacement: function(error, element) {
                element.closest('.form-group').append(error);
            },
            submitHandler: function(form, e) {
                e.preventDefault();
                $(form).find(':submit').prop('disabled', true).text('Processing...')
                var form_data = new FormData(form);
                // console.log(form_data)
                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url('work_location/add_work_location_post') ?>',
                        cache: false,
                        async: false,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            var obj = $.parseJSON(response);
                            if (obj.status == 1) {
                                toaster('success', obj.msg);
                                $(form).trigger("reset");
                                work_locations_table.ajax.reload();
                                $(form).find(':submit').prop('disabled', false).text('Submit');
                            } else {
                                toaster('error', obj.msg);
                                $(form).find(':submit').prop('disabled', false).text('Submit');
                            }

                        },
                        error: function(error) {
                            toaster('error', error);
                            $(form).find(':submit').prop('disabled', false).text('Submit');
                        }
                    });

                }, 500);
                return false;
            }

        });

    });
</script>

<script type="text/javascript">
    var work_locations_table;
    $(document).ready(function() {

        work_locations_table = $('#work_locations_table').DataTable({
            "ajax": {
                type: 'POST',
                url: '<?= base_url("work_location/view_work_locations_ajax") ?>'
            },

            "columns": [{
                    "data": "sno"
                },
                {
                    "data": "name"
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

    function delete_work_location(id) {
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
                url: '<?= base_url('work_location/delete_work_location_post') ?>',
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