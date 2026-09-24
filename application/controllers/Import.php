<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Import extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!check_user_login()) {
            redirect('signin', 'refresh');
        }
        if (!check_login('1')) {
            redirect('signin', 'refresh');
        }
        $this->load->model('employee_model');
    }

    public function index()
    {
        $this->import();
    }

    public function import()
    {
        $page_data['page_type']    = 'employee/import';
        $page_data['menu']         = 'employee';
        $page_data['page_name']    = 'employee_import';
        $page_data['page_title']   = 'Import Employees';
        $this->load->view('theme/user/main', $page_data);
    }

    public function import_post()
    {
        $input = $this->input->post(null, true);
        $select = 'e.employee_id,e.code,e.name,e.approved_date';
        $where['1'] = "1";
        $order_by = 'e.employee_id DESC';
        $last_row = $this->employee_model->get_employees($select, $where, array('order_by' => $order_by))->row_array();
        $filters = array('approved_date' => $last_row['approved_date'] ?? '', 'limit' => $input['limit'] ?? '');
        $json = json_encode($filters ?? []);
        // pr($json);
        // exit;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://ksoftcloud.com/ecos/mrf/api/get_candidates",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $json
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $output = array();
        if ($err) {
            $response = array('status' => false, 'total' => 0, 'inserted' => 0, 'skipped' => 0, 'failed' => 0);
        } else {
            $output = json_decode($response);
        }
        // exit;
        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_URL, "http://52.76.137.192/enventuremrf/api/get_candidates");
        // // curl_setopt($curl, CURLOPT_URL, 'http://localhost/env_recruiter_05032024/api/get_candidates');
        // curl_setopt($curl, CURLOPT_POST, true);
        // // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        // $output = curl_exec($curl);
        // curl_close($curl);
        // $output = json_decode($output);

        // pr($output);
        // exit;
        // exit;
        // if(empty($output)){
        // 	$output=[];
        // }
        $total = count($output);
        $skipped = 0;
        $inserted = 0;
        $failed = 0;
        if (!empty($output)) {

            foreach ($output as $key => $row) {
                // pr($output);exit;
                $exist = $this->common_model->selectOne('employee', array('code' => $row->employee_code), 'employee_id,code,name');
                if (empty($exist)) {

                    $insert['name']                     = $row->standardised_name;
                    $insert['code']                    = $row->employee_code;
                    $insert['email']                = $row->official_email_id;
                    $insert['department_id'] = $row->department;
                    $insert['designation_id'] = $row->designation;

                    $insert['candidate_num']     = $row->candidate_num;
                    $insert['full_name']     = $row->full_name;
                    $insert['standardised_name']     = $row->standardised_name;
                    $insert['personal_email']     = $row->personal_email;
                    $insert['mobile_number'] = $row->mobile_number;
                    $insert['country_code'] = $row->country_code;
                    $insert['gender'] = $row->gender;
                    $insert['nationality_id'] = $row->nationality_id;
                    $insert['gender'] = $row->gender;
                    $insert['religion'] = $row->religion;
                    $insert['category_id'] = $row->category_id;
                    $insert['how_you_hear_id'] = $row->how_you_hear_id;
                    $insert['how_you_hear_other'] = $row->how_you_hear_other;
                    $insert['currently_employed'] = $row->currently_employed;
                    $insert['available_joining_id'] = $row->available_joining_id;
                    // resume
                    $insert['date_of_birth'] = $row->date_of_birth;
                    $insert['marital_status'] = $row->marital_status;
                    $insert['blood_group'] = $row->blood_group;
                    $insert['pan_number'] = $row->pan_number;
                    // pan_card
                    $insert['aadhar_number'] = $row->aadhar_number;
                    // aadhar_card
                    // photo
                    $insert['valid_passport'] = $row->valid_passport;
                    $insert['passport_number'] = $row->passport_number;
                    $insert['passport_validity'] = $row->passport_validity;
                    $insert['business_visa_usa'] = $row->business_visa_usa;
                    $insert['visa_validity'] = $row->visa_validity;
                    $insert['country_id'] = ($row->address_same == 'no') ? $row->country_id : $row->pre_country_id;
                    $insert['state_id'] = ($row->address_same == 'no') ? $row->state_id : $row->pre_state_id;
                    $insert['district_id'] = ($row->address_same == 'no') ? $row->district_id : $row->pre_district_id;
                    $insert['pincode'] = ($row->address_same == 'no') ? $row->pincode : $row->pre_pincode;
                    $insert['address'] = ($row->address_same == 'no') ? $row->address : $row->pre_address;
                    $insert['address_same'] = $row->address_same;
                    $insert['pre_country_id'] = $row->pre_country_id;
                    $insert['pre_state_id'] = $row->pre_state_id;
                    $insert['pre_district_id'] = $row->pre_district_id;
                    $insert['pre_pincode'] = $row->pre_pincode;
                    $insert['pre_address'] = $row->pre_address;
                    $insert['employment_history'] = $row->employment_history;
                    // salary_slip_last3
                    // salary_breakup
                    // candidate_assessment_sheet
                    // candidate_assessment_sheet_signed
                    $insert['offerletter_release_date'] = $row->offerletter_release_date;
                    // offerletter
                    // offerletter_accepted
                    // pre_address_proof
                    // resignation_proof
                    // resignation_accept_proof
                    // uan_proof
                    // power_backup_proof
                    // internet_bill
                    $insert['internet_speed'] = $row->internet_speed;
                    $insert['medical_exam_date'] = $row->medical_exam_date;
                    $insert['identification_mark'] = $row->identification_mark;
                    $insert['major_illness_id'] = $row->major_illness_id;
                    $insert['operation_or_accident'] = $row->operation_or_accident;
                    $insert['db_bp_ep_finding'] = $row->db_bp_ep_finding;
                    $insert['db_bp_family'] = $row->db_bp_family;
                    $insert['musculoskeletal_problem'] = $row->musculoskeletal_problem;
                    $insert['expecting_mother'] = $row->expecting_mother;
                    $insert['expected_delivery_date'] = $row->expected_delivery_date;
                    // fitness_certificate
                    // fitness_certificate_self
                    $insert['basic'] = $row->basic;
                    $insert['vda'] = $row->vda;
                    $insert['advanced_retension_bonus'] = $row->advanced_retension_bonus;
                    $insert['hra'] = $row->hra;
                    $insert['nightshift_allowance'] = $row->nightshift_allowance;
                    $insert['wfh_allowance'] = $row->wfh_allowance;
                    $insert['flexi_allowance'] = $row->flexi_allowance;
                    $insert['variable_performance_bonus'] = $row->variable_performance_bonus;
                    $insert['payment_frequency_vpb'] = $row->payment_frequency_vpb;
                    $insert['employer_pf'] = $row->employer_pf;
                    $insert['employer_esi'] = $row->employer_esi;
                    $insert['employer_gratuity'] = $row->employer_gratuity;
                    $insert['annual_retention_bonus'] = $row->annual_retention_bonus;
                    $insert['months_eligible_bonus'] = $row->months_eligible_bonus;
                    $insert['retention_bonus_duedate'] = $row->retention_bonus_duedate;
                    $insert['statutory_bonus'] = $row->statutory_bonus;
                    $insert['medical_insurance'] = $row->medical_insurance;
                    $insert['designation'] = $row->designation;
                    $insert['reporting_manager'] = $row->reporting_manager;
                    $insert['band'] = $row->band;
                    $insert['sub_band'] = $row->sub_band;
                    $insert['work_location_id'] = $row->work_location_id;
                    $insert['resume_source_id'] = $row->resume_source_id;
                    $insert['referral_amount'] = $row->referral_amount;
                    $insert['referral_amount_duedate'] = $row->referral_amount_duedate;
                    $insert['referrance_received'] = $row->referrance_received;
                    $insert['gross'] = $row->gross;
                    $insert['monthly_ctc'] = $row->monthly_ctc;
                    $insert['annual_ctc'] = $row->annual_ctc;
                    $insert['wfh_or_office'] = $row->wfh_or_office;
                    $insert['department'] = $row->department;
                    $insert['sub_department'] = $row->sub_department;
                    $insert['shift'] = $row->shift;
                    $insert['vacancy_type'] = $row->vacancy_type;
                    $insert['replacement_for'] = $row->replacement_for;
                    $insert['employee_code'] = $row->employee_code;
                    $insert['joining_date'] = $row->joining_date;
                    $insert['official_email_id'] = $row->official_email_id;
                    $insert['replacement_for'] = $row->replacement_for;
                    // appointment_letter
                    // accepted_appointment_letter
                    // releaving_letter_previous
                    // joining_report
                    // form_2
                    // form_q
                    // form_11
                    // form_1
                    // esi_form
                    $insert['probation_duration'] = $row->probation_duration;
                    $insert['first_increment_duedate'] = $row->first_increment_duedate;
                    // reference_check
                    $insert['confirmation_duedate'] = $row->confirmation_duedate;
                    $insert['blacklist_reason'] = $row->blacklist_reason;
                    $insert['hr'] = $row->hr;
                    $insert['recruiter'] = $row->recruiter;
                    $insert['stage_2_submit'] = $row->stage_2_submit;
                    $insert['stage_4_submit	'] = $row->stage_4_submit;
                    $insert['salary_other_submit'] = $row->salary_other_submit;
                    // accepted_offer_letter
                    $insert['no_of_position'] = $row->no_of_position;
                    // relieving_letter
                    $insert['current_ctc'] = $row->current_ctc;
                    $insert['remarks'] = $row->remarks;
                    $insert['name_of_position'] = $row->name_of_position;
                    // mrf_file
                    // authorization_letter
                    $insert['father_name'] = $row->father_name;
                    $insert['hiring_manager'] = $row->hiring_manager;
                    $insert['interviewer_review'] = $row->interviewer_review;
                    $insert['candidate_cas_result'] = $row->candidate_cas_result;
                    $insert['relieving_date'] = $row->relieving_date;

                    $insert['approved_date'] = $row->approved_date;

                    // check department exist
                    if (!empty($row->department)) {
                        $dep_exist = $this->common_model->selectOne('departments', array('name' => $row->department), '*');
                        if (empty($dep_exist)) {
                            $new_dep_id = $this->common_model->insert(array('name' => $row->department), 'departments');
                            if ($new_dep_id) {
                                $insert['department_id'] = $new_dep_id;
                            }
                        } else {
                            $insert['department_id'] = $dep_exist['department_id'];
                        }
                    }

                    // check designation exist
                    if (!empty($row->designation)) {
                        $desig_exist = $this->common_model->selectOne('designations', array('name' => $row->designation), '*');
                        if (empty($desig_exist)) {
                            $new_desig_id = $this->common_model->insert(array('name' => $row->designation), 'designations');
                            if ($new_desig_id) {
                                $insert['designation_id'] = $new_desig_id;
                            }
                        } else {
                            $insert['designation_id'] = $desig_exist['designation_id'];
                        }
                    }

                    // check sub department exist
                    if (!empty($row->sub_department)) {
                        $sub_dep_exist = $this->common_model->selectOne('subdepartment', array('name' => $row->sub_department), '*');
                        if (empty($sub_dep_exist)) {
                            $new_sub_dep_id = $this->common_model->insert(array('name' => $row->sub_department), 'subdepartment');
                            if ($new_sub_dep_id) {
                                $insert['sub_department_id'] = $new_sub_dep_id;
                            }
                        } else {
                            $insert['sub_department_id'] = $sub_dep_exist['id'];
                        }
                    }

                    $id = $this->common_model->insert($insert, 'employee');
                    if ($id) {

                        // send password
                        $password = generateRandomString(6);
                        // sendPassword($password, $insert['email'], $insert['name']);
                        // sendPassword($password, 'aneesh@ksofttechnologies.com', 'Aneesh');
                        // $resp = $this->common_model->update(array('password' => md5($password)), array('employee_id' => $id), 'employee');

                        $user_docs = 'assets/uploads/user_docs/candidates_docs/' . $id;

                        $oldmask = umask(0);
                        if (!is_dir($user_docs)) {
                            mkdir($user_docs, 0777, true);
                        }
                        umask($oldmask);
                        if (is_writable(($user_docs))) {
                            if (!file_exists($user_docs . '/index.html')) {
                                file_put_contents($user_docs . '/index.html', '');
                            }
                            $fileFields = [
                                'resume', 'pan_card', 'aadhar_card', 'photo', 'salary_slip_last3', 'salary_breakup',
                                'pre_address_proof', 'resignation_proof', 'resignation_accept_proof', 'uan_proof', 'power_backup_proof', 'internet_bill', 'fitness_certificate', 'fitness_certificate_self', 'releaving_letter_previous', 'joining_report', 'form_2', 'form_q', 'form_11', 'form_1', 'esi_form', 'authorization_letter'
                            ];
                            $uploadedFiles = [];
                            foreach ($fileFields as $fileField) {
                                if ($row->$fileField) {
                                    $files = explode(',', $row->$fileField);
                                    foreach ($files as $key => $file) {
                                        $upload = file_put_contents($user_docs . '/' . basename($file), file_get_contents($file));
                                        if ($upload) {
                                            $uploadedFiles[$key] = basename($file);
                                        }
                                    }
                                    if (!empty($uploadedFiles)) {
                                        $resp = $this->common_model->update(array($fileField => implode(',', $uploadedFiles)), array('employee_id' => $id), 'employee');
                                    }
                                }
                            }
                        }
                        $verified_docs = 'assets/uploads/verified_docs/' . $id;
                        $oldmask = umask(0);
                        if (!is_dir($verified_docs)) {
                            mkdir($verified_docs, 0777, true);
                        }
                        umask($oldmask);
                        if (is_writable(($verified_docs))) {
                            if (!file_exists($verified_docs . '/index.html')) {
                                file_put_contents($verified_docs . '/index.html', '');
                            }
                            $fileFields = ['candidate_assessment_sheet', 'candidate_assessment_sheet_signed', 'offerletter', 'offerletter_accepted', 'appointment_letter', 'accepted_appointment_letter', 'reference_check', 'accepted_offer_letter', 'relieving_letter', 'mrf_file'];
                            $uploadedFiles = [];
                            // pr($fileFields);exit;
                            foreach ($fileFields as $fileField) {
                                if ($row->$fileField) {
                                    $files = explode(',', $row->$fileField);
                                    foreach ($files as $key => $file) {
                                        $upload = file_put_contents($verified_docs . '/' . basename($file), file_get_contents($file));
                                        if ($upload) {
                                            $uploadedFiles[$key] = basename($file);
                                        }
                                    }
                                    if (!empty($uploadedFiles)) {
                                        $resp = $this->common_model->update(array($fileField => implode(',', $uploadedFiles)), array('employee_id' => $id), 'employee');
                                    }
                                }
                            }
                        }

                        // education details
                        if (!empty($row->education_details)) {
                            foreach ($row->education_details as $key => $item) {
                                $edu['candidate_id'] = $id;
                                $edu['course_id'] = $item->course_id;
                                $edu['specialization'] = $item->specialization;
                                $edu['institute_name'] = $item->institute_name;
                                $edu['country_id'] = $item->country_id;
                                $edu['state_id'] = $item->state_id;
                                $edu['nearest_city'] = $item->nearest_city;
                                $edu['percentage_mark'] = $item->percentage_mark;
                                $edu['completion_year'] = $item->completion_year;
                                // mark_card

                                $edu_id = $this->common_model->insert($edu, 'education_history');
                                if (!empty($edu_id)) {

                                    if (!empty($item->mark_card)) {
                                        if (is_writable(($user_docs))) {
                                            $files = explode(',', $item->mark_card);
                                            foreach ($files as $key => $file) {
                                                // echo '<pre>' . $file;
                                                // echo '<pre>' . $user_docs . '/' . basename($file);
                                                // echo '<pre>up::::' . $upload;
                                                $upload = file_put_contents($user_docs . '/' . basename($file), file_get_contents($file));
                                                if ($upload) {
                                                    $uploadedFiles[$key] = basename($file);
                                                }
                                            }
                                            if (!empty($uploadedFiles)) {
                                                $resp = $this->common_model->update(array('mark_card' => implode(',', $uploadedFiles)), array('education_id' => $edu_id), 'education_history');
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        // certification_course
                        if (!empty($row->certification_course)) {
                            foreach ($row->certification_course as $key => $item) {
                                $cert[$key]['candidate_id'] = $id;
                                $cert[$key]['certification'] = $item->certification;
                                $cert[$key]['specialization'] = $item->specialization;
                                $cert[$key]['institute_name'] = $item->institute_name;
                                $cert[$key]['start_date'] = $item->start_date;
                                $cert[$key]['end_date'] = $item->end_date;
                                $cert[$key]['valid_upto'] = $item->valid_upto;
                            }
                            $this->db->insert_batch('certification_history', $cert);
                        }

                        // employment_histories
                        if (!empty($row->employment_histories)) {
                            foreach ($row->employment_histories as $key => $item) {
                                $emp[$key]['candidate_id'] = $id;
                                $emp[$key]['company_name'] = $item->company_name;
                                $emp[$key]['country_id'] = $item->country_id;
                                $emp[$key]['state_id'] = $item->state_id;
                                $emp[$key]['nearest_city'] = $item->nearest_city;
                                $emp[$key]['start_date'] = $item->start_date;
                                $emp[$key]['experience_in_month'] = $item->experience_in_month;
                                $emp[$key]['is_this_relevant_exp'] = $item->is_this_relevant_exp;
                                $emp[$key]['last_designation'] = $item->last_designation;
                                $emp[$key]['responsibilities'] = $item->responsibilities;
                                $emp[$key]['annual_ctc'] = $item->annual_ctc;
                                $emp[$key]['reason_jobchance'] = $item->reason_jobchance;
                                $emp[$key]['salary_revision_letter'] = $item->salary_revision_letter;
                            }
                            $this->db->insert_batch('employment_history', $emp);
                        }

                        // ref_experienced
                        if (!empty($row->ref_experienced)) {
                            foreach ($row->ref_experienced as $key => $item) {
                                $ref_exp[$key]['candidate_id'] = $id;
                                $ref_exp[$key]['company_name'] = $item->company_name;
                                $ref_exp[$key]['reporting_manager_name'] = $item->reporting_manager_name;
                                $ref_exp[$key]['ref_designation	'] = $item->ref_designation;
                                $ref_exp[$key]['official_email_rm'] = $item->official_email_rm;
                                $ref_exp[$key]['mobile_number_rm'] = $item->mobile_number_rm;
                                $ref_exp[$key]['country_code_rm'] = $item->country_code_rm;
                                $ref_exp[$key]['hr_name'] = $item->hr_name;
                                $ref_exp[$key]['official_email_hr'] = $item->official_email_hr;
                                $ref_exp[$key]['mobile_number_hr'] = $item->mobile_number_hr;
                                $ref_exp[$key]['country_code_hr'] = $item->country_code_hr;
                            }
                            $this->db->insert_batch('reference_experienced', $ref_exp);
                        }

                        // ref_fresher
                        if (!empty($row->ref_fresher)) {
                            foreach ($row->ref_fresher as $key => $item) {
                                $ref_fresher[$key]['candidate_id'] = $id;
                                $ref_fresher[$key]['college_name'] = $item->college_name;
                                $ref_fresher[$key]['name_of_hod'] = $item->name_of_hod;
                                $ref_fresher[$key]['email_id_hod	'] = $item->email_id_hod;
                                $ref_fresher[$key]['mobile_number_hod'] = $item->mobile_number_hod;
                                $ref_fresher[$key]['country_code_hod'] = $item->country_code_hod;
                                $ref_fresher[$key]['project_cord_name'] = $item->project_cord_name;
                                $ref_fresher[$key]['email_id_cord'] = $item->email_id_cord;
                                $ref_fresher[$key]['mobile_number_cord'] = $item->mobile_number_cord;
                                $ref_fresher[$key]['country_code_cord'] = $item->country_code_cord;
                            }
                            $this->db->insert_batch('reference_fresher', $ref_fresher);
                        }

                        // family_details
                        if (!empty($row->family_details)) {
                            foreach ($row->family_details as $key => $item) {
                                $family[$key]['candidate_id'] = $id;
                                $family[$key]['fmid'] = $item->fmid;
                                $family[$key]['name'] = $item->name;
                                $family[$key]['relationship	'] = $item->relationship;
                                $family[$key]['living_status'] = $item->living_status;
                                $family[$key]['gender'] = $item->gender;
                                $family[$key]['date_of_birth'] = $item->date_of_birth;
                                $family[$key]['occupation_id'] = $item->occupation_id;
                                $family[$key]['mobile_number'] = $item->mobile_number;
                                $family[$key]['emergency_contact'] = $item->emergency_contact;
                            }
                            $this->db->insert_batch('family_details', $family);
                        }


                        $trackerInsert['first_name']                     = $row->standardised_name;
                        $trackerInsert['email']                = $row->official_email_id;
                        $trackerInsert['department_id'] = $row->department;
                        $trackerInsert['designation_id'] = $row->designation;
                        $trackerInsert['phone '] = $row->mobile_number;
                        $trackerInsert['joining_date'] = $row->joining_date;

                        $curl = curl_init();

                        curl_setopt_array($curl, [
                            CURLOPT_URL => "https://trackerbackend.ksofttechnologies.com/add-employee",
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => json_encode($trackerInsert)
                        ]);

                        $curlResp = curl_exec($curl);
                        $curlErr = curl_error($curl);

                        curl_close($curl);

                        $inserted++;
                    } else {
                        $failed++;
                    }
                } else {
                    $skipped++;
                }
                // pr($exist);
                // exit;
            }
            if ($inserted > 0) {
                $response = array('status' => true, 'total' => $total, 'inserted' => $inserted, 'skipped' => $skipped, 'failed' => $failed);
            } else {
                $response = array('status' => false, 'total' => $total, 'inserted' => $inserted, 'skipped' => $skipped, 'failed' => $failed);
            }
            // pr($insert);
            // exit;
        } else {
            $response = array('status' => false, 'total' => $total, 'inserted' => $inserted, 'skipped' => $skipped, 'failed' => $failed);
        }
        echo json_encode($response);
        exit;
    }
}
