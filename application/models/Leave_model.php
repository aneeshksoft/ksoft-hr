<?php

class Leave_model extends CI_Model
{
    //calculate monthly PL and SL updation cron. this will run on every 1st day of month
    public function updateLeaveCron($employee_id = "")
    {
        //echo 'employee '.$employee_id.'<br>';
        //get employee status first
        $empstatus = "select status,joining_date from employee where employee_id = ? and status != 'resigned' and status != 'noticeperiod'";
        $empstatusres = $this->db->query($empstatus, array($employee_id))->row_array();

        //pr($empstatusres);

        if(!empty($empstatusres) && !empty($empstatusres['joining_date'])) {

            $ret['date_of_join'] = $empstatusres['joining_date'];
            $ret['employee_id'] = $employee_id;

            //for live get current date
            $curDate = date('Y-m-d');
            //fortesting
            //$curDate = date('2027-01-01');

            //register new entry of current month when cron run
            $year = date('Y', strtotime($curDate));
            $month = date('m', strtotime($curDate));
            $newStartDate = date('Y-m-01', strtotime("$year-$month-01"));
            $newEndDate = date('Y-m-t', strtotime("$year-$month-01"));

            //check for previous month entry and get carry fwd
            $qry1 =  "select * from employee_leave_details where employee_id = ? order by leave_details_id DESC limit 1";
            $prevMonthData = $this->db->query($qry1, array($employee_id))->row_array();

            if(empty($prevMonthData)) {
                //set carry fwd 0 if no previous entry
                $pl_carry_fwd = 0;
                $co_carry_fwd = 0;
                $sl_carry_fwd = 0;
            } else {
                //set carry fwd 0 if previous entry
                //get carry fwd limit of PL
                //previous month balance carry fwd to next based on the limit
                $cflimit = $this->common_model->selectOne('leave_types', array('leave_type_id' => 1), 'carry_fwd_limit');
                if($prevMonthData['pl_balance'] < 0) {               
                    $pl_carry_fwd = $prevMonthData['pl_balance'];
                } else {
                    $pl_carry_fwd = ($prevMonthData['pl_balance'] >= $cflimit['carry_fwd_limit']) ? $cflimit['carry_fwd_limit'] : $prevMonthData['pl_balance'];
                }

                //co carry fwd
                $co_carry_fwd = $prevMonthData['co'];
                $sl_carry_fwd = $prevMonthData['sl_balance'];
            }

            $currentMonth = date('m', strtotime($curDate));
            $isDecember = ($currentMonth == '12');
            $isJanuary = ($currentMonth == '01');

            //if december need to add 2 PL and 1 SL else 1PL and 1SL only if user join before july
            // if($isDecember) {
            //     $currentMonth = date('m', strtotime($curDate));
            //     $currentYear = date('Y', strtotime($curDate));
            //     $givenMonth = date('m', strtotime($ret['date_of_join']));
            //     $givenYear = date('Y', strtotime($ret['date_of_join']));
            //     if (($givenYear < $currentYear) || ($givenYear == $currentYear && $givenMonth < 7)) {
            //         //yes user joined before july
            //         $sl = 1;
            //         $pl = 1;
            //     } else {
            //         //user joined after current july
            //         $sl = 1;
            //         $pl = 1;
            //     }
            // } else {
            //     $sl = 1;
            //     $pl = 1;
            // }

            //new logic implemented in january 2026 like no monthly leave addtion, instead set total annual leave on january
            if($isJanuary) {
                $sl = 4;
                $pl = 12;
                $sl_carry_fwd = 0;
                $pl_carry_fwd = 0;
            } else {
                $sl = 0;
                $pl = 0;
            }

            //echo $pl."-".$sl."-".$pl_carry_fwd."-".$sl_carry_fwd;
            //exit;
        
            //if there is additional leave compansate the same by allocating 0 to pl or sl
            // if user have 3 additional leave, for the 3 coming months the pl or sl will be 0 and from 4th month the normal calculation continue. this normal calculation will restart on every new year also

            //pr($prevMonthData);
            //check whether its new year entry

            // $isJanuary = ($currentMonth == '01');
            // if($isJanuary) {
            //     $sl_carry_fwd = 0;
            // }

            // $pl_additional = $prevMonthData['pl_additional_leave_counter'] ?? 0;
            // $sl_additional = $prevMonthData['sl_additional_leave_counter'] ?? 0;

            // if($pl_additional > 0) {
            //     $pl_additional = $pl_additional - $pl;
            //     $pl = 0;
            // }
            // if($sl_additional > 0) {
            //     $sl_additional = $sl_additional - $sl;
            //     $sl = 0;
            // }

            /* } else {
                 //if january reset the additional leave counter to 0
                 $pl_additional = 0;
                 $sl_additional = 0;
             }*/

            //no need of additional leave logic as we are adding all available leaves in a year on january
            $pl_additional = 0;
            $sl_additional = 0;

            //ksoft specific
            //in a year only 4 SL for entire year. so we are calculating total sl alloted in the current year, if its 4 then no need to add additional SL
            // $year = date('Y', strtotime($curDate));
            // $firstDate = date('Y-m-01', strtotime($year."-01-01"));
            // $lastDate = date('Y-m-t', strtotime($year."-12-31"));

            // $qry2 = "SELECT IFNULL(sum(sl),0) as totsl FROM `employee_leave_details` where start_date>=? and start_date<=? and employee_id = ?";
            // $res2 = $this->db->query($qry2, array($firstDate,$lastDate,$employee_id))->row_array();
            // //pr($res2);
            // if(!empty($res2)) {
            //     if($res2['totsl'] >= 4) {
            //         $sl = 0;
            //     }
            // }
            //end soft specific

            $param  = array();
            $param['employee_id']  = $employee_id;
            $param['start_date']   = $newStartDate;
            $param['end_date']     = $newEndDate;
            $param['sl']           = $sl;
            $param['pl']           = $pl;
            $param['pl_carry_fwd'] = $pl_carry_fwd;
            $param['pl_total']     = ($pl + $pl_carry_fwd);
            $param['pl_balance']   = ($pl + $pl_carry_fwd); //default on first setting pl_used will be zero
            $param['sl_balance']   = ($sl + $sl_carry_fwd); //default on first setting sl_used will be zero
            $param['pl_additional_leave_counter']   = $pl_additional;
            $param['sl_additional_leave_counter']   = $sl_additional;
            $param['co']     = ($co_carry_fwd);
            $param['last_updated'] = date('Y-m-d H:i:s');

            if(!empty($param)) {

                //check already added current month data
                $checkExist = "select * from employee_leave_details where employee_id = ? and start_date = ? and end_date = ? ";
                $resp = $this->db->query($checkExist, array($employee_id,$newStartDate,$newEndDate))->row_array();

                //add entry only if no previous entry for current month
                if(empty($resp)) {
                    $leave_details_id = $this->common_model->insert($param, 'employee_leave_details');
                    if(!empty($leave_details_id)) {
                        $ret['start_date'] = $newStartDate;
                        $ret['end_date']   = $newEndDate;
                        $ret['leave_details_id'] = $leave_details_id;
                    }
                }
            }

            //need to do the leave deduction based on the leaves taken will go here

            return $ret;

        } else {
            return false;
        }
    }

