			<!-- BEGIN: Header -->
			<header id="m_header" class="m-grid__item    m-header " m-minimize-offset="200" m-minimize-mobile-offset="200">
				<div class="m-container m-container--fluid m-container--full-height">
					<div class="m-stack m-stack--ver m-stack--desktop">
						<!-- BEGIN: Brand -->
						<div class="m-stack__item m-brand  m-brand--skin-dark ">
							<div class="m-stack m-stack--ver m-stack--general">
								<div class="m-stack__item m-stack__item--middle m-brand__logo">
									<a href="/" class="m-brand__logo-wrapper">
										<img alt="<?php echo BUSINESS_NAME ?>" src="templates/default/assets/images/logo-white_trans.png" />
									</a>
								</div>
								<div class="m-stack__item m-stack__item--middle m-brand__tools">
									<!-- BEGIN: Left Aside Minimize Toggle -->
									<a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block">
										<span></span>
									</a>
									<!-- END -->
									<!-- BEGIN: Responsive Aside Left Menu Toggler -->
									<a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
										<span></span>
									</a>
									<!-- END -->
									<!-- BEGIN: Responsive Header Menu Toggler -->
									<a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
										<span></span>
									</a>
									<!-- END -->
									<!-- BEGIN: Topbar Toggler -->
									<a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
										<i class="flaticon-more"></i>
									</a>
									<!-- BEGIN: Topbar Toggler -->
								</div>
							</div>
						</div>
						<!-- END: Brand -->
						
						<div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
							<!-- BEGIN: Horizontal Menu -->
							<button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark " id="m_aside_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
							<div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark ">
								<ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
									<li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" m-menu-link-redirect="1" aria-haspopup="true"><a href="javascript:;" class="m-menu__link m-menu__toggle" title="<?php echo NAV_TOP_SECTION1?>"><i
											 class="m-menu__link-icon flaticon-profile"></i><span class="m-menu__link-text"><?php echo NAV_TOP_SECTION1?></span><i class="m-menu__hor-arrow la la-angle-down"></i><i class="m-menu__ver-arrow la la-angle-right"></i></a>
										<div class="m-menu__submenu m-menu__submenu--classic m-menu__submenu--left"><span class="m-menu__arrow m-menu__arrow--adjust"></span>
											<ul class="m-menu__subnav">
												<li class="m-menu__item " aria-haspopup="true"><a href="/page-account-profile" class="m-menu__link "><i class="m-menu__link-icon flaticon-profile-1"></i><span class="m-menu__link-text"><?php echo NAV_TOP_SECTION1_1 ?></span></a></li>
