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

    .common-footer-padding {
        padding-bottom: 30px;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <? //= pr($resignation);
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
                            <p class="common font-weight-bold">
                                Mr. / Ms. <b><?= $resignation['employee'] ?></b>
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

                        <h5 class="mt-4 text-center common font-weight-bold">
                            Sub: Unauthorized absence from work
                        </h5>
                        <p class="common">
                            It is observed that you are absent from work without any
                            authorization from the competent authority, as per details below:
                        </p>
                        <p class="common text-center common">
                            <b>Absent from: <?= get_date($resignation['date']) ?> to <?= date('d-m-Y') ?></b>
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
        // mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'assets/common/css/main.css"">');
        // mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'assets/common/css/color_skins.css"">');
        // mywindow.document.write('<link rel="stylesheet" href="' + base_url + 'sets/user/css/custom.css"">');
        mywindow.document.write('<style>body {font-family: "Times New Roman", Georgia, serif;}.common {font-size: 18px;}.common-gap {gap: 10px;}.logo-img {width: 150px;padding-bottom: 100px;}.common-padding {padding-bottom: 50px;}.common-footer-padding {padding-bottom: 30px;}</style>');
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