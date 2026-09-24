<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                <form class="form-auth-small" action="" name="leave_application" id="leave_application" method="POST" enctype="multipart/form-data">
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Employee code<sup>*</sup></label>
                                        <input type="text" class="form-control" name="code" autofocus>
                                    </div>
                                </div>                                
                                <div class="col-md-3 col-sm-12">                                    
                                    <div class="form-group">
                                      <label>Leave type<sup>*</sup></label>
                                        <select class="form-control show-tick" name="leave_type_id">
                                            <option value="">Select leave type</option>
                                            <?php foreach($leave_types as $row) { ?>
                                            <option value="<?= $row['leave_type_id']?>"><?= $row['title']?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                    <label>Range<sup>*</sup></label>
                                    <div class="input-daterange input-group" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true">
                                        <input type="text" class="form-control" name="from_date">
                                        <span class="input-group-addon">&nbsp;&nbsp;to&nbsp;&nbsp;</span>
                                        <input type="text" class="form-control" name="to_date">
                                    </div>
                                </div> 
                             
		<div class="col-md-6 col-sm-12">                                    
                                    <div class="form-group">
                                        <label>Reason</label>
                                        <textarea class="form-control" name="remarks" rows="4" cols="30"></textarea>
                                    </div>
                                </div>
		<div class="col-md-6" id="eligibilityWrapper">		
		</div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="mt-4">
		        <input type="button" class="btn btn-lg btn-warning" onclick="checkLeaveEligibility()" value="Check Eligibility">
                                        <input type="submit" class="btn btn-lg btn-primary btnsmt hbtn d-none" value="Apply Now" />
                                        <input type="reset" class="btn btn-lg btn-danger hbtn d-none" value="Cancel">		        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                  </form>
                </div>
            </div>

<script src="<?= base_url()?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
    $(function() {                               

       $("#leave_application").validate({
          rules: {
             code: {
               required:true,
               remote: {
                url: "user/checkExistCode",
                type: "post"
               }
             },
             leave_type_id: {
               required:true
             },
             from_date: {
               required:true
             },
             to_date: {
               required:true
             }
         },
         messages: {
            code:{
                required: "Please enter employee code",
                remote: "Employee code not exist"
            },           
            leave_type_id:"Please choose leave type",
            from_date:"Please choose from date",
            to_date:"Please choose to date"
         },
         submitHandler: function(form, e) {
               e.preventDefault();   
               $('.btnsmt').prop('disabled', true).attr('value','Processing...');
               
               var form_data = new FormData(form);
	
               setTimeout(function() {
               $.ajax({
	type: 'POST',
	url: base_url + 'user/leave_application_process',
	cache: false,
	async: false,
	data: form_data,
	contentType: false,
	processData: false,
	success: function(response) {
	  var obj = $.parseJSON(response);
	  if(obj.status==1) {                     
                        
	        toaster('success',obj.msg);
                        setTimeout(function(){
                                window.location.reload();
	        },2000);
                           
	        
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