<html>
<head>
<?php 
	$ckey = 'stylesheet';
	if( isset( $data['website_data'][ $ckey ] ) ){
		//print_r( $data['website_data'][ $ckey ] ); exit;
		echo $data['website_data'][ $ckey ];
	}
	
	$pr = get_project_data();
	//print_r( $pr ); exit;
	$ckey = 'title'; 
	if( isset( $data[ $ckey ] ) && $data[ $ckey ] ){
		echo '<title>' . $data[ $ckey ] . '</title>';
	}
	
	$ckey = 'javascript';
	if( isset( $data['website_data'][ $ckey ] ) ){
		echo $data['website_data'][ $ckey ];
	}
?>
</head>
<body style="  margin:15px !important;">
<div id="pagepointer" style="display:none;"><?php echo $pr["domain_name"]; ?></div>
<?php
	
	//print_r( $user_info );
	//print_r( $data['title'] );
	//print_r( $data['preset_header'] );
	//print_r( $data );

	$ckey = 'html_markup';
	if( isset( $data['website_data'][ $ckey ] ) ){
		echo $data['website_data'][ $ckey ];
	}

?>
<div <?php set_hyella_source_path( __FILE__, 1 ); ?> id="dash-board-main-content-area">
<?php
	$ckey = 'html'; 
	if( isset( $data[ $ckey ] ) ){
		echo $data[ $ckey ];
	}else{
		$ar = array( "html_replacement" => "html_replacement_selector", "html_replacement_one" => "html_replacement_selector_one", "html_replacement_two" => "html_replacement_selector_two", "html_replacement_three" => "html_replacement_selector_three", "html_prepend" => "html_prepend_selector", "html_prepend_one" => "html_prepend_selector_one", "html_append" => "html_append_selector", "html_append_one" => "html_append_selector_one", "html_replace" => "html_replace_selector" );
		foreach( $ar as $ak => $av ){
			if( isset( $data[ $ak ] ) && ( $data[ $av ] )  && ! is_array( $data[ $ak] ) ){
				//echo strpos();
				if( strpos( $data[ $av ], '#' ) > -1 ){
					echo '<div id="'. substr( $data[ $av ], 1, strlen( $data[ $av ] ) - 1 ) .'">';
				}else{
					echo '<div class="'. substr( $data[ $av ], 1, strlen( $data[ $av ] ) - 1 ) .'">';
				}
				echo $data[ $ak ] . '</div>';
			}
		}
	}
