<!doctype html>
<html lang="en">
  <head>
  	<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial scale=1.0">
		<title><?= $page_title.'-'.time()?></title>
		<link rel="icon" href="<?= base_url()?>favicon.ico" type="image/x-icon">
         <link rel="stylesheet" href="<?=base_url()?>assets/user/css/print.css" />
  	     <link rel="preconnect" href="https://fonts.gstatic.com">
         <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  </head>
  <body>
		<div class="header"><img src="<?=base_url('assets/common/lhead.png')?>" /></div>
		<div class="letter-content">
		<?php		  
		//	pr($details);
		?>
		<div style = "-webkit-box-decoration-break: clone">
		<table class="table">
			<thead>
				<tr>
					<th width="17%">Ref: <?= $details[0]['unique_code']?></th>
					<th colspan="2" class="text-center">REIMBURSEMENT OF EXPENSES</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><strong>DATE</strong></td>
					<td><strong>DETAILS</strong></td>
					<td width="15%"><strong>AMOUNT</strong></td>
				</tr>
				<?php $tot = 0; foreach($details as $row) {?>
				<tr>
					<td><?= get_date($row['imbursement_date'])?></td>
					<td><?= $row['details']?></td>
					<td><?= $currency.' '.round($row['amount'],2)?></td>
				</tr>
				<?php $tot+=round($row['amount'],2);}
				for($i=0;$i<=15;$i++) {
				?>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<?php } ?>
				<tr>
					<td><strong>Total</strong></td>
					<td></td>
					<td><strong><h3><?=$currency.' '.round($tot,2)?></h5></strong></td>
				</tr>
				<tr>
					<td colspan="3">Amount in words: <strong><?=AmountInWords($tot)?></strong></td>
				</tr>
			</tbody>
			</table>
		</div>
		<div style = "-webkit-box-decoration-break: clone">
			 <table class="table" style="margin-top:10px;">
				<thead>
					<tr>
						<th colspan="2">Project: </th>
						<th colspan="3" class="text-center">Management Approval</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td width="10%">Claimed By:</td>
						<td width="30%"></td>
						<td rowspan="2" width="20%" class="text-center"><strong>Department Head</strong></td>
						<td rowspan="2" width="20%" class="text-center"><strong>HRM</strong></td>
						<td rowspan="2" width="20%" class="text-center"><strong>Finance Department</strong></td>
					</tr>
					<tr>
						<td>Designation:</td>
						<td></td>
					</tr>
					<tr>
						<td>Empl. No.:</td>
						<td></td>
						<td rowspan="3"></td>
						<td rowspan="3"></td>
						<td rowspan="3"></td>
					</tr>
					<tr>
						<td>Signature:</td>
						<td></td>
					</tr>
					<tr>
						<td>Date:</td>
						<td></td>
					</tr>
				</tbody>
				</table>
		</div>
		</div>
		<div class="footer"><img src="<?=base_url('assets/common/lfoot.png')?>" /></div>
		<div class="text-center">
		<a href="javascript:;" class="dontprint btn btn-primary btn-lg" id="print-btn">Print in letterhead</a>
		<a href="javascript:;" class="dontprint btn btn-primary btn-lg" id="print-btn1">Print plain</a>
		</div>
  </body>
  </html>
