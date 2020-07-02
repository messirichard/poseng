<!DOCTYPE html>
<html lang="<?php echo LANG ?>">
	<?php require_once('_head.php'); ?>
	<link href="templates/default/assets/view/price-tags.css" rel="stylesheet" type="text/css" />
	<script
       src="https://www.paypal.com/sdk/js?client-id=<?php echo PAYMENT_PAYPAL_KEY ?>&vault=true">
  	</script>
	<body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default <?php echo $defNav ?>">
		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<?php require_once('_page_head.php'); ?>
			<!-- begin::Body -->
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
				<?php require_once('_page_nav.php'); ?>
				<div class="m-grid__item m-grid__item--fluid m-wrapper">					
					<div class="m-content">
						<div class="m-portlet">
							<div class="m-portlet__body m-portlet__body--no-padding">
								<div class="m-pricing-table-2">
									<div class="m-pricing-table-2__head">
										<div class="m-pricing-table-2__title m--font-light">
											<h1><?php echo ACCOUNT_UPGRADE_PRICING ?></h1>
										</div>
										<div class="btn-group nav m-btn-group m-btn-group--pill m-btn-group--air" role="group">
											<button type="button" class="btn m-btn--pill  active m-btn--wide m-btn--uppercase m-btn--bolder" data-toggle="tab" href="#m-pricing-table_content1" role="tab" aria-expanded="true">
												<?php echo ACCOUNT_UPGRADE_PLAN_MONTHLY ?>
											</button>
											<button type="button" class="btn m-btn--pill  m-btn--wide m-btn--uppercase m-btn--bolder" data-toggle="tab" href="#m-pricing-table_content2" role="tab" aria-expanded="false">
												<?php echo ACCOUNT_UPGRADE_PLAN_ANNUAL ?>
											</button>
										</div>
									</div>
									<div class="tab-content">
										<div class="tab-pane active" id="m-pricing-table_content1" aria-expanded="true">
											<div class="m-pricing-table-2__content">
												<div class="m-pricing-table-2__container upgrade-window">
													<div class="m-pricing-table-2__items row">
														<?php 
															$icon = array("flaticon-bus-stop","flaticon-car","flaticon-earth-globe","flaticon-rocket","flaticon-confetti");
															foreach ($licenses as $key=>$val) { 
																if ($key<4) {
														?>
														<div class="m-pricing-table-2__item col-lg-3">
															<div class="m-pricing-table-2__visual">
																<div class="m-pricing-table-2__hexagon"></div>
																<span class="m-pricing-table-2__icon m--font-info"><i class="fa <?php echo $icon[$key] ?>"></i></span>
															</div>
															<h2 class="m-pricing-table-2__subtitle"><?php echo $val->name ?></h2>
															<div class="m-pricing-table-2__features">
																<span><?php echo nameNumber($val->total_devices,0) . ' ' . ACCOUNT_UPGRADE_LICENSE_TOTAL_DEVICES . DEFAULT_CURRENCY_SYMBOL . nameNumber($val->price_add_device,0) . ACCOUNT_UPGRADE_LICENSE_TOTAL_ADD_DEVICE ?></span>
																<span><?php echo nameBytes($val->total_bytes,0) . ACCOUNT_UPGRADE_LICENSE_TOTAL_LOGS . DEFAULT_CURRENCY_SYMBOL . nameNumber($val->price_add_bytes,0) . ACCOUNT_UPGRADE_LICENSE_TOTAL_ADD_LOG ?></span>
																<span><?php echo nameNumber($val->api_apps,0) . ' ' . ACCOUNT_UPGRADE_LICENSE_API_APPS ?></span>
																<span><?php echo $val->public==1?ACCOUNT_UPGRADE_LICENSE_PUBLIC_1:ACCOUNT_UPGRADE_LICENSE_PUBLIC_0 ?></span>
																<?php if ($val->total_users>0) { ?> 
																<span><?php echo $val->total_users>0?nameNumber($val->total_users,0) . ' ' . ACCOUNT_UPGRADE_LICENSE_TOTAL_USERS . DEFAULT_CURRENCY_SYMBOL . nameNumber($val->price_add_user,0) . ACCOUNT_UPGRADE_LICENSE_TOTAL_ADD_USER:"&nbsp;" ?></span>
																<?php } ?>
																<span><?php echo $val->email_service==1?ACCOUNT_UPGRADE_LICENSE_OEMEMAIL_YES:ACCOUNT_UPGRADE_LICENSE_OEMEMAIL_NO ?></span>																
																<span><?php echo $val->userbase==1?ACCOUNT_UPGRADE_LICENSE_USERBASE_YES:ACCOUNT_UPGRADE_LICENSE_USERBASE_NO ?></span>																
															</div>
															<div class="price-tag-left">
															<?php echo '<span class="tag tag-left">' . ($val->id>4?ACCOUNT_UPGRADE_CALL:(DEFAULT_CURRENCY_SYMBOL . nameNumber($val->price,2))) . '</span>' . (($val->disc==null||$val->disc<=0)?'':'<span class="tag-diagonal"></span>') ?>
															</div>
															<div class="price-tag-left">
															<?php echo ($val->disc==null||$val->disc<=0) ?'<span class="tag"></span>':'<span class="tag tag-left2">' .  DEFAULT_CURRENCY_SYMBOL . nameNumber(($val->price-$val->disc),2) . '</span>' ?>
															</div>
															<div class="m-pricing-table-2__btn">
																<?php if ($dev->license->id!==$val->id) { ?>
																	<span class="paypal-upgrade"><?php echo isset($dev->pay_channels->paypal)?($dev->license->id==$val->id?ACCOUNT_UPGRADE_CURRENT:($dev->license->id>$val->id?ACCOUNT_UPGRADE_DOWN:ACCOUNT_UPGRADE_UP)):ACCOUNT_UPGRADE_SUBS ?></span>
																	<div id="paypal-button-container-monthly-<?php echo $dev->license->id==$val->id?'':$val->id ?>" class="paypal-button-container" data-subscriber="<?php echo isset($dev->pay_channels->paypal->id)?$dev->pay_channels->paypal->id:'' ?>" data-plan="<?php echo $dev->license->id==$val->id?'':paypalPlan($val->id,true) ?>" data-null="<?php echo ACCOUNT_UPGRADE_NULL ?>" data-ctc="<?php echo ACCOUNT_UPGRADE_EMAIL_SUPPORT_ADDRESS ?>" data-sbj="<?php echo ACCOUNT_UPGRADE_EMAIL_SUPPORT_SUBJECT ?>"></div>
																<?php } else { ?>
																	<div class="button--upgrade-contact btn-upgrade btn m-btn--pill btn-primary m-btn--wide m-btn--uppercase m-btn--bolder m-btn--lg"><?php echo ACCOUNT_UPGRADE_PLAN_CURRENT ?></div>
																<?php } ?>
															</div>
														</div>																												
														<?php }} ?>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane" id="m-pricing-table_content2" aria-expanded="false">
											<div class="m-pricing-table-2__content">
												<div class="m-pricing-table-2__container upgrade-window">
													<div class="m-pricing-table-2__items row">
														<?php 
															$icon = array("flaticon-bus-stop","flaticon-earth-globe","flaticon-rocket","flaticon-confetti");
															foreach ($licenses as $key=>$val) { 
																if ($key<4) {
														?>
														<div class="m-pricing-table-2__item col-lg-3">
															<div class="m-pricing-table-2__visual">
																<div class="m-pricing-table-2__hexagon"></div>
																<span class="m-pricing-table-2__icon m--font-info"><i class="fa <?php echo $icon[$key] ?>"></i></span>
															</div>
															<h2 class="m-pricing-table-2__subtitle"><?php echo $val->name ?></h2>
															<div class="m-pricing-table-2__features">
																<span><?php echo nameNumber($val->total_devices,0) . ' ' . ACCOUNT_UPGRADE_LICENSE_TOTAL_DEVICES . DEFAULT_CURRENCY_SYMBOL . nameNumber($val->price_add_device,0) . ACCOUNT_UPGRADE_LICENSE_TOTAL_ADD_DEVICE ?></span>
																<span><?php echo nameBytes($val->total_bytes,0) . ACCOUNT_UPGRADE_LICENSE_TOTAL_LOGS . DEFAULT_CURRENCY_SYMBOL . nameNumber($val->price_add_bytes,0) . ACCOUNT_UPGRADE_LICENSE_TOTAL_ADD_LOG ?></span>
																<span><?php echo nameNumber($val->api_apps,0) . ' ' . ACCOUNT_UPGRADE_LICENSE_API_APPS ?></span>
																<span><?php echo $val->public==1?ACCOUNT_UPGRADE_LICENSE_PUBLIC_1:ACCOUNT_UPGRADE_LICENSE_PUBLIC_0 ?></span>																
																<?php if ($val->total_users>0) { ?> 
																<span><?php echo $val->total_users>0?nameNumber($val->total_users,0) . ' ' . ACCOUNT_UPGRADE_LICENSE_TOTAL_USERS . DEFAULT_CURRENCY_SYMBOL . nameNumber($val->price_add_user,0) . ACCOUNT_UPGRADE_LICENSE_TOTAL_ADD_USER:"&nbsp;" ?></span>
																<?php } ?>
																<span><?php echo $val->email_service==1?ACCOUNT_UPGRADE_LICENSE_OEMEMAIL_YES:ACCOUNT_UPGRADE_LICENSE_OEMEMAIL_NO ?></span>																
																<span><?php echo $val->userbase==1?ACCOUNT_UPGRADE_LICENSE_USERBASE_YES:ACCOUNT_UPGRADE_LICENSE_USERBASE_NO ?></span>																
															</div>
															<div class="price-tag-left">
															<?php echo '<span class="tag tag-left">' . ($val->id>4?ACCOUNT_UPGRADE_CALL:(DEFAULT_CURRENCY_SYMBOL . nameNumber($val->price_year,2))) . '</span>' . (($val->disc_year==null||$val->disc_year<=0)?'':'<span class="tag-diagonal"></span>') ?>
															</div>
															<div class="price-tag-left">
															<?php echo ($val->disc_year==null||$val->disc_year<=0) ?'<span class="tag"></span>':'<span class="tag tag-left2">' .  DEFAULT_CURRENCY_SYMBOL . nameNumber(($val->price_year-$val->disc_year),2) . '</span>' ?>
															</div>
															<div class="m-pricing-table-2__btn">
																<?php if ($dev->license->id!==$val->id) { ?>
																	<span class="paypal-upgrade"><?php echo isset($dev->pay_channels->paypal)?($dev->license->id==$val->id?ACCOUNT_UPGRADE_CURRENT:($dev->license->id>$val->id?ACCOUNT_UPGRADE_DOWN:ACCOUNT_UPGRADE_UP)):ACCOUNT_UPGRADE_SUBS ?></span>
																	<div id="paypal-button-container-yearly-<?php echo $dev->license->id==$val->id?'':$val->id ?>" class="paypal-button-container" data-subscriber="<?php echo isset($dev->pay_channels->paypal->id)?$dev->pay_channels->paypal->id:'' ?>" data-plan="<?php echo $dev->license->id==$val->id?'':paypalPlan($val->id,false) ?>" data-null="<?php echo ACCOUNT_UPGRADE_NULL ?>" data-ctc="<?php echo ACCOUNT_UPGRADE_EMAIL_SUPPORT_ADDRESS ?>" data-sbj="<?php echo ACCOUNT_UPGRADE_EMAIL_SUPPORT_SUBJECT ?>"></div>
																<?php } else { ?>
																	<div class="button--upgrade-contact btn-upgrade btn m-btn--pill btn-primary m-btn--wide m-btn--uppercase m-btn--bolder m-btn--lg"><?php echo ACCOUNT_UPGRADE_PLAN_CURRENT ?></div>
																<?php } ?>
															</div>
														</div>																												
														<?php }} ?>
													</div>
												</div>
											</div>
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
		<script src="templates/default/assets/js/account-subscription.js" type="text/javascript"></script>
	</body>
</html>