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
				 <style>
					 table th {
						border:0px;
						padding-left: 0px;
						padding-right: 0px;
					 }
					 table {
						height:auto;
					 }
				 </style>
  </head>
  <body>
    <div class="header"><img src="<?=base_url('assets/common/lhead.png')?>" /></div>
    <div class="letter-content">
			<?php //pr($details);?>
			
			<?php
			if(!empty($details['detailed_status'])) {
				foreach($details['detailed_status'] as $row) {
					if($row['position'] == 1) {
						$hod = $row['name'].' - '.get_date($row['status_changed']);
					}
					if($row['position'] == 2) {
						$smanager = $row['name'].' - '.get_date($row['status_changed']);
					}
					if($row['position'] == 3) {
						$hr = $row['name'].' - '.get_date($row['status_changed']);
					}
				}
			}
			?>
      <div style="width:100%;float:left;margin-bottom:10px;font-weight:bold;text-decoration:underline; font-size:19px;text-align:center;">LEAVE APPLICATION FORM</div>
      <div style="width:100%;float:left;">
        <div style="float:right;">Employee File No.:&nbsp;<?=$details['code']?></div>
      </div>
      <div style="width:100%;float:left;">Type of Leave:</div>
      <table style="width:100%;float:left;">
        <thead>
          <tr>
            <th><?php echo (($details['leave_type_id'] == '1')?'<div style="width: 15px;height: 15px;background: #000;display: inline-block;margin-right: 10px;vertical-align: middle;"></div>':'')?>Annual Leave</th>
            <th><?php echo (($details['leave_type_id'] == '3')?'<div style="width: 15px;height: 15px;background: #000;display: inline-block;margin-right: 10px;vertical-align: middle;"></div>':'')?>Medical Leave</th>
            <th><?php echo (($details['leave_type_id'] == '4')?'<div style="width: 15px;height: 15px;background: #000;display: inline-block;margin-right: 10px;vertical-align: middle;"></div>':'')?>Emergency Leave</th>
            <th><?php echo (($details['leave_type_id'] != '1' && $details['leave_type_id'] != '3' && $details['leave_type_id'] != '4')?'<div style="width: 15px;height: 15px;background: #000;display: inline-block;margin-right: 10px;vertical-align: middle;"></div>':'')?>Others</th>
          </tr>
        </thead>
      </table>
      <table style="width:100%;float:left;">
        <tbody>
          <tr>
            <td width="33%">Passport No.:&nbsp;<?=$details['passport_number']?></td>
            <td width="30%">Date of Joining:&nbsp;<?=$details['date_of_join']?> </td>
            <td width="36%">Date of Request: &nbsp;<?=get_date($details['created_at'])?></td>
          </tr>
          <tr>
            <td colspan="2">Requester's Full Name:&nbsp;<?=$details['name']?> </td>
            <td>Designation:&nbsp;<?=$details['designation']?> </td>
          </tr>
        </tbody>
      </table>
      <div style="width:100%;float:left;margin-top:10px;">Schedule of Leave:</div>
      <table style="width:100%;float:left;">
        <tbody>
          <tr>
            <td width="66%">Purpose/Reason of Leave:&nbsp;<?=nl2br($details['remarks'])?> </td>
            <td width="34%">From Date: &nbsp;<?=get_date($details['from_date'])?><br/>To Date:&nbsp;<?=get_date($details['to_date'])?></td>
          </tr>
          <tr>
            <td>Previous Leave Taken: </td>
            <td>From Date: <br/>To Date:</td>
          </tr>
        </tbody>
      </table>
      <div style="width:100%;float:left;margin-top:10px;">Reporting Date:&nbsp;<?=get_date($details['created_at'])?></div>
      <div style="width:100%;float:left;">Requester's Contact Number:&nbsp;<?=$details['phone_office']?></div>
      <div style="width:100%;float:left;">Mob UAE:&nbsp;<?=$details['phone_current']?></div>
      <div style="width:100%;float:left;">Mob Home Country:&nbsp;<?=$details['phone_home']?></div>
      <div style="width:100%;float:left;">Home Country Destination:</div>


      <table style="width:100%;float:left;">
        <tbody>
          <tr>
            <td width="25%" style="border:none;">Requester's Signature </td>
            <td width="25%" style="border:none;">Approval of HOD </td>
            <td width="25%" style="border:none;">Site Manager </td>
						<td width="25%" style="border:none;">HR </td>
          </tr>
          <tr>
            <td height="50"></td>
            <td style="vertical-align: bottom;padding: 0 5px 2px 5px;font-size: 10px;"><?=$hod?></td>
            <td style="vertical-align: bottom;padding: 0 5px 2px 5px;font-size: 10px;"><?=$smanager?></td>
						<td style="vertical-align: bottom;padding: 0 5px 2px 5px;font-size: 10px;"><?=$hr?></td>
          </tr>
        </tbody>
      </table>
      <div style="width:100%;float:left;margin-top:10px;">Receiving of Passport and Labour Card Original Copy:</div>
      <table style="width:100%;float:left;">
        <tbody>
          <tr>
            <td width="50%">Document</td>
            <td width="20%">Received By/Date</td>
            <td width="30%">(Returned) Received By/Date</td>
          </tr>
          <tr>
            <td>Passport:&nbsp;<?=$details['passport_number']?>
              <br />Emirates ID/Medical Card:&nbsp;<?=$details['emirate_number']?>
            </td>
            <td></td>
            <td></td>
          </tr>
        </tbody>
      </table>
      <div style="width:100%;float:left;margin-top:10px;">For Accounts Use Only</div>
      <table style="width:100%;float:left;">
        <tbody>
          <tr>
            <td width="50%">No. of Days Leave with Pay: </td>
            <td width="50%">No. of Days Leave without Pay:</td>
          </tr>

          <tr>
            <td>Dues Salary:</td>
            <td rowspan="2">Total Salary Paid: </td>
          </tr>
          <tr>
            <td>Leave Salary:</td>
          </tr>
        </tbody>
      </table>

      <div style="width:100%;float:left;margin-top:10px;">For Office Use Only:</div>
      <table style="width:100%;float:left;">
        <tbody>
          <tr>
            <td>Ticker Eligibility: <span style="float:right">By Company</span><?php echo (($details['airticket'] == 'bycompany')?'<div style="width: 15px;height: 15px;background: #000;display: inline-block;margin-left: 10px;vertical-align: middle;"></div>':'')?></td>
            <td><span>By Person</span><?php echo (($details['airticket'] == 'byperson')?'<div style="width: 15px;height: 15px;background: #000;display: inline-block;margin-left: 10px;vertical-align: middle;"></div>':'')?></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="footer"><img src="<?=base_url('assets/common/lfoot.png')?>" /></div>
    <div class="text-center">
      <a href="javascript:;" class="dontprint btn btn-primary btn-lg" id="print-btn">Print in letterhead</a>
      <a href="javascript:;" class="dontprint btn btn-primary btn-lg" id="print-btn1">Print plain</a>
    </div>
  </body>

  </html>
	
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