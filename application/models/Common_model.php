<?php

class Common_model extends CI_Model
{
    // get table count
    public function get_count($table, $where = "")
    {
        if ($where != "") {
            $this->db->where($where);
        }
        $res = $this->db->count_all_results($table);

        return $res;
    }

    //-- insert function
    public function insert($data, $table)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    //-- update function
    public function update($action, $where, $table)
    {
        $this->db->where($where);
        $this->db->update($table, $action);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //-- delete by ids
    public function deleteByids($where, $table)
    {
        $this->db->delete($table, $where);
        return true;
    }

    //-- select function
    public function selectAll($table, $where, $fields)
    {
        $this->db->select($fields);
        $this->db->from($table);
        if ($where != "") {
            $this->db->where($where);
        }
        $query = $this->db->get();
        $row = $query->result_array();
        if (!empty($row)) {
            return $row;
        } else {
            return false;
        }
    }

    public function selectOne($table, $where, $fields)
    {
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        $row = $query->row_array();
        if (!empty($row)) {
            return $row;
        } else {
            return false;
        }
    }

    public function enum_select($table, $field)
    {
        $query = " SHOW COLUMNS FROM `$table` LIKE '$field' ";
        $row = $this->db->query(" SHOW COLUMNS FROM `$table` LIKE '$field' ")->row()->Type;
        $regex = "/'(.*?)'/";
        preg_match_all($regex, $row, $enum_array);
        $enum_fields = $enum_array[1];
        return ($enum_fields);
    }
    // unlink files from folder
    public function unlink_files($file_name, $filefolder)
    {
        if (!empty($file_name)) {
            if (file_exists($filefolder . '/' . $file_name)) {
                if (unlink($filefolder . '/' . $file_name)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function employees_list($status = "")
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

    public function employees_by_id($id)
    {

        $param = array($id);
        $qry = "select e.*,dep.name as department,des.name as designation, NULL as password from employee as e left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id) where e.employee_id = ?";
        $res = $this->db->query($qry, $param)->row_array();
        if (!empty($res)) {

            //get passport details
            $res['passport_docs'] = $this->db->get_where('employee_docs', array('employee_id' => $res['employee_id'], 'type' => 'passport'))->result_array();
            $res['visa_docs'] = $this->db->get_where('employee_docs', array('employee_id' => $res['employee_id'], 'type' => 'visa'))->result_array();
            $res['emirate_docs'] = $this->db->get_where('employee_docs', array('employee_id' => $res['employee_id'], 'type' => 'emirate'))->result_array();
            $res['labour_docs'] = $this->db->get_where('employee_docs', array('employee_id' => $res['employee_id'], 'type' => 'labour'))->result_array();

            $this->db->select('e.*,q.name');
            $this->db->join('qualifications as q', 'e.qualification_id = q.qualification_id');
            $res['education'] = $this->db->get_where('education as e', array('e.employee_id' => $res['employee_id']))->result_array();
            if (!empty($res['education'])) {
                foreach ($res['education'] as $key => $value) {

                    $res['education'][$key]['docs'] = $this->db->get_where('education_docs', array('education_id' => $value['education_id']))->result_array();
                }
            }

            $res['insurance'] = $this->db->get_where('insurance', array('employee_id' => $res['employee_id']))->result_array();
            if (!empty($res['insurance'])) {
                foreach ($res['insurance'] as $key => $value) {

                    $res['insurance'][$key]['docs'] = $this->db->get_where('insurance_docs', array('insurance_id' => $value['insurance_id']))->result_array();
                }
            }
            $res['bank_details'] = $this->db->get_where('bank', array('employee_id' => $res['employee_id']))->result_array();
            if (!empty($res['bank_details'])) {
                foreach ($res['bank_details'] as $key => $value) {

                    $res['bank_details'][$key]['docs'] = $this->db->get_where('bank_docs', array('bank_id' => $value['bank_id']))->result_array();
                }
            }

            $res['heads'] = $this->get_assignments($res['code']);

            $qry3 = "select r.name,r.role_id from user_roles as ur left join roles as r on (ur.role_id = r.role_id) where ur.employee_id = ?";
            $res['roles'] = $this->db->query($qry3, $res['employee_id'])->result_array();

            return $res;
        } else {
            return false;
        }
    }

    public function employees_by_code($code)
    {
        $this->load->model('employee_model');
        $select = 'e.*,"" as password,pre_district.name pre_district,per_district.name per_district,pre_state.name pre_state,per_state.name per_state,pre_country.name pre_country,per_country.name per_country,nationality.name nationality,cat.name category,des.name designation,dep.name department,rm.name reporting_manager,rmroles.name reporting_manager_role';
        $where['1'] = "1";
        $where['e.code'] = $code;
        $join = "pre_state,per_state,pre_district,per_district,pre_country,per_country,nationality,cat,des,dep,rm,rmroles";
        $res = $this->employee_model->get_employees($select, $where, array('join' => $join))->row_array();
        if (!empty($res)) {
            //get passport details
            $res['passport_docs'] = $this->db->get_where('employee_docs', array('employee_id' => $res['employee_id'], 'type' => 'passport'))->result_array();
            $res['visa_docs'] = $this->db->get_where('employee_docs', array('employee_id' => $res['employee_id'], 'type' => 'visa'))->result_array();
            $res['emirate_docs'] = $this->db->get_where('employee_docs', array('employee_id' => $res['employee_id'], 'type' => 'emirate'))->result_array();
            $res['labour_docs'] = $this->db->get_where('employee_docs', array('employee_id' => $res['employee_id'], 'type' => 'labour'))->result_array();

            $this->db->select('e.*,q.name');
            $this->db->join('qualifications as q', 'e.qualification_id = q.qualification_id');
            $res['education'] = $this->db->get_where('education as e', array('e.employee_id' => $res['employee_id']))->result_array();
            if (!empty($res['education'])) {
                foreach ($res['education'] as $key => $value) {

                    $res['education'][$key]['docs'] = $this->db->get_where('education_docs', array('education_id' => $value['education_id']))->result_array();
                }
            }

            $res['insurance'] = $this->db->get_where('insurance', array('employee_id' => $res['employee_id']))->result_array();
            if (!empty($res['insurance'])) {
                foreach ($res['insurance'] as $key => $value) {

                    $res['insurance'][$key]['docs'] = $this->db->get_where('insurance_docs', array('insurance_id' => $value['insurance_id']))->result_array();
                }
            }
            $res['bank_details'] = $this->db->get_where('bank', array('employee_id' => $res['employee_id']))->result_array();
            if (!empty($res['bank_details'])) {
                foreach ($res['bank_details'] as $key => $value) {

                    $res['bank_details'][$key]['docs'] = $this->db->get_where('bank_docs', array('bank_id' => $value['bank_id']))->result_array();
                }
            }

            $res['heads'] = $this->get_assignments($res['code']);

            $qry3 = "select r.name,r.role_id from user_roles as ur left join roles as r on (ur.role_id = r.role_id) where ur.employee_id = ?";
            $res['roles'] = $this->db->query($qry3, $res['employee_id'])->result_array();

            return $res;
        } else {
            return false;
        }
    }

    public function employees_id_by_code($code)
    {

        $param = array($code);
        $qry = "select e.employee_id from employee as e where e.code = ?";
        $res = $this->db->query($qry, $param)->row_array();
        if (!empty($res)) {
            return $res['employee_id'];
        }
    }

    public function checkDupCode($code, $employee_id = "")
    {

        if (!empty($code) && $code != "" && !empty($employee_id) && $employee_id != "") {
            $param = array($code, $employee_id);
            $sql = "select code from employee where code = ? and employee_id != ?";
        } else {
            $param = array($code);
            $sql = "select code from employee where code = ?";
        }

        $res = $this->db->query($sql, $param)->row_array();
        if (!empty($res)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkDupEmail($email, $code = "")
    {

        if (!empty($email) && $email != "" && !empty($code) && $code != "") {
            $param = array($email, $code);
            $sql = "select email from employee where email = ? and code != ?";
        } else {
            $param = array($email);
            $sql = "select email from employee where email = ?";
        }

        $res = $this->db->query($sql, $param)->row_array();
        //echo $this->db->last_query();
        if (!empty($res)) {
            return true;
        } else {
            return false;
        }
    }

    public function save_education($data, $files)
    {

        if (!empty($data)) {

            foreach ($data as $key => $value) {

                if (!empty($value['education_id'])) {
                    //edit existing
                    $parm = array(
                        'qualification_id' => $value['qualification_id'],
                        'year' => $value['year'],
                        'institution' => $value['institution'],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $this->db->where('education_id', $value['education_id']);
                    $this->db->update('education', $parm);
                    $education_id = $value['education_id'];
                } else {
                    //add new entry
                    unset($value['education_id']);
                    $this->db->insert('education', $value);
                    $education_id = $this->db->insert_id();
                }

                if (!empty($education_id)) {

                    $oldmask = umask(0);
                    if (!is_dir('assets/uploads/user_docs/' . $value['employee_id'] . '/education')) {
                        mkdir('assets/uploads/user_docs/' . $value['employee_id'] . '/education', 0777, true);
                        if (!file_exists('assets/uploads/user_docs/' . $value['employee_id'] . '/education/index.html')) {
                            file_put_contents('assets/uploads/user_docs/' . $value['employee_id'] . '/education/index.html', '');
                        }
                    }
                    umask($oldmask);

                    $count = count($files['education_images']['name'][$key]);
                    if (!empty($count)) {
                        for ($i = 0; $i < $count; $i++) {

                            $_FILES['education_images']['name'] = $files['education_images']['name'][$key][$i];
                            $_FILES['education_images']['type'] = $files['education_images']['type'][$key][$i];
                            $_FILES['education_images']['tmp_name'] = $files['education_images']['tmp_name'][$key][$i];
                            $_FILES['education_images']['error'] = $files['education_images']['error'][$key][$i];
                            $_FILES['education_images']['size'] = $files['education_images']['size'][$key][$i];

                            $image = date('dmYhis') . '_' . rand(0, 99999) . "." . pathinfo($_FILES['education_images']['name'], PATHINFO_EXTENSION);
                            $path = 'assets/uploads/user_docs/' . $value['employee_id'] . '/education';

                            $config['upload_path'] = $path;
                            $config['allowed_types'] = '*';
                            $config['file_name']   = $image;

                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('education_images')) {

                                //resize the uploaded
                                image_resize($path . '/' . $image, '1000', '1000', true);
                                $udata = array(
                                    'education_id' => $education_id,
                                    'doc_path' => $image,
                                    'created_at' => date('Y-m-d H:i:s')

                                );
                                $udata = $this->security->xss_clean($udata);
                                $this->common_model->insert($udata, 'education_docs');
                            }
                            unset($_FILES['education_images'][0]);
                        }
                    }
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function save_insurance($data, $files)
    {

        if (!empty($data)) {

            foreach ($data as $key => $value) {

                if (!empty($value['insurance_id'])) {
                    //edit existing
                    $parm = array(
                        'company' => $value['company'],
                        'cost' => $value['cost'],
                        'expiry' => $value['expiry'],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $this->db->where('insurance_id', $value['insurance_id']);
                    $this->db->update('insurance', $parm);
                    $insurance_id = $value['insurance_id'];
                } else {
                    //add new entry
                    unset($value['insurance_id']);
                    $this->db->insert('insurance', $value);
                    $insurance_id = $this->db->insert_id();
                }

                if (!empty($insurance_id)) {

                    $oldmask = umask(0);
                    if (!is_dir('assets/uploads/user_docs/' . $value['employee_id'] . '/insurance')) {
                        mkdir('assets/uploads/user_docs/' . $value['employee_id'] . '/insurance', 0777, true);
                        if (!file_exists('assets/uploads/user_docs/' . $value['employee_id'] . '/insurance/index.html')) {
                            file_put_contents('assets/uploads/user_docs/' . $value['employee_id'] . '/insurance/index.html', '');
                        }
                    }
                    umask($oldmask);

                    $count = count($files['insurance_images']['name'][$key]);
                    if (!empty($count)) {
                        for ($i = 0; $i < $count; $i++) {

                            $_FILES['insurance_images']['name'] = $files['insurance_images']['name'][$key][$i];
                            $_FILES['insurance_images']['type'] = $files['insurance_images']['type'][$key][$i];
                            $_FILES['insurance_images']['tmp_name'] = $files['insurance_images']['tmp_name'][$key][$i];
                            $_FILES['insurance_images']['error'] = $files['insurance_images']['error'][$key][$i];
                            $_FILES['insurance_images']['size'] = $files['insurance_images']['size'][$key][$i];

                            $image = date('dmYhis') . '_' . rand(0, 99999) . "." . pathinfo($_FILES['insurance_images']['name'], PATHINFO_EXTENSION);
                            $path = 'assets/uploads/user_docs/' . $value['employee_id'] . '/insurance';

                            $config['upload_path'] = $path;
                            $config['allowed_types'] = '*';
                            $config['file_name']   = $image;

                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('insurance_images')) {

                                //resize the uploaded
                                image_resize($path . '/' . $image, '1000', '1000', true);
                                $udata = array(
                                    'insurance_id' => $insurance_id,
                                    'doc_path' => $image,
                                    'created_at' => date('Y-m-d H:i:s')

                                );
                                $udata = $this->security->xss_clean($udata);
                                $this->common_model->insert($udata, 'insurance_docs');
                            }
                            unset($_FILES['insurance_images'][0]);
                        }
                    }
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function save_bank($data, $files)
    {

        if (!empty($data)) {

            foreach ($data as $key => $value) {

                if (!empty($value['bank_id'])) {
                    //edit existing
                    $parm = array(
                        'bank_name' => $value['bank_name'],
                        'account_number' => $value['account_number'],
                        'iban_number' => $value['iban_number'],
                        'swift_code' => $value['swift_code'],
                        'updated_at' => date('Y-m-d H:i:s')
                    );
                    $this->db->where('bank_id', $value['bank_id']);
                    $this->db->update('bank', $parm);
                    $bank_id = $value['bank_id'];
                } else {
                    //add new entry
                    unset($value['bank_id']);
                    $this->db->insert('bank', $value);
                    $bank_id = $this->db->insert_id();
                }

                if (!empty($bank_id)) {

                    $oldmask = umask(0);
                    if (!is_dir('assets/uploads/user_docs/' . $value['employee_id'] . '/bank')) {
                        mkdir('assets/uploads/user_docs/' . $value['employee_id'] . '/bank', 0777, true);
                        if (!file_exists('assets/uploads/user_docs/' . $value['employee_id'] . '/bank/index.html')) {
                            file_put_contents('assets/uploads/user_docs/' . $value['employee_id'] . '/bank/index.html', '');
                        }
                    }
                    umask($oldmask);

                    $count = count($files['bank_images']['name'][$key]);
                    if (!empty($count)) {
                        for ($i = 0; $i < $count; $i++) {

                            $_FILES['bank_images']['name'] = $files['bank_images']['name'][$key][$i];
                            $_FILES['bank_images']['type'] = $files['bank_images']['type'][$key][$i];
                            $_FILES['bank_images']['tmp_name'] = $files['bank_images']['tmp_name'][$key][$i];
                            $_FILES['bank_images']['error'] = $files['bank_images']['error'][$key][$i];
                            $_FILES['bank_images']['size'] = $files['bank_images']['size'][$key][$i];

                            $image = date('dmYhis') . '_' . rand(0, 99999) . "." . pathinfo($_FILES['bank_images']['name'], PATHINFO_EXTENSION);
                            $path = 'assets/uploads/user_docs/' . $value['employee_id'] . '/bank';

                            $config['upload_path'] = $path;
                            $config['allowed_types'] = '*';
                            $config['file_name']   = $image;

                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('bank_images')) {

                                //resize the uploaded
                                image_resize($path . '/' . $image, '1000', '1000', true);
                                $udata = array(
                                    'bank_id' => $bank_id,
                                    'doc_path' => $image,
                                    'created_at' => date('Y-m-d H:i:s')

                                );
                                $udata = $this->security->xss_clean($udata);
                                $this->common_model->insert($udata, 'bank_docs');
                            }
                            unset($_FILES['bank_images'][0]);
                        }
                    }
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function employee_delete($employee_id, $code)
    {

        $exist = $this->db->get_where('employee', array('employee_id' => $employee_id, 'code' => $code))->row_array();
        if (!empty($exist)) {

            $this->db->delete('employee', array('employee_id' => $exist['employee_id'], 'code' => $exist['code']));
            if ($this->db->affected_rows() > 0) {

                //remove profile pic
                if (!empty($exist['profile_photo'])) {
                    if (file_exists('assets/uploads/user_pic/' . $exist['profile_photo'])) {
                        unlink('assets/uploads/user_pic/' . $exist['profile_photo']);
                    }
                }
                //remove user docs
                @delete_directory('assets/uploads/user_docs/' . $exist['employee_id']);

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function leave_application_list($employee_id)
    {

        //$qry = "select l.*,lt.title,e.code,e.name,e.profile_photo,e.email,dep.name as department,des.name as designation from leave_application as l left join employee as e on (l.employee_id = e.employee_id) left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id) left join leave_types as lt on (l.leave_type_id = lt.leave_type_id)";

        $qry = 'select DISTINCT(l.leave_application_id),l.*,lt.title,e.code,e.name,e.profile_photo,e.email,dep.name as department,des.name as designation from leave_application_status as las left join leave_application as l on (las.leave_application_id = l.leave_application_id) left join employee as e on (l.employee_id = e.employee_id) left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id) left join leave_types as lt on (l.leave_type_id = lt.leave_type_id) where las.reporting_head_id = ?';

        $res = $this->db->query($qry, $employee_id)->result_array();

        // echo $this->db->last_query();
        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }

    public function leave_application_status($employee_id)
    {

        $where = " 1=1 ";
        if (!check_role_permission(5)) {
            $where .= " and l.employee_id = ? ";
        }

        $qry = 'select DISTINCT(l.leave_application_id),l.*,lt.title,e.code,e.name,e.profile_photo,e.email,dep.name as department,des.name as designation from leave_application as l left join employee as e on (l.employee_id = e.employee_id) left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id) left join leave_types as lt on (l.leave_type_id = lt.leave_type_id) where ' . $where;

        $res = $this->db->query($qry, $employee_id)->result_array();

        // echo $this->db->last_query();
        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }

    public function get_heads($role)
    {

        $qry = "select e.employee_id,e.name from user_roles as ur left join employee as e on (ur.employee_id = e.employee_id) where ur.role_id = ?";
        $res = $this->db->query($qry, $role)->result_array();
        return $res;
    }

    //for showing current heads in head assignment page
    public function get_assignments($code)
    {

        $qry = "SELECT rh.reporting_id,emp.name,r.name as role,emp.profile_photo,emp.code,rh.created_at FROM reporting_heads as rh left join employee as emp on (rh.reporting_head_id = emp.employee_id) left join roles as r on(rh.role_id = r.role_id) left join employee as e1 on (rh.employee_id = e1.employee_id) where e1.code = ?";
        $res = $this->db->query($qry, $code)->result_array();
        return $res;
    }

    public function delete_assignment($reporting_id)
    {

        $this->db->delete('reporting_heads', array('reporting_id' => $reporting_id));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function pending_head_assignment($employee_id)
    {
        $qry = "select GROUP_CONCAT(r.name) as roles from leave_approval_order as a left join roles as r on (a.role_id = r.role_id) where a.role_id NOT IN (select rh.role_id from reporting_heads as rh where rh.employee_id = ?)";
        $res = $this->db->query($qry, $employee_id)->row_array();
        return $res['roles'];
    }

    public function pending_imbursement_head_assignment($employee_id)
    {
        $qry = "select GROUP_CONCAT(r.name) as roles from imbursement_approval_order as a left join roles as r on (a.role_id = r.role_id) where a.role_id NOT IN (select rh.role_id from reporting_heads as rh where rh.employee_id = ?)";
        $res = $this->db->query($qry, $employee_id)->row_array();
        return $res['roles'];
    }

    public function get_head_by_position($employee_id, $position)
    {
        $qry = "SELECT ao.role_id,ao.position,rh.reporting_head_id FROM `leave_approval_order` as ao left join reporting_heads as rh on (ao.role_id = rh.role_id) where rh.employee_id = ? and ao.position = ?";
        $res = $this->db->query($qry, array($employee_id, $position))->row_array();
        return $res;
    }

    public function get_base_head($employee_id)
    {
        $qry = "SELECT ao.role_id,ao.position,rh.reporting_head_id FROM `leave_approval_order` as ao left join reporting_heads as rh on (ao.role_id = rh.role_id) where rh.employee_id = ? and ao.position = (select min(position) from leave_approval_order)";
        $res = $this->db->query($qry, $employee_id)->row_array();
        return $res;
    }
    public function get_top_head($employee_id)
    {
        $qry = "SELECT ao.role_id,ao.position,rh.reporting_head_id FROM `leave_approval_order` as ao left join reporting_heads as rh on (ao.role_id = rh.role_id) where rh.employee_id = ? and ao.position = (select max(position) from leave_approval_order)";
        $res = $this->db->query($qry, $employee_id)->row_array();
        return $res;
    }
    public function get_next_head($employee_id, $position)
    {
        $qry = "SELECT ao.role_id,ao.position,rh.reporting_head_id FROM `leave_approval_order` as ao left join reporting_heads as rh on (ao.role_id = rh.role_id) where rh.employee_id = ? and ao.position = (SELECT position FROM leave_approval_order where position > ? ORDER BY position limit 1)";
        $res = $this->db->query($qry, array($employee_id, $position))->row_array();
        return $res;
    }

    public function get_imbursement_base_head($employee_id)
    {
        $qry = "SELECT ao.role_id,ao.position,rh.reporting_head_id FROM `imbursement_approval_order` as ao left join reporting_heads as rh on (ao.role_id = rh.role_id) where rh.employee_id = ? and ao.position = (select min(position) from imbursement_approval_order)";
        $res = $this->db->query($qry, $employee_id)->row_array();
        return $res;
    }
    public function get_imbursement_top_head($employee_id)
    {
        $qry = "SELECT ao.role_id,ao.position,rh.reporting_head_id FROM `imbursement_approval_order` as ao left join reporting_heads as rh on (ao.role_id = rh.role_id) where rh.employee_id = ? and ao.position = (select max(position) from imbursement_approval_order)";
        $res = $this->db->query($qry, $employee_id)->row_array();
        return $res;
    }
    public function get_imbursement_next_head($employee_id, $position)
    {
        $qry = "SELECT ao.role_id,ao.position,rh.reporting_head_id FROM `imbursement_approval_order` as ao left join reporting_heads as rh on (ao.role_id = rh.role_id) where rh.employee_id = ? and ao.position = (SELECT position FROM imbursement_approval_order where position > ? ORDER BY position limit 1)";
        $res = $this->db->query($qry, array($employee_id, $position))->row_array();
        return $res;
    }

    public function check_in_heads_list($employee_id, $session_id)
    {

        $roles = $this->session->userdata('type');
        $role_array = array();
        if ($roles != "") {
            $role_array = explode(",", $roles);
        }

        $qry = "select * from reporting_heads where employee_id = ? and reporting_head_id = ?";
        $res = $this->db->query($qry, array($employee_id, $session_id))->row_array();

        if (!empty($res) || in_array('5', $role_array)) {
            return true;
        } else {
            return false;
        }
    }

    //full details of leave
    public function leave_by_id($leave_application_id)
    {

        $qry = "select la.*,lt.title,e.code,e.passport_number,e.emirate_number,e.date_of_join,e.name,d.name as designation,e.phone_office,e.phone_current,e.phone_home from leave_application as la left join leave_types as lt on (la.leave_type_id = lt.leave_type_id) left join employee as e on (la.employee_id = e.employee_id) left join designations as d on (e.designation_id = d.designation_id) where la.leave_application_id = ?";
        $res = $this->db->query($qry, $leave_application_id)->row_array();
        if (!empty($res)) {

            $qry1 = "select las.leave_application_status_id,e.name,e.profile_photo,r.name as role,las.position,las.status,las.status_changed,las.remarks from leave_application_status as las left join employee as e on (las.reporting_head_id = e.employee_id) left join roles as r on(las.role_id = r.role_id) where las.leave_application_id = ? order by position ASC";
            $res1 = $this->db->query($qry1, $res['leave_application_id'])->result_array();

            $res['detailed_status'] = $res1;

            return $res;
        } else {
            return false;
        }
    }

    //for approval process, get current head leave status which is latest status
    public function leave_status_by_head($head_id, $leave_id)
    {
        $qry = "select las.*,r.name as role from leave_application_status as las left join roles as r on(las.role_id = r.role_id) where las.reporting_head_id = ? and las.leave_application_id = ? order by las.leave_application_status_id DESC limit 1";
        $res = $this->db->query($qry, array($head_id, $leave_id))->row_array();
        return $res;
    }

    public function save_assets($assets)
    {

        if (!empty($assets)) {
            $this->db->trans_start();
            $this->db->insert_batch('asset_details', $assets);
            $this->db->trans_complete();
            if ($this->db->trans_status() === true) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function employee_assets()
    {

        $qry = "select distinct(aa.employee_id),count(aa.employee_id) as assets_count,e.code,e.name,e.email,e.phone_current,e.status,e.profile_photo,dep.name as department,des.name as designation from asset_assignment as aa left join employee as e on (aa.employee_id = e.employee_id )left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id) group by aa.employee_id";
        $res = $this->db->query($qry)->result_array();
        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }

    public function assets_list($employee_id)
    {

        $qry = "select aa.*, a.name from asset_assignment as aa left join assets as a on (aa.asset_id = a.asset_id) where aa.employee_id = ?";
        $res = $this->db->query($qry, $employee_id)->result_array();
        if (!empty($res)) {
            foreach ($res as $key => $value) {

                $qry1 = "select asset_details_id,asset_assignment_id,title,description from asset_details where asset_assignment_id = ?";
                $res1 = $this->db->query($qry1, $value['asset_assignment_id'])->result_array();

                $res[$key]['details'] = $res1;
            }

            return $res;
        } else {
            return false;
        }
    }

    public function remove_asset($id, $type)
    {

        if ($type == "sub") {
            $this->db->delete('asset_details', array('asset_details_id' => $id));
        }
        if ($type == "full") {
            $this->db->delete('asset_assignment', array('asset_assignment_id' => $id));
        }
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function assigned_work_locations()
    {

        $qry = "select wla.assignment_id,e.code,e.name,e.email,e.phone_current,e.status,e.profile_photo,dep.name as department,des.name as designation,wl.name as work_location,wla.status as work_status,wla.start_date,wla.end_date from work_location_assignment as wla left join employee as e on (wla.employee_id = e.employee_id ) left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id) left join work_locations as wl ON (wla.work_location_id = wl.work_location_id)";
        $res = $this->db->query($qry)->result_array();
        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }

    public function assigned_work_locations_by_employee($employee_id)
    {

        $qry = "select wla.assignment_id,wl.name as work_location,wla.status as work_status,wla.start_date,wla.end_date,wl.location,wl.latitude,wl.longitude from work_location_assignment as wla left join work_locations as wl ON (wla.work_location_id = wl.work_location_id) where wla.employee_id = ?";
        $res = $this->db->query($qry, $employee_id)->result_array();
        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }

    public function delete_location_assignment($assignment_id)
    {

        $this->db->delete('work_location_assignment', array('assignment_id' => $assignment_id));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function print_letters()
    {

        $qry = "select el.employee_letter_id,l.type,el.letter_id,el.generated_date,el.refno,el.created_at,el.updated_at,e.code,e.name,e.email,e.phone_current,e.status,e.profile_photo,dep.name as department,des.name as designation from employee_letters as el left join employee as e on (el.employee_id = e.employee_id ) left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id) left join letters as l ON (el.letter_id = l.letter_id)";
        $res = $this->db->query($qry)->result_array();
        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }

    public function delete_employee_letter($employee_letter_id)
    {

        $this->db->delete('employee_letters', array('employee_letter_id' => $employee_letter_id));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function save_imbursement($data, $employee_id, $files)
    {

        if (!empty($data)) {

            $base_reporting_head = $this->get_imbursement_base_head($employee_id);

            foreach ($data as $key => $value) {

                $this->db->insert('imbursement_application', $value);
                $imbursement_application_id = $this->db->insert_id();
                if (!empty($imbursement_application_id)) {

                    //save to imbursement status table
                    $param1 = array(
                        'imbursement_application_id' => $imbursement_application_id,
                        'status' => 'pending',
                        'status_changed' => date('Y-m-d'),
                        'reporting_head_id' => $base_reporting_head['reporting_head_id'],
                        'role_id' => $base_reporting_head['role_id'],
                        'position' => $base_reporting_head['position'],
                        'created_at' => date('Y-m-d H:i:s')
                    );

                    $param1 = $this->security->xss_clean($param1);
                    $imbursement_application_status_id = $this->common_model->insert($param1, 'imbursement_application_status');

                    $oldmask = umask(0);
                    if (!is_dir('assets/uploads/user_docs/' . $value['employee_id'] . '/imbursement')) {
                        mkdir('assets/uploads/user_docs/' . $value['employee_id'] . '/imbursement', 0777, true);
                        if (!file_exists('assets/uploads/user_docs/' . $value['employee_id'] . '/imbursement/index.html')) {
                            file_put_contents('assets/uploads/user_docs/' . $value['employee_id'] . '/imbursement/index.html', '');
                        }
                    }
                    umask($oldmask);

                    $count = count($files['imbursement_images']['name'][$key]);
                    if (!empty($count)) {
                        for ($i = 0; $i < $count; $i++) {

                            $_FILES['imbursement_images']['name'] = $files['imbursement_images']['name'][$key][$i];
                            $_FILES['imbursement_images']['type'] = $files['imbursement_images']['type'][$key][$i];
                            $_FILES['imbursement_images']['tmp_name'] = $files['imbursement_images']['tmp_name'][$key][$i];
                            $_FILES['imbursement_images']['error'] = $files['imbursement_images']['error'][$key][$i];
                            $_FILES['imbursement_images']['size'] = $files['imbursement_images']['size'][$key][$i];

                            $image = date('dmYhis') . '_' . rand(0, 99999) . "." . pathinfo($_FILES['imbursement_images']['name'], PATHINFO_EXTENSION);
                            $path = 'assets/uploads/user_docs/' . $value['employee_id'] . '/imbursement';

                            $config['upload_path'] = $path;
                            $config['allowed_types'] = '*';
                            $config['file_name']   = $image;

                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('imbursement_images')) {

                                //resize the uploaded
                                image_resize($path . '/' . $image, '1000', '1000', true);
                                $udata = array(
                                    'imbursement_application_id' => $imbursement_application_id,
                                    'doc_path' => $image,
                                    'created_at' => date('Y-m-d H:i:s')

                                );
                                $udata = $this->security->xss_clean($udata);
                                $this->common_model->insert($udata, 'imbursement_docs');
                            }
                            unset($_FILES['imbursement_images'][0]);
                        }
                    }
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function edit_imbursement($data, $employee_id, $files)
    {

        if (!empty($data)) {
            $base_reporting_head = $this->get_imbursement_base_head($employee_id);
            foreach ($data as $key => $value) {

                if (!empty($value['imbursement_application_id'])) {
                    //edit existing
                    $parm = array(
                        'imbursement_date' => $value['imbursement_date'],
                        'details' => $value['details'],
                        'amount' => $value['amount'],
                        'updated_at' => $value['updated_at'],
                        'updated_by' => $value['updated_by']
                    );
                    $this->db->where('imbursement_application_id', $value['imbursement_application_id']);
                    $this->db->update('imbursement_application', $parm);
                    $imbursement_application_id = $value['imbursement_application_id'];
                } else {
                    //add new entry
                    $param1 = array(
                        'imbursement_date' => $value['imbursement_date'],
                        'details' => $value['details'],
                        'amount' => $value['amount'],
                        'employee_id' => $employee_id,
                        'status' => 'pending',
                        'unique_code' => $value['unique_code'],
                        'status_changed' => date('Y-m-d'),
                        'created_by' => $value['updated_by'],
                        'created_at' => $value['updated_at']
                    );

                    $param1 = $this->security->xss_clean($param1);
                    $this->db->insert('imbursement_application', $param1);
                    $imbursement_application_id = $this->db->insert_id();

                    if (!empty($imbursement_application_id)) {
                        //save to imbursement status table
                        $param2 = array(
                            'imbursement_application_id' => $imbursement_application_id,
                            'status' => 'pending',
                            'status_changed' => date('Y-m-d'),
                            'reporting_head_id' => $base_reporting_head['reporting_head_id'],
                            'role_id' => $base_reporting_head['role_id'],
                            'position' => $base_reporting_head['position'],
                            'created_at' => date('Y-m-d H:i:s')
                        );

                        $param2 = $this->security->xss_clean($param2);
                        $imbursement_application_status_id = $this->common_model->insert($param2, 'imbursement_application_status');
                    }
                }

                if (!empty($imbursement_application_id)) {

                    $oldmask = umask(0);
                    if (!is_dir('assets/uploads/user_docs/' . $value['employee_id'] . '/imbursement')) {
                        mkdir('assets/uploads/user_docs/' . $value['employee_id'] . '/imbursement', 0777, true);
                        if (!file_exists('assets/uploads/user_docs/' . $value['employee_id'] . '/imbursement/index.html')) {
                            file_put_contents('assets/uploads/user_docs/' . $value['employee_id'] . '/imbursement/index.html', '');
                        }
                    }
                    umask($oldmask);

                    $count = count($files['imbursement_images']['name'][$key]);
                    if (!empty($count)) {
                        for ($i = 0; $i < $count; $i++) {

                            $_FILES['imbursement_images']['name'] = $files['imbursement_images']['name'][$key][$i];
                            $_FILES['imbursement_images']['type'] = $files['imbursement_images']['type'][$key][$i];
                            $_FILES['imbursement_images']['tmp_name'] = $files['imbursement_images']['tmp_name'][$key][$i];
                            $_FILES['imbursement_images']['error'] = $files['imbursement_images']['error'][$key][$i];
                            $_FILES['imbursement_images']['size'] = $files['imbursement_images']['size'][$key][$i];

                            $image = date('dmYhis') . '_' . rand(0, 99999) . "." . pathinfo($_FILES['imbursement_images']['name'], PATHINFO_EXTENSION);
                            $path = 'assets/uploads/user_docs/' . $value['employee_id'] . '/imbursement';

                            $config['upload_path'] = $path;
                            $config['allowed_types'] = '*';
                            $config['file_name']   = $image;

                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);

                            if ($this->upload->do_upload('imbursement_images')) {

                                //resize the uploaded
                                image_resize($path . '/' . $image, '1000', '1000', true);
                                $udata = array(
                                    'imbursement_application_id' => $imbursement_application_id,
                                    'doc_path' => $image,
                                    'created_at' => date('Y-m-d H:i:s')

                                );
                                $udata = $this->security->xss_clean($udata);
                                $this->common_model->insert($udata, 'imbursement_docs');
                            }
                            unset($_FILES['imbursement_images'][0]);
                        }
                    }
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function imbursement_application_list($employee_id)
    {

        $qry = 'select DISTINCT(i.unique_code),sum(IF((i.status = "pending" || i.status = "approved"), i.amount, 0)) as amount,e.code,e.name,e.profile_photo,e.email,dep.name as department,des.name as designation,i.created_at from imbursement_application as i left join imbursement_application_status as ias on (i.imbursement_application_id = ias.imbursement_application_id) left join employee as e on (i.employee_id = e.employee_id) left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id) where ias.reporting_head_id = ? GROUP BY i.unique_code';

        $res = $this->db->query($qry, $employee_id)->result_array();

        // echo $this->db->last_query();
        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }

    public function imbursement_application_status($employee_id)
    {

        $where = " 1=1 ";
        if (!check_role_permission(5)) {
            $where .= " and i.employee_id = ? ";
        }

        $qry = 'select DISTINCT(i.unique_code),sum(IF((i.status = "pending" || i.status = "approved"), i.amount, 0)) as amount,i.employee_id,e.code,e.name,e.profile_photo,e.email,dep.name as department,des.name as designation,i.created_at from imbursement_application as i left join employee as e on (i.employee_id = e.employee_id) left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id) where ' . $where . ' GROUP BY i.unique_code';

        $res = $this->db->query($qry, $employee_id)->result_array();

        // echo $this->db->last_query();
        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }

    public function imbursement_report()
    {

        $qry = "select DISTINCT(i.unique_code),sum(IF((i.status = 'approved'), i.amount, 0)) as amount, sum(IF(i.status = 'approved', 1, 0)) as approved,i.employee_id,e.code,e.name,e.profile_photo,e.email,dep.name as department,des.name as designation,i.created_at from imbursement_application as i left join employee as e on (i.employee_id = e.employee_id) left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id) where i.status != 'pending' and i.status != 'rejected' GROUP BY i.unique_code";

        $res = $this->db->query($qry)->result_array();

        // echo $this->db->last_query();
        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }

    public function imbursement_status_by_head($head_id, $unique_code)
    {

        $qry = 'SELECT sum(IF(ias.status = "pending", 1, 0)) as pending, sum(IF(ias.status = "approved", 1, 0)) as approved, sum(IF(ias.status = "rejected", 1, 0)) as rejected FROM imbursement_application_status as ias left join imbursement_application as ia on (ias.imbursement_application_id = ia.imbursement_application_id) where ia.unique_code = ? and ias.reporting_head_id = ? and ias.position = (SELECT max(ias1.position) from imbursement_application_status as ias1 left join imbursement_application as ia1 on(ias1.imbursement_application_id = ia1.imbursement_application_id) where ias1.reporting_head_id = ? and ia1.unique_code = ?)';

        $res = $this->db->query($qry, array($unique_code, $head_id, $head_id, $unique_code))->row_array();
        //echo $this->db->last_query();
        return $res;
    }

    public function imbursement_status_by_code($unique_code)
    {

        $qry = 'SELECT sum(IF(ia.status = "pending", 1, 0)) as pending, sum(IF(ia.status = "approved", 1, 0)) as approved, sum(IF(ia.status = "rejected", 1, 0)) as rejected FROM imbursement_application as ia  where ia.unique_code = ?';

        $res = $this->db->query($qry, array($unique_code))->row_array();
        //echo $this->db->last_query();
        return $res;
    }

    public function imbursement_detail_by_code($unique_code)
    {

        $qry = 'SELECT * FROM imbursement_application where unique_code = ?';
        $res = $this->db->query($qry, $unique_code)->result_array();

        if (!empty($res)) {

            foreach ($res as $key => $value) {

                $qry1 = "select ias.imbursement_application_status_id,ias.imbursement_application_id, ias.status, ias.status_changed,ias.reporting_head_id,ias.role_id,ias.position,e.name,r.name as role from imbursement_application_status as ias left join employee as e on (ias.reporting_head_id = e.employee_id) left join roles as r on (ias.role_id = r.role_id) where ias.imbursement_application_id = ? order by ias.position ASC";

                $res1 = $this->db->query($qry1, $value['imbursement_application_id'])->result_array();

                $res[$key]['full_status'] = $res1;

                $res[$key]['docs'] = $this->db->get_where('imbursement_docs', array('imbursement_application_id' => $value['imbursement_application_id']))->result_array();
            }

            return $res;
        } else {
            return false;
        }
    }

    public function save_imbursement_status($data, $employee_id, $unique_code)
    {

        if (!empty($data)) {

            $get_top_head = $this->common_model->get_imbursement_top_head($employee_id);

            $rejected_count = 0;
            foreach ($data as $key => $value) {

                if ($value['status'] == "rejected") {
                    $rejected_count++;
                }

                $param = array(
                    'status' => $value['status'],
                    'status_changed' => $value['status_changed'],
                    'updated_at' => $value['updated_at']
                );

                $param = $this->security->xss_clean($param);
                //update status table
                $this->common_model->update($param, array('imbursement_application_status_id' => $value['imbursement_application_status_id']), 'imbursement_application_status');
            }

            //reject whole imbursement on rejection of one
            if ($rejected_count > 0) {

                $param1 = array(
                    'status' => 'rejected',
                    'status_changed' => date('Y-m-d'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $param1 = $this->security->xss_clean($param1);
                $this->common_model->update($param1, array('unique_code' => $unique_code), 'imbursement_application');
            } else {

                foreach ($data as $key => $value) {

                    //change status of main imbursement if currrent user id top head
                    if ($get_top_head['reporting_head_id'] == $value['reporting_head_id'] && $get_top_head['role_id'] == $value['role_id'] && $get_top_head['position'] == $value['position']) {

                        $param3 = array(
                            'status' => $value['status'],
                            'status_changed' => $value['status_changed'],
                            'updated_at' => $value['updated_at']
                        );

                        $param3 = $this->security->xss_clean($param3);
                        //update main table
                        $this->common_model->update($param3, array('imbursement_application_id' => $value['imbursement_application_id']), 'imbursement_application');
                    } else {

                        $next_head = $this->common_model->get_imbursement_next_head($employee_id, $value['position']);
                        $param4 = array(
                            'imbursement_application_id' => $value['imbursement_application_id'],
                            'status' => 'pending',
                            'status_changed' => date('Y-m-d'),
                            'reporting_head_id' => $next_head['reporting_head_id'],
                            'role_id' => $next_head['role_id'],
                            'position' => $next_head['position'],
                            'created_at' => date('Y-m-d H:i:s')
                        );
                        $param4 = $this->security->xss_clean($param4);
                        $this->common_model->insert($param4, 'imbursement_application_status');
                    }
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function delete_department($department_id)
    {

        $this->db->delete('departments', array('department_id' => $department_id));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_designation($designation_id)
    {

        $this->db->delete('designations', array('designation_id' => $designation_id));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_role($role_id)
    {

        $this->db->delete('roles', array('role_id' => $role_id));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function delete_leave_type($leave_type_id)
    {

        $this->db->delete('leave_types', array('leave_type_id' => $leave_type_id));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function leave_approval_order()
    {
        $qry = "SELECT ao.approval_order_id,ao.role_id,ao.position,r.name FROM `leave_approval_order` as ao left join roles as r on (ao.role_id = r.role_id) order by ao.position ASC";
        $res = $this->db->query($qry)->result_array();
        return $res;
    }

    public function imbursement_approval_order()
    {
        $qry = "SELECT ao.imbursement_approval_order_id,ao.role_id,ao.position,r.name FROM `imbursement_approval_order` as ao left join roles as r on (ao.role_id = r.role_id) order by ao.position ASC";
        $res = $this->db->query($qry)->result_array();
        return $res;
    }

    public function new_leave_approval_head($role_id)
    {

        $exist = $this->db->get_where("leave_approval_order", array('role_id' => $role_id))->row_array();
        if (empty($exist)) {

            $this->db->select_max("position");
            $max_position = $this->db->get('leave_approval_order')->row_array();

            //insert new role
            $this->db->insert('leave_approval_order', array('role_id' => $role_id, 'position' => ($max_position['position'] + 1)));
            $leave_approval_id = $this->db->insert_id();
            if (!empty($leave_approval_id)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function new_imbursement_approval_head($role_id)
    {

        $exist = $this->db->get_where("imbursement_approval_order", array('role_id' => $role_id))->row_array();
        if (empty($exist)) {

            $this->db->select_max("position");
            $max_position = $this->db->get('imbursement_approval_order')->row_array();

            //insert new role
            $this->db->insert('imbursement_approval_order', array('role_id' => $role_id, 'position' => ($max_position['position'] + 1)));
            $imbursement_approval_id = $this->db->insert_id();
            if (!empty($imbursement_approval_id)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function delete_leave_approval_order($approval_order_id)
    {

        $this->db->delete('leave_approval_order', array('approval_order_id' => $approval_order_id));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_imbursement_approval_order($imbursement_approval_order_id)
    {

        $this->db->delete('imbursement_approval_order', array('imbursement_approval_order_id' => $imbursement_approval_order_id));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_work_location($work_location_id)
    {

        $this->db->delete('work_locations', array('work_location_id' => $work_location_id));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_rejoin_status($employee_id)
    {

        $qry = "SELECT rj.leave_rejoin_id,lt.title as leave_type,rj.employee_id,rj.leave_status,rj.leave_date,rj.rejoin_status,rj.rejoin_date,rj.paid,rj.unpaid,rj.remarks,rj.total_days,rj.leave_application_id FROM employee_leave_rejoin as rj left join leave_application as la on (rj.leave_application_id = la.leave_application_id) left join leave_types as lt on (la.leave_type_id = lt.leave_type_id) where rj.employee_id = ? order by rj.leave_rejoin_id DESC";
        $resp = $this->db->query($qry, $employee_id)->result_array();
        return $resp;
    }

    public function employees_master()
    {

        $qry = "select e.*,dep.name as department,des.name as designation, NULL as password from employee as e left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id)";
        $res = $this->db->query($qry)->result_array();
        if (!empty($res)) {

            foreach ($res as $key => $value) {

                $this->db->select('e.*,q.name');
                $this->db->join('qualifications as q', 'e.qualification_id = q.qualification_id');
                $res[$key]['education'] = $this->db->get_where('education as e', array('e.employee_id' => $value['employee_id']))->result_array();

                $res[$key]['insurance'] = $this->db->get_where('insurance', array('employee_id' => $value['employee_id']))->result_array();

                $res[$key]['bank_details'] = $this->db->get_where('bank', array('employee_id' => $value['employee_id']))->result_array();

                $res[$key]['heads'] = $this->get_assignments($value['code']);

                $qry3 = "select r.name,r.role_id from user_roles as ur left join roles as r on (ur.role_id = r.role_id) where ur.employee_id = ?";
                $res[$key]['roles'] = $this->db->query($qry3, $value['employee_id'])->result_array();

                $qry4 = "select wl.name, wla.start_date,wla.end_date from work_location_assignment as wla left join work_locations as wl on (wla.work_location_id = wl.work_location_id) where wla.status = 'active' and wla.employee_id = ?";
                $res[$key]['locations'] = $this->db->query($qry4, $value['employee_id'])->result_array();
            }

            return $res;
        } else {
            return false;
        }
    }

    public function get_permissions($role_id)
    {

        $qry = "select m.name,m.module_id from modules as m";
        $res = $this->db->query($qry)->result_array();

        if (!empty($res)) {

            foreach ($res as $key => $value) {
                $qry1 =  "select mp.module_permission_id,mp.role_id,mp.v, mp.a,mp.e,mp.d,mp.ar from module_permission as mp where mp.role_id = ? and mp.module_id = ?";
                $res1 = $this->db->query($qry1, array($role_id, $value['module_id']))->row_array();

                if (!empty($res1)) {
                    $res[$key]['module_permission_id'] = $res1['module_permission_id'];
                    $res[$key]['role_id'] = $role_id;
                    $res[$key]['module_id'] = $value['module_id'];
                    $res[$key]['view'] = $res1['v'];
                    $res[$key]['add'] = $res1['a'];
                    $res[$key]['edit'] = $res1['e'];
                    $res[$key]['delete'] = $res1['d'];
                    $res[$key]['approvereject'] = $res1['ar'];
                } else {
                    $res[$key]['module_permission_id'] = null;
                    $res[$key]['role_id'] = $role_id;
                    $res[$key]['module_id'] = $value['module_id'];
                    $res[$key]['view'] = "0";
                    $res[$key]['add'] = "0";
                    $res[$key]['edit'] = "0";
                    $res[$key]['delete'] = "0";
                    $res[$key]['approvereject'] = "0";
                }
            }
        }

        return $res;
    }

    public function permission_process($data)
    {

        $exist = "select module_permission_id from module_permission where module_id = ? and role_id = ?";
        $qry = $this->db->query($exist, array($data['module_id'], $data['role_id']))->row_array();
        if (!empty($qry)) {
            $resp = $this->update(array($data['type'] => $data['value']), array('module_permission_id' => $qry['module_permission_id']), 'module_permission');
            if (!empty($resp)) {
                return true;
            } else {
                return false;
            }
        } else {
            $resp = $this->insert(array('module_id' => $data['module_id'], 'role_id' => $data['role_id'], $data['type'] => $data['value']), 'module_permission');
            if (!empty($resp)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function updateLeaveCron($employee_id = "")
    {

        //get employee status first
        $empstatus = "select status,date_of_join from employee where employee_id = ? and status != ?";
        $empstatusres = $this->db->query($empstatus, array($employee_id, 'resigned'))->row_array();

        if (!empty($empstatusres) && !empty($empstatusres['date_of_join'])) {

            $ret['date_of_join'] = set_date($empstatusres['date_of_join']);
            $ret['employee_id'] = $employee_id;
            $curYear = date('Y-m-d');

            $aperyear =  "select leave_details_id,start_date, end_date from employee_leave_details where employee_id = ? and start_date <= ? and end_date >= ? order by leave_details_id DESC limit 1";
            $res = $this->db->query($aperyear, array($employee_id, $curYear, $curYear))->row_array();

            if (!empty($res)) {

                $ret['start_date'] = $res['start_date'];
                $ret['end_date']   = $res['end_date'];
                $ret['leave_details_id']   = $res['leave_details_id'];
            } else {

                //get last entry
                $lastentry = "select leave_details_id,start_date, end_date,balance_annual from employee_leave_details where employee_id = ? order by leave_details_id DESC limit 1";
                $res1 = $this->db->query($lastentry, array($employee_id))->row_array();
                if (!empty($res1)) {

                    //add entry followed by existing
                    $newsdate = date('Y-m-d', strtotime($res1['end_date'] . ' + 1 days'));
                    $newedate = date('Y-m-d', strtotime($newsdate . ' + 364 days'));
                    //need to do carry fwd thing here

                    if ($res1['balance_annual'] < 0) {
                        $balance_annual = 0;
                    } else {
                        $balance_annual = $res1['balance_annual'];
                    }

                    //need to do carry fwd thing here

                } else {

                    //add new entry with date of join
                    $newsdate = set_date($empstatusres['date_of_join']);
                    $newedate = date('Y-m-d', strtotime($newsdate . ' + 364 days'));
                    $balance_annual = 0;
                }

                $leave_details_id = $this->common_model->insert(array('employee_id' => $employee_id, 'start_date' => $newsdate, 'end_date' => $newedate, 'carry_fwd' => $balance_annual, 'last_updated' => date('Y-m-d H:i:s')), 'employee_leave_details');
                if (!empty($leave_details_id)) {
                    $ret['start_date'] = $newsdate;
                    $ret['end_date']   = $newedate;
                    $ret['leave_details_id'] = $leave_details_id;
                }
            }

            if (!empty($ret['start_date']) && !empty($ret['end_date']) && !empty($ret['leave_details_id'])) {
                echo '<br/>----------------------------------------------------<br/>';
                echo 'start: ' . $ret['start_date'] . ' end:' . $ret['end_date'];

                //calculate total days after join upto today
                $joindate   = $ret['date_of_join'];
                $datetime1  = strtotime($joindate . ' 00:00:00');
                $datetime2  = strtotime($curYear . ' 24:00:00');
                $difference = abs($datetime2 - $datetime1);

                $years  = floor($difference / (365 * 60 * 60 * 24));
                $months = floor(($difference - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                $days   = floor(($difference - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

                $ret['years']   = $years;
                $ret['months']  = $months;
                $ret['days']    = $days;

                //find this year leaves
                $datetime2  = strtotime($ret['start_date'] . ' 00:00:00');
                $datetime3  = strtotime($curYear . ' 24:00:00');
                $difference1 = abs($datetime3 - $datetime2);

                $y  = floor($difference1 / (365 * 60 * 60 * 24));
                ;
                $m  = floor(($difference1 - $y * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                $d  = floor(($difference1 - $y * 365 * 60 * 60 * 24 - $m * 30 * 60 * 60 * 24) / (60 * 60 * 24));

                //check whether current date in the calculating year gap. because not to calculate leave for old join dates ranges
                if ($curYear >= $ret['start_date'] && $curYear <= $ret['end_date']) {

                    //fetch total leave taken in the date range
                    $qry2 = "SELECT lt.leave_type_id,lt.title,IFNULL((select sum(elr.total_days) as totdays from employee_leave_rejoin as elr left join leave_application as la on (elr.leave_application_id = la.leave_application_id) where elr.employee_id = ? and la.leave_type_id = lt.leave_type_id and elr.leave_date >= ? and elr.leave_date <= ? and elr.leave_status = 'no' and elr.rejoin_status = 'yes' and la.status = 'approved'),0) as lcount FROM leave_types as lt";
                    $tleaves = $this->db->query($qry2, array($employee_id, $ret['start_date'], $ret['end_date']))->result_array();
                    $ret['taken_leaves'] = $tleaves;
                    $emergency = 0; //emergency taken - unpaid
                    $annual = 0; //annual taken - paid
                    if (!empty($tleaves)) {
                        foreach ($tleaves as $row) {
                            if ($row['leave_type_id'] == 4) {
                                $emergency += $row['lcount'];
                            }
                            if ($row['leave_type_id'] == 1) {
                                $annual += $row['lcount'];
                            }
                        }
                    }

                    if ($ret['years'] >= 1) {
                        //calculation for employees whose join date is morethan 1 year
                        $ret['per_month_eligible'] = (($y) * 12 * 2.5) + ($m * 2.5);
                    } else {
                        //calculation for employees whose join date lesstan 1 year
                        $ret['per_month_eligible'] = ($m * 2);
                    }

                    $totuptotoday                = (365 * $y + 30 * $m + $d); //from start date to today
                    $work_days_upto_today        = ($totuptotoday - $emergency);
                    $ret['work_days_upto_today'] = $work_days_upto_today;
                    $ret['eligible_annual']      = round((($work_days_upto_today / 365) * $ret['per_month_eligible']) - $annual, 2);

                    $updata['total_annual']      = round((($work_days_upto_today / 365) * $ret['per_month_eligible']), 2);
                    $updata['annual_enjoyed']    = $annual;

                    $carry_fwd = $this->db->get_where('employee_leave_details', array('leave_details_id' => $ret['leave_details_id']))->row_array();
                    $updata['balance_annual']    = (($carry_fwd['carry_fwd'] + $updata['total_annual']) - $updata['annual_enjoyed']);

                    //update total annual leave
                    $fupdate = $this->common_model->update($updata, array('leave_details_id' => $ret['leave_details_id']), 'employee_leave_details');
                } else {
                    $ret['per_month_eligible']   = 0;
                    $ret['work_days_upto_today'] = 0;
                    $ret['eligible_annual']      = 0;
                }
                //
                //pr($updata);
                //pr($ret);
                return $ret;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /* previous logic with date difference change
     *function updateLeaveCron($employee_id="") {

        //get employee status first
        $empstatus = "select status,date_of_join from employee where employee_id = ? and status != ?";
        $empstatusres = $this->db->query($empstatus,array($employee_id,'resigned'))->row_array();

        if(!empty($empstatusres) && !empty($empstatusres['date_of_join'])) {

            $ret['date_of_join'] = set_date($empstatusres['date_of_join']);
            $ret['employee_id'] = $employee_id;
            $curYear = date('Y-m-d');

            $aperyear =  "select leave_details_id,start_date, end_date from employee_leave_details where employee_id = ? and start_date <= ? and end_date >= ? order by leave_details_id DESC limit 1";
            $res = $this->db->query($aperyear,array($employee_id,$curYear,$curYear))->row_array();

            if(!empty($res)) {

                $ret['start_date'] = $res['start_date'];
                $ret['end_date']   = $res['end_date'];
                $ret['leave_details_id']   = $res['leave_details_id'];

            } else {

                //get last entry
                $lastentry = "select leave_details_id,start_date, end_date,balance_annual from employee_leave_details where employee_id = ? order by leave_details_id DESC limit 1";
                $res1 = $this->db->query($lastentry,array($employee_id))->row_array();
                if(!empty($res1)) {

                    //add entry followed by existing
                    $newsdate = date('Y-m-d', strtotime($res1['end_date']. ' + 1 days'));
                    $newedate = date('Y-m-d', strtotime($newsdate. ' + 364 days'));
                    //need to do carry fwd thing here

                    if($res1['balance_annual'] < 0) {
                        $balance_annual = 0;
                    } else {
                        $balance_annual = $res1['balance_annual'];
                    }

                    //need to do carry fwd thing here

                } else {

                    //add new entry with date of join
                    $newsdate = set_date($empstatusres['date_of_join']);
                    $newedate = date('Y-m-d', strtotime($newsdate. ' + 364 days'));
                    $balance_annual = 0;

                }

                $leave_details_id = $this->common_model->insert(array('employee_id'=>$employee_id,'start_date'=>$newsdate,'end_date'=>$newedate,'carry_fwd'=>$balance_annual,'last_updated'=>date('Y-m-d H:i:s')),'employee_leave_details');
                if(!empty($leave_details_id)) {
                  $ret['start_date'] = $newsdate;
                  $ret['end_date']   = $newedate;
                  $ret['leave_details_id'] = $leave_details_id;
                }
            }

            if(!empty($ret['start_date']) && !empty($ret['end_date']) && !empty($ret['leave_details_id'])) {

                //calculate total days after join upto today
                $joindate   = $ret['date_of_join'];
                $datetime1  = new DateTime(date('Y-m-d H:i:s',strtotime($joindate.' 00:00:00')));
                $datetime2  = new DateTime(date('Y-m-d H:i:s',strtotime($curYear.' 24:00:00')));
                $difference = $datetime2->diff($datetime1);
                $totdays    = ($difference->days);
                $ret['totdays'] = $totdays;
                $ret['years']   = $difference->y;
                $ret['months']  = $difference->m;
                $ret['days']    = $difference->d;

                //find this year leaves
                $datetime2  = new DateTime(date('Y-m-d H:i:s',strtotime($ret['start_date'].' 00:00:00')));
                $datetime3  = new DateTime(date('Y-m-d H:i:s',strtotime($curYear.' 24:00:00')));
                $difference1 = $datetime3->diff($datetime2);

                //pr($difference1);

                $y  = $difference1->y;
                $m  = $difference1->m;
                $d  = $difference1->d;

                //check whether current date in the calculating year gap. because not to calculate leave for old join dates ranges
                if($curYear >= $ret['start_date'] && $curYear <= $ret['end_date']) {

                   //fetch total leave taken in the date range
                   $qry2 = "SELECT lt.leave_type_id,lt.title,IFNULL((select sum(elr.total_days) as totdays from employee_leave_rejoin as elr left join leave_application as la on (elr.leave_application_id = la.leave_application_id) where elr.employee_id = ? and la.leave_type_id = lt.leave_type_id and elr.leave_date >= ? and elr.leave_date <= ? and elr.leave_status = 'no' and elr.rejoin_status = 'yes' and la.status = 'approved'),0) as lcount FROM leave_types as lt";
                    $tleaves = $this->db->query($qry2, array($employee_id,$ret['start_date'],$ret['end_date']))->result_array();
                    $ret['taken_leaves'] = $tleaves;
                    $emergency = 0; //emergency taken - unpaid
                    $annual = 0; //annual taken - paid
                    if(!empty($tleaves)) {
                        foreach($tleaves as $row) {
                            if($row['leave_type_id'] == 4) {
                                $emergency += $row['lcount'];
                            }
                            if($row['leave_type_id'] == 1) {
                                $annual += $row['lcount'];
                            }
                        }
                    }

                    if($totdays >= 365) {
                        //calculation for employees whose join date is morethan 1 year
                        $ret['per_month_eligible'] = (($y)*12*2.5)+($m*2.5);
                    } else {
                        //calculation for employees whose join date lesstan 1 year
                        $ret['per_month_eligible'] = ($m*2);
                    }

                    $totuptotoday                = ($difference1->days);//from start date to today
                    $work_days_upto_today        = ($totuptotoday-$emergency);
                    $ret['work_days_upto_today'] = $work_days_upto_today;
                    $ret['eligible_annual']      = round((($work_days_upto_today/365)*$ret['per_month_eligible'])-$annual,2);

                    $updata['total_annual']      = round((($work_days_upto_today/365)*$ret['per_month_eligible']),2);
                    $updata['annual_enjoyed']    = $annual;

                    $carry_fwd = $this->db->get_where('employee_leave_details',array('leave_details_id' => $ret['leave_details_id']))->row_array();
                    $updata['balance_annual']    = (($carry_fwd['carry_fwd']+$updata['total_annual'])-$updata['annual_enjoyed']);

                    //update total annual leave
                    $fupdate = $this->common_model->update($updata,array('leave_details_id'=>$ret['leave_details_id']),'employee_leave_details');

                } else {
                    $ret['per_month_eligible']   = 0;
                    $ret['work_days_upto_today'] = 0;
                    $ret['eligible_annual']      = 0;
                }
                //
                //pr($updata);
                //pr($ret);
                return $ret;

            } else {
                return false;
            }

        } else {
            return false;
        }
    } */

    //for old dates calculation - one time use only
    public function updateLeaveCronOldDate($employee_id = "")
    {

        //get employee status first
        $empstatus = "select status,date_of_join from employee where employee_id = ? and status != ?";
        $empstatusres = $this->db->query($empstatus, array($employee_id, 'resigned'))->row_array();

        if (!empty($empstatusres) && !empty($empstatusres['date_of_join'])) {

            $ret['date_of_join'] = set_date($empstatusres['date_of_join']);
            $ret['employee_id'] = $employee_id;
            $curYear = date('Y-m-d');

            $aperyear =  "select leave_details_id,start_date, end_date from employee_leave_details where employee_id = ? and start_date <= ? and end_date >= ? order by leave_details_id DESC limit 1";
            $res = $this->db->query($aperyear, array($employee_id, $curYear, $curYear))->row_array();

            if (!empty($res)) {

                $ret['start_date'] = $res['start_date'];
                $ret['end_date']   = $res['end_date'];
                $ret['leave_details_id']   = $res['leave_details_id'];
            } else {

                //get last entry
                $lastentry = "select leave_details_id,start_date, end_date,balance_annual from employee_leave_details where employee_id = ? order by leave_details_id DESC limit 1";
                $res1 = $this->db->query($lastentry, array($employee_id))->row_array();
                if (!empty($res1)) {

                    //add entry followed by existing
                    $newsdate = date('Y-m-d', strtotime($res1['end_date'] . ' + 1 days'));
                    $newedate = date('Y-m-d', strtotime($newsdate . ' + 364 days'));
                    //need to do carry fwd thing here

                    if ($res1['balance_annual'] < 0) {
                        $balance_annual = 0;
                    } else {
                        $balance_annual = $res1['balance_annual'];
                    }

                    //need to do carry fwd thing here

                } else {

                    //add new entry with date of join
                    $newsdate = set_date($empstatusres['date_of_join']);
                    $newedate = date('Y-m-d', strtotime($newsdate . ' + 364 days'));
                    $balance_annual = 0;
                }

                $leave_details_id = $this->common_model->insert(array('employee_id' => $employee_id, 'start_date' => $newsdate, 'end_date' => $newedate, 'carry_fwd' => $balance_annual, 'last_updated' => date('Y-m-d H:i:s')), 'employee_leave_details');
                if (!empty($leave_details_id)) {
                    $ret['start_date'] = $newsdate;
                    $ret['end_date']   = $newedate;
                    $ret['leave_details_id'] = $leave_details_id;
                }
            }

            if (!empty($ret['start_date']) && !empty($ret['end_date']) && !empty($ret['leave_details_id'])) {
                echo '<br/>----------------------------------------------------<br/>';
                echo 'start: ' . $ret['start_date'] . ' end:' . $ret['end_date'];

                //calculate total days after join upto today
                $joindate   = $ret['date_of_join'];
                $datetime1  = strtotime($joindate . ' 00:00:00');
                $datetime2  = strtotime($ret['end_date'] . ' 24:00:00');
                $difference = abs($datetime2 - $datetime1);

                $years  = floor($difference / (365 * 60 * 60 * 24));
                $months = floor(($difference - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                $days   = floor(($difference - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

                $ret['years']   = $years;
                $ret['months']  = $months;
                $ret['days']    = $days;

                //find this year leaves
                $datetime2  = strtotime($ret['start_date'] . ' 00:00:00');
                $datetime3  = strtotime($ret['end_date'] . ' 24:00:00');
                $difference1 = abs($datetime3 - $datetime2);

                $y  = floor($difference1 / (365 * 60 * 60 * 24));
                ;
                $m  = floor(($difference1 - $y * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                $d  = floor(($difference1 - $y * 365 * 60 * 60 * 24 - $m * 30 * 60 * 60 * 24) / (60 * 60 * 24));

                //check whether current date in the calculating year gap. because not to calculate leave for old join dates ranges
                //if($curYear >= $ret['start_date'] && $curYear <= $ret['end_date']) {

                //fetch total leave taken in the date range
                $qry2 = "SELECT lt.leave_type_id,lt.title,IFNULL((select sum(elr.total_days) as totdays from employee_leave_rejoin as elr left join leave_application as la on (elr.leave_application_id = la.leave_application_id) where elr.employee_id = ? and la.leave_type_id = lt.leave_type_id and elr.leave_date >= ? and elr.leave_date <= ? and elr.leave_status = 'no' and elr.rejoin_status = 'yes' and la.status = 'approved'),0) as lcount FROM leave_types as lt";
                $tleaves = $this->db->query($qry2, array($employee_id, $ret['start_date'], $ret['end_date']))->result_array();
                $ret['taken_leaves'] = $tleaves;
                $emergency = 0; //emergency taken - unpaid
                $annual = 0; //annual taken - paid
                if (!empty($tleaves)) {
                    foreach ($tleaves as $row) {
                        if ($row['leave_type_id'] == 4) {
                            $emergency += $row['lcount'];
                        }
                        if ($row['leave_type_id'] == 1) {
                            $annual += $row['lcount'];
                        }
                    }
                }

                if ($ret['years'] >= 1) {
                    //calculation for employees whose join date is morethan 1 year
                    $ret['per_month_eligible'] = (($y) * 12 * 2.5) + ($m * 2.5);
                } else {
                    //calculation for employees whose join date lesstan 1 year
                    $ret['per_month_eligible'] = ($m * 2);
                }

                $totuptotoday                = (365 * $y + 30 * $m + $d); //from start date to today
                $work_days_upto_today        = ($totuptotoday - $emergency);
                $ret['work_days_upto_today'] = $work_days_upto_today;
                $ret['eligible_annual']      = round((($work_days_upto_today / 365) * $ret['per_month_eligible']) - $annual, 2);

                $updata['total_annual']      = round((($work_days_upto_today / 365) * $ret['per_month_eligible']), 2);
                $updata['annual_enjoyed']    = $annual;

                $carry_fwd = $this->db->get_where('employee_leave_details', array('leave_details_id' => $ret['leave_details_id']))->row_array();
                $updata['balance_annual']    = (($carry_fwd['carry_fwd'] + $updata['total_annual']) - $updata['annual_enjoyed']);

                //update total annual leave
                $fupdate = $this->common_model->update($updata, array('leave_details_id' => $ret['leave_details_id']), 'employee_leave_details');

                //} else {
                //	$ret['per_month_eligible']   = 0;
                //	$ret['work_days_upto_today'] = 0;
                //	$ret['eligible_annual']      = 0;
                //}
                //
                //pr($updata);
                //pr($ret);
                return $ret;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function checkLeaveEligibility($employee_id = "")
    {

        $curYear  = date('Y-m-d');
        $aperyear = "select leave_details_id,start_date, end_date, balance_annual from employee_leave_details where employee_id = ? and start_date <= ? and end_date >= ? order by leave_details_id DESC limit 1";
        $res = $this->db->query($aperyear, array($employee_id, $curYear, $curYear))->row_array();
        //pr($res);
        if (!empty($res)) {

            $ret['start_date'] = $res['start_date'];
            $ret['end_date']   = $res['end_date'];
            $ret['leave_details_id']   = $res['leave_details_id'];
            $ret['eligible_annual_leave']   = $res['balance_annual'];

            $qry2 = "SELECT lt.leave_type_id,lt.title,IFNULL((select sum(elr.total_days) as totdays from employee_leave_rejoin as elr left join leave_application as la on (elr.leave_application_id = la.leave_application_id) where elr.employee_id = ? and la.leave_type_id = lt.leave_type_id and elr.leave_date >= ? and elr.leave_date <= ? and elr.leave_status = 'no' and elr.rejoin_status = 'yes' and la.status = 'approved'),0) as lcount FROM leave_types as lt";
            $tleaves = $this->db->query($qry2, array($employee_id, $ret['start_date'], $ret['end_date']))->result_array();
            $ret['taken_leaves'] = $tleaves;
            //pr($ret);
            return $ret;
        } else {
            return false;
        }
    }

    public function rejoin_leave_split($data)
    {

        $rejoin_date = set_date($data['rjdte']);
        $leave_type = $data['ltype'];
        $employee_id = $data['employee_id'];
        $leave_rejoin_id = $data['rjid'];

        $rejoin = $this->selectOne('employee_leave_rejoin', array('leave_rejoin_id' => $leave_rejoin_id), '*');
        $eligible_annual_leave = $this->checkLeaveEligibility($employee_id);

        $available_annual = ($eligible_annual_leave['eligible_annual_leave'] > 0) ? $eligible_annual_leave['eligible_annual_leave'] : 0;
        if ($leave_type == "1") {
            //for annual leave
            //check actual approved date is less than current rejoin date
            if ($available_annual >= 1) {

                //days between applied and rejoin date
                $datetime1  = new DateTime(date('Y-m-d H:i:s', strtotime($rejoin['leave_date'] . ' 00:00:00')));
                $datetime2  = new DateTime(date('Y-m-d H:i:s', strtotime($rejoin_date . ' 23:59:59')));
                $difference = $datetime2->diff($datetime1);
                $totdays    = ($difference->days + 1);

                $totdays = intval($totdays);
                $available_annual = intval($available_annual);

                if ($available_annual > $totdays) {
                    $ret[0]['leave_date']  = $rejoin['leave_date'];
                    $ret[0]['rejoin_date'] = $rejoin_date;
                    $ret[0]['paid'] = 'yes';
                    $ret[0]['unpaid'] = 'no';
                    $ret[0]['leave_rejoin_id'] = $leave_rejoin_id;
                } else {
                    //split into two entries -> from leave date to available leave days as paid and rest of the days as unpaid
                    $excess = $totdays - $available_annual;
                    $excess = intval($excess);
                    $ret[0]['leave_date']  = $rejoin['leave_date'];
                    $ret[0]['rejoin_date'] = date('Y-m-d', strtotime($rejoin['leave_date'] . ' + ' . ($available_annual - 1) . ' days'));
                    $ret[0]['paid'] = 'yes';
                    $ret[0]['unpaid'] = 'no';
                    $ret[0]['leave_rejoin_id'] = $leave_rejoin_id;
                    if ($excess > 0) {
                        $ret[1]['leave_date']  = date('Y-m-d', strtotime($ret[0]['rejoin_date'] . ' + 1 days'));
                        $ret[1]['rejoin_date'] = $rejoin_date;
                        $ret[1]['paid'] = 'no';
                        $ret[1]['unpaid'] = 'yes';
                        $ret[1]['leave_rejoin_id'] = null;
                    }
                }
            } else {
                $ret[0]['leave_date']  = $rejoin['leave_date'];
                $ret[0]['rejoin_date'] = $rejoin_date;
                $ret[0]['paid'] = 'no';
                $ret[0]['unpaid'] = 'yes';
                $ret[0]['leave_rejoin_id'] = $leave_rejoin_id;
            }
        } else {
            $ret[0]['leave_date']  = $rejoin['leave_date'];
            $ret[0]['rejoin_date'] = $rejoin_date;
            if ($leave_type == "4") {
                $ret[0]['paid'] = 'no';
                $ret[0]['unpaid'] = 'yes';
            } else {
                $ret[0]['paid'] = 'yes';
                $ret[0]['unpaid'] = 'no';
            }
            $ret[0]['leave_rejoin_id'] = $leave_rejoin_id;
        }

        return $ret;
    }

    //function checkLeaveEligibility($employee_id="") {
    //
    //	//get employee status first
    //	$empstatus = "select status,date_of_join from employee where employee_id = ? and status != ?";
    //	$empstatusres = $this->db->query($empstatus,array($employee_id,'resigned'))->row_array();
    //
    //	if(!empty($empstatusres) && !empty($empstatusres['date_of_join'])) {
    //
    //	    $ret['date_of_join'] = set_date($empstatusres['date_of_join']);
    //		$curYear = date('Y-m-d');
    //
    //		//new block start here.. comment from here to run old code
    //		//calculate total days after join upto today
    //	//    $cur        = date('Y-m-d');
    //	//	$joindate   = $ret['date_of_join'];
    //	//	$datetime1  = new DateTime(date('Y-m-d',strtotime($joindate.' 00:00:00')));
    //	//	$datetime2  = new DateTime(date('Y-m-d',strtotime($cur.' 23:59:59')));
    //	//	$difference = $datetime2->diff($datetime1);
    //	//	$totdays    = $difference->days;
    //	//	$ret['years']   = $difference->y;
    //	//	$ret['months']  = $difference->m;
    //	//	$ret['days']    = $difference->d;
    //	//
    //	//	if($totdays >= 365) {
    //	//		$ret['calculated_annual'] = (($ret['years'])*12*2.5)+($ret['months']*2.5);
    //	//	} else {
    //	//		$ret['calculated_annual'] = ($ret['months']*2);
    //	//	}
    //	//
    //	//	//total leaves taken up to today
    //	//	$qry2 = "SELECT lt.leave_type_id,lt.title,IFNULL((select sum(elr.total_days) as totdays from employee_leave_rejoin as elr left join leave_application as la on (elr.leave_application_id = la.leave_application_id) where elr.employee_id = ? and la.leave_type_id = lt.leave_type_id and elr.leave_date >= ? and elr.leave_date <= ? and elr.leave_status = 'no' and elr.rejoin_status = 'yes' and la.status = 'approved'),0) as lcount FROM leave_types as lt";
    //	//    $tleaves = $this->db->query($qry2, array($employee_id,$joindate,$cur))->result_array();
    //	//	$ret['taken_leaves'] = $tleaves;
    //	//
    //	//	$emergency = 0; //emergency taken - unpaid
    //	//	$annual = 0; //annual taken - paid
    //	//	if(!empty($tleaves)) {
    //	//		foreach($tleaves as $row) {
    //	//			if($row['leave_type_id'] == 4) {
    //	//				$emergency += $row['lcount'];
    //	//			}
    //	//			if($row['leave_type_id'] == 1) {
    //	//				$annual += $row['lcount'];
    //	//			}
    //	//		}
    //	//	}
    //	//	//only emergency leave impact on total work days
    //	//	$total_work_days = (365-$emergency);
    //	//	$ret['total_work_days'] = $total_work_days;
    //	//	$ret['eligible_annaul'] = round((($total_work_days/365)*30)-$annual,2);
    //	//	pr($ret);
    //	//	exit;
    //		//new block end here
    //
    //		$aperyear =  "select leave_details_id,start_date, end_date from employee_leave_details where employee_id = ? and start_date <= ? and end_date >= ? order by leave_details_id DESC limit 1";
    //		$res = $this->db->query($aperyear,array($employee_id,$curYear,$curYear))->row_array();
    //
    //		if(!empty($res)) {
    //
    //			$ret['start_date'] = $res['start_date'];
    //			$ret['end_date']   = $res['end_date'];
    //			$ret['leave_details_id']   = $res['leave_details_id'];
    //
    //		} else {
    //
    //			//get last entry
    //			$lastentry = "select leave_details_id,start_date, end_date from employee_leave_details where employee_id = ? order by leave_details_id DESC limit 1";
    //		    $res1 = $this->db->query($lastentry,array($employee_id))->row_array();
    //			if(!empty($res1)) {
    //
    //				//add entry followed by existing
    //				$newsdate = date('Y-m-d', strtotime($res1['end_date']. ' + 1 days'));
    //				$newedate = date('Y-m-d', strtotime($newsdate. ' + 1 years'));
    //				//need to do carry fwd thing here
    //
    //
    //				//need to do carry fwd thing here
    //
    //			} else {
    //
    //				//add new entry with date of join
    //				$newsdate = set_date($empstatusres['date_of_join']);
    //				$newedate = date('Y-m-d', strtotime($newsdate. ' + 1 years'));
    //
    //			}
    //
    //			$leave_details_id = $this->common_model->insert(array('employee_id'=>$employee_id,'start_date'=>$newsdate,'end_date'=>$newedate,'last_updated'=>date('Y-m-d H:i:s')),'employee_leave_details');
    //			if(!empty($leave_details_id)) {
    //			  $ret['start_date'] = $newsdate;
    //			  $ret['end_date']   = $newedate;
    //			  $ret['leave_details_id'] = $leave_details_id;
    //			}
    //		}
    //
    //		if(!empty($ret['start_date']) && !empty($ret['end_date']) && !empty($ret['leave_details_id'])) {
    //
    //
    //			//calculate total days after join upto today
    //			$cur        = date('Y-m-d');
    //			$joindate   = $ret['date_of_join'];
    //			$datetime1  = new DateTime(date('Y-m-d',strtotime($joindate.' 00:00:00')));
    //			$datetime2  = new DateTime(date('Y-m-d',strtotime($cur.' 23:59:59')));
    //			$difference = $datetime2->diff($datetime1);
    //			$totdays    = $difference->days;
    //			$ret['years']   = $difference->y;
    //		    $ret['months']  = $difference->m;
    //		    $ret['days']    = $difference->d;
    //
    //			//find this year leaves
    //			$datetime2  = new DateTime(date('Y-m-d',strtotime($ret['start_date'].' 00:00:00')));
    //			$datetime3  = new DateTime(date('Y-m-d',strtotime($cur.' 23:59:59')));
    //			$difference1 = $datetime3->diff($datetime2);
    //
    //			//pr($difference1);
    //
    //			$y  = $difference1->y;
    //		    $m  = $difference1->m;
    //		    $d  = $difference1->d;
    //
    //			//check whether current date in the calculating year gap. because not to calculate leave for old join dates ranges
    //			if($cur >= $ret['start_date'] && $cur <= $ret['end_date']) {
    //
    //				//if the join date is morethan 1 year per month 2.5 else 2
    //				if($totdays >= 365) {
    //					$ret['total_annual'] = (($y)*12*2.5)+($m*2.5);
    //				} else {
    //					$ret['total_annual'] = ($m*2);
    //				}
    //
    //			} else {
    //				$ret['total_annual'] = 0;
    //			}
    //
    //			//update total annual leave
    //			$fupdate = $this->common_model->update(array('total_annual'=>$ret['total_annual']),array('leave_details_id'=>$ret['leave_details_id']),'employee_leave_details');
    //
    //			//fetch total leave taken in the date range
    //			$qry2 = "SELECT lt.leave_type_id,lt.title,IFNULL((select sum(elr.total_days) as totdays from employee_leave_rejoin as elr left join leave_application as la on (elr.leave_application_id = la.leave_application_id) where elr.employee_id = ? and la.leave_type_id = lt.leave_type_id and elr.leave_date >= ? and elr.leave_date <= ? and elr.leave_status = 'no' and elr.rejoin_status = 'yes' and la.status = 'approved'),0) as lcount FROM leave_types as lt";
    //	        $tleaves = $this->db->query($qry2, array($employee_id,$ret['start_date'],$ret['end_date']))->result_array();
    //			//echo $this->db->last_query();
    //			$ret['taken_leaves'] = $tleaves;
    //
    //			$totleaves = 0; //total leave
    //			$totualeaves = 0; //unauthorixed leave
    //			if(!empty($tleaves)) {
    //				foreach($tleaves as $row) {
    //					$totleaves += $row['lcount'];
    //					if($row['leave_type_id'] == 4) {
    //						$totualeaves += $row['lcount'];
    //					}
    //				}
    //			}
    //			$ret['total_leaves'] = $totleaves;
    //			$ret['total_ua_leaves'] = $totualeaves;
    //
    //			pr($ret);
    //			return $ret;
    //
    //
    //		} else {
    //			return false;
    //		}
    //
    //	} else {
    //		return false;
    //	}
    //}

    public function inoutstatus($employee_id, $assignment_id)
    {

        $qry = "select * from work_location_inout where employee_id = ? and assignment_id = ? order by inout_id DESC limit 1";
        $res = $this->db->query($qry, array($employee_id, $assignment_id))->row_array();

        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }

    public function inoutreport($employee_id, $limit, $start)
    {

        if (($limit != "") && ($start != "")) {
            $this->db->limit($limit, $start);
        }

        $this->db->select('wli.*,wl.name as location_name');
        $this->db->join('work_location_assignment as wla', 'wli.assignment_id = wla.assignment_id');
        $this->db->join('work_locations as wl', 'wla.work_location_id = wl.work_location_id');
        $this->db->from('work_location_inout as wli');
        $this->db->where('wli.employee_id', $employee_id);
        $this->db->order_by('wli.inout_id', 'DESC');
        $res = $this->db->get()->result_array();
        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }

    public function leave_history_list($employee_id = "", $from_date = "", $to_date = "")
    {

        $where = " 1=1 ";
        if ($employee_id != "") {
            $where .= " and elr.employee_id = '" . $employee_id . "' ";
        }
        if ($from_date != "" && $to_date != "") {
            $where .= " and (YEAR(elr.leave_date) = '" . $from_date . "' OR YEAR(elr.leave_date) = '" . $to_date . "' OR YEAR(elr.rejoin_date) = '" . $from_date . "' OR YEAR(elr.rejoin_date) = '" . $to_date . "') ";
        }

        $qry2 = "SELECT elr.leave_date,elr.rejoin_date,elr.total_days,elr.paid,elr.unpaid,elr.remarks,lt.title,e.name,e.code,e.profile_photo from employee_leave_rejoin as elr left join leave_application as la on (elr.leave_application_id = la.leave_application_id) left join leave_types as lt on (la.leave_type_id = lt.leave_type_id) left join employee as e on (elr.employee_id=e.employee_id) where " . $where . " order by elr.leave_rejoin_id DESC";
        $tleaves = $this->db->query($qry2, array($from_date, $to_date))->result_array();
        //echo $this->db->last_query();
        return $tleaves;
    }

    public function leave_annual_list($employee_id)
    {

        $qry2 = "SELECT eld.* from employee_leave_details as eld where eld.employee_id = ? order by eld.leave_details_id DESC";
        $tleaves = $this->db->query($qry2, array($employee_id))->result_array();
        //echo $this->db->last_query();
        return $tleaves;
    }

    public function asset_report($employee_id = "", $asset_id = "")
    {

        $where = " 1=1 ";
        if (!empty($employee_id)) {
            $where .= " and aa.employee_id = " . $employee_id;
        }
        if (!empty($asset_id)) {
            $where .= " and aa.asset_id = " . $asset_id;
        }

        $qry = "select distinct(aa.employee_id),GROUP_CONCAT(aa.asset_id) as assets,e.code,e.name,e.email,e.phone_current,e.status,e.profile_photo,dep.name as department,des.name as designation from asset_assignment as aa left join employee as e on (aa.employee_id = e.employee_id )left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id) where " . $where . " group by aa.employee_id";
        $res = $this->db->query($qry)->result_array();
        if (!empty($res)) {

            foreach ($res as $key => $row) {

                if (!empty($row['assets'])) {

                    $ids = explode(",", $row['assets']);
                    if (!empty($ids)) {
                        foreach ($ids as $y => $x) {
                            //assignment id
                            $this->db->join('assets as a', 'aa.asset_id=a.asset_id');
                            $assets = $this->db->get_where('asset_assignment as aa', array('aa.employee_id' => $row['employee_id'], 'aa.asset_id' => $x))->row_array();

                            $res[$key]['details'][$y]['asset'] = $assets['name'];

                            $qry1 = "select asset_details_id,asset_assignment_id,title,description from asset_details where asset_assignment_id = ?";
                            $res1 = $this->db->query($qry1, $assets['asset_assignment_id'])->result_array();
                            $res[$key]['details'][$y]['specifications'] = $res1;
                        }
                    }
                }
            }
            return $res;
        } else {
            return false;
        }
    }

    public function delete_leave($leave_application_id)
    {

        $this->db->delete('leave_application', array('leave_application_id' => $leave_application_id));
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /* for updating last login date */
    public function updateLastLogin($employee_id)
    {

        $data = array(date('Y-m-d H:i:s'), $employee_id);
        $qry = "update employee set last_login = ? where employee_id = ?";
        $res = $this->db->query($qry, $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function notificationCount($user_id)
    {

        $param = array($user_id);
        $qry = "select COUNT(id) as 'other' from tbl_notification where user_id = ? and read_status = '0'";
        $res = $this->db->query($qry, $param)->row_array();

        if (!empty($res)) {
            return @$res;
        } else {
            return false;
        }
    }

    public function notificationsAll($user_id)
    {

        $param = array($user_id);
        $qry = "select * from tbl_notification where user_id = ? and read_status = '0' order by created_at DESC";
        $res = $this->db->query($qry, $param)->result_array();
        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }

    public function readNotifications($user_id, $notificationId = "")
    {

        if ($user_id != "") {

            if ($notificationId != "") {
                $where = " id = " . $notificationId;
            } else {
                $where = " 1=1  ";
            }
            $param = array($user_id);

            //$qry = "update tbl_notification set read_status = '1' where user_id = ? and ".$where;
            $qry = "delete from tbl_notification where user_id = ? and " . $where;

            $res = $this->db->query($qry, $param);
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function remove_msg_notification($user_id, $sender_id)
    {

        if (!empty($user_id) && !empty($sender_id)) {

            $param = array($sender_id, $user_id);
            $qry = "delete from tbl_notification where user_id = ? and from_user_id = ? and type = 'message'";
            $this->db->query($qry, $param);
        }
    }
}