?>
</div>
</body>
<?php 
	$ckey = 'javascript';
	if( isset( $data['website_data'][ $ckey ] ) ){
		//echo $data['website_data'][ $ckey ];
		?>
		<script type="text/javascript">
			$(document).ready(function(){
				
				// App.init(); // initlayout and core plugins
				// Index.init();
				<?php if( defined("NWP_ENDPOINT") && NWP_ENDPOINT ){ ?>
						$.fn.cProcessFormUrl.customURL = 1;
						$.fn.cProcessFormUrl.requestURL = document.location.origin + "<?php echo NWP_ENDPOINT; ?>";
				<?php } ?>
				
				$.fn.cProcessForm.activateAjaxRequestButton();
				$.fn.cProcessForm.activateDevelopmentMode();
				
				$('body')
				.on("click", '.vertical-nav-menu li > a', function( e ){
					$('.vertical-nav-menu li > a').removeClass( "mm-active" );
					$(this).addClass( "mm-active" );
				});
				
				$('body')
				.on("click", 'li.app-sidebar__heading', function( e ){
					
					$(this)
					.siblings()
					.find("i")
					.removeClass("icon-caret-down")
					.addClass("icon-caret-left");
					
					$(this)
					.siblings()
					.next()
					.find("ul")
					.addClass("hidden");
					
					$(this)
					.find("i")
					.removeClass("icon-caret-left")
					.addClass("icon-caret-down");
					
					$(this)
					.next()
					.find("ul:first")
					.hide()
					.removeClass("hidden")
					.slideDown();
				});
				
				$.fn.cCallBack = {
					action: "$.fn.cCallBack.setUpHospital",
					displayTabTitle: function () {	
						if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.data && $.fn.cProcessForm.returned_ajax_data.data.tab_title ){
							var e = $.fn.cProcessForm.returned_ajax_data.data;
							$("#tab-1-title").html( e.tab_title );
							$('a[href="#tab-1"]').click();
						}
					},
					emptyTabTitle: function () {
						$("#dash-board-main-content-area-content-sub").html('');
						$("#tab-1-title").html('');
						$('a[href="#tab-2"]').click();
					},
					setUpHospital: function() {
						
						$.fn.cProcessForm.ajax_data = {
							ajax_data: { container: "#mis-container" },
							form_method: 'post',
							ajax_data_type: 'json',
							ajax_action: 'request_function_output',
							ajax_container: '',
							ajax_get_url: "?action=all_reports&todo=display_frontend_hospital",
							//ajax_get_url: "?action=all_reports&todo=display_dashboard_hospital",
						};
						$.fn.cProcessForm.ajax_send();
						
					},
					openPatientFile: function() {
						var tmp = $.fn.cCallBack.visibleSearchForm;
						$.fn.cCallBack.visibleSearchForm = 1;
						$('a#search-for-patient-tab').click();
						//setTimeout( function(){ $('form#search-for-patient-content-form').submit(); }, 500 );
						$.fn.cCallBack.visibleSearchForm = tmp;
					},
					visibleSearchForm: 0,
					showSearchPatientForm: function(){
						if( ! $.fn.cCallBack.visibleSearchForm ){
							//$.fn.cCallBack.visibleSearchForm = 1;
							
							$.fn.cProcessForm.ajax_data = {
								ajax_data: { container: "#search-for-patient" },
								form_method: 'post',
								ajax_data_type: 'json',
								ajax_action: 'request_function_output',
								ajax_container: '',
								ajax_get_url: "?action=customers&todo=display_app_view_version_2",
							};
							$.fn.cProcessForm.ajax_send();
						}
					},
					visibleSearchInPatientRegister: 0,
					showSearchInPatientRegister: function(){
						if( ! $.fn.cCallBack.visibleSearchInPatientRegister ){
							//$.fn.cCallBack.visibleSearchInPatientRegister = 1;
							
							$.fn.cProcessForm.ajax_data = {
								ajax_data: { container: "#search-for-patient" },
								form_method: 'post',
								ajax_data_type: 'json',
								ajax_action: 'request_function_output',
								ajax_container: '',
								//ajax_get_url: "?action=admission2&todo=display_manage_admission2",
								ajax_get_url: "?action=appointment2&todo=display_inpatient_view",
							};
							$.fn.cProcessForm.ajax_send();
						}
					},
					visibleSearchAppointmentPatientRegister: 0,
					showSearchAppointmentPatientRegister: function(){
						if( ! $.fn.cCallBack.visibleSearchAppointmentPatientRegister ){
							$.fn.cCallBack.visibleSearchAppointmentPatientRegister = 1;
							
							$.fn.cProcessForm.ajax_data = {
								ajax_data: { container: "#search-for-patient" },
								form_method: 'post',
								ajax_data_type: 'json',
								ajax_action: 'request_function_output',
								ajax_container: '',
								ajax_get_url: "?action=appointment2&todo=display_manage_appointment3",
							};
							$.fn.cProcessForm.ajax_send();
						}
					},
					visibleSearchReports: 0,
					showSearchReports: function(){
						if( ! $.fn.cCallBack.visibleSearchReports ){
							$.fn.cCallBack.visibleSearchReports = 1;	//disabled for now
							
							$.fn.cProcessForm.ajax_data = {
								ajax_data: { container: "#search-for-patient" },
								form_method: 'post',
								ajax_data_type: 'json',
								ajax_action: 'request_function_output',
								ajax_container: '',
								ajax_get_url: "?action=all_reports&todo=display_all_reports_full_view2",
							};
							$.fn.cProcessForm.ajax_send();
						}
					},
					visibleSearchLaboratory: 0,
					showSearchLaboratory: function(){
						if( ! $.fn.cCallBack.visibleSearchLaboratory ){
							//$.fn.cCallBack.visibleSearchLaboratory = 1;	//disabled for now
							
							$.fn.cProcessForm.ajax_data = {
								ajax_data: { container: "#search-for-patient" },
								form_method: 'post',
								ajax_data_type: 'json',
								ajax_action: 'request_function_output',
								ajax_container: '',
								ajax_get_url: "?action=appointment2&todo=display_laboratory_view",
							};
							$.fn.cProcessForm.ajax_send();
						}
					},
					visibleSearchRadiology: 0,
					showSearchRadiology: function(){
						if( ! $.fn.cCallBack.visibleSearchRadiology ){
							//$.fn.cCallBack.visibleSearchRadiology = 1;	//disabled for now
							
							$.fn.cProcessForm.ajax_data = {
								ajax_data: { container: "#search-for-patient" },
								form_method: 'post',
								ajax_data_type: 'json',
								ajax_action: 'request_function_output',
								ajax_container: '',
								ajax_get_url: "?action=appointment2&todo=display_radiology_view",
							};
							$.fn.cProcessForm.ajax_send();
						}
					},
					visibleSearchDispensary: 0,
					showSearchDispensary: function(){
						if( ! $.fn.cCallBack.visibleSearchDispensary ){
							//$.fn.cCallBack.visibleSearchDispensary = 1;	//disabled for now
							
							$.fn.cProcessForm.ajax_data = {
								ajax_data: { container: "#search-for-patient" },
								form_method: 'post',
								ajax_data_type: 'json',
								ajax_action: 'request_function_output',
								ajax_container: '',
								ajax_get_url: "?action=appointment2&todo=display_dispensary_view",
							};
							$.fn.cProcessForm.ajax_send();
						}
					},
					visibleSearchRequisition: 0,
					showSearchRequisition: function(){
						if( ! $.fn.cCallBack.visibleSearchRequisition ){
							//$.fn.cCallBack.visibleSearchDispensary = 1;	//disabled for now
							
							$.fn.cProcessForm.ajax_data = {
								ajax_data: { container: "#search-for-patient" },
								form_method: 'post',
								ajax_data_type: 'json',
								ajax_action: 'request_function_output',
								ajax_container: '',
								ajax_get_url: "?action=appointment2&todo=display_requisition_view",
							};
							$.fn.cProcessForm.ajax_send();
						}
					},
					clickActiveTab: function(){
						
						$("#main-tabs")
						.find("li > a.active")
						.click();
						
						if( ! $.fn.cCallBack.activatedEmptyTab ){
							$.fn.cCallBack.activateEmptyTab();
						}
					},
					activatedEmptyTab:0,
					activateEmptyTab: function(){
						$.fn.cProcessForm.activateEmptyTab();
					},
					activateSidebarMenu: function(){
						App.reHandleSidebarMenu();
					},
					submitForm: function( $form_id ){
						if( $( "form#" + $form_id ) && $( "form#" + $form_id ).is(":visible") ){
							$( "form#" + $form_id )
							.submit();
						}
					},
					submitVisibleForm: function(){
						$("form.refresh-form:visible").submit();
					},
					submitVisibleForm2: function(){
						$("form.refresh-form2:visible").submit();
					},
				};
				
				$.fn.cProcessForm.nwp_hash = '<?php echo nwp_request_hash_key(); ?>';
				
				setTimeout(function(){
					if( typeof( $nwProcessor ) !== 'undefined' ){
						$nwProcessor.bind_show_hide_column_checkbox();
						$nwProcessor.bind_create_field_selector_control();
					}
				<?php
					$ckey = 'data'; 
					if( isset( $data[ $ckey ] ) && is_array( $data[ $ckey ] ) && ! empty( $data[ $ckey ] ) ){
						?>$.fn.cProcessForm.returned_ajax_data.data = <?php echo json_encode( $data[ $ckey ] ); ?>;
						<?php
					}
					$ckey = 'javascript_functions'; 
					if( isset( $data[ $ckey ] ) && is_array( $data[ $ckey ] ) && ! empty( $data[ $ckey ] ) ){
						foreach( $data[ $ckey ] as $js ){
							if( $js ){
								echo $js.'();';
							}
						}
					}
				?>
				}, 100);
			});
		</script>
		<?php
	}
?>
</html>