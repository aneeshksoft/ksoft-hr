<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <form class="form-auth-small" action="" name="additional_leave" id="additional_leave" method="POST" enctype="multipart/form-data">
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
                                <label>Leave type<sup>*</sup></label>
                                <select class="form-control show-tick" name="leave_type_id" id="leave_type_id">
                                    <option value="">Select leave type</option>
                                    <?php foreach($leave_types as $row) { ?>
                                    <option value="<?= $row['leave_type_id']?>"><?= $row['title']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label>Leave Count<sup>*</sup></label>
                            <input type="text" class="form-control" name="allotted_leave">
                        </div>

                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Reason</label>
                                <textarea class="form-control" name="remarks" rows="4" cols="30"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="mt-4">
                                <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />
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

    $('#dateRangePicker').datepicker({
        beforeShowDay: function(date) {
            var day = date.getDay();
            return {
                enabled: true
            };
        }
    });

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

    $("#additional_leave").validate({
        rules: {
            allotted_leave: {
                required: true,
                digits: true
            },
            leave_type_id: {
                required:true
            }
        },
        messages: {

        },
        submitHandler: function(form, e) {
            e.preventDefault();
            $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');

            var form_data = new FormData(form);

            var employee_id = $('#employee_id').val();
            var allotted_leave = $('input[name="allotted_leave"]').val();

            if (employee_id != "" && allotted_leave != "") {

                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'leave/additional_leave_process',
                        cache: false,
                        async: false,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            var obj = $.parseJSON(response);
                            if (obj.status == 1) {
                                toaster('success', obj.msg);
                                $('#additional_leave')[0].reset();
                                $('#employee_name').val(null).trigger('change');
                                $('#employee_id').val("");
                                $('.btnsmt').prop('disabled', false).attr('value', 'Submit');

                            } else {
                                toaster('error', obj.msg);
                                $('.btnsmt').prop('disabled', false).attr('value', 'Submit');
                            }
                        },
                        error: function(error) {
                            toaster('error', error);
                            $('.btnsmt').prop('disabled', false).attr('value', 'Submit');
                        }
                    });
                }, 500);
            } else {
                toaster('error', 'Please enter all required details!');
                $('.btnsmt').prop('disabled', false).attr('value', 'Submit');
            }
            return false;
        }
    });

});
</script>