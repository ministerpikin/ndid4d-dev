<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<iframe id="form-frame" style="border:none; width:100%; height:100vh;"></iframe>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
$action = isset( $data[ 'action' ] ) ? $data[ 'action' ] : '';
$url = isset( $data[ 'url' ] ) ? $data[ 'url' ] : '';
$form_params = isset( $data[ 'form_params' ] ) ? $data[ 'form_params' ] : '';
$post = isset( $data[ 'post' ] ) ? json_encode( $data[ 'post' ] ) : '';

$form_data = isset( $data[ 'form_data' ] ) ? $data[ 'form_data' ] : [];

$h = '<div class="row">';
$h .= '<div class="col-md-offset-2 col-md-8">';
$h .= '<form class="activate-ajaxX" action="'. $url . $form_params .'&pretty_print=1" method="post" >';

	$h .= '<br>';

	$h .= '<label>Group</label>';
	$h .= '<input class="form-control" value="'. ( isset( $form_data[ 'group' ] ) ? $form_data[ 'group' ] : '' ) .'" name="group" />';
	$h .= '<br>';

	$h .= '<label>Title</label>';
	$h .= '<input class="form-control" value="'. ( isset( $form_data[ 'name' ] ) ? $form_data[ 'name' ] : '' ) .'" name="name" />';
	$h .= '<br>';

	$h .= '<label>Sub-Title</label>';
	$h .= '<input class="form-control" value="'. ( isset( $form_data[ 'title' ] ) ? $form_data[ 'title' ] : '' ) .'" name="title" />';
	$h .= '<br>';

	$h .= '<label>Report Key</label>';
	$h .= '<input class="form-control" value="'. ( isset( $form_data[ 'report_key' ] ) ? $form_data[ 'report_key' ] : '' ) .'" name="report_key" />';
	$h .= '<br>';

	$opt = '';
	if( function_exists( 'get_report_bay_type' ) ){
		foreach( get_report_bay_type() as $fk => $fv ){
			$sl = '';
			if( isset( $form_data[ 'type' ] ) && $form_data[ 'type' ] == $fk ){
				$sl = ' selected ';
			}
			$opt .= '<option value="'. $fk .' " '. $sl .'>'. $fv .'</option>';
		}
	}
	$h .= '<label>Type</label>';
	$h .= '<select class="form-control" name="type" >'. $opt .'</select>';
	$h .= '<br>';

	$opt = '';
	if( function_exists( 'get_report_bay_category' ) ){
		foreach( get_report_bay_category() as $fk => $fv ){
			$sl = '';
			if( isset( $form_data[ 'category' ] ) && $form_data[ 'category' ] == $fk ){
				$sl = ' selected ';
			}
			$opt .= '<option value="'. $fk .' " '. $sl .'>'. $fv .'</option>';
		}
	}
	$h .= '<label>Category</label>';
	$h .= '<select class="form-control" name="category" >'. $opt .'</select>';
	$h .= '<br>';

	$h .= '<label>Endpoint</label>';
	$h .= '<textarea readonly class="form-control" name="url" >'. $url .'</textarea>';
	$h .= '<br>';

	$h .= '<label>Data</label>';
	$h .= '<textarea readonly class="form-control" name="data">'. $post .'</textarea>';
	$h .= '<br>';
		
	$ins[] = '<label><input type="radio" required checked name="action" value="execute"> Test Only</label>';
	$ins[] = '<label><input type="radio" required name="action" value="save"> Save Only</label>';
	$h .= implode('&nbsp;&nbsp;&nbsp;&nbsp;', $ins ) . '<br /><br />';
		
	$h .= '<input class="btn blue" value="Test Endpoint &rarr;" type="submit"> ';
$h .= '</form>';

$h .= '</div>';
$h .= '</div>';

?>
<script type="text/javascript">
	setTimeout( function(){
		var header = '';
		$("link[href$='.css']").each(function(){
			header += '<link href="'+ $(this).attr('href') +'" rel="stylesheet" type="text/css" />';
		});
		
		$("iframe#form-frame")
		.contents()
		.find('html')
		.html( header + '<?php echo addslashes($h); ?>' );

		var submit = 0;
		$("iframe#form-frame")
		.contents()
		.find("#nwp-api-form").on("submit", function(){
			$(this).attr("action", $(this).find("#nwp-get-params").val() );
		});
	}, 300 );
	
</script>
</div>