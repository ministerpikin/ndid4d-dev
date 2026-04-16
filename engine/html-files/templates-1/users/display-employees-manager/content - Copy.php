<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
	$gbranch = isset( $more_data[ 'branch' ] )?$more_data[ 'branch' ]:'';
	$g_search_params = '';
	$g_search_params2 = '';
	
	if( $gbranch ){
		$store = $gbranch;
		$g_search_params2 = '&branch='.$gbranch;
		$g_search_params .= '&parish='.$gbranch;
	}
?>
<div class="row" >
	<div class="col-md-12"> 
		<form class="activate-ajax" method="post" id="employee" action="?action=users&todo=search_employee">
			<div class="row">
				<div class="col-md-4">
					<input class="form-control select2" action="?module=&action=users&todo=get_users_select2<?php echo $g_search_params; ?>" placeholder="Select Employee" name="employee" minlength="0" />
				</div>
				<div class="col-md-8">
					<button class="btn btn-lg1 btn-block1 dark" type="submit">GO</button>
					<?php if( ! $minimal ){ ?>
					<a class="btn default btn-block1 custom-single-selected-record-button" override-selected-record="1" action="?module=&action=users&todo=new_popup_form" title="Add New Employee">New Employee</a>
					<?php } ?>
					<div class="btn-group">
						<a type="button"  class="btn default dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
						View Reports <i class="icon-angle-down"></i>
						</a><div class="dropdown-backdrop"></div>
						 
						<ul class="dropdown-menu" role="menu">
							
							<li>
							<a href="#" class="custom-single-selected-record-button" override-selected-record="upcoming_birthdays" action="?module=&action=users&todo=view_quick_report" title="List of Upcoming Birthdays">Upcoming Birthdays &nbsp;&nbsp;</a>
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

<div class="tabbable tabs-left" id="employee-tabs">
	<ul class="nav nav-tabs" id="employee-tabs-link">
		<li class="active"><a href="#employee-summary" class="custom-single-selected-record-button populate-with-selected" action="?module=&action=users&todo=view_employee_profile" data-toggle="tab">Employee Profile</a></li>
		<!--
		<li><a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button populate-with-selected" action="?module=&action=users&todo=manage_salary_history">Salary History</a></li>
		
		<li><a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button populate-with-selected" action="?module=&action=users&todo=manage_payslips_history">Pay Slips</a></li>
		-->
		<?php if( ! $minimal ){ ?>
		<li><a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button populate-with-selected" action="?module=&action=users&todo=manage_salary_details">Salary Details</a></li>
		<?php } ?>
		
		<li><a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button populate-with-selected" action="?module=&action=users&todo=manage_work_history">Current Work History</a></li>
		
		<?php if( ! $minimal ){ ?>
		<li><a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button populate-with-selected" action="?module=&action=users&todo=manage_disciplinary_history">Disciplinary History</a></li>
		<?php } ?>
		
		<?php if( class_exists("cLeave_requests") ){ ?>
		<li><a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button populate-with-selected" action="?module=&action=users&todo=manage_leave_history">Leave History</a></li>
		<?php } ?>
		
		<li><a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button populate-with-selected" action="?module=&action=users&todo=manage_performance_appraisal">Performance Appraisal History</a></li>
		
		<li><a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button populate-with-selected" action="?module=&action=users&todo=manage_next_of_kin" >Next of Kin</a></li>
		
		<li><a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button populate-with-selected" action="?module=&action=users&todo=manage_dependents">Dependents</a></li>
		
	   <li><a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button populate-with-selected" action="?module=&action=users&todo=manage_educational_history" >Educational History</a></li>
	   
	   <li><a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button populate-with-selected" action="?module=&action=users&todo=manage_professional_association" >Professional Association</a></li>
	   
	   <li><a href="#employee_bill_client_history" data-toggle="tab" class="custom-single-selected-record-button populate-with-selected" action="?module=&action=users&todo=manage_work_experience">Work Experience</a></li>
	   
	</ul>
	<div class="tab-content " >
		
		<div class="tab-pane active" >
			<div classX="row">
				<div classX="col-md-12">
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
		
	</div>
</div>

</div>