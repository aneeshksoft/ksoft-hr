<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
<?php
$user_docs = base_url('assets/uploads/user_docs/candidates_docs/' . $employee['employee_id'] . '/');

?>
<div class="row">
    <div class="col-sm-12">

        <div class="card">
            <div class="card-header bg-white border-bottom-0">
                <?php $this->load->view("employee/nav_tab", array('_employee_id' => $employee['employee_id'])); ?>
            </div>
            <div class="card-body">
                <form action="" name="edit_employee" id="edit_employee" method="POST" enctype="multipart/form-data">
                    <? //= pr($employee);
                    ?>

                    <div class="row">


                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Basic<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm amount" name="basic" value="<?= $employee['basic'] ?>" onkeyup="calcsalary()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>VDA<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm amount" name="vda" value="<?= $employee['vda'] ?>" onkeyup="calcsalary()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>HRA<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm amount" name="hra" value="<?= $employee['hra'] ?>" onkeyup="calcsalary()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Night Shift Allowance<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm amount" name="nightshift_allowance" value="<?= $employee['nightshift_allowance'] ?>" onkeyup="calcsalary()">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>WFH Allowance<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm amount" name="wfh_allowance" value="<?= $employee['wfh_allowance'] ?>" onkeyup="calcsalary()">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Flexi Benefits Allowance Plan<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm amount" name="flexi_allowance" value="<?= $employee['flexi_allowance'] ?>" onkeyup="calcsalary()">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Variable Performance Bonus<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm amount" name="variable_performance_bonus" value="<?= $employee['variable_performance_bonus'] ?>" onkeyup="calcsalary()">
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Frequency of payment for VPB<sup>*</sup></label>
                                <select class="form-control form-control-sm" name="payment_frequency_vpb">
                                    <option value="">Select</option>
                                    <?php if ($payment_frequency_vpb) : ?>
                                        <?php foreach ($payment_frequency_vpb as $value) : ?>
                                            <option value="<?= $value; ?>" <?= (($value == $employee['payment_frequency_vpb']) ? 'selected' : ''); ?>><?= ucfirst($value); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Employer PF<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm amount" name="employer_pf" value="<?= $employee['employer_pf'] ?>" onkeyup="calcsalary()">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Employer ESI<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm amount" name="employer_esi" value="<?= $employee['employer_esi'] ?>" onkeyup="calcsalary()">
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Guaranteed Bonus/Retention Bonus(Rs)<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm amount" name="annual_retention_bonus" id="annual_retention_bonus" onkeyup="calcsalary()" value="<?= $employee['annual_retention_bonus'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Advanced Retention Bonus(Rs)<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm amount" name="advanced_retension_bonus" id="advanced_retension_bonus" onkeyup="calcsalary()" value="<?= $employee['advanced_retension_bonus'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Months eligible for Retention Bonus<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm" name="months_eligible_bonus" id="months_eligible_bonus" value="<?= $employee['months_eligible_bonus'] ?>">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Due date for Payment of Retention bonus</label>
                                <input type="text" class="form-control form-control-sm" name="retention_bonus_duedate" id="retention_bonus_duedate" data-date-format="dd-M-yyyy" data-date-autoclose="true" value="<?= get_date($employee['retention_bonus_duedate']) ?>">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Employer Gratuity<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm amount" name="employer_gratuity" value="<?= $employee['employer_gratuity'] ?>" onkeyup="calcsalary()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Statutory Bonus<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm amount" name="statutory_bonus" value="<?= $employee['statutory_bonus'] ?>" onkeyup="calcsalary()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Medical Insurance<sup>*</sup></label>
                                <input type="text" class="form-control form-control-sm amount" name="medical_insurance" value="<?= $employee['medical_insurance'] ?>" onkeyup="calcsalary()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Effective Date of the Salary</label>
                                <input type="text" class="form-control form-control-sm" name="effective_date_of_salary" id="effective_date_of_salary" value="<?= get_date($employee['effective_date_of_salary']) ?>">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Gross</label>
                                <input type="text" class="form-control form-control-sm amount" name="gross" value="<?= $employee['gross'] ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Monthly CTC</label>
                                <input type="text" class="form-control form-control-sm amount" name="monthly_ctc" value="<?= $employee['monthly_ctc'] ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Annual CTC</label>
                                <input type="text" class="form-control form-control-sm amount" name="annual_ctc" value="<?= $employee['annual_ctc'] ?>" readonly>
                            </div>
                        </div>

                        <div class="col-sm-12 text-right">
                            <div class="mt-4">
                                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

<!-- stage 1 form script -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            todayHighlight: true,
            autoclose: true
        });
        $('#retention_bonus_duedate').datepicker({
            todayHighlight: true,
            autoclose: true,
            format: 'dd-mm-yyyy',
            startView: "years",
            startDate: new Date()
        });
        $('#effective_date_of_salary').datepicker({
            todayHighlight: true,
            autoclose: true,
            format: 'dd-mm-yyyy',
            startView: "years",
        });


        $("#edit_employee").validate({
            // ignore: ':hidden:not(#skill_id)',
            rules: {
                basic: {
                    required: true,
                },
                vda: {
                    required: true,
                },
                hra: {
                    required: true,
                },
                nightshift_allowance: {
                    required: true,
                },
                wfh_allowance: {
                    required: true,
                },
                flexi_allowance: {
                    required: true,
                },
                variable_performance_bonus: {
                    required: true,
                },
                payment_frequency_vpb: {
                    required: true,
                },
                employer_pf: {
                    required: true,
                },
                employer_esi: {
                    required: true,
                },
                employer_gratuity: {
                    required: true,
                },
                annual_retention_bonus: {
                    required: true,
                },
                months_eligible_bonus: {
                    required: true,
                },
                /*    retention_bonus_duedate: {
                     required: true,
                 },*/
                statutory_bonus: {
                    required: true,
                },
                medical_insurance: {
                    required: true,
                },
                effective_date_of_salary: {
                    required: true,
                },



            },
            // messages: {

            // },
            errorPlacement: function(error, element) {
                if (element.hasClass('select2') && element.next('.select2-container').length) {
                    error.insertAfter(element.next('.select2-container')).addClass('d-block');
                } else {
                    element.closest('.form-group').append(error);
                }
            },
            submitHandler: function(form, e) {
                e.preventDefault();
                $(form).find(':submit').prop('disabled', true).text('Processing...')
                var form_data = new FormData(form);
                form_data.append('employee_id', '<?= $employee['employee_id'] ?>');
                // console.log(form_data)
                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url('employee/edit_employee_salary_and_other_post') ?>',
                        cache: false,
                        async: false,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            var obj = $.parseJSON(response);
                            if (obj.status == 1) {
                                toaster('success', obj.msg);
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                toaster('error', obj.msg);
                                $(form).find(':submit').prop('disabled', false).text('Update');
                            }

                        },
                        error: function(error) {
                            toaster('error', error);
                            $(form).find(':submit').prop('disabled', false).text('Update');
                        }
                    });

                }, 500);
                return false;
            }

        });

    });

    function calcsalary() {

        var basic = $('input[name="basic"]').val();
        var vda = $('input[name="vda"]').val();
        var hra = $('input[name="hra"]').val();
        var nightshift_allowance = $('input[name="nightshift_allowance"]').val();
        var wfh_allowance = $('input[name="wfh_allowance"]').val();
        var flexi_allowance = $('input[name="flexi_allowance"]').val();
        var variable_performance_bonus = $('input[name="variable_performance_bonus"]').val();
        var employer_pf = $('input[name="employer_pf"]').val();
        var employer_esi = $('input[name="employer_esi"]').val();
        var employer_gratuity = $('input[name="employer_gratuity"]').val();
        var statutory_bonus = $('input[name="statutory_bonus"]').val();
        var medical_insurance = $('input[name="medical_insurance"]').val();
        var months_eligible_bonus = $('input[name="months_eligible_bonus"]').val();
        var annual_retention_bonus = $('input[name="annual_retention_bonus"]').val();
        var advanced_retension_bonus = $('input[name="advanced_retension_bonus"]').val();

        console.log(annual_retention_bonus + "annual_retention_bonus");
        var gross = 0;
        var monthly_ctc = 0;
        var annual_ctc = 0;

        gross = Number(basic) + Number(vda) + Number(hra) + Number(nightshift_allowance) + Number(wfh_allowance) + Number(flexi_allowance) + Number(variable_performance_bonus) + Number(months_eligible_bonus) +
            Number(advanced_retension_bonus);
        if (gross == '' || gross == null || gross === 'undefined' || isNaN(gross)) {
            gross = 0;
        }

        monthly_ctc = Number(gross) + Number(employer_pf) + Number(employer_esi) + Number(employer_gratuity) + Number(statutory_bonus) + Number(medical_insurance) + Number(annual_retention_bonus);
        //monthly_ctc = Number(basic)+Number(vda)+Number(hra)+Number(flexi_allowance)+Number(variable_performance_bonus)+Number(months_eligible_bonus);
        if (monthly_ctc == '' || monthly_ctc == null || monthly_ctc === 'undefined' || isNaN(monthly_ctc)) {
            monthly_ctc = 0;
        }
        //console.log(monthly_ctc+"===monthly_ctc");
        annual_ctc = Number(monthly_ctc) * 12;


        $('input[name="gross"]').val(gross.toFixed(2));
        $('input[name="monthly_ctc"]').val(monthly_ctc.toFixed(2));
        $('input[name="annual_ctc"]').val(annual_ctc.toFixed(2));

    }
</script>