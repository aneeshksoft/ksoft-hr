<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<style>
    .error-text {
        color: red;
    }
</style>
<style>
    .loader {
        display: none;
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: rgba(0, 0, 0, .8) url('img/loader.gif') 50% 50% no-repeat;
    }

    body.loading {
        overflow: hidden;
    }


    body.loading .loader {
        display: block;
    }

    table#mrftable {
        empty-cells: show;
        border-collapse: collapse;
    }

    table#mrftable td,
    table#mrftable th {
        border: 1px solid black;
        padding: 5px;
    }

    table#irftable {
        empty-cells: show;
        border-collapse: collapse;
    }

    table#irftable td,
    table#irftable th {
        border: 1px solid black;
        padding: 5px;
    }
</style>

<?php

$show_approve_reject_button = true;
$approve_button_text = "Submit";
$relieving_letter_text=($resignation->is_absconding==1)?"Termination Letter":"Relieving Letter";
$show_approve_form=false;
$show_relieving_date=false;
$show_ff_document=false;
$show_relieving_letter=false;
$show_clearance_letter=false;
//echo $resignation->status;
$role=$this->session->userdata('type');
if($resignation->status!=16)
{
    $show_approve_form=true;
}
if(empty($resignation->agreed_relieving_date) && $employee_details->reporting_manager_id==$this->session->userdata('employee_id'))
{
    $show_relieving_date=true;
}
if(in_array($resignation->status,array(12)) && $role==1)
{
    $show_ff_document=true;
    $show_relieving_letter=true;
    $show_clearance_letter=true;
}
if(!$show_ff_document && !$show_relieving_letter && !$show_clearance_letter && !$show_relieving_date)
{
    $show_approve_reject_button=false;
    $show_approve_form=false;
}
//echo json_encode($employee_details);


?>


<table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" class="mt-3">
    <tr>
        <td align="center" width="80%">
            <table border="0" cellpadding="0" cellspacing="0" align="left" width="98%" id="mrftable">
                <tr>
                    <td height="40px" align="left" style="padding-left:10px;color:grey" width="20%" valign="top"><b>Employee Name: </b></td>
                    <td align="left" style="padding-left:10px" width="20%">
                        <label><?= (!empty($resignation->employee_name)) ? ($resignation->employee_name) : ''; ?></label>

                    </td>
                    <td height="40px" align="left" style="padding-left:10px;color:grey" width="20%" valign="top"><b>Relieving Date: </b></td>
                    <td align="left" style="padding-left:10px" width="30%">
                        <label><?= (!empty($resignation->relieving_date)) ? date('d-m-Y', strtotime($resignation->relieving_date)) : ''; ?></label>
                    </td>
                </tr>
                <tr>
                    <td height="40px" align="left" style="padding-left:10px;color:grey" width="20%" valign="top"><b>Reason: </b></td>
                    <td align="left" style="padding-left:10px" width="20%">
                        <label><?= (!empty($resignation->reason)) ? ($resignation->reason) : ''; ?></label>
                    </td>
                    <td height="40px" align="left" style="padding-left:10px;color:grey" width="20%" valign="top"><b>Status: </b></td>
                    <td align="left" style="padding-left:10px" width="30%">
                        <label><?= (!empty($resignation->status)) ? resignation_status_format($resignation->status) : ''; ?></label>
                    </td>
                </tr>

                <tr>
                    <td height="40px" align="left" style="padding-left:10px;color:grey" width="20%" valign="top"><b>Comment: </b></td>
                    <td align="left" style="padding-left:10px" width="20%">
                        <label><?= (!empty($resignation->comment)) ? $resignation->comment : '-'; ?></label>
                    </td>
                    <td height="40px" align="left" style="padding-left:10px;color:grey" width="20%" valign="top"><b>Is Absconding: </b></td>
                    <td align="left" style="padding-left:10px" width="30%">
                        <label><?= (!empty($resignation->is_absconding)) ? 'Yes' : 'No'; ?></label>
                    </td>
                </tr>
                <tr>
                    <td height="40px" align="left" style="padding-left:10px;color:grey" width="20%" valign="top"><b>Projected Relieving Date: </b></td>
                    <td align="left" style="padding-left:10px" width="20%">
                        <label><?= (!empty($resignation->projected_relieving_date)) ? date('d-m-Y', strtotime($resignation->projected_relieving_date)) : ''; ?></label>
                    </td>
                    <td height="40px" align="left" style="padding-left:10px;color:grey" width="20%" valign="top"><b>Agreed Relieving Date: </b></td>
                    <td align="left" style="padding-left:10px" width="30%">
                        <label><?= (!empty($resignation->agreed_relieving_date)) ? date('d-m-Y', strtotime($resignation->agreed_relieving_date)) : '-'; ?></label>
                    </td>
                </tr>
                <tr >
                    <td height="40px" align="left" style="padding-left:10px;color:grey" width="20%" valign="top"><b>F&F Document </b></td>
                    <td align="left" style="padding-left:10px" width="20%">
                    <?php if(empty($resignation->ff_document)){ ?>-
                    <?php }else { ?>
                        <a target="_blank" class=" btn-link" href="<?= $resignation->ff_document ?>">View</a>
                        <?php } ?>
                    </td>
                    <td height="40px" align="left" style="padding-left:10px;color:grey" width="20%" valign="top"><b><?="Signed copy of ".$relieving_letter_text?></b></td>
                    <td align="left" style="padding-left:10px" width="20%">
                    <?php if(empty($resignation->relieving_letter)){ ?>-
                    <?php }else { ?>
                        <a target="_blank" class=" btn-link" href="<?= $resignation->relieving_letter ?>">View</a>
                        <?php } ?>
                    </td>
                </tr>
                <tr >
                <td height="40px" align="left" style="padding-left:10px;color:grey" width="20%" valign="top"><b>Signed copy of Clearance Letter</b></td>
                    <td align="left" style="padding-left:10px" width="20%">
                    <?php if(empty($resignation->clearance_letter)){ ?>-
                    <?php }else { ?>
                        <a target="_blank" class=" btn-link" href="<?= $resignation->clearance_letter ?>">View</a>
                        <?php } ?>
                    </td>
                    <td></td>
                    <td></td>
                </tr>

            </table>

        </td>
    </tr>
