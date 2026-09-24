<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<div class="row clearfix">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="body">
                        <form class="form-auth-small" action="<?=base_url()?>user/employee_import" name="employee_import_form" id="employee_import_form" method="POST" enctype="multipart/form-data">
                           <input type="file" style="width:200px;margin-right:3px" name="uploadFile" value="" />
              <input type="submit" name="submit" class="btn btn-info btn-sm text-center btn-search p-t-5" value="Import" />
              <a href="<?=base_url()?>assets/common/employee-bulk.csv" class="pull-right mt-2"><strong>Download Sample CSV</strong></a>
                        </form>
	        
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                                    
                     <form class="form-auth-small" action="" name="employee_import_process" id="employee_import_process" method="POST" enctype="multipart/form-data">
                    <div class="card">
                           <div class="body">
                            <div class="row clearfix">                               
                         
                                
                               <?php if(!empty($csvdata)) { ?>
	              
	                      <div class="col-md-12">
		           <div class="table-responsive">
			<table class="table table-striped m-t-20">
			   <tr>
			     <td style="white-space: nowrap;font-weight:bold;">Employee code</td>
			     <td style="white-space: nowrap;font-weight:bold;">Password</td>
			     <td style="white-space: nowrap;font-weight:bold;">Name</td>
			     <td style="white-space: nowrap;font-weight:bold;">Designation</td>
			     <td style="white-space: nowrap;font-weight:bold;">Department</td>
			     <td style="white-space: nowrap;font-weight:bold;">Date of birth</td>
			     <td style="white-space: nowrap;font-weight:bold;">Age</td>
			     <td style="white-space: nowrap;font-weight:bold;">Date of join</td>
			     <td style="white-space: nowrap;font-weight:bold;">Last work date</td>
			     <td style="white-space: nowrap;font-weight:bold;">Current address</td>
			     <td style="white-space: nowrap;font-weight:bold;">Permanent address</td>
			     <td style="white-space: nowrap;font-weight:bold;">Office phone</td>
			     <td style="white-space: nowrap;font-weight:bold;">Current phone</td>
			     <td style="white-space: nowrap;font-weight:bold;">Official email</td>
			     <td style="white-space: nowrap;font-weight:bold;">Personal email</td>
			     <td style="white-space: nowrap;font-weight:bold;">Country</td>
			     <td style="white-space: nowrap;font-weight:bold;">Overtime</td>
			     <td style="white-space: nowrap;font-weight:bold;">Passport no.</td>
			     <td style="white-space: nowrap;font-weight:bold;">Passport expiry</td>
			     <td style="white-space: nowrap;font-weight:bold;">Passport with</td>
			     <td style="white-space: nowrap;font-weight:bold;">Visa no.</td>
			     <td style="white-space: nowrap;font-weight:bold;">Visa expiry</td>
			     <td style="white-space: nowrap;font-weight:bold;">Labour no.</td>
			     <td style="white-space: nowrap;font-weight:bold;">Labour expiry</td>
			     <td style="white-space: nowrap;font-weight:bold;">Emirate no.</td>
			     <td style="white-space: nowrap;font-weight:bold;">Emirate expiry</td>
			     <td style="white-space: nowrap;font-weight:bold;">Unified no.</td>
			     <td style="white-space: nowrap;font-weight:bold;">Nationality</td>
			     <td style="white-space: nowrap;font-weight:bold;">Basic pay</td>
			     <td style="white-space: nowrap;font-weight:bold;">HRA</td>
			     <td style="white-space: nowrap;font-weight:bold;">Transport</td>
			     <td style="white-space: nowrap;font-weight:bold;">Special allowance</td>
			     <td style="white-space: nowrap;font-weight:bold;">Others</td>
			     <td style="white-space: nowrap;font-weight:bold;">Insurance company</td>
			     <td style="white-space: nowrap;font-weight:bold;">Insurance cost</td>
			     <td style="white-space: nowrap;font-weight:bold;">Insurance expiry</td>
			     <td style="white-space: nowrap;font-weight:bold;">Bank name</td>
			     <td style="white-space: nowrap;font-weight:bold;">Account no.</td>
			     <td style="white-space: nowrap;font-weight:bold;">IBAN no.</td>
                                                     <td style="white-space: nowrap;font-weight:bold;">Status</td>
			   </tr>
			<?php 	
		                   $i=0;foreach($csvdata as $row) {		
			?>
					
			<tr id="r<?=$i?>">                                                 
                                                  <td style="white-space: nowrap;">
                                                    <input type="text" class="input-radius form-control" placeholder="" name="code[<?=$i?>]" id="code<?=$i?>" value="<?=$row['code']?>">
                                                    <input type="hidden" name="ids[<?=$i?>]" id="ids<?=$i?>" value="r<?=$i?>">
                                                  </td>
                                                  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="password[<?=$i?>]" id="password<?=$i?>" value="<?=$row['password']?>">
                                                  </td>
                                                  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="name[<?=$i?>]" id="name<?=$i?>" value="<?=$row['name']?>">
                                                  </td>
                                                  <td style="white-space: nowrap;">
                                                   <select class="input-radius form-control" style="width:auto" placeholder="Select" name="designation_id[<?=$i?>]" id="designation_id<?=$i?>">
                                                     <option value="">Select Designation</option>
                                                       <?php foreach ($designations as $cn) { ?>
                                                 <option value="<?php echo $cn['designation_id']?>" <?= (strtolower($row['designation'])== strtolower($cn['name']))?'selected':''?>><?php echo $cn['name'];?></option>
                                                       <?php }?>
                                                  </select>
                                                  </td>
                                                  <td style="white-space: nowrap;">
                                                   <select class="input-radius form-control" style="width:auto" placeholder="Select" name="department_id[<?=$i?>]" id="department_id<?=$i?>">
                                                     <option value="">Select Department</option>
                                                       <?php foreach ($departments as $cn) { ?>
                                                         <option value="<?php echo $cn['department_id']?>" <?= (strtolower($row['department'])== strtolower($cn['name']))?'selected':''?>><?php echo $cn['name'];?></option>
                                                       <?php }?>
                                                  </select>
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="date_of_birth[<?=$i?>]" id="date_of_birth<?=$i?>" class="input-radius form-control" value="<?=$row['date_of_birth']?>">
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control number" placeholder="" name="age[<?=$i?>]" id="age<?=$i?>" value="<?=$row['age']?>">
                                                  </td>
                                                  <td style="white-space: nowrap;">
                                                   <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="date_of_join[<?=$i?>]" id="date_of_join<?=$i?>" class="input-radius form-control" value="<?=$row['date_of_join']?>">
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="last_work_date[<?=$i?>]" id="last_work_date<?=$i?>" class="input-radius form-control" value="<?=$row['last_work_date']?>">
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="current_address[<?=$i?>]" id="current_address<?=$i?>" value="<?=$row['current_address']?>">
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="permanent_address[<?=$i?>]" id="permanent_address<?=$i?>" value="<?=$row['permanent_address']?>">
                                                  </td>
			  
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="phone_office[<?=$i?>]" id="phone_office<?=$i?>" value="<?=$row['phone_office']?>">
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="phone_current[<?=$i?>]" id="phone_current<?=$i?>" value="<?=$row['phone_current']?>">
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="email" class="input-radius form-control" placeholder="" name="email[<?=$i?>]" id="email<?=$i?>" value="<?=$row['email']?>">
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="email" class="input-radius form-control" placeholder="" name="personal_email[<?=$i?>]" id="personal_email<?=$i?>" value="<?=$row['personal_email']?>">
                                                  </td>
			  
			 <td style="white-space: nowrap;">
			    <select class="form-control show-tick" name="country[<?=$i?>]" id="country<?=$i?>">
			        <option value="">Select country</option>
			        <?php 
			            foreach ($countries as $key => $value) {
			        ?>
			        <option value="<?=$key?>" <?= (strtolower($row['country'])== strtolower($value))?'selected':''?>><?=$value?></option>
			        <?php } ?>
			    </select>
			 </td>
			 
			 <td style="white-space: nowrap;">
			    <select class="form-control show-tick" name="overtime[<?=$i?>]" id="overtime<?=$i?>">
			        <option value="">Select overtime</option>			        
			        <option value="yes" <?= (strtolower($row['overtime'])== 'yes')?'selected':''?>>YES</option>
			        <option value="no" <?= (strtolower($row['overtime'])== 'no')?'selected':''?>>NO</option>
			    </select>
			 </td>
			 
			 <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="passport_number[<?=$i?>]" id="passport_number<?=$i?>" value="<?=$row['passport_number']?>">
                                                  </td>
			 
			 <td style="white-space: nowrap;">
                                                   <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="passport_expiry[<?=$i?>]" id="passport_expiry<?=$i?>" class="input-radius form-control" value="<?=$row['passport_expiry']?>">
                                                  </td>
			 
			 <td style="white-space: nowrap;">
			    <select class="form-control show-tick" name="passport_with[<?=$i?>]" id="passport_with<?=$i?>">
			        <option value="">Select passport with</option>			        
			        <option value="employee" <?= (strtolower($row['passport_with'])== 'employee')?'selected':''?>>Employee</option>
			        <option value="company" <?= (strtolower($row['passport_with'])== 'company')?'selected':''?>>Company</option>
			        <option value="hr" <?= (strtolower($row['passport_with'])== 'hr')?'selected':''?>>HR</option>
			        <option value="pro" <?= (strtolower($row['passport_with'])== 'pro')?'selected':''?>>PRO</option>
			    </select>
			 </td>
			  
			   <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="visa_number[<?=$i?>]" id="visa_number<?=$i?>" value="<?=$row['visa_number']?>">
                                                  </td>
			 
			 <td style="white-space: nowrap;">
                                                   <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="visa_expiry[<?=$i?>]" id="visa_expiry<?=$i?>" class="input-radius form-control" value="<?=$row['visa_expiry']?>">
                                                  </td>
			 
			 
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="labour_number[<?=$i?>]" id="labour_number<?=$i?>" value="<?=$row['labour_number']?>">
                                                  </td>
			 
			 <td style="white-space: nowrap;">
                                                   <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="labour_expiry[<?=$i?>]" id="labour_expiry<?=$i?>" class="input-radius form-control" value="<?=$row['labour_expiry']?>">
                                                  </td>
			 
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="emirate_number[<?=$i?>]" id="emirate_number<?=$i?>" value="<?=$row['emirate_number']?>">
                                                  </td>
			 
			 <td style="white-space: nowrap;">
                                                   <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="emirate_expiry[<?=$i?>]" id="emirate_expiry<?=$i?>" class="input-radius form-control" value="<?=$row['emirate_expiry']?>">
                                                  </td>
			 
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="unified_no[<?=$i?>]" id="unified_no<?=$i?>" value="<?=$row['unified_no']?>">
                                                  </td>
			  
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="nationality[<?=$i?>]" id="nationality<?=$i?>" value="<?=$row['nationality']?>">
                                                  </td>
			  
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="basic_pay[<?=$i?>]" id="basic_pay<?=$i?>" value="<?=$row['basic_pay']?>">
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="hra[<?=$i?>]" id="hra<?=$i?>" value="<?=$row['hra']?>">
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="transport[<?=$i?>]" id="transport<?=$i?>" value="<?=$row['transport']?>">
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="special_allowance[<?=$i?>]" id="special_allowance<?=$i?>" value="<?=$row['special_allowance']?>">
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="others[<?=$i?>]" id="others<?=$i?>" value="<?=$row['others']?>">
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="insurance_company[<?=$i?>]" id="insurance_company<?=$i?>" value="<?=$row['insurance_company']?>">
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="insurance_cost[<?=$i?>]" id="insurance_cost<?=$i?>" value="<?=$row['insurance_cost']?>">
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="insurance_expiry[<?=$i?>]" id="insurance_expiry<?=$i?>" class="input-radius form-control" value="<?=$row['insurance_expiry']?>">
                                                  </td>
			  
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="bank_name[<?=$i?>]" id="bank_name<?=$i?>" value="<?=$row['bank_name']?>">
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="account_number[<?=$i?>]" id="account_number<?=$i?>" value="<?=$row['account_number']?>">
                                                  </td>
			  <td style="white-space: nowrap;">
                                                   <input type="text" class="input-radius form-control" placeholder="" name="iban_number[<?=$i?>]" id="iban_number<?=$i?>" value="<?=$row['iban_number']?>">
                                                  </td>
			  
                                                  <td style="white-space: nowrap;">
                                                   <select class="form-control show-tick" name="status[<?=$i?>]" id="status<?=$i?>">
                                                     <option value="">Select Status</option>
                                                     <option value="active" <?= (strtolower($row['status'])== 'active')?'selected':''?>>Active</option>
                                                     <option value="inactive" <?= (strtolower($row['status'])== 'inactive')?'selected':''?>>Inactive</option>
                                                     <option value="leave" <?= (strtolower($row['status'])== 'leave')?'selected':''?>>Leave</option>
                                                     <option value="resigned" <?= (strtolower($row['status'])== 'resigned')?'selected':''?>>Resigned</option>
                                                     <option value="noticeperiod" <?= (strtolower($row['status'])== 'noticeperiod')?'selected':''?>>Notice Period</option>
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
                                 
                                
                            </div>
                        </div>
                    </div>
                </form>
                </div>
            </div>
<style>
	.form-control {
		width:auto;
	}
</style>
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
//      focusInvalid: false,
//	invalidHandler: function(form, validator) {
//            
//	    if (!validator.numberOfInvalids())
//	        return;
//            
//	    $('html, body').animate({
//	        scrollTop: $(validator.errorList[0].element).offset().top
//	    }, 2000);
//            
//	},
//	highlight: function(element) {
//        $(element).parent('td').parent('tr').addClass('field-error');
//    },
//    unhighlight: function(element) {
//        $(element).parent('td').parent('tr').removeClass('field-error');
//    },
//errorClass: "field-error",

      errorPlacement: function ( error, element ) {
      $(element).parent('td').parent('tr').addClass('field-error');
      error.insertAfter( element );
      },
      invalidHandler: function(e, validator) {
                  var errors = validator.numberOfInvalids();
                  if (errors) {
                    toaster('error',errors+' errors are there!');
                  } else {
	    return;
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
	              toaster('error',value.msg);
	              $('.btnsmt').prop('disabled', false).attr('value','Submit');
	               $('#'+value.rid).css('background','#ffe2e2');
                          } else {	
	            $('#'+value.rid).html('<td colspan="7" style="background:green;color:#fff;text-align:center">'+value.msg+'</td>');
	            $('.btnsmt').prop('disabled', false).attr('value','Submit');
	          }
	        });					
	      } else {
	         toaster('error',obj.msg);
	         $('.btnsmt').prop('disabled', false).attr('value','Submit');
	      }
       
                },
                error: function(error) {
                  toaster('error',error);
                  $('.btnsmt').prop('disabled', false).attr('value','Submit');
                }
            });
          },500);
            return false;
        }
  });
   
   $("#employee_import_process input[name^='code']").each(function() {
                $(this).rules('add', {
                    required: true,
                    messages: {
                       required: "Please enter code",
                    }
                })
           });
   $("#employee_import_process input[name^='password']").each(function() {
                $(this).rules('add', {
                    required: true,
                    messages: {
                       required: "Please enter password",
                    }
                })
           });
   $("#employee_import_process input[name^='name']").each(function() {
                $(this).rules('add', {
                    required: true,
                    messages: {
                       required: "Please enter name",
                    }
                })
           });
   $("#employee_import_process select[name^='department']").each(function() {
                $(this).rules('add', {
                    required: true,
                    messages: {
                       required: "Please choose department",
                    }
                })
           });
   $("#employee_import_process select[name^='designation']").each(function() {
                $(this).rules('add', {
                    required: true,
                    messages: {
                       required: "Please choose designation",
                    }
                })
           });
   $("#employee_import_process input[name^='date_of_join']").each(function() {
                $(this).rules('add', {
                    required: true,
                    messages: {
                       required: "Please choose date of join",
                    }
                })
           });
   $("#employee_import_process select[name^='status']").each(function() {
                $(this).rules('add', {
                    required: true,
                    messages: {
                       required: "Please choose status",
                    }
                })
           });
   $('input, select').on('blur', function(){
	
      if ($(this).hasClass('valid')) {
	
        $(this).parent('td').parent('tr').removeClass('field-error');
      }
      if ($(this).hasClass('error')) {
	
        $(this).parent('td').parent('tr').addClass('field-error');
      }
   });
                
});
</script>