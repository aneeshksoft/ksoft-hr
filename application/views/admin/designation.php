<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                <form class="form-auth-small" action="" name="add_designation" id="add_designation" method="POST" enctype="multipart/form-data">
                    <div class="card mb-3">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Department Name<sup>*</sup></label>
                                        <input type="text" class="form-control" name="name" autofocus>
                                        <input type="hidden" class="form-control" name="designation_id">
                                    </div>
                                </div> 
                                <div class="col-md-3 col-sm-12">                                    
                                    <div class="form-group mt-4">
                                        <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Save" />
                                        <a href="<?=base_url('designation')?>" class="btn btn-lg btn-danger">Cancel</a>
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
                            <h2>All Departments</h2>
                        </div>
                        <div class="body">
                           <div class="table-responsive">
                                <table class="table table-hover dataTable table-custom table-striped m-b-0 c_list" id="designation_list">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Name</th>
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
        
        table = $('#designation_list').DataTable({
            "ajax": base_url+"admin/designation_list_ajax",
            "columns": [
                {"data": "name"},
                {"data": "action"}
            ],
            "order": [[0, 'asc']]
        });
       
       $("#add_designation").validate({
          rules: {
             name: {
               required:true,
               remote: {
                url: "admin/checkDesignationExist",
                type: "post"
               }
             }
         },
         messages: {
            name:{
                required: "Please enter designation name",
                remote: "Already exist"
            }
         },
         submitHandler: function(form, e) {
                
               e.preventDefault();               
               $('.btnsmt').prop('disabled', true).attr('value','Processing...');               
               var form_data = new FormData(form);
               setTimeout(function() {
               $.ajax({
                type: 'POST',
                url: base_url + 'admin/designation_process',
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
                        $('input[name="designation_id"]').val("");
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