<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                <form class="form-auth-small" action="" name="add_role" id="add_role" method="POST" enctype="multipart/form-data">
                    <div class="card mb-3">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Role<sup>*</sup></label>
                                        <input type="text" class="form-control" name="name" autofocus>
                                        <input type="hidden" class="form-control" name="role_id">
                                    </div>
                                </div>
		<div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                         <label>Reporting Head Assignment<sup>*</sup></label>       
                                        <select class="form-control show-tick" name="head_assignment">
		            <option value="">Select</option>
		            <option value="no">NO</option>
                                            <option value="yes">YES</option>                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">                                    
                                    <div class="form-group mt-4">
                                        <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Save" />
                                        <a href="<?=base_url('role')?>" class="btn btn-lg btn-danger">Cancel</a>
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
                            <h2>All Roles</h2>
                        </div>
                        <div class="body">
                           <div class="table-responsive">
                                <table class="table table-hover dataTable table-custom table-striped m-b-0 c_list" id="role_list">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Name</th>
		            <th>Reporting Head Assignment</th>
                                            <th width="20%">Action</th>
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
        
        table = $('#role_list').DataTable({
            "ajax": base_url+"admin/role_list_ajax",
            "columns": [
                {"data": "name"},
	{"data": "head_assignment"},
                {"data": "action"}
            ],
            "order": [[0, 'asc']]
        });
       
       $("#add_role").validate({
          rules: {
             name: {
               required:true,
               remote: {
                url: "admin/checkRoleExist",
                type: "post",
	data: {
	  'role_id': function () { return $('input[name="role_id"]').val(); }
	},
               }
             },
             head_assignment: {
               required:true
             }
         },
         messages: {
            name:{
                required: "Please enter role name",
                remote: "Already exist"
            },
            head_assignment: {
	required: "Please choose head assignment"
            }
         },
         submitHandler: function(form, e) {
                
               e.preventDefault();               
               $('.btnsmt').prop('disabled', true).attr('value','Processing...');               
               var form_data = new FormData(form);
               setTimeout(function() {
               $.ajax({
                type: 'POST',
                url: base_url + 'admin/role_process',
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
                        $('input[name="name"]').val("");
	        $('select[name="head_assignment"]').val("");
                        $('input[name="role_id"]').val("");
	        table.ajax.reload();
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
</script>