<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Cron extends MY_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->load->model('tracker_model');
		// pr($this->session->userdata());exit;
	}

	function import_employees()
	{
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