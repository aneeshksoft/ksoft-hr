<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
<style>
    .table.table-custom.table thead th, .table tbody tr td {
        text-align:center;
    }
    .table.table-custom.table thead th:first-child, .table tbody tr td:first-child {
        text-align:left;
    }
    
    </style>
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
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>From<sup>*</sup></label>
                                <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="from_date" id="from_date" class="form-control" value="<?= date('d/m/Y') ?>">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>To<sup>*</sup></label>
                                <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="to_date" id="to_date" class="form-control" value="<?= date('d/m/Y') ?>">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="mt-4">
                                <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />
                                        <input type="hidden" name="employee_id" id="employee_id" value="">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-12">
        <div class="card">

            <div class="body">
                <div class="table-responsive">
                    <table class="table table-hover dataTable table-custom table-striped m-b-0 c_list" id="leave_report_list">
                        <thead class="thead-dark">
                            <tr>
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
var table;
$(document).ready(function() {

    leave_report_list();

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

initializeDataTable([], []);
    $("#leave_report").validate({
        rules: {
            from_date:{
                required:true
            },
            to_date:{
                required:true
            }
        },
        messages: {},
        submitHandler: function(form, e) {

            e.preventDefault();

            leave_report_list();

            return false;
        }
    });

});

function initializeDataTable(columns, data) {
    table = $('#leave_report_list').DataTable({
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
        "columns": columns,
        "data": data,
        "order": [],
        "ordering": false,
        "paging": false
    });
}

function leave_report_list() {
    $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');

    var employee_id = $('#employee_id').val();
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();

    setTimeout(function() {
        $.ajax({
            type: 'POST',
            url: base_url + "leave/leave_report_list_ajax",
            cache: false,
            async: false,
            data: "employee_id=" + employee_id+"&from_date=" + from_date+"&to_date=" + to_date,
            success: function(response) {
                var obj = $.parseJSON(response);
                table.destroy();
                initializeDataTable(obj.columns, obj.data);               
                $('.btnsmt').prop('disabled', false).attr('value', 'Submit');
            },
            error: function(error) {
                toaster('error', error);
                $('.btnsmt').prop('disabled', false).attr('value', 'Submit');
            }
        });
    }, 500);
}
</script>