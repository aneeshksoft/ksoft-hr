<div class="row clearfix" id="row<?=$count?>">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Qualification</label>
                                        <select class="form-control show-tick" name="qualification_id[<?=$count?>]" id="edu<?=$count?>">
                                            <option value="">Select Qualification</option>
                                            <?php foreach($qualifications as $row) { ?>
                                            <option value="<?= $row['qualification_id']?>"><?= $row['name']?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>Year</label>
                                        <select class="form-control show-tick" name="year[<?=$count?>]" id="yr<?=$count?>">
                                            <option value="">Year</option>
                                            <?php for($i=1947;$i<=date('Y');$i++) { ?>
                                            <option value="<?= $i?>"><?= $i?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Institution</label>
                                        <input type="text" class="form-control" name="institution[<?=$count?>]" id="ins<?=$count?>">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Upload document(s)</label>
                                        <input type="file" name="education_images[<?=$count?>][]" value="" id="doc<?=$count?>" class="form-control file" multiple="">
                                        <a href="javascript:;" class="pull-right removeP trash" data-id="<?=$count?>"><i class="icon-trash"></i></a>
                                    </div>
                                </div>
                                <input type="hidden" name="education_id[<?=$count?>]" value="">
                            </div>