<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('_sendMail')) {
    function _sendMail($subject, $body, $emailTo, $emailToName = "")
    {

        if (empty($emailTo)) {
            return false;
        }

        $message = '<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f6f6f6" align="center">
            <tbody>
                <tr>
                    <td valign="top" align="center">
                        <table style="max-width:400px;background:#ffffff" width="100%" border="0" align="center">
                            <tbody>
                                <tr>
                                    <td bgcolor="#ffffff">
                                        <table style="max-width:360px;background:#ffffff;font-family:Gotham,Helvetica Neue,Helvetica,Arial,sans-serif;font-size:12px" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                                            <tbody>
                                                <tr>
                                                    <td bgcolor="#ffffff" style="padding:20px 10px 10px 10px;" align="left"><a href="javascript:;"><img src="' . base_url() . 'assets/common/email-logo.png?id=1235" alt="Ksoft Technologies" title="Ksoft Technologies" style="border:none;display:block;color:#e00000;font-size:15px" width="60%"/></a></td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:20px 10px 10px 10px;font-family:Gotham,Helvetica Neue,Helvetica,Arial,sans-serif;font-size:16px;color:#282c3f" bgcolor="#ffffff" align="left"></td>
                                                </tr>
                                                <tr>
                                                    <td style="border:10px solid #ffffff;font-family:Gotham,Helvetica Neue,Helvetica,Arial,sans-serif;font-size:12px;color:#29303f" bgcolor="#ffffff" align="left">Hi,<br/><br/>
                                                        <div style="text-decoration:none;color:#29303f">' . $body . '</div>
                                                        <div style="border-bottom:1px solid #eaeaec">&nbsp;</div>
                                                        <div style="border-top:10px solid #ffffff;border-bottom:30px solid #ffffff">Regards,<br/>Ksoft Technologies Team</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>';

        //echo $message;
        //return true;
       
        require_once(APPPATH . 'third_party/phpmailer/PHPMailerAutoload.php');
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'email-smtp.ap-south-1.amazonaws.com';
        $mail->Port       = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth   = true;
        $mail->Username = 'AKIAQFRHKF4TLHY4FTFZ';
        $mail->Password = 'BBObZOgxSgFJEDjcrUjAXI5SsEbj0TzB3TqZ+PK4mwVM';
        $mail->SetFrom('noreply@ksofttechnologies.website', 'Ksoft Technologies');
        $mail->addAddress($emailTo, $emailToName);

        $mail->IsHTML(true);

        $mail->Subject = $subject;
        $mail->Body    = $message;

        //as temporary fix
        //return true;

        if (!$mail->send()) {
            //echo 'Message could not be sent.';
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        } else {
            //echo 'Message has been sent';
            return true;
        }
    }
}

if (!function_exists('send_absconding_mail')) {
    function send_absconding_mail($subject, $body, $emailTo, $emailToName , $resignation_date)
    {
        $today = date('d-m-Y');
        if (empty($emailTo)) {
            return false;
        }
        $body = '<p class="common">
        It is observed that you are absent from work without any
        authorization from the competent authority, as per details below:
        </p>
        <p class="common text-center common">
            <b>Absent from: ' . $resignation_date . ' to ' . $today . '</b>
        </p>
        <p class="common">
            You are hereby called upon to explain the reasons for your
            unauthorized absence; to enable us to take appropriate decision in
            the matter.
        </p>
        <p class="common">
            If no reply is received within 07 days of the issue of this letter,
            further action as deemed fit will be taken and the same will be
            binding on you.
        </p>
        <p class="mt-4 common">Yours faithfully,</p>
        <p class="font-weight-bold common-footer-padding">
            For Ksoft Technologies
        </p>';

        $message = '<table width="100%" cellspacing="0" cellpadding="0" border="0" bgcolor="#f6f6f6" align="center">
            <tbody>
                <tr>
                    <td valign="top" align="center">
                        <table style="max-width:400px;background:#ffffff" width="100%" border="0" align="center">
                            <tbody>
                                <tr>
                                    <td bgcolor="#ffffff">
                                        <table style="max-width:360px;background:#ffffff;font-family:Gotham,Helvetica Neue,Helvetica,Arial,sans-serif;font-size:12px" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
                                            <tbody>
                                                <tr>
                                                    <td bgcolor="#ffffff" style="padding:20px 10px 10px 10px;" align="left"><img src="' . base_url() . 'assets/common/email-logo.png" alt="Ksoft Technologies" title="Ksoft Technologies" style="border:none;display:block;color:#e00000;font-size:15px" width="35%"></td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:20px 10px 10px 10px;font-family:Gotham,Helvetica Neue,Helvetica,Arial,sans-serif;font-size:16px;color:#282c3f" bgcolor="#ffffff" align="left"></td>
                                                </tr>
                                                <tr>
                                                    <td style="border:10px solid #ffffff;font-family:Gotham,Helvetica Neue,Helvetica,Arial,sans-serif;font-size:12px;color:#29303f" bgcolor="#ffffff" align="left">Hi,<br/><br/>
                                                        <div style="text-decoration:none;color:#29303f">' . $body . '</div>
                                                        <div style="border-bottom:1px solid #eaeaec">&nbsp;</div>
                                                        <div style="border-top:10px solid #ffffff;border-bottom:30px solid #ffffff">Regards,<br/>Ksoft Technologies Team</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>';

        //echo $message;

        require_once(APPPATH . 'third_party/phpmailer/PHPMailerAutoload.php');
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'email-smtp.ap-south-1.amazonaws.com';
        $mail->Port       = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth   = true;
        $mail->Username = 'AKIAQFRHKF4TLHY4FTFZ';
        $mail->Password = 'BBObZOgxSgFJEDjcrUjAXI5SsEbj0TzB3TqZ+PK4mwVM';
        $mail->SetFrom('noreply@ksofttechnologies.live', 'Ksoft Technologies');
        $mail->addAddress($emailTo, $emailToName);

        $mail->IsHTML(true);

        $mail->Subject = $subject;
        $mail->Body    = $message;

        if (!$mail->send()) {
            //echo 'Message could not be sent.';
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        } else {
            //echo 'Message has been sent';
            return true;
        }
    }
}

