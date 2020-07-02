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
						<div class="m-portlet m-portlet--mobile">
							<div class="m-portlet__head">
								<div class="m-portlet__head-caption">
									<div class="m-portlet__head-title">
										<h3 class="m-portlet__head-text">
											<?php echo BILLING_TITLE ?>
										</h3>
									</div>
								</div>
							</div>
							<div class="m-portlet__body" data-currency="<?php echo DEFAULT_CURRENCY_SYMBOL ?> ">								
								<table class="table table-striped- table-bordered table-hover table-checkable" id="tbl_billing">
									<thead>
										<tr>
											<th><?php echo BILLING_TABLE_COL1 ?></th>
											<th><?php echo BILLING_TABLE_COL2 ?></th>
											<th><?php echo BILLING_TABLE_COL3 ?></th>
											<th><?php echo BILLING_TABLE_COL4 ?></th>
											<th><?php echo BILLING_TABLE_COL5 ?></th>
											<th><?php echo BILLING_TABLE_COL6 ?></th>
											<th><?php echo BILLING_TABLE_COL7 ?></th>
										</tr>
									</thead>
								</table>
							</div>
							<!-- modal invoice -->
							<div class="modal fade" id="billing-modal-inv" tabindex="-1" role="dialog" aria-labelledby="billingInvModalCenterTitle" aria-hidden="true">
								<div class="modal-dialog modal-lg" role="document">
									<div class="modal-content">										
										<div id="printModal" class="modal-body">
											<div class="row">
												<div class="col-lg-12">
													<div class="m-portlet">
														<div class="m-portlet__body m-portlet__body--no-padding">
															<div class="m-invoice-2">
																<div class="m-invoice__wrapper">
																	<div class="m-invoice__head" style="background-image: url(/templates/default/assets/images/wave.png);background-size:contain;background-repeat:no-repeat;background-position:topcenter;">
																		<div class="m-invoice__container m-invoice__container--centered">
																			<div class="m-invoice__logo">
																				<a href="#">
																					<h1>INVOICE</h1>
																				</a>
																				<a href="#">
																					<img src="/templates/default/assets/images/logo-sam.png" style="width:150px">
																				</a>
																			</div>
																			<span class="m-invoice__desc">
																				<span><?php echo BUSINESS_ADDR1 ?></span>
																				<span><?php echo BUSINESS_ADDR2 ?></span>
																			</span>
																			<div class="m-invoice__items">
																				<div class="m-invoice__item">
																					<span class="m-invoice__subtitle"><?php echo BILLING_INV_MODAL_INVDATE ?></span>
																					<span class="inv-issdate m-invoice__text"></span>
																				</div>
																				<div class="m-invoice__item">
																					<span class="m-invoice__subtitle"><?php echo BILLING_INV_MODAL_INVNO ?></span>
																					<span class="inv-order-id m-invoice__text"></span>
																				</div>
																				<div class="m-invoice__item">
																					<span class="m-invoice__subtitle"><?php echo BILLING_INV_MODAL_INVTO ?></span>
																					<span class="inv-to m-invoice__text"><span></span>
																						<?php echo !empty($dev->org_name)?'<br/>' . $dev->org_name:'' ?>
																						<?php echo !empty($dev->org_addr1)?'<br/>' . $dev->org_addr1:'' ?>
																						<?php echo !empty($dev->org_addr2)?'<br/>' . $dev->org_addr2:'' ?>
																						<?php echo !empty($dev->org_city)?'<br/>' . $dev->org_city:'' ?><?php echo !empty($dev->org_province)?', ' . $dev->org_province:'' ?><?php echo !empty($dev->org_zip)?' ' . $dev->org_zip:'' ?>
																						<?php echo !empty($dev->org_country)?'<br/>' . $dev->org_country:'' ?>
																					</span>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="m-invoice__body m-invoice__body--centered">
																		<div class="table-responsive">
																			<table class="table">
																				<thead>
																					<tr>
																						<th><?php echo BILLING_INV_MODAL_INVDESC ?></th>
																						<th><?php echo BILLING_INV_MODAL_INVPRICE ?></th>
																						<th><?php echo BILLING_INV_MODAL_INVQTY ?></th>
																						<th><?php echo BILLING_INV_MODAL_INVSAVE ?></th>
																						<th><?php echo BILLING_INV_MODAL_INVAMOUNT ?></th>
																					</tr>
																				</thead>
																				<tbody>
																					<tr class="inv-item">
																						<td></td>
																						<td class="money"></td>
																						<td></td>
																						<td>(<span class="money"></span>)</td>
																						<td class="m--font-danger money"></td>
																					</tr>																
																				</tbody>
																			</table>
																		</div>
																	</div>
																	<div class="m-invoice__footer">
																		<div class="m-invoice__table  m-invoice__table--centered table-responsive">
																			<table class="table">
																				<thead>
																					<tr>
																						<th><?php echo BILLING_INV_MODAL_INVDUEDATE ?></th>
																						<th><?php echo BILLING_INV_MODAL_INVPAIDDATE ?></th>
																						<th><?php echo BILLING_INV_MODAL_INVTAX ?></th>
																						<th><?php echo BILLING_INV_MODAL_INVTOTALAMOUNT ?></th>
																					</tr>
																				</thead>
																				<tbody>
																					<tr>
																						<td class="inv-duedate"></td>
																						<td class="inv-paddate"></td>
																						<td class="inv-tax inv-price m--font-danger money"></td>
																						<td class="inv-total m--font-danger"><?php echo DEFAULT_CURRENCY_SYMBOL ?> <span class="money"></span></td>
																					</tr>
																				</tbody>
																			</table>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo BILLING_INV_MODAL_BTN_CLOSE ?></button>
											<button type="button" class="btn-print btn btn-primary"><?php echo BILLING_INV_MODAL_BTN_PRINT ?></button>
										</div>
									</div>
								</div>
							</div>
							<!-- end modal invoice -->
						</div>
						
					</div>
				</div>
			</div>
			<!-- end:: Body -->
			<?php require_once('_page_foot.php'); ?>
		</div>
		<!-- end:: Page -->
		<?php require_once('_foot.php'); ?>
		<script src="templates/default/assets/js/account-billing.js" type="text/javascript"></script>
		<?php if (PAYMENT_MIDTRANS_PRODUCTION) { ?>
		<script src="https://app.midtrans.com/snap/snap.js" data-client-key="Mid-client-vwE78_ltZGYf26ZQ"></script>
		<?php } else { ?>
		<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-PMindfCMlqSBFP6d"></script>
		<?php } ?>
	</body>
</html>