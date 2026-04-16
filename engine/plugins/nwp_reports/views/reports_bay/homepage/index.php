<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<style>
		.counter-value{
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}
	</style>
<?php  
	$rd = isset( $data[ 'rdata' ] ) ? $data[ 'rdata' ] : [];
	$rpd = isset( $data[ 'rpdata' ] ) ? $data[ 'rpdata' ] : [];
	// echo '<pre>';print_r( $data );echo '</pre>'; 
	$date_range = isset( $data[ 'date_range' ] ) ? $data[ 'date_range' ] : '';
	$add_url = isset( $data[ 'add_url' ] ) ? $data[ 'add_url' ] : '';
	$addQuery = isset( $data[ 'addQuery' ] ) ? $data[ 'addQuery' ] : [];
	$last_pull_date = isset( $data['last_pull_date'] ) && $data['last_pull_date'] ? $data['last_pull_date'] : 0;
	$iframe = isset( $data['iframe'] ) && $data['iframe'] ? $data['iframe'] : 0;
	$dashboard_action = isset( $data['action_to_perform'] ) && $data['action_to_perform'] ? $data['action_to_perform'] : '';

	$replace_url = '';
	if( defined( 'NWP_USE_PACKAGE_URL_4_ENDPOINTS' ) && NWP_USE_PACKAGE_URL_4_ENDPOINTS ){
		$gd = get_project_data();
		$replace_url = $gd[ 'domain_name' ] . 'api/?';
	}

	$table = 'es_monitoring';
	$plugin = 'nwp_reports';

	$rtit0 = 'Report Dashboard';
	$rtit = 'Report Dashboard';

	$rr = isset( $data[ 'report' ] ) && $data[ 'report' ] ? $data[ 'report' ] : 'environmental_and_social_safeguard_indicators';
	$fname = str_replace( '_', '-', $rr );

	switch( $rr ){
		case 'environmental_and_social_safeguard_indicators':
			$rtit0 = 'Environmental And Social Safeguard Indicators';
			$rtit = 'Total NIN Enrollment Statistics';
		break;
		case 'grm_dashboard':
			$rtit0 = 'Grievance Redress Mechanism (GRM) Indicators';
			$rtit = 'GRM Indicators For Enrolment Grievances';

			$table = 'grm_data';

		break;
		case 'comm_dashboard':
			$rtit0 = 'Enrolee Satisfaction';
			$rtit = 'Indicators in Enrolee\'s Satisfaction';

			$table = 'communications';

		break;
		case 'comm_i_dashboard':
			$rtit0 = 'Internal and External Communications';
			$rtit = 'Report Communications related Activity';
		break;
		case 'social_dashboard':
			$rtit0 = 'Social Accountability Monitoring Indicators';
			$rtit = 'Report social accountability enrolment related indicators';

			$table = 'social_accountability_monitoring';

		break;
		case 'suspicious_dashboard':
			$rtit0 = 'Traceability Of Suspicious Enrolment Activity Indicators';
			$rtit = 'Report social accountability enrolment related indicators';

			$table = 'suspicious_activity';

		break;
		case 'financial_dashboard':
			$rtit0 = 'Financial Indicators On Enrolment Services';
			$rtit = 'Financial Indicators On Enrolment Services';

			$table = 'financial_indicators_on_enrolment_services';

		break;
		case 'project_dashboard':
			$rtit0 = 'Project Development Objectives Indicators';
			$rtit = 'Report Project Development Objectives Indicators For Monitoring & Evaluation';
		break;
		case 'indicator_dashboard':
			$rtit0 = 'Project Development Objectives Indicators (Revised)';
			$rtit = 'Report Project Development Objectives Indicators';
		break;
		case 'device_dashboard':
			$rtit0 = 'Approved Enrollment Device Management';
			$rtit = 'Report Approved Enrolment Device Management Activity';
			$table = 'device_mgt';
			$plugin = 'nwp_device_management';
		break;
		case 'device_dashboard2':
			$rtit0 = 'Pending Enrollment Device Management';
			$rtit = 'Report on Pending Devices Activity';
			$table = 'device_mgt_sr';
			$plugin = 'nwp_device_management';
		break;
		case 'nin_enrolments':
			$rtit0 = 'Enrollment Statistics';
			$rtit = 'Report Indicators on NIN Enrolment Activity';
			$table = 'es_monitoring';
			$plugin = 'nwp_reports';
		break;
	}

	switch( $date_range ){
		case 'this_month':
			$rtit .= ' for ' . date("M.Y");
		break;
		case 'last_month':
			$rtit .= ' for ' . date("M.Y", strtotime( '1 month ago' ));
		break;
		case 'last3_month':
			$rtit .= ' form ' . date("M.Y", strtotime( '3 months ago' )) . ' to date';
		break;
		case 'ytd':
			$rtit .= ' form Jan.' . date("Y") . ' to date';
		break;
		case 'last_year':
			$rtit .= ' for ' . ( date("Y") - 1 );
		break;
		case 'last_3_years':
			$rtit .= ' form ' . ( date("Y") - 3 ) . ' to date ';
		break;
	}

	$additional_title = '';

	$rp_icon = 'ri-toggle-line';
	$ptodo = 'display_dashboard_personal';
	if( ! empty( $rpd ) ){
		$additional_title = '<span class="text-danger"> - Personal</span>';
		$ptodo = 'display_dashboard';
		$rp_icon = 'ri-toggle-fill';
	}

