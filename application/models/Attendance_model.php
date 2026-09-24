<?php
class Attendance_model extends CI_Model {

    function attendance_history_list($employee_id="",$from_date="",$to_date="") {
		
		$where = " 1=1 ";
		if($employee_id != "") {
			$where .= " and a.employee_id = '".$employee_id."' ";
		}
		if($from_date != "" && $to_date != "") {
			$where .= " and (DATE_FORMAT(a.attendance_date,'%d/%m/%Y') BETWEEN '".$from_date."' AND '".$to_date."') ";
		}
		
		$qry2 = "SELECT a.*,e.code,e.name from attendance as a left join employee as e on (a.employee_id = e.employee_id) where ".$where." order by a.attendance_date DESC";			
		$tleaves = $this->db->query($qry2)->result_array();		
		return $tleaves;
	}

}