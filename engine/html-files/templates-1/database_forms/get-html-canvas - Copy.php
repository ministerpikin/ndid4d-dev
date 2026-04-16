<style type="text/css">
	ul.tools_list>li, ul.tools_from_database>li {
	    margin: 12px 0;
	    list-style-type: none;
	    border: 2px solid gray;
	    border-radius: 4px !important;
	    padding: 4px;
	}
	.hey {
		margin: 20px;
	}

	span.options {
		/* position: relative; */
		/* font-size: 25px; */
		/* font-weight: bold; */
		/* float: right; */
		/* right: 16px; */
	}
	.row.demo-row {
	    border: 0.5px dashed black;
	    min-height: 100px;
		margin-bottom:20px;
	}
	#finalWizard i{
		padding: 0 5px;
	}
	div.demo-col {
		
		border: 1px dashed black;
		min-height: 100px;
		padding: 0;
	}
	
	.ui-state-highlight{
		height: fit-content;
	}
	.demo-row>.options {
		clear: both;
		display: block;
	}
	#list-option input{
		position: absolute;
	}
	div#list-option input {
	    width: 33%;
	    float: left;
	    position: relative;
	}
	#field-script-container,
	#field-options-container{
		    border: 1px solid #ddd;
		box-shadow: 2px 2px 2px #ddd;
		width: 96%;
		position: absolute;
		top: 1%;
		padding: 25px;
		z-index: 10;
		background: #fff;
	}
	#field-script-container fieldset,
	#field-options-container fieldset{
		
	}
	#field-label{
		text-transform: uppercase;
		display: inline;
	}
	ul.tools_from_database,
	ul.tools_list{
		padding-left:0;
	}
</style>
<?php

$form_data = "''";
$record_id = '';
$form_name = '';
// print_r( $data );exit;

if( isset( $data ) && $data ){
	$form_data = json_encode( $data[ 'data' ][ 'row_col' ] );
	$record_id = $data[ 'record_id' ];
	$form_name = $data[ 'form_name' ];
}
?>
<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<div class="wizard row" id="finalWizard">
		<div class="col-md-2 ">
			<form>
				<a id="click" href="#" onclick="$.fn.dragDropCanvas.clear();" class="btn btn-xs btn-default"><i class="icon-trash"></i> Clear All</a>
				
				<div class="panel-group accordion" id="side-accordion">
					
					<div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#side-accordion" href="#side-accordion-1" style="display:block;"><strong>
									Alignment
                                </strong></a>
                            </h4>
                        </div>
                        <div id="side-accordion-1" class="panel-collapse collapse">
                            <div class="panel-body">
								<div title="Drag a row / column and drop it to the right">
									<ul class="tools_list">
										<li class="tool" id="row">Row</li>
										<li class="tool ui-widget-content" id="column">Column</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					
					<div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#side-accordion" href="#side-accordion-2" style="display:block;"><strong>
									Fields
                                </strong></a>
                            </h4>
                        </div>
                        <div id="side-accordion-2" class="panel-collapse collapse">
                            <div class="panel-body">
								<div title="Drag a form control and drop it to the right">
								<ul class="tools_list">
									<li class="tool" id="text">Text Field</li>
									<li class="tool" id="select">Select</li>

									<li class="tool" id="multi-select">Multi-Select</li>
									<li class="tool" id="date-5">Date</li>

									<li class="tool" id="date-5time">Date (Show Time)</li>
									<li class="tool" id="textarea">Text Area</li>
									<li class="tool" id="password">Password</li>

									<li class="tool" id="old-password">Old Password</li>
									<li class="tool" id="tel">Telephone</li>
									<li class="tool" id="email">Email</li>

									<li class="tool" id="decimal">Decimal</li>
									<li class="tool" id="number">Number</li>

									<li class="tool" id="currency">Currency</li>
									<li class="tool" id="calculated">Calculated</li>
									<li class="tool" id="file">File Upload</li>

									<li class="tool" id="textarea-unlimited">Textarea Unlimited</li>

									<li class="tool" id="single_json_data">Single JSON Data</li>

									<li class="tool" id="multiple_json_data">Multiple JSON Data</li>
									<li class="tool" id="radio">Radio Button</li>
									<li class="tool" id="checkbox">Checkbox Button</li>
									<li class="tool" id="button">Button</li>
									<li class="tool" id="header">Header</li>

									<li class="tool" id="field_group">Field Group</li>
									<li class="tool" id="html">HTML</li>
								</ul>
							</div>
							</div>
						</div>
					</div>
					
					<div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#side-accordion" href="#side-accordion-3" style="display:block;"><strong>
									Search
                                </strong></a>
                            </h4>
                        </div>
                        <div id="side-accordion-3" class="panel-collapse collapse">
                            <div class="panel-body">
								<div id="existingFields">
									<div class="row">
										<div class="col-md-12">
											<input value="" class="form-control form-gen-element select2" type="text" name="table_name" action="?action=database_table&todo=get_available_database_tables" id="searchDatabaseTable" onchange="$.fn.dragDropCanvas.searchDatabaseTableForForms('searchDatabaseTable');" required>
										</div>
									</div>
									<ul class="tools_from_database" style="max-height:300px; overflow-y:auto;">
									</ul>
								</div>
							</div>
						</div>
					</div>
					
					<div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#side-accordion" href="#side-accordion-4" style="display:block;" onclick="$.fn.dragDropCanvas.showScriptWindow(); return false;"><strong>
									Script Window
                                </strong> <i class="icon-external-link pull-right"></i></a>
                            </h4>
                        </div>
					</div>
					
				</div>
			</form>
			
			<div style="margin:20px 0; clear:both;">
			<div >
				<form id="drag-drop-form" method="post" action="?action=database_forms&todo=drag_drop_save" class="activate-ajax">
					<textarea type="text" name="form_data" style1="display:none;"></textarea>
					<textarea name="form_script" style1="display:none;"></textarea>
					
					<input type="hidden" name="record_id" value="<?php echo $record_id ?>" >
					
					
					<div class="row">
						<div class="col-md-12">
							<div class="radio-listx">
							<?php 
								$save_options = array(
									0 => array( "label" => "Form Only", "value" => "form", "checked" => ' checked="checked" ' ),
									1 => array( "label" => "Table & Form", "value" => "table_form"  ),
								);
								
								unset( $save_options[1] );
								
								foreach( $save_options as $opt ){
							?>
								<label class="radio-inlinex" style="margin-right:15px;">
								<input type="radio" name="status" onclick="$.fn.dragDropCanvas.displayOptions( 'forms', '<?php echo $opt["value"] ?>' )" value="<?php echo $opt["value"]; ?>" <?php if( isset( $opt["checked"] ) )echo $opt["checked"]; ?> > <?php echo $opt["label"]; ?>
								</label>
									<?php
								}
							?>
						  </div>
						</div>
					</div>
					<br />
					
					<div class="forms" id="form">
						<div class="row">
							<div class="col-md-12">
								<label>Form Name</label>
								<div class=" ">
									<input value="" class="form-control form-gen-element" type="text" name="form_name" >
								</div>
							</div>
						</div>
						<br />
					</div>
					<div class="forms" id="table_form" style="display:none;">
						<div class="row">
							<div class="col-md-12">
								<label>Table Name</label>
								<div class=" ">
									<input value="" class="form-control form-gen-element" type="text" name="new_table_name" >
								</div>
							</div>
							<br />
							<div class="col-md-12">
								<label>Table Label</label>
								<div class=" ">
									<input value="" class="form-control form-gen-element" type="text" name="table_label" >
								</div>
							</div>
							<br />
						</div>
					</div>
					<!--
					<h3>Tie form to Existing Table</h3>
					<div>
						<div class="row">
							<div class="col-md-12">
								<label>Select Table</label>
								<div class=" ">
									<input value="" class="form-control form-gen-element select2" type="text" name="table_name" action="?action=database_table&amp;todo=get_available_database_tables">
								</div>
							</div>
						</div>
					</div>
					-->
					<br />
					<input class="btn btn-primary blue" value="Save Changes " type="submit" id="submit">
				</form>
			</div>
		</div>
		
		</div>
		<div class="col-md-10" id=""  style="">
			<div id="field-script-container" style="display:none; z-index:100;">
				<form id="field-script" class="client-form">
					<a href="#" id="close-field-settings2" class="btn btn-sm red pull-right" title="Close Me" onclick="$.fn.dragDropCanvas.closeSettingsForm2();" style="margin-top:10px;"><i class="icon-remove"></i></a>
					<fieldset><legend>FORM VALIDATION SCRIPT </legend>
					
					<div class="row">
						<div class="col-md-9">
							<textarea class="form-control" type="text" name="validation_code" rows="30" style="background:#444; color:#fff;">/*Enter your validation JavaScript Code Here*/</textarea>
						</div>
						<div class="col-md-3">
							<strong>Field Identifiers</strong><br />
							<pre id="field-id-container" style="line-height:2; white-space:nowrap;">
								
							</pre>
						</div>
					</div>	
					<br />
					<input class="btn btn-primary blue" value="Save Script" type="submit" />
					</fieldset>
					
				</form>
			</div>
			
			<div id="field-options-container" style="display:none;">
				
				<form id="field-options" class="client-form">
					<div class="row" id="edit-field">
						<div class="col-md-12">
							<a href="#" id="close-field-settings" class="btn btn-sm red pull-right" title="Close Me" onclick="" style="margin-top:10px;"><i class="icon-remove"></i></a>
							
							<fieldset><legend>FIELD SETTINGS: <div id="field-label"></div></legend>
								<div class="row" style="display:none;">
									<div class="col-md-3">
										<label>Form Field Type</label>
										<input type="hidden" class="field_options form-control" name="form_field" value="" key="" id="">
									</div>
									<div class="col-md-3">
										<label>ID</label>
										<input type="hidden" class="field_options form-control" name="id" value="" key="" id="">
									</div>
									<div class="col-md-3">
										<label>Field Label</label>
										<input type="hidden" class="field_options form-control" name="field_label" value="" key="" id="">
									</div>
									<div class="col-md-3">
										<label>Reuse</label>
										<input type="hidden" class="field_options form-control" name="reuse" value="" key="" id="">
									</div>
								</div>
								<div class="row">
									<div class="col-md-3" id="required_field" style="display:none;">
										<label>Required Field</label>
										<select class="field_options form-control" value="" key="" id="" name="required_field">
											<option value="yes" >Yes</option>
											<option value="no">No</option>
										</select>
									</div>
									<div class="col-md-3" id="class" style="display:none;">
										<label>Class</label>
										<input type="input" class="field_options form-control" name="class" value="" key="" id="">
									</div>
									<div class="col-md-3" id="attributes" style="display:none;">
										<label>Attributes</label>
										<input type="input" class="field_options form-control" name="attributes" value="" key="" id="">
									</div>
									<div class="col-md-3" id="value" style="display:none;">
										<label>Value</label>
										<input type="input" class="field_options form-control" name="value" value="" key="" id="">
									</div>
								</div>
								
								<br />
								<div class="row">
									<div class="col-md-3" id="tooltip" style="display:none;">
										<label>Tooltip</label>
										<input type="input" class="field_options form-control" name="tooltip" value="" key="" id="">
									</div>
									<div class="col-md-3" id="note" style="display:none;">
										<label>Note</label>
										<input type="input" class="field_options form-control" name="note" value="" key="" id="">
									</div>
									<div class="col-md-3" id="placeholder" style="display:none;">
										<label>Placeholder</label>
										<input type="input" class="field_options form-control" name="placeholder" value="" key="" id="">
									</div>
									<div class="col-md-3" id="serial_number" style="display:none;">
										<label>Serial Number</label>
										<input type="input" class="field_options form-control" name="serial_number" value="" key="" id="">
									</div>
								</div>
								
								<br />
								<div class="row">
									<div class="col-md-3" id="display_position" style="display:none;">
										<label>Display Position</label>
										<select class="field_options form-control" value="" key="" id="" name="display_position">
											<?php
												$a = get_field_display_options();

												if( isset( $a ) && is_array( $a ) && ! empty( $a ) ){
													foreach( $a as $key => $value ){
											?>
													<option value="<?php echo $key ?>" ><?php echo $value ?></option>
											<?php
													}
												}
											?>
										</select>
									</div>
									<div class="col-md-3" id="default_appearance_in_table_fields" style="display:none;">
										<label>Appearance In Table Fields</label>
										<select class="field_options form-control" value="" key="" id="" name="default_appearance_in_table_fields">
											<?php
												$a = get_field_appearance();

												if( isset( $a ) && is_array( $a ) && ! empty( $a ) ){
													foreach( $a as $key => $value ){
											?>
													<option value="<?php echo $key ?>" ><?php echo $value ?></option>
											<?php
													}
												}
											?>
										</select>
									</div>
									<div class="col-md-3" id="display_field_label" style="display:none;">
										<label>Display Field Label</label>
										<input type="input" class="field_options form-control" name="display_field_label" value="" key="" id="">
									</div>
									<div class="col-md-3" id="acceptable_files_format" style="display:none;">
										<label>Acceptable Files Format</label>
										<input type="input" class="field_options form-control" name="acceptable_files_format" value="" key="" id="">
									</div>
									<div class="col-md-3" id="tag" style="display:none;">
										<label>Tag</label>
										<input type="input" class="field_options form-control" name="tag" value="" key="" id="">
									</div>
								</div>
								
								<br />
								<div class="row">
									<div class="col-md-3" id="options" style="display:none;">
										<div class="radio-listx">
											<?php 
												$select_options = array(
													0 => array( "label" => "New List", "value" => "new_list", "checked" => ' checked="checked" ' ),
													1 => array( "label" => "Existing List", "value" => "existing_list"  ),
													2 => array( "label" => "Functions", "value" => "form_field_options"  ),
												);
												
												foreach( $select_options as $opt ){
											?>
												<label class="radio-inlinex" style="margin-right:15px;">
												<input type="radio" name="status" onclick="$.fn.dragDropCanvas.displayOptions( 'select_options', '<?php echo $opt["value"] ?>' )" value="<?php echo $opt["value"]; ?>" <?php if( isset( $opt["checked"] ) )echo $opt["checked"]; ?> > <?php echo $opt["label"]; ?>
												</label>
													<?php
												}
											?>
										</div>
										<div class="row select_options" id="new_list">
											<div class="col-md-12">
												<textarea class="form-control" placeholder="red: Red; blue: Blue;" id="" name="options"></textarea>
											</div>
										</div>
										<div class="row select_options" id="existing_list" style="display:none;">
											<div class="col-md-12">
												<input class="form-control" type="text"  placeholder="" id="" />
											</div>
										</div>
										<div class="row select_options" id="form_field_options" style="display:none;">
											<div class="col-md-12">
												<input class="form-control" type="text"  name="form_field_options" id="" />
											</div>
										</div>
									</div>
									<div class="col-md-3" id="calculations" style="display:none;">
										<label>Calculations</label>
										<textarea class="form-control" placeholder="red: Red; blue: Blue;" id="" name="calculations"></textarea>
									</div>
									<div class="col-md-3" id="other_parameters" style="display:none;">
										<label>Other Parameters</label>
										<textarea class="form-control" placeholder="red: Red; blue: Blue;" id="" name="other_parameters"></textarea>
									</div>
								</div>
								
								<br />
								<input class="btn btn-primary blue" value="Save Changes " type="submit" id="submit">
							</fieldset>
						</div>
					</div>
				</form>
			</div>
			<div class="ui-widget-header" id="droppable"  style="max-height: 750px; min-height:400px; padding:40px; overflow-y:auto;"></div>
		</div>
	</div>
</div>
<script>
</script>
<script type="text/javascript" >
	<?php if( file_exists( dirname( __FILE__ ).'/get-html-canvas-script.js' ) )include "get-html-canvas-script.js"; ?>
</script>