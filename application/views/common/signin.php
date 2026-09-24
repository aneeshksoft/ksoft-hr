<div class="header">
	<p class="lead">Sign in to your account</p>
</div>
<div class="body">
	<form class="form-auth-small" action="" name="signin" id="signin" method="POST">
		<div class="form-group">
			<label for="employee_code" class="control-label sr-only">Employee Code</label>
			<input type="text" class="form-control" name="employee_code" id="employee_code" value="" placeholder="Employee Code" autocomplete="off" autofocus>
		</div>
		<div class="form-group">
			<label for="password" class="control-label sr-only">Password</label>
			<input type="password" class="form-control" name="password" id="password" value="" placeholder="Password" autocomplete="off">
		</div>		
		<button type="submit" class="btn btn-primary btn-lg btn-block btnsmt">SIGN IN</button>
		<div class="bottom">
			<span class="helper-text m-b-10"><i class="fa fa-lock"></i> <a href="<?= base_url().'forgot' ?>">Forgot password?</a></span>
		</div>
	</form>
	<div class="alert d-none text-center" role="alert"></div>
</div>
