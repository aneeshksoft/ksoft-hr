<div class="header">
	<p class="lead">Recover your password</p>
</div>
<div class="body">
	<p>Please enter your registered email below to receive verification code.</p>
	<form class="form-auth-small" action="" name="forgot" id="forgot" method="POST">
		<div class="form-group">
			<input type="text" class="form-control" name="email" id="email" placeholder="Email">
		</div>
		<button type="submit" class="btn btn-primary btn-lg btn-block btnsmt">SEND OTP</button>
		<div class="bottom">
			<span class="helper-text m-b-10">Know your password? <a href="<?= base_url() . 'signin' ?>">Signin</a></span>
		</div>
	</form>
	<div class="alert d-none text-center" role="alert"></div>
</div>