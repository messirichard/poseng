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
						<div class="m-portlet m-portlet--mobile">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text">
											<?php echo API_TITLE ?>
										</h3>
									</div>
								</div>
								<div class="m-portlet__head-tools">
									<ul class="m-portlet__nav">
										<li class="m-portlet__nav-item">
											<a href="#" class="btn btn-info m-btn m-btn--custom m-btn--icon m-btn--air" data-toggle="modal" data-target="#api_add">
												<span>
													<i class="la la-plus"></i>
													<span><?php echo API_NEW ?></span>
												</span>
											</a>
										</li>
									</ul>
								</div>								
							</div>
							<div class="m-portlet__body">
								<!--begin: Selected Rows Group Action Form -->
								<div id="api_with_selected" class="api_with_selected collapse">
									<div class="row align-items-center">
										<div class="col-xl-12">
											<div class="m-form__group m-form__group--inline">
												<div class="m-form__label m-form__label-no-wrap">
													<label class="m--font-bold m--font-danger-"><?php echo API_WITH_SELECTED ?></label>
												</div>
												<div class="m-form__control">
													<div class="btn-toolbar">
														<div class="dropdown">
															<button type="button" class="btn btn-accent btn-sm dropdown-toggle" data-toggle="dropdown">
																<?php echo API_STATE_UPDATE ?>
															</button>
															<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
																<a class="api_rows_upd dropdown-item" href="#" data-upd="<?php echo API_OPTION_DEACTIVE ?>"><?php echo API_STATE_DEACTIVATED ?></a>
																<a class="api_rows_upd dropdown-item" href="#" data-upd="<?php echo API_OPTION_DEVICE ?>"><?php echo API_STATE_DEVICE ?></a>
																<a class="api_rows_upd dropdown-item" href="#" data-upd="<?php echo API_OPTION_APP ?>"><?php echo API_STATE_APP ?></a>
															</div>
														</div>
														&nbsp;&nbsp;&nbsp;
														<button class="btn btn-sm btn-danger" type="button" id="api_rows_del"><?php echo API_STATE_DELETE ?></button>														
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!--end: Selected Rows Group Action Form -->
								
								<table class="table table-striped- table-bordered table-hover table-checkable" id="tbl_api">
									<thead>
										<tr>
											<th><?php echo API_TABLE_COL1 ?></th>
											<th><?php echo API_TABLE_COL2 ?></th>
											<th><?php echo API_TABLE_COL3 ?></th>
											<th><?php echo API_TABLE_COL4 ?></th>
											<th><?php echo API_TABLE_COL5 ?></th>
										</tr>
									</thead>
								</table>
							</div>							
						</div>
						
						<div class="modal fade" id="api_add" tabindex="-1" role="dialog" aria-labelledby="apiAddModal" aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="apiAddModal"><?php echo API_ADD_TITLE ?></h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<form method="POST" class="m-form">
										<div class="modal-body">
												<div id="api_info"></div>
												<div class="form-group m-form__group">
													<label for="api_add_type"><?php echo API_ADD_TYPE ?></label>
													<div class="row">
														<div class="col-lg-6">
															<label class="m-option">
																<span class="m-option__control">
																	<span class="m-radio m-radio--brand m-radio--check-bold">
																		<input type="radio" name="api_type" value="1" checked>
																		<span></span>
																	</span>
																</span>
																<span class="m-option__label">
																	<span class="m-option__head">
																		<span class="m-option__title">
																			<?php echo API_ADD_TYPE_DEVICE_TITLE ?>
																		</span>
																		<span class="m-option__focus">
																		</span>
																	</span>
																	<span class="m-option__body">
																		<?php echo API_ADD_TYPE_DEVICE_DESC ?>
																	</span>
																</span>
															</label>
														</div>
														<div class="col-lg-6">
															<label class="m-option">
																<span class="m-option__control">
																	<span class="m-radio m-radio--brand m-radio--check-bold">
																		<input type="radio" name="api_type" value="2">
																		<span></span>
																	</span>
																</span>
																<span class="m-option__label">
																	<span class="m-option__head">
																		<span class="m-option__title">
																			<?php echo API_ADD_TYPE_APP_TITLE ?>
																		</span>
																		<span class="m-option__focus">
																		</span>
																	</span>
																	<span class="m-option__body">
																		<?php echo API_ADD_TYPE_APP_DESC ?>
																	</span>
																</span>
															</label>
														</div>
													</div>
												</div>
												<div class="form-group m-form__group">
													<label for="api_add_name" class="form-control-label"><?php echo API_ADD_NAME ?></label>
													<input name="api_name" type="text" class="form-control" id="api_add_name">
												</div>
												<div class="form-group">
													<label for="api_dev_id" class="form-control-label"><?php echo API_ADD_DEVID ?></label>
													<input name="api_devid" type="text" class="form-control m-input m-input--solid" id="api_dev_id" placeholder="<?php echo $dev->id ?>" readonly>
												</div>
												<div class="form-group">
													<label for="api_add_usr" class="form-control-label"><?php echo API_ADD_USERNAME ?></label>
													<input name="api_usr" type="text" class="form-control m-input m-input--solid" id="api_add_usr" placeholder="<?php echo API_ADD_USERNAME_PLACEHOLDER ?>" readonly>
												</div>
												<div class="form-group">
													<label for="api_add_pwd" class="form-control-label"><?php echo API_ADD_PASSWORD ?></label>
													<input name="api_pwd" type="text" class="form-control m-input m-input--solid" id="api_add_pwd" placeholder="<?php echo API_ADD_PASSWORD_PLACEHOLDER ?>" readonly>
												</div>											
												<div id="api_alert" class="form-group">
													<div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-danger alert-dismissible fade show" role="alert">
														<div class="m-alert__icon">
															<i class="flaticon-exclamation-1"></i>
															<span></span>
														</div>
														<div class="m-alert__text">
															<?php echo API_ADD_PASSWORD_DESC ?>
														</div>													
													</div>
												</div>
										</div>
										<div class="modal-footer">
											<button id="api_add_close" type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo API_ADD_BUTTON_CLOSE ?></button>
											<button id="api_add_create" type="button" class="btn btn-primary"><?php echo API_ADD_BUTTON_CREATE ?></button>
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
		<script src="templates/default/assets/js/api.js" type="text/javascript"></script>
	</body>
</html>