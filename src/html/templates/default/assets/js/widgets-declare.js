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
	
		var clearWidgetAdd = function(f) {
			var x = $(f);
			x.find('#widget_info .alert').remove();
			x.find('#widget_alert').hide();
			x.find('.widgets-idx').val('');
			x.find('.widgets-api').val($('#widget_select_api').val());
			x.find('.widgets-preview').attr('src','/templates/default/assets/images/widget-ss.jpg');
			x.find('form *').filter('.widgets-input:input').each(function(){
				if ($(this).hasClass('widgets-number') || $(this).hasClass('widgets_add_element_maxparams')) this.value='1';
				else if (this.type=='file' || this.type=='text') this.value='';
				else if (this.type=='select-multiple') {
					if (this.name=="widgets_tags[]") $(this).find('option').remove().end();
					$(this).val(null).trigger('change');
				}
				else if (this.type=='checkbox') $(this).prop('checked',true).trigger('change');
			});
			var c = x.find('.nav-link i.la-close');
			c.each(function() {
				x.find('.nav-link').eq(0).trigger('click');
				x.find($(this).parent().attr('href')).remove();
				$(this).closest('.nav-item').remove();
			});
			x.find('.nav-link').eq(0).html('Element');
			x.find('.modal-title').html('Create New Widget');
			x.find('#widgets_add_create').html('Create');
		}
		
		var fillWidgetAdd = function(x,w) {
			var tagOpts = jQuery.map(w.tags, function(n,i) {
				let x = {
					id: n,
					text: n
				}
				return x;
			})
			x.find('.widgets-idx').val(w.id);
			x.find('.widgets-api').val(w.api);
			x.find('.widgets-preview').attr('src',(w.logo!==undefined && w.logo!==null)?w.logo:'/templates/default/assets/images/widget-ss.jpg');
			x.find('input[name=widgets_id]').val(w.id2);
			x.find('input[name=widgets_name]').val(w.name);
			x.find('.widgets-number').trigger('touchspin.destroy');
			var tags = x.find('select[name="widgets_tags[]"]');
			tags.select2({
				placeholder:"Add a tag",
				tags:!0,
				width: '100%'
			});			
			$.each(w.tags,function(i,v) {
				var option = new Option(v, v, true, true);
				tags.append(option).trigger('change');
			});
			tags.trigger({
				type: 'select2:select',
				params: {
					data: tagOpts	
				}
			});
			x.find('input[name=widgets_minwidth]').val(w.minwidth).TouchSpin({
				buttondown_class:"btn btn-secondary",
				buttonup_class:"btn btn-secondary",
				initval:w.minwidth,
				min:-100,
				max:100,
				step:1,
				decimals:0,
				boostat:5,
				maxboostedstep:10
			});
			x.find('input[name=widgets_maxwidth]').val(w.maxwidth).TouchSpin({
				buttondown_class:"btn btn-secondary",
				buttonup_class:"btn btn-secondary",
				initval:w.maxwidth,
				min:-100,
				max:100,
				step:1,
				decimals:0,
				boostat:5,
				maxboostedstep:10
			});
			x.find('input[name=widgets_minheight]').val(w.minheight).TouchSpin({
				buttondown_class:"btn btn-secondary",
				buttonup_class:"btn btn-secondary",
				initval:w.minheight,
				min:-100,
				max:100,
				step:1,
				decimals:0,
				boostat:5,
				maxboostedstep:10
			});
			x.find('input[name=widgets_maxheight]').val(w.maxheight).TouchSpin({
				buttondown_class:"btn btn-secondary",
				buttonup_class:"btn btn-secondary",
				initval:w.maxheight,
				min:-100,
				max:100,
				step:1,
				decimals:0,
				boostat:5,
				maxboostedstep:10
			});
			
			for(var i=1;i<w.elements.length;i++) {
				x.find('.widgets_element_add').trigger('click');
			}
			x.find('.tab-pane').each(function(i) {
				var a = x.find('a.nav-link[href="#' + $(this).attr("id") + '"]');
				if (x.find('a.nav-link').index(a)>0) a.html(w.elements[i].name + '&nbsp;<i class="la la-close" style="padding-left: 10px;"></i>');
				else a.html(w.elements[i].name);
				$(this).find('input[name="widgets_element_id[]"]').val(w.elements[i].id2);
				$(this).find('input[name="widgets_element_name[]"]').val(w.elements[i].name);
				$(this).find('.widgets_add_settable').prop("checked",w.elements[i].settable).trigger('change');
				$(this).find('.widgets_add_retained').prop("checked",w.elements[i].retained).trigger('change');
				$(this).find('.widgets_add_element_maxparams').val(w.elements[i].maxparams).TouchSpin({
					buttondown_class:"btn btn-secondary",
					buttonup_class:"btn btn-secondary",
					initval:w.elements[i].maxparams,
					min:1,
					max:10,
					step:1,
					decimals:0,
					boostat:2,
					maxboostedstep:5
				});
				var dt = null;
				var b = $(this).find('.widgets_add_element_datatype');
				b.find('option').remove().end();
				if (b.hasClass("select2-hidden-accessible")) b.select2('destroy');
				if (jQuery.isArray(w.elements[i].datatypes)) {
					dt = jQuery.map(w.elements[i].datatypes, function(n,i) {
						let x = {
							id: n,
							text: n
						}
						return x;
					})
					$.each(w.elements[i].datatypes,function(i,v) {
						var option = new Option(v, v, true, true);
						b.append(option).trigger('change');
					});
				}
				b.select2({
					placeholder:"Add a supported datatype",
					width: '100%'
				});
				b.trigger({
					type: 'select2:select',
					params: {
						data: dt	
					}
				});
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
			elem.find('.widget-thumb img').attr('src',(w.logo!==undefined && w.logo!==null)?w.logo:'templates/default/assets/images/widget-ss.jpg');
			elem.find('.widget-title h6').text(w.name);
		}
		
		var refreshCards = function(a,s) {
			$(".widget-card:not(:last)").fadeOut();
			$.ajax({
				type: "POST",
				url: '/ajax-widgets-declare?s=1',
				data: {widgets_api: a, search: {value:s}},
				success: function(json, status, xhr) {					
					if (!$('input.widget-search').val().trim() && json.data.length==0) toastr.success(json.hasOwnProperty("desc")?json.desc:"You don't have any widget on this API app. Try to create a new one.");
					$(".widget-card:not(:last)").remove();
					$.each(json.data,function(i, w){
						showWidgetAdd(w);
					});
				},
				error: function(response, status, msg) {
					toastr.error(response.hasOwnProperty("responseJSON")?response.responseJSON.desc:"Something failed while refreshing your widget. Please try again.");
				}
			});
			
		}
	
		var refreshList = function() {
			$.ajax({
				type: "POST",
				url: '/ajax-api?s=1',
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
						$("#widget_select_api").select2({
							placeholder: "Select API App",
							data: json.data
						})
					}
					if (f==0) toastr.success(json.hasOwnProperty("desc")?json.desc:"You don't have any API App. Try to create a new one.");
					$("#widget_select_api").trigger("change");
				},
				error: function(response, status, msg) {
					toastr.error(response.hasOwnProperty("responseJSON")?response.responseJSON.desc:"You don't have any API App. Try to create a new one.");
				}
			});
		}
		
		var handleEdit = function(id,api,callbackOK,callbackERR) {
			if (id===null) return false; 
			$.ajax({
				type: "POST",
				url: '/ajax-widgets-declare?s=1',
				data: { widgets_api: api, id: id },
				success: function(json, status, xhr) {
					if ($.isFunction(callbackOK)) callbackOK();
					if (!json.hasOwnProperty("desc") || !json.hasOwnProperty("data") || !$.isArray(json.data) || json.data.length===0) toastr.success("Incorrect return. Fail to retrieve data. Please try again.");
					else {
						var m = $('#widget_add');
						fillWidgetAdd(m,json.data[0]);
						m.find('.modal-title').html('Edit Widget');
						m.find('#widgets_add_create').html('Update');
						m.modal('show');
						setTimeout(function() {
							m.find('.nav-link').eq(0).trigger('click');
							m.find('.nav-link').eq(m.find('.nav-link').length-2).trigger('click');
						},300);
					}
				},
				error: function(response, status, msg) {
					toastr.error(response.hasOwnProperty("responseJSON")?response.responseJSON.desc:"An error occurred while processing your request. Please try again.");
					if ($.isFunction(callbackERR)) callbackERR();
				}
			});
			return true;
		}		
		
		var handleUpdateState = function(arr,status,callbackOK,callbackERR) {
			if (arr.length===0) return false; 
			swal({
                title: "Are you sure?",
                text: "You are going to " + (status?"enable":"disable") + " " + arr.length + " of your widget(s).",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, update it!",
                cancelButtonText: "Cancel!",
                reverseButtons: !0
            }).then(function(e) {
				if (e.value) {
					$.ajax({
						type: "POST",
						url: '/ajax-widgets-declare?s=3',
						data: { widgets_id: arr, widgets_status: status },
						success: function(json, status, xhr) {
							toastr.success(json.hasOwnProperty("desc")?json.desc:"Your API has been updated.");
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
		
		var handleDeletion = function(arr,callbackOK,callbackERR) {
			if (arr.length===0) return false; 
			swal({
                title: "Are you sure?",
                text: "You are going to delete " + arr.length + " of your widget(s). You won't be able to revert this!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel!",
                reverseButtons: !0
            }).then(function(e) {
				if (e.value) {
					$.ajax({
						type: "POST",
						url: '/ajax-widgets-declare?s=4',
						data: { widgets_id: arr },
						success: function(json, status, xhr) {
							toastr.success(json.hasOwnProperty("desc")?json.desc:"Your widget has been deleted.");
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
				refreshCards($('#widget_select_api').val(),text);
			}, 1000);
		}

		$('#widget_add').on('shown.bs.modal', function () {
			if ($('.widgets-idx').val().trim()) return;
			$("#widgets_add_tags").select2({placeholder:"Add a tag",tags:!0});
			$(".widgets_add_element_datatype").select2({placeholder:"Add a supported datatype"});
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
			$(".widgets_add_element_maxparams").TouchSpin({
				buttondown_class:"btn btn-secondary",
				buttonup_class:"btn btn-secondary",
				initval:1,
				min:1,
				max:10,
				step:1,
				decimals:0,
				boostat:2,
				maxboostedstep:5
			})
		})
		$('#widget_add').on('hidden.bs.modal', function () {
			clearWidgetAdd(this);
		})


		$(".widgets_element_add").on("click", function(e) {
			e.preventDefault();
			var a = $(this);
			var x = $(this).closest('#widget_add');
			var i = x.find('.nav-tabs').children().length;
			var y = x.find('.nav-item:eq(0)').clone();
			var z = x.find('.tab-pane:eq(0)');
			var d = z.find('.m-select2');
			if (d.hasClass("select2-hidden-accessible")) d.select2('destroy');
			var b = z.clone();
			var id = Math.floor(Math.random()*1000);
			b.find('.widgets-number').trigger('touchspin.destroy');
			b.removeClass('active').attr('id','widgets_element_'+id);
			b.appendTo(x.find('.tab-content'));
			y.find('.nav-link').attr('href','#widgets_element_'+id).html('Element <i class="la la-close" style="padding-left: 10px;"></i>');
			y.insertBefore($(this).closest('.nav-item'));
			setTimeout(function() {
				a.removeClass('active').removeClass('show').trigger('mouseleave');
				x.find('.nav-link').eq(0).trigger('click');
				x.find('.nav-link').eq(i-1).trigger('click');
			},100);
		});
		
		$('#widgets_add_create').click(function(e) {
			e.preventDefault();
			var btn = $(this);
			var form = $(this).closest('form');
			form.validate({
				rules: {
					"widgets_id": {
						required: true,
						minlength: 3
					},
					"widgets_name": {
						required: true,
						minlength: 3
					},
					"widgets_element_id[]": {
						required: true,
						minlength: 3
					},
					"widgets_element_name[]": {
						required: true,
						minlength: 3
					}
				}
			});

			if (!form.valid()) {
				return;
			}
			btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
			form.ajaxSubmit({
				url: '/ajax-widgets-declare?s=2',
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
					showErrorMsg(form.find('#widget_info'), 'danger', response.hasOwnProperty("responseJSON")?response.responseJSON.desc:'Something failed while creating your widget. Please try again.');
				}
			});
		});
		
		jQuery(document.body).on('change','#widget_select_api',function(e) {
			refreshCards($(this).val(),$('input.widget-search').val());
		});
		
		jQuery(document.body).on('keyup','input.widget-search',function(e) {
			var x = $(this).val().trim().length;
			if (x==0 || x>=3) doSearch($(this).val());
		});
		
		jQuery(document.body).on('click','.nav-link',function(e) {
			e.preventDefault();
			var x = $(this).closest('#widget_add');
			if (!$(this).hasClass('widgets_element_add')) {
				$(this).tab('show');
				var y = x.find($(this).attr('href'));
				y.find('.widgets_add_settable').attr('name','widgets_element_settable[' + y.attr('id') + ']');
				y.find('.widgets_add_settable_hidden').attr('name','widgets_element_settable[' + y.attr('id') + ']');
				y.find('.widgets_add_retained').attr('name','widgets_element_retained[' + y.attr('id') + ']');
				y.find('.widgets_add_retained_hidden').attr('name','widgets_element_retained[' + y.attr('id') + ']');
				y.find('.widgets_add_element_datatype_hidden').attr('name','widgets_element_datatype[' + y.attr('id') + '][]');
				var z = y.find('.widgets_add_element_datatype').attr('name','widgets_element_datatype[' + y.attr('id') + '][]');
				if (!z.hasClass("select2-hidden-accessible")) z.select2({placeholder:"Add a supported datatype"});
				y.find('.widgets_add_element_maxparams').TouchSpin({
					buttondown_class:"btn btn-secondary",
					buttonup_class:"btn btn-secondary",
					initval:1,
					min:1,
					max:10,
					step:1,
					decimals:0,
					boostat:2,
					maxboostedstep:5
				})
			}			
		});
		jQuery(document.body).on('keyup','.widgets_element_name',function() {
			var a = $(this).val();
			var x = $(this).closest('#widget_add');
			var y = x.find('a.nav-link[href="#' + $(this).closest('.tab-pane').attr("id") + '"]');
			var z = x.find('a.nav-link').index(y)+1;
			var p = (z>1)?'&nbsp;<i class="la la-close" style="padding-left: 10px;"></i>':'';
			y.html(a==""?'Element '+z+p:a+p);
		});
		jQuery(document.body).on('click','.nav-link i',function(e) {
			e.preventDefault();
			if (!$(this).parent().hasClass('widgets_element_add')) {
				var x = $(this).closest('#widget_add');
				x.find('.nav-link').eq(0).trigger('click');
				x.find($(this).parent().attr('href')).remove();
				$(this).closest('.nav-item').remove();				
			}
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
			handleEdit(parseInt(a.attr('data-id')),$('#widget_select_api').val(),function() {
				btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false).find('i').addClass('flaticon-edit');
			},function() {
				btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false).find('i').addClass('flaticon-edit');
			});
		});
		jQuery(document.body).on('click','.card-status',function(e) {
			var x = $(this);
			var a = $(this).closest('.widget-card');
			var s = $(this).hasClass('btn-success')?0:1;
			handleUpdateState([parseInt(a.attr('data-id'))],s,function() {
				if (s) x.removeClass('btn-danger').addClass('btn-success').find('i').removeClass('la-unlink').addClass('la-link');
				else x.removeClass('btn-success').addClass('btn-danger').find('i').removeClass('la-link').addClass('la-unlink');
			});
		});
		jQuery(document.body).on('click','.card-del',function(e) {
			var a = $(this).closest('.widget-card');
			handleDeletion([parseInt(a.attr('data-id'))],function() {
				a.fadeOut("slow",function() { a.remove(); });
			});
		});
		
		
		refreshList();
    }	
};
					
jQuery(document).ready(function() {
    devMsgs.init('all,widget-declare');
    Snippet.init();
});