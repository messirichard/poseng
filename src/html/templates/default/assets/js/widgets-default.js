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
		
		var activeWidget = null;
		var listApi = null;
	
		var clearWidgetAdd = function(f) {
			var x = $(f);
			x.find('#default_info .alert').remove();
			x.find('#default_alert').hide();
			x.find('.widgets-idx').val('');
			x.find('.widgets-element-id').val('');
			x.find('.widgets-preview').attr('src','/templates/default/assets/images/default-ss.jpg');
			x.find('form *').filter('.widgets-input:input').each(function(){
				if ($(this).hasClass('widgets-number')) this.value='1';
				else if (this.type=='file' || this.type=='text') this.value='';
				else if (this.type=='checkbox') $(this).prop('checked',true).trigger('change');
			});
			x.find('.modal-title').html('Create New Model');
			x.find('#default_create').html('Create');
		}
		
		var fillWidgetAdd = function(x,w) {
			x.find('.widgets-idx').val(w.id);
			x.find('.widgets-preview').attr('src',(w.logo!==undefined && w.logo!==null)?w.logo:'/templates/default/assets/images/default-ss.jpg');
			x.find('input[name=default_model]').val(w.name);
			x.find('input[name=default_desc]').val(w.description);
			x.find('input[name=default_title]').val(w.widget.title);

			$("#default_select_widget").val(w.widget.id).trigger("change");

			var m = jQuery.grep(activeWidget, function(n,i) { return (n.id==w.widget.id); });			
			x.find('input[name=default_width]').val(w.widget.width).TouchSpin({
				buttondown_class:"btn btn-secondary",
				buttonup_class:"btn btn-secondary",
				initval:w.widget.width,
				min:m.length?m[0].minwidth:-100,
				max:m.length?m[0].maxwidth:100,
				step:1,
				decimals:0,
				boostat:5,
				maxboostedstep:10
			});
			x.find('input[name=default_height]').val(w.widget.height).TouchSpin({
				buttondown_class:"btn btn-secondary",
				buttonup_class:"btn btn-secondary",
				initval:w.widget.height,
				min:m.length?m[0].minheight:-100,
				max:m.length?m[0].maxheight:100,
				step:1,
				decimals:0,
				boostat:5,
				maxboostedstep:10
			});
			
			x.find('.tab-pane').each(function(i) {
				var id = parseInt($(this).find('input[name="default_element_id[]"]').val());
				var z = jQuery.grep(w.widget.elements, function(n,i) { return (n.id==id); });
				if (m.length) {
					var y = jQuery.grep(m[0].elements, function(n,i) { return (n.id==id); });
					x.find('.widgets-element-param span').text(y[0].maxparams);
				}
				if (z!==null && z.length>0) $(this).find('input[name="default_element_param[]"]').val(z[0].param.join(","));
			});
		}
		
		var showWidgetAdd = function(w) {
			var elem = $('.widget-card:last').clone();
			showWidgetUpdate(elem,w);
			if (w.status) elem.find('.card-status').removeClass('btn-danger').addClass('btn-success').find('i').removeClass('la-unlink').addClass('la-link');
			else elem.find('.card-status').removeClass('btn-success').addClass('btn-danger').find('i').removeClass('la-link').addClass('la-unlink');			
			elem.hide();
			$('.widget-card:last').before(elem);
			elem.fadeIn();
		}
		
		var showWidgetUpdate = function(elem,w) {
			elem.attr('data-id',w.id).attr('data-toggle','').attr('data-target','');
			elem.find('.widget-thumb img').attr('src',(w.logo!==undefined && w.logo!==null)?w.logo:'templates/default/assets/images/default-ss.jpg');
			elem.find('.widget-title h6').text(w.name);
		}
		
		var refreshCards = function(s) {
			$(".widget-card:not(:last)").fadeOut();
			$.ajax({
				type: "POST",
				url: '/ajax-widgets-default?s=1',
				data: {search: {value:s}},
				success: function(json, status, xhr) {					
					if (!$('input.widget-search').val().trim() && json.data.length==0) toastr.success(json.hasOwnProperty("desc")?json.desc:"You don't have any models. Try to create a new one.");
					$(".widget-card:not(:last)").remove();
					$.each(json.data,function(i, w){
						showWidgetAdd(w);
					});
				},
				error: function(response, status, msg) {
					toastr.error(response.hasOwnProperty("responseJSON")?response.responseJSON.desc:"Something failed while refreshing your models. Please try again.");
				}
			});
			
		}
		
		var refreshList = function() {
			$("#default_select_api").find('option').remove().end();
			if (listApi==null) {
				$.ajax({
					type: "POST",
					url: '/ajax-api?s=1',
					data: {all:1},
					success: function(json, status, xhr) {
						var f = 0;
						if (jQuery.isArray(json.data)) {
							json.data = jQuery.grep(json.data, function(n,i) {
								return (n.state==2);
							});
							json.data = jQuery.map(json.data, function(n,i) {
								let x = n.state;
								n.text = n.name;
								delete n.name;
								delete n.api_username;
								delete n.state;
								if (x==2) return n;
							});
							f = json.data.length;
							listApi = json.data;
						}
						$("#default_select_api").select2({
							placeholder: "Select API App",
							data: f?json.data:null,
							width: '100%'
						}).trigger("change");
						if (!f) toastr.success(json.hasOwnProperty("desc")?json.desc:"We are sorry! Currently there is no available widgets on public collection.");						
					},
					error: function(response, status, msg) {
						toastr.error(response.hasOwnProperty("responseJSON")?response.responseJSON.desc:"Something failed while retrieving widgets. Please try again.");
					}
				});
			} else {
				$("#default_select_api").select2({
					placeholder: "Select API App",
					data: listApi,
					width: '100%'
				}).trigger("change");
			}
		}
		
		var refreshWidgets = function(api) {
			function formatState (state) {
				if (!state.id) return state.text;
				var $state = $('<span><img src="' + state.logo + '" class="widget-default-widgets-img" /> ' + state.text + '</span>');
				return $state;
			};

			$.ajax({
				type: "POST",
				url: '/ajax-widgets-declare?s=1',
				data: {widgets_api: api, all:1},
				success: function(json, status, xhr) {
					var f = 0;
					if (jQuery.isArray(json.data)) {
						json.data = jQuery.grep(json.data, function(n,i) {
							return (n.status);
						});
						json.data = jQuery.map(json.data, function(n,i) {
							if (n.logo==null) n.logo = '/templates/default/assets/images/widget-ss.jpg';
							n.text = n.name;
							return n;
						});
						f = json.data.length;					
					} 
					$("#default_select_widget").select2({
						placeholder: "Select Default Widget",
						data: f?json.data:null,
						templateResult: formatState
					})
					activeWidget = json.data;
					if (!f) toastr.success(json.hasOwnProperty("desc")?json.desc:"We are sorry! Currently there is no available widgets on public collection.");
					$("#default_select_widget").trigger("change");
				},
				error: function(response, status, msg) {
					activeWidget = null;
					toastr.error(response.hasOwnProperty("responseJSON")?response.responseJSON.desc:"Something failed while retrieving widgets. Please try again.");
				}
			});
		}		
	
		var refreshModelForm = function(id) {
			var x = $('#widget_add');
			var m = jQuery.grep(activeWidget, function(n,i) { return (n.id==id); });
			$(".widgets-number").trigger('touchspin.destroy');
			x.find("#default_width").TouchSpin({
				buttondown_class:"btn btn-secondary",
				buttonup_class:"btn btn-secondary",
				initval:1,
				min:m.length?m[0].minwidth:-100,
				max:m.length?m[0].maxwidth:100,
				step:1,
				decimals:0,
				boostat:5,
				maxboostedstep:10
			});
			x.find("#default_height").TouchSpin({
				buttondown_class:"btn btn-secondary",
				buttonup_class:"btn btn-secondary",
				initval:1,
				min:m.length?m[0].minheight:-100,
				max:m.length?m[0].maxheight:100,
				step:1,
				decimals:0,
				boostat:5,
				maxboostedstep:10
			});
						
			var e1 = x.find('.nav-item:eq(0)').clone();
			e1.find('a').removeClass('active').removeClass('show');
			var z = x.find('.tab-pane:eq(0)');
			var e2 = z.clone();
			e2.removeClass('active');

			x.find('.nav-item').remove();
			x.find('.tab-pane').remove();
			
			if (m.length) {
				$.each(m[0].elements, function(i,e) {
					var a = e1.clone();
					var b = e2.clone();
					a.find('.nav-link').attr('href','#default_element_'+e.id).html(e.name);
					a.appendTo(x.find('.nav-tabs'));
					b.attr('id','default_element_'+e.id);
					b.find('.widgets-element-id').val(e.id);
					b.find('.default_element_datatype').val($.isArray(e.datatypes)?e.datatypes.join(', '):'').prop('disabled',true);
					b.find('.widgets-element-param span').text(e.maxparams);
					b.appendTo(x.find('.tab-content'));					
				});
				setTimeout(function() {
					x.find('.nav-link').eq(m.length).trigger('click');
					x.find('.nav-link').eq(0).trigger('click');
				},100);
			}
			$("#default_select_widget").parent().find('.select2-container').css('width','100%');
		}
	
		var handleEdit = function(id,callbackOK,callbackERR) {
			if (id===null) return false; 
			$.ajax({
				type: "POST",
				url: '/ajax-widgets-default?s=1',
				data: {id: id},
				success: function(json, status, xhr) {
					if ($.isFunction(callbackOK)) callbackOK();
					if (!json.hasOwnProperty("desc") || !json.hasOwnProperty("data") || !$.isArray(json.data) || json.data.length===0) toastr.success("Incorrect return. Fail to retrieve data. Please try again.");
					else {
						var m = $('#widget_add');
						m.find('.modal-title').html('Edit Model');
						m.find('#default_create').html('Update');
						m.modal('show');
						fillWidgetAdd(m,json.data[0]);
					}
				},
				error: function(response, status, msg) {
					toastr.error(response.hasOwnProperty("responseJSON")?response.responseJSON.desc:"An error occurred while processing your request. Please try again.");
					if ($.isFunction(callbackERR)) callbackERR();
				}
			});
			return true;
		}		
		
		var handleDeletion = function(arr,callbackOK,callbackERR) {
			if (arr.length===0) return false; 
			swal({
                title: "Are you sure?",
                text: "You are going to delete " + arr.length + " of your model(s). You won't be able to revert this!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel!",
                reverseButtons: !0
            }).then(function(e) {
				if (e.value) {
					$.ajax({
						type: "POST",
						url: '/ajax-widgets-default?s=4',
						data: { default_id: arr },
						success: function(json, status, xhr) {
							toastr.success(json.hasOwnProperty("desc")?json.desc:"Your model has been deleted.");
							if ($.isFunction(callbackOK)) callbackOK();
						},
						error: function(response, status, msg) {
							toastr.error(response.hasOwnProperty("responseJSON")?response.responseJSON.desc:"An error occurred while processing your request. Please try again.");
							if ($.isFunction(callbackERR)) callbackERR();
						}
					});
				}
            })
			return true;
		}

		var delayTimer;
		var doSearch = function(text) {
			clearTimeout(delayTimer);
			delayTimer = setTimeout(function() {
				refreshCards(text);
			}, 1000);
		}

		$('#widget_add').on('shown.bs.modal', function () {
			if ($('.widgets-idx').val().trim()) return;
			refreshList();
			clearWidgetAdd(this);
			$(".widgets-number").TouchSpin({
				buttondown_class:"btn btn-secondary",
				buttonup_class:"btn btn-secondary",
				initval:1,
				min:-100,
				max:100,
				step:1,
				decimals:0,
				boostat:5,
				maxboostedstep:10
			});
		})
		$('#widget_add').on('hidden.bs.modal', function () {
			clearWidgetAdd(this);
		})
	
		$('#default_create').click(function(e) {
			e.preventDefault();
			var btn = $(this);
			var form = $(this).closest('form');
			form.validate({
				rules: {
					"default_model": {
						required: true,
						minlength: 3,
						maxlength: 16
					},
					"default_desc": {
						required: true,
						minlength: 20,
						maxlength: 70
					},
					"default_title": {
						required: false,
						maxlength: 35
					},
					"default_element_param": {
						required: false,
						maxlength: 2000
					}
				}
			});

			if (!form.valid()) {
				return;
			}
			btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
			form.ajaxSubmit({
				url: '/ajax-widgets-default?s=2',
				accepts: {
					text: "application/json"
				},
				success: function(json, status, xhr, $form) {
					btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
					$('#widget_add').modal('hide');
					if (json.mode) showWidgetAdd(json.body);
					else showWidgetUpdate($('.widget-card[data-id=' + json.body.id + ']'),json.body);
					toastr.success(json.hasOwnProperty("desc")?json.desc:"Incorrect return. Please check or try again later.");
				},
				error: function(response, status, msg, $form) {
					if (response.status==401) window.location.replace("/");
					btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
					showErrorMsg(form.find('#default_info'), 'danger', response.hasOwnProperty("responseJSON")?response.responseJSON.desc:'Something failed while creating your widget. Please try again.');
				}
			});
		});
		
		jQuery(document.body).on('change','#default_select_api',function(e) {
			refreshWidgets($(this).val());
		});
		
		jQuery(document.body).on('change','#default_select_widget',function(e) {
			refreshModelForm($(this).val());
		});
	
		jQuery(document.body).on('keyup','input.widget-search',function(e) {
			var x = $(this).val().trim().length;
			if (x==0 || x>=3) doSearch($(this).val());
		});
		
		jQuery(document.body).on('change','.file-avatar',function(e) {
			if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.widgets-preview').attr('src', e.target.result);
                };
                reader.readAsDataURL(this.files[0]);
            }
		});
		jQuery(document.body).on('click','.widgets-thumb-img',function(e) {
			$('.file-avatar').trigger('click');
		});
		jQuery(document.body).on('click','.card-edit',function(e) {
			var btn = $(this);
			var a = $(this).closest('.widget-card');
			btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true).find('i').removeClass('flaticon-edit');
			handleEdit(parseInt(a.attr('data-id')),function() {
				btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false).find('i').addClass('flaticon-edit');
			},function() {
				btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false).find('i').addClass('flaticon-edit');
			});
		});
		jQuery(document.body).on('click','.card-del',function(e) {
			var a = $(this).closest('.widget-card');
			handleDeletion([parseInt(a.attr('data-id'))],function() {
				a.fadeOut("slow",function() { a.remove(); });
			});
		});
		
		refreshCards();
		refreshList();
    }	
};
					
jQuery(document).ready(function() {
    devMsgs.init('all,widget-declare');
    Snippet.init();
});