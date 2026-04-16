<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
// echo '<pre>'; print_r( $data ); echo '</pre>';
	if( ! isset( $data["table"] ) )$data["table"] = '';
	
	$search_table = $data["table"];
	$search_table = isset( $data[ 'ptable' ] ) ? $data[ 'ptable' ] : $data["table"];
	$plugin = isset( $data["plugin"] ) ? $data["plugin"] : '';
	
	if( isset( $data["search_table"] ) ){
		$search_table =$data["search_table"];
	}
	
	$search_todo = 'search_list2';
	if( isset( $data["search_todo"] ) && $data["search_todo"] )$search_todo = $data["search_todo"];
	
	$params = '';
	if( isset( $data[ "html_replacement_selector" ] ) && $data[ "html_replacement_selector" ] ){
		$params = '&html_replacement_selector=' . $data[ "html_replacement_selector" ];
	}
	
	if( ! isset( $show_new_button ) )$show_new_button = 1;
	if( ! isset( $show_delete_button ) )$show_delete_button = 1;
	
	$show_print_button = 1;
	if( isset( $data["hide_print_button"] ) && $data["hide_print_button"] ){
		$show_print_button = 0;
	}
	
	if( isset( $data["hide_new_button"] ) && $data["hide_new_button"] ){
		$show_new_button = 0;
	}
	
	if( isset( $data["hide_delete_button"] ) && $data["hide_delete_button"] ){
		$show_delete_button = 0;
	}
	
	$show_update_button = 0;
	if( isset( $data["update_button"] ) && $data["update_button"] ){
		$show_update_button = 1;
	}
	
	$reference = 'new';
	$new_todo = ( isset( $data["new_button_todo"] ) && $data["new_button_todo"] )?$data["new_button_todo"]:'new_popup_form';
	
	$delete_todo = ( isset( $data["delete_button_todo"] ) && $data["delete_button_todo"] )?$data["delete_button_todo"]:'delete_app_manager';
	
	$show_last_num_records_count = 0;
	if( isset( $data["show_last_records_option"] ) && $data["show_last_records_option"] ){
		$show_last_num_records_count = get_app_manager_control_number_of_records_count();
		
		if( isset( $data["show_last_records_count"] ) && $data["show_last_records_count"] ){
			$show_last_num_records_count = $data["show_last_records_count"];
		}
	}
	
	$class_col_span = '8';
	$show_buttons = 1;
	if( isset( $data["hide_buttons"] ) && $data["hide_buttons"] ){
		$show_buttons = 0;
		$class_col_span = '12';
	}
	$opts = array( "copy" => 0 );
