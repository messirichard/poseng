<html lang="<?php echo LANG ?>">
	<?php require_once('_head.php'); ?>
	<!-- begin::Body -->
	<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--fixed m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default <?php echo $defNav ?>">
		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<div class="m-grid__item m-grid__item--fluid m-grid  m-error-3" style="background-image: url(templates/default/assets/images/error/<?php echo $error_bg ?>);">
				<div class="m-error_container">
					<span class="m-error_number">
						<h1><?php echo $error_title ?></h1>
					</span>
					<p class="m-error_title m--font-light"><?php echo $error_p1 ?></p>
					<p class="m-error_subtitle"><?php echo $error_p2 ?></p>
					<p class="m-error_description"><?php echo $error_p3 ?></p>
				</div>
			</div>
		</div>
		<!-- end:: Page -->
		<?php require_once('_foot.php'); ?>
	</body>
	<!-- end::Body -->
</html>