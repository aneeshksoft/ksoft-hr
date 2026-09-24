<?php
$leave = $this->common_model->leave_by_id($param2);
if(!empty($leave)) {
   // pr($leave);
?>

<div class="row clearfix">
    <div class="col-md-12 col-sm-12">
        
        <div class="card">
                        <div class="body">
                            <div class="row">
                                <div class="col-7">
                                    <small class="text-small">Overall Leave Status</small>
                                    <h5 class="m-t-0"><?= ucfirst($leave['status'])?></h5>                                    
                                </div>                                                             
                            </div>
                        </div>
                    </div>
            
            <ul class="right_chat list-unstyled">
                            <?php foreach($leave['detailed_status'] as $row) {?>                               
                                
                                <li class="offline">
                                    <a href="javascript:void(0);">
                                        <div class="media">
                                            <img class="media-object" src="<?= getUserImage($row['profile_photo'])?>" alt="">
                                            <div class="media-body">
                                                <span class="name"><?= $row['name']?>&nbsp;&nbsp;-&nbsp;&nbsp;<?= $row['role']?> <small class="float-right"><?= get_date($row['status_changed'])?></small></span>
                                                <span class="message"><?= ($row['remarks'] != "")?$row['remarks']:"No remarks!"?></span>
                                                <div class="mt-2"><?=leave_status_c($row['status'])?></div>
                                            </div>
                                        </div>
                                    </a>                            
                                </li>
                                
                                
                                <?php } ?>
           </ul>
      
    </div>
</div>
<?php } else {
$this->load->view('theme/user/notfound');
} ?>