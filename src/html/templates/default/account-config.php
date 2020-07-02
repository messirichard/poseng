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
					<!-- BEGIN: Subheader -->
					<div class="m-subheader ">
						<div class="d-flex align-items-center">
							<div class="mr-auto">
								<h3 class="m-subheader__title "><?php echo HEAD_TITLE ?></h3>
								<ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
									<li class="m-nav__item m-nav__item--home">
										<a href="/" class="m-nav__link m-nav__link--icon">
											<i class="m-nav__link-icon la la-home"></i>
										</a>
									</li>
									<li class="m-nav__separator">-</li>
									<li class="m-nav__item">
										<span class="m-nav__link-text"><?php echo HEAD_DESCRIPTION ?></span>
									</li>	
								</ul>
							</div>				
						</div>
					</div>
					<!-- END: Subheader -->				
					<div class="m-content">
						<div class="row">
							<div class="col-lg-12 col-xl-6">
								<div class="m-portlet">
									<div class="m-portlet__head">
										<div class="m-portlet__head-caption">
											<div class="m-portlet__head-title">
												<h3 class="m-portlet__head-text">
													<?php echo CONFIG_TAB1 ?>
												</h3>
											</div>
										</div>
									</div>
									<form class="m-form" method="post">
										<div class="m-portlet__body">
											<div class="m-form__section m-form__section--first">
												<?php if ($dev->license->public==0) { ?>
												<div class="form-group m-form__group">
													<label><?php echo CONFIG_DEVICE_INCLUSIVENESS ?></label>
													<div class="row">
														<div class="col-lg-6">
															<label class="m-option">
																<span class="m-option__control">
																	<span class="m-radio m-radio--brand m-radio--check-bold">
																		<input type="radio" name="cfg_incl_dev" value="1" <?php echo $dev->stats->public==1||$dev->stats->public==2?'checked':'' ?>>
																		<span></span>
																	</span>
																</span>
																<span class="m-option__label">
																	<span class="m-option__head">
																		<span class="m-option__title">
																			<?php echo CONFIG_INCLUSIVENESS_OPEN ?>
																		</span>	
																	</span>
																	<span class="m-option__body">
																		<?php echo CONFIG_DEVICE_INCLUSIVENESS_OPEN ?>
																	</span>
																</span>
															</label>
														</div>
														<div class="col-lg-6">
															<label class="m-option">
																<span class="m-option__control">
																	<span class="m-radio m-radio--brand m-radio--check-bold">
																		<input type="radio" name="cfg_incl_dev" value="0" <?php echo $dev->stats->public==0||$dev->stats->public==3?'checked':'' ?>>
																		<span></span>
																	</span>
																</span>
																<span class="m-option__label">
																	<span class="m-option__head">
																		<span class="m-option__title">
																			<?php echo CONFIG_INCLUSIVENESS_CLOSED ?>
																		</span>																		
																	</span>
																	<span class="m-option__body">
																		<?php echo CONFIG_DEVICE_INCLUSIVENESS_CLOSED ?>
																	</span>
																</span>
															</label>
														</div>
													</div>
												</div>
												<div class="form-group m-form__group">
													<label><?php echo CONFIG_APP_INCLUSIVENESS ?></label>
													<div class="row">
														<div class="col-lg-6">
															<label class="m-option">
																<span class="m-option__control">
																	<span class="m-radio m-radio--brand m-radio--check-bold">
																		<input type="radio" name="cfg_incl_app" value="1" <?php echo $dev->stats->public==1||$dev->stats->public==3?'checked':'' ?>>
																		<span></span>
																	</span>
																</span>
																<span class="m-option__label">
																	<span class="m-option__head">
																		<span class="m-option__title">
																			<?php echo CONFIG_INCLUSIVENESS_OPEN ?>
																		</span>	
																	</span>
																	<span class="m-option__body">
																		<?php echo CONFIG_APP_INCLUSIVENESS_OPEN ?>
																	</span>
																</span>
															</label>
														</div>
														<div class="col-lg-6">
															<label class="m-option">
																<span class="m-option__control">
																	<span class="m-radio m-radio--brand m-radio--check-bold">
																		<input type="radio" name="cfg_incl_app" value="0" <?php echo $dev->stats->public==0||$dev->stats->public==2?'checked':'' ?>>
																		<span></span>
																	</span>
																</span>
																<span class="m-option__label">
																	<span class="m-option__head">
																		<span class="m-option__title">
																			<?php echo CONFIG_INCLUSIVENESS_CLOSED ?>
																		</span>																		
																	</span>
																	<span class="m-option__body">
																		<?php echo CONFIG_APP_INCLUSIVENESS_CLOSED ?>
																	</span>
																</span>
															</label>
														</div>
													</div>
												</div>	
												<?php } ?>
												<div class="m-section__content">
													<div class="m-demo">
														<div class="m-demo__preview">
															<div class="m-form__group form-group row">
																<label class="col-3 col-form-label"><?php echo CONFIG_LOG_ME ?></label>
																<div class="col-3">
																	<span class="m-switch m-switch--lg m-switch--icon">
																		<label>
																			<input type="checkbox" <?php echo $dev->stats->default_logme?'checked="checked"':'' ?> name="cfg_log_me" value="1">
																			<span></span>
																		</label>
																	</span>
																</div>													
															</div>
															<div class="form-group m-form__group">
																<label for="cfg_log_default" class="form-control-label"><?php echo CONFIG_LOG_DEFAULT ?></label>
																<div class="input-group">
																	<input type="hidden" name="cfg_log_default" value="<?php echo $dev->stats->default_logs ?>" />
																	<input name="cfg_log_default_visible" type="text" class="cfg_number form-control" id="cfg_log_default" value="<?php echo $dev->stats->default_logs/1000000 ?>" placeholder="<?php echo DEFAULT_LOG_LIMIT ?>">
																	<div class="input-group-append"><span class="input-group-text"><?php echo CONFIG_LOG_DEFAULT_ITEM ?></span></div>
																</div>
															</div>
															<div class="form-group m-form__group">
																<label for="cfg_log_keep" class="form-control-label"><?php echo CONFIG_LOG_KEEP ?></label>
																<div class="input-group">
																	<input type="hidden" name="cfg_log_keep" value="<?php echo $dev->stats->default_logkeep ?>" />
																	<input name="cfg_log_keep_visible" type="text" class="cfg_number form-control" id="cfg_log_keep" value="<?php echo $dev->stats->default_logkeep ?>" placeholder="<?php echo DEFAULT_LOG_KEEP ?>">
																	<div class="input-group-append"><span class="input-group-text"><?php echo CONFIG_LOG_KEEP_DAYS ?></span></div>
																</div>
															</div>														
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="m-portlet__foot m-portlet__foot--fit">
											<div class="m-form__actions m-form__actions">
												<button type="button" id="account-config-tab1-submit" class="btn btn-accent m-btn m-btn--air m-btn--custom"><?php echo CONFIG_BTN_UPDATE ?></button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="col-lg-12 col-xl-6">
								<div class="m-portlet">
									<div class="m-portlet__head">
										<div class="m-portlet__head-caption">
											<div class="m-portlet__head-title">
												<h3 class="m-portlet__head-text">
													<?php echo CONFIG_TAB2 ?>
												</h3>
											</div>
										</div>
									</div>
									<form class="m-form" method="post">
										<div class="m-portlet__body">
											<div class="m-form__section m-form__section--first">												
												<div class="form-group m-form__group">
													<label for="cfg_oem_brand" class="form-control-label"><?php echo CONFIG_OEM_COMPANY_BRAND ?></label>
													<div class="input-group">
														<input name="cfg_oem_brand" type="text" class="form-control" id="cfg_oem_brand" maxlength="70" value="<?php echo $dev->oem->brand ?>">
														<div class="input-group-append"><span class="input-group-text"><i class="la la-bookmark"></i></span></div>
													</div>
												</div>
												<div class="form-group m-form__group">
													<label for="cfg_oem_tagline" class="form-control-label"><?php echo CONFIG_OEM_COMPANY_TAGLINE ?></label>
													<div class="input-group">
														<input name="cfg_oem_tagline" type="text" class="form-control" id="cfg_oem_tagline" maxlength="70" value="<?php echo $dev->oem->tagline ?>">
														<div class="input-group-append"><span class="input-group-text"><i class="la la-info"></i></span></div>
													</div>
												</div>
												<div class="form-group m-form__group">
													<label for="cfg_oem_domain" class="form-control-label"><?php echo CONFIG_OEM_DOMAIN_URL ?></label>
													<div class="input-group">
														<input name="cfg_oem_domain" type="text" class="form-control" id="cfg_oem_domain" maxlength="255" value="<?php echo $dev->oem->domain ?>">
														<div class="input-group-append"><span class="input-group-text"><i class="la la-globe"></i></span></div>
													</div>
												</div>
												<div class="form-group m-form__group">
													<label for="cfg_oem_email_noreply" class="form-control-label"><?php echo CONFIG_OEM_EMAIL_NOREPLY ?></label>
													<div class="input-group">
														<input name="cfg_oem_email_noreply" type="text" class="form-control" id="cfg_oem_email_noreply" maxlength="255" value="<?php echo $dev->oem->email_noreply ?>" placeholder="<?php echo EMAIL_USER_NOREPLY ?>">
														<div class="input-group-append"><span class="input-group-text"><i class="la la-at"></i></span></div>
													</div>
												</div>
												<div class="form-group m-form__group">
													<label for="cfg_oem_email_support" class="form-control-label"><?php echo CONFIG_OEM_EMAIL_SUPPORT ?></label>
													<div class="input-group">
														<input name="cfg_oem_email_support" type="text" class="form-control" id="cfg_oem_email_support" maxlength="255" value="<?php echo $dev->oem->email_support ?>">
														<div class="input-group-append"><span class="input-group-text"><i class="la la-at"></i></span></div>
													</div>
												</div>
												<div class="form-group m-form__group">
													<label for="cfg_oem_url_logo" class="form-control-label"><?php echo CONFIG_OEM_URL_LOGO ?></label>
													<div class="input-group">
														<input name="cfg_oem_url_logo" type="text" class="form-control" id="cfg_oem_url_logo" maxlength="255" value="<?php echo $dev->oem->url_logo ?>">
														<div class="input-group-append"><span class="input-group-text"><i class="la la-image"></i></span></div>
													</div>
												</div>
												<div class="form-group m-form__group">
													<label for="cfg_oem_url_tos" class="form-control-label"><?php echo CONFIG_OEM_URL_TOS ?></label>
													<div class="input-group">
														<input name="cfg_oem_url_tos" type="text" class="form-control" id="cfg_oem_url_tos" maxlength="255" value="<?php echo $dev->oem->url_tos ?>">
														<div class="input-group-append"><span class="input-group-text"><i class="la la-legal"></i></span></div>
													</div>
												</div>
												<div class="form-group m-form__group">
													<label for="cfg_oem_url_privacy" class="form-control-label"><?php echo CONFIG_OEM_URL_PRIVACY ?></label>
													<div class="input-group">
														<input name="cfg_oem_url_privacy" type="text" class="form-control" id="cfg_oem_url_privacy" maxlength="255" value="<?php echo $dev->oem->url_privacy ?>">
														<div class="input-group-append"><span class="input-group-text"><i class="la la-legal"></i></span></div>
													</div>
												</div>
												<div class="form-group m-form__group">
													<label for="cfg_oem_url_android" class="form-control-label"><?php echo CONFIG_OEM_APP_ANDROID ?></label>
													<div class="input-group">
														<input name="cfg_oem_url_android" type="text" class="form-control" id="cfg_oem_url_android" maxlength="255" value="<?php echo $dev->oem->url_android ?>">
														<div class="input-group-append"><span class="input-group-text"><i class="la la-android"></i></span></div>
													</div>
												</div>
												<div class="form-group m-form__group">
													<label for="cfg_oem_url_ios" class="form-control-label"><?php echo CONFIG_OEM_APP_IOS ?></label>
													<div class="input-group">
														<input name="cfg_oem_url_ios" type="text" class="form-control" id="cfg_oem_url_ios" maxlength="255" value="<?php echo $dev->oem->url_ios ?>">
														<div class="input-group-append"><span class="input-group-text"><i class="la la-apple"></i></span></div>
													</div>
												</div>
												<div class="form-group m-form__group">
													<label for="cfg_oem_url_otherapp" class="form-control-label"><?php echo CONFIG_OEM_APP_OTHER ?></label>
													<div class="input-group">
														<input name="cfg_oem_url_otherapp" type="text" class="form-control" id="cfg_oem_url_otherapp" maxlength="255" value="<?php echo $dev->oem->url_otherapp ?>">
														<div class="input-group-append"><span class="input-group-text"><i class="la la-desktop"></i></span></div>
													</div>
												</div>												
											</div>
										</div>
										<div class="m-portlet__foot m-portlet__foot--fit">
											<div class="m-form__actions m-form__actions">
												<button type="button" id="account-config-tab2-submit" class="btn btn-accent m-btn m-btn--air m-btn--custom"><?php echo CONFIG_BTN_UPDATE ?></button>
											</div>
										</div>
									</form>
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
		<script src="templates/default/assets/js/account-config.js" type="text/javascript"></script>
	</body>
</html>