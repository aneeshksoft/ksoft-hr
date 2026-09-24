<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/dropify/css/dropify.min.css">
<div class="row">
    <div class="col-sm-12">

        <div class="card">
            <div class="card-body">
                <form action="" name="add_employee_life_cycle_doc" id="add_employee_life_cycle_doc" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Type<sup>*</sup></label>
                                <select class="form-control form-control-sm" name="type" id="type">
                                    <option value="">Select</option>
                                    <option value="1">Appreciation Letters</option>
                                    <option value="2">Reprimanding Letters</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Doc(s)<sup>*</sup></label>
                                <input type="file" name="docs[]" id="docs" class="dropify form-control" multiple>
                            </div>
                        </div>
                        <div class="col-sm-12 text-right">
                            <div class="mt-4">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-danger" onclick="showAjaxModal('<?= base_url('employee/view_employee_life_cycle_docs/' . $employee['employee_id']) ?>','Employee Life Cycle Documents','modal-xl')">Cancel</button>

                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script src="<?= base_url() ?>assets/vendor/dropify/js/dropify.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('.dropify').dropify({
            messages: {
                'remove': 'Remove',
                'error': ''
            },
            error: {
                'fileSize': 'The file size is too big ({{ value }} max).',
                'fileExtension': 'Invalid file type. Only {{ value }} are allowed.'
            }

        });

        $("#add_employee_life_cycle_doc").validate({
            rules: {
                "docs[]": {
                    required: true,
                }
            },
            messages: {
                "docs[]": {
                    required: "Doc(s) is required"
                }
            },
            errorPlacement: function(error, element) {
                if (element.hasClass('select2') && element.next('.select2-container').length) {
                    error.insertAfter(element.next('.select2-container')).addClass('d-block');
                } else {
                    element.closest('.form-group').append(error);
                }
            },
            submitHandler: function(form, e) {
                e.preventDefault();
                $(form).find(':submit').prop('disabled', true).text('Processing...')
                var form_data = new FormData(form);
                form_data.append('employee_id', '<?= $employee['employee_id'] ?>');
                // console.log(form_data)
                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url('employee/add_employee_life_cycle_doc_post') ?>',
                        cache: false,
                        async: false,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            var obj = $.parseJSON(response);
                            if (obj.status == 1) {

                                showAjaxModal('<?= base_url('employee/view_employee_life_cycle_docs/' . $employee['employee_id']) ?>', 'Employee Life Cycle Documents', 'modal-xl');

                                toaster('success', obj.msg);
                            } else {
                                toaster('error', obj.msg);
                                $(form).find(':submit').prop('disabled', false).text('Submit');
                            }

                        },
                        error: function(error) {
                            toaster('error', error);
                            $(form).find(':submit').prop('disabled', false).text('Submit');
                        }
                    });

                }, 500);
                return false;
            }


        });
    });
</script>