<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.bootstrap4.min.css">
<div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">
                       
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-hover dataTable table-custom table-striped m-b-0 no-alignment" id="employee_master">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Employee Code</th>
                                            <th>Name</th>
                                            <th>Designation</th>
                                            <th>Department</th>
                                            <th>Date of birth</th>
                                            <th>Age</th>
                                            <th>Date of join</th>
                                            <th>Last worked date</th>
                                            <th>Current address</th>
                                            <th>Permanent address</th>
                                            <th>Local Mob. No.</th>
                                            <th>Home Phone</th>
                                            <th>Office Phone</th>
                                            <th>Official Email</th>
                                            <th>Personal Email</th>
                                            <th>Nationality</th>
                                            <th>Country</th>
                                            <th>Overtime</th>
                                            <th>Passport number</th>
                                            <th>Passport Expiry</th>
                                            <th>Passport with</th>
                                            <th>Visa number</th>
                                            <th>Visa expiry</th>
                                            <th>Labour number</th>
                                            <th>Labour expiry</th>
                                            <th>Emirate number</th>
                                            <th>Emirate expiry</th>
                                            <th>Unified No.</th>
                                            <th>Basic pay</th>
                                            <th>HRA</th>
                                            <th>Transport</th>
                                            <th>Special allowance</th>
                                            <th>Others</th>
                                            <th>Education</th>
                                            <th>Insurance</th>
                                            <th>Bank Details</th>
                                            <th>Locations</th>
                                            <th>Assigned Heads</th>
                                            <th>Assigned Roles</th>
                                            <th>Current status</th>
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
        
       

        var table = $('#employee_master').DataTable({
            "ajax": base_url+"user/employees_master_ajax",           
            "scrollY": 450,
            "scrollX": true,
            "columnDefs": [
                { className: "text-nowrap", "targets": [ 0,1,2,28,29,30,31,32,33,34 ] }
            ],
             dom: 'Bfrtip',
                buttons: [      
                 {
                     extend: 'excelHtml5',
                     exportOptions: {
                         columns: [0,':visible'],
                         stripHtml: false,
                         format: {
                           body: function ( data, column, row ) {
                              return data.replace(/<br\s*\/?>/ig, "\r\n").replace(/<.*?>/ig, "");
                           }
                         },
                     },
                     customize: function( xlsx ) {
                                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                $('row c', sheet).attr( 's', '50' );
                                $('row c', sheet).attr( 's', '55' );
                                
                            },
                 },          
                 'colvis'
             ],
            "columns": [ 
                { "data": "code" },
                { "data": "name" },
                { "data": "designation" },
                { "data": "department" },
                { "data": "date_of_birth" },
                { "data": "age" },
                { "data": "date_of_join" },
                { "data": "last_work_date" },
                { "data": "current_address" },
                { "data": "permanent_address" },
                { "data": "phone_current" },
                { "data": "phone_home" },
                { "data": "phone_office" },
                { "data": "email" },
                { "data": "personal_email" },
                { "data": "nationality" },
                { "data": "country" },
                { "data": "overtime" },
                { "data": "passport_number" },
                { "data": "passport_expiry" },
                { "data": "passport_with" },
                { "data": "visa_number" },
                { "data": "visa_expiry" },
                { "data": "labour_number" },
                { "data": "labour_expiry" },
                { "data": "emirate_number" },
                { "data": "emirate_expiry" },
                { "data": "unified_no" },
                { "data": "basic_pay" },
                { "data": "hra" },
                { "data": "transport" },
                { "data": "special_allowance" },
                { "data": "others" },
                { "data": "education" },
                { "data": "insurance" },
                { "data": "bank_details" },
                { "data": "locations" },
                { "data": "heads" },
                { "data": "roles" },
                { "data": "status" }
            ],
            "order": [[1, 'asc']]
        });
         
});
</script>