?>
<div class="hidden-print" id="report-preview-container-id">
	<?php if( $show_print_button ){ ?>
	<div class="row">
		<div class="col-lg-<?php echo $class_col_span; ?>">
			<?php echo get_export_and_print_popup( ".table" , "#". $data["table"] ."-record-search-result", "", 1, $opts ) . "</div>"; ?>
		</div>
	</div>
	<?php } ?>
	
	<div class="row">
	<div class="col-md-12 col-lg-<?php echo $class_col_span; ?>">
		<?php
			$act = 'action='. $search_table .'&todo='. $search_todo . $params;
			$act2 = 'action='. $search_table .'&todo=';

			if( $plugin ){
				$act = 'action='.$plugin .'&todo=execute&nwp_action='. $search_table .'&nwp_todo='. $search_todo . $params;
				$act2 = 'action='.$plugin .'&todo=execute&nwp_action='. $search_table .'&nwp_todo=';
			}
		?>
		<form class="activate-ajax refresh-form" method="post" id="search" action="?<?php echo $act; ?>">
		
		<?php if( isset( $show_process_reference ) && $show_process_reference ){ ?>
		<input type="hidden" value="<?php if( isset( $data[ 'process_reference' ] ) )echo $data[ 'process_reference' ]; ?>" name="process_reference" />
		<?php } ?>
		
		<?php if( isset( $data[ 'reference' ] ) && isset( $data[ 'reference_value' ] ) && $data[ 'reference_value' ] ){ $reference = $data[ 'reference_value' ]; ?>
		<input type="hidden" value="<?php echo $data[ 'reference_value' ]; ?>" name="<?php echo $data[ 'reference' ]; ?>" />
		<?php } ?>
		
		<?php 
			if( isset( $data[ 'hidden_table_fields' ] ) && is_array( $data[ 'hidden_table_fields' ] ) && ! empty( $data[ 'hidden_table_fields' ] ) ){
				foreach( $data[ 'hidden_table_fields' ] as $k => $v ){
					?><input type="hidden" value="<?php echo $v; ?>" name="hidden_field[<?php echo $k; ?>]" /><?php
				}
			}
		?>
		
		<div class="input-group"> 
			<?php if( isset( $data[ 'table_fields' ] ) && is_array( $data[ 'table_fields' ] ) ){  ?>
		 <select class="form-control" id="search-field" name="field">
			<?php 
				if( $show_last_num_records_count ){
				?>
				<option value="_most_recent" data-form_field="number" data-default_value="<?php echo $show_last_num_records_count; ?>">Show Most Recent Records</option>
				<?php
				}
				
				foreach( $data[ 'table_fields' ] as $k => $v ){
					
					?><option value="<?php echo $k; ?>" data-form_field="<?php if( isset( $data[ 'table_label' ][ $v ][ "field_label" ] ) )echo $data[ 'table_label' ][ $v ][ "form_field" ]; ?>" data-form_field_options="<?php if( isset( $data[ 'table_label' ][ $v ][ "form_field_options" ] ) )echo $data[ 'table_label' ][ $v ][ "form_field_options" ]; ?>" <?php if( isset( $data[ 'table_label' ][ $v ][ "attributes" ] ) )echo $data[ 'table_label' ][ $v ][ "attributes" ]; ?>><?php if( isset( $data[ 'table_label' ][ $v ][ "field_label" ] ) )echo $data[ 'table_label' ][ $v ][ "field_label" ]; else echo $k; ?></option><?php
				}
			?>
		 </select>
			<?php } ?>
		 <span class="input-group-addon" style="<?php echo $style1; ?>">Search</span>
		 <input type="text" class="form-control form_field_element text-field" name="search" placeholder="Search Value" />
		 <input type="number" class="form-control form_field_element number-field" min="0" step="1" name="search_number" placeholder="Enter Number" />
		 
		 <input type="text" class="form-control form_field_element select-field select2" action="?<?php echo $act2; ?>get_select2_option" style="display:none; width:250px;" name="search_select" placeholder="Search Value" />
		 
		 <input type="date" class="form-control form_field_element date-5-field" style="display:none;" name="start_date" value="<?php echo date("Y-m-01"); ?>" />
		 <span class="input-group-addon form_field_element date-5-field" style="display:none; <?php echo $style1; ?>">To</span>
		 <input type="date" class="form-control form_field_element date-5-field" style="display:none;" name="end_date" />
		 
		 <span class="input-group-btn">
			<button class="btn dark" type="submit"><i class="icon-search"></i>&nbsp;</button>
		 </span>
		</div>
		
		</form><br />
	</div>
	
	<?php if( $show_buttons ){  ?>
	<div class="col-lg-4">
		<!--<button class="btn pull-right" onclick="nwFiaps.clientPrint('.shopping-cart-table');" type="button"><i class="icon-print"></i> Print</button>-->
		
		<?php if( $show_update_button ){ ?>
		<button class="btn dark pull-right" id="update-records" type="button" title="Refresh Email Addresses" data-button="update-records-button"><i class="icon-refresh"></i> Update</button>
		
		<button class="btn dark pull-right custom-single-selected-record-button" style="display:none;" id="update-records-button" action="?module=&<?php echo $act2; ?>update_app_manager&app_manager_control=1" type="button"><i class="icon-refresh"></i> Update</button>
		<?php } ?>
		
		<?php if( $show_delete_button ){ ?>
		<button class="btn dark pull-right" id="delete-records" type="button" data-button="delete-records-button"><i class="icon-trash"></i> Delete</button>
		
		<button class="btn dark pull-right custom-single-selected-record-button" style="display:none;" id="delete-records-button" action="?module=&<?php echo $act2; ?><?php echo $delete_todo; ?>&app_manager_control=1" type="button"><i class="icon-trash"></i> Delete</button>
		<?php } ?>
		
		<?php if( $show_new_button ){ ?>
		<button class="btn dark pull-right custom-single-selected-record-button" override-selected-record="<?php echo $reference; ?>" action="?module=&<?php echo $act2; ?><?php echo $new_todo; ?>&app_manager_control=1" type="button">New</button>
		<button class="btn dark pull-right custom-single-selected-record-button" override-selected-record="<?php echo $reference; ?>" action="?module=&<?php echo $act2; ?>refresh_cache&app_manager_control=1" type="button" title="Refresh Cache: Recreate Temporary List for rapid info retrieval"><i class="icon-refresh"></i>&nbsp;</button>
		<?php } ?>
		
		</form>
	</div>
	<?php } ?>
	</div>

