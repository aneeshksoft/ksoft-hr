<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <form class="form-auth-small" action="" name="assets_assignment" id="assets_assignment" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="body">
                    <div class="row clearfix">

                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Employee Name<sup>*</sup></label>
                                <a href="javascript:;" id="clearButton" class="pull-right mt-1">Clear Selection</a>
                                <select class="form-control" name="employee_name" id="employee_name" tabindex="0">
                                    <option value="">Search here...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Work Location<sup>*</sup></label>
                                <select class="form-control show-tick" name="work_location_id">
                                    <option value="">Select work location</option>
                                    <?php foreach($locations as $row) { ?>
                                    <option value="<?= $row['work_location_id']?>"><?= $row['name']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label>From Date</label>
                                <input type="text" class="form-control" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" name="start_date">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label>To Date</label>
                                <input type="text" class="form-control" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" name="end_date">
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-12">
                            <div class="form-group">
                                <label>Status<sup>*</sup></label>
                                <select class="form-control show-tick" name="status">
                                    <option value="">Select status</option>

                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="mt-4">
                                <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Save" />
                                <input type="reset" class="btn btn-lg btn-danger" value="Cancel">
                                <input type="hidden" name="employee_id" id="employee_id" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?= base_url()?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
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

    $("#assets_assignment").validate({
        rules: {

            work_location_id: {
                required: true
            },

            status: {
                required: true
            }
        },
        messages: {

            work_location_id: "Please choose work location",

            status: "Please choose status"
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');

            var form_data = new FormData(form);

            setTimeout(function() {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'user/work_location_assignment_process',
                    cache: false,
                    async: false,
                    data: form_data,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var obj = $.parseJSON(response);
                        if (obj.status == 1) {
                            toaster('success', obj.msg);
                            $('input[name*=start_date]').val("");
                            $('input[name*=end_date]').val("");
                            $('select[name="work_location_id"]').val("");
                            $('#employee_name').val(null).trigger('change');
                            $('#employee_id').val("");
                            $('.btnsmt').prop('disabled', false).attr('value', 'Save');

                        } else {
                            toaster('error', obj.msg);
                            $('.btnsmt').prop('disabled', false).attr('value', 'Save');
                        }
                    },
                    error: function(error) {
                        toaster('error', error);
                        $('.btnsmt').prop('disabled', false).attr('value', 'Save');
                    }
                });
            }, 500);
            return false;
        }
    });

});
</script>