<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Probation extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('employee_model');
		if (!check_user_login()) {
			redirect('signin', 'refresh');
		}
		// pr($this->session->userdata());exit;
	}

	function view_employees()
	{
		$page_data['page_type']    = 'probation';
		$page_data['menu']         = 'probation';
		$page_data['page_name']    = 'view_employees';
		$page_data['page_title']   = 'Employees (Under Probation)';
		$this->load->view('theme/user/main', $page_data);
	}

	function view_employees_ajax()
	{
		$session = $this->session->userdata();
		$select = 'e.*,des.name designation,dep.name department';
		$where['1'] = "1";
		$where['e.probation_status'] = "1";
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

				$html = '<div class="btn-group">';
				$html .= '<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
				$html .= '<div class="dropdown-menu dropdown-menu-right">';
				if (check_login('1')) {
					$html .= '  <button class="dropdown-item" type="button" onclick="showAjaxModal(\'' . base_url('employee/reporting_heads/' . $value['employee_id']) . '\',\'Reporting Heads\',\'modal-md\')">Reporting Heads</button>';
				}
				if (check_login('2') || check_login('5')) {
					$html .= '  <a class="dropdown-item" href="' . base_url('probation/probation_evaluation/' . $value['employee_id']) . '">Probation Evaluation</a>';
				}
				$html .= '</div>';
				$html .= '</div>';
				$data['data'][$key]['action']			= $html;
			}
		}
		echo json_encode($data);
	}

	function probation_evaluation($id)
	{
		$session = $this->session->userdata();
		$page_data['done'] = $this->common_model->enum_select('probation_training', 'done');
		$page_data['first_assessment_ratings'] = $this->common_model->enum_select('probation_ratings', 'first_assessment_rating');
		$page_data['second_assessment_ratings'] = $this->common_model->enum_select('probation_ratings', 'second_assessment_rating');
		$page_data['first_assessment_overall_ratings'] = $this->common_model->enum_select('probation', 'first_assessment_rating_overall');
		$page_data['second_assessment_overall_ratings'] = $this->common_model->enum_select('probation', 'second_assessment_rating_overall');
		$page_data['rating_parameters'] = $this->common_model->selectAll('probation_rating_parameters', "", "*");

		$select = 'e.*,des.name designation';
		$where = [];
		$where['e.employee_id'] = $id;
		if (!is_admin()) {
			$where['e.reporting_manager_id'] = $session['employee_id'];
		}

		$join = "des";
		$employee = $this->employee_model->get_employees($select, $where, array('join' => $join))->row_array();
		if (empty($employee)) {
			$this->session->set_flashdata('error', 'Record does not exist');
			redirect('probation/view_employees', 'refresh');
			exit;
		}
		if (is_admin()) {
			if (!$employee['probation_status'] == 0) {
				$this->session->set_flashdata('error', 'Not Available');
				redirect('probation/view_employees', 'refresh');
				exit;
			}
		}
		$select = 'p.*';
		$where = [];
		$where['p.employee_id'] = $employee['employee_id'];
		$join = "";
		$probation = $this->employee_model->get_probations($select, $where, array('join' => $join))->row_array();
		if (!empty($probation)) {
			$probation_trainings_first = $this->common_model->selectAll('probation_training', array('probation_id' => $probation['id'], 'assessment_type' => 1), "*");
			$probation_trainings_second = $this->common_model->selectAll('probation_training', array('probation_id' => $probation['id'], 'assessment_type' => 2), "*");

			$select = 'pr.*,prp.parameter,prp.explanation';
			$where = [];
			$where['pr.probation_id'] = $probation['id'];
			$join = "prp";
			$probation_ratings = $this->employee_model->get_probation_ratings($select, $where, array('join' => $join))->result_array();
		}

		$page_data['employee'] 		= $employee;
		$page_data['probation'] 	= $probation;

		$page_data['probation_trainings_first'] = $probation_trainings_first ?? [];
		$page_data['probation_trainings_second'] = $probation_trainings_second ?? [];
		$page_data['probation_ratings'] = $probation_ratings ?? [];

		$page_data['page_type']    	= 'probation';
		$page_data['page_name']    	= 'probation_evaluation_form';
		$page_data['page_title']   	= 'Probation Evaluation';
		$page_data['menu']         	= 'employee';
		$this->load->view('theme/user/main', $page_data);
	}

	function probation_evaluation_post()
	{

		if (!check_login('2')) {
			$this->session->set_flashdata('error', 'Permission denied!');
			redirect('signin', 'refresh');
		}
		if (isset($_POST['submit'])) {
			// pr($_POST);
			// exit;
			// pr($_FILES);exit;
			$this->form_validation->set_rules('employee_id', 'Employee', 'trim|required|record_exist[employee.employee_id]');
			$probation = $this->employee_model->get_probations("p.id", array('employee_id' => $_POST['employee_id']))->row_array();
			$rating_parameters = $this->common_model->selectAll('probation_rating_parameters', "", "id,parameter");
			if (empty($probation)) {
				$this->form_validation->set_rules('training_name[]', 'Training Name ', 'trim|required');
				$this->form_validation->set_rules('trainer[]', 'Facilitator/Trainer', 'trim|required');
				$this->form_validation->set_rules('hours_to_be_attended[]', 'No of Hours to be Attended', 'trim|required');
				$this->form_validation->set_rules('done[]', 'Done? (Yes/No)', 'trim|required');

				if (!empty($rating_parameters)) {
					foreach ($rating_parameters as $key => $parameter) {
						$this->form_validation->set_rules('first_assessment_rating[' . $parameter['id'] . ']', $parameter['parameter'], 'trim|required');
					}
				}
				$this->form_validation->set_rules('first_assessment_rating_overall', 'Rate Overall Performance on a scale of 1-5', 'trim|required');
				$this->form_validation->set_rules('first_assessment_status', 'Employment Status', 'trim|required');

				$this->form_validation->set_rules('first_assessment_status_remark', 'Remark', 'trim|required');
				if ($_POST['first_assessment_status'] == 2) {
					$this->form_validation->set_rules('extention_start_date', 'Start Date', 'trim|required');
					$this->form_validation->set_rules('extention_end_date', 'End Date', 'trim|required');
				}
			} else {
				$this->form_validation->set_rules('training_name_extended[]', 'Training Name ', 'trim|required');
				$this->form_validation->set_rules('hours_to_be_attended_extended[]', 'No of Hours to be Attended', 'trim|required');
				$this->form_validation->set_rules('done_extended[]', 'Done? (Yes/No)', 'trim|required');
				if (!empty($rating_parameters)) {
					foreach ($rating_parameters as $key => $parameter) {
						$this->form_validation->set_rules('second_assessment_rating[' . $parameter['id'] . ']', $parameter['parameter'], 'trim|required');
					}
				}
				$this->form_validation->set_rules('second_assessment_rating_overall', 'Rate Overall Performance on a scale of 1-5', 'trim|required');
				$this->form_validation->set_rules('second_assessment_status', 'Employment Status', 'trim|required');
				$this->form_validation->set_rules('second_assessment_status_remark', 'Remark', 'trim|required');
			}

			$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
			if ($this->form_validation->run()) {
				$data = $this->input->post(NULL, true);
				// 	pr($data);
				// exit;
				$exist = $this->employee_model->get_probations("p.id,p.employee_id,p.first_assessment_status", array('p.employee_id' => $data['employee_id']))->row_array();
				if (empty($exist)) {
					$insert['employee_id'] 							= $data['employee_id'];
					$insert['first_assessment_status'] 				= $data['first_assessment_status'];
					$insert['first_assessment_rating_overall'] 		= $data['first_assessment_rating_overall'];
					$insert['first_assessment_status_remark'] 		= $data['first_assessment_status_remark'];
					$insert['extention_start_date'] 				= !empty($data['extention_start_date']) ? set_date($data['extention_start_date']) : NULL;
					$insert['extention_end_date'] 					= !empty($data['extention_end_date']) ? set_date($data['extention_end_date']) : NULL;

					// pr($insert);
					// 		exit;
					$id = $this->common_model->insert($insert, 'probation');
					if ($id) {

						// update probation status in employee table
						if ($insert['first_assessment_status'] == 1) {
							$employeeTable['probation_status'] = '0';
							$this->common_model->update($employeeTable, array('employee_id' => $insert['employee_id']), 'employee');
						}

						// trainings
						if (!empty($data['training_name'])) {
							$insert_trainings = [];
							foreach ($data['training_name'] as $key => $training_name) {
								$insert_trainings[$key]['probation_id'] 		= $id;
								$insert_trainings[$key]['training_name']		= $data['training_name'][$key];
								$insert_trainings[$key]['trainer'] 				= $data['trainer'][$key];
								$insert_trainings[$key]['hours_to_be_attended'] = $data['hours_to_be_attended'][$key];
								$insert_trainings[$key]['done'] 				= $data['done'][$key];
								$insert_trainings[$key]['assessment_type'] 		= 1;
							}
							$this->db->insert_batch('probation_training', $insert_trainings);
						}

						// ratings
						if (!empty($data['first_assessment_rating'])) {
							$insert_ratings = [];
							foreach ($data['first_assessment_rating'] as $key => $item) {
								$insert_ratings[$key]['probation_id'] 			= $id;
								$insert_ratings[$key]['parameter_id'] 			= $key;
								$insert_ratings[$key]['first_assessment_rating'] = $data['first_assessment_rating'][$key];
							}
							$this->db->insert_batch('probation_ratings', $insert_ratings);
						}
						$response = array('status' => '1', 'msg' => 'Data Saved!');
					} else {
						$response = array('status' => '0', 'msg' => 'Something Went Wrong!');
					}
				} else if ($exist['first_assessment_status'] == 2) {
					$update['second_assessment_status'] 				= $data['second_assessment_status'];
					$update['second_assessment_rating_overall'] 		= $data['second_assessment_rating_overall'];
					$update['second_assessment_status_remark'] 			= $data['second_assessment_status_remark'];

					$resp = $this->common_model->update($update, array('id' => $exist['id']), 'probation');

					if (!empty($resp)) {

						// update probation status in employee table
						if ($update['second_assessment_status'] == 1) {
							$employeeTable['probation_status'] = '0';
							$this->common_model->update($employeeTable, array('employee_id' => $exist['employee_id']), 'employee');
						}

						// trainings
						if (!empty($data['training_name_extended'])) {
							$insert_trainings = [];
							foreach ($data['training_name_extended'] as $key => $training_name) {
								$insert_trainings[$key]['probation_id'] 		= $exist['id'];
								$insert_trainings[$key]['training_name']		= $data['training_name_extended'][$key];
								$insert_trainings[$key]['hours_to_be_attended'] = $data['hours_to_be_attended_extended'][$key];
								$insert_trainings[$key]['done'] 				= $data['done_extended'][$key];
								$insert_trainings[$key]['assessment_type'] 		= 2;
							}
							$this->db->insert_batch('probation_training', $insert_trainings);
						}

						// ratings
						if (!empty($data['second_assessment_rating'])) {
							$insert_ratings = [];
							foreach ($data['second_assessment_rating'] as $key => $item) {
								$update_ratings['second_assessment_rating'] = $data['second_assessment_rating'][$key];
								$this->common_model->update($update_ratings, array('probation_id' => $exist['id'], 'parameter_id' => $key), 'probation_ratings');
							}
						}
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
}
