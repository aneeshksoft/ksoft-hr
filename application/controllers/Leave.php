<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Leave extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Leave_model', 'leave');
        $this->load->model('Employee_model', 'employee_model');
    }

    public function leave_type()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
        if (!is_admin()) {
            $this->session->set_flashdata('error', 'Permission denied!');
            redirect('dashboard', 'refresh');
        }
        $page_data['speacial_leave_approve_head']    =  $this->common_model->selectOne('additional_leave_approve_head', array(), '*');
        $page_data['employees']  =  $this->employee_model->get_employees("e.employee_id,e.name,e.code", array('e.probation_status' => '0'))->result_array();

        $page_data['page_type']    = 'leave';
        $page_data['page_name']    = 'leave_type';
        $page_data['menu']         = 'leave';
        $page_data['page_title']   = 'Add/Edit Leave Type';

        $this->load->view('theme/user/main', $page_data);

    }

    public function leave_type_list_ajax()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $all = $this->common_model->selectAll('leave_types', '', '');

        $leave_type['data'] = [];
        if(!empty($all)) {
            foreach($all as $key => $value) {

                $leave_type['data'][$key]['title'] = $value['title'];
                $leave_type['data'][$key]['carry_fwd_limit'] = $value['carry_fwd_limit'];
                $leave_type['data'][$key]['type'] = strtoupper($value['type']);
                $leave_type['data'][$key]['is_sandwich'] = ($value['is_sandwich'] == 0) ? 'No' : 'Yes';
                $leave_type['data'][$key]['special_leave_approval_needed'] = ($value['special_leave_approval_needed'] == 0) ? 'No' : 'Yes';
                $editBtn = '<button class="btn btn-sm btn-outline-secondary" type="button" onclick="showAjaxModal(\'' . base_url('leave/edit_leave_type/' . $value['leave_type_id']) . '\',\'Edit Leave Type\',\'modal-lg\')" title="Edit"><i class="fa fa-pencil-square-o"></i></button>&nbsp;';
                $deleteBtn = '<button type="button" class="btn btn-sm btn-outline-danger d-none" title="Delete" onclick="deleteLeaveType(\'' . $value['leave_type_id'] . '\')"><i class="fa fa-trash-o"></i></button>';
                $leave_type['data'][$key]['action'] = $editBtn;

            }
        }
        echo json_encode($leave_type);

    }
    public function add_leave_type()
    {

        if (!check_user_login()) {
            redirect('signin', 'refresh');
        }
        if (!is_admin()) {
            $this->session->set_flashdata('error', 'Permission denied!');
            redirect('dashboard', 'refresh');
        }

        $this->load->view('leave/add_leave_type');
    }

    public function edit_leave_type($id)
    {

        if (!check_user_login()) {
            redirect('signin', 'refresh');
        }
        if (!is_admin()) {
            $this->session->set_flashdata('error', 'Permission denied!');
            redirect('dashboard', 'refresh');
        }
        $leave_type = $this->db->get_where('leave_types', array('leave_type_id' => $id))->row_array();
        if (empty($leave_type)) {
            exit;
        }
        $page_data['leave_type'] = $leave_type;
        $this->load->view('leave/edit_leave_type', $page_data);
    }

    public function leave_type_process()
    {

        if (!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $this->form_validation->set_rules('title', 'Leave type name', 'trim|required');
        // $this->form_validation->set_rules('carry_fwd_limit', 'Carry Forward Limit', 'trim|required');
        $this->form_validation->set_rules('is_sandwich', 'Sandwich', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        if (!empty($_POST['type']) && $_POST['type'] == "direct") {
            $this->form_validation->set_rules('special_leave_approval_needed', 'Special leave approval needed?', 'trim|required');
        } else {
            unset($_POST['special_leave_approval_needed']);
        }
        $this->form_validation->set_rules('leave_type_id', 'leave_type_id', 'trim');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
        if ($this->form_validation->run()) {
            $data = $this->input->post(null, true);
            $leave_type_id = $data['leave_type_id'] ?? "";

            if (!empty($leave_type_id)) {
                $update['title'] = $data['title'];
                $update['carry_fwd_limit'] = 0;
                $update['is_sandwich'] = $data['is_sandwich'];
                $update['type'] = $data['type'];
                $update['special_leave_approval_needed'] = $data['special_leave_approval_needed'] ?? "0";
                $resp = $this->common_model->update($update, array('leave_type_id' => $leave_type_id), 'leave_types');
                if (!empty($resp)) {
                    $response = array('status' => 1, 'msg' => 'Updated successfully!');
                } else {
                    $response = array('status' => 0, 'msg' => 'Something went wrong!');
                }
            } else {
                $insert['title'] = $data['title'];
                $insert['carry_fwd_limit'] = 0;
                $insert['is_sandwich'] = $data['is_sandwich'];
                $insert['type'] = $data['type'];
                $insert['special_leave_approval_needed'] = $data['special_leave_approval_needed'] ?? "0";
                $resp = $this->common_model->insert($insert, 'leave_types');
                if (!empty($resp)) {
                    $response = array('status' => 1, 'msg' => 'Added successfully!');
                } else {
                    $response = array('status' => 0, 'msg' => 'Something went wrong!');
                }
            }
        } else {
            $response = array('status' => 0, 'msg' => validation_errors());
        }
        echo json_encode($response);
        exit;
    }

    //check employee code exist
    public function checkLeaveTypeExist()
    {
        $title = $this->input->post('title');
        $leave_type_id = $this->input->post('leave_type_id');
        if(!empty($title) && trim($title) != "") {
            if(!empty($leave_type_id)) {
                $exist = $this->common_model->selectAll('leave_types', array('title' => $title,'leave_type_id!=' => $leave_type_id), 'leave_type_id');
            } else {
                $exist = $this->common_model->selectAll('leave_types', array('title' => $title), 'leave_type_id');
            }
            if(!empty($exist)) {
                echo 'false';
            } else {
                echo 'true';
            }
        }
    }

    public function delete_leave_type_process()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $this->form_validation->set_rules('leave_type_id', 'Leave id', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
        if($this->form_validation->run()) {

            $data = $this->input->post(null, true);

            $resp = $this->common_model->delete_leave_type($data['leave_type_id']);
            if(!empty($resp)) {
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

    public function popup($page_name = '', $param2 = '', $param3 = '', $param4 = '')
    {

        $page_data['param2']        =   $param2;
        $page_data['param3']        =   $param3;
        $page_data['param4']        =   $param4;

        $this->load->view('leave/'.$page_name, $page_data);

    }

    public function leave_application()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $page_data['page_type']    = 'leave';
        $page_data['menu']         = 'leave';
        $page_data['page_name']    = 'leave_application';
        $page_data['page_title']   = 'Leave Application';
        $page_data['leave_types']  = $this->common_model->selectAll('leave_types', array('hidden_dropdown' => '0'), '');
        $holidays_qry = "select holiday_date from holidays where holiday_date >= '" . date('Y-m-d') . "'";
        $page_data['holidays'] = $this->db->query($holidays_qry)->result_array();
        $this->load->view('theme/user/main', $page_data);
    }

    public function leave_application_process()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $this->form_validation->set_rules('employee_id', 'Employee id', 'trim|required');
        $this->form_validation->set_rules('leave_type_id', 'Leave type', 'trim|required');
        $this->form_validation->set_rules('from_date', 'From date', 'trim|required');
        $this->form_validation->set_rules('to_date', 'To date', 'trim|required');
        $this->form_validation->set_rules('is_halfday', 'Is half day', 'trim|required');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
        if($this->form_validation->run()) {

            $data = $this->input->post(null, true);

            $availability = $this->leave->checkLeaveAvailability($data['employee_id']);

            if(empty($availability)) {
                $response = array('status' => 0, 'msg' => 'No month based leave available!');
                echo json_encode($response);
                exit;
            }

            $employee_id = $data['employee_id'];
            $session_id = $this->session->userdata('employee_id');

            $get_leave_type  = $this->common_model->selectOne('leave_types', array('leave_type_id' => $data['leave_type_id']), 'type');

            //check all heads are assigned
            //$reporting_heads = $this->leave->pending_head_assignment($employee_id);
            //to check RM is assigned for the employee
            if($get_leave_type['type'] == 'hierarchy') {
                $reporting_heads = $this->leave->rm_assigned_check($employee_id);
                if(empty($reporting_heads['head_1']) || empty($reporting_heads['head_2'])) {
                    $response = array('status' => 0, 'msg' => 'Reporting managers not assigned.');
                    echo json_encode($response);
                    exit;
                }
            } else {
                //foor direct  leaves special approval head is needed
                $reporting_heads = $this->leave->get_special_head();
                if(empty($reporting_heads['head_1'])) {
                    $response = array('status' => 0, 'msg' => 'Special approval head not assigned.');
                    echo json_encode($response);
                    exit;
                }
            }

            //check you are an rm of the employee if session other than the employee id
            if($employee_id != $session_id) {
                //$check_head  = $this->leave->check_in_heads_list($employee_id, $session_id);
                $check_head  = $this->leave->check_in_rm_list($employee_id, $session_id);
                if(empty($check_head)) {
                    $response = array('status' => 0, 'msg' => 'You are not authorized to apply leave!');
                    echo json_encode($response);
                    exit;
                }
            }

            $eligibility = $this->leave->get_leave_days($data['employee_id'], $data['leave_type_id'], $data['from_date'], $data['to_date'], $data['is_halfday']);

            if(!empty($eligibility)) {
                if($eligibility['status'] == 0) {
                    $response = array('status' => 0, 'msg' => 'No eligibility to apply leave!');
                    echo json_encode($response);
                    exit;
                }
            } else {
                $response = array('status' => 0, 'msg' => 'Eligibility data not found!');
                echo json_encode($response);
                exit;
            }

            if($eligibility['prevgroup_id'] != "") {
                //comment this checking to avoid adding leave to existing group id if its sandwich
                $group_id = $eligibility['prevgroup_id'];
            } else {
                $group_id = generate_uuid();//substr(sha1(time()), 0, 16);
            }

            $temp = 0;
            foreach($eligibility['all_dates'] as $row) {

                $param = array(
                    'employee_id' => $employee_id,
                    'leave_type_id' => $data['leave_type_id'],
                    'group_id' => $group_id,
                    'leave_date' => $row,
                    'is_halfday' => $data['is_halfday'],
                    'fn_an' => ($data['is_halfday'] == 1) ? $data['fn_an'] : null,
                    'is_sandwich_applied' => $eligibility['is_sandwich_applied'],
                    'remarks' => ($data['remarks'] != "") ? $data['remarks'] : null,
                    'status' => 'pending',
                    'status_changed' => date('Y-m-d'),
                    'created_by' => $this->session->userdata('employee_id'),
                    'created_at' => date('Y-m-d H:i:s')
                );

                $leave_id = $this->common_model->insert($param, 'leave_application');

                if(!empty($leave_id)) {
                    $temp = 1;
                }

            }

            if($temp == 1 && $group_id != "") {

                //$get_leave_type  = $this->common_model->selectOne('leave_types', array('leave_type_id' => $data['leave_type_id']), 'type');

                //$base_reporting_head = $this->leave->get_base_head($employee_id);
                //$top_reporting_head  = $this->leave->get_top_head($employee_id);

                if($get_leave_type['type'] == "hierarchy") {

                    $param1 = array(
                        'group_id' => $group_id,
                        'status' => 'pending',
                        'status_changed' => date('Y-m-d'),
                        'head_id' => $reporting_heads['head_1'],
                        'position' => '1',
                        'created_at' => date('Y-m-d H:i:s')
                    );

                } else {

                    $param1 = array(
                        'group_id' => $group_id,
                        'status' => 'pending',
                        'status_changed' => date('Y-m-d'),
                        'head_id' => $reporting_heads['head_1'],
                        'position' => '1',
                        'created_at' => date('Y-m-d H:i:s')
                    );

                }

                //check already entry with same group id - this case will come on sandwich leave
                $getexistgrp  = $this->common_model->selectOne('leave_application_status', array('group_id' => $group_id), 'leave_application_status_id');

                if(!empty($getexistgrp)) {
                    //update all group status to is_sandwhich. because when appending new set of sandwich leaves to normal leaves
                    $updatesandwich = $this->common_model->update(array('is_sandwich_applied' => '1'), array('group_id' => $group_id), 'leave_application');

                    $leave_application_status_id = $getexistgrp['leave_application_status_id'];
                } else {
                    //add base or top head id on creation. next head will add on approval
                    $leave_application_status_id = $this->common_model->insert($param1, 'leave_application_status');
                }

                if(!empty($leave_application_status_id)) {

                    //send email to head
                    $hid = $reporting_heads['head_1'];
                    @leaveApplicationEmail($employee_id, $hid, $group_id);
                    //send email

                    $response = array('status' => 1, 'msg' => 'Leave applied successfully!');
                    echo json_encode($response);
                    exit;
                } else {
                    $response = array('status' => 0, 'msg' => 'Leave status not added!');
                    echo json_encode($response);
                    exit;
                }

            } else {
                $response = array('status' => 0, 'msg' => 'Leave not added!');
                echo json_encode($response);
                exit;
            }

        } else {
            $response = array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
            exit;
        }

    }

    public function checkLeaveEligibility()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $employee_id = $this->input->post('employee_id');
        $leave_type_id = $this->input->post('leave_type_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $is_halfday = $this->input->post('is_halfday');
        $fn_an = $this->input->post('fn_an');

        if(!empty($employee_id) && !empty($leave_type_id) && !empty($from_date) && !empty($to_date)) {

            //check already leave applied for the date range
            if($is_halfday == 0) {
                $lexist_qry = "select leave_date from leave_application where employee_id = ? and status != 'rejected' and cancel_status = '0' and leave_date between ? and ?";
                $lexist_resp = $this->db->query($lexist_qry, array($employee_id, set_date($from_date), set_date($to_date)))->result_array();
                //echo $this->db->last_query();
          } else {
              $lexist_qry = "select leave_date from leave_application where employee_id = ? and status != 'rejected' and cancel_status = '0' and leave_date between ? and ? and fn_an = ?";
                $lexist_resp = $this->db->query($lexist_qry, array($employee_id, set_date($from_date), set_date($to_date),$fn_an))->result_array();
                //echo $this->db->last_query();
          }

            if(!empty($lexist_resp)) {
                $response = array('status' => 0, 'msg' => 'You have already applied leave on the dates. Please choose another range!');
                echo json_encode($response);
                exit;
            }

            $eligibility = $this->leave->get_leave_days($employee_id, $leave_type_id, $from_date, $to_date, $is_halfday);

            if(!empty($eligibility)) {
                $response = array('status' => 1, 'msg' => 'Success!', 'result' => $eligibility);
                echo json_encode($response);
                exit;
            } else {
                $response = array('status' => 0, 'msg' => 'Eligibility data not found!');
                echo json_encode($response);
                exit;
            }

        } else {
            $response = array('status' => 0, 'msg' => 'Employee details not found!');
            echo json_encode($response);
            exit;
        }

    }

    public function get_employee()
    {
        $data = $this->input->post('query');
        if(!empty($data) && trim($data) != "") {
            $exist = $this->leave->get_employee($data);
            if(!empty($exist)) {
                echo json_encode($exist);
            } else {
                echo json_encode(array());
            }
        } else {
            echo json_encode(array());
        }
    }

    public function get_employee_by_head()
    {
        $data = $this->input->post('query');
        $head_id = $this->input->post('head_id');
        if(!empty($data) && trim($data) != "") {
            $exist = $this->leave->get_employee_by_head($data, $head_id);
            if(!empty($exist)) {
                echo json_encode($exist);
            } else {
                echo json_encode(array());
            }
        } else {
            echo json_encode(array());
        }
    }

    public function leave_application_status()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        // if(!check_permission('3', 'v')) {
        //     $this->session->set_flashdata('error', 'Permission denied!');
        //     redirect('dashboard', 'refresh');
        // }

        $page_data['page_type']    = 'leave';
        $page_data['menu']         = 'leave';
        $page_data['page_name']    = 'leave_application_status';
        $page_data['page_title']   = 'Leave Application Status';
        $this->load->view('theme/user/main', $page_data);
    }

    public function leave_application_status_ajax()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $employee_id = $this->session->userdata('employee_id');
        $all = $this->leave->leave_application_status($employee_id);
        //pr($all);
        $leave['data'] = [];
        if(!empty($all)) {

            foreach($all as $key => $value) {

                $dates = explode(",", $value['from_to']);
                usort($dates, function ($a, $b) {
                    return strtotime($a) - strtotime($b);
                });

                $leave['data'][$key]['name'] = '<a href="'.base_url().'profile/'.$value['code'].'" title="View profile" target="_blank"><h6 class="mb-0">'.$value['name'].'</h6><span>'.$value['code'].'</span></a>';
                $leave['data'][$key]['created_at'] = get_date($value['created_at']);
                $leave['data'][$key]['leave_type'] = '<span class="badge badge-danger">'.$value['title'].'</span>';
                $leave['data'][$key]['is_halfday'] = is_halfday($value['is_halfday']);
                $leave['data'][$key]['fn_an'] = $value['fn_an'] ?? '-';
                $leave['data'][$key]['date'] = '<strong>'.get_date($dates[0]).'</strong> to <strong>'.get_date($dates[count($dates) - 1]).'</strong>';
                $leave['data'][$key]['leave_count'] = trimDecimal($value['total_count']);
                $leave['data'][$key]['remarks'] = ($value['remarks'] != "") ? (nl2br($value['remarks'])) : '-';
                $leave['data'][$key]['status'] = leave_status_c($value['status']);

                $leave['data'][$key]['status_changed'] = get_date($value['status_changed']);
                $leave['data'][$key]['action'] = '<button type="button" class="btn btn-sm btn-outline-success" title="Status" onclick="showAjaxModal(\''.base_url('leave/popup/leave_status/'.$value['group_id']).'\',\'Leave Status\')"><i class="fa fa-eye"></i></button>';

            }

        }
        echo json_encode($leave);

    }

    public function leave_application_list()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        if (!is_in_headlist()) {
            $this->session->set_flashdata('error', 'Permission denied!');
            redirect('dashboard', 'refresh');
        }

        $page_data['page_type']    = 'leave';
        $page_data['menu']         = 'leave';
        $page_data['page_name']    = 'leave_application_list';
        $page_data['page_title']   = 'Leave Application List';
        $this->load->view('theme/user/main', $page_data);
    }

    public function leave_application_list_ajax()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $employee_id = $this->session->userdata('employee_id');
        $status = $this->input->post('status');
        //pr($status);
        $all = $this->leave->leave_application_list($employee_id, $status);
        //pr($all);
        $leave = [];
        if(!empty($all)) {

            foreach($all as $key => $value) {

                $dates = explode(",", $value['from_to']);
                usort($dates, function ($a, $b) {
                    return strtotime($a) - strtotime($b);
                });

                $leave[$key]['name'] = '<a href="'.base_url().'profile/'.$value['code'].'" title="View profile" target="_blank"><h6 class="mb-0">'.$value['name'].'</h6><span>'.$value['code'].'</span></a>';

                $leave[$key]['designation'] = '<div><strong>'.$value['designation'].'</strong></div><span>'.$value['department'].'</span>';
                $leave[$key]['created_at'] = get_date($value['created_at']);
                $leave[$key]['leave_type'] = '<span class="badge badge-danger">'.$value['title'].'</span>';
                $leave[$key]['is_halfday'] = is_halfday($value['is_halfday']);
                $leave[$key]['fn_an'] = $value['fn_an'] ?? '-';
                $leave[$key]['date'] = '<strong>'.get_date($dates[0]).'</strong> to <strong>'.get_date($dates[count($dates) - 1]).'</strong>';
                $leave[$key]['leave_count'] = 'Active: <strong>'.$value['active_count'].'</strong> <br>Cancelled: <strong>'.$value['cancelled_count'].'</strong>';
                $leave[$key]['totdays'] = trimDecimal($value['total_count']);
                $leave[$key]['leave_reason'] = ($value['leave_reason'] != "") ? (nl2br($value['leave_reason'])) : '-';
                $leave[$key]['remarks'] = ($value['remarks'] != "") ? (nl2br($value['remarks'])) : '-';
                $leave[$key]['status'] = leave_status_c($value['status']);
                $leave[$key]['status_changed'] = get_date($value['status_changed']);
                $leave[$key]['action'] = ($value['status'] == 'pending') ? '<button type="button" class="btn btn-sm btn-outline-success" title="Approve" onclick="showAjaxModal(\''.base_url('leave/popup/approve_leave/'.$value['group_id']).'\',\'Approve Leave\')"><i class="fa fa-check"></i></button>&nbsp;<button type="button" class="btn btn-sm btn-outline-danger" title="Reject" onclick="showAjaxModal(\''.base_url('leave/popup/reject_leave/'.$value['group_id']).'\',\'Reject Leave\')"><i class="fa fa-close"></i></button>' : '<button type="button" class="btn btn-sm btn-outline-success" title="Status" onclick="showAjaxModal(\''.base_url('leave/popup/leave_status/'.$value['group_id']).'\',\'Leave Status\')"><i class="fa fa-eye"></i></button>';

            }

        }
        echo json_encode($leave);

    }

    public function approve_leave_process()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
        $this->form_validation->set_rules('leave_application_status_id', 'Application status id', 'trim|required');
        $this->form_validation->set_rules('group_id', 'Group id', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');

        if($this->form_validation->run()) {

            $data = $this->input->post(null, true);

            $leave = $this->leave->leave_by_id($data['group_id']);

            if(!empty($leave)) {

                //check leave available before approving
                $active_leave_count = (!empty($leave['active_leaves'])) ? trimDecimal($leave['total_count']) : 0;
                //echo $active_leave_count;
                //pr($leave);exit;

                if(empty($active_leave_count)) {
                    $response = array('status' => 0, 'msg' => 'No active leave to approve!');
                    echo json_encode($response);
                    exit;
                }

                //get month base leave availability
                $availability = $this->leave->checkLeaveAvailability($leave['employee_id']);

                // pr($availability);
                //pr($leave);

                if(empty($availability)) {
                    $response = array('status' => 0, 'msg' => 'No month based leave available!');
                    echo json_encode($response);
                    exit;
                }

                if($leave['leave_type_id'] == 1) {
                    if($active_leave_count > $availability['pl_balance']) {
                        $response = array('status' => 0, 'msg' => 'Cannot approve! '.$availability['pl_balance'].' day available!');
                        echo json_encode($response);
                        exit;
                    }
                } elseif ($leave['leave_type_id'] == 3) {
                    if($active_leave_count > $availability['sl_balance']) {
                        $response = array('status' => 0, 'msg' => 'Cannot approve! '.$availability['sl_balance'].' day available!');
                        echo json_encode($response);
                        exit;
                    }
                } elseif ($leave['leave_type_id'] == 4) {
                    if($active_leave_count > $availability['co']) {
                        $response = array('status' => 0, 'msg' => 'Cannot approve! '.$availability['co'].' day available!');
                        echo json_encode($response);
                        exit;
                    }
                }

                //recalculate leave availability
                $update_param = array();
                if($leave['leave_type_id'] == 1) {

                    $update_param['pl_used'] = ($availability['pl_used'] + trimDecimal($leave['total_count']));
                    $update_param['pl_balance'] = ($availability['pl_balance'] - trimDecimal($leave['total_count']));

                } elseif ($leave['leave_type_id'] == 3) {

                    $update_param['sl_used'] = ($availability['sl_used'] + trimDecimal($leave['total_count']));
                    $update_param['sl_balance'] = ($availability['sl_balance'] - trimDecimal($leave['total_count']));

                } elseif ($leave['leave_type_id'] == 4) {

                    $update_param['co'] = ($availability['co'] - trimDecimal($leave['total_count']));

                }

                $session_id = $this->session->userdata('employee_id');

                $get_leave_type  = $this->common_model->selectOne('leave_types', array('leave_type_id' => $leave['leave_type_id']), 'type');

                //check top head assigned if leave type is hierarchy
                if($get_leave_type['type'] == 'hierarchy') {
                    $reporting_heads = $this->leave->rm_assigned_check($leave['employee_id']);
                    if(empty($reporting_heads['head_2'])) {
                        $response = array('status' => 0, 'msg' => 'RM of RM not assigned.');
                        echo json_encode($response);
                        exit;
                    }
                }

                //current loggined user status
                $res = $this->leave->leave_status_by_head($session_id, $leave['group_id']);
                if(empty($res)) {
                    $response = array('status' => 0, 'msg' => 'Permission denied!');
                    echo json_encode($response);
                    exit;
                }
                //pr($res);

                if($res['status'] == 'pending') {
                    //save status
                    $param = array(
                        'status' => 'approved',
                        'status_changed' => date('Y-m-d'),
                        'remarks' => ($data['remarks'] != "") ? $data['remarks'] : null,
                        'updated_at' => date('Y-m-d H:i:s')
                    );

                    $ustatus = $this->common_model->update($param, array('leave_application_status_id' => $res['leave_application_status_id'],'head_id' => $res['head_id'],'position' => $res['position']), 'leave_application_status');
                    if($ustatus) {

                        //check whether its top level approval or its direct approval type leave
                        if($res['position'] == 2 || $get_leave_type['type'] == 'direct') {

                            $param2 = array(
                             'status' => 'approved',
                             'status_changed' => date('Y-m-d')
                            );

                            $mleaves = $this->common_model->update($param2, array('group_id' => $leave['group_id']), 'leave_application');

                            if(!empty($mleaves)) {

                                //update leave avilable tables once leave approved
                                if(!empty($update_param)) {
                                    $this->common_model->update($update_param, array('leave_details_id' => $availability['leave_details_id'],'employee_id' => $availability['employee_id']), 'employee_leave_details');
                                }

                                @leaveApprovedEmail($availability['employee_id'],$res['head_id'], $leave['group_id']);
                                //sending emails code need to integrate

                                $response = array('status' => 1, 'msg' => 'Approved successfully!');
                                echo json_encode($response);
                                exit;

                            } else {
                                $response = array('status' => 0, 'msg' => 'Something went wrong!');
                                echo json_encode($response);
                                exit;
                            }

                        } else {
                            //pass to next head approval

                            //$next_head = $this->leave->get_next_head($leave['employee_id'], $res['position']);
                            $reporting_heads = $this->leave->rm_assigned_check($leave['employee_id']);

                            $param1 = array(
                                'group_id' => $leave['group_id'],
                                'status' => 'pending',
                                'status_changed' => date('Y-m-d'),
                                'head_id' => $reporting_heads['head_2'],
                                'position' => '2',
                                'created_at' => date('Y-m-d H:i:s')
                            );

                            $leave_application_status_id = $this->common_model->insert($param1, 'leave_application_status');

                            if(!empty($leave_application_status_id)) {

                                //send to another top head approval position
                                @leaveApplicationEmail($leave['employee_id'], $reporting_heads['head_2'], $leave['group_id']);

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
                    $response = array('status' => 0, 'msg' => 'Already approved!');
                    echo json_encode($response);
                    exit;
                }

            } else {
                $response = array('status' => 0, 'msg' => 'Leave details not found!');
                echo json_encode($response);
                exit;
            }

        } else {
            $response = array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
            exit;
        }
    }

    public function reject_leave_process()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $this->form_validation->set_rules('leave_application_status_id', 'Application status id', 'trim|required');
        $this->form_validation->set_rules('group_id', 'Group id', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');

        if($this->form_validation->run()) {
            $data = $this->input->post(null, true);

            $leave = $this->leave->leave_by_id($data['group_id']);

            if(!empty($leave)) {

                $session_id = $this->session->userdata('employee_id');

                //current loggined user status
                $res = $this->leave->leave_status_by_head($session_id, $leave['group_id']);

                if(empty($res)) {
                    $response = array('status' => 0, 'msg' => 'Permission denied!');
                    echo json_encode($response);
                    exit;
                }

                if($res['status'] == 'pending') {
                    //save status
                    $param = array(
                        'status' => 'rejected',
                        'status_changed' => date('Y-m-d'),
                        'remarks' => ($data['remarks'] != "") ? $data['remarks'] : null,
                        'updated_at' => date('Y-m-d H:i:s')
                    );

                    $ustatus = $this->common_model->update($param, array('leave_application_status_id' => $res['leave_application_status_id'],'head_id' => $res['head_id'],'position' => $res['position']), 'leave_application_status');
                    if($ustatus) {

                        //change main leave status to rejected
                        $param2 = array(
                         'status' => 'rejected',
                         'status_changed' => date('Y-m-d')
                        );

                        $mleaves = $this->common_model->update($param2, array('group_id' => $leave['group_id']), 'leave_application');

                        if(!empty($mleaves)) {

                            //email send to employee when leave rejected
                            @leaveRejectedEmail($leave['employee_id'],$res['head_id'], $leave['group_id']);

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

    public function leave_cancel_process()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $this->form_validation->set_rules('leave_application_id', 'Application status id', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');

        if($this->form_validation->run()) {

            $data = $this->input->post(null, true);
            $session_id = $this->session->userdata('employee_id');

            $leave_exist  = $this->common_model->selectOne('leave_application', array('leave_application_id' => $data['leave_application_id'],'employee_id' => $session_id,'cancel_status' => '0'), '*');

            if(empty($leave_exist)) {
                $response = array('status' => 0, 'msg' => 'Leave details not found!');
                echo json_encode($response);
                exit;
            }

            //update leave cancel status

            $param2 = array(
             'cancel_status' => '1',
             'cancelled_on' => date('Y-m-d H:i:s'),
             'cancelled_by' => $session_id
            );

            $mleaves = $this->common_model->update($param2, array('leave_application_id' => $leave_exist['leave_application_id'],'employee_id' => $leave_exist['employee_id'],'cancel_status' => '0'), 'leave_application');

            if(!empty($mleaves)) {

                //reduce of increase leave by this value.
                $lcounter = ($leave_exist['is_halfday'] == 1) ? 0.5 : 1;

                //increase availability when leave cancelled
                if($leave_exist['status'] == 'approved') {
                    //get month base leave availability
                    $availability = $this->leave->checkLeaveAvailability($leave_exist['employee_id']);
                    $update_param = array();
                    //reduce count based on the availability
                    if($leave_exist['leave_type_id'] == 1) {
                        $update_param['pl_used'] = ($availability['pl_used'] - $lcounter);
                        $update_param['pl_balance'] = ($availability['pl_balance'] + $lcounter);
                    } elseif ($leave_exist['leave_type_id'] == 3) {
                        $update_param['sl_used'] = ($availability['sl_used'] - $lcounter);
                        $update_param['sl_balance'] = ($availability['sl_balance'] + $lcounter);
                    } elseif ($leave_exist['leave_type_id'] == 4) {
                        $update_param['co'] = ($availability['co'] + $lcounter);
                    }
                    //update the employee leave details with the leave counts
                    if(!empty($update_param)) {
                        $this->common_model->update($update_param, array('leave_details_id' => $availability['leave_details_id'],'employee_id' => $leave_exist['employee_id']), 'employee_leave_details');
                    }
                }

                //cancellation of holidays added as sandwich incase of the leave is part of sandwich. this function cancel both leaves left and right of the current cancelled leave.

                if($leave_exist['is_sandwich_applied']) {

                    $holidays_qry = "select holiday_date from holidays where holiday_date between '".date('Y')."-01-01' and '".date('Y')."-12-31'";
                    $holidays_resp = $this->db->query($holidays_qry)->result_array();
                    $ignore_dates = array();

                    if(!empty($holidays_resp)) {
                        $ignore_dates = array_map(function ($item) { return $item['holiday_date']; }, $holidays_resp);
                    }

                    //get leaves lessthan current cancelled date
                    $leave_less = "select leave_application_id,leave_date,status,leave_type_id from leave_application where employee_id = ? and cancel_status = '0' and group_id = '".$leave_exist['group_id']."' and leave_date < '".$leave_exist['leave_date']."' order by leave_date DESC";
                    $leave_less_resp = $this->db->query($leave_less, $leave_exist['employee_id'])->result_array();

                    if(!empty($leave_less_resp)) {
                        $isWorkingDay = false;
                        foreach($leave_less_resp as $row11) {

                            //pr($row11);
                            //loop through all leave and check wheter its workign day or not if working day, then quit loop else cancel it. because we don't want to cancel the working days
                            $dayOfWeek = date('N', strtotime($row11['leave_date']));
                            if($dayOfWeek < 7 &&  !in_array($row11['leave_date'], $ignore_dates)) {
                                break;
                            } else {
                                //echo 'holiday';
                                //cancel leave and check whether its approved, if approved and leave type is PL or SL increase leave count
                                $param4 = array(
                                    'cancel_status' => '1',
                                    'cancelled_on' => date('Y-m-d H:i:s'),
                                    'cancelled_by' => $session_id
                                );
                                $cleave = $this->common_model->update($param4, array('leave_application_id' => $row11['leave_application_id'],'employee_id' => $leave_exist['employee_id'],'cancel_status' => '0'), 'leave_application');

                                //update the leave availability only if its approved
                                if(!empty($cleave) && $row11['status'] == 'approved') {

                                    //get month base leave availability
                                    $availability = $this->leave->checkLeaveAvailability($leave_exist['employee_id']);

                                    $update_param = array();
                                    //reduce count based on the availability. no sandwich for half days so directly reduce count by 1
                                    if($row11['leave_type_id'] == 1) {
                                        $update_param['pl_used'] = ($availability['pl_used'] - 1);
                                        $update_param['pl_balance'] = ($availability['pl_balance'] + 1);
                                    } elseif ($row11['leave_type_id'] == 3) {
                                        $update_param['sl_used'] = ($availability['sl_used'] - 1);
                                        $update_param['sl_balance'] = ($availability['sl_balance'] + 1);
                                    } elseif ($row11['leave_type_id'] == 4) {
                                        $update_param['co'] = ($availability['co'] + 1);
                                    }
                                    //update the employee leave details with the leave counts
                                    if(!empty($update_param)) {
                                        //pr($update_param);
                                        $this->common_model->update($update_param, array('leave_details_id' => $availability['leave_details_id'],'employee_id' => $leave_exist['employee_id']), 'employee_leave_details');
                                    }
                                }
                            }

                        }
                    }
                    // *************************************************************************************************************
                    //get leaves greater than current cancelled date
                    $leave_great = "select leave_application_id,leave_date,status,leave_type_id from leave_application where employee_id = ? and cancel_status = '0' and  group_id = '".$leave_exist['group_id']."' and  leave_date > '".$leave_exist['leave_date']."' order by leave_date ASC";
                    $leave_great_resp = $this->db->query($leave_great, $leave_exist['employee_id'])->result_array();

                    if(!empty($leave_great_resp)) {
                        $isWorkingDay = false;
                        foreach($leave_great_resp as $row11) {

                            //pr($row11);
                            //loop through all leave and check wheter its workign day or not if working day, then quit loop else cancel it
                            $dayOfWeek = date('N', strtotime($row11['leave_date']));
                            if($dayOfWeek < 7 &&  !in_array($row11['leave_date'], $ignore_dates)) {
                                break;
                            } else {
                                //echo 'holiday';
                                //cancel leave and check whether its approved, if approved and leave type is PL or SL increase leave count
                                $param4 = array(
                                    'cancel_status' => '1',
                                    'cancelled_on' => date('Y-m-d H:i:s'),
                                    'cancelled_by' => $session_id
                                );
                                $cleave = $this->common_model->update($param4, array('leave_application_id' => $row11['leave_application_id'],'employee_id' => $leave_exist['employee_id'],'cancel_status' => '0'), 'leave_application');

                                //update the leave availability only if its approved
                                if(!empty($cleave) && $row11['status'] == 'approved') {

                                    //get month base leave availability
                                    $availability = $this->leave->checkLeaveAvailability($leave_exist['employee_id']);

                                    $update_param = array();
                                    //reduce count based on the availability. no sandwich for half days so directly reduce count by 1
                                    if($row11['leave_type_id'] == 1) {
                                        $update_param['pl_used'] = ($availability['pl_used'] - 1);
                                        $update_param['pl_balance'] = ($availability['pl_balance'] + 1);
                                    } elseif ($row11['leave_type_id'] == 3) {
                                        $update_param['sl_used'] = ($availability['sl_used'] - 1);
                                        $update_param['sl_balance'] = ($availability['sl_balance'] + 1);
                                    } elseif ($row11['leave_type_id'] == 4) {
                                        $update_param['co'] = ($availability['co'] + 1);
                                    }
                                    //update the employee leave details with the leave counts
                                    if(!empty($update_param)) {
                                        //pr($update_param);
                                        $this->common_model->update($update_param, array('leave_details_id' => $availability['leave_details_id'],'employee_id' => $leave_exist['employee_id']), 'employee_leave_details');
                                    }
                                }
                            }

                        }
                    }
                }

                //send leave cancelled status to all heads
                @leaveCancelEmail($leave_exist['employee_id'], $leave_exist['group_id']);

                $response = array('status' => 1, 'msg' => 'Cancelled successfully!');
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

    public function holiday_calendar()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        if (!is_admin()) {
            $this->session->set_flashdata('error', 'Permission denied!');
            redirect('dashboard', 'refresh');
        }

        $page_data['page_type']    = 'leave';
        $page_data['page_name']    = 'holiday_calendar';
        $page_data['menu']         = 'leave';
        $page_data['page_title']   = 'Holiday Calendar';

        $this->load->view('theme/user/main', $page_data);

    }

    public function get_holidays()
    {
        $holidays = $this->leave->get_holidays();
        echo json_encode($holidays);
        exit;
    }

    public function create_holiday_process()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('holiday_date', 'Holiday Date', 'trim|required');
        $this->form_validation->set_rules('color', 'Highlight', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
        if($this->form_validation->run()) {

            $data = $this->input->post(null, true);

            $param = array(
              'title' => $data['title'],
              'holiday_date' => set_date($data['holiday_date']),
              'color' => $data['color'],
              'created_by' => $this->session->userdata('employee_id'),
              'created_at' => date('Y-m-d H:i:s')
            );

            $resp = $this->common_model->insert($param, 'holidays');

            if(!empty($resp)) {
                $response = array('status' => 1, 'msg' => 'Holiday added successfully!');
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

    public function holiday_delete_process()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $this->form_validation->set_rules('id', 'Holiday', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
        if($this->form_validation->run()) {
            $data = $this->input->post(null, true);

            $resp = $this->leave->holiday_delete($data['id']);
            if(!empty($resp)) {
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

    public function edit_holiday_process()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $this->form_validation->set_rules('id', 'Holiday', 'trim|required');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('holiday_date', 'Holiday Date', 'trim|required');
        $this->form_validation->set_rules('color', 'Highlight', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
        if($this->form_validation->run()) {

            $data = $this->input->post(null, true);

            $param = array(
              'title' => $data['title'],
              'holiday_date' => set_date($data['holiday_date']),
              'color' => $data['color'],
              'created_by' => $this->session->userdata('employee_id'),
              'created_at' => date('Y-m-d H:i:s')
            );

            $resp = $this->common_model->update($param, array('id' => $data['id']), 'holidays');

            if(!empty($resp)) {
                $response = array('status' => 1, 'msg' => 'Holiday updated successfully!');
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

    public function hr_leave_allocation()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        if (!is_admin()) {
            $this->session->set_flashdata('error', 'Permission denied!');
            redirect('dashboard', 'refresh');
        }

        $page_data['page_type']    = 'leave';
        $page_data['menu']         = 'leave';
        $page_data['page_name']    = 'hr_leave_allocation';
        $page_data['page_title']   = 'Comp Off';

        $this->load->view('theme/user/main', $page_data);
    }

    public function hr_leave_allocation_process()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $this->form_validation->set_rules('employee_id', 'Employee id', 'trim|required');
        $this->form_validation->set_rules('from_date', 'From date', 'trim|required');
        $this->form_validation->set_rules('to_date', 'To date', 'trim|required');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
        if($this->form_validation->run()) {

            $data = $this->input->post(null, true);

            $availability = $this->leave->checkLeaveAvailability($data['employee_id']);

            if(empty($availability)) {
                $response = array('status' => 0, 'msg' => 'No month based leave available!');
                echo json_encode($response);
                exit;
            }

            $from_date  = set_date($data['from_date']);
            $to_date    = set_date($data['to_date']);

            $datetime1  = new DateTime(date('Y-m-d H:i:s', strtotime($from_date.' 00:00:00')));
            $datetime2  = new DateTime(date('Y-m-d H:i:s', strtotime($to_date.' 23:59:59')));

            $interval   = new DateInterval('P1D');
            $daterange  = new DatePeriod($datetime1, $interval, $datetime2);

            $totleave = 0;
            $attendance_history = array();
            foreach ($daterange as $key => $date) {

                $formatted_date = $date->format('Y-m-d');
                $expiry_date = date('Y-m-d', strtotime("+89 day", strtotime($formatted_date)));

                $param = array(
                    'employee_id' => $data['employee_id'],
                    'allotted_date' => $formatted_date,
                    'expiry_date' => $expiry_date,
                    'allotted_leave' => 1,
                    'remarks' => ($data['remarks'] != "") ? $data['remarks'] : null,
                    'created_by' => $this->session->userdata('employee_id'),
                    'created_at' => date('Y-m-d H:i:s')
                );

                //check attendance marked for the particular day
                $check_attendance =  $this->common_model->selectOne('attendance', array('employee_id' => $data['employee_id'],'attendance_date' => $formatted_date), '*');

                if(!empty($check_attendance)) {

                    $leave_stat = leave_status($check_attendance['duration']);
                    if($leave_stat == 'Present') {
                        //check already allotted leave
                        $check_allotted =  $this->common_model->selectOne('hr_leave_allocation_history', array('employee_id' => $data['employee_id'],'allotted_date' => $formatted_date), '*');

                        if(empty($check_allotted)) {
                            $allotted = $this->common_model->insert($param, 'hr_leave_allocation_history');
                            if(!empty($allotted)) {
                                $totleave++;
                                $attendance_history[$key]['date'] = get_date($formatted_date);
                                $attendance_history[$key]['leave'] = 'Allotted successfully!';
                            }
                        } else {
                            $attendance_history[$key]['date'] = get_date($formatted_date);
                            $attendance_history[$key]['leave'] = 'Already allotted!';
                        }
                    } else {
                        $attendance_history[$key]['date'] = get_date($formatted_date);
                        $attendance_history[$key]['leave'] = 'Half Day / Absent!';
                    }

                } else {
                    $attendance_history[$key]['date'] = get_date($formatted_date);
                    $attendance_history[$key]['leave'] = 'No attendance entry found for this date!';
                }

            }
            //update to leave availability table
            if(!empty($totleave)) {

                // $update_param['pl_hr_allotted'] = ($availability['pl_hr_allotted'] + $totleave);
                // $update_param['pl_total'] = ($availability['pl_total'] + $totleave);
                // $update_param['pl_balance'] = ($availability['pl_balance'] + $totleave);
                $update_param['co'] = ($availability['co'] + $totleave);

                if(!empty($update_param)) {
                    $this->common_model->update($update_param, array('leave_details_id' => $availability['leave_details_id'],'employee_id' => $availability['employee_id']), 'employee_leave_details');
                }

            }

            //leave allotted
            if($totleave > 0) {
                @leaveAlottedEmail($availability['employee_id']);
            }

            $response = array('status' => 1, 'msg' => 'Success!','total_leave' => $totleave,'attendance_history' => $attendance_history);
            echo json_encode($response);
            exit;

        } else {
            $response = array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
            exit;
        }

    }

    public function hr_leave_allocation_list()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        // if (!is_rm()) {
        // 	$this->session->set_flashdata('error', 'Permission denied!');
        // 	redirect('dashboard', 'refresh');
        // }

        // if(!check_permission('3', 'v')) {
        //     $this->session->set_flashdata('error', 'Permission denied!');
        //     redirect('dashboard', 'refresh');
        // }

        $page_data['page_type']    = 'leave';
        $page_data['menu']         = 'leave';
        $page_data['page_name']    = 'hr_leave_allocation_list';
        $page_data['page_title']   = 'Comp Off List';
        $this->load->view('theme/user/main', $page_data);
    }

    public function hr_leave_allocation_list_ajax()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $employee_id = $this->session->userdata('employee_id');

        $all = $this->leave->hr_leave_allocation_list($employee_id);
        // pr($all);
        $leave['data'] = [];
        if(!empty($all)) {

            foreach($all as $key => $value) {

                $leave['data'][$key]['name'] = '<a href="'.base_url().'profile/'.$value['code'].'" title="View profile" target="_blank"><h6 class="mb-0">'.$value['name'].'</h6><span>'.$value['code'].'</span></a>';
                $leave['data'][$key]['allotted_date'] = get_date($value['allotted_date']);
                $leave['data'][$key]['allotted_leave'] = $value['allotted_leave'];
                $leave['data'][$key]['remarks'] = ($value['remarks'] != "") ? (nl2br($value['remarks'])) : '-';
                $leave['data'][$key]['action'] = (is_admin()) ? '<button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteLeaveAllocation('.$value['id'].','.$value['employee_id'].')"><i class="fa fa-trash-o"></i></button>' : '';

            }

        }
        echo json_encode($leave);

    }

    public function deleteHrLeaveAllocation()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $this->form_validation->set_rules('id', 'Id', 'trim|required');
        $this->form_validation->set_rules('employee_id', 'Employee Id', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
        if($this->form_validation->run()) {

            $data = $this->input->post(null, true);

            $availability = $this->leave->checkLeaveAvailability($data['employee_id']);

            if(empty($availability)) {
                $response = array('status' => 0, 'msg' => 'No month based leave available!');
                echo json_encode($response);
                exit;
            }

            // pr($data);
            $check_exist =  $this->common_model->selectOne('hr_leave_allocation_history', array('employee_id' => $data['employee_id'],'id' => $data['id']), '*');

            if(!empty($check_exist)) {

                $resp = $this->leave->leave_allotment_delete($check_exist['id']);

                if(!empty($resp)) {

                    //re calculate employee leave eligibility
                    $update_param['pl_hr_allotted'] = ($availability['pl_hr_allotted'] - $check_exist['allotted_leave']);
                    $update_param['pl_total'] = ($availability['pl_total'] - $check_exist['allotted_leave']);
                    $update_param['pl_balance'] = ($availability['pl_balance'] - $check_exist['allotted_leave']);

                    if(!empty($update_param)) {

                        $respp = $this->common_model->update($update_param, array('leave_details_id' => $availability['leave_details_id'],'employee_id' => $check_exist['employee_id']), 'employee_leave_details');

                        if(!empty($respp)) {
                            $response = array('status' => 1, 'msg' => 'Deleted successfully!');
                            echo json_encode($response);
                            exit;
                        } else {
                            $response = array('status' => 0, 'msg' => 'Something went wrong!');
                            echo json_encode($response);
                            exit;
                        }
                    }

                } else {
                    $response = array('status' => 0, 'msg' => 'Something went wrong!');
                    echo json_encode($response);
                    exit;
                }

            } else {
                $response = array('status' => 0, 'msg' => 'Details not found!');
                echo json_encode($response);
                exit;
            }

            //pr($check_exist);

        } else {
            $response = array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
            exit;
        }

    }

    public function available_leaves()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
        if (!is_admin()) {
            $this->session->set_flashdata('error', 'Permission denied!');
            redirect('dashboard', 'refresh');
        }

        $page_data['page_type']    = 'leave';
        $page_data['page_name']    = 'available_leaves';
        $page_data['menu']         = 'leave';
        $page_data['page_title']   = 'Available Leaves';

        $this->load->view('theme/user/main', $page_data);

    }

    public function available_leaves_list_ajax()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $curDate = date('Y-m-d');
        $year = date('Y', strtotime($curDate));
        $month = date('m', strtotime($curDate));
        $newStartDate = date('Y-m-01', strtotime("$year-$month-01"));
        $newEndDate = date('Y-m-t', strtotime("$year-$month-01"));

        $all = $this->leave->available_leaves($newStartDate, $newEndDate);
        //pr($all);
        $leave_type['data'] = [];
        if(!empty($all)) {
            foreach($all as $key => $value) {

                $leave_type['data'][$key]['employee'] = '<strong>'.$value['name'].'</strong><br>'.$value['code'];
                $leave_type['data'][$key]['email'] = $value['email'];
                $leave_type['data'][$key]['pl_carry_fwd'] = $value['pl_carry_fwd'];
                $leave_type['data'][$key]['pl_total'] = $value['pl_total'];
                $leave_type['data'][$key]['pl_used'] = $value['pl_used'];
                $leave_type['data'][$key]['pl_balance'] = $value['pl_balance'];
                $leave_type['data'][$key]['sl_total'] = $value['sl'];
                $leave_type['data'][$key]['sl_used'] = $value['sl_used'];
                $leave_type['data'][$key]['sl_balance'] = $value['sl_balance'];
                $leave_type['data'][$key]['co'] = $value['co'];

            }
        }
        echo json_encode($leave_type);

    }

    //additional leave allocation by hr
    public function additional_leave()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        if (!is_admin()) {
            $this->session->set_flashdata('error', 'Permission denied!');
            redirect('dashboard', 'refresh');
        }

        $page_data['page_type']    = 'leave';
        $page_data['menu']         = 'leave';
        $page_data['page_name']    = 'additional_leave';
        $page_data['page_title']   = 'Additional Leave';
        $page_data['leave_types']  = $this->common_model->selectAll('leave_types', 'leave_type_id in (1,3)', '');

        $this->load->view('theme/user/main', $page_data);
    }

    public function additional_leave_process()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $this->form_validation->set_rules('employee_id', 'Employee id', 'trim|required');
        $this->form_validation->set_rules('allotted_leave', 'Count', 'trim|required');
        $this->form_validation->set_rules('leave_type_id', 'Leave type', 'trim|required');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
        if($this->form_validation->run()) {

            $data = $this->input->post(null, true);

            $availability = $this->leave->checkLeaveAvailability($data['employee_id']);

            if(empty($availability)) {
                $response = array('status' => 0, 'msg' => 'No month based leave available!');
                echo json_encode($response);
                exit;
            }

            $formatted_date = date('Y-m-d');
            $param = array(
                'employee_id' => $data['employee_id'],
                'allotted_date' => $formatted_date,
                'allotted_leave' => $data['allotted_leave'],
                'leave_type_id' => $data['leave_type_id'],
                'remarks' => ($data['remarks'] != "") ? $data['remarks'] : null,
                'created_by' => $this->session->userdata('employee_id'),
                'created_at' => date('Y-m-d H:i:s')
            );

            if(!empty($param)) {

                $allotted = $this->common_model->insert($param, 'additional_leave_history');

                if(!empty($allotted)) {

                    //this count is used for calculating PL and SL. if there is additioonal leave, no pl or sl added unless its compansated
                    if($data['leave_type_id'] == 1) {

                        $update_param['pl_hr_allotted'] = ($availability['pl_hr_allotted'] + $data['allotted_leave']);
                        $update_param['pl_total'] = ($availability['pl_total'] + $data['allotted_leave']);
                        $update_param['pl_balance'] = ($availability['pl_balance'] + $data['allotted_leave']);
                        $update_param['pl_additional_leave_counter'] = ($availability['pl_additional_leave_counter'] + $data['allotted_leave']);
                    }
                    if($data['leave_type_id'] == 3) {

                        $update_param['sl_hr_allotted'] = ($availability['sl_hr_allotted'] + $data['allotted_leave']);
                        $update_param['sl_balance'] = ($availability['sl_balance'] + $data['allotted_leave']);
                        $update_param['sl_additional_leave_counter'] = ($availability['sl_additional_leave_counter'] + $data['allotted_leave']);

                    }

                    if(!empty($update_param)) {
                        $resp = $this->common_model->update($update_param, array('leave_details_id' => $availability['leave_details_id'],'employee_id' => $availability['employee_id']), 'employee_leave_details');

                        if(!empty($resp)) {
                            //send email to employee
                            @leaveAlottedEmail($availability['employee_id']);

                            $response = array('status' => 1, 'msg' => 'Success!','total_leave' => $data['allotted_leave']);
                            echo json_encode($response);
                            exit;

                        } else {
                            $response = array('status' => 0, 'msg' => 'Updating to leave eligibility failed!');
                            echo json_encode($response);
                            exit;
                        }
                    }
                }

            } else {
                $response = array('status' => 0, 'msg' => 'Data not found!');
                echo json_encode($response);
                exit;
            }

        } else {
            $response = array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
            exit;
        }

    }

    public function additional_leave_list()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        if (!is_admin()) {
            $this->session->set_flashdata('error', 'Permission denied!');
            redirect('dashboard', 'refresh');
        }

        $page_data['page_type']    = 'leave';
        $page_data['menu']         = 'leave';
        $page_data['page_name']    = 'additional_leave_list';
        $page_data['page_title']   = 'Additional Leave List';
        $this->load->view('theme/user/main', $page_data);
    }

    public function additional_leave_list_ajax()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $employee_id = $this->session->userdata('employee_id');

        $all = $this->leave->additional_leave_list($employee_id);
        // pr($all);
        $leave['data'] = [];
        if(!empty($all)) {

            foreach($all as $key => $value) {

                $leave['data'][$key]['name'] = '<a href="'.base_url().'profile/'.$value['code'].'" title="View profile" target="_blank"><h6 class="mb-0">'.$value['name'].'</h6><span>'.$value['code'].'</span></a>';
                $leave['data'][$key]['allotted_date'] = get_date($value['allotted_date']);
                $leave['data'][$key]['allotted_leave'] = $value['allotted_leave'];
                $leave['data'][$key]['leave_type'] =  '<span class="badge badge-danger">'.$value['leave_type'].'</span>';
                $leave['data'][$key]['remarks'] = ($value['remarks'] != "") ? (nl2br($value['remarks'])) : '-';
                $leave['data'][$key]['action'] = (is_admin()) ? '<button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteLeaveAllocation('.$value['id'].','.$value['employee_id'].')"><i class="fa fa-trash-o"></i></button>' : '';

            }

        }
        echo json_encode($leave);

    }

    public function deleteAdditionalLeave()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $this->form_validation->set_rules('id', 'Id', 'trim|required');
        $this->form_validation->set_rules('employee_id', 'Employee Id', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
        if($this->form_validation->run()) {

            $data = $this->input->post(null, true);

            $availability = $this->leave->checkLeaveAvailability($data['employee_id']);

            if(empty($availability)) {
                $response = array('status' => 0, 'msg' => 'No month based leave available!');
                echo json_encode($response);
                exit;
            }

            // pr($data);
            $check_exist =  $this->common_model->selectOne('additional_leave_history', array('employee_id' => $data['employee_id'],'id' => $data['id']), '*');

            if(!empty($check_exist)) {

                $resp = $this->leave->additional_leave_delete($check_exist['id']);

                if(!empty($resp)) {

                    //re calculate employee leave eligibility
                    if($check_exist['leave_type_id'] == 1) {

                        $update_param['pl_hr_allotted'] = ($availability['pl_hr_allotted'] - $check_exist['allotted_leave']);
                        $update_param['pl_total'] = ($availability['pl_total'] - $check_exist['allotted_leave']);
                        $update_param['pl_balance'] = ($availability['pl_balance'] - $check_exist['allotted_leave']);
                        $update_param['pl_additional_leave_counter'] = ($availability['pl_additional_leave_counter'] - $check_exist['allotted_leave']);

                    } elseif($check_exist['leave_type_id'] == 3) {

                        $update_param['sl_hr_allotted'] = ($availability['sl_hr_allotted'] - $check_exist['allotted_leave']);
                        $update_param['sl_balance'] = ($availability['sl_balance'] - $check_exist['allotted_leave']);
                        $update_param['sl_additional_leave_counter'] = ($availability['sl_additional_leave_counter'] - $check_exist['allotted_leave']);

                    }

                    if(!empty($update_param)) {

                        $respp = $this->common_model->update($update_param, array('leave_details_id' => $availability['leave_details_id'],'employee_id' => $check_exist['employee_id']), 'employee_leave_details');

                        if(!empty($respp)) {
                            $response = array('status' => 1, 'msg' => 'Deleted successfully!');
                            echo json_encode($response);
                            exit;
                        } else {
                            $response = array('status' => 0, 'msg' => 'Something went wrong!');
                            echo json_encode($response);
                            exit;
                        }
                    }

                } else {
                    $response = array('status' => 0, 'msg' => 'Something went wrong!');
                    echo json_encode($response);
                    exit;
                }

            } else {
                $response = array('status' => 0, 'msg' => 'Details not found!');
                echo json_encode($response);
                exit;
            }

            //pr($check_exist);

        } else {
            $response = array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
            exit;
        }

    }

    public function leave_report()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        if (!is_in_headlist()) {
            $this->session->set_flashdata('error', 'Permission denied!');
            redirect('dashboard', 'refresh');
        }

        $page_data['page_type']    = 'leave';
        $page_data['menu']         = 'leave';
        $page_data['page_name']    = 'leave_report';
        $page_data['page_title']   = 'Leave Report';
        $this->load->view('theme/user/main', $page_data);
    }

    public function leave_report_list_ajax()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $employee_id = $this->input->post('employee_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        //pr($status);
        $all = $this->leave->leave_report_list($employee_id, $from_date, $to_date);

        $output = [];
        $columns = ['Employee Name'];

        if(!empty($all)) {
            foreach ($all as $employee) {
                $row = ['employee_name' => $employee['name']];
                foreach ($employee['leave_history'] as $leave) {
                    $leave_type = strtolower($leave['leave_type']);
                    if (!in_array($leave_type, $columns)) {
                        $columns[] = $leave_type;
                    }
                    $row[$leave_type] = trimDecimal($leave['total_leaves'])>0?'<span class="text-danger"><strong>'.trimDecimal($leave['total_leaves']).'</strong></span>':'-';
                }
                $output[] = $row;
            }

            $columnDefs = array_map(function ($col) {
                return ['data' => strtolower(str_replace(' ', '_', $col)), 'title' => strtoupper($col)];
            }, $columns);
        }

        echo json_encode([
            'columns' => $columnDefs,
            'data' => $output
        ]);

    }

    public function leave_report_detailed()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        if (!is_in_headlist()) {
            $this->session->set_flashdata('error', 'Permission denied!');
            redirect('dashboard', 'refresh');
        }

        $page_data['page_type']    = 'leave';
        $page_data['menu']         = 'leave';
        $page_data['page_name']    = 'leave_report_detailed';
        $page_data['page_title']   = 'Leave History';
        $this->load->view('theme/user/main', $page_data);
    }

    public function leave_report_detailed_list_ajax()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $employee_id = $this->input->post('employee_id');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $status = $this->input->post('status');
        //pr($status);
        $all = $this->leave->leave_report_detailed_list($employee_id, $from_date, $to_date,$status);

        $assi = [];
        if(!empty($all)) {
            foreach($all as $key => $value) {

                $assi[$key]['name'] = '<a href="'.base_url().'profile/'.$value['code'].'" title="View profile" target="_blank"><h6 class="mb-0">'.$value['name'].'</h6></a>';
                $assi[$key]['code'] = $value['code'];

                $assi[$key]['leave_date']  = get_date($value['leave_date']);
                $assi[$key]['leave_type'] = '<span class="badge badge-danger">'.$value['title'].'</span>';
                $assi[$key]['day_type']  = is_halfday($value['is_halfday']);
                $assi[$key]['fn_an']  = $value['fn_an']??'-';
                $assi[$key]['status']  = leave_status_c($value['status']);
                $assi[$key]['remarks']  = ($value['remarks'] != "") ? (nl2br($value['remarks'])) : '-';
                
            }
        }
        echo json_encode($assi);

    }

    public function getAvailability()
    {

        if(!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $employee_id = $this->input->post('employee_id');

        if(!empty($employee_id)) {

            //$eligibility = $this->leave->checkLeaveAvailability($employee_id);
            $eligibility = $this->leave->checkCanApplyOrNot($employee_id);

            if(!empty($eligibility)) {
                $response = array('status' => 1, 'msg' => 'Success!', 'result' => $eligibility);
                echo json_encode($response);
                exit;
            } else {
                $response = array('status' => 0, 'msg' => 'Eligibility data not found!');
                echo json_encode($response);
                exit;
            }

        } else {
            $response = array('status' => 0, 'msg' => 'Employee details not found!');
            echo json_encode($response);
            exit;
        }

    }

    public function test()
    {
        //leaveApplicationEmail(2,5,'8b24e22c-a163-40f4-a129-26fc86482ebc');
        //leaveApprovedEmail(4,'gggg');
        //leaveRejectedEmail(4,'fsfsf');
        //leaveCancelEmail(12,'dgd');
        //leaveAlottedEmail(4);
    }

    public function add_special_leave_approval_head_post()
    {

        if (!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $this->form_validation->set_rules('head_id', 'Head', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
        if ($this->form_validation->run()) {
            $data = $this->input->post(null, true);
            $exist =  $this->common_model->selectOne('additional_leave_approve_head', array(), '*');

            if (!empty($exist)) {
                $update['head_id'] = $data['head_id'];
                $resp = $this->common_model->update($update, array('id' => $exist['id']), 'additional_leave_approve_head');
                if (!empty($resp)) {
                    $response = array('status' => 1, 'msg' => 'Updated successfully!');
                } else {
                    $response = array('status' => '0', 'msg' => 'No changes were made to the data!');
                }
            } else {
                $insert['head_id'] = $data['head_id'];
                $resp = $this->common_model->insert($insert, 'additional_leave_approve_head');
                if (!empty($resp)) {
                    $response = array('status' => 1, 'msg' => 'Added successfully!');
                } else {
                    $response = array('status' => 0, 'msg' => 'Something went wrong!');
                }
            }
        } else {
            $response = array('status' => 0, 'msg' => validation_errors());
        }
        echo json_encode($response);
        exit;
    }

    public function leave_manage_admin()
    {

        if (!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $session = $this->session->userdata();

        if ($session['code'] != 'K005') {
            $this->session->set_flashdata('error', 'Permission denied!');
            redirect('dashboard', 'refresh');
        }

        $page_data['page_type']    = 'leave';
        $page_data['menu']         = 'leave_management';
        $page_data['page_name']    = 'leave_manage_list_admin';
        $page_data['page_title']   = 'Leave Management';
        $this->load->view('theme/user/main', $page_data);
    }

    public function leave_manage_admin_list_ajax()
    {

        if (!check_user_login()) {
            redirect('signin', 'refresh');
        }

        $all = $this->leave->leave_application_list_admin();

        $leave = [];
        if (!empty($all)) {

            foreach ($all as $key => $value) {

                $dates = explode(",", $value['from_to']);
                usort($dates, function ($a, $b) {
                    return strtotime($a) - strtotime($b);
                });

                $leave[$key]['name'] = '<a href="' . base_url() . 'employee/profile/' . $value['code'] . '" title="View profile" target="_blank"><h6 class="mb-0">' . $value['name'] . '</h6><span>' . $value['code'] . '</span></a>';

                $leave[$key]['created_at'] = get_date($value['created_at']);
                $leave[$key]['leave_type'] = '<span class="badge badge-danger">' . $value['title'] . '</span>';
                $leave[$key]['is_halfday'] = is_halfday($value['is_halfday']);
                $leave[$key]['fn_an'] = $value['fn_an'] ?? '-';
                $leave[$key]['date'] = '<strong>' . get_date($dates[0]) . '</strong> to <strong>' . get_date($dates[count($dates) - 1]) . '</strong>';
                $leave[$key]['leave_count'] = 'Active: <strong>' . $value['active_count'] . '</strong> <br>Cancelled: <strong>' . $value['cancelled_count'] . '</strong>';

                $leave[$key]['remarks'] = ($value['remarks'] != "") ? (nl2br($value['remarks'])) : '-';
                $leave[$key]['status'] = leave_status_c($value['status']);
                           
                $leaveStatusButton = '<button type="button" class="btn btn-sm btn-outline-success" title="Status" onclick="showAjaxModal(\'' . base_url('leave/popup/leave_cancel_admin/' . $value['group_id']) . '\',\'Leave Cancel\')">Action</button>';
                $leave[$key]['action'] = $leaveStatusButton;
            }
        }
        echo json_encode($leave);
    }

    public function leave_cancel_admin_process()
    {

        if (!check_user_login()) {
            redirect('signin', 'refresh');            
        }

        $session = $this->session->userdata();
        if ($session['code'] != 'K005') {
            $this->session->set_flashdata('error', 'Permission denied!');
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('leave_application_id', 'Application status id', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');

        if ($this->form_validation->run()) {

            $data = $this->input->post(null, true);
            $session_id = $this->session->userdata('employee_id');

            $leave_exist  = $this->common_model->selectOne('leave_application', array('leave_application_id' => $data['leave_application_id'], 'cancel_status' => '0'), '*');

            if (empty($leave_exist)) {
                $response = array('status' => 0, 'msg' => 'Leave details not found!');
                echo json_encode($response);
                exit;
            }

            //update leave cancel status

            $param2 = array(
                'cancel_status' => '1',
                'cancelled_on' => date('Y-m-d H:i:s'),
                'cancelled_by' => $session_id
            );

            $mleaves = $this->common_model->update($param2, array('leave_application_id' => $leave_exist['leave_application_id'], 'employee_id' => $leave_exist['employee_id'], 'cancel_status' => '0'), 'leave_application');

            if (!empty($mleaves)) {

                //reduce of increase leave by this value.
                $lcounter = ($leave_exist['is_halfday'] == 1) ? 0.5 : 1;

                //increase availability when leave cancelled
                if ($leave_exist['status'] == 'approved') {
                    //get month base leave availability
                    $availability = $this->leave->checkLeaveAvailability($leave_exist['employee_id']);

                    $update_param = array();
                    //reduce count based on the availability
                    if ($leave_exist['leave_type_id'] == 1) {
                        $update_param['pl_used'] = ($availability['pl_used'] - $lcounter);
                        $update_param['pl_balance'] = ($availability['pl_balance'] + $lcounter);
                    } elseif ($leave_exist['leave_type_id'] == 3) {
                        $update_param['sl_used'] = ($availability['sl_used'] - $lcounter);
                        $update_param['sl_balance'] = ($availability['sl_balance'] + $lcounter);
                    } elseif ($leave_exist['leave_type_id'] == 4) {
                        $update_param['co'] = ($availability['co'] + $lcounter);
                    }
                    //update the employee leave details with the leave counts
                    if (!empty($update_param)) {
                        $this->common_model->update($update_param, array('leave_details_id' => $availability['leave_details_id'], 'employee_id' => $leave_exist['employee_id']), 'employee_leave_details');
                    }
                }           

                $response = array('status' => 1, 'msg' => 'Cancelled successfully!');
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

}
