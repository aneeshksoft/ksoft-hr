<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'third_party/REST_Controller.php';
require_once APPPATH . 'third_party/Format.php';
require_once APPPATH . '/libraries/JWT.php';
require_once APPPATH . '/libraries/BeforeValidException.php';
require_once APPPATH . '/libraries/ExpiredException.php';
require_once APPPATH . '/libraries/SignatureInvalidException.php';

require __DIR__ . '/../../vendor/autoload.php';

use \Firebase\JWT\JWT;

class Api extends REST_Controller
{
	protected $output = array();
	private $auth;
	function __construct()
	{

		parent::__construct();

		ini_set('MAX_EXECUTION_TIME', '-1');

		$this->load->helper("date");
	}
	public function auth()
	{
		//JWT Auth middleware
		$headers = $this->input->get_request_header('Authorization');
		$ky = $this->config->item('jwt_key');
		$token = "token";
		if (!empty($headers)) {
			if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
				$token = $matches[1];
			}
		}
		try {
			$decoded = JWT::decode($token, $ky, array('HS256'));
			$this->user_data  = $decoded;
			$this->user_token = $token;
		} catch (Exception $e) {
			$invalid = ['status' => FALSE, 'message' => $e->getMessage()];
			$this->response($invalid, 401);
		}
	}

	//generate jwt token
	function generate_token($row_usr)
	{

		$ky = $this->config->item('jwt_key');
		$token['employee_id'] = $row_usr['employee_id'];
		$token['code'] = $row_usr['code'];
		$token['name'] = $row_usr['name'];
		$date = new DateTime();
		$token['iat'] = $date->getTimestamp();
		$token['exp'] = $date->getTimestamp() + (2628000); //jwt expiration
		$rtoken = JWT::encode($token, $ky);
		return $rtoken;
	}

	function login_post()
	{

		$data = json_decode(file_get_contents('php://input'), true);
		$code  = $data['code'];
		$password  = $data['password'];
		$exist = $this->common_model->selectOne('employee', array('code' => trim($data['code'])), 'employee_id,code,password,designation_id,department_id,status,name');

		if (!empty($exist)) {

			if ($exist['password'] == md5($data['password'])) {

				if ($exist['status'] !== 'inactive' && $exist['status'] !== 'resigned') {

					$this->common_model->updateLastLogin($exist['employee_id']);

					$output['token']                      = $this->generate_token($exist);
					$output['employee']['employee_id']    = $exist['employee_id'];
					$output['employee']['code']           = $exist['code'];
					$output['employee']['name']           = $exist['name'];
					$output['employee']['designation_id'] = $exist['designation_id'];
					$output['employee']['department_id']  = $exist['department_id'];
					$output['employee']['status']         = $exist['status'];

					$this->set_response($output, REST_Controller::HTTP_OK);
				} else {
					$output = ['status' => FALSE, 'message' => 'Please contact administrator!'];
					$this->set_response($output, REST_Controller::HTTP_NOT_FOUND);
				}
			} else {
				$output = ['status' => FALSE, 'message' => 'Invalid password'];
				$this->set_response($output, REST_Controller::HTTP_NOT_FOUND);
			}
		} else {
			$output = ['status' => FALSE, 'message' => 'Invalid code'];
			$this->set_response($output, REST_Controller::HTTP_NOT_FOUND);
		}
	}

	//get user info by token
	function employeeinfo_get()
	{

		$this->auth();
		$details = $this->user_data; // data from token
		$employee = $this->common_model->employees_by_id($details->employee_id);
		if (!empty($employee)) {
			$output['token']    = $this->user_token;
			$output['employee']     = $employee;
			$this->set_response($output, REST_Controller::HTTP_OK);
		} else {
			$output = [];
			$this->set_response($output, REST_Controller::HTTP_OK);
		}
	}

	function worklocations_get()
	{

		$this->auth();
		$details = $this->user_data; // data from token
		$locations = $this->common_model->assigned_work_locations_by_employee($details->employee_id);
		if (!empty($locations)) {
			$output = $locations;
			$this->set_response($output, REST_Controller::HTTP_OK);
		} else {
			$output = [];
			$this->set_response($output, REST_Controller::HTTP_OK);
		}
	}

	function inoutstatus_get()
	{

		$this->auth();
		$details        = $this->user_data; // data from token
		$employee_id    = $details->employee_id;
		$assignment_id  = $this->get('assignment_id');
		$locations = $this->common_model->inoutstatus($employee_id, $assignment_id);
		if (!empty($locations)) {
			$output = $locations;
			$this->set_response($output, REST_Controller::HTTP_OK);
		} else {
			$output = [];
			$this->set_response($output, REST_Controller::HTTP_OK);
		}
	}

	function inoutupdate_post()
	{

		$this->auth();
		$details = $this->user_data; // data from token

		$data = json_decode(file_get_contents('php://input'), true);
		if ($data['status'] == 'in') {

			$param = array(
				'employee_id'   => $details->employee_id,
				'assignment_id' => $data['assignment_id'],
				'in_datetime'   => $data['in_datetime'],
				'in_latitude'   => $data['in_latitude'],
				'in_longitude'  => $data['in_longitude'],
				'status'        => 'in',
				'in_remarks'    => (!empty($data['remarks'])) ? $data['remarks'] : NULL,
				'created_at'    => date('Y-m-d H:i:s'),
			);

			$res = $this->common_model->insert($param, 'work_location_inout');
			if (!empty($res)) {
				$output = ['status' => TRUE, 'inout_id' => $res, 'message' => 'Punch in successfully!'];
				$this->set_response($output, REST_Controller::HTTP_OK);
			} else {
				$output = ['status' => FALSE, 'message' => 'Punch in not updated'];
				$this->set_response($output, REST_Controller::HTTP_NOT_FOUND);
			}
		} else {
			$param = array(
				'out_datetime'  => $data['out_datetime'],
				'out_latitude'  => $data['out_latitude'],
				'out_longitude' => $data['out_longitude'],
				'status'        => 'out',
				'out_remarks'   => (!empty($data['remarks'])) ? $data['remarks'] : NULL,
				'updated_at'    => date('Y-m-d H:i:s'),
			);

			$res = $this->common_model->update($param, array('inout_id' => $data['inout_id'], 'employee_id' => $details->employee_id, 'assignment_id' => $data['assignment_id']), 'work_location_inout');
			if (!empty($res)) {
				$output = ['status' => TRUE, 'message' => 'Punch out successfully!'];
				$this->set_response($output, REST_Controller::HTTP_OK);
			} else {
				$output = ['status' => FALSE, 'message' => 'Punch out not updated'];
				$this->set_response($output, REST_Controller::HTTP_NOT_FOUND);
			}
		}
	}

	function inoutreport_get()
	{

		$this->auth();
		$details = $this->user_data; // data from token
		$limit    = ($this->get('per_page') != "") ? ((string)$this->get('per_page')) : "20";
		$start    = ($this->get('page') != 0) ? ((string)(($this->get('page') - 1) * $limit)) : "0";

		$orders = $this->common_model->inoutreport($details->employee_id, $limit, $start);
		if (!empty($orders)) {
			$output = $orders;
			$this->set_response($output, REST_Controller::HTTP_OK);
		} else {
			$output = [];
			$this->set_response($output, REST_Controller::HTTP_OK);
		}
	}

	function test_post()
	{

		$this->auth();
		$output['token']   = $this->user_token;
		$output['details'] = $this->user_data;
		$this->set_response($output, REST_Controller::HTTP_OK);
	}

}