    public function get_employee($data = "")
    {
        $result = array();
        $sql = "select employee_id, name from employee where status != 'resigned' and status != 'noticeperiod' and (name like '%".$data."%' OR code like '%".$data."%')";
        $res = $this->db->query($sql)->result_array();

        if(!empty($res)) {

            foreach($res as $key => $row) {
                $result[$key]['id'] = $row['employee_id'];
                $result[$key]['text'] = $row['name'];
            }

        }

        return $result;
    }

    public function get_employee_by_head($data = "", $head_id = "")
    {
        $result = array();
        $sql = "select employee_id, name from employee where status != 'resigned' and status != 'noticeperiod' and (name like '%".$data."%' OR code like '%".$data."%') and (reporting_manager_id = ? || employee_id = ?)";
        $res = $this->db->query($sql, array($head_id,$head_id))->result_array();

        if(!empty($res)) {

            foreach($res as $key => $row) {
                $result[$key]['id'] = $row['employee_id'];
                $result[$key]['text'] = $row['name'];
            }

        }

        return $result;
    }

    public function checkLeaveAvailability($employee_id = "")
    {

        $curDate  = date('Y-m-d');
        //for testing
        //$curDate = date('2026-01-01');

        $year = date('Y', strtotime($curDate));
        $month = date('m', strtotime($curDate));
        $newStartDate = date('Y-m-01', strtotime("$year-$month-01"));
        $newEndDate = date('Y-m-t', strtotime("$year-$month-01"));

        $aperyear = "select * from employee_leave_details where employee_id = ? and start_date = ? and end_date = ? order by leave_details_id DESC limit 1";

        $res = $this->db->query($aperyear, array($employee_id,$newStartDate,$newEndDate))->row_array();

        if(!empty($res)) {

            $ret['employee_id']        = $res['employee_id'];
            $ret['month_start']        = $res['start_date'];
            $ret['month_end']          = $res['end_date'];
            $ret['leave_details_id']   = $res['leave_details_id'];
            $ret['pl_balance']         = $res['pl_balance'];
            $ret['sl_balance']         = $res['sl_balance'];
            $ret['pl_used']            = $res['pl_used'];
            $ret['sl_used']            = $res['sl_used'];
            $ret['sl']                 = $res['sl'];
            $ret['pl']                 = $res['pl'];
            $ret['pl_carry_fwd']       = $res['pl_carry_fwd'];
            $ret['pl_hr_allotted']     = $res['pl_hr_allotted'];
            $ret['sl_hr_allotted']     = $res['sl_hr_allotted'];
            $ret['pl_total']           = $res['pl_total'];
            $ret['pl_additional_leave_counter']           = $res['pl_additional_leave_counter'];
            $ret['sl_additional_leave_counter']           = $res['sl_additional_leave_counter'];
            $ret['co']                 = $res['co'];

            return $ret;

        } else {
            return array();
        }
    }

