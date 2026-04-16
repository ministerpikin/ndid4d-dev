<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 

$type = isset( $data[ 'type' ] ) ? $data[ 'type' ] : '';
$title = isset( $data[ 'title' ] ) ? $data[ 'title' ] : '';

$d = isset( $data[ 'data' ] ) ? $data[ 'data' ] : array();
$total = array();

$table = isset( $data[ 'table' ] ) ? $data[ 'table' ] : '';

$today = date("U");
$colors = array( 'bg-success text-white', 'bg-primary text-white', 'bg-dark', 'bg-warning', 'bg-danger text-white' );

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

if( ! empty( $d ) ){
	$params = '?action='.$table;
	$params .= isset( $data[ 'action_to_perform' ] ) ? '&todo=' . $data[ 'action_to_perform' ] : '';
	$params .= isset( $data[ 'html_replacement_selector' ] ) ? '&html_replacement_selector=' . $data[ 'html_replacement_selector' ] : '';
	$params .= isset( $data[ 'title' ] ) ? '&title=' . $data[ 'title' ] : '';
	$params .= '&get_children=1';
	$typ = isset( $data[ 'type' ] ) ? '&type=' . $data[ 'type' ] : '';
	$params .= $typ;

	$id = isset( $data[ 'id' ] ) ? $data[ 'id' ] : '-';
	
?>
    
	<div class="row">
		<div class="col-md-12" style="text-align: center;">
			<h4 style="text-align:center;display: inline-block;"><a href="#" title="Re-open Window" class="custom-single-selected-record-button" override-selected-record="<?php echo $id; ?>" action="<?php echo $params; ?>"><i class="icon-refresh"></i></a> <strong><?php echo $title; ?></strong></h4>
		</div>
	</div>
	<br />
	
	<div class="row">
		<div class="col-md-12">
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
				<div class=" <?php echo 'col-md-'.$class . ( isset( $dy[ 'item_id' ] ) && $dy[ 'item_id' ] ? " custom-single-selected-record-button" : "" ) ?> " override-selected-record="<?php echo ( isset( $dy[ 'item_id' ] ) && $dy[ 'item_id' ] ? $dy[ 'item_id' ] : "-" ); ?>" action="?<?php echo str_replace(":::", "&", $dy[ 'id' ] ); ?>" style="cursor: pointer;">
					
					<div class="tile-body">

						<div class="card-header <?php echo $color; ?>" style=" ">
							<h4 style="text-align:center;"><strong><?php echo $dy[ 'text' ]; ?></strong></h4>
							<?php echo isset( $dy[ 'icon' ] ) ? '&nbsp;<i class="' . $dy[ 'icon' ] . '"></i>' : ''; ?>
						</div>
						
						<div class="card-body" style="text-align: center; <?php echo $color; ?>">
						</div>
					</div>
				</div>
				<?php 
				} 
				echo '</div>&nbsp;';
				?>
			</div>

		</div>
	</div>

<?php } ?>     
<script type="text/javascript" >
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>