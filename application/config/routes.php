<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'common/signin';

$route['signin'] = 'common/signin';
$route['forgot'] = 'common/forgot';
$route['forgotVerify'] = 'common/forgotVerify';
$route['change'] = 'common/change';

$route['logout'] = 'common/logout';

$route['dashboard'] = 'user/dashboard';
$route['employees-list'] = 'user/employees_list';
$route['employees-list/(:any)'] = 'user/employees_list/$1';
$route['employees-master'] = 'user/employees_master';
$route['employee-add'] = 'user/employee_add';
$route['employee-import'] = 'user/employee_import';
$route['employee-edit/(:any)'] = 'user/employee_edit/$1';
$route['employee-additional/(:any)'] = 'user/employee_additional/$1';
//$route['employee-edit-additional/(:any)'] = 'user/employee_edit_additional/$1';
$route['profile'] = 'user/profile';
$route['profile/(:any)'] = 'user/profile/$1';

//$route['leave-application'] = 'user/leave_application';
//$route['leave-application-list'] = 'user/leave_application_list';
//$route['leave-application-status'] = 'user/leave_application_status';
$route['reporting-heads'] = 'user/reporting_heads';
$route['assets-assignment'] = 'user/assets_assignment';
$route['employee-assets'] = 'user/employee_assets';
$route['work-location-assignment'] = 'user/work_location_assignment';
$route['assigned-work-locations'] = 'user/assigned_work_locations';
$route['generate-letter'] = 'user/generate_letter';
$route['print-letters'] = 'user/print_letters';
$route['print-letters/(:any)'] = 'user/print_letter_page/$1';
$route['imbursement-create'] = 'user/imbursement_create';
$route['imbursement-list'] = 'user/imbursement_application_list';
$route['imbursement-status'] = 'user/imbursement_application_status';
$route['rejoin'] = 'user/rejoin';
$route['leave-report'] = 'user/leave_report';
$route['passport-status-report'] = 'user/passport_status_report';
$route['assets-report'] = 'user/assets_report';
$route['imbursement-report'] = 'user/imbursement_report';
$route['print-imbursement/(:any)'] = 'user/print_imbursement_page/$1';
$route['imbursement-edit/(:any)'] = 'user/imbursement_edit/$1';
$route['print-leave-application/(:any)'] = 'user/print_leave_page/$1';

$route['department'] = 'admin/department';
$route['designation'] = 'admin/designation';
$route['role'] = 'admin/role';
//$route['leave-type'] = 'admin/leave_type';
$route['leave-approval-order'] = 'admin/leave_approval_order';
$route['imbursement-approval-order'] = 'admin/imbursement_approval_order';
$route['work-location'] = 'admin/work_location';
$route['permission'] = 'admin/permission';

//resignation

$route['add-resignation'] = 'resignation/add_resignation';
$route['view-resignation'] = 'resignation/view_resignation';
$route['view-resignation-details/(:num)'] = 'resignation/view_resignation_details/$1';
$route['add-absconding-resignation'] = 'resignation/add_absconding_resignation';
$route['view-absconding-resignation'] = 'resignation/view_absconding_resignation';
$route['view-absconding-resignation-details/(:num)'] = 'resignation/view_absconding_resignation_details/$1';
$route['delete-resignation'] = 'resignation/delete_resignation';
$route['edit-resignation/(:num)'] = 'resignation/edit_resignation/$1';  
$route['exit-interview/(:num)'] = 'resignation/add_exit_interview_details/$1';
$route['view-exit-interview/(:num)'] = 'resignation/view_exit_interview/$1';
$route['clearance-certificate/(:num)'] = 'resignation/add_clearance_certificate/$1';
$route['view-clearance-certificate/(:num)'] = 'resignation/view_clearance_certificate/$1';
$route['trigger-exit-interview'] = 'resignation/trigger_exit_interview';
$route['trigger-exit-clearance'] = 'resignation/trigger_clearance_certificate';
$route['trigger-absconding-reminder'] = 'resignation/trigger_absconding_reminder';

$route['trigger-probation-confirmation-email'] = 'resignation/trigger_probation_confirmation_email';

//attendance
$route['add-attendance']    = 'attendance/add_attendance';
$route['view-attendance']   = 'attendance/view_attendance';
$route['attendance-import'] = 'attendance/attendance_import';

//leave
$route['leave-type'] = 'leave/leave_type';
$route['leave-application'] = 'leave/leave_application';
$route['leave-approval-order'] = 'leave/leave_approval_order';
$route['leave-application-list'] = 'leave/leave_application_list';
$route['leave-application-status'] = 'leave/leave_application_status';
$route['holiday-calendar'] = 'leave/holiday_calendar';
$route['hr-leave-allocation'] = 'leave/hr_leave_allocation';
$route['hr-leave-allocation-list'] = 'leave/hr_leave_allocation_list';
$route['available-leaves'] = 'leave/available_leaves';
$route['additional-leave'] = 'leave/additional_leave';
$route['additional-leave-list'] = 'leave/additional_leave_list';
$route['leave-report'] = 'leave/leave_report';
$route['leave-report-detailed'] = 'leave/leave_report_detailed';

$route['autologin'] = 'common/autologin';
$route['set-cookie'] = 'common/set_cookie';

$route['404_override'] = 'common/notfound';
$route['translate_uri_dashes'] = FALSE;
$route['import-employees'] = 'Cron/import_employees';