    //this function for preventing apply additional leave if employee applied already applied a leave.
    public function checkCanApplyOrNot($employee_id = "")
    {

        $curDate  = date('Y-m-d');
        //for testing
        //$curDate = date('2026-01-01');

        $year = date('Y', strtotime($curDate));
        $month = date('m', strtotime($curDate));
        $newStartDate = date('Y-m-01', strtotime("$year-$month-01"));
        $newEndDate = date('Y-m-t', strtotime("$year-$month-01"));

        $aperyear = "select * from employee_leave_details where employee_id = ? and start_date = ? and end_date = ? order by leave_details_id DESC limit 1";

        $res = $this->db->query($aperyear, array($employee_id, $newStartDate, $newEndDate))->row_array();

        //get employee pending leaves PL,SL, CO
        $pqry = "SELECT COALESCE(SUM(CASE WHEN leave_type_id = 1 THEN (CASE WHEN is_halfday = '1' THEN 0.5 ELSE 1 END) ELSE 0 END), 0) AS pl_pending, COALESCE(SUM(CASE WHEN leave_type_id = 3 THEN (CASE WHEN is_halfday = '1' THEN 0.5 ELSE 1 END) ELSE 0 END), 0) AS sl_pending, COALESCE(SUM(CASE WHEN leave_type_id = 4 THEN (CASE WHEN is_halfday = '1' THEN 0.5 ELSE 1 END) ELSE 0 END), 0) AS co_pending FROM leave_application WHERE status = 'pending' AND cancel_status = '0' AND employee_id = ?";

        $pres = $this->db->query($pqry, $employee_id)->row_array();

        if (!empty($res)) {

            $ret['employee_id']        = $res['employee_id'];
            $ret['month_start']        = $res['start_date'];
            $ret['month_end']          = $res['end_date'];
            $ret['leave_details_id']   = $res['leave_details_id'];
            $ret['pl_balance']         = (($res['pl_balance'] - $pres['pl_pending']) < 0) ? 0 : ($res['pl_balance'] - $pres['pl_pending']);
            $ret['sl_balance']         = (($res['sl_balance'] - $pres['sl_pending']) < 0) ? 0 : ($res['sl_balance'] - $pres['sl_pending']);
            $ret['co']                 = (($res['co'] - $pres['co_pending']) < 0) ? 0 : ($res['co'] - $pres['co_pending']);

            //pr($res);
            //pr($ret);
            //pr($pres);

            return $ret;
        } else {
            return array();
        }
    }

