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
	
		var handlePayButton = function() {
			e.preventDefault();
				var btn = $(this);				

				$upg_to = btn.attr('data-id');
				$upg_term = btn.attr('data-plan');
				if (!$upg_to) return;				
				btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
				$.ajax({
					type: "POST",
					url: '/ajax-account-billing?s=3',
					data: { 
						"upgrade_to": $upg_to,
						"upgrade_term": $upg_term
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
							},
							onError: function(result){
								toastr.info("Order id " + result.order_id + " failed to processed. Please try again.");								
							}
						});
					},
					error: function(response, status, msg) {
						if (response.status==401) window.location.replace("/");
						btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
						toastr.error(response.hasOwnProperty("responseJSON")?response.responseJSON.desc:"An error occurred while processing your request. Please try again.");
					}
				});	
		}
	
		var handleActionView = function() {
			$("#billing-modal-inv .btn-print").on("click",function(){
				printModal(document.getElementById("printModal"));
			})
			$('.billing-view').click(function(e) {
				e.preventDefault();
				var btn = $(this);				
				btn.addClass('m-loader m-loader--right m-loader--light').attr('disabled', true);
				btn.find('i').hide();
				$.ajax({
					type: "POST",
					url: '/ajax-account-billing?s=2',
					data: { 
						"billing_id": btn.attr('data-id')
					},
					success: function(json, status, xhr) {
						btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
						btn.find('i').show();
						var issdate = new Date(json.data.issdate +'-0000');
						var duedate = new Date(json.data.duedate +'-0000');
						var paiddate = json.data.paiddate==null?null:(new Date(json.data.paddate +'-0000'));						
						var m = $('#billing-modal-inv');
						var n = m.find('.inv-item:eq(0)');
						n.hide();
						m.find('.inv-item:not(:eq(0))').remove();
						m.find('.inv-issdate').text(issdate.toLocaleDateString());
						m.find('.inv-order-id').text(json.data.order_id);
						m.find('.inv-to>span').text(json.data.invto);
						m.find('.inv-duedate').text(duedate.toLocaleDateString());
						m.find('.inv-paddate').text(paiddate==null?'':paiddate.toLocaleDateString());
						m.find('.inv-tax').text(json.data.tax);
						m.find('.inv-total>span').text(json.data.total);												
						json.data.items.forEach(function(item,i) {
							var elem = n.clone();
							elem.find('td:eq(0)').text(item.name);
							elem.find('td:eq(1)').text(item.price);
							elem.find('td:eq(2)').text(item.qty);
							elem.find('td:eq(3) span').text(item.disc);
							elem.find('td:eq(4)').text(item.total);
							elem.appendTo(n.parent());
							elem.show();
						});
						if (json.data.coupon_id) {
							var elem = n.clone();
							elem.find('td:eq(0)').text('Coupon "' + json.data.coupon_name + '"');
							elem.find('td:eq(1)').text('');
							elem.find('td:eq(2)').text('');
							elem.find('td:eq(3)').text('');
							elem.find('td:eq(4) span').text(json.data.coupon_value);
							elem.appendTo(n.parent());
							elem.show();							
						}
						m.find('.money').unmask().mask("#,##0.00", {reverse: true});
						m.modal();
					},
					error: function(response, status, msg) {
						if (response.status==401) window.location.replace("/");
						btn.removeClass('m-loader m-loader--right m-loader--light').attr('disabled', false);
						btn.find('i').show();
						showErrorMsg(form.find('#api_info'), 'danger', response.hasOwnProperty("responseJSON")?response.responseJSON.desc:'An error occurred while processing your request. Please try again.');
					}
				});				
			});
			$('.billing-btn-pay').click(function() {
				var btn = $(this);
				var told = btn.text();
				btn.addClass('m-loader m-loader--right m-loader--light').html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;').attr('disabled', true);
				$.ajax({
					type: "POST",
					url: '/ajax-account-billing?s=4',
					data: { 
						"billing_id": btn.attr('data-id')
					},
					success: function(json, status, xhr) {
						btn.removeClass('m-loader m-loader--right m-loader--light').text(told).attr('disabled', false);
						snap.pay(json.snapToken, {
							onSuccess: function(result){
								toastr.success("Order id " + result.order_id + " has been created successfully. Your account upgrading is now completed.");
								setTimeout(function() {tbl.draw(false);},1000);
							},
							onPending: function(result){
								toastr.info("Order id " + result.order_id + " has been created and now is in pending state.");
								setTimeout(function() {tbl.draw(false);},1000);
							},
							onError: function(result){
								toastr.info("Order id " + result.order_id + " failed to processed. Please try again.");
								setTimeout(function() {tbl.draw(false);},1000);
							}
						});
					},
					error: function(response, status, msg) {
						if (response.status==401) window.location.replace("/");
						btn.removeClass('m-loader m-loader--right m-loader--light').text(told).attr('disabled', false);
						toastr.error(response.hasOwnProperty("responseJSON")?response.responseJSON.desc:"An error occurred while processing your request. Please try again.");
					}
				});	
			});
		}

		var printModal = function(elem) {
			var domClone = elem.cloneNode(true);
			var $printSection = document.getElementById("printSection");
			if (!$printSection) {
				var $printSection = document.createElement("div");
				$printSection.id = "printSection";
				document.body.appendChild($printSection);
			}
			$printSection.innerHTML = "";
			$printSection.appendChild(domClone);
			window.print();
		}

        var tbl = $('#tbl_billing').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: "ajax-account-billing?s=1",
				type: "POST"
			},
			dom:"<'row'<'col-sm-8 text-left'f><'col-sm-4 text-right'B>>\n\t\t\t<'row'<'col-sm-12'tr>>\n\t\t\t<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
			buttons:["print", "copyHtml5", "excelHtml5", "csvHtml5", "pdfHtml5"],
			deferRender: true,
			columns: [
				{ data: "id" },
				{ data: "issdate" },
				{ data: "title" },
				{ data: "duedate" },
				{ data: "paiddate" },
				{ data: "total" },
				{ data: null }
			],
            responsive:!0,			
			lengthMenu:[5, 10, 25, 50, 100],
			pageLength:10,
			language: {
                lengthMenu: "Display _MENU_"
            },
			order:[[0, "desc"]],
			columnDefs:[{
                targets:0,
				width:"30px",
				className:"dt-right",
				searchable:!1,
				orderable:!1,
				visible: false
			},{
				responsivePriority:1,
                targets:-1,
				width:"100px",
				className:"dt-center",
				searchable:!1,
				orderable:!1,
				render:function(e, a, t, n) {
                    return '<a href="#" data-id="' + t.id + '" class="billing-view billing_row_view m-portlet__nav-link btn m-btn m-btn--hover-brand m-btn--icon m-btn--icon-only m-btn--pill" title="View"><i class="la la-search"></i></a>'
                }
            },{
                targets:1,
				width:"120px",
				searchable:!1,
				className:"dt-center",
				render:function(e, a, t, n) {
					var d = new Date(t.issdate +'-0000');
                    return d.toLocaleDateString();
                }
            },{
				responsivePriority:2,
                targets:2,
				searchable:1,
            },{
                targets:3,
				width:"120px",
				className:"dt-center",
				searchable:!1,
				render:function(e, a, t, n) {
					var d = new Date(t.duedate +'-0000');
                    return d.toLocaleDateString();
                },
				createdCell: function (td, cellData, rowData, row, col) {	
					if (cellData!==undefined && cellData!==null) {
						var a = new Date(cellData +'-0000');
						var b = new Date();
						var diff = new Date(a-b);
						diff = diff/1000/60/60/24;
						if (diff>=90) {
							$(td).css('background-color', mApp.getColor('accent'));
							$(td).css('color', '#fff');							
						} else if (diff>=7) {
							$(td).css('background-color', mApp.getColor('info'));
							$(td).css('color', '#fff');
						} else if (diff>=0) {
							$(td).css('background-color', mApp.getColor('warning'));
							$(td).css('color', '#fff');
						} else {
							$(td).css('background-color', mApp.getColor('danger'));
							$(td).css('color', '#fff');
						}
					}
				}
            },{
                targets:4,
				width:"120px",
				className:"dt-center",
				searchable:!1,
				render:function(e, a, t, n) {
					if (t.paiddate!==null) {
						var d = new Date(t.paiddate +'-0000');
						return d.toLocaleDateString();
					} else if (t.status=='unpaid') return '<strong>' + t.status.toUpperCase() + '</strong></br><span data-id="' + t.id + '" class="billing-btn-pay m-badge m-badge--info m-badge--wide">PAY</span>';
					else return '<strong>' + t.status.toUpperCase() + '</strong>';
                },
				createdCell: function (td, cellData, rowData, row, col) {
					var s = rowData.status;
					if (s!==undefined && s!==null) {
						if (s=='unpaid') $(td).css('background-color', mApp.getColor('danger'));
						else if (s=='challenge') $(td).css('background-color', mApp.getColor('warning'));
						else if (s=='success') $(td).css('background-color', mApp.getColor('success'));
						else if (s=='settlement') $(td).css('background-color', mApp.getColor('brand'));
						else if (s=='pending') $(td).css('background-color', mApp.getColor('focus'));
						else if (s=='denied') $(td).css('background-color', mApp.getColor('danger'));
						else if (s=='expired') $(td).css('background-color', mApp.getColor('metal'));
						else if (s=='cancel') $(td).css('background-color', mApp.getColor('metal'));
						else $(td).css('background-color', mApp.getColor('danger'));
						$(td).css('color', '#fff');	
					}
				}
            },{
                targets:5,
				width:"120px",
				className:"dt-right",
				searchable:!1,
				render:function(e, a, t, n) {
					var d = parseFloat(t.total);
                    return '<h5>' + $('.m-portlet__body').attr('data-currency') + d.toLocaleString() + '</h5>';
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
		tbl.on("click", "tbody tr .billing_row_del", function() {
			var row = tbl.row($(this).closest('tr'));
			handleDeletion([parseInt($(this).attr("data-id"))],function() {
				row.remove().draw(false);
			});
		})
		tbl.on("draw", function() {
			handleActionView();
		})
    }	
};

jQuery(document).ready(function() {
    devMsgs.init('all,billing');
    Snippet.init();
});