?>

	<?php require_once 'prepard_card.php'; ?>
	
	<div class="row <?php echo ($spd ? "p-4" : "") ?>">
		<div class="col-12">
			<div class="page-title-box d-sm-flex align-items-center justify-content-between">
				<h4 class="mb-sm-0 text-primary"><?php echo $rtit0 . $additional_title; ?></h4>
				<?php
					if( $last_pull_date ){ ?>
						<div class="">
							<div class="text-danger"><small>Last Refresh: <?php echo date("jS F Y H:i A", $last_pull_date) ?></small></div>
						</div>
					<?php }
				?>
				<div class="page-title-right d-sm-flex">
					<?php
						if( !$iframe ){ ?>
							<div class="">
								<a href="#" class="ex-dash-img page-title-right"  title="Export Dashboard As JPEG" style="margin: 0 15px 0;">
									<i class="mdi mdi-image" style="font-size: 25px;"></i>
								</a>
							</div>
							<div class="">
								<a href="#" class="ex-dash page-title-right"  title="Export Dashboard As PDF" style="margin: 0 15px 0;">
									<i class="mdi mdi-printer" style="font-size: 25px;"></i>
								</a>
							</div>
						<?php }
					?>
					<?php
						if( !$spd ){ ?>
							<div class="">
								<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=<?php echo $ptodo; ?>&plugin=<?php echo $plugin; ?>&table=<?php echo $table; ?>&no_side_bar=1&report=<?php echo $rr; ?>" class="DashboardSingleFilter custom-single-selected-record-button page-title-right" override-selected-record="<?php echo '-'; ?>" title="Show Personalized Dashboard" style="margin: 0 15px 0;">
									<i class="<?php echo $rp_icon; ?>" style="font-size: 25px;"></i>
								</a>
							</div>
							<select class="form-control dashboard-filter" name="date_range" data-bs-toggle="tooltip" title="Filter Date Data was ingested">
								<option>-- Filter Date --</option>
								<option value="this_month" <?php echo $date_range == 'this_month' ? 'selected' : '' ?>>This Month (<?php echo date("M.Y"); ?>)</option>
								<option value="last_month" <?php echo $date_range == 'last_month' ? 'selected' : '' ?>>Last Month</option>
								<option value="last3_month" <?php echo $date_range == 'last3_month' ? 'selected' : '' ?>>Last 3 Months</option>
								<option value="ytd" <?php echo $date_range == 'ytd' ? 'selected' : '' ?>>Year to Date</option>
								<option value="last_year" <?php echo $date_range == 'last_year' ? 'selected' : '' ?>>Last Year</option>
								<option value="last_3_years" <?php echo $date_range == 'last_3_years' ? 'selected' : '' ?>>Last 3 Years</option>
							</select>
							<span><i style="font-size: large;" class="mdi mdi-help-circle ms-2 text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="Filter Date Data was ingested"></i></span>
						<?php }
					?>
				</div>			
			</div>
		</div>
	</div>
	<div id="e-dashboard" class="<?php echo ($spd ? "p-4" : "px-2") ?>">
		
		<?php include dirname( dirname( __FILE__ ) ) . '/reports/' . $fname . '.php' ?>

		</div>
	</div>
	<?php
		if( $iframe ){ ?>
			<div id="preloader">
				<div id="status">
					<div class="spinner-border text-primary avatar-sm" role="status">
						<span class="visually-hidden">Loading...</span>
					</div>
				</div>
			</div>
		<?php }
	?>
	<script type="text/javascript" >
		var dd = <?php echo json_encode( $rd ); ?>;
		var replace_url = '<?php echo $replace_url; ?>';
		var add_url = '<?php echo $add_url; ?>';
		var addQuery = <?php echo json_encode( $addQuery ); ?>;
		var dashboard_action = '<?php echo $dashboard_action; ?>';
		var tooltipInit = false;
		<?php if( file_exists( dirname( __FILE__ ).'/dashboard-script.js' ) )include "dashboard-script.js"; ?>

		var params = {};
		$( 'select.dashboard-filter,input.dashboard-filter' ).off( 'change' ).on( 'change', function(){
			params[ $(this).attr( 'name' ) ] = $(this).val();

			$.fn.cProcessForm.function_click_process = 1;
			$.fn.cProcessForm.ajax_data = {
				ajax_data: params,
				form_method: 'post',
				ajax_data_type: 'json',
				ajax_action: 'request_function_output',
				ajax_container: '',
				ajax_get_url: "?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=display_dashboard&no_side_bar=1&report=<?php echo $rr; ?>",
			};
			$.fn.cProcessForm.ajax_send();
		});

		$(function(){
			if( !tooltipInit ){
				let tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
				tooltips.forEach(el => new bootstrap.Tooltip( el ));
				tooltipInit = true;
			}

			$('.ex-prt').click(function(){
				let elem = $(this).parents('.col-md-6')[0];
				if(elem){
					$(document).find('html').attr('data-preloader', "enable");
					setTimeout(function(){
						let tt = $(elem).find('.c-title').text();
						html2pdf()
						.set({ margin: 5, html2canvas: {scale : 5} })
						.from( elem )
						.save( tt + '.pdf' )
						$(document).find('html').attr('data-preloader', "disable");
					}, 1000);
				}
			});

			$('.ex-img').click(function(){
				let elem = $(this).parents('.col-md-6')[0];
				if(elem){
					$(document).find('html').attr('data-preloader', "enable");
					setTimeout(function(){
						let tt = $(elem).find('.c-title').text();
						html2canvas(elem).then(function(c){
					    	let a = document.createElement('a');
							a.download = tt + '.png';
							a.href = c.toDataURL();
							a.click();
						});
						$(document).find('html').attr('data-preloader', "disable");
					}, 1000);
				}
			});

			$('.ex-dash').click(function(){
				let elem = $('#e-dashboard')[0];
				let tt = $('.page-title-box').find('h4').text();
				if( elem ){
					$(document).find('html').attr('data-preloader', "enable");
					setTimeout(function(){
						domtoimage.toPng(elem, { style: { backgroundColor: '#f2f2f7' } }).then(function( dataUrl ){
							const { jsPDF } = window.jspdf;
					        const pdf = new jsPDF({orientation: 'landscape'});
					        const pageWidth = pdf.internal.pageSize.getWidth();
				            const pageHeight = pdf.internal.pageSize.getHeight();

				            const img = new Image();
				            img.src = dataUrl;
				            img.onload = function () {
				                const imgWidth = img.width;
				                const imgHeight = img.height;
				                const ratio = Math.min(pageWidth / imgWidth, pageHeight / imgHeight);
				                const imgX = (pageWidth - imgWidth * ratio) / 2;
				                const imgY = (pageHeight - imgHeight * ratio) / 2;
				                pdf.addImage(dataUrl, 'PNG', imgX, imgY, imgWidth * ratio, imgHeight * ratio);				                
					        	pdf.save(tt + ".pdf");
				            };
						});
						$(document).find('html').attr('data-preloader', "disable");
					}, 1500);
				}
			});

			$('.ex-dash-img').click(function(){
				let elem = $('#e-dashboard')[0];
				let tt = $('.page-title-box').find('h4').text();
				if( elem ){
					$(document).find('html').attr('data-preloader', "enable");
					setTimeout(function(){
						domtoimage.toPng(elem, { style: { backgroundColor: '#f2f2f7' } }).then(function(dataUrl){
							let a = document.createElement('a');
							a.download = tt + '.png';
							a.href = dataUrl;
							a.click();
						});
						$(document).find('html').attr('data-preloader', "disable");
					}, 1500);
				}
			});

			<?php
				if( $iframe ){ ?>
					setTimeout(function(){
						$(document).find('html').attr('data-preloader', "disable");
					}, 1000);
				<?php }
			?>
		});
	</script>
</div>
