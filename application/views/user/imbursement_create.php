<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                <form class="form-auth-small" action="" name="imbursement_create" id="imbursement_create" method="POST" enctype="multipart/form-data">
                    <div class="card">
                        <div class="body">
                            <div class="row clearfix">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Employee code<sup>*</sup></label>
                                        <input type="text" class="form-control" name="code" autofocus>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    
                                        <a href="javascript:;" onclick="addMoreImbursement()" data-toggle="tooltip" data-placement="left" title="Add more details" class="header-dropdown" style="position: absolute;right: 11px;z-index: 9999;top: -5px;"><i class="icon-plus"></i></a>
                                    
                                    <div class="row">
		       <div class="col-md-2 col-sm-12">
                                           <div class="form-group">
                                               <label>Date<sup>*</sup></label>       
                                               <input type="text" class="form-control" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="imbursement_date[0]" id="idt0">
                                           </div>
                                       </div>
                                        <div class="col-md-5 col-sm-12">
                                           <div class="form-group">
                                               <label>Details<sup>*</sup></label>       
                                               <input type="text" class="form-control" name="details[0]" id="id0">
                                           </div>
                                       </div>
                                        <div class="col-md-2 col-sm-12">
                                           <div class="form-group">
                                               <label>Amount<sup>*</sup></label>       
                                               <input type="text" class="form-control amount" name="amount[0]" id="ia0">
                                           </div>
                                       </div>
		        <div class="col-md-3 col-sm-12">
			<div class="form-group">
			    <label>Upload document(s)</label>
			    <input type="file" name="imbursement_images[0][]" value="" id="im0" class="form-control file" multiple="">
			</div>
		            </div>
                                    </div>
                                    <div class="row ajaxImbursement">
                                      
                                    </div>
		    <div class="row">
			<div class="col-md-2 col-sm-12">
                                           &nbsp;
                                       </div>
                                        <div class="col-md-5 col-sm-12 text-right">
                                          <h5>Total Amount</h5>
                                       </div>
                                        <div class="col-md-5 col-sm-12">
                                          <h5><?= CURRENCY.' '?><span class="tmnt">0.00</span></h5>
                                       </div>
		        
		    </div>
		    
                                    <input type="hidden" name="ajaxImbursement" class="ajaxImbursementCount" value="1">
                                </div>
                               
								
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="mt-4">
                                        <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Save" />
                                        <a href="<?=base_url('imbursement-create')?>" class="btn btn-lg btn-danger">Cancel</a>
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

       $("#imbursement_create").validate({
          rules: {
             code: {
               required:true,
               remote: {
                url: "user/checkExistCode",
                type: "post"
               }
             },
             "imbursement_date[0]": {
               required:true
             },
             "details[0]": {
               required:true
             },
             "amount[0]": {
               required:true
             }
         },
         messages: {
            code:{
                required: "Please enter employee code",
                remote: "Employee code not exist"
            },           
            "imbursement_date[0]":"Please enter date",
            "details[0]":"Please enter details",
            "amount[0]":"Please enter amount"
         },
         submitHandler: function(form, e) {
               e.preventDefault();   
               $('.btnsmt').prop('disabled', true).attr('value','Processing...');               
               var form_data = new FormData(form);	
               setTimeout(function() {
               $.ajax({
	type: 'POST',
	url: base_url + 'user/imbursement_create_process',
	cache: false,
	async: false,
	data: form_data,
	contentType: false,
	processData: false,
	success: function(response)	{
	 var obj = $.parseJSON(response);		          
	 if(obj.status==1) {                        
                        toaster('success',obj.msg);
	        setTimeout(function(){
		window.location.reload();      
	        },2000);                                        
                        
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
       
       $(document).on('keyup','body .amount', function () {
            calcAmount();            
      });
       
       
    }); 
    
    function addMoreImbursement() {

         var count = $('body .ajaxImbursementCount').val();

         $.ajax({
            type: 'POST',
            url: base_url + 'user/ajax_imbursement',
            cache: false,
            async: false,
            data: "count="+count,
            dataType:"html",
            success: function(response) {
               $('.ajaxImbursement').append(response);
               var newcount = (parseInt(count)+1);
               $('body .ajaxImbursementCount').val(newcount);
               $('#imbursement_create input[name*=imbursement_date]').each(function() {
                 $(this).rules('add', {
                    required: true,
                    messages: {
                       required: "Please enter date",
                    }
                 })
                });
               
               $('#imbursement_create input[name*=details]').each(function() {
                 $(this).rules('add', {
                    required: true,
                    messages: {
                       required: "Please enter details",
                    }
                 })
                });
               
               $('#imbursement_create input[name*=amount]').each(function() {
                 $(this).rules('add', {
                    required: true,
                    messages: {
                       required: "Please enter amount",
                    }
                 })
                });
               
            },
            error: function(error) {	      
                toaster('error',error);
            }
         });
     }
     $(document).on('click','.removeP',function(){
        var id = $(this).data("id");
        $('body #t'+id).remove();
        $('body #d'+id).remove();
        $('body #s'+id).remove();
        $('body #a'+id).remove();
        calcAmount();
     });
     
     function calcAmount() {
        var tot = 0;	
            $('body .amount').each(function() { 
                tot += Number($(this).val());               
            });
           
            if(tot=='' || tot== null || tot === 'undefined' || isNaN(tot)) {
                tot = 0;
            }			
            tot = Number(tot);
            $('.tmnt').html(tot.toFixed(2));
     }
     
     
</script>