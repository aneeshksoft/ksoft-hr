<div class="header">
	<p class="lead">OTP Verification</p>
</div>
<div class="body">
	<p>Please enter the otp received.</p>
	<form class="form-auth-small" action="" name="forgotVerify" id="forgotVerify" method="POST">
		<div class="form-group">
			<input type="text" class="form-control" name="otp" id="otp" placeholder="OTP">
		</div>
		<button type="submit" class="btn btn-primary btn-lg btn-block btnsmt">VERIFY OTP</button>
		
        <div class="bottom" hidden>
        <span class="helper-text m-b-10">Resend OTP? &nbsp;<button  onclick="resend_forgot_otp()" id="timer">Resend</button></span>
		</div>
	</form>
	<div class="alert d-none text-center" role="alert"></div>
</div>
