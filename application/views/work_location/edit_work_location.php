<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <form action="" name="edit_work_location" id="edit_work_location" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="name">Name<sup>*</sup></label>
                                <input type="text" class="form-control" name="name" id="name" value="<?= $work_location['name'] ?>">
                            </div>
                        </div>
                        <div class="col-sm-12 text-right">
                            <div class="mt-4">
                                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        $("#edit_work_location").validate({
            rules: {
                name: {
                    required: true
                },
            },
            messages: {
                name: {
                    required: "Name is required"
                },
            },
            errorPlacement: function(error, element) {
                element.closest('.form-group').append(error);
            },
            submitHandler: function(form, e) {
                e.preventDefault();
                $(form).find(':submit').prop('disabled', true).text('Processing...')
                var form_data = new FormData(form);
                form_data.append('id', '<?= $work_location['work_location_id'] ?>');
                // console.log(form_data)
                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url('work_location/edit_work_location_post') ?>',
                        cache: false,
                        async: false,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            var obj = $.parseJSON(response);
                            if (obj.status == 1) {
                                toaster('success', obj.msg);
                                work_locations_table.ajax.reload();
                                $('#modal_ajax').modal('toggle');
                            } else {
                                toaster('error', obj.msg);
                                $(form).find(':submit').prop('disabled', false).text('Update');
                            }

                        },
                        error: function(error) {
                            toaster('error', error);
                            $(form).find(':submit').prop('disabled', false).text('Update');
                        }
                    });

                }, 500);
                return false;
            }

        });

    })
</script>