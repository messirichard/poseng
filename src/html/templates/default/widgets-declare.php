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
										<div class="m-2 col-12 col-sm-3 col-md-3 col-lg-2 col-xl-2">
											<h3 class="m-portlet__head-text">
												<?php echo WIDGETS_TITLE_SELECT_API ?>
											</h3>
										</div>
										<div class="m-2 col-12 col-sm-8 col-md-8 col-lg-4 col-xl-4">
											<select class="form-control m-select2" id="widget_select_api" name="widget_select_api">
											</select>
										</div>
										<div class="m-2 col-12 col-sm-12 col-md-12 col-lg-5 col-xl-5">
											<div class="m-input-icon m-input-icon--left">
												<input type="text" class="widget-search form-control m-input" placeholder="<?php echo WIDGETS_TITLE_SEARCH_PLACEHOLDER ?>">
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
													<a href="#" class="card-status my-1 mr-1 btn btn-danger m-btn m-btn--icon m-btn--icon-only m-btn--pill m-btn--air">
														<i class="la la-link"></i>
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
														<h6><?php echo WIDGETS_TITLE_BUTTON_DECLARE ?></h6>
													</div>													
												</div>
											</div>
										</div>
									</div>
									
								</div>
							</div>
						</div>	

						<div class="modal fade" id="widget_add" tabindex="-1" role="dialog" aria-labelledby="widgetAddModal" aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="widgetAddModal"><?php echo WIDGETS_ADD_TITLE ?></h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<form method="POST" class="m-form">
										<div class="modal-body">
											<div class="row m-0 my-3">
												<div class="px-3 col-12" id="widget_info"></div>
												<div class="px-3 col-md-5 col-sm-12">
													<div class="border-3 widget-thumb-add" style="border: 1px solid #ced4da;border-radius: 15px;">
														<input class="widgets-idx" type="hidden" name="widgets_idx" value="">
														<input class="widgets-api" type="hidden" name="widgets_api" value="">
														<img class="widgets-preview" src="/templates/default/assets/images/widget-ss.jpg" alt="">
														<img class="widgets-thumb-img" src="/templates/default/assets/images/cam.png" border="0" >
														<input class="widgets-input file-avatar" type="file" name="widgets_logo" accept="image/gif, image/jpeg, image/png">
													</div>	
													<div id="widgets_logo_info" class="form-control-feedback"><?php echo WIDGETS_LOGO_INFO ?></div>														
												</div>
												<div class="px-3 col-md-7 col-sm-12">
													<div class="row">
														<div class="form-group m-form__group p-3 col-12">
															<label for="widgets_add_id" class="form-control-label"><?php echo WIDGETS_ADD_ID ?></label>
															<input name="widgets_id" type="text" class="widgets-input form-control" id="widgets_add_id">
														</div>
														<div class="form-group m-form__group p-3 col-12">
															<label for="widgets_add_name" class="form-control-label"><?php echo WIDGETS_ADD_NAME ?></label>
															<input name="widgets_name" type="text" class="widgets-input form-control" id="widgets_add_name">
														</div>
														<div class="form-group m-form__group px-3 col-12">
															<label for="widgets_add_tags" class="form-control-label"><?php echo WIDGETS_ADD_TAGS ?></label>
															<select class="widgets-input form-control m-select2" id="widgets_add_tags" name="widgets_tags[]" multiple>																
															</select>
														</div>
													</div>													
												</div>
											</div>
											<div class="row m-0 my-3">											
												<div class="px-3 col-md-6 col-sm-12">
													<label for="widgets_add_minwidth" class="form-control-label"><?php echo WIDGETS_ADD_MIN_WIDTH ?></label>
													<input id="widgets_add_minwidth" type="text" class="widgets-number widgets-input form-control" value="" name="widgets_minwidth" type="text">
												</div>
												<div class="px-3 col-md-6 col-sm-12">
													<label for="widgets_add_maxwidth" class="form-control-label"><?php echo WIDGETS_ADD_MAX_WIDTH ?></label>
													<input id="widgets_add_maxwidth" type="text" class="widgets-number widgets-input form-control" value="" name="widgets_maxwidth" type="text">
												</div>
											</div>											
											<div class="row m-0 my-3">
												<div class="px-3 col-md-6 col-sm-12">
													<label for="widgets_add_minheight" class="form-control-label"><?php echo WIDGETS_ADD_MIN_HEIGHT ?></label>
													<input id="widgets_add_minheight" type="text" class="widgets-number widgets-input form-control" value="" name="widgets_minheight" type="text">
												</div>
												<div class="px-3 col-md-6 col-sm-12">
													<label for="widgets_add_maxheight" class="form-control-label"><?php echo WIDGETS_ADD_MAX_HEIGHT ?></label>
													<input id="widgets_add_maxheight" type="text" class="widgets-number widgets-input form-control" value="" name="widgets_maxheight" type="text">
												</div>
											</div>
											<div class="row m-0 my-3">
												<div class="px-3 col-12">
													<div class="m-separator m-separator--dashed"></div>
													<ul class="nav nav-tabs" role="tablist">
														<li class="nav-item">
															<a class="nav-link active" data-toggle="tab" href="#widgets_element_1" role="tab">New Element</a>
														</li>
														<li class="nav-item">
															<a class="nav-link widgets_element_add" data-toggle="tab" href="#" role="tab"><i class="la la-plus"></i></a>
														</li>
													</ul>
													<div class="tab-content">
														<div class="tab-pane active" id="widgets_element_1" role="tabpanel">
															<div class="row m-0 my-3">
																<div class="form-group m-form__group py-3 px-3 col-md-6 col-sm-12">
																	<label class="form-control-label"><?php echo WIDGETS_ADD_ELEMENT_ID ?></label>
																	<input name="widgets_element_id[]" type="text" class="widgets-input form-control">
																</div>
																<div class="form-group m-form__group py-3 px-3 col-md-6 col-sm-12">
																	<label class="form-control-label"><?php echo WIDGETS_ADD_ELEMENT_NAME ?></label>
																	<input name="widgets_element_name[]" type="text" class="widgets-input form-control widgets_element_name">
																</div>
															</div>
															<div class="row m-0 my-3">
																<div class="pt-3 px-3 col-lg-4 col-md-6 col-sm-12">
																	<label class="h-100 align-middle form-control-label mr-3"><?php echo WIDGETS_ADD_ELEMENT_SETTABLE ?></label>
																	<span class="m-switch m-switch--lg m-switch--icon">
																		<label>
																			<input class="widgets_add_settable_hidden" type="hidden" name="widgets_element_settable[widgets_element_1]" value="0">
																			<input class="widgets-input widgets_add_settable" type="checkbox" checked="checked" name="widgets_element_settable[widgets_element_1]" value="1">
																			<span></span>
																		</label>
																	</span>
																</div>
																<div class="pt-3 px-3 col-lg-4 col-md-6 col-sm-12">
																	<label class="h-100 align-middle form-control-label mr-3"><?php echo WIDGETS_ADD_ELEMENT_RETAINED ?></label>
																	<span class="m-switch m-switch--lg m-switch--icon">
																		<label>
																			<input class="widgets_add_retained_hidden" type="hidden" name="widgets_element_retained[widgets_element_1]" value="0">
																			<input class="widgets-input widgets_add_retained" type="checkbox" checked="checked" name="widgets_element_retained[widgets_element_1]" value="1">
																			<span></span>
																		</label>
																	</span>
																</div>
																<div class="px-3 col-lg-4 col-md-6 col-sm-12">
																	<label class="form-control-label"><?php echo WIDGETS_ADD_ELEMENT_PARAMS_MAX ?></label>
																	<input type="text" class="widgets-input widgets_add_element_maxparams form-control" value="" name="widgets_element_maxparams[]" type="text">
																</div>
															</div>
															<div class="row m-0 my-3">
																<div class="px-3 col-12">
																	<label class="form-control-label"><?php echo WIDGETS_ADD_ELEMENT_PARAMS_DATATYPE ?></label>
																	<input class="widgets_add_element_datatype_hidden" type="hidden" name="widgets_element_datatype[widgets_element_1][]" value="null">
																	<select class="widgets-input widgets_add_element_datatype form-control m-select2" name="widgets_element_datatype[widgets_element_1][]" multiple>
																		<?php foreach($elem_datatype as $dt) { ?>
																			<option value="<?php echo $dt ?>"><?php echo $dt ?></option>
																		<?php } ?>
																	</select>
																</div>
															</div>
														</div>														
													</div>
												</div>
											</div>
										</div>										
										<div class="modal-footer">
											<button id="widgets_add_close" type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo WIDGETS_ADD_BUTTON_CLOSE ?></button>
											<button id="widgets_add_create" type="button" class="btn btn-primary"><?php echo WIDGETS_ADD_BUTTON_CREATE ?></button>
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
		<script src="templates/default/assets/js/widgets-declare.js" type="text/javascript"></script>
	</body>
</html>