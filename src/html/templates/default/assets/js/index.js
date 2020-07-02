class myPie {
	constructor() {
		this.elemName = null;
		this.chart = null;
	}
	
	init(elemName) {
		this.elemName = elemName;
		if (0 != $(this.elemName).length) {
			var e = new Chartist.Pie(this.elemName, {
				series: [],
				labels: []
			},{
				donut: !0,
				donutWidth: 17,
				showLabel: !1
			});
			e.on("draw", function(e) {
				if ("slice" === e.type) {
					var t = e.element._node.getTotalLength();
					e.element.attr({
						"stroke-dasharray": t + "px " + t + "px"
					});
					var a = {
						"stroke-dashoffset": {
							id: "anim" + e.index,
							dur: 1e3,
							from: -t + "px",
							to: "0px",
							easing: Chartist.Svg.Easing.easeOutQuint,
							fill: "freeze",
							stroke: e.meta.color
						}
					};
					0 !== e.index && (a["stroke-dashoffset"].begin = "anim" + (e.index - 1) + ".end"),
					e.element.attr({
						"stroke-dashoffset": -t + "px",
						stroke: e.meta.color
					}),
					e.element.animate(a, !1)
				}
			}),
			e.on("created", function() {
				window.__anim21278907124 && (clearTimeout(window.__anim21278907124),
				window.__anim21278907124 = null),
				window.__anim21278907124 = setTimeout(e.update.bind(e), 15e3)
			});
			this.chart = e;
		}
	}

	update(total,data) {	
		var el = $(this.elemName).closest('.row').find('.w-legends');
		el.empty();
		data.series.forEach(function(item,i) {
			var x = item.value/total*100;
			x = x || 0;
			var legend = $('<div class="m-widget27__legend">\
						<span class="m-widget27__legend-bullet" style="background-color: ' + item.meta.color + '!important"></span>\
						<span class="m-widget27__legend-text">' + Math.round(x) + '% ' + item.name + '</span></div>');
			legend.appendTo(el);
		});
		this.chart.update(data);
	}
	
	refresh() {
		this.chart.update();
	}
}

var clr = ["accent","warning","danger","brand","success","focus","metal"];

