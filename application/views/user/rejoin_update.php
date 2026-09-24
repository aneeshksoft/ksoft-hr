<?php
$leave  = $this->common_model->leave_by_id($param3);
$rejoin = $this->common_model->selectOne('employee_leave_rejoin',array('leave_rejoin_id'=>$param2),'*');
$eligibility = $this->common_model->checkLeaveEligibility($leave['employee_id']);
//pr($eligibility);
//pr($rejoin);
//pr($leave);
$my_id = $this->session->userdata('employee_id');
//pr($leave);


if(!empty($leave) && !empty($rejoin)) {
?>

<div class="row clearfix">
    <div class="col-md-12 col-sm-12">
		<div class="card">
                        <div class="body">
                            <div class="row">
                                <div class="col-7">
                                    <small class="text-small">Leave applied date</small>
                                    <h6 class="m-t-0"><?=get_date($leave['from_date']).' to '.get_date($leave['to_date'])?></h6><small class="text-small">Leave Type</small>
                                    <h6 class="m-t-0"><?=$leave['title']?></h6>
									<?php if($leave['leave_type_id'] == 1) {?>
									<small class="text-small">Eligible annual leave</small>
                                    <h6 class="m-t-0"><?=$eligibility['eligible_annual_leave'].' day(s)'?></h6>
									<?php } ?>
                                </div>                                                             
                            </div>
                        </div>
                    </div>
        <div class="form-group">
           <form class="form-auth-small" action="" name="rejoin_update" id="rejoin_update" method="POST" enctype="multipart/form-data">
            <div class="row clearfix">
				<div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                     <label>Date of join<sup>*</sup></label>
                                        <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="rejoin_date" data-date-start-date="<?=get_date($rejoin['leave_date'])?>" class="form-control" >
                                    </div>
                                </div>
				<div class="col-md-12 col-sm-12" id="ajax-leave-split">
				</div>				
                <div class="col-md-12 col-sm-12">
                     <div class="form-group">
                        <label>Remarks</label>
                            <textarea class="form-control" name="remarks" rows="4" cols="30"></textarea>
                    </div>
                </div>
                <div class="col-sm-12">
                    <input type="submit" class="btn btn-lg btn-primary btnsmt1" value="Rejoin" />
                </div>
            </div>
           </form>
        </div>
    </div>
</div>

<script type="text/javascript">
$("#rejoin_update").validate({		  
          rules: {
			rejoin_date: {
				required:true             
			}
          },
          messages: {
            rejoin_date: {
				required:"Please choose rejoin date"            
			}
          },
          submitHandler: function(form, e) {
            
            
               e.preventDefault();
               
               $('.btnsmt1').prop('disabled', true).attr('value','Processing...');
               var leave_rejoin_id = '<?= $param2?>';
	           var leave_type_id = '<?= $leave['leave_type_id']?>';
	           var employee_id = '<?= $leave['employee_id']?>';
			   
               var form_data = new FormData(form);
               form_data.append('leave_rejoin_id',leave_rejoin_id);
			   form_data.append('leave_type_id',leave_type_id);
			   form_data.append('employee_id',employee_id);
               
               swal({
                    title: "Are you sure?",
                    text: "Want to rejoin the employee?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    confirmButtonText: "Yes, rejoin now!",
                    closeOnConfirm: true
                }, function (isConfirm) {
               
                    if (isConfirm) {         
                        $.ajax({
                         type: 'POST',
                         url: base_url + 'user/rejoin_process',
                         cache: false,
                         async: false,
                         data: form_data,
                         contentType: false,
                         processData: false,
                         success: function(response) {
                                                       
                            var obj = $.parseJSON(response);		          
                            if(obj.status==1) {
                               toaster('success',obj.msg);
                               $('.btnsmt').trigger('submit');
                               $('#modal_ajax').modal('toggle');
                            } else {
                              toaster('error',obj.msg);
                              $('.btnsmt1').prop('disabled', false).attr('value','Rejoin');
                            }
                             
                         }, error: function(error) {	      
							toaster('error',error);
							$('.btnsmt1').prop('disabled', false).attr('value','Rejoin');
					     }
                         
                        });
                        
                    } else {
                        $('#modal_ajax').modal('toggle');
                    }
               });
               return false;
          }
});

$('input[name="rejoin_date"]').on("change",function(){
	
	var rjdte = $('input[name="rejoin_date"]').val(); //rejoin date
	var leave_rejoin_id = '<?= $rejoin['leave_rejoin_id']?>';
	var leave_type_id = '<?= $leave['leave_type_id']?>';
	var employee_id = '<?= $leave['employee_id']?>';
	$('#ajax-leave-split').html("");
	setTimeout(function(){
	if (rjdte != "") {
        $.ajax({
            type: 'POST',
            url: base_url + 'user/rejoin_leave_split',
            cache: false,
            async: false,
            data: "rjdte="+rjdte+"&ltype="+leave_type_id+"&employee_id="+employee_id+"&rjid="+leave_rejoin_id,
            dataType: "html",
            success: function(response) {
				$('#ajax-leave-split').html(response);
			}
		});
    }
	},500);
	
	
});

</script>

<?php } else {
$this->load->view('theme/user/notfound');
} ?>