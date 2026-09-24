<div class="col-md-6 col-sm-12" id="t<?=$count?>">
    <div class="form-group">
        <label>Title<sup>*</sup></label>
        <input type="text" class="form-control" name="title[<?=$count?>]">
    </div>
</div>
<div class="col-md-6 col-sm-12" id="d<?=$count?>">
    <div class="form-group">
        <label>Description<sup>*</sup></label>
        <input type="text" class="form-control" name="description[<?=$count?>]">
        <a href="javascript:;" class="pull-right removeP trash" data-id="<?=$count?>"><i class="icon-trash"></i></a>
    </div>
</div>