</table>
<form class="form-auth-small" action="" name="resignation_form" id="resignation_form" method="POST" enctype="multipart/form-data" <?= ($show_approve_form) ? '' : 'hidden' ?>>
    <div class="card" >
        <div class="body">
            <input class="mt-2" type="hidden" name="id" value="<?= $resignation->id  ?>" />
            <input class="mt-2" type="hidden" name="employee_id" value="<?= $resignation->employee_id  ?>" />
            <div class="row clearfix">
                <div class="col-md-3 col-sm-3" <?= ($show_relieving_date) ? '' : 'hidden' ?>>
                    <div class="form-group">
                        <label>Agreed Relieving Date<sup>*</sup></label>
                        <input type="text" data-date-start-date="today" data-provide="datepicker" data-date-format="dd-mm-yyyy" data-date-autoclose="true" name="agreed_relieving_date" class="form-control">
                    </div>
                </div>
                <div class="col-md-3 col-sm-3" <?= ($show_ff_document) ? '' : 'hidden' ?>>
                    <div class="form-group">
                        <label>F&F Document<sup>*</sup></label>
                        <input type="file" name="ff_document" class="form-control">
                    </div>
                </div>
                <div class="col-md-3 col-sm-3" <?= ($show_relieving_letter) ? '' : 'hidden' ?>>
                    <div class="form-group">
                        <label><?="Signed copy of ".$relieving_letter_text?><sup>*</sup></label>
                        <input type="file" name="relieving_letter" class="form-control">
                    </div>
                </div>
                <div class="col-md-3 col-sm-3" <?= ($show_clearance_letter) ? '' : 'hidden' ?>>
                    <div class="form-group">
                        <label>Signed copy of Clearance Letter<sup>*</sup></label>
                        <input type="file" name="clearance_letter" class="form-control">
                    </div>
                </div>

                <div class="col-sm-3 text-center mt-3" <?= ($show_approve_reject_button) ? '' : 'hidden' ?>>
                <button type="submit" class="btn btn-lg btn-primary btnsmt"><?= $approve_button_text ?></button>
                </div>


                <div class="row clearfix" hidden>
                    <div class="col-sm-3">
                        <div class="mt-4">
                            <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Approve" />
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
</form>


<script src="<?= base_url() ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>

<script type="text/javascript">
    $(function() {
        $("#resignation_form").validate({
            ignore: ':hidden:not("#multiselect3-all")',
            errorPlacement: function(error, element) {
                error.insertAfter(element);

            },
            rules: {

                agreed_relieving_date: {
                    required: true
                },
                ff_document: {
                    required: true
                },
                relieving_letter: {
                    required: true
                },
                clearance_letter: {
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
                        url: base_url + 'resignation/update_other_resignation_details_process',
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
                                window.location.href = base_url + 'view-resignation';
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



    $(document).ready(function() {
        //initial_validation();
    });
</script>