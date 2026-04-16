<style type="text/css">
html, body{
	background:#777;
}
#invoice-container{
	margin:auto;
	margin-top:20px;
	margin-bottom:20px;
}
#invoice-container .invoice{
	max-width:700px;
	background:#fff;
	margin:auto;
	border:1px solid #ddd;
	padding:10px 30px;
}
<style type="text/css">
	@media print{
		.hidden-print{
			display:none;
		}
	}
</style>
<button onclick="window.close();" class="hidden-print" style="font-size:20px; ">Go Back</button>
<?php
	if( isset( $data["html"] ) )echo $data["html"];
	//include dirname( dirname( __FILE__ ) ) . "/globals/invoice.php"; 
?>