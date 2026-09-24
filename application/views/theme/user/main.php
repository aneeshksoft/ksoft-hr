<?php
$system_title = $this->settings->get_settings('systemTitle');
$system_logo = $this->settings->get_settings('systemLogo');
$system_theme = $this->settings->get_settings('systemTheme');
define('CURRENCY',$this->settings->get_settings('currency'));
$user = $this->settings->get_userdata($this->session->userdata('employee_id'));
?>
<!doctype html>
<html lang="en">

<head>
<title><?php echo $page_title; ?> | <?php echo $system_title;?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="description" content="">
<meta name="author" content="">

<link rel="icon" href="<?= base_url()?>favicon.ico" type="image/x-icon">
<?php require_once 'include_top.php';?>
</head>

<body class="<?= $system_theme ?>">

<!-- Page Loader -->
<div class="page-loader-wrapper">
    <div class="loader">
        <?php /* <div class="m-t-30"><img src="<?= base_url().'assets/common/logo-icon.svg'?>" width="48" height="48" alt="<?=$system_title?>"></div> */?>
		<div class="m-t-30"><img src="<?= base_url().'assets/common/logo-icon.svg'?>" alt="<?=$system_title?>"></div>
        <p>Please wait...</p>        
    </div>
</div>

	<!-- WRAPPER -->
	<div id="wrapper">
		
		<?php require_once 'include_topbar.php' ?>
		<?php require_once 'include_sidemenu.php' ?>
		
		<div id="main-content">
			<div class="container-fluid">
				<div class="block-header">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							<h2><a href="javascript:void(0);" class="btn btn-xs btn-link btn-toggle-fullwidth"><i class="fa fa-arrow-left"></i></a> <?=$page_title?></h2>							
						</div>            
						
					</div>
				</div>
				<?php $this->load->view($page_type."/".$page_name); ?>
			</div>
		</div>
	</div>
	<!-- END WRAPPER -->
	<?php require_once 'include_bottom.php';?>
    <?php require_once 'modal.php';?>
</body>
</html>