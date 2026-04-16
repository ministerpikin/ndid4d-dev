<?php include 'title-row.php'; ?>

<div class="row">
	<div class="col-xl-6 col-md-6">
		
		<div class="row">
			<?php 
			$rkey = 'ray20u3364918501';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			$rdata[ 'color' ] = 'bg-primary';
			$rdata[ 'icon' ] = 'mdi-human-white-cane';
			echo $__prepareCard( $rdata, $rpd );
			?>
			
			<?php 
			$rkey = 'ray20u3364922341';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			$rdata[ 'icon' ] = 'mdi-human-male-female';
			echo $__prepareCard( $rdata, $rpd );
			?>
			
			<?php 
			$rkey = 'ray20u3364915511';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			$rdata[ 'icon' ] = 'mdi-human-wheelchair';
			echo $__prepareCard( $rdata, $rpd );
			?>
			
			<?php 
			$rkey = 'ray20u3365887441';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			echo $__prepareCard( $rdata, $rpd );
			?>
		</div>
	</div>
	
	<?php 
	$rkey = 'ray20u3364917181';
	$rdata = [];
	if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
		$rdata = $rd[ $rkey ];
	}
	echo $__prepareCard( $rdata, $rpd );
	?>
	
	
	
	<?php 
	$rkey = 'ray20u3364941341';
	$rdata = [];
	if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
		$rdata = $rd[ $rkey ];
	}
	echo $__prepareCard( $rdata, $rpd );
	?>
	
	
	
	<?php 
	$rkey = 'ray20u3365807841';
	$rdata = [];
	if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
		$rdata = $rd[ $rkey ];
	}
	echo $__prepareCard( $rdata, $rpd );
	?>
	
	
	

</div>