    //calculate actual leave dates from the applied dates
    public function get_leave_days($employee_id, $leave_type_id, $from_date, $to_date, $is_halfday)
    {

        $totdays = 0;
        $totdates = array();
        $prevgroup_id = "";
        $is_sandwich_applied = '0';

        $from_date  = set_date($from_date);
        $to_date    = set_date($to_date);

        //get holidays in the current year
        $holiday_from_date = date('Y-m-d', strtotime("$from_date -3 week"));
        $holiday_to_date = date('Y-m-d', strtotime("$to_date +3 week")); 

        $holidays_qry = "select holiday_date from holidays where holiday_date between '" . $holiday_from_date . "' and '" . $holiday_to_date . "'";
        $holidays_resp = $this->db->query($holidays_qry)->result_array();
        $ignore_dates = array();

        if(!empty($holidays_resp)) {
            $ignore_dates = array_map(function ($item) { return $item['holiday_date']; }, $holidays_resp);
        }

        //pr($ignore_dates);

        $leave_type_details = $this->common_model->selectOne('leave_types', array('leave_type_id' => $leave_type_id), '*');

        //check previous days are saturday and sunday or holiday marked untill a working day reached or leave application reached - after that set starting date as this - if leave is sandwich
        //sandwich calculation fir from date. no sandwich for half day
        $isWorkingDay = false;
        $temp_from_date = date('Y-m-d', strtotime("-1 day", strtotime($from_date)));
        while (!$isWorkingDay && $leave_type_details['is_sandwich'] == 1 && $is_halfday == 0) {

            $dayOfWeek = date('N', strtotime($temp_from_date));
            if($dayOfWeek < 7 &&  !in_array($temp_from_date, $ignore_dates)) {
                $isWorkingDay = true;
                //check if user applied same type leave on this day
                $prev_leave = $this->common_model->selectOne('leave_application', array('employee_id'=>$employee_id,'leave_date' => $temp_from_date,'cancel_status' => '0','leave_type_id' => $leave_type_id,'is_halfday' => '0'), 'leave_application_id,group_id,status');

                if(!empty($prev_leave)) {
                    $from_date = date('Y-m-d', strtotime("+1 day", strtotime($temp_from_date)));
                    $is_sandwich_applied = '1';
                    if($prev_leave['status'] == 'pending') {
                        $prevgroup_id = $prev_leave['group_id'];
                    }
                }

            } else {
                $temp_from_date = date('Y-m-d', strtotime("-1 day", strtotime($temp_from_date)));
            }
        }
        //sandwich calculation for from date
        //sandwich calculation to  date . no sandwich for half day
        $isWorkingDay1 = false;
        $temp_to_date = date('Y-m-d', strtotime("+1 day", strtotime($to_date)));
        while (!$isWorkingDay1 && $leave_type_details['is_sandwich'] == 1 && $is_halfday == 0) {

            $dayOfWeek = date('N', strtotime($temp_to_date));
            if($dayOfWeek < 7 &&  !in_array($temp_to_date, $ignore_dates)) {
                $isWorkingDay1 = true;
                //check if user applied same type leave on this day
                $next_leave = $this->common_model->selectOne('leave_application', array('employee_id'=>$employee_id,'leave_date' => $temp_to_date,'cancel_status' => '0','leave_type_id' => $leave_type_id,'is_halfday' => '0'), 'leave_application_id,group_id,status');

                if(!empty($next_leave)) {
                    $to_date = date('Y-m-d', strtotime("-1 day", strtotime($temp_to_date)));
                    $is_sandwich_applied = '1';
                    if($next_leave['status'] == 'pending') {
                        $prevgroup_id = $next_leave['group_id'];
                    }
                }

            } else {
                $temp_to_date = date('Y-m-d', strtotime("+1 day", strtotime($temp_to_date)));
            }
        }
        //sandwich calculation to  date

        //echo 'frm'.$from_date;
        //echo 'to'.$to_date;

        $datetime1  = new DateTime(date('Y-m-d H:i:s', strtotime($from_date.' 00:00:00')));
        $datetime2  = new DateTime(date('Y-m-d H:i:s', strtotime($to_date.' 23:59:59')));

        $interval   = new DateInterval('P1D');
        $daterange  = new DatePeriod($datetime1, $interval, $datetime2);

        $weekdays = 0;
        $incrementor = ($is_halfday == 1) ? 0.5 : 1;
        foreach ($daterange as $date) {

            $formatted_date = $date->format('Y-m-d');

            // Monday to Saturday (1 to 6) and ignore public holidays
            //in case of SL sandwich leave applied -> eg: if user applied from friday to saturday, leave calculated as 4 days saturday and sunday not ignored

            if($leave_type_details['is_sandwich'] == 1) {
                //is sandwich leave
                $weekdays += $incrementor;
                $totdates[] = $formatted_date;
            } else {
                if($date->format('N') < 7 && !in_array($formatted_date, $ignore_dates)) {
                    $weekdays += $incrementor;
                    $totdates[] = $formatted_date;
                }
            }

        }

        $totdays = $weekdays;

        //get leave availability from monthly auto updated leave table
        $eligible_temp = false;
        $eligibility = $this->checkLeaveAvailability($employee_id);
        //pr($eligibility);
        if(!empty($eligibility)) {
            $eligible_temp = true;
        }

        $eligibility['applied_days']     = $totdays;
        $eligibility['leave_type_id']    = $leave_type_id;
        $eligibility['employee_id']      = $employee_id;
        $eligibility['leave_start_date'] = get_date($from_date);
        $eligibility['leave_end_date']   = get_date($to_date);
        $eligibility['all_dates']        = $totdates;
        $eligibility['prevgroup_id']     = $prevgroup_id;
        $eligibility['is_sandwich_applied'] = $is_sandwich_applied;

        $gender = $this->common_model->selectOne('employee', array('employee_id' => $employee_id), 'gender');
        //pr($gender);
        if(!empty($gender)) {
            if($gender['gender'] == 'male' && $leave_type_id == 2) {
                $eligible_temp = false;
            }
        }

        //check PL and SL leave type eligibility 0-not eligible, 1 eligible
        if($eligible_temp && !empty($eligibility) && $from_date != "" && $to_date != "") {
            if($leave_type_id == 1) {
                $eligibility['status'] = ($totdays > $eligibility['pl_balance']) ? 0 : 1;
            } elseif($leave_type_id == 3) {
                $eligibility['status'] = ($totdays > $eligibility['sl_balance']) ? 0 : 1;
            } elseif($leave_type_id == 4) {
                $eligibility['status'] = ($totdays > $eligibility['co']) ? 0 : 1;
            } elseif($leave_type_id == 2) {
                $eligibility['status'] = ($totdays > 180) ? 0 : 1;
            } else {
                $eligibility['status'] = 1;
            }
        } else {
            //not eligible
            $eligibility['status'] = 0;
        }

        return $eligibility;

    }

