<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php
$d = '';
$tb = isset( $data[ 'data' ][ 'table_id' ] ) ? $data[ 'data' ][ 'table_id' ] : '';
$tb_name = isset( $data[ 'data' ][ 'table_name' ] ) ? $data[ 'data' ][ 'table_name' ] : '';
$record_id = isset( $data[ 'record_id' ] ) ? $data[ 'record_id' ] : '';
if( isset( $data[ 'data' ][ 'data' ] ) && $data[ 'data' ][ 'data' ] ){
	$d = $data[ 'data' ][ 'data' ];
}
?>
<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<?php 
		?>
		<br />
		<form id="client-form" action="?action=api_definition&todo=save_new-api-definition" class="activate-ajax">
			<input type="hidden" name="id" value="<?php echo $record_id ?>" />
			<input type="hidden" name="table_name" value="<?php echo $tb_name ?>" />
			<div>
				<label>Table Name<sup>*</sup></label>
				<input value="" class="form-control form-gen-element select2" type="text" name="table_id" action="?action=database_table&todo=get_available_database_tables" id="searchDatabaseTable"  required>
			</div>
			<br />
			<div id="field-selection"></div>
			<br />
			<input type="submit" value="Save Changes" class="btn blue" />
			
		</form>

		<br />
		
	</div>
	
</div>

<script type="text/javascript" class="auto-remove">
var data = '<?php echo $d ? $d : "{}" ?>';
var tbName = '<?php echo $tb_name ? $tb_name : "" ?>';
var tbID = '<?php echo $tb ? $tb : "" ?>';
var apiDefinition = function () {
	return {
		data: JSON.parse( data ),
		tableName: tbName,
		tableID: tbID,
		first: 1,
		init: function () {

			$( 'summarsave_download_csvy' ).click();
			$("form#client-form").on( 'change', '#searchDatabaseTable', function(){
				apiDefinition.request( $(this).val() );
			});
			$("form#client-form").on( 'change', '#all', function(){
				
				var s = $(this).attr('data');
				var a = $(this).is( ':checked' );
				switch( a ){
				case true:
					$("input." + s )
					.prop("checked", true );
				break;
				case false:
					$("input." + s )
					.prop("checked", false );
				break;
				}
				
			});
			if( apiDefinition.data && ! $.isEmptyObject( apiDefinition.data ) ){
				console.log( apiDefinition.data );
				$( '#searchDatabaseTable' ).val( apiDefinition.tableID );
				apiDefinition.request( apiDefinition.tableID );
			}
		},
		request: function( d ){
			$.fn.cProcessForm.ajax_data = {
				ajax_data: { tableID: d, callback: 'apiDefinition.prepareSelect' },
				form_method: 'post',
				ajax_data_type: 'json',
				ajax_action: 'request_function_output',
				ajax_container: '',
				ajax_get_url: "?action=database_table&todo=get_form_fields&return_field=1",
			};
			$.fn.cProcessForm.ajax_send();
		},
		prepareSelect: function(){
			if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.data ){

				var d = $.fn.cProcessForm.returned_ajax_data.data;
				var table = $( '#searchDatabaseTable' ).select2( 'data' );
				if( table && table.name && ! apiDefinition.tableName ){
					$( 'input[name="table_name"]' ).val( table.name )
				}

				var ss = 1;
				var serial = 0;
				var html = '<details style="border:1px dashed; margin-bottom:20px; padding:10px;"><summary style="cursor:pointer;"><h4 style="display:inline;"><strong>'+ ( table ? table[ 'text' ] : apiDefinition.tableName ) +'</strong></h4></summary><br /><div class="row">';
				
				$.each( d, function( mname, sval ){
					
					if( serial && ! ( serial % 4 ) ){
						html += '</div><br /><div class="row">';
					}
					++serial;
					
					var input_name = 'data[data][]';
					if( ss ){
						html += '<div class="col-md-3">';
						html += '<label><input type="checkbox" id="all" data="'+ sval[ 'table' ] +'" />** ALL '+ sval[ 'table' ] +'**</label>';
						html += '</div>';
						ss = 0;
						++serial;
					}
					var sel = '';
					if( apiDefinition.data && apiDefinition.data && apiDefinition.data[ sval.field_identifier ] ){
						sel = ' checked="checked" ';
					}

					html += '<div class="col-md-3">';
					html += '<label><input type="checkbox" '+ sel +' class="'+ sval[ 'table' ] +'" name="'+ input_name +'" value="'+ sval["field_identifier"] +'" />'+ ( sval["abbreviation"] ? sval["abbreviation"] : ( sval[ 'display_field_label' ] ? sval[ 'display_field_label' ] : ( sval[ 'field_label' ] ? sval[ 'field_label' ] : '' ) ) ) +'</label>';
					html += '</div>';
					delete d[ mname ];
				});
				html += '</div></details><hr />';
				$( '#field-selection' ).html( html );
			}
		}
	};
	
}();
apiDefinition.init();
</script>

</div>