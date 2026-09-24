<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                <form class="form-auth-small" action="" name="rejoin" id="rejoin" method="POST" enctype="multipart/form-data">
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
                                    <div class="mt-4">
                                        <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Get Status" />
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
                        </div>
                        <div class="body">
		<div class="table-responsive">
                                <table class="table table-hover dataTable table-custom table-striped m-b-0 c_list" id="rejoin_list">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Leave Type</th>
                                            <th>Leave Status</th>
                                            <th>Leave Date</th>
                                            <th>Rejoin Status</th>		            
		            <th>Rejoin Date</th>
		            <th>Total Days</th>
		            <th>Paid</th>
		            <th>Unpaid</th>
		            <th>Remarks</th>
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

<script src="<?= base_url()?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/user/bundles/datatablescripts.bundle.js"></script>
<script src="<?= base_url() ?>assets/user/js/pages/tables/jquery-datatable.js"></script>
<script type="text/javascript">
    var table;
    $(function() {
	 
       table = $('#rejoin_list').DataTable({
        "columns": [
                { "data": "leave_type"},                 
                { "data": "leave_status" },
                { "data": "leave_date" }, 
                { "data": "rejoin_status" },
                { "data": "rejoin_date" },
	{ "data": "total_days" },
	{ "data": "paid" },
	{ "data": "unpaid" },
	{ "data": "remarks" },
	{ "data": "action" }
            ],
            "order": [[4, 'desc']]
        });
       
       $('input[name="code"]').on('input',function(e){      
         table.clear().draw();
       });

       $("#rejoin").validate({
          rules: {
             code: {
               required:true,
               remote: {
                url: "user/checkExistCode",
                type: "post"
               }
             }
         },
         messages: {
            code:{
                required: "Please enter employee code",
                remote: "Employee code not exist"
            }
         },
         submitHandler: function(form, e) {
	
               e.preventDefault();  
               var form_data = new FormData(form);
	
               
               $.ajax({
	type: 'POST',
	url: base_url + 'user/rejoin_status_list',
	cache: false,
	async: false,
	data: form_data,
	contentType: false,
	processData: false,
	success: function(response) {
	   var obj = $.parseJSON(response);
                   table.clear();
	   table.rows.add(obj).draw();
	},
	error: function(error) {
	   toaster('error',error);
	}
	});
               return false;
               }
      });
       
       
    });
    
    
</script>