    //to check whether there is an approval order created for employee
    public function pending_head_assignment($employee_id)
    {
        $qry = "select * from leave_approval_order where employee_id = ?";
        $res = $this->db->query($qry, $employee_id)->row_array();
        return $res;
    }

    //to check whether the current person has any leave approval permission of the current employee
    public function check_in_heads_list($employee_id, $session_id)
    {

        $roles = $this->session->userdata('type');
        $role_array = array();
        if($roles != "") {
            $role_array = explode(",", $roles);
        }

        $qry = "select * from leave_approval_order where employee_id = ? and head_id = ?";
        $res = $this->db->query($qry, array($employee_id,$session_id))->row_array();

        if(!empty($res) || in_array('1', $role_array)) {
            return true;
        } else {
            return false;
        }

    }

    public function get_base_head($employee_id)
    {
        $qry = "SELECT ao.role_id,ao.position,ao.head_id FROM `leave_approval_order` as ao where ao.employee_id = ? and ao.position = (select min(position) from leave_approval_order where employee_id = ?)";
        $res = $this->db->query($qry, array($employee_id,$employee_id))->row_array();
        return $res;
    }

    public function get_top_head($employee_id)
    {
        $qry = "SELECT ao.role_id,ao.position,ao.head_id FROM `leave_approval_order` as ao where ao.employee_id = ? and ao.position = (select max(position) from leave_approval_order where employee_id = ?)";
        $res = $this->db->query($qry, array($employee_id,$employee_id))->row_array();
        return $res;
    }
    public function get_next_head($employee_id, $position)
    {
        $qry = "SELECT ao.role_id,ao.position,ao.head_id FROM `leave_approval_order` as ao where ao.employee_id = ? and ao.position = (SELECT position FROM leave_approval_order where position > ? and employee_id = ? ORDER BY position limit 1)";
        $res = $this->db->query($qry, array($employee_id,$position,$employee_id))->row_array();
        return $res;
    }