</div>

<script type="text/javascript">
var g_site_url = "<?php echo $site_url; ?>";
var nwTableFieldsSearch = function () {
	return {
		recordItem: {
			id:"",
			name:"",
		},
		activeRefreshForm:'',
		url:g_site_url + "php/ajax_request_processing_script.php",
		init: function () {			
			$('#delete-records')
			.add('#update-records')
			.on('click', function(e){
				var ids = $('input.delete-checkbox:checked').map(function() {return this.value;}).get().join(',');
				
				if( ids ){
					var $butt = $("#delete-records-button");
					if( $(this).attr("data-button") ){
						var $butt = $( "#" + $(this).attr("data-button") );
					}
					
					$butt
					.attr("override-selected-record", ids )
					.click();
				}else{
					var data = {theme:'alert-info', err:'No Record Selected', msg:'You must select items by clicking on the checkboxes first', typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
				}
				
			});
			
			if( $("select#search-field") ){
				$("select#search-field")
				.on("change", function(){
					var $form = $(this).parents('form');
					var v = $(this).val();
					
					if( v ){
						var $f = $(this).children('option[value="'+v+'"]');
						var f = $f.attr("data-form_field");
						if( f ){
							
							if( $f.attr("action") ){
								f = 'select';
							}
							
							switch( f ){
							case "date-5":
								$form.find('.form_field_element').not('.date-5-field').hide();
								$form.find('.form_field_element.date-5-field').show();
							break;
							case "number":
								$form.find('.form_field_element').not('.number-field').hide();
								$form.find('.form_field_element.number-field').show();
							break;
							case "select":
								$form.find('.form_field_element').not('.select-field').hide();
								$form.find('input[name="search_select"]').val('');
								
								var minlength = 2;
								
								var action = $form.find('.form_field_element.select-field.select2.active').attr("action");
								if( $f.attr("action") ){
									action = $f.attr("action");
								}else{
									action = '?action=banks&todo=get_list_for_search&function=' + $f.attr("data-form_field_options");
									minlength = 0;
								}
								
								if( $f.attr("minimum_length") ){
									var ml = parseInt( $f.attr("minimum_length") );
									if( ! isNaN( ml ) )minlength = ml;
								}
								
								if( $form.find('.form_field_element.select-field.select2.active') ){
									$form
									.find('.form_field_element.select-field.select2.active')
									.select2("destroy")
								}
								
								$form.find('.form_field_element.select-field.select2')
								.select2({
									ajax: {
									url: nwTableFieldsSearch.url + action,
									dataType: 'json',
									delay: 250,
									type: "post",
									data: function ( term, page ) {
									  return { term: term, page : page, page_limit: 2 };
									},
									results: function (data, page) { // parse the results into the format expected by Select2.
										// since we are using custom formatting functions we do not need to alter remote JSON data
										return {
											results: data.items
										};
									},
									cache: true
									},
									minimumInputLength: minlength,
								});
								
								$form.find('.form_field_element.select-field').show();
							break;
							default:
								$form.find('.form_field_element.select-field.select2.active').val(0);
								$form.find('.form_field_element').not('.text-field').hide();
								$form.find('.form_field_element.text-field').show();
							break;
							}
							
							if( $f.attr("data-default_value") ){
								$form.find('.form_field_element:visible').val( $f.attr("data-default_value") );
							}
							
						}
					}
				}).change();
			}
		},
		search: function () {
			if( ! nwTableFieldsSearch.activeRefreshForm ){
				nwTableFieldsSearch.activeRefreshForm = "form#customer";
			}
		},
		refreshAcitveTab:function(){
			
		},
		closePopup: function(){
			nwTableFieldsSearch.refreshAcitveTab();
		},
		addComma: function( nStr ){
			nStr += '';
			x = nStr.split('.');
			x1 = x[0];
			x2 = x.length > 1 ? '.' + x[1] : '';
			var rgx = /(\d+)(\d{3})/;
			while (rgx.test(x1)) {
				x1 = x1.replace(rgx, '$1' + ',' + '$2');
			}
			return x1 + x2;
		}
	};
	
}();
</script>
</div>