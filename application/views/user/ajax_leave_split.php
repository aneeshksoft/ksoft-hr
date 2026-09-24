<?php if(!empty($split)) {
    $i=0;
foreach($split as $row) { 
?>
<div class="alert <?=($row['paid']=='yes')?'alert-success':'alert-warning'?> mb-1" role="alert">Leave Date: <strong><?=get_date($row['leave_date'])?></strong>&nbsp;|&nbsp;Rejoin Date: <strong><?=get_date($row['rejoin_date'])?></strong>&nbsp;|&nbsp;<strong><?=($row['paid']=='yes')?'Paid':'Unpaid'?></strong></div>
<div class="row">
<div class="col-md-12 mt-2">
    <div class="form-group">
        <label class="fancy-radio"><input name="paid_status[<?=$i?>]" value="paid" id="p<?=$i?>" type="radio" <?=($row['paid']=='yes')?'checked':''?>><span><i></i>Paid</span></label>
       <label class="fancy-radio"><input name="paid_status[<?=$i?>]" value="unpaid" id="up<?=$i?>" type="radio" <?=($row['paid']=='no')?'checked':''?>><span><i></i>Unpaid</span></label>         
    </div>
</div>
</div>
<?php $i++; } } ?>