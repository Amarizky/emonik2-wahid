<!DOCTYPE html>
<html lang="en">

<head>
	<?php echo $head_tags; ?>
</head>

<body class="">
	<?php echo $navbar; ?>
	<!-- Page content -->
	<div class="page-content">

		<!-- Main sidebar -->
		<?php echo $sidebar; ?>
		<!-- /main sidebar -->

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<?php echo $contents; ?>
			<!-- /content area -->

			<!-- Footer -->
			<div class="navbar navbar-expand-lg navbar-light">
				<span class="navbar-text">
					&copy; <?php echo date('Y'); ?>. <a href="https://www.pupuk-kujang.co.id/" target="_blank">All right reserved</a>
				</span>
			</div>
			<!-- /footer -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->
	<script>
		$(function() {
			<?php $CI = &get_instance(); ?>
			var POs = [<?= '"' . implode('", "', getPOs()) . '"'; ?>]
			var csrfName = '<?= $CI->security->get_csrf_token_name(); ?>'
			var csrfHash = '<?= $CI->security->get_csrf_hash(); ?>'
			setInterval(() => {
				$.ajax({
					type: "post",
					url: "<?= base_url('admin/Dashboard/checkPO'); ?>",
					dataType: "json",
					data: {
						[csrfName]: csrfHash,
						pos: POs
					},
					success: function(data) {
						csrfName = data.csrfName;
						csrfHash = data.csrfHash;
						if (data.result == false) {
							alert("Ada PO baru dikonfirmasi")
							location.reload();
						};
					}
				});
			}, 2000);
		});
	</script>
	<?php echo $footer_tags; ?>
</body>

</html>