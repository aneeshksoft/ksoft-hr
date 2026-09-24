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
		  echo '<div style="width:100%;float:left;">   <div style="float:left;">REF: '.$letter['refno'].'</div>   <div style="float:right;">Date: '.get_date($letter['generated_date']).'</div> </div>';
			echo stripslashes($letter['letter_data']);
		?>
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