var Snippet = function() {

    var showErrorMsg = function(form, type, msg) {
        var alert = $('<div class="m-alert m-alert--outline alert alert-' + type + ' alert-dismissible" role="alert">\
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>\
			<span></span>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form.find('.m-portlet__body'));
        //alert.animateClass('fadeIn animated');
        mUtil.animateClass(alert[0], 'fadeIn animated');
        alert.find('span').html(msg);
    }

    var handleTab1FormSubmit = function() {
        $('#account-config-tab1-submit').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');
			
            form.validate({
                rules: {
					cfg_log_default: {
						required: false,
					},
					cfg_log_keep: {
						required: false,
					}					
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
            form.ajaxSubmit({
                url: '/ajax-account-config?s=1',
				accepts: {
					text: "application/json"
				},
				beforeSerialize: function($form, options) {
					var def = $form.find('input[name=cfg_log_default_visible]').val();
					var keep = $form.find('input[name=cfg_log_keep_visible]').val();
					def = def.replace(/\.|\,/g,'');
					keep = keep.replace(/\.|\,/g,'');
					$form.find('input[name=cfg_log_default]').val(def*1000000); // as MB
					$form.find('input[name=cfg_log_keep]').val(keep);
				},
                success: function(json, status, xhr, $form) {
					btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
					$('#m_scroll_top').trigger('click');
                    showErrorMsg(form, 'success', json.hasOwnProperty("desc")?json.desc:'Setting has been successfully updated..!');
                },
				error: function(response, status, msg, $form) {
					if (response.status==401) window.location.replace("/");
                    btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
					$('#m_scroll_top').trigger('click');
					showErrorMsg(form, 'danger', response.hasOwnProperty("responseJSON")?response.responseJSON.desc:'Something failed while updating setting. Please try again.');
				}
            });
        });
    }
	
	var handleTab2FormSubmit = function() {
        $('#account-config-tab2-submit').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');
			
            form.validate({
                rules: {
					cfg_oem_brand: {
						required: true,
						minlength: 3
					},
					cfg_oem_tagline: {
						required: false,
						minlength: 0
					},
					cfg_oem_domain: {
						required: true,
						minlength: 4
					},
					cfg_oem_email_noreply: {
						required: false,
						email: {
							depends: function(element) {
								return $(this).val().trim().length>0;
							}
						}
					},
					cfg_oem_email_support: {
						required: true,
						email: true
					},	
					cfg_oem_url_logo: {
						required: true,
						minlength: 4
					},
					cfg_oem_url_tos: {
						required: true,
						minlength: 4
					},					
                    cfg_oem_url_privacy: {
                        required: true,
                        minlength: 4
					},
					cfg_oem_url_android: {
						required: {
							depends: function(element) {
								return !(form.find('input[name=cfg_oem_url_ios]').val().trim().length>4 || form.find('input[name=cfg_oem_url_otherapp]').val().trim().length>4);
							}
						},
						minlength: 4
					},
					cfg_oem_url_ios: {
						required: {
							depends: function(element) {
								return !(form.find('input[name=cfg_oem_url_android]').val().trim().length>4 || form.find('input[name=cfg_oem_url_otherapp]').val().trim().length>4);
							}
						},
						minlength: 4
					},
					cfg_oem_url_otherapp: {
						required: {
							depends: function(element) {
								return !(form.find('input[name=cfg_oem_url_android]').val().trim().length>4 || form.find('input[name=cfg_oem_url_ios]').val().trim().length>4);
							}
						},
						minlength: 4
					}					
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
            form.ajaxSubmit({
                url: '/ajax-account-config?s=2',
				accepts: {
					text: "application/json"
				},
                success: function(json, status, xhr, $form) {
					btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
					$('#m_scroll_top').trigger('click');
                    showErrorMsg(form, 'success', json.hasOwnProperty("desc")?json.desc:'OEM service configuration has been successfully updated..!');
                },
				error: function(response, status, msg, $form) {
					if (response.status==401) window.location.replace("/");
                    btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
					$('#m_scroll_top').trigger('click');
					showErrorMsg(form, 'danger', response.hasOwnProperty("responseJSON")?response.responseJSON.desc:'Something failed while updating OEM service configuration. Please try again.');
				}
            });
        });
    }

    //== Public Functions
    return {
        // public functions
        init: function() {
            handleTab1FormSubmit();
            handleTab2FormSubmit();
        },
		showErrorMsg: function(form, type, msg) {
			showErrorMsg(form, type, msg);
		}
    };
}();

//== Class Initialization
jQuery(document).ready(function() {
	$('.cfg_number').mask("#,##0", {reverse: true});
    devMsgs.init('all,config');
    Snippet.init();
});