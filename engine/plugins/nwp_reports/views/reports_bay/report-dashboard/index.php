<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
$rd = isset( $data[ 'rdata' ] ) && is_array( $data[ 'rdata' ] ) ? $data[ 'rdata' ] : array();
$add_url = isset( $data[ 'add_url' ] ) ? $data[ 'add_url' ] : '';
// $rd = array( $rd[0] );
// echo '<pre>';print_r( $data );echo '</pre>'; 

if( ! empty( $rd ) ){ ?>
	<div class="row">
	<?php 
		$col = 4;
		$html = '';
		$sn = 0;
		foreach( $rd as $rr ){
			$btn1 = '';
			$btn2 = '';

			if( ! ( $sn%3 ) ){
				$html .= '</div><br><div class="row">';
			}

			$html .= '<div class="col-md-'. $col .'">';

			switch( $rr[ 'type' ] ){
			case 'card':
				
				$html .= '<div class="card mb-3 widget-content card-shadow-primary border border-primaryx" id="'. $rr[ 'id' ] .'-card">';
					$html .= '<div class="widget-content-outer">';
						$html .= '<div class="widget-content-wrapper">';
							$html .= '<div class="widget-content-left">';
								$html .= '<div class="widget-heading" styleX="min-height:50px;">'. $rr["name"] .'</div>';
								$html .= '<div class="widget-subheading" >'. ( $rr[ 'title' ] ? $rr[ 'title' ]:'&nbsp;' ) .'</div>';
							$html .= '</div>';
							$html .= '<div class="widget-content-right">';
								$html .= '<div class="widget-numbers text-success"></div>';
							$html .= '</div>';
						$html .= '</div>';
					
					$html .= '</div>';
				$html .= '</div>';

			break;
			case 'pie':
			case 'bar':
				
				$html .= '
					<div class="rm-border card-header">
						<div>
							<h6 class="menu-header-title text-capitalize text-primary">'. ( $rr[ 'name' ] ? $rr[ 'name' ]:'&nbsp;' ) .'</h6>
						</div>';
						if( $btn1 || $btn2 ){
							$html .= '<div class="btn-actions-pane-right pull-right">';
							if( $btn1 ){
								$html .= '<div role="group" class="btn-group-sm btn-group">'. $btn1 .'</div>';
							}
							$html .= '</div>';
						}
				$html .= '</div>
						<div class="pl-3 pr-3 pb-2" id="'. $rr[ 'id' ].'-container" style="background:white;">
							<canvas id="'. $rr[ 'id' ] .'-canvas"  width="400" height="400"></canvas>
						</div>';

			break;
			}

			$html .= '</div>';
			$sn++;
		}

		echo $html;
	?>
	</div>
<?php }else{ ?>
	<div class="note note-warning"><h4><strong>No Report Data</strong></h4>Please save some reports</div>
<?php }
?>
<script type="text/javascript" >
	var dd = <?php echo json_encode( $rd ); ?>;
	var add_url = '<?php echo $add_url; ?>';
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>