    public function leave_application_status($employee_id)
    {

        $where = " 1=1 ";
        //if(!check_role_permission(1)) {
        $where .= " and l.employee_id = ? ";
        //}

        $qry = "select DISTINCT(l.group_id),GROUP_CONCAT(l.leave_date) as from_to,SUM(CASE WHEN l.cancel_status = '1' THEN 1 ELSE 0 END) AS cancelled_count, SUM(CASE WHEN l.cancel_status = '0' THEN 1 ELSE 0 END) AS active_count,SUM(CASE WHEN l.is_halfday = '1' AND l.cancel_status = '0' THEN 0.5 WHEN l.is_halfday = '0' AND l.cancel_status = '0' THEN 1 ELSE 0 END) AS total_count,l.*,lt.title,e.code,e.name,e.email,dep.name as department,des.name as designation from leave_application as l left join employee as e on (l.employee_id = e.employee_id) left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id) left join leave_types as lt on (l.leave_type_id = lt.leave_type_id) where ".$where." group by l.group_id order by l.leave_application_id DESC";

        $res = $this->db->query($qry, $employee_id)->result_array();

        // echo $this->db->last_query();
        if(!empty($res)) {
            return $res;
        } else {
            return false;
        }

    }

    public function leave_by_id($group_id)
    {

        $qry = "select la.employee_id,la.group_id,la.leave_type_id,la.status,la.status_changed,la.is_halfday,la.fn_an,SUM(CASE WHEN la.is_halfday = '1' AND la.cancel_status = '0' THEN 0.5 WHEN la.is_halfday = '0' AND la.cancel_status = '0' THEN 1 ELSE 0 END) AS total_count,la.remarks,lt.title,e.name,d.name as designation,e.phone_office,e.phone_current,e.phone_home from leave_application as la left join leave_types as lt on (la.leave_type_id = lt.leave_type_id) left join employee as e on (la.employee_id = e.employee_id) left join designations as d on (e.designation_id = d.designation_id) where la.group_id = ? limit 1";

        $res = $this->db->query($qry, $group_id)->row_array();
        if(!empty($res)) {

            $qry2 = "select l.leave_application_id,l.leave_date,l.cancel_status,l.is_sandwich_applied from leave_application as l where l.group_id = ?";

            $res2 = $this->db->query($qry2, $res['group_id'])->result_array();

            $res['leave_split'] = $res2;

            $qry3 = "select l.leave_application_id,l.leave_date,l.cancel_status,l.is_sandwich_applied from leave_application as l where l.group_id = ? and l.cancel_status='0'";

            $res3 = $this->db->query($qry3, $res['group_id'])->result_array();

            $res['active_leaves'] = $res3;

            $qry4 = "select l.leave_application_id,l.leave_date,l.cancel_status,l.is_sandwich_applied from leave_application as l where l.group_id = ? and l.cancel_status='1'";

            $res4 = $this->db->query($qry4, $res['group_id'])->result_array();

            $res['cancelled_leaves'] = $res4;

            $qry1 = "select las.group_id,las.leave_application_status_id,e.name,r.name as role,las.position,las.status,las.status_changed,las.remarks from leave_application_status as las left join employee as e on (las.head_id = e.employee_id) left join roles as r on(e.role_id = r.role_id) where las.group_id = ? order by position ASC";
            $res1 = $this->db->query($qry1, $res['group_id'])->result_array();

            $res['detailed_status'] = $res1;

            return $res;
        } else {
            return false;
        }

    }

