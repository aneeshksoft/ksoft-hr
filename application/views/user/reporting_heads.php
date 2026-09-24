<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                <form class="form-auth-small" action="" name="head_assignment" id="head_assignment" method="POST" enctype="multipart/form-data">
                    <div class="card mb-3">
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
                                      <label>Role<sup>*</sup></label>
                                        <select class="form-control show-tick" name="role_id" onchange="getHeads(this)">
                                            <option value="">Select role</option>
                                            <?php foreach($roles as $row) { ?>
                                            <option value="<?= $row['role_id']?>"><?= $row['name']?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">                                    
                                    <div class="form-group">
                                      <label>Head<sup>*</sup></label>
                                        <select class="form-control show-tick" name="reporting_head_id">
                                            <option value="">Select head</option>    
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">                                    
                                    <div class="form-group mt-4">
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
                    <div class="card">
                        <div class="header">
                            <h2>Current Assignments</h2>
                            <ul class="header-dropdown">
                                <li><a href="javascript:void(0);" class="btn btn-info" onclick="getAssignment()">Show Assignments</a></li>
                            </ul>
                        </div>
                        <div class="body">
                           <div class="table-responsive">
                                <table class="table table-hover dataTable table-custom table-striped m-b-0 c_list" id="assignment_list">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                &nbsp;
                                            </th>
                                            <th>Name</th>
                                            <th>Employee Code</th>
                                            <th>Role</th>
                                            <th>Assigned Date</th>  
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<script src="<?= base_url() ?>assets/user/bundles/datatablescripts.bundle.js"></script>
<script src="<?= base_url() ?>assets/user/js/pages/tables/jquery-datatable.js"></script>
<script type="text/javascript">
    var table;
    $(function() {
        
       table = $('#assignment_list').DataTable({
        "columns": [
                { "data": "profile_photo"},                 
                { "data": "name" },
                { "data": "code" }, 
                { "data": "role" },
                { "data": "created_at" },
                { "data": "action" }
            ],
            "order": [[1, 'desc']]
        });
       
       $('input[name="code"]').on('input',function(e){      
         table.clear().draw();
       });

       $("#head_assignment").validate({
          rules: {
             code: {
               required:true,
               remote: {
                url: "user/checkExistCode",
                type: "post"
               }
             },
             role_id: {
               required:true
             },
             reporting_head_id: {
               required:true
             }
         },
         messages: {
            code:{
                required: "Please enter employee code",
                remote: "Employee code not exist"
            },           
            role_id:"Please choose role",
            reporting_head_id:"Please choose head"
         },
         submitHandler: function(form, e) {
               e.preventDefault();
               
               $('.btnsmt').prop('disabled', true).attr('value','Processing...');
               
               var form_data = new FormData(form);
	
               setTimeout(function() {
               $.ajax({
				type: 'POST',
				url: base_url + 'user/head_assignment_process',
				cache: false,
				async: false,
				data: form_data,
				contentType: false,
				processData: false,
				   success: function(response)	{
										   
					 var obj = $.parseJSON(response);		          
					 if(obj.status==1) {
                        toaster('success',obj.msg);
                        $('.btnsmt').prop('disabled', false).attr('value','Save');
                        $('select[name="role_id"]').val("");
                        $('select[name="reporting_head_id"]').empty().append('<option value="">Select head</option>');
                        getAssignment();
                      } else {
                       toaster('error',obj.msg);
                       $('.btnsmt').prop('disabled', false).attr('value','Save');
                      }
                      
					},
					error: function(error) {	      
								  toaster('error',error);
								  $('.btnsmt').prop('disabled', false).attr('value','Save');
					}
						   });
               },500);
						   return false;
					 }
      });
       
    });
    
    function getAssignment() {
        
       var code = $('input[name="code"]').val(); 
       if(code != "") {     
        $.ajax({
             type: 'POST',
             url: base_url + 'user/get_assignments',
             cache: false,
             async: false,
             data: "code="+code,
             dataType:"html",
             success: function(response) {               
               
                 var obj = JSON.parse(response);                
                 table.clear();
				 table.rows.add(obj).draw();	
                 
             }
        });
       } else {
        toaster('error',"Please enter employee code!");
       }
    
     }
    
</script>