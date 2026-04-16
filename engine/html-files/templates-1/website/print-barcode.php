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
?>