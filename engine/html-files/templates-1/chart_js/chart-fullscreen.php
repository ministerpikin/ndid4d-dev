<!doctype html>
<html lang="en">
<?php
$pr = get_project_data();
$domain = isset( $pr[ 'domain_name' ] ) ? $pr[ 'domain_name' ] : '';
// print_r( $domain );
?>
<head>
    <title><?php echo isset( $data[ 'title' ] ) ? $data[ 'title' ] : ''; ?></title>
	<link rel="stylesheet" href="<?php echo $domain . '../sign-in/' ?>assets/app/chart.css">
</head>
<style type="text/css">
	.btn-actions-pane-right.pull-right{
		display: none;
	}
	.menu-header-title {
		font-weight: 800 !important;
		font-size: 1.55rem;
		margin: 0;
		position: absolute;
		right: 1em;
		top: 2em;
	}
	.text-primary {
	    color: #3f6ad8 !important;
	}
</style>
<body>
	<?php 
	if( isset( $data[ 'html' ] ) && $data[ 'html' ] )echo $data[ 'html' ];
	?>
   <script src="<?php echo $domain . '../sign-in/' ?>assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
    <script src="<?php echo $domain . '../sign-in/' ?>assets/app/custom.chart.js" type="text/javascript"></script>

	<script type="text/javascript" src="<?php echo $domain . '../sign-in/' ?>assets/app/chart.js"></script>
</body>
</html>
