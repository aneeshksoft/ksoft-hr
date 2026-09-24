<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<div class="row clearfix">
                    <div class="col-lg-12">
                    <div class="card">
                       
                        <div class="body">
                                        <div class="row">
                   
                    <div class="col-md-4 col-sm-12">
                        <label>Choose Role Type<sup>*</sup></label>
                            <select class="form-control show-tick" name="role_id">
                                <option>Select Role Type</option>
                                <?php foreach($roles as $row) { if($row['role_id'] == "5") {continue;}?>
                                   <option value="<?= $row['role_id']?>"><?= $row['name']?></option>
                                <?php } ?>
                            </select>
                        
                    </div>
                                        </div>
                        </div>
                    </div>
                    
                    <div class="card">
                       <div class="header">
                            <h2>Assigned Permissions</h2>
                        </div>
                        <div class="body">
                                        
                                        <div class="row">
                    
                    <div class="col-12">
                        
                        
                        <table class="table table-hover dataTable table-custom table-striped m-b-0 no-alignment" id="permission_list">
                                    <thead class="thead-dark">
                                        <th>Module</th>
                                        <th width="15%">View</th>
                                        <th width="15%">Add</th>
                                        <th width="15%">Edit</th>
                                        <th width="15%">Delete</th>
                                        <th width="15%">Approve/Reject</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                        </table>
                    </div>
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
        
       table = $('#permission_list').DataTable({
            //"ajax": base_url+"admin/permission_list_ajax",
            "columns": [
                {"data": "module"},
                {"data": "view"},
                {"data": "add"},
                {"data": "edit"},
                {"data": "delete"},
                {"data": "approvereject"}
            ],
            "order": [[0, 'asc']]
        });
       
       $('select[name="role_id"]').on("change",function(){
          getPermissions();
       });
       
       $(document).on('change','input[type="checkbox"]',function() {
          
          var role_id = $(this).data('role');
          var module_id = $(this).data('module');
          var type = $(this).data('type');
          var value;
          if(this.checked) { 
            value = 1;
          } else {
            value = 0;
          }
          
          $.ajax({
            type: 'POST',
            url: base_url + 'admin/permission_process',
            cache: false,
            async: false,
            data: "role_id="+role_id+"&module_id="+module_id+"&type="+type+"&value="+value,
            dataType:"html",          
            success: function(response) {              
              var obj = $.parseJSON(response);		          
                if(obj.status==1) {
                toaster('success',obj.msg);
              } else {
                toaster('error',obj.msg);            
              }
            },
            error: function(error) {	      
                toaster('error',obj.msg);
            }
        });
          
       });
       
    });
    
    function getPermissions() {        
       var role_id = $('select[name="role_id"]').val(); 
       if(role_id != "") {     
        $.ajax({
             type: 'POST',
             url: base_url + 'admin/permission_ajax',
             cache: false,
             async: false,
             data: "role_id="+role_id,
             dataType:"html",
             success: function(response) {                 
                 var obj = JSON.parse(response);                 
                 table.clear();
                 table.rows.add(obj).draw();
             }
        });
       } else {
        toaster('error',"Please choose a role!");
       }    
    }
</script>