//send mail to head when employee applied leave
function leaveApplicationEmail($employee_id, $hid, $group_id)
{

    //echo $group_id;
    // $ci = get_instance();

    // $emp = $ci->common_model->selectOne('employee', array('employee_id' => $employee_id), 'name,email');
    // $head = $ci->common_model->selectOne('employee', array('employee_id' => $hid), 'name,email');

    // $subject = 'Leave application';
    // $message = 'New leave application from ' . $emp['name'] . ' - ' . $emp['email'] . '.<br><br>Please <a href="' . base_url() . '" target="_blank">CLICK HERE</a> to login for more details.<br>';

    // $resp = _sendMail($subject, $message, $head['email'], $head['name']);
    // if ($resp) {
    //     return true;
    // } else {
    //     return false;
    // }

    $ci = get_instance();

    $emp = $ci->common_model->selectOne('employee', array('employee_id' => $employee_id), 'name,email');
    $head = $ci->common_model->selectOne('employee', array('employee_id' => $hid), 'name,email');
    $leave = $ci->leave_model->leave_by_id($group_id);
    //pr($leave);

    $subject = 'Leave Notification - ' . $emp['name'] . ' Applied for ' . @$leave['total_count'] . ' Day(s) of ' . $leave['title'] . ' Leave';

    $message = $emp['name'] . ' has applied for leave; details as below.<br><br>';

    $message .= 'Date(s): ' . get_date($leave['active_leaves'][0]['leave_date']) . ' to ' . get_date($leave['active_leaves'][count($leave['active_leaves']) - 1]['leave_date']) . '';

    $message .= '<br>Leave type: ' . $leave['title'];
    $message .= '<br>Half/Full Day: ' . is_halfday($leave['is_halfday']).($leave['is_halfday'] == 1 ? ' - '.$leave['fn_an'].'' : '');
    $message .= '<br>Comments: ' . $leave['remarks'];

    $message .= '<br><br>Please <a href="' . base_url('autologin?email='.$head['email']) . '" target="_blank">CLICK HERE</a> to approve or reject the leave.<br>';

    $resp = _sendMail($subject, $message, $head['email'], $head['name']);
    if ($resp) {
        return true;
    } else {
        return false;
    }

}

//approve leave email
function leaveApprovedEmail($employee_id,$hid, $group_id)
{

    // $ci = get_instance();
    // $emp = $ci->common_model->selectOne('employee', array('employee_id' => $employee_id), 'name,email');

    // $subject = 'Leave approved';
    // $message = 'Your leave application approved.<br><br>Please <a href="' . base_url() . '" target="_blank">CLICK HERE</a> to login for more details.<br>';

    // $resp = _sendMail($subject, $message, $emp['email'], $emp['name']);
    // if ($resp) {
    //     return true;
    // } else {
    //     return false;
    // }

    $ci = get_instance();
    $emp = $ci->common_model->selectOne('employee', array('employee_id' => $employee_id), 'name,email');
    $head = $ci->common_model->selectOne('employee', array('employee_id' => $hid), 'name,email');
    $leave = $ci->leave_model->leave_by_id($group_id);

    $subject = 'Leave Notification - ' . $head['name'] . ' has approved your leave request';
    
    $message = $head['name'] . ' has approved your leave; details as below.<br><br>';

    $message .= 'Date(s): ' . get_date($leave['active_leaves'][0]['leave_date']) . ' to ' . get_date($leave['active_leaves'][count($leave['active_leaves']) - 1]['leave_date']) . '';

    $message .= '<br>Leave type: ' . $leave['title'];
    $message .= '<br>Half/Full Day: ' . is_halfday($leave['is_halfday']).($leave['is_halfday'] == 1 ? ' - '.$leave['fn_an'].'' : '');
    $message .= '<br>Comments: ' . $leave['remarks'];

    $resp = _sendMail($subject, $message, $emp['email'], $emp['name']);
    if ($resp) {
        return true;
    } else {
        return false;
    }

}

