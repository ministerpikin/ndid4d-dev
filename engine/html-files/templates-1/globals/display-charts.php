<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	.menu-header-title {
	    font-weight: 600 !important;
	    font-size: 1.25rem;
	    margin: 0;
	}
</style>
<?php 
// echo '<pre>';print_r($data);echo '</pre>'; 
$cols = 'col-md-12';
if( isset( $data[ 'col' ] ) && $data[ 'col' ] )$cols = 'col-md-'.$data[ 'col' ];
$err = 0;
$e = 0;
if( isset( $data[ 'charts' ] ) && ! empty( $data[ 'charts' ] ) ){ ?>
	<div class="row"> <?php
	foreach( $data[ 'charts' ] as $c ){
		if( isset( $c[ 'html' ] ) && $c[ 'html' ] ){ $e = 1;?>
			<div class="<?php echo $cols; ?>">
				<div class="mb-3 profile-responsive card">
					<ul class="list-group list-group-flush" style="margin-bottom: 0;">
					<?php echo $c[ 'html' ]; ?>
					</ul>
				</div>
			</div>
		<?php }else{
			$err = 1;
		}
	}
	?> </div> <?php
}else{
	$err = 1;
}

if( $err && ! $e ){
	echo '<div class="note note-warning">No Data Found for This Chart</div>';
}
?>
</div>