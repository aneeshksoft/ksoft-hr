<?php
$assets = $this->common_model->assets_list($param2);
if(!empty($assets)) {
   //pr($assets);
?>

<div class="row clearfix">
    <div class="col-md-12 col-sm-12">
       
       <div class="table-responsive">
                                <table class="table table-hover m-b-0">
                                    <thead class="thead-dark">
                                        <tr>
                                        <th>Type</th>
                                        <th>Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php foreach($assets as $row) {?>                                        
                                        <tr class="td<?=$row['asset_assignment_id'] ?>">
                                            <td class="hover-delete1" <?=(count($row['details'])>0)?('rowspan="'.(count($row['details'])+1).'"'):''?>><?= $row['name']?>
                                            <?php if(check_permission('7','d')) {?>
                                            <a href="javascript:;" class="pull-right trash2" onclick="removeAsset('<?=$row['asset_assignment_id']?>','full')"><i class="icon-trash"></i></a>
                                            <?php } ?>
                                            </td>
                                            <?php if(empty($row['details'])) { ?>
                                            <td>-</td>
                                            <?php } ?>
                                        </tr>
                                        <?php
                                        if(!empty($row['details'])) {
                                        foreach($row['details'] as $row1) {?>
                                        <tr class="td<?=$row1['asset_assignment_id'] ?>">
                                            <td class="hover-delete" id="td<?=$row1['asset_details_id'] ?>"><strong><?=ucfirst($row1['title'])?></strong>:&nbsp;&nbsp;<?=$row1['description']?>
                                            <?php if(check_permission('7','d')) {?>
                                            <a href="javascript:;" class="pull-right trash1" onclick="removeAsset('<?=$row1['asset_details_id']?>','sub')"><i class="icon-trash"></i></a>
                                            <?php } ?>
                                            </td>
                                        </tr>
                                        <?php }?>                                        
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                  
    </div>
</div>
<?php } else {
$this->load->view('theme/user/notfound');
} ?>