<?php
$system_title = $this->settings->get_settings('systemTitle');
$system_logo = $this->settings->get_settings('systemLogo');
$system_theme = $this->settings->get_settings('systemTheme');
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
	<!-- WRAPPER -->
	<div id="wrapper">
		<div class="vertical-align-wrap">
			<div class="vertical-align-middle auth-main">
				<div class="auth-box">
                    <div class="top">
                        <img src="<?= $system_logo ?>" alt="<?= $system_title ?>">
                    </div>
					<div class="card">
                        <?php $this->load->view($page_type."/".$page_name); ?>
                    </div>
				</div>
			</div>
		</div>
	</div>
	<!-- END WRAPPER -->
	<?php require_once 'include_bottom.php';?>
</body>
</html>