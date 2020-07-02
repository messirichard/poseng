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
							<div class="m-portlet__head px-3" style="height:auto">
								<div class="m-portlet__head-caption" style="width:100%">
									<div class="row m-portlet__head-title my-3" style="width:calc(100% + 20px);">										
										<div class="m-2 col-12">
											<div class="m-input-icon m-input-icon--left">
												<input type="text" class="widget-search form-control m-input" placeholder="<?php echo DEFAULT_TITLE_SEARCH_PLACEHOLDER ?>">
												<span class="m-input-icon__icon m-input-icon__icon--left"><span><i class="la la-search"></i></span></span>
											</div>
										</div>
									</div>									
								</div>
							</div>
							<div class="m-portlet__body">					
								<div class="widget-cards row align-items-center">								
									<div class="widget-card col-xl-2 col-lg-3 col-md-4 col-sm-5 col-6" data-toggle="modal" data-target="#widget_add">
										<div class="m-portlet m-portlet--bordered-semi m-portlet--full-height  m-portlet--rounded-force widget-container">
											<div class="m-portlet__head m-portlet__head--fit p-1 text-right">&nbsp;
												<div class="widget-card-button">
													<a href="#" class="card-edit my-1 mr-1 btn btn-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill m-btn--air">
														<i class="la flaticon-edit"></i>
													</a>
													<a href="#" class="card-del my-1 mr-1 btn btn-metal m-btn m-btn--icon m-btn--icon-only m-btn--pill m-btn--air">
														<i class="la la-trash"></i>
													</a>
												</div>
											</div>
											<div class="m-portlet__body">
												<div class="widget-card-body">
													<div class="m-portlet-fit--top m-portlet-fit--sides widget-thumb">
														<img src="/templates/default/assets/images/add-widget.jpg" alt="">														
														<div class="m-widget19__shadow"></div>
													</div>
													<div class="widget-title text-center">
														<h6><?php echo DEFAULT_TITLE_BUTTON_DECLARE ?></h6>
													</div>													
												</div>
											</div>
										</div>
									</div>
									
								</div>
							</div>
						</div>	

						<div class="modal fade" id="widget_add" tabindex="false" role="dialog" aria-labelledby="widgetAddModal" aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="widgetAddModal"><?php echo DEFAULT_TITLE ?></h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<form method="POST" class="m-form">
										<div class="modal-body">
											<div class="row m-0 my-3">
												<div class="px-3 col-12" id="default_info"></div>
												<div class="px-3 col-md-5 col-sm-12">
													<div class="border-3 widget-thumb-add" style="border: 1px solid #ced4da;border-radius: 15px;">
														<input class="widgets-idx" type="hidden" name="default_idx" value="">
														<img class="widgets-preview" src="/templates/default/assets/images/default-ss.jpg" alt="">
														<img class="widgets-thumb-img" src="/templates/default/assets/images/cam.png" border="0" >
														<input class="widgets-input file-avatar" type="file" name="default_logo" accept="image/gif, image/jpeg, image/png">
													</div>
													<div id="default_logo_info" class="form-control-feedback"><?php echo DEFAULT_LOGO_INFO ?></div>													
												</div>
												<div class="px-3 col-md-7 col-sm-12">
													<div class="row">
														<div class="form-group m-form__group p-3 col-12">
															<label for="default_model" class="form-control-label"><?php echo DEFAULT_MODEL ?></label>
															<input name="default_model" type="text" class="widgets-input form-control" id="default_model" maxlength="16">
														</div>
														<div class="form-group m-form__group p-3 col-12">
															<label for="default_desc" class="form-control-label"><?php echo DEFAULT_DESC ?></label>
															<input name="default_desc" type="text" class="widgets-input form-control" id="default_desc" placeholder="<?php echo DEFAULT_DESC_PLACEHOLDER ?>"  maxlength="70">
														</div>
														<div class="form-group m-form__group p-3 col-12">
															<label for="default_select_api" class="form-control-label"><?php echo DEFAULT_API ?></label>
															<select class="form-control m-select2" id="default_select_api" name="default_select_api">
															</select>
														</div>
														<div class="form-group m-form__group px-3 col-12">
															<label for="default_select_widget" class="form-control-label"><?php echo DEFAULT_WIDGET ?></label>
															<select class="form-control m-select2" id="default_select_widget" name="default_select_widget">
															</select>
														</div>
													</div>													
												</div>
											</div>
											<div class="row m-0 my-3">											
												<div class="px-3 col-12">
													<label for="default_title" class="form-control-label"><?php echo DEFAULT_DEFTITLE ?></label>
													<input id="default_title" type="text" class="widgets-input form-control" value="" name="default_title" type="text" maxlength="35">
												</div>
											</div>
											<div class="row m-0 my-3">											
												<div class="px-3 col-md-6 col-sm-12">
													<label for="default_width" class="form-control-label"><?php echo DEFAULT_WIDTH ?></label>
													<input id="default_width" type="text" class="widgets-number widgets-input form-control" value="" name="default_width" type="text">
												</div>
												<div class="px-3 col-md-6 col-sm-12">
													<label for="default_height" class="form-control-label"><?php echo DEFAULT_HEIGHT ?></label>
													<input id="default_height" type="text" class="widgets-number widgets-input form-control" value="" name="default_height" type="text">
												</div>
											</div>
											<div class="row m-0 my-3">
												<div class="px-3 col-12">
													<div class="m-separator m-separator--dashed"></div>
													<ul class="nav nav-tabs" role="tablist">
														<li class="nav-item">
															<a class="nav-link active" data-toggle="tab" href="#default_element_1" role="tab">Element</a>
														</li>														
													</ul>
													<div class="tab-content">
														<div class="tab-pane active" id="default_element_1" role="tabpanel">
															<div class="row m-0 my-3">
																<div class="form-group m-form__group py-3 px-3 col-md-6 col-sm-12">
																	<input class="widgets-element-id" type="hidden" name="default_element_id[]" value="">
																	<label class="form-control-label"><?php echo DEFAULT_ELEMENT_PARAMS_DATATYPE ?></label>
																	<input name="default_element_datatype[]" type="text" class="widgets-input form-control default_element_datatype" placeholder="<?php echo DEFAULT_ELEMENT_PARAMS_DATATYPE_ANY ?>">
																</div>
																<div class="form-group m-form__group py-3 px-3 col-md-6 col-sm-12">
																	<label class="widgets-element-param form-control-label"><?php echo DEFAULT_ELEMENT_PARAM ?> <span></span></label>
																	<input name="default_element_param[]" type="text" class="widgets-input form-control default_element_param" placeholder="<?php echo DEFAULT_ELEMENT_PARAM_PLACEHOLDER ?>" maxlength="2000">
																</div>
															</div>
														</div>														
													</div>
												</div>
											</div>											
										</div>										
										<div class="modal-footer">
											<button id="default_close" type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo DEFAULT_BUTTON_CLOSE ?></button>
											<button id="default_create" type="button" class="btn btn-primary"><?php echo DEFAULT_BUTTON_CREATE ?></button>
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
		<script src="templates/default/assets/js/widgets-default.js" type="text/javascript"></script>
	</body>
</html>