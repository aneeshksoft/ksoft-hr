<?php
$my_id = $this->session->userdata('employee_id');
$details = $this->common_model->imbursement_detail_by_code($param2);
define('CURRENCY',$this->settings->get_settings('currency'));
if(!empty($details)) {
   
?>

<div class="row clearfix">
    <div class="col-md-12 col-sm-12">
        <form class="form-auth-small" action="" name="imbursement_status_process" id="imbursement_status_process" method="POST" enctype="multipart/form-data">
  <?php $i=0; foreach($details as $row) {?>
        <div class="card hover-card mb-2">
                        <div class="header">
                            <h2><?= ucfirst($row['details'])?></h2>
							<div class="row">
								<div class="col-md-4">
                            <div><?= CURRENCY.' '.$row['amount']?></div>
                            <small><?= get_date($row['imbursement_date'])?></small>
								</div>
								<div class="col-md-8">
									<?php if(!empty($row['docs'])) {?>
                                                <div class="align-items-center d-flex">
                                                    <div class="mb-0 mr-2">Documents:</div>
                                                    <ul class="list-unstyled team-info margin-0">
                                                        <?php foreach($row['docs'] as $row1) {?>        
                                                        <li><a href="<?= base_url()?>assets/uploads/user_docs/<?= $row['employee_id'].'/imbursement/'.$row1['doc_path']?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Click to open" ><img src="<?= base_url()?>assets/common/preview.svg" alt="Avatar" /></a></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            <?php } ?>
								</div>									
							</div>
                        </div>
                        <div class="body pt-0">
                            <div class="table-responsive">
                                <table class="table m-b-0">
                                    <tbody>
                                        <?php                                       
                                        foreach($row['full_status'] as $row1) {?>
                                        <tr>
                                            <td class="pl-0"><?= $row1['name']?></td>
                                            <td><?= $row1['role']?></td>                                           
                                            <td class="text-right pr-0">
                                                <?php if(($row1['status'] == 'pending') && ($row1['reporting_head_id'] == $my_id)) { $i++;?>
                                                <label class="fancy-radio custom-color-green"><input name="status[<?=$row1['imbursement_application_status_id']?>]" value="approved" type="radio" checked="checked"><span><i></i>Approve</span></label><label class="fancy-radio"><input name="status[<?=$row1['imbursement_application_status_id']?>]" value="rejected" type="radio"><span><i></i>Reject</span></label>
												<input type="hidden" name="reporting_head_id[<?=$row1['imbursement_application_status_id']?>]" value="<?=$row1['reporting_head_id']?>">
												<input type="hidden" name="role_id[<?=$row1['imbursement_application_status_id']?>]" value="<?=$row1['role_id']?>">
												<input type="hidden" name="position[<?=$row1['imbursement_application_status_id']?>]" value="<?=$row1['position']?>">
												<input type="hidden" name="imbursement_application_id[<?=$row1['imbursement_application_status_id']?>]" value="<?=$row1['imbursement_application_id']?>">
												<input type="hidden" name="imbursement_application_status_id[<?=$row1['imbursement_application_status_id']?>]" value="<?=$row1['imbursement_application_status_id']?>">
                                                <?php } else {
                                                echo leave_status_c($row1['status']);
                                                 } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
         </div>
        <?php } ?>
        <?php if($i>0) {?>
   <div class="row">
                                <div class="col-sm-12">
                                    <div class="mt-4">
                                        <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />
                                    </div>
                                </div>
   </div>
   <?php } ?>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(function() {                               

       $("#imbursement_status_process").validate({
          rules: {
            
         },
         messages: {
           
         },
         submitHandler: function(form, e) {
			
               e.preventDefault();
			   
               $('.btnsmt').prop('disabled', true).attr('value','Processing...');
               var employee_id = '<?= $details[0]['employee_id']?>';
			   var unique_code = '<?= $details[0]['unique_code']?>';
			   
               var form_data = new FormData(form);
			   form_data.append('employee_id',employee_id);
			   form_data.append('unique_code',unique_code);
			   setTimeout(function() {
               $.ajax({
				type: 'POST',
				url: base_url + 'user/imbursement_status_process',
				cache: false,
				async: false,
				data: form_data,
				contentType: false,
				processData: false,
				    success: function(response)	{										   
					 var obj = $.parseJSON(response);		          
					 if(obj.status==1) {
                        toaster('success',obj.msg);
						$('#imbursement_application_list').DataTable().ajax.reload();
						$('#modal_ajax').modal('toggle');
                      } else {
                       toaster('error',obj.msg);
                       $('.btnsmt').prop('disabled', false).attr('value','Apply Now');
                      }                      
					},
					error: function(error) {	      
					  toaster('error',error);
					  $('.btnsmt').prop('disabled', false).attr('value','Apply Now');
					}
			  });
			   },500);
			  return false;
		 }
      });
       
    });
</script>

<?php } else {
$this->load->view('theme/user/notfound');
} ?>
