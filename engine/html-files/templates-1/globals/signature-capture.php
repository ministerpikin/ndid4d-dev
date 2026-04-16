<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php  
$build_query = isset( $data[ 'build_query' ] ) && $data[ 'build_query' ] ? $data[ 'build_query' ] : '';
$action = isset( $data[ 'action' ] ) && $data[ 'action' ] ? $data[ 'action' ] : '';
$todo = isset( $data[ 'todo' ] ) && $data[ 'todo' ] ? $data[ 'todo' ] : '';
$params = isset( $data[ 'params' ] ) && $data[ 'params' ] ? $data[ 'params' ] : '';
$change_form_action = isset( $data[ 'change_form_action' ] ) ? $data[ 'change_form_action' ] : '';
$plugin = isset( $data["plugin"] ) ? $data["plugin"] : '';

$container = isset( $data["container"] ) ? $data["container"] : '';

$action_to_perform = isset( $data[ 'action_to_perform' ] ) && $data[ 'action_to_perform' ] ? $data[ 'action_to_perform' ] : '';

if( $plugin ){
	$form_act = 'action='.$plugin.'&todo=execute&nwp_action='.$action.'&nwp_todo='.$todo;
}else{
	$form_act = 'action='.$action.'&todo='.$todo;
}
$form_act = '?'.$build_query.'&'.$form_act;

$pr = get_project_data(); 
if( defined("NWP_RESOURCE_PATH") && NWP_RESOURCE_PATH ){
	$pr["domain_name"] = NWP_RESOURCE_PATH . 'engine/';
}
	// echo '<pre>'; print_r( $mname ); echo '</pre>';
?>

<div styleX="position:absolute; z-index:10;">
<br /><a href="#" class="btn red btn-sm pull-rightX" onclick="$.fn.cProcessForm.getSignature({'action':'<?php echo $form_act; ?>', 'clear_container':1, 'input_container': '', 'input_fields' : '' }, '<?php echo isset( $container )?$container:''; ?>'); return false;">Close</a><br />
<iframe src="<?php echo $pr["domain_name"]; ?>html-files/templates-1/items/signature.html" style="border:none;  width:100%; min-width:440px; /* min-width:240px; max-width:240px; height:160px;*/ min-height:460px; border: 3px solid #555; overflow:hidden;"></iframe>
</div>
</div>