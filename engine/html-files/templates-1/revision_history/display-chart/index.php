<div <?php set_hyella_source_path( __FILE__, 1 ); ?> id="rev-history-con">
<?php 

$action_to_perform = isset( $data[ 'action_to_perform' ] ) ? $data[ 'action_to_perform' ] : '';
$type = isset( $data[ 'type' ] ) ? $data[ 'type' ] : '';
$title = isset( $data[ 'title' ] ) ? $data[ 'title' ] : '';

$start_date = isset( $data[ 'start_date' ] ) ? $data[ 'start_date' ] : '';
$end_date = isset( $data[ 'end_date' ] ) ? $data[ 'end_date' ] : '';

$d = isset( $data[ 'data' ] ) ? $data[ 'data' ] : array();
$total = array();

$table = isset( $data[ 'table' ] ) ? $data[ 'table' ] : '';

$today = date("U");
$colors = array( 'bg-success text-white', 'bg-primary text-white', 'bg-dark text-white', 'bg-warning', 'bg-danger text-white' );

$show_tabs = 0;
$sn = 0;
$class = 4;

$parent = '';
switch( $type ){
case 'department':
	if( isset( $data[ 'chart_data' ] ) && ! empty( $data[ 'chart_data' ] ) ){
		$show_tabs = 1;

		$total_wards = 0;
		$total_patients = 0;
	}
break;
}
// echo '<pre>';print_r( $data );echo '</pre>'; 
// echo '<pre>';print_r( $d );echo '</pre>'; 
// echo '<pre>';print_r( $total );echo '</pre>'; 
?>
    
	<form class="activate-ajax" method="post" action="?action=revision_history&todo=<?php echo $action_to_perform; ?>&html_replacement_selector=rev-history-con" style="position:relative;">
		<div class="row">
			<div class="col-md-2">
				<label>Start Date<!--  <sup>*</sup> --></label>
				<input type="datetime-local" name="start_date" class="form-control" value="<?php echo $start_date; ?>" />
			</div>
			<div class="col-md-2">
				<label>End Date<!--  <sup>*</sup> --></label>
				<input type="datetime-local" name="end_date" class="form-control" value="<?php echo $end_date; ?>" />
			</div>
			<div class="col-md-2">
				<label>User</label>
				<input class="form-control select2" minlength="0" name="user" action="?action=users&todo=get_select2" />
			</div>
			<div class="col-md-1">
				<label>&nbsp;</label>
				<input type="submit" value="Submit &rarr;" class="btn blue form-control" />
			</div>
		</div>
	</form>
	<br />
	<br />
	
	<div class="row" >
		<div class="col-md-12" id="rev-history-con-tb">
		<?php
		if( ! empty( $d ) ){
			$params = '?action='.$table;
			$params .= isset( $data[ 'action_to_perform' ] ) ? '&todo=' . $data[ 'action_to_perform' ] : '';
			$params .= isset( $data[ 'html_replacement_selector' ] ) ? '&html_replacement_selector=' . $data[ 'html_replacement_selector' ] : '';
			$params .= isset( $data[ 'title' ] ) ? '&title=' . $data[ 'title' ] : '';
			$params .= '&get_children=1';
			
			$typ = isset( $data[ 'type' ] ) ? '&gtype=' . $data[ 'type' ] : '';
			$params .= $typ;
			
			$id = isset( $data[ 'id' ] ) ? $data[ 'id' ] : '-';
			
		?>
			<div class="<?php echo $type; ?>">
				<?php
				$scolor = 0;
				$snn = 0;
				$skip = 0;
				$da = 0;
				echo '<div class="row">';
				foreach( $d as $dz => $dy ){
					$color = isset( $colors[ $scolor ] ) ? $colors[ $scolor ] : '';
					if( $scolor >= count( $colors ) ){
						$scolor = 0;
					}else{
						++$scolor;
					}
					$snn++;
					if( $snn == $class ){
						echo '</div><div class="row">';
					}
				?>
				<a href="#" class=" <?php echo 'col-md-'.$class . ( isset( $dy[ 'item_id' ] ) && $dy[ 'item_id' ] ? " custom-single-selected-record-button" : "" ) ?> " override-selected-record="<?php echo ( isset( $dy[ 'item_id' ] ) && $dy[ 'item_id' ] ? $dy[ 'item_id' ] : "-" ); ?>" action="?<?php echo str_replace(":::", "&", $dy[ 'id' ] ); ?>&html_replacement_selector=rev-history-con-tb" no-store="1">
					
					<div class="tile-body card">

						<div class="card-header <?php echo $color; ?>">
							<h4 class="<?php echo $color; ?>" style="text-align:center;"><strong><?php if( $dy[ 'text' ] )echo $dy[ 'text' ];else echo 'N/A'; ?></strong></h4>
							<?php echo isset( $dy[ 'icon' ] ) ? '&nbsp;<i class="' . $dy[ 'icon' ] . '"></i>' : ''; ?>
						</div>

						<!-- <div class="card-body" style="text-align: center; <?php //echo $color; ?>"></div> -->
					</div>
				</a>
				<?php 
				} 
				echo '</div>&nbsp;';
				?>
			</div>

		<?php }else{ ?>     
		<div class="note note-warning"><h4>No Entries</h4></div>
		<?php } ?>
		</div>
	</div>

</div>