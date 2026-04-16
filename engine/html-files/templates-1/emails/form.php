<style type="text/css">
    .text-more{
        font-size:0.9em;
        font-style:italic;
    }
	table.small-size td{
		font-size:0.8em;
	}
	table.small-size th{
		font-size:0.9em;
	}
</style>
	<h4>Select Date to View Out-going Emails</h4>
	<div id="form-panel-wrapper">
	<form method="post" action="?action=emails&todo=emails_log" id="emails-report-form" class="activate-ajax">
		
		<div class="input-group">
			 <input type="date" name="date" required="required" placeholder="YYYY-MM-DD" value="<?php if( isset( $data['date'] ) && $data['date'] ) echo $data["date"]; ?>" class="form-control">
			 <span class="input-group-btn">
			 <input type="submit" class="btn green" value="Go!" />
			 </span>
		  </div>
	</form>
	</div>