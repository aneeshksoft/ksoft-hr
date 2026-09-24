<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<?php ?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <form class="form-auth-small" action="" name="resignation_form" id="resignation_form" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="body">
                    <div class="row clearfix">

                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Employee<sup>*</sup></label>
                                <select class="form-control show-tick" name="employee_id">
                                    <option value="">Select Employee</option>
                                    <?php foreach ($employees as $row) { ?>
                                        <option value="<?= $row['employee_id'] ?>"><?= $row['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-3 col-sm-3">
                            <div class="form-group">
                                <label>Date<sup>*</sup></label>
                                <input readonly data-date-start-date="today" type="text" data-provide="datepicker" data-date-format="dd-mm-yyyy" data-date-autoclose="true" name="absconding_date" class="form-control" value="<?= date('d-m-Y') ?>" />
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label>Comment</label>
                                <input type="text" class="form-control" name="comment"></input>
                            </div>
                        </div>

                    </div>

                    <div class="row clearfix">
                        <div class="col-sm-12">
                            <div class="mt-4">
                                <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />
                                <input type="reset" class="btn btn-lg btn-danger" value="Reset">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?= base_url() ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script src="<?= base_url() ?>assets/vendor/dropify/js/dropify.min.js"></script>
<script src="<?= base_url() ?>assets/user/js/pages/forms/dropify.js"></script>
<script type="text/javascript">
    $(function() 
    {

        $("#resignation_form").validate({
            ignore: ':hidden',
            errorPlacement: function(error, element) {
                if (element.attr("name") == "role_id[]")
                    error.insertAfter(".btn-group");
                else
                    error.insertAfter(element);
            },
            rules: {

                employee_id: {
                    required: true
                },
                absconding_date: {
                    required: true
                },


            },

            submitHandler: function(form, e) {
                e.preventDefault();
                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');

                var form_data = new FormData(form);

                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'resignation/add_absconding_resignation_process',
                        cache: false,
                        async: false,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {

                            var obj = $.parseJSON(response);
                            if (obj.status == 1) {
                                toaster('success', obj.msg);
                                $('.btnsmt').prop('disabled', false).attr('value', 'Submit');
                                window.location.href = base_url + 'view-absconding-resignation';
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
                return false;
            }
        });

    });
</script>