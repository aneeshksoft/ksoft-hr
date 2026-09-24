<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		ini_set('MAX_EXECUTION_TIME', '-1');
		ini_set('max_input_vars', 10000);
	}

	function dashboard()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

		$page_data['page_type']    = 'user';
		$page_data['page_name']    = 'dashboard';
		$page_data['page_title']   = 'Dashboard';
		$page_data['menu']         = 'dashboard';
		$page_data['emp_count']    = $this->common_model->get_count('employee', array('probation_status' => '0'));
		//$page_data['active']       = $this->common_model->get_count('employee', array('status' => 'active', 'probation_status' => '0'));
		//$page_data['inactive']       = $this->common_model->get_count('employee', array('status' => 'inactive', 'probation_status' => '0'));
		$page_data['leave']       = $this->common_model->get_count('leave_application', array('status' => 'approved', 'cancel_status' => '0', 'leave_date' => date('Y-m-d')));

		$employee_id = $this->session->userdata('employee_id');
		$page_data['employee'] = $this->db->select('name')->get_where('employee', array('employee_id' => $employee_id))->row_array();
		//$page_data['resigned']       = $this->common_model->get_count('employee', array('status' => 'resigned', 'probation_status' => '0'));
		
		$curDate = date('Y-m-d');

		//$curDate = '2025-04-01';

		$year = date('Y', strtotime($curDate));
		$month = date('m', strtotime($curDate));
		$newStartDate = date('Y-m-01', strtotime("$year-$month-01"));
		$newEndDate = date('Y-m-t', strtotime("$year-$month-01"));
		$leave_details = $this->db->select('pl_balance,sl_balance,co')->get_where("employee_leave_details", array('start_date' => $newStartDate, 'end_date' => $newEndDate, 'employee_id' => $employee_id))->row_array();

        //pr($leave_details);
		//echo($this->db->last_query());
		// exit;

		$pqry = "SELECT COALESCE(SUM(CASE WHEN leave_type_id = 1 THEN (CASE WHEN is_halfday = '1' THEN 0.5 ELSE 1 END) ELSE 0 END), 0) AS pl_pending, COALESCE(SUM(CASE WHEN leave_type_id = 3 THEN (CASE WHEN is_halfday = '1' THEN 0.5 ELSE 1 END) ELSE 0 END), 0) AS sl_pending, COALESCE(SUM(CASE WHEN leave_type_id = 4 THEN (CASE WHEN is_halfday = '1' THEN 0.5 ELSE 1 END) ELSE 0 END), 0) AS co_pending FROM leave_application WHERE status = 'pending' AND cancel_status = '0' AND employee_id = ?";
		$pres = $this->db->query($pqry, $employee_id)->row_array();

		//pr($pres);

		$page_data['available_leaves'] = [
			[
				'type' => 'PL',
				'balance' => (($leave_details['pl_balance'] - $pres['pl_pending']) < 0) ? 0 : ($leave_details['pl_balance'] - $pres['pl_pending']),
				'color' => '#22af46',
				'bgcolor' => '#DDDBFF'
			],
			[
				'type' => 'SL',
				'balance' => (($leave_details['sl_balance'] - $pres['sl_pending']) < 0) ? 0 : ($leave_details['sl_balance'] - $pres['sl_pending']),
				'color' => '#de4848',
				'bgcolor' => '#ffccdb'
			],
			[
				'type' => 'CO',
				'balance' => (($leave_details['co'] - $pres['co_pending']) < 0) ? 0 : ($leave_details['co'] - $pres['co_pending']),
				'color' => '#3C89DA',
				'bgcolor' => '#a6f7f5'
			]
		];

		//pr($page_data['available_leaves']);

		$all = $this->leave_model->leave_report_list($employee_id, $newStartDate, $newEndDate);
		$page_data['pending_approvals']       = $this->common_model->get_count('leave_application_status', array('status' => 'pending', 'head_id' => $employee_id, 'created_at >=' => $newStartDate));
		
		$page_data['pending']       = $this->common_model->get_count('leave_application', array('status' => 'pending', 'employee_id' => $employee_id, 'created_at >=' => $newStartDate));
		$page_data['approved']       = $this->common_model->get_count('leave_application', array('status' => 'approved', 'employee_id' => $employee_id, 'created_at >=' => $newStartDate));
		$page_data['rejected']       = $this->common_model->get_count('leave_application', array('status' => 'rejected', 'employee_id' => $employee_id, 'created_at >=' => $newStartDate));
		// Initialize leave summary with colors

		$leaves_summary = [
			'PL' => ['total' => 0, 'color' => '#22af46'], // Green
			'ML' => ['total' => 0, 'color' => '#3C89DA'], // Blue
			'SL' => ['total' => 0, 'color' => '#de4848'], // Red
			'CO' => ['total' => 0, 'color' => '#FFA500'], // Orange
			'OD' => ['total' => 0, 'color' => '#800080'], // Purple
			'LOP' => ['total' => 0, 'color' => '#FF4500'] // Dark Orange
		];

		// Process the leaves data
		if (!empty($all) && isset($all[0]['leave_history'])) {
			foreach ($all[0]['leave_history'] as $leave) {
				$leave_type = $leave['leave_type'];
				$total_leaves = (float) $leave['total_leaves'];

				// Accumulate total leaves for each type
				if (isset($leaves_summary[$leave_type])) {
					$leaves_summary[$leave_type]['total'] += $total_leaves;
				}
			}
		}

		// Extract data for the chart
		$series = array_column($leaves_summary, 'total'); // Get totals
		$labels = array_keys($leaves_summary);           // Get leave types
		$colors = array_column($leaves_summary, 'color'); // Get colors

		// Pass the data to the view
		$page_data['chart_data'] = [
			'series' => $series,
			'labels' => $labels,
			'colors' => $colors,
		];

		$this->load->view('theme/user/main', $page_data);
	}

	function employees_list($status = "")
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		if (!check_permission('1', 'v')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}
		$page_data['status']       = $status;
		$page_data['page_type']    = 'user';
		$page_data['page_name']    = 'employees_list';
		$page_data['menu']         = 'employee';
		$page_data['page_title']   = 'Employees List';
		$this->load->view('theme/user/main', $page_data);
	}

	function employees_ajax()
	{

		$status = $this->input->post('status');
		$all = $this->common_model->employees_list($status);
		$employees['data'] = [];
		if (!empty($all)) {

			foreach ($all as $key => $value) {

				$employees['data'][$key]['profile_photo'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><img src="' . getUserImage($value['profile_photo']) . '" class="rounded-circle avatar" alt="' . $value['name'] . '"></a>';
				$employees['data'][$key]['name'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><h6 class="mb-0">' . $value['name'] . '</h6><span>' . $value['email'] . '</span></a>';
				$employees['data'][$key]['code'] = $value['code'];
				$employees['data'][$key]['designation'] = '<div><strong>' . $value['designation'] . '</strong></div><span>' . $value['department'] . '</span>';
				$employees['data'][$key]['phone'] = ($value['phone_current'] != "") ? $value['phone_current'] : '-';
				$employees['data'][$key]['status'] = employee_status_c($value['status']);
				$employees['data'][$key]['action'] = ((check_permission('1', 'e')) ? '<a href="' . base_url('employee-edit/' . $value['code']) . '" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fa fa-edit"></i></a>' : '') . ((check_permission('1', 'd')) ? '&nbsp;<button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteEmployee(\'' . $value['employee_id'] . '\',\'' . $value['code'] . '\')"><i class="fa fa-trash-o"></i></button>' : '');
			}
		}
		echo json_encode($employees);
	}

	function employee_add()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('1', 'a')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'employee_add';
		$page_data['page_title']   = 'Add New';
		$page_data['departments']  = $this->common_model->selectAll('departments', '', '');
		$page_data['designations'] = $this->common_model->selectAll('designations', '', '');
		$page_data['roles']  = $this->common_model->selectAll('roles', '', '');
		$this->load->view('theme/user/main', $page_data);
	}

	function employee_edit($code)
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		if (!check_permission('1', 'e')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'employee_edit';
		$page_data['page_title']   = 'Edit Employee';
		$page_data['departments']  = $this->common_model->selectAll('departments', '', '');
		$page_data['designations'] = $this->common_model->selectAll('designations', '', '');
		$page_data['roles']  = $this->common_model->selectAll('roles', '', '');
		$page_data['employee_details'] = $this->common_model->employees_by_code($code);
		$this->load->view('theme/user/main', $page_data);
	}

	function employee_add_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('code', 'Code', 'trim|required|is_unique[employee.code]');
		$this->form_validation->set_rules('date_of_join', 'Date of join', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		$this->form_validation->set_rules('department_id', 'Department', 'trim|required');
		$this->form_validation->set_rules('designation_id', 'Designation', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|is_unique[employee.email]');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);
			$data['name'] = ucfirst($data['name']);
			$data['code'] = strtoupper($data['code']);
			$data['password'] = md5($data['password']);
			$data['created_by'] = $this->session->userdata('employee_id');
			$data['created_at'] = date('Y-m-d H:i:s');
			$roles = $data['role_id'];
			unset($data['role_id']);
			$employee_id = $this->common_model->insert($data, 'employee');
			if (!empty($employee_id)) {

				//insert to role
				if (!empty($roles)) {
					foreach ($roles as $row2) {
						$this->common_model->insert(array('employee_id' => $employee_id, 'role_id' => $row2), 'user_roles');
					}
				}

				if ($_FILES['file_name']['error'] != 4) {

					$oldmask = umask(0);
					if (!is_dir('assets/uploads/user_pic')) {
						mkdir('assets/uploads/user_pic', 0777, true);
						if (!file_exists('assets/uploads/user_pic/index.html')) {
							file_put_contents('assets/uploads/user_pic/index.html', '');
						}
					}
					umask($oldmask);

					$image = date('dmYhis') . '_' . rand(0, 99999) . "." . pathinfo($_FILES['file_name']['name'], PATHINFO_EXTENSION);
					$config['upload_path']   =  'assets/uploads/user_pic/';
					$config['allowed_types'] = 'jpg|png|jpeg';
					$config['file_name']     = $image;
					$this->load->library('upload', $config);
					if ($this->upload->do_upload('file_name')) {

						//resize the uploaded
						image_resize('assets/uploads/user_pic/' . $image, '140', '140', FALSE);

						$data_update = array(
							'profile_photo'  => $image,
						);
						$data_update = $this->security->xss_clean($data_update);
						$this->common_model->update($data_update, array('employee_id' => $employee_id), 'employee');
					}
				}

				$response = array('status' => 1, 'code' => $data['code'], 'msg' => 'Registered successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function employee_import()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('1', 'a')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}
		//$page_data['csvdata']  = array();
		if (isset($_POST['submit'])) {

			$ext = pathinfo($_FILES["uploadFile"]["name"])['extension'];
			$fileName = $_FILES["uploadFile"]["tmp_name"];
			if ($_FILES["uploadFile"]["size"] > 0 && ($ext == 'csv' || $ext == 'CSV')) {

				$file = fopen($fileName, "r");
				$i = 0;
				$j = 0;
				$dataArray = array();
				while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {

					if ($i++ == 0) {
						continue;
					}

					$dataArray[] = array(
						"code" => trim($column[0]),
						"password" => trim($column[1]),
						"name" => trim($column[2]),
						"designation" => trim($column[3]),
						"department" => trim($column[4]),
						'date_of_birth' => str_replace("-", "/", trim($column[5])),
						'age' => trim($column[6]),
						"date_of_join" => str_replace("-", "/", trim($column[7])),
						'last_work_date' => trim($column[8]),
						'current_address' => trim($column[9]),
						'permanent_address' => trim($column[10]),
						'phone_office' => trim($column[11]),
						'phone_current' => trim($column[12]),
						'email' => trim($column[13]),
						'personal_email' => trim($column[14]),
						'country' => trim($column[15]),
						'overtime' => trim($column[16]),
						'passport_number' => trim($column[17]),
						'passport_expiry' => str_replace("-", "/", trim($column[18])),
						'passport_with' => trim($column[19]),
						'visa_number' => trim($column[20]),
						'visa_expiry' => str_replace("-", "/", trim($column[21])),
						'labour_number' => trim($column[22]),
						'labour_expiry' => str_replace("-", "/", trim($column[23])),
						'emirate_number' => trim($column[24]),
						'emirate_expiry' => str_replace("-", "/", trim($column[25])),
						'unified_no' => trim($column[26]),
						'nationality' => trim($column[27]),
						'basic_pay' => trim($column[28]),
						'hra' => trim($column[29]),
						'transport' => trim($column[30]),
						'special_allowance' => trim($column[31]),
						'others' => trim($column[32]),
						'insurance_company' => trim($column[33]),
						'insurance_cost' => trim($column[34]),
						'insurance_expiry' => str_replace("-", "/", trim($column[35])),
						'bank_name' => trim($column[36]),
						'account_number' => trim($column[37]),
						'iban_number' => trim($column[38]),
						"status" => trim($column[39])
					);
				}

				$page_data['csvdata']  = $dataArray;
			}
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'employee_import';
		$page_data['page_title']   = 'Bulk Import';
		$page_data['departments']  = $this->common_model->selectAll('departments', '', '');
		$page_data['designations'] = $this->common_model->selectAll('designations', '', '');
		$page_data['countries'] = get_countries();
		$page_data['roles']  = $this->common_model->selectAll('roles', '', '');
		$this->load->view('theme/user/main', $page_data);
	}

	function employee_import_process()
	{

		//pr($_POST);

		if (isset($_POST['employeesubmit'])) {

			$this->form_validation->set_rules('code[]', 'Code', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password[]', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('ids[]', 'Ids', 'trim|required|xss_clean');
			$this->form_validation->set_rules('name[]', 'Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('designation_id[]', 'Designation', 'trim|required|xss_clean');
			$this->form_validation->set_rules('department_id[]', 'Department', 'trim|required|xss_clean');
			$this->form_validation->set_rules('date_of_join[]', 'Date of join', 'trim|required|xss_clean');
			$this->form_validation->set_rules('status[]', 'Status', 'trim|xss_clean');

			if ($this->form_validation->run()) {

				$data = $this->input->post(NULL, true);
				//pr($data);
				$employeearray = array();
				$errorArray = array();
				if (!empty($data['code'])) {

					foreach ($data['code'] as $key => $value) {

						$param = array(
							"code" => $data['code'][$key],
							"password" => md5($data['password'][$key]),
							"name" => $data['name'][$key],
							"designation_id" => $data['designation_id'][$key],
							"department_id" => $data['department_id'][$key],
							'date_of_birth' => $data['date_of_birth'][$key],
							'age' => $data['age'][$key],
							'date_of_join' => $data['date_of_join'][$key],
							'last_work_date' => $data['last_work_date'][$key],
							'current_address' => $data['current_address'][$key],
							'permanent_address' => $data['permanent_address'][$key],
							'phone_office' => $data['phone_office'][$key],
							'phone_current' => $data['phone_current'][$key],
							'email' => $data['email'][$key],
							'personal_email' => $data['personal_email'][$key],
							'country' => $data['country'][$key],
							'overtime' => $data['overtime'][$key],
							'passport_number' => $data['passport_number'][$key],
							'passport_expiry' => $data['passport_expiry'][$key],
							'passport_with' => $data['passport_with'][$key],
							'visa_number' => $data['visa_number'][$key],
							'visa_expiry' => $data['visa_expiry'][$key],
							'labour_number' => $data['labour_number'][$key],
							'labour_expiry' => $data['labour_expiry'][$key],
							'emirate_number' => $data['emirate_number'][$key],
							'emirate_expiry' => $data['emirate_expiry'][$key],
							'unified_no' => $data['unified_no'][$key],
							'nationality' => $data['nationality'][$key],
							'basic_pay' => $data['basic_pay'][$key],
							'hra' => $data['hra'][$key],
							'transport' => $data['transport'][$key],
							'special_allowance' => $data['special_allowance'][$key],
							'others' => $data['others'][$key],
							"status" => $data['status'][$key]
						);

						$bank = array(
							'bank_name' => $data['bank_name'][$key],
							'account_number' => $data['account_number'][$key],
							'iban_number' => $data['iban_number'][$key]
						);
						$insurance = array(
							'company' => $data['insurance_company'][$key],
							'cost' => $data['insurance_cost'][$key],
							'expiry' => set_date($data['insurance_expiry'][$key])
						);

						$exist = $this->common_model->selectOne('employee', array('code' => $param['code']), 'employee_id');
						if (!empty($exist)) {
							$errorArray[$key]['rid'] = $data['ids'][$key];
							$errorArray[$key]['msg'] = "Employee code exist!";
							$errorArray[$key]['status'] = 0;
						} else {

							$employee_id = $this->common_model->insert($param, 'employee');
							if (!empty($employee_id)) {

								if (!empty($bank) && !empty($bank['account_number'])) {
									$bank['employee_id'] = $employee_id;
									$bank_data = $this->common_model->insert($bank, 'bank');
								}

								if (!empty($insurance) && !empty($insurance['company'])) {
									$insurance['employee_id'] = $employee_id;
									$insurance_data = $this->common_model->insert($insurance, 'insurance');
								}

								$roles = $this->common_model->insert(array('employee_id' => $employee_id, 'role_id' => '1'), 'user_roles');

								if (!empty($roles)) {
									$errorArray[$key]['rid'] = $data['ids'][$key];
									$errorArray[$key]['msg'] = "Employee details saved!";
									$errorArray[$key]['status'] = 1;
								} else {
									$errorArray[$key]['rid'] = $data['ids'][$key];
									$errorArray[$key]['msg'] = "Permission not saved!";
									$errorArray[$key]['status'] = 0;
								}
							} else {
								$errorArray[$key]['rid'] = $data['ids'][$key];
								$errorArray[$key]['msg'] = "Employee details not saved!";
								$errorArray[$key]['status'] = 0;
							}
						}
					}
				}

				$response = array('status' => 1, 'data' => $errorArray, 'msg' => 'Saved!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => validation_errors());
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => 'Something went wrong!');
			echo json_encode($response);
			exit;
		}
	}

	function delete_pro_pic()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('1', 'e,d')) {
			$response = array('status' => 0, 'msg' => 'Permission denied!');
			echo json_encode($response);
			exit;
		}

		$code  = $this->input->post('code');
		$exist = $this->db->get_where('employee', array('code' => $code))->row_array();
		if (!empty($exist)) {
			//remove profile pic 
			if (!empty($exist['profile_photo'])) {
				if (file_exists('assets/uploads/user_pic/' . $exist['profile_photo'])) {
					unlink('assets/uploads/user_pic/' . $exist['profile_photo']);

					$this->db->where('code', $code);
					$this->db->update('employee', array('profile_photo' => NULL));

					$response = array('status' => 1, 'msg' => 'Removed successfully!');
					echo json_encode($response);
					exit;
				} else {
					$response = array('status' => 0, 'msg' => 'Something went wrong!');
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('status' => 0, 'msg' => 'Can\'t remove default photo!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => 'Employee details not exist!');
			echo json_encode($response);
			exit;
		}
	}

	function employee_edit_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('code', 'Code', 'trim|required');
		$this->form_validation->set_rules('date_of_join', 'Date of join', 'trim|required');
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		$this->form_validation->set_rules('department_id', 'Department', 'trim|required');
		$this->form_validation->set_rules('designation_id', 'Designation', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);
			$employee_id = $this->common_model->employees_id_by_code($data['code']);

			if (empty($employee_id)) {
				$response = array('status' => 0, 'msg' => 'Invalid employee details!');
				echo json_encode($response);
				exit;
			}

			$data['name'] = ucfirst($data['name']);
			$data['updated_by'] = $this->session->userdata('employee_id');
			$data['updated_at'] = date('Y-m-d H:i:s');
			$roles = $data['role_id'];
			$code  = $data['code'];
			unset($data['role_id']);
			unset($data['code']);

			$user_update = $this->security->xss_clean($data);
			$update_resp = $this->common_model->update($user_update, array('code' => $code, 'employee_id' => $employee_id), 'employee');

			if ($update_resp) {
				$response = array('status' => 1, 'msg' => 'General details updated!');
				echo json_encode($response);
			}

			if (!empty($roles)) {

				$arr = implode(',', $roles);
				foreach ($roles as $row2) {
					$rexist = $this->db->get_where('user_roles', array('employee_id' => $employee_id, 'role_id' => $row2))->row_array();
					if (empty($rexist)) {
						$this->common_model->insert(array('employee_id' => $employee_id, 'role_id' => $row2), 'user_roles');
					}
				}
				//delete employee roles which is not in selected
				$qry = "delete from user_roles where employee_id = ? and role_id not in (" . $arr . ")";
				$this->db->query($qry, $employee_id);
			}

			if ($_FILES['file_name']['error'] != 4) {

				$oldmask = umask(0);
				if (!is_dir('assets/uploads/user_pic')) {
					mkdir('assets/uploads/user_pic', 0777, true);
					if (!file_exists('assets/uploads/user_pic/index.html')) {
						file_put_contents('assets/uploads/user_pic/index.html', '');
					}
				}
				umask($oldmask);

				$image = date('dmYhis') . '_' . rand(0, 99999) . "." . pathinfo($_FILES['file_name']['name'], PATHINFO_EXTENSION);
				$config['upload_path']   =  'assets/uploads/user_pic/';
				$config['allowed_types'] = 'jpg|png|jpeg';
				$config['file_name']     = $image;
				$this->load->library('upload', $config);
				if ($this->upload->do_upload('file_name')) {

					//resize the uploaded
					image_resize('assets/uploads/user_pic/' . $image, '140', '140', FALSE);

					//delete old pic
					$old_pic = $this->db->select('profile_photo')->get_where('employee', array('employee_id' => $employee_id))->row_array();
					if (!empty($old_pic['profile_photo'])) {
						if (file_exists('assets/uploads/user_pic/' . $old_pic['profile_photo'])) {
							unlink('assets/uploads/user_pic/' . $old_pic['profile_photo']);
						}
					}

					//update new pic
					$data_update = array(
						'profile_photo'  => $image,
					);
					$data_update = $this->security->xss_clean($data_update);
					$this->common_model->update($data_update, array('employee_id' => $employee_id), 'employee');
				}
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function employee_additional($code)
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('1', 'a,e,d')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'employee_additional';
		$page_data['page_title']   = 'Additional Details';
		$page_data['employee_details'] = $this->common_model->employees_by_code($code);
		$page_data['qualifications']  = $this->common_model->selectAll('qualifications', '', '');
		$this->load->view('theme/user/main', $page_data);
	}

	function employee_edit_additional($code)
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'employee_edit_additional';
		$page_data['page_title']   = 'Edit Additional Details';
		$page_data['employee_details'] = $this->common_model->employees_by_code($code);
		$page_data['qualifications']  = $this->common_model->selectAll('qualifications', '', '');
		$this->load->view('theme/user/main', $page_data);
	}

	function employee_additional_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('employee_id', 'Employee id', 'trim|required');
		$this->form_validation->set_rules('code', 'Employee code', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');

		if ($this->form_validation->run()) {

			$data1 = $this->input->post(NULL, true);

			$params = array(
				'passport_number' => ($data1['passport_number'] != "") ? $data1['passport_number'] : NULL,
				'passport_expiry' => ($data1['passport_expiry'] != "") ? $data1['passport_expiry'] : NULL,
				'passport_with' => ($data1['passport_with'] != "") ? $data1['passport_with'] : NULL,
				'passport_with_remarks' => ($data1['passport_with_remarks'] != "") ? $data1['passport_with_remarks'] : NULL,
				'visa_number' => ($data1['visa_number'] != "") ? $data1['visa_number'] : NULL,
				'visa_expiry' => ($data1['visa_expiry'] != "") ? $data1['visa_expiry'] : NULL,
				'labour_number' => ($data1['labour_number'] != "") ? $data1['labour_number'] : NULL,
				'labour_expiry' => ($data1['labour_expiry'] != "") ? $data1['labour_expiry'] : NULL,
				'emirate_number' => ($data1['emirate_number'] != "") ? $data1['emirate_number'] : NULL,
				'emirate_expiry' => ($data1['emirate_expiry'] != "") ? $data1['emirate_expiry'] : NULL,
				'unified_no' => ($data1['unified_no'] != "") ? $data1['unified_no'] : NULL
			);

			//remove empty details
			//    foreach($params as $key=>$value) {
			//		if(empty($value)) {
			//			unset($params[$key]);
			//		}
			//	}

			if (!empty($params)) {

				$update_status = $this->common_model->update($params, array('employee_id' => $data1['employee_id'], 'code' => $data1['code']), 'employee');

				//upload corresponding docs
				$oldmask = umask(0);
				if (!is_dir('assets/uploads/user_docs/' . $data1['employee_id'])) {
					mkdir('assets/uploads/user_docs/' . $data1['employee_id'], 0777, true);
					if (!file_exists('assets/uploads/user_docs/' . $data1['employee_id'] . '/index.html')) {
						file_put_contents('assets/uploads/user_docs/' . $data1['employee_id'] . '/index.html', '');
					}
				}
				umask($oldmask);

				$files = $_FILES;
				//upload passport image				
				$this->uploadDocs($files, 'passport', count($_FILES['passport_images']['name']), 'assets/uploads/user_docs/' . $data1['employee_id'], $data1['employee_id']);
				$this->uploadDocs($files, 'visa', count($_FILES['visa_images']['name']), 'assets/uploads/user_docs/' . $data1['employee_id'], $data1['employee_id']);
				$this->uploadDocs($files, 'labour', count($_FILES['labour_images']['name']), 'assets/uploads/user_docs/' . $data1['employee_id'], $data1['employee_id']);
				$this->uploadDocs($files, 'emirate', count($_FILES['emirate_images']['name']), 'assets/uploads/user_docs/' . $data1['employee_id'], $data1['employee_id']);

				$response = array('status' => 1, 'msg' => 'Saved successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Nothing added!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function uploadDocs($files, $type, $count, $path, $employee_id)
	{

		for ($i = 0; $i < $count; $i++) {

			$_FILES['' . $type . '_images']['name'] = $files['' . $type . '_images']['name'][$i];
			$_FILES['' . $type . '_images']['type'] = $files['' . $type . '_images']['type'][$i];
			$_FILES['' . $type . '_images']['tmp_name'] = $files['' . $type . '_images']['tmp_name'][$i];
			$_FILES['' . $type . '_images']['error'] = $files['' . $type . '_images']['error'][$i];
			$_FILES['' . $type . '_images']['size'] = $files['' . $type . '_images']['size'][$i];

			$image = date('dmYhis') . '_' . rand(0, 99999) . "." . pathinfo($_FILES['' . $type . '_images']['name'], PATHINFO_EXTENSION);

			$config['upload_path'] = $path;
			$config['allowed_types'] = '*';
			$config['file_name']   = $image;

			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload('' . $type . '_images')) {

				//resize the uploaded
				image_resize($path . '/' . $image, '1000', '1000', TRUE);

				$data = array(
					'employee_id' => $employee_id,
					'type' => $type,
					'doc_path' => $image,
					'created_at' => date('Y-m-d H:i:s')

				);
				$data = $this->security->xss_clean($data);
				$this->common_model->insert($data, 'employee_docs');
			}
		}
	}

	function removeDocs()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$type  = $this->input->post('type');
		$docs_id  = $this->input->post('docs_id');
		$path  = $this->input->post('path');
		$employee_id  = $this->input->post('employee_id');
		$exist = $this->db->get_where($type . '_docs', array($type . '_docs_id' => $docs_id))->row_array();

		if (!empty($exist)) {
			//remove profile pic 
			if (!empty($exist['doc_path'])) {

				if (!empty($path)) {
					$fpath = 'assets/uploads/user_docs/' . $employee_id . '/' . $path . '/' . $exist['doc_path'];
				} else {
					$fpath = 'assets/uploads/user_docs/' . $employee_id . '/' . $exist['doc_path'];
				}

				if (file_exists($fpath)) {
					unlink($fpath);

					$this->db->delete($type . '_docs', array($type . '_docs_id' => $docs_id));

					$response = array('status' => 1, 'msg' => 'Removed successfully!');
					echo json_encode($response);
					exit;
				} else {
					$response = array('status' => 0, 'msg' => 'Something went wrong!');
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('status' => 0, 'msg' => 'Can\'t remove document!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => 'Document does not exist!');
			echo json_encode($response);
			exit;
		}
	}

	function deleteAdditional()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$type  = $this->input->post('type');
		$id  = $this->input->post('id');
		$exist = $this->db->get_where($type, array($type . '_id' => $id))->row_array();

		if (!empty($exist)) {

			$get_docs = $this->db->get_where($type . "_docs", array($type . '_id' => $exist[$type . "_id"]))->result_array();
			//remove docs 
			if (!empty($get_docs)) {
				foreach ($get_docs as $row) {
					if (file_exists('assets/uploads/user_docs/' . $exist['employee_id'] . '/' . $type . '/' . $row['doc_path'])) {
						unlink('assets/uploads/user_docs/' . $exist['employee_id'] . '/' . $type . '/' . $row['doc_path']);
					}
				}
			}

			//delete main entry
			$resp = $this->db->delete($type, array($type . '_id' => $id));
			$response = array('status' => 1, 'msg' => 'Removed successfully!');
			echo json_encode($response);
			exit;
		} else {
			$response = array('status' => 0, 'msg' => 'Details does not exist!');
			echo json_encode($response);
			exit;
		}
	}

	function employee_education_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('employee_id', 'Employee id', 'trim|required');
		$this->form_validation->set_rules('qualification_id[]', 'Qualification', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);

			$education = array();

			if (!empty($data['qualification_id'])) {
				foreach ($data['qualification_id'] as $key => $value) {
					$education[$key]['qualification_id'] = $data['qualification_id'][$key];
					$education[$key]['year']    = ($data['year'][$key] != "") ? $data['year'][$key] : NULL;
					$education[$key]['institution']  = ($data['institution'][$key] != "") ? $data['institution'][$key] : NULL;
					$education[$key]['employee_id']    = $data['employee_id'];
					$education[$key]['education_id']    = $data['education_id'][$key];
					$education[$key]['created_at'] = date('Y-m-d H:i:s');
				}
			}

			//remove empty details
			$c = function ($v) {
				return array_filter($v) != array();
			};
			$education = array_filter($education, $c);

			$files = $_FILES;
			$resp = $this->common_model->save_education($education, $files);
			if (!empty($resp)) {
				$response = array('status' => 1, 'msg' => 'Saved successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function employee_insurance_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('employee_id', 'Employee id', 'trim|required');
		$this->form_validation->set_rules('company[]', 'Provider', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);

			$insurance = array();

			if (!empty($data['company'])) {
				foreach ($data['company'] as $key => $value) {
					$insurance[$key]['company'] = $data['company'][$key];
					$insurance[$key]['cost']    = ($data['cost'][$key] != "") ? $data['cost'][$key] : NULL;
					$insurance[$key]['expiry']  = ($data['expiry'][$key] != "") ? set_date($data['expiry'][$key]) : NULL;
					$insurance[$key]['employee_id']    = $data['employee_id'];
					$insurance[$key]['insurance_id']    = $data['insurance_id'][$key];
					$insurance[$key]['created_at'] = date('Y-m-d H:i:s');
				}
			}

			//remove empty details
			$c = function ($v) {
				return array_filter($v) != array();
			};
			$insurance = array_filter($insurance, $c);

			$files = $_FILES;
			$resp = $this->common_model->save_insurance($insurance, $files);
			if (!empty($resp)) {
				$response = array('status' => 1, 'msg' => 'Saved successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function employee_bank_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('employee_id', 'Employee id', 'trim|required');
		$this->form_validation->set_rules('bank_name[]', 'Bank name', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);

			$bank = array();

			if (!empty($data['bank_name'])) {
				foreach ($data['bank_name'] as $key => $value) {
					$bank[$key]['bank_name'] = $data['bank_name'][$key];
					$bank[$key]['account_number']    = ($data['account_number'][$key] != "") ? $data['account_number'][$key] : NULL;
					$bank[$key]['iban_number']  = ($data['iban_number'][$key] != "") ? $data['iban_number'][$key] : NULL;
					$bank[$key]['swift_code']  = ($data['swift_code'][$key] != "") ? $data['swift_code'][$key] : NULL;
					$bank[$key]['employee_id']    = $data['employee_id'];
					$bank[$key]['bank_id']    = $data['bank_id'][$key];
					$bank[$key]['created_at'] = date('Y-m-d H:i:s');
				}
			}

			//remove empty details
			$c = function ($v) {
				return array_filter($v) != array();
			};
			$bank = array_filter($bank, $c);

			$files = $_FILES;
			$resp = $this->common_model->save_bank($bank, $files);
			if (!empty($resp)) {
				$response = array('status' => 1, 'msg' => 'Saved successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function ajax_education()
	{
		$page_data['count'] = $this->input->post('count');
		$page_data['qualifications']  = $this->common_model->selectAll('qualifications', '', '');
		$this->load->view('user/ajax_education', $page_data);
	}
	function ajax_insurance()
	{
		$page_data['count'] = $this->input->post('count');
		$this->load->view('user/ajax_insurance', $page_data);
	}
	function ajax_bank()
	{
		$page_data['count'] = $this->input->post('count');
		$this->load->view('user/ajax_bank', $page_data);
	}

	function profile($code = "")
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (empty($code)) {
			$code = $this->session->userdata('code');
		}

		$employee_id = $this->common_model->employees_id_by_code($code);
		$session_id  = $this->session->userdata('employee_id');
		$check_head  = $this->common_model->check_in_heads_list($employee_id, $session_id);

		// if (empty($check_head) && !check_role_permission('5') && !check_role_permission('3') && ($employee_id !== $session_id)) {
		// 	$this->session->set_flashdata('error', 'Permission denied!');
		// 	redirect('dashboard', 'refresh');
		// }

		$page_data['session_id']   =  $session_id;
		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'profile';
		$page_data['page_title']   = 'Profile';
		$page_data['employee_details'] = $this->common_model->employees_by_code($code);
		$page_data['leave_annual_list']  = $this->common_model->leave_annual_list($employee_id);
		$page_data['leave_history_list']  = $this->common_model->leave_history_list($employee_id, "", "");
		$this->load->view('theme/user/main', $page_data);
	}

	function employee_delete_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('employee_id', 'Employee id', 'trim|required');
		$this->form_validation->set_rules('code', 'Employee code', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {
			$data = $this->input->post(NULL, true);

			$resp = $this->common_model->employee_delete($data['employee_id'], $data['code']);
			if (!empty($resp)) {
				$response = array('status' => 1, 'msg' => 'Deleted successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function leave_application()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('3', 'a')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'leave';
		$page_data['page_name']    = 'leave_application';
		$page_data['page_title']   = 'Leave Application';
		$page_data['leave_types']  = $this->common_model->selectAll('leave_types', '', '');
		//$page_data['designations'] = $this->common_model->selectAll('designations','','');
		$this->load->view('theme/user/main', $page_data);
	}

	function leave_application_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('code', 'Employee code', 'trim|required');
		$this->form_validation->set_rules('leave_type_id', 'Leave type', 'trim|required');
		$this->form_validation->set_rules('from_date', 'From date', 'trim|required');
		$this->form_validation->set_rules('to_date', 'To date', 'trim|required');
		$this->form_validation->set_rules('remarks', 'Remarks', 'trim');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {
			$data = $this->input->post(NULL, true);
			$employee_id = $this->common_model->employees_id_by_code($data['code']);
			$session_id = $this->session->userdata('employee_id');
			//check all heads are assigned
			$reporting_heads = $this->common_model->pending_head_assignment($employee_id);

			if (!empty($reporting_heads)) {

				$response = array('status' => 0, 'msg' => 'Reporting heads not assigned: ' . $reporting_heads);
				echo json_encode($response);
				exit;
			}

			//check you are an head of the employee if session other than the employee id		  
			if ($employee_id != $session_id) {
				$check_head  = $this->common_model->check_in_heads_list($employee_id, $session_id);
				if (empty($check_head)) {
					$response = array('status' => 0, 'msg' => 'You are not authorized to apply leave!');
					echo json_encode($response);
					exit;
				}
			}

			$param = array(
				'employee_id' => $employee_id,
				'leave_type_id' => $data['leave_type_id'],
				'from_date' => set_date($data['from_date']),
				'to_date' => set_date($data['to_date']),
				'remarks' => ($data['remarks'] != "") ? $data['remarks'] : NULL,
				'status' => 'pending',
				'status_changed' => date('Y-m-d'),
				'created_by' => $this->session->userdata('employee_id'),
				'created_at' => date('Y-m-d H:i:s')
			);

			$param = $this->security->xss_clean($param);
			$leave_id = $this->common_model->insert($param, 'leave_application');

			if (!empty($leave_id)) {

				//check hierrarchy or direct leave
				$get_leave_type  = $this->common_model->selectOne('leave_types', array('leave_type_id' => $data['leave_type_id']), 'type');
				//$base_reporting_head  = $this->common_model->get_head_by_position($employee_id,'1');
				$base_reporting_head = $this->common_model->get_base_head($employee_id);
				$top_reporting_head = $this->common_model->get_top_head($employee_id);
				//$base_reporting_head2 = $this->common_model->get_top_head($employee_id);
				//$base_reporting_head3 = $this->common_model->get_next_head($employee_id,'2');			

				if ($get_leave_type['type'] == "hierarchy") {

					$param1 = array(
						'leave_application_id' => $leave_id,
						'status' => 'pending',
						'status_changed' => date('Y-m-d'),
						'reporting_head_id' => $base_reporting_head['reporting_head_id'],
						'role_id' => $base_reporting_head['role_id'],
						'position' => $base_reporting_head['position'],
						'created_at' => date('Y-m-d H:i:s')
					);
				} else {

					$param1 = array(
						'leave_application_id' => $leave_id,
						'status' => 'pending',
						'status_changed' => date('Y-m-d'),
						'reporting_head_id' => $top_reporting_head['reporting_head_id'],
						'role_id' => $top_reporting_head['role_id'],
						'position' => $top_reporting_head['position'],
						'created_at' => date('Y-m-d H:i:s')
					);
				}
				//add base or top head id on creation. next head will add on approval
				$param1 = $this->security->xss_clean($param1);
				$leave_application_status_id = $this->common_model->insert($param1, 'leave_application_status');

				if (!empty($leave_application_status_id)) {
					$response = array('status' => 1, 'msg' => 'Applied successfully!');
					echo json_encode($response);
					exit;
				} else {
					$response = array('status' => 0, 'msg' => 'Something went wrong!');
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function leave_edit_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		$this->form_validation->set_rules('leave_application_id', 'Leave application id', 'trim|required');
		$this->form_validation->set_rules('leave_type_id', 'Leave type', 'trim|required');
		$this->form_validation->set_rules('from_date', 'From date', 'trim|required');
		$this->form_validation->set_rules('to_date', 'To date', 'trim|required');
		$this->form_validation->set_rules('remarks', 'Remarks', 'trim');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {
			$data = $this->input->post(NULL, true);
			$param = array(
				'leave_type_id' => $data['leave_type_id'],
				'from_date' => set_date($data['from_date']),
				'to_date' => set_date($data['to_date']),
				'remarks' => ($data['remarks'] != "") ? $data['remarks'] : NULL,
				'updated_by' => $this->session->userdata('employee_id'),
				'updated_at' => date('Y-m-d H:i:s')
			);

			$param = $this->security->xss_clean($param);
			$leave_id = $this->common_model->update($param, array('leave_application_id' => $data['leave_application_id']), 'leave_application');

			if (!empty($leave_id)) {
				$response = array('status' => 1, 'msg' => 'Updated successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Not updated!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function leave_application_list()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('3', 'ar')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'leave';
		$page_data['page_name']    = 'leave_application_list';
		$page_data['page_title']   = 'Leave Application List';
		$this->load->view('theme/user/main', $page_data);
	}

	function leave_application_list_ajax()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$employee_id = $this->session->userdata('employee_id');
		$all = $this->common_model->leave_application_list($employee_id);
		//pr($all);
		$leave['data'] = [];
		if (!empty($all)) {

			foreach ($all as $key => $value) {

				$res = $this->common_model->leave_status_by_head($employee_id, $value['leave_application_id']);

				$leave['data'][$key]['profile_photo'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><img src="' . getUserImage($value['profile_photo']) . '" class="rounded-circle avatar" alt="' . $value['name'] . '"></a>';
				$leave['data'][$key]['name'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><h6 class="mb-0">' . $value['name'] . '</h6><span>' . $value['code'] . '</span></a>';

				$leave['data'][$key]['designation'] = '<div><strong>' . $value['designation'] . '</strong></div><span>' . $value['department'] . '</span>';
				$leave['data'][$key]['created_at'] = get_date($value['created_at']);
				$leave['data'][$key]['leave_type'] = '<span class="badge badge-danger">' . $value['title'] . '</span>';
				$leave['data'][$key]['date'] = '<strong>' . get_date($value['from_date']) . '</strong> to <strong>' . get_date($value['to_date']) . '</strong>';
				$leave['data'][$key]['totdays'] = day_diff($value['from_date'], $value['to_date']);
				$leave['data'][$key]['remarks'] = ($value['remarks'] != "") ? (nl2br($value['remarks'])) : '-';
				$leave['data'][$key]['status'] = leave_status_c($res['status']);
				$leave['data'][$key]['airticket'] = airticket_status($value['airticket']);
				$leave['data'][$key]['status_changed'] = get_date($res['status_changed']);
				$leave['data'][$key]['action'] = ($res['status'] == 'pending') ? '<button type="button" class="btn btn-sm btn-outline-success" title="Approve" onclick="showAjaxModal(\'' . base_url('user/popup/approve_leave/' . $value['leave_application_id']) . '\',\'Approve Leave\')"><i class="fa fa-check"></i></button>&nbsp;<button type="button" class="btn btn-sm btn-outline-danger" title="Reject" onclick="showAjaxModal(\'' . base_url('user/popup/reject_leave/' . $value['leave_application_id']) . '\',\'Reject Leave\')"><i class="fa fa-close"></i></button>' : '<button type="button" class="btn btn-sm btn-outline-success" title="Status" onclick="showAjaxModal(\'' . base_url('user/popup/leave_status/' . $value['leave_application_id']) . '\',\'Leave Status\')"><i class="fa fa-eye"></i></button>';
			}
		}
		echo json_encode($leave);
	}

	function leave_application_status()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('3', 'v')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'leave';
		$page_data['page_name']    = 'leave_application_status';
		$page_data['page_title']   = 'Leave Application Status';
		$this->load->view('theme/user/main', $page_data);
	}

	function leave_application_status_ajax()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$employee_id = $this->session->userdata('employee_id');
		$all = $this->common_model->leave_application_status($employee_id);
		//pr($all);
		$leave['data'] = [];
		if (!empty($all)) {

			foreach ($all as $key => $value) {

				$leave['data'][$key]['profile_photo'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><img src="' . getUserImage($value['profile_photo']) . '" class="rounded-circle avatar" alt="' . $value['name'] . '"></a>';
				$leave['data'][$key]['name'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><h6 class="mb-0">' . $value['name'] . '</h6><span>' . $value['code'] . '</span></a>';
				$leave['data'][$key]['created_at'] = get_date($value['created_at']);
				$leave['data'][$key]['leave_type'] = '<span class="badge badge-danger">' . $value['title'] . '</span>';
				$leave['data'][$key]['date'] = '<strong>' . get_date($value['from_date']) . '</strong> to <strong>' . get_date($value['to_date']) . '</strong>';
				$leave['data'][$key]['remarks'] = ($value['remarks'] != "") ? (nl2br($value['remarks'])) : '-';
				$leave['data'][$key]['status'] = leave_status_c($value['status']);
				$leave['data'][$key]['airticket'] = airticket_status($value['airticket']);
				$leave['data'][$key]['status_changed'] = get_date($value['status_changed']);
				$leave['data'][$key]['action'] = '<button type="button" class="btn btn-sm btn-outline-success" title="Status" onclick="showAjaxModal(\'' . base_url('user/popup/leave_status/' . $value['leave_application_id']) . '\',\'Leave Status\')"><i class="fa fa-eye"></i></button>' . ((check_permission('3', 'e')) ? '&nbsp;<button type="button" class="btn btn-sm btn-outline-secondary" title="Status" onclick="showAjaxModal(\'' . base_url('user/popup/leave_edit/' . $value['leave_application_id']) . '\',\'Leave Edit\')"><i class="fa fa-edit"></i></button>' : '') . (((check_role_permission(5) || check_role_permission(3)) && $value['status'] == 'approved') ? '&nbsp;<a href="' . base_url() . 'print-leave-application/' . $value['leave_application_id'] . '" class="btn btn-sm btn-outline-secondary" title="Print" target="_blank"><i class="fa fa-print"></i></a>' : '') . ((check_permission('3', 'd')) ? '&nbsp;<button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteLeave(\'' . $value['leave_application_id'] . '\')"><i class="fa fa-trash-o"></i></button>' : '');
			}
		}
		echo json_encode($leave);
	}

	function reporting_heads()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('6', 'v,a,e,d')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'reporting_heads';
		$page_data['page_title']   = 'Reporting Heads';
		$page_data['roles']  = $this->common_model->selectAll('roles', array('head_assignment' => 'yes'), '');

		$this->load->view('theme/user/main', $page_data);
	}

	function get_heads()
	{

		$role = $this->input->post('role');
		$heads  = $this->common_model->get_heads($role);
		echo json_encode($heads);
	}

	function get_assignments()
	{

		$code = $this->input->post('code');
		$assignments  = $this->common_model->get_assignments($code);

		$assi = [];
		if (!empty($assignments)) {

			foreach ($assignments as $key => $value) {

				$assi[$key]['profile_photo'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><img src="' . getUserImage($value['profile_photo']) . '" class="rounded-circle avatar" alt="' . $value['name'] . '"></a>';
				$assi[$key]['name'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><h6 class="mb-0">' . $value['name'] . '</h6></a>';
				$assi[$key]['code'] = $value['code'];
				$assi[$key]['role'] = '<span class="badge badge-success">' . $value['role'] . '</span>';
				$assi[$key]['created_at'] = get_date($value['created_at']);
				$assi[$key]['action'] = ((check_permission('6', 'd')) ? '<button type="button" class="btn btn-sm btn-outline-danger" title="Reject" onclick="deleteAssignment(\'' . $value['reporting_id'] . '\')"><i class="fa fa-trash"></i></button>' : '');
			}
		}
		echo json_encode($assi);
	}

	function head_assignment_process()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('6', 'a')) {
			$response = array('status' => 0, 'msg' => 'Permission denied!');
			echo json_encode($response);
			exit;
		}

		$this->form_validation->set_rules('code', 'Employee code', 'trim|required');
		$this->form_validation->set_rules('role_id', 'Role', 'trim|required');
		$this->form_validation->set_rules('reporting_head_id', 'Head', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);
			$employee_id = $this->common_model->employees_id_by_code($data['code']);

			$exist = $this->common_model->selectOne('reporting_heads', array('employee_id' => $employee_id, 'role_id' => $data['role_id']), '*');

			if (empty($exist)) {

				$param = array(
					'employee_id' => $employee_id,
					'role_id' => $data['role_id'],
					'reporting_head_id' => $data['reporting_head_id'],
					'created_by' => $this->session->userdata('employee_id'),
					'created_at' => date('Y-m-d H:i:s')
				);

				$param = $this->security->xss_clean($param);
				$leave_id = $this->common_model->insert($param, 'reporting_heads');

				if (!empty($leave_id)) {
					$response = array('status' => 1, 'msg' => 'Assigned successfully!');
					echo json_encode($response);
					exit;
				} else {
					$response = array('status' => 0, 'msg' => 'Something went wrong!');
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('status' => 0, 'msg' => 'Assignment already exist!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function delete_assignment_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('reporting_id', 'Report id', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {
			$data = $this->input->post(NULL, true);

			$resp = $this->common_model->delete_assignment($data['reporting_id']);
			if (!empty($resp)) {
				$response = array('status' => 1, 'msg' => 'Deleted successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function approve_leave_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('leave_application_status_id', 'Application status id', 'trim|required');
		$this->form_validation->set_rules('leave_application_id', 'Application id', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');

		if ($this->form_validation->run()) {
			$data = $this->input->post(NULL, true);

			$leave = $this->common_model->leave_by_id($data['leave_application_id']);

			if (!empty($leave)) {

				$session_id = $this->session->userdata('employee_id');
				$get_leave_type  = $this->common_model->selectOne('leave_types', array('leave_type_id' => $leave['leave_type_id']), 'type');
				$get_top_head    = $this->common_model->get_top_head($leave['employee_id']);

				//current loggined user status			
				$res = $this->common_model->leave_status_by_head($session_id, $leave['leave_application_id']);

				if ($res['status'] == 'pending') {
					//save status
					$param = array(
						'status' => 'approved',
						'status_changed' => date('Y-m-d'),
						'remarks' => ($data['remarks'] != "") ? $data['remarks'] : NULL,
						'updated_at' => date('Y-m-d H:i:s')
					);

					$param = $this->security->xss_clean($param);
					$ustatus = $this->common_model->update($param, array('leave_application_status_id' => $res['leave_application_status_id'], 'reporting_head_id' => $res['reporting_head_id'], 'role_id' => $res['role_id'], 'position' => $res['position']), 'leave_application_status');
					if ($ustatus) {

						//check whether its top level approval
						if ($get_top_head['reporting_head_id'] == $res['reporting_head_id'] && $get_top_head['role_id'] == $res['role_id'] && $get_top_head['position'] == $res['position']) {

							$param2 = array(
								'status' => 'approved',
								'status_changed' => date('Y-m-d'),
								'airticket' => (!empty($data['airticket'])) ? $data['airticket'] : 'notapplicable'
							);

							$param2 = $this->security->xss_clean($param2);
							$mleaves = $this->common_model->update($param2, array('leave_application_id' => $leave['leave_application_id']), 'leave_application');

							if (!empty($mleaves)) {

								//update to rejoin table
								$rjparam = array(
									'employee_id' => $leave['employee_id'],
									'leave_application_id' => $leave['leave_application_id'],
									'leave_status' => 'yes',
									'leave_date' => $leave['from_date']
								);

								$leave_rejoin_id = $this->common_model->insert($rjparam, 'employee_leave_rejoin');

								if (!empty($leave_rejoin_id)) {
									$response = array('status' => 1, 'msg' => 'Approved successfully!');
									echo json_encode($response);
									exit;
								} else {
									$response = array('status' => 0, 'msg' => 'Something went wrong!');
									echo json_encode($response);
									exit;
								}
							} else {
								$response = array('status' => 0, 'msg' => 'Something went wrong!');
								echo json_encode($response);
								exit;
							}
						} else {
							//pass to next head approval

							$next_head = $this->common_model->get_next_head($leave['employee_id'], $res['position']);
							$param1 = array(
								'leave_application_id' => $leave['leave_application_id'],
								'status' => 'pending',
								'status_changed' => date('Y-m-d'),
								'reporting_head_id' => $next_head['reporting_head_id'],
								'role_id' => $next_head['role_id'],
								'position' => $next_head['position'],
								'created_at' => date('Y-m-d H:i:s')
							);

							$param1 = $this->security->xss_clean($param1);
							$leave_application_status_id = $this->common_model->insert($param1, 'leave_application_status');

							if (!empty($leave_application_status_id)) {
								$response = array('status' => 1, 'msg' => 'Approved and passed to next head!');
								echo json_encode($response);
								exit;
							} else {
								$response = array('status' => 0, 'msg' => 'Something went wrong!');
								echo json_encode($response);
								exit;
							}
						}
					} else {
						$response = array('status' => 0, 'msg' => 'Update failed!');
						echo json_encode($response);
						exit;
					}
				} else {
					$response = array('status' => 0, 'msg' => 'Something went wrong!');
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function reject_leave_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('leave_application_status_id', 'Application status id', 'trim|required');
		$this->form_validation->set_rules('leave_application_id', 'Application id', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');

		if ($this->form_validation->run()) {
			$data = $this->input->post(NULL, true);

			$leave = $this->common_model->leave_by_id($data['leave_application_id']);

			if (!empty($leave)) {

				$session_id = $this->session->userdata('employee_id');

				//current loggined user status			
				$res = $this->common_model->leave_status_by_head($session_id, $leave['leave_application_id']);

				if ($res['status'] == 'pending') {
					//save status
					$param = array(
						'status' => 'rejected',
						'status_changed' => date('Y-m-d'),
						'remarks' => ($data['remarks'] != "") ? $data['remarks'] : NULL,
						'updated_at' => date('Y-m-d H:i:s')
					);

					$param = $this->security->xss_clean($param);
					$ustatus = $this->common_model->update($param, array('leave_application_status_id' => $res['leave_application_status_id'], 'reporting_head_id' => $res['reporting_head_id'], 'role_id' => $res['role_id'], 'position' => $res['position']), 'leave_application_status');
					if ($ustatus) {

						//change main leave status to rejected					
						$param2 = array(
							'status' => 'rejected',
							'status_changed' => date('Y-m-d')
						);

						$param2 = $this->security->xss_clean($param2);
						$mleaves = $this->common_model->update($param2, array('leave_application_id' => $leave['leave_application_id']), 'leave_application');

						if (!empty($mleaves)) {
							$response = array('status' => 1, 'msg' => 'Rejected successfully!');
							echo json_encode($response);
							exit;
						} else {
							$response = array('status' => 0, 'msg' => 'Something went wrong!');
							echo json_encode($response);
							exit;
						}
					} else {
						$response = array('status' => 0, 'msg' => 'Update failed!');
						echo json_encode($response);
						exit;
					}
				} else {
					$response = array('status' => 0, 'msg' => 'Something went wrong!');
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function assets_assignment()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('7', 'a,e')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'assets_assignment';
		$page_data['page_title']   = 'Assets Assignment';
		$page_data['assets']  = $this->common_model->selectAll('assets', '', '');
		$this->load->view('theme/user/main', $page_data);
	}

	function assets_assignment_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('employee_id', 'Employee id', 'trim|required');
		$this->form_validation->set_rules('asset_id', 'Asset', 'trim|required');
		$this->form_validation->set_rules('title[]', 'Title', 'trim|required');
		$this->form_validation->set_rules('description[]', 'Description', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);

			//$employee_id = $this->common_model->employees_id_by_code($data['code']);
			$employee_id = $data['employee_id'];
			$check_assignment_exist = $this->common_model->selectOne('asset_assignment', array('employee_id' => $employee_id, 'asset_id' => $data['asset_id']), '*');

			if (!empty($check_assignment_exist)) {
				$asset_assignment_id = $check_assignment_exist['asset_assignment_id'];
			} else {
				$param1 = array(
					'employee_id' => $employee_id,
					'asset_id' => $data['asset_id'],
					'created_by' => $this->session->userdata('employee_id'),
					'status' => 'active',
					'created_at' => date('Y-m-d H:i:s')
				);

				$param1 = $this->security->xss_clean($param1);
				$asset_assignment_id = $this->common_model->insert($param1, 'asset_assignment');
			}

			$assets = array();

			if (!empty($data['title'])) {
				foreach ($data['title'] as $key => $value) {
					$assets[$key]['title'] = $data['title'][$key];
					$assets[$key]['description']    = $data['description'][$key];
					$assets[$key]['asset_assignment_id'] = $asset_assignment_id;
				}
			}

			//remove empty details
			$c = function ($v) {
				return array_filter($v) != array();
			};
			$assets = array_filter($assets, $c);

			$resp = $this->common_model->save_assets($assets);

			if (!empty($resp)) {
				$response = array('status' => 1, 'msg' => 'Saved successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function ajax_assets()
	{
		$page_data['count'] = $this->input->post('count');
		$this->load->view('user/ajax_assets', $page_data);
	}

	function employee_assets()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('7', 'v,e,d')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'employee_assets';
		$page_data['page_title']   = 'Assigned Assets';
		$this->load->view('theme/user/main', $page_data);
	}

	function employee_assets_ajax()
	{

		$all = $this->common_model->employee_assets();
		$employees['data'] = [];
		if (!empty($all)) {

			foreach ($all as $key => $value) {

				$employees['data'][$key]['profile_photo'] = '<a href="' . base_url() . 'employee/profile/' . $value['code'] . '" title="View profile" target="_blank"><img src="' . getUserImage($value['profile_photo']) . '" class="rounded-circle avatar" alt="' . $value['name'] . '"></a>';
				$employees['data'][$key]['name'] = '<a href="' . base_url() . 'employee/profile/' . $value['code'] . '" title="View profile" target="_blank"><h6 class="mb-0">' . $value['name'] . '</h6><span>' . $value['email'] . '</span></a>';
				$employees['data'][$key]['code'] = $value['code'];
				$employees['data'][$key]['designation'] = '<div><strong>' . $value['designation'] . '</strong></div><span>' . $value['department'] . '</span>';
				$employees['data'][$key]['phone'] = ($value['phone_current'] != "") ? $value['phone_current'] : '-';
				$employees['data'][$key]['assets_count'] = $value['assets_count'];
				$employees['data'][$key]['action'] = '<button type="button" class="btn btn-sm btn-outline-success" title="Assets List" onclick="showAjaxModal(\'' . base_url('user/popup/assets_list/' . $value['employee_id']) . '\',\'Assets List\')"><i class="fa fa-eye"></i></button>';
			}
		}
		echo json_encode($employees);
	}

	function remove_asset()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('7', 'd')) {
			$response = array('status' => 0, 'msg' => 'Permission denied!');
			echo json_encode($response);
			exit;
		}

		$this->form_validation->set_rules('id', 'Asset id', 'trim|required');
		$this->form_validation->set_rules('type', 'Type', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);
			$resp = $this->common_model->remove_asset($data['id'], $data['type']);
			if (!empty($resp)) {
				$response = array('status' => 1, 'msg' => 'Deleted successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function assets_report()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('8', 'v,e,d')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'reports';
		$page_data['page_name']    = 'assets_report';
		$page_data['page_title']   = 'Assets Report';
		$page_data['assets']       = $this->common_model->selectAll('assets', '', '');
		$this->load->view('theme/user/main', $page_data);
	}

	function asset_report_ajax()
	{

		$code = $this->input->post('code');
		$asset_id = $this->input->post('asset_id');
		$employee_id = $this->common_model->employees_id_by_code($code);
		$all = $this->common_model->asset_report($employee_id, $asset_id);

		$assets = [];
		if (!empty($all)) {

			foreach ($all as $key => $value) {

				$assets[$key]['profile_photo'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><img src="' . getUserImage($value['profile_photo']) . '" class="rounded-circle avatar" alt="' . $value['name'] . '"></a>';
				$assets[$key]['name'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><h6 class="mb-0">' . $value['name'] . '</h6><span>' . $value['email'] . '</span></a>';
				$assets[$key]['code'] = $value['code'];
				$assets[$key]['designation'] = '<div><strong>' . $value['designation'] . '</strong></div><span>' . $value['department'] . '</span>';
				$assets[$key]['phone'] = ($value['phone_current'] != "") ? $value['phone_current'] : '-';
				$assets[$key]['assets'] = '<button type="button" class="btn btn-sm btn-outline-success" title="Assets List" onclick="showAjaxModal(\'' . base_url('user/popup/assets_list/' . $value['employee_id']) . '\',\'Assets List\')"><i class="fa fa-eye"></i></button>';

				$html = "";
				if (!empty($value['details'])) {
					$html .= '<table class="table"><tbody>';
					foreach ($value['details'] as $row) {
						$html .= '<tr>
                                            <td ' . ((count($row['specifications']) > 0) ? ('rowspan="' . (count($row['specifications']) + 1) . '"') : '') . '>' . $row['asset'] . '</td>';
						if (empty($row['specifications'])) {
							$html .= '<td>-</td>';
						}
						$html .= '</tr>';

						if (!empty($row['specifications'])) {
							foreach ($row['specifications'] as $row1) {
								$html .= '<tr> <td><strong>' . ucfirst($row1['title']) . '</strong>:&nbsp;&nbsp;' . $row1['description'] . '</td>
                                        </tr>';
							}
						}
					}
					$html .= '</tbody></table>';
				}
				$assets[$key]['assets'] = $html;
			}
		}
		echo json_encode($assets);
	}

	function work_location_assignment()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('8', 'a')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'work_location_assignment';
		$page_data['page_title']   = 'Work Location Assignment';
		$page_data['locations']  = $this->common_model->selectAll('work_locations', '', '');
		$this->load->view('theme/user/main', $page_data);
	}

	function work_location_assignment_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('employee_id', 'Employee id', 'trim|required');
		$this->form_validation->set_rules('work_location_id', 'Work Location', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);
			$employee_id = $data['employee_id'];
			//$this->common_model->employees_id_by_code($data['code']);

			$param1 = array(
				'employee_id' => $employee_id,
				'work_location_id' => $data['work_location_id'],
				'status' => $data['status'],
				'start_date' => (!empty($data['start_date'])) ? set_date($data['start_date']) : NULL,
				'end_date' => (!empty($data['end_date'])) ? set_date($data['end_date']) : NULL,
				'created_by' => $this->session->userdata('employee_id'),
				'created_at' => date('Y-m-d H:i:s')
			);

			$param1 = $this->security->xss_clean($param1);
			$assignment_id = $this->common_model->insert($param1, 'work_location_assignment');

			if (!empty($assignment_id)) {
				$response = array('status' => 1, 'msg' => 'Assigned successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function assigned_work_locations()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('8', 'v,e,d')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'assigned_work_locations';
		$page_data['page_title']   = 'Assigned Work Locations';
		$this->load->view('theme/user/main', $page_data);
	}

	function assigned_work_locations_ajax()
	{

		$all = $this->common_model->assigned_work_locations();
		$employees['data'] = [];
		if (!empty($all)) {

			foreach ($all as $key => $value) {

				$employees['data'][$key]['profile_photo'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><img src="' . getUserImage($value['profile_photo']) . '" class="rounded-circle avatar" alt="' . $value['name'] . '"></a>';
				$employees['data'][$key]['name'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><h6 class="mb-0">' . $value['name'] . '</h6><span>' . $value['email'] . '</span></a>';
				$employees['data'][$key]['code'] = $value['code'];
				$employees['data'][$key]['work_location'] = $value['work_location'];
				$employees['data'][$key]['start_date'] = get_date($value['start_date']);
				$employees['data'][$key]['end_date'] = get_date($value['end_date']);
				$employees['data'][$key]['status'] = employee_status_c($value['work_status']);
				$employees['data'][$key]['action'] = ((check_permission('8', 'e')) ? '<button type="button" class="btn btn-sm btn-outline-secondary" title="Edit" onclick="showAjaxModal(\'' . base_url('user/popup/edit_assigned_location/' . $value['assignment_id']) . '\',\'Edit assigned location\')"><i class="fa fa-edit"></i></button>&nbsp;' : '') . ((check_permission('8', 'd')) ? '<button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteLocationAssignment(\'' . $value['assignment_id'] . '\')"><i class="fa fa-trash-o"></i></button>' : '');
			}
		}
		echo json_encode($employees);
	}

	function delete_location_assignment_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('assignment_id', 'Assignment id', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {
			$data = $this->input->post(NULL, true);

			$resp = $this->common_model->delete_location_assignment($data['assignment_id']);
			if (!empty($resp)) {
				$response = array('status' => 1, 'msg' => 'Deleted successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function work_location_update_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('assignment_id', 'Assignment', 'trim|required');
		$this->form_validation->set_rules('work_location_id', 'Work Location', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);

			$param1 = array(
				'work_location_id' => $data['work_location_id'],
				'status' => $data['status'],
				'start_date' => (!empty($data['start_date'])) ? set_date($data['start_date']) : NULL,
				'end_date' => (!empty($data['end_date'])) ? set_date($data['end_date']) : NULL,
				'updated_by' => $this->session->userdata('employee_id'),
				'updated_at' => date('Y-m-d H:i:s')
			);

			$param1 = $this->security->xss_clean($param1);
			$assignment_id = $this->common_model->update($param1, array('assignment_id' => $data['assignment_id']), 'work_location_assignment');

			if (!empty($assignment_id)) {
				$response = array('status' => 1, 'msg' => 'Updated successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function generate_letter()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('5', 'a')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'letters';
		$page_data['page_name']    = 'generate_letter';
		$page_data['page_title']   = 'Generate Letter';
		$page_data['letters']  = $this->common_model->selectAll('letters', '', '');
		echo base64_decode("");
		$this->load->view('theme/user/main', $page_data);
	}

	function get_letter()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		$letter_id = $this->input->post('letter_id');
		$code = $this->input->post('code');
		$employee = $this->common_model->employees_by_code($code);
		//pr($employee);
		$letter = $this->common_model->selectOne('letters', array('letter_id' => $letter_id), '*');
		if (!empty($letter)) {

			$qry = "SELECT refno FROM employee_letters where letter_id = ? and YEAR(created_at) = ? and MONTH(created_at) = ? order by employee_letter_id DESC limit 1";
			$res = $this->db->query($qry, array($letter_id, date('Y'), date('m')))->row_array();

			if (!empty($res)) {
				$lastid = substr($res['refno'], strrpos($res['refno'], '/') + 1);
				$cnt = ($lastid + 1);
			} else {
				$cnt = 1;
			}

			$token = array(
				'curdate'  => date('d/m/Y'),
				'employee_name' => $employee['name'],
				'employee_designation' => $employee['designation'],
				'employee_number' => $employee['code'],
				'employee_passport' => $employee['passport_number'],
				'employee_emirate' => $employee['emirate_number'],
				'employee_emirate_expiry' => $employee['emirate_expiry'],
				'employee_country' => $employee['country'],
				'employee_salary' => $employee['basic_pay'],
				'employee_join' => get_date($employee['date_of_join']),
				'account_number' => @$employee['bank_details'][0]['account_number'],
				'bank_name' => @$employee['bank_details'][0]['bank_name'],
				'refno' => "LEM/HRD/" . $letter['code'] . "/" . date('Y') . "/" . date('m') . "/" . sprintf("%02d", $cnt)
			);
			$pattern = '{{%s}}';
			foreach ($token as $key => $val) {
				$varMap[sprintf($pattern, $key)] = $val;
			}
			$data['letterContent'] = strtr($letter['content'], $varMap);
			$data['refno'] = $token['refno'];
			echo json_encode($data, true);
		} else {
			echo "invalid";
		}
		exit;
	}

	function generate_letter_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$_POST = json_decode(file_get_contents("php://input"), true);

		$this->form_validation->set_rules('code', 'Code', 'trim|required');
		$this->form_validation->set_rules('letter_id', 'Letter Type', 'trim|required');
		$this->form_validation->set_rules('letter_data', 'Letter Content', 'trim|required');
		$this->form_validation->set_rules('refno', 'Ref. No.', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$code = $this->input->post('code');
			$data['employee_id'] = $this->common_model->employees_id_by_code($code);
			$data['letter_id'] = $this->input->post('letter_id');
			$data['letter_data'] = addslashes($this->input->post('letter_data'));
			$data['refno'] = $this->input->post('refno');
			$data['generated_date'] = set_date($this->input->post('generated_date'));
			$data['created_by'] = $this->session->userdata('employee_id');
			$data['created_at'] = date('Y-m-d H:i:s');

			$exist = $this->db->get_where('employee_letters', array('refno' => $data['refno']))->row_array();
			if (empty($exist)) {
				$employee_letter_id = $this->common_model->insert($data, 'employee_letters');
				if (!empty($employee_letter_id)) {
					$response = array('status' => 1, 'employee_letter_id' => $employee_letter_id, 'msg' => 'Generated successfully!');
					echo json_encode($response);
					exit;
				} else {
					$response = array('status' => 0, 'msg' => 'Something went wrong!');
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('status' => 0, 'msg' => 'Ref. No. already exist!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function print_letters()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('5', 'v,e,d')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'letters';
		$page_data['page_name']    = 'print_letters';
		$page_data['page_title']   = 'Print Letters';
		$this->load->view('theme/user/main', $page_data);
	}

	function print_letters_ajax()
	{

		$all = $this->common_model->print_letters();

		$employees['data'] = [];
		if (!empty($all)) {

			foreach ($all as $key => $value) {

				$employees['data'][$key]['profile_photo'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><img src="' . getUserImage($value['profile_photo']) . '" class="rounded-circle avatar" alt="' . $value['name'] . '"></a>';
				$employees['data'][$key]['name'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><h6 class="mb-0">' . $value['name'] . '</h6><span>' . $value['email'] . '</span></a>';
				$employees['data'][$key]['code'] = $value['code'];
				$employees['data'][$key]['designation'] = '<div><strong>' . $value['designation'] . '</strong></div><span>' . $value['department'] . '</span>';
				$employees['data'][$key]['letter_type'] = $value['type'];
				$employees['data'][$key]['refno'] = $value['refno'];
				$employees['data'][$key]['generated_date'] = get_date($value['generated_date']);
				$employees['data'][$key]['updated_at'] = get_date($value['updated_at']);
				$employees['data'][$key]['action'] = '<a href="' . base_url() . 'print-letters/' . $value['employee_letter_id'] . '" class="btn btn-sm btn-outline-secondary" title="Print" target="_blank"><i class="fa fa-print"></i></a>&nbsp;<button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteEmployeeLetter(\'' . $value['employee_letter_id'] . '\')"><i class="fa fa-trash-o"></i></button>';
			}
		}
		echo json_encode($employees);
	}

	function print_letter_page($employee_letter_id)
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		if (!check_permission('5', 'v,e,d')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}
		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'employee';
		$page_data['page_name']    = 'print_letter_page';
		$page_data['page_title']   = 'Print Letter';
		$page_data['letter']       = $this->common_model->selectOne('employee_letters', array('employee_letter_id' => $employee_letter_id), '*');

		if (empty($page_data['letter'])) {
			$this->session->set_flashdata('error', 'Invalid letter!');
			redirect('print-letters', 'refresh');
		}

		$this->load->view('user/print_letter_page', $page_data);
	}

	function print_imbursement_page($unique_code)
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		if (!check_permission('8', 'v,a,e,d')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}
		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'report';
		$page_data['page_name']    = 'print_imbursement_page';
		$page_data['page_title']   = 'Print Reimbursement';
		$page_data['details']       = $this->common_model->imbursement_detail_by_code($unique_code);
		$page_data['currency'] = $this->settings->get_settings('currency');
		if (empty($page_data['details'])) {
			$this->session->set_flashdata('error', 'Invalid Reimbursement Application!');
			redirect('imbursement-report', 'refresh');
		}

		$this->load->view('user/print_imbursement_page', $page_data);
	}

	function delete_employee_letter_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('employee_letter_id', 'Letter id', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {
			$data = $this->input->post(NULL, true);

			$resp = $this->common_model->delete_employee_letter($data['employee_letter_id']);
			if (!empty($resp)) {
				$response = array('status' => 1, 'msg' => 'Deleted successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function imbursement_create()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('2', 'a')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'imbursement';
		$page_data['page_name']    = 'imbursement_create';
		$page_data['page_title']   = 'Reimbursement Create';

		$this->load->view('theme/user/main', $page_data);
	}

	function ajax_imbursement()
	{
		$page_data['count'] = $this->input->post('count');
		$this->load->view('user/ajax_imbursement', $page_data);
	}

	function imbursement_create_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('code', 'Employee code', 'trim|required');
		$this->form_validation->set_rules('imbursement_date[]', 'Date', 'trim|required');
		$this->form_validation->set_rules('details[]', 'Details', 'trim|required');
		$this->form_validation->set_rules('amount[]', 'Amount', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);
			$employee_id = $this->common_model->employees_id_by_code($data['code']);
			$session_id = $this->session->userdata('employee_id');
			//check all heads are assigned
			$reporting_heads = $this->common_model->pending_imbursement_head_assignment($employee_id);
			if (!empty($reporting_heads)) {
				$response = array('status' => 0, 'msg' => 'Reporting heads not assigned: ' . $reporting_heads);
				echo json_encode($response);
				exit;
			}

			//check you are an head of the employee if session other than the employee id		  
			if ($employee_id != $session_id) {
				$check_head  = $this->common_model->check_in_heads_list($employee_id, $session_id);
				if (empty($check_head)) {
					$response = array('status' => 0, 'msg' => 'You are not authorized to create imbursement!');
					echo json_encode($response);
					exit;
				}
			}

			$imbursement = array();

			if (!empty($data['imbursement_date'])) {

				$unique_code = $this->get_unique_code();

				foreach ($data['imbursement_date'] as $key => $value) {
					$imbursement[$key]['imbursement_date'] = set_date($data['imbursement_date'][$key]);
					$imbursement[$key]['details']    = ($data['details'][$key] != "") ? $data['details'][$key] : NULL;
					$imbursement[$key]['amount']  = ($data['amount'][$key] != "") ? round($data['amount'][$key], 2) : NULL;
					$imbursement[$key]['employee_id']    = $employee_id;
					$imbursement[$key]['status']    = 'pending';
					$imbursement[$key]['unique_code']    = $unique_code;
					$imbursement[$key]['status_changed'] = date('Y-m-d');
					$imbursement[$key]['created_by'] = $this->session->userdata('employee_id');
					$imbursement[$key]['created_at'] = date('Y-m-d H:i:s');
				}
			}

			//remove empty details
			$c = function ($v) {
				return array_filter($v) != array();
			};
			$imbursement = array_filter($imbursement, $c);

			$files = $_FILES;
			$resp = $this->common_model->save_imbursement($imbursement, $employee_id, $files);
			if (!empty($resp)) {
				$response = array('status' => 1, 'msg' => 'Saved successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function imbursement_application_list()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('2', 'ar')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'imbursement';
		$page_data['page_name']    = 'imbursement_application_list';
		$page_data['page_title']   = 'Reimbursement Application List';
		$this->load->view('theme/user/main', $page_data);
	}

	function imbursement_application_list_ajax()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$employee_id = $this->session->userdata('employee_id');
		$all = $this->common_model->imbursement_application_list($employee_id);
		//pr($all);
		$imbursement['data'] = [];
		if (!empty($all)) {
			$currency = $this->settings->get_settings('currency');
			foreach ($all as $key => $value) {

				$res = $this->common_model->imbursement_status_by_head($employee_id, $value['unique_code']);

				$imbursement['data'][$key]['profile_photo'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><img src="' . getUserImage($value['profile_photo']) . '" class="rounded-circle avatar" alt="' . $value['name'] . '"></a>';
				$imbursement['data'][$key]['name'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><h6 class="mb-0">' . $value['name'] . '</h6><span>' . $value['code'] . '</span></a>';

				$imbursement['data'][$key]['designation'] = '<div><strong>' . $value['designation'] . '</strong></div><span>' . $value['department'] . '</span>';
				$imbursement['data'][$key]['unique_id'] = $value['unique_code'];
				$imbursement['data'][$key]['created_at'] = get_date($value['created_at']);
				$imbursement['data'][$key]['amount'] = $currency . ' ' . round($value['amount'], 2);
				$imbursement['data'][$key]['status'] = '<span class="badge badge-success">Approved (' . $res['approved'] . ')</span>&nbsp;<span class="badge badge-warning">Pending (' . $res['pending'] . ')</span>&nbsp;<span class="badge badge-danger">Rejected (' . $res['rejected'] . ')</span>';
				$imbursement['data'][$key]['action'] = '<button type="button" class="btn btn-sm btn-outline-success" title="Status" onclick="showAjaxModal(\'' . base_url('user/popup/imbursement_status/' . $value['unique_code']) . '\',\'Reimbursement Status\')"><i class="fa fa-eye"></i></button>';
			}
		}
		echo json_encode($imbursement);
	}

	function imbursement_application_status()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('2', 'v')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'imbursement';
		$page_data['page_name']    = 'imbursement_application_status';
		$page_data['page_title']   = 'Reimbursement Application Status';
		$this->load->view('theme/user/main', $page_data);
	}

	function imbursement_application_status_ajax()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$employee_id = $this->session->userdata('employee_id');
		$all = $this->common_model->imbursement_application_status($employee_id);
		//pr($all);
		$imbursement['data'] = [];
		if (!empty($all)) {
			$currency = $this->settings->get_settings('currency');
			foreach ($all as $key => $value) {

				$res = $this->common_model->imbursement_status_by_code($value['unique_code']);

				$imbursement['data'][$key]['profile_photo'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><img src="' . getUserImage($value['profile_photo']) . '" class="rounded-circle avatar" alt="' . $value['name'] . '"></a>';
				$imbursement['data'][$key]['name'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><h6 class="mb-0">' . $value['name'] . '</h6><span>' . $value['code'] . '</span></a>';

				$imbursement['data'][$key]['profile_photo'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><img src="' . getUserImage($value['profile_photo']) . '" class="rounded-circle avatar" alt="' . $value['name'] . '"></a>';
				$imbursement['data'][$key]['name'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><h6 class="mb-0">' . $value['name'] . '</h6><span>' . $value['code'] . '</span></a>';

				$imbursement['data'][$key]['designation'] = '<div><strong>' . $value['designation'] . '</strong></div><span>' . $value['department'] . '</span>';
				$imbursement['data'][$key]['unique_id'] = $value['unique_code'];
				$imbursement['data'][$key]['created_at'] = get_date($value['created_at']);
				$imbursement['data'][$key]['amount'] = $currency . ' ' . round($value['amount'], 2);
				$imbursement['data'][$key]['status'] = '<span class="badge badge-success">Approved (' . $res['approved'] . ')</span>&nbsp;<span class="badge badge-warning">Pending (' . $res['pending'] . ')</span>&nbsp;<span class="badge badge-danger">Rejected (' . $res['rejected'] . ')</span>';
				$imbursement['data'][$key]['action'] = '<button type="button" class="btn btn-sm btn-outline-success" title="Status" onclick="showAjaxModal(\'' . base_url('user/popup/my_imbursement_status/' . $value['unique_code']) . '\',\'Reimbursement Status\')"><i class="fa fa-eye"></i></button>' . ((check_permission('2', 'e')) ? '&nbsp;<a href="' . base_url() . 'imbursement-edit/' . $value['unique_code'] . '" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="fa fa-edit"></i></a>' : '') . ((check_permission('2', 'd')) ? '&nbsp;<button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteImbursement(\'' . $value['unique_code'] . '\')"><i class="fa fa-trash-o"></i></button>' : '');
			}
		}
		echo json_encode($imbursement);
	}

	function imbursement_status_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('status[]', 'Status', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);
			$employee_id = $data['employee_id'];
			$unique_code = $data['unique_code'];
			$imbursement = array();

			if (!empty($data['status'])) {

				foreach ($data['status'] as $key => $value) {
					$imbursement[$key]['status'] = $data['status'][$key];
					$imbursement[$key]['reporting_head_id']    = $data['reporting_head_id'][$key];
					$imbursement[$key]['role_id']  = $data['role_id'][$key];
					$imbursement[$key]['position']    = $data['position'][$key];
					$imbursement[$key]['status_changed'] = date('Y-m-d');
					$imbursement[$key]['updated_at'] = date('Y-m-d H:i:s');
					$imbursement[$key]['imbursement_application_status_id'] = $data['imbursement_application_status_id'][$key];
					$imbursement[$key]['imbursement_application_id'] = $data['imbursement_application_id'][$key];
				}
			}

			//remove empty details
			$c = function ($v) {
				return array_filter($v) != array();
			};
			$imbursement = array_filter($imbursement, $c);

			$resp = $this->common_model->save_imbursement_status($imbursement, $employee_id, $unique_code);
			if (!empty($resp)) {
				$response = array('status' => 1, 'msg' => 'Status updated successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function imbursement_report()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('8', 'v,a,e,d')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'reports';
		$page_data['page_name']    = 'imbursement_report';
		$page_data['page_title']   = 'Reimbursement Reports';
		$this->load->view('theme/user/main', $page_data);
	}

	function imbursement_report_ajax()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$all = $this->common_model->imbursement_report();

		$imbursement['data'] = [];
		if (!empty($all)) {
			$currency = $this->settings->get_settings('currency');
			foreach ($all as $key => $value) {

				$imbursement['data'][$key]['profile_photo'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><img src="' . getUserImage($value['profile_photo']) . '" class="rounded-circle avatar" alt="' . $value['name'] . '"></a>';
				$imbursement['data'][$key]['name'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><h6 class="mb-0">' . $value['name'] . '</h6><span>' . $value['code'] . '</span></a>';

				$imbursement['data'][$key]['designation'] = '<div><strong>' . $value['designation'] . '</strong></div><span>' . $value['department'] . '</span>';
				$imbursement['data'][$key]['unique_id'] = $value['unique_code'];
				$imbursement['data'][$key]['created_at'] = get_date($value['created_at']);
				$imbursement['data'][$key]['amount'] = $currency . ' ' . round($value['amount'], 2);
				$imbursement['data'][$key]['status'] = '<span class="badge badge-success">Approved (' . $value['approved'] . ')</span>';
				$imbursement['data'][$key]['action'] = '<a href="' . base_url() . 'print-imbursement/' . $value['unique_code'] . '" class="btn btn-sm btn-outline-secondary" title="Print" target="_blank"><i class="fa fa-print"></i></a>&nbsp;<button type="button" class="btn btn-sm btn-outline-success" title="Status" onclick="showAjaxModal(\'' . base_url('user/popup/imbursement_status/' . $value['unique_code']) . '\',\'Reimbursement Status\')"><i class="fa fa-eye"></i></button>';
			}
		}
		echo json_encode($imbursement);
	}

	function imbursement_edit($unique_id)
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$page_data['details']  = $this->common_model->imbursement_detail_by_code($unique_id);

		if (empty($unique_id) || empty($page_data['details'])) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('imbursement-status', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'imbursement';
		$page_data['page_name']    = 'imbursement_edit';
		$page_data['page_title']   = 'Reimbursement Edit';

		$this->load->view('theme/user/main', $page_data);
	}

	function imbursement_edit_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('imbursement_date[]', 'Date', 'trim|required');
		$this->form_validation->set_rules('details[]', 'Details', 'trim|required');
		$this->form_validation->set_rules('amount[]', 'Amount', 'trim|required');

		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);
			$employee_id = $this->common_model->selectOne('imbursement_application', array('unique_code' => $data['unique_code']), 'employee_id');
			$imbursement = array();

			if (!empty($data['imbursement_date'])) {
				foreach ($data['imbursement_date'] as $key => $value) {
					$imbursement[$key]['imbursement_date'] = set_date($data['imbursement_date'][$key]);
					$imbursement[$key]['details']    = ($data['details'][$key] != "") ? $data['details'][$key] : NULL;
					$imbursement[$key]['amount']  = ($data['amount'][$key] != "") ? round($data['amount'][$key], 2) : NULL;
					$imbursement[$key]['imbursement_application_id']    = (!empty($data['imbursement_application_id'][$key])) ? $data['imbursement_application_id'][$key] : NULL;
					$imbursement[$key]['unique_code'] = $data['unique_code'];
					$imbursement[$key]['employee_id'] = $employee_id['employee_id'];
					$imbursement[$key]['updated_by'] = $this->session->userdata('employee_id');
					$imbursement[$key]['updated_at'] = date('Y-m-d H:i:s');
				}
			}

			//remove empty details
			$c = function ($v) {
				return array_filter($v) != array();
			};
			$imbursement = array_filter($imbursement, $c);

			$files = $_FILES;
			$resp = $this->common_model->edit_imbursement($imbursement, $employee_id['employee_id'], $files);
			if (!empty($resp)) {
				$response = array('status' => 1, 'msg' => 'Updated successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function rejoin()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('9', 'v,a,e,d')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'leave';
		$page_data['page_name']    = 'rejoin';
		$page_data['page_title']   = 'Rejoin';
		$this->load->view('theme/user/main', $page_data);
	}

	function rejoin_status_list()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$code = $this->input->post('code');
		$employee_id = $this->common_model->employees_id_by_code($code);
		$rejoin_status_list  = $this->common_model->get_rejoin_status($employee_id);

		$assi = [];
		if (!empty($rejoin_status_list)) {

			foreach ($rejoin_status_list as $key => $value) {

				$assi[$key]['leave_type'] = $value['leave_type'];
				$assi[$key]['leave_status'] = rejoin_status($value['leave_status']);
				$assi[$key]['leave_date'] = get_date($value['leave_date']);
				$assi[$key]['rejoin_status'] = rejoin_status($value['rejoin_status']);
				$assi[$key]['total_days'] = $value['total_days'];
				$assi[$key]['paid'] = ($value['paid'] == 'yes') ? '<span class="text-success">PAID</span>' : '-';
				$assi[$key]['unpaid'] = ($value['unpaid'] == 'yes') ? '<span class="text-danger">UNPAID</span>' : '-';
				$assi[$key]['remarks'] = $value['remarks'];
				$assi[$key]['rejoin_date'] = ($value['rejoin_status'] == "yes") ? get_date($value['rejoin_date']) : '-';
				$assi[$key]['action'] = ($value['rejoin_status'] == "no") ? ('<button type="button" class="btn btn-sm btn-outline-success" title="Status" onclick="showAjaxModal(\'' . base_url('user/popup/rejoin_update/' . $value['leave_rejoin_id'] . '/' . $value['leave_application_id']) . '\',\'Rejoin\')"><i class="fa fa-edit"></i></button>') : '';
			}
		}
		echo json_encode($assi);
	}

	function rejoin_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('leave_rejoin_id', 'Rejoin id', 'trim|required');
		$this->form_validation->set_rules('leave_type_id', 'Leave type', 'trim|required');
		$this->form_validation->set_rules('employee_id', 'Employee id', 'trim|required');
		$this->form_validation->set_rules('rejoin_date', 'Date', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);

			//pr($data);

			$leave_date = $this->common_model->selectOne('employee_leave_rejoin', array('leave_rejoin_id' => $data['leave_rejoin_id']), '*');

			$param['rjdte'] = $data['rejoin_date'];
			$param['ltype'] = $data['leave_type_id'];
			$param['employee_id'] = $data['employee_id'];
			$param['rjid'] = $data['leave_rejoin_id'];
			$leave_split = $this->common_model->rejoin_leave_split($param);
			//pr($leave_split);exit;

			if (!empty($leave_split) && !empty($data['paid_status'])) {

				foreach ($leave_split as $key => $row) {

					$datetime1  = new DateTime(date('Y-m-d H:i:s', strtotime($row['leave_date'] . ' 00:00:00')));
					$datetime2  = new DateTime(date('Y-m-d H:i:s', strtotime($row['rejoin_date'] . ' 23:59:59')));
					$difference = $datetime2->diff($datetime1);
					$totdays    = ($difference->days + 1);

					//overriding paid/unpaid status

					if (isset($data['paid_status'][$key]) && $data['paid_status'][$key] == 'paid') {
						$row['paid'] = 'yes';
						$row['unpaid'] = 'no';
					}
					if (isset($data['paid_status'][$key]) && $data['paid_status'][$key] == 'unpaid') {
						$row['paid'] = 'no';
						$row['unpaid'] = 'yes';
					}

					$param1 = array(
						'leave_application_id' => $leave_date['leave_application_id'],
						'employee_id' => $param['employee_id'],
						'leave_status' => 'no',
						'rejoin_status' => 'yes',
						'leave_date' => $row['leave_date'],
						'rejoin_date' => $row['rejoin_date'],
						'total_days' => $totdays,
						'paid' => $row['paid'],
						'unpaid' => $row['unpaid'],
						'remarks' => $data['remarks'],
						'updated_at' => date('Y-m-d H:i:s'),
						'updated_by' => $this->session->userdata('employee_id')
					);

					//pr($param1);exit;

					if (!empty($row['leave_rejoin_id'])) {
						$this->common_model->update($param1, array('leave_rejoin_id' => $data['leave_rejoin_id']), 'employee_leave_rejoin');
					} else {
						$this->common_model->insert($param1, 'employee_leave_rejoin');
					}
				}

				//update employee status back to active after leave
				$this->db->where('employee_id', $data['employee_id']);
				$this->db->update('employee', array('status' => 'active'));

				$response = array('status' => 1, 'msg' => 'Updated successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function employees_master()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('4', 'v,a,e,d')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['page_name']    = 'employees_master';
		$page_data['menu']         = 'reports';
		$page_data['page_title']   = 'Employees Master';
		$this->load->view('theme/user/main', $page_data);
	}

	function employees_master_ajax()
	{

		$all = $this->common_model->employees_master();
		//pr($all);

		$employees['data'] = [];

		if (!empty($all)) {

			foreach ($all as $key => $value) {
				$employees['data'][$key]['name'] = $value['name'];
				$employees['data'][$key]['code'] = $value['code'];
				$employees['data'][$key]['designation'] = $value['designation'];
				$employees['data'][$key]['department'] = $value['department'];

				$employees['data'][$key]['date_of_birth'] = $value['date_of_birth'];
				$employees['data'][$key]['age'] = $value['age'];
				$employees['data'][$key]['date_of_join'] = $value['date_of_join'];
				$employees['data'][$key]['last_work_date'] = $value['last_work_date'];
				$employees['data'][$key]['current_address'] = $value['current_address'];
				$employees['data'][$key]['permanent_address'] = $value['permanent_address'];
				$employees['data'][$key]['phone_current'] = $value['phone_current'];
				$employees['data'][$key]['phone_home'] = $value['phone_home'];
				$employees['data'][$key]['phone_office'] = $value['phone_office'];
				$employees['data'][$key]['email'] = $value['email'];
				$employees['data'][$key]['personal_email'] = $value['personal_email'];
				$employees['data'][$key]['nationality'] = $value['nationality'];
				$employees['data'][$key]['country'] = $value['country'];
				$employees['data'][$key]['overtime'] = $value['overtime'];
				$employees['data'][$key]['passport_number'] = $value['passport_number'];
				$employees['data'][$key]['passport_expiry'] = $value['passport_expiry'];
				$employees['data'][$key]['passport_with'] = $value['passport_with'];
				$employees['data'][$key]['visa_number'] = $value['visa_number'];
				$employees['data'][$key]['visa_expiry'] = $value['visa_expiry'];
				$employees['data'][$key]['labour_number'] = $value['labour_number'];
				$employees['data'][$key]['labour_expiry'] = $value['labour_expiry'];
				$employees['data'][$key]['emirate_number'] = $value['emirate_number'];
				$employees['data'][$key]['emirate_expiry'] = $value['emirate_expiry'];
				$employees['data'][$key]['unified_no'] = $value['unified_no'];
				$employees['data'][$key]['basic_pay'] = $value['basic_pay'];
				$employees['data'][$key]['hra'] = $value['hra'];
				$employees['data'][$key]['transport'] = $value['transport'];
				$employees['data'][$key]['special_allowance'] = $value['special_allowance'];
				$employees['data'][$key]['others'] = $value['others'];
				$employees['data'][$key]['status'] = employee_status_c($value['status']);

				$education = "";
				if (!empty($value['education'])) {

					foreach ($value['education'] as $row) {
						$education .= '<strong>Qualification:</strong> ' . $row['name'] . "<br/><strong>Year:</strong> " . $row['year'] . "<br/><strong>Institution:</strong> " . $row['institution'] . "<br/><br/>";
					}
				}
				$employees['data'][$key]['education'] = $education;

				$insurance = "";
				if (!empty($value['insurance'])) {

					foreach ($value['insurance'] as $row) {
						$insurance .= '<strong>Agency:</strong> ' . $row['company'] . "<br/><strong>Cost:</strong> " . $row['cost'] . "<br/><strong>Expiry:</strong> " . get_date($row['expiry']) . "<br/><br/>";
					}
				}
				$employees['data'][$key]['insurance'] = $insurance;

				$bank = "";
				if (!empty($value['bank_details'])) {

					foreach ($value['bank_details'] as $row) {
						$bank .= '<strong>Bank Name:</strong> ' . $row['bank_name'] . "<br/><strong>Act.No.:</strong> " . $row['account_number'] . "<br/><strong>IBAN No.:</strong> " . $row['iban_number'] . "<br/><strong>Swift code:</strong> " . $row['swift_code'] . "<br/><br/>";
					}
				}
				$employees['data'][$key]['bank_details'] = $bank;

				$heads = "";
				if (!empty($value['heads'])) {

					foreach ($value['heads'] as $row) {
						$heads .= '<strong>' . $row['role'] . ":</strong> " . $row['name'] . "<br/>";
					}
				}
				$employees['data'][$key]['heads'] = $heads;

				$roles = "";
				if (!empty($value['roles'])) {

					foreach ($value['roles'] as $row) {
						$roles .= $row['name'] . "<br/>";
					}
				}
				$employees['data'][$key]['roles'] = $roles;

				$locations = "";
				if (!empty($value['locations'])) {

					foreach ($value['locations'] as $row) {
						$locations .= '<strong>Location:</strong> ' . $row['name'] . '<br/><strong>Start date:</strong> ' . get_date($row['start_date']) . '<br/><strong>End date:</strong> ' . get_date($row['end_date']) . "<br/><br/>";
					}
				}
				$employees['data'][$key]['locations'] = $locations;
			}
		}
		echo json_encode($employees);
	}

	function leave_report()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('4', 'v,a,e,d')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'reports';
		$page_data['page_name']    = 'leave_report';
		$page_data['page_title']   = 'Leave Report';
		$this->load->view('theme/user/main', $page_data);
	}

	function leave_history_list()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$code = $this->input->post('code');
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');
		$employee_id = $this->common_model->employees_id_by_code($code);
		$leave_history_list  = $this->common_model->leave_history_list($employee_id, $from_date, $to_date);
		//pr($leave_history_list);
		$assi = [];
		if (!empty($leave_history_list)) {
			foreach ($leave_history_list as $key => $value) {

				$assi[$key]['profile_photo'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><img src="' . getUserImage($value['profile_photo']) . '" class="rounded-circle avatar" alt="' . $value['name'] . '"></a>';
				$assi[$key]['name'] = '<a href="' . base_url() . 'profile/' . $value['code'] . '" title="View profile" target="_blank"><h6 class="mb-0">' . $value['name'] . '</h6></a>';
				$assi[$key]['code'] = $value['code'];

				$assi[$key]['leave_type']  = $value['title'];
				$assi[$key]['leave_date']  = get_date($value['leave_date']);
				$assi[$key]['rejoin_date'] = get_date($value['rejoin_date']);
				$assi[$key]['total_days']  = $value['total_days'];
				$assi[$key]['paid']        = ($value['paid'] == 'yes') ? '<span class="text-success">PAID</span>' : '-';
				$assi[$key]['unpaid']      = ($value['unpaid'] == 'yes') ? '<span class="text-danger">UNPAID</span>' : '-';
				$assi[$key]['remarks']     = $value['remarks'];
			}
		}
		echo json_encode($assi);
	}

	function leave_annual_list()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$code = $this->input->post('code');
		//$from_date = $this->input->post('from_date');
		//$to_date = $this->input->post('to_date');
		$employee_id = $this->common_model->employees_id_by_code($code);
		$leave_annual_list  = $this->common_model->leave_annual_list($employee_id);

		$assi = [];
		if (!empty($leave_annual_list)) {
			foreach ($leave_annual_list as $key => $value) {
				$assi[$key]['start_date']  = get_date($value['start_date']);
				$assi[$key]['end_date']  = get_date($value['end_date']);
				$assi[$key]['total_annual'] = $value['carry_fwd'] + $value['total_annual'];
				$assi[$key]['annual_enjoyed']  = $value['annual_enjoyed'];
				$assi[$key]['balance_annual']        = $value['balance_annual'];
			}
		}
		echo json_encode($assi);
	}

	function passport_status_report()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		if (!check_permission('4', 'v,a,e,d')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('dashboard', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'reports';
		$page_data['page_name']    = 'passport_status_report';
		$page_data['page_title']   = 'Passport Status Report';
		$this->load->view('theme/user/main', $page_data);
	}

	function passport_status_ajax()
	{

		$all = $this->common_model->employees_master();

		$employees['data'] = [];

		if (!empty($all)) {

			foreach ($all as $key => $value) {

				if ($value['passport_number'] != "") {
					$employees['data'][$key]['name'] = $value['name'];
					$employees['data'][$key]['code'] = $value['code'];
					$employees['data'][$key]['designation'] = $value['designation'];
					$employees['data'][$key]['department'] = $value['department'];
					$employees['data'][$key]['passport_number'] = $value['passport_number'];
					$employees['data'][$key]['passport_expiry'] = $value['passport_expiry'];
					$employees['data'][$key]['passport_with'] = ($value['passport_with'] == 'employee') ? '<span class="badge badge-success">' . $value['passport_with'] . '</span>' : '<span class="badge badge-warning">' . $value['passport_with'] . '</span>';
					$employees['data'][$key]['passport_with_remarks'] = $value['passport_with_remarks'];
				}
			}
		}
		$employees['data'] = array_values($employees['data']);
		echo json_encode($employees);
	}

	function checkLeaveEligibility()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$code = $this->input->post('code');
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');
		if (!empty($code)) {

			$employee_id = $this->common_model->employees_id_by_code($code);
			$session_id = $this->session->userdata('employee_id');

			if ($employee_id != $session_id) {
				$check_head  = $this->common_model->check_in_heads_list($employee_id, $session_id);
				if (empty($check_head)) {
					echo 'You are not authorized to check eligibility!';
					exit;
				}
			}

			if ($employee_id == "") {

				echo 'Invalid employee details!';
				exit;
			}

			$eligibility = $this->common_model->checkLeaveEligibility($employee_id);
			$totdays = 0;
			if ($from_date != "" && $to_date != "") {
				$from_date  = set_date($from_date);
				$to_date    = set_date($to_date);
				$datetime1  = new DateTime(date('Y-m-d H:i:s', strtotime($from_date . ' 00:00:00')));
				$datetime2  = new DateTime(date('Y-m-d H:i:s', strtotime($to_date . ' 23:59:59')));
				$difference = $datetime2->diff($datetime1);
				$totdays    = ($difference->days + 1);
			}
			$eligibility['applied_days'] = $totdays;
			//pr($eligibillity);
			if (!empty($eligibility)) {
				$this->load->view('user/ajax-leave-eligibility', $eligibility);
			} else {
				echo 'Something went wrong! Please try again after sometimes.';
				exit;
			}
		} else {
			echo 'Invalid employee code!';
			exit;
		}
	}

	function rejoin_leave_split()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$data['rjdte'] = $this->input->post('rjdte');
		$data['ltype'] = $this->input->post('ltype');
		$data['employee_id'] = $this->input->post('employee_id');
		$data['rjid'] = $this->input->post('rjid');

		$page_data['split'] = $this->common_model->rejoin_leave_split($data);
		$this->load->view('user/ajax_leave_split', $page_data);
	}

	//check employee code already assigned
	function checkDupCode()
	{
		$code = $this->input->post('code');
		$employee_id = $this->input->post('employee_id');
		if (!empty($code) && trim($code) != "") {
			$exist = $this->common_model->checkDupCode($code, $employee_id);
			if ($exist) {
				echo 'false';
			} else {
				echo 'true';
			}
		}
	}

	function change_password()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
		$this->form_validation->set_rules('code', 'Code', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);
			$employee_id = $this->common_model->employees_id_by_code($data['code']);
			if (!empty($employee_id)) {

				$resp = $this->common_model->update(array('password' => md5($data['password'])), array('employee_id' => $employee_id), 'employee');
				if (!empty($resp)) {
					$response = array('status' => 1, 'msg' => 'Password updated successfully!');
					echo json_encode($response);
					exit;
				} else {
					$response = array('status' => 0, 'msg' => 'Something went wrong!');
					echo json_encode($response);
					exit;
				}
			} else {
				$response = array('status' => 0, 'msg' => 'Invalid employee!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function delete_leave_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('leave_application_id', 'Leave application id', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {
			$data = $this->input->post(NULL, true);

			if (!check_permission('3', 'd')) {
				$response = array('status' => 0, 'msg' => 'Permission denied!');
				echo json_encode($response);
				exit;
			}

			$resp = $this->common_model->delete_leave($data['leave_application_id']);
			if (!empty($resp)) {
				$response = array('status' => 1, 'msg' => 'Deleted successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function delete_imbursement_process()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('unique_code', 'Unique code', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');

		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);

			if (!check_permission('2', 'd')) {
				$response = array('status' => 0, 'msg' => 'Permission denied!');
				echo json_encode($response);
				exit;
			}

			$exist = $this->db->get_where('imbursement_application', array('unique_code' => $data['unique_code']))->result_array();

			if (!empty($exist)) {

				foreach ($exist as $row1) {

					$get_docs = $this->db->get_where("imbursement_docs", array('imbursement_application_id' => $row1["imbursement_application_id"]))->result_array();
					//remove docs 
					if (!empty($get_docs)) {
						foreach ($get_docs as $row) {
							if (file_exists('assets/uploads/user_docs/' . $row1['employee_id'] . '/imbursement/' . $row['doc_path'])) {
								unlink('assets/uploads/user_docs/' . $row1['employee_id'] . '/imbursement/' . $row['doc_path']);
							}
						}
					}
				}

				//delete main entry
				$resp = $this->db->delete('imbursement_application', array('unique_code' => $data['unique_code']));
				$response = array('status' => 1, 'msg' => 'Removed successfully!');
				echo json_encode($response);
				exit;
			} else {
				$response = array('status' => 0, 'msg' => 'Details does not exist!');
				echo json_encode($response);
				exit;
			}
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function print_leave_page($leave_application_id)
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$page_data['page_type']    = 'user';
		$page_data['menu']         = 'report';
		$page_data['page_name']    = 'print_leave_page';
		$page_data['page_title']   = 'Print Leave Application';
		$page_data['details']      = $this->common_model->leave_by_id($leave_application_id);

		if (empty($page_data['details'])) {
			$this->session->set_flashdata('error', 'Invalid Leave Application!');
			redirect('leave-application-status', 'refresh');
		}

		$this->load->view('user/print_leave_page', $page_data);
	}

	//check employee code exist
	function checkExistCode()
	{
		$code = $this->input->post('code');
		$employee_id = $this->input->post('employee_id');
		if (!empty($code) && trim($code) != "") {
			$exist = $this->common_model->checkDupCode($code, $employee_id);
			if ($exist) {
				echo 'true';
			} else {
				echo 'false';
			}
		}
	}

	function checkDupEmail()
	{
		$email = $this->input->post('email');
		$code = $this->input->post('code');
		if (!empty($email) && trim($email) != "") {
			$exist = $this->common_model->checkDupEmail($email, $code);
			if ($exist) {
				echo 'false';
			} else {
				echo 'true';
			}
		}
	}

	function popup($page_name = '', $param2 = '', $param3 = '', $param4 = '')
	{

		$page_data['param2']        =   $param2;
		$page_data['param3']        =   $param3;
		$page_data['param4']        =   $param4;

		$this->load->view('user/' . $page_name, $page_data);
	}

	private function get_unique_code()
	{

		$code_feed = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyv0123456789";
		$code_length = 8;  // Set this to be your desired code length
		$final_code = "";
		$feed_length = strlen($code_feed);

		for ($i = 0; $i < $code_length; $i++) {
			$feed_selector = rand(0, $feed_length - 1);
			$final_code .= substr($code_feed, $feed_selector, 1);
		}
		return $final_code;
	}
}