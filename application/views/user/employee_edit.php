<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="<?= base_url()?>assets/vendor/dropify/css/dropify.min.css">
<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css">
<div class="row clearfix">
	<?php 	
	$exarray = array();
	if(!empty($employee_details['roles'])) {		
		foreach($employee_details['roles'] as $row) {
		   array_push($exarray,$row['role_id']);
		}
	}
//pr($employee_details);
	?>
                <div class="col-lg-12 col-md-12 col-sm-12">
                <form class="form-auth-small" action="" name="employee_edit" id="employee_edit" method="POST" enctype="multipart/form-data">
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Employee Code<sup>*</sup></label>
                                        <input type="text" class="form-control" name="code" autofocus readonly value="<?=$employee_details['code']?>">
                                    </div>
                                </div>
                                
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Employee Name<sup>*</sup></label>       
                                        <input type="text" class="form-control" name="name" value="<?=$employee_details['name']?>">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                         <label>Status<sup>*</sup></label>       
                                        <select class="form-control show-tick" name="status">
                                            <option value="">Select Status</option>
                                            <option value="active" <?=($employee_details['status']=="active")?'selected':''?>>Active</option>
                                            <option value="inactive" <?=($employee_details['status']=="inactive")?'selected':''?>>Inactive</option>
                                            <option value="leave" <?=($employee_details['status']=="leave")?'selected':''?>>Leave</option>
                                            <option value="resigned" <?=($employee_details['status']=="resigned")?'selected':''?>>Resigned</option>
                                            <option value="noticeperiod" <?=($employee_details['status']=="noticeperiod")?'selected':''?>>Notice Period</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                         <label>Department<sup>*</sup></label>       
                                        <select class="form-control show-tick" name="department_id">
                                            <option value="">Select Department</option>
                                            <?php foreach($departments as $row) { ?>
                                            <option value="<?= $row['department_id']?>" <?=($employee_details['department_id']==$row['department_id'])?'selected':''?>><?= $row['name']?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Designation<sup>*</sup></label>
                                        <select class="form-control show-tick" name="designation_id">
                                            <option value="">Select Designation</option>
                                            <?php foreach($designations as $row) { ?>
                                            <option value="<?= $row['designation_id']?>" <?=($employee_details['designation_id']==$row['designation_id'])?'selected':''?>><?= $row['name']?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
								<div class="col-md-3 col-sm-12">
									<label>Role<sup>*</sup></label>
                                    <div class="form-group mselect">                                        
                                        <select id="multiselect3-all" class="multiselect multiselect-custom form-control" name="role_id[]" multiple="multiple">
                                            <?php foreach($roles as $row) { ?>
                                            <option value="<?= $row['role_id']?>" <?= (in_array($row['role_id'],$exarray))?'selected':''?>><?= $row['name']?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Date of birth</label>
                                        <input type="text" name="date_of_birth" class="form-control" value="<?=$employee_details['date_of_birth']?>" onchange="calcAge(this)">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Age</label>
                                        <input type="text" class="form-control number" name="age" readonly value="<?=$employee_details['age']?>">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                     <label>Date of join<sup>*</sup></label>
                                        <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="date_of_join" class="form-control" value="<?=$employee_details['date_of_join']?>">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Last worked date</label>
                                        <input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="last_work_date" class="form-control" value="<?=$employee_details['last_work_date']?>">
                                    </div>
                                </div>                                
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-6 col-sm-12">                                    
                                    <div class="form-group">
                                        <label>Current address</label>
                                        <textarea class="form-control" name="current_address" rows="4" cols="30"><?=$employee_details['current_address']?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">                                   
                                    <div class="form-group">
                                      <label>Permanent address</label>
                                        <textarea class="form-control" name="permanent_address" rows="4" cols="30"><?=$employee_details['permanent_address']?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Local Mob. No.</label>
                                        <input type="text" class="form-control number" name="phone_current" value="<?=$employee_details['phone_current']?>">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Home Phone</label>
                                        <input type="text" class="form-control number" name="phone_home" value="<?=$employee_details['phone_home']?>">
                                    </div>
                                </div>
								<div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Office Phone</label>
                                        <input type="text" class="form-control number" name="phone_office" value="<?=$employee_details['phone_office']?>">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Official Email Address</label>
                                        <input type="email" class="form-control" name="email" value="<?=$employee_details['email']?>">
                                    </div>
                                </div>
								<div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Personal Email Address</label>
                                        <input type="email" class="form-control" name="personal_email" value="<?=$employee_details['personal_email']?>">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Nationality</label>
                                        <input type="text" class="form-control" name="nationality" value="<?=$employee_details['nationality']?>">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">                                    
                                    <div class="form-group">
                                    <?php $countries = get_countries();?>
                                      <label>Country</label>
                                        <select class="form-control show-tick" name="country">
                                            <option value="">Select country</option>
                                            <?php 
			foreach ($countries as $key => $value) {
		            ?>
                                            <option value="<?=$key?>" <?=($employee_details['country']==$key)?'selected':''?>><?=$value?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">                                    
                                    <div class="form-group">
                                      <label>Overtime</label>
                                        <select class="form-control show-tick" name="overtime">                                         
                                            <option value="yes" <?=($employee_details['overtime']=="yes")?'selected':''?>>YES</option>
                                            <option value="no" <?=($employee_details['overtime']=="no")?'selected':''?>>NO</option>                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>Basic pay</label>
                                        <input type="text" class="form-control amount" name="basic_pay" value="<?=$employee_details['basic_pay']?>">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>HRA</label>
                                        <input type="text" class="form-control amount" name="hra" value="<?=$employee_details['hra']?>">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>Transport allowance</label>
                                        <input type="text" class="form-control amount" name="transport" value="<?=$employee_details['transport']?>">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>Special allowance</label>
                                        <input type="text" class="form-control amount" name="special_allowance" value="<?=$employee_details['special_allowance']?>">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>Other allowance</label>
                                        <input type="text" class="form-control amount" name="others" value="<?=$employee_details['others']?>">
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row clearfix">
                                <div class="col-12">
                                   <label>Profile Photo</label>
                                    <input type="file" name="file_name" id="file_name" class="dropify" data-code="<?=$employee_details['code']?>" data-default-file="<?= getUserImage($employee_details['profile_photo'])?>">
                                    <div class="mt-3"></div>
                                </div>                                
                              
                                <div class="col-sm-12">
                                    <div class="mt-4">
                                        <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Update" />
                                        <input type="reset" class="btn btn-lg btn-danger" value="Cancel">
										<a href="<?=base_url()?>employee-additional/<?=$employee_details['code']?>" class="btn btn-lg btn-warning pull-right">Edit Additional</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </form>
                </div>
            </div>

<script src="<?= base_url()?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url()?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script src="<?= base_url()?>assets/vendor/dropify/js/dropify.min.js"></script>
<script src="<?= base_url()?>assets/user/js/pages/forms/dropify.js"></script>
<script type="text/javascript">
    $(function() {                               

	   $('#multiselect3-all').multiselect({
        includeSelectAllOption: false,
    });
	   
	    $('input[name="date_of_birth"]').datepicker({		
		todayHighlight: true,
		autoclose: true,
		format: 'dd/mm/yyyy',
		startView: 2
	            });
	   
       $("#employee_edit").validate({
		  ignore: ':hidden:not("#multiselect3-all")',
		  errorPlacement: function(error, element) {			
			if (element.attr("name") == "role_id[]" )
              error.insertAfter(".btn-group");
			else
			   error.insertAfter(element);
		  },
          rules: {
			 code: {
				required:true
			 },
             date_of_join: {
	           required:true
             },             
             name: {
               required:true
             },
             status: {
               required:true
             },
             department_id: {
               required:true
             },
             designation_id: {
               required:true
             },
             age:{
               max:100,
               min:10
             },
             email: {
               email: true,
               remote: {
                url: base_url+"user/checkDupEmail",
                type: "post",
				data: {
					'code': function () { return $('input[name="code"]').val(); }
				},
               }
             },
			 'role_id[]': {
               required:true
             },
         },
         messages: {
            code:{
                required: "Please enter employee code"
            },
            date_of_join:"Please enter date of join",
            name:"Please enter employee name",
            status:"Please choose status",
            department_id:"Please choose department",
            designation_id:"Please choose designation",
            email: {
                email: "Invalid email format",
                remote: "Email already exist"
            },
			'role_id[]': {
				required: "Please choose role"
			}
         },
         submitHandler: function(form, e) {
               e.preventDefault();   
               $('.btnsmt').prop('disabled', true).attr('value','Processing...');
               
               var form_data = new FormData(form);
	           //append files
               var file = document.getElementById('file_name').files[0];
               if (file) {   
	             form_data.append('file_name', file);
               }
               setTimeout(function() {
               $.ajax({
				type: 'POST',
				url: base_url + 'user/employee_edit_process',
				cache: false,
				async: false,
				data: form_data,
				contentType: false,
				processData: false,
				success: function(response)	{
										   
					 var obj = $.parseJSON(response);		          
					 if(obj.status==1) {
								   toaster('success',obj.msg);
								   $('.btnsmt').prop('disabled', false).attr('value','Update');
								 } else {
								  toaster('error',obj.msg);
								  $('.btnsmt').prop('disabled', false).attr('value','Update');
								 }
								 
				   },
				   error: function(error) {	      
								 toaster('error',error);
								 $('.btnsmt').prop('disabled', false).attr('value','Update');
				   }
               });
			   },500);
               return false;
         }
      });
       
    });
</script>