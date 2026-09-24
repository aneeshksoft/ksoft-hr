<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<div class="row clearfix">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="body">
                        <form class="form-auth-small" action="<?=base_url()?>user/employee_import" name="employee_import_form" id="employee_import_form" method="POST" enctype="multipart/form-data">
                           <input type="file" style="width:200px;margin-right:3px" name="uploadFile" value="" />
              <input type="submit" name="submit" class="btn btn-info btn-sm text-center btn-search p-t-5" value="Import" />
                        </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                                    
                    
                    <div class="card">
                           <div class="body">
                            <div class="row clearfix">                               
                         
                                <form class="form-auth-small" action="user/employee_import_process" name="employee_import_process" id="employee_import_process" method="POST" enctype="multipart/form-data">
                               <?php if(!empty($csvdata)) { ?>
	                      <div class="col-md-12">
		           <div class="table-responsive">
			<table class="table table-striped m-t-20">
			   <tr>
			     <td style="white-space: nowrap;font-weight:bold;">Employee Code</td>
			     <td style="white-space: nowrap;font-weight:bold;">Password</td>
			     <td style="white-space: nowrap;font-weight:bold;">Name</td>
			     <td style="white-space: nowrap;font-weight:bold;">Designation</td>
			     <td style="white-space: nowrap;font-weight:bold;">Department</td>
			     <td style="white-space: nowrap;font-weight:bold;">Date of Join</td>
                                                     <td style="white-space: nowrap;font-weight:bold;">Status</td>
			   </tr>
			<?php 	
		                   $i=1;foreach($csvdata as $row) {		
			?>
					
			<tr id="r<?=$i?>">                                                 
                                                  <td style="white-space: nowrap;">
                                                    <input type="text" class="input-radius form-control" placeholder="" name="code[]" id="code<?=$i?>" value="<?=$row['code']?>">
                                                    <input type="hidden" name="ids[]" id="ids<?=$i?>" value="r<?=$i?>">
                                                  </td>
                                                  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="password[]" id="password<?=$i?>" value="<?=$row['password']?>">
                                                  </td>
                                                  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="name[]" id="name<?=$i?>" value="<?=$row['name']?>">
                                                  </td>
                                                  <td style="white-space: nowrap;">
                                                   <select class="input-radius form-control" style="width:auto" placeholder="Select" name="designation_id[]" id="designation_id<?=$i?>">
                                                     <option value="">Select Designation</option>
                                                       <?php foreach ($designations as $cn) { ?>
                                                 <option value="<?php echo $cn['designation_id']?>" <?= ($row['designation']== $cn['name'])?'selected':''?>><?php echo $cn['name'];?></option>
                                                       <?php }?>
                                                  </select>
                                                  </td>
                                                  <td style="white-space: nowrap;">
                                                   <select class="input-radius form-control" style="width:auto" placeholder="Select" name="department_id[]" id="department_id<?=$i?>">
                                                     <option value="">Select Department</option>
                                                       <?php foreach ($departments as $cn) { ?>
                                                         <option value="<?php echo $cn['department_id']?>" <?= ($row['department']== $cn['name'])?'selected':''?>><?php echo $cn['name'];?></option>
                                                       <?php }?>
                                                  </select>
                                                  </td>
                                                  <td style="white-space: nowrap;">
                                                   <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="date_of_join[]" id="date_of_join<?=$i?>" class="input-radius form-control" value="<?=$row['date_of_join']?>">
                                                  </td>
                                                  <td style="white-space: nowrap;">
                                                   <select class="form-control show-tick" name="status[]" id="status<?=$i?>">
                                                     <option value="">Select Status</option>
                                                     <option value="active" <?= ($row['status']== 'active')?'selected':''?>>Active</option>
                                                     <option value="inactive" <?= ($row['status']== 'inactive')?'selected':''?>>Inactive</option>
                                                     <option value="leave" <?= ($row['status']== 'leave')?'selected':''?>>Leave</option>
                                                     <option value="resigned" <?= ($row['status']== 'resigned')?'selected':''?>>Resigned</option>
                                                     <option value="noticeperiod" <?= ($row['status']== 'noticeperiod')?'selected':''?>>Notice Period</option>
                                                   </select>
                                                  </td>
			</tr>
			<?php $i++;} ?>
			</table>
			</div>
			</div>
			<div class="col-sm-12 m-t-20 text-right">
			<input type="submit" class="btn btn-lg btn-primary btnsmt" name="employeesubmit" id="employeesubmit" value="Submit">
			</div>
		    <?php } else {?>
			<p class="text-center mb-0 w-100">No valid data found!</p>
			<?php } ?>
                                  </form>
                                
                            </div>
                        </div>
                    </div>
               
                </div>
            </div>

<script src="<?= base_url()?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
function resizeInput() {
  $(this).attr('size', $(this).val().length);
}


$(document).ready(function() {
   
   $('input[type="text"]').keyup(resizeInput).each(resizeInput); 
    
   $("#employee_import_process").validate({
       rules: {
         'code[]' : {
            required:true,
         },
        'password[]' : {
            required:true
         },
        'name[]' : {
           required:true
        },
        'designation_id[]' : {
           required:true
        },
        'department_id[]' : {
           required:true
        },
        'date_of_join[]' : {
           required:true
        },
        'status[]' : {
           required:true
         }
      },

      messages: {
        'code[]' : {
            required:'Please enter code'
         },
        'password[]' : {
            required:'Please enter password'
         },
        'name[]' : {
           required:'Please enter name'
        },
        'designation_id[]' : {
           required:'Please choose designation'
        },
        'department_id[]' : {
           required:'Please choose department'
        },
        'date_of_join[]' : {
           required:'Please  choose join date'
        },
        'status[]' : {
           required:'Please choose status'
         }
      },

      submitHandler: function(form, e) {

          e.preventDefault();
          $('.btnsmt').prop('disabled', true).attr('value','Processing...');
          var form_data = new FormData(form);
          setTimeout(function(){
          $.ajax({
                type: 'POST',
                url: base_url + 'user/employee_import_process',
                cache: false,
	async: false,
	data: form_data,
	contentType: false,
	processData: false,
                success: function(result) {
                  
	     var obj = $.parseJSON(result);
	     if(obj.status==1) {					
	        $.each(obj.data, function (index, value) {
	          if (value.status == 0) {                           
		toaster('error',obj.msg);
                          } else {	
	            $('#'+value.rid).html('<td colspan="6" style="background:green;color:#fff;">Saved!</td>');
	          }
	        });					
	      } else {
	         toaster('error',obj.msg);			
	      }
				 
                      $('.btnsmt').prop('disabled', false).attr('value','Submit');
       
                },
                error: function(error) {
                toaster('error',error);
                  // $('.btnsmt').prop('disabled', false).attr('value','Submit');
                }
            });
          },500);
            return false;
        }
  });
   
   $('#employee_import_process input[name*=code]').each(function() {
                $(this).rules('add', {
                    required: true,
                    messages: {
                       required: "Please enter code",
                    }
                })
           });
                
});
</script>