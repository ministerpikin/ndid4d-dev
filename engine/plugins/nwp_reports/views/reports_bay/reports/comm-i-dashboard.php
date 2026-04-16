<?php 
	$hide_filter = 1;
	include 'title-row.php'; 
	// echo "<pre>";print_r($data);echo "</pre>";
?>

<div class="row">
	<div class="col-xl-6 col-md-6">
		
		<div class="row">
			<?php 
			$rkey = 'ray20u34978654711839494267';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			$rdata[ 'color' ] = 'bg-primary';
			$rdata[ 'icon' ] = 'mdi-human-white-cane';
			echo $__prepareCard( $rdata, $rpd );
			?>
			
			<?php 
			$rkey = 'ray20u3497866001632857778';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			$rdata[ 'icon' ] = 'mdi-human-male-female';
			echo $__prepareCard( $rdata, $rpd );
			?>
			
			<?php 
			$rkey = 'ray20u3497866641932580866';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			$rdata[ 'icon' ] = 'mdi-human-wheelchair';
			echo $__prepareCard( $rdata, $rpd );
			?>
			
			<?php 
			$rkey = 'ray20u3497891201521603358';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			echo $__prepareCard( $rdata, $rpd );
			?>
		</div>
	</div>
	
	<?php 
	$rkey = 'ray20u34978922211530290716';
	$rdata = [];
	if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
		$rdata = $rd[ $rkey ];
	}
	echo $__prepareCard( $rdata, $rpd );
	?>
	

</div>