<!-- 												<li class="m-menu__item " aria-haspopup="true"><a href="/page-account-billing" class="m-menu__link "><i class="m-menu__link-icon flaticon-file"></i><span class="m-menu__link-text"><?php echo NAV_TOP_SECTION1_2 ?></span></a></li>
 -->												<li class="m-menu__item " aria-haspopup="true"><a href="/page-account-subscription" class="m-menu__link "><i class="m-menu__link-icon flaticon-rocket"></i><span class="m-menu__link-text"><?php echo NAV_TOP_SECTION1_3 ?></span></a></li>
												<li class="m-menu__item " aria-haspopup="true"><a href="/page-account-config" class="m-menu__link "><i class="m-menu__link-icon flaticon-cogwheel-2"></i><span class="m-menu__link-text"><?php echo NAV_TOP_SECTION1_4 ?></span></a></li>
											</ul>
										</div>
									</li>
									<li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" m-menu-submenu-toggle="click" m-menu-link-redirect="1" aria-haspopup="true">
										<a href="<?php echo DOMAIN_ASK ?>" target="_blank" class="m-menu__link" title="<?php echo NAV_TOP_SECTION2?>"><i class="m-menu__link-icon flaticon-lifebuoy"></i><span class="m-menu__link-text"><?php echo NAV_TOP_SECTION2?></span></a>										
									</li>									
								</ul>
							</div>
							<!-- END: Horizontal Menu -->

							<!-- BEGIN: Topbar -->
							<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general m-stack--fluid">
								<div class="m-stack__item m-topbar__nav-wrapper">
									<ul class="m-topbar__nav m-nav m-nav--inline">									
										<li class="m-nav__item m-topbar__notifications m-topbar__notifications--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-right m-dropdown--mobile-full-width" m-dropdown-toggle="click"
										 m-dropdown-persistent="1">
											<a href="#" class="m-nav__link m-dropdown__toggle" id="m_topbar_notification_icon">
												<span class="m-nav__link-badge m-badge m-badge--dot m-badge--dot-small m-badge--danger" style="display:none"></span>
												<span class="m-nav__link-icon"><i class="flaticon-alarm"></i></span>
											</a>
											<div class="m-dropdown__wrapper">
												<span class="m-dropdown__arrow m-dropdown__arrow--right"></span>
												<div class="m-dropdown__inner">
													<div class="m-dropdown__header m--align-center" style="background: url(templates/default/assets/images/notification_bg.jpg); background-size: cover;">
														<span class="m-dropdown__header-title">0 <?php echo NAV_TOP_NOTIF_NEW ?></span>
														<span class="m-dropdown__header-subtitle"><?php echo NAV_TOP_NOTIF_NOTIFICATIONS ?></span>
													</div>
													<div class="m-dropdown__body">
														<div class="m-dropdown__content">															
															<div class="tab-content">
																<div class="tab-pane active" id="topbar_notifications_notifications" role="tabpanel">
																	<div class="m-scrollable" data-scrollable="true" data-height="250" data-mobile-height="200">
																		<div class="m-list-timeline m-list-timeline--skin-light">
																			<div class="m-list-timeline__items">																				
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</li>																				
										<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
										 m-dropdown-toggle="click">
											<a href="#" class="m-nav__link m-dropdown__toggle">
												<span class="m-topbar__userpic">
													<img src="<?php echo $dev->user_avatar ?>" class="m--img-rounded m--marginless" alt="" />
												</span>
												<span class="m-topbar__username m--hide">Nick</span>
											</a>
											<div class="m-dropdown__wrapper">
												<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
												<div class="m-dropdown__inner">
													<div class="m-dropdown__header m--align-center" style="background: url(templates/default/assets/images/user_profile_bg.jpg); background-size: cover;">
														<div class="m-card-user m-card-user--skin-dark">
															<div class="m-card-user__pic">
																<img src="<?php echo $dev->user_avatar ?>" class="m--img-rounded m--marginless" alt="" />

																<!--
						<span class="m-type m-type--lg m--bg-danger"><span class="m--font-light">S<span><span>
						-->
															</div>
															<div class="m-card-user__details">
																<span class="m-card-user__name m--font-weight-500"><?php echo $dev->user_fname . ' ' . $dev->user_lname ?></span>
																<a href="/page-account-profile" class="m-card-user__email m--font-weight-300 m-link"><?php echo $dev->user_email ?></a>
															</div>
														</div>
													</div>
													<div class="m-dropdown__body">
														<div class="m-dropdown__content">
															<ul class="m-nav m-nav--skin-light">
																<li class="m-nav__section m--hide">
																	<span class="m-nav__section-text">Section</span>
																</li>
																<li class="m-nav__item">
																	<a href="/page-account-profile" class="m-nav__link">
																		<i class="m-nav__link-icon flaticon-profile-1"></i>
																		<span class="m-nav__link-title"><?php echo NAV_TOP_SECTION1_1 ?></span>
																	</a>
																</li>
<!-- 																<li class="m-nav__item">
																	<a href="/page-account-billing" class="m-nav__link">
																		<i class="m-nav__link-icon flaticon-file"></i>
																		<span class="m-nav__link-text"><?php echo NAV_TOP_SECTION1_2 ?></span>
																	</a>
																</li>
 -->																<li class="m-nav__item">
																	<a href="/page-account-subscription" class="m-nav__link">
																		<i class="m-nav__link-icon flaticon-rocket"></i>
																		<span class="m-nav__link-text"><?php echo NAV_TOP_SECTION1_3 ?></span>
																	</a>
																</li>
																<li class="m-nav__item">
																	<a href="/page-account-config" class="m-nav__link">
																		<i class="m-nav__link-icon flaticon-cogwheel-2"></i>
																		<span class="m-nav__link-text"><?php echo NAV_TOP_SECTION1_4 ?></span>
																	</a>
																</li>
																<li class="m-nav__separator m-nav__separator--fit">
																</li>
																<li class="m-nav__item">
																	<a href="<?php echo DOMAIN_ASK ?>" class="m-nav__link">
																		<i class="m-nav__link-icon flaticon-users"></i>
																		<span class="m-nav__link-text"><?php echo NAV_TOP_SECTION2 ?></span>
																	</a>
																</li>																
																<li class="m-nav__separator m-nav__separator--fit">
																</li>
																<li class="m-nav__item">
																	<a href="/page-logout" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder"><?php echo NAV_LOGOUT ?></a>
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

							<!-- END: Topbar -->
						</div>
					</div>
				</div>
			</header>
			<!-- END: Header -->
