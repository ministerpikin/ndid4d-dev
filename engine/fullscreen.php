<?php
	$pagepointer = './';
	require_once $pagepointer."settings/Config.php";
	require_once $pagepointer."settings/Setup.php";
	
?>
<html>
<head>
	<script src="js/handsontable/handsontable.full.min.js"></script>
	<link rel="stylesheet" media="screen" href="js/handsontable/handsontable.full.min.css">
</head>
<body>
<?php
	if( isset( $_POST["data"] ) && $_POST["data"] ){
		
	}else{
		?>
		<h1>Invalid Data</h1>
		<p>Please try again</p>
		<?php
	}
?>
<div id="report-fullscreen"></div>
</body>
<?php
	if( isset( $data ) && $data ){
?>
<script type="text/javascript">
	var data = [
	  ["", "Ford", "Tesla", "Toyota", "Honda"],
	  ["2017", 10, 11, 12, 13],
	  ["2018", 20, 11, 14, 13],
	  ["2019", 30, 15, 12, 13]
	];

	var container = document.getElementById('report-fullscreen');
	var hot = new Handsontable(container, {
	  data: data,
	  rowHeaders: true,
	  colHeaders: true,
	  filters: true,
	  dropdownMenu: true
	});
</script>
<?php } ?>
</html>