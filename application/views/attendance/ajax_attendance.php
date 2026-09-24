<div class="col-md-2 col-sm-12" id="t<?=$count?>">
    <div class="form-group">
        <label>Attendance Date<sup>*</sup></label>
        <input type="text" class="form-control" name="attendance_date[<?=$count?>]" data-provide="datepicker" data-date-format="dd/mm/yyyy" data-date-autoclose="true" data-date-end-date="today">
    </div>
</div>
<div class="col-md-1 col-sm-12" id="it<?=$count?>">
    <div class="form-group">
        <label>In Time<sup>*</sup></label>
        <input type="text" class="form-control" name="in_time[<?=$count?>]" id="intime<?=$count?>" onkeyup="getDifference(<?=$count?>)">
    </div>
</div>
<div class="col-md-1 col-sm-12" id="ot<?=$count?>">
    <div class="form-group">
        <label>Out Time<sup>*</sup></label>
        <input type="text" class="form-control" name="out_time[<?=$count?>]" id="outtime<?=$count?>" onkeyup="getDifference(<?=$count?>)">
    </div>
</div>
<div class="col-md-1 col-sm-12" id="d<?=$count?>">
    <div class="form-group">
        <label>Duration<sup>*</sup></label>
        <input type="text" class="form-control" name="duration[<?=$count?>]" id="dura<?=$count?>">
    </div>
</div>
<div class="col-md-2 col-sm-12" id="s<?=$count?>">
    <div class="form-group">
        <label>Shift</label>
        <select name="shift[<?=$count?>]" class="form-control">
            <option value="">Select shift</option>
            <option value="FS">FS</option>
            <option value="SS">SS</option>
        </select>
    </div>
</div>
<div class="col-md-2 col-sm-12" id="st<?=$count?>">
    <div class="form-group">
        <label>Status<sup>*</sup></label>       
        <select name="status[<?=$count?>]" class="form-control">
            <option value="">Select status</option>
            <option value="P/P">P/P</option>
            <option value="P/A">P/A</option>
            <option value="A/P">A/P</option>
            <option value="A/A">A/A</option>
        </select>
    </div>
</div>
<div class="col-md-3 col-sm-12" id="r<?=$count?>">
    <div class="form-group">
        <label>Remarks</label>
        <input type="text" class="form-control" name="remarks[<?=$count?>]">
        <a href="javascript:;" class="pull-right removeP trash" data-id="<?=$count?>"><i class="icon-trash"></i></a>
    </div>
</div>