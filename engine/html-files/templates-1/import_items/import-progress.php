<!--IMPORT STARTED-->
<?php
if( isset( $data['import_progress'] ) && $data['import_progress'] ){
	krsort( $data['import_progress'] );
	
	foreach( $data['import_progress'] as $k => $v ){
?>
	<li><?php echo $v['title']; ?></li>
<?php		
	}
}
?>