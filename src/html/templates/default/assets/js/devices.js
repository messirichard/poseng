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
				$('#tbl_devices_filter').fadeOut(100,function() {
					$("#devices_with_selected").fadeIn(100);
				});
			} else {
				$("#devices_with_selected").fadeOut(100,function() {
					$('#tbl_devices_filter').fadeIn(100);
				});
			}
		}
		
		var handleUpdateState = function(arr,type,callbackOK,callbackERR) {
			if (arr.length===0) return false; 
			swal({
                title: "Are you sure?",
                text: "You are going to update the status of " + arr.length + " device(s).",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, update it!",
                cancelButtonText: "Cancel!",
                reverseButtons: !0
            }).then(function(e) {
				if (e.value) {
					$.ajax({
						type: "POST",
						url: '/ajax-devices?s=3',
						data: { "device_id": arr, "device_status" : type },
						success: function(json, status, xhr) {
							toastr.success(json.hasOwnProperty("desc")?json.desc:"Your device has been updated.");
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
		
		var handleUpdateLog = function(arr,type,callbackOK,callbackERR) {
			if (arr.length===0) return false; 
			var c = null;
			swal({
                title: type==2?"Max. Log":"Are you sure?",
                text: type==2?"Enter maximum log allowed in MB (0 for no limit)":"You are going to update the log state of " + arr.length + " device(s).",
                type: type==2?"question":"warning",
				input: type==2?"number":null,
				inputAttributes: {
					min: 0
				},
                showCancelButton: !0,
                confirmButtonText: type==2?"Set Max. Log!":"Yes, update it!",
                cancelButtonText: "Cancel!",
                reverseButtons: !0,
				inputValidator: (value) => {
					return value<0 && 'You need to set the value greater than or equal to 0 (zero)!'
				}
            }).then(function(e) {
				if (e.value) {
					$.ajax({
						type: "POST",
						url: '/ajax-devices?s=2',
						data: { "device_id": arr, "device_status" : type, "device_value": e.value*1000000 },
						success: function(json, status, xhr) {
							toastr.success(json.hasOwnProperty("desc")?json.desc:"Your device has been updated.");
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
                text: "You are going to delete " + arr.length + " of device(s). You won't be able to revert this!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel!",
                reverseButtons: !0
            }).then(function(e) {
				if (e.value) {
					$.ajax({
						type: "POST",
						url: '/ajax-devices?s=4',
						data: { "device_id": arr },
						success: function(json, status, xhr) {
							toastr.success(json.hasOwnProperty("desc")?json.desc:"Your device has been deleted.");
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
	
	
		var nameBytes = function($number,$dec) {
			$no = $number / 1000000000000;
			if ($no>=1) return (($no*10%10)==0?$no.toFixed(0):$no.toFixed($dec)) + " TB";
			else {
				$no = $number / 1000000000;
				if ($no>=1) return (($no*10%10)==0?$no.toFixed(0):$no.toFixed($dec)) + " GB";
				else {
					$no = $number / 1000000;
					if ($no>=1) return (($no*10%10)==0?$no.toFixed(0):$no.toFixed($dec)) + " MB";
					else {
						$no = $number / 1000;
						if ($no>=1) return (($no*10%10)==0?$no.toFixed(0):$no.toFixed($dec)) + " KB";
						else return $number + ' B';
					}
				}
			}
		}

		var tbl = $('#tbl_devices').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: "ajax-devices?s=1",
				type: "POST"
			},
			dom:"<'row'<'col-sm-8 text-left'f><'col-sm-4 text-right'B>>\n\t\t\t<'row'<'col-sm-12'tr>>\n\t\t\t<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
			buttons:["print", "copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
			deferRender: true,
			columns: [
				{ data: "id" },
				{ data: "sn" },
				{ data: "model" },
				{ data: "name" },
				{ data: "mac" },
				{ data: "sammy" },
				{ data: "fw_name" },
				{ data: "born_date" },
				{ data: "admin_id" },
				{ data: "loglimit" },
				{ data: "logme" },
				{ data: "state" },
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
                targets:1
            },{
                targets:4,
				width:"120px",
				searchable:!1
            },{
                targets:5,
				width:"50px",
				searchable:!1
            },{
                targets:6,
				width:"120px",
				searchable:!1,
				render:function(e, a, t, n) {
                    return t.fw_name + ' / ' + t.fw_version;
                }				
            },{
                targets:7,
				width:"120px",
				searchable:!1,
				render:function(e, a, t, n) {
					var d = new Date(t.born_date +'-0000');
                    return d.toLocaleString();
                }
            },{
                targets:8,
				searchable:!1,
				orderable:!1,
				render:function(e, a, t, n) {
                    return t.admin_name;
                }
            },{
                targets:9,
				width:"50px",
				searchable:!1,
				render:function(e, a, t, n) {
					var x = nameBytes(t.loglimit,2);
					return x;
                }
            },{
                targets:10,
				width:"60px",
				className:"dt-center",
				searchable:!1,
				render:function(e, a, t, n) {
                    var s= {
						false: {
                            title: "No", class: " m-badge--warning"
                        },
						true: {
                            title: "Yes", class: " m-badge--info"
                        }	
                    };
                    return void 0===s[e]?e:'<span class="m-badge '+s[e].class+' m-badge--wide">'+s[e].title+"</span>"
                }
            },{
                targets:11,
				width:"60px",
				className:"dt-center",
				searchable:!1,
				render:function(e, a, t, n) {
                    var s= {
						'init': {
                            title: "Initializing", class: " m-badge--info"
                        },
						'ready': {
                            title: "Ready", class: " m-badge--success"
                        },
						'disconnected': {
                            title: "Disconnected", class: " m-badge--metal"
                        },
						'sleeping': {
                            title: "Sleeping", class: " m-badge--sleeping"
                        },
						'lost': {
                            title: "Lost", class: " m-badge--danger"
                        },
						'alert': {
                            title: "Alert", class: " m-badge--warning"
                        }						
                    };
                    return void 0===s[e]?e:'<span class="m-badge '+s[e].class+' m-badge--wide">'+s[e].title+"</span>"
                }
            },{
                targets:12,
				width:"60px",
				className:"dt-center",
				searchable:!1,
				render:function(e, a, t, n) {
                    var s= {
						false: {
                            title: "Deactivated", class: " m-badge--metal"
                        },
						true: {
                            title: "Activated", class: " m-badge--success"
                        }						
                    };
                    return void 0===s[e]?e:'<span class="m-badge '+s[e].class+' m-badge--wide">'+s[e].title+"</span>"
                }
            },{
				responsivePriority:1,
                targets:-1,
				width:"100px",
				className:"dt-center",
				searchable:!1,
				orderable:!1,
				render:function(e, a, t, n) {
                    return '<span class="dropdown"><a href="#" class="btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="true"><i class="la la-toggle-down"></i></a><div class="dropdown-menu dropdown-menu-right"><a class="devices_row_upd dropdown-item" href="#" data-upd="0" data-id="' + t.id + '"><i class="la la-unlink"></i> Deactivate</a><a class="devices_row_upd dropdown-item" href="#" data-upd="1" data-id="' + t.id + '"><i class="la la-link"></i> Activate</a></div></span><a href="#" data-id="' + t.id + '" class="devices_row_del m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="Delete"><i class="la la-trash"></i></a>'
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
		tbl.on("click", "tbody tr .devices_row_del", function() {
			var row = tbl.row($(this).closest('tr'));
			handleDeletion([parseInt($(this).attr("data-id"))],function() {
				row.remove().draw(false);
			});
		})
		tbl.on("click", "tbody tr .devices_row_upd", function() {
			var row = tbl.row($(this).closest('tr'));
			handleUpdateState([parseInt($(this).attr("data-id"))],parseInt($(this).attr("data-upd")),function() {
				row.draw(false);
			});
		})		
		$("#devices_with_selected").detach().appendTo($('#tbl_devices_filter').parent());
		$("#devices_rows_del").click(function() {
			var rows = tbl.rows('.active');
			var arr = rows.data().toArray().map(function(obj,key) {
				return parseInt(obj.id);
			});
			handleDeletion(arr,function() {
				rows.remove().draw(false);
			});
		});
		$(".devices_rows_upd").click(function() {
			var rows = tbl.rows('.active');
			var arr = rows.data().toArray().map(function(obj,key) {
				return parseInt(obj.id);
			});
			handleUpdateState(arr,parseInt($(this).attr("data-upd")),function() {
				rows.draw(false);
			});
		});
		$(".devices_rows_log").click(function() {
			var rows = tbl.rows('.active');
			var arr = rows.data().toArray().map(function(obj,key) {
				return parseInt(obj.id);
			});
			handleUpdateLog(arr,parseInt($(this).attr("data-upd")),function() {
				rows.draw(false);
			});
		});
    }	
};

jQuery(document).ready(function() {
    devMsgs.init('all,devices');
    Snippet.init();
});