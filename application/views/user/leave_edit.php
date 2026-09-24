<?php
$leave = $this->common_model->leave_by_id($param2);
//pr($leave);
$status = $this->common_model->selectOne("leave_application_status","(status = 'approved' or status = 'rejected') and leave_application_id=".$param2,"*");

$leave_types  = $this->common_model->selectAll('leave_types','','');
if(!empty($leave)) {
?>

<div class="row clearfix">
    <?php if(empty($status)) {?>
    <div class="col-md-12 col-sm-12">
        <div class="form-group">

           <form class="form-auth-small" action="" name="edit_leave" id="edit_leave" method="POST" enctype="multipart/form-data">
            <div class="row clearfix">                
                <div class="col-md-12 col-sm-12">
                  <div class="form-group">
                    <label>Leave type<sup>*</sup></label>
                    <select class="form-control show-tick" name="leave_type_id">
                      <option value="">Select leave type</option>
                      <?php foreach($leave_types as $row) { ?>
                      <option value="<?= $row['leave_type_id']?>" <?=($leave['leave_type_id']==$row['leave_type_id'])?'selected':''?>>
                        <?= $row['title']?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <label>Range<sup>*</sup></label>
                  <div class="input-daterange input-group" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true">
                    <input type="text" class="form-control" name="from_date" value="<?=get_date($leave['from_date'])?>">
                    <span
                      class="input-group-addon">&nbsp;&nbsp;to&nbsp;&nbsp;</span>
                    <input type="text" class="form-control" name="to_date" value="<?=get_date($leave['to_date'])?>">
                  </div>
                </div>
                <div class="col-md-12 col-sm-12 mt-2">
                  <div class="form-group">
                    <label>Reason</label>
                    <textarea class="form-control" name="remarks" rows="4" cols="30"><?=$leave['remarks']?></textarea>
                  </div>
                </div>
                
                <div class="col-sm-12">                   
                        <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />                   
                </div>
            </div>
           </form>
    
        </div>
    </div>
    <?php } else {?>
    <div class="col-md-12"><div class="alert alert-danger" role="alert">You can't edit this leave application!</div></div>
    <?php } ?>
</div>

<script type="text/javascript">
$("#edit_leave").validate({		  
          rules: {
             
          },
          messages: {
            
          },
          submitHandler: function(form, e) {
            
            
               e.preventDefault();
               
               $('.btnsmt').prop('disabled', true).attr('value','Processing...');
              
               var leave_application_id = '<?= $leave['leave_application_id']?>';
               var form_data = new FormData(form);
               form_data.append('leave_application_id',leave_application_id);
               
               swal({
                    title: "Are you sure?",
                    text: "Want to update this leave request?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    confirmButtonText: "Yes, update it!",
                    closeOnConfirm: true
                }, function (isConfirm) {
               
                    if (isConfirm) {         
                        $.ajax({
                         type: 'POST',
                         url: base_url + 'user/leave_edit_process',
                         cache: false,
                         async: false,
                         data: form_data,
                         contentType: false,
                         processData: false,
                         success: function(response) {
                                                       
                            var obj = $.parseJSON(response);		          
                            if(obj.status==1) {
                               toaster('success',obj.msg);
                               $('#leave_application_list').DataTable().ajax.reload();
                               $('#modal_ajax').modal('toggle');
                            } else {
                              toaster('error',obj.msg);
                              $('.btnsmt').prop('disabled', false).attr('value','Approve');
                            }
                             
                         }, error: function(error) {	      
							toaster('error',error);
							$('.btnsmt').prop('disabled', false).attr('value','Approve');
					     }
                         
                        });
                        
                    } else {
                        $('#modal_ajax').modal('toggle');
                    }
               });
               return false;
          }
});
</script>

<?php } else {
$this->load->view('theme/user/notfound');
} ?>