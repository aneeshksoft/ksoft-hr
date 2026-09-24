<form action="" name="stage_4_form" id="stage_4_form" method="POST" enctype="multipart/form-data">
    <div class="row">

        <?php if ($candidate['employment_history'] == 'no') { ?>
            <div class="col-md-12 col-sm-12">
                <label>
                    <h5>Reference Check:</h5>
                </label>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label>College Name<sup>*</sup></label>
                    <?php if (@$candidate['ref_fresher'][0]['reference_fre_id_verified'] == 'no' || @$candidate['ref_fresher'][0]['college_name'] == "") { ?>
                        <input type="text" class="form-control form-control-sm" name="college_name" id="college_name" value="<?= @$candidate['ref_fresher'][0]['college_name']; ?>">
                    <?php } else { ?>
                        <p><?= (@$candidate['ref_fresher'][0]['college_name'] != "") ? ucfirst(@$candidate['ref_fresher'][0]['college_name']) : '-' ?></p>
                    <?php } ?>
                </div>
            </div>

            <div class="col-md-2 col-sm-12">
                <div class="form-group">
                    <label>Name of the HOD<sup>*</sup></label>
                    <?php if (@$candidate['ref_fresher'][0]['reference_fre_id_verified'] == 'no' || @$candidate['ref_fresher'][0]['name_of_hod'] == "") { ?>
                        <input type="text" class="form-control form-control-sm" name="name_of_hod" id="name_of_hod" value="<?= @$candidate['ref_fresher'][0]['name_of_hod']; ?>">
                    <?php } else { ?>
                        <p><?= (@$candidate['ref_fresher'][0]['name_of_hod'] != "") ? ucfirst(@$candidate['ref_fresher'][0]['name_of_hod']) : '-' ?></p>
                    <?php } ?>
                </div>
            </div>

            <div class="col-md-3 col-sm-12">
                <div class="form-group">
                    <label>Email ID<sup>*</sup></label>
                    <?php if (@$candidate['ref_fresher'][0]['reference_fre_id_verified'] == 'no' || @$candidate['ref_fresher'][0]['email_id_hod'] == "") { ?>
                        <input type="text" class="form-control form-control-sm" name="email_id_hod" id="email_id_hod" value="<?= @$candidate['ref_fresher'][0]['email_id_hod']; ?>">
                    <?php } else { ?>
                        <p><?= (@$candidate['ref_fresher'][0]['email_id_hod'] != "") ? ucfirst(@$candidate['ref_fresher'][0]['email_id_hod']) : '-' ?></p>
                    <?php } ?>
                </div>
            </div>

            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <label>Mobile Number<sup>*</sup></label>
                    <?php if (@$candidate['ref_fresher'][0]['reference_fre_id_verified'] == 'no' || @$candidate['ref_fresher'][0]['mobile_number_hod'] == "") { ?>
                        <div class="w-100 d-flex">

                            <select class="form-control chosen-select <?= (get_comment('reference_fre_id' . @$candidate['ref_fresher'][0]['reference_fre_id'], $comments) != "") ? 'allowedit' : '' ?>" name="country_code_hod" id="country_code_hod">
                                <option value="">Code</option>
                                <?php if ($country_code) : ?>
                                    <?php foreach ($country_code as $code) : ?>
                                        <option value="<?= $code->code; ?>" <?= (($code->code == @$candidate['ref_fresher'][0]['country_code_hod']) ? 'selected' : ''); ?>><?= $code->code; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>


                            &nbsp;
                            <input type="text" class="form-control form-control-sm d-inline-block w-100 " name="mobile_number_hod" id="mobile_number_hod" value="<?= @$candidate['ref_fresher'][0]['mobile_number_hod']; ?>">
                        </div>
                    <?php } else { ?>
                        <p><?= (@$candidate['ref_fresher'][0]['mobile_number_hod'] != "") ? (@$candidate['ref_fresher'][0]['country_code_hod'] . ' ' . @$candidate['ref_fresher'][0]['mobile_number_hod']) : '-'; ?></p>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <label>Name of the Project Coordinator<sup>*</sup></label>
                    <?php if (@$candidate['ref_fresher'][0]['reference_fre_id_verified'] == 'no' || @$candidate['ref_fresher'][0]['project_cord_name'] == "") { ?>
                        <input type="text" class="form-control form-control-sm" name="project_cord_name" id="project_cord_name" value="<?= @$candidate['ref_fresher'][0]['project_cord_name']; ?>">
                    <?php } else { ?>
                        <p><?= (@$candidate['ref_fresher'][0]['project_cord_name'] != "") ? ucfirst(@$candidate['ref_fresher'][0]['project_cord_name']) : '-' ?></p>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <label>Email ID<sup>*</sup></label>
                    <?php if (@$candidate['ref_fresher'][0]['reference_fre_id_verified'] == 'no' || @$candidate['ref_fresher'][0]['email_id_cord'] == "") { ?>
                        <input type="text" class="form-control form-control-sm" name="email_id_cord" id="email_id_cord" value="<?= @$candidate['ref_fresher'][0]['email_id_cord']; ?>">
                    <?php } else { ?>
                        <p><?= (@$candidate['ref_fresher'][0]['email_id_cord'] != "") ? ucfirst(@$candidate['ref_fresher'][0]['email_id_cord']) : '-' ?></p>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <label>Mobile Number<sup>*</sup></label>
                    <?php if (@$candidate['ref_fresher'][0]['reference_fre_id_verified'] == 'no' || @$candidate['ref_fresher'][0]['mobile_number_cord'] == "") { ?>
                        <div class="w-100 d-flex">
                            <select class="form-control chosen-select <?= (get_comment('reference_fre_id' . @$candidate['ref_fresher'][0]['reference_fre_id'], $comments) != "") ? 'allowedit' : '' ?>" name="country_code_cord" id="country_code_cord">
                                <option value="">Code</option>
                                <?php if ($country_code) : ?>
                                    <?php foreach ($country_code as $code) : ?>
                                        <option value="<?= $code->code; ?>" <?= (($code->code == @$candidate['ref_fresher'][0]['country_code_cord']) ? 'selected' : ''); ?>><?= $code->code; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>

                            &nbsp;
                            <input type="text" class="form-control form-control-sm d-inline-block w-100 <?= (get_comment('reference_fre_id' . @$candidate['ref_fresher'][0]['reference_fre_id'], $comments) != "") ? 'allowedit' : '' ?>" name="mobile_number_cord" id="mobile_number_cord" value="<?= @$candidate['ref_fresher'][0]['mobile_number_cord']; ?>">
                        </div>
                    <?php } else { ?>
                        <p><?= (@$candidate['ref_fresher'][0]['mobile_number_cord'] != "") ? (@$candidate['ref_fresher'][0]['country_code_cord'] . ' ' . @$candidate['ref_fresher'][0]['mobile_number_cord']) : '-'; ?></p>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <div class="col-md-12 col-sm-12">
            <hr />
        </div>


        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label>UAN Details<a href="javascript:;" class="has-popover ml-2" data-toggle="popover" data-content="Screenshot of PF Passbook in .pdf or .jpeg or .png or .jpg Format"><i class="icon-info"></i></a><?= get_comment('uan_proof', $comments) ?></label>
                <?php if ($candidate['uan_proof_verified'] == 'no') { ?>
                    <input type="file" class="form-control form-control-sm <?= (get_comment('uan_proof', $comments) != "") ? 'allowedit' : '' ?> <?= ($candidate['uan_proof'] != "") ? 'temphidden' : '' ?>" name="uan_proof[]" id="uan_proof" value="" multiple>
                <?php }

                $verified = ($candidate['uan_proof_verified'] == 'yes') ? 'yes' : ((get_comment('uan_proof', $comments) != "") ? 'no' : 'yes');
                ?>
                <div><?= candidate_docs($candidate['uan_proof'], 'uan_proof', 'candidate', '4', $verified); ?></div>

            </div>
        </div>
        <?php if ($candidate['wfh_or_office'] == 'wfh') { ?>
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <label>Power Backup proof <sup>*</sup><a href="javascript:;" class="has-popover ml-2" data-toggle="popover" data-content="Power Back-up of atleast 3 Hours is mandated. Upload the Invoice Copy of Power Back-up Procured. Invoice to be on Self Name or any of the Family Member's Name. If Invoice Copy is NOT available, upload the Picture of the UPS"><i class="icon-info"></i></a><?= get_comment('power_backup_proof', $comments) ?></label>
                    <?php if ($candidate['power_backup_proof_verified'] == 'no') { ?>
                        <input type="file" class="form-control form-control-sm <?= (get_comment('power_backup_proof', $comments) != "") ? 'allowedit' : '' ?> <?= ($candidate['power_backup_proof'] != "") ? 'temphidden' : '' ?>" name="power_backup_proof[]" id="power_backup_proof" value="" multiple>
                    <?php }

                    $verified = ($candidate['power_backup_proof_verified'] == 'yes') ? 'yes' : ((get_comment('power_backup_proof', $comments) != "") ? 'no' : 'yes');
                    ?>
                    <div><?= candidate_docs($candidate['power_backup_proof'], 'power_backup_proof', 'candidate', '4', $verified); ?></div>

                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <label>Internet Bill of last month <sup>*</sup><a href="javascript:;" class="has-popover ml-2" data-toggle="popover" data-content="Internet Speed of atleast 40MBPS is mandated. Upload the Invoice Copy of Internet Bill of last Month. Invoice to be on Self Name or any of the Family Member's Name"><i class="icon-info"></i></a><?= get_comment('internet_bill', $comments) ?></label>
                    <?php if ($candidate['internet_bill_verified'] == 'no') { ?>
                        <input type="file" class="form-control form-control-sm <?= (get_comment('internet_bill', $comments) != "") ? 'allowedit' : '' ?> <?= ($candidate['internet_bill'] != "") ? 'temphidden' : '' ?>" name="internet_bill[]" id="internet_bill" value="" multiple>
                    <?php }
                    $verified = ($candidate['internet_bill_verified'] == 'yes') ? 'yes' : ((get_comment('internet_bill', $comments) != "") ? 'no' : 'yes');
                    ?>
                    <div><?= candidate_docs($candidate['internet_bill'], 'internet_bill', 'candidate', '4', $verified); ?></div>

                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <label>Screenshot of Internet Speed test <sup>*</sup><a href="javascript:;" class="has-popover ml-2" data-toggle="popover" data-content="Conduct the Speed Test on Ookla and upload the Screen shot of the Test, which clearly displays, Name of the ISP, Location of the System, Download & Upload Speed"><i class="icon-info"></i></a><?= get_comment('internet_speed', $comments) ?></label>
                    <?php if ($candidate['internet_speed_verified'] == 'no') { ?>
                        <input type="file" class="form-control form-control-sm <?= (get_comment('internet_speed', $comments) != "") ? 'allowedit' : '' ?> <?= ($candidate['internet_speed'] != "") ? 'temphidden' : '' ?>" name="internet_speed[]" id="internet_speed" value="" multiple>
                    <?php }

                    $verified = ($candidate['internet_speed_verified'] == 'yes') ? 'yes' : ((get_comment('internet_speed', $comments) != "") ? 'no' : 'yes');
                    ?>
                    <div><?= candidate_docs($candidate['internet_speed'], 'internet_speed', 'candidate', '4', $verified); ?></div>

                </div>
            </div>
        <?php } ?>
        <?php if ($candidate['wfh_or_office'] == 'hybrid') { ?>
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <label>Power Backup proof <sup>*</sup><a href="javascript:;" class="has-popover ml-2" data-toggle="popover" data-content="Power Back-up of atleast 3 Hours is mandated. Upload the Invoice Copy of Power Back-up Procured. Invoice to be on Self Name or any of the Family Member's Name. If Invoice Copy is NOT available, upload the Picture of the UPS"><i class="icon-info"></i></a><?= get_comment('power_backup_proof', $comments) ?></label>
                    <?php if ($candidate['power_backup_proof_verified'] == 'no') { ?>
                        <input type="file" class="form-control form-control-sm <?= (get_comment('power_backup_proof', $comments) != "") ? 'allowedit' : '' ?> <?= ($candidate['power_backup_proof'] != "") ? 'temphidden' : '' ?>" name="power_backup_proof[]" id="power_backup_proof" value="" multiple>
                    <?php }

                    $verified = ($candidate['power_backup_proof_verified'] == 'yes') ? 'yes' : ((get_comment('power_backup_proof', $comments) != "") ? 'no' : 'yes');
                    ?>
                    <div><?= candidate_docs($candidate['power_backup_proof'], 'power_backup_proof', 'candidate', '4', $verified); ?></div>

                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <label>Internet Bill of last month <sup>*</sup><a href="javascript:;" class="has-popover ml-2" data-toggle="popover" data-content="Internet Speed of atleast 40MBPS is mandated. Upload the Invoice Copy of Internet Bill of last Month. Invoice to be on Self Name or any of the Family Member's Name"><i class="icon-info"></i></a><?= get_comment('internet_bill', $comments) ?></label>
                    <?php if ($candidate['internet_bill_verified'] == 'no') { ?>
                        <input type="file" class="form-control form-control-sm <?= (get_comment('internet_bill', $comments) != "") ? 'allowedit' : '' ?> <?= ($candidate['internet_bill'] != "") ? 'temphidden' : '' ?>" name="internet_bill[]" id="internet_bill" value="" multiple>
                    <?php }
                    $verified = ($candidate['internet_bill_verified'] == 'yes') ? 'yes' : ((get_comment('internet_bill', $comments) != "") ? 'no' : 'yes');
                    ?>
                    <div><?= candidate_docs($candidate['internet_bill'], 'internet_bill', 'candidate', '4', $verified); ?></div>

                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <label>Screenshot of Internet Speed test <sup>*</sup><a href="javascript:;" class="has-popover ml-2" data-toggle="popover" data-content="Conduct the Speed Test on Ookla, and upload the Screen shot of the Test, which clearly displays, Name of the ISP, Location of the System, DownLoad & Upload Speed"><i class="icon-info"></i></a><?= get_comment('internet_speed', $comments) ?></label>
                    <?php if ($candidate['internet_speed_verified'] == 'no') { ?>
                        <input type="file" class="form-control form-control-sm <?= (get_comment('internet_speed', $comments) != "") ? 'allowedit' : '' ?> <?= ($candidate['internet_speed'] != "") ? 'temphidden' : '' ?>" name="internet_speed[]" id="internet_speed" value="" multiple>
                    <?php }

                    $verified = ($candidate['internet_speed_verified'] == 'yes') ? 'yes' : ((get_comment('internet_speed', $comments) != "") ? 'no' : 'yes');
                    ?>
                    <div><?= candidate_docs($candidate['internet_speed'], 'internet_speed', 'candidate', '4', $verified); ?></div>

                </div>
            </div>
        <?php } ?>
        <div class="col-md-12 col-sm-12">
            <hr />
            <label>
                <h5>Pre-Employment Medicals:</h5>
            </label>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label>Identification Marks<sup>*</sup><?= get_comment('identification_mark', $comments) ?></label>
                <?php if ($candidate['identification_mark_verified'] == 'no') { ?>
                    <input type="text" class="form-control form-control-sm <?= (get_comment('identification_mark', $comments) != "") ? 'allowedit' : '' ?>" name="identification_mark" id="identification_mark" value="<?= $candidate['identification_mark']; ?>">
                <?php } else { ?>
                    <p><?= ($candidate['identification_mark'] != "") ? ucfirst($candidate['identification_mark']) : '-' ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label>Any Major Illness in the past?<sup>*</sup><?= get_comment('major_illness_id', $comments) ?></label>
                <?php if ($candidate['major_illness_id_verified'] == 'no') { ?>
                    <select class="form-control form-control-sm <?= (get_comment('major_illness_id', $comments) != "") ? 'allowedit' : '' ?>" name="major_illness_id" id="major_illness_id">
                        <option value="">Select</option>
                        <?php if ($major_illness) : ?>
                            <?php foreach ($major_illness as $value) : ?>
                                <option value="<?= $value->major_illness_id; ?>" <?= (($value->major_illness_id == $candidate['major_illness_id']) ? 'selected' : '') ?>><?= Ucfirst($value->name); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                <?php } else { ?>
                    <p><?= ($candidate['major_illness'] != "") ? ucfirst($candidate['major_illness']) : '-' ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label>Any Operations or Accidents or Injuries?<sup>*</sup><?= get_comment('operation_or_accident', $comments) ?></label>
                <?php if ($candidate['operation_or_accident_verified'] == 'no') { ?>
                    <select class="form-control form-control-sm <?= (get_comment('operation_or_accident', $comments) != "") ? 'allowedit' : '' ?>" name="operation_or_accident" id="operation_or_accident">
                        <option value=""> Select </option>
                        <option value="yes" <?= (($candidate['operation_or_accident'] == 'yes') ? 'selected' : ''); ?>> Yes </option>
                        <option value="no" <?= (($candidate['operation_or_accident'] == 'no') ? 'selected' : ''); ?>> No </option>
                    </select>
                <?php } else { ?>
                    <p><?= ($candidate['operation_or_accident'] != "") ? ucfirst($candidate['operation_or_accident']) : '-' ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label>Any findings in you of Diabetes, High Blood Pressure or Epilepsy<sup>*</sup><?= get_comment('db_bp_ep_finding', $comments) ?></label>
                <?php if ($candidate['db_bp_ep_finding_verified'] == 'no') { ?>
                    <select class="form-control form-control-sm <?= (get_comment('db_bp_ep_finding', $comments) != "") ? 'allowedit' : '' ?>" name="db_bp_ep_finding" id="db_bp_ep_finding">
                        <option value=""> Select </option>
                        <option value="yes" <?= (($candidate['db_bp_ep_finding'] == 'yes') ? 'selected' : ''); ?>> Yes </option>
                        <option value="no" <?= (($candidate['db_bp_ep_finding'] == 'no') ? 'selected' : ''); ?>> No </option>
                    </select>
                <?php } else { ?>
                    <p><?= ($candidate['db_bp_ep_finding'] != "") ? ucfirst($candidate['db_bp_ep_finding']) : '-' ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label>Any Family History of Diabetes or High Blood Pressure?<sup>*</sup><?= get_comment('db_bp_family', $comments) ?></label>
                <?php if ($candidate['db_bp_family_verified'] == 'no') { ?>
                    <select class="form-control form-control-sm <?= (get_comment('db_bp_family', $comments) != "") ? 'allowedit' : '' ?>" name="db_bp_family" id="db_bp_family">
                        <option value=""> Select </option>
                        <option value="yes" <?= (($candidate['db_bp_family'] == 'yes') ? 'selected' : ''); ?>> Yes </option>
                        <option value="no" <?= (($candidate['db_bp_family'] == 'no') ? 'selected' : ''); ?>> No </option>
                    </select>
                <?php } else { ?>
                    <p><?= ($candidate['db_bp_family'] != "") ? ucfirst($candidate['db_bp_family']) : '-' ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label>Any Musculoskeletal problems?<sup>*</sup><?= get_comment('musculoskeletal_problem', $comments) ?></label>
                <?php if ($candidate['musculoskeletal_problem_verified'] == 'no') { ?>
                    <select class="form-control form-control-sm <?= (get_comment('musculoskeletal_problem', $comments) != "") ? 'allowedit' : '' ?>" name="musculoskeletal_problem" id="musculoskeletal_problem">
                        <option value=""> Select </option>
                        <option value="yes" <?= (($candidate['musculoskeletal_problem'] == 'yes') ? 'selected' : ''); ?>> Yes </option>
                        <option value="no" <?= (($candidate['musculoskeletal_problem'] == 'no') ? 'selected' : ''); ?>> No </option>
                    </select>
                <?php } else { ?>
                    <p><?= ($candidate['musculoskeletal_problem'] != "") ? ucfirst($candidate['musculoskeletal_problem']) : '-' ?></p>
                <?php } ?>
            </div>
        </div>

        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label>Fitness Certificate from Medical Officer<sup>*</sup><a href="javascript:;" class="has-popover ml-2" data-toggle="popover" data-content="Click 'Save Draft' button below. Print the Medical Certificate Format. Get it filled by a Registererd Medical Practitioner and Upload the Scanned Copy in .pdf or .jpeg or.png or jpg Format"><i class="icon-info"></i></a><?= get_comment('fitness_certificate', $comments) ?></label>
                <?php if ($candidate['fitness_certificate_verified'] == 'no') { ?>
                    <div class="chip ml-3"><a href="<?= base_url('assets/downloads/fitness_certificate.pdf') ?>" title="Click to print the file" target="_blank"><i class="icon-printer"></i>&nbsp;&nbsp;Download Form</a></div>
                    <input type="file" class="form-control form-control-sm <?= (get_comment('fitness_certificate', $comments) != "") ? 'allowedit' : '' ?> <?= ($candidate['fitness_certificate'] != "") ? 'temphidden' : '' ?>" name="fitness_certificate[]" id="fitness_certificate" value="" multiple>
                <?php }

                $verified = ($candidate['fitness_certificate_verified'] == 'yes') ? 'yes' : ((get_comment('fitness_certificate', $comments) != "") ? 'no' : 'yes');
                ?>
                <div><?= candidate_docs($candidate['fitness_certificate'], 'fitness_certificate', 'candidate', '4', $verified); ?></div>

            </div>
        </div>


        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label>Fitness Certificate Self Declaration<sup>*</sup><a href="javascript:;" class="has-popover ml-2" data-toggle="popover" data-content="Click 'Save Draft' button below. Print Form9, fill it up appropriately, sign it and Upload it in .pdf or .jpeg or .png or jpg Format."><i class="icon-info"></i></a><?= get_comment('fitness_certificate_self', $comments) ?></label>
                <?php if ($candidate['fitness_certificate_self_verified'] == 'no') { ?>
                    <div class="chip ml-3"><a href="<?= base_url('download/form9') ?>" title="Click to print the file" target="_blank"><i class="icon-printer"></i>&nbsp;&nbsp;Form 9</a></div>
                    <input type="file" class="form-control form-control-sm <?= (get_comment('fitness_certificate_self', $comments) != "") ? 'allowedit' : '' ?> <?= ($candidate['fitness_certificate_self'] != "") ? 'temphidden' : '' ?>" name="fitness_certificate_self[]" id="fitness_certificate_self" value="" multiple>
                <?php }

                $verified = ($candidate['fitness_certificate_self_verified'] == 'yes') ? 'yes' : ((get_comment('fitness_certificate_self', $comments) != "") ? 'no' : 'yes');
                ?>
                <div><?= candidate_docs($candidate['fitness_certificate_self'], 'fitness_certificate_self', 'candidate', '4', $verified); ?></div>

            </div>
        </div>


        <div class="col-md-12 col-sm-12">
            <hr />
        </div>
        <!-- Educational Qulification  -->
        <div class="col-md-12 col-sm-12">
            <label>
                <h5>Educational Qualifications: </h5>
            </label><a href="javascript:;" class="has-popover ml-2" data-toggle="popover" data-content="Upload the Marks Card of each of the Courses in .pdf or .png or .jpeg or .jpg Format"><i class="icon-info"></i></a>
            <div class="row clearfix">
                <div class="col-md-12 col-sm-12">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th rowspan="2">Qualification<sup>*</sup></th>
                                <th rowspan="2">Specialization<sup>*</sup></th>
                                <th rowspan="2">Institute<sup>*</sup></th>
                                <th class="text-center" style="border-bottom:1px solid #dee2e6 !important;" colspan="3">Location of the Institute</th>
                                <th rowspan="2">% Marks / Grade<sup>*</sup></th>
                                <th rowspan="2">Year of Completion<sup>*</sup></th>
                                <th rowspan="2">Upload Marks Card<sup>*</sup></th>
                            </tr>
                            <tr>

                                <th>Country<sup>*</sup></th>
                                <th>State<sup>*</sup></th>
                                <th>Nearest City<sup>*</sup></th>
                            </tr>
                        </thead>
                        <tbody id="edu_show">
                            <?php if ($candidate['education_details']) :
                                $i = 0;
                            ?>
                                <?php foreach ($candidate['education_details'] as $eduItems) :  ?>
                                    <tr>

                                        <td>
                                            <?= $eduItems['course_name'] ?>
                                            <input type="hidden" name="education_id[<?= $i; ?>]" value="<?= $eduItems['education_id']; ?>">
                                        </td>


                                        <td><?= ($eduItems['specialization'] != "") ? $eduItems['specialization'] : '-'; ?></td>
                                        <td><?= ucfirst($eduItems['institute_name']); ?></td>
                                        <td> <?= $eduItems['country_name'] ?> </td>
                                        <td><?= $eduItems['state_name'] ?></td>
                                        <td><?= $eduItems['nearest_city'] ?></td>
                                        <td><?= $eduItems['percentage_mark']; ?></td>
                                        <td><?= $eduItems['completion_year']; ?></td>
                                        <td>
                                            <?php if ($eduItems['mark_card_verified'] == 'no') { ?>
                                                <input class="form-control form-control-sm edu-required-file <?= (get_comment('mark_card' . $eduItems['education_id'], $comments) != "") ? 'allowedit' : '' ?> <?= ($eduItems['mark_card'] != "") ? 'temphidden' : '' ?>" type="file" name="update_mark_card_<?= $i; ?>[]" id="mark_card_<?= $i; ?>" multiple><?= get_comment('mark_card' . $eduItems['education_id'], $comments) ?>
                                            <?php }

                                            $verified = ($eduItems['mark_card_verified'] == 'yes') ? 'yes' : ((get_comment('mark_card' . $eduItems['education_id'], $comments) != "") ? 'no' : 'yes');
                                            ?>

                                            <div><?= other_docs($eduItems['mark_card'], 'mark_card', 'education_history', '4', 'education_id', $eduItems['education_id'], $i, $verified); ?></div>



                                        </td>
                                    </tr>
                                <?php
                                    $i++;
                                endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <hr />
        </div>
        <div class="col-sm-12">
            <?php if ($candidate['employment_history'] == 'yes') { ?>

                <div class="col-md-4 col-sm-12" id="releaving_letter_div" style="display:none">
                    <div class="form-group">
                        <label>Relieving Letter from previous Company<sup>*</sup><a href="javascript:;" class="has-popover ml-2" data-toggle="popover" data-content="Upload the document in .pdf or .png or .jpg or .jpeg Format."><i class="icon-info"></i></a><?= get_comment('releaving_letter_previous', $comments) ?></label>
                        <?php if ($candidate['releaving_letter_previous_verified'] == 'no') { ?>
                            <input type="file" class="form-control <?= (get_comment('releaving_letter_previous', $comments) != "") ? 'allowedit' : '' ?> <?= ($candidate['releaving_letter_previous'] != "") ? 'temphidden' : '' ?>" name="releaving_letter_previous[]" id="releaving_letter_previous" value="" multiple>
                        <?php }
                        $verified = ($candidate['releaving_letter_previous_verified'] == 'yes') ? 'yes' : ((get_comment('releaving_letter_previous', $comments) != "") ? 'no' : 'yes');
                        ?>
                        <div><?= candidate_docs($candidate['releaving_letter_previous'], 'releaving_letter_previous', 'candidate', '4', $verified); ?></div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12" id="relieving_date_div" style="display:none">
                    <div class="form-group">
                        <label>Relieving Date</label>
                        <?php if ($candidate['releaving_date_verified'] == 'no') { ?>
                            <input type="text" class="form-control form-control-sm datepicker" name="releaving_date" data-date-start-date="0d" id="releaving_date" value="<?= get_date($candidate['releaving_date']); ?>">
                        <?php } else { ?>
                            <p class="deliverydt"><?= get_date($candidate['releaving_date']) ?></p>
                        <?php } ?>

                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="col-sm-12 mb-5">
            <label>
                <h5>Download Forms:</h5>
            </label><a href="javascript:;" class="has-popover ml-2" data-toggle="popover" data-content="Print the Forms, fill them appropriately, sign as neccessary and upload them in corresponding sections, in .pdf or .png or .jpeg or .jpg Format."><i class="icon-info"></i></a>
            <div>
                <div class="chip"><a href="<?= base_url('download/form2') ?>" title="Click to print the file" target="_blank"><i class="icon-printer"></i>&nbsp;&nbsp;Form - 2</a></div>
                <div class="chip"><a href="<?= base_url('download/formq') ?>" title="Click to print the file" target="_blank"><i class="icon-printer"></i>&nbsp;&nbsp;Form - Q</a></div>
                <div class="chip"><a href="<?= base_url('download/form11') ?>" title="Click to print the file" target="_blank"><i class="icon-printer"></i>&nbsp;&nbsp;Form - 11</a></div>
                <div class="chip"><a href="<?= base_url('download/formI') ?>" title="Click to print the file" target="_blank"><i class="icon-printer"></i>&nbsp;&nbsp;Form - I</a></div>
                <?php if ($candidate['employer_esi'] > 0 &&  !empty($candidate['employer_esi'])) { ?>
                    <div class="chip"><a href="<?= base_url('download/esiform') ?>" title="Click to print the file" target="_blank"><i class="icon-printer"></i>&nbsp;&nbsp;ESI -Form</a></div>
                <?php } ?>
                <div class="chip"><a href="<?= base_url('download/nda') ?>" title="Click to print the file" target="_blank"><i class="icon-printer"></i>&nbsp;&nbsp;NDA</a></div>
            </div>
        </div>


        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label>Form - 2<sup>*</sup><?= get_comment('form_2', $comments) ?></label>
                <?php if ($candidate['form_2_verified'] == 'no') { ?>
                    <input type="file" class="form-control <?= (get_comment('form_2', $comments) != "") ? 'allowedit' : '' ?> <?= ($candidate['form_2'] != "") ? 'temphidden' : '' ?>" name="form_2[]" id="form_2" value="" multiple>
                <?php }
                $verified = ($candidate['form_2_verified'] == 'yes') ? 'yes' : ((get_comment('form_2', $comments) != "") ? 'no' : 'yes');
                ?>
                <div><?= candidate_docs($candidate['form_2'], 'form_2', 'candidate', '4', $verified); ?></div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label>Form - Q<sup>*</sup><?= get_comment('form_q', $comments) ?></label>
                <?php if ($candidate['form_q_verified'] == 'no') { ?>
                    <input type="file" class="form-control <?= (get_comment('form_q', $comments) != "") ? 'allowedit' : '' ?> <?= ($candidate['form_q'] != "") ? 'temphidden' : '' ?>" name="form_q[]" id="form_q" value="" multiple>
                <?php }
                $verified = ($candidate['form_q_verified'] == 'yes') ? 'yes' : ((get_comment('form_q', $comments) != "") ? 'no' : 'yes');
                ?>
                <div><?= candidate_docs($candidate['form_q'], 'form_q', 'candidate', '4', $verified); ?></div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label>Form - 11<sup>*</sup><?= get_comment('form_11', $comments) ?></label>
                <?php if ($candidate['form_11_verified'] == 'no') { ?>
                    <input type="file" class="form-control <?= (get_comment('form_11', $comments) != "") ? 'allowedit' : '' ?> <?= ($candidate['form_11'] != "") ? 'temphidden' : '' ?>" name="form_11[]" id="form_11" value="" multiple>
                <?php }
                $verified = ($candidate['form_11_verified'] == 'yes') ? 'yes' : ((get_comment('form_11', $comments) != "") ? 'no' : 'yes');
                ?>
                <div><?= candidate_docs($candidate['form_11'], 'form_11', 'candidate', '4', $verified); ?></div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label>Form - I<sup>*</sup><?= get_comment('form_1', $comments) ?></label>
                <?php if ($candidate['form_1_verified'] == 'no') { ?>
                    <input type="file" class="form-control <?= (get_comment('form_1', $comments) != "") ? 'allowedit' : '' ?> <?= ($candidate['form_1'] != "") ? 'temphidden' : '' ?>" name="form_1[]" id="form_1" value="" multiple>
                <?php }
                $verified = ($candidate['form_1_verified'] == 'yes') ? 'yes' : ((get_comment('form_1', $comments) != "") ? 'no' : 'yes');
                ?>
                <div><?= candidate_docs($candidate['form_1'], 'form_1', 'candidate', '4', $verified); ?></div>
            </div>
        </div>
        <?php if ($candidate['employer_esi'] > 0 &&  !empty($candidate['employer_esi'])) {  ?>
            <div class="col-md-4 col-sm-12">
                <div class="form-group">
                    <label>ESI - Form<sup>*</sup><?= get_comment('esi_form', $comments) ?></label>
                    <?php if ($candidate['esi_form_verified'] == 'no') { ?>
                        <input type="file" class="form-control <?= (get_comment('esi_form', $comments) != "") ? 'allowedit' : '' ?> <?= ($candidate['esi_form'] != "") ? 'temphidden' : '' ?>" name="esi_form[]" id="esi_form" value="" multiple>
                    <?php }
                    $verified = ($candidate['esi_form_verified'] == 'yes') ? 'yes' : ((get_comment('esi_form', $comments) != "") ? 'no' : 'yes');
                    ?>
                    <div><?= candidate_docs($candidate['esi_form'], 'esi_form', 'candidate', '4', $verified); ?></div>
                </div>
            </div>
        <?php } ?>
        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label>NDA<sup>*</sup><?= get_comment('signed_nda', $comments) ?></label>
                <?php if ($candidate['signed_nda_verified'] == 'no') { ?>
                    <input type="file" class="form-control <?= (get_comment('signed_nda', $comments) != "") ? 'allowedit' : '' ?> <?= ($candidate['signed_nda'] != "") ? 'temphidden' : '' ?>" name="signed_nda[]" id="signed_nda" value="" multiple>
                <?php }
                $verified = ($candidate['signed_nda_verified'] == 'yes') ? 'yes' : ((get_comment('signed_nda', $comments) != "") ? 'no' : 'yes');
                ?>
                <div><?= candidate_docs($candidate['signed_nda'], 'signed_nda', 'candidate', '4', $verified); ?></div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-sm-12">
            <?php if (get_status_stage($candidate['candidate_id'], 4, 'approval_status') != 'approved') : ?>
                <div class="mt-4">
                    <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />
                    <button type="button" class="btn btn-lg btn-danger" id="enable_edit_4">Edit</button>
                    <input type="button" class="btn btn-sm btn-primary sprogress_4" style="position: fixed;right: 0px;bottom: 1rem;z-index: 10000;left: 0px;margin: auto;width: 150px;" onclick="autosave_4()" value="Save Draft" />
                </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<!-- /from -->
<script type="text/javascript">
    $(function() {
        //to disable fields when stage approved
        var stage_verified = '<?= get_status_stage($candidate['candidate_id'], 4, 'approval_status') ?>';
        if (stage_verified == 'approved') {
            $("#candidate_add_step_four input, #candidate_add_step_four select").attr('disabled', true);
        }
    });

    function changeRelievingLetter(selval) {
        $("#releaving_letter_div").hide();
        $("#relieving_date_div").hide();
        if (selval == 1) {
            $("#releaving_letter_div").show();
        } else {
            $("#relieving_date_div").show();
        }

    }
</script>
<?php if (get_status_stage($candidate['candidate_id'], 4, 'approval_status') != 'approved') : ?>
    <script type="text/javascript">
        $(function() {

            var comments_count_s4 = '<?= $comments_count4 ?>';
            if (comments_count_s4 <= 0) {
                $('#candidate_add_step_four #enable_edit_4').addClass('d-none');
            } else {
                $('#candidate_add_step_four #enable_edit_4').removeClass('d-none');
            }

            //disable the form on open and enable on edit button click
            var stype = '<?= $candidate['stage_4_submit'] ?>';
            if (stype == "yes") {
                $("#candidate_add_step_four input:not([type=hidden]), #candidate_add_step_four select").attr('disabled', true);
                $('#candidate_add_step_four .closebtn').css('pointer-events', 'none');
                $('#candidate_add_step_four .btnsmt, #candidate_add_step_four .sprogress_4').addClass('d-none');
            } else {
                $('#candidate_add_step_four #enable_edit_4').addClass('d-none');
                $('#candidate_add_step_four .btnsmt').removeClass('d-none');
            }

            $('#enable_edit_4').on("click", function() {

                $('#candidate_add_step_four #enable_edit_4').addClass('d-none');
                $("#candidate_add_step_four input.allowedit, #candidate_add_step_four select.allowedit").attr('disabled', false);
                $('#candidate_add_step_four .closebtn').css('pointer-events', 'all');
                $('#candidate_add_step_four .btnsmt').attr('disabled', false).removeClass('d-none');

                $('.chosen-select').trigger('chosen:updated');

                if (stype == "no") {
                    $('#candidate_add_step_four .sprogress_4').attr('disabled', false).removeClass('d-none');
                }

                //validate all fields with particular class

                $("#candidate_add_step_four .edu-required-file").each(function() {
                    $(this).rules('add', {
                        required: true,
                        extension: "png|jpg|jpeg|pdf",
                        maxupload: 3,
                        maxfilesize: 2
                    });
                });
            });


            $('.datepicker').datepicker({
                format: 'dd-M-yyyy',
                todayHighlight: true,
                autoclose: true
            });

            $("#candidate_add_step_four").validate({
                ignore: ":hidden:not(.chosen-select)",
                rules: {
                    'company_name[0]': {
                        required: true
                    },
                    'reporting_manager_name[0]': {
                        required: true,
                    },
                    'ref_designation[0]': {
                        required: true
                    },
                    'official_email_rm[0]': {
                        required: true,
                        email: true
                    },
                    'mobile_number_rm[0]': {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    'country_code_rm[0]': {
                        required: true,
                        // minlength: 3,
                        // maxlength: 8,
                        // countrycode: true
                    },
                    'hr_name[0]': {
                        required: true
                    },
                    'official_email_hr[0]': {
                        required: true,
                        email: true
                    },
                    'mobile_number_hr[0]': {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    'country_code_hr[0]': {
                        required: true,
                        // minlength: 3,
                        // maxlength: 8,
                        // countrycode: true
                    },
                    /*  'company_name[1]': {
                          required: true
                      },
                      'reporting_manager_name[1]': {
                          required: true,
                      },
                      'ref_designation[1]': {
                          required: true
                      },
                      'official_email_rm[1]': {
                          required: true,
                          email: true
                      },
                      'mobile_number_rm[1]': {
                          required: true,
                          digits: true,
                          minlength: 10,
                          maxlength: 10
                      },
                      'country_code_rm[1]': {
                          required: true,
                          // minlength: 3,
                          // maxlength: 8,
                          // countrycode: true
                      },
                      'hr_name[1]': {
                          required: true
                      },
                      'official_email_hr[1]': {
                          required: true,
                          email: true
                      },
                      'mobile_number_hr[1]': {
                          required: true,
                          digits: true,
                          minlength: 10,
                          maxlength: 10
                      },
                      'country_code_hr[1]': {
                          required: true,
                          // minlength: 3,
                          // maxlength: 8,
                          // countrycode: true
                      },*/
                    college_name: {
                        required: true
                    },
                    name_of_hod: {
                        required: true
                    },
                    email_id_hod: {
                        required: true,
                        email: true
                    },
                    mobile_number_hod: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    country_code_hod: {
                        required: true,
                        // minlength: 3,
                        // maxlength: 8,
                        // countrycode: true
                    },
                    project_cord_name: {
                        required: true
                    },
                    email_id_cord: {
                        required: true,
                        email: true
                    },
                    mobile_number_cord: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    country_code_cord: {
                        required: true,
                        // minlength: 3,
                        // maxlength: 8,
                        // countrycode: true
                    },
                    major_illness_id: {
                        required: true
                    },
                    operation_or_accident: {
                        required: true
                    },
                    medical_exam_date: {
                        required: true
                    },
                    identification_mark: {
                        required: true
                    },
                    db_bp_ep_finding: {
                        required: true
                    },
                    db_bp_family: {
                        required: true
                    },
                    musculoskeletal_problem: {
                        required: true
                    },
                    'uan_proof[]': {
                        extension: "png|jpg|jpeg|pdf",
                        maxupload: 3,
                        maxfilesize: 2
                    },
                    'power_backup_proof[]': {
                        required: true,
                        extension: "png|jpg|jpeg|pdf",
                        maxupload: 3,
                        maxfilesize: 2
                    },
                    'internet_bill[]': {
                        required: true,
                        extension: "png|jpg|jpeg|pdf",
                        maxupload: 3,
                        maxfilesize: 2
                    },
                    'internet_speed[]': {
                        required: true,
                        extension: "png|jpg|jpeg|pdf",
                        maxupload: 3,
                        maxfilesize: 2
                    },
                    'fitness_certificate[]': {
                        required: true,
                        extension: "png|jpg|jpeg|pdf",
                        maxupload: 3,
                        maxfilesize: 2
                    },
                    'fitness_certificate_self[]': {
                        required: true,
                        extension: "png|jpg|jpeg|pdf",
                        maxupload: 3,
                        maxfilesize: 2
                    },
                    'releaving_letter_previous[]': {
                        required: true,
                        extension: "png|jpg|jpeg|pdf",
                        maxupload: 3,
                        maxfilesize: 2
                    },
                    'form_2[]': {
                        required: true,
                        extension: "png|jpg|jpeg|pdf",
                        maxupload: 3,
                        maxfilesize: 2
                    },
                    'form_q[]': {
                        required: true,
                        extension: "png|jpg|jpeg|pdf",
                        maxupload: 3,
                        maxfilesize: 2
                    },
                    'form_11[]': {
                        required: true,
                        extension: "png|jpg|jpeg|pdf",
                        maxupload: 3,
                        maxfilesize: 2
                    },
                    'form_1[]': {
                        required: true,
                        extension: "png|jpg|jpeg|pdf",
                        maxupload: 3,
                        maxfilesize: 2
                    },
                    'esi_form[]': {
                        required: true,
                        extension: "png|jpg|jpeg|pdf",
                        maxupload: 3,
                        maxfilesize: 2
                    },
                    'signed_nda[]': {
                        required: true,
                        extension: "png|jpg|jpeg|pdf",
                        maxupload: 3,
                        maxfilesize: 2
                    },
                },
                messages: {

                },
                submitHandler: function(form, e) {
                    e.preventDefault();
                    $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');
                    var form_data = new FormData(form);
                    form_data.append('action', 'submit');
                    $('.page-loader-wrapper').removeAttr('style');
                    setTimeout(function() {
                        $.ajax({
                            type: 'POST',
                            url: base_url + 'user/stage_4_update',
                            cache: false,
                            async: false,
                            data: form_data,
                            contentType: false,
                            processData: false,
                            success: function(response) {

                                //console.log(response);
                                var obj = $.parseJSON(response);
                                if (obj.status == 1) {
                                    $('.page-loader-wrapper').fadeOut();
                                    showUpdateMessage();
                                    $('.btnsmt').prop('disabled', false).attr('value', 'Submit');
                                } else {
                                    $('.page-loader-wrapper').fadeOut();
                                    toaster('error', obj.msg);
                                    $('.btnsmt').prop('disabled', false).attr('value', 'Submit');
                                }

                            },
                            error: function(error) {
                                $('.page-loader-wrapper').fadeOut();
                                toaster('error', error);
                                $('.btnsmt').prop('disabled', false).attr('value', 'Submit');
                            }
                        });
                    }, 500);
                    return false;
                }
            });
        });

        setTimeout(() => {
            $("#candidate_add_step_four .edu-required-file").each(function() {
                $(this).rules('add', {
                    required: true,
                    extension: "png|jpg|jpeg|pdf",
                    maxupload: 6,
                    maxfilesize: 6
                });
            });
        }, 500);



        $('.cmpnyselect').on('change', function() {
            $('option[disabled]').prop('disabled', false);
            $('.cmpnyselect').each(function() {
                $('.cmpnyselect').not(this).find('option[value="' + this.value + '"]').prop('disabled', true);
            });
        });

        //select all comnay on page load
        $(function() {
            $('.cmpnyselect').each(function() {
                $('.cmpnyselect').not(this).find('option[value="' + this.value + '"]').prop('disabled', true);
            });
        });

        function autosave_4() {

            var form = document.getElementById('candidate_add_step_four');
            var form_data = new FormData(form);
            form_data.append('action', 'save');
            $('.sprogress_4').prop('disabled', true).attr('value', 'Processing...');

            $('.page-loader-wrapper').removeAttr('style');

            setTimeout(function() {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'user/stage_4_update',
                    cache: false,
                    async: false,
                    data: form_data,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var obj = $.parseJSON(response);
                        if (obj.status == 1) {
                            $('.page-loader-wrapper').fadeOut();
                            toaster('warning', 'Please click submit to send for verification!');
                            toaster('warning', 'Data saved in draft!');
                            $('.sprogress_4').prop('disabled', false).attr('value', 'Save Draft');
                            window.location.reload();
                        } else {
                            $('.page-loader-wrapper').fadeOut();
                            toaster('error', obj.msg);
                            $('.sprogress_4').prop('disabled', false).attr('value', 'Save Draft');
                        }
                    },
                    error: function(error) {
                        $('.page-loader-wrapper').fadeOut();
                        toaster('error', 'Error while saving!');
                        $('.sprogress_4').prop('disabled', false).attr('value', 'Save Draft');
                    }
                });
            }, 500)
        }
    </script>
<?php endif; ?>