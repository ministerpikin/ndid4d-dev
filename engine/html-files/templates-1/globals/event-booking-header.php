<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<button type="button" title="Select Another Date" class="custom-action-button btn pull-right" function-id="1" function-class="events" function-name="display_calendar_view_mobile" operator-id="-" department-id="-" budget-id="1457043783" month-id="monthly"><i class="icon-calendar"></i> Select Another Date</button>
<h3 style="margin-top:0;">
	<?php 
	if( $date ){
		echo date("D jS M, Y", $date );
	}
	?>
</h3>

<hr />
<ul class="breadcrumb" style="">
	<li style=""><a href=""><i class="icon-check"></i> Select Date</a></li>
	<li class="active">Select Booking Type</li>
	<li >Provide Contact Info.</li>
	<li >Print Invoice</li>
</ul>
<hr />
</div>