<?php

defined('BASEPATH') or exit('No direct script access allowed');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Attendance extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Attendance_model', 'attendance');
    }

    public function add_attendance()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        if (!is_admin()) {
            $this->session->set_flashdata('error', 'Permission denied!');
            redirect('dashboard', 'refresh');
        }

        $page_data['page_type']    = 'attendance';
        $page_data['page_name']    = 'add_attendance';
        $page_data['menu']         = 'attendance';
        $page_data['page_title']   = 'Add Attendance';
        $this->load->view('theme/user/main', $page_data);

    }

    public function popup($page_name = '', $param2 = '', $param3 = '', $param4 = '')
    {

        $page_data['param2']        =   $param2;
        $page_data['param3']        =   $param3;
        $page_data['param4']        =   $param4;

        $this->load->view('attendance/'.$page_name, $page_data);

    }

    public function attendance_import()
    {

        if (!check_user_login()) {
            redirect('signin', 'refresh');
        }

        if (!is_admin()) {
            $this->session->set_flashdata('error', 'Permission denied!');
            redirect('dashboard', 'refresh');
        }

        set_time_limit(0);
        $page_data['page_type']    = 'attendance';
        $page_data['page_name']    = 'attendance_import';
        $page_data['menu']         = 'attendance';
        $page_data['productsData']	   = [];
        $page_data['page_title']   = 'Bulk Import';
        $this->load->view('theme/user/main', $page_data);
    }

    public function get_excel_data()
    {

        $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        if (isset($_FILES)) {
            set_time_limit(0);
            $ext		= pathinfo($_FILES["products"]["name"])['extension'];
            $fileName	= $_FILES["products"]["tmp_name"];
            $i			= 0;
            $j			= 0;
            $dataArray	= array();
            if ($_FILES["products"]["size"] > 0 && ($ext == 'csv' || $ext == 'CSV' || $ext == 'xlsx' || $ext == 'XLSX' || $ext == 'xls' || $ext == 'XLS') && in_array($_FILES['products']['type'], $file_mimes)) {

                if('csv' == $ext) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                } elseif('xls' == $ext) {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }

                $spreadsheet    = $reader->load($fileName);
                $sheet_data     = $spreadsheet->getActiveSheet()->toArray();

                //pr($sheet_data);

                if(!empty($sheet_data)) {
                    foreach($sheet_data as $key => $value) {

                        if ($key == 0) {
                            continue;
                        }

                        if (trim($value[0]??'') != "") {
                            $dataArray[] = array(
                            "code" => trim($value[0]??''),
                            "name" => trim($value[1]??''),
                            "department" => trim($value[2]??''),
                            "attendance_date" => date('d/m/Y', strtotime(trim($value[3]))),
                            "in_time" => trim($value[4]??'00:00'),
                            'out_time' => trim($value[5]??'00:00'),
                            'shift' => trim($value[6]??''),
                            "duration" => trim($value[7]??'00:00'),
                            'status' => trim($value[8]??''),
                            'remarks' => trim($value[9]??'')
                            );
                        }
                    }
                }                
            }

            if (!empty($dataArray)) {
                $response = array('status' => 1, 'data' => $dataArray, 'msg' => 'Uploaded successfully!');
                echo json_encode($response);
                exit;
            } else {
                $response = array('status' => 0, 'data' => $dataArray, 'msg' => 'Something went wrong!');
                echo json_encode($response);
                exit;
            }
        }
    }

    public function save_products()
    {
        set_time_limit(0);
        $strRequest = file_get_contents('php://input');
        $Request = json_decode($strRequest, true);

        $dataBatch = array();

        $sadded = 0;
        $serror = 0;
        $nocode = 0;
        $noexist = 0;
        $aexist = 0;

        for ($i = 0; $i < count($Request); $i++) {

            if ($Request[$i]['code'] == "") {
                $nocode++;
                continue;
            } else {

                $exist = $this->common_model->selectOne('employee', array('code' => $Request[$i]['code']), 'employee_id');
                if(empty($exist)) {
                    $noexist++;
                } else {

                    $param = array(
                        "employee_id" => $exist['employee_id'],
                        "attendance_date" => set_date($Request[$i]['attendance_date']),
                        "in_time" => $Request[$i]['in_time'],
                        "out_time" => $Request[$i]['out_time'],
                        'shift' => $Request[$i]['shift'],
                        'duration' => $Request[$i]['duration'],
                        'status' => $Request[$i]['status'],
                        'remarks' => $Request[$i]['remarks']
                      );

                    //check attendance already added
                    $checkexist = $this->common_model->selectOne('attendance', array('employee_id' => $exist['employee_id'],'attendance_date' => set_date($Request[$i]['attendance_date'])), 'id');

                    if(!empty($checkexist)) {
                        $aexist++;
                    } else {
                        $attendance_id = $this->common_model->insert($param, 'attendance');
                        if(!empty($attendance_id)) {
                            $sadded++;
                        } else {
                            $serror++;
                        }
                    }
                }
            }
        }

        $response = array('status' => 1, 'attendance_added_count' => $sadded, 'attendance_error_count' => $serror, 'no_emp_code' => $nocode, 'no_emp_exist' => $noexist,'attendance_exist' => $aexist, 'msg' => 'Response');
        echo json_encode($response);
        exit;
    }

    public function ajax_attendance()
    {
        $page_data['count'] = $this->input->post('count');
        $this->load->view('attendance/ajax_attendance', $page_data);
    }

    public function add_attendance_process()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $this->form_validation->set_rules('employee_id', 'Employee id', 'trim|required');
        $this->form_validation->set_rules('attendance_date[]', 'Date', 'trim|required');
        $this->form_validation->set_rules('in_time[]', 'In Time', 'trim|required');
        $this->form_validation->set_rules('out_time[]', 'Out Time', 'trim|required');
        $this->form_validation->set_rules('duration[]', 'Duration', 'trim|required');
        $this->form_validation->set_rules('shift[]', 'shift', 'trim');
        $this->form_validation->set_rules('status[]', 'Status', 'trim|required');
        $this->form_validation->set_rules('remarks[]', 'Remarks', 'trim');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
        if($this->form_validation->run()) {

            $data = $this->input->post(null, true);

            $employee_id = $data['employee_id'];

            if(!empty($employee_id)) {

                $atten = array();

                if(!empty($data['attendance_date'])) {
                    $i = 0;
                    foreach($data['attendance_date'] as $key => $value) {

                        $checkexist = $this->common_model->selectOne('attendance', array('employee_id' => $employee_id,'attendance_date' => set_date($data['attendance_date'][$key])), 'id');

                        if(empty($checkexist)) {
                            $atten[$i]['employee_id']     = $employee_id;
                            $atten[$i]['attendance_date'] = set_date($data['attendance_date'][$key]);
                            $atten[$i]['in_time']         = $data['in_time'][$key];
                            $atten[$i]['out_time']        = $data['out_time'][$key];
                            $atten[$i]['duration']        = $data['duration'][$key];
                            $atten[$i]['shift']           = $data['shift'][$key];
                            $atten[$i]['status']          = $data['status'][$key];
                            $atten[$i]['remarks']         = $data['remarks'][$key];
                            $i++;
                        }

                    }
                }

                //remove empty details
                $c = function ($v) {
                    return array_filter($v) != array();
                };
                $atten = array_filter($atten, $c);

                if(!empty($atten)) {
                    $this->db->trans_start();
                    $this->db->insert_batch('attendance', $atten);
                    $this->db->trans_complete();
                    if ($this->db->trans_status() === true) {
                        $response = array('status' => 1, 'msg' => 'Attendance added successfully!');
                        echo json_encode($response);
                        exit;
                    } else {
                        $response = array('status' => 0, 'msg' => 'Attendance not added!');
                        echo json_encode($response);
                        exit;
                    }
                } else {
                    $response = array('status' => 0, 'msg' => 'Attendance not added!');
                    echo json_encode($response);
                    exit;
                }

            } else {

                $response = array('status' => 0, 'msg' => 'Employee details not found!');
                echo json_encode($response);
                exit;

            }

        } else {
            $response = array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
            exit;
        }
    }

    public function view_attendance()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        if (!is_admin()) {
            $this->session->set_flashdata('error', 'Permission denied!');
            redirect('dashboard', 'refresh');
        }

        $page_data['page_type']    = 'attendance';
        $page_data['menu']         = 'attendance';
        $page_data['page_name']    = 'view_attendance';
        $page_data['page_title']   = 'View Attendance';
        $this->load->view('theme/user/main', $page_data);
    }

    public function attendance_history_list()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $employee_id = $this->input->post('employee_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        //$employee_id = $this->common_model->employees_id_by_code($code);
        $attendance_history_list  = $this->attendance->attendance_history_list($employee_id, $from_date, $to_date);

        $assi = [];
        if(!empty($attendance_history_list)) {
            foreach($attendance_history_list as $key => $value) {

                $assi[$key]['name'] = '<a href="'.base_url().'profile/'.$value['code'].'" title="View profile" target="_blank"><h6 class="mb-0">'.$value['name'].'</h6></a>';
                $assi[$key]['code'] = $value['code'];

                $assi[$key]['attendance_date']  = get_date($value['attendance_date']);
                $assi[$key]['in_time'] = $value['in_time'];
                $assi[$key]['out_time']  = $value['out_time'];
                $assi[$key]['out_time']  = $value['out_time'];
                $assi[$key]['duration']  = $value['duration'];
                $assi[$key]['shift']  = $value['shift'];
                $assi[$key]['leave_status']  = leave_status($value['duration']);
                $assi[$key]['status']  = $value['status'];
                $assi[$key]['remarks']  = $value['remarks'];
            }
        }
        echo json_encode($assi);
    }

}
