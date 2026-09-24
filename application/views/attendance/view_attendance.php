<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <form class="form-auth-small" action="" name="attendance_report" id="attendance_report" method="POST" enctype="multipart/form-data">
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
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>From<sup>*</sup></label>
                                <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="from_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>To<sup>*</sup></label>
                                <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="to_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="mt-4">
                                <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Get Report" />
                                <input type="reset" class="btn btn-lg btn-danger" value="Cancel">
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
            <div class="header">
                <h2>Attendance History</h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover dataTable table-custom table-striped m-b-0 no-alignment c_list" id="attendance_report_list">
                        <thead class="thead-dark">
                            <tr>                          
                                <th>Employee</th>
                                <th>Code</th>
                                <th>Attendance Date</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Duration</th>
                                <th>Leave Status</th>
                                <th>Shift</th>
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

    table = $('#attendance_report_list').DataTable({
        dom: 'Bfrtip',
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
        "columnDefs": [{
            className: "text-nowrap",
            "targets": [1]
        }],
        "columns": [{
                "data": "name"
            },
            {
                "data": "code"
            },
            {
                "data": "attendance_date"
            },
            {
                "data": "in_time"
            },
            {
                "data": "out_time"
            },
            {
                "data": "duration"
            },
            {
                "data": "leave_status"
            },
            {
                "data": "shift"
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

    $("#attendance_report").validate({
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
        url: base_url + 'attendance/attendance_history_list',
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
},500);
}
</script>