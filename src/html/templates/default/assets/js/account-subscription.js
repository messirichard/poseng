var Snippet = {
	init: function () {

		var showErrorMsg = function (form, type, msg) {
			var alert = $('<div class="m-alert m-alert--outline alert alert-' + type + ' alert-dismissible" role="alert">\
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>\
				<span></span>\
			</div>');

			form.find('.alert').remove();
			alert.prependTo(form);
			//alert.animateClass('fadeIn animated');
			mUtil.animateClass(alert[0], 'fadeIn animated');
			alert.find('span').html(msg);
		}

		var paypalUpgrade = function () {
			$('.paypal-button-container').each(function (i, va) {
				let btn_id = $(this).attr('id');
				let upg_plan = $(this).attr('data-plan');
				let user = $(this).attr('data-subscriber');
				if (upg_plan) {
					if (user) {
						paypal.Buttons({
							locale: 'en_US',
							commit: true,
							style: {
								layout: 'horizontal',
								size: 'responsive',
								color: 'blue',
								shape: 'pill',
								label: 'paypal',
								height: 40,
								tagline: false
							},
							createSubscription: function (data, actions) {
								return actions.subscription.revise(user, {
									'plan_id': upg_plan,
									'application_context': {
										'shipping_preference': 'NO_SHIPPING'
									}
								});
							},
							onError: function (err) {
								toastr.error(err);
							},
							onApprove: function (data, actions) {
								toastr.success("Order id " + data.orderID + " has been successfully placed. Please wait while we're creating the invoice.");
								$.ajax({
									type: "POST",
									url: '/ajax-account-billing-paypal?s=1',
									data: {
										"order_id": data.orderID,
										"subscription_id": data.subscriptionID
									},
									success: function (json, status, xhr) {
										toastr.success(json.hasOwnProperty("desc") ? json.desc : "Thank you..!");
										setTimeout(function () {
											location.reload();
										}, 3000);
									},
									error: function (response, status, msg) {
										toastr.error(response.hasOwnProperty("responseJSON") ? response.responseJSON.desc : "An error occurred while processing your request. Please try again.");
									}
								});
							}
						}).render('#' + btn_id);
					} else {
						paypal.Buttons({
							locale: 'en_US',
							commit: true,
							style: {
								layout: 'horizontal',
								size: 'responsive',
								color: 'blue',
								shape: 'pill',
								label: 'paypal',
								height: 40,
								tagline: false
							},
							createSubscription: function (data, actions) {
								return actions.subscription.create({
									'plan_id': upg_plan,
									'application_context': {
										'shipping_preference': 'NO_SHIPPING'
									}
								});
							},
							onError: function (err) {
								toastr.error(err);
							},
							onApprove: function (data, actions) {
								toastr.success("Order id " + data.orderID + " has been successfully placed. Please wait while we're creating the invoice.");
								$.ajax({
									type: "POST",
									url: '/ajax-account-billing-paypal?s=1',
									data: {
										"order_id": data.orderID,
										"subscription_id": data.subscriptionID
									},
									success: function (json, status, xhr) {
										toastr.success(json.hasOwnProperty("desc") ? json.desc : "Thank you..!");
										setTimeout(function () {
											location.reload();
										}, 3000);
									},
									error: function (response, status, msg) {
										toastr.error(response.hasOwnProperty("responseJSON") ? response.responseJSON.desc : "An error occurred while processing your request. Please try again.");
									}
								});
							}
						}).render('#' + btn_id);
					}
				} else {
					$(this).addClass('button--upgrade-contact btn-upgrade btn m-btn--pill btn-danger m-btn--wide m-btn--uppercase m-btn--bolder m-btn--lg').removeClass('paypal-button-container').text($(this).attr('data-null')).click(function () {
						window.open('mailto:' + $(this).attr('data-ctc') + '?subject=' + $(this).attr('data-sbj'));
					});
				}
			})
		}
		paypalUpgrade();
	}
};

jQuery(document).ready(function () {
	devMsgs.init('all,upgrade');
	Snippet.init();
});