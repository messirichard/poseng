<!DOCTYPE html>
<html lang="<?php echo LANG ?>">
	<?php require_once('_head.php'); ?>
	<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default <?php echo $defNav ?>">
		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<?php require_once('_page_head.php'); ?>
			<!-- begin::Body -->
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
				<?php require_once('_page_nav.php'); ?>
				<div class="m-grid__item m-grid__item--fluid m-wrapper">				
					<div class="m-content">
						<div class="row">
							<div class="col-xl-3 col-lg-4">
								<div class="m-portlet m-portlet--full-height  ">
									<div class="m-portlet__body">
										<div class="m-card-profile">
											<div class="m-card-profile__pic">
												<div class="m-card-profile__pic-wrapper">
													<img class="plc-avatar" src="<?php echo $dev->user_avatar ?>" alt="<?php echo $dev->user_fname . ' ' . $dev->user_lname ?>" />
													<img class="cam-avatar" src="templates/default/assets/images/cam.png" border=0 />
												</div>
												<form method="POST" enctype="multipart/form-data">
													<input class="file-avatar" type="file" name="avatar" accept="image/gif, image/jpeg, image/png">
												</form>
											</div>
											<div class="m-card-profile__details">
												<span class="m-card-profile__name"><?php echo $dev->user_fname . ' ' . $dev->user_lname ?></span>
												<a href="" class="m-card-profile__email m-link"><?php echo $dev->user_email ?></a>
											</div>
										</div>
										<ul class="m-nav m-nav--hover-bg m-portlet-fit--sides">
											<li class="m-nav__separator m-nav__separator--fit"></li>
											<li class="m-nav__section m--hide">
												<span class="m-nav__section-text">Section</span>
											</li>
											<li class="m-nav__item">
												<a href="/page-account-profile" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-profile-1"></i>
													<span class="m-nav__link-text"><?php echo NAV_TOP_SECTION1_1 ?></span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="/page-account-billing" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-file"></i>
													<span class="m-nav__link-text"><?php echo NAV_TOP_SECTION1_2 ?></span>
												</a>
											</li>
											<li class="m-nav__item">
												<a href="/page-account-subscription" class="m-nav__link">
													<i class="m-nav__link-icon flaticon-rocket"></i>
													<span class="m-nav__link-text"><?php echo NAV_TOP_SECTION1_3 ?></span>
												</a>
											</li>											
										</ul>
										<div class="m-portlet__body-separator"></div>
										<div class="m-widget1 m-widget1--paddingless">
											<div class="m-widget1__item">
												<div class="row m-row--no-padding align-items-center">
													<div class="col">
														<h3 class="m-widget1__title"><?php echo ACCOUNT_PROFILE_ACCTYPE ?></h3>
														<span class="m-widget1__desc"><?php echo empty($dev->license->desc)?ACCOUNT_PROFILE_ACCTYPE_DESC:$dev->license->desc ?></span>
													</div>
													<div class="col m--align-right">
														<span class="m-widget1__number m--font-brand"><?php echo $dev->license->name ?></span>
													</div>
												</div>
											</div>											
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-9 col-lg-8">
								<div class="m-portlet m-portlet--full-height m-portlet--tabs  ">
									<div class="m-portlet__head">
										<div class="m-portlet__head-tools">
											<ul class="nav nav-tabs m-tabs m-tabs-line   m-tabs-line--left m-tabs-line--primary" role="tablist">
												<li class="nav-item m-tabs__item">
													<a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_user_profile_tab_1" role="tab">
														<i class="flaticon-share m--hide"></i>
														<?php echo ACCOUNT_PROFILE_SECTION_TAB1_TITLE ?>
													</a>
												</li>
												<li class="nav-item m-tabs__item">
													<a class="nav-link m-tabs__link" data-toggle="tab" href="#m_user_profile_tab_2" role="tab">
														<?php echo ACCOUNT_PROFILE_SECTION_TAB2_TITLE ?>
													</a>
												</li>
											</ul>
										</div>
									</div>
									<div class="tab-content">
										<div class="tab-pane active" id="m_user_profile_tab_1">
											<form class="form-margin m-form m-form--fit m-form--label-align-right" method="post">
												<div class="m-portlet__body">													
													<div class="form-group m-form__group row">
														<div class="col-10 ml-auto">
															<h3 class="m-form__section"><?php echo ACCOUNT_PROFILE_SECTION_PERSONAL ?></h3>
														</div>
													</div>
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB1_FNAME ?></label>
														<div class="col-7">
															<input name="fname" class="form-control m-input" type="text" value="<?php echo $dev->user_fname ?>" maxlength="35">
														</div>
													</div>
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB1_LNAME ?></label>
														<div class="col-7">
															<input name="lname" class="form-control m-input" type="text" value="<?php echo $dev->user_lname ?>" maxlength="35">
														</div>
													</div>													
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB1_TITLE ?></label>
														<div class="col-7">
															<input name="title" class="form-control m-input" type="text" value="<?php echo $dev->user_title ?>" maxlength="35">
														</div>
													</div>
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB1_PHONE ?></label>
														<div class="col-7">
															<div class="input-group">
																<input name="phone" class="phone form-control m-input" type="text" value="<?php echo $dev->user_phone ?>" maxlength="20">	
																<div class="input-group-append"><span class="input-group-text"><i class="la la-phone"></i></span></div>
															</div>
														</div>
													</div>
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB1_EMAIL ?></label>
														<div class="col-7">
															<div class="input-group">
																<input class="form-control m-input m-input--solid" type="text" value="<?php echo $dev->user_email ?>" readonly>
																<div class="input-group-append"><span class="input-group-text"><i class="la la-at"></i></span></div>
															</div>
															<span class="m-form__help"><?php echo ACCOUNT_PROFILE_TAB1_EMAIL_DESC ?></span>
														</div>
													</div>
													<div class="m-form__seperator m-form__seperator--dashed m-form__seperator--space-2x"></div>
													<div class="form-group m-form__group row">
														<div class="col-10 ml-auto">
															<h3 class="m-form__section"><?php echo ACCOUNT_PROFILE_SECTION_PASSWORD ?></h3>
														</div>
													</div>
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB1_PWD_ORI ?></label>
														<div class="col-7">
															<input name="opassword" class="form-control m-input" type="password" value="" placeholder="<?php echo ACCOUNT_PROFILE_TAB1_PWD_ORI_PLACEHOLDER ?>" autocomplete="off">
														</div>
													</div>
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB1_PWD_NEW ?></label>
														<div class="col-7">
															<input id="password" name="password" class="form-control m-input" type="password" value="" placeholder="<?php echo ACCOUNT_PROFILE_TAB1_PWD_NEW_PLACEHOLDER ?>" autocomplete="off" maxlength="128">
														</div>
													</div>
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB1_PWD_NEW2 ?></label>
														<div class="col-7">
															<input name="rpassword" class="form-control m-input" type="password" value="" placeholder="<?php echo ACCOUNT_PROFILE_TAB1_PWD_NEW2_PLACEHOLDER ?>" autocomplete="off" maxlength="128">
														</div>
													</div>													
													<div class="m-form__seperator m-form__seperator--dashed m-form__seperator--space-2x"></div>
													<div class="form-group m-form__group row">
														<div class="col-10 ml-auto">
															<h3 class="m-form__section"><?php echo ACCOUNT_PROFILE_SECTION_EMAIL ?></h3>
														</div>
													</div>
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB1_EMAIL_NEW ?></label>
														<div class="col-7">
															<div class="input-group">
																<input name="cemail" class="form-control m-input" type="text" value="" placeholder="<?php echo ACCOUNT_PROFILE_TAB1_EMAIL_NEW_PLACEHOLDER ?>" autocomplete="off" maxlength="255">
																<div class="input-group-append"><span class="input-group-text"><i class="la la-at"></i></span></div>
															</div>
															<span class="m-form__help"><?php echo ACCOUNT_PROFILE_TAB1_EMAILNEW_DESC ?></span>
														</div>
													</div>													
												</div>
												<div class="m-portlet__foot m-portlet__foot--fit">
													<div class="m-form__actions">
														<div class="row">
															<div class="col-2">
															</div>
															<div class="col-7">
																<button type="button" id="account-profile-tab1-submit" class="btn btn-accent m-btn m-btn--air m-btn--custom"><?php echo ACCOUNT_PROFILE_SECTION_TAB1_BUTTON ?></button>
															</div>
														</div>
													</div>
												</div>
											</form>
										</div>
										<div class="tab-pane " id="m_user_profile_tab_2">
											<form class="form-margin m-form m-form--fit m-form--label-align-right" method="post">
												<div class="m-portlet__body">													
													<div class="form-group m-form__group row">
														<div class="col-10 ml-auto">
															<h3 class="m-form__section"><?php echo ACCOUNT_PROFILE_SECTION_ORG ?></h3>
														</div>
													</div>
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB2_ORG_NAME ?></label>
														<div class="col-7">
															<input name="org_name" class="form-control m-input" type="text" value="<?php echo $dev->org_name ?>" maxlength="64">
														</div>
													</div>
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB2_ORG_ADDR1 ?></label>
														<div class="col-7">
															<input name="org_addr1" class="form-control m-input" type="text" value="<?php echo $dev->org_addr1 ?>" maxlength="70">
														</div>
													</div>													
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB2_ORG_ADDR2 ?></label>
														<div class="col-7">
															<input name="org_addr2" class="form-control m-input" type="text" value="<?php echo $dev->org_addr2 ?>" maxlength="70">
														</div>
													</div>
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB2_ORG_CITY ?></label>
														<div class="col-7">
															<input id="account_profile_org_city" name="org_city" class="form-control m-input" type="text" value="<?php echo $dev->org_city ?>" maxlength="35">
														</div>
													</div>
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB2_ORG_PROVINCE ?></label>
														<div class="col-7">
															<input id="account_profile_org_province" name="org_province" class="form-control m-input" type="text" value="<?php echo $dev->org_province ?>" maxlength="20">
														</div>
													</div>
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB2_ORG_COUNTRY ?></label>
														<div class="col-7">
															<input id="account_profile_org_country" name="org_country" class="form-control m-input" type="text" value="<?php echo $dev->org_country ?>" maxlength="52">
														</div>
													</div>
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB2_ORG_ZIP ?></label>
														<div class="col-3">
															<input name="org_zip" class="form-control m-input" type="text" value="<?php echo $dev->org_zip ?>" maxlength="8">
														</div>
													</div>
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB2_ORG_WEB ?></label>
														<div class="col-7">
															<div class="input-group">
																<input name="org_web" class="form-control m-input" type="text" value="<?php echo $dev->org_web ?>" placeholder="<?php echo AJAX_PROFILE_ERROR_INVALIDORGWEB_PLACEHOLDER ?>" maxlength="70">
																<div class="input-group-append"><span class="input-group-text"><i class="la la-globe"></i></span></div>
															</div>
														</div>
													</div>
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB2_ORG_EMAIL ?></label>
														<div class="col-7">
															<div class="input-group">
																<input name="org_email" class="form-control m-input" type="text" value="<?php echo $dev->org_email ?>" placeholder="<?php echo AJAX_PROFILE_ERROR_INVALIDORGEMAIL_PLACEHOLDER ?>" maxlength="255">
																<div class="input-group-append"><span class="input-group-text"><i class="la la-at"></i></span></div>																
															</div>
														</div>
													</div>													
													<div class="form-group m-form__group row">
														<label class="col-2 col-form-label"><?php echo ACCOUNT_PROFILE_TAB1_PHONE ?></label>
														<div class="col-7">
															<div class="input-group">
																<input name="org_phone" class="phone form-control m-input" type="text" value="<?php echo $dev->org_phone ?>" maxlength="20">
																<div class="input-group-append"><span class="input-group-text"><i class="la la-phone"></i></span></div>
															</div>
														</div>
													</div>												
												</div>
												<div class="m-portlet__foot m-portlet__foot--fit">
													<div class="m-form__actions">
														<div class="row">
															<div class="col-2">
															</div>
															<div class="col-7">
																<button type="button" id="account-profile-tab2-submit" class="btn btn-accent m-btn m-btn--air m-btn--custom"><?php echo ACCOUNT_PROFILE_SECTION_TAB2_BUTTON ?></button>
															</div>
														</div>
													</div>
												</div>
											</form>
										</div>										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- end:: Body -->
			<?php require_once('_page_foot.php'); ?>
		</div>
		<!-- end:: Page -->
		<?php require_once('_foot.php'); ?>
		<script src="templates/default/assets/js/account-profile.js" type="text/javascript"></script>
	</body>
</html>