<?php /*
<!DOCTYPE html>
<html>

<head>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
 <style>

.table td,
.table th {
	border: 1px solid #dddddd;
	text-align: left;
	padding: 8px;
}

.table {
	border-collapse: collapse;
	width: 90%;

	margin:auto;
}	
	.table { page-break-after:auto;
  page-break-before: always; }
	.page-header, .page-header-space {
  height: 216px;
}

.page-footer, .page-footer-space {
  height: 103px;

}

.page-footer {
  position: fixed;
  bottom: 0;
  width: 100%; 
}

.page-header {
  position: fixed;
  top: 0mm;
  width: 100%;
}

.page {
  page-break-after: always;
}

@page {
  margin: 20mm
}

.page-header img {
	width:100%;
	height:auto;
}
.page-footer img {
	width:100%;
	height:auto;
}

.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}
.btn {
    font-size: 14px;
}
.btn-lg {
    padding: .5rem 1rem;
    font-size: 1.25rem;
    line-height: 1.5;
    border-radius: .3rem;
	text-decoration:none;
	text-align:center;
}

.text-center {
	text-align:center;
	margin-bottom:3rem;
}

@media print {
   thead {display: table-header-group;} 
   tfoot {display: table-footer-group;}   
   button {display: none;}   
   body {margin: 0;padding:0}
	.dontprint {
		visibility: hidden;
	}
}
 </style>
</head>

<body>

  <div class="page-header" style="text-align: center">
   <img src="<?=base_url('assets/common/lhead.png')?>" />
    <br/>
    <button type="button" onClick="window.print()" style="background: pink">
      PRINT ME!
    </button>
  </div>

  <div class="page-footer">
    <img src="<?=base_url('assets/common/lfoot.png')?>" />
  </div>

  <table width="100%" border="0">

    <thead>
      <tr>
        <td>
          <!--place holder for the fixed-position header-->
          <div class="page-header-space"></div>
        </td>
      </tr>
    </thead>

    <tbody>
      <tr>
        <td>
          <!--*** CONTENT GOES HERE ***-->
          <div class="page">
            <table class="table" align="center">
							<thead>
								<tr>
									<th width="17%">Ref: <?= $details[0]['unique_code']?></th>
									<th colspan="2" class="text-center">REIMBURSEMENT OF EXPENSES</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><strong>DATE</strong></td>
									<td><strong>DETAILS</strong></td>
									<td width="15%"><strong>AMOUNT</strong></td>
								</tr>
								<?php $tot = 0; foreach($details as $row) {?>
								<tr>
									<td><?= get_date($row['imbursement_date'])?></td>
									<td><?= $row['details']?></td>
									<td><?= $currency.' '.round($row['amount'],2)?></td>
								</tr>
								<?php $tot+=round($row['amount'],2);}
								for($i=0;$i<=15;$i++) {
								?>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<?php } ?>
								<tr>
									<td><strong>Total</strong></td>
									<td></td>
									<td><strong><h3><?=$currency.' '.round($tot,2)?></h5></strong></td>
								</tr>
								<tr>
									<td colspan="3">Amount in words: <strong><?=AmountInWords($tot)?></strong></td>
								</tr>
							</tbody>
							</table>
						
						<table class="table" style="margin-top:10px;">
				<thead>
					<tr>
						<th colspan="2">Project: </th>
						<th colspan="3" class="text-center">Management Approval</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td width="10%">Claimed By:</td>
						<td width="30%"></td>
						<td rowspan="2" width="20%" class="text-center"><strong>Department Head</strong></td>
						<td rowspan="2" width="20%" class="text-center"><strong>HRM</strong></td>
						<td rowspan="2" width="20%" class="text-center"><strong>Finance Department</strong></td>
					</tr>
					<tr>
						<td>Designation:</td>
						<td></td>
					</tr>
					<tr>
						<td>Empl. No.:</td>
						<td></td>
						<td rowspan="3"></td>
						<td rowspan="3"></td>
						<td rowspan="3"></td>
					</tr>
					<tr>
						<td>Signature:</td>
						<td></td>
					</tr>
					<tr>
						<td>Date:</td>
						<td></td>
					</tr>
				</tbody>
				</table>
          </div>
        </td>
      </tr>
    </tbody>

    <tfoot>
      <tr>
        <td>
          <!--place holder for the fixed-position footer-->
          <div class="page-footer-space"></div>
        </td>
      </tr>
    </tfoot>

  </table>
<div class="text-center">
		<a href="javascript:;" class="dontprint btn btn-primary btn-lg" id="print-btn">Print in letterhead</a>
		<a href="javascript:;" class="dontprint btn btn-primary btn-lg" id="print-btn1">Print plain</a>
		</div>
</body>

</html> */ ?>
	
	
	
<script src="<?= base_url() ?>assets/user/bundles/libscripts.bundle.js"></script>
<script type="text/javascript">
	$('#print-btn1').on('click',function(){
		$('.header, .footer').remove();
		$('.letter-content').addClass('active');
		window.print();
		window.close();
		});
	$('#print-btn').on('click',function() {		
		window.print();
		window.close();
		});
</script>