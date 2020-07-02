<!DOCTYPE html>
<html lang="<?php echo LANG ?>">
	<?php require_once('_head.php'); ?>
	<!-- begin::Body -->
	<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-grid--tablet-and-mobile m-grid--hor-tablet-and-mobile m-login m-login--1 m-login--signin" id="m_login">
				<div class="m-grid__item m-grid__item--order-tablet-and-mobile-2 m-login__aside">
					<div class="m-stack m-stack--hor m-stack--desktop">
						<div class="m-stack__item m-stack__item--fluid">
							<div class="m-login__wrapper">
								<div class="m-login__logo">
									<img src="templates/default/assets/images/logo.png">
								</div>
								<div class="m-login__signin">
									<div class="m-login__head">
										<h3 class="m-login__title"><?php echo FORM_SIGNIN_TITLE ?></h3>
									</div>
									<form class="m-login__form m-form" action="" method="post" data-dest="<?php echo isset($_GET['return'])?$_GET['return']:'page-index' ?>">
										<div class="form-group m-form__group">
											<input class="form-control m-input" type="text" placeholder="<?php echo FORM_SIGNIN_EMAIL ?>" name="email" autocomplete="off">
										</div>
										<div class="form-group m-form__group">
											<input class="form-control m-input m-login__form-input--last" type="password" placeholder="<?php echo FORM_SIGNIN_PASSWORD ?>" name="password" autocomplete="off">
										</div>
										<div class="row m-login__form-sub">
											<div class="col m--align-right">
												<a href="javascript:;" id="m_login_forget_password" class="m-link"><?php echo FORM_SIGNIN_FORGOT ?></a>
											</div>
										</div>
										<div class="m-login__form-action">
											<button id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air"><?php echo FORM_SIGNIN_LOGIN ?></button>
										</div>
									</form>
								</div>
								<div class="m-login__signup">
									<div class="m-login__head">
										<h3 class="m-login__title"><?php echo FORM_SIGNUP_TITLE ?></h3>
										<div class="m-login__desc"><?php echo FORM_SIGNUP_DESC ?></div>
									</div>
									<form class="m-login__form m-form" action="" method="post">
										<div class="form-group m-form__group">
											<input class="form-control m-input" type="text" placeholder="<?php echo FORM_SIGNUP_FULLNAME ?>" name="fullname" maxlength="71">
										</div>
										<div class="form-group m-form__group">
											<input class="form-control m-input" type="text" placeholder="<?php echo FORM_SIGNUP_EMAIL ?>" name="email" autocomplete="off" maxlength="255">
										</div>
										<div class="form-group m-form__group">
											<input class="form-control m-input" type="password" placeholder="<?php echo FORM_SIGNUP_PASSWORD ?>" id="signup_password" name="password" maxlength="128" autocomplete="off">
										</div>
										<div class="form-group m-form__group">
											<input class="form-control m-input m-login__form-input--last" type="password" placeholder="<?php echo FORM_SIGNUP_PASSWORD2 ?>" name="rpassword" maxlength="128" autocomplete="off">
										</div>
										<div class="row form-group m-form__group m-login__form-sub">
											<div class="col m--align-left">
												<label class="m-checkbox m-checkbox--focus">
													<input type="checkbox" name="agree"> <?php echo FORM_SIGNUP_TC1 ?> <a href="https://ask.samelement.com/kbs/general/terms-of-service/" target="_blank" class="m-link m-link--focus"><?php echo FORM_SIGNUP_TC2 ?></a>
													<span></span>
												</label>
												<span class="m-form__help"></span>
											</div>
										</div>
										<div class="m-login__form-action">
											<button id="m_login_signup_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air"><?php echo FORM_SIGNUP_REGISTER ?></button>
											<button id="m_login_signup_cancel" class="btn btn-outline-focus  m-btn m-btn--pill m-btn--custom"><?php echo FORM_SIGNUP_CANCEL ?></button>
										</div>
									</form>
								</div>
								<div class="m-login__forget-password">
									<div class="m-login__head">
										<h3 class="m-login__title"><?php echo FORM_FORGOT_TITLE ?></h3>
										<div style="<?php echo $confirm_reset!==1?'':'display:none' ?>" class="m-login__desc"><?php echo FORM_FORGOT_DESC ?></div>
										<div style="<?php echo $confirm_reset==1?'':'display:none' ?>" class="m-login__desc"><?php echo FORM_FORGOT2_DESC ?></div>
									</div>
									<form class="m-login__form m-form" action="" method="post">
										<div class="form-group m-form__group">
											<input style="<?php echo $confirm_reset!==1?'':'display:none' ?>" class="form-control m-input" type="text" placeholder="<?php echo FORM_FORGOT_EMAIL ?>" name="semail" id="m_email" autocomplete="off" maxlength="255">											
											<input style="<?php echo $confirm_reset==1?'':'display:none' ?>" type="hidden" name="reset" value="<?php echo $confirm_reset==1?$_GET['code']:'' ?>" />
											<input style="<?php echo $confirm_reset==1?'':'display:none' ?>" type="hidden" name="email" value="<?php echo $confirm_reset==1?$_GET['email']:'' ?>" />
											<input style="<?php echo $confirm_reset==1?'':'display:none' ?>" class="form-control m-input" type="password" placeholder="<?php echo FORM_SIGNUP_PASSWORD ?>" name="password" id="reset_password" autocomplete="off" maxlength="128">
											<input style="<?php echo $confirm_reset==1?'':'display:none' ?>" class="form-control m-input" type="password" placeholder="<?php echo FORM_SIGNUP_PASSWORD2 ?>" name="rpassword" autocomplete="off" maxlength="128">
										</div>
										<div class="m-login__form-action">
											<button id="m_login_forget_password_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">
												<span style="<?php echo $confirm_reset!==1?'':'display:none' ?>"><?php echo FORM_FORGOT_REQUEST ?></span>
												<span style="<?php echo $confirm_reset==1?'':'display:none' ?>"><?php echo FORM_FORGOT2_UPDATE ?></span>
											</button>
											<button id="m_login_forget_password_cancel" class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom"><?php echo FORM_FORGOT_CANCEL ?></button>
										</div>
									</form>
								</div>								
							</div>
						</div>
						<div class="m-stack__item m-stack__item--center">
							<div class="m-login__account">
								<span class="m-login__account-msg">
									Don't have an account yet ?
								</span>&nbsp;&nbsp;
								<a href="javascript:;" id="m_login_signup" class="m-link m-link--focus m-login__account-link"><?php echo FORM_SIGNUP_TITLE ?></a>
							</div>
						</div>
					</div>
				</div>
				<div class="m-grid__item m-grid__item--fluid m-grid m-grid--center m-grid--hor m-grid__item--order-tablet-and-mobile-1	m-login__content m-grid-item--center" style="background-image: url(templates/default/assets/images/bg-login.jpg)">
					<img class="login-splashlogo" border="0" src="templates/default/assets/images/logo-white_trans.png" />
					<div class="m-grid__item">
						<h3 class="m-login__welcome"><?php echo SPLASH_TITLE ?></h3>
						<p class="m-login__msg"><?php echo SPLASH_DESC ?></p>
					</div>
				</div>
			</div>
		</div>
		<!-- end:: Page -->
		<?php require_once('_foot.php'); ?>
		<!--begin::Page Scripts -->
		<script src="templates/default/assets/js/login.js" type="text/javascript"></script>
		<!--end::Page Scripts -->
		<?php
			if ($confirm_reg!==null) {
				$mode = $confirm_reg==1?'success':'danger';
				$text = $confirm_reg==1?FORM_CONFIRMSIGNUP_200:($confirm_reg==3?FORM_CONFIRMSIGNUP_500:FORM_CONFIRMSIGNUP_401);
				echo "<script>jQuery(document).ready(function() { var login = $('#m_login'); var f = login.find('.m-login__signin form'); SnippetLogin.displaySignInForm(); SnippetLogin.showErrorMsg(f, '$mode', '$text'); })</script>";
			} else if ($confirm_reset!==null) {
				$mode = $confirm_reset==1?'info':'danger';
				$text = $confirm_reset==1?FORM_CONFIRMRESETPWD_200:($confirm_reset==2?FORM_CONFIRMRESETPWD_500:FORM_CONFIRMRESETPWD_401);
				echo "<script>jQuery(document).ready(function() { var login = $('#m_login'); var f = login.find('.m-login__forget-password form'); SnippetLogin.displayForgetPasswordForm(); SnippetLogin.showErrorMsg(f, '$mode', '$text'); })</script>";
			} else if (isset($_GET['a']) && $_GET['a']=='signup') {
				echo "<script>jQuery(document).ready(function() { SnippetLogin.displaySignUpForm(); })</script>";
			}
		?>
	</body>
	<!-- end::Body -->
</html>