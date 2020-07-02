var Snippet = {
    init: function() {
	
		var showErrorMsg = function(form, type, msg) {
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
	
		var handleUpgrade = function() {
			$('.btn-upgrade ').click(function(e) {
				e.preventDefault();
				var btn = $(this);
				$upg_to = btn.attr('data-id');
				$upg_term = btn.attr('data-plan');
				if (!$upg_to) return;

				swal({
					title: "Coupon Code",
					text: "Enter coupon code if you have one or leave it blank.",
					type: "info",
					input: "text",
					inputAttributes: {
						min: 0
					},
					showCancelButton: !0,
					confirmButtonText: "Submit",
					cancelButtonText: "Cancel",
					reverseButtons: !0
				}).then(function(e) {
					if (e.hasOwnProperty("value")) {
						btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
						$.ajax({
							type: "POST",
							url: '/ajax-account-billing?s=3',
							data: { 
								"upgrade_to": $upg_to,
								"upgrade_term": $upg_term,
								"coupon": e.value 
							},
							success: function(json, status, xhr) {
								toastr.success(json.hasOwnProperty("desc")?json.desc:"An invoice has been created for you. Waiting for payment..");
								try {
									$.ajax({
										type: "POST",
										url: '/ajax-account-billing?s=4',
										data: { 
											"billing_id": json.data.id
										},
										success: function(json, status, xhr) {
											btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
											snap.pay(json.snapToken, {
												onSuccess: function(result){
													toastr.success("Order id " + result.order_id + " has been created successfully. Your account upgrading is now completed.");
													setTimeout(function() {
														window.location.href = "/page-account-billing";
													},3000);
												},
												onPending: function(result){
													toastr.info("Order id " + result.order_id + " has been created and now is in pending state.");
													setTimeout(function() {
														window.location.href = "/page-account-billing";
													},3000);
												},
												onError: function(result){
													toastr.info("Order id " + result.order_id + " failed to processed. Please try again.");								
												},
												onClose: function(result){
													toastr.warning("Your order is incompleted. We are taking you now to the billing page.");
													setTimeout(function() {
														window.location.href = "/page-account-billing";
													},3000);
												}
											});
										},
										error: function(response, status, msg) {
											if (response.status==401) window.location.replace("/");
											btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
											toastr.error(response.hasOwnProperty("responseJSON")?response.responseJSON.desc:"An error occurred while processing your request. Please try again.");
										}
									});	
								} catch (e) {
									btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
									toastr.error(e.message);									
								}
							},
							error: function(response, status, msg) {
								btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
								toastr.error(response.hasOwnProperty("responseJSON")?response.responseJSON.desc:"An error occurred while processing your request. Please try again.");
							}
						});
					}
				})
			});
		}
		handleUpgrade();
    }	
};

jQuery(document).ready(function() {
    devMsgs.init('all,upgrade');
    Snippet.init();
});