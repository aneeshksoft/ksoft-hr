<div><h6 class="d-inline">Eligible annual leave</h6>:&nbsp;&nbsp;<h6 class="d-inline"><strong><?= $eligible_annual_leave.' days'?></strong></h6>
<small class="mb-2 d-block"><?= get_date($start_date).' to '.get_date(date('Y-m-d'))?></small></div>

<?php if($applied_days > 0) {
 if($applied_days >= intval($eligible_annual_leave)) {
   $paid = intval($eligible_annual_leave);
   $unpaid = ($applied_days - intval($eligible_annual_leave));
 } else {
    $paid = $applied_days;
    $unpaid = 0;    
 }
?>
<div>
    <div>Total days applied:&nbsp;&nbsp;<strong><?= $applied_days.' days'?></strong></div>
    <div>Paid annual:&nbsp;&nbsp;<strong><?= $paid.' days'?></strong></div>
    <div>Unpaid annual:&nbsp;&nbsp;<strong><?= $unpaid.' days'?></strong></h6>
</div>
<?php } ?>

<?php if(!empty($taken_leaves)) {?>
<h6 class="mt-3 mb-0">Leave History</h6>
<small class="mb-2 d-block"><?= get_date($start_date).' to '.get_date(date('Y-m-d'))?></small>
<?php $i=1; foreach($taken_leaves as $row) {?>
<div><?= $i.'. '.$row['title'].': <strong>'.$row['lcount'].' day(s)</strong>'?></div>
<?php $i++;} } ?>