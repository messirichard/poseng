//== Class Definition
var SnippetLogin = function() {

    var login = $('#m_login');

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

    //== Private Functions

    var displaySignUpForm = function() {
        login.removeClass('m-login--forget-password');
        login.removeClass('m-login--signin');

        login.addClass('m-login--signup');
        mUtil.animateClass(login.find('.m-login__signup')[0], 'flipInX animated');
    }

    var displaySignInForm = function() {
        login.removeClass('m-login--forget-password');
        login.removeClass('m-login--signup');

        login.addClass('m-login--signin');
        mUtil.animateClass(login.find('.m-login__signin')[0], 'flipInX animated');
        //login.find('.m-login__signin').animateClass('flipInX animated');
    }

    var displayForgetPasswordForm = function() {
        login.removeClass('m-login--signin');
        login.removeClass('m-login--signup');

        login.addClass('m-login--forget-password');
        //login.find('.m-login__forget-password').animateClass('flipInX animated');
        mUtil.animateClass(login.find('.m-login__forget-password')[0], 'flipInX animated');

    }
	
	var resetPasswordForm = function() {
		var form = $('.m-login__forget-password form');
		var title = form.closest('.m-login__forget-password').find('.m-login__head');
		title.find('div:eq(1)').remove();	
		title.find('div:eq(0)').css('display','');
		var form = form.closest('form');
		form.find('.alert').remove();
		form.find('input[name=reset]').remove();
		form.find('input[name=email]').remove();
		form.find('input[name=password]').remove();
		form.find('input[name=rpassword]').remove();
		form.find('input[name=semail]').css('display','');
		form.find('.m-login__form-action button:eq(0) span:eq(1)').remove();
		form.find('.m-login__form-action button:eq(0) span:eq(0)').css('display','');
        form.clearForm(); // clear form
        form.validate().resetForm(); // reset validation states		
	}

    var handleFormSwitch = function() {
        $('#m_login_forget_password').click(function(e) {
            e.preventDefault();
            displayForgetPasswordForm();
        });

        $('#m_login_forget_password_cancel').click(function(e) {
            e.preventDefault();
			resetPasswordForm();
            displaySignInForm();
        });

        $('#m_login_signup').click(function(e) {
            e.preventDefault();
            displaySignUpForm();
        });

        $('#m_login_signup_cancel').click(function(e) {
            e.preventDefault();
            displaySignInForm();
        });
    }

    var handleSignInFormSubmit = function() {
        $('#m_login_signin_submit').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');
			
            form.validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
            form.ajaxSubmit({
                url: '/ajax-login?s=1',
				beforeSend: function (xhr) {
					xhr.setRequestHeader ("Authorization", "Basic " + btoa(form.find("input[name=email]").val() + ":" + form.find("input[name=password]").val()));
				},
                success: function(json, status, xhr, $form) {
                    btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                    showErrorMsg(form, 'success', json.hasOwnProperty("desc")?json.desc:'Authorization succeeded. Redirecting..');
					window.location = "/" + form.attr('data-dest');
                },
				error: function(response, status, msg, $form) {
                    btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
					showErrorMsg(form, 'danger', response.hasOwnProperty("responseJSON")?response.responseJSON.desc:'Incorrect username or password. Please try again.');
				}
            });
        });
    }

    var handleSignUpFormSubmit = function() {
        $('#m_login_signup_submit').click(function(e) {
            e.preventDefault();

            var btn = $(this);
            var form = $(this).closest('form');

            form.validate({
                rules: {
                    fullname: {
                        required: true,
						minlength: 5
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    },
                    rpassword: {
                        required: true,
						equalTo: "#signup_password"
                    },
                    agree: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);

            form.ajaxSubmit({
                url: '/ajax-login?s=2',
                success: function(json, status, xhr, $form) {
                    btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
                    form.clearForm();
                    form.validate().resetForm();

                    displaySignInForm();
                    var signInForm = login.find('.m-login__signin form');
                    signInForm.clearForm();
                    signInForm.validate().resetForm();

					showErrorMsg(signInForm, 'info', json.hasOwnProperty("desc")?json.desc:'Thank you. To complete your registration please check your email.');
                },
				error: function(response, status, msg, $form) {
               		btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
					showErrorMsg(form, 'danger', response.hasOwnProperty("responseJSON")?response.responseJSON.desc:'Something went wrong. Please try again!');
				}
            });
        });
    }

    var handleForgetPasswordFormSubmit = function() {
        $('#m_login_forget_password_submit').click(function(e) {
            e.preventDefault();

            var btn = $(this);
            var form = $(this).closest('form');

			let fr = form.find('input[name=reset]');
			let fs = form.find('input[name=email]');
			let cp = fr!==undefined && fr!==null && fs!==undefined && fs!==null && fr.length>0 && fs.length>0 && fr.val().length>0 && fs.val().length>0;

			form.validate({
				rules: {
					semail: {
						required: !cp,
						email: !cp
					},
					email: {
						required: cp,
						email: cp
					},
					password: {
						required: cp
					},
					rpassword: {
						required: cp,
						equalTo: {
							param: "#reset_password",
							depends: function(element) {
								return cp;
							}
						}
					}						
				}
			});

            if (!form.valid()) {
                return;
            }

            btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);

            form.ajaxSubmit({
                url: '/ajax-login?s=3',
                success: function(json, status, xhr, $form) { 	
					resetPasswordForm();
               		btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);

	                    // display signup form
                    displaySignInForm();
                    var signInForm = login.find('.m-login__signin form');
                    signInForm.clearForm();
                    signInForm.validate().resetForm();

					showErrorMsg(signInForm, 'success', json.hasOwnProperty("desc")?json.desc:'Cool! Password recovery instruction has been sent to your email.');
                },
				error: function(response, status, msg, $form) {
               		btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
					showErrorMsg(form, 'danger', response.hasOwnProperty("responseJSON")?response.responseJSON.desc:'Something went wrong. Please try again!');
				}
            });
        });
    }

    //== Public Functions
    return {
        // public functions
        init: function() {
            handleFormSwitch();
            handleSignInFormSubmit();
            handleSignUpFormSubmit();
            handleForgetPasswordFormSubmit();
        },
		showErrorMsg: function(form, type, msg) {
			showErrorMsg(form, type, msg);
		},
		displaySignInForm: function() {
			displaySignInForm();
		},
		displaySignUpForm: function() {
			displaySignUpForm();
		},
		displayForgetPasswordForm: function() {
			displayForgetPasswordForm();
		}
    };
}();

//== Class Initialization
jQuery(document).ready(function() {
    SnippetLogin.init();
});