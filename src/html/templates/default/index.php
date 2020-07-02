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
							<div class="col-xl-4">
								<!--begin:: Widgets/trends-->
								<div id="w-growth" class="m-portlet m-portlet--bordered-semi m-portlet--full-height ">
									<div class="m-portlet__head">
										<div class="m-portlet__head-caption">
											<div class="m-portlet__head-title">
												<h3 class="m-portlet__head-text">
													<?php echo INDEX_WIDGET_GROWTH ?>
												</h3>
											</div>
										</div>
										<div class="m-portlet__head-tools">
											<ul class="m-portlet__nav">
												<li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" m-dropdown-toggle="hover" aria-expanded="true">
													<a href="#" class="w-options m-portlet__nav-link m-dropdown__toggle dropdown-toggle btn btn--sm m-btn--pill btn-secondary m-btn m-btn--label-brand">
														<?php echo INDEX_OPTION_TIME_LIFETIME ?>
													</a>
													<div class="m-dropdown__wrapper">
														<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 36.5px;"></span>
														<div class="m-dropdown__inner">
															<div class="m-dropdown__body">
																<div class="m-dropdown__content">
																	<ul class="m-nav">
																		<li class="m-nav__item">
																			<a href="#" class="w-option m-nav__link" data-day="7">
																				<span class="m-nav__link-text"><?php echo INDEX_OPTION_TIME_7DAYS ?></span>
																			</a>
																		</li>
																		<li class="m-nav__item">
																			<a href="#" class="w-option m-nav__link" data-day="30">
																				<span class="m-nav__link-text"><?php echo INDEX_OPTION_TIME_30DAYS ?></span>
																			</a>
																		</li>
																		<li class="m-nav__item">
																			<a href="#" class="w-option m-nav__link" data-day="180">
																				<span class="m-nav__link-text"><?php echo INDEX_OPTION_TIME_180DAYS ?></span>
																			</a>
																		</li>
																		<li class="m-nav__item">
																			<a href="#" class="w-option m-nav__link" data-day="365">
																				<span class="m-nav__link-text"><?php echo INDEX_OPTION_TIME_365DAYS ?></span>
																			</a>
																		</li>
																		<li class="m-nav__item">
																			<a href="#" class="w-option m-nav__link" data-day="0">
																				<span class="m-nav__link-text"><?php echo INDEX_OPTION_TIME_LIFETIME ?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
													</div>
												</li>
											</ul>
										</div>
									</div>
									<div class="m-portlet__body">
										<!--begin::Widget5-->
										<div class="m-widget4">
											<div class="m-widget4__chart m-portlet-fit--sides m--margin-top-10 m--margin-top-20" style="height:260px;">
												<canvas id="m_chart_trends_stats"></canvas>
											</div>
											<div class="m-widget4__item">
												<div class="m-widget4__img m-widget4__img--logo">
													<img src="/templates/default/assets/images/dashboard/users.png" alt="">
												</div>
												<div class="m-widget4__info">
													<span class="m-widget4__title">
														<?php echo INDEX_WIDGET_GROWTH_USERS ?>
													</span><br>
													<span class="m-widget4__sub">
														<span><?php echo $dev->stats->total_users ?></span>
														<?php echo INDEX_WIDGET_GROWTH_USERS_TOTAL ?>
													</span>
												</div>
												<span class="w-option-users m-widget4__ext">
													<span class="m-widget4__number m--font-danger">0</span>
												</span>
											</div>
											<div class="m-widget4__item">
												<div class="m-widget4__img m-widget4__img--logo">
													<img src="/templates/default/assets/images/dashboard/devices.png" alt="">
												</div>
												<div class="m-widget4__info">
													<span class="m-widget4__title">
														<?php echo INDEX_WIDGET_GROWTH_DEVICES ?>
													</span><br>
													<span class="m-widget4__sub">
														<span><?php echo $dev->stats->total_devices ?></span>
														<?php echo INDEX_WIDGET_GROWTH_DEVICES_TOTAL ?>
													</span>
												</div>
												<span class="w-option-devices m-widget4__ext">
													<span class="m-widget4__number m--font-danger">0</span>
												</span>
											</div>											
										</div>

										<!--end::Widget 5-->
									</div>
								</div>
								<!--end:: Widgets/trends-->
							</div>
							<div class="col-xl-4">
								<!--begin:: Widgets/users-->
								<div id="w-users" class="m-portlet m-portlet--head-overlay m-portlet--full-height  m-portlet--rounded-force">
									<div class="m-portlet__head m-portlet__head--fit-">
										<div class="m-portlet__head-caption">
											<div class="m-portlet__head-title">
												<h3 class="m-portlet__head-text m--font-light">
													<?php echo INDEX_WIDGET_USERS ?>
												</h3>
											</div>
										</div>
										<div class="m-portlet__head-tools">
											<ul class="m-portlet__nav">
												<li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" m-dropdown-toggle="hover">
													<a href="#" class="w-options m-portlet__nav-link m-dropdown__toggle dropdown-toggle btn btn--sm m-btn--pill m-btn btn-outline-light m-btn--hover-light">
														<?php echo INDEX_OPTION_TIME_LIFETIME ?>
													</a>
													<div class="m-dropdown__wrapper">
														<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
														<div class="m-dropdown__inner">
															<div class="m-dropdown__body">
																<div class="m-dropdown__content">
																	<ul class="m-nav">
																		<li class="m-nav__item">
																			<a href="#" class="w-option m-nav__link" data-day="7">
																				<span class="m-nav__link-text"><?php echo INDEX_OPTION_TIME_7DAYS ?></span>
																			</a>
																		</li>
																		<li class="m-nav__item">
																			<a href="#" class="w-option m-nav__link" data-day="30">
																				<span class="m-nav__link-text"><?php echo INDEX_OPTION_TIME_30DAYS ?></span>
																			</a>
																		</li>
																		<li class="m-nav__item">
																			<a href="#" class="w-option m-nav__link" data-day="180">
																				<span class="m-nav__link-text"><?php echo INDEX_OPTION_TIME_180DAYS ?></span>
																			</a>
																		</li>
																		<li class="m-nav__item">
																			<a href="#" class="w-option m-nav__link" data-day="365">
																				<span class="m-nav__link-text"><?php echo INDEX_OPTION_TIME_365DAYS ?></span>
																			</a>
																		</li>
																		<li class="m-nav__item">
																			<a href="#" class="w-option m-nav__link" data-day="0">
																				<span class="m-nav__link-text"><?php echo INDEX_OPTION_TIME_LIFETIME ?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
													</div>
												</li>
											</ul>
										</div>
									</div>
									<div class="m-portlet__body">
										<div class="m-widget27 m-portlet-fit--sides">
											<div class="m-widget27__pic">
												<img src="/templates/default/assets/images/dashboard/users-bg.jpg" alt="">
												<h3 class="w-users-total m-widget27__title m--font-light">
													<span><span>+</span>0</span>
												</h3>
												<div class="m-widget27__btn">
													<button type="button" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--bolder"><?php echo INDEX_WIDGET_USERS_TITLE ?></button>
												</div>
											</div>
											<div class="m-widget27__container">

												<!-- begin::Nav pills -->
												<ul class="m-widget27__nav-items nav nav-pills nav-fill" role="tablist">
													<li class="m-widget27__nav-item nav-item">
														<a class="nav-link active" data-toggle="pill" href="#w-users-tab1"><?php echo INDEX_WIDGET_USERS_TAB1_TITLE ?></a>
													</li>
													<li class="m-widget27__nav-item nav-item">
														<a class="nav-link" data-toggle="pill" href="#w-users-tab2"><?php echo INDEX_WIDGET_USERS_TAB2_TITLE ?></a>
													</li>
													<li class="m-widget27__nav-item nav-item">
														<a class="nav-link" data-toggle="pill" href="#w-users-tab3"><?php echo INDEX_WIDGET_USERS_TAB3_TITLE ?></a>
													</li>
												</ul>

												<!-- end::Nav pills -->

												<!-- begin::Tab Content -->
												<div class="m-widget27__tab tab-content m-widget27--no-padding">
													<div id="w-users-tab1" class="tab-pane active">
														<div class="row  align-items-center">
															<div class="col">
																<div id="m_chart_users_tab_1" class="m-widget27__chart" style="height: 160px">
																	<div class="w-users-tab1-chtitle m-widget27__stat">0</div>
																</div>
															</div>
															<div class="col">
																<div class="m-widget27__legends w-legends">																	
																</div>
															</div>
														</div>
													</div>
													<div id="w-users-tab2" class="tab-pane fade">
														<div class="row  align-items-center">
															<div class="col">
																<div id="m_chart_users_tab_2" class="m-widget27__chart" style="height: 160px">
																	<div class="w-users-tab2-chtitle m-widget27__stat">0</div>
																</div>
															</div>
															<div class="col">
																<div class="m-widget27__legends w-legends">														
																</div>
															</div>
														</div>
													</div>
													<div id="w-users-tab3" class="tab-pane fade">
														<div class="row  align-items-center">
															<div class="col">
																<div id="m_chart_users_tab_3" class="m-widget27__chart" style="height: 160px">
																	<div class="w-users-tab3-chtitle m-widget27__stat">0</div>
																</div>
															</div>
															<div class="col">
																<div class="m-widget27__legends w-legends">																	
																</div>
															</div>
														</div>
													</div>													
												</div>

												<!-- end::Tab Content -->
											</div>
										</div>
									</div>
								</div>
								<!--end:: Widgets/users-->
							</div>
							<div class="col-xl-4">
								<!--begin:: Widgets/devices-->
								<div id="w-devices" class="m-portlet m-portlet--head-overlay m-portlet--full-height  m-portlet--rounded-force">
									<div class="m-portlet__head m-portlet__head--fit-">
										<div class="m-portlet__head-caption">
											<div class="m-portlet__head-title">
												<h3 class="m-portlet__head-text m--font-light">
													<?php echo INDEX_WIDGET_DEVICES ?>
												</h3>
											</div>
										</div>
										<div class="m-portlet__head-tools">
											<ul class="m-portlet__nav">
												<li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" m-dropdown-toggle="hover">
													<a href="#" class="w-options m-portlet__nav-link m-dropdown__toggle dropdown-toggle btn btn--sm m-btn--pill m-btn btn-outline-light m-btn--hover-light">
														<?php echo INDEX_OPTION_TIME_LIFETIME ?>
													</a>
													<div class="m-dropdown__wrapper">
														<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
														<div class="m-dropdown__inner">
															<div class="m-dropdown__body">
																<div class="m-dropdown__content">
																	<ul class="m-nav">
																		<li class="m-nav__item">
																			<a href="#" class="w-option m-nav__link" data-day="7">
																				<span class="m-nav__link-text"><?php echo INDEX_OPTION_TIME_7DAYS ?></span>
																			</a>
																		</li>
																		<li class="m-nav__item">
																			<a href="#" class="w-option m-nav__link" data-day="30">
																				<span class="m-nav__link-text"><?php echo INDEX_OPTION_TIME_30DAYS ?></span>
																			</a>
																		</li>
																		<li class="m-nav__item">
																			<a href="#" class="w-option m-nav__link" data-day="180">
																				<span class="m-nav__link-text"><?php echo INDEX_OPTION_TIME_180DAYS ?></span>
																			</a>
																		</li>
																		<li class="m-nav__item">
																			<a href="#" class="w-option m-nav__link" data-day="365">
																				<span class="m-nav__link-text"><?php echo INDEX_OPTION_TIME_365DAYS ?></span>
																			</a>
																		</li>
																		<li class="m-nav__item">
																			<a href="#" class="w-option m-nav__link" data-day="0">
																				<span class="m-nav__link-text"><?php echo INDEX_OPTION_TIME_LIFETIME ?></span>
																			</a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
													</div>
												</li>
											</ul>
										</div>
									</div>
									<div class="m-portlet__body">
										<div class="m-widget27 m-portlet-fit--sides">
											<div class="m-widget27__pic">
												<img src="/templates/default/assets/images/dashboard/devices-bg.jpg" alt="">
												<h3 class="w-devices-total m-widget27__title m--font-light">
													<span><span>+</span>0</span>
												</h3>
												<div class="m-widget27__btn">
													<button type="button" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--bolder"><?php echo INDEX_WIDGET_DEVICES_TITLE ?></button>
												</div>
											</div>
											<div class="m-widget27__container">

												<!-- begin::Nav pills -->
												<ul class="m-widget27__nav-items nav nav-pills nav-fill" role="tablist">
													<li class="m-widget27__nav-item nav-item">
														<a class="nav-link active" data-toggle="pill" href="#w-devices-tab1"><?php echo INDEX_WIDGET_DEVICES_TAB1_TITLE ?></a>
													</li>
													<li class="m-widget27__nav-item nav-item">
														<a class="nav-link" data-toggle="pill" href="#w-devices-tab2"><?php echo INDEX_WIDGET_DEVICES_TAB2_TITLE ?></a>
													</li>
													<li class="m-widget27__nav-item nav-item">
														<a class="nav-link" data-toggle="pill" href="#w-devices-tab3"><?php echo INDEX_WIDGET_DEVICES_TAB3_TITLE ?></a>
													</li>
												</ul>

												<!-- end::Nav pills -->

												<!-- begin::Tab Content -->
												<div class="m-widget27__tab tab-content m-widget27--no-padding">
													<div id="w-devices-tab1" class="tab-pane active">
														<div class="row  align-items-center">
															<div class="col">
																<div id="m_chart_devices_tab_1" class="m-widget27__chart" style="height: 160px">
																	<div class="w-devices-tab1-chtitle m-widget27__stat">0</div>
																</div>
															</div>
															<div class="col">
																<div class="m-widget27__legends w-legends">																	
																</div>
															</div>
														</div>
													</div>
													<div id="w-devices-tab2" class="tab-pane fade">
														<div class="row  align-items-center">
															<div class="col">
																<div id="m_chart_devices_tab_2" class="m-widget27__chart" style="height: 160px">
																	<div class="w-devices-tab2-chtitle m-widget27__stat">0</div>
																</div>
															</div>
															<div class="col">
																<div class="m-widget27__legends w-legends">																	
																</div>
															</div>
														</div>
													</div>
													<div id="w-devices-tab3" class="tab-pane fade">
														<div class="row  align-items-center">
															<div class="col">
																<div id="m_chart_devices_tab_3" class="m-widget27__chart" style="height: 160px">
																	<div class="w-devices-tab3-chtitle m-widget27__stat">0</div>
																</div>
															</div>
															<div class="col">
																<div class="m-widget27__legends w-legends">																	
																</div>
															</div>
														</div>
													</div>
												</div>

												<!-- end::Tab Content -->
											</div>
										</div>
									</div>
								</div>
								<!--end:: Widgets/devices-->
							</div>
						</div>

						<div class="row">
							<div class="col-xl-4">
								<!--begin:: Widgets/account-->
								<div class="m-portlet m-portlet--bordered-semi m-portlet--widget-fit m-portlet--full-height m-portlet--skin-light  m-portlet--rounded-force">
									<div class="m-portlet__head">
										<div class="m-portlet__head-caption">
											<div class="m-portlet__head-title">
												<h3 class="m-portlet__head-text m--font-light">
													<?php echo INDEX_WIDGET_ACCOUNT ?>
												</h3>
											</div>
										</div>										
									</div>
									<div class="m-portlet__body">
										<div class="m-widget17">
											<div class="m-widget17__visual m-widget17__visual--chart m-portlet-fit--top m-portlet-fit--sides m--bg-danger">
												<div class="m-widget17__chart" style="height:150px;">
												</div>
											</div>
											<div class="m-widget17__stats">
												<div class="m-widget17__items m-widget17__items-col1">
													<div class="m-widget17__item">
														<span class="m-widget17__icon">
															<i class="flaticon-globe m--font-brand"></i>															
														</span>
														<span class="m-widget17__subtitle">															
															<?php echo $dev->license->name ?>
														</span>
														<span class="m-widget17__desc">
															<?php echo INDEX_WIDGET_ACCOUNT_LICENSE_NAME ?>
														</span>
													</div>
													<div class="m-widget17__item">
														<span class="m-widget17__icon">
															<i class="flaticon-shapes m--font-success"></i>
														</span>
														<span class="m-widget17__subtitle">
															<?php echo nameNumber($dev->stats->total_devices,1) . " / " . nameNumber($dev->license->total_devices,1) ?>
														</span>
														<span class="m-widget17__desc">
															<?php echo INDEX_WIDGET_ACCOUNT_TOTAL_DEVICES ?>
														</span>
													</div>	
													<div class="m-widget17__item">
														<span class="m-widget17__icon">
															<i class="flaticon-layers m--font-accent"></i>
														</span>
														<span class="m-widget17__subtitle">
															<?php echo nameNumber($dev->stats->api_apps,1) . " / " . nameNumber($dev->license->api_apps,1) ?>
														</span>
														<span class="m-widget17__desc">
															<?php echo INDEX_WIDGET_ACCOUNT_API_APPS ?>
														</span>
													</div>																										
												</div>
												<div class="m-widget17__items m-widget17__items-col2">
													<div class="m-widget17__item">
														<span class="m-widget17__icon">
															<i class="flaticon-paper-plane m--font-info"></i>
														</span>
														<span class="m-widget17__subtitle">
															<?php 
																$devs = ($dev->stats->public==1 || $dev->stats->public==2)?INDEX_WIDGET_ACCOUNT_PUBLIC:INDEX_WIDGET_ACCOUNT_PRIVATE;
																$apps = ($dev->stats->public==1 || $dev->stats->public==3)?INDEX_WIDGET_ACCOUNT_PUBLIC:INDEX_WIDGET_ACCOUNT_PRIVATE;
																echo $devs  . ' / ' . $apps; 
															?>
														</span>		
														<span class="m-widget17__desc">
															<?php echo INDEX_WIDGET_ACCOUNT_PUBLICPRIVATE ?>
														</span>														
													</div>													
													<div class="m-widget17__item">
														<span class="m-widget17__icon">
															<i class="flaticon-customer m--font-danger"></i>
														</span>
														<span class="m-widget17__subtitle">
															<?php echo nameNumber($dev->stats->total_users,1) ?>
														</span>
														<span class="m-widget17__desc">
															<?php echo INDEX_WIDGET_ACCOUNT_TOTAL_USERS ?>
														</span>
													</div>
													<div class="m-widget17__item">
														<span class="m-widget17__icon">
															<i class="flaticon-analytics m--font-warning"></i>
														</span>
														<span class="m-widget17__subtitle">
															<?php echo nameBytes($dev->stats->total_bytes,1) . " / " . nameBytes($dev->license->total_bytes,1) ?>
														</span>
														<span class="m-widget17__desc">
															<?php echo INDEX_WIDGET_ACCOUNT_TOTAL_LOGS ?>
														</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

								<!--end:: Widgets/account-->
							</div>
							<div class="col-xl-4">
								<!--begin:: Widgets/info-->
								<div class="m-portlet m-portlet--head-overlay m-portlet--full-height   m-portlet--rounded-force">
									<div class="m-portlet__head m-portlet__head--fit">
										<div class="m-portlet__head-caption">
											<div class="m-portlet__head-title">
												<h3 class="m-portlet__head-text m--font-light">
													<?php echo INDEX_WIDGET_INFO ?>
												</h3>
											</div>
										</div>										
									</div>
									<div class="m-portlet__body">
										<div class="m-widget28">
											<div class="m-widget28__pic m-portlet-fit--sides"></div>
											<div class="m-widget28__container">

												<!-- begin::Nav pills -->
												<ul class="m-widget28__nav-items nav nav-pills nav-fill" role="tablist">
													<li class="m-widget28__nav-item nav-item">
														<a class="nav-link active" data-toggle="pill" href="#menu11"><span><i class="fa flaticon-network"></i></span><span><?php echo INDEX_WIDGET_INFO_ORG?></span></a>
													</li>
													<li class="m-widget28__nav-item nav-item">
														<a class="nav-link" data-toggle="pill" href="#menu21"><span><i class="fa flaticon-avatar"></i></span><span><?php echo INDEX_WIDGET_INFO_CTC ?></span></a>
													</li>
													<li class="m-widget28__nav-item nav-item">
														<a class="nav-link" data-toggle="pill" href="#menu31"><span><i class="fa flaticon-coins"></i></span><span><?php echo INDEX_WIDGET_INFO_BILLING ?></span></a>
													</li>
												</ul>

												<!-- end::Nav pills -->

												<!-- begin::Tab Content -->
												<div class="m-widget28__tab tab-content">
													<div id="menu11" class="m-widget28__tab-container tab-pane active">
														<div class="m-widget28__tab-items">
															<div class="m-widget28__tab-item">
																<span><?php echo INDEX_WIDGET_INFO_ORG_NAME ?></span>
																<span><?php echo $dev->org_name ?></span>
															</div>
															<div class="m-widget28__tab-item">
																<span><?php echo INDEX_WIDGET_INFO_ORG_COUNTRY ?></span>
																<span><?php echo $dev->org_country ?></span>
															</div>
															<div class="m-widget28__tab-item">
																<span><?php echo INDEX_WIDGET_INFO_ORG_BORN ?></span>
																<span><?php echo date_format(date_create($dev->born_date),"F jS, Y") ?></span>
															</div>
															<div class="m-widget28__tab-item">
																<span><?php echo INDEX_WIDGET_INFO_ORG_STATUS ?></span>
																<span>
																<?php echo $dev->status==DEV_OPTION_SUSPENDED?INDEX_WIDGET_INFO_ORG_STATUS_SUSPENDED:$dev->status==DEV_OPTION_INACTIVE?INDEX_WIDGET_INFO_ORG_STATUS_INACTIVE:$dev->status==DEV_OPTION_ACTIVE?INDEX_WIDGET_INFO_ORG_STATUS_ACTIVE:INDEX_WIDGET_INFO_ORG_STATUS_UNKNOWN ?>
																<?php 
																	$expdate = $dev->expdate!==null?new DateTime($dev->expdate, new DateTimeZone("UTC")):null;
																	echo $expdate!==null?'(' . INDEX_WIDGET_INFO_ORG_STATUS_EXP . ' ' . $expdate->format('M jS, Y') . ')':''  
																?>
																</span>
															</div>
														</div>
													</div>
													<div id="menu21" class="m-widget28__tab-container tab-pane fade">
														<div class="m-widget28__tab-items">
															<div class="m-widget28__tab-item">
																<span><?php echo INDEX_WIDGET_INFO_CTC_NAME ?></span>
																<span><?php echo $dev->user_fname . ' ' .$dev->user_lname ?></span>
															</div>
															<div class="m-widget28__tab-item">
																<span><?php echo INDEX_WIDGET_INFO_CTC_TITLE ?></span>
																<span><?php echo $dev->user_title ?></span>
															</div>
															<div class="m-widget28__tab-item">
																<span><?php echo INDEX_WIDGET_INFO_CTC_EMAIL ?></span>
																<span><?php echo $dev->user_email ?></span>
															</div>
															<div class="m-widget28__tab-item">
																<span><?php echo INDEX_WIDGET_INFO_CTC_PHONE ?></span>
																<span><?php echo $dev->user_phone ?></span>
															</div>
														</div>
													</div>
													<div id="menu31" class="m-widget28__tab-container tab-pane fade">
														<div class="m-widget28__tab-items">
															<div class="m-widget28__tab-item">
																<span><?php echo INDEX_WIDGET_INFO_BILLING_METHOD ?></span>
																<span></span>
															</div>
															<div class="m-widget28__tab-item">
																<span><?php echo INDEX_WIDGET_INFO_BILLING_PAYMENT ?></span>
																<span></span>
															</div>
															<div class="m-widget28__tab-item">
																<span><?php echo INDEX_WIDGET_INFO_BILLING_INVOICE ?></span>
																<span></span>
															</div>
														</div>
													</div>
												</div>

												<!-- end::Tab Content -->
											</div>
										</div>
									</div>
								</div>

								<!--end:: Widgets/info-->
							</div>
							<div class="col-xl-4">
								<!--begin:: Widgets/Blog-->
								<div id="w-blog" class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--rounded-force">
									<div class="m-portlet__head m-portlet__head--fit">
										<div class="m-portlet__head-caption">
											<div class="m-portlet__head-action">
												<button type="button" class="btn-blog btn btn-sm m-btn--pill btn-brand">Blog</button>
											</div>
										</div>
									</div>
									<div class="m-portlet__body">
										<div class="m-widget19">
											<div class="m-widget19__pic m-portlet-fit--top m-portlet-fit--sides" style="min-height-: 286px">
												<img src="/templates/default/assets/images/notification_bg.jpg" alt="">
												<h3 class="m-widget19__title m--font-light">
													Loading...
												</h3>
												<div class="m-widget19__shadow"></div>
											</div>
											<div class="m-widget19__content">
												<div class="m-widget19__header">
													<div class="m-widget19__user-img">
														<img class="m-widget19__img" src="" alt="">
													</div>
													<div class="m-widget19__info">
														<span class="m-widget19__username">
															
														</span><br>
														<span class="m-widget19__time">
															
														</span>
													</div>
													<div class="m-widget19__stats">
														<span class="m-widget19__number m--font-brand">
															0
														</span>
														<span class="m-widget19__comment">
															<?php echo INDEX_WIDGET_BLOG_COMMENTS ?>
														</span>
													</div>
												</div>
												<div class="m-widget19__body">
													Loading...
												</div>
											</div>
											<div class="m-widget19__action">
												<button type="button" class="btn-more btn m-btn--pill btn-secondary m-btn m-btn--hover-brand m-btn--custom">Read More</button>
											</div>
										</div>
									</div>
								</div>

								<!--end:: Widgets/Blog-->
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
		<script src="templates/default/assets/js/index.js" type="text/javascript"></script>
	</body>
</html>