<?php

///////////// By Aneesh

defined('BASEPATH') or exit('No direct script access allowed');
class MY_Form_validation extends CI_Form_validation
{

    protected $CI;

    public function __construct($rules = array())
    {
        parent::__construct($rules);
    }

    // checks the value in db table is unique 
    // return false means validation failed.
    function unique_check($value, $params)
    {
        // throw new Exception($value);
        list($table, $field,$id_field) = array_pad(explode(".", $params),3,NULL);
        $id = $this->CI->input->post($id_field);

        $this->CI->db->select($field);
        $this->CI->db->from($table);
        $this->CI->db->where($field, $value);
        if (!empty($id_field) && !empty($id)) {
            $this->CI->db->where($id_field . '!=', $id);
        }
        $this->CI->db->limit(1);
        $query = $this->CI->db->get();
        $this->CI->form_validation->set_message('unique_check', 'The %s is already exist.');
        if ($query->num_rows() === 0) {
            return true;
        } else {
            return false;
        }
    }

    // checks the record exists in db table
    // return false means validation failed.
    function record_exist($value, $params)
    {

        list($table, $field) = explode(".", $params);

        $this->CI->db->select('*');
        $this->CI->db->from($table);
        $this->CI->db->where($field, $value);
        $this->CI->db->limit(1);
        $query = $this->CI->db->get();
        // echo $this->CI->db->last_query();exit;
        $this->CI->form_validation->set_message('record_exist', 'The %s does not exist.');
        if ($query->num_rows() === 0) {
            return false;
        } else {
            return true;
        }
    }

    function in_enum_list($value, $params){
        list($table, $field) = explode(".", $params);

        $query = " SHOW COLUMNS FROM `$table` LIKE '$field' ";
		$row = $this->CI->db->query(" SHOW COLUMNS FROM `$table` LIKE '$field' ")->row()->Type;
		$regex = "/'(.*?)'/";
		preg_match_all($regex, $row, $enum_array);
		$enum_fields = $enum_array[1];
        $this->CI->form_validation->set_message('in_enum_list', 'The %s does not exist.');
        if(in_array($value,$enum_fields)){
            return true;
        }else{
            return false;
        }
		return ($enum_fields);
    }

    
}

