var Snippet = function() {

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

    var handleTab1FormSubmit = function() {
        $('#account-profile-tab1-submit').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');
			
            form.validate({
                rules: {
					fname: {
						required: true,
						minlength: 3
					},
					lname: {
						required: false,
					},
					title: {
						required: false,
					},
					phone: {
						required: true,
						minlength: 14
					},
                    cemail: {
                        required: false,
                        email: {
							depends: function(element) {
								return $(this).val().trim().length>0;
							}
						}
                    },
                    opassword: {
                        required: false
                    },					
                    password: {
                        required: false
                    },
					rpassword: {
						required: false,
						equalTo: "#password"
					}
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
            form.ajaxSubmit({
                url: '/ajax-account-profile?s=1',
				accepts: {
					text: "application/json"
				},
                success: function(json, status, xhr, $form) {
					btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
					$('#m_scroll_top').trigger('click');
                    showErrorMsg(form, 'success', json.hasOwnProperty("desc")?json.desc:'Profile has been successfully updated..!');
                },
				error: function(response, status, msg, $form) {
					if (response.status==401) window.location.replace("/");
                    btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
					$('#m_scroll_top').trigger('click');
					showErrorMsg(form, 'danger', response.hasOwnProperty("responseJSON")?response.responseJSON.desc:'Something failed while updating your profile. Please try again.');
				}
            });
        });
    }
	
	var handleTab2FormSubmit = function() {
        $('#account-profile-tab2-submit').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');
			
            form.validate({
                rules: {
					org_name: {
						required: true,
						minlength: 5
					},
					org_addr1: {
						required: true,
						minlength: 10
					},
					org_city: {
						required: true,
						minlength: 3
					},
					org_province: {
						required: true,
						minlength: 3
					},
					org_country: {
						required: true,
						minlength: 5
					},	
					org_zip: {
						required: true,
						minlength: 3
					},
					org_web: {
						required: true,
						minlength: 4
					},					
                    org_email: {
                        required: true,
                        email: true
					},
					org_phone: {
						required: true,
						minlength: 14
					},
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
            form.ajaxSubmit({
                url: '/ajax-account-profile?s=2',
				accepts: {
					text: "application/json"
				},
                success: function(json, status, xhr, $form) {
					btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
					$('#m_scroll_top').trigger('click');
                    showErrorMsg(form, 'success', json.hasOwnProperty("desc")?json.desc:'Organization has been successfully updated..!');
                },
				error: function(response, status, msg, $form) {
					if (response.status==401) window.location.replace("/");
                    btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
					$('#m_scroll_top').trigger('click');
					showErrorMsg(form, 'danger', response.hasOwnProperty("responseJSON")?response.responseJSON.desc:'Something failed while updating your organization. Please try again.');
				}
            });
        });
    }

	var handleCityOnChange = function() {
		var options = {
			url: "/ajax-cities",
			getValue: function(element) {
				return element.name;
			},
			template: {
				type: "description",
				fields: {
					description: "district"
				}
			},
			ajaxSettings: {
				dataType: "json",
				method: "GET",
				data: {}
			},
			preparePostData: function(data) {
				data.search = $("#account_profile_org_city").val();
				return data;
			},
			list: {
				onSelectItemEvent: function() {
					var selectedRegion = $("#account_profile_org_city").getSelectedItemData().district;
					var selectedCountry = $("#account_profile_org_city").getSelectedItemData().country;
					$("#account_profile_org_province").val(selectedRegion).trigger("change");
					$("#account_profile_org_country").val(selectedCountry).trigger("change");
				}
			},
			requestDelay: 400
		};

		$('#account_profile_org_city').easyAutocomplete(options);
	}

	var handleCountryOnChange = function() {
		var options = {
			url: "/ajax-countries",
			getValue: function(element) {
				return element.name;
			},
			ajaxSettings: {
				dataType: "json",
				method: "GET",
				data: {}
			},
			preparePostData: function(data) {
				data.search = $("#account_profile_org_country").val();
				return data;
			},
			requestDelay: 400
		};

		$('#account_profile_org_country').easyAutocomplete(options);
	}
	
	var handleAvatarOnChange = function() {
		$('.file-avatar').change(function() {
			var file = $(this)[0].files[0];
			var formData = new FormData();
			formData.append("file", file, file.name);
			formData.append("upload_file", true);
			$.ajax({
				url: "/ajax-account-profile?s=3", 
				type: "POST",
				data: formData,
				contentType: false,
				cache: false,
				processData:false,
				success: function(response)	{
					$('.m-card-profile').find('.alert').remove();
					$('.plc-avatar').fadeOut(function() {
						$('.plc-avatar').attr('src',response.body).fadeIn();
					});
				},
				error: function(response) {
					showErrorMsg($('.m-card-profile'), 'danger', response.hasOwnProperty("responseJSON")?response.responseJSON.desc:'Something failed while updating your avatar. Please try again.');
				}
			});
		});
		$('.m-card-profile__pic-wrapper').click(function(e) {
			$('.file-avatar').trigger('click');
		});
	}
	
    //== Public Functions
    return {
        // public functions
        init: function() {
			handleAvatarOnChange();
            handleTab1FormSubmit();
            handleTab2FormSubmit();
			handleCityOnChange();
			handleCountryOnChange();
        },
		showErrorMsg: function(form, type, msg) {
			showErrorMsg(form, type, msg);
		}
    };
}();

//== Class Initialization
jQuery(document).ready(function() {
	$('.phone').mask('(+09) 000-0000-9999-9999');
    devMsgs.init('all,account');
    Snippet.init();
});