<div class="row clearfix" id="row2<?=$count?>">
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                        <label>Bank name</label>
                                        <input type="text" class="form-control" name="bank_name[<?=$count?>]" id="bname<?=$count?>">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Account number</label>
                                        <input type="text" class="form-control" name="account_number[<?=$count?>]" id="anum<?=$count?>">                                       
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                                                
                                         <label>IBAN number</label>
                                        <input type="text" class="form-control" name="iban_number[<?=$count?>]" id="iban<?=$count?>">                   
                                       
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-group">
                                     <label>Swift code</label>
                                        <input type="text" class="form-control" name="swift_code[<?=$count?>]" id="cst<?=$count?>">                  
                                       
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Upload document(s)</label>
                                        <input type="file" name="bank_images[<?=$count?>][]" value="" id="bnk<?=$count?>" class="form-control file" multiple="">
                                        <a href="javascript:;" class="pull-right removeP2 trash" data-id="<?=$count?>"><i class="icon-trash"></i></a>
                                    </div>
                                </div>
                                <input type="hidden" name="bank_id[<?=$count?>]" value="">
                            </div>