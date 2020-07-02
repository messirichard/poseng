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
											<?php echo DEVICES_TITLE ?>
										</h3>
									</div>
								</div>
							</div>
							<div class="m-portlet__body">
								<!--begin: Selected Rows Group Action Form -->
								<div id="devices_with_selected" class="devices_with_selected collapse">
									<div class="row align-items-center">
										<div class="col-xl-12">
											<div class="m-form__group m-form__group--inline">
												<div class="m-form__label m-form__label-no-wrap">
													<label class="m--font-bold m--font-danger-"><?php echo DEVICES_WITH_SELECTED ?></label>
												</div>
												<div class="m-form__control">
													<div class="btn-toolbar">
														<div class="dropdown">
															<button type="button" class="btn btn-accent btn-sm dropdown-toggle" data-toggle="dropdown">
																<?php echo DEVICES_STATUS_UPDATE ?>
															</button>
															<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
																<a class="devices_rows_upd dropdown-item" href="#" data-upd="0"><?php echo DEVICES_STATUS_DEACTIVATED ?></a>
																<a class="devices_rows_upd dropdown-item" href="#" data-upd="1"><?php echo DEVICES_STATUS_ACTIVATE ?></a>
															</div>
														</div>
														&nbsp;&nbsp;&nbsp;
														<div class="dropdown">
															<button type="button" class="btn btn-accent btn-sm dropdown-toggle" data-toggle="dropdown">
																<?php echo DEVICES_LOGS_UPDATE ?>
															</button>
															<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
																<a class="devices_rows_log dropdown-item" href="#" data-upd="0"><?php echo DEVICES_LOGS_NOTALLOW ?></a>
																<a class="devices_rows_log dropdown-item" href="#" data-upd="1"><?php echo DEVICES_LOGS_ALLOW ?></a>
																<a class="devices_rows_log dropdown-item" href="#" data-upd="2"><?php echo DEVICES_LOGS_MAX ?></a>
															</div>
														</div>
														&nbsp;&nbsp;&nbsp;
														<button class="btn btn-sm btn-danger" type="button" id="devices_rows_del"><?php echo DEVICES_OPTION_DELETE ?></button>	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!--end: Selected Rows Group Action Form -->
								
								<table class="table table-striped- table-bordered table-hover table-checkable" id="tbl_devices">
									<thead>
										<tr>
											<th><?php echo DEVICES_TABLE_COL1 ?></th>
											<th><?php echo DEVICES_TABLE_COL2 ?></th>
											<th><?php echo DEVICES_TABLE_COL3 ?></th>
											<th><?php echo DEVICES_TABLE_COL4 ?></th>
											<th><?php echo DEVICES_TABLE_COL5 ?></th>
											<th><?php echo DEVICES_TABLE_COL6 ?></th>
											<th><?php echo DEVICES_TABLE_COL7 ?></th>
											<th><?php echo DEVICES_TABLE_COL8 ?></th>
											<th><?php echo DEVICES_TABLE_COL9 ?></th>
											<th><?php echo DEVICES_TABLE_COL10 ?></th>
											<th><?php echo DEVICES_TABLE_COL11 ?></th>
											<th><?php echo DEVICES_TABLE_COL12 ?></th>
											<th><?php echo DEVICES_TABLE_COL13 ?></th>
											<th><?php echo DEVICES_TABLE_COL14 ?></th>
										</tr>
									</thead>
								</table>
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
		<script src="templates/default/assets/js/devices.js" type="text/javascript"></script>
	</body>
</html>