var devMsgs = {
	container: $('.m-content'),
	list: null,
	show: function() {
		if (devMsgs.list===null || devMsgs.list.length===0) return;
		var alert = $('<div class="msg m-alert m-alert--icon m-alert--air m-alert--square alert alert-dismissible m--margin-bottom-30" role="alert">\
			<button type="button" class="close" data-id="' + devMsgs.list[0].id + '" data-dismiss="alert" aria-label="Close"></button>\
			<div class="m-alert__icon"><i class="flaticon-exclamation m--font-' + devMsgs.list[0].type + '"></i></div><div class="m-alert__text"><span></span></div></div>');

		devMsgs.container.find('.msg').remove();
		alert.prependTo(devMsgs.container);
		mUtil.animateClass(alert[0], 'fadeIn animated');
		alert.find('span').html(devMsgs.list[0].title);
		alert.find('.close').click(function() {
			var idd = $(this).attr('data-id');
			$.ajax({
				type: "POST",
				url: '/ajax-msgs?s=2',
				data: { id: idd },
				success: function(json, status, xhr) {
					var str = $('#m_header_topbar .m-dropdown__header-title').text();
					var ori = parseInt(str.split(' ')[0]);
					ori = ori>0?ori-1:ori;
					str = str.replace(/^\S+/g, ori.toString());
					if (ori===0) $('#m_header_topbar .m-nav__link-badge').hide();
					$('#m_header_topbar .m-dropdown__header-title').text(str);
					$('#topbar_notifications_notifications .m-list-timeline__item[data-id="' + idd + '"]').addClass('m-list-timeline__item--read');
					devMsgs.list.shift();
					devMsgs.show();
				}
			});
		});
	},
	notif: function(json) {
		if (json.data.length>0) {
			var alert = $('<div class="m-list-timeline__item" data-id=""><span class="m-list-timeline__badge"></span>\
						<span class="m-list-timeline__text"></span>\
						<span class="m-list-timeline__time"></span></div>');
			var notifElem = $('#topbar_notifications_notifications .m-list-timeline__items');
			var str = $('#m_header_topbar .m-dropdown__header-title').text();
			var sticky = '<span class="m-badge m-badge--accent m-badge--wide">sticky</span>';
			var d = json.data.filter(function(value, index, arr){ return !value.isread });
			notifElem.empty();
			if (d.length>0) {
				str = str.replace(/^\S+/g, d.length.toString());
				$('#m_header_topbar .m-nav__link-badge').show();
				$('#m_header_topbar .m-dropdown__header-title').text(str);
			} else $('#m_header_topbar .m-nav__link-badge').hide();
			$.each(json.data, function(i, item) {
				var d = new Date(item.born_date +'-0000');
				var b = compareDate(d,new Date());
				var a = alert.clone();
				var t = item.title;	
				if (item.sticky) t += ' ' + sticky;
				if (item.isread) a.addClass('m-list-timeline__item--read');
				a.find('.m-list-timeline__text').html(t);
				a.find('.m-list-timeline__time').html(b.d + ' ' + b.u);
				a.attr('data-id',item.id);
				a.prependTo(notifElem);
			});	
		} else {
			$('#m_header_topbar .m-nav__link-badge').hide();
		}
	},
	init: function(s) {
		$.ajax({
			type: "POST",
			url: '/ajax-msgs?s=1',
			success: function(json, status, xhr) {
				devMsgs.notif(json);
				if (json.data.length>0) {
					var d = json.data.filter(function(value, index, arr){
						var chk = 0;
						s.split(",").forEach(function(item) {
							if ($.inArray(item,value.tags)>=0) chk=1;
						});
						return (!value.isread || value.sticky) && chk;
					});
					devMsgs.list = d.sort((a,b) => (a.sticky > b.sticky) ? -1 : ((b.sticky > a.sticky) ? 1 : 0)); 
					devMsgs.show();
				}
			}
		});
	}	
}

jQuery(document).ready(function() {
});