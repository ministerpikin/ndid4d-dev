<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<?php include 'title-row.php'; ?>

	<div class="row">
		<div class="col-xl-6 col-md-6">
			
			<div class="row">
				<?php 
					$rkey = 'ray20u3365759271';
					$rdata = [];
					if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
						$rdata = $rd[ $rkey ];
					}
					$rdata[ 'color' ] = 'bg-primary';
					$rdata[ 'icon' ] = 'mdi-human-white-cane';
					echo $__prepareCard( $rdata, $rpd );

					$rkey = 'ray20u3365760421';
					$rdata = [];
					if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
						$rdata = $rd[ $rkey ];
					}
					$rdata[ 'icon' ] = 'mdi-human-male-female';
					echo $__prepareCard( $rdata, $rpd );

					$rkey = 'ray20u3365762011';
					$rdata = [];
					if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
						$rdata = $rd[ $rkey ];
					}
					$rdata[ 'icon' ] = 'mdi-human-wheelchair';
					echo $__prepareCard( $rdata, $rpd );

					$rkey = 'ray20u3365985431';
					$rdata = [];
					if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
						$rdata = $rd[ $rkey ];
					}
					echo $__prepareCard( $rdata, $rpd );
				?>
			</div>
		</div>
		
		<?php 
			$rkey = 'ray20u3365762931';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			echo $__prepareCard( $rdata, $rpd );
			
			$rkey = 'ray20u3365796161';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			echo $__prepareCard( $rdata, $rpd );

			$rkey = 'ray20u3365801201';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			echo $__prepareCard( $rdata, $rpd );
		?>
		<div class="row">
			
			<?php 
				$rkey = 'ray20u3722614811223403245';
				$rdata = [];
				if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
					$rdata = $rd[ $rkey ];
				}
				$rdata['col'] = 4;
				echo $__prepareCard( $rdata, $rpd );
				
				$rkey = 'ray20u37226405111609947514';
				$rdata = [];
				if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
					$rdata = $rd[ $rkey ];
				}
				$rdata['col'] = 4;
				echo $__prepareCard( $rdata, $rpd );

				$rkey = 'ray20u37226410711777109638';
				$rdata = [];
				if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
					$rdata = $rd[ $rkey ];
				}
				$rdata['col'] = 4;
				echo $__prepareCard( $rdata, $rpd );
			?>

		</div>
	</div>
</div>