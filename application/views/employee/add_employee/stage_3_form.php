<form action="" name="stage_3_form" id="stage_3_form" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label>Proof of Resignation<sup>*</sup><span data-html="true" data-placement="top" data-toggle="popover" data-content="Upload Resignation Letter/Email in .pdf or .jpeg or .jpg or .png Format"><i class="icon-info"></i></span></label>
                <input type="file" class="form-control form-control-sm" name="resignation_proof[]" id="resignation_proof" value="" multiple>

            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label>Authorization Letter<sup>*</sup><span data-html="true" data-placement="top" data-toggle="popover" data-content="Download Authorization Letter. Fill it appropriately, sign it and upload it, in .pdf Format."><i class="icon-info"></i></span><a href='<?php echo base_url() ?>download/authorization_letter'><img src="<?php echo base_url() ?>assets/downloads/download.png" width="20px" height="20px" target="_blank"></a></label>
                <input type="file" class="form-control form-control-sm" name="authorization_letter[]" id="authorization_letter" value="" multiple>
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label>Proof of Acceptance of Resignation<sup>*</sup><span data-html="true" data-placement="top" data-toggle="popover" data-content="Upload Acceptance of Resignation Letter/Email in .pdf Format"><i class="icon-info"></i></span></label>
                <input type="file" class="form-control form-control-sm" name="resignation_accept_proof[]" id="resignation_accept_proof" value="" multiple>

            </div>
        </div>


    </div>
</form>
<script type="text/javascript">
    $(function() {
        $("#update_resignation").validate({
            rules: {
                'resignation_proof[]': {
                    required: true,
                    extension: "png|jpg|jpeg|pdf",
                    maxupload: 3,
                    maxfilesize: 2
                },
                'resignation_accept_proof[]': {
                    required: true,
                    extension: "png|jpg|jpeg|pdf",
                    maxupload: 3,
                    maxfilesize: 2
                },
                'authorization_letter[]': {
                    required: true,
                    extension: "pdf",
                    maxupload: 3,
                    maxfilesize: 2
                }
            },
            messages: {},
            submitHandler: function(form, e) {
                e.preventDefault();
                $('.btnsmt3_1').prop('disabled', true).attr('value', 'Processing...');
                var form_data = new FormData(form);

                $('.page-loader-wrapper').removeAttr('style');

                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'user/update_resignation_docs',
                        cache: false,
                        async: false,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {

                            //console.log(response);
                            var obj = $.parseJSON(response);
                            if (obj.status == 1) {
                                $('.page-loader-wrapper').fadeOut();
                                showUpdateMessage();
                                $('.btnsmt3_1').prop('disabled', false).attr('value', 'Submit');
                            } else {
                                $('.page-loader-wrapper').fadeOut();
                                toaster('error', obj.msg);
                                $('.btnsmt3_1').prop('disabled', false).attr('value', 'Submit');
                            }

                        },
                        error: function(error) {
                            $('.page-loader-wrapper').fadeOut();
                            toaster('error', error);
                            $('.btnsmt3_1').prop('disabled', false).attr('value', 'Submit');
                        }
                    });
                }, 500);
                return false;
            }

        });
    });
</script>