<style>
    .card-body {
        font-family: "Times New Roman", Georgia, serif;
    }

    .common {
        font-size: 18px;
    }

    .common-gap {
        gap: 10px;
    }

    .logo-img {
        width: 150px;
        padding-bottom: 100px;
    }

    .common-padding {
        padding-bottom: 50px;
    }

    .common-padding1 {
        padding-bottom: 75px;
    }

    .common-footer-padding {
        padding-bottom: 40px;
    }

    .common-footer-padding1 {
        padding-bottom: 100px;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <? //= pr($probation); 
        ?>
        <? //= pr($probation_ratings); 
        ?>
        <div class="card">
            <div class="card-header bg-white">
                <div class="row">
                    <div class=" col-sm-12 text-right">
                        <button type="button" class="btn btn-success btn-sm" onclick="print()"><i class="fa fa-print"></i> Print</button>
                    </div>
                </div>
            </div>
            <div class="card-body" id="printArea">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row common-gap">
                            <div class="col-md-12">
                                <img src="<?= base_url('assets/common/letter_head.png') ?>" class="logo-img" />
                            </div>
                            <div class="col-md-12">
                                <div class="font-weight-bold common"><?= date('d-m-Y') ?></div>
                            </div>
                            <div class="col-md-12">
                                <p class="font-weight-bold common">
                                    HCM/ENV/<?= date('d-m-Y') ?>
                                </p>
                            </div>
                        </div>
                        <div class="common-padding">
                            <p class="py-3">To,</p>
                            <p class="common font-weight-bold">
                                Mr. / Ms. <b><<?= $resignation['employee'] ?></b>
                            </p>
                            <p>
                                <b><span class="common"><?= $resignation['per_address'] ?></span></b>
                            </p>
                            <p>
                                <b><span class="common"><?= $resignation['per_district'] ?></span></b>
                            </p>
                            <p>
                                <b><span class="common"><?= $resignation['per_state'] ?></span></b>
                            </p>
                            <p>
                                <b><span class="common"><?= $resignation['per_country'] ?></span></b>
                            </p>
                            <p>
                                <b><span class="common"><?= $resignation['per_pincode'] ?></span></b>
                            </p>
                        </div>
                        <p class="font-weight-bold common common-padding1">
                            Dear<span class="font-weight-normal"> <?= $resignation['employee'] ?> </span>,
                        </p>
                        <h5 class="mt-4 font-weight-bold common">
                            Sub: Release from the post of <?= $resignation['designation'] ?> 
                        </h5>
                        <p class="common mt-4">
                            We have accepted your resignation and relieve you of all the duties
                            and responsibilities vested in you with effect from <?= get_date($resignation['agreed_relieving_date']) ?>.
                        </p>
                        <p class="common mt-4">
                            We confirm that you have been working in our company from <?= get_date($resignation['joining_date']) ?>
                            to <?= get_date($resignation['agreed_relieving_date']) ?>.
                        </p>
                        <p class="common common-footer-padding ">
                            Settlement of all your dues will happen in due course and be
                            intimated to you at the earliest.
                        </p>

                        <p class="common-footer-padding1">
                            For <span class="font-weight-bold">Ksoft Technologies</span>
                        </p>
                        <p class="font-weight-bold">Authorized Signatory.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- stage 1 form script -->
<script type="text/javascript">
    $(document).ready(function() {});

    function print() {
        var content = $('#printArea').html();
        var mywindow = window.open('', 'Releaving Letter', 'height=800, width=1000');
        mywindow.document.write('<html><head><title>Releaving Letter</title>');
        /*optional stylesheet*/ //
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'assets/vendor/bootstrap/css/bootstrap.min.css"">');
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'assets/common/css/main.css"">');
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'assets/common/css/color_skins.css"">');
        mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'sets/user/css/custom.css"">');
        mywindow.document.write('<style>body {font-family: "Times New Roman", Georgia, serif;}.common {font-size: 18px;}.common-gap {gap: 10px;}.logo-img {width: 150px;padding-bottom: 100px;}.common-padding {padding-bottom: 50px;}.common-padding1 {padding-bottom: 75px;}.common-footer-padding {padding-bottom: 40px;}.common-footer-padding1 {padding-bottom: 100px;}</style>');
        mywindow.document.write('</head><body >');
        mywindow.document.write(content);
        mywindow.document.write('</body></html>');
        setTimeout(function() {
            mywindow.print();
        }, 2000);
        mywindow.onafterprint = function() {
            mywindow.close();
        }
        //  mywindow.print();
        // mywindow.close();

    }
</script>