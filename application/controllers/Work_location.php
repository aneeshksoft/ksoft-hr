<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Work_location extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!check_user_login()) {
            redirect('signin', 'refresh');
        }
        $this->load->model('work_location_model');
    }

    public function index()
    {
        $this->view_work_locations();
    }

    public function view_work_locations()
    {
        $page_data['page_type']    = 'work_location';
        $page_data['menu']         = 'work_location';
        $page_data['page_name']    = 'view_work_locations';
        $page_data['page_title']   = 'Work Locations';
        $this->load->view('theme/user/main', $page_data);
    }

    public function view_work_locations_ajax()
    {
        $all = $this->work_location_model->get_work_locations('wl.*', [], array('order_by' => 'wl.work_location_id DESC'))->result_array();
        $data['data'] = [];
        // echo $this->db->last_query();exit;
        // pr($all);
        // exit;
        if (!empty($all)) {

            foreach ($all as $key => $value) {
                $data['data'][$key]['sno'] 					= $key + 1;
                $data['data'][$key]['name']					= $value['name'];
                $editBtn = '<button class="btn btn-sm btn-outline-secondary" type="button" onclick="showAjaxModal(\'' . base_url('work_location/edit_work_location/' . $value['work_location_id']) . '\',\'Edit Work Location\',\'modal-md\')"><i class="fa fa-pencil-square-o"></i></button>&nbsp;';
                $deleteBtn = '<button type="button" class="btn btn-sm btn-outline-danger" data-toggle="tooltip" title="Delete" onclick="delete_work_location(\'' . $value['work_location_id'] . '\')"><i class="fa fa-trash"></i></button>';

                $data['data'][$key]['action']			= $editBtn . $deleteBtn;
            }
        }
        echo json_encode($data);
    }

    public function add_work_location_post()
    {
        if (isset($_POST['submit'])) {
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
            if ($this->form_validation->run()) {
                $data = $this->input->post(null, true);

                $insert['name'] 		= $data['name'] ?? null;

                //check exist
                $exist = $this->common_model->selectAll('work_locations', array('name' => $insert['name']), '*');

                if(!empty($exist)) {
                    $response = array('status' => '0', 'msg' => 'Already exist!');
                } else {
                    $id = $this->common_model->insert($insert, 'work_locations');
                    if ($id) {
                        $response = array('status' => '1', 'msg' => 'Data Saved!');
                    } else {
                        $response = array('status' => '0', 'msg' => 'Something Went Wrong!');
                    }
                }

            } else {
                // echo $this->db->last_query();exit;
                $response = array('status' => '0', 'msg' => validation_errors());
            }
            echo json_encode($response);
            exit;
        }
    }

    public function edit_work_location($id)
    {
        $page_data['work_location']	=	$this->work_location_model->get_work_locations('wl.*', array('wl.work_location_id' => $id))->row_array();
        if (empty($page_data['work_location'])) {
            exit;
        }
        $this->load->view('work_location/edit_work_location', $page_data);
    }

    public function edit_work_location_post()
    {
        if (isset($_POST['submit'])) {
            $this->form_validation->set_rules('id', 'ID', 'trim|required');
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
            if ($this->form_validation->run()) {
                $data = $this->input->post(null, true);
                $exist = $this->work_location_model->get_work_locations('wl.*', array('wl.work_location_id' => $data['id']))->row_array();
                if (!empty($exist)) {
                    // pr($data);
                    // exit;
                    $update['name'] 		= $data['name'] ?? null;

                    $already = $this->common_model->selectAll('work_locations', array('name' => $update['name'],'work_location_id != ' => $data['id']), '*');

                    if(!empty($already)) {
                        $response = array('status' => '0', 'msg' => 'Already exist!');
                    } else {
                        $resp = $this->common_model->update($update, array('work_location_id' => $exist['work_location_id']), 'work_locations');

                        if (!empty($resp)) {
                            $response = array('status' => '1', 'msg' => 'Data Updated!');
                        } else {
                            $response = array('status' => '0', 'msg' => 'No changes were made to the data!');
                        }
                    }

                } else {
                    $response = array('status' => '0', 'msg' => 'Record does not exist!');
                }
            } else {
                $response = array('status' => '0', 'msg' => validation_errors());
            }
            echo json_encode($response);
            exit;
        }
    }

    public function delete_work_location_post()
    {
        $this->form_validation->set_rules('id', 'ID', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');
        if ($this->form_validation->run()) {
            $data = $this->input->post(null, true);
            $exist = $this->work_location_model->get_work_locations('wl.*', array('wl.work_location_id' => $data['id']))->row_array();
            if (!empty($exist)) {

                $this->db->delete('work_locations', array('work_location_id' => $data['id']));
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
}