//rejected leave email
function leaveRejectedEmail($employee_id,$hid, $group_id)
{

    // $ci = get_instance();
    // $emp = $ci->common_model->selectOne('employee', array('employee_id' => $employee_id), 'name,email');

    // $subject = 'Leave rejected';
    // $message = 'Your leave application rejected.<br><br>Please <a href="' . base_url() . '" target="_blank">CLICK HERE</a> to login for more details.<br>';

    // $resp = _sendMail($subject, $message, $emp['email'], $emp['name']);
    // if ($resp) {
    //     return true;
    // } else {
    //     return false;
    // }

    $ci = get_instance();
    $emp = $ci->common_model->selectOne('employee', array('employee_id' => $employee_id), 'name,email');
    $head = $ci->common_model->selectOne('employee', array('employee_id' => $hid), 'name,email');
    $leave = $ci->leave_model->leave_by_id($group_id);

    $subject = 'Leave Notification - ' . $head['name'] . ' has rejected your leave request';
    
    $message = $head['name'] . ' has rejected your leave request; details as below.<br><br>';

    $message .= 'Date(s): ' . get_date($leave['active_leaves'][0]['leave_date']) . ' to ' . get_date($leave['active_leaves'][count($leave['active_leaves']) - 1]['leave_date']) . '';

    $message .= '<br>Leave type: ' . $leave['title'];
    $message .= '<br>Half/Full Day: ' . is_halfday($leave['is_halfday']).($leave['is_halfday'] == 1 ? ' - '.$leave['fn_an'].'' : '');
    $message .= '<br>Comments: ' . $leave['remarks'];

    $resp = _sendMail($subject, $message, $emp['email'], $emp['name']);
    if ($resp) {
        return true;
    } else {
        return false;
    }
    
}

//leave cancel, mail to all heads
function leaveCancelEmail($employee_id, $group_id)
{

    $ci = get_instance();
    $emp = $ci->common_model->selectOne('employee', array('employee_id' => $employee_id), 'name,email');

    //get heads
    $qry = "select e.name,e.email from leave_approval_order as lao left join employee as e on (lao.head_id=e.employee_id) where lao.employee_id = ?";
    $resp = $ci->db->query($qry, $employee_id)->result_array();

    $i = 0;
    if (!empty($resp)) {
        foreach ($resp as $row) {

            $subject = 'Leave cancelled';
            $message = 'Employee ' . $emp['name'] . ' - ' . $emp['email'] . ' cancelled leave.<br><br>Please <a href="' . base_url() . '" target="_blank">CLICK HERE</a> to login for more details.<br>';
            $resp = @_sendMail($subject, $message, $row['email'], $row['name']);
            if ($resp) {
                $i++;
            }
        }
    }
    //return true if atleast 1 email delivered
    if ($i > 0) {
        return true;
    } else {
        return false;
    }
}

//compoff allocation
function leaveAlottedEmail($employee_id)
{
    $ci = get_instance();
    $emp = $ci->common_model->selectOne('employee', array('employee_id' => $employee_id), 'name,email');

    $subject = 'Leave allotted';
    $message = 'Leave allotted successfully.<br><br>Please <a href="' . base_url() . '" target="_blank">CLICK HERE</a> to login for more details.<br>';

    $resp = _sendMail($subject, $message, $emp['email'], $emp['name']);
    if ($resp) {
        return true;
    } else {
        return false;
    }
}

// aneesh start

function sendPassword($password, $emailTo, $emailToName = "")
{
    $subject = "Password";
    $message = "Your password is <b>" . $password . "</b>";
    // pr($emailTo);pr($emailToName);exit;
    $resp = _sendMail($subject, $message, $emailTo, $emailToName);
    return $resp;
}
//send mail to employee when employee is absent
function send_absent_mail($emailTo, $emailToName, $absentDates)
{

    $subject = 'Absent';
    $message = 'You have been absent from work on these days:<br>' . join(", ",$absentDates);

    $resp = _sendMail($subject, $message, $emailTo, $emailToName);
    if ($resp) {
        return true;
    } else {
        return false;
    }
}

// aneesh end

//added by sooraj
function employeeRegister_mail($email, $name, $password,$empcode)
{

    $subject = "Account Registered";
    $message = "Your account has been registered successfully.<p>Login <a href='https://ksoftcloud.com/ecos/hr/' target='_blank'><b>Click Here.</b></a></p><p>Your Employee code is <b>" . $empcode . "</b></p><p>Your temporary password is <b>" . $password . "</b></p>";

    $resp = _sendMail($subject, $message, $email, $name);
    if ($resp) {
        return true;
    } else {
        return false;
    }
}