<link rel="stylesheet" href="<?= base_url()?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css"/>
<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                <form class="form-auth-small" action="" name="assets_assignment" id="assets_assignment" method="POST" enctype="multipart/form-data">
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
                                    <div class="form-group">
                                      <label>Choose Letter Type<sup>*</sup></label>
                                        <select class="form-control show-tick" name="letter_id" onchange="getLetter()">
                                            <option value="">Select letter type</option>
                                            <?php foreach($letters as $row) { ?>
                                            <option value="<?= $row['letter_id']?>"><?= $row['type']?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>						
                            </div>
                            
                        </div>
                    </div>
	    <div class="card">
		<div class="body">
			<div class="row">
				<div class="col-md-5">
					<label>Ref. No.<sup>*</sup></label>
					<input type="text" class="form-control" name="refno" value=""/>
				</div>
				<div class="col-md-3">
					<label>Date<sup>*</sup></label>
					<input type="text" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="generated_date" class="form-control" value="<?=date('d/m/Y')?>">
				</div>
				<div class="col-sm-12 mt-3">
                                    <textarea id="summernote" name="letterdata"></textarea>
		    
                                </div>
				
			</div>
			<div class="row clearfix">
                                <div class="col-sm-12 d-none" id="actbtn">
                                    <div class="mt-4">
			
                                        <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Generate" />
                                        <input type="reset" class="btn btn-lg btn-danger" value="Cancel">
                                    </div>
                                </div>
                            </div>
		</div>
	    </div>
	    
                  </form>
                </div>
            </div>

<script src="<?= base_url()?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script type="text/javascript">
    $(function() {                               
      $('#summernote').summernote({
        placeholder: 'Please choose a letter type to edit.',
        codeviewFilter: false,
        codeviewIframeFilter: true,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['view', ['fullscreen', 'codeview','undo','redo']]
         ]
      });
      
      $('input[name="code"]').on('input',function(e){      
         $('select[name="letter_id"]').val("");
         $('.note-editable').html("");
         $('#actbtn').addClass('d-none');
         $('input[name="refno"]').val("");
       });
      
       $("#assets_assignment").validate({
          rules: {
             code: {
               required:true,
               remote: {
                url: "user/checkExistCode",
                type: "post"
               }
             },
             letter_id: {
               required:true
             },
             refno: {
	required:true
             },
             generated_date: {
	required:true
             }
         },
         messages: {
            code:{
                required: "Please enter employee code",
                remote: "Employee code not exist"
            },           
            letter_id:"Please choose letter type",
            refno: "Please enter ref. no.",
            generated_date: "Please choose date"
         },
         submitHandler: function(form, e) {
               e.preventDefault();   
               $('.btnsmt').prop('disabled', true).attr('value','Processing...');
                              
               var param = {
	letter_data : $('#summernote').summernote('code'),
	code : $('input[name="code"]').val(),
                letter_id : $('select[name="letter_id"]').val(),
	refno: $('input[name="refno"]').val(),
	generated_date: $('input[name="generated_date"]').val()
               };
               setTimeout(function() {
               $.ajax({
	type: 'POST',
	url: base_url + 'user/generate_letter_process',
	cache: false,
	async: false,
	dataType: "html",
	data: JSON.stringify(param),
	success: function(response) {
	   var obj = $.parseJSON(response);		          
	   if(obj.status==1) {                        
	     
	     swal({
                            title: obj.msg,
                            text: "Do you want to generate another?",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonColor: "#009c3b",
                            confirmButtonText: "No. Print current letter",
                            cancelButtonText: "Yes. Back to form",
                            closeOnConfirm: true,
                            closeOnCancel: true
                        }, function (isConfirm) {
                            if (isConfirm) {
		window.open(base_url+'print-letters/'+obj.employee_letter_id, "_blank");
		window.location.reload();
                            } else {
                                window.location.reload();
                            }
                        });
	     
	   } else {
                     toaster('error',obj.msg);
	     $('.btnsmt').prop('disabled', false).attr('value','Generate');
	   }
	},
	error: function(error) {
	  toaster('error',error);
	  $('.btnsmt').prop('disabled', false).attr('value','Generate');
	}
             });
               },500);
             return false;
          }
      });
       
    });
    
    function getLetter() {
	
        var code = $('input[name="code"]').val();
        var letter_id = $('select[name="letter_id"]').val();
        
        if (code == "") {
            toaster('error',"Enter employee code"); 
        } else {
	
	$('.note-editable').html("");
	$('#actbtn').removeClass('d-none');
	
	$.ajax({
	    type: 'POST',
	    url: base_url + 'user/get_letter',
	    cache: false,
	    async: false,
	    data: "letter_id="+letter_id+"&code="+code,
	    dataType:"html",
	    success: function(response) {              
	        if(response!='invalid') {
	          var obj = JSON.parse(response);	
	          $('#summernote').summernote('code', obj.letterContent);
	          $('input[name="refno"]').val(obj.refno);
	        } else {
	          toaster('error',"Invalid");
	          $('#actbtn').addClass('d-none');
	          $('input[name="refno"]').val("");
	        }	    
	    },
	    error: function(error) {	      
	        toaster('error',error);   
	    }
	 });
        }
    }
</script>