<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<div class="row clearfix">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <form class="form-auth-small" action="" name="create_holiday" id="create_holiday" method="POST" enctype="multipart/form-data">
                <div class="row clearfix">

                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label>Title<sup>*</sup></label>
                            <input type="text" class="form-control" name="title" />
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label>Holiday Date<sup>*</sup></label>
                            <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="holiday_date" class="form-control" onkeydown="return false;">
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label>Highlight<sup>*</sup></label>
                            <select class="form-control show-tick" name="color">
                                <option value="red">Red</option>
                                <option value="green">Green</option>
                                <option value="blue">Blue</option>
                                <option value="orange">Orange</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 text-right">

                        <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Save" />

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url()?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
$("#create_holiday").validate({
    rules: {
        title: {
            required: true
        },
        holiday_date: {
            required: true
        },        
        color: {
            required: true
        }
    },
    messages: {
        title: {
            required: "Please enter title"
        },
        holiday_date: {
            required: "Please choose holiday date"
        },       
        color: {
            required: "Please choose highlight color"
        }
    },
    submitHandler: function(form, e) {

        e.preventDefault();

        $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
        var form_data = new FormData(form);
        setTimeout(function() {
            $.ajax({
                type: 'POST',
                url: base_url + 'leave/create_holiday_process',
                cache: false,
                async: false,
                data: form_data,
                contentType: false,
                processData: false,
                success: function(response) {

                    var obj = $.parseJSON(response);
                    if (obj.status == 1) {
                        toaster('success', obj.msg);
                        if ($("#calendar").length !== 0) {
                            calendar.refetchEvents();
                        }
                        $('#modal_ajax').modal('toggle');
                    } else {
                        toaster('error', obj.msg);
                        $('.btnsmt').prop('disabled', false).attr('value', 'Approve');
                    }

                },
                error: function(error) {
                    toaster('error', error);
                    $('.btnsmt').prop('disabled', false).attr('value', 'Approve');
                }

            });
        }, 500);

        return false;
    }
});
</script>