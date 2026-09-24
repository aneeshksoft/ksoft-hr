<?php
if(!empty($employee_details)) {
?>
<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="<?= base_url()?>assets/vendor/dropify/css/dropify.min.css">
<div class="row clearfix">
                <div class="col-lg-12 col-md-12">
                    <div class="card client-detail">
                        <div class="body d-flex flex-wrap">
                            <div class="profile-image">
                                <img src="<?= getUserImage($employee_details['profile_photo'])?>" alt="<?= $employee_details['name']?>" class="rounded">
                            </div>
                            <div class="details">
                                <h4 class="m-t-0 m-b-0"><strong><?= $employee_details['name']?></strong></h4>
                                <h6 class="mt-1 mb-0">Code: <?= $employee_details['code']?></h6>
                                <div class="job_post"><strong><?= $employee_details['designation']?></strong>&nbsp;-&nbsp;<?= $employee_details['department']?></div>
                                <small class="text-muted">Current address: </small>
                                <div><?= ($employee_details['current_address'] != "")?$employee_details['current_address']:"-"?></div>
                                <small class="text-muted">Current phone: </small>
                                <div><?= ($employee_details['phone_current'] != "")?$employee_details['phone_current']:"-"?></div>
                                <a href="<?= base_url()?>profile/<?= $employee_details['code']?>" class="btn btn-lg btn-success vprofile">Skip & View Profile</a>                               
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12">
                <form class="form-auth-small" action="" name="employee_additional" id="employee_additional" method="POST" enctype="multipart/form-data">
                    <div class="card">
                       <div class="header">
                            <h2>Other Details</h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>Passport number</label>
                                        <input type="text" class="form-control" name="passport_number">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>Passport expiry</label>
                                        <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" name="passport_expiry" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>Passport With</label>
                                        <select class="form-control show-tick" name="passport_with" id="pass0">
                                            <option value="">Select</option>                                           
                                            <option value="employee">Employee</option>
                                            <option value="company">Company</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="passport_with_remarks" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Upload document(s)</label>
                                        <input type="file" name="passport_images[]" value="" class="form-control file" multiple="">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Visa number</label>
                                        <input type="text" class="form-control" name="visa_number">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Visa expiry</label>
                                        <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" name="visa_expiry" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Upload document(s)</label>
                                        <input type="file" name="visa_images[]" value="" class="form-control file" multiple="">
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Labour number</label>
                                        <input type="text" class="form-control" name="labour_number">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Labour expiry</label>
                                        <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" name="labour_expiry" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Upload document(s)</label>
                                        <input type="file" name="labour_images[]" value="" class="form-control file" multiple="">
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Emirate number</label>
                                        <input type="text" class="form-control" name="emirate_number">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Emirate expiry</label>
                                        <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" name="emirate_expiry" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Upload document(s)</label>
                                        <input type="file" name="emirate_images[]" value="" class="form-control file" multiple="">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="mt-4">
                                        <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Save" />
                                        <input type="reset" class="btn btn-lg btn-danger" value="Cancel">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </form>
                </div>
                
                <div class="col-lg-12 col-md-12 col-sm-12">
                <form class="form-auth-small" action="" name="employee_education" id="employee_education" method="POST" enctype="multipart/form-data">
                    <div class="card">
                       <div class="header">
                            <h2>Education Details</h2>
                            <a href="javascript:;" onclick="addMoreEducation()" data-toggle="tooltip" data-placement="left" title="Add More" class="header-dropdown"><i class="icon-plus"></i></a>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Qualification</label>
                                        <select class="form-control show-tick" name="qualification_id[0]" id="edu0">
                                            <option value="">Select Qualification</option>
                                            <?php foreach($qualifications as $row) { ?>
                                            <option value="<?= $row['qualification_id']?>"><?= $row['name']?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>Year</label>
                                        <select class="form-control show-tick" name="year[0]" id="yr0">
                                            <option value="">Year</option>
                                            <?php for($i=1947;$i<=date('Y');$i++) { ?>
                                            <option value="<?= $i?>"><?= $i?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Institution</label>
                                        <input type="text" class="form-control" name="institution[0]" id="ins0">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Upload document(s)</label>
                                        <input type="file" name="education_images[0][]" value="" id="doc0" class="form-control file" multiple="">
                                    </div>
                                </div>
                            </div>
                            <div class="ajaxEducation">
                            </div>
                            <input type="hidden" name="ajaxEducation" class="ajaxEducationCount" value="1">
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="mt-4">
                                        <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Save" />
                                        <input type="reset" class="btn btn-lg btn-danger" value="Cancel">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </form>
                </div>
                
                <div class="col-lg-12 col-md-12 col-sm-12">
                <form class="form-auth-small" action="" name="employee_insurance" id="employee_insurance" method="POST" enctype="multipart/form-data">
                    <div class="card">
                       <div class="header">
                            <h2>Insurance Details</h2>
                            <a href="javascript:;" onclick="addMoreInsurance()" data-toggle="tooltip" data-placement="left" title="Add More" class="header-dropdown"><i class="icon-plus"></i></a>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Provider</label>
                                        <input type="text" class="form-control" name="company[0]" id="cmp0">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Cost</label>
                                        <input type="text" class="form-control amount" name="cost[0]" id="cst0">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>Valid upto</label>
                                        <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" name="expiry[0]" class="form-control" id="exp0">                                        
                                    </div>
                                </div>                                
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Upload document(s)</label>
                                        <input type="file" name="insurance_images[0][]" value="" id="ins0" class="form-control file" multiple="">
                                    </div>
                                </div>
                            </div>
                            <div class="ajaxInsurance">
                            </div>
                            <input type="hidden" name="ajaxInsurance" class="ajaxInsuranceCount" value="1">
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="mt-4">
                                        <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Save" />
                                        <input type="reset" class="btn btn-lg btn-danger" value="Cancel">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </form>
                </div>
                
                <div class="col-lg-12 col-md-12 col-sm-12">
                <form class="form-auth-small" action="" name="employee_bank" id="employee_bank" method="POST" enctype="multipart/form-data">
                    <div class="card">
                       <div class="header">
                            <h2>Account Details</h2>
                            <a href="javascript:;" onclick="addMoreBank()" data-toggle="tooltip" data-placement="left" title="Add More" class="header-dropdown"><i class="icon-plus"></i></a>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>Bank name</label>
                                        <input type="text" class="form-control" name="bank_name[0]" id="bname0">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Account number</label>
                                        <input type="text" class="form-control" name="account_number[0]" id="anum0">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>IBAN number</label>
                                        <input type="text" class="form-control" name="iban_number[0]" id="iban0">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>Swift code</label>
                                        <input type="text" class="form-control" name="swift_code[0]" id="cst0">
                                    </div>
                                </div>
                                                                
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Upload document(s)</label>
                                        <input type="file" name="bank_images[0][]" value="" id="bnk0" class="form-control file" multiple="">
                                    </div>
                                </div>
                            </div>
                            <div class="ajaxBank">
                            </div>
                            <input type="hidden" name="ajaxBank" class="ajaxBankCount" value="1">
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="mt-4">
                                        <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Save" />
                                        <input type="reset" class="btn btn-lg btn-danger" value="Cancel">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </form>
                </div>
                
            </div>

<script src="<?= base_url()?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url()?>assets/vendor/dropify/js/dropify.min.js"></script>
<script src="<?= base_url()?>assets/user/js/pages/forms/dropify.js"></script>
<script type="text/javascript">
    $(function() {                               

    $("#employee_additional").validate({
          rules: {
             passport_number: {
               required: function(element) {                  
                   return ($('input[name="passport_images[]"]').get(0).files.length > 0 || $('input[name="passport_expiry"]').val().length > 0);
                }
             },
             passport_expiry: {
               required:function(element){
                   return ($('input[name="passport_images[]"]').get(0).files.length > 0 || $('input[name="passport_number"]').val().length > 0);
                }
             },
             visa_number: {
               required:function(element){
                   return ($('input[name="visa_images[]"]').get(0).files.length > 0 || $('input[name="visa_expiry"]').val().length > 0);
                }
             },
             visa_expiry: {
               required:function(element){
                   return ($('input[name="visa_images[]"]').get(0).files.length > 0 || $('input[name="visa_number"]').val().length > 0);
                }
             },
             emirate_number: {
               required:function(element){
                   return ($('input[name="emirate_images[]"]').get(0).files.length > 0 || $('input[name="emirate_expiry"]').val().length > 0);
                }
             },
             emirate_expiry: {
               required:function(element){
                   return ($('input[name="emirate_images[]"]').get(0).files.length > 0 || $('input[name="emirate_number"]').val().length > 0);
                }
             },
             labour_number: {
               required:function(element){
                   return ($('input[name="labour_images[]"]').get(0).files.length > 0 || $('input[name="labour_expiry"]').val().length > 0);
                }
             },
             labour_expiry: {
               required:function(element){
                   return ($('input[name="labour_images[]"]').get(0).files.length > 0 || $('input[name="labour_number"]').val().length > 0);
                }
             }
         },
         messages: {
            
            passport_number:"Please enter passport number",
            passport_expiry:"Please enter expiry date",
            visa_number:"Please enter visa number",
            visa_expiry:"Please enter expiry date",
            labour_number:"Please enter labour number",
            labour_expiry:"Please enter expiry date",
            emirate_number:"Please enter emirate number",
            emirate_expiry:"Please enter expiry date"
            
         },
         submitHandler: function(form, e) {
               
               $('#employee_additional .btnsmt').prop('disabled', true).attr('value','Processing...');
               e.preventDefault();   
               var employee_id = '<?= $employee_details['employee_id']?>';
               var employee_code = '<?= $employee_details['code']?>';
               var form_data = new FormData(form);
               form_data.append('employee_id',employee_id);
               form_data.append('code',employee_code);
               setTimeout(function() {
               $.ajax({
	 type: 'POST',
	 url: base_url + 'user/employee_additional_process',
	 cache: false,
	 async: false,
	 data: form_data,
	 contentType: false,
	 processData: false,
	    success: function(response)	{
                                
	      var obj = $.parseJSON(response);		          
	      if(obj.status==1) {                       
                        showConfirmMessage(employee_code);
                      } else {
                       toaster('error',obj.msg);
                       $('#employee_additional .btnsmt').prop('disabled', false).attr('value','Save');
                      }
                      
	    },
	    error: function(error) {	      
                      toaster('error',error);
                      $('#employee_additional .btnsmt').prop('disabled', false).attr('value','Save');
	    }
               });
               },500);
               return false;
         }
      });
       
     $("#employee_education").validate({
     rules: {
         "qualification_id[0]": {
           required:true
         }
                
     },
     messages: {
       "qualification_id[0]": {
          required: "Please choose qualification"
       }
     },
     submitHandler: function(form, e) {
       
       $('#employee_education .btnsmt').prop('disabled', true).attr('value','Processing...');         
       e.preventDefault();       
       var employee_id = '<?= $employee_details['employee_id']?>';
       var employee_code = '<?= $employee_details['code']?>';
       var form_data = new FormData(form);
       form_data.append('employee_id',employee_id);
       
       $.ajax({
	 type: 'POST',
	 url: base_url + 'user/employee_education_process',
	 cache: false,
	 async: false,
	 data: form_data,
	 contentType: false,
	 processData: false,
	    success: function(response)	{
                                
                      var obj = $.parseJSON(response);		          
	      if(obj.status==1) {
                        showConfirmMessage(employee_code);                        
                      } else {
                       toaster('error',obj.msg);
                       $('#employee_education .btnsmt').prop('disabled', false).attr('value','Save');
                      }
                                
                    },
	    error: function(error) {	      
                      toaster('error',error);
                      $('#employee_education .btnsmt').prop('disabled', false).attr('value','Save');
	    }
       });
       
       return false;
     }
     
     });
     
     $("#employee_insurance").validate({
     rules: {
         "company[0]": {
           required:true
         }
                
     },
     messages: {
       "company[0]": {
          required: "Please enter provider"
       }
     },
     submitHandler: function(form, e) {
       
       $('#employee_insurance .btnsmt').prop('disabled', true).attr('value','Processing...');         
       e.preventDefault();       
       var employee_id = '<?= $employee_details['employee_id']?>';
       var employee_code = '<?= $employee_details['code']?>';
       var form_data = new FormData(form);
       form_data.append('employee_id',employee_id);
       
       $.ajax({
	 type: 'POST',
	 url: base_url + 'user/employee_insurance_process',
	 cache: false,
	 async: false,
	 data: form_data,
	 contentType: false,
	 processData: false,
	    success: function(response)	{
                                
                      var obj = $.parseJSON(response);		          
	      if(obj.status==1) {
                        showConfirmMessage(employee_code);                        
                      } else {
                       toaster('error',obj.msg);
                       $('#employee_insurance .btnsmt').prop('disabled', false).attr('value','Save');
                      }
                                
                    },
	    error: function(error) {	      
                      toaster('error',error);
                      $('#employee_insurance .btnsmt').prop('disabled', false).attr('value','Save');
	    }
       });
       
       return false;
     }
     
     });
     
      $("#employee_bank").validate({
     rules: {
         "bank_name[0]": {
           required:true
         }
                
     },
     messages: {
       "bank_name[0]": {
          required: "Please enter bank name"
       }
     },
     submitHandler: function(form, e) {
       
       $('#employee_bank .btnsmt').prop('disabled', true).attr('value','Processing...');         
       e.preventDefault();       
       var employee_id = '<?= $employee_details['employee_id']?>';
       var employee_code = '<?= $employee_details['code']?>';
       var form_data = new FormData(form);
       form_data.append('employee_id',employee_id);
       
       $.ajax({
	 type: 'POST',
	 url: base_url + 'user/employee_bank_process',
	 cache: false,
	 async: false,
	 data: form_data,
	 contentType: false,
	 processData: false,
	    success: function(response)	{
                                
                      var obj = $.parseJSON(response);		          
	      if(obj.status==1) {
                        showConfirmMessage(employee_code);                        
                      } else {
                       toaster('error',obj.msg);
                       $('#employee_bank .btnsmt').prop('disabled', false).attr('value','Save');
                      }
                                
                    },
	    error: function(error) {	      
                      toaster('error',error);
                      $('#employee_bank .btnsmt').prop('disabled', false).attr('value','Save');
	    }
       });
       
       return false;
     }
     
     });
       
    });
    
    function addMoreEducation() {

         var count = $('body .ajaxEducationCount').val();

         $.ajax({
            type: 'POST',
            url: base_url + 'user/ajax_education',
            cache: false,
            async: false,
            data: "count="+count,
            dataType:"html",
            success: function(response) {
               $('.ajaxEducation').append(response);
               var newcount = (parseInt(count)+1);
               $('body .ajaxEducationCount').val(newcount);
               
               $('#employee_education select[name*=qualification_id]').each(function() {
                 $(this).rules('add', {
                    required: true,
                    messages: {
                       required: "Please choose qualification",
                    }
                 })
                });
               
            },
            error: function(error) {	      
                toaster('error',error);
            }
         });
     }
     
     function addMoreInsurance() {

         var count = $('body .ajaxInsuranceCount').val();

         $.ajax({
            type: 'POST',
            url: base_url + 'user/ajax_insurance',
            cache: false,
            async: false,
            data: "count="+count,
            dataType:"html",
            success: function(response) {
               $('.ajaxInsurance').append(response);
               var newcount = (parseInt(count)+1);
               $('body .ajaxInsuranceCount').val(newcount);
               
               $('#employee_insurance input[name*=company]').each(function() {
                 $(this).rules('add', {
                    required: true,
                    messages: {
                       required: "Please enter provider",
                    }
                 })
                });
               
            },
            error: function(error) {	      
                toaster('error',error);
            }
         });
     }
     
     function addMoreBank() {

         var count = $('body .ajaxBankCount').val();

         $.ajax({
            type: 'POST',
            url: base_url + 'user/ajax_bank',
            cache: false,
            async: false,
            data: "count="+count,
            dataType:"html",
            success: function(response) {
               $('.ajaxBank').append(response);
               var newcount = (parseInt(count)+1);
               $('body .ajaxBankCount').val(newcount);
               
               $('#employee_bank input[name*=bank_name]').each(function() {
                 $(this).rules('add', {
                    required: true,
                    messages: {
                       required: "Please enter bank name",
                    }
                 })
                });
               
            },
            error: function(error) {	      
                toaster('error',error);
            }
         });
     }
     
     $(document).on('click','.removeP',function(){
        var id = $(this).data("id");
        $('body #row'+id).remove();
     });
     $(document).on('click','.removeP1',function(){
        var id = $(this).data("id");
        $('body #row1'+id).remove();
     });
     $(document).on('click','.removeP2',function(){
        var id = $(this).data("id");
        $('body #row2'+id).remove();
     });
     
</script>
<?php } else {
$this->load->view('theme/user/notfound');
} ?>