<div style="position:absolute; left:16px; z-index:10;">
<?php	//echo '<pre>';print_r( $data );echo '</pre>';
$table = isset( $data[ 'table' ] ) ? $data[ 'table' ] : 'users';
$todo = isset( $data[ 'todo' ] ) ? $data[ 'todo' ] : 'save_captured_image';
?>
<a href="#" class="btn dark btn-sm pull-right" id="close-image-capture" onclick="$.fn.cProcessForm.closeImageCapture( '?action=<?php echo $table; ?>&todo=<?php echo $todo; ?>&id=<?php if( isset( $data["id"] ) )echo $data["id"]; ?>' ); return false;">Close</a>
<iframe src="<?php $pr = get_project_data(); echo $pr["domain_name"]; ?>html-files/templates-1/items/image-capture.html" style="border:none; /*width:192px; height:180px;*/ width:326px; height:274px; border: 3px solid #555; overflow:hidden;"></iframe>
</div>