    public function leave_application_list($employee_id, $status)
    {

        $where = " 1=1 ";
        if($status != 'all') {
            $where .= " and las.status ='".$status."' ";
        }
        $qry = "select GROUP_CONCAT(l.leave_date) as from_to,SUM(CASE WHEN l.cancel_status = '1' THEN 1 ELSE 0 END) AS cancelled_count, SUM(CASE WHEN l.cancel_status = '0' THEN 1 ELSE 0 END) AS active_count,l.is_halfday,l.fn_an,SUM(CASE WHEN l.is_halfday = '1' AND l.cancel_status = '0' THEN 0.5 WHEN l.is_halfday = '0' AND l.cancel_status = '0' THEN 1 ELSE 0 END) AS total_count,l.remarks as leave_reason,las.*,lt.title,lt.is_sandwich,e.code,e.name,e.email,dep.name as department,des.name as designation from leave_application_status as las left join leave_application as l on (las.group_id = l.group_id) left join employee as e on (l.employee_id = e.employee_id) left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id) left join leave_types as lt on (l.leave_type_id = lt.leave_type_id) where ".$where." and las.head_id = ? group by l.group_id order by las.leave_application_status_id DESC";

        $res = $this->db->query($qry, $employee_id)->result_array();

        // echo $this->db->last_query();
        if(!empty($res)) {
            return $res;
        } else {
            return false;
        }

    }

    //for approval process, get current head leave status which is latest status
    public function leave_status_by_head($head_id, $group_id)
    {
        $qry = "select las.*,r.name as role from leave_application_status as las left join employee as e on (las.head_id=e.employee_id) left join roles as r on(e.role_id=r.role_id)  where las.head_id = ? and las.group_id = ? order by las.leave_application_status_id DESC limit 1";
        $res = $this->db->query($qry, array($head_id,$group_id))->row_array();
        return $res;
    }

    public function get_holidays()
    {

        $this->db->select('title,holiday_date as start,id,color');
        $res = $this->db->get('holidays')->result_array();
        return $res;

    }