var handleGrowth = function() {
	var elem = $('#w-growth');
	elem.find('.w-option').click(function(e) {
		e.preventDefault();
		var d = parseInt($(this).attr('data-day'));
		elem.find('.w-options').text($(this).find('span').text());
		$.ajax({
			type: "POST",
			url: '/ajax-index?s=1',
			data: { "range": d },
			success: function(json, status, xhr) {
				var dm = ["January", "February", "March","April", "May", "June", "July","August", "September", "October","November", "December"];
				var umin = null;
				var umax = null;
				var dmin = null;
				var dmax = null;
				if (json.body.data_users.length>0) {
					umin = new Date(json.body.data_users[0].born_date +'-0000');
					umax = new Date(json.body.data_users[json.body.data_users.length-1].born_date +'-0000');
				}
				if (json.body.data_devices.length>0) {
					dmin = new Date(json.body.data_devices[0].born_date +'-0000');
					dmax = new Date(json.body.data_devices[json.body.data_devices.length-1].born_date +'-0000');
				}
				var min = umin<dmin?umin:dmin;
				var max = umax<dmax?dmax:umax;
				var r = 24*60*60*1000;
				var clabels = [];
				var cvals = [];
				cvals[0] = [];
				cvals[1] = [];
				min = umin===null?dmin:min;
				max = umax===null?dmax:max;
				if (min==null || max==null) return;
				if (d<=0 || d>180) {
					var md = moment(min).local();
				    var t = (max.getFullYear() - min.getFullYear()) * 12;
					t -= min.getMonth();
					t += max.getMonth()+1;
					t = t<=0?0:t;
					for (i=0;i<t;i++) {
						cvals[0][i] = 0;
						cvals[1][i] = 0;
						json.body.data_users.forEach(function(item,j) {
							var ud = moment(item.born_date);
							if (ud.year()==md.year() && ud.month()==md.month()) cvals[0][i] = parseInt(item.b);
						});
						json.body.data_devices.forEach(function(item,j) {
							var ud = moment(item.born_date);
							if (ud.year()==md.year() && ud.month()==md.month()) cvals[1][i] = parseInt(item.b);
						});
						clabels.push(md.format("MMM, YYYY"));
						md.add(1,'M');
					}					
				} else if (d<=30) {
					var md = moment(min).local();
					var t = new Date(max-min);
					t = Math.ceil(t/(r))+1;
					for (i=0;i<t;i++) {						
						cvals[0][i] = 0;
						cvals[1][i] = 0;
						json.body.data_users.forEach(function(item,j) {
							var ud = moment(item.born_date);
							if (ud.year()==md.year() && ud.month()==md.month() && ud.date()==md.date()) cvals[0][i] = parseInt(item.b);
						});
						json.body.data_devices.forEach(function(item,j) {
							var ud = moment(item.born_date);
							if (ud.year()==md.year() && ud.month()==md.month() && ud.date()==md.date()) cvals[1][i] = parseInt(item.b);
						});
						clabels.push(md.format("MMM Do, YYYY"));
						md.add(1,'d');
					}
				} else {
					var md = moment(min).local();
					var t = new Date(max-min);
					t = Math.ceil(t/(r*7));
					for (i=0;i<t;i++) {
						cvals[0][i] = 0;
						cvals[1][i] = 0;
						json.body.data_users.forEach(function(item,j) {
							var ud = moment(item.born_date);
							if (ud.year()==md.year() && ud.week()==md.week()) cvals[0][i] = parseInt(item.b);
						});
						json.body.data_devices.forEach(function(item,j) {
							var ud = moment(item.born_date);
							if (ud.year()==md.year() && ud.week()==md.week()) cvals[1][i] = parseInt(item.b);
						});						
						clabels.push('Week ' + Math.ceil(md.date()/7)  + ' of ' + md.format("MMM, YYYY"));
						md.add(1,'w');
					}
				}
				
				myChart.update(clabels,cvals);
				elem.find('.w-option-users span').text('+' + json.body.total_users);
				elem.find('.w-option-devices span').text('+' + json.body.total_devices);
				animateCSS('#w-growth .w-option-users', 'flash');
				animateCSS('#w-growth .w-option-devices', 'flash');
			},
			error: function(response, status, msg) {
				toastr.error(response.hasOwnProperty("responseJSON")?response.responseJSON.desc:"An error occurred while requesting data. Please try again.");
			}
		});
	});
	
	var myChart = {
		chart: null,
		init: function() {
			if (0 != $("#m_chart_trends_stats").length) {
				var e = document.getElementById("m_chart_trends_stats").getContext("2d");
				var t = e.createLinearGradient(0, 0, 0, 240);
				var u = e.createLinearGradient(0, 0, 0, 240);
				t.addColorStop(0, Chart.helpers.color("#00c5dc").alpha(.7).rgbString()),
				t.addColorStop(1, Chart.helpers.color("#f2feff").alpha(0).rgbString());
				u.addColorStop(0, Chart.helpers.color("#2fdc00").alpha(.7).rgbString()),
				u.addColorStop(1, Chart.helpers.color("#f3fff2").alpha(0).rgbString());
				a = {
					type: "line",
					data: {
						labels: [],
						datasets: [{
							label: "New Users",
							backgroundColor: t,
							borderColor: "#0dc8de",
							pointBackgroundColor: Chart.helpers.color("#ffffff").alpha(0).rgbString(),
							pointBorderColor: Chart.helpers.color("#ffffff").alpha(0).rgbString(),
							pointHoverBackgroundColor: mApp.getColor("warning"),
							pointHoverBorderColor: Chart.helpers.color("#0dc8de").alpha(.2).rgbString(),
							data: []
						},{
							label: "New Devices",
							backgroundColor: u,
							borderColor: "#33cc00",
							pointBackgroundColor: Chart.helpers.color("#ffffff").alpha(0).rgbString(),
							pointBorderColor: Chart.helpers.color("#ffffff").alpha(0).rgbString(),
							pointHoverBackgroundColor: mApp.getColor("warning"),
							pointHoverBorderColor: Chart.helpers.color("#33cc00").alpha(.2).rgbString(),
							data: []
						}],
					},
					options: {
						title: {
							display: !1
						},
						tooltips: {
							intersect: !1,
							mode: "nearest",
							xPadding: 10,
							yPadding: 10,
							caretPadding: 10
						},
						legend: {
							display: !1
						},
						responsive: !0,
						maintainAspectRatio: !1,
						hover: {
							mode: "index"
						},
						scales: {
							xAxes: [{
								display: !1,
								gridLines: !1,
								scaleLabel: {
									display: !0,
									labelString: "Month"
								}
							}],
							yAxes: [{
								display: !1,
								gridLines: !1,
								scaleLabel: {
									display: !0,
									labelString: "Value"
								},
								ticks: {
									beginAtZero: !0
								}
							}]
						},
						elements: {
							line: {
								tension: .19
							},
							point: {
								radius: 4,
								borderWidth: 12
							}
						},
						layout: {
							padding: {
								left: 0,
								right: 0,
								top: 5,
								bottom: 0
							}
						}
					}
				};
				myChart.chart = new Chart(e,a);
			}
		},
		clear: function() {
			myChart.chart.data.labels = [];
			myChart.chart.data.datasets.forEach((dataset) => {
				dataset.data = [];
			});
			myChart.chart.update();
		},
		update: function(labels,vals) {
			myChart.clear();
			myChart.chart.data.labels = labels;
			myChart.chart.data.datasets.forEach((dataset,index) => {
				dataset.data = vals[index];
			});
			myChart.chart.update();
		}
	}
	
	myChart.init();
	elem.find('.w-option:eq(4)').trigger('click');
}

var handleUsers = function() {
	var elem = $('#w-users');
	var chart1 = new myPie();
	var chart2 = new myPie();
	var chart3 = new myPie();
	chart1.init('#m_chart_users_tab_1');
	chart2.init('#m_chart_users_tab_2');
	chart3.init('#m_chart_users_tab_3');
	elem.find('.w-option').click(function(e) {
		e.preventDefault();
		var d = parseInt($(this).attr('data-day'));
		elem.find('.w-options').text($(this).find('span').text());
		$.ajax({
			type: "POST",
			url: '/ajax-index?s=2',
			data: { "range": d },
			success: function(json, status, xhr) {
				var data1 = {
					series: [{
						value: json.body.stats_confirm.confirmed,
						name: "confirmed users",
						className: "custom",
						meta: {
							color: mApp.getColor(clr[0])
						}
					}, {
						value: json.body.stats_confirm.unconfirmed,
						name: "unconfirmed users",
						className: "custom",
						meta: {
							color: mApp.getColor(clr[1])
						}
					}]
				};
				var data2 = {
					series: [{
						value: json.body.stats_complete.completed,
						name: "completed profiles",
						className: "custom",
						meta: {
							color: mApp.getColor(clr[0])
						}
					}, {
						value: json.body.stats_complete.uncompleted,
						name: "uncompleted profiles",
						className: "custom",
						meta: {
							color: mApp.getColor(clr[1])
						}
					}]
				};
				var data3 = {
					series: []
				}
				
				var ucnt = 0;
				var tu = json.body.stats_ownership.map(e => e.devices);
				if (tu.length>0) tu = tu.reduce(function(t,n) {return t+n});
				for (i=0;i<=json.body.stats_ownership.length;i++) {
					var item = i<json.body.stats_ownership.length?json.body.stats_ownership[i]:null;					
					var x = {
						value: item!=null?item.users:json.body.total_users-ucnt,
						name: item!=null?"users owned " + Math.round(item.devices/tu*100) + "% devices":"Users owned no device",
						className: "custom",
						meta: {
							color: mApp.getColor(clr[i])
						}
					}
					ucnt += item!=null?item.users:0;
					data3.series.push(x);
				}
				
				chart1.update(parseInt(json.body.total_users),data1);
				chart2.update(parseInt(json.body.total_users),data2);
				chart3.update(parseInt(json.body.total_users),data3);
				elem.find('.w-users-total span').html('<span>+</span>' + json.body.total_users);
				elem.find('.w-users-tab1-chtitle').text(json.body.total_users);
				elem.find('.w-users-tab2-chtitle').text(json.body.total_users);
				elem.find('.w-users-tab3-chtitle').text(json.body.total_users);
				animateCSS('#w-users .w-users-total', 'flash');
				animateCSS('#w-users .w-users-tab1-chtitle', 'flash');
			}
		});
	});
	
	elem.find('a[data-toggle="pill"]').on("shown.bs.tab", function(t) {
		switch ($(t.target).attr("href")) {
			case "#w-users-tab1":
				chart1.refresh();
				break;
			case "#w-users-tab2":
                chart2.refresh();
                break;
			case "#w-users-tab3":
                chart3.refresh();
                break;
		}
	});
	
	elem.find('.w-option:eq(4)').trigger('click');
}

var handleDevices = function() {
	var elem = $('#w-devices');
	var chart1 = new myPie();
	var chart2 = new myPie();
	var chart3 = new myPie();
	chart1.init('#m_chart_devices_tab_1');
	chart2.init('#m_chart_devices_tab_2');
	chart3.init('#m_chart_devices_tab_3');
	elem.find('.w-option').click(function(e) {
		e.preventDefault();
		var clrState = [{"name":"init","color":"accent"},{"name":"ready","color":"success"},{"name":"disconnected","color":"metal"},{"name":"sleeping","color":"brand"},{"name":"lost","color":"danger"},{"name":"alert","color":"warning"}];
		var d = parseInt($(this).attr('data-day'));
		elem.find('.w-options').text($(this).find('span').text());
		$.ajax({
			type: "POST",
			url: '/ajax-index?s=3',
			data: { "range": d },
			success: function(json, status, xhr) {
				var data1 = {
					series: []
				};
				var data2 = {
					series: []
				};
				var data3 = {
					series: []
				}
				
				for (i=0;i<json.body.stats_state.length;i++) {
					var item = json.body.stats_state[i];					
					var c = clrState.find(i => i.name==item.state);
					var x = {
						value: item.total,
						name: "in " + item.state + " state",
						className: "custom",
						meta: {
							color: mApp.getColor(c.color)
						}
					}
					data1.series.push(x);
				}
				
				var ucnt = 0;
				for (i=0;i<=json.body.stats_topmodel.length;i++) {
					var item = i<json.body.stats_topmodel.length?json.body.stats_topmodel[i]:null;					
					if (item===null && ucnt==json.body.total_devices) break;
					var x = {
						value: item!=null?item.total:json.body.total_devices-ucnt,
						name: item!=null?item.model:"the rest",
						className: "custom",
						meta: {
							color: mApp.getColor(clr[i])
						}
					}
					ucnt += item!=null?item.total:0;
					data2.series.push(x);
				}
				
				ucnt = 0;
				var tu = json.body.stats_ownership.map(e => e.users);
				if (tu.length>0) tu = tu.reduce(function(t,n) {return t+n});
				for (i=0;i<=json.body.stats_ownership.length;i++) {
					var item = i<json.body.stats_ownership.length?json.body.stats_ownership[i]:null;
					var x = {
						value: item!=null?item.devices:json.body.total_devices-ucnt,
						name: item!=null?"devices owned by " + Math.round(item.users/tu*100) + "% users":"devices still unclaimed",
						className: "custom",
						meta: {
							color: mApp.getColor(clr[i])
						}
					}
					ucnt += item!=null?item.devices:0;
					data3.series.push(x);
				}
				
				chart1.update(parseInt(json.body.total_devices),data1);
				chart2.update(parseInt(json.body.total_devices),data2);
				chart3.update(parseInt(json.body.total_devices),data3);
				elem.find('.w-devices-total span').html('<span>+</span>' + json.body.total_devices);
				elem.find('.w-devices-tab1-chtitle').text(json.body.total_devices);
				elem.find('.w-devices-tab2-chtitle').text(json.body.total_devices);
				elem.find('.w-devices-tab3-chtitle').text(json.body.total_devices);
				animateCSS('#w-devices .w-devices-total', 'flash');
				animateCSS('#w-devices .w-devices-tab1-chtitle', 'flash');
			}
		});
	});
	
	elem.find('a[data-toggle="pill"]').on("shown.bs.tab", function(t) {
		switch ($(t.target).attr("href")) {
			case "#w-devices-tab1":
				chart1.refresh();
				break;
			case "#w-devices-tab2":
                chart2.refresh();
                break;
			case "#w-devices-tab3":
                chart3.refresh();
                break;
		}
	});
	
	elem.find('.w-option:eq(4)').trigger('click');
}

var handleBlog = function() {
	var blog = 'https://blog.samelement.com';
	var elem = $('#w-blog');
	elem.find('.btn-blog').click(function() {
		window.open(blog,'_blank');
	});
	elem.find('.btn-more').click(function() {
		window.open($(this).attr('dest'), '_blank');
	});
	$.ajax({
		type: "GET",
		url: blog + '/wp-json/wp/v2/posts?_embed',
		data: { "page": 1, "per_page":1 },
		success: function(json, status, xhr) {
			if (json.length) {
				var p = json[0];
				var a = p.content.rendered;
				elem.find('h3').text(p.title.rendered);
				elem.find('.m-widget19__body').html(a.substr(0,a.indexOf('</p>')) + '</p>');
				elem.find('.btn-more').attr('dest',p.link);
				if (p._embedded["wp:featuredmedia"].length) {
					elem.find('.m-widget19__pic img').attr('src',p._embedded["wp:featuredmedia"][0].source_url);
					elem.find('.m-widget19__pic img').on('load',function() {
						$(this).hide().fadeIn();
					});
				}
				if (p._embedded.hasOwnProperty('author') && p._embedded.author.length) {
					elem.find('.m-widget19__username').text(p._embedded.author[0].name);
					elem.find('.m-widget19__img').attr('src',p._embedded.author[0].avatar_urls["48"]);
					elem.find('.m-widget19__time').text(p._embedded.author[0].description);
				}
				if (p._embedded.hasOwnProperty('replies') && p._embedded.replies.length) {
					elem.find('.m-widget19__number').text(p._embedded.replies[0].length);
				}
			}
		}
	});
}

jQuery(document).ready(function() {
    devMsgs.init('all,dashboard');
	handleGrowth();
	handleUsers();
	handleDevices();
	handleBlog();
});