<!-- Begin page -->
<div id="layout-wrapper">

<?php 
$no_side_bar = isset( $data[ 'no_side_bar' ] ) ? $data[ 'no_side_bar' ] : '';
	// echo '<pre>';print_r( $no_side_bar );echo '</pre>'; 

if( ! $no_side_bar ){
	include "main-header.php";
	include "main-menu.php"; 
}


if( ! $no_side_bar ){
?>
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>

	<!-- ============================================================== -->
	<!-- Start right Content here -->
	<!-- ============================================================== -->
	<div class="main-content">
	
		<div class="page-content">
			<div class="container-fluid"  id="<?php echo $container; ?>">
<?php } ?>
				
				<?php //include "main-default-content.php"; ?>
				<?php include "main-default-db-content.php"; ?>
				
<?php if( ! $no_side_bar ){ ?>
			</div>
			<!-- container-fluid -->
		</div>
		<!-- End Page-content -->

		<footer class="footer">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-6">
						<?php echo get_app_version( $pagepointer ); ?>
					</div>
					<div class="col-sm-6">
						<div class="text-sm-end d-none d-sm-block">
							<?php echo $project["company_name"]; ?>
						</div>
					</div>
				</div>
			</div>
		</footer>
	</div>
	<!-- end main content-->

</div>
<!-- END layout-wrapper -->



<!--start back-to-top-->
<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
	<i class="ri-arrow-up-line"></i>
</button>
<!--end back-to-top-->

<!--preloader-->
<div id="preloader">
	<div id="status">
		<div class="spinner-border text-primary avatar-sm" role="status">
			<span class="visually-hidden">Loading...</span>
		</div>
	</div>
</div>
<?php } ?>
