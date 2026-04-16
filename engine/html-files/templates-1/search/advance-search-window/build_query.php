<div class="row" id="build-query">
	<div class="col-md-4">

		<?php 

			$transaction_options = array( 
				0 => array( "label" => "Select Fields", "value" => "select-fields", "onchange" => 'nwSearch.changeSearchMode(this)', "form_id" => "search-list" ),
				1 => array( "label" => "Search", "value" => "search-form", "onchange" => 'nwSearch.changeSearchMode(this)', "form_id" => "search-list" ),
				2 => array( "label" => "Update", "value" => "update-form", "onchange" => 'nwSearch.changeSearchMode(this)', "form_id" => "search-list" ),
				3 => array( "label" => "Saved", "value" => "saved-searches", "onchange" => 'nwSearch.changeSearchMode(this)', "form_id" => "saved-searches" ),
			);
			
			if( ! $add_select_fields ){
				unset( $transaction_options[0] );
			}
			if( ! $show_update ){
				unset( $transaction_options[2] );
			}
		?>
		<div class="form-group" style="background: #fff; margin-bottom:30px; padding-top: 5px; clear: both; border: 1px solid #ddd; display: block; padding-left: 15px;">
		  <div class="radio-listx">
			<?php 
				$snx = 0;
				$aform = '';
				foreach( $transaction_options as $opt ){
					if( ! $snx ){
						$aform = $opt[ 'value' ];
						$opt[ 'checked' ] = ' checked="checked" ';
						$snx++;
					}
			?>
				<label class="radio-inlinex" style="margin-right:15px;">
				 <input type="radio" id="radio-<?php echo $opt["form_id"]; ?>" name="transaction_option" value="<?php echo $opt["value"]; ?>" <?php if( isset( $opt["checked"] ) )echo $opt["checked"]; ?> onchange="<?php echo $opt["onchange"]; ?>"> <small><?php echo $opt["label"]; ?></small>
				 </label>
					<?php
				}
						// echo '<pre>'; print_r( $aform ); echo '</pre>';
			?>
		  </div>
	   </div>

	    <div id="select-fields" class="search-mode-form" <?php echo $aform == 'select-fields' ? '' : ' style="display:none;" '; ?>>
	   		<form id="create-select-form" class="client-form">
	   			<input value="" class="form-control" type="hidden" name="id" />
	   			
	   			<?php if( isset( $data[ "tables" ] ) && ! empty( $data[ "tables" ] ) ){ ?>
	   			<div style="<?php if( $single_table ){ echo 'display:none;'; } ?>" >
	   				<label>Data Source <sup>*</sup></label>
	   				<select class="form-control" name="table_name" required type="select" onchange="nwSearch.changeDataSource( 'create-query-form' );">
	   					<?php 
	   						foreach( $data[ "tables" ] as $tk => $tv ){ 
	   							$attr = '';
	   							if( isset( $tv['plugin'] ) ){
	   								$attr = ' data-plugin="' . $tv['plugin'] . '" ';
	   							}
	   							echo '<option value="'. $tk .'" '.$attr.'>' . ( isset( $tv['text'] )?$tv['text']:'' ) . '</option>'; 
	   						} 
	   					?>
	   				</select>
	   				<br />
	   			</div>
	   			<?php } ?>

	   			<div>
	   				<label>Fields<sup>*</sup></label>
	   				<select class="form-control" required="required" name="field" onchange="nwSearch.changeField( '', '', 'create-select-form' );">
	   				</select>
	   			</div>
	   			<br />
	   				
	   			<div>
	   				<label>Aggregation </label>
	   				<select class="form-control" name="aggregation" type="select" >
	   					<option value=""></option>
	   					<option value="sum">SUM</option>
	   					<option value="count">Count</option>
	   					<option value="avg">Average</option>
	   					<option value="min">Min</option>
	   					<option value="max">Max</option>
	   					<option value="sort_asc">Sort ASC</option>
	   					<option value="sort_desc">Sort DESC</option>
	   					<option value="group">Group</option>
	   					<option value="percentage">Percentage</option>
	   				</select>
	   			</div>
	   			<br />
	   	
			   	<div>
			   		<label>Alias </label>
			   		<input class="form-control" name="alias" onchange="" />
			   	</div>
			   	<br />
			   		
	   			<!-- <div id="">
	   				<label><input type="checkbox" id="group-by" value="group" /> Group</label>
	   			</div>
	   			<br /> -->
	   				
	   			<br />
	   			<input class="btn dark btn-block" value="Add Query" type="submit" />
	   			
	   		</form>
	   		
	   	</div>


	   	<div id="search-form" class="search-mode-form" <?php echo $aform == 'search-form' ? '' : ' style="display:none;" '; ?>>
			<form id="create-query-form" class="client-form">
				<input value="" class="form-control" type="hidden" name="id" />

				<div>
					<label>Sub Query</label>
					<div class="input-group">
						<select class="form-control" name="sub_query" type="select"  onchange="nwSearch.logicalOperator();">
							<option value="1">1</option>
						</select>
						<span class="input-group-btn">
							<a href="#" class="btn dark" title="Add New Sub-query" onclick="nwSearch.addNewSubQuery();"><i class="icon-plus"></i></a>
						 </span>
					</div>
				</div>
				<br />
				
				<?php if( isset( $data[ "tables" ] ) && ! empty( $data[ "tables" ] ) ){ ?>
				<div style="<?php if( $single_table ){ echo 'display:none;'; } ?>" >
					<label>Data Source <sup>*</sup></label>
					<select class="form-control" name="table_name" required type="select" onchange="nwSearch.changeDataSource( 'create-query-form' );">
						<?php 
							foreach( $data[ "tables" ] as $tk => $tv ){ 
								$attr = '';
								if( isset( $tv['plugin'] ) ){
									$attr = ' data-plugin="' . $tv['plugin'] . '" ';
								}
								echo '<option value="'. $tk .'" '.$attr.'>' . ( isset( $tv['text'] )?$tv['text']:'' ) . '</option>'; 
							} 
						?>
					</select>
					<br />
				</div>
				<?php } ?>

				<div>
					<label>Fields<sup>*</sup></label>
					<select class="form-control" required="required" name="field" onchange="nwSearch.changeField( '', '', 'create-query-form' );">
					</select>
				</div>
				<br />
					
				<div>
					<label>Condition <sup>*</sup></label>
					<select class="form-control" name="condition" type="select" required="required">
					</select>
				</div>
				<br />
		
				<div id="value-container-create-query-form">
					<div class="note note-warning">Please select a field first</div>
				</div>
				<br />
					
				<div>
					<label>Logical Operator <sup>*</sup></label>
					<select class="form-control clear-value"  id="logical-operator" name="logical_operator" type="select" required="required" disabled>
						<?php
							$a = array( "" => "", "and" => "AND", "or" => "OR" );

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
				<br />
				<input class="btn dark btn-block" value="Add Query" type="submit" />
				
			</form>
			
		</div>


		<div id="saved-searches" class="search-mode-form" <?php echo $aform == 'saved-searches' ? '' : ' style="display:none;" '; ?>>
			
			<form id="saved-search-form" class="client-form">
			<label>Saved Searches <sup>*</sup></label>
				<input value="" class="form-control select2" type="text" minlength="0" name="saved_search" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo=get_select2&type=search&created_by=1&include_content=1&reference_table=<?php echo $parent_table; ?>" onchange="nwSearch.loadSaveSearch(this);"  />
				<!-- <input value="" class="form-control select2" type="text" minlength="0" name="saved_search" action="?action=files&todo=get_select2&type=search&created_by=1&include_content=1&reference_table=<?php echo $parent_table; ?>" onchange="nwSearch.loadSaveSearch(this);"  /> -->
			
			<br />
			<br />
			<!-- <input class="btn dark btn-block" value="Add Query" type="submit" /> -->
			</form>
		</div>


	   	<div id="update-form" class="search-mode-form" <?php echo $aform == 'update-form' ? '' : ' style="display:none;" '; ?>>
			<form id="<?php echo $update_form_id; ?>" class="client-form">
				<input value="" class="form-control" type="hidden" name="id" />

				<?php if( isset( $data[ "tables" ] ) && ! empty( $data[ "tables" ] ) ){ ?>
				<div style="<?php if( $single_table ){ echo 'display:none;'; } ?>" >
					<label>Data Source <sup>*</sup></label>
					<select class="form-control" name="table_name" required type="select" onchange="nwSearch.changeDataSource( 'update-value-form' );">
						<?php 
							foreach( $data[ "tables" ] as $tk => $tv ){ 
								$attr = '';
								if( isset( $tv['plugin'] ) ){
									$attr = ' data-plugin="' . $tv['plugin'] . '" ';
								}
								echo '<option value="'. $tk .'" '.$attr.'>' . ( isset( $tv['text'] )?$tv['text']:'' ) . '</option>'; 
							} 
						?>
					</select>
					<br />
				</div>
				<?php } ?>

				<div>
					<label>Fields<sup>*</sup></label>
					<select class="form-control" required="required" name="field" onchange="nwSearch.changeField( '', '', 'update-value-form' );">
					</select>
				</div>
				<br />

				<div id="value-container-update-value-form">
					<div class="note note-warning">Please select a field first</div>
				</div>
				<br />
					
				<br />
				<input class="btn dark btn-block" value="Add Query" type="submit" />
				
			</form>
			
		</div>

	</div>
	<div class="col-md-8">
		<br />
		<div class="shopping-cart-table" id="search-form" <?php echo $aform == 'search-form' ? '' : ' style="display:none;" '; ?>>
			<div class="table-responsive">
				<table class="table table-striped table-hover bordered">
					<thead>
					   <tr>
						  <th>Serial No.</th>
						  <th>Logical Operator</th>
						  <th>Table</th>
						  <th>Field</th>
						  <th>Condition</th>
						  <th>Value</th>
						  <!--<th>Dependencies</th>-->
						  <th class="r"></th>
					   </tr>
					</thead>
					<tbody id="create-query-form-table">
					   
					</tbody>
					<tfoot>
					   
					</tfoot>
				</table>
			</div>
		</div>
	
		<div class="shopping-cart-table" id="update-form" <?php echo $aform == 'update-form' ? '' : ' style="display:none;" '; ?>>
			<div class="table-responsive">
				<table class="table table-striped table-hover bordered">
					<thead>
					   <tr>
						  <th>Serial No.</th>
						  <th>Table</th>
						  <th>Field</th>
						  <th>Value</th>
						  <!--<th>Dependencies</th>-->
						  <th class="r"></th>
					   </tr>
					</thead>
					<tbody id="update-value-form-table">
					   
					</tbody>
					<tfoot>
					   
					</tfoot>
				</table>
			</div>
		</div>
	

		<div class="shopping-cart-table" id="select-fields" <?php echo $aform == 'select-fields' ? '' : ' style="display:none;" '; ?>>
			<div class="table-responsive">
				<table class="table table-striped table-hover bordered">
					<thead>
					   <tr>
						  <th>Serial No.</th>
						  <th>Field</th>
						  <th>Aggregation</th>
						  <th>Alias</th>
						  <!-- <th>Group</th> -->
						  <!--<th>Dependencies</th>-->
						  <th class="r"></th>
					   </tr>
					</thead>
					<tbody id="create-select-form-table">
					   
					</tbody>
					<tfoot>
					   
					</tfoot>
				</table>
			</div>
		</div>
		
		<?php if( ! $query_builder_only ){ ?>
		<form class="activate-ajax" action="<?php echo $action; ?>" id="query-execute-form" style="text-align:center;">
			<?php 
				//@nw5
				foreach( array( "plugin", "real_table", "db_table", "db_table_filter", "o_table" ) as $ok ){
				if( isset( $data[ $ok ] ) && $data[ $ok ] ){ 
			?>
			<input type="hidden" name="<?php echo $ok; ?>" value="<?php echo $data[ $ok ]; ?>">
			<?php } } ?>
			<input type="hidden" name="set_cache_query" value="1">
			<input type="hidden" name="table" value="">
			<?php 
			$tstyle = '';?>
			<textarea class="form-control hyella-data" style="<?php echo $tstyle; ?>" name="data"><?php echo json_encode( $osearch ); ?></textarea>

			<?php  if( ! $hide_json ){ ?>
			<label class="radio-inlinex" style="margin-right:15px;">
			 <input type="checkbox" value="" onchange="nwSearch.save_search(this)"> <small>Save Search</small>
			</label>
			<br>
			<?php } ?>

			<div id="value-save-name" style="display:none;">
				<input class="form-control select2-2 add-attr" placeholder="Enter a description to save search" type="text" name="save_query" type2="search_value" />
			</div>

			<input class="btn blue" value="Execute Query &rarr;" type="submit"> 
		</form>
		<br>
		<?php } ?>
		<?php /*
		<div class="note note-warning"><?php if( $query_builder_only ){ ?><strong>JSON object for Filtering</strong><button class="pull-right btn btn-xs btn-default" onclick="
			$.fn.cProcessForm.copyInputContent( 'where-json-object' ); if( typeof( nwTestAPI ) !== 'undefined' && nwTestAPI.data ){ nwTestAPI.setWhere( 'where-json-object' ); }">Copy JSON Object</button><br /><?php } ?>The JSON object can be used for filtering API 'read' request as the 'where' parameter.</div>
		<textarea class="form-control" readonly name="cart_items" id="where-json-object" <?php if( $query_builder_only ){ echo 'style="min-height:300px;"'; } ?>></textarea>
		*/ ?>
	</div>
</div>