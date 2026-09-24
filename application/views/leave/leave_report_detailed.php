<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">

<div class="row clearfix">
<div class="col-lg-12 col-md-12 col-sm-12">
        <form class="form-auth-small" action="" name="leave_report" id="leave_report" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="body">
                    <div class="row clearfix">                        
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Employee Name</label>
                                <a href="javascript:;" id="clearButton" class="pull-right mt-1">Clear Selection</a>
                                <select class="form-control" name="employee_name" id="employee_name" tabindex="0">
                                    <option value="">Search here...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-3">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status" id="status" tabindex="0">
                                    <option value="all">All</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label>From<sup>*</sup></label>
                                <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="from_date" class="form-control" value="<?=date('01/m/Y')?>">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label>To<sup>*</sup></label>
                                <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="to_date" class="form-control" value="<?=date("t/m/Y", strtotime(date('Y-m-d')))?>">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="mt-4">
                                <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Get Report" />
                                <input type="hidden" name="employee_id" id="employee_id" value="">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">            
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover dataTable table-custom table-striped m-b-0 no-alignment c_list" id="leave_report_detailed_list">
                        <thead class="thead-dark">
                            <tr>                          
                                <th>Employee</th>
                                <!-- <th>Code</th> -->
                                <th>Leave type</th>
                                <th>Day type</th>
                                <th>FN/AN</th>
                                <th>Leave Date</th>
                                <th>Status</th>
                                <th>Remarks</th>
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
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
<script type="text/javascript">
var table, table1;
$(function() {

    $('body #employee_name').select2({
        ajax: {
            url: base_url + 'leave/get_employee',
            dataType: 'json',
            delay: 250,
            method: 'post',
            data: function(params) {
                return {
                    query: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: data

                };
            },
            cache: true
        },
        minimumInputLength: 1,
        tags: false,
        escapeMarkup: function(markup) {
            return markup;
        }
    }).on('change', function(e) {
        var getID = $(this).select2('data');
        $('#employee_id').val(getID[0]['id']);
    });

    $('#clearButton').click(function() {
        $('#employee_name').val(null).trigger('change');
        $('#employee_id').val("");
    });

    table = $('#leave_report_detailed_list').DataTable({
        //dom: 'lfrtip',
        dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [{
            extend: 'excelHtml5',
            exportOptions: {
                stripHtml: false,
                format: {
                    body: function(data, column, row) {
                        return data.replace(/<.*?>/ig, "");
                    }
                }
            }
        }],
        "pageLength": 10, // Set the default page length
        "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ], // Options for per page count
        "paging": true,
        "columnDefs": [{
            className: "text-nowrap",
            "targets": [4]
        }],
        "columns": [{
                "data": "name"
            },
            // {
            //     "data": "code"
            // },
            {
                "data": "leave_type"
            },
            {
                "data": "day_type"
            },
            {
                "data": "fn_an"
            },
            {
                "data": "leave_date"
            },
            {
                "data": "status"
            },
            {
                "data": "remarks"
            }
        ],
        "ordering": false
    });

    $('input[name="from_date"],input[name="to_date"]').on('input', function(e) {
        table.clear().draw();
    });

    $("#leave_report").validate({
        rules: {

            from_date: {
                required: true
            },
            to_date: {
                required: true
            }
        },
        messages: {

            from_date: {
                required: "Please choose from date"
            },
            to_date: {
                required: "Please choose to date"
            }
        },
        submitHandler: function(form, e) {

            e.preventDefault();
            var form_data = new FormData(form);

            leave_history(form_data);

            return false;
        }
    });

});

function leave_history(form_data) {
    $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
    setTimeout(function() {
        $.ajax({
            type: 'POST',
            url: base_url + 'leave/leave_report_detailed_list_ajax',
            cache: false,
            async: false,
            data: form_data,
            contentType: false,
            processData: false,
            success: function(response) {
                var obj = $.parseJSON(response);
                table.clear();
                table.rows.add(obj).draw();
                $('.btnsmt').prop('disabled', false).attr('value', 'Get Report');
            },
            error: function(error) {
                toaster('error', error);
                $('.btnsmt').prop('disabled', false).attr('value', 'Get Report');
            }
        });
    }, 500);
}
</script>