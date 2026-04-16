<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php
if( isset( $data["id"] ) && $data["id"] && isset( $data["html"] ) && $data["html"] ){
	echo '<div class="search-engine-result" id="'.$data["id"].'">'.$data["html"].'</div>';
}
?>
</div>