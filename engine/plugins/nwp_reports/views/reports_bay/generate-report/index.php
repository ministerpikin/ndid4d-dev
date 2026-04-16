<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php 
$db_tables = isset( $data[ "db_tables" ] )? $data[ "db_tables" ] :[];
$exclude = isset( $data[ "exclude_tables" ] )? $data[ "exclude_tables" ] :[];
$plugin = isset( $data[ "plugin" ] )? $data[ "plugin" ] : '';
$table = isset( $data[ "table" ] )? $data[ "table" ] : '';
$todo = isset( $data[ "todo" ] )? $data[ "todo" ] : '';
					// echo '<pre>';print_r( $db_tables );echo '</pre>'; 
if( ! empty( $db_tables ) ){

	$action = 'action='. $plugin .'&todo=execute&nwp_action='. $table .'&nwp_todo='.$todo;
?>

<h4>Generate Report</h4>

<div>
	<form id="select-data-source-form" class="activate-ajax" action="?<?php echo $action ?>&fields_container=fields-configure&html_replacement_selector=report-source-container">
		<input type="hidden" name="no_refresh_options" class="form-control">
		<div class="row">
			<div class="col-md-4">
				<label>Select Data Source</label>
				<?php 

					if( isset( $db_tables ) && ! empty( $db_tables ) ){
						$opt = '';
						foreach( $db_tables as $dbk1 => $dbv1 ){
							if( isset( $dbv1["classes"] ) && ! empty( $dbv1["classes"] ) ){
								$opt .= '<optgroup label="'. $dbv1["label"] .'">';
								foreach( $dbv1["classes"] as $dbk => $dbv ){
									
									$continue = 1;
									if( isset( $exclude[ $dbk ] ) && $exclude[ $dbk ] ){
										$continue = 0;
									}	

									if( $continue ){
										$opt .= '<option value="type='. $dbv1["type"] .':::key='. $dbk1 .':::value='. $dbk .':::hash='. get_file_hash( [ "hash" => 1, "file_id" => $dbk, "date_filter" => "d-M-Y" ] ) .':::text='. rawurlencode( $dbv ) .'">'. $dbv .'</option>';
									}
								}
								$opt .= '</optgroup>';
							}
						}
						echo '<select name="data_source" class="form-control select2" required><option></option>';
						echo $opt;
						echo '</select>';
					} 
				?>
			</div>
			
		</div>
	</form>
	<br>
	<div class="cardX">
		<div id="report-source-container" class="card-bodyX">
		</div>
	</div>
	<script type="text/javascript">
		var nwMedicalRecordsAPI = function () {
			return {
				init : function(){
					$.fn.cProcessForm.activateAjaxForm();
					$( 'form#select-data-source-form' ).find( 'select.form-control,input.form-control' ).off( 'change' ).on( 'change', function(){ 
						// console.log( $(this).val() )
						$( 'form#select-data-source-form' ).submit() 
					});
				}
			};
		}();
		nwMedicalRecordsAPI.init();
	</script>
</div>

<?php }else{ ?>
<div class="note note-danger"><h4>No Tables</h4>The functionality is not installed</div>
<?php } ?>
</div>