    public function holiday_delete($id)
    {

        $exist = $this->db->get_where('holidays', array('id' => $id))->row_array();
        if(!empty($exist)) {

            $this->db->delete('holidays', array('id' => $exist['id']));
            if($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }

    }

    public function hr_leave_allocation_list($employee_id)
    {

        $where = ' 1=1 ';
        if(!is_admin()) {
            $where .= ' and la.employee_id = '.$employee_id;
        }

        $qry = "select la.*, e.name,e.code,d.name as designation,e.phone_office,e.phone_current,e.phone_home from hr_leave_allocation_history as la left join employee as e on (la.employee_id = e.employee_id) left join departments as dep on (e.department_id = dep.department_id) left join designations as d on (e.designation_id = d.designation_id) where ".$where." order by id DESC";
        $res = $this->db->query($qry)->result_array();
        return $res;

    }

    public function additional_leave_list($employee_id)
    {

        $qry = "select la.*, e.name,e.code,d.name as designation,e.phone_office,e.phone_current,e.phone_home,lt.title as leave_type from additional_leave_history as la left join employee as e on (la.employee_id = e.employee_id) left join departments as dep on (e.department_id = dep.department_id) left join designations as d on (e.designation_id = d.designation_id) left join leave_types as lt on (la.leave_type_id = lt.leave_type_id) order by id DESC";
        $res = $this->db->query($qry)->result_array();
        return $res;

    }

    public function leave_allotment_delete($id)
    {

        $this->db->delete('hr_leave_allocation_history', array('id' => $id));
        if($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function additional_leave_delete($id)
    {

        $this->db->delete('additional_leave_history', array('id' => $id));
        if($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }

    }

    public function available_leaves($newStartDate, $newEndDate)
    {

        //check for previous month entry and get carry fwd
        $checkExist = "select eld.*,e.name,e.code,e.email from employee_leave_details as eld left join employee as e on (eld.employee_id = e.employee_id) where eld.start_date = ? and eld.end_date = ? ";
        $resp = $this->db->query($checkExist, array($newStartDate,$newEndDate))->result_array();
        return $resp;

    }

    //to check there is rm assigned for the employee

    public function rm_assigned_check($employee_id)
    {

        $qry = "select rm.reporting_manager_id as head_1,rmrm.reporting_manager_id as head_2 from employee as rm left join employee as rmrm on (rm.reporting_manager_id=rmrm.employee_id) where rm.employee_id = ?";
        $res = $this->db->query($qry, $employee_id)->row_array();
        return $res;
    }

    //check logged in user is RM of the employee
    public function check_in_rm_list($employee_id, $session_id)
    {
        $qry = "select * from employee where employee_id = ? and reporting_manager_id = ?";
        $res = $this->db->query($qry, array($employee_id,$session_id))->row_array();
        return $res;
    }

    public function get_special_head()
    {
        $res = $this->db->select('head_id as head_1')->get_where('additional_leave_approve_head')->row_array();
        return $res;
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

    public function leave_report_list($employee_id, $from_date, $to_date)
    {

        $from_date  = set_date($from_date);
        $to_date    = set_date($to_date);

        $qry1 = "select * from leave_types";
        $ltypes = $this->db->query($qry1)->result_array();

        $where = " 1=1 ";
        if(!empty($employee_id)) {
            $where .= ' and employee_id = '.$employee_id;
        }

        $emps = "select employee_id, name, code from employee where ".$where." and status != 'resigned' order by name ASC";
        $empsres = $this->db->query($emps)->result_array();

        if(!empty($empsres) && !empty($ltypes)) {
            foreach($empsres as $key => $row) {

                $leavearray = array();
                foreach($ltypes as $row1) {

                    $qry = "select IFNULL(SUM(CASE WHEN l.is_halfday = '1' AND l.cancel_status = '0' AND l.status='approved' THEN 0.5 WHEN l.is_halfday = '0' AND l.cancel_status = '0' AND l.status='approved' THEN 1 ELSE 0 END),0) AS total_count from leave_application as l where l.leave_type_id = ? and l.employee_id = ? and l.leave_date between '".$from_date."' and '".$to_date."'";

                    $res = $this->db->query($qry, array($row1['leave_type_id'],$row['employee_id']))->row_array();

                    $param = array();
                    $param['leave_type_id'] = $row1['leave_type_id'];
                    $param['leave_type'] = $row1['title'];
                    $param['total_leaves'] = $res['total_count'];

                    if(!empty($res)) {
                        array_push($leavearray, $param);
                    }

                }

                $empsres[$key]['from_date'] = $from_date;
                $empsres[$key]['to_date'] = $to_date;
                $empsres[$key]['leave_history'] = $leavearray;

            }
        }

        return $empsres;

    }

    public function leave_report_detailed_list($employee_id, $from_date, $to_date,$status)
    {

        $where = " 1=1 ";
        if(!empty($employee_id)) {
            $where .= " and la.employee_id = ".$employee_id;
        }

        if(!empty($from_date) && !empty($to_date)) {

            $from_date  = set_date($from_date);
            $to_date    = set_date($to_date);

            $where .= " and la.leave_date between '".$from_date."' and '".$to_date."' ";
        }
        if(!empty($status) && $status != 'all') {
            $where .= " and la.status = '".$status."' ";
        }

        $qry = "select la.leave_date,la.remarks,la.is_halfday,la.fn_an,e.code,e.name,lt.title,la.status from leave_application as la left join employee as e on (e.employee_id=la.employee_id) left join leave_types as lt on (lt.leave_type_id = la.leave_type_id) where ".$where." and la.cancel_status = ? order by la.leave_date ASC";

        $res = $this->db->query($qry, array('0'))->result_array();

        return $res;
    }
    public function leave_application_list_admin()
    {

        $qry = "select l.group_id,GROUP_CONCAT(l.leave_date) as from_to,SUM(CASE WHEN l.cancel_status = '1' THEN 1 ELSE 0 END) AS cancelled_count, SUM(CASE WHEN l.cancel_status = '0' THEN 1 ELSE 0 END) AS active_count,l.is_halfday,l.fn_an,SUM(CASE WHEN l.is_halfday = '1' AND l.cancel_status = '0' THEN 0.5 WHEN l.is_halfday = '0' AND l.cancel_status = '0' THEN 1 ELSE 0 END) AS total_count,lt.title,lt.is_sandwich,e.code,e.name,e.email,dep.name as department,des.name as designation,l.created_at,l.remarks,l.status,l.status_changed from leave_application as l left join employee as e on (l.employee_id = e.employee_id) left join departments as dep on (e.department_id = dep.department_id) left join designations as des on (e.designation_id = des.designation_id) left join leave_types as lt on (l.leave_type_id = lt.leave_type_id) where (l.status ='approved' OR l.status ='pending') and l.cancel_status = '0' group by l.group_id order by l.leave_application_id DESC";

        $res = $this->db->query($qry)->result_array();

        if (!empty($res)) {
            return $res;
        } else {
            return false;
        }
    }
}
