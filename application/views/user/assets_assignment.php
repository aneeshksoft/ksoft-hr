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
                                <label>Assets<sup>*</sup></label>
                                <select class="form-control show-tick" name="asset_id">
                                    <option value="">Select asset</option>
                                    <?php foreach($assets as $row) { ?>
                                    <option value="<?= $row['asset_id']?>"><?= $row['name']?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <a href="javascript:;" onclick="addMoreAssets()" data-toggle="tooltip" data-placement="left" title="Add more details" class="header-dropdown" style="position: absolute;right: 11px;z-index: 9999;top: -5px;"><i class="icon-plus"></i></a>

                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Title<sup>*</sup></label>
                                        <input type="text" class="form-control" name="title[0]">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Description<sup>*</sup></label>
                                        <input type="text" class="form-control" name="description[0]">
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

    $("#assets_assignment").validate({
        rules: {
            
            asset_id: {
                required: true
            },
            "title[0]": {
                required: true
            },
            "description[0]": {
                required: true
            }
        },
        messages: {
            
            asset_id: "Please choose asset",
            "title[0]": "Please enter title",
            "description[0]": "Please enter description"
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');

            var form_data = new FormData(form);

            setTimeout(function() {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'user/assets_assignment_process',
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
                            $('input[name*=title]').val("");
                            $('input[name*=description]').val("");
                            $('select[name="asset_id"]').val("");
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

function addMoreAssets() {

    var count = $('body .ajaxAssetsCount').val();

    $.ajax({
        type: 'POST',
        url: base_url + 'user/ajax_assets',
        cache: false,
        async: false,
        data: "count=" + count,
        dataType: "html",
        success: function(response) {
            $('.ajaxAssets').append(response);
            var newcount = (parseInt(count) + 1);
            $('body .ajaxAssetsCount').val(newcount);
            $('#assets_assignment input[name*=title]').each(function() {
                $(this).rules('add', {
                    required: true,
                    messages: {
                        required: "Please enter title",
                    }
                })
            });

            $('#assets_assignment input[name*=description]').each(function() {
                $(this).rules('add', {
                    required: true,
                    messages: {
                        required: "Please enter description",
                    }
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
    $('body #d' + id).remove();
});
</script>