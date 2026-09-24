<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

	function __construct() {
		parent:: __construct();
	}
	
	function department() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$page_data['page_type']    = 'admin';
		$page_data['page_name']    = 'department';
		$page_data['menu']         = 'admin';
	    $page_data['page_title']   = 'Add/Edit Department';
				
	    $this->load->view('theme/user/main', $page_data);
		
	}
	
	function department_list_ajax() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$all = $this->common_model->selectAll('departments','','');
		
		$department['data'] = [];
		if(!empty($all)) {
			foreach($all as $key=>$value) {
				
				$department['data'][$key]['name'] = $value['name'];				
				$department['data'][$key]['action'] = '<button type="button" class="btn btn-sm btn-outline-secondary" title="Edit" onclick="editDepartment(\''.$value['name'].'\',\''.$value['department_id'].'\')"><i class="fa fa-edit"></i></button>&nbsp;<button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteDepartment(\''.$value['department_id'].'\')"><i class="fa fa-trash-o"></i></button>';;
				
			}			
		}		
		echo json_encode($department);
		
	}
	
	function department_process() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$this->form_validation->set_rules('name', 'Department name', 'trim|required');	
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');		
		if($this->form_validation->run()) {			
			$name = $this->input->post('name');
			$department_id = $this->input->post('department_id');
			
			if(!empty($department_id)) {
				
				$resp = $this->common_model->update(array('name'=>$name),array('department_id'=>$department_id),'departments');
				if(!empty($resp)) {
				$response=array('status' => 1, 'msg' => 'Updated successfully!');
				echo json_encode($response);
				exit;
			  } else {
				$response=array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			  }
				
			} else {
				
				$resp = $this->common_model->insert(array('name'=>$name),'departments');
				if(!empty($resp)) {
				$response=array('status' => 1, 'msg' => 'Added successfully!');
				echo json_encode($response);
				exit;
			  } else {
				$response=array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			  }
			}
			
		} else {
			$response=array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
			exit;
		}
	}
	
	function delete_department_process() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$this->form_validation->set_rules('department_id', 'Department id', 'trim|required');		
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');		
		if($this->form_validation->run()) {
			
		  $data = $this->input->post(NULL,true);
		  
		  $resp = $this->common_model->delete_department($data['department_id']);
		  if(!empty($resp)) {
			$response=array('status' => 1, 'msg' => 'Deleted successfully!');
		    echo json_encode($response);
		    exit;
		  } else {
			$response=array('status' => 0, 'msg' => 'Something went wrong!');
		    echo json_encode($response);
		    exit;
		  }	  
		  
		} else {
			$response=array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
			exit;
		}
	}
	
	//check employee code exist
	function checkDepartmentExist() {
	   $name = $this->input->post('name');
       if(!empty($name) && trim($name) != "") {		 
           $exist = $this->common_model->selectAll('departments',array('name'=>$name),'department_id');		   
          if(!empty($exist)) {
              echo 'false';
          } else {
              echo 'true';
          }
       } 
	}
	
	
	function designation() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$page_data['page_type']    = 'admin';
		$page_data['page_name']    = 'designation';
		$page_data['menu']         = 'admin';
	    $page_data['page_title']   = 'Add/Edit Designation';
		
	    $this->load->view('theme/user/main', $page_data);
		
	}
	
	function designation_list_ajax() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$all = $this->common_model->selectAll('designations','','');
		
		$designation['data'] = [];
		if(!empty($all)) {
			foreach($all as $key=>$value) {
				
				$designation['data'][$key]['name'] = $value['name'];				
				$designation['data'][$key]['action'] = '<button type="button" class="btn btn-sm btn-outline-secondary" title="Edit" onclick="editDesignation(\''.$value['name'].'\',\''.$value['designation_id'].'\')"><i class="fa fa-edit" ></i></button>&nbsp;<button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteDesignation(\''.$value['designation_id'].'\')"><i class="fa fa-trash-o"></i></button>';;
				
			}			
		}		
		echo json_encode($designation);
		
	}
	
	function designation_process() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$this->form_validation->set_rules('name', 'Department name', 'trim|required');	
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');		
		if($this->form_validation->run()) {			
			$name = $this->input->post('name');
			$designation_id = $this->input->post('designation_id');
			
			if(!empty($designation_id)) {
				
				$resp = $this->common_model->update(array('name'=>$name),array('designation_id'=>$designation_id),'designations');
				if(!empty($resp)) {
				$response=array('status' => 1, 'msg' => 'Updated successfully!');
				echo json_encode($response);
				exit;
			  } else {
				$response=array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			  }
				
			} else {
				
				$resp = $this->common_model->insert(array('name'=>$name),'designations');
				if(!empty($resp)) {
				$response=array('status' => 1, 'msg' => 'Added successfully!');
				echo json_encode($response);
				exit;
			  } else {
				$response=array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			  }
			}
			
		} else {
			$response=array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
			exit;
		}
	}
	
	function delete_designation_process() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$this->form_validation->set_rules('designation_id', 'Department id', 'trim|required');		
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');		
		if($this->form_validation->run()) {
			
		  $data = $this->input->post(NULL,true);
		  
		  $resp = $this->common_model->delete_designation($data['designation_id']);
		  if(!empty($resp)) {
			$response=array('status' => 1, 'msg' => 'Deleted successfully!');
		    echo json_encode($response);
		    exit;
		  } else {
			$response=array('status' => 0, 'msg' => 'Something went wrong!');
		    echo json_encode($response);
		    exit;
		  }	  
		  
		} else {
			$response=array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
			exit;
		}
	}
	
	//check employee code exist
	function checkDesignationExist() {
	   $name = $this->input->post('name');
       if(!empty($name) && trim($name) != "") {		  
          $exist = $this->common_model->selectAll('designations',array('name'=>$name),'designation_id');		   
          if(!empty($exist)) {
              echo 'false';
          } else {
              echo 'true';
          }
       } 
	}
	
	
	function role() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$page_data['page_type']    = 'admin';
		$page_data['page_name']    = 'role';
		$page_data['menu']         = 'admin';
	    $page_data['page_title']   = 'Add/Edit Role';
				
	    $this->load->view('theme/user/main', $page_data);
		
	}
	
	function role_list_ajax() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$all = $this->common_model->selectAll('roles','','');
		
		$role['data'] = [];
		if(!empty($all)) {
			foreach($all as $key=>$value) {
				
				$role['data'][$key]['name'] = $value['name'];
				$role['data'][$key]['head_assignment'] = strtoupper($value['head_assignment']);
				$role['data'][$key]['action'] = '<button type="button" class="btn btn-sm btn-outline-secondary" title="Edit" onclick="editRole(\''.$value['name'].'\',\''.$value['head_assignment'].'\',\''.$value['role_id'].'\')"><i class="fa fa-edit" ></i></button>';
				//&nbsp;<button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteRole(\''.$value['role_id'].'\')"><i class="fa fa-trash-o"></i></button>
				
			}			
		}		
		echo json_encode($role);
		
	}
	
	function role_process() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$this->form_validation->set_rules('name', 'Role name', 'trim|required');
		$this->form_validation->set_rules('head_assignment', 'Head Assignment', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');		
		if($this->form_validation->run()) {
			
			$name = $this->input->post('name');
			$role_id = $this->input->post('role_id');
			$head_assignment = $this->input->post('head_assignment');
			
			if(!empty($role_id)) {
				
				$resp = $this->common_model->update(array('name'=>$name,'slug'=>slugify($name),'head_assignment'=>$head_assignment),array('role_id'=>$role_id),'roles');
				if(!empty($resp)) {
				$response=array('status' => 1, 'msg' => 'Updated successfully!');
				echo json_encode($response);
				exit;
			  } else {
				$response=array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			  }
				
			} else {
				
				$resp = $this->common_model->insert(array('name'=>$name,'slug'=>slugify($name),'head_assignment'=>$head_assignment),'roles');
				if(!empty($resp)) {
				$response=array('status' => 1, 'msg' => 'Added successfully!');
				echo json_encode($response);
				exit;
			  } else {
				$response=array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			  }
			}
			
		} else {
			$response=array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
			exit;
		}
	}
	
	//check employee code exist
	function checkRoleExist() {
	   $name = $this->input->post('name');
	   $role_id = $this->input->post('role_id');
       if(!empty($name) && trim($name) != "") {
		  if(!empty($role_id))
		  $exist = $this->common_model->selectAll('roles',array('name'=>$name,'role_id!='=>$role_id),'role_id');	
		  else
          $exist = $this->common_model->selectAll('roles',array('name'=>$name),'role_id');		   
          if(!empty($exist)) {
              echo 'false';
          } else {
              echo 'true';
          }
       } 
	}
	
	function delete_role_process() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$this->form_validation->set_rules('role_id', 'Role id', 'trim|required');		
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');		
		if($this->form_validation->run()) {
			
		  $data = $this->input->post(NULL,true);
		  
		  $resp = $this->common_model->delete_role($data['role_id']);
		  if(!empty($resp)) {
			$response=array('status' => 1, 'msg' => 'Deleted successfully!');
		    echo json_encode($response);
		    exit;
		  } else {
			$response=array('status' => 0, 'msg' => 'Something went wrong!');
		    echo json_encode($response);
		    exit;
		  }	  
		  
		} else {
			$response=array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
			exit;
		}
	}
	
	
	function leave_type() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$page_data['page_type']    = 'admin';
		$page_data['page_name']    = 'leave_type';
		$page_data['menu']         = 'admin';
	    $page_data['page_title']   = 'Add/Edit Leave Type';
		
	    $this->load->view('theme/user/main', $page_data);
		
	}
	
	function leave_type_list_ajax() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$all = $this->common_model->selectAll('leave_types','','');
		
		$leave_type['data'] = [];
		if(!empty($all)) {
			foreach($all as $key=>$value) {
				
				$leave_type['data'][$key]['title'] = $value['title'];
				$leave_type['data'][$key]['type'] = strtoupper($value['type']);
				$leave_type['data'][$key]['action'] = '<button type="button" class="btn btn-sm btn-outline-secondary" title="Edit" onclick="editLeaveType(\''.$value['title'].'\',\''.$value['type'].'\',\''.$value['leave_type_id'].'\')"><i class="fa fa-edit" ></i></button>&nbsp;<button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteLeaveType(\''.$value['leave_type_id'].'\')"><i class="fa fa-trash-o"></i></button>';
				
			}			
		}		
		echo json_encode($leave_type);
		
	}
	
	function leave_type_process() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$this->form_validation->set_rules('title', 'Leave type name', 'trim|required');
		$this->form_validation->set_rules('type', 'Leave type', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');		
		if($this->form_validation->run()) {
			
			$title = $this->input->post('title');
			$leave_type_id = $this->input->post('leave_type_id');
			$type = $this->input->post('type');
			
			if(!empty($leave_type_id)) {
				
				$resp = $this->common_model->update(array('title'=>$title,'type'=>$type),array('leave_type_id'=>$leave_type_id),'leave_types');
				if(!empty($resp)) {
				$response=array('status' => 1, 'msg' => 'Updated successfully!');
				echo json_encode($response);
				exit;
			  } else {
				$response=array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			  }
				
			} else {
				
				$resp = $this->common_model->insert(array('title'=>$title,'type'=>$type),'leave_types');
				if(!empty($resp)) {
				$response=array('status' => 1, 'msg' => 'Added successfully!');
				echo json_encode($response);
				exit;
			  } else {
				$response=array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			  }
			}
			
		} else {
			$response=array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
			exit;
		}
	}
	
	//check employee code exist
	function checkLeaveTypeExist() {
	   $title = $this->input->post('title');
	   $leave_type_id = $this->input->post('leave_type_id');
       if(!empty($title) && trim($title) != "") {
		  if(!empty($leave_type_id))
		  $exist = $this->common_model->selectAll('leave_types',array('title'=>$title,'leave_type_id!='=>$leave_type_id),'leave_type_id');	
		  else
          $exist = $this->common_model->selectAll('leave_types',array('title'=>$title),'leave_type_id');		   
          if(!empty($exist)) {
              echo 'false';
          } else {
              echo 'true';
          }
       } 
	}
	
	function delete_leave_type_process() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$this->form_validation->set_rules('leave_type_id', 'Role id', 'trim|required');		
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');		
		if($this->form_validation->run()) {
			
		  $data = $this->input->post(NULL,true);
		  
		  $resp = $this->common_model->delete_leave_type($data['leave_type_id']);
		  if(!empty($resp)) {
			$response=array('status' => 1, 'msg' => 'Deleted successfully!');
		    echo json_encode($response);
		    exit;
		  } else {
			$response=array('status' => 0, 'msg' => 'Something went wrong!');
		    echo json_encode($response);
		    exit;
		  }	  
		  
		} else {
			$response=array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
			exit;
		}
	}
	
	function leave_approval_order() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$page_data['page_type']    = 'admin';
		$page_data['page_name']    = 'leave_approval_order';
		$page_data['menu']         = 'admin';
	    $page_data['page_title']   = 'Leave Approval Order';
		$page_data['roles']  = $this->common_model->selectAll('roles',array('head_assignment'=>'yes'),'');
		$page_data['order']  = $this->common_model->leave_approval_order();
	    $this->load->view('theme/user/main', $page_data);
		
	}
	
	function arrange_leave_approval_order() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$data = $this->input->post('data');
		$data = json_decode($data,true);
		
		if(!empty($data)) {
			
			$count = 0;
			foreach($data as $key=>$value) {
		
				$resp = $this->common_model->update(array('position'=>($key+1)),array('role_id'=>$value['role_id'],'approval_order_id'=>$value['approval_order_id']),'leave_approval_order');
				if(!empty($resp)) {
				  $count++;
			    }				
			}
			
			if($count > 0) {
				$response=array('status' => 1, 'msg' => 'Approval order changed!');
		        echo json_encode($response);
		        exit;
			}		
			
		} else {
			$response=array('status' => 0, 'msg' => 'Something went wrong!');
		    echo json_encode($response);
		    exit;
		}
	}
	
	function leave_approval_order_process() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$this->form_validation->set_rules('role_id', 'Role id', 'trim|required');		
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');		
		if($this->form_validation->run()) {
			
		  $data = $this->input->post(NULL,true);
		  
		  $resp = $this->common_model->new_leave_approval_head($data['role_id']);
		  if(!empty($resp)) {
			$response=array('status' => 1, 'msg' => 'Added successfully!');
		    echo json_encode($response);
		    exit;
		  } else {
			$response=array('status' => 0, 'msg' => 'Something went wrong!');
		    echo json_encode($response);
		    exit;
		  }	  
		  
		} else {
			$response=array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
			exit;
		}
		
	}
	
	function delete_leave_approval_order_process() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$this->form_validation->set_rules('approval_order_id', 'Order id', 'trim|required');		
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');		
		if($this->form_validation->run()) {
			
		  $data = $this->input->post(NULL,true);
		  
		  $resp = $this->common_model->delete_leave_approval_order($data['approval_order_id']);
		  if(!empty($resp)) {
			$response=array('status' => 1, 'msg' => 'Deleted successfully!');
		    echo json_encode($response);
		    exit;
		  } else {
			$response=array('status' => 0, 'msg' => 'Something went wrong!');
		    echo json_encode($response);
		    exit;
		  }	  
		  
		} else {
			$response=array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
			exit;
		}
	}
	
	
	function imbursement_approval_order() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$page_data['page_type']    = 'admin';
		$page_data['page_name']    = 'imbursement_approval_order';
		$page_data['menu']         = 'admin';
	    $page_data['page_title']   = 'Imbursement Approval Order';
		$page_data['roles']  = $this->common_model->selectAll('roles',array('head_assignment'=>'yes'),'');
		$page_data['order']  = $this->common_model->imbursement_approval_order();
	    $this->load->view('theme/user/main', $page_data);
		
	}
	
	function arrange_imbursement_approval_order() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$data = $this->input->post('data');
		$data = json_decode($data,true);
		
		if(!empty($data)) {
			
			$count = 0;
			foreach($data as $key=>$value) {
		
				$resp = $this->common_model->update(array('position'=>($key+1)),array('role_id'=>$value['role_id'],'imbursement_approval_order_id'=>$value['imbursement_approval_order_id']),'imbursement_approval_order');
				if(!empty($resp)) {
				  $count++;
			    }				
			}
			
			if($count > 0) {
				$response=array('status' => 1, 'msg' => 'Approval order changed!');
		        echo json_encode($response);
		        exit;
			}		
			
		} else {
			$response=array('status' => 0, 'msg' => 'Something went wrong!');
		    echo json_encode($response);
		    exit;
		}
	}
	
	function imbursement_approval_order_process() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$this->form_validation->set_rules('role_id', 'Role id', 'trim|required');		
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');		
		if($this->form_validation->run()) {
			
		  $data = $this->input->post(NULL,true);
		  
		  $resp = $this->common_model->new_imbursement_approval_head($data['role_id']);
		  if(!empty($resp)) {
			$response=array('status' => 1, 'msg' => 'Added successfully!');
		    echo json_encode($response);
		    exit;
		  } else {
			$response=array('status' => 0, 'msg' => 'Something went wrong!');
		    echo json_encode($response);
		    exit;
		  }	  
		  
		} else {
			$response=array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
			exit;
		}
		
	}
	
	function delete_imbursement_approval_order_process() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$this->form_validation->set_rules('imbursement_approval_order_id', 'Order id', 'trim|required');		
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');		
		if($this->form_validation->run()) {
			
		  $data = $this->input->post(NULL,true);
		  
		  $resp = $this->common_model->delete_imbursement_approval_order($data['imbursement_approval_order_id']);
		  if(!empty($resp)) {
			$response=array('status' => 1, 'msg' => 'Deleted successfully!');
		    echo json_encode($response);
		    exit;
		  } else {
			$response=array('status' => 0, 'msg' => 'Something went wrong!');
		    echo json_encode($response);
		    exit;
		  }	  
		  
		} else {
			$response=array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
			exit;
		}
	}
	
	function work_location() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$page_data['page_type']    = 'admin';
		$page_data['page_name']    = 'work_location';
		$page_data['menu']         = 'admin';
	    $page_data['page_title']   = 'Add/Edit Work Location';
				
	    $this->load->view('theme/user/main', $page_data);
		
	}
	
	function work_location_list_ajax() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$all = $this->common_model->selectAll('work_locations','','');
		
		$work_location['data'] = [];
		if(!empty($all)) {
			foreach($all as $key=>$value) {
				
				$work_location['data'][$key]['name'] = $value['name'];
				$work_location['data'][$key]['description'] = $value['description'];
				$work_location['data'][$key]['latitude'] = $value['latitude'];
				$work_location['data'][$key]['longitude'] = $value['longitude'];
				$work_location['data'][$key]['action'] = '<button type="button" class="btn btn-sm btn-outline-secondary" title="Edit" onclick="editWorkLocation(\''.addslashes($value['name']).'\',\''.addslashes($value['description']).'\',\''.$value['work_location_id'].'\',\''.$value['latitude'].'\',\''.$value['longitude'].'\')"><i class="fa fa-edit"></i></button>&nbsp;<button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteWorkLocation(\''.$value['work_location_id'].'\')"><i class="fa fa-trash-o"></i></button>';;
				
			}			
		}		
		echo json_encode($work_location);
		
	}
	
	function work_location_process() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$this->form_validation->set_rules('name', 'Location name', 'trim|required');	
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');		
		if($this->form_validation->run()) {			
			$name = $this->input->post('name');
			$description = $this->input->post('description');
			$latitude = $this->input->post('latitude');
			$longitude = $this->input->post('longitude');
			$work_location_id = $this->input->post('work_location_id');
			
			if(!empty($work_location_id)) {
				
				$resp = $this->common_model->update(array('name'=>stripslashes($name),'description'=>stripslashes($description),'latitude'=>stripslashes($latitude),'longitude'=>stripslashes($longitude)),array('work_location_id'=>$work_location_id),'work_locations');
				if(!empty($resp)) {
				$response=array('status' => 1, 'msg' => 'Updated successfully!');
				echo json_encode($response);
				exit;
			  } else {
				$response=array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			  }
				
			} else {
				
				$resp = $this->common_model->insert(array('name'=>stripslashes($name),'description'=>stripslashes($description),'latitude'=>stripslashes($latitude),'longitude'=>stripslashes($longitude)),'work_locations');
				if(!empty($resp)) {
				$response=array('status' => 1, 'msg' => 'Added successfully!');
				echo json_encode($response);
				exit;
			  } else {
				$response=array('status' => 0, 'msg' => 'Something went wrong!');
				echo json_encode($response);
				exit;
			  }
			}
			
		} else {
			$response=array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
			exit;
		}
	}
	
	//check employee code exist
	function checkWorkLocationExist() {
	   $name = $this->input->post('name');
	   $work_location_id = $this->input->post('work_location_id');
       if(!empty($name) && trim($name) != "") {
		   if(!empty($work_location_id))
		   $exist = $this->common_model->selectAll('work_locations',array('name'=>$name,'work_location_id!='=>$work_location_id),'work_location_id');
		   else
           $exist = $this->common_model->selectAll('work_locations',array('name'=>$name),'work_location_id');		   
          if(!empty($exist)) {
              echo 'false';
          } else {
              echo 'true';
          }
       } 
	}
	
	function delete_work_location_process() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$this->form_validation->set_rules('work_location_id', 'Location id', 'trim|required');		
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');		
		if($this->form_validation->run()) {
			
		  $data = $this->input->post(NULL,true);
		  
		  $resp = $this->common_model->delete_work_location($data['work_location_id']);
		  if(!empty($resp)) {
			$response=array('status' => 1, 'msg' => 'Deleted successfully!');
		    echo json_encode($response);
		    exit;
		  } else {
			$response=array('status' => 0, 'msg' => 'Something went wrong!');
		    echo json_encode($response);
		    exit;
		  }	  
		  
		} else {
			$response=array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
			exit;
		}
	}
	
	function permission() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$page_data['page_type']    = 'admin';
		$page_data['menu']         = 'admin';
		$page_data['page_name']    = 'permission';
	    $page_data['page_title']   = 'Permission';
		$page_data['roles']  = $this->common_model->selectAll('roles','','');
	    $this->load->view('theme/user/main', $page_data);
	}
	
	function permission_ajax() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$role_id = $this->input->post('role_id');
		$all = $this->common_model->get_permissions($role_id);
		
		$permission = [];
		if(!empty($all)) {
			$i=0;
			foreach($all as $key=>$value) {
				
				$permission[$key]['module'] = $value['name'];
				$permission[$key]['view'] = '<label class="fancy-checkbox"><input class="checkbox-tick" type="checkbox" name="view'.$i.'" '.(($value['view'] == "1")?'checked':'').' data-role="'.$value['role_id'].'" data-module="'.$value['module_id'].'" data-type="v"><span></span></label>';
				$permission[$key]['add'] = '<label class="fancy-checkbox"><input class="checkbox-tick" type="checkbox" name="add'.$i.'" '.(($value['add'] == "1")?'checked':'').' data-role="'.$value['role_id'].'" data-module="'.$value['module_id'].'" data-type="a"><span></span></label>';
				$permission[$key]['edit'] = '<label class="fancy-checkbox"><input class="checkbox-tick" type="checkbox" name="edit'.$i.'" '.(($value['edit'] == "1")?'checked':'').' data-role="'.$value['role_id'].'" data-module="'.$value['module_id'].'" data-type="e"><span></span></label>';
				$permission[$key]['delete'] = '<label class="fancy-checkbox"><input class="checkbox-tick" type="checkbox" name="delete'.$i.'" '.(($value['delete'] == "1")?'checked':'').' data-role="'.$value['role_id'].'" data-module="'.$value['module_id'].'" data-type="d"><span></span></label>';
				$permission[$key]['approvereject'] = '<label class="fancy-checkbox"><input class="checkbox-tick" type="checkbox" name="approvereject'.$i.'" '.(($value['approvereject'] == "1")?'checked':'').' data-role="'.$value['role_id'].'" data-module="'.$value['module_id'].'" data-type="ar"><span></span></label>';
				$i++;
			}			
		}		
		echo json_encode($permission);		
	}
	
	function permission_process() {
		
		if(!check_user_login()) {
            redirect('signin', 'refresh');
        }
		
		$this->form_validation->set_rules('role_id', 'Role id', 'trim|required');
		$this->form_validation->set_rules('module_id', 'Module id', 'trim|required');
		$this->form_validation->set_rules('type', 'Type', 'trim|required');
		$this->form_validation->set_rules('value', 'Value', 'trim|required');
		$this->form_validation->set_error_delimiters('<div class="mt-2">', '</div>');		
		if($this->form_validation->run()) {
			
		  $data = $this->input->post(NULL,true);		  
		  $resp = $this->common_model->permission_process($data);
		  if(!empty($resp)) {
			$response=array('status' => 1, 'msg' => 'Permission changed successfully!');
		    echo json_encode($response);
		    exit;
		  } else {
			$response=array('status' => 0, 'msg' => 'Something went wrong!');
		    echo json_encode($response);
		    exit;
		  }	  
		  
		} else {
			$response=array('status' => 0, 'msg' => validation_errors());
            echo json_encode($response);
			exit;
		}
	}
	
	function popup($page_name = '' , $param2 = '' , $param3 = '',$param4='') {

        $page_data['param2']        =   $param2;
        $page_data['param3']        =   $param3;
        $page_data['param4']        =   $param4;
		
        $this->load->view('admin/'.$page_name, $page_data);

    }
}