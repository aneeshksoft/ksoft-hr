<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <form class="form-auth-small" action="" name="add_attendance" id="add_attendance" method="POST" enctype="multipart/form-data">
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
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <a href="javascript:;" onclick="addMoreAssets()" data-toggle="tooltip" data-placement="left" title="Add more details" class="header-dropdown" style="position: absolute;right: 11px;z-index: 9999;top: -5px;"><i class="icon-plus"></i></a>

                            <div class="row">
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>Attendance Date<sup>*</sup></label>
                                        <input type="text" class="form-control" name="attendance_date[0]" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-end-date="today" data-date-autoclose="true">
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-12">
                                    <div class="form-group">
                                        <label>In Time<sup>*</sup></label>
                                        <input type="text" class="form-control" name="in_time[0]" id="intime0" onkeyup="getDifference(0)">
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-12">
                                    <div class="form-group">
                                        <label>Out Time<sup>*</sup></label>
                                        <input type="text" class="form-control" name="out_time[0]" id="outtime0" onkeyup="getDifference(0)">
                                    </div>
                                </div>
                                <div class="col-md-1 col-sm-12">
                                    <div class="form-group">
                                        <label>Duration<sup>*</sup></label>
                                        <input type="text" class="form-control" name="duration[0]" id="dura0">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>Shift</label>                                      
                                        <select name="shift[0]" class="form-control">
                                            <option value="">Select shift</option>
                                            <option value="FS">FS</option>
                                            <option value="SS">SS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>Status<sup>*</sup></label>                                       
                                        <select name="status[0]" class="form-control">
                                            <option value="">Select status</option>
                                            <option value="P/P">P/P</option>
                                            <option value="P/A">P/A</option>
                                            <option value="A/P">A/P</option>
                                            <option value="A/A">A/A</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" class="form-control" name="remarks[0]">
                                    </div>
                                </div>
                            </div>
                            <div class="row ajaxAssets">

                            </div>
                            <input type="hidden" name="ajaxAssets" class="ajaxAssetsCount" value="1">
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

    $("#add_attendance").validate({
        rules: {
            "attendance_date[0]": {
                required: true
            },
            "in_time[0]": {
                required: true,
                time24: true
            },
            "out_time[0]": {
                required: true,
                time24: true
            },
            "duration[0]": {
                required: true,
                time24: true
            },
            "status[0]": {
                required: true
            }
        },
        messages: {

        },
        submitHandler: function(form, e) {
            e.preventDefault();
            $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');

            var form_data = new FormData(form);

            var employee_id = $('#employee_id').val();
            console.log(employee_id);
            if (employee_id != "") {
                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'attendance/add_attendance_process',
                        cache: false,
                        async: false,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            var obj = $.parseJSON(response);
                            if (obj.status == 1) {
                                toaster('success', obj.msg);
                                $('.ajaxAssets').html("");
                                $('#add_attendance')[0].reset();
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
            } else {
                toaster('error', "Please choose employee!");
                $('.btnsmt').prop('disabled', false).attr('value', 'Save');
            }
            return false;
        }
    });

});

function addMoreAssets() {

    var count = $('body .ajaxAssetsCount').val();

    $.ajax({
        type: 'POST',
        url: base_url + 'attendance/ajax_attendance',
        cache: false,
        async: false,
        data: "count=" + count,
        dataType: "html",
        success: function(response) {
            $('.ajaxAssets').append(response);
            var newcount = (parseInt(count) + 1);
            $('body .ajaxAssetsCount').val(newcount);
            $('#add_attendance input[name*=attendance_date]').each(function() {
                $(this).rules('add', {
                    required: true
                })
            });

            $('#add_attendance input[name*=in_time]').each(function() {
                $(this).rules('add', {
                    required: true,
                    time24: true
                })
            });

            $('#add_attendance input[name*=out_time]').each(function() {
                $(this).rules('add', {
                    required: true,
                    time24: true
                })
            });
            $('#add_attendance input[name*=duration]').each(function() {
                $(this).rules('add', {
                    required: true,
                    time24: true
                })
            });
            $('#add_attendance select[name*=status]').each(function() {
                $(this).rules('add', {
                    required: true
                })
            });

        },
        error: function(error) {
            toaster('error', error);
        }
    });
}
$(document).on('click', '.removeP', function() {
    var id = $(this).data("id");
    $('body #t' + id).remove();
    $('body #it' + id).remove();
    $('body #ot' + id).remove();
    $('body #d' + id).remove();
    $('body #s' + id).remove();
    $('body #st' + id).remove();
    $('body #r' + id).remove();
});

function timeDifference(time1, time2) {

    if (!isValidTime(time1) || !isValidTime(time2)) {
        return '';
    }

    if (time1 === time2) {
        return '00:00';
    }

    var time1Parts = time1.split(':');
    var time2Parts = time2.split(':');

    var minutes1 = parseInt(time1Parts[0]) * 60 + parseInt(time1Parts[1]);
    var minutes2 = parseInt(time2Parts[0]) * 60 + parseInt(time2Parts[1]);

    var difference = minutes2 - minutes1;

    if (difference < 0) {
        difference += 1440;
    }

    var hours = Math.floor(difference / 60);
    var remainingMinutes = difference % 60;

    return ('0' + hours).slice(-2) + ':' + ('0' + remainingMinutes).slice(-2);
}

function isValidTime(time) {
    return /^([01][0-9]|2[0-3]):[0-5][0-9]$/.test(time);
}

function getDifference(i) {

    $('#dura' + i).val('');
    var intime = $('#intime' + i).val();
    var outtime = $('#outtime' + i).val();

    if (intime != "" && outtime != "") {
        var timedif = timeDifference(intime, outtime);
        $('#dura' + i).val(timedif);
    }
}
</script>