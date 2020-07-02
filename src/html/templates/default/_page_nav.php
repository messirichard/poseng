				<!-- BEGIN: Left Aside -->
				<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn"><i class="la la-close"></i></button>
				<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">

					<!-- BEGIN: Aside Menu -->
					<div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500" style="position: relative;">
						<ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
							<li class="m-menu__item  m-menu__item--active" aria-haspopup="true">
								<a href="/" class="m-menu__link ">
									<i class="m-menu__link-icon flaticon-home"></i>
									<span class="m-menu__link-title">
										<span class="m-menu__link-wrap">
											<span class="m-menu__link-text"><?php echo NAV_LEFT_SECTION1 ?></span>
										</span>
									</span>
								</a>
							</li>
							<li class="m-menu__section ">
								<h4 class="m-menu__section-text"><?php echo NAV_LEFT_SECTION2 ?></h4>
							</li>
							<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
								<a href="/page-api" class="m-menu__link m-menu__toggle">
									<i class="m-menu__link-icon flaticon-layers"></i>
									<span class="m-menu__link-text"><?php echo NAV_LEFT_SECTION2_1 ?></span>
								</a>
							</li>
							<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
								<a href="/page-devices" class="m-menu__link m-menu__toggle">
									<i class="m-menu__link-icon flaticon-shapes"></i>
									<span class="m-menu__link-text"><?php echo NAV_LEFT_SECTION2_2 ?></span>
								</a>
							</li>
							<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
								<a href="javascript:;" class="m-menu__link m-menu__toggle">
									<i class="m-menu__link-icon flaticon-interface-8"></i>
									<span class="m-menu__link-text"><?php echo NAV_LEFT_SECTION2_4 ?></span>
									<i class="m-menu__ver-arrow la la-angle-right"></i>
								</a>
								<div class="m-menu__submenu "><span class="m-menu__arrow"></span>
									<ul class="m-menu__subnav">
										<?php if ($dev->stats->api_apps) { ?>
										<li class="m-menu__item " aria-haspopup="true">
											<a href="/page-widgets-declare" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
												<span class="m-menu__link-text"><?php echo NAV_LEFT_SECTION2_4_1 ?><span>
											</a>
										</li>
										<?php } ?>
										<li class="m-menu__item " aria-haspopup="true">
											<a href="/page-widgets-default" class="m-menu__link ">
												<i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
												<span class="m-menu__link-text"><?php echo NAV_LEFT_SECTION2_4_2 ?></span>
											</a>
										</li>
 									</ul>
								</div>
							</li>
							<?php if ($dev->license->userbase) { ?>
							<li class="m-menu__item  m-menu__item--submenu" aria-haspopup="true" m-menu-submenu-toggle="hover">
								<a href="/page-users" class="m-menu__link m-menu__toggle">
									<i class="m-menu__link-icon flaticon-customer"></i>
									<span class="m-menu__link-text"><?php echo NAV_LEFT_SECTION2_3 ?></span>
								</a>
							</li>
							<?php } ?>							
						</ul>
					</div>
					<!-- END: Aside Menu -->
				</div>
				<!-- END: Left Aside -->