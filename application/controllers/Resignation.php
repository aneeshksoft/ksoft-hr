<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Resignation extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		ini_set('MAX_EXECUTION_TIME', '-1');
		ini_set('max_input_vars', 10000);
		$this->load->model('Resignation_model', 'resignation_model');
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

	function add_resignation()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		// if(!check_permission('1','a')) {
		//    $this->session->set_flashdata('error','Permission denied!');
		//    redirect('dashboard', 'refresh');
		// }

		$page_data['page_type']    = 'user/resignation';
		$page_data['menu']         = 'resignation';
		$page_data['page_name']    = 'add_resignation';
		$page_data['page_title']   = 'Add New';
		$page_data['employees']  	= $this->resignation_model->get_all_employees();
		$page_data['reasons']  		= $this->resignation_model->get_all('resignation_reason_master');
		$page_data['role']  		= $this->session->userdata('type');
		$employee_name = "";
		$employee_details = $this->resignation_model->get_employee_by_id($this->session->userdata('employee_id'));
		if ($page_data['role'] == 2) {
			$employee_name = $employee_details->name;
		}
		$page_data['employee_name']  		= $employee_name;
		$this->load->view('theme/user/main', $page_data);
	}
	function add_resignation_process()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		$role = $this->session->userdata('type');

		$this->form_validation->set_rules('reason_id', 'Reason', 'trim|required');
		$this->form_validation->set_rules('relieving_date', 'Relieving Date', 'trim|required');
		if ($role == 1)
			$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required');

		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {
			$data = $this->input->post(NULL, true);
			$role_id = $this->session->userdata('type');
			$employee_id = "";
			if ($role_id == 1) {
				$employee_id = $data['employee_id'];
			} else if ($role_id == 2) {
				$employee_id = $this->session->userdata('employee_id');
				$data['employee_id'] = $employee_id;
			}
			if (empty($employee_id)) {
				$response = array('status' => 0, 'msg' => "Employee ID not found");
				echo json_encode($response);
				exit;
			}
			$resignation = $this->resignation_model->get_resignation_by_employee_id($employee_id, 16);
			if (!empty($resignation)) {
				$response = array('status' => 0, 'msg' => "A pending resignation application exists for this employee");
				echo json_encode($response);
				exit;
			}
			$abs_resignation = $this->resignation_model->get_absconding_resignation_by_employee_id($employee_id, array(4, 5));
			if (!empty($abs_resignation)) {
				$response = array('status' => 0, 'msg' => "A pending absconding resignation application exists for this employee");
				echo json_encode($response);
				exit;
			}

			$data['is_absconding'] = empty($data['is_absconding']) ? 0 : 1;

			$last_id = $this->resignation_model->add_resignation($data);
			if (!empty($last_id)) {
				$response = array('status' => 1, 'msg' => "Resignation added");
				echo json_encode($response);
			} else {

				$response = array('status' => 0, 'msg' => "Not added");
				echo json_encode($response);
			}
			exit;
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}
	function view_resignation()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		// if(!check_permission('1','a')) {
		//    $this->session->set_flashdata('error','Permission denied!');
		//    redirect('dashboard', 'refresh');
		// }

		$page_data['page_type']    = 'user/resignation';
		$page_data['menu']         = 'resignation';
		$page_data['page_name']    = 'view_resignations';
		$page_data['page_title']   = 'View Resignation';
		//$page_data['employees']  	= $this->resignation_model->get_all_employees();
		//$page_data['designations'] = $this->common_model->selectAll('designations','','');
		//$page_data['roles']  = $this->common_model->selectAll('roles', '', '');
		$this->load->view('theme/user/main', $page_data);
	}


	function view_resignation_details($resig_id)
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		// if(!check_permission('1','a')) {
		//    $this->session->set_flashdata('error','Permission denied!');
		//    redirect('dashboard', 'refresh');
		// }
		$role = $this->session->userdata('type');
		$page_data['role']         		= $role;
		$page_data['page_type']    		= 'user/resignation';
		$page_data['menu']         		= 'resignation';
		$page_data['page_name']    		= 'view_resignation_details';
		$page_data['page_title']   		= 'View Resignation';
		$page_data['resignation']  		= $this->resignation_model->get_resignation_by_id($resig_id);
		$page_data['employee_details']  = $this->resignation_model->get_employee_by_id($page_data['resignation']->employee_id);
		$this->load->view('theme/user/main', $page_data);
	}

	function get_all_resignations_ajax()
	{
		$data = $this->input->post(null, true);
		$all = $this->resignation_model->get_all_resignations(null, null);
		$role = $this->session->userdata('type');
		$employee_id = $this->session->userdata('employee_id');
		$session_employee_details  		= $this->resignation_model->get_employee_by_id($employee_id);
		$pecial_role =

			$employees['data'] = [];
		$sno = 0;
		if (!empty($all)) {
			foreach ($all as $key => $value) {
				$sno++;
				$employees['data'][$key]['sno'] = $sno;
				$employees['data'][$key]['id'] = $value->id;
				$employees['data'][$key]['employee_name'] = $value->employee_name;
				$employees['data'][$key]['employee_email'] = $value->employee_email;
				$employees['data'][$key]['reason'] = $value->reason;
				$employees['data'][$key]['relieving_date'] = empty($value->relieving_date) ? '' : date('d-m-Y', strtotime($value->relieving_date));
				$employees['data'][$key]['status'] = resignation_status_format($value->status);

				//$clearance_certificate_status_array = array(1, 2);
				//termination_check_array = array(1, 2);
				$resignation_cancelled = ($value->status == 16) ? true : false;
				$view_url = base_url('view-resignation-details/' . $value->id);
				$exit_interview_submitted = false;
				if ($value->exit_interview_submitted == 1) {
					//echo " 1 ";
					$exit_interview_submitted = true;
				} else if ($value->is_absconding == 1) {
					//echo " 2 ";
					$exit_interview_submitted = true;
				}
				//echo "exit_interview_submitted ".$value->is_absconding."--";
				$cancel_check_array = array(12, 14, 15);

				$edit_button =  base_url('edit-resignation/' . $value->id);
				$edit_button = ($employee_id == $value->added_by && $value->status == 1) ? '<a class="dropdown-item" href="' . $edit_button . '" onclick="">Edit</a>' : '';

				$delete_button = ($employee_id == $value->added_by && !$resignation_cancelled && !in_array($value->status, $cancel_check_array)) ? '<button class="dropdown-item"  onclick="cancelResignation(\'' . $value->id . '\')">Cancel</button>' : '';

				$exit_interview_button =  base_url('exit-interview/' . $value->id);
				$exit_interview_button = ($value->is_absconding != 1 && !$resignation_cancelled && $value->exit_interview_triggered == 1 && !$exit_interview_submitted && $employee_id == $value->employee_id) ? '<a class="dropdown-item" href="' . $exit_interview_button . '" onclick="">Add Exit Interview</a>' : '';

				$view_exit_interview_button =  base_url('view-exit-interview/' . $value->id);
				$view_exit_interview_button = ($value->is_absconding != 1 && !$resignation_cancelled && $exit_interview_submitted)  ? '<a class="dropdown-item" href="' . $view_exit_interview_button . '" onclick="">View Exit Interview</a>' : '';


				$clearance_certificate =  base_url('clearance-certificate/' . $value->id);
				$clearance_certificate = (!$resignation_cancelled && $exit_interview_submitted && $value->clearance_certificate_triiggered == 1
					&& $value->clearance_certificate_submitted == 0 && $employee_id != $value->employee_id) ? '<a class="dropdown-item" href="' . $clearance_certificate . '" onclick="">Add Clearance Certificate</a>' : '';

				$view_clearance_certificate =  base_url('view-clearance-certificate/' . $value->id);
				$view_clearance_certificate = ($role == 1 && in_array($value->status, array(12, 14, 15))) ? '<a class="dropdown-item" href="' . $view_clearance_certificate . '" onclick="">View Clearance Certificate</a>' : '';

				$termination_letter =  base_url('resignation/termination_letter/' . $value->id);
				$termination_letter = ($value->is_absconding == 1 && $role == 1 && in_array($value->status, array(12, 14, 15))) ? '<a class="dropdown-item" href="' . $termination_letter . '" onclick="">Termination Letter</a>' : '';

				$releaving_letter =  base_url('resignation/releaving_letter/' . $value->id);
				$releaving_letter = ($value->is_absconding != 1  &&  $role == 1 && in_array($value->status, array(12, 14, 15))) ? '<a class="dropdown-item" href="' . $releaving_letter . '" onclick="">Relieving Letter</a>' : '';

				// $absconding_letter =  base_url('resignation/absconding_letter/' . $value->id);
				// $absconding_letter = ($value->is_absconding == 1 && $role == 1) ? '<a class="dropdown-item" href="' . $absconding_letter . '" onclick="">Absconding Letter</a>' : '';

				$employees['data'][$key]['action'] = '<div class="btn-group">
				<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  Action
				</button>
				<div class="dropdown-menu dropdown-menu-right">
				<a class="dropdown-item" href="' . $view_url . '" onclick="">View Details</a>'   . $edit_button . $delete_button . $exit_interview_button . $view_exit_interview_button . $clearance_certificate . $view_clearance_certificate . $termination_letter . $releaving_letter .  '			  
				  
				</div>
			  </div>';
			}
		}
		echo json_encode($employees);
	}

	function delete_resignation()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		$data = $this->input->post(NULL, true);
		$res = $this->resignation_model->delete_resignation($data['resignation_id']);
		if (!empty($res)) {
			$response = array('status' => 1, 'msg' => "Resignation Deleted");
			echo json_encode($response);
			exit;
		} else {
			$response = array('status' => 0, 'msg' => "Not Deleted");
			echo json_encode($response);
			exit;
		}
	}
	function cancel_resignation()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		$data = $this->input->post(NULL, true);
		$res = $this->resignation_model->cancel_resignation($data['resignation_id']);
		if (!empty($res)) {
			$response = array('status' => 1, 'msg' => "Resignation Cancelled");
			echo json_encode($response);
			exit;
		} else {
			$response = array('status' => 0, 'msg' => "Not Cancelled");
			echo json_encode($response);
			exit;
		}
	}
	function edit_resignation($resig_id)
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		// if(!check_permission('1','a')) {
		//    $this->session->set_flashdata('error','Permission denied!');
		//    redirect('dashboard', 'refresh');
		// }

		$page_data['page_type']    	= 'user/resignation';
		$page_data['menu']         	= 'resignation';
		$page_data['page_name']    	= 'edit_resignation';
		$page_data['page_title']   	= 'Edit Resignation';
		$page_data['resignation']  	= $this->resignation_model->get_resignation_by_id($resig_id);
		$page_data['employees']  	= $this->resignation_model->get_all_employees();
		$page_data['reasons']  		= $this->resignation_model->get_all('resignation_reason_master');
		$page_data['role']  		= $this->session->userdata('type');
		$employee_name = "";
		$employee_details = $this->resignation_model->get_employee_by_id($this->session->userdata('employee_id'));
		if ($page_data['role'] == 2) {
			$employee_name = $employee_details->name;
		}
		$page_data['employee_name']  		= $employee_name;
		$this->load->view('theme/user/main', $page_data);
	}

	function edit_resignation_process()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		$this->form_validation->set_rules('reason_id', 'Reason', 'trim|required');
		$this->form_validation->set_rules('relieving_date', 'Relieving Date', 'trim|required');
		$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required');

		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {

			$data = $this->input->post(NULL, true);
			$role_id = $this->session->userdata('type');
			$employee_id = "";
			if ($role_id == 1) {
				$employee_id = $data['employee_id'];
			} else if ($role_id == 2) {
				$employee_id = $this->session->userdata('employee_id');
				$data['employee_id'] = $employee_id;
			}
			if (empty($employee_id)) {
				$response = array('status' => 0, 'msg' => "Employee ID not found");
				echo json_encode($response);
				exit;
			}
			$data['is_absconding'] = empty($data['is_absconding']) ? 0 : 1;

			$last_id = $this->resignation_model->edit_resignation($data);
			if (!empty($last_id)) {
				$response = array('status' => 1, 'msg' => "Data Updated");
				echo json_encode($response);
			} else {
				$response = array('status' => 0, 'msg' => "Data Not Updated");
				echo json_encode($response);
			}
			exit;
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function update_other_resignation_details_process()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		//$this->form_validation->set_rules('agreed_relieving_date', 'Agreed Relieving Date', 'trim|required');

		//$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		//if ($this->form_validation->run()) {

		$data = $this->input->post(NULL, true);
		$last_id = $this->resignation_model->update_other_resignation_details($data);
		if (!empty($last_id)) {
			$response = array('status' => 1, 'msg' => "Data Updated");
			echo json_encode($response);
		} else {
			$response = array('status' => 0, 'msg' => "Data Not Updated");
			echo json_encode($response);
		}
		exit;
		//} 
		//else 
		{
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}

	function add_absconding_resignation()
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		// if(!check_permission('1','a')) {
		//    $this->session->set_flashdata('error','Permission denied!');
		//    redirect('dashboard', 'refresh');
		// }

		$page_data['page_type']    = 'user/resignation';
		$page_data['menu']         = 'resignation';
		$page_data['page_name']    = 'add_absconding_resignation';
		$page_data['page_title']   = 'Add New';
		$page_data['employees']  	= $this->resignation_model->get_all_reportees();
		$page_data['reasons']  		= $this->resignation_model->get_all('resignation_reason_master');
		$page_data['role']  		= $this->session->userdata('type');
		$employee_name = "";
		$employee_details = $this->resignation_model->get_employee_by_id($this->session->userdata('employee_id'));
		if ($page_data['role'] == 2) {
			$employee_name = $employee_details->name;
		}
		$page_data['employee_name']  		= $employee_name;
		$this->load->view('theme/user/main', $page_data);
	}
	function add_absconding_resignation_process()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		$role = $this->session->userdata('type');

		$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required');
		$this->form_validation->set_rules('absconding_date', 'Date', 'trim|required');


		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		if ($this->form_validation->run()) {
			$data = $this->input->post(NULL, true);
			$role_id = $this->session->userdata('type');
			$employee_id = $data['employee_id'];

			if (empty($employee_id)) {
				$response = array('status' => 0, 'msg' => "Employee ID not found");
				echo json_encode($response);
				exit;
			}
			$resignation = $this->resignation_model->get_resignation_by_employee_id($employee_id, 16);
			if (!empty($resignation)) {
				$response = array('status' => 0, 'msg' => "A pending resignation application exists for this employee");
				echo json_encode($response);
				exit;
			}
			$abs_resignation = $this->resignation_model->get_absconding_resignation_by_employee_id($employee_id, array(4, 5));
			if (!empty($abs_resignation)) {
				$response = array('status' => 0, 'msg' => "A pending absconding resignation application exists for this employee");
				echo json_encode($response);
				exit;
			}

			$last_id = $this->resignation_model->add_absconding_resignation($data);

			if (!empty($last_id)) {
				$response = array('status' => 1, 'msg' => "Resignation added");
				echo json_encode($response);
			} else {

				$response = array('status' => 0, 'msg' => "Not added");
				echo json_encode($response);
			}
			exit;
		} else {
			$response = array('status' => 0, 'msg' => validation_errors());
			echo json_encode($response);
			exit;
		}
	}
	function view_absconding_resignation()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		// if(!check_permission('1','a')) {
		//    $this->session->set_flashdata('error','Permission denied!');
		//    redirect('dashboard', 'refresh');
		// }

		$page_data['page_type']    = 'user/resignation';
		$page_data['menu']         = 'resignation';
		$page_data['page_name']    = 'view_absconding_resignations';
		$page_data['page_title']   = 'View Resignation';
		$page_data['employees']  = $this->resignation_model->get_all_employees();
		//$page_data['designations'] = $this->common_model->selectAll('designations','','');
		$page_data['roles']  = $this->common_model->selectAll('roles', '', '');
		$this->load->view('theme/user/main', $page_data);
	}
	function get_all_absconding_resignations_ajax()
	{
		$data = $this->input->post(null, true);
		$all = $this->resignation_model->get_all_absconding_resignations(null, null);
		$role = $this->session->userdata('type');
		$employee_id = $this->session->userdata('employee_id');
		$sno = 0;
		$employees['data'] = [];
		if (!empty($all)) {
			foreach ($all as $key => $value) {
				$sno++;
				$employees['data'][$key]['sno'] = $sno;
				$employees['data'][$key]['id'] = $value->id;
				$employees['data'][$key]['employee_name'] = $value->employee_name;
				$employees['data'][$key]['employee_email'] = $value->employee_email;
				$employees['data'][$key]['employee_code'] = $value->employee_code;
				$employees['data'][$key]['comment'] = $value->comment;
				$employees['data'][$key]['absconding_date'] = empty($value->absconding_date) ? '' : date('d-m-Y', strtotime($value->absconding_date));
				$employees['data'][$key]['status'] = absconding_resignation_status_format($value->status);


				$resignation_cancelled = ($value->status == 5) ? true : false;
				$resignation_approved = ($value->status == 2) ? true : false;


				$view_url = base_url('view-absconding-resignation-details/' . $value->id);

				$edit_button =  base_url('edit-absconding-resignation/' . $value->id);
				$edit_button = ($value->status == 1) ? '<a class="dropdown-item" href="' . $edit_button . '" onclick="">Edit</a>' : '';

				$cancel_button = (!$resignation_cancelled && !in_array($value->status, array(4, 5))) ? '<button class="dropdown-item"  onclick="cancelResignation(\'' . $value->id . '\')">Cancel</button>' : '';

				$approve_button = (!$resignation_cancelled && !$resignation_approved && $role == 1 && $value->status == 1) ? '<button class="dropdown-item"  onclick="approveResignation(\'' . $value->id . '\')">Approve</button>' : '';

				$send_resignation_button = ($resignation_approved && $role == 1) ? '<button class="dropdown-item"  onclick="sendAbscondingLetter(\'' . $value->id . '\')">Send Absconding Letter</button>' : '';
				//echo " send ".$send_resignation_button;
				$start_resignation_process_button = ($value->status == 3 && $value->absconding_reminder_sent == 1 && $role == 1) ? '<button class="dropdown-item"  onclick="statrtResignationProcess(\'' . $value->id . '\')">Start Resignation Process</button>' : '';

				//$show_action=(!in_array($value->status,array(4,5)) && (!empty($cancel_button) && !empty($approve_button)&& !empty($send_resignation_button)&& !empty($start_resignation_process_button)))?;
				$employees['data'][$key]['action'] = (in_array($value->status, array(4, 5))) ? '' : '<div class="btn-group">
				<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  Action
				</button>
				<div class="dropdown-menu dropdown-menu-right">'
					. $cancel_button . $approve_button . $start_resignation_process_button . $send_resignation_button . '			  
				  
				</div>
			  </div>';
			}
		}
		echo json_encode($employees);
	}
	function update_absconding_resignation_status()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		$data = $this->input->post(NULL, true);
		$status = $data['status'];
		$status_text = "Success";
		if ($status == 2) {
			$status_text = "Resignation Approved";
		} else if ($status == 4) {
			$status_text = "Resignation Process Started";
		} else if ($status == 5) {
			$status_text = "Resignation Cancelled";
		}
		//$status_text = ($status == 2) ? 'Approved' : 'Cancelled';
		$res = $this->resignation_model->update_absconding_resignation($data['resignation_id'], $status);
		if (!empty($res)) {
			$response = array('status' => 1, 'msg' =>  $status_text);
			echo json_encode($response);
			exit;
		} else {
			$response = array('status' => 0, 'msg' => "Failed");
			echo json_encode($response);
			exit;
		}
	}

	function send_absconding_letter()
	{
		$data = $this->input->post(NULL, true);
		$absconding_resignation_id = $data['resignation_id'];

		$absconding_details = $this->resignation_model->get_absconding_resignation_by_id($absconding_resignation_id);
		$employee_details = $this->resignation_model->get_employee_by_id($absconding_details->employee_id);
		if (!empty($employee_details)) {
			$subject = "Unauthorized absence from work";
			//$body = "New resigantion application has been raised by $employee_details->name. Login to see more details";
			//_sendMail($subject, $body, $employee_details->email, $employee_details->name);
			$absconding_date = $absconding_details->absconding_date;
			$res = send_absconding_mail($subject, "", $employee_details->email, $employee_details->name, date('d-m-Y', strtotime($absconding_date)));
			if (!empty($res)) {
				$subject = "New Absconding Resignation raised";
				$body = "Mr/Mrs " . $employee_details->name . "(" . $employee_details->code . ") was marked as absconding. Please do the neddful";
				$all_it_clearance = $this->resignation_model->get_all_special_role_users("Clearance");
				foreach ($all_it_clearance as $row) {
					$emailToName = $row->name;
					$emailTo = $row->email;
					_sendMail($subject, $body, $emailTo, $emailToName);
				}
				$update_array = array("status" => 3, "absconding_letter_sent_date" => date('Y-m-d'));
				$this->db->update('absconding_resignation', $update_array, array("id" => $absconding_resignation_id));
				$response = array('status' => 1, 'msg' => "Absconding mail sent.");
				echo json_encode($response);
				exit;
			}
		}
		$response = array('status' => 0, 'msg' => "Absconding mail not sent.");
		echo json_encode($response);
		exit;
	}
	function trigger_absconding_reminder()
	{
		$this->resignation_model->trigger_absconding_reminder();
		echo "Success";
	}



	function add_exit_interview_details($resig_id)
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		// if(!check_permission('1','a')) {
		//    $this->session->set_flashdata('error','Permission denied!');
		//    redirect('dashboard', 'refresh');
		// }

		$page_data['page_type']    			= 'user/resignation';
		$page_data['menu']         			= 'resignation';
		$page_data['page_name']    			= 'edit_exit_interview_form';
		$page_data['page_title']   			= 'Exit Interview';
		$page_data['reasons']  				= $this->resignation_model->get_all('resignation_reason_master');
		$page_data['ratings']  				= $this->resignation_model->get_all('exit_interview_rating_master');
		$page_data['role']  				= $this->session->userdata('type');

		$page_data['resignation']  			= $this->resignation_model->get_resignation_by_id($resig_id);
		$page_data['interview']  			= $this->resignation_model->get_exit_interview_details_by_resignation_id($resig_id);
		$page_data['resignation_id']  		= $resig_id;
		$page_data['exit_interview_id']  	= $page_data['interview']->id;
		$page_data['employee_id']  			= $page_data['resignation']->employee_id;
		$page_data['employee_details']  	= $this->resignation_model->get_employee_by_id($page_data['employee_id']);
		$this->load->view('theme/user/main', $page_data);
	}

	function add_exit_interview_process()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		$role = $this->session->userdata('type');

		//$this->form_validation->set_rules('reason_id', 'Reason', 'trim|required');
		//$this->form_validation->set_rules('relieving_date', 'Relieving Date', 'trim|required');
		//if ($role == 1)
		//	$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required');

		//$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		//if ($this->form_validation->run()) 
		{
			$data = $this->input->post(NULL, true);
			$role_id = $this->session->userdata('type');

			// $resignation = $this->resignation_model->get_resignation_by_employee_id($employee_id, 16);
			// if (!empty($resignation)) {
			// 	$response = array('status' => 0, 'msg' => "A pending resignation application exists for this employee");
			// 	echo json_encode($response);
			// 	exit;
			// }

			// $data['is_absconding'] = empty($data['is_absconding']) ? 0 : 1;

			$last_id = $this->resignation_model->edit_exit_interview($data);
			if (!empty($last_id)) {
				$response = array('status' => 1, 'msg' => "Exit Interview Details added", "Resp" => $last_id);
				echo json_encode($response);
			} else {

				$response = array('status' => 0, 'msg' => "Not added");
				echo json_encode($response);
			}
			exit;
		}
		// else {
		// 	$response = array('status' => 0, 'msg' => validation_errors());
		// 	echo json_encode($response);
		// 	exit;
		// }
	}
	function edit_exit_interview_details($interview_id)
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		// if(!check_permission('1','a')) {
		//    $this->session->set_flashdata('error','Permission denied!');
		//    redirect('dashboard', 'refresh');
		// }

		$page_data['page_type']    = 'user/resignation';
		$page_data['menu']         = 'resignation';
		$page_data['page_name']    = 'exit_interview_form';
		$page_data['page_title']   = 'Add New';
		$page_data['reasons']  		= $this->resignation_model->get_all('resignation_reason_master');
		$page_data['ratings']  		= $this->resignation_model->get_all('exit_interview_rating_master');
		$page_data['role']  		= $this->session->userdata('type');


		$page_data['employee_details'] =
			$page_data['resignation']  			= $this->resignation_model->get_resignation_by_id($resig_id);
		$page_data['resignation_id']  		= $resig_id;
		$page_data['employee_id']  			= $page_data['resignation']->employee_id;
		$page_data['employee_details']  		= $this->resignation_model->get_employee_by_id($page_data['employee_id']);

		$this->load->view('theme/user/main', $page_data);
	}
	function trigger_exit_interview()
	{
		$this->resignation_model->trigger_exit_interview();
		echo "Success";
	}

	function view_exit_interview($resig_id)
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		// if(!check_permission('1','a')) {
		//    $this->session->set_flashdata('error','Permission denied!');
		//    redirect('dashboard', 'refresh');
		// }
		$reasosns = $this->resignation_model->get_all('resignation_reason_master');
		$ratings = $this->resignation_model->get_all('exit_interview_rating_master');
		$interview =	$this->resignation_model->get_exit_interview_details_by_resignation_id($resig_id);
		$rating_text = array_column($ratings, 'name');
		$reason_text = array_column($reasosns, 'name');
		$yes_or_no_text = array('Yes', 'No');
		//$rating_text=array("");
		//$reason_text=array("",$reason_text_db);
		//array_push($rating_text,$rating_text_db);
		//array_push($reason_text,$reason_text_db);
		//echo json_encode($reason_text);
		//exit;
		//echo 	$rating_text[$interview->section_3_question_1];
		//exit;
		//echo $interview->section_3_question_2;	
		if (!empty($interview)) {
			$interview->section_2_question_1 = @$reason_text[$interview->section_2_question_1 - 1];
			$interview->section_2_question_12 = @$yes_or_no_text[$interview->section_2_question_12 - 1];
			$interview->section_2_question_15 = @$yes_or_no_text[$interview->section_2_question_15 - 1];

			$interview->section_3_question_1 = @$rating_text[$interview->section_3_question_1 - 1];
			$interview->section_3_question_2 = @$rating_text[$interview->section_3_question_2 - 1];
			$interview->section_3_question_3 = @$rating_text[$interview->section_3_question_3 - 1];
			$interview->section_3_question_4 = @$rating_text[$interview->section_3_question_4 - 1];
			$interview->section_3_question_5 = @$rating_text[$interview->section_3_question_5 - 1];
			$interview->section_3_question_6 = @$rating_text[$interview->section_3_question_6 - 1];
			$interview->section_3_question_7 = @$rating_text[$interview->section_3_question_7 - 1];
			$interview->section_3_question_8 = @$rating_text[$interview->section_3_question_8 - 1];

			$interview->section_4_question_1 = @$rating_text[$interview->section_4_question_1 - 1];
			$interview->section_4_question_2 = @$rating_text[$interview->section_4_question_2 - 1];
			$interview->section_4_question_3 = @$rating_text[$interview->section_4_question_3 - 1];
			$interview->section_4_question_4 = @$rating_text[$interview->section_4_question_4 - 1];
			$interview->section_4_question_5 = @$rating_text[$interview->section_4_question_5 - 1];
			$interview->section_4_question_6 = @$rating_text[$interview->section_4_question_6 - 1];
			$interview->section_4_question_7 = @$rating_text[$interview->section_4_question_7 - 1];
			$interview->section_4_question_8 = @$rating_text[$interview->section_4_question_8 - 1];
			$interview->section_4_question_9 = @$rating_text[$interview->section_4_question_9 - 1];
			$interview->section_4_question_10 = @$rating_text[$interview->section_4_question_10 - 1];
		}
		//echo json_encode($interview->section_3_question_2);	
		//$interview->section_3_question_2=4;

		$page_data['page_type']    			= 'user/resignation';
		$page_data['menu']         			= 'resignation';
		$page_data['page_name']    			= 'view_exit_interview';
		$page_data['page_title']   			= 'Exit Interview';
		//$page_data['reasons']  				= $this->resignation_model->get_all('resignation_reason_master');
		//$page_data['ratings']  				= $this->resignation_model->get_all('exit_interview_rating_master');
		$page_data['role']  				= $this->session->userdata('type');

		$page_data['resignation']  			= $this->resignation_model->get_resignation_by_id($resig_id);
		$page_data['interview']  			= $interview;
		$page_data['resignation_id']  		= $resig_id;
		$page_data['exit_interview_id']  	= $page_data['interview']->id;
		$page_data['employee_id']  			= $page_data['resignation']->employee_id;
		$page_data['employee_details']  	= $this->resignation_model->get_employee_by_id($page_data['employee_id']);
		$this->load->view('theme/user/main', $page_data);
	}

	function update_exit_interview_status()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		$data = $this->input->post(NULL, true);
		$res = $this->resignation_model->update_exit_interview_status($data);
		if (!empty($res)) {
			$response = array('status' => 1, 'msg' => "Exit interview completed");
			echo json_encode($response);
			exit;
		} else {
			$response = array('status' => 0, 'msg' => "Data not updated");
			echo json_encode($response);
			exit;
		}
	}

	function trigger_clearance_certificate()
	{
		$this->resignation_model->trigger_clearance_certificate();
		echo "Success";
	}


	function add_clearance_certificate($resig_id)
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		// if(!check_permission('1','a')) {
		//    $this->session->set_flashdata('error','Permission denied!');
		//    redirect('dashboard', 'refresh');
		// }

		$page_data['page_type']    	= 'user/resignation';
		$page_data['menu']         	= 'resignation';
		$page_data['page_name']    	= 'edit_clearance_certificate';
		$page_data['page_title']   	= 'Clearance Certiificate';
		$page_data['reasons']  		= $this->resignation_model->get_all('resignation_reason_master');
		$page_data['ratings']  		= $this->resignation_model->get_all('exit_interview_rating_master');
		$page_data['role']  		= $this->session->userdata('type');

		$clearance_certificate 		= $this->resignation_model->get_clearance_certificate_by_resignation_id($resig_id);
		$page_data['clearance_certificate_id'] = empty($clearance_certificate) ? 0 : $clearance_certificate->id;
		$page_data['resignation']  			= $this->resignation_model->get_resignation_by_id($resig_id);
		$page_data['resignation_id']  		= $resig_id;
		$page_data['employee_id']  			= $page_data['resignation']->employee_id;
		$page_data['certificate']  			= $clearance_certificate;
		$page_data['employee_details']  		= $this->resignation_model->get_employee_by_id($page_data['employee_id']);
		$session_employee_id = $this->session->userdata('employee_id');
		$page_data['session_employee_id']  		= $session_employee_id;
		$page_data['session_employee_details']  		= $this->resignation_model->get_employee_by_id($session_employee_id);
		$this->load->view('theme/user/main', $page_data);
	}

	function add_clearance_certificate_on_job_process()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		$role = $this->session->userdata('type');

		//$this->form_validation->set_rules('reason_id', 'Reason', 'trim|required');
		//$this->form_validation->set_rules('relieving_date', 'Relieving Date', 'trim|required');
		//if ($role == 1)
		//	$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required');

		//$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		//if ($this->form_validation->run()) 
		{
			$data = $this->input->post(NULL, true);
			$role_id = $this->session->userdata('type');

			// $resignation = $this->resignation_model->get_resignation_by_employee_id($employee_id, 16);
			// if (!empty($resignation)) {
			// 	$response = array('status' => 0, 'msg' => "A pending resignation application exists for this employee");
			// 	echo json_encode($response);
			// 	exit;
			// }

			// $data['is_absconding'] = empty($data['is_absconding']) ? 0 : 1;

			$last_id = $this->resignation_model->add_clearance_certificate_on_job($data);
			if (!empty($last_id)) {
				$response = array('status' => 1, 'msg' => "Data added");
				echo json_encode($response);
			} else {

				$response = array('status' => 0, 'msg' => "Not added");
				echo json_encode($response);
			}
			exit;
		}
		// else {
		// 	$response = array('status' => 0, 'msg' => validation_errors());
		// 	echo json_encode($response);
		// 	exit;
		// }
	}

	function update_clearance_certificate_it_clearance_process()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		//$role = $this->session->userdata('type');

		//$this->form_validation->set_rules('reason_id', 'Reason', 'trim|required');
		//$this->form_validation->set_rules('relieving_date', 'Relieving Date', 'trim|required');
		//if ($role == 1)
		//	$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required');

		//$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		//if ($this->form_validation->run()) 
		{
			$data = $this->input->post(NULL, true);
			$role_id = $this->session->userdata('type');

			// $resignation = $this->resignation_model->get_resignation_by_employee_id($employee_id, 16);
			// if (!empty($resignation)) {
			// 	$response = array('status' => 0, 'msg' => "A pending resignation application exists for this employee");
			// 	echo json_encode($response);
			// 	exit;
			// }

			// $data['is_absconding'] = empty($data['is_absconding']) ? 0 : 1;

			$last_id = $this->resignation_model->update_clearance_certificate_it_clearance($data);
			if (!empty($last_id)) {
				$response = array('status' => 1, 'msg' => "Data added");
				echo json_encode($response);
			} else {

				$response = array('status' => 0, 'msg' => "Not added");
				echo json_encode($response);
			}
			exit;
		}
		// else {
		// 	$response = array('status' => 0, 'msg' => validation_errors());
		// 	echo json_encode($response);
		// 	exit;
		// }
	}
	function update_clearance_certificate_admin_clearance_process()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		//$role = $this->session->userdata('type');

		//$this->form_validation->set_rules('reason_id', 'Reason', 'trim|required');
		//$this->form_validation->set_rules('relieving_date', 'Relieving Date', 'trim|required');
		//if ($role == 1)
		//	$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required');

		//$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		//if ($this->form_validation->run()) 
		{
			$data = $this->input->post(NULL, true);
			$role_id = $this->session->userdata('type');

			// $resignation = $this->resignation_model->get_resignation_by_employee_id($employee_id, 16);
			// if (!empty($resignation)) {
			// 	$response = array('status' => 0, 'msg' => "A pending resignation application exists for this employee");
			// 	echo json_encode($response);
			// 	exit;
			// }

			// $data['is_absconding'] = empty($data['is_absconding']) ? 0 : 1;

			$last_id = $this->resignation_model->update_clearance_certificate_admin_clearance($data);
			if (!empty($last_id)) {
				$response = array('status' => 1, 'msg' => "Data added");
				echo json_encode($response);
			} else {

				$response = array('status' => 0, 'msg' => "Not added");
				echo json_encode($response);
			}
			exit;
		}
		// else {
		// 	$response = array('status' => 0, 'msg' => validation_errors());
		// 	echo json_encode($response);
		// 	exit;
		// }
	}


	function update_clearance_certificate_hod_clearance_process()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		//$role = $this->session->userdata('type');

		//$this->form_validation->set_rules('reason_id', 'Reason', 'trim|required');
		//$this->form_validation->set_rules('relieving_date', 'Relieving Date', 'trim|required');
		//if ($role == 1)
		//	$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required');

		//$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		//if ($this->form_validation->run()) 
		{
			$data = $this->input->post(NULL, true);
			$role_id = $this->session->userdata('type');

			// $resignation = $this->resignation_model->get_resignation_by_employee_id($employee_id, 16);
			// if (!empty($resignation)) {
			// 	$response = array('status' => 0, 'msg' => "A pending resignation application exists for this employee");
			// 	echo json_encode($response);
			// 	exit;
			// }

			// $data['is_absconding'] = empty($data['is_absconding']) ? 0 : 1;

			$last_id = $this->resignation_model->update_clearance_certificate_hod_clearance($data);
			if (!empty($last_id)) {
				$response = array('status' => 1, 'msg' => "Data added");
				echo json_encode($response);
			} else {

				$response = array('status' => 0, 'msg' => "Not added");
				echo json_encode($response);
			}
			exit;
		}
		// else {
		// 	$response = array('status' => 0, 'msg' => validation_errors());
		// 	echo json_encode($response);
		// 	exit;
		// }
	}
	function update_clearance_certificate_hcm_clearance_process()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		//$role = $this->session->userdata('type');

		//$this->form_validation->set_rules('reason_id', 'Reason', 'trim|required');
		//$this->form_validation->set_rules('relieving_date', 'Relieving Date', 'trim|required');
		//if ($role == 1)
		//	$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required');

		//$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		//if ($this->form_validation->run()) 
		{
			$data = $this->input->post(NULL, true);
			$role_id = $this->session->userdata('type');

			// $resignation = $this->resignation_model->get_resignation_by_employee_id($employee_id, 16);
			// if (!empty($resignation)) {
			// 	$response = array('status' => 0, 'msg' => "A pending resignation application exists for this employee");
			// 	echo json_encode($response);
			// 	exit;
			// }

			// $data['is_absconding'] = empty($data['is_absconding']) ? 0 : 1;

			$last_id = $this->resignation_model->update_clearance_certificate_hcm_clearance($data);
			if (!empty($last_id)) {
				$response = array('status' => 1, 'msg' => "Data added");
				echo json_encode($response);
			} else {

				$response = array('status' => 0, 'msg' => "Not added");
				echo json_encode($response);
			}
			exit;
		}
		// else {
		// 	$response = array('status' => 0, 'msg' => validation_errors());
		// 	echo json_encode($response);
		// 	exit;
		// }
	}
	function update_clearance_certificate_cfo_clearance_process()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		//$role = $this->session->userdata('type');

		//$this->form_validation->set_rules('reason_id', 'Reason', 'trim|required');
		//$this->form_validation->set_rules('relieving_date', 'Relieving Date', 'trim|required');
		//if ($role == 1)
		//	$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required');

		//$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		//if ($this->form_validation->run()) 
		{
			$data = $this->input->post(NULL, true);
			$role_id = $this->session->userdata('type');

			// $resignation = $this->resignation_model->get_resignation_by_employee_id($employee_id, 16);
			// if (!empty($resignation)) {
			// 	$response = array('status' => 0, 'msg' => "A pending resignation application exists for this employee");
			// 	echo json_encode($response);
			// 	exit;
			// }

			// $data['is_absconding'] = empty($data['is_absconding']) ? 0 : 1;

			$last_id = $this->resignation_model->update_clearance_certificate_cfo_clearance($data);
			if (!empty($last_id)) {
				$response = array('status' => 1, 'msg' => "Data added");
				echo json_encode($response);
			} else {

				$response = array('status' => 0, 'msg' => "Not added");
				echo json_encode($response);
			}
			exit;
		}
		// else {
		// 	$response = array('status' => 0, 'msg' => validation_errors());
		// 	echo json_encode($response);
		// 	exit;
		// }
	}

	function update_clearance_certificate_final_clearance_process()
	{
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		//$role = $this->session->userdata('type');

		//$this->form_validation->set_rules('reason_id', 'Reason', 'trim|required');
		//$this->form_validation->set_rules('relieving_date', 'Relieving Date', 'trim|required');
		//if ($role == 1)
		//	$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required');

		//$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
		//if ($this->form_validation->run()) 
		{
			$data = $this->input->post(NULL, true);
			$role_id = $this->session->userdata('type');

			// $resignation = $this->resignation_model->get_resignation_by_employee_id($employee_id, 16);
			// if (!empty($resignation)) {
			// 	$response = array('status' => 0, 'msg' => "A pending resignation application exists for this employee");
			// 	echo json_encode($response);
			// 	exit;
			// }

			// $data['is_absconding'] = empty($data['is_absconding']) ? 0 : 1;

			$last_id = $this->resignation_model->update_clearance_certificate_final_clearance($data);
			if (!empty($last_id)) {
				$response = array('status' => 1, 'msg' => "Data added");
				echo json_encode($response);
			} else {

				$response = array('status' => 0, 'msg' => "Not added");
				echo json_encode($response);
			}
			exit;
		}
		// else {
		// 	$response = array('status' => 0, 'msg' => validation_errors());
		// 	echo json_encode($response);
		// 	exit;
		// }
	}
	function view_clearance_certificate($resig_id)
	{

		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}

		// if(!check_permission('1','a')) {
		//    $this->session->set_flashdata('error','Permission denied!');
		//    redirect('dashboard', 'refresh');
		// }

		$page_data['page_type']    	= 'user/resignation';
		$page_data['menu']         	= 'resignation';
		$page_data['page_name']    	= 'view_clearance_certificate';
		$page_data['page_title']   	= 'Clearance Certiificate';
		$page_data['reasons']  		= $this->resignation_model->get_all('resignation_reason_master');
		$page_data['ratings']  		= $this->resignation_model->get_all('exit_interview_rating_master');
		$page_data['role']  		= $this->session->userdata('type');

		$clearance_certificate 		= $this->resignation_model->get_clearance_certificate_by_resignation_id($resig_id);
		$page_data['clearance_certificate_id'] = empty($clearance_certificate) ? 0 : $clearance_certificate->id;
		$page_data['resignation']  			= $this->resignation_model->get_resignation_by_id($resig_id);
		$page_data['resignation_id']  		= $resig_id;
		$page_data['employee_id']  			= $page_data['resignation']->employee_id;
		$page_data['certificate']  			= $clearance_certificate;
		$page_data['employee_details']  		= $this->resignation_model->get_employee_by_id($page_data['employee_id']);
		$this->load->view('theme/user/main', $page_data);
	}

	function send_mail()
	{
		$emailToName = "sudhi";
		$emailTo = "sudhi@ksofttechnologies.com";
		$body = "This is a sample mail";
		$subject = "Sample Subject";
		_sendMail($subject, $body, $emailTo, $emailToName);
	}

	public function popup($page_name = '', $param2 = '', $param3 = '', $param4 = '')
	{
		$page_data['param2']        =   $param2;
		$page_data['param3']        =   $param3;
		$page_data['param4']        =   $param4;
		$this->load->view('user/resignation/' . $page_name, $page_data);
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

	// for employee 
	function trigger_probation_confirmation_email()
	{
		$this->resignation_model->trigger_probation_confirmation_email();
		echo "Success";
	}


	// Aneesh start

	function termination_letter($resignation_id)
	{
		// resignation
		$select = 'r.*,e.name employee,e.address per_address,e.pincode per_pincode,per_district.name per_district,per_state.name per_state,per_country.name per_country,des.name designation';
		$where['r.id'] = $resignation_id;
		$join = "e,pre_state,per_state,pre_district,per_district,pre_country,per_country,des";
		$resignation = $this->resignation_model->get_resignations($select, $where, array('join' => $join))->row_array();
		if (empty($resignation)) {
			redirect('resignation/view_resignation');
		}


		$abs_resignation = $this->resignation_model->get_absconding_resignation_by_employee_id($resignation['employee_id'], '');
		$page_data['resignation']    	= $resignation;
		$page_data['abs_resignation']    	= $abs_resignation;

		//pr($abs_resignation);
		//exit;
		// pr($resignation);exit;
		$page_data['page_type']    = 'user/resignation';
		$page_data['page_type']    = 'user/resignation';
		$page_data['menu']         = 'resignation';
		$page_data['page_name']    = 'termination_letter';
		$page_data['page_title']   = 'Termination Letter';
		$this->load->view('theme/user/main', $page_data);
	}

	function releaving_letter($resignation_id)
	{
		// resignation
		$select = 'r.*,e.name employee,e.address per_address,e.pincode per_pincode,per_district.name per_district,per_state.name per_state,per_country.name per_country,des.name designation,e.joining_date';
		$where['r.id'] = $resignation_id;
		$join = "e,pre_state,per_state,pre_district,per_district,pre_country,per_country,des";
		$resignation = $this->resignation_model->get_resignations($select, $where, array('join' => $join))->row_array();
		if (empty($resignation)) {
			redirect('resignation/view_resignation');
		}
		$page_data['resignation']    	= $resignation;

		$page_data['page_type']    = 'user/resignation';
		$page_data['menu']         = 'resignation';
		$page_data['page_name']    = 'releaving_letter';
		$page_data['page_title']   = 'Releaving Letter';
		$this->load->view('theme/user/main', $page_data);
	}

	function absconding_letter($resignation_id)
	{
		$select = 'r.*,e.name employee,e.address per_address,e.pincode per_pincode,per_district.name per_district,per_state.name per_state,per_country.name per_country,des.name designation';
		$where['r.id'] = $resignation_id;
		$join = "e,pre_state,per_state,pre_district,per_district,pre_country,per_country,des";
		$resignation = $this->resignation_model->get_resignations($select, $where, array('join' => $join))->row_array();
		if (empty($resignation)) {
			redirect('resignation/view_resignation');
		}
		$page_data['resignation']    	= $resignation;

		$page_data['page_type']    = 'user/resignation';
		$page_data['menu']         = 'resignation';
		$page_data['page_name']    = 'absconding_letter';
		$page_data['page_title']   = 'Absconding Letter';
		$this->load->view('theme/user/main', $page_data);
	}

	// Aneesh end
}
