<?php
$location = $this->common_model->selectOne('work_location_assignment',array('assignment_id'=>$param2),'*');
$locations  = $this->common_model->selectAll('work_locations','','');
if(!empty($location)) {
?>
<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<div class="row clearfix">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
           <form class="form-auth-small" action="" name="location_update" id="location_update" method="POST" enctype="multipart/form-data">
            <div class="row clearfix">
				<div class="col-md-6 col-sm-12">                                    
                    <div class="form-group">
                        <label>Work Location<sup>*</sup></label>
                            <select class="form-control show-tick" name="work_location_id">
                                            <option value="">Select work location</option>
                                            <?php foreach($locations as $row) { ?>
                                            <option value="<?= $row['work_location_id']?>" <?=($row['work_location_id'] == $location['work_location_id'])?'selected':''?>><?= $row['name']?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                </div>
				<div class="col-md-6 col-sm-12">                                    
                    <div class="form-group">
                        <label>Status<sup>*</sup></label>
                            <select class="form-control show-tick" name="status">
                                            <option value="">Select status</option>
                                            <option value="active" <?=($location['status'] == 'active')?'selected':''?>>Active</option>
                                            <option value="inactive" <?=($location['status'] == 'inactive')?'selected':''?>>Inactive</option>
                                        </select>
                                    </div>
                </div>
				<div class="col-md-6 col-sm-12">                                    
                    <div class="form-group">
                        <label>Start date</label>
                            <input type="text" class="form-control" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" name="start_date" value="<?=get_date($location['start_date'])?>">
                                    </div>
                </div>
				<div class="col-md-6 col-sm-12">                                    
                    <div class="form-group">
                        <label>End date</label>
                            <input type="text" class="form-control" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" name="end_date" value="<?=get_date($location['end_date'])?>">
                                    </div>
                </div>
				                
                <div class="col-sm-12">
                    <input type="submit" class="btn btn-lg btn-primary btnsmt1" value="Update" />
                </div>
            </div>
           </form>
        </div>
    </div>
</div>
<script src="<?= base_url()?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
$("#location_update").validate({		  
          rules: {
			work_location_id: {
				required:true             
			},
			status: {
				required:true
			}
          },
          messages: {
            work_location_id: {
				required:"Please choose location"            
			},
			status: {
				required: "Please choose status"
			}
          },
          submitHandler: function(form, e) {
            
            
               e.preventDefault();
               
               $('.btnsmt1').prop('disabled', true).attr('value','Processing...');
               var assignment_id = '<?= $param2?>';			   
               var form_data = new FormData(form);
               form_data.append('assignment_id',assignment_id);
               
               swal({
                    title: "Are you sure?",
                    text: "Want to update assignment?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    confirmButtonText: "Yes, update now!",
                    closeOnConfirm: true
                }, function (isConfirm) {
               
                    if (isConfirm) {         
                        $.ajax({
                         type: 'POST',
                         url: base_url + 'user/work_location_update_process',
                         cache: false,
                         async: false,
                         data: form_data,
                         contentType: false,
                         processData: false,
                         success: function(response) {
                                                       
                            var obj = $.parseJSON(response);		          
                            if(obj.status==1) {
                               toaster('success',obj.msg);
							   $('#employee_work_location').DataTable().ajax.reload();
                               $('#modal_ajax').modal('toggle');
                            } else {
                              toaster('error',obj.msg);
                              $('.btnsmt1').prop('disabled', false).attr('value','Update');
                            }
                             
                         }, error: function(error) {	      
							toaster('error',error);
							$('.btnsmt1').prop('disabled', false).attr('value','Update');
					     }
                         
                        });
                        
                    } else {
                        $('#modal_ajax').modal('toggle');
                    }
               });
               return false;
          }
});
</script>
<?php } else {
$this->load->view('theme/user/notfound');
} ?>