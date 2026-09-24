<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Employee extends MY_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		$this->load->model('employee_model');
		// pr($this->session->userdata());exit;
	}

	function profile($code = "")
	{

		if (empty($code)) {
			$code = $this->session->userdata('code');
		}

		// employee
		$select = 'e.*,"" as password,pre_district.name pre_district,per_district.name per_district,pre_state.name pre_state,per_state.name per_state,pre_country.name pre_country,per_country.name per_country,nationality.name nationality,cat.name category,des.name designation,dep.name department,sub_dep.name sub_department,rm.name reporting_manager,rmroles.name reporting_manager_role,wl.name as work_location';
		$where = [];
		$where['e.code'] = $code;
		$join = "pre_state,per_state,pre_district,per_district,pre_country,per_country,nationality,cat,des,dep,sub_dep,rm,rmroles,wl";
		$employee = $this->employee_model->get_employees($select, $where, array('join' => $join))->row_array();
		if (empty($employee)) {
			$this->load->view('theme/user/notfound');
			exit;
		}

		// education_history
		$select = 'eh.*,courses.name course,country.name country,state.name state';
		$where = [];
		$where['eh.candidate_id'] = $employee['employee_id'];
		$join = "courses,country,state";
		$education_history =  $this->employee_model->get_education_history($select, $where, array('join' => $join))->result_array();

		// certification_history
		$select = 'ch.*';
		$where = [];
		$where['ch.candidate_id'] = $employee['employee_id'];
		$join = "";
		$certification_history =  $this->employee_model->get_certification_history($select, $where, array('join' => $join))->result_array();

		// employment_history
		$select = 'eh.*,country.name country,state.name state';
		$where = [];
		$where['eh.candidate_id'] = $employee['employee_id'];
		$join = "country,state";
		$employment_history =  $this->employee_model->get_employment_history($select, $where, array('join' => $join))->result_array();

		// family_details
		$select = 'fd.*,o.name occupation';
		$where = [];
		$where['fd.candidate_id'] = $employee['employee_id'];
		$join = "o";
		$family_details =  $this->employee_model->get_family_details($select, $where, array('join' => $join))->result_array();

		// employee_roles
		$select = 'ur.*,r.name';
		$where = [];
		$where['ur.employee_id'] = $employee['employee_id'];
		$join = "r";
		$employee_roles =  $this->employee_model->get_employee_roles($select, $where, array('join' => $join))->result_array();

		$page_data['page_type']    = 'employee/profile';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'profile';
		$page_data['page_title']   = 'Profile';
		$page_data['employee_details'] 	= $employee;
		$page_data['education_history'] = $education_history;
		$page_data['certification_history'] = $certification_history;
		$page_data['employment_history'] = $employment_history;
		$page_data['family_details'] = $family_details;
		$page_data['employee_roles']	 = $employee_roles;
		// $page_data['education_history'] = $education_history;

		$this->load->view('theme/user/main', $page_data);
	}

	function view_employees($status = "")
	{
		$page_data['status']       = $status;
		$page_data['page_type']    = 'employee';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'view_employees';
		$page_data['page_title']   = 'Employees';
		$this->load->view('theme/user/main', $page_data);
	}

	function view_employees_ajax()
	{
		$filters = $this->input->post();
		if (!empty($filters['status'])) {
			$where['e.status'] = $filters['status'];
		}
		$session = $this->session->userdata();
		$select = 'e.*,des.name designation,dep.name department';
		$where['1'] = "1";
		$where['e.probation_status'] = "0";
		if ($session['type'] == '2') {
			$where['e.reporting_manager_id'] = $session['employee_id'];
		}
		$join = "des,dep";
		$order_by = 'e.employee_id DESC';
		$all = $this->employee_model->get_employees($select, $where, array('join' => $join, 'order_by' => $order_by))->result_array();
		$data['data'] = [];
		// echo $this->db->last_query();exit;
		// pr($all);
		// exit;
		if (!empty($all)) {

			foreach ($all as $key => $value) {
				$data['data'][$key]['sno'] 					= $key + 1;
				$data['data'][$key]['name']					= '<a href="' . base_url() . 'employee/profile/' . $value['code'] . '" class="btn-link" title="View profile" target="_blank">' . $value['name'] . '</a>';
				$data['data'][$key]['code']					= $value['code'];
				$data['data'][$key]['designation']			= $value['designation'];
				$data['data'][$key]['department']			= $value['department'];
				$data['data'][$key]['probation_status']		= probation_status($value['probation_status']);
				$data['data'][$key]['status']				= employee_status_c($value['status']);

				$html = '<div class="btn-group">';
				$html .= '<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
				$html .= '<div class="dropdown-menu dropdown-menu-right">';
				if (check_login('1')) {
					$html .= '  <a class="dropdown-item" href="' . base_url('employee/edit_employee/' . $value['employee_id']) . '">Edit</a>';
				}
				if (check_login('1')) {
					$html .= '  <a class="dropdown-item" href="' . base_url('employee/view_employee_history/' . $value['employee_id']) . '">Employee History</a>';
				}
				if (check_login('1')) {
					$html .= '  <button class="dropdown-item" type="button" onclick="showAjaxModal(\'' . base_url('employee/reporting_heads/' . $value['employee_id']) . '\',\'Reporting Heads\',\'modal-md\')">Reporting Heads</button>';
				}
				if (check_login('1') || check_login('2')) {
					$html .= '  <a class="dropdown-item" href="' . base_url('probation/probation_evaluation/' . $value['employee_id']) . '">Probation Evaluation</a>';
				}
				/*if (check_login('1')) {
					$html .= '  <button class="dropdown-item" type="button" onclick="showAjaxModal(\'' . base_url('employee/leave_approval_order/' . $value['employee_id']) . '\',\'Leave Approval Order\',\'modal-lg\')">Leave Approval Order</a>';
				}*/
				if (check_login('1')) {
					$html .= '  <button class="dropdown-item" type="button" onclick="showAjaxModal(\'' . base_url('employee/view_employee_life_cycle_docs/' . $value['employee_id']) . '\',\'Employee Life Cycle Documents\',\'modal-xl\')">Employee Life Cycle Documents</a>';
				}
				$html .= '</div>';
				$html .= '</div>';
				$data['data'][$key]['action']			= $html;
			}
		}
		echo json_encode($data);
	}

	function edit_employee($id)
	{
		if (!check_login('1')) {
			$session = $this->session->userdata();
			$id = $session['employee_id'];
			// $this->session->set_flashdata('error', 'Permission denied!');
			// redirect('signin', 'refresh');
		}
		$select = 'e.*,"" as password,pre_district.name pre_district,per_district.name per_district,pre_state.name pre_state,per_state.name per_state,pre_country.name pre_country,per_country.name per_country,nationality.name nationality,cat.name category,des.name designation,dep.name department,rm.name reporting_manager,rmroles.name reporting_manager_role';
		$where['1'] = "1";
		$where['e.employee_id'] = $id;
		$join = "pre_state,per_state,pre_district,per_district,pre_country,per_country,nationality,cat,des,dep,rm,rmroles";
		$employee = $this->employee_model->get_employees($select, $where, array('join' => $join))->row_array();
		if (empty($employee)) {
			exit;
		}
		$page_data['employee']         	= $employee;

		$page_data['genders']			= $this->common_model->enum_select('employee', 'gender');
		$page_data['nationality']		= $this->common_model->selectAll('nationality', "", "*");
		$page_data['categories']		= $this->common_model->selectAll('category', "", "*");
		$page_data['marital_status']	= $this->common_model->enum_select('employee', 'marital_status');
		$page_data['blood_groups']		= $this->common_model->enum_select('employee', 'blood_group');
		$page_data['valid_passport']	= $this->common_model->enum_select('employee', 'valid_passport');
		$page_data['business_visa_usa']	= $this->common_model->enum_select('employee', 'business_visa_usa');
		$page_data['countries']			= $this->common_model->selectAll('country', "", "*");
		$page_data['address_same']		= $this->common_model->enum_select('employee', 'address_same');

		$page_data['wfh_or_office']  = $this->common_model->enum_select('employee', 'wfh_or_office');
		$page_data['departments']  = $this->common_model->selectAll('departments', '', '');
		$page_data['designations'] = $this->common_model->selectAll('designations', '', '');
		$page_data['work_locations'] = $this->common_model->selectAll('work_locations', '', '');
		$page_data['bands']		= $this->common_model->enum_select('employee', 'band');

		$page_data['page_type']    = 'employee/edit_employee';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'edit_employee';
		$page_data['page_title']   = 'Edit Employee';
		$this->load->view('theme/user/main', $page_data);
	}

	function edit_employee_post()
	{
		if (!check_login('1')) {
			$session = $this->session->userdata();
			$_POST['employee_id'] = $session['employee_id'];
			// $this->session->set_flashdata('error', 'Permission denied!');
			// redirect('signin', 'refresh');
		}
		if (isset($_POST['submit'])) {
			// pr($_POST);
			// exit;
			// pr($_FILES);exit;
			// stage 1
			$this->form_validation->set_rules('employee_id', 'employee_id', 'trim|required');
			$this->form_validation->set_rules('name', 'Name', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required');
			$this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|min_length[10]|max_length[10]');
			$this->form_validation->set_rules('gender', 'Gender', 'trim|required');
			$this->form_validation->set_rules('nationality_id', 'Nationality', 'trim|required');
			$this->form_validation->set_rules('religion', 'Religion', 'trim|required');
			$this->form_validation->set_rules('category_id', 'Category', 'trim|required');
			$this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|required');
			$this->form_validation->set_rules('marital_status', 'Marital Status', 'trim|required');
			$this->form_validation->set_rules('blood_group', 'Blood Group', 'trim|required');
			$this->form_validation->set_rules('pan_number', 'PAN Number', 'trim|required');
			$this->form_validation->set_rules('valid_passport', 'Do you have a valid passport?', 'trim|required');
			if (!empty($_POST['valid_passport'])) {
				if ($_POST['valid_passport'] == 'yes') {
					$this->form_validation->set_rules('passport_number', 'Passport Number', 'trim|required');
					$this->form_validation->set_rules('passport_validity', 'Valid Until', 'trim|required');
					$this->form_validation->set_rules('business_visa_usa', 'Do you have Active Business Visa of USA?', 'trim|required');
					if ($_POST['valid_passport'] == 'yes') {
						$this->form_validation->set_rules('visa_validity', 'Visa Valid Until', 'trim|required');
					}
				}
			}

			$this->form_validation->set_rules('pre_country_id', 'Country', 'trim|required');
			$this->form_validation->set_rules('pre_state_id', 'State', 'trim|required');
			$this->form_validation->set_rules('pre_district_id', 'District', 'trim|required');
			$this->form_validation->set_rules('pre_pincode', 'PIN Code', 'trim|required');
			$this->form_validation->set_rules('pre_address', 'Rest of the Address', 'trim|required');
			$this->form_validation->set_rules('address_same', 'Is Permanent Address same as Present?', 'trim|required');
			if (!empty($_POST['address_same'])) {
				if ($_POST['address_same'] != 'yes') {
					$this->form_validation->set_rules('country_id', 'Country', 'trim|required');
					$this->form_validation->set_rules('state_id', 'State', 'trim|required');
					$this->form_validation->set_rules('district_id', 'District', 'trim|required');
					$this->form_validation->set_rules('pincode', 'PIN Code', 'trim|required');
					$this->form_validation->set_rules('address', 'Rest of the Address', 'trim|required');
				}
			}

			$this->form_validation->set_rules('wfh_or_office', 'WFH/Office/Hybrid', 'trim|required');
			$this->form_validation->set_rules('department_id', 'Department', 'trim|required');
			$this->form_validation->set_rules('sub_department_id', 'Sub Department', 'trim|required');
			$this->form_validation->set_rules('designation_id', 'Designations', 'trim|required');
			$this->form_validation->set_rules('work_location_id', 'Work Location', 'trim|required');
			$this->form_validation->set_rules('shift', 'Shift', 'trim|required');
			$this->form_validation->set_rules('vacancy_type', 'Vacancy Type', 'trim|required');
			$this->form_validation->set_rules('band', 'Band', 'trim|required');
			$this->form_validation->set_rules('sub_band', 'Sub Band', 'trim|required');
			$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
			if ($this->form_validation->run()) {
				$data = $this->input->post(NULL, true);

				$select = 'e.*,"" as password,pre_district.name pre_district,per_district.name per_district,pre_state.name pre_state,per_state.name per_state,pre_country.name pre_country,per_country.name per_country,nationality.name nationality,cat.name category,des.name designation,dep.name department,rm.name reporting_manager,rmroles.name reporting_manager_role';
				$where['e.employee_id'] = $data['employee_id'];
				$join = "pre_state,per_state,pre_district,per_district,pre_country,per_country,nationality,cat,des,dep,rm,rmroles";
				$exist = $this->employee_model->get_employees($select, $where, array('join' => $join))->row_array();
				if (!empty($exist)) {
					$update['name'] 				= $data['name'];
					$update['email'] 				= $data['email'];
					$update['mobile_number'] 		= $data['mobile_number'];
					$update['gender'] 				= $data['gender'];
					$update['nationality_id'] 		= $data['nationality_id'];
					$update['religion'] 			= $data['religion'];
					$update['category_id'] 			= $data['category_id'];
					$update['date_of_birth'] 		= set_date($data['date_of_birth']);
					$update['marital_status'] 		= $data['marital_status'];
					$update['blood_group'] 			= $data['blood_group'];
					$update['pan_number'] 			= $data['pan_number'];
					$update['valid_passport'] 		= $data['valid_passport'];
					$update['passport_number'] 		= ($update['valid_passport'] == 'yes') ? $data['passport_number'] : "";
					$update['passport_validity'] 	= ($update['valid_passport'] == 'yes') ? set_date($data['passport_validity']) : "";
					$update['business_visa_usa'] 	= ($update['valid_passport'] == 'yes') ? $data['business_visa_usa'] : "";
					$update['visa_validity'] 		= ($update['business_visa_usa'] == 'yes') ? set_date($data['visa_validity']) : "";
					$update['pre_country_id'] 		= $data['pre_country_id'];
					$update['pre_state_id'] 		= $data['pre_state_id'];
					$update['pre_district_id'] 		= $data['pre_district_id'];
					$update['pre_pincode'] 			= $data['pre_pincode'];
					$update['pre_address'] 			= $data['pre_address'];
					$update['address_same'] 		= $data['address_same'];
					$update['country_id'] 			= ($update['address_same'] == 'no') ? $data['country_id'] : $data['pre_country_id'];
					$update['state_id'] 			=  ($update['address_same'] == 'no') ? $data['state_id'] : $data['pre_state_id'];
					$update['district_id'] 			=  ($update['address_same'] == 'no') ? $data['district_id'] : $data['pre_district_id'];
					$update['pincode'] 				=  ($update['address_same'] == 'no') ? $data['pincode'] : $data['pre_pincode'];
					$update['address'] 				= ($update['address_same'] == 'no') ? $data['address'] : $data['pre_address'];
					$update['wfh_or_office'] 		= $data['wfh_or_office'];
					$update['department_id'] 		= $data['department_id'];
					$update['sub_department_id'] 	= $data['sub_department_id'];
					$update['designation_id'] 		= $data['designation_id'];
					$update['work_location_id'] 	= $data['work_location_id'];
					$update['shift'] 				= $data['shift'];
					$update['vacancy_type'] 		= $data['vacancy_type'];
					$update['band'] 				= $data['band'];
					$update['sub_band'] 			= $data['sub_band'];

					$resp = $this->common_model->update($this->security->xss_clean($update), array('employee_id' => $exist['employee_id']), 'employee');

					if (!empty($resp)) {
						$select = 'e.*,"" as password,pre_district.name pre_district,per_district.name per_district,pre_state.name pre_state,per_state.name per_state,pre_country.name pre_country,per_country.name per_country,nationality.name nationality,cat.name category,des.name designation,dep.name department,rm.name reporting_manager,rmroles.name reporting_manager_role';
						$where = [];
						$where['e.employee_id'] = $data['employee_id'];
						$join = "pre_state,per_state,pre_district,per_district,pre_country,per_country,nationality,cat,des,dep,rm,rmroles";
						$updated = $this->employee_model->get_employees($select, $where, array('join' => $join))->row_array();

						$requiredKeys = ['name' => 'Name', 'email' => 'Email', 'mobile_number' => 'Mobile Number', 'gender' => 'Gender', 'nationality' => 'Nationality', 'religion' => 'Religion', 'category' => 'Category', 'date_of_birth' => 'Date of Birth', 'marital_status' => 'Marital Status', 'blood_group' => 'Blood Group', 'pre_country' => 'Present Country', 'pre_state' => 'Present State', 'pre_district' => 'Present District', 'pre_pincode' => 'Present Pincode', 'pre_address' => 'Present Address', 'per_country' => 'Permanent Country', 'per_state' => 'Permanent State', 'per_district' => 'Permanent District', 'pincode' => 'Permanent Pincode', 'address' => 'Permanent Address', 'department' => 'Department', 'designation' => 'Designation', 'band' => 'Band', 'sub_band' => 'Sub Band'];

						foreach ($requiredKeys as $key => $value) {
							// pr($requiredKeys);
							// pr($key);exit;
							$prevData[$key] = $exist[$key];
							$newData[$key] = $updated[$key];
						}

						$diff = array_diff($prevData, $newData);
						$history = [];
						$session = $this->session->userdata();
						foreach ($diff as $key => $value) {
							$item['employee_id'] = $exist['employee_id'];
							$item['updated_by'] = $session['employee_id'];
							$item['changes'] = $requiredKeys[$key] . ' updated from "' . $prevData[$key] . '" to "' . $newData[$key] . '"';
							array_push($history, $item);
						}
						if (!empty($history)) {
							$this->db->insert_batch('employee_history', $this->security->xss_clean($history));
						}

						// pr($diff);
						// pr($history);
						// exit;
						$response = array('status' => '1', 'msg' => 'Data Updated!');
					} else {
						$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
					}
				} else {
					$response = array('status' => '0', 'msg' => 'Record does not exist!');
				}
			} else {
				// echo $this->db->last_query();exit;
				$response = array('status' => '0', 'msg' => validation_errors());
			}
			echo json_encode($response);
			exit;
		}
	}

	function edit_employee_educational_history($id)
	{
		if (!check_login('1')) {
			$session = $this->session->userdata();
			$id = $session['employee_id'];
			// $this->session->set_flashdata('error', 'Permission denied!');
			// redirect('signin', 'refresh');
		}
		// employee
		$select = 'e.employee_id';
		$where['1'] = "1";
		$where['e.employee_id'] = $id;
		$join = "";
		$employee = $this->employee_model->get_employees($select, $where, array('join' => $join))->row_array();

		if (empty($employee)) {
			$this->session->set_flashdata('error', 'Record does not exist!');
			redirect('employee/view_employees', 'refresh');
			exit;
		}

		// educational_history
		$select = 'eh.*,courses.name course,country.name country,state.name state';
		$where = [];
		$where['eh.candidate_id'] = $employee['employee_id'];
		$join = "courses,country,state";
		$educational_history =  $this->employee_model->get_education_history($select, $where, array('join' => $join))->result_array();

		$page_data['employee']         	= $employee;
		$page_data['educational_history']    = $educational_history;

		$page_data['countries']			= $this->common_model->selectAll('country', "", "*");
		$page_data['courses']			= $this->common_model->selectAll('courses', "", "*");

		$page_data['page_type']    = 'employee/edit_employee';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'edit_employee_educational_history';
		$page_data['page_title']   = 'Edit Employee Educational History';
		$this->load->view('theme/user/main', $page_data);
	}

	function edit_employee_educational_history_post()
	{
		if (!check_login('1')) {
			$session = $this->session->userdata();
			$_POST['employee_id'] = $session['employee_id'];
			// $this->session->set_flashdata('error', 'Permission denied!');
			// redirect('signin', 'refresh');
		}
		if (isset($_POST['submit'])) {
			// pr($_POST);
			// exit;
			if (isset($_POST['course_id'])) {
				// pr($_POST);
				// exit;
				// pr($_FILES);exit;
				// stage 1
				$this->form_validation->set_rules('employee_id', 'employee_id', 'trim|required');
				$this->form_validation->set_rules('course_id[]', 'Qualification', 'trim|required');
				// $this->form_validation->set_rules('specialization[]', 'Specialization', 'trim|required');
				$this->form_validation->set_rules('institute_name[]', 'Institute', 'trim|required');
				$this->form_validation->set_rules('country_id[]', 'Country', 'trim|required');
				$this->form_validation->set_rules('state_id[]', 'State', 'trim|required');
				$this->form_validation->set_rules('nearest_city[]', 'Nearest City', 'trim|required');
				$this->form_validation->set_rules('percentage_mark[]', '% Marks / Grade', 'trim|required');
				$this->form_validation->set_rules('completion_year[]', 'Year of Completion', 'trim|required');
				$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
				if ($this->form_validation->run()) {
					$data = $this->input->post(NULL, true);

					if (!empty($data['course_id'])) {
						$user_docs = 'assets/uploads/user_docs/candidates_docs/' . $data['employee_id'];
						$oldmask = umask(0);
						if (!is_dir($user_docs)) {
							mkdir($user_docs, 0777, true);
						}
						umask($oldmask);
						if (is_writable(($user_docs))) {
							if (!file_exists($user_docs . '/index.html')) {
								file_put_contents($user_docs . '/index.html', '');
							}
							$inserted  = 0;
							foreach ($data['course_id'] as $key => $item) {
								$edu['candidate_id'] = $data['employee_id'];
								$edu['course_id'] = $data['course_id'][$key];
								$edu['specialization'] = ($edu['course_id'] > 2) ? ($data['specialization'][$key] ?? "") : "";
								$edu['institute_name'] = $data['institute_name'][$key];
								$edu['country_id'] = $data['country_id'][$key];
								$edu['state_id'] = $data['state_id'][$key];
								$edu['nearest_city'] = $data['nearest_city'][$key];
								$edu['percentage_mark'] = $data['percentage_mark'][$key];
								$edu['completion_year'] = $data['completion_year'][$key];
								// mark_card

								$edu_id = $this->common_model->insert($edu, 'education_history');
								if (!empty($edu_id)) {
									if (!empty($_FILES['mark_card_' . $key]['name']) && is_array($_FILES['mark_card_' . $key]['name'])) {
										$update_mark_card = [];

										foreach ($_FILES['mark_card_' . $key]['name'] as $key => $mark_card_) {
											if (!empty($_FILES['mark_card_' . $key]['name'][$key])) {
												$_FILES['file']['name']     = $_FILES['mark_card_' . $key]['name'][$key];
												$_FILES['file']['type']     = $_FILES['mark_card_' . $key]['type'][$key];
												$_FILES['file']['tmp_name'] = $_FILES['mark_card_' . $key]['tmp_name'][$key];
												$_FILES['file']['error']     = $_FILES['mark_card_' . $key]['error'][$key];
												$_FILES['file']['size']     = $_FILES['mark_card_' . $key]['size'][$key];

												$config['upload_path']          = $user_docs;
												$config['allowed_types']        = 'jpg|png|jpeg|pdf';
												$config['max_size']             = 2048;
												$config['file_name']    		= date('dmYhis') . '_' . uniqid() . "." . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
												$this->load->library('upload', $config);
												$this->upload->initialize($config);
												if ($this->upload->do_upload('file')) {

													$uploadedFileData = $this->upload->data();
													$fileName = $uploadedFileData['file_name'];
													array_push($update_mark_card, $fileName);
												}
											}
										}
										if (!empty($update_mark_card)) {
											$resp = $this->common_model->update(array('mark_card' => implode(',', $update_mark_card)), array('education_id' => $edu_id), 'education_history');
										}
									}
									$inserted++;
								}
							}
							if ($inserted > 0) {
								$response = array('status' => '1', 'msg' => 'Data Updated!');
							} else {
								$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
							}
						} else {
							$response = array('status' => '0', 'msg' => 'File upload path is not writable!');
						}
					} else {
						$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
					}
				} else {
					// echo $this->db->last_query();exit;
					$response = array('status' => '0', 'msg' => validation_errors());
				}
			} else {
				$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
			}
			echo json_encode($response);
			exit;
		}
	}

	function edit_employee_certification_history($id)
	{
		if (!check_login('1')) {
			$session = $this->session->userdata();
			$id = $session['employee_id'];
			// $this->session->set_flashdata('error', 'Permission denied!');
			// redirect('signin', 'refresh');
		}
		// employee
		$select = 'e.employee_id';
		$where['1'] = "1";
		$where['e.employee_id'] = $id;
		$join = "";
		$employee = $this->employee_model->get_employees($select, $where, array('join' => $join))->row_array();

		if (empty($employee)) {
			$this->session->set_flashdata('error', 'Record does not exist!');
			redirect('employee/view_employees', 'refresh');
			exit;
		}

		// certification_history
		$select = 'ch.*';
		$where = [];
		$where['ch.candidate_id'] = $employee['employee_id'];
		$join = "";
		$certification_history =  $this->employee_model->get_certification_history($select, $where, array('join' => $join))->result_array();
		$page_data['employee']         	= $employee;
		$page_data['certification_history']    = $certification_history;

		$page_data['page_type']    = 'employee/edit_employee';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'edit_employee_certification_history';
		$page_data['page_title']   = 'Edit Employee Certification History';
		$this->load->view('theme/user/main', $page_data);
	}

	function edit_employee_certification_history_post()
	{
		if (!check_login('1')) {
			$session = $this->session->userdata();
			$_POST['employee_id'] = $session['employee_id'];
			// $this->session->set_flashdata('error', 'Permission denied!');
			// redirect('signin', 'refresh');
		}
		if (isset($_POST['submit'])) {
			if (isset($_POST['certification'])) {
				$this->form_validation->set_rules('employee_id', 'employee_id', 'trim|required');
				$this->form_validation->set_rules('certification[]', 'Name of the Certification', 'trim|required');
				$this->form_validation->set_rules('specialization[]', 'Specialization', 'trim|required');
				$this->form_validation->set_rules('institute_name[]', 'Institute Name', 'trim|required');
				$this->form_validation->set_rules('start_date[]', 'Start Date', 'trim|required');
				$this->form_validation->set_rules('end_date[]', 'End Date', 'trim|required');
				// $this->form_validation->set_rules('valid_upto[]', 'Valid Upto', 'trim|required');
				$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
				if ($this->form_validation->run()) {
					$data = $this->input->post(NULL, true);
					if (!empty($data['certification'])) {
						$inserted  = 0;
						foreach ($data['certification'] as $key => $item) {
							$edu['candidate_id'] = $data['employee_id'];
							$edu['certification'] = $data['certification'][$key];
							$edu['specialization'] = $data['specialization'][$key];
							$edu['institute_name'] = $data['institute_name'][$key];
							$edu['start_date'] = set_date($data['start_date'][$key]);
							$edu['end_date'] = set_date($data['end_date'][$key]);
							$edu['valid_upto'] = set_date($data['valid_upto'][$key] ?? "");

							$edu_id = $this->common_model->insert($edu, 'certification_history');
							if (!empty($edu_id)) {
								$inserted++;
							}
						}
						if ($inserted > 0) {
							$response = array('status' => '1', 'msg' => 'Data Updated!');
						} else {
							$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
						}
					} else {
						$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
					}
				} else {
					// echo $this->db->last_query();exit;
					$response = array('status' => '0', 'msg' => validation_errors());
				}
			} else {
				$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
			}
			echo json_encode($response);
			exit;
		}
	}

	function edit_employee_employment_history($id)
	{
		if (!check_login('1')) {
			$session = $this->session->userdata();
			$id = $session['employee_id'];
			// $this->session->set_flashdata('error', 'Permission denied!');
			// redirect('signin', 'refresh');
		}
		// employee
		$select = 'e.employee_id';
		$where['1'] = "1";
		$where['e.employee_id'] = $id;
		$join = "";
		$employee = $this->employee_model->get_employees($select, $where, array('join' => $join))->row_array();

		if (empty($employee)) {
			$this->session->set_flashdata('error', 'Record does not exist!');
			redirect('employee/view_employees', 'refresh');
			exit;
		}

		// employment_history
		$select = 'eh.*,country.name country,state.name state';
		$where = [];
		$where['eh.candidate_id'] = $employee['employee_id'];
		$join = "country,state";
		$employment_history =  $this->employee_model->get_employment_history($select, $where, array('join' => $join))->result_array();
		$page_data['employee']         	= $employee;
		$page_data['employment_history']    = $employment_history;

		$page_data['countries']			= $this->common_model->selectAll('country', "", "*");

		$page_data['page_type']    = 'employee/edit_employee';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'edit_employee_employment_history';
		$page_data['page_title']   = 'Edit Employee Employment History';
		$this->load->view('theme/user/main', $page_data);
	}

	function edit_employee_employment_history_post()
	{
		if (!check_login('1')) {
			$session = $this->session->userdata();
			$_POST['employee_id'] = $session['employee_id'];
			// $this->session->set_flashdata('error', 'Permission denied!');
			// redirect('signin', 'refresh');
		}
		if (isset($_POST['submit'])) {
			if (isset($_POST['company_name'])) {
				$this->form_validation->set_rules('employee_id', 'employee_id', 'trim|required');
				$this->form_validation->set_rules('company_name[]', 'Company Name', 'trim|required');
				$this->form_validation->set_rules('country_id[]', 'Country', 'trim|required');
				$this->form_validation->set_rules('state_id[]', 'State', 'trim|required');
				$this->form_validation->set_rules('nearest_city[]', 'Nearest City', 'trim|required');
				$this->form_validation->set_rules('start_date[]', 'Start Date', 'trim|required');
				// $this->form_validation->set_rules('end_date[]', 'End Date', 'trim|required');
				$this->form_validation->set_rules('last_designation[]', 'Last Designation Held', 'trim|required');
				$this->form_validation->set_rules('responsibilities[]', 'Responsibilities Handled', 'trim|required');
				$this->form_validation->set_rules('annual_ctc[]', 'Annual CTC (LPA)', 'trim|required');
				$this->form_validation->set_rules('reason_jobchance[]', 'Reason For Job Change', 'trim|required');
				$this->form_validation->set_rules('is_this_relevant_exp[]', 'Is this experience relevant?', 'trim|required');
				$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
				if ($this->form_validation->run()) {
					$data = $this->input->post(NULL, true);
					if (!empty($data['company_name'])) {
						$inserted  = 0;
						foreach ($data['company_name'] as $key => $item) {
							$insert['candidate_id'] = $data['employee_id'];
							$insert['company_name'] = $data['company_name'][$key];
							$insert['country_id'] = $data['country_id'][$key];
							$insert['state_id'] = $data['state_id'][$key];
							$insert['nearest_city'] = $data['nearest_city'][$key];
							$insert['start_date'] = set_date($data['start_date'][$key]);
							$insert['end_date'] = set_date($data['end_date'][$key] ?? '');
							$insert['last_designation'] = $data['last_designation'][$key];
							$insert['responsibilities'] = $data['responsibilities'][$key];
							$insert['annual_ctc'] = $data['annual_ctc'][$key];
							$insert['reason_jobchance'] = $data['reason_jobchance'][$key];
							$insert['is_this_relevant_exp'] = $data['is_this_relevant_exp'][$key];

							$id = $this->common_model->insert($insert, 'employment_history');
							if (!empty($id)) {
								$inserted++;
							}
						}
						if ($inserted > 0) {
							$response = array('status' => '1', 'msg' => 'Data Updated!');
						} else {
							$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
						}
					} else {
						$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
					}
				} else {
					// echo $this->db->last_query();exit;
					$response = array('status' => '0', 'msg' => validation_errors());
				}
			} else {
				$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
			}
			echo json_encode($response);
			exit;
		}
	}

	function edit_employee_family_details($id)
	{
		if (!check_login('1')) {
			$session = $this->session->userdata();
			$id = $session['employee_id'];
			// $this->session->set_flashdata('error', 'Permission denied!');
			// redirect('signin', 'refresh');
		}
		// employee
		$select = 'e.employee_id';
		$where['1'] = "1";
		$where['e.employee_id'] = $id;
		$join = "";
		$employee = $this->employee_model->get_employees($select, $where, array('join' => $join))->row_array();

		if (empty($employee)) {
			$this->session->set_flashdata('error', 'Record does not exist!');
			redirect('employee/view_employees', 'refresh');
			exit;
		}

		// family_details
		$select = 'fd.*,o.name occupation';
		$where = [];
		$where['fd.candidate_id'] = $employee['employee_id'];
		$join = "o";
		$family_details =  $this->employee_model->get_family_details($select, $where, array('join' => $join))->result_array();
		$page_data['employee']         	= $employee;
		$page_data['family_details']    = $family_details;

		$page_data['relationships']		= $this->common_model->enum_select('family_details', 'relationship');
		$page_data['living_status']		= $this->common_model->enum_select('family_details', 'living_status');
		$page_data['genders']		= $this->common_model->enum_select('family_details', 'gender');
		$page_data['genders']		= $this->common_model->enum_select('family_details', 'gender');
		$page_data['occupations']			= $this->common_model->selectAll('occupation', "", "*");
		$page_data['emergency_contact']		= $this->common_model->enum_select('family_details', 'emergency_contact');
		$page_data['page_type']    = 'employee/edit_employee';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'edit_employee_family_details';
		$page_data['page_title']   = 'Edit Employee Family History';
		$this->load->view('theme/user/main', $page_data);
	}

	function edit_employee_family_details_post()
	{
		if (!check_login('1')) {
			$session = $this->session->userdata();
			$_POST['employee_id'] = $session['employee_id'];
			// $this->session->set_flashdata('error', 'Permission denied!');
			// redirect('signin', 'refresh');
		}
		if (isset($_POST['submit'])) {
			if (isset($_POST['name'])) {
				$this->form_validation->set_rules('employee_id', 'employee_id', 'trim|required');
				$this->form_validation->set_rules('name[]', 'Name of Family Member', 'trim|required');
				$this->form_validation->set_rules('relationship[]', 'Relationship with the Employee', 'trim|required');
				$this->form_validation->set_rules('living_status[]', 'Living Status', 'trim|required');
				// $this->form_validation->set_rules('gender[]', 'Gender', 'trim|required');
				// $this->form_validation->set_rules('date_of_birth[]', 'Date of Birth', 'trim|required');
				// $this->form_validation->set_rules('occupation_id[]', 'Occupation', 'trim|required');
				// $this->form_validation->set_rules('mobile_number[]', 'Mobile Number', 'trim|required');
				// $this->form_validation->set_rules('emergency_contact[]', 'Emergency Contact?', 'trim|required');
				$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
				if ($this->form_validation->run()) {
					$data = $this->input->post(NULL, true);
					if (!empty($data['name'])) {
						$inserted  = 0;
						foreach ($data['name'] as $key => $item) {
							$insert['candidate_id'] 	= $data['employee_id'];
							$insert['name'] 			= $data['name'][$key];
							$insert['relationship'] 	= $data['relationship'][$key];
							$insert['living_status'] 	= $data['living_status'][$key];
							$insert['gender'] 			= ($insert['living_status'] != 'deceased') ? $data['gender'][$key] : null;
							$insert['date_of_birth'] 	= ($insert['living_status'] != 'deceased') ? set_date($data['date_of_birth'][$key]) : null;
							$insert['occupation_id'] 	= ($insert['living_status'] != 'deceased') ? $data['occupation_id'][$key] : null;
							$insert['mobile_number'] 	= ($insert['living_status'] != 'deceased') ? $data['mobile_number'][$key] : null;
							$insert['emergency_contact'] = ($insert['living_status'] != 'deceased') ? $data['emergency_contact'][$key] : 'no';

							$id = $this->common_model->insert($insert, 'family_details');
							if (!empty($id)) {
								$inserted++;
							}
						}
						if ($inserted > 0) {
							$response = array('status' => '1', 'msg' => 'Data Updated!');
						} else {
							$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
						}
					} else {
						$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
					}
				} else {
					// echo $this->db->last_query();exit;
					$response = array('status' => '0', 'msg' => validation_errors());
				}
			} else {
				$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
			}
			echo json_encode($response);
			exit;
		}
	}

	function edit_employee_salary_and_other($id)
	{
		if (!check_login('1')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('signin', 'refresh');
		}
		$select = 'e.*,"" as password,pre_district.name pre_district,per_district.name per_district,pre_state.name pre_state,per_state.name per_state,pre_country.name pre_country,per_country.name per_country,nationality.name nationality,cat.name category,des.name designation,dep.name department,rm.name reporting_manager,rmroles.name reporting_manager_role';
		$where['1'] = "1";
		$where['e.employee_id'] = $id;
		$join = "pre_state,per_state,pre_district,per_district,pre_country,per_country,nationality,cat,des,dep,rm,rmroles";
		$employee = $this->employee_model->get_employees($select, $where, array('join' => $join))->row_array();
		if (empty($employee)) {
			exit;
		}
		$page_data['employee']         	= $employee;

		$page_data['payment_frequency_vpb']			= $this->common_model->enum_select('employee', 'payment_frequency_vpb');

		$page_data['page_type']    = 'employee/edit_employee';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'edit_employee_salary_and_other';
		$page_data['page_title']   = 'Edit Employee Salary and Other';
		$this->load->view('theme/user/main', $page_data);
	}

	function edit_employee_salary_and_other_post()
	{
		if (!check_login('1')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('signin', 'refresh');
		}
		if (isset($_POST['submit'])) {
			// pr($_POST);
			// exit;
			// pr($_FILES);exit;
			// stage 1
			$this->form_validation->set_rules('employee_id', 'employee_id', 'trim|required');
			$this->form_validation->set_rules('basic', 'basic', 'trim');
			$this->form_validation->set_rules('vda', 'vda', 'trim');
			$this->form_validation->set_rules('hra', 'hra', 'trim');
			$this->form_validation->set_rules('nightshift_allowance', 'nightshift_allowance', 'trim');
			$this->form_validation->set_rules('wfh_allowance', 'wfh_allowance', 'trim');
			$this->form_validation->set_rules('flexi_allowance', 'flexi_allowance', 'trim');
			$this->form_validation->set_rules('variable_performance_bonus', 'variable_performance_bonus', 'trim');
			$this->form_validation->set_rules('payment_frequency_vpb', 'payment_frequency_vpb', 'trim');
			$this->form_validation->set_rules('employer_pf', 'employer_pf', 'trim');
			$this->form_validation->set_rules('employer_esi', 'employer_esi', 'trim');
			$this->form_validation->set_rules('employer_gratuity', 'employer_gratuity', 'trim');
			$this->form_validation->set_rules('annual_retention_bonus', 'annual_retention_bonus', 'trim');
			$this->form_validation->set_rules('months_eligible_bonus', 'months_eligible_bonus', 'trim');
			$this->form_validation->set_rules('retention_bonus_duedate', 'retention_bonus_duedate', 'trim');
			$this->form_validation->set_rules('statutory_bonus', 'statutory_bonus', 'trim');
			$this->form_validation->set_rules('medical_insurance', 'medical_insurance', 'trim');
			$this->form_validation->set_rules('gross', 'gross', 'trim');
			$this->form_validation->set_rules('monthly_ctc', 'monthly_ctc', 'trim');
			$this->form_validation->set_rules('annual_ctc', 'annual_ctc', 'trim');
			$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
			if ($this->form_validation->run()) {
				$data = $this->input->post(NULL, true);

				$select = 'e.*';
				$where['e.employee_id'] = $data['employee_id'];
				$join = "";
				$exist = $this->employee_model->get_employees($select, $where, array('join' => $join))->row_array();
				if (!empty($exist)) {
					$update['basic'] 				= $data['basic'] ?? '';
					$update['vda'] 				= $data['vda'] ?? '';
					$update['hra'] 		= $data['hra'] ?? '';
					$update['nightshift_allowance'] 				= $data['nightshift_allowance'] ?? '';
					$update['wfh_allowance'] 		= $data['wfh_allowance'] ?? '';
					$update['flexi_allowance'] 			= $data['flexi_allowance'] ?? '';
					$update['variable_performance_bonus'] 			= $data['variable_performance_bonus'] ?? '';
					$update['payment_frequency_vpb'] 		= $data['payment_frequency_vpb'] ?? '';
					$update['employer_pf'] 			= $data['employer_pf'] ?? '';
					$update['employer_esi'] 			= $data['employer_esi'] ?? '';
					$update['employer_gratuity'] 		= $data['employer_gratuity'] ?? '';
					$update['annual_retention_bonus'] 		= $data['annual_retention_bonus'] ?? '';
					$update['months_eligible_bonus'] 		= $data['months_eligible_bonus'] ?? '';
					$update['retention_bonus_duedate'] 		= $data['retention_bonus_duedate'] ?? '';
					$update['statutory_bonus'] 		= $data['statutory_bonus'] ?? '';
					$update['medical_insurance'] 		= $data['medical_insurance'] ?? '';
					$update['effective_date_of_salary'] 		= $data['effective_date_of_salary'] ?? '';
					$update['gross'] 		= $data['gross'] ?? '';
					$update['monthly_ctc'] 		= $data['monthly_ctc'] ?? '';
					$update['annual_ctc'] 		= $data['annual_ctc'] ?? '';

					$resp = $this->common_model->update($this->security->xss_clean($update), array('employee_id' => $exist['employee_id']), 'employee');

					if (!empty($resp)) {
						$response = array('status' => '1', 'msg' => 'Data Updated!');
					} else {
						$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
					}
				} else {
					$response = array('status' => '0', 'msg' => 'Record does not exist!');
				}
			} else {
				// echo $this->db->last_query();exit;
				$response = array('status' => '0', 'msg' => validation_errors());
			}
			echo json_encode($response);
			exit;
		}
	}

	/* get state function */
	public function get_sub_departments()
	{
		$department_id =  $this->input->post('department_id');
		$subdepartments = $this->db->where('department_id', $department_id)->get('subdepartment')->result();
		echo json_encode($subdepartments);
	}

	/* get state function */
	public function get_states()
	{
		$country_id =  $this->input->post('country_id');
		$states = $this->db->where('country_id', $country_id)->get('state')->result();
		echo json_encode($states);
	}

	/* get district function */
	public function get_district()
	{
		$state_id = $this->input->post('state_id');
		$district = $this->db->where('state_id', $state_id)->get('district')->result();
		echo json_encode($district);
	}

	function view_employee_history($employee_id = "")
	{

		if (!check_login('1')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('signin', 'refresh');
		}

		$page_data['employee_id']    = $employee_id;
		$page_data['page_type']    = 'employee/employee_history';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'view_employee_history';
		$page_data['page_title']   = 'Employee History';
		$this->load->view('theme/user/main', $page_data);
	}

	function view_employee_history_ajax()
	{
		if (!check_login('1')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('signin', 'refresh');
		}

		$filters = $this->input->post();
		if (!empty($filters['employee_id'])) {
			$where['e.employee_id'] = $filters['employee_id'];
		}

		$select = 'eh.*,e.name employee,e.code code,ub.name updated_by';
		$where['1'] = "1";
		$join = "e,ub";
		$order_by = 'eh.id DESC';
		$all = $this->employee_model->get_employee_history($select, $where, array('join' => $join, 'order_by' => $order_by))->result_array();
		$data['data'] = [];
		// echo $this->db->last_query();exit;
		// pr($all);
		// exit;
		if (!empty($all)) {

			foreach ($all as $key => $value) {
				$data['data'][$key]['sno'] 					= $key + 1;
				$data['data'][$key]['employee']				= '<a href="' . base_url() . 'employee/profile/' . $value['code'] . '" class="btn-link" title="View profile" target="_blank">' . $value['employee'] . '</a>';
				$data['data'][$key]['code']					= $value['code'];
				$data['data'][$key]['changes']				= $value['changes'];
				$data['data'][$key]['updated_by']			= $value['updated_by'];
				$data['data'][$key]['updated_at']			= date('d-m-Y H:i', strtotime($value['created_at']));
				$html = '';
				// $html .= '<div class="btn-group">';
				// $html .= '<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
				// $html .= '<div class="dropdown-menu dropdown-menu-right">';
				// $html .= '  <a class="dropdown-item" href="' . base_url('employee/edit_employee/' . $value['employee_id']) . '">Edit</a>';
				// $html .= '  <button class="dropdown-item" type="button" onclick="showAjaxModal(\'' . base_url('employee/reporting_heads/' . $value['employee_id']) . '\',\'Reporting Heads\',\'modal-md\')">Reporting Heads</button>';
				// $html .= '  <a class="dropdown-item" href="' . base_url('probation/probation_evaluation/' . $value['employee_id']) . '">Probation Evaluation</a>';
				// $html .= '</div>';
				// $html .= '</div>';
				$data['data'][$key]['action']			= $html;
			}
		}
		echo json_encode($data);
	}

	function reporting_heads($employee_id)
	{

		if (!check_login('1')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('signin', 'refresh');
		}
		$page_data['employee']  =  $this->employee_model->get_employees("e.*", array('employee_id' => $employee_id))->row_array();
		$page_data['reporting_managers']  = $this->common_model->selectAll('employee', array('employee_id!=' => $employee_id, 'probation_status' => '0'), '');
		// echo $this->db->last_query();exit;
		$page_data['special_roles'] = $this->common_model->enum_select('employee', 'special_role');

		// $page_data['page_type']    = 'employee/reporting_heads';
		// $page_data['menu']         = 'employee';
		// $page_data['page_name']    = 'reporting_heads';
		// $page_data['page_title']   = 'Reporting Heads'

		$this->load->view('employee/reporting_heads/reporting_heads', $page_data);
	}

	function reporting_head_post()
	{
		if (!check_login('1')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('signin', 'refresh');
		}
		if (isset($_POST['submit'])) {
			// pr($_POST);
			// exit;
			// pr($_FILES);exit;
			$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required|record_exist[employee.employee_id]');
			$this->form_validation->set_rules('reporting_manager_id', 'Reporting Manager', 'trim|required|differs[employee_id]|record_exist[employee.employee_id]');
			$employee = $this->employee_model->get_employees("e.*", array('e.employee_id' => $_POST['employee_id']))->row_array();
			if (!empty($employee)) {
				if ($employee['probation_status'] == 0) {
					$this->form_validation->set_rules('special_role', 'Special Role', 'trim|required');
				}
			}
			$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
			if ($this->form_validation->run()) {
				$data = $this->input->post(NULL, true);
				// 	pr($data);
				// exit;
				$employee = $this->employee_model->get_employees("e.*", array('e.employee_id' => $data['employee_id']))->row_array();
				if (!empty($employee)) {
					$update['reporting_manager_id'] 		= $data['reporting_manager_id'];
					$update['special_role'] 				= ($employee['probation_status'] == 0) ? $data['special_role'] : NULL;

					$resp = $this->common_model->update($update, array('employee_id' => $employee['employee_id']), 'employee');

					if (!empty($resp)) {
						$response = array('status' => '1', 'msg' => 'Data Updated!');
					} else {
						$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
					}
				} else {
					$response = array('status' => '0', 'msg' => 'Something Went Wrong!');
				}
			} else {
				// echo $this->db->last_query();exit;
				$response = array('status' => '0', 'msg' => validation_errors());
			}
			echo json_encode($response);
			exit;
		}
	}

	function leave_approval_order($employee_id)
	{

		if (!check_login('1')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('signin', 'refresh');
		}
		$page_data['page_type']    = 'employee/leave';
		$page_data['page_name']    = 'leave_approval_order';
		$page_data['menu']         = 'employee';
		$page_data['page_title']   = 'Leave Approval Order';
		$page_data['employee_id']  = $employee_id;
		$page_data['employees']  =  $this->employee_model->get_employees("e.employee_id,e.name,e.code", array('e.employee_id!=' => $employee_id, 'e.probation_status' => '0'))->result_array();

		$select = 'ao.*,e.name employee,roles.name employee_role,h.name head,hroles.name head_role';
		$where['ao.employee_id'] = $employee_id;
		$join = "e,roles,h,hroles";
		$order_by = 'ao.position ASC';
		$orders = $this->employee_model->get_leave_approval_order($select, $where, array('join' => $join, 'order_by' => $order_by))->result_array();

		$page_data['order']  = $orders;
		$this->load->view('employee/leave/leave_approval_order', $page_data);
	}

	function arrange_leave_approval_order()
	{

		if (!check_login('1')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('signin', 'refresh');
		}

		$data = $this->input->post('data');
		$data = json_decode($data, true);
		// pr($data);
		// exit;
		if (!empty($data)) {

			$count = 0;
			foreach ($data as $key => $value) {
				$update['position'] = $key + 1;

				$where['employee_id'] 		= $value['employee_id'];
				$where['head_id'] 			= $value['head_id'];
				$where['approval_order_id'] = $value['approval_order_id'];
				$resp = $this->common_model->update($update, $where, 'leave_approval_order');
				if (!empty($resp)) {
					$count++;
				}
			}

			if ($count > 0) {
				$response = array('status' => 1, 'msg' => 'Approval order changed!');
			} else {
				$response = array('status' => 0, 'msg' => 'Approval order not changed!');
			}
		} else {
			$response = array('status' => 0, 'msg' => 'Something went wrong!');
		}
		echo json_encode($response);
		exit;
	}

	function leave_approval_order_process()
	{

		if (!check_login('1')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('signin', 'refresh');
		}
		$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required|record_exist[employee.employee_id]');
		$this->form_validation->set_rules('head_id', 'Head', 'trim|required|differs[employee_id]|record_exist[employee.employee_id]');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);
			$select = 'ao.*';
			$where['ao.employee_id'] = $data['employee_id'];
			$exist = $this->employee_model->get_leave_approval_order($select, $where)->result_array();
			if (count($exist) < 2) {
				$exist_heads = array_column($exist, 'head_id');
				if (!in_array($data['head_id'], $exist_heads)) {

					$max_position = $this->db->select_max("position")->where(array('employee_id' => $data['employee_id']))->get('leave_approval_order')->row_array();
					$head = $this->common_model->selectOne('employee', array('employee_id' => $data['employee_id']), 'role_id');

					$insert['employee_id'] 	= $data['employee_id'];
					$insert['head_id'] 		= $data['head_id'];
					$insert['role_id'] 		= $head['role_id'];
					$insert['position'] 	= $max_position['position'] + 1;

					$id = $this->common_model->insert($this->security->xss_clean($insert), 'leave_approval_order');
					if ($id) {
						$response = array('status' => '1', 'msg' => 'Data Saved!');
					} else {
						$response = array('status' => '0', 'msg' => 'Something Went Wrong!');
					}
				} else {
					$response = array('status' => '0', 'msg' => 'Record already exist!');
				}
			} else {
				$response = array('status' => '0', 'msg' => 'Limit reached!');
			}
		} else {
			$response = array('status' => '0', 'msg' => validation_errors());
		}
		echo json_encode($response);
		exit;
	}

	function delete_leave_approval_order_process()
	{
		if (!check_login('1')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('signin', 'refresh');
		}
		$this->form_validation->set_rules('approval_order_id', 'Order id', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {
			$data = $this->input->post(NULL, true);
			$exist = $this->common_model->selectOne('leave_approval_order', array('approval_order_id' => $data['approval_order_id']), '*');

			if (!empty($exist)) {

				$this->db->delete('leave_approval_order', array('approval_order_id' => $data['approval_order_id']));
				if ($this->db->affected_rows() > 0) {
					$response = array('status' => '1', 'msg' => 'Data Deleted!');
				} else {
					$response = array('status' => '0', 'msg' => 'Something Went Wrong!');
				}
			} else {
				$response = array('status' => '0', 'msg' => 'Record does not exist!');
			}
		} else {
			$response = array('status' => '0', 'msg' => 'Something Went Wrong!');
		}
		echo json_encode($response);
		exit;
	}
	function view_employee_life_cycle_docs($employee_id)
	{
		if (!check_login('1')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('signin', 'refresh');
		}
		$page_data['employee']  =  $this->employee_model->get_employees("e.*", array('employee_id' => $employee_id))->row_array();
		if (empty($page_data['employee'])) {
			exit;
		}
		$this->load->view('employee/employee_life_cycle_docs/view_employee_life_cycle_docs', $page_data);
	}

	function view_employee_life_cycle_docs_ajax()
	{
		$filters = $this->input->post();
		if (!empty($filters['employee_id'])) {
			$where['elcd.employee_id'] = $filters['employee_id'];
		}

		$select = 'elcd.*';
		$where['1'] = "1";
		$join = "";
		$order_by = 'elcd.id DESC';
		$all = $this->employee_model->get_employee_life_cycle_docs($select, $where, array('join' => $join, 'order_by' => $order_by))->result_array();
		$data['data'] = [];
		// echo $this->db->last_query();exit;
		// pr($all);
		// exit;

		if (!empty($all)) {

			foreach ($all as $key => $value) {
				$data['data'][$key]['sno'] 					= $key + 1;
				$data['data'][$key]['type'] 					= $value['type'] == 1 ? "Appreciation Letters" : "Reprimanding Letters";
				$data['data'][$key]['doc']					= '<a href="' . base_url() . '/assets/uploads/employee/employee_life_cycle_docs/' . $value['employee_id'] . '/' . $value['doc'] . '" class="btn-link" title="View" target="_blank">' . $value['doc'] . '</a>';

				$html = '<div class="btn-group">';
				$html .= '<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
				$html .= '<div class="dropdown-menu dropdown-menu-right">';
				$html .= '  <a class="dropdown-item" href="' . base_url() . '/assets/uploads/employee/employee_life_cycle_docs/' . $value['employee_id'] . '/' . $value['doc'] .  '" download>Download</a>';
				$html .= '  <button class="dropdown-item" type="button" onclick="delete_employee_life_cycle_doc(\'' . $value['id'] . '\')">Delete</button>';
				$html .= '</div>';
				$html .= '</div>';
				$data['data'][$key]['action']			= $html;
			}
		}
		echo json_encode($data);
	}

	function add_employee_life_cycle_doc($employee_id)
	{
		if (!check_login('1')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('signin', 'refresh');
		}
		// employee
		$page_data['employee']  =  $this->employee_model->get_employees("e.*", array('employee_id' => $employee_id))->row_array();
		if (empty($page_data['employee'])) {
			exit;
		}
		$this->load->view('employee/employee_life_cycle_docs/add_employee_life_cycle_doc', $page_data);
	}

	function add_employee_life_cycle_doc_post()
	{
		if (!check_login('1')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('signin', 'refresh');
		}
		if (isset($_POST['submit'])) {
			// pr($_FILES);
			// exit;
			$this->form_validation->set_rules('employee_id', 'employee_id', 'trim|required|record_exist[employee.employee_id]');
			$this->form_validation->set_rules('type', 'Type', 'trim|required');
			if (empty($_FILES['docs']['name'])) {
				$this->form_validation->set_rules('docs', 'Docs', 'trim|required');
			}
			$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
			if ($this->form_validation->run()) {
				$data = $this->input->post(NULL, true);

				$file_upload_path = 'assets/uploads/employee/employee_life_cycle_docs/' . $data['employee_id'];
				$oldmask = umask(0);
				if (!is_dir($file_upload_path)) {
					mkdir($file_upload_path, 0777, true);
				}
				umask($oldmask);
				if (is_writable(($file_upload_path))) {
					if (!file_exists($file_upload_path . '/index.html')) {
						file_put_contents($file_upload_path . '/index.html', '');
					}

					if (!empty($_FILES['docs']['name']) && is_array($_FILES['docs']['name'])) {
						$inserted  = 0;
						//pr($_FILES);
						foreach ($_FILES['docs']['name'] as $key => $doc) {
							if (!empty($_FILES['docs']['name'][$key])) {
								//echo 'hfghfhfgh';
								$_FILES['file']['name']     = $_FILES['docs']['name'][$key];
								$_FILES['file']['type']     = $_FILES['docs']['type'][$key];
								$_FILES['file']['tmp_name'] = $_FILES['docs']['tmp_name'][$key];
								$_FILES['file']['error']     = $_FILES['docs']['error'][$key];
								$_FILES['file']['size']     = $_FILES['docs']['size'][$key];

								$config['upload_path']          = $file_upload_path;
								$config['allowed_types']        = 'jpg|png|jpeg|pdf';
								// $config['max_size']             = 2048;
								$config['file_name']    		= clean_string($_FILES['file']['name']) . '_' . date('dmYhis') . '_' . uniqid() . "." . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
								$this->load->library('upload', $config);
								$this->upload->initialize($config);
								if ($this->upload->do_upload('file')) {

									$uploadedFileData = $this->upload->data();
									$fileName = $uploadedFileData['file_name'];
									$insert['employee_id'] = $data['employee_id'];
									$insert['type'] = $data['type'];
									$insert['doc'] = $fileName;
									$id = $this->common_model->insert($insert, 'employee_life_cycle_docs');
									if ($id) {
										$inserted++;
									}
								}
							}
						}
						if ($inserted > 0) {
							$response = array('status' => '1', 'msg' => 'Data Updated!');
						} else {
							$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
						}
					} else {
						$response = array('status' => '0', 'msg' => 'No changes were made to the data!');
					}
				} else {
					$response = array('status' => '0', 'msg' => 'File upload path is not writable!');
				}
			} else {
				// echo $this->db->last_query();exit;
				$response = array('status' => '0', 'msg' => validation_errors());
			}
			echo json_encode($response);
			exit;
		}
	}

	function delete_employee_life_cycle_doc_post()
	{
		if (!check_login('1')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('signin', 'refresh');
		}
		$this->form_validation->set_rules('id', 'Doc Id', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {
			$data = $this->input->post(NULL, true);
			$select = 'elcd.*';
			$where['elcd.id'] = $data['id'];
			$join = "";
			$order_by = 'elcd.id DESC';
			$exist = $this->employee_model->get_employee_life_cycle_docs($select, $where, array('join' => $join, 'order_by' => $order_by))->row_array();

			if (!empty($exist)) {

				$this->db->delete('employee_life_cycle_docs', array('id' => $exist['id']));
				if ($this->db->affected_rows() > 0) {
					$file_upload_path = 'assets/uploads/employee/employee_life_cycle_docs/' . $exist['employee_id'];
					// unlink the old image and its resized versions
					$this->common_model->unlink_files($exist['doc'], $file_upload_path);
					$response = array('status' => '1', 'msg' => 'Data Deleted!');
				} else {
					$response = array('status' => '0', 'msg' => 'Something Went Wrong!');
				}
			} else {
				$response = array('status' => '0', 'msg' => 'Record does not exist!');
			}
		} else {
			$response = array('status' => '0', 'msg' => 'Something Went Wrong!');
		}
		echo json_encode($response);
		exit;
	}

	//added by sooraj 09012025
	function import_employees()
	{
		$this->load->model('tracker_model');

		$input = $this->input->post(NULL, true);
		$statements = $where = [];
		$select = "e.*";
		$statements['order_by'] = "id ASC";
		$employees = $this->tracker_model->get_employees($select, $where, $statements)->result_array();
		if (!empty($employees)) {
			$inserted = 0;
			$data = [];
			foreach ($employees as &$employee) {
				$exist = $this->db->get_where('employee', array('email' => $employee['email']))->row();
				if (!empty($exist)) {
					continue;
				}
				$insert = [];
				$insert['name'] = $employee['first_name'] . ' ' . $employee['last_name'];
				$insert['email'] = $employee['email'];
				$insert['department_id'] = $employee['department_id'];
				$insert['designation_id'] = $employee['designation_id'];
				$insert['gender'] = $employee['gender'];
				$insert['joining_date'] = $employee['joining_date'];
				$insert['mobile_number'] = $employee['phone'];
				$insert['country'] = "India";
				$insert['nationality'] = "Indian";
				$insert['status'] = 1;
				$insert['role_id'] = 2;
				$insert['reporting_manager'] = 0;
				$password = generateRandomString(6);
				$insert['password'] = md5($password);

				$id = $id = $this->common_model->insert($insert, 'employee');
				if (!empty($id)) {
					$key = sprintf("k%03d", $id);

					$query = "UPDATE employee SET code  = '" . $key . "' WHERE employee_id  =" . $id;
					$this->db->query($query);
					if($this->db->affected_rows() > 0){
						$emp = $this->db->get_where('employee', array('employee_id' => $id))->row_array();
						if ($emp) {
							$data[] = $emp;
						}
					}
					employeeRegister_mail($insert['email'], $insert['name'], $password ,$key);
					$inserted++;
				}
			}
			if (!empty($inserted)) {
				$response = array('status' => true, 'msg' => 'Employees imported successfully!','data'=>$data);
			} else {
				$response = array('status' => false, 'msg' => 'No employees to import!');
			}
		} else {
			$response = array('status' => false, 'msg' => 'No data found!');
		}
		echo json_encode($response);
		exit;
	}
}