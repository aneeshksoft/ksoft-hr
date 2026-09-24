<?php
$holiday = $this->common_model->selectOne('holidays',array('id'=>$param2),'*');
?>

<div class="row clearfix">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <form class="form-auth-small" action="" name="delete_holiday" id="delete_holiday" method="POST" enctype="multipart/form-data">
                <div class="row clearfix">

                    <div class="col-md-12 col-sm-12">
                        <div class="row">
                            <div class="col-md-4"><strong class="text-danger">Title:</strong></div>
                            <div class="col-md-8 mb-3"><?= nl2br($holiday['title'])?></div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="row">
                            <div class="col-md-4"><strong class="text-danger">Holiday Date:</strong></div>
                            <div class="col-md-8 mb-3"><?= get_date($holiday['holiday_date'])?></div>
                        </div>
                    </div>
                    <div class="col-sm-12 mt-4">
                        <?php if(check_permission(26)) {?>
                        <input type="button" class="btn btn-lg btn-primary btnsmt pull-left" value="Edit" onclick="showAjaxModal('<?=base_url('leave/popup/edit_holiday/'.$holiday['id'])?>','Edit Holiday')" />
                        <?php } ?>
                        <?php if(check_permission(28)) {?>
                        <input type="button" class="btn btn-lg btn-danger btnsmt2 pull-right" value="Delete" onclick="deleteHoliday('<?=$holiday['id']?>')" />
                        <?php } ?>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>