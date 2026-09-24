<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                <form class="form-auth-small" action="" name="leave_report" id="leave_report" method="POST" enctype="multipart/form-data">
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
                                     <label>From<sup>*</sup></label>
                                        <input type="text" data-provide="datepicker" data-date-format="yyyy" data-date-autoclose="true" name="from_date" data-date-min-view-mode="years" data-date-view-mode="years" class="form-control" >
                                    </div>
                                </div>
		<div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                     <label>To<sup>*</sup></label>
                                        <input type="text" data-provide="datepicker" data-date-format="yyyy" data-date-autoclose="true" name="to_date" data-date-min-view-mode="years" data-date-view-mode="years" class="form-control" >
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
                        <div class="header">
                            <h2>Leave History</h2>
                        </div>
                        <div class="body">
		<div class="table-responsive">
                                <table class="table table-hover dataTable table-custom table-striped m-b-0 no-alignment c_list" id="leave_report_list">
                                    <thead class="thead-dark">
                                        <tr>
			<th>#</th>
			<th>Employee</th>
			<th>Code</th>
		           <th>Leave Type</th>
                                            <th>Leave From</th>
                                            <th>Rejoin</th>
                                            <th>Total Days</th>
                                            <th>Paid</th>		            
		            <th>Unpaid</th>
		            <th>Remarks</th>	
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
	        </div>
	    </div>
	</div>
	
	<!--<div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="header">
                            <h2>Leave Annual Summary</h2>
                        </div>
                        <div class="body">
		<div class="table-responsive">
                                <table class="table table-hover dataTable table-custom table-striped m-b-0 no-alignment" id="leave_annual_list">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Total Annual</th>
                                            <th>Annual Enjoyed</th>		            
		            <th>Balance Annual</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
	        </div>
	    </div>
	</div>-->
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
    var table,table1;
    $(function() {
	 
       table = $('#leave_report_list').DataTable({ 
        dom: 'Bfrtip',
                buttons: [      
                 {
                     extend: 'excelHtml5',
                     exportOptions: {
                         stripHtml: false,
                         format: {
                           body: function ( data, column, row ) {
                              return data.replace(/<.*?>/ig, "");
                           }
                         }
                     }             
                 }
             ],
	"columnDefs": [
                { className: "text-nowrap", "targets": [1] }
            ],
        "columns": [
	{ "data": "profile_photo"},
	{ "data": "name"},
	{ "data": "code"},  
	{ "data": "leave_type"},  
                { "data": "leave_date"},                 
                { "data": "rejoin_date" },
                { "data": "total_days" }, 
                { "data": "paid" },
                { "data": "unpaid" },
	{ "data": "remarks" }
            ],
        "ordering": false
            //"order": [[0, 'desc']]
        });
        
        table1 = $('#leave_annual_list').DataTable({
	dom: 'Bfrtip',
                buttons: [      
                 {
                     extend: 'excelHtml5',
                     exportOptions: {
                         stripHtml: false,
                         format: {
                           body: function ( data, column, row ) {
                              return data;
                           }
                         }
                     }             
                 }
             ],	
        "columns": [
                { "data": "start_date"},                 
                { "data": "end_date" },
                { "data": "total_annual" },
                { "data": "annual_enjoyed" },
	{ "data": "balance_annual" }
            ],
        "ordering": false
            //"order": [[0, 'desc']]
        });
       
       $('input[name="code"],input[name="from_date"],input[name="to_date"]').on('input',function(e){      
         table.clear().draw();
         table1.clear().draw();
       });

       $("#leave_report").validate({
          rules: {
             code: {
               remote: {
                url: "user/checkExistCode",
                type: "post"
               }
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
                remote: "Employee code not exist"
            },
            from_date: {
	required: "Please choose from year"
            },
            to_date: {
	required: "Please choose to year"
            }
         },
         submitHandler: function(form, e) {
	
               e.preventDefault();  
               var form_data = new FormData(form);
	
               leave_history(form_data);
               //leave_annual(form_data);
               
               return false;
               }
      });
       
       
    });
    
    function leave_history(form_data) {
        $.ajax({
	type: 'POST',
	url: base_url + 'user/leave_history_list',
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
    
//    function leave_annual(form_data) {
//        $.ajax({
//	type: 'POST',
//	url: base_url + 'user/leave_annual_list',
//	cache: false,
//	async: false,
//	data: form_data,
//	contentType: false,
//	processData: false,
//	success: function(response) {
//	   var obj = $.parseJSON(response);
//                   table1.clear();
//	   table1.rows.add(obj).draw();
//	},
//	error: function(error) {
//	   toaster('error',error);
//	}
//       });
//    }
    
</script>