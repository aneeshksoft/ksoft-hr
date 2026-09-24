<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings {

   protected $CI;

   function __construct() {

     $this->CI =& get_instance();
     $this->CI->ci_minifier->init('html,css,js');
     $this->CI->ci_minifier->set_domparser(2);

   }
      
   public function getDate($original_date = "") {
      if($original_date == "" || empty($original_date)) {
          $new_date = NULL;
      } else {
          $timestamp = strtotime($original_date);
          $new_date = date("d-M-Y", $timestamp);
      }
      return $new_date;
  }

   function get_settings($title) {

		$res = $this->CI->db->get_where('settings',array('title'=>$title))->row_array();
		return @$res['value'];

	}

   function get_userdata($employee_id) {

		$this->CI->db->select('e.employee_id,e.code,e.name,e.email,e.profile_photo,e.last_login,e.status,dep.name as department,des.name as designation');
      $this->CI->db->join('departments as dep','e.department_id = dep.department_id','left');
		$this->CI->db->join('designations as des','e.designation_id = des.designation_id','left');
		$res = $this->CI->db->get_where('employee as e',array('e.employee_id' => $employee_id))->row_array();

		if(!empty($res))
		 return $res;
		else
		 return false;

	}
	public function getPermissionbyType($type='')
	{
		$qry5 = "(SELECT u.*,ut.userType,m.module FROM userPermission as u left join usertype as ut on (u.typeId = ut.typeId) left join modules as m on (u.moduleId = m.moduleId) where u.typeId = ?) ";
		$res = $this->CI->db->query($qry5,$type)->result_array();
		if(!empty($res)){
			return $res;
		}else{
			return false;
		}
	}
	

   //cut sentance
   function substrwords($text, $maxchar, $end='...') {
    if (strlen($text) > $maxchar || $text == '') {
        $words = preg_split('/\s/', $text);
        $output = '';
        $i      = 0;
        while (1) {
            $length = strlen($output)+strlen($words[$i]);
            if ($length > $maxchar) {
                break;
            }
            else {
                $output .= " " . $words[$i];
                ++$i;
            }
        }
        $output .= $end;
    }
    else {
        $output = $text;
    }
    return $output;
  }
  function getUserNames($userId) {

    $this->CI->db->select('firstName,lastName,occupation');
    $res = $this->CI->db->get_where('userProfile',array('userId' => $userId))->row_array();

    if(!empty($res))
     return $res;
    else
     return false;

  }

  public function getgroup($groupId='')
  {
    // $groupId='1';
    $this->CI->db->select('groupName,groupId,createdBy');
    $res = $this->CI->db->get_where('messageGroups',array('groupId'=>$groupId))->row_array();
    // echo $this->db->last_query();
    // pr($res);
    if(!empty($res))
     return $res;
    else
     return false;
  }
}