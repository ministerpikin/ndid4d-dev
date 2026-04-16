<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>

<div class="row">
	<div class="col-md-12">

	<div class="portlet grey box">
	<div class="portlet-title">
		<div class="caption"><small>Audit Trail Analysis</small></div>
	</div>
	<div class="portlet-body " style="padding-bottom:50px;">
		
		<div class="row">
			<div class="col-md-2">
				<form class="activate-ajax" method="post" action="?action=audit&todo=generate_audit_trail_analysis">
					
					<div>
					<select class="form-control" name="report_format" required="required">
						<?php 
							$field["option"] = get_audit_trail_report_format();
							if( isset( $field["option"] ) && is_array( $field["option"] ) && !empty( $field["option"] ) ){
								foreach( $field["option"] as $k => $v ){
								?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php
								}
							}
						?>
					</select>
					</div>
					<br />
				
					<div>
					<select class="form-control" name="report_action">
						<option value="">Filter By Action</option>
						<?php 
							$field["option"] = get_audit_trail_report_actions();
							if( isset( $field["option"] ) && is_array( $field["option"] ) && !empty( $field["option"] ) ){
								foreach( $field["option"] as $k => $v ){
								?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php
								}
							}
						?>
					</select>
					</div>
					<br />
							
					<div>
					<label>Report <sup>*</sup></label>
					<select class="form-control" name="report_type" required="required">
						<?php 
							$field["option"] = get_audit_trail_report_types();
							if( isset( $field["option"] ) && is_array( $field["option"] ) && !empty( $field["option"] ) ){
								foreach( $field["option"] as $k => $v ){
								?>
								<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
								<?php
								}
							}
						?>
					</select>
					</div>
					<br />
						
					<div>
						<label>Start Date <sup>*</sup></label>
						 <input type="date" name="start_date" class="form-control" required="required" value="<?php echo date("Y-m-d"); ?>" />
					</div>
					<br />
					
					<div>
						<label>End Date <sup>*</sup></label>
						 <input type="date" name="end_date" class="form-control" required="required" value="<?php echo date("Y-m-t"); ?>" />
					</div>
					<br />
					
					<div>
						<!--<label>Data Source</label>-->
						<?php 
							/* $data["databases"] = get_database_tables( array( "group_plugin" => 1 ) );
							if( isset( $data["databases"] ) && ! empty( $data["databases"] ) ){
								echo '<select name="data_source" class="form-control select2"><option></option>';
								foreach( $data["databases"] as $dbk1 => $dbv1 ){
									if( isset( $dbv1["classes"] ) && ! empty( $dbv1["classes"] ) ){
										echo '<optgroup label="'. $dbv1["label"] .'">';
										foreach( $dbv1["classes"] as $dbk => $dbv ){
											
											echo '<option value="type='. $dbv1["type"] .':::key='. $dbk1 .':::value='. $dbk .'">'. $dbv .'</option>';
										}
										echo '</optgroup>';
									}
								}
								echo '</select>';
							}  */
						?>
					</div>
					<br />
					
					<input class="btn btn-block blue" type="submit" value="Search" />
						
				</form>
				
			</div>
			<div class="col-md-10 resizable-height" style="overflow-y:auto; overflow-x:hidden;">
				<div id="audit-trail-analysis-container" >
					
				</div>
			</div>
			
		</div>
		
	</div>
	</div>
	</div>
</div>

</div>
