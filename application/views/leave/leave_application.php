<?php
if(!empty($holidays)) {
    $holidays_js = array_map(function ($holiday) {
        return "'" . $holiday['holiday_date'] . "'";
    }, $holidays);

    $holidays_js_string = '[' . implode(', ', $holidays_js) . ']';
} else {
    $holidays_js_string = "";
}

$role = $this->session->userdata('type');
$employee_id = $this->session->userdata('employee_id');

?>
<style>
.disabled-date {
    background-color: #fff4f4 !important;
}

.datepicker table tr td,
.datepicker table tr th {
    border-radius: 0px !important;
}
</style>
<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<div class="row clearfix">
    <div class="col-md-5 m-auto">
        <form class="form-auth-small" action="" name="leave_application" id="leave_application" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <?php if(is_in_rmlist()) { ?>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Employee Name<sup>*</sup></label>
                                <a href="javascript:;" id="clearButton" class="pull-right mt-1">Clear Selection</a>
                                <select class="form-control" name="employee_name" id="employee_name" tabindex="0">
                                    <option value="">Search here...</option>
                                </select>

                            </div>
                        </div>
                        <?php } else { ?>
                        <script type="text/javascript">
                        $(function() {
                            getAvailability(<?=$employee_id?>);
                        });
                        </script>
                        <?php } ?>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Leave type<sup>*</sup></label>
                                <select class="form-control show-tick" name="leave_type_id" id="leave_type_id" onchange="clearAvailability();changeLeave(this);">
                                    <option value="">Select type</option>
                                    <?php foreach($leave_types as $row) {
                                        ?>
                                    <option value="<?= $row['leave_type_id']?>"><?= $row['title']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Is Half Day<sup>*</sup></label>
                                <select class="form-control show-tick" name="is_halfday" id="is_halfday" onchange="clearAvailability();fnanShow(this);">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 d-none" id="fnan">
                            <div class="form-group">
                                <label>FN/AN<sup>*</sup></label>
                                <select class="form-control show-tick" name="fn_an" id="fn_an" onchange="clearAvailability();">
                                    <option value="FN">FN</option>
                                    <option value="AN">AN</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label>Range<sup>*</sup></label>
                            <div class="form-group">
                                <div class="input-daterange input-group" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" data-date-start-date="<?php echo date('d/m/Y', strtotime('-2 weeks')); ?>" onchange="clearAvailability()" id="dateRangePicker">
                                    <input type="text" class="form-control" name="from_date" id="from_date">
                                    <span class="input-group-addon">&nbsp;&nbsp;to&nbsp;&nbsp;</span>
                                    <input type="text" class="form-control" name="to_date" id="to_date">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Reason</label>
                                <textarea class="form-control" name="remarks" rows="3"></textarea>
                            </div>
                        </div>

                        <!------------------ -->
                        <div class="col-md-12 d-flex">
                            <div class="card card-shadow border-0 mb-4 mr-2 d-none awrap">
                                <div class="card-body p-3" id="availabilityWrapper">

                                </div>
                            </div>
                            <div class="card card-shadow border-0 mb-4 ml-2 d-none ewrap">
                                <div class="card-body p-3" id="eligibilityWrapper">

                                </div>
                            </div>
                        </div>
                        <!-- ---------------- -->

                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="mt-2">
                                <input type="button" class="btn btn-lg btn-warning" onclick="checkLeaveEligibility()" value="Check Eligibility">
                                <input type="submit" class="btn btn-lg btn-primary btnsmt hbtn d-none" value="Apply Now" />
                                <input type="reset" class="btn btn-lg btn-danger hbtn d-none" value="Cancel">
                                <input type="hidden" name="employee_id" id="employee_id" value="<?php echo (!is_in_rmlist()) ? $employee_id : ''?>">
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
var pl_balance = 0;
var sl_balance = 0;
var co_balance = 0;
$(function() {

    $('#dateRangePicker').datepicker({
        todayHighlight: true,
        beforeShowDay: function(date) {
            var day = date.getDay();
            // Disable Saturdays (day 6) and Sundays (day 0)
            if (day == 0) {
                return {
                    enabled: false,
                    classes: 'disabled-date'
                };
            }
            var holidays = "<?php echo $holidays_js_string; ?>";
            if (holidays != "") {
                var arr = JSON.parse(holidays.replace(/'/g, '"'));
                var dateString = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + String(date.getDate()).padStart(2, '0');
                if ($.inArray(dateString, arr) != -1) {
                    return {
                        enabled: false,
                        classes: 'disabled-date'
                    };
                }
            }

            return {
                enabled: true
            };
        }
    });

    $('body #employee_name').select2({
        ajax: {
            url: base_url + 'leave/get_employee_by_head',
            dataType: 'json',
            delay: 250,
            method: 'post',
            data: function(params) {
                return {
                    query: params.term,
                    head_id: <?=$employee_id?>
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
        clearAvailability();
        getAvailability(getID[0]['id']);
    });

    $('#clearButton').click(function() {
        $('#employee_name').val(null).trigger('change');
        $('#employee_id').val("");
        clearAvailability();
        $('#availabilityWrapper').html("");
        $('.awrap').addClass('d-none');
    });

    $("#leave_application").validate({
        rules: {
            leave_type_id: {
                required: true
            },
            from_date: {
                required: true
            },
            to_date: {
                required: true
            },
            is_halfday: {
                required: true
            }
        },
        messages: {
            leave_type_id: "Please choose leave type",
            from_date: "Please choose from date",
            to_date: "Please choose to date",
            is_halfday: "Please choose half day status"
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');

            var form_data = new FormData(form);

            setTimeout(function() {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'leave/leave_application_process',
                    cache: false,
                    async: false,
                    data: form_data,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var obj = $.parseJSON(response);
                        if (obj.status == 1) {

                            toaster('success', obj.msg);
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);

                        } else {
                            toaster('error', obj.msg);
                            $('.btnsmt').prop('disabled', false).attr('value', 'Apply Now');
                        }
                    },
                    error: function(error) {
                        toaster('error', error);
                        $('.btnsmt').prop('disabled', false).attr('value', 'Apply Now');
                    }
                });
            }, 500);
            return false;
        }
    });

});

function checkLeaveEligibility() {

    var employee_id = $('#employee_id').val();
    var leave_type_id = $('#leave_type_id').val();
    var from_date = $('input[name="from_date"]').val();
    var to_date = $('input[name="to_date"]').val();
    var is_halfday = $('#is_halfday').val();
    var fn_an = $('#fn_an').val();

    to_date = (is_halfday == 1) ? from_date : to_date;

    $('#eligibilityWrapper').html("");
    $('.ewrap').addClass('d-none');
    getAvailability(employee_id);
    $('input.hbtn').addClass('d-none');

    if (employee_id != "" && leave_type_id != "" && from_date != "" && to_date != "" && is_halfday != "") {

        setTimeout(function() {
            $.ajax({
                type: 'POST',
                url: base_url + 'leave/checkLeaveEligibility',
                cache: false,
                async: false,
                data: "employee_id=" + employee_id + "&from_date=" + from_date + "&to_date=" + to_date + "&leave_type_id=" + leave_type_id + "&is_halfday=" + is_halfday+ "&fn_an=" + fn_an,
                dataType: "html",
                success: function(response) {
                    var obj = $.parseJSON(response);
                    if (obj.status == 1) {

                        var eligibility = 0;

                        leave_type_id = Number(leave_type_id);

                        if (![1, 3, 4].includes(leave_type_id)) {
                            eligibility = (obj?.result?.status == 1) ? 1 : 0;
                        } else {
                            switch (leave_type_id) {
                                case 1:
                                    eligibility = (pl_balance > 0 && pl_balance >= obj.result.applied_days) ? 1 : 0;
                                    break;
                                case 3:
                                    eligibility = (sl_balance > 0 && sl_balance >= obj.result.applied_days) ? 1 : 0;
                                    break;
                                case 4:
                                    eligibility = (co_balance > 0 && co_balance >= obj.result.applied_days) ? 1 : 0;
                                    break;
                                default:
                                    eligibility = 0;
                                    break;
                            }
                        }

                        var html = '<div><div>Leave from:&nbsp;&nbsp;<strong>' + obj.result.leave_start_date + '</strong></div><div>Leave to:&nbsp;&nbsp;<strong>' + obj.result.leave_end_date + '</strong></div><div>Total days applied:&nbsp;&nbsp;<strong class="badge badge-danger">' + obj.result.applied_days + ' day(s)</strong></div><div>Eligibility:&nbsp;&nbsp;<h6 class="d-inline-block">' + ((eligibility == 1) ? '<span class="text-success">ELIGIBLE</span>' : '<span class="text-danger">NOT ELIGIBLE</span>') + '</h6></div></div>';

                        $('#eligibilityWrapper').html(html);
                        $('.ewrap').removeClass('d-none');

                        if (eligibility == 1) {
                            $('input.hbtn').removeClass('d-none');
                        } else {
                            $('input.hbtn').addClass('d-none');
                        }

                    } else {
                        toaster('error', obj.msg);
                    }
                },
                error: function(error) {
                    toaster('error', obj.msg);
                    $('input.hbtn').addClass('d-none');
                }
            });
        }, 500);
    } else {
        toaster('error', 'Please enter all required details!');
    }

}

function clearAvailability() {
    $('#eligibilityWrapper').html("");
    $('input.hbtn').addClass('d-none');
    $('.ewrap').addClass('d-none');
}

function fnanShow(ths) {
    var s = $(ths).val();
    if (s == 1) {
        $('#fnan').removeClass('d-none');
        $('#to_date').datepicker('setDate', $('#from_date').val()).off('focus').prop('readonly', true);
    } else {
        $('#fnan').addClass('d-none');
        $('#to_date').prop('readonly', false);
        $('#to_date').on('focus', function() {
            $(this).datepicker('show');
        });
    }
}

$('#from_date').on('changeDate', function() {
    if ($('#is_halfday').val()==1) {
        $('#to_date').datepicker('setDate', $('#from_date').val()).off('focus').prop('readonly', true);
    }
});

function changeLeave(ths) {
    var s = $(ths).val();
    if (s == 2) {
        $('#is_halfday').val(0).trigger('change').attr('readonly', true);
        $('#is_halfday option[value="1"]').prop('disabled', true).css('display', 'none');
    } else {
        $('#is_halfday').attr('readonly', false);
        $('#is_halfday option[value="1"]').prop('disabled', false).css('display', 'block');
    }
}

function getAvailability(employee_id) {
    $('#availabilityWrapper').html("");
    $('.awrap').addClass('d-none');
    if (employee_id != "") {
        setTimeout(function() {
            $.ajax({
                type: 'POST',
                url: base_url + 'leave/getAvailability',
                cache: false,
                async: false,
                data: "employee_id=" + employee_id,
                dataType: "html",
                success: function(response) {
                    var obj = $.parseJSON(response);
                    $('.awrap').removeClass('d-none');
                    if (obj.status == 1) {
                        var html = '<div><div>PL available:&nbsp;&nbsp;<strong class="badge badge-success" style="margin-left: 3px !important;">' + (obj.result.pl_balance ?? 0) + ' day(s)</strong></div><div>SL available:&nbsp;&nbsp;<strong class="badge badge-danger" style="margin-left: 3px !important;">' + (obj?.result?.sl_balance ?? 0) + ' day(s)</strong></div><div>CO available:&nbsp;&nbsp;<strong class="badge badge-info">' + (obj?.result?.co ?? 0) + ' day(s)</strong></div></div>';
                        $('#availabilityWrapper').html(html);
                        pl_balance = obj?.result?.pl_balance ?? 0;
                        sl_balance = obj?.result?.sl_balance ?? 0;
                        co_balance = obj?.result?.co ?? 0;
                    } else {
                        $('#availabilityWrapper').html(obj.msg);
                    }
                }
            });
        }, 500);
    } else {
        toaster('error', 'Employee id not available!');
    }
}
</script>