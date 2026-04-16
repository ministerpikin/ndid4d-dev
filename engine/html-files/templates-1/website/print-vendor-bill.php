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
	max-width:1000px;
	background:#fff;
	margin:auto;
	padding:8px;
}

@media print{
	.hidden-print{
		display:none;
	}
}
</style>
<button onclick="window.close();" class="hidden-print" style="font-size:20px; ">Go Back</button>
<div id="modal-replacement-handle" class="modal-print-box">
<?php
	if( isset( $data["html"] ) )echo $data["html"];
	//include dirname( dirname( __FILE__ ) ) . "/globals/invoice.php"; 
?>
</div>