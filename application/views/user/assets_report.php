<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                <form class="form-auth-small" action="" name="asset_report" id="asset_report" method="POST" enctype="multipart/form-data">
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Employee code</label>
                                        <input type="text" class="form-control" name="code" autofocus>
                                    </div>
                                </div>
		<div class="col-md-3 col-sm-12">                                    
                                    <div class="form-group">
                                      <label>Asset Type</label>
                                        <select class="form-control show-tick" name="asset_id">
                                            <option value="">Select asset</option>
                                            <?php foreach($assets as $row) { ?>
                                            <option value="<?= $row['asset_id']?>"><?= $row['name']?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
		<div class="col-md-3 col-sm-12">
                                    <div class="mt-4">
                                        <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Get Report" />
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
                    
                        <div class="body">
		<div class="table-responsive">
                                <table class="table table-hover dataTable table-custom table-striped m-b-0 no-alignment c_list" id="asset_report_list">
                                    <thead class="thead-dark">
                                        <th>
                                                &nbsp;
                                            </th>
                                            <th>Name</th>
                                            <th>Employee Code</th>
                                            <th>Designation & Department</th>
                                            <th>Assets</th>
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
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
<script type="text/javascript">
    var table;
    $(function() {
	
       table = $('#asset_report_list').DataTable({ 
        
        "columns": [
	{ "data": "profile_photo"},
                { "data": "name" },
                { "data": "code" },
                { "data": "designation" },            
                { "data": "assets" }
            ],
            "order": [[1, 'desc']]
        });

       $("#asset_report").validate({
          rules: {
             code: {
               remote: {
                url: "user/checkExistCode",
                type: "post"
               }
             }
         },
         messages: {
            code:{
                remote: "Employee code not exist"
            }
         },
         submitHandler: function(form, e) {
	
               e.preventDefault();  
               var form_data = new FormData(form);	
               asset_reports(form_data);               
               return false;
               }
      });
       
       asset_reports(); 
    });
    
    function asset_reports(form_data) {
        $.ajax({
	type: 'POST',
	url: base_url + 'user/asset_report_ajax',
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
    }
    
</script>