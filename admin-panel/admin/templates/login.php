<div class="hold-transition login-page">
	<div class="login-box">
		<div class="login-logo">
			<a href="index.php"><b>Moydom</b></a>
		</div>
	  <!-- /.login-logo -->
	  <div class="card">
		<div class="card-body login-card-body">
			<p class="login-box-msg">Please login with your Email and Password.</p>

			<form id="login_frm" action="" method="post" role="form" enctype="multipart/form-data">
				<div class="form-group has-feedback">
					<input type="text" name="user_email" value="<?php if(isset($_COOKIE["user_email"])) { echo $_COOKIE["user_email"]; } ?>" class="form-control" placeholder="Email">
				</div>
				<div class="form-group has-feedback">
					<input type="password" class="form-control" name="password" value="<?php if(isset($_COOKIE["password"])) { echo $_COOKIE["password"]; } ?>" placeholder="Password">
				</div>
				<div class="row">
					<div class="col-12 err_msg"></div>
					<div class="col-8">
						<div class="checkbox icheck">
							<label><input type="checkbox" class="checkbox icheck" name="remember" id="remember" <?php if(isset($_COOKIE["user_email"])) { ?> checked <?php } ?> /> Remember Me</label>
						</div>
					</div>
					<!-- /.col -->
					<div class="col-4">
						<button type="submit" name="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
					</div>
				  <!-- /.col -->
				</div>
			</form>
		  <!-- /.social-auth-links -->
		</div>
		<!-- /.login-card-body -->
	  </div>
	</div>
</div>
