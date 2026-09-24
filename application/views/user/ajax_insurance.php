<div class="row clearfix" id="row1<?=$count?>">
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        
                                        <label>Provider</label>
                                        <input type="text" class="form-control" name="company[<?=$count?>]" id="cmp<?=$count?>">                        
                                                                
                                        
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Cost</label>
                                        <input type="text" class="form-control amount" name="cost[<?=$count?>]" id="cst<?=$count?>">                                        
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                                                
                                        <label>Valid upto</label>
                                        <input type="text" data-provide="datepicker" data-date-autoclose="true" data-date-format="dd/mm/yyyy" name="expiry[<?=$count?>]" class="form-control" id="exp<?=$count?>">  
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Upload document(s)</label>
                                        <input type="file" name="insurance_images[<?=$count?>][]" value="" id="ins<?=$count?>" class="form-control file" multiple="">
                                        <a href="javascript:;" class="pull-right removeP1 trash" data-id="<?=$count?>"><i class="icon-trash"></i></a>
                                    </div>
                                </div>
                                 <input type="hidden" name="insurance_id[<?=$count?>]" value="">
                            </div>