<head>
	<meta charset="utf-8" />
	<title><?php echo HEAD_TITLE ?></title>
	<meta name="description" content="<?php echo HEAD_DESCRIPTION ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
			active: function() {
				sessionStorage.fonts = true;
			}	
		});
    </script>
	<link rel="shortcut icon" href="templates/default/assets/images/favicon.png" />

	<!--begin:: Global Mandatory Vendors -->
	<link href="vendor/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" type="text/css" />
	<!--end:: Global Mandatory Vendors -->

	<!--begin:: Global Optional Vendors -->
	<link href="vendor/tether/dist/css/tether.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/bootstrap-datetime-picker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
	<link href="vendor/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/nouislider/distribute/nouislider.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/owl.carousel/dist/assets/owl.carousel.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/owl.carousel/dist/assets/owl.theme.default.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet" type="text/css" />
	<link href="vendor/ion-rangeslider/css/ion.rangeSlider.skinFlat.css" rel="stylesheet" type="text/css" />
	<link href="vendor/dropzone/dist/min/dropzone.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/summernote/dist/summernote.css" rel="stylesheet" type="text/css" />
	<link href="vendor/bootstrap-markdown/css/bootstrap-markdown.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/animate.css/animate.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/toastr/build/toastr.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/morris.js/morris.css" rel="stylesheet" type="text/css" />
	<link href="vendor/chartist/dist/chartist.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/socicon/css/socicon.css" rel="stylesheet" type="text/css" />
	<link href="vendor/line-awesome/css/line-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="vendor/flaticon/css/flaticon.css" rel="stylesheet" type="text/css" />
	<link href="vendor/metronic/css/styles.css" rel="stylesheet" type="text/css" />
	<link href="vendor/fontawesome5/css/all.min.css" rel="stylesheet" type="text/css" />
	<!--end:: Global Optional Vendors -->

	<!--begin::Global Theme Styles -->
	<link href="templates/default/assets/view/style.bundle.min.css" rel="stylesheet" type="text/css" />
	<!--RTL version:<link href="templates/default/assets/view/style.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
	<!--end::Global Theme Styles -->

	<!--begin::Page Vendors Styles -->
	<link href="templates/default/assets/vendor/custom/fullcalendar/fullcalendar.bundle.min.css" rel="stylesheet" type="text/css" />
	<!--RTL version:<link href="templates/default/assets/vendor/custom/fullcalendar/fullcalendar.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
	<link href="templates/default/assets/vendor/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
	<!--RTL version:<link href=templates/default/assets/vendor/custom/datatables/datatables.bundle.rtl.css" rel="stylesheet" type="text/css" />-->	
	<!--end::Page Vendors Styles -->
	<link href="templates/default/assets/view/custom.css" rel="stylesheet" type="text/css" />
	<?php 
		$defNav = isset($_COOKIE["sidebar_toggle_state"])?($_COOKIE["sidebar_toggle_state"]=="on"?"m-brand--minimize m-aside-left--minimize":""):"on";
	?>
</head>