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
	
		var handleSelection = function(rows) {
			if (rows.data().length>0) {
				$('#tbl_api_filter').fadeOut(100,function() {
					$("#api_with_selected").fadeIn(100);
				});
			} else {
				$("#api_with_selected").fadeOut(100,function() {
					$('#tbl_api_filter').fadeIn(100);
				});
			}
		}
		
		var handleUpdateState = function(arr,type,callbackOK,callbackERR) {
			if (arr.length===0) return false; 
			swal({
                title: "Are you sure?",
                text: "You are going to update the state of " + arr.length + " API(s).",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, update it!",
                cancelButtonText: "Cancel!",
                reverseButtons: !0
            }).then(function(e) {
				if (e.value) {
					$.ajax({
						type: "POST",
						url: '/ajax-api?s=3',
						data: { "api_id": arr, "api_type" : type },
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
                text: "You are going to delete " + arr.length + " of API(s). You won't be able to revert this!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel!",
                reverseButtons: !0
            }).then(function(e) {
				if (e.value) {
					$.ajax({
						type: "POST",
						url: '/ajax-api?s=4',
						data: { "api_id": arr },
						success: function(json, status, xhr) {
							toastr.success(json.hasOwnProperty("desc")?json.desc:"Your API has been deleted.");
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
	
		var handleCreateFormSubmit = function() {
			$('#api_alert').hide();
			$('#api_add_close').click(function(e) {
				e.preventDefault();
				var btn = $(this);
				var form = $(this).closest('form');
				
				
				
				form.resetForm();
				form.find('#api_info .alert').remove();
				$('#api_alert').hide();
			});
			$('#api_add_create').click(function(e) {
				e.preventDefault();
				var btn = $(this);
				var form = $(this).closest('form');
				
				form.validate({
					rules: {
						api_name: {
							required: true,
							minlength: 5
						}
					}
				});

				if (!form.valid()) {
					return;
				}
				$('#api_alert').fadeOut();
				btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
				form.ajaxSubmit({
					url: '/ajax-api?s=2',
					accepts: {
						text: "application/json"
					},
					success: function(json, status, xhr, $form) {						
						form.find('input[name=api_usr]').val(json.body.api_username);
						form.find('input[name=api_pwd]').val(json.body.api_password);
						btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
						$('#api_alert').fadeIn();
						showErrorMsg(form.find('#api_info'), 'success', json.hasOwnProperty("desc")?json.desc:'API successfully created..!');
						tbl.draw(false);
					},
					error: function(response, status, msg, $form) {
						if (response.status==401) window.location.replace("/");
						btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
						showErrorMsg(form.find('#api_info'), 'danger', response.hasOwnProperty("responseJSON")?response.responseJSON.desc:'Something failed while creating your API. Please try again.');
					}
				});
			});
		}
		handleCreateFormSubmit();

        var tbl = $('#tbl_api').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: "ajax-api?s=1",
				type: "POST"
			},
			dom:"<'row'<'col-sm-8 text-left'f><'col-sm-4 text-right'B>>\n\t\t\t<'row'<'col-sm-12'tr>>\n\t\t\t<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
			buttons:["print", "copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
			deferRender: true,
			columns: [
				{ data: "id" },
				{ data: "name" },
				{ data: "api_username" },
				{ data: "state" },
				{ data: null }
			],
            responsive:!0,			
			lengthMenu:[5, 10, 25, 50, 100],
			pageLength:10,
			language: {
                lengthMenu: "Display _MENU_"
            },
			order:[[1, "desc"]],
			headerCallback:function(e, a, t, n, s) {
                e.getElementsByTagName("th")[0].innerHTML='<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="" class="m-group-checkable"><span></span></label>'
            },
			drawCallback: function( settings ) {
				handleSelection(tbl.rows('.active'));
			},
			columnDefs:[{
                targets:0,
				width:"30px",
				className:"dt-right",
				searchable:!1,
				orderable:!1,
				render:function(e, a, t, n) {
					return '<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="' + t.id + '" class="m-checkable"><span></span></label>'
                }
			},{
				responsivePriority:1,
                targets:-1,
				width:"100px",
				className:"dt-center",
				searchable:!1,
				orderable:!1,
				render:function(e, a, t, n) {
                    return '<span class="dropdown"><a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true"><i class="la la-toggle-down"></i></a><div class="dropdown-menu dropdown-menu-right"><a class="api_row_upd dropdown-item" href="#" data-upd="0" data-id="' + t.id + '"><i class="la la-unlink"></i> Deactivate</a><a class="api_row_upd dropdown-item" href="#" data-upd="1" data-id="' + t.id + '"><i class="la la-tachometer"></i> Assign as Device API</a><a class="api_row_upd dropdown-item" href="#" data-upd="2" data-id="' + t.id + '"><i class="la la-desktop"></i> Assign as App API</a></div></span><a href="#" data-id="' + t.id + '" class="api_row_del m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="Delete"><i class="la la-trash"></i></a>'
                }
            },{
                targets:3,
				width:"80px",
				className:"dt-center",
				searchable:!1,
				render:function(e, a, t, n) {
                    var s= {
						0: {
                            title: "Stop", class: " m-badge--metal"
                        },
						1: {
                            title: "Device", class: " m-badge--success"
                        },
						2: {
                            title: "App", class: " m-badge--info"
                        },
						3: {
                            title: "Suspended", class: " m-badge--danger"
                        }						
                    };
                    return void 0===s[e]?e:'<span class="m-badge '+s[e].class+' m-badge--wide">'+s[e].title+"</span>"
                }
            }]
        });
        tbl.on("change", ".m-group-checkable", function() {
            var e=$(this).closest("table").find("td:first-child .m-checkable"), a=$(this).is(":checked");
            $(e).each(function() {
                a?($(this).prop("checked", !0), $(this).closest("tr").addClass("active")): ($(this).prop("checked", !1), $(this).closest("tr").removeClass("active"))
            })
			handleSelection(tbl.rows('.active'));
        });
        tbl.on("change", "tbody tr .m-checkbox", function() {
            $(this).parents("tr").toggleClass("active")
			handleSelection(tbl.rows('.active'));
        })
		tbl.on("click", "tbody tr .api_row_del", function() {
			var row = tbl.row($(this).closest('tr'));
			handleDeletion([parseInt($(this).attr("data-id"))],function() {
				row.remove().draw(false);
			});
		})
		tbl.on("click", "tbody tr .api_row_upd", function() {
			var row = tbl.row($(this).closest('tr'));
			handleUpdateState([parseInt($(this).attr("data-id"))],parseInt($(this).attr("data-upd")),function() {
				row.draw(false);
			});
		})		
		$("#api_with_selected").detach().appendTo($('#tbl_api_filter').parent());
		$("#api_rows_del").click(function() {
			var rows = tbl.rows('.active');
			var arr = rows.data().toArray().map(function(obj,key) {
				return parseInt(obj.id);
			});
			handleDeletion(arr,function() {
				rows.remove().draw(false);
			});
		});
		$(".api_rows_upd").click(function() {
			var rows = tbl.rows('.active');
			var arr = rows.data().toArray().map(function(obj,key) {
				return parseInt(obj.id);
			});
			handleUpdateState(arr,parseInt($(this).attr("data-upd")),function() {
				rows.draw(false);
			});
		});		
    }	
};

jQuery(document).ready(function() {
    devMsgs.init('all,api');
    Snippet.init();
});