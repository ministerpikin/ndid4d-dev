<?php  
//received from steve 06-oct-23

$pd = get_project_data();
$full_name = isset( $user_info[ 'user_full_name' ] ) ? $user_info[ 'user_full_name' ] : '';
$access = get_accessed_functions();
$super = !is_array($access) && $access == 1 ? 1 : 0;

//echo '<pre>'; print_r( $user_info ); echo '</pre>';
// echo '<pre>'; print_r( $this->class_settings ); echo '</pre>';
?><!-- start page title -->
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-sm-flex align-items-center justify-content-between">
			<h4 class="mb-sm-0" style="text-transform: inherit;">Welcome <span class="text-primary"><?php echo $full_name; ?>!</span></h4>

		</div>
	</div>
</div>
<!-- end page title -->

<div class="row">

	<div class="col-xxl-6">
		<div class="d-flex flex-column h-100">

			<div class="row">
				<?php
					if( $super || (isset($access['accessible_functions'][ 'indicator_dashboard' ]) && $access['accessible_functions'][ 'indicator_dashboard' ]) ){ ?>
						<div class="col-md-6">
							<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=indicator_dashboard" class="custom-single-selected-record-button" override-selected-record="<?php echo '-'; ?>" title="Show Project Development Indicators Dashboard" style="margin: 0 15px 0;">
								<div class="card card-animate bg-soft-success success ">
									<div class="card-body">
										<div class="d-flex justify-content-between">
											<div>
												<h2 class="mt-4X ff-secondary fw-semibold text-black">Project Development Indicators</h2>
											</div>
											<div>
												<div class="avatar-sm flex-shrink-0">
													<span class="avatar-title bg-soft-success text-black rounded-circle fs-2">
														<i class="mdi mdi-account-box-multiple"></i>
													</span>
												</div>
											</div>
										</div>
									</div><!-- end card body -->
								</div> <!-- end card-->
							</a>
						</div> <!-- end col-->
					<?php }
				?>

				<?php
					if( $super || (isset($access['accessible_functions'][ 'report_dashboard2' ]) && $access['accessible_functions'][ 'report_dashboard2' ]) ){ ?>
						<div class="col-md-6">
							<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=environmental_and_social_safeguard_indicators" class="custom-single-selected-record-button" override-selected-record="<?php echo '-'; ?>" title="Show Environmental And Social Safeguard Indicators Dashboard" style="margin: 0 15px 0;">
								<div class="card card-animate bg-primary ">
									<div class="card-body">
										<div class="d-flex justify-content-between">
											<div>
												<h2 class="mt-4X ff-secondary fw-semibold text-white">Environmental & Social Safeguards</h2>
											</div>
											<div>
												<div class="avatar-sm flex-shrink-0">
													<span class="avatar-title bg-primary rounded-circle fs-2">
														<i data-feather="users"></i>
													</span>
												</div>
											</div>
										</div>
									</div><!-- end card body -->
								</div> <!-- end card-->
							</a>
						</div> <!-- end col-->
					<?php }
				?>
				
				<?php
					if( $super || (isset($access['accessible_functions'][ 'report_dashboard3' ]) && $access['accessible_functions'][ 'report_dashboard3' ]) ){ ?>
						<div class="col-md-6">
							<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=grm_dashboard" class="custom-single-selected-record-button" override-selected-record="<?php echo '-'; ?>" title="Show Grievance Redress Mechanism (GRM) Indicators Dashboard" style="margin: 0 15px 0;">
								<div class="card card-animate bg-danger ">
									<div class="card-body">
										<div class="d-flex justify-content-between">
											<div>
												<h2 class="mt-4X ff-secondary fw-semibold text-white">Grievance Redress Indicators</h2>
											</div>
											<div>
												<div class="avatar-sm flex-shrink-0">
													<span class="avatar-title bg-danger rounded-circle fs-2">
														<i data-feather="activity"></i>
													</span>
												</div>
											</div>
										</div>
									</div><!-- end card body -->
								</div> <!-- end card-->
							</a>
						</div> <!-- end col-->
					<?php } 
				?>


			<!-- </div> <!-- end row-->

			<!-- <div class="row"> -->
				<?php
					if( $super || (isset($access['accessible_functions'][ 'communication_dashboard' ]) && $access['accessible_functions'][ 'communication_dashboard' ]) ){ ?>
						<div class="col-md-6">
							<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=comm_dashboard" class="custom-single-selected-record-button" override-selected-record="<?php echo '-'; ?>" title="Show Enrolee Satisfaction Indicators Dashboard" style="margin: 0 15px 0;">
								<div class="card card-animate bg-warning ">
									<div class="card-body">
										<div class="d-flex justify-content-between">
											<div>
												<h2 class="mt-4X ff-secondary fw-semibold text-white">Enrolee Satisfaction Indicators</h2>
											</div>
											<div>
												<div class="avatar-sm flex-shrink-0">
													<span class="avatar-title bg-warning rounded-circle fs-2">
														<i data-feather="clock"></i>
													</span>
												</div>
											</div>
										</div>
									</div><!-- end card body -->
								</div> <!-- end card-->
							</a>
						</div> <!-- end col-->						
					<?php }
				?>

				<?php
					if( $super || (isset($access['accessible_functions'][ 'comm_i_dashboard' ]) && $access['accessible_functions'][ 'comm_i_dashboard' ]) ){ ?>
						<div class="col-md-6">
							<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=comm_i_dashboard" class="custom-single-selected-record-button" override-selected-record="<?php echo '-'; ?>" title="Show Internal / External Communications Dashboard" style="margin: 0 15px 0;">
								<div class="card card-animate bg-vertical-gradient-4 ">
									<div class="card-body">
										<div class="d-flex justify-content-between">
											<div>
												<h2 class="mt-4X ff-secondary fw-semibold text-white">Internal and External Communications</h2>
											</div>
											<div>
												<div class="avatar-sm flex-shrink-0">
													<span class="avatar-title bg-transparent rounded-circle fs-2">
														<i data-feather="archive"></i>
													</span>
												</div>
											</div>
										</div>
									</div><!-- end card body -->
								</div> <!-- end card-->
							</a>
						</div> <!-- end col-->
					<?php }
				?>

				<?php
					if( $super || (isset($access['accessible_functions'][ 'social_dashboard' ]) && $access['accessible_functions'][ 'social_dashboard' ]) ){ ?>
						<div class="col-md-6">
							<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=social_dashboard" class="custom-single-selected-record-button" override-selected-record="<?php echo '-'; ?>" title="Report social accountability enrolment related indicators Dashboard" style="margin: 0 15px 0;">
								<div class="card card-animate bg-success ">
									<div class="card-body">
										<div class="d-flex justify-content-between">
											<div>
												<h2 class="mt-4X ff-secondary fw-semibold text-white">Social Accountability Indicators</h2>
											</div>
											<div>
												<div class="avatar-sm flex-shrink-0">
													<span class="avatar-title bg-success rounded-circle fs-2">
														<i data-feather="external-link"></i>
													</span>
												</div>
											</div>
										</div>
									</div><!-- end card body -->
								</div> <!-- end card-->
							</a>
						</div> <!-- end col-->
					<?php }
				?>

				<?php
					if( $super || (isset($access['accessible_functions'][ 'suspicious_dashboard' ]) && $access['accessible_functions'][ 'suspicious_dashboard' ]) ){ ?>
						<div class="col-md-6">
							<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=suspicious_dashboard" class="custom-single-selected-record-button" override-selected-record="<?php echo '-'; ?>" title="Show Traceability Of Suspicious Enrolment Activity Indicators Dashboard" style="margin: 0 15px 0;">
								<div class="card card-animate bg-secondary ">
									<div class="card-body">
										<div class="d-flex justify-content-between">
											<div>
												<h2 class="mt-4X ff-secondary fw-semibold text-white">Suspicious Enrolment Activity</h2>
											</div>
											<div>
												<div class="avatar-sm flex-shrink-0">
													<span class="avatar-title bg-secondary rounded-circle fs-2">
														<i data-feather="user"></i>
													</span>
												</div>
											</div>
										</div>
									</div><!-- end card body -->
								</div> <!-- end card-->
							</a>
						</div> <!-- end col-->
					<?php }
				?>


			<!-- </div> <!-- end row--> 

			<!-- <div class="row"> -->
				<?php
					if( $super || (isset($access['accessible_functions'][ 'financial_dashboard' ]) && $access['accessible_functions'][ 'financial_dashboard' ]) ){ ?>
						<div class="col-md-6">
							<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=financial_dashboard" class="custom-single-selected-record-button" override-selected-record="<?php echo '-'; ?>" title="Financial Indicators On Enrolment Services Dashboard" style="margin: 0 15px 0;">
								<div class="card card-animate bg-info ">
									<div class="card-body">
										<div class="d-flex justify-content-between">
											<div>
												<h2 class="mt-4X ff-secondary fw-semibold text-white">Financial Indicators on Enrolments</h2>
											</div>
											<div>
												<div class="avatar-sm flex-shrink-0">
													<span class="avatar-title bg-info rounded-circle fs-2">
														<i data-feather="external-link"></i>
													</span>
												</div>
											</div>
										</div>
									</div><!-- end card body -->
								</div> <!-- end card-->
							</a>
						</div> <!-- end col-->
					<?php }
				?>

				<?php
					if( $super || (isset($access['accessible_functions'][ 'indicator_dashboard' ]) && $access['accessible_functions'][ 'indicator_dashboard' ]) ){ ?>
							<div class="col-md-6">
								<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=project_dashboard" class="custom-single-selected-record-button" override-selected-record="<?php echo '-'; ?>" title="Show Traceability Of Suspicious Enrolment Activity Indicators Dashboard" style="margin: 0 15px 0;">
									<div class="card card-animate bg-black ">
										<div class="card-body">
											<div class="d-flex justify-content-between">
												<div>
													<h2 class="mt-4X ff-secondary fw-semibold text-white">Project Development ( NIDB )</h2>
												</div>
												<div>
													<div class="avatar-sm flex-shrink-0">
														<span class="avatar-title bg-black rounded-circle fs-2">
															<i data-feather="activity"></i>
														</span>
													</div>
												</div>
											</div>
										</div><!-- end card body -->
									</div> <!-- end card-->
								</a>
							</div> <!-- end col-->
					<?php }
				?>

			</div> <!-- end row--> 

		</div>
	</div> <!-- end col-->

	<div class="col-xxl-6">
		
		<div class="d-flex flex-column h-100">

			<div class="row">
				<?php
					if( $super || (isset($access['accessible_functions'][ 'device_mgt' ]) && $access['accessible_functions'][ 'device_mgt' ]) ){ ?>
						<div class="col-md-6">
							<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=device_dashboard" class="custom-single-selected-record-button" override-selected-record="<?php echo '-'; ?>" title="Show Traceability Of Enrolment Device Activities Dashboard" style="margin: 0 15px 0;">
								<div class="card card-animate bg-secondary ">
									<div class="card-body">
										<div class="d-flex justify-content-between">
											<div>
												<h2 class="mt-4X ff-secondary fw-semibold text-white"> Approved - Enrolment Device Management</h2>
											</div>
											<div>
												<div class="avatar-sm flex-shrink-0">
													<span class="avatar-title bg-secondary rounded-circle fs-2">
														<i data-featherx="user" class="mdi mdi-animation-outline"></i>
													</span>
												</div>
											</div>
										</div>
									</div><!-- end card body -->
								</div> <!-- end card-->
							</a>
						</div> <!-- end col-->
					<?php }
				?>

				<?php
					if( $super || (isset($access['accessible_functions'][ 'device_mgt2' ]) && $access['accessible_functions'][ 'device_mgt2' ]) ){ ?>
						<div class="col-md-6">
							<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=device_dashboard2" class="custom-single-selected-record-button" override-selected-record="<?php echo '-'; ?>" title="Show Traceability Of Pending Enrolment Device Activities Dashboard" style="margin: 0 15px 0;">
								<div class="card card-animate bg-success ">
									<div class="card-body">
										<div class="d-flex justify-content-between">
											<div>
												<h2 class="mt-4X ff-secondary fw-semibold text-white">Pending Enrolment Device Management</h2>
											</div>
											<div>
												<div class="avatar-sm flex-shrink-0">
													<span class="avatar-title bg-warning rounded-circle fs-2">
														<i data-featherx="user" class="mdi-access-point-off mdi"></i>
													</span>
												</div>
											</div>
										</div>
									</div><!-- end card body -->
								</div> <!-- end card-->
							</a>
						</div> <!-- end col-->
					<?php }
				?>

				<?php
					if( $super || (isset($access['accessible_functions'][ 'nin_enrolments' ]) && $access['accessible_functions'][ 'nin_enrolments' ]) ){ ?>
						<div class="col-md-6">
							<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=nin_enrolments" class="custom-single-selected-record-button" override-selected-record="<?php echo '-'; ?>" title="Show Traceability Of All Enrolment Activities Dashboard" style="margin: 0 15px 0;">
								<div class="card card-animate shadow-lg">
									<div class="card-body">
										<div class="d-flex justify-content-between">
											<div>
												<h2 class="mt-4X ff-secondary fw-semibold">Enrolment Report Indicators</h2>
											</div>
											<div>
												<div class="avatar-sm flex-shrink-0">
													<span class="avatar-title rounded-circle fs-2">
														<i data-featherx="user" class="mdi mdi-account-arrow-left"></i>
													</span>
												</div>
											</div>
										</div>
									</div><!-- end card body -->
								</div> <!-- end card-->
							</a>
						</div> <!-- end col-->
					<?php }
				?>

				<div class="col-md-6">
					<a href="#" action="?action=nwp_orm&todo=execute&nwp_action=orm_elasticsearch&nwp_todo=ping" class="custom-single-selected-record-button" override-selected-record="<?php echo '-'; ?>" title="Ping Reporting Server" style="margin: 0 15px 0;">
						<div class="card card-animate bg-black ">
							<div class="card-body">
								<div class="d-flex justify-content-between">
									<div>
										<h2 class="mt-4X ff-secondary fw-semibold text-white">Ping Report Server</h2>
									</div>
									<div>
										<div class="avatar-sm flex-shrink-0">
											<span class="avatar-title bg-black rounded-circle fs-2">
												<i data-feather="activity"></i>
											</span>
										</div>
									</div>
								</div>
							</div><!-- end card body -->
						</div> <!-- end card-->
					</a>
				</div> <!-- end col-->

			</div> <!-- end row-->

		</div>
	</div>

</div> <!-- end row-->
