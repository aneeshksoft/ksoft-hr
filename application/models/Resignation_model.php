<?php
class Resignation_model extends CI_Model
{

	function employees_list($status = "")
	{

		$where = " 1=1 ";
		if (!empty($status)) {
			$where .= " and e.status = '" . $status . "' ";
		}

		$qry = "select e.*,dep.name as department,des.name as designation, NULL as password from employee as e left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id) where " . $where;
		$res = $this->db->query($qry)->result_array();
		if (!empty($res)) {
			return $res;
		} else {
			return false;
		}
	}

	function get_employee_by_id($id)
	{
		$where = "e.employee_id=" . $id;

		$qry = "select e.*,dep.name as department,des.name as designation, NULL as password,wl.name as work_location from employee as e left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id) left join work_locations as wl on wl.work_location_id=e.work_location_id  where " . $where;
		$res = $this->db->query($qry)->row();
		if (!empty($res)) {
			$reporting_manager_name = "";
			$reporting_manager_id = $res->reporting_manager_id;
			$res2 = $this->db->where('employee_id', $reporting_manager_id)->get('employee')->row();
			if (!empty($res2)) {
				$reporting_manager_name = $res2->name;
			}
			$res->reporting_manager_name = $reporting_manager_name;
			return $res;
		} else {
			return false;
		}
	}
	function get_all_employees()
	{
		$where = "probation_status='0'";
		$roles = $this->session->userdata('type');
		$employee_id = $this->session->userdata('employee_id');
		if ($roles == 2) {
			$where .= " and e.employee_id=" . $employee_id;
		}
		$qry = "select e.name,e.employee_id from employee as e where " . $where . "";
		$res = $this->db->query($qry)->result_array();
		//echo $this->db->last_query();
		//exit;
		if (!empty($res)) {
			return $res;
		} else {
			return array();
		}
	}
	function get_all_reportees()
	{
		$reporting_manager_id = $this->session->userdata('employee_id');
		$qry = "select e.name,e.employee_id from employee as e where probation_status='0' and e.reporting_manager_id=" . $reporting_manager_id;
		$res = $this->db->query($qry)->result_array();
		if (!empty($res)) {
			return $res;
		} else {
			return array();
		}
	}
	function get_all_admins()
	{
		$where = " role_id=1 and probation_status='0'";
		$qry = "select e.name,e.employee_id,e.email from employee as e where " . $where . "";
		$res = $this->db->query($qry)->result();
		if (!empty($res)) {
			return $res;
		} else {
			return array();
		}
	}
	function get_all_special_role_users($role)
	{
		$qry = "select e.name,e.employee_id,e.email from employee as e where special_role='" . $role . "' and probation_status='0'";
		$res = $this->db->query($qry)->result();
		if (!empty($res)) {
			return $res;
		} else {
			return array();
		}
	}
	function get_employee_department_id($employee_id)
	{
		$qry = "select e.name,e.employee_id,e.email from employee as e where special_role='" . $role . "' and probation_status='0'";
		$res = $this->db->query($qry)->result();
		if (!empty($res)) {
			return $res;
		} else {
			return array();
		}
	}
	function get_all($table)
	{
		$qry = "select * from " . $table;
		$res = $this->db->query($qry)->result_array();
		if (!empty($res)) {
			return $res;
		} else {
			return array();
		}
	}
	function add_resignation($input = array())
	{
		//$data = array();
		if (!empty($input)) {
			$role_id = $this->session->userdata('type');
			$employee_id = $input['employee_id'];

			$employee_details = $this->get_employee_by_id($employee_id);
			if (empty($employee_details) || empty($employee_details->notice_period)) {
				return false;
			}
			$notice_period = $employee_details->notice_period;
			$today = new DateTime();
			$today->modify('+' . $notice_period . ' days');
			$projected_date = $today->format('Y-m-d');
			$input['projected_relieving_date'] = $projected_date;
			$input['added_by_role_id'] = $role_id;
			$input['employee_id'] = $employee_id;
			$input['added_by'] = $this->session->userdata('employee_id');
			$input['relieving_date'] = date('Y-m-d', strtotime($input['relieving_date']));
			$input['date'] = date('Y-m-d', strtotime($input['date']));
			$input['created_at'] = date('Y-m-d H:i:s');
			$input['updated_at'] = date('Y-m-d H:i:s');
			$res = $this->db->insert('resignation', $input);

			if (!empty($res)) {
				$inserted_id = $this->db->insert_id();
				$clear_certificate_array = array("resignation_id" => $inserted_id);
				$this->db->insert('clearance_certificate', $clear_certificate_array);

				$exit_interview_array = array("resignation_id" => $inserted_id, "employee_id" => $employee_id);
				$this->db->insert('exit_interview', $exit_interview_array);
				$subject = "New Resignation Application";
				$body = "New resigantion application has been raised by $employee_details->name. Login to see more details";
				$rm_details = $this->get_employee_by_id($employee_details->reporting_manager_id);
				if (!empty($rm_details) && !empty($rm_details->email)) {
					_sendMail($subject, $body, $rm_details->email, $rm_details->name);
				}



				return $inserted_id;
			}
		}

		return false;
	}
	function get_all_resignations($start, $limit)
	{
		$roles = $this->session->userdata('type');
		$employee_id = $this->session->userdata('employee_id');
		$special_role = $this->session->userdata('special_role');
		//echo "role ".$special_role;
		$role_array = array('HCM', 'CFO', 'Clearance', 'IT Admin', 'Accounts', 'HOD');
		if ($roles == 2) {
			if ($special_role == 'HOD') {
				//$dept;
			}
			if (!in_array($special_role, $role_array)) {
				$this->db->where('r.employee_id', $employee_id);
				$this->db->or_where('e.reporting_manager_id', $employee_id);
			}
		}
		$this->db->select('r.*,e.name as employee_name,e.email as employee_email,rrm.name as reason')->from('resignation r');
		$this->db->join('employee e', 'r.employee_id=e.employee_id', 'left');
		$this->db->join('resignation_reason_master rrm', 'rrm.id=r.reason_id', 'left');
		if (isset($start) && isset($limit)) {
			$this->db->limit($limit, $start);
		} else if (isset($limit)) {
			$this->db->limit($limit);
		}
		$this->db->order_by('id', 'desc');
		$res = $this->db->get()->result();
		//echo $this->db->last_query();
		if (!empty($res)) {
			return $res;
		}
		return array();
	}
	function get_resignation_by_id($resignation_id)
	{

		$this->db->select('r.*,e.name as employee_name,rrm.name as reason')->from('resignation r');
		$this->db->join('employee e', 'r.employee_id=e.employee_id', 'left');
		$this->db->join('resignation_reason_master rrm', 'rrm.id=r.reason_id', 'left');

		$this->db->where('r.id', $resignation_id);
		$res = $this->db->get()->row();
		if (!empty($res)) {
			if (!empty($res->ff_document)) {
				$res->ff_document = base_url('assets/uploads/resignation/ff_documents/') . $res->ff_document;
			}
			if (!empty($res->relieving_letter)) {
				$res->relieving_letter = base_url('assets/uploads/resignation/relieving_letter/') . $res->relieving_letter;
			}
			if (!empty($res->clearance_letter)) {
				$res->clearance_letter = base_url('assets/uploads/resignation/clearance_letter/') . $res->clearance_letter;
			}
			return $res;
		}
		return array();
	}
	function get_resignation_by_employee_id($employee_id, $status)
	{
		$this->db->select('r.*,e.name as employee_name')->from('resignation r');
		$this->db->join('employee e', 'r.employee_id=e.employee_id', 'left');

		if (!empty($status)) {
			$this->db->where('r.status!=' . $status);
		}
		$this->db->where('r.employee_id', $employee_id);
		$res = $this->db->get()->result();
		// echo $this->db->last_query();
		// exit;
		if (!empty($res)) {
			return $res;
		}
		return array();
	}



	function delete_resignation($resignation_id)
	{

		$this->db->where('id', $resignation_id);
		$res = $this->db->delete('resignation');
		if ($this->db->affected_rows() > 0)
			return true;
		else
			return false;
	}

	function edit_resignation($input)
	{
		$data = array();
		if (!empty($input)) {
			$data['reason_id'] = $input['reason_id'];
			$data['comment'] = $input['comment'];
			$data['employee_id'] = $input['employee_id'];
			$data['is_absconding'] = $input['is_absconding'];
			if (!empty($input['relieving_date']))
				$data['relieving_date'] = date('Y-m-d', strtotime($input['relieving_date']));
			$data['updated_at'] = date('Y-m-d H:i:s');
			$where = array("id" => $input['id']);
			$res = $this->db->update('resignation', $data, $where);

			if (!empty($input['relieving_date'])) {
				$employee_details = $this->get_employee_by_id($input['employee_id']);
				$subject = "Resignation Application Updated";
				$body = "Mr/Mrs $employee_details->name's last day of work will be on " . $input['relieving_date'];
				$rm_details = $this->get_employee_by_id($employee_details->reporting_manager_id);
				if (!empty($rm_details) && !empty($rm_details->email)) {
					_sendMail($subject, $body, $rm_details->email, $rm_details->name);
				}
				$admins = $this->get_all_admins();
				foreach ($admins as $row) {
					_sendMail($subject, $body, $row->email, $row->name);
				}
			}
			if ($this->db->affected_rows() > 0)
				return true;
			else
				return false;
		}

		return false;
	}
	function update_other_resignation_details($input)
	{
		$data = array();
		$files = $_FILES;
		$ff_document = '';
		$relieving_letter = '';
		$clearance_letter = '';
		if (!empty($_FILES['ff_document']['name'])) {
			$oldmask = umask(0);
			if (!is_dir('assets/uploads/resignation/ff_documents')) {
				mkdir('assets/uploads/resignation/ff_documents', 0777, true);
				if (!file_exists('assets/uploads/resignation/ff_documents/index.html')) {
					file_put_contents('assets/uploads/resignation/ff_documents/index.html', '');
				}
			}
			umask($oldmask);
			$_FILES['file']['name']     = $files['ff_document']['name'];
			$_FILES['file']['type']     = $files['ff_document']['type'];
			$_FILES['file']['tmp_name'] = $files['ff_document']['tmp_name'];
			$_FILES['file']['error']    = $files['ff_document']['error'];
			$_FILES['file']['size']     = $files['ff_document']['size'];

			$config['upload_path']   =  'assets/uploads/resignation/ff_documents/';
			$config['allowed_types'] = 'png|jpg|jpeg|pdf|xls|xlsx|doc|docx';

			$fname = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
			$config['file_name']     = cleanFileName($fname) . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload('file')) {
				$fileData = $this->upload->data();
				$ff_document = $fileData['file_name'];
			}
		}
		if (!empty($_FILES['clearance_letter']['name'])) {
			$oldmask = umask(0);
			if (!is_dir('assets/uploads/resignation/clearance_letter')) {
				mkdir('assets/uploads/resignation/clearance_letter', 0777, true);
				if (!file_exists('assets/uploads/resignation/clearance_letter/index.html')) {
					file_put_contents('assets/uploads/resignation/clearance_letter/index.html', '');
				}
			}
			umask($oldmask);
			$_FILES['file']['name']     = $files['clearance_letter']['name'];
			$_FILES['file']['type']     = $files['clearance_letter']['type'];
			$_FILES['file']['tmp_name'] = $files['clearance_letter']['tmp_name'];
			$_FILES['file']['error']    = $files['clearance_letter']['error'];
			$_FILES['file']['size']     = $files['clearance_letter']['size'];

			$config['upload_path']   =  'assets/uploads/resignation/clearance_letter/';
			$config['allowed_types'] = 'png|jpg|jpeg|pdf|xls|xlsx|doc|docx';

			$fname = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
			$config['file_name']     = cleanFileName($fname) . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload('file')) {
				$fileData = $this->upload->data();
				$clearance_letter = $fileData['file_name'];
			}
		}
		if (!empty($_FILES['relieving_letter']['name'])) {
			$oldmask = umask(0);
			if (!is_dir('assets/uploads/resignation/relieving_letter')) {
				mkdir('assets/uploads/resignation/relieving_letter', 0777, true);
				if (!file_exists('assets/uploads/resignation/relieving_letter/index.html')) {
					file_put_contents('assets/uploads/resignation/relieving_letter/index.html', '');
				}
			}
			umask($oldmask);
			$_FILES['file']['name']     = $files['relieving_letter']['name'];
			$_FILES['file']['type']     = $files['relieving_letter']['type'];
			$_FILES['file']['tmp_name'] = $files['relieving_letter']['tmp_name'];
			$_FILES['file']['error']    = $files['relieving_letter']['error'];
			$_FILES['file']['size']     = $files['relieving_letter']['size'];

			$config['upload_path']   =  'assets/uploads/resignation/relieving_letter/';
			$config['allowed_types'] = 'png|jpg|jpeg|pdf|xls|xlsx|doc|docx';

			$fname = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);
			$config['file_name']     = cleanFileName($fname) . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload('file')) {
				$fileData = $this->upload->data();
				$relieving_letter = $fileData['file_name'];
			}
		}

		if (!empty($input)) {
			$update_array = array();
			if (!empty($input['agreed_relieving_date'])) {
				$data['status'] = 2;
				$data['agreed_relieving_date'] = date('Y-m-d', strtotime($input['agreed_relieving_date']));
				$update_array['relieving_date'] = $data['agreed_relieving_date'];
			}
			if (!empty($ff_document)) {
				$data['status'] = 15;
				$data['ff_document'] = $ff_document;
				$update_array['status'] = 'resigned';
			}
			if (!empty($clearance_letter)) {
				//$data['status'] = 15;
				$data['clearance_letter'] = $clearance_letter;
			}
			if (!empty($relieving_letter)) {
				//$data['status'] = 15;
				$data['relieving_letter'] = $relieving_letter;
			}

			$data['updated_at'] = date('Y-m-d H:i:s');
			$where = array("id" => $input['id']);
			$res = $this->db->update('resignation', $data, $where);
			if ($this->db->affected_rows() > 0) {
				if (!empty($data['status']) && $data['status'] == 15) {
					$where = array("id" => $input['employee_id']);
					$this->db->update('employee', $update_array, $where);
				}


				return true;
			} else
				return false;
		}

		return false;
	}
	function cancel_resignation($resignation_id)
	{
		$update_array = array("status" => 16);
		$where = array("id" => $resignation_id);
		$this->db->update('resignation', $update_array, $where);
		if ($this->db->affected_rows() > 0)
			return true;
		else
			return false;
	}

	function add_absconding_resignation($input = array())
	{
		//$data = array();
		if (!empty($input)) {
			$role_id = $this->session->userdata('type');
			$employee_id = $input['employee_id'];

			$employee_details = $this->get_employee_by_id($employee_id);
			if (empty($employee_details) || empty($employee_details->notice_period)) {
				return false;
			}
			$notice_period = $employee_details->notice_period;
			$today = new DateTime();
			$today->modify('+' . $notice_period . ' days');
			$input['added_by_role_id'] = $role_id;
			$input['employee_id'] = $employee_id;
			$input['added_by'] = $this->session->userdata('employee_id');
			$input['absconding_date'] = date('Y-m-d', strtotime($input['absconding_date']));
			$input['created_at'] = date('Y-m-d H:i:s');
			$input['updated_at'] = date('Y-m-d H:i:s');
			$res = $this->db->insert('absconding_resignation', $input);

			if (!empty($res)) {
				$inserted_id = $this->db->insert_id();

				$admins = $this->get_all_admins();
				$subject = "New Abscondong Resignation raised";
				$body = "New absconding resigantion has been raised by RM. Login to see more details";
				foreach ($admins as $row) {
					_sendMail($subject, $body, $row->email, $row->name);
				}


				return $inserted_id;
			}
		}

		return false;
	}
	function get_all_absconding_resignations($start, $limit)
	{
		$roles = $this->session->userdata('type');
		$employee_id = $this->session->userdata('employee_id');
		//$special_role = $this->session->userdata('special_role');
		//$role_array=array('HOD','HCM','CFO','Clearance','IT Admin','Accounts');
		if ($roles != 1)
			$this->db->where('e.reporting_manager_id', $employee_id);
		$this->db->select('r.*,e.name as employee_name,e.email as employee_email,e.code as employee_code')->from('absconding_resignation r');
		$this->db->join('employee e', 'r.employee_id=e.employee_id', 'left');
		//$this->db->join('resignation_reason_master rrm', 'rrm.id=r.reason_id', 'left');
		if (isset($start) && isset($limit)) {
			$this->db->limit($limit, $start);
		} else if (isset($limit)) {
			$this->db->limit($limit);
		}
		$this->db->order_by('id', 'desc');
		$res = $this->db->get()->result();
		//echo $this->db->last_query();
		if (!empty($res)) {
			return $res;
		}
		return array();
	}
	function get_absconding_resignation_by_id($id)
	{
		$roles = $this->session->userdata('type');
		$employee_id = $this->session->userdata('employee_id');

		$this->db->where('r.id', $id);
		$this->db->select('r.*,e.name as employee_name,e.email as employee_email,e.code as employee_code')->from('absconding_resignation r');
		$this->db->join('employee e', 'r.employee_id=e.employee_id', 'left');

		$res = $this->db->get()->row();
		//echo $this->db->last_query();
		if (!empty($res)) {
			return $res;
		}
		return array();
	}
	function get_absconding_resignation_by_employee_id($employee_id, $status)
	{
		$this->db->select('r.*,e.name as employee_name')->from('absconding_resignation r');
		$this->db->join('employee e', 'r.employee_id=e.employee_id', 'left');
		if (!empty($status)) {
			$this->db->where_not_in('r.status', $status);
		}
		$this->db->where('r.employee_id', $employee_id);
		$this->db->order_by('r.id', 'desc');
		$res = $this->db->get()->row();
		// echo $this->db->last_query();
		// exit;
		if (!empty($res)) {
			return $res;
		}
		return array();
	}

	function update_absconding_resignation($resignation_id, $status)
	{
		$update_array = array("status" => $status);
		$where = array("id" => $resignation_id);
		$res = $this->db->update('absconding_resignation', $update_array, $where);
		if (!empty($res))
			return true;
		else
			return false;
	}
	function trigger_absconding_reminder()
	{
		//echo "dsjfhdjshf";
		$query = "SELECT * FROM absconding_resignation WHERE CURDATE() >= DATE_ADD(absconding_date, INTERVAL 7 DAY) and status=3";
		$res = $this->db->query($query)->result();
		//echo $this->db->last_query();
		//echo json_encode($res);
		$subject = "Absconding Reminder";

		if (!empty($res)) {
			foreach ($res as $row) {
				$this->db->update('absconding_resignation', array("absconding_reminder_sent" => 1), array("id" => $row->id));
				$employee_details = $this->get_employee_by_id($row->employee_id);
				$body = "Please start the main resignation process for Mr/Mrs. " . $employee_details->name;
				//_sendMail($subject, $body, $employee_details->email, $employee_details->name);
				$admins = $this->get_all_admins();
				foreach ($admins as $row) {
					$emailToName = $row->name;
					$emailTo = $row->email;
					_sendMail($subject, $body, $emailTo, $emailToName);
				}
			}
		}
		return true;
	}



	function edit_exit_interview($input = array())
	{
		//$data = array();
		if (!empty($input)) {
			$exit_interview_id = $input['id'];
			$input['section_1_question_10'] = date('Y-m-d', strtotime($input['section_1_question_10']));
			$input['added_by'] = $this->session->userdata('employee_id');

			//$input['created_at'] = date('Y-m-d H:i:s');
			$input['updated_at'] = date('Y-m-d H:i:s');
			$where = array("id" => $exit_interview_id);
			$res = $this->db->update('exit_interview', $input, $where);

			if (!empty($res)) {
				$details = $this->get_exit_interview_details_by_id($exit_interview_id);
				if ($details->is_edited == 0) {
					$update_array = array("is_edited" => 1);
					$where = array("id" => $exit_interview_id);
					$this->db->update('exit_interview', $update_array, $where);

					$update_array = array("status" => 18, "exit_interview_submitted" => 1);
					$where = array("id" => $input['resignation_id']);
					$this->db->update('resignation', $update_array, $where);

					$employee_details = $this->get_employee_by_id($input['employee_id']);
					$subject = "Exit interview details submitted by " . $employee_details->name;
					$body = "Exit Interview Submitted";
					$admins = $this->get_all_admins();
					foreach ($admins as $row) {
						$emailToName = $row->name;
						$emailTo = $row->email;
						$resp = _sendMail($subject, $body, $emailTo, $emailToName);
					}
				}

				return $exit_interview_id;
				//return $resp;
			}
		}

		return false;
	}

	function get_exit_interview_details_by_id($interview_id)
	{
		$this->db->where('id', $interview_id);
		$res = $this->db->get('exit_interview')->row();
		if (!empty($res))
			return $res;
		return array();
	}
	function get_exit_interview_details_by_resignation_id($resignation_id)
	{
		$this->db->where('resignation_id', $resignation_id);
		$res = $this->db->get('exit_interview')->row();
		if (!empty($res))
			return $res;
		return array();
	}
	function trigger_exit_interview()
	{
		//echo "dsjfhdjshf";
		$query = "SELECT * FROM resignation WHERE CURDATE() >= DATE_SUB(agreed_relieving_date, INTERVAL 2 DAY) and status=2 and is_absconding!=1";
		$res = $this->db->query($query)->result();
		echo $this->db->last_query();
		echo json_encode($res);
		if (!empty($res)) {
			foreach ($res as $row) {
				$this->db->update('resignation', array("status" => 17, "exit_interview_triggered" => 1), array("id" => $row->id));
				$employee_details = $this->get_employee_by_id($row->employee_id);
				$subject = "Exit interview triggered";
				$body = "Exit Interview form available to be filled";
				//_sendMail($subject, $body, $employee_details->email, $employee_details->name);
				$admins = $this->get_all_admins();
				foreach ($admins as $row) {
					$emailToName = $row->name;
					$emailTo = $row->email;
					//_sendMail($subject, $body, $emailTo, $emailToName);
				}
			}
		}
		return true;
	}

	function update_exit_interview_status($data)
	{
		$exit_interview_id = $data['interview_id'];
		$resignation_id = $data['resignation_id'];
		$update_array = array("status" => 2);
		$where = array("id" => $exit_interview_id);
		$this->db->update('exit_interview', $update_array, $where);

		$update_array = array("status" => 13, "exit_interview_approved" => 1);
		$where = array("id" => $resignation_id);
		$this->db->update('resignation', $update_array, $where);

		if ($this->db->affected_rows() > 0)
			return true;
		else
			return false;
	}

	function trigger_clearance_certificate()
	{
		$query = "SELECT * FROM resignation WHERE CURDATE()>=agreed_relieving_date and ((is_absconding=1 and status=2) or (is_absconding=0 and status in (13,18)))";
		$res = $this->db->query($query)->result();
		//echo $this->db->last_query();
		//echo json_encode($res);
		if (!empty($res)) {
			foreach ($res as $row) {
				$this->db->update('resignation', array("status" => 3, "clearance_certificate_triiggered" => 1), array("id" => $row->id));

				$employee_details = $this->get_employee_by_id($row->employee_id);
				$subject = "Exit clearance triggered";
				$body = "Exit clearance form available to be filled";
				_sendMail($subject, $body, $employee_details->email, $employee_details->name);
				$this->send_mails_to_all($subject, $body);
			}
		}
		return true;
	}
	function send_mails_to_all($subject, $body)
	{
		$admins = $this->get_all_admins();
		foreach ($admins as $row) {
			$emailToName = $row->name;
			$emailTo = $row->email;
			_sendMail($subject, $body, $emailTo, $emailToName);
		}
		$all_it_clearance = $this->get_all_special_role_users("Clearance");
		foreach ($all_it_clearance as $row) {
			$emailToName = $row->name;
			$emailTo = $row->email;
			_sendMail($subject, $body, $emailTo, $emailToName);
		}
		$all_hod = $this->get_all_special_role_users("HOD");
		foreach ($all_hod as $row) {
			$emailToName = $row->name;
			$emailTo = $row->email;
			_sendMail($subject, $body, $emailTo, $emailToName);
		}
		$all_hcm = $this->get_all_special_role_users("HCM");
		foreach ($all_hcm as $row) {
			$emailToName = $row->name;
			$emailTo = $row->email;
			_sendMail($subject, $body, $emailTo, $emailToName);
		}
		$all_cfo = $this->get_all_special_role_users("CFO");
		foreach ($all_cfo as $row) {
			$emailToName = $row->name;
			$emailTo = $row->email;
			_sendMail($subject, $body, $emailTo, $emailToName);
		}
		$all_it_admin = $this->get_all_special_role_users("IT Admin");
		foreach ($all_it_admin as $row) {
			$emailToName = $row->name;
			$emailTo = $row->email;
			_sendMail($subject, $body, $emailTo, $emailToName);
		}
		$all_accounts = $this->get_all_special_role_users("Accounts");
		foreach ($all_accounts as $row) {
			$emailToName = $row->name;
			$emailTo = $row->email;
			_sendMail($subject, $body, $emailTo, $emailToName);
		}
	}
	function add_clearance_certificate_on_job($input = array())
	{
		if (!empty($input)) {
			$main_data = array();
			$clearance_certificate_id = $input['clearance_certificate_id'];
			$status = 0;
			$is_on_job_added = $this->get_clearance_certificate_status($clearance_certificate_id, 'is_on_job_added');
			if ($is_on_job_added == 0) {
				$status = 4;
				$main_data['is_on_job_added'] = 1;
				//echo "inside is_on_job_added";
			}

			$main_data['resignation_id'] = $input['resignation_id'];
			$main_data['employee_type'] = $input['employee_type'];
			$main_data['on_job_reporting_manager_name'] = $input['on_job_reporting_manager_name'];
			$main_data['on_job_date']	= empty($input['on_job_date']) ? null : date('Y-m-d', strtotime($input['on_job_date']));
			$main_data['added_by'] = $this->session->userdata('employee_id');

			$where = array("id" => $clearance_certificate_id);
			$main_data['updated_at'] = date('Y-m-d H:i:s');
			$res = $this->db->update('clearance_certificate', $main_data, $where);

			if (!empty($res)) {
				if (!empty($input['employee_duties'])) {
					$this->deleteClearanceCertificateMultiValues('clearance_certificate_duties', $clearance_certificate_id);
				}
				if (!empty($input['employee_pending_tasks'])) {
					$this->deleteClearanceCertificateMultiValues('clearance_certificate_pending_tasks', $clearance_certificate_id);
				}
				if (!empty($input['employee_reports'])) {
					$this->deleteClearanceCertificateMultiValues('clearance_certificate_reports', $clearance_certificate_id);
				}

				$duty_text = "";
				foreach ($input['employee_duties'] as $row) {
					$input_array = array();
					$input_array['clearance_certificate_id'] = $clearance_certificate_id;
					$input_array['duty']  = $row;
					$duty_text .= "</br>1. " . $row;
					$input_array['created_at'] = date('Y-m-d H:i:s');
					$input_array['updated_at'] = date('Y-m-d H:i:s');
					$this->db->insert('clearance_certificate_duties', $input_array);
				}
				foreach ($input['employee_pending_tasks'] as $row) {

					$input_array = array();
					$input_array['clearance_certificate_id'] = $clearance_certificate_id;
					$input_array['task']  = $row;
					$input_array['created_at'] = date('Y-m-d H:i:s');
					$input_array['updated_at'] = date('Y-m-d H:i:s');
					$this->db->insert('clearance_certificate_pending_tasks', $input_array);
				}
				foreach ($input['employee_reports'] as $row) {

					$input_array = array();
					$input_array['clearance_certificate_id'] = $clearance_certificate_id;
					$input_array['report']  = $row;
					$input_array['created_at'] = date('Y-m-d H:i:s');
					$input_array['updated_at'] = date('Y-m-d H:i:s');
					$this->db->insert('clearance_certificate_reports', $input_array);
				}
				if (!empty($status)) {
					//echo "inside status";
					$update_array = array("status" => $status);
					$where = array("id" => $input['resignation_id']);
					$this->db->update('resignation', $update_array, $where);
				}
				$assigned_manager = $input['on_job_reporting_manager_name'];
				if (!empty($assigned_manager)) {
					$subject = "Duties Assigned";
					$body = "The following duties have been assigned for you. " . $duty_text;
					_sendMail($subject, $body, $assigned_manager, "");
					//$this->send_mails_to_all($subject, $body);
				}
				$employee_details = $this->get_resignation_employee_details($input['resignation_id']);
				$subject = "On Job Clearance Completed";
				$body = "On job clearance completed for the resignation raised by " . $employee_details->name;


				$all_it_clearance = $this->get_all_special_role_users("Clearance");
				foreach ($all_it_clearance as $row) {
					$emailToName = $row->name;
					$emailTo = $row->email;
					_sendMail($subject, $body, $emailTo, $emailToName);
				}

				return $clearance_certificate_id;
			}
		}

		return false;
	}

	function update_clearance_certificate_it_clearance($input = array())
	{
		if (!empty($input)) {
			if (empty($input['clearance_certificate_id'])) {
				return false;
			}

			$clearance_certificate_id = $input['clearance_certificate_id'];
			$main_data = array();
			$status = 0;

			if (!empty($input['it_clearance_section_1'])) {
				$is_it_added = $this->get_clearance_certificate_status($clearance_certificate_id, 'is_it_added');
				if ($is_it_added == 0) {
					$status = 5;
					$main_data['is_it_added'] = 1;
				}
				if (!empty($status)) {
					$update_array = array("status" => $status);
					$where = array("id" => $input['resignation_id']);
					$this->db->update('resignation', $update_array, $where);

					$employee_details = $this->get_resignation_employee_details($input['resignation_id']);
					$rm_detils = $this->get_resignation_employee_details($employee_details->reporting_manager_id);
					$subject = "IT Clearance Completed";
					$body = "IT clearance completed for the resignation raised by " . $employee_details->name;
					_sendMail($subject, $body, $rm_detils->email, $rm_detils->name);
				}
				$main_data['it_clearance_voip']						= @$input['it_clearance_voip'];
				$main_data['it_clearance_sales_force']				= @$input['it_clearance_sales_force'];
				$main_data['it_clearance_pc_data']					= $input['it_clearance_pc_data'];
				$main_data['it_clearance_domain_login']				= $input['it_clearance_domain_login'];
				$main_data['it_clearance_system_format']			= @$input['it_clearance_system_format'];
				$main_data['it_clearance_system']					= @$input['it_clearance_system'];
				$main_data['it_clearance_rep_name']					= @$input['it_clearance_rep_name'];
				$main_data['it_clearance_rep_date'] = empty($input['it_clearance_rep_date']) ? null : date('Y-m-d', strtotime($input['it_clearance_rep_date']));
				$where = array("id" => $input['clearance_certificate_id']);
				$main_data['updated_at'] = date('Y-m-d H:i:s');
				$res = $this->db->update('clearance_certificate', $main_data, $where);

				if (!empty($input['it_clearance_email'])) {
					//echo "first ";
					//$this->deleteClearanceCertificateMultiValues('clearance_certificate_it_clearance', $clearance_certificate_id);
					$res = $this->db->where('clearance_certificate_id', $clearance_certificate_id)->get('clearance_certificate_it_clearance')->result();
					if (empty($res)) {
						for ($i = 0; $i < count($input['it_clearance_email']); $i++) {
							$input_array = array();
							$input_array['clearance_certificate_id'] = $clearance_certificate_id;
							$input_array['it_clearance_email']  = $input['it_clearance_email'][$i];
							//$input_array['it_clearance_email_delete']  = empty($input['it_clearance_email_delete'][$i])?0:$input['it_clearance_email_delete'][$i];
							//$input_array['it_clearance_email_alias_to']  = @$input['it_clearance_email_alias_to'][$i];
							//$input_array['it_clearance_email_assign_to']  = @$input['it_clearance_email_assign_to'][$i];
							if (!empty($input['it_clearance_email_validation']) && !empty($input['it_clearance_email_validation'][$i]))
								$input_array['it_clearance_email_validation']  = @$input['it_clearance_email_validation'][$i];
							$input_array['created_at'] = date('Y-m-d H:i:s');
							$input_array['updated_at'] = date('Y-m-d H:i:s');
							$this->db->insert('clearance_certificate_it_clearance', $input_array);
						}
					} else {
						//echo "second ";
						for ($i = 0; $i < count($res); $i++) {
							$id = $res[$i]->id;
							$input_array = array();
							$input_array['clearance_certificate_id'] = $clearance_certificate_id;
							$input_array['it_clearance_email']  = $input['it_clearance_email'][$i];
							//$input_array['it_clearance_email_delete']  = empty($input['it_clearance_email_delete'][$i])?0:$input['it_clearance_email_delete'][$i];
							//$input_array['it_clearance_email_alias_to']  = @$input['it_clearance_email_alias_to'][$i];
							//$input_array['it_clearance_email_assign_to']  = @$input['it_clearance_email_assign_to'][$i];
							//if(!empty($input['it_clearance_email_validation']) && $input['it_clearance_email_validation'][$i])
							//$input_array['it_clearance_email_validation']  = @$input['it_clearance_email_validation'][$i];
							//$input_array['created_at'] = date('Y-m-d H:i:s');
							$input_array['updated_at'] = date('Y-m-d H:i:s');
							$this->db->update('clearance_certificate_it_clearance', $input_array, array("id" => $id));
						}
					}
				}
				if (!empty($input['it_clearance_ftp'])) {
					//$this->deleteClearanceCertificateMultiValues('clearance_certificate_it_clearance_ftp', $clearance_certificate_id);
					$res = $this->db->where('clearance_certificate_id', $clearance_certificate_id)->get('clearance_certificate_it_clearance_ftp')->result();
					if (empty($res)) {
						for ($i = 0; $i < count($input['it_clearance_ftp']); $i++) {
							$input_array = array();
							$input_array['clearance_certificate_id'] = $clearance_certificate_id;
							$input_array['it_clearance_ftp']  = @$input['it_clearance_ftp'][$i];
							//if(!empty($input['it_clearance_ftp_validation'] && !empty($input['it_clearance_ftp_validation'][$i])))
							//$input_array['it_clearance_ftp_validation']  = $input['it_clearance_ftp_validation'][$i];
							$input_array['created_at'] = date('Y-m-d H:i:s');
							$input_array['updated_at'] = date('Y-m-d H:i:s');
							$this->db->insert('clearance_certificate_it_clearance_ftp', $input_array);
						}
					} else {
						for ($i = 0; $i < count($res); $i++) {
							$id = $res[$i]->id;
							$input_array = array();
							$input_array['clearance_certificate_id'] = $clearance_certificate_id;
							$input_array['it_clearance_ftp']  = @$input['it_clearance_ftp'][$i];
							//if(!empty($input['it_clearance_ftp_validation'] && !empty($input['it_clearance_ftp_validation'][$i])))
							//$input_array['it_clearance_ftp_validation']  = $input['it_clearance_ftp_validation'][$i];
							$input_array['updated_at'] = date('Y-m-d H:i:s');
							$this->db->update('clearance_certificate_it_clearance_ftp', $input_array, array("id" => $id));
						}
					}
				}
				$where = array("id" => $input['clearance_certificate_id']);
				$main_data['updated_at'] = date('Y-m-d H:i:s');
				$res = $this->db->update('clearance_certificate', $main_data, $where);
			}
			if (!empty($input['it_clearance_section_2'])) {
				$is_it_added = $this->get_clearance_certificate_status($clearance_certificate_id, 'is_it_rm_added');
				if ($is_it_added == 0) {
					$status = 6;
					$main_data['is_it_rm_added'] = 1;
				}
				if (!empty($status)) {
					$update_array = array("status" => $status);
					$where = array("id" => $input['resignation_id']);
					$this->db->update('resignation', $update_array, $where);

					$employee_details = $this->get_resignation_employee_details($input['resignation_id']);
					$subject = "Asset Details Completed";
					$body = "Asset details added for the resignation raised by " . $employee_details->name;
					$all_it_clearance = $this->get_all_special_role_users("Clearance");
					foreach ($all_it_clearance as $row) {
						$emailToName = $row->name;
						$emailTo = $row->email;
						_sendMail($subject, $body, $emailTo, $emailToName);
					}
				}
				$main_data['it_clearance_voip_delete']				= empty($input['it_clearance_voip_delete']) ? 0 : 1;
				$main_data['it_clearance_voip_reset_password']		= empty($input['it_clearance_voip_reset_password']) ? 0 : 1;
				$main_data['it_clearance_voip_reset_password_value'] = $input['it_clearance_voip_reset_password_value'];
				$main_data['it_clearance_sales_force_delete']		= empty($input['it_clearance_sales_force_delete']) ? 0 : 1;
				$main_data['it_clearance_sales_force_empty']		=  empty($input['it_clearance_sales_force_empty']) ? 0 : 1;
				$main_data['it_clearance_pc_data_backup']			= empty($input['it_clearance_pc_data_backup']) ? 0 : 1;
				$main_data['it_clearance_pc_data_transfer_to']		=  empty($input['it_clearance_pc_data_transfer_to']) ? 0 : 1;
				$main_data['it_clearance_pc_data_transfer_to_value'] = $input['it_clearance_pc_data_transfer_to_value'];
				$main_data['it_clearance_domain_login_delete']		=  empty($input['it_clearance_domain_login_delete']) ? 0 : 1;
				$main_data['it_clearance_is_system_formatted']		=  empty($input['it_clearance_is_system_formatted']) ? 0 : $input['it_clearance_is_system_formatted'];
				$main_data['it_clearance_system_moved_to_stores']	=  empty($input['it_clearance_system_moved_to_stores']) ? 0 : 1;
				$main_data['it_clearance_system_asset_management_entry'] =  empty($input['it_clearance_system_asset_management_entry']) ? 0 : 1;
				$main_data['it_clearance_reporting_manager_name']					= @$input['it_clearance_reporting_manager_name'];
				$main_data['it_clearance_reporting_manager_comment']					= @$input['it_clearance_reporting_manager_comment'];
				$main_data['it_clearance_reporting_manager_date'] = empty($input['it_clearance_reporting_manager_date']) ? null : date('Y-m-d', strtotime($input['it_clearance_reporting_manager_date']));


				if (!empty($input['it_clearance_email_alias_to'])) {
					//echo "third ";
					$res = $this->db->where('clearance_certificate_id', $clearance_certificate_id)->get('clearance_certificate_it_clearance')->result();

					for ($i = 0; $i < count($res); $i++) {

						$id = $res[$i]->id;
						$input_array = array();
						$input_array['clearance_certificate_id'] = $clearance_certificate_id;
						//$input_array['it_clearance_email']  = $input['it_clearance_email'][$i];
						$input_array['it_clearance_email_delete']  = empty($input['it_clearance_email_delete'][$i]) ? 0 : 1;
						$input_array['it_clearance_email_alias_to']  = @$input['it_clearance_email_alias_to'][$i];
						$input_array['it_clearance_email_assign_to']  = @$input['it_clearance_email_assign_to'][$i];
						//if(!empty($input['it_clearance_email_validation']) && $input['it_clearance_email_validation'][$i])
						//$input_array['it_clearance_email_validation']  = @$input['it_clearance_email_validation'][$i];
						//$input_array['created_at'] = date('Y-m-d H:i:s');
						$input_array['updated_at'] = date('Y-m-d H:i:s');
						$this->db->update('clearance_certificate_it_clearance', $input_array, array("id" => $id));
					}
				}


				if (!empty($input['it_clearance_ftp_reset_password_value'])) {
					$res = $this->db->where('clearance_certificate_id', $clearance_certificate_id)->get('clearance_certificate_it_clearance_ftp')->result();
					for ($i = 0; $i < count($res); $i++) {
						$id = $res[$i]->id;
						$input_array = array();
						$input_array['clearance_certificate_id'] = $clearance_certificate_id;
						$input_array['it_clearance_ftp_delete']  = 	empty($input['it_clearance_ftp_delete'][$i]) ? 0 : 1;
						$input_array['it_clearance_ftp_reset_password']  = empty($input['it_clearance_ftp_reset_password'][$i]) ? 0 : 1;
						$input_array['it_clearance_ftp_reset_password_value']  = @$input['it_clearance_ftp_reset_password_value'][$i];
						$input_array['updated_at'] = date('Y-m-d H:i:s');
						$this->db->update('clearance_certificate_it_clearance_ftp', $input_array, array("id" => $id));
					}
				}
				$where = array("id" => $input['clearance_certificate_id']);
				$main_data['updated_at'] = date('Y-m-d H:i:s');
				$res = $this->db->update('clearance_certificate', $main_data, $where);
			}
			if (!empty($input['it_clearance_section_3'])) {

				$is_it_added = $this->get_clearance_certificate_status($clearance_certificate_id, 'is_it_validation_added');
				if ($is_it_added == 0) {
					$status = 7;
					$main_data['is_it_validation_added'] = 1;
				}
				if (!empty($status)) {
					$update_array = array("status" => $status);
					$where = array("id" => $input['resignation_id']);
					$this->db->update('resignation', $update_array, $where);


					$employee_details = $this->get_resignation_employee_details($input['resignation_id']);
					$subject = "IT Clearance Validation Completed";
					$body = "IT clearance validation completed for the resignation raised by " . $employee_details->name;


					$all_it_admin = $this->get_all_special_role_users("IT Admin");
					foreach ($all_it_admin as $row) {
						$emailToName = $row->name;
						$emailTo = $row->email;
						_sendMail($subject, $body, $emailTo, $emailToName);
					}
				}
				$main_data['it_clearance_voip_validation']				= @$input['it_clearance_voip_validation'];
				$main_data['it_clearance_sales_force_validation']		= @$input['it_clearance_sales_force_validation'];
				$main_data['it_clearance_pc_data_validation']			= @$input['it_clearance_pc_data_validation'];
				$main_data['it_clearance_domain_login_validation']		= @$input['it_clearance_domain_login_validation'];
				$main_data['it_clearance_system_format_validation']		= @$input['it_clearance_system_format_validation'];
				$main_data['it_clearance_system_validation'] 			= @$input['it_clearance_system_validation'];

				if (!empty($input['it_clearance_email_validation'])) {
					$res = $this->db->where('clearance_certificate_id', $clearance_certificate_id)->get('clearance_certificate_it_clearance')->result();

					for ($i = 0; $i < count($res); $i++) {
						$id = $res[$i]->id;
						$input_array = array();
						$input_array['clearance_certificate_id'] = $clearance_certificate_id;
						if (!empty($input['it_clearance_email_validation']) && !empty($input['it_clearance_email_validation'][$i]))
							$input_array['it_clearance_email_validation']  = @$input['it_clearance_email_validation'][$i];
						$input_array['updated_at'] = date('Y-m-d H:i:s');
						$this->db->update('clearance_certificate_it_clearance', $input_array, array("id" => $id));
					}
				}
				$where = array("id" => $input['clearance_certificate_id']);
				$main_data['updated_at'] = date('Y-m-d H:i:s');
				$res = $this->db->update('clearance_certificate', $main_data, $where);
			}



			//echo $input['it_clearance_domain_login'];

			//echo json_encode($input['it_clearance_email_alias_to']);


			if (!empty($input['clearance_certificate_id'])) {


				return $clearance_certificate_id;
			}
		}

		return false;
	}
	function get_resignation_employee_details($resignation_id)
	{
		$resignation_details = $this->get_resignation_by_id($resignation_id);
		$employee_details = $this->get_employee_by_id($resignation_details->employee_id);
		return $employee_details;
	}
	function update_clearance_certificate_admin_clearance($input = array())
	{
		if (!empty($input)) {
			//echo json_encode($input);
			$main_data = array();
			$clearance_certificate_id = $input['clearance_certificate_id'];
			$status = 0;
			$is_it_added = $this->get_clearance_certificate_status($clearance_certificate_id, 'is_admin_added');
			if ($is_it_added == 0) {
				$status = 8;
				$main_data['is_admin_added'] = 1;
			}
			$main_data['admin_comments']						= $input['admin_comments'];

			$main_data['admin_clearance_reporting_manager_name']		= $input['admin_clearance_reporting_manager_name'];
			$main_data['admin_clearance_date']	= empty($input['admin_clearance_date']) ? null : date('Y-m-d', strtotime($input['admin_clearance_date']));

			$where = array("id" => $input['clearance_certificate_id']);
			$main_data['updated_at'] = date('Y-m-d H:i:s');
			$res = $this->db->update('clearance_certificate', $main_data, $where);
			if (!empty($input['clearance_certificate_id'])) {

				if (!empty($input['admin_clearance_duties'])) {
					$this->deleteClearanceCertificateMultiValues('clearance_certificate_adamin_clearance_duties', $clearance_certificate_id);
				}

				for ($i = 0; $i < count($input['admin_clearance_duties']); $i++) {
					$input_array = array();
					$input_array['clearance_certificate_id'] = $clearance_certificate_id;
					$input_array['admin_clearance_duties']  = $input['admin_clearance_duties'][$i];
					$input_array['admin_clearance_moved_to_store']  = empty($input['admin_clearance_moved_to_store'][$i]) ? 0 : 1;
					$input_array['admin_clearance_management_entry']  = empty($input['admin_clearance_management_entry'][$i]) ? 0 : 1;
					$input_array['created_at'] = date('Y-m-d H:i:s');
					$input_array['updated_at'] = date('Y-m-d H:i:s');
					$this->db->insert('clearance_certificate_adamin_clearance_duties', $input_array);
				}
				if (!empty($status)) {
					$update_array = array("status" => $status);
					$where = array("id" => $input['resignation_id']);
					$this->db->update('resignation', $update_array, $where);

					$employee_details = $this->get_resignation_employee_details($input['resignation_id']);
					$subject = "Admin Clearance Completed";
					$body = "Admin clearance completed for the resignation raised by " . $employee_details->name;


					$all_hod = $this->get_all_special_role_users("HOD");
					foreach ($all_hod as $row) {
						$emailToName = $row->name;
						$emailTo = $row->email;
						_sendMail($subject, $body, $emailTo, $emailToName);
					}
				}


				return $clearance_certificate_id;
			}
		}

		return false;
	}

	function update_clearance_certificate_hod_clearance($input = array())
	{
		if (!empty($input)) {
			//echo json_encode($input);
			$main_data = array();
			$clearance_certificate_id = $input['clearance_certificate_id'];
			$status = 0;
			$is_it_added = $this->get_clearance_certificate_status($clearance_certificate_id, 'is_hod_added');

			if ($is_it_added == 0) {

				$status = 9;
				$main_data['is_hod_added'] = 1;
			}
			$main_data['hod_comments']						= $input['hod_comments'];
			$main_data['hod_verification_admin_rep_name']		= @$input['hod_verification_admin_rep_name'];
			$main_data['hod_verification_date']	= empty($input['hod_verification_date']) ? null : date('Y-m-d', strtotime($input['hod_verification_date']));

			$where = array("id" => $input['clearance_certificate_id']);
			$main_data['updated_at'] = date('Y-m-d H:i:s');
			$res = $this->db->update('clearance_certificate', $main_data, $where);
			//echo $this->db->last_query();
			$clearance_certificate_id = $input['clearance_certificate_id'];
			if (!empty($status)) {
				//echo "inside 2 ::"; 
				//echo "</br>";
				$update_array = array("status" => $status);
				$where = array("id" => $input['resignation_id']);
				$this->db->update('resignation', $update_array, $where);

				$employee_details = $this->get_resignation_employee_details($input['resignation_id']);
				$subject = "HOD Clearance Completed";
				$body = "HOD clearance completed for the resignation raised by " . $employee_details->name;
				$all_hcm = $this->get_all_special_role_users("HCM");
				foreach ($all_hcm as $row) {
					$emailToName = $row->name;
					$emailTo = $row->email;
					_sendMail($subject, $body, $emailTo, $emailToName);
				}
			}
			return $clearance_certificate_id;
		}

		return false;
	}
	function update_clearance_certificate_hcm_clearance($input = array())
	{
		if (!empty($input)) {
			$main_data = array();
			$clearance_certificate_id = $input['clearance_certificate_id'];
			$status = 0;
			$is_it_added = $this->get_clearance_certificate_status($clearance_certificate_id, 'is_hcm_added');
			if ($is_it_added == 0) {
				$status = 10;
				$main_data['is_hcm_added'] = 1;
			}

			$main_data['hcm_clearance_id_card_received']						= empty($input['hcm_clearance_id_card_received']) ? 0 : $input['hcm_clearance_id_card_received'];
			$main_data['hcm_access_control_deleted_time']		= empty($input['hcm_access_control_deleted_time']) ? null : date('H:i', strtotime($input['hcm_access_control_deleted_time']));
			$main_data['hcm_access_control_deleted_date']	= empty($input['hcm_access_control_deleted_date']) ? null : date('Y-m-d', strtotime($input['hcm_access_control_deleted_date']));
			$main_data['hcm_clearance_is_nda']						= empty($input['hcm_clearance_is_nda']) ? 0 : 1;
			$main_data['is_ohrm_deactivated']						= empty($input['is_ohrm_deactivated']) ? 0 : 1;;
			$main_data['is_pulse_deactivated']						= empty($input['is_pulse_deactivated']) ? 0 : 1;;
			$main_data['is_timelinq_deactivated']						= empty($input['is_timelinq_deactivated']) ? 0 : 1;
			$main_data['hcm_comments']						= @$input['hcm_comments'];
			$main_data['hcm_rep_name']						= @$input['hcm_rep_name'];
			$main_data['hcm_clearance_date']	= empty($input['hcm_clearance_date']) ? null : date('Y-m-d', strtotime($input['hcm_clearance_date']));

			$where = array("id" => $input['clearance_certificate_id']);
			$main_data['updated_at'] = date('Y-m-d H:i:s');
			$res = $this->db->update('clearance_certificate', $main_data, $where);
			//echo $this->db->last_query();
			$clearance_certificate_id = $input['clearance_certificate_id'];
			if (!empty($status)) {
				$update_array = array("status" => $status);
				$where = array("id" => $input['resignation_id']);
				$this->db->update('resignation', $update_array, $where);

				$employee_details = $this->get_resignation_employee_details($input['resignation_id']);
				$subject = "HCM Clearance Completed";
				$body = "HCM clearance completed for the resignation raised by " . $employee_details->name;

				$all_cfo = $this->get_all_special_role_users("CFO");
				foreach ($all_cfo as $row) {
					$emailToName = $row->name;
					$emailTo = $row->email;
					_sendMail($subject, $body, $emailTo, $emailToName);
				}
			}
			return $clearance_certificate_id;
		}

		return false;
	}
	function update_clearance_certificate_cfo_clearance($input = array())
	{
		if (!empty($input)) {
			$main_data = array();
			$clearance_certificate_id = $input['clearance_certificate_id'];
			$status = 0;
			$is_it_added = $this->get_clearance_certificate_status($clearance_certificate_id, 'is_cfo_added');
			if ($is_it_added == 0) {
				$status = 11;
				$main_data['is_cfo_added'] = 1;
			}

			$main_data['final_approval_cfo_name']						= @$input['final_approval_cfo_name'];
			$main_data['final_approval_date']	= empty($input['final_approval_date']) ? null : date('Y-m-d', strtotime($input['final_approval_date']));
			$where = array("id" => $input['clearance_certificate_id']);
			$main_data['updated_at'] = date('Y-m-d H:i:s');
			$res = $this->db->update('clearance_certificate', $main_data, $where);
			//echo $this->db->last_query();
			if (!empty($status)) {
				$update_array = array("status" => $status);
				$where = array("id" => $input['resignation_id']);
				$this->db->update('resignation', $update_array, $where);
			}
			return $clearance_certificate_id;
		}

		return false;
	}
	function update_clearance_certificate_final_clearance($input = array())
	{
		if (!empty($input)) {
			$main_data = array();

			$clearance_certificate_id = $input['clearance_certificate_id'];
			$status = 0;
			$is_it_added = $this->get_clearance_certificate_status($clearance_certificate_id, 'is_final_added');
			if ($is_it_added == 0) {
				$status = 12;
				$main_data['is_final_added'] = 1;
			}

			$main_data['pl_balance']						= @$input['pl_balance'];
			$main_data['pl_excess']							= @$input['pl_excess'];
			$main_data['no_of_days']						= @$input['no_of_days'];
			$main_data['net_salary']						= @$input['net_salary'];
			$main_data['pl_encashment']						= @$input['pl_encashment'];
			$main_data['is_gratuity']						= @$input['is_gratuity'];
			$main_data['salary_advance']					= @$input['salary_advance'];
			$main_data['payable_amount']					= @$input['payable_amount'];
			$main_data['check_no']							= @$input['check_no'];
			$main_data['check_no_date']						= empty($input['check_no_date']) ? null : date('Y-m-d', strtotime($input['check_no_date']));
			$main_data['check_drawn_date']					= empty($input['check_drawn_date']) ? null : date('Y-m-d', strtotime($input['check_drawn_date']));
			$main_data['check_amount']						= @$input['check_amount'];

			$where = array("id" => $input['clearance_certificate_id']);
			$main_data['updated_at'] = date('Y-m-d H:i:s');
			$res = $this->db->update('clearance_certificate', $main_data, $where);
			//echo $this->db->last_query();
			$clearance_certificate_id = $input['clearance_certificate_id'];
			if (!empty($status)) {
				//$main_data['clearance_certificate_submitted'] = 1;
				$update_array = array("status" => $status, "clearance_certificate_submitted" => 1);
				$where = array("id" => $input['resignation_id']);
				$this->db->update('resignation', $update_array, $where);

				$employee_details = $this->get_resignation_employee_details($input['resignation_id']);
				$body = "Exit clearance approved for the resignation of " . $employee_details->name;
				$subject = "Exit Clearance Approved";
				$this->send_mails_to_all($subject, $body);
			}
			return $clearance_certificate_id;
		}

		return false;
	}

	function get_clearance_certificate_by_resignation_id($resignation_id)
	{
		$res = $this->db->select('*')->from('clearance_certificate')->where('resignation_id', $resignation_id)->get()->row();

		if (!empty($res)) {
			$res2 = $this->db->select('*')->from('clearance_certificate_adamin_clearance_duties')->where('clearance_certificate_id', $res->id)->get()->result();
			$res3 = $this->db->select('*')->from('clearance_certificate_duties')->where('clearance_certificate_id', $res->id)->get()->result();
			$res4 = $this->db->select('*')->from('clearance_certificate_it_clearance')->where('clearance_certificate_id', $res->id)->get()->result();
			$res5 = $this->db->select('*')->from('clearance_certificate_it_clearance_ftp')->where('clearance_certificate_id', $res->id)->get()->result();
			$res6 = $this->db->select('*')->from('clearance_certificate_pending_tasks')->where('clearance_certificate_id', $res->id)->get()->result();
			$res7 = $this->db->select('*')->from('clearance_certificate_reports')->where('clearance_certificate_id', $res->id)->get()->result();
			$res->adamin_clearance_duties = $res2;
			$res->duties = $res3;
			$res->it_clearance = $res4;
			$res->it_clearance_ftp = $res5;
			$res->pending_tasks = $res6;
			$res->reports = $res7;
		}
		//echo json_encode($res);

		return $res;
	}
	function get_clearance_certificate_status($id, $column)
	{
		$res = $this->db->select($column)->from('clearance_certificate')->where('id', $id)->get()->row();
		if (!empty($res)) {
			return $res->$column;
		}
		return 0;
	}
	function deleteClearanceCertificateMultiValues($table, $clearance_certificate_id)
	{
		$where = array("clearance_certificate_id" => $clearance_certificate_id);
		$this->db->delete($table, $where);
	}


	// for employee 

	function trigger_probation_confirmation_email()
	{
		//echo "dsjfhdjshf";
		$query = "SELECT * FROM employee WHERE CURDATE()>=DATE_SUB(confirmation_duedate, INTERVAL 7 DAY) and probation_status=1";
		$res = $this->db->query($query)->result();
		//echo $this->db->last_query();
		//echo json_encode($res);
		if (!empty($res)) {
			foreach ($res as $row) {
				//$this->db->update('resignation', array("status" => 17, "exit_interview_triggered" => 1), array("id" => $row->id));
				$employee_details = $this->get_employee_by_id($row->reporting_manager_id);
				$subject = "Probation Confirmation is Due";
				$body = "Probation confirmation of Mr/Ms " . $row->employee_id . "(" . $row->code . ") is due on " . date('d-m-Y', strtotime($row->confirmation_duedate));

				_sendMail($subject, $body, $employee_details->email, $employee_details->name);
				// $admins = $this->common_model->get_all_admins();
				// foreach ($admins as $row) {
				// 	$emailToName = $row->name;
				// 	$emailTo = $row->email;
				// 	_sendMail($subject, $body, $emailTo, $emailToName);
				// }
			}
		}
		return true;
	}








	// Aneesh start
	public function get_resignations($select, array $where = [], array $statements = [])
	{

		$this->db->select($select);
		$this->db->from('resignation r');
		if (!empty($statements['join']) && is_string($statements['join'])) {
			$tables = explode(',', $statements['join']);
			if (in_array('e', $tables)) {
				$this->db->join('employee e', 'e.employee_id=r.employee_id', 'left');
			}
			if (in_array('pre_state', $tables)) {
				$this->db->join('state pre_state', 'pre_state.state_id=e.pre_state_id', 'left');
			}
			if (in_array('per_state', $tables)) {
				$this->db->join('state per_state', 'per_state.state_id=e.state_id', 'left');
			}
			if (in_array('pre_district', $tables)) {
				$this->db->join('district pre_district', 'pre_district.district_id=e.pre_district_id', 'left');
			}
			if (in_array('per_district', $tables)) {
				$this->db->join('district per_district', 'per_district.district_id=e.district_id', 'left');
			}
			if (in_array('pre_country', $tables)) {
				$this->db->join('country pre_country', 'pre_country.country_id=e.pre_country_id', 'left');
			}
			if (in_array('per_country', $tables)) {
				$this->db->join('country per_country', 'per_country.country_id=e.country_id', 'left');
			}
			if (in_array('nationality', $tables)) {
				$this->db->join('nationality', 'nationality.nationality_id=e.nationality_id', 'left');
			}
			if (in_array('cat', $tables)) {
				$this->db->join('category cat', 'cat.category_id=e.category_id', 'left');
			}
			if (in_array('des', $tables)) {
				$this->db->join('designations des', 'des.designation_id=e.designation_id', 'left');
			}
			if (in_array('dep', $tables)) {
				$this->db->join('departments dep', 'dep.department_id =e.department_id', 'left');
			}
			if (in_array('roles', $tables)) {
				$this->db->join('roles', 'roles.role_id=e.role_id', 'left');
			}
			if (in_array('rm', $tables)) {
				$this->db->join('employee rm', 'rm.employee_id=e.reporting_manager_id', 'left');
			}
			if (in_array('rmroles', $tables)) {
				$this->db->join('roles rmroles', 'rmroles.role_id=rm.role_id', 'left');
			}
			if (in_array('wl', $tables)) {
				$this->db->join('work_locations wl', 'wl.work_location_id=e.work_location_id', 'left');
			}
		}

		if (!empty($where)) {
			$this->db->where($where);
			if (!empty($statements['or_where'])) {
				$this->db->or_where($statements['or_where']);
			}
		}
		if (!empty($statements['group_by'])) {
			$this->db->group_by($statements['group_by']);
		}
		if (!empty($statements['order_by'])) {
			$this->db->order_by($statements['order_by']);
		}
		if (!empty($statements['or_like'])) {
			$this->db->group_start();
			$this->db->or_like($statements['or_like']);
			$this->db->group_end();
		}
		if (!empty($statements['limit'])) {
			$this->db->limit($statements['limit']);
		}

		$query = $this->db->get();
		// echo $this->db->last_query();exit;
		return $query;
	}
	// Aneesh end
}
