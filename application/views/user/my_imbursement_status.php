<?php
$my_id = $this->session->userdata('employee_id');
$details = $this->common_model->imbursement_detail_by_code($param2);
define('CURRENCY',$this->settings->get_settings('currency'));
if(!empty($details)) {
   
?>

<div class="row clearfix">
    <div class="col-md-12 col-sm-12">
        <form class="form-auth-small" action="" name="imbursement_status_process" id="imbursement_status_process" method="POST" enctype="multipart/form-data">
  <?php $i=0; foreach($details as $row) {?>
        <div class="card hover-card mb-2">
                        <div class="header">
                            <h2><?= ucfirst($row['details'])?></h2>
							<div class="row">
								<div class="col-md-4">
                            <div><?= CURRENCY.' '.$row['amount']?></div>
                            <small><?= get_date($row['imbursement_date'])?></small>
								</div>
								<div class="col-md-8">
									<?php if(!empty($row['docs'])) {?>
                                                <div class="align-items-center d-flex">
                                                    <div class="mb-0 mr-2">Documents:</div>
                                                    <ul class="list-unstyled team-info margin-0">
                                                        <?php foreach($row['docs'] as $row1) {?>        
                                                        <li><a href="<?= base_url()?>assets/uploads/user_docs/<?= $row['employee_id'].'/imbursement/'.$row1['doc_path']?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Click to open" ><img src="<?= base_url()?>assets/common/preview.svg" alt="Avatar" /></a></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            <?php } ?>
								</div>									
							</div>
                        </div>
                        <div class="body pt-0">
                            <div class="table-responsive">
                                <table class="table m-b-0">
                                    <tbody>
                                        <?php                                       
                                        foreach($row['full_status'] as $row1) {?>
                                        <tr>
                                            <td class="pl-0"><?= $row1['name']?></td>
                                            <td><?= $row1['role']?></td>                                           
                                            <td class="text-right pr-0">
                                                <?php
                                                echo leave_status_c($row1['status']);
                                                 ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
         </div>
        <?php } ?>
        <?php if($i>0) {?>
   <div class="row">
                                <div class="col-sm-12">
                                    <div class="mt-4">
                                        <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />
                                    </div>
                                </div>
   </div>
   <?php } ?>
        </form>
    </div>
</div>
<?php } else {
$this->load->view('theme/user/notfound');
} ?>
