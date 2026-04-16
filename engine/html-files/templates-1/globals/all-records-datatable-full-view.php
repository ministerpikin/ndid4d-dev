<?php
	if( ! isset( $col_1 ) )$col_1 = 3;
	if( ! isset( $col_2 ) )$col_2 = 9;
	
	$theme = '';
	if( defined("HYELLA_THEME") ){
		$theme = HYELLA_THEME;
	}
	
?>
<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>

<?php
	switch( $theme ){
	case "v3":	//reports ui
		?>
		<div class="row">
			<div class="col-md-12">
				<?php include "line-items-datatable-view-v3-header.php"; ?>
			</div>
		</div>
		<?php
	break;
	}
?>

<div class="row">
	<?php if( ( isset( $data["no_col_1"] ) && $data["no_col_1"] ) ){ $col_2 = 12; }else{ ?>
    <div class="col-md-<?php echo $col_1; ?>">
		<?php include dirname( dirname( __FILE__ ) ) . "/globals/form-details-report-view.php"; ?>
	</div>
	<?php } ?>
    <div class="col-md-<?php echo $col_2; ?>"> 
		<?php
			switch( $theme ){
			case "v2":	//purple theme
				include dirname( dirname( __FILE__ ) ) . "/globals/line-items-datatable-view-v2.php";
			break;
			case "v3":	//reports ui
				include dirname( dirname( __FILE__ ) ) . "/globals/line-items-datatable-view-v3.php";
			break;
			default:
				include dirname( dirname( __FILE__ ) ) . "/globals/line-items-datatable-view.php";
			break;
			}
		?>
	</div>
</div>
</div>