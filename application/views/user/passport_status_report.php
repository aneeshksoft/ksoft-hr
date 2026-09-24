<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
<div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                       
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover dataTable table-custom table-striped m-b-0 no-alignment" id="passport_status">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Employee Code</th>
                                            <th>Name</th>
                                            <th>Designation</th>
                                            <th>Department</th>                                            
                                            <th>Passport number</th>
                                            <th>Passport Expiry</th>
                                            <th>Passport with</th>
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
            </div>
<script src="<?= base_url() ?>assets/user/bundles/datatablescripts.bundle.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
        
       

        var table = $('#passport_status').DataTable({
            "ajax": base_url+"user/passport_status_ajax",
            "columnDefs": [
                { className: "text-nowrap", "targets": [ 0,1,2,3,4,5,6 ] }
            ],
             dom: 'Bfrtip',
                buttons: [      
                 {
                     extend: 'excelHtml5',
                     exportOptions: {
                         stripHtml: false,
                         format: {
                           body: function ( data, column, row ) {
                              return data.replace(/<br\s*\/?>/ig, "\r\n").replace(/<.*?>/ig, "");
                           }
                         },
                     }
                 }
             ],
            "columns": [ 
                { "data": "code" },
                { "data": "name" },
                { "data": "designation" },
                { "data": "department" },                
                { "data": "passport_number" },
                { "data": "passport_expiry" },
                { "data": "passport_with" },
                { "data": "passport_with_remarks" }
            ],
            "order": [[1, 'asc']]
        });
         
});
</script>