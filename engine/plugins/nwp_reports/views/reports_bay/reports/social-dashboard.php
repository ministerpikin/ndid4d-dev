<?php include 'title-row.php'; ?>

<div class="row">
	<div class="col-xl-6 col-md-6">
		
		<div class="row">
			<?php 
			$rkey = 'ray20u3365682151';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			$rdata[ 'color' ] = 'bg-primary';
			$rdata[ 'icon' ] = 'mdi-human-white-cane';
			echo $__prepareCard( $rdata, $rpd );
			?>
			
			<?php 
			$rkey = 'ray20u3365683021';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			$rdata[ 'icon' ] = 'mdi-human-male-female';
			echo $__prepareCard( $rdata, $rpd );
			?>
			
			<?php 
			$rkey = 'ray20u3365684501';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			$rdata[ 'icon' ] = 'mdi-human-wheelchair';
			echo $__prepareCard( $rdata, $rpd );
			?>
			
			<?php 
			$rkey = 'ray20u3365722181';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			echo $__prepareCard( $rdata, $rpd );
			?>
		</div>
	</div>
	
	<?php 
	$rkey = 'ray20u3365697091';
	$rdata = [];
	if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
		$rdata = $rd[ $rkey ];
	}
	echo $__prepareCard( $rdata, $rpd );
	?>
	
	
	<div class="row">
		<?php 
			$rkey = 'ray20u3497829421973933796';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
				$rdata['col'] = 4;
				$rdata['color'] = 'bg-success';
				$rdata[ 'icon' ] = 'mdi-account-arrow-right';
			}
			echo $__prepareCard( $rdata, $rpd );
		?>		
		<?php 
			$rkey = 'ray20u3497830191732020106';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
				$rdata['col'] = 4;
			}
			echo $__prepareCard( $rdata, $rpd );
		?>		
		<?php 
			$rkey = 'ray20u34978319411213205043';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
				$rdata['col'] = 4;
				$rdata['color'] = 'bg-primary';
				$rdata[ 'icon' ] = 'mdi-wheel-barrow';
			}
			echo $__prepareCard( $rdata, $rpd );
		?>		
	</div>
	
	<?php 
	$rkey = 'ray20u3365712891';
	$rdata = [];
	if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
		$rdata = $rd[ $rkey ];
	}
	echo $__prepareCard( $rdata, $rpd );
	?>
	
	
	<?php 
	$rkey = 'ray20u3365736271';
	$rdata = [];
	if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
		$rdata = $rd[ $rkey ];
	}
	echo $__prepareCard( $rdata, $rpd );
	?>
	
	
	
	

</div>