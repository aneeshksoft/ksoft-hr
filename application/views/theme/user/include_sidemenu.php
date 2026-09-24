<?php
$session = $this->session->userdata();
?>
<div id="left-sidebar" class="sidebar">
    <div class="sidebar-scroll">
        <div class="user-account">
            <img src="<?= getUserImage($user['profile_photo']) ?>" class="rounded-circle user-photo" alt="User Profile Picture">
            <div class="dropdown">
                <span>Welcome,</span>
                <a href="javascript:void(0);" class="dropdown-toggle user-name" data-toggle="dropdown"><strong><?= $user['name'] ?></strong></a>
                <ul class="dropdown-menu dropdown-menu-right account animated flipInY">
                    <li><a href="<?= base_url() . 'employee/profile' ?>"><i class="icon-user"></i>My Profile</a></li>
                    <li class="divider"></li>
                    <li><a href="<?= base_url() . 'logout' ?>"><i class="icon-power"></i>Logout</a></li>
                </ul>
            </div>

            <hr>
            <div class="row">
                <div class="col-12">
                    <h6><?= $user['department'] ?></h6>
                    <small><?= $user['designation'] ?></small>
                </div>
            </div>
        </div>
        <!-- Nav tabs -->
        <?php $is_rm = $this->session->userdata('is_rm'); ?>
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#hr_menu">Main Menu</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content p-l-0 p-r-0">
            <div class="tab-pane animated fadeIn active" id="hr_menu">
                <nav class="sidebar-nav">
                    <ul class="main-menu metismenu">
                        <li <?= ($menu == 'dashboard') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'dashboard' ?>"><i class="icon-speedometer"></i><span>Dashboard</span></a></li>

                         <li <?= ($menu == 'probation') ? 'class="active"' : '' ?>>
                            <a href="#" class="has-arrow"><i class="icon-users"></i><span>Probation</span></a>
                            <ul>
                                <li <?= ($menu == "probation" && in_array($page_name, ['view_employees', 'probation_evaluation_form'])) ? 'class="active"' : '' ?>><a href="<?= base_url() . 'probation/view_employees' ?>">Employees List</a></li>
                            </ul>
                        </li>
                        
                        <li <?= ($menu == 'employee') ? 'class="active"' : '' ?>>
                            <a href="#Employees" class="has-arrow"><i class="icon-users"></i><span>Employees</span></a>
                            <ul>
                                <?php /* if (is_admin()) { ?>
                                    <li <?= ($page_name == 'employee_add') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'employee-add' ?>">Add New Employee</a></li>
                                    <li <?= ($page_name == 'employee_import') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'employee-import' ?>">Employee Bulk Import</a></li>
                                <?php } */ ?>

                                <?php if (is_admin() || !empty($is_rm)) { ?>
                                    <li <?= ($menu == "employee" && $page_name == 'view_employees') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'employee/view_employees' ?>">Employees List</a></li>
                                <?php } ?>
                                <?php if (is_admin()) { ?>
                                    <li <?= ($page_name == 'view_employee_history') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'employee/view_employee_history' ?>">Employee History</a></li>
                                <?php } ?>

                                <?php /* if (is_admin()) { ?>
                                    <li <?= ($page_name == 'assets_assignment') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'assets-assignment' ?>">Assets Assignment</a></li>
                                <?php } ?>
                                <?php if (is_admin()) { ?>
                                    <li <?= ($page_name == 'employee_assets') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'employee-assets' ?>">Assigned Assets</a></li>
                                <?php } */ ?>
                                <li <?= ($page_name == 'profile') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'employee/profile' ?>">My Profile</a></li>
                            </ul>
                        </li>

                        <?php /* if (is_admin()) { ?>
                            <li <?= ($menu == 'reports') ? 'class="active"' : '' ?>>
                                <a href="#Reports" class="has-arrow"><i class="icon-bar-chart"></i><span>Reports</span></a>
                                <ul>
                                 <li <?= ($page_name == 'employees_master') ? 'class="active"' : '' ?>><a href="<?= base_url() ?>employees-master">Employees Master</a></li>
                                    <li <?= ($page_name == 'assets_report') ? 'class="active"' : '' ?>><a href="<?= base_url() ?>assets-report">Asset Reports</a></li>
                                </ul>
                            </li>
                        <?php } */ ?>

                        <?php if (is_admin()) { ?>
                            <li <?= ($menu == 'admin') ? 'class="active"' : '' ?>>
                                <a href="#Admin" class="has-arrow"><i class="icon-lock"></i><span>Admin</span></a>
                                <ul>
                                    <li <?= ($page_name == 'department') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'department' ?>">Add/Edit Department</a></li>
                                    <li <?= ($page_name == 'designation') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'designation' ?>">Add/Edit Designation</a></li>
                                    <?php /* <li <?= ($page_name == 'role') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'role' ?>">Add/Edit Role</a></li> */ ?>

                                    <?php /* <li <?= ($page_name == 'view_work_locations') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'work_location' ?>">Add/Edit Work Location</a></li>
                                    */ ?>
                                    <?php /* 
                                    <li <?= ($page_name == 'permission') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'permission' ?>">Permission</a></li>
                                   */ ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php  if (1 == 1) { ?>
                            <li <?= ($menu == 'resignation') ? 'class="active"' : '' ?>>
                                <a href="#Resignation" class="has-arrow"><i class="icon-calculator"></i><span>Resignation</span></a>
                                <ul>
                                    <?php if (1 == 1) { ?>
                                        <li <?= ($page_name == 'add_resignation') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'add-resignation' ?>">Add Resignation</a></li>
                                    <?php } ?>

                                    <?php if (1 == 1) { ?>
                                        <li <?= ($page_name == 'view_resignation') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'view-resignation' ?>">View Resignations</a></li>
                                    <?php } ?>

                                    <?php if (!empty($is_rm)) { ?>
                                        <li <?= ($page_name == 'add_absconding_resignation') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'add-absconding-resignation' ?>">Add Absconding Resignation</a></li>
                                    <?php } ?>

                                    <?php if (is_admin() || !empty($is_rm)) { ?>
                                        <li <?= ($page_name == 'view_absconding_resignation') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'view-absconding-resignation' ?>">View Absconding Resignations</a></li>
                                    <?php } ?>

                                </ul>
                            </li>
                        <?php }  ?>
                        <?php if (is_admin()) { ?>
                            <li <?= ($menu == 'attendance') ? 'class="active"' : '' ?>>
                                <a href="#Attendance" class="has-arrow"><i class="icon-calculator"></i><span>Attendance</span></a>
                                <ul>

                                    <li <?= ($page_name == 'add_attendance') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'add-attendance' ?>">Add Attendance</a></li>

                                    <li <?= ($page_name == 'attendance_import') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'attendance-import' ?>">Bulk Import</a></li>

                                    <li <?= ($page_name == 'view_attendance') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'view-attendance' ?>">View Attendance</a></li>

                                </ul>
                            </li>
                        <?php  } ?>
                        <li <?= ($menu == 'leave') ? 'class="active"' : '' ?>>
                            <a href="#Leave" class="has-arrow"><i class="icon-calculator"></i><span>Leave</span></a>
                            <ul>
                                <?php if (is_admin()) { ?>
                                    <li <?= ($page_name == 'leave_type') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'leave-type' ?>">Leave Types</a></li>
                                    <li <?= ($page_name == 'available_leaves') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'available-leaves' ?>">Available Leaves</a></li>
                                    <li <?= ($page_name == 'leave_report') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'leave-report' ?>">Leave Report</a></li>
                                    <li <?= ($page_name == 'leave_report_detailed') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'leave-report-detailed' ?>">Leave History</a></li>
                                <?php } ?>

                                <li <?= ($page_name == 'leave_application') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'leave-application' ?>">Leave Application</a></li>

                                <li <?= ($page_name == 'leave_application_status') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'leave-application-status' ?>">My Leaves</a></li>

                                <?php if (is_in_headlist()) { ?>
                                    <li <?= ($page_name == 'leave_application_list') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'leave-application-list' ?>">Approve/Reject</a></li>
                                <?php } ?>

                                <?php if (is_admin()) { ?>
                                    <li <?= ($page_name == 'holiday_calendar') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'holiday-calendar' ?>">Holiday Calendar</a></li>
                                <?php } ?>

                                <?php if (is_admin()) { ?>
                                    <li <?= ($page_name == 'hr_leave_allocation') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'hr-leave-allocation' ?>">Comp Off</a></li>
                                <?php } ?>
                                <li <?= ($page_name == 'hr_leave_allocation_list') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'hr-leave-allocation-list' ?>">Comp Off List</a></li>

                                <?php if (is_admin()) { ?>
                                    <li <?= ($page_name == 'additional_leave') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'additional-leave' ?>">Additional Leave</a></li>
                                    <li <?= ($page_name == 'additional_leave_list') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'additional-leave-list' ?>">Additional Leave List</a></li>
                                <?php } ?>

                            </ul>
                        </li>
                        <?php if (is_admin()) { ?>
                            <li <?= ($menu == 'import') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'import' ?>"><i class="icon-arrow-down"></i><span>Import</span></a></li>
                        <?php } ?>

                        <?php  
            if ($session['code']=='K005') { ?>
            <li <?= ($menu == 'leave_management') ? 'class="active"' : '' ?>><a href="<?= base_url() . 'leave/leave_manage_admin' ?>"><i class="icon-arrow-down"></i><span class="text-danger">Leave Management</span></a></li>
            <?php } ?>

                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>