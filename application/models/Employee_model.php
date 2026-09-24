<?php

class Employee_model extends CI_Model
{
    /* Start Aneesh */

    public function get_employees($select, array $where = [], array $statements = [])
    {

        $this->db->select($select);
        $this->db->from('employee e');
        if (!empty($statements['join']) && is_string($statements['join'])) {
            $tables = explode(',', $statements['join']);
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
            if (in_array('sub_dep', $tables)) {
                $this->db->join('subdepartment sub_dep', 'sub_dep.id =e.sub_department_id', 'left');
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

    public function get_education_history($select, array $where = [], array $statements = [])
    {

        $this->db->select($select);
        $this->db->from('education_history eh');
        if (!empty($statements['join']) && is_string($statements['join'])) {
            $tables = explode(',', $statements['join']);
            if (in_array('e', $tables)) {
                $this->db->join('employee e', 'e.employee_id=eh.candidate_id', 'left');
            }
            if (in_array('courses', $tables)) {
                $this->db->join('courses', 'courses.course_id=eh.course_id', 'left');
            }
            if (in_array('country', $tables)) {
                $this->db->join('country', 'country.country_id=eh.country_id', 'left');
            }
            if (in_array('state', $tables)) {
                $this->db->join('state', 'state.state_id=eh.state_id', 'left');
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

    public function get_certification_history($select, array $where = [], array $statements = [])
    {

        $this->db->select($select);
        $this->db->from('certification_history ch');
        if (!empty($statements['join']) && is_string($statements['join'])) {
            $tables = explode(',', $statements['join']);
            if (in_array('e', $tables)) {
                $this->db->join('employee e', 'e.employee_id=ch.candidate_id', 'left');
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

    public function get_employment_history($select, array $where = [], array $statements = [])
    {

        $this->db->select($select);
        $this->db->from('employment_history eh');
        if (!empty($statements['join']) && is_string($statements['join'])) {
            $tables = explode(',', $statements['join']);
            if (in_array('e', $tables)) {
                $this->db->join('employee e', 'e.employee_id=eh.candidate_id', 'left');
            }
            if (in_array('country', $tables)) {
                $this->db->join('country', 'country.country_id=eh.country_id', 'left');
            }
            if (in_array('state', $tables)) {
                $this->db->join('state', 'state.state_id=eh.state_id', 'left');
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

    public function get_family_details($select, array $where = [], array $statements = [])
    {

        $this->db->select($select);
        $this->db->from('family_details fd');
        if (!empty($statements['join']) && is_string($statements['join'])) {
            $tables = explode(',', $statements['join']);
            if (in_array('e', $tables)) {
                $this->db->join('employee e', 'e.employee_id=fd.candidate_id', 'left');
            }
            if (in_array('o', $tables)) {
                $this->db->join('occupation o', 'o.occupation_id=fd.occupation_id', 'left');
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

    public function get_employee_history($select, array $where = [], array $statements = [])
    {

        $this->db->select($select);
        $this->db->from('employee_history eh');
        if (!empty($statements['join']) && is_string($statements['join'])) {
            $tables = explode(',', $statements['join']);
            if (in_array('e', $tables)) {
                $this->db->join('employee e', 'e.employee_id=eh.employee_id', 'left');
            }
            if (in_array('ub', $tables)) {
                $this->db->join('employee ub', 'ub.employee_id=eh.updated_by', 'left');
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

    public function get_probations($select, array $where = [], array $statements = [])
    {

        $this->db->select($select);
        $this->db->from('probation p');
        if (!empty($statements['join']) && is_string($statements['join'])) {
            $tables = explode(',', $statements['join']);
            if (in_array('des', $tables)) {
                $this->db->join('designations des', 'des.designation_id=e.designation_id', 'left');
            }
            if (in_array('dep', $tables)) {
                $this->db->join('departments dep', 'wp.id=cr.work_place_id', 'left');
            }
            if (in_array('pt', $tables)) {
                $this->db->join('mrf_primary_tool pt', 'pt.id=cr.primary_tool_id', 'left');
            }
            if (in_array('st', $tables)) {
                $this->db->join('mrf_secondary_tool st', 'st.id=cr.secondary_tool_id', 'left');
            }
            if (in_array('rrs', $tables)) {
                $this->db->join('mrf_resume_required_skill rrs', 'rrs.resume_id=cr.id', 'left');
            }
            if (in_array('sk', $tables)) {
                $this->db->join('mrf_skill sk', 'sk.id=rrs.skill_id', 'left');
            }
            if (in_array('u', $tables)) {
                $this->db->join('users u', 'u.user_id=cr.reffered_by', 'left');
            }
            if (in_array('mu', $tables)) {
                $this->db->join('users mu', 'mu.user_id=m.added_by', 'left');
            }
            if (in_array('t', $tables)) {
                $this->db->join('mrf_track t', 'cr.track_id=t.id', 'left');
            }
            if (in_array('min_edu', $tables)) {
                $this->db->join('mrf_minimum_educational_qualification_master min_edu', 'cr.minimum_educational_qualification_id=min_edu.id', 'left');
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

    public function get_probation_ratings($select, array $where = [], array $statements = [])
    {

        $this->db->select($select);
        $this->db->from('probation_ratings pr');
        if (!empty($statements['join']) && is_string($statements['join'])) {
            $tables = explode(',', $statements['join']);
            if (in_array('prp', $tables)) {
                $this->db->join('probation_rating_parameters prp', 'prp.id=pr.parameter_id', 'left');
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

    public function get_leave_approval_order($select, array $where = [], array $statements = [])
    {

        $this->db->select($select);
        $this->db->from('leave_approval_order ao');
        if (!empty($statements['join']) && is_string($statements['join'])) {
            $tables = explode(',', $statements['join']);
            if (in_array('e', $tables)) {
                $this->db->join('employee e', 'e.employee_id=ao.employee_id', 'left');
            }
            if (in_array('roles', $tables)) {
                $this->db->join('roles', 'roles.role_id=e.role_id', 'left');
            }
            if (in_array('h', $tables)) {
                $this->db->join('employee h', 'h.employee_id=ao.head_id', 'left');
            }
            if (in_array('hroles', $tables)) {
                $this->db->join('roles hroles', 'hroles.role_id=h.role_id', 'left');
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

    public function get_employee_roles($select, array $where = [], array $statements = [])
    {

        $this->db->select($select);
        $this->db->from('user_roles ur');
        if (!empty($statements['join']) && is_string($statements['join'])) {
            $tables = explode(',', $statements['join']);
            if (in_array('r', $tables)) {
                $this->db->join('roles r', 'ur.role_id=r.role_id', 'left');
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
    public function get_employee_life_cycle_docs($select, array $where = [], array $statements = [])
    {

        $this->db->select($select);
        $this->db->from('employee_life_cycle_docs elcd');
        if (!empty($statements['join']) && is_string($statements['join'])) {
            $tables = explode(',', $statements['join']);
            if (in_array('e', $tables)) {
                $this->db->join('employee e', 'e.employee_id=elcd.employee_id', 'left');
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

    /* End Aneesh */
    /* Start By Sudheesh */

    public function trigger_probation_confirmation_email()
    {
        //echo "dsjfhdjshf";
        $query = "SELECT * FROM employee WHERE CURDATE()>=DATE_SUB(confirmation_duedate, INTERVAL 7 DAY) and probation_status=1";
        $res = $this->db->query($query)->result();
        //echo $this->db->last_query();
        //echo json_encode($res);
        if (!empty($res)) {
            foreach ($res as $row) {
                //$this->db->update('resignation', array("status" => 17, "exit_interview_triggered" => 1), array("id" => $row->id));
                $employee_details = $this->common_model->get_employee_by_id($row->reporting_manager_id);
                $subject = "Probation Confirmation is Due";
                $body = "Probation confirmation of Mr/Ms ".$row->employee_id."(".$row->code.") is due on ".date('d-m-Y', strtotime($row->confirmation_duedate));

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
    /* Start By Sudheesh */
}
