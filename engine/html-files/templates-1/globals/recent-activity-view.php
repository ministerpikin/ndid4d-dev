<?php
	$now = date("U");
	$u = array();
	if( isset( $data['recent_activity'] ) && is_array( $data['recent_activity'] ) ){
		?>
		<div class="table-responsive" id="recent-activities-table-container" >
	<table class="table table-striped table-hover">
	<tbody>
		<?php
		$count = 0;
		foreach( $data['recent_activity'] as $sval ){
			
			if( isset( $sval["user_id"] ) && $sval["user_id"] && isset( $sval["date"] ) && $sval["date"] && isset( $sval["table"] ) && $sval["table"] && isset( $sval["function"] ) && $sval["function"] && isset( $sval["id"] ) && $sval["id"] ){
				++$count;
				if( $count > 5 )break;
			?>
			<tr>
			  <td>
				<?php 
					if( ! isset( $u[ $sval["user_id"] ] ) ){
						$u[ $sval["user_id"] ] = get_users( array( 'id' => $sval["user_id"] ) ); 
					}
					
					if( isset( $u[ $sval["user_id"] ][ "email" ] ) ){
						echo "<strong><i class='icon-user'></i> " . $u[ $sval["user_id"] ][ "email" ] . "</strong><br />";
					}
					
					$title = "";
					$title1 = "";
					$data_text = "";
					
					switch( $sval["function"] ){
					case "get_import_cash_calls_details":
					case "get_import_tendering_details":
					case "get_import_exploration_details":
					case "get_import_geophysics_plan_and_actual_performance_details":
						$title1 = "Import";
						
						if( isset( $sval[ "data" ] ) && is_array( $sval[ "data" ] ) ){
							foreach( $sval[ "data" ] as $ki => $val ){
								$data_text .= "<strong>".ucwords( str_replace("_", " ", $ki ) )."</strong> { ".number_format( doubleval($val) , 0 )." } | ";
							}
						}
					break;
					}
					
					switch( $sval["table"] ){
					case "cash_calls":
						if( isset( $sval["department"] ) ){
							$title = get_select_option_value( array( 'id' => $sval["department"], 'function_name' => "get_departments" ) ) . " - ";
						}
						if( isset( $sval["month"] ) ){
							$title .= get_select_option_value( array( 'id' => $sval["month"], 'function_name' => "get_months_of_year" ) ) . " ";
						}
						$title .= "Cash Calls " . $title1;
					break;
					case "budget_details":
						$title = "Budget Line Items " . $title1;
					break;
					case "exploration_drilling":
					case "exploration_drilling_status":
					case "exploration_activities":
					case "exploration_activities_status":
					case "tendering":
					case "tendering_mexcom":
					case "tendering_contract":
					case "tendering_contract_approved":
					case "tendering_status":
					case "geophysics_plan_and_actual_performance":
						if( isset( $sval["operator"] ) ){
							$title = get_select_option_value( array( 'id' => $sval["operator"], 'function_name' => "get_operators" ) ) . " - ";
						}
						if( isset( $sval["department"] ) ){
							$title .= get_select_option_value( array( 'id' => $sval["department"], 'function_name' => "get_departments" ) ) . " - ";
						}
						
						$status = "";
						$tt1 = "Tendering";
						switch( $sval["table"] ){
						case "tendering_status":
							$status = " Status";
						break;
						case "tendering_mexcom":
							$status = " MEXCOM";
						break;
						case "tendering_contract":
							$status = " Contract Awaiting Approval";
						break;
						case "tendering_contract_approved":
							$status = " Approved Contract";
						break;
						case "exploration_drilling":
							$tt1 = "Exploration Drilling ";
						break;
						case "exploration_drilling_status":
							$tt1 = "Exploration Drilling Status ";
						break;
						case "exploration_activities":
							$tt1 = "Exploration Activities ";
						break;
						case "exploration_activities_status":
							$tt1 = "Exploration Activities Status ";
						break;
						case "geophysics_plan_and_actual_performance":
							$tt1 = "Geophysics Plan / Actual ";
						break;
						}
						$title .= $tt1.$status." " . $title1;
						
					break;
					}
					
					switch( $sval["table"] ){
					case "cash_calls":
					case "budget_details":
					case "tendering":
					case "tendering_mexcom":
					case "tendering_contract":
					case "tendering_contract_approved":
					case "tendering_status":
					case "exploration_drilling":
					case "exploration_drilling_status":
					case "exploration_activities":
					case "exploration_activities_status":
					case "geophysics_plan_and_actual_performance":
						if( function_exists( $sval["function"] ) ){
							$d = $sval["function"]( array( 'id' => $sval["id"] ) );
							if( isset( $d[ "budget_code" ] ) && $d[ "budget_code" ] ){
								$title = get_select_option_value( array( 'id' => $d[ "budget_code" ], 'function_name' => "get_all_budgets" ) ) . " - " . $title;
							}
							
							if( isset( $d[ "file" ] ) && $d[ "file" ] ){
								$title .= '<small><br /><br /><a href="' . $site_url . $d[ "file" ] . '" target="_blank" title="Click to View Original Excel File">View Excel File</a></small>'; 
							}
						}
					break;
					}
					
					if( $title ){
						echo $title . "<br /><small>" . $data_text . "</small>";
					}
				?>
			  </td>
			  <td><span class="pull-right"><?php echo time_passed_since_action_occurred( $now - doubleval( $sval["date"] ), 2 ); ?></span></td>
		   </tr>
			<?php
			}
		}
		?>
		</tbody>
	</table>
 </div>
		<?php
	}
?>
	