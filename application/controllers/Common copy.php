<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Common extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Leave_model', 'leave');
        $this->load->model('employee_model');
    }

    public function signin()
    {

        if (check_user_login()) {
            redirect('dashboard', 'refresh');
        }

        $page_data['page_type']    = 'common';
        $page_data['page_name']    = 'signin';
        $page_data['page_title']   = 'Sign In';
        $this->load->view('theme/common/main', $page_data);
    }

    public function signinProcess()
    {

        $this->form_validation->set_rules('employee_code', 'Employee Code', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run()) {

            $data['code'] = $this->input->post('employee_code');
            $data['password'] = $this->input->post('password');

            $exist = $this->common_model->selectOne('employee', array('code' => trim($data['code'])), 'employee_id,code,password,designation_id,department_id,status,name,role_id,special_role,email');

            if (!empty($exist)) {

                if ($exist['password'] == md5($data['password'])) {
                    $is_rm = $this->common_model->selectAll('employee', array("reporting_manager_id" => $exist['employee_id']), "employee_id");
                    if ($exist['status'] !== 'inactive' && $exist['status'] !== 'resigned') {

                        $this->common_model->updateLastLogin($exist['employee_id']);
                        //$type = $this->common_model->selectOne('user_roles',array('employee_id'=>$exist['employee_id']),'GROUP_CONCAT(role_id) as roles');

                        $session = array(
                            'employee_id' => $exist['employee_id'],
                            'code' => $exist['code'],
                            'email' => $exist['email'],
                            'designation_id' => $exist['designation_id'],
                            'department_id' => $exist['department_id'],
                            'type' => $exist['role_id'],
                            'special_role' => $exist['special_role'],
                            'name' => $exist['name'],
                            'is_rm' => empty($is_rm) ? 0 : 1,
                            'is_user_login' => true
                        );

                        $this->session->set_userdata($session);
                        $this->session->set_flashdata('success', 'Welcome back ' . $exist['name'] . '!');

                        $message = array('message' => 'Successfully Signed In.', 'status' => '1');
                        echo json_encode($message);
                    } else {
                        $message = array('message' => 'Please contact administrator!', 'status' => '0');
                        echo json_encode($message);
                    }
                } else {
                    $message = array('message' => 'Invalid password!', 'status' => '0');
                    echo json_encode($message);
                }
            } else {
                $message = array('message' => 'Invalid employee code!', 'status' => '0');
                echo json_encode($message);
            }
        } else {
            $message = array('message' => 'Validation errors!', 'status' => '0');
            echo json_encode($message);
        }
    }

    public function notfound()
    {

        $page_data['page_type']    = 'common';
        $page_data['page_name']    = 'notfound';
        $page_data['page_title']   = 'Page not found';
        $this->load->view('theme/common/main', $page_data);
    }

    public function logout()
    {

        $array_val = array('employee_id', 'code', 'designation_id', 'department_id', 'is_user_login');
        $this->session->unset_userdata($array_val);
        $this->session->sess_destroy();
        redirect('signin', 'refresh');
    }

    //for cron update leave; running order 1
    public function api_calc_leave()
    {

        $list = $this->common_model->selectAll('employee', array('status != ' => 'resigned', 'status != ' => 'noticeperiod', 'probation_status' => '0'), 'employee_id');
        //pr($list);
        if (!empty($list)) {

            foreach ($list as $row) {
                $resp = $this->leave->updateLeaveCron($row['employee_id']);
                pr($resp);
            }
        }
    }

    //to reduce CO count on expiry. need to run every day
    public function api_calc_co()
    {

        $curDate = date('Y-m-d');
        //$curDate = '2024-05-20';
        $list = $this->common_model->selectAll('hr_leave_allocation_history', array('expiry_date' => $curDate,'expire_status' => '0'), '*');

        if (!empty($list)) {

            foreach ($list as $row) {

                $year = date('Y', strtotime($curDate));
                $month = date('m', strtotime($curDate));
                $newStartDate = date('Y-m-01', strtotime("$year-$month-01"));
                $newEndDate = date('Y-m-t', strtotime("$year-$month-01"));

                $aperyear = "select * from employee_leave_details where employee_id = ? and start_date = ? and end_date = ? order by leave_details_id DESC limit 1";

                $res = $this->db->query($aperyear, array($row['employee_id'],$newStartDate,$newEndDate))->row_array();
                if(!empty($res)) {
                    if($res['co'] > 0) {
                        $param = array();
                        $param['co'] = ($res['co'] - 1);
                        $resp = $this->common_model->update($param, array('leave_details_id' => $res['leave_details_id']), 'employee_leave_details');
                        if (!empty($resp)) {
                            $param1 = array();
                            $param1['expire_status'] = '1';
                            $resp1 = $this->common_model->update($param1, array('id' => $row['id']), 'hr_leave_allocation_history');
                            if(!empty($resp1)) {
                                pr($row);
                                pr($res);
                            }
                        }
                    }
                }

            }
        }
    }

    //auto send reminder emails. need to run every day
    public function api_leave_reminder()
    {
        // get all employees(not resigned & not in notice period)
        $select = 'e.*';
        $where['1'] = "1";
        $where['e.status!='] = "resigned";
        $where['e.status!='] = "noticeperiod";
        $join = "";
        $employees = $this->employee_model->get_employees($select, $where, array('join' => $join))->result_array();

        if (!empty($employees)) {

            $startDate = date('Y-m-d', strtotime('first day of this month'));
            $endDate = date('Y-m-d');
            $dates = getDatesBetweenTwoDates($startDate, $endDate);

            // remove holidays
            $holidays = $this->db->get_where('holidays', array('holiday_date >=' => $startDate, 'holiday_date <=' => $endDate))->result_array();

            if (!empty($holidays)) {
                $holiday_dates = array_column($holidays, "holiday_date");
                $dates = array_filter($dates, function ($date) use ($holiday_dates) {
                    return !in_array($date, $holiday_dates);
                });
            }

            // remove saturdays & sundays
            $dates = array_filter($dates, function ($date) {
                return !in_array(date("D", strtotime($date)), ['Sat', 'Sun']);
            });

            $total_emails_sent = 0;
            foreach ($employees as $key => $employee) {
                $absentDates = [];
                foreach ($dates as $date) {

                    // check attendance(givent date)
                    $attendance_exist = $this->common_model->selectOne('attendance', array('employee_id' => $employee['employee_id'], 'attendance_date' => $date), 'id');
                    //pr($attendance_exist);
                    if (empty($attendance_exist)) {
                        // check leave(givent date)
                        $leave_exist = $this->common_model->selectOne('leave_application', array('employee_id' => $employee['employee_id'], 'leave_date' => $date), 'leave_application_id');

                        if (empty($leave_exist)) {
                            array_push($absentDates, $date);
                        }
                    }
                }
                if (!empty($absentDates)) {
                    //pr($absentDates);
                    //pr($employee['email']);
                    $resp = send_absent_mail($employee['email'], $employee['name'], $absentDates);
                    if ($resp) {
                        $total_emails_sent++;
                    }
                }
            }
            if ($total_emails_sent > 0) {
                $response = array('status' => '1', 'msg' => $total_emails_sent . ' emails sent');
            } else {
                $response = array('status' => '1', 'msg' => 'No absentees found!');
            }
        } else {
            $response = array('status' => '0', 'msg' => 'No employees found!');
        }

        pr($response);
        exit;
    }

    //for cron to import employees from recruit
    public function api_user_import()
    {

        $limit = 10;
        $select = 'e.employee_id,e.code,e.name,e.approved_date';
        $where['1'] = "1";
        $order_by = 'e.employee_id DESC';
        $last_row = $this->employee_model->get_employees($select, $where, array('order_by' => $order_by))->row_array();
        $filters = array('approved_date' => $last_row['approved_date'] ?? '', 'limit' => $limit ?? '');
        $json = json_encode($filters ?? []);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "http://52.76.137.192/enventuremrf/api/get_candidates",
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

        $total = count($output);
        $skipped = 0;
        $inserted = 0;
        $failed = 0;
        if (!empty($output)) {

            foreach ($output as $key => $row) {

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

    //auto leave approvel; running order 2
    public function api_auto_approve_leave()
    {

        //get last month full unapproved leaves
        $startDate = date('Y-m-d', strtotime('first day of last month'));
        $endDate = date('Y-m-d', strtotime('last day of last month'));

        //fir test
        // $startDate = '2024-05-01';
        // $endDate = '2024-05-31';

        $qry = "select distinct(la.group_id),la.employee_id,la.leave_type_id,lt.title,lt.type from leave_application as la left join leave_types as lt on (lt.leave_type_id=la.leave_type_id) where la.leave_date >= ? and la.leave_date <= ? and la.status = ?";
        $res = $this->db->query($qry, array($startDate,$endDate,'pending'))->result_array();
        //pr($res);

        if(!empty($res)) {
            foreach($res as $row) {

                $availability = $this->leave->checkLeaveAvailability($row['employee_id']);
                //pr($availability);

                if(!empty($availability)) {

                    $leave = $this->leave->leave_by_id($row['group_id']);
                    //pr($leave);
                    $active_leave_count = (!empty($leave['active_leaves'])) ? count($leave['active_leaves']) : 0;

                    if(!empty($active_leave_count)) {

                        if($leave['leave_type_id'] == 1) {
                            if($active_leave_count > $availability['pl_balance']) {
                                $response = array('status' => 0, 'msg' => 'Cannot approve! '.$availability['pl_balance'].' day available!');
                                pr($response);
                                continue;
                            }
                        } elseif ($leave['leave_type_id'] == 3) {
                            if($active_leave_count > $availability['sl_balance']) {
                                $response = array('status' => 0, 'msg' => 'Cannot approve! '.$availability['sl_balance'].' day available!');
                                pr($response);
                                continue;
                            }
                        } elseif ($leave['leave_type_id'] == 4) {
                            if($active_leave_count > $availability['co']) {
                                $response = array('status' => 0, 'msg' => 'Cannot approve! '.$availability['co'].' day available!');
                                pr($response);
                                continue;
                            }
                        }

                        //to recalculate leave count after approveal
                        $update_param = array();
                        if($leave['leave_type_id'] == 1) {

                            $update_param['pl_used'] = ($availability['pl_used'] + count($leave['active_leaves']));
                            $update_param['pl_balance'] = ($availability['pl_balance'] - count($leave['active_leaves']));

                        } elseif ($leave['leave_type_id'] == 3) {

                            $update_param['sl_used'] = ($availability['sl_used'] + count($leave['active_leaves']));
                            $update_param['sl_balance'] = ($availability['sl_balance'] - count($leave['active_leaves']));

                        } elseif ($leave['leave_type_id'] == 4) {

                            $update_param['co'] = ($availability['co'] + count($leave['active_leaves']));

                        }

                        //get leave approval history
                        $qry1 = "select * from leave_application_status as las where las.group_id = ? and las.status = ?";
                        $res1 = $this->db->query($qry1, array($row['group_id'],'pending'))->result_array();
                        if(!empty($res1)) {
                            foreach($res1 as $row1) {
                                //direct leave approval
                                if($row['type'] == 'direct') {
                                    //approved the head status and overall status then
                                    $param = array();
                                    $param['status'] = 'approved';
                                    $param['status_changed'] = date('Y-m-d');
                                    $param['remarks'] = 'System auto approved';
                                    $param['updated_at'] = date('Y-m-d H:i:s');
                                    if(!empty($param)) {
                                        //approve status entry
                                        $resp = $this->common_model->update($param, array('leave_application_status_id' => $row1['leave_application_status_id']), 'leave_application_status');
                                        if($resp) {
                                            //approve entire leave
                                            $param = array();
                                            $param['status'] = 'approved';
                                            $param['status_changed'] = date('Y-m-d');
                                            $param['updated_at'] = date('Y-m-d H:i:s');
                                            $resp1 = $this->common_model->update($param, array('group_id' => $row['group_id']), 'leave_application');
                                            if($resp1) {
                                                pr($row);
                                                //update leave avilable tables once leave approved
                                                if(!empty($update_param)) {
                                                    $this->common_model->update($update_param, array('leave_details_id' => $availability['leave_details_id'],'employee_id' => $availability['employee_id']), 'employee_leave_details');
                                                }
                                            }
                                        }
                                    }

                                } else {

                                    $reporting_heads = $this->leave->rm_assigned_check($row['employee_id']);
                                    //check its first level approval or last level
                                    if($row1['position'] == 1) {
                                        //approve first level then add second level and approved the same and then approve full leave
                                        $param = array();
                                        $param['status'] = 'approved';
                                        $param['status_changed'] = date('Y-m-d');
                                        $param['remarks'] = 'System auto approved';
                                        $param['updated_at'] = date('Y-m-d H:i:s');
                                        if(!empty($param)) {
                                            $resp = $this->common_model->update($param, array('leave_application_status_id' => $row1['leave_application_status_id']), 'leave_application_status');
                                            if($resp) {
                                                //insert second head entry with direct approval status
                                                $param = array();
                                                $param['group_id'] = $row['group_id'];
                                                $param['status'] = 'approved';
                                                $param['status_changed'] = date('Y-m-d');
                                                $param['remarks'] = 'System auto approved';
                                                $param['head_id'] = $reporting_heads['head_2'];
                                                $param['position'] = 2;
                                                $param['created_at'] = date('Y-m-d H:i:s');
                                                $param['updated_at'] = date('Y-m-d H:i:s');
                                                if(!empty($param)) {
                                                    $resp1 = $this->common_model->insert($param, 'leave_application_status');
                                                    if(!empty($resp1)) {
                                                        //approve entire leave
                                                        $param = array();
                                                        $param['status'] = 'approved';
                                                        $param['status_changed'] = date('Y-m-d');
                                                        $param['updated_at'] = date('Y-m-d H:i:s');
                                                        $resp2 = $this->common_model->update($param, array('group_id' => $row['group_id']), 'leave_application');
                                                        if($resp2) {
                                                            pr($row);
                                                            //update leave avilable tables once leave approved
                                                            if(!empty($update_param)) {
                                                                $this->common_model->update($update_param, array('leave_details_id' => $availability['leave_details_id'],'employee_id' => $availability['employee_id']), 'employee_leave_details');
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                    } else {
                                        //approve second level and approve full leave
                                        $param = array();
                                        $param['status'] = 'approved';
                                        $param['status_changed'] = date('Y-m-d');
                                        $param['remarks'] = 'System auto approved';
                                        $param['updated_at'] = date('Y-m-d H:i:s');
                                        if(!empty($param)) {
                                            $resp = $this->common_model->update($param, array('leave_application_status_id' => $row1['leave_application_status_id']), 'leave_application_status');
                                            if($resp) {
                                                //approve entire leave
                                                $param = array();
                                                $param['status'] = 'approved';
                                                $param['status_changed'] = date('Y-m-d');
                                                $param['updated_at'] = date('Y-m-d H:i:s');
                                                $resp2 = $this->common_model->update($param, array('group_id' => $row['group_id']), 'leave_application');
                                                if($resp2) {
                                                    pr($row);
                                                    //update leave avilable tables once leave approved
                                                    if(!empty($update_param)) {
                                                        $this->common_model->update($update_param, array('leave_details_id' => $availability['leave_details_id'],'employee_id' => $availability['employee_id']), 'employee_leave_details');
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        echo 'No active leave to approve in group id: '.$row['group_id'];
                    }
                } else {
                    echo 'No month based leave available for employee: '.$row['employee_id'];
                }
            }
        } else {
            echo 'No pending leave to auto approve!';
        }
    }

    //auto create leave if not applied; running order 3
    public function api_leave_regularization()
    {

        $startDate = date('Y-m-d', strtotime('first day of last month'));
        $endDate = date('Y-m-d', strtotime('last day of last month'));

        //for test
        $startDate = '2024-05-01';
        $endDate = '2024-05-31';

        // get all dates of the previous month
        $dates = getDatesBetweenTwoDates($startDate, $endDate);

        //remove holidays
        // $holidays = $this->db->get_where('holidays', array('holiday_date >=' => $startDate, 'holiday_date <=' => $endDate))->result_array();
        // if (!empty($holidays)) {
        //     $holiday_dates = array_column($holidays, "holiday_date");
        //     $dates = array_filter($dates, function ($date) use ($holiday_dates) {
        //         return !in_array($date, $holiday_dates);
        //     });
        // }

        // // remove saturdays & sundays
        // $dates = array_filter($dates, function ($date) {
        //     return !in_array(date("D", strtotime($date)), ['Sat', 'Sun']);
        // });

        if (!empty($dates)) {
            foreach($dates as $date) {
                echo '<br>Date: '.$date.'<br>';
                $qry = "SELECT employee.employee_id FROM employee
                LEFT JOIN attendance ON employee.employee_id = attendance.employee_id AND attendance.attendance_date = ?
                LEFT JOIN leave_application ON employee.employee_id = leave_application.employee_id AND leave_application.leave_date = ?
                WHERE attendance.employee_id IS NULL AND leave_application.employee_id IS NULL AND employee.status != 'resigned' and employee.status != 'noticeperiod'";
                $resp = $this->db->query($qry, array($date,$date))->result_array();
                //pr($resp);
                if(!empty($resp)) {
                    foreach($resp as $employee) {

                        //for testing
                        if($employee['employee_id'] == 4) {
                            //pr($employee);
                            $availability = $this->leave->checkLeaveAvailability($employee['employee_id']);
                            //pr($availability);
                            if(empty($availability)) {
                                echo 'No month based leave available for employee: '.$employee['employee_id'];
                                continue;
                            }

                            //apply pl, sl or lop based on the availability
                            //pr($availability);
                            if ($availability['pl_balance'] > 0) {
                                $leave_type_id = 1;
                            } elseif ($availability['sl_balance'] > 0) {
                                $leave_type_id = 3;
                            } else {
                                $leave_type_id = 10;
                            }

                            echo 'pl_balance: '.$availability['pl_balance'].' sl_balance: '.$availability['sl_balance'].'<br>';
                            echo 'LEAVE type:'.$leave_type_id.'<br>';
                            //for testing
                            //$leave_type_id = 3;

                            $get_leave_type  = $this->common_model->selectOne('leave_types', array('leave_type_id' => $leave_type_id), 'type');

                            if($get_leave_type['type'] == 'hierarchy') {
                                $reporting_heads = $this->leave->rm_assigned_check($employee['employee_id']);
                                if(empty($reporting_heads['head_1']) || empty($reporting_heads['head_2'])) {
                                    $response = array('status' => 0, 'msg' => 'Reporting managers not assigned.');
                                    pr($response);
                                    continue;
                                }
                            } else {
                                //foor direct  leaves special approval head is needed
                                $reporting_heads = $this->leave->get_special_head();
                                if(empty($reporting_heads['head_1'])) {
                                    $response = array('status' => 0, 'msg' => 'Special approval head not assigned.');
                                    pr($response);
                                    continue;
                                }
                            }

                            //pr($reporting_heads);

                            $eligibility = $this->leave->get_leave_days($employee['employee_id'], $leave_type_id, $date, $date);
                            pr($eligibility);
                            if(!empty($eligibility)) {
                                if($eligibility['status'] == 0) {
                                    $response = array('status' => 0, 'msg' => 'No eligibility to apply leave!');
                                    pr($response);
                                    continue;
                                }
                            }

                            if($eligibility['prevgroup_id'] != "") {
                                //comment this checking to avoid adding leave to existing group id if its sandwich
                                $group_id = $eligibility['prevgroup_id'];
                            } else {
                                $group_id = generate_uuid();
                            }

                            echo 'group_id: '.$group_id.'<br>';
                            //add leave to main table with approved status
                            $temp = 0;
                            foreach($eligibility['all_dates'] as $row) {

                                $check_exist = $this->common_model->selectOne('leave_application', array('employee_id' => $employee['employee_id'],'leave_date' => $row), 'leave_application_id');
                                if(empty($check_exist)) {
                                    $param = array(
                                        'employee_id' => $employee['employee_id'],
                                        'leave_type_id' => $leave_type_id,
                                        'group_id' => $group_id,
                                        'leave_date' => $row,
                                        'is_sandwich_applied' => $eligibility['is_sandwich_applied'],
                                        'remarks' => 'System added leave!',
                                        'status' => 'approved',
                                        'status_changed' => date('Y-m-d'),
                                        'created_by' => 1,
                                        'created_at' => date('Y-m-d H:i:s')
                                    );

                                    $leave_id = $this->common_model->insert($param, 'leave_application');
                                    echo '<br>'.$this->db->last_query().'<br>';
                                    if(!empty($leave_id)) {
                                        $temp = 1;
                                    }
                                }
                            }

                            if($temp == 1 && $group_id != "") {

                                if($get_leave_type['type'] == "hierarchy") {

                                    $param1 = array();
                                    $param1[0]['group_id'] = $group_id;
                                    $param1[0]['status'] = 'approved';
                                    $param1[0]['status_changed'] = date('Y-m-d');
                                    $param1[0]['head_id'] = $reporting_heads['head_1'];
                                    $param1[0]['position']   = '1';
                                    $param1[0]['remarks'] = 'System auto approved';
                                    $param1[0]['created_at'] = date('Y-m-d H:i:s');

                                    $param1[1]['group_id'] = $group_id;
                                    $param1[1]['status'] = 'approved';
                                    $param1[1]['status_changed'] = date('Y-m-d');
                                    $param1[1]['head_id'] = $reporting_heads['head_2'];
                                    $param1[1]['position']   = '2';
                                    $param1[1]['remarks'] = 'System auto approved';
                                    $param1[1]['created_at'] = date('Y-m-d H:i:s');

                                } else {

                                    $param1 = array();
                                    $param1[0]['group_id'] = $group_id;
                                    $param1[0]['status'] = 'approved';
                                    $param1[0]['status_changed'] = date('Y-m-d');
                                    $param1[0]['head_id'] = $reporting_heads['head_1'];
                                    $param1[0]['position']   = '1';
                                    $param1[0]['remarks'] = 'System auto approved';
                                    $param1[0]['created_at'] = date('Y-m-d H:i:s');

                                }

                                $getexistgrp  = $this->common_model->selectOne('leave_application_status', array('group_id' => $group_id), 'leave_application_status_id');
                                echo '<br>'.$this->db->last_query().'<br>';
                                echo 'check gid exist';
                                pr($getexistgrp);
                                if(!empty($getexistgrp)) {
                                    //update all group status to is_sandwhich. because when appending new set of sandwich leaves to normal leaves
                                    $updatesandwich = $this->common_model->update(array('is_sandwich_applied' => '1'), array('group_id' => $group_id), 'leave_application');
                                    echo '<br>'.$this->db->last_query().'<br>';
                                    $leave_application_status_id = $getexistgrp['leave_application_status_id'];
                                } else {
                                    //add base or top head id on creation. next head will add on approval
                                    $leave_application_status_id = $this->db->insert_batch('leave_application_status', $param1);
                                    echo '<br>'.$this->db->last_query().'<br>';
                                }

                                if(!empty($leave_application_status_id)) {
                                    //reduce leave status by 1
                                    $leave = $this->leave->leave_by_id($group_id);
                                    $update_param = array();
                                    if($leave_type_id == 1) {
                                        $update_param['pl_used'] = ($availability['pl_used'] + count($leave['active_leaves']));
                                        $update_param['pl_balance'] = ($availability['pl_balance'] - count($leave['active_leaves']));
                                    } elseif ($leave_type_id == 3) {
                                        $update_param['sl_used'] = ($availability['sl_used'] + count($leave['active_leaves']));
                                        $update_param['sl_balance'] = ($availability['sl_balance'] - count($leave['active_leaves']));
                                    }

                                    //reduce leave availability
                                    if(!empty($update_param)) {

                                        //update all leave status to approved
                                        // $lstatus = $this->common_model->update(array('status' => 'approved'), array('group_id' => $group_id), 'leave_application');
                                        // $lsststus = $this->common_model->update(array('status' => 'approved'), array('group_id' => $group_id), 'leave_application_status');

                                        // if($lstatus && $lsststus) {

                                        $ress = $this->common_model->update($update_param, array('leave_details_id' => $availability['leave_details_id'],'employee_id' => $availability['employee_id']), 'employee_leave_details');

                                        echo $this->db->last_query();

                                        if($ress) {
                                            echo 'Leve regularized employee: '.$availability['employee_id'].'<br>***************************<br>';
                                        }
                                        //}
                                    }
                                }
                            }
                        }//for testing
                    }
                } else {
                    echo 'No data to regularize!';
                }
            }
        }
    }

    // by Sudheesh
    public function forgot()
    {

        if (check_user_login()) {
            redirect('dashboard', 'refresh');
        }

        $page_data['page_type']    = 'common';
        $page_data['page_name']    = 'forgot';
        $page_data['page_title']   = 'Forgot';
        $this->load->view('theme/common/main', $page_data);
    }

    public function forgotProcess()
    {

        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        if($this->form_validation->run()) {
            $email = $this->input->post('email');

            $exist = $this->common_model->selectOne('employee', array('email' => trim($email)), 'employee_id,email,name');
            if(!empty($exist)) {

                $otp = rand(100000, 999999);
                $data_update = array('otp' => $otp);
                //$mobile= $exist['email'];
                $resp = $this->db->update('employee', $data_update, array('employee_id' => $exist['employee_id']));
                if($resp) {
                    $this->session->set_userdata('forgot', $exist['employee_id']);
                    $subject = "Verification";
                    $body = "This is your one time passowrd:" . $otp . " Do not share this with anyone";

                    //$resss=_sendMail($subject, $body, $exist['email'], $exist['name']);

                    $this->session->set_flashdata('success', 'OTP Sent!');
                    $response = array('message' => 'OTP Sent!','status' => '1','otp' => $otp);
                } else {
                    $response = array('message' => 'Something went wrong!<br>Try again!','status' => '0');
                }
            } else {
                $response = array('message' => 'Account not found!','status' => '0');
            }
        } else {
            $response = array('message' => 'Validation Errors!','status' => '0');
        }
        echo json_encode($response);
    }

    public function forgotVerify()
    {

        if(empty($this->session->userdata('forgot'))) {
            redirect('forgot', 'refresh');
        }
        $page_data['page_type']    = 'common';
        $page_data['page_name']    = 'forgotVerify';
        $page_data['page_title']   = 'Verify';
        $this->load->view('theme/common/main', $page_data);
    }

    public function forgotVerifyProcess()
    {

        $this->form_validation->set_rules('otp', 'OTP', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
        if($this->form_validation->run()) {

            $employee_id = $this->session->userdata('forgot');
            if(!empty($employee_id)) {

                $otp = $this->input->post('otp');
                $exist = $this->common_model->selectOne('employee', array('employee_id' => trim($employee_id)), 'otp');
                if(!empty($exist)) {

                    if($exist['otp'] == '') {
                        $response = array('message' => 'Something went wrong. Resend the otp again!','status' => '0');
                    } elseif($otp != $exist['otp']) {
                        $response = array('message' => 'Incorrect otp!','status' => '0');
                    } else {
                        $data_update = array('otp' => '');
                        $resp = $this->db->update('employee', $data_update, array('employee_id' => $employee_id));
                        $this->session->set_userdata('change', $employee_id);
                        if($resp) {
                            $this->session->set_flashdata('success', 'OTP Verified!');
                            $response = array('message' => 'OTP Verified!','status' => '1');
                        } else {
                            $response = array('message' => 'Something went wrong!<br>Try again!','status' => '0');
                        }
                    }
                } else {
                    $response = array('message' => 'Account not found!','status' => '0');
                }
            } else {
                $response = array('message' => 'Something went wrong!','status' => '0');
            }
        } else {
            $response = array('message' => validation_errors(),'status' => '0');
        }
        echo json_encode($response);
    }

    public function resend_forgot_otp_process()
    {

        $employee_id = $this->session->userdata('forgot');
        if($employee_id) {

            $exist = $this->common_model->selectOne('employee', array('employee_id' => trim($employee_id)), 'employee_id,email,name');
            $email = $exist['email'];
            $otp = rand(100000, 999999);
            $data_update = array('otp' => $otp);
            $resp =  $this->db->update('employee', $data_update, array('employee_id' => $employee_id));
            if($resp) {
                $subject = "Verification";
                $body = "This is your one time passowrd:" . $otp . " Do not share this with anyone";

                $resss = _sendMail($subject, $body, $exist['email'], $exist['name']);
                $response = array('message' => 'OTP Sent!','status' => '1','otp' => $otp);
            } else {
                $response = array('message' => 'Something went wrong!<br>Try again!','status' => '0');
            }

        } else {
            $response = array('message' => 'Something went wrong!','status' => '0');
        }
        echo json_encode($response);
    }

    public function change()
    {

        if(empty($this->session->userdata('change'))) {
            redirect('forgot', 'refresh');
        }
        $page_data['page_type']    = 'common';
        $page_data['page_name']    = 'change';
        $page_data['page_title']   = 'Change Password';
        $this->load->view('theme/common/main', $page_data);
    }

    public function changeProcess()
    {
        $this->form_validation->set_rules('new_password', 'Password', 'trim|required');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[new_password]');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
        if($this->form_validation->run()) {

            $employee_id = $this->session->userdata('change');
            if(!empty($employee_id)) {

                $password = $this->input->post('confirm_password');
                $data_update['password'] = md5($password);
                $exist = $this->common_model->selectOne('employee', array('employee_id' => trim($employee_id)), 'employee_id,email,name');
                //$exist = $this->db->select('*')->from('partner as p')->where('p.company_id', trim($company_id))->get()->row_array();
                if(!empty($exist)) {

                    $resp = $this->db->update('employee', $data_update, array('employee_id' => $employee_id));
                    if($resp) {
                        $this->session->unset_userdata('employee_id');
                        $this->session->unset_userdata('otp');
                        $this->session->sess_destroy();
                        $response = array('message' => 'Password changed successfully!','status' => '1');
                        $this->session->set_flashdata('success', 'Password changed successfully');
                    } else {
                        $response = array('message' => 'Something went wrong!<br>Try again!','status' => '0');
                    }
                } else {
                    $response = array('message' => 'Account not found!','status' => '0');
                }
            } else {
                $response = array('message' => 'Something went wrong!','status' => '0');
            }
        } else {
            $response = array('message' => validation_errors(),'status' => '0');
        }
        echo json_encode($response);
    }

    // end by sudheesh
}
