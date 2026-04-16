<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<div class="row">
<div class="col-md-10 col-md-offset-1">

<div class="portlet grey box">
<div class="portlet-title">
	<div class="caption"><small><?php if( isset( $data["title"] ) )echo $data["title"]; ?></small></div>
</div>
<div class="portlet-body" style="padding-bottom:50px;" >

<div class="row" >
	
	<div class="col-md-3">
		<h4><strong>New Leave Roster</strong></h4>
		<hr />
		<div class="note note-warning">
			<p>
			   <big>Create a new annual leave roster</big>
			</p>
			<a href="#" class="btn dark btn-block custom-action-button-old" module-name="HR" month-id="-" budget-id="" function-id="1" function-class="leave_roaster" function-name="display_new_leave_roaster"  title="New Leave Roster">
				<i class="icon-file"></i> New Leave Roster
			</a>
		 </div>
	</div>

	<div class="col-md-3">
		<h4><strong>Manage Leave Roster</strong></h4>
		<hr />
		<div class="note note-warning">
			<p>
			   <big>Modify or remove existing leave roster</big>
			</p>
			<a href="#" class="btn dark btn-block custom-action-button-old" module-name="HR" month-id="-" budget-id="" function-id="1" function-class="leave_roaster" function-name="display_all_records_full_view"  title="Manage Leave Roster">
				<i class="icon-gear"></i> Manage Leave Roster
			</a>
		 </div>
	</div>

	<div class="col-md-3">
		<h4><strong>New Leave Request</strong></h4>
		<hr />
		<div class="note note-warning">
			<p>
			   <big>Apply for leave of absence by filling this form</big>
			</p>
			<a href="#" class="btn dark btn-block custom-action-button-old" module-name="HR" month-id="-" budget-id="" function-id="1" function-class="leave_requests" function-name="new_leave_request_form"  title="New Leave Request">
				<i class="icon-file"></i> New Leave Request
			</a>
		 </div>
	</div>

	<div class="col-md-3">
		<h4><strong>Manage Leave Requests</strong></h4>
		<hr />
		<div class="note note-warning">
			<p>
			   <big>Add, approve, modify or remove leave requests</big>
			</p>
			<a href="#" class="btn dark btn-block custom-action-button-old" module-name="HR" month-id="-" budget-id="" function-id="1" function-class="leave_requests" function-name="display_all_records_full_view"  title="Manage Leave Requests">
				<i class="icon-gear"></i> Manage Leave Requests
			</a>
		 </div>
	</div>

	<div class="col-md-3">
		<h4><strong>Manage Leave Types</strong></h4>
		<hr />
		<div class="note note-warning">
			<p>
			   <big>Add, modify or remove types of leave</big>
			</p>
			<a href="#" class="btn dark btn-block custom-action-button-old" module-name="HR" month-id="-" budget-id="" function-id="1" function-class="leave_types" function-name="display_all_records_full_view"  title="Manage Leave Types">
				<i class="icon-gear"></i> Manage Leave Types
			</a>
		 </div>
	</div>

</div>



</div>
</div>
</div>
</div>

</div>