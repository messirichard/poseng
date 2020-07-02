var Snippet = {
    init: function() {
		var isUrl =	function(s) {
		   var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
		   return regexp.test(s);
		}
		
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
				$('#tbl_users_filter').fadeOut(100,function() {
					$("#users_with_selected").fadeIn(100);
				});
			} else {
				$("#users_with_selected").fadeOut(100,function() {
					$('#tbl_users_filter').fadeIn(100);
				});
			}
		}
		
		var handleUpdateState = function(arr,type,callbackOK,callbackERR) {
			if (arr.length===0) return false; 
			swal({
                title: "Are you sure?",
                text: "You are going to update the status of " + arr.length + " user(s).",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, update it!",
                cancelButtonText: "Cancel!",
                reverseButtons: !0
            }).then(function(e) {
				if (e.value) {
					$.ajax({
						type: "POST",
						url: '/ajax-users?s=2',
						data: { "user_id": arr, "user_status" : type },
						success: function(json, status, xhr) {
							toastr.success(json.hasOwnProperty("desc")?json.desc:"Your user has been updated.");
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
                text: "You are going to delete " + arr.length + " of user(s). You won't be able to revert this!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel!",
                reverseButtons: !0
            }).then(function(e) {
				if (e.value) {
					$.ajax({
						type: "POST",
						url: '/ajax-users?s=3',
						data: { "user_id": arr },
						success: function(json, status, xhr) {
							toastr.success(json.hasOwnProperty("desc")?json.desc:"Your user has been deleted.");
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
	
		var tbl = $('#tbl_users').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: "ajax-users?s=1",
				type: "POST"
			},
			dom:"<'row'<'col-sm-8 text-left'f><'col-sm-4 text-right'B>>\n\t\t\t<'row'<'col-sm-12'tr>>\n\t\t\t<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
			buttons:["print", "copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
			deferRender: true,
			columns: [
				{ data: "id" },
				{ data: "user_fname" },
				{ data: "user_email" },
				{ data: "user_phone" },
				{ data: "org_name" },
				{ data: "org_addr1" },
				{ data: "org_web" },
				{ data: "org_email" },
				{ data: "org_phone" },
				{ data: "born_date" },
				{ data: "status" },
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
                targets:1,
				render:function(e, a, t, n) {
					var n = t.user_fname + ' '  + t.user_lname;
					var m = (t.user_avatar===null || t.user_avatar.length === 0 || !t.user_avatar.trim())?'<div class="m-card-user__no-photo m--bg-fill-success"><span>' + t.user_fname.charAt(0).toUpperCase() + '</span></div>':'<img src="' + t.user_avatar + '" class="m--img-rounded m--marginless" alt="' + n + '">';
					return '<div class="m-card-user m-card-user--sm">\
                            <div class="m-card-user__pic">' + m + '</div>\
							<div class="m-card-user__details"><span class="m-card-user__name">' + n + '</span></div>\
							</div>'
                }
            },{
                targets:2,
				render:function(e, a, t, n) {
					var s = (e===null || e.length === 0 || !e.trim());
                    return void s?e:'<a href="mailto:' + e + '" />' + e + '</a>';
                }
            },{
                targets:3,
				width:"120px",
				searchable:!1
            },{
                targets:5,
				searchable:!1
            },{
                targets:6,
				searchable:!1,
				render:function(e, a, t, n) {
					var s = (e===null || e.length === 0 || !e.trim())?false:(isUrl(e)?true:false);
                    return s?'<a href="' + e + '" target="_blank" />' + e + '</a>':'';
                }
            },{
                targets:7,
				render:function(e, a, t, n) {
					var s = (e===null || e.length === 0 || !e.trim());
                    return s?e:'<a href="mailto:' + e + '" />' + e + '</a>';
                }
            },{
                targets:8,
				searchable:!1
            },{
                targets:9,
				width:"120px",
				searchable:!1,
				render:function(e, a, t, n) {
					var d = new Date(t.born_date +'-0000');
                    return d.toLocaleString();
                }
            },{
                targets:10,
				width:"60px",
				className:"dt-center",
				searchable:!1,
				render:function(e, a, t, n) {
                    var s= {
						false: {
                            title: "Unconfirmed", class: " m-badge--metal"
                        },
						true: {
                            title: "Confirmed", class: " m-badge--success"
                        }						
                    };
                    return void 0===s[e]?e:'<span class="m-badge '+s[e].class+' m-badge--wide">'+s[e].title+'</span>'
                }
            },{
				responsivePriority:1,
                targets:-1,
				width:"100px",
				className:"dt-center",
				searchable:!1,
				orderable:!1,
				render:function(e, a, t, n) {
                    return '<span class="dropdown"><a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true"><i class="la la-toggle-down"></i></a><div class="dropdown-menu dropdown-menu-right"><a class="users_row_upd dropdown-item" href="#" data-upd="0" data-id="' + t.id + '"><i class="la la-unlink"></i> Unconfirm</a><a class="users_row_upd dropdown-item" href="#" data-upd="1" data-id="' + t.id + '"><i class="la la-link"></i> Confirm</a></div></span><a href="#" data-id="' + t.id + '" class="users_row_del m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="Delete"><i class="la la-trash"></i></a>'
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
		tbl.on("click", "tbody tr .users_row_del", function() {
			var row = tbl.row($(this).closest('tr'));
			handleDeletion([parseInt($(this).attr("data-id"))],function() {
				row.remove().draw(false);
			});
		})
		tbl.on("click", "tbody tr .users_row_upd", function() {
			var row = tbl.row($(this).closest('tr'));
			handleUpdateState([parseInt($(this).attr("data-id"))],parseInt($(this).attr("data-upd")),function() {
				row.draw(false);
			});
		})		
		$("#users_with_selected").detach().appendTo($('#tbl_users_filter').parent());
		$("#users_rows_del").click(function() {
			var rows = tbl.rows('.active');
			var arr = rows.data().toArray().map(function(obj,key) {
				return parseInt(obj.id);
			});
			handleDeletion(arr,function() {
				rows.remove().draw(false);
			});
		});
		$(".users_rows_upd").click(function() {
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
    devMsgs.init('all,users');
    Snippet.init();
});