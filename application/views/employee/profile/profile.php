<div class="row profilepage_2 blog-page">
    <div class="col-lg-12 col-md-12 text-right mb-2">
        <a href="<?= base_url('employee/edit_employee/' . $employee_details['employee_id']) ?>" class="btn btn-sm btn-primary">Edit</a>
    </div>
    <div class="col-lg-3 col-md-12">
        <div class="card profile-header">
            <div class="body">
                <div class="profile-image"> <img src="<?= getUserImage($employee_details['profile_photo']) ?>" class="rounded-circle" alt="<?= $employee_details['name'] ?>"> </div>
                <div>
                    <h6 class="m-b-1"><strong><?= $employee_details['name'] ?></strong></h6>
                    <div>Code: <strong><?= $employee_details['code'] ?></strong></div>
                    <div class="mt-1"><?= employee_status_c($employee_details['status']) ?></div>
                </div>
            </div>
        </div>

        <?php if (!empty($employee_details['special_role'])) : ?>
            <div class="card">
                <div class="header">
                    <h6>Special Roles</h6>
                </div>
                <div class="body pt-0">
                    <span class="badge badge-primary"><?= ucfirst($employee_details['special_role']) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="header">
                <h6>Reporting Manager</h6>

            </div>
            <div class="body pt-0">

                <?php if (!empty($employee_details['reporting_manager_id'])) { ?>
                    <p><?= $employee_details['reporting_manager'] ?></p>
                <?php
                } else { ?>
                    -
                <?php } ?>
            </div>
        </div>
        <div class="sticky-top profile-menu">
            <div class="card">
                <div class="card-body">
                    <ul class="nav">
                        <li>
                            <a href="#general" class="scrollLink"><i class="fa fa-circle text-danger"></i>General Details</a>
                        </li>
                        <li>
                            <a href="#educational" class="scrollLink"><i class="fa fa-circle text-info"></i>Educational History</a>
                        </li>
                        <li>
                            <a href="#certification_history" class="scrollLink"><i class="fa fa-circle text-dark"></i>Certification History</a>
                        </li>
                        <li>
                            <a href="#employment_history" class="scrollLink"><i class="fa fa-circle text-primary"></i>Employment History</a>
                        </li>
                        <li>
                            <a href="#family_details" class="scrollLink"><i class="fa fa-circle text-secondary"></i>Family Details</a>
                        </li>
                        <li>
                            <a href="#password" class="scrollLink"><i class="fa fa-circle text-warning"></i>Change Password</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-md-12">
        <div class="card" id="general">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover m-b-0">
                        <tbody>
                            <tr>
                                <td width="25%">Code:</td>
                                <td><?= $employee_details['code'] ?></td>
                            </tr>
                            <tr>
                                <td>Status:</td>
                                <td><?= employee_status_n($employee_details['status']) ?></td>
                            </tr>
                            <tr>
                                <td>Name:</td>
                                <td><?= $employee_details['name'] ?></td>
                            </tr>
                            <tr>
                                <td>Mobile No.:</td>
                                <td><?= $employee_details['mobile_number'] ?? "-" ?></td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td><?= $employee_details['email'] ?? "-" ?></td>
                            </tr>
                            <tr>
                                <td>Present Address:</td>
                                <td><?= $employee_details['pre_address'] ?></td>
                            </tr>
                            <tr>
                                <td>District:</td>
                                <td><?= $employee_details['pre_district'] ?></td>
                            </tr>
                            <tr>
                                <td>State:</td>
                                <td><?= $employee_details['pre_state'] ?></td>
                            </tr>
                            <tr>
                                <td>Country:</td>
                                <td><?= $employee_details['pre_address'] ?></td>
                            </tr>
                            <tr>
                                <td>PIN Code:</td>
                                <td><?= $employee_details['pre_pincode'] ?></td>
                            </tr>
                            <tr>
                                <td>Permanent address:</td>
                                <td><?= $employee_details['address'] ?></td>
                            </tr>
                            <tr>
                                <td>District:</td>
                                <td><?= $employee_details['per_district'] ?></td>
                            </tr>
                            <tr>
                                <td>State:</td>
                                <td><?= $employee_details['per_state'] ?></td>
                            </tr>
                            <tr>
                                <td>Country:</td>
                                <td><?= $employee_details['per_country'] ?></td>
                            </tr>
                            <tr>
                                <td>PIN Code:</td>
                                <td><?= $employee_details['pincode'] ?></td>
                            </tr>
                            <tr>
                                <td>Nationality:</td>
                                <td><?= $employee_details['nationality'] ?></td>
                            </tr>
                            <tr>
                                <td>Date of birth:</td>
                                <td><?= get_date($employee_details['date_of_birth']) ?></td>
                            </tr>
                            <tr>
                                <td>Age:</td>
                                <td><?= ageCalculator($employee_details['date_of_birth']) ?></td>
                            </tr>
                            <tr>
                                <td>Gender:</td>
                                <td><?= $employee_details['gender'] ?></td>
                            </tr>
                            <tr>
                                <td>Religion:</td>
                                <td><?= $employee_details['religion'] ?></td>
                            </tr>
                            <tr>
                                <td>Category:</td>
                                <td><?= $employee_details['category'] ?></td>
                            </tr>
                            <tr>
                                <td>Marital Status:</td>
                                <td><?= $employee_details['marital_status'] ?></td>
                            </tr>
                            <tr>
                                <td>Blood Group:</td>
                                <td><?= $employee_details['blood_group'] ?></td>
                            </tr>
                            <tr>
                                <td>Currently employed?:</td>
                                <td><?= ($employee_details['currently_employed']) ? ucfirst($employee_details['currently_employed']) : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Resume:</td>
                                <td>
                                    <?= display_images($employee_details['resume'], $employee_details['employee_id']); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Date of join:</td>
                                <td><?= get_date($employee_details['joining_date']) ?></td>
                            </tr>
                            <tr>
                                <td>MRF Ref.no.:</td>
                                <td><?= $employee_details['mrf_refno'] ?></td>
                            </tr>
                            <tr>
                                <td>Last worked date:</td>
                                <td><?= $employee_details['last_work_date'] ?? "-" ?></td>
                            </tr>
                            <tr>
                                <td>WFH/Office/Hybrid:</td>
                                <td><?= ucfirst($employee_details['wfh_or_office'] ?? '') ?></td>
                            </tr>
                            <tr>
                                <td>Department:</td>
                                <td><?= $employee_details['department'] ?></td>
                            </tr>
                            <tr>
                                <td>Sub Department:</td>
                                <td><?= $employee_details['sub_department'] ?></td>
                            </tr>
                            <tr>
                                <td>Designation:</td>
                                <td><?= $employee_details['designation'] ?></td>
                            </tr>
                            <tr>
                                <td>Work Location:</td>
                                <td><?= $employee_details['work_location'] ?></td>
                            </tr>
                            <tr>
                                <td>Shift:</td>
                                <td><?= $employee_details['shift'] ?></td>
                            </tr>
                            <tr>
                                <td>Vacancy Type:</td>
                                <td><?= ucfirst($employee_details['vacancy_type'] ?? '') ?></td>
                            </tr>
                            <tr>
                                <td>Replacement For:</td>
                                <td><?= ucfirst($employee_details['replacement_for'] ?? "") ?></td>
                            </tr>
                            <tr>
                                <td>Band:</td>
                                <td><?= $employee_details['band'] ?? "-" ?></td>
                            </tr>
                            <tr>
                                <td>Sub Band:</td>
                                <td><?= $employee_details['sub_band'] ?? "-" ?></td>
                            </tr>
                            <tr>
                                <td>Probation Status:</td>
                                <td><?= probation_status($employee_details['probation_status']) ?></td>
                            </tr>
                            <tr>
                                <td>Date of offer Release:</td>
                                <td><?= get_date($employee_details['offerletter_release_date']) ?></td>
                            </tr>
                            <tr>
                                <td>Offer Letter:</td>
                                <td>
                                    <?= display_images_verified($employee_details['offerletter'], $employee_details['employee_id']); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Accepted Offer Letter:</td>
                                <td>
                                    <?= display_images_verified($employee_details['offerletter_accepted'], $employee_details['employee_id']); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Experience before Ksoft (In months):</td>
                                <td><?= $employee_details['experience_before_enventure']; ?></td>
                            </tr>
                            <tr>
                                <td>Proof of Resignation:</td>
                                <td><?= display_images($employee_details['resignation_proof'], $employee_details['employee_id']); ?></td>
                            </tr>
                            <tr>
                                <td>Proof of Authorization Letter:</td>
                                <td><?= display_images($employee_details['authorization_letter'], $employee_details['employee_id']); ?></td>
                            </tr>
                            <tr>
                                <td>Proof of Acceptance of Resignation:</td>
                                <td><?= display_images($employee_details['resignation_accept_proof'], $employee_details['employee_id']); ?></td>
                            </tr>
                            <tr>
                                <td>UAN Details:</td>
                                <td><?= display_images($employee_details['uan_proof'], $employee_details['employee_id']); ?></td>
                            </tr>
                            <tr>
                                <td>Power Backup proof:</td>
                                <td><?= display_images($employee_details['power_backup_proof'], $employee_details['employee_id']); ?></td>
                            </tr>
                            <tr>
                                <td>Internet Bill of last month:</td>
                                <td><?= display_images($employee_details['internet_bill'], $employee_details['employee_id']); ?></td>
                            </tr>
                            <tr>
                                <td>Screenshot of Internet Speed test:</td>
                                <td><?= display_images($employee_details['internet_speed'], $employee_details['employee_id']); ?></td>
                            </tr>
                            <tr>
                                <td>Identification Marks:</td>
                                <td><?= ucfirst($employee_details['identification_mark'] ?? '') ?></td>
                            </tr>
                            <tr>
                                <td>Any Operations or Accidents or Injuries?:</td>
                                <td><?= ucfirst($employee_details['operation_or_accident'] ?? '') ?></td>
                            </tr>
                            <tr>
                                <td>Any findings in you of Diabetes, High Blood Pressure or Epilipsy:</td>
                                <td><?= ucfirst($employee_details['db_bp_ep_finding'] ?? '') ?></td>
                            </tr>
                            <tr>
                                <td>Any Family History of Diabetes or High Blood Pressure?:</td>
                                <td><?= ucfirst($employee_details['db_bp_family'] ?? '') ?></td>
                            </tr>
                            <tr>
                                <td>Any Musculoskeletal problems?:</td>
                                <td><?= ucfirst($employee_details['musculoskeletal_problem'] ?? '') ?></td>
                            </tr>

                            <tr>
                                <td>Fitness Certificate from Medical Officer:</td>
                                <td><?= display_images($employee_details['fitness_certificate'], $employee_details['employee_id']); ?></td>
                            </tr>
                            <tr>
                                <td>Fitness Certificate Self Declaration:</td>
                                <td><?= display_images($employee_details['fitness_certificate_self'], $employee_details['employee_id']); ?></td>
                            </tr>
                            <tr>
                                <td>Basic:</td>
                                <td><?= ($employee_details['basic'] != "") ? $employee_details['basic'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>VDA:</td>
                                <td><?= ($employee_details['vda'] != "") ? $employee_details['vda'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>HRA:</td>
                                <td><?= ($employee_details['hra'] != "") ? $employee_details['hra'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Night Shift Allowance:</td>
                                <td><?= ($employee_details['nightshift_allowance'] != "") ? $employee_details['nightshift_allowance'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>WFH Allowance:</td>
                                <td><?= ($employee_details['wfh_allowance'] != "") ? $employee_details['wfh_allowance'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Flexi Benefits Allowance Plan:</td>
                                <td><?= ($employee_details['flexi_allowance'] != "") ? $employee_details['flexi_allowance'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Variable Performance Bonus:</td>
                                <td><?= ($employee_details['variable_performance_bonus'] != "") ? $employee_details['variable_performance_bonus'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Frequency of payment for VPB:</td>
                                <td><?= ($employee_details['payment_frequency_vpb'] != "") ? $employee_details['payment_frequency_vpb'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Employer PF:</td>
                                <td><?= ($employee_details['employer_pf'] != "") ? $employee_details['employer_pf'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Employer ESI:</td>
                                <td><?= ($employee_details['employer_esi'] != "") ? $employee_details['employer_esi'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Employer Gratuity:</td>
                                <td><?= ($employee_details['employer_gratuity'] != "") ? $employee_details['employer_gratuity'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Annual Guaranteed Bonus / Retention Bonus:</td>
                                <td><?= ($employee_details['advanced_retension_bonus'] != "") ? $employee_details['advanced_retension_bonus'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Advanced Retention Bonus(Rs):</td>
                                <td><?= ($employee_details['annual_retention_bonus'] != "") ? $employee_details['annual_retention_bonus'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Months eligible for Retention Bonus:</td>
                                <td><?= ($employee_details['months_eligible_bonus'] != "") ? $employee_details['months_eligible_bonus'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Due date for Payment of Retention bonus:</td>
                                <td><?= get_date($employee_details['retention_bonus_duedate']) ?></td>
                            </tr>
                            <tr>
                                <td>Statutory Bonus:</td>
                                <td><?= ($employee_details['statutory_bonus'] != "") ? $employee_details['statutory_bonus'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Medical Insurance:</td>
                                <td><?= ($employee_details['medical_insurance'] != "") ? $employee_details['medical_insurance'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Referral Amount to be paid:</td>
                                <td><?= ($employee_details['referral_amount'] != "") ? $employee_details['referral_amount'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Due date for Referral Amount to be paid:</td>
                                <td><?= get_date($employee_details['referral_amount_duedate']) ?></td>
                            </tr>
                            <tr>
                                <td>Reference Received from:</td>
                                <td><?= ($employee_details['referrance_received'] != "") ? ucfirst($employee_details['referrance_received']) : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Effective Date of the Salary:</td>
                                <td><?= get_date($employee_details['effective_date_of_salary']) ?></td>
                            </tr>
                            <tr>
                                <td>Gross:</td>
                                <td><?= ($employee_details['gross'] != "") ? $employee_details['gross'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Monthly CTC:</td>
                                <td><?= ($employee_details['monthly_ctc'] != "") ? $employee_details['monthly_ctc'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>Annual CTC:</td>
                                <td><?= ($employee_details['annual_ctc'] != "") ? $employee_details['annual_ctc'] : '-' ?></td>
                            </tr>

                            <tr>
                                <td>Candidate Assessment Sheet:</td>
                                <td><?= display_images_verified($employee_details['candidate_assessment_sheet'], $employee_details['employee_id']); ?></td>
                            </tr>
                            <tr>
                                <td>Candidate Assessment Sheet Signed:</td>
                                <td><?= display_images_verified($employee_details['candidate_assessment_sheet_signed'], $employee_details['employee_id']); ?></td>
                            </tr>
                            <tr>
                                <td>Approved MRF File:</td>
                                <td><?= display_images_verified($employee_details['mrf_file'], $employee_details['employee_id']); ?></td>
                            </tr>
                            <tr>
                                <td>Accepted Appointment Letter:</td>
                                <td><?= display_images_verified($employee_details['accepted_appointment_letter'], $employee_details['employee_id']) ?></td>
                            </tr>

                            <tr>
                                <td>Relieving Letter from previous Company:</td>
                                <td><?= display_images($employee_details['releaving_letter_previous'], $employee_details['employee_id']) ?></td>
                            </tr>
                            <tr>
                                <td>Joining Report:</td>
                                <td><?= display_images_verified($employee_details['joining_report'], $employee_details['employee_id']) ?></td>
                            </tr>
                            <tr>
                                <td>Form - 2:</td>
                                <td><?= display_images($employee_details['form_2'], $employee_details['employee_id']) ?></td>
                            </tr>
                            <tr>
                                <td>Form Q:</td>
                                <td><?= display_images($employee_details['form_q'], $employee_details['employee_id']) ?></td>
                            </tr>
                            <tr>
                                <td>Form 11:</td>
                                <td><?= display_images($employee_details['form_11'], $employee_details['employee_id']) ?></td>
                            </tr>
                            <tr>
                                <td>Form I:</td>
                                <td><?= display_images($employee_details['form_1'], $employee_details['employee_id']) ?></td>
                            </tr>
                            <tr>
                                <td>ESI Form:</td>
                                <td><?= display_images($employee_details['esi_form'], $employee_details['employee_id']) ?></td>
                            </tr>
                            <tr>
                                <td>Probation Duration (Months):</td>
                                <td><?= ($employee_details['probation_duration'] != "") ? $employee_details['probation_duration'] : '-' ?></td>
                            </tr>
                            <tr>
                                <td>First Increment Due Date:</td>
                                <td><?= get_date($employee_details['first_increment_duedate']) ?></td>
                            </tr>
                            <tr>
                                <td>Reference Check:</td>
                                <td><?= display_images_verified($employee_details['reference_check'], $employee_details['employee_id']) ?></td>
                            </tr>
                            <tr>
                                <td>Due Date for Confirmation:</td>
                                <td><?= get_date($employee_details['confirmation_duedate']) ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card" id="educational">
            <div class="header">
                <h6>Educational History</h6>
            </div>
            <div class="body pt-0">

                <div class="table-responsive">
                    <table class="table table-hover m-b-0">
                        <thead>
                            <th>#</th>
                            <th>Qualification</th>
                            <th>Specialization</th>
                            <th>Institute</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>Nearest City</th>
                            <th>% Marks / Grade</th>
                            <th>Year of Completion</th>
                            <th>Mark Cards</th>
                        </thead>
                        <tbody>
                            <?php if (!empty($education_history)) : ?>
                                <?php foreach ($education_history as $key => $row) : ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $row['course'] ?></td>
                                        <td><?= $row['specialization'] ?></td>
                                        <td><?= $row['institute_name'] ?></td>
                                        <td><?= $row['country'] ?></td>
                                        <td><?= $row['state'] ?></td>
                                        <td><?= $row['nearest_city'] ?></td>
                                        <td><?= $row['percentage_mark'] ?></td>
                                        <td><?= $row['completion_year'] ?></td>
                                        <td>
                                            <?php if (!empty($row['mark_card'])) : ?>
                                                <?php $files = explode(',', $row['mark_card']) ?>
                                                <div class="align-items-center d-flex">
                                                    <!-- <div class="mb-0 mr-2">Documents:</div> -->
                                                    <ul class="list-unstyled team-info margin-0">
                                                        <?php foreach ($files as $file) : ?>
                                                            <li>
                                                                <a href="<?= base_url('assets/uploads/user_docs/' . $row['candidate_id'] . '/' . $file) ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="Click to open">
                                                                    <img src="<?= base_url() ?>assets/common/preview.svg" alt="Avatar" />
                                                                </a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted">No records found!</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card" id="certification_history">
            <div class="header">
                <h6>Certification History</h6>
            </div>
            <div class="body pt-0">

                <div class="table-responsive">
                    <table class="table table-hover m-b-0">
                        <thead>
                            <th>#</th>
                            <th>Name of the Certification</th>
                            <th>Specialization</th>
                            <th>Institute</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Valid Upto</th>
                        </thead>
                        <tbody>
                            <?php if (!empty($certification_history)) : ?>
                                <?php foreach ($certification_history as $key => $row) : ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $row['certification'] ?></td>
                                        <td><?= $row['specialization'] ?></td>
                                        <td><?= $row['institute_name'] ?></td>
                                        <td><?= get_date($row['start_date']) ?></td>
                                        <td><?= get_date($row['end_date']) ?></td>
                                        <td><?= get_date($row['valid_upto']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted">No records found!</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card" id="employment_history">
            <div class="header">
                <h6>Employment History</h6>
            </div>
            <div class="body pt-0">
                <div class="row">
                    <div class="col-auto">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Do you have Employment History?:</td>
                                    <td><?= ucfirst($employee_details['employment_history'] ?? '') ?></td>
                                </tr>
                                <?php if ($employee_details['employment_history'] == 'yes') : ?>
                                    <tr>
                                        <td>Salary Slips for past three months:</td>
                                        <td>
                                            <?= display_images($employee_details['salary_slip_last3'], $employee_details['employee_id']); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Salary Break-up:</td>
                                        <td>
                                            <?= display_images($employee_details['salary_breakup'], $employee_details['employee_id']); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($employee_details['employment_history'] == 'yes') : ?>
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-hover m-b-0">
                                    <thead>
                                        <th>#</th>
                                        <th>Company Name</th>
                                        <th>Country</th>
                                        <th>State</th>
                                        <th>Nearest City</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Last Designation Held</th>
                                        <th>Responsibilities Handled</th>
                                        <th>Annual CTC (LPA) </th>
                                        <th>Reason For Job Change </th>
                                        <th>Is This Experience Relevant? </th>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($employment_history)) : ?>
                                            <?php foreach ($employment_history as $key => $row) : ?>
                                                <tr>
                                                    <td><?= $key + 1 ?></td>
                                                    <td><?= $row['company_name'] ?></td>
                                                    <td><?= $row['country'] ?></td>
                                                    <td><?= $row['state'] ?></td>
                                                    <td><?= $row['nearest_city'] ?></td>
                                                    <td><?= get_date($row['start_date']) ?></td>
                                                    <td><?= get_date($row['end_date']) ?></td>
                                                    <td><?= $row['last_designation'] ?></td>
                                                    <td><?= $row['responsibilities'] ?></td>
                                                    <td><?= $row['annual_ctc'] ?></td>
                                                    <td><?= $row['reason_jobchance'] ?></td>
                                                    <td><?= $row['is_this_relevant_exp'] == 1 ? 'Yes' : ($row['is_this_relevant_exp'] == 2 ? "No" : "") ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="12" class="text-center text-muted">No records found!</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>

        <div class="card" id="family_details">
            <div class="header">
                <h6>Family Details</h6>
            </div>
            <div class="body pt-0">

                <div class="table-responsive">
                    <table class="table table-hover m-b-0">
                        <thead>
                            <th>#</th>
                            <th>Name of family member</th>
                            <th>Relation</th>
                            <th>Living Status</th>
                            <th>Gender</th>
                            <th>Date of Birth</th>
                            <th>Occupation</th>
                            <th>Mobile Number</th>
                            <th>Emergency Contact</th>
                        </thead>
                        <tbody>
                            <?php if (!empty($family_details)) : ?>
                                <?php foreach ($family_details as $key => $row) : ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $row['name'] ?></td>
                                        <td><?= $row['relationship'] ?></td>
                                        <td><?= $row['living_status'] ?></td>
                                        <td><?= $row['gender'] ?></td>
                                        <td><?= get_date($row['date_of_birth']) ?></td>
                                        <td><?= $row['occupation'] ?></td>
                                        <td><?= $row['mobile_number'] ?></td>
                                        <td><?= $row['emergency_contact'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="12" class="text-center text-muted">No records found!</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card" id="password">
            <div class="header">
                <h2>Change Password</h2>
            </div>
            <div class="body pt-0">
                <form class="form-auth-small" action="" name="change_password" id="change_password" method="POST" enctype="multipart/form-data">
                    <div class="row clearfix">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>New Password<sup>*</sup></label>
                                <input type="text" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Confirm Password<sup>*</sup></label>
                                <input type="password" class="form-control" name="confirm_password">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="mt-4">
                                <input type="submit" class="btn btn-lg btn-primary btnsmt" value="Submit" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    $("#change_password").validate({
        rules: {
            password: {
                required: true,
                minlength: 3
            },
            confirm_password: {
                required: true,
                minlength: 3,
                equalTo: '[name="password"]'
            }
        },
        messages: {
            password: {
                required: "Please enter password"
            },
            confirm_password: {
                required: "Please confirm password",
                equalTo: "Password mismatch"
            }
        },
        submitHandler: function(form, e) {

            e.preventDefault();

            $(form).prop('disabled', true).attr('value', 'Processing...');

            var code = '<?= $employee_details['code'] ?>';
            var form_data = new FormData(form);
            form_data.append('code', code);

            setTimeout(function() {
                $.ajax({
                    type: 'POST',
                    url: base_url + 'user/change_password',
                    cache: false,
                    async: false,
                    data: form_data,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        var obj = $.parseJSON(response);
                        if (obj.status == 1) {
                            toaster('success', obj.msg);
                            $('input[name="password"]').val("");
                            $('input[name="confirm_password"]').val("");
                            $(form).prop('disabled', false).attr('value', 'Submit');

                        } else {
                            toaster('error', obj.msg);
                            $(form).prop('disabled', false).attr('value', 'Submit');
                        }
                    },
                    error: function(error) {
                        toaster('error', error);
                        $(form).prop('disabled', false).attr('value', 'Submit');
                    }
                });
            }, 500);
            return false;
        }
    });
</script>