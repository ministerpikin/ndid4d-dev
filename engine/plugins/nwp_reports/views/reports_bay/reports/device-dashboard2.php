<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<?php 
		include 'title-row.php'; 
	?>
	<div class="row">
		<div class="col-xl-6 col-md-6">
			<div class="row">
				<?php 
					$rkey = 'ray1036777247711325337495';
					$rdata = [];
					if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
						$rdata = $rd[ $rkey ];
					}
					$rdata[ 'color' ] = 'bg-warning';
					$rdata[ 'icon' ] = 'mdi-devices';
					echo $__prepareCard( $rdata, $rpd );
					 
					$rkey = 'ray936777247711325337495';
					$rdata = [];
					if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
						$rdata = $rd[ $rkey ];
					}
					$rdata[ 'icon' ] = 'mdi-human-male-female';
					echo $__prepareCard( $rdata, $rpd );
					 
					$rkey = 'ray636777247711325337495';
					$rdata = [];
					if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
						$rdata = $rd[ $rkey ];
					}
					$rdata[ 'icon' ] = 'mdi-human-male-female';
					echo $__prepareCard( $rdata, $rpd );
					 
					$rkey = 'ray536777247711325337495';
					$rdata = [];
					if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
						$rdata = $rd[ $rkey ];
					}
					$rdata[ 'color' ] = 'bg-dark';
					echo $__prepareCard( $rdata, $rpd );
				?>				
			</div>
		</div>
		<?php 
			$rkey = 'ray836777247711325337495';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			echo $__prepareCard( $rdata, $rpd );


		?>	
	</div>
	<div class="row">
		<?php
			$rkey = 'ray736777247711325337495';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			echo $__prepareCard( $rdata, $rpd );

		?>
		<div class="col-xl-6 col-md-6">
			<div class="row">
				
			<?php
				$rkey = 'ray336777247711325337495';
				$rdata = [];
				if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
					$rdata = $rd[ $rkey ];
				}
				$rdata['col'] = '12';
				$rdata[ 'color' ] = 'bg-info';
				$rdata[ 'icon' ] = 'mdi-devices';
				echo $__prepareCard( $rdata, $rpd );


				$rkey = 'ray236777247711325337495';
				$rdata = [];
				if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
					$rdata = $rd[ $rkey ];
					$rdata['col'] = '12';
				}
				$rdata[ 'color' ] = 'bg-danger';
				echo $__prepareCard( $rdata, $rpd );

			?>
			</div>
		</div>
	</div>

	<div class="row">
		<?php
			$rkey = 'ray436777247711325337495';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			echo $__prepareCard( $rdata, $rpd );

		?>

		<?php
			$rkey = 'ray136777247711325337495';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			echo $__prepareCard( $rdata, $rpd );

		?>
	</div>
</div>