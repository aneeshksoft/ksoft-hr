<div class="col-md-2 col-sm-12" id="d<?=$count?>">
	<div class="form-group">
		<label>Date<sup>*</sup></label>
		<input type="text" class="form-control" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" name="imbursement_date[<?=$count?>]" id="idt<?=$count?>">
	</div>
</div>
<div class="col-md-5 col-sm-12" id="t<?=$count?>">
	<div class="form-group">
		<label>Details<sup>*</sup></label>
		<input type="text" class="form-control" name="details[<?=$count?>]" id="id<?=$count?>">
	</div>
</div>
<div class="col-md-2 col-sm-12" id="s<?=$count?>">
	<div class="form-group">
		<label>Amount<sup>*</sup></label>
		<input type="text" class="form-control amount" name="amount[<?=$count?>]" id="ia<?=$count?>">
        
	</div>
</div>
<div class="col-md-3 col-sm-12" id="a<?=$count?>">
			<div class="form-group">
			    <label>Upload document(s)</label>
			    <input type="file" name="imbursement_images[<?=$count?>][]" value="" id="im<?=$count?>" class="form-control file" multiple="">
                <a href="javascript:;" class="pull-right removeP trash" data-id="<?=$count?>"><i class="icon-trash"></i></a>
			</div>
	</div>
