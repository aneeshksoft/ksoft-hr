<ul class="nav nav-tabs nav-fill align-items-center">
    <li class="nav-item">
        <? //=pr($page_name);
        ?>
        <a class="nav-link <?= ($page_name == 'edit_employee') ? 'active' : '' ?>" href="<?= base_url('employee/edit_employee/' . $_employee_id) ?>">General</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($page_name == 'edit_employee_educational_history') ? 'active' : '' ?>" href="<?= base_url('employee/edit_employee_educational_history/' . $_employee_id) ?>">Educational History</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($page_name == 'edit_employee_certification_history') ? 'active' : '' ?>" href="<?= base_url('employee/edit_employee_certification_history/' . $_employee_id) ?>">Certification History</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($page_name == 'edit_employee_employment_history') ? 'active' : '' ?> " href="<?= base_url('employee/edit_employee_employment_history/' . $_employee_id) ?>">Employment History</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($page_name == 'edit_employee_family_details') ? 'active' : '' ?>" href="<?= base_url('employee/edit_employee_family_details/' . $_employee_id) ?>">Family Details</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= ($page_name == 'edit_employee_salary_and_other') ? 'active' : '' ?>" href="<?= base_url('employee/edit_employee_salary_and_other/' . $_employee_id) ?>">Salary & Other</a>
    </li>
</ul>