<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	$gbranch = isset( $more_data[ 'branch' ] )?$more_data[ 'branch' ]:'';
	$g_search_params = '';
	$g_search_params2 = '';
	
	$cu_attr = '';
	$cu = get_current_customer( "current_employee" );
	if( $cu ){
		$cu_attr = ' value="'. $cu .'" label="' . get_name_of_referenced_record( array( "id" => $cu, "table" => "users_employee_profile" ) ) . '" ';
	}
	
	if( $gbranch ){
		$store = $gbranch;
		$g_search_params2 = '&branch='.$gbranch;
		$g_search_params .= '&parish='.$gbranch;
	}
?>
<div class="row" >
	<div class="col-md-12"> 
		<form class="activate-ajax" method="post" id="employee" action="?action=users_employee_profile&todo=search_employee">
			<div class="row">
				<div class="col-md-4">
					<input class="form-control select2" action="?module=&action=users_employee_profile&todo=get_select2<?php echo $g_search_params; ?>" placeholder="Select Employee" name="employee" minlength="0" <?php echo $cu_attr; ?> />
				</div>
				<div class="col-md-8">
					<button class="btn btn-lg1 btn-block1 dark" type="submit">GO</button>
					<?php if( ! $minimal ){ ?>
					<a class="btn default btn-block1 custom-single-selected-record-button" override-selected-record="1" action="?module=&action=users_employee_profile&todo=new_personnel_popup<?php echo $g_search_params2; ?>" title="Add New Employee">New Employee</a>
					<?php } ?>
					<div class="btn-group">
						<a type="button"  class="btn default dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
						View Reports <i class="icon-angle-down"></i>
						</a><div class="dropdown-backdrop"></div>
						 
						<ul class="dropdown-menu" role="menu">
							
							<li>
							<a href="#" class="custom-single-selected-record-button" override-selected-record="upcoming_birthdays" action="?module=&action=users_employee_profile&todo=view_quick_report" title="List of Upcoming Birthdays">Upcoming Birthdays &nbsp;&nbsp;</a>
							</li>
							<!--
							<li class="divider"></li>
							
							<li>
							<a href="#" class="custom-single-selected-record-button" override-selected-record="contact_list" action="?module=&action=priest&todo=view_quick_report" title="Contact List">Contact List &nbsp;&nbsp;</a>
							</li>
							-->
							
						</ul>
					</div>
				 </div>
			</div>
		</form>
		<hr />

	</div>
</div>

<div class="row">
	<div class="col-md-3">
		
		<div class="list-group" id="employee-tabs-link">
			<a href="#employee-summary" class="bg-green custom-single-selected-record-button list-group-item populate-with-selected" action="?module=&action=users_employee_profile&todo=view_employee_profile" data-toggle="tab">Employee Profile</a>
			<!--
			<a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button list-group-item populate-with-selected" action="?module=&action=users_employee_profile&todo=manage_salary_history">Salary History</a>
			
			<a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button list-group-item populate-with-selected" action="?module=&action=users_employee_profile&todo=manage_payslips_history">Pay Slips</a>
			-->
			<?php if( ! $minimal ){ ?>
			<a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button list-group-item populate-with-selected" action="?module=&action=users_employee_profile&todo=manage_salary_details">Salary Details</a>
			<?php } ?>
			
			<a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button list-group-item populate-with-selected" action="?module=&action=users_employee_profile&todo=manage_work_history">Current Work History</a>
			
			<?php if( ! $minimal ){ ?>
			<a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button list-group-item populate-with-selected" action="?module=&action=users_employee_profile&todo=manage_disciplinary_history">Disciplinary History</a>
			<?php } ?>
			
			<?php if( class_exists("cLeave_requests") ){ ?>
			<a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button list-group-item populate-with-selected" action="?module=&action=users_employee_profile&todo=manage_leave_history">Leave History</a>
			<?php } ?>
			
			<?php if( class_exists("cNwp_human_resource") ){ ?>
			<a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button list-group-item populate-with-selected" action="?module=&action=nwp_human_resource&todo=execute&nwp_action=users_bonus&nwp_todo=manage_users_bonus">Bonus</a>
			<?php } ?>
			
			<a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button list-group-item populate-with-selected" action="?module=&action=users_employee_profile&todo=manage_performance_appraisal">Performance Appraisal History</a>
			
			<a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button list-group-item populate-with-selected" action="?module=&action=users_employee_profile&todo=manage_next_of_kin" >Next of Kin</a>
			
			<a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button list-group-item populate-with-selected" action="?module=&action=users_employee_profile&todo=manage_dependents">Dependents</a>
			
		   <a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button list-group-item populate-with-selected" action="?module=&action=users_employee_profile&todo=manage_educational_history" >Educational History</a>
		   
		   <a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button list-group-item populate-with-selected" action="?module=&action=users_employee_profile&todo=manage_professional_association" >Professional Association</a>
		   
		   <a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button list-group-item populate-with-selected" action="?module=&action=users_employee_profile&todo=manage_work_experience">Work Experience</a>
		   
		   <a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button list-group-item populate-with-selected" action="?module=&action=users_employee_profile&todo=manage_attendance">Attendance</a>
		   
		   <a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button list-group-item populate-with-selected" action="?module=&action=users_employee_profile&todo=manage_expense">Expense</a>
		   
		 </div>
		 
	</div>
	<div class="col-md-9">
		<div id="page-sub-content" styleX="max-height:380px; overflow-y:auto;">
			
			<div class="alert alert-danger">
				<h4><i class="icon-bell"></i> No Employee Selected</h4>
				<p>
					<strong>Please Select an Employee</strong>
				</p>
			</div>

		</div>
	</div>
</div>

</div>