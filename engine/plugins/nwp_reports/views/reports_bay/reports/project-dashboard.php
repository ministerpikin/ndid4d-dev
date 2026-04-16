<?php 
	$hide_filter = 1;
	include 'title-row.php'; 
?>

<div class="row">
	<div class="col-xl-6 col-md-6">
		
		<div class="row">
			<?php 
			$rkey = 'ray20u3366103111';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			$rdata[ 'color' ] = 'bg-primary';
			$rdata[ 'icon' ] = 'mdi-human-white-cane';
			echo $__prepareCard( $rdata, $rpd );
			?>
			
			<?php 
			$rkey = 'ray20u3370248681';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			$rdata[ 'icon' ] = 'mdi-human-male-female';
			echo $__prepareCard( $rdata, $rpd );
			?>
			
			<?php 
			$rkey = 'ray26u3399341221140939385';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			$rdata[ 'icon' ] = 'mdi-human-male-female';
			echo $__prepareCard( $rdata, $rpd );
			?>
			
			<?php 
			$rkey = 'ray26u3399624401963948550';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			$rdata[ 'icon' ] = 'mdi-human-male-female';
			echo $__prepareCard( $rdata, $rpd );
			?>
			

		</div>
	</div>
	
	<?php 
	$rkey = 'ray20u3366101911';
	$rdata = [];
	if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
		$rdata = $rd[ $rkey ];
	}
	echo $__prepareCard( $rdata, $rpd );
	?>
	
	
	
	<?php 
	$rkey = 'ray20u3366105421';
	$rdata = [];
	if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
		$rdata = $rd[ $rkey ];
	}
	$rdata[ 'col' ] = 6;
	echo $__prepareCard( $rdata, $rpd );
	?>
	
	
	<?php 
	$rkey = 'ray26u33997291612015832311';
	$rdata = [];
	if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
		$rdata = $rd[ $rkey ];
	}
	$rdata[ 'col' ] = 6;
	echo $__prepareCard( $rdata, $rpd );
	?>
	
	<?php 
	$rkey = 'ray26u3399649501306015449';
	$rdata = [];
	if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
		$rdata = $rd[ $rkey ];
	}
	$rdata[ 'col' ] = 12;
	echo $__prepareCard( $rdata, $rpd );
	?>
	
</div>
