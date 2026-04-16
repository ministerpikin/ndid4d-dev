<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<?php 
		// $custom_filter_value = isset($data['custom_filter_value']) && $data['custom_filter_value'] ? $data['custom_filter_value'] : '';
		// $custom_filter = '<div class="fep-filter" style="margin-right: 10px;">
		// 					<input name="selected_fep" type="hidden" style="width:250px;" placeholder="Select a Valid FEP" action="?action=nwp_enrolment_data&todo=execute&nwp_action=enrolment_partner&nwp_todo=get_select2" minlength="0" class="form-control" value="'. $custom_filter_value .'" />
		// 				</div>';
		include 'title-row.php'; 
	?>
	<div class="row">
		<div class="col-xl-6 col-md-6">
			<div class="row">
				<?php 
					$rkey = 'ray20u34990239812022373656';
					$rdata = [];
					if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
						$rdata = $rd[ $rkey ];
					}
					$rdata[ 'color' ] = 'bg-primary';
					$rdata[ 'icon' ] = 'mdi-devices';
					echo $__prepareCard( $rdata, $rpd );
					 
					$rkey = 'ray20u34990264012109248254';
					$rdata = [];
					if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
						$rdata = $rd[ $rkey ];
					}
					$rdata[ 'icon' ] = 'mdi-human-male-female';
					echo $__prepareCard( $rdata, $rpd );
					 
					$rkey = 'ray20u34990296111035604471';
					$rdata = [];
					if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
						$rdata = $rd[ $rkey ];
					}
					$rdata[ 'icon' ] = 'mdi-human-male-female';
					echo $__prepareCard( $rdata, $rpd );
					 
					$rkey = 'ray20u34990303411883334431';
					$rdata = [];
					if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
						$rdata = $rd[ $rkey ];
					}
					echo $__prepareCard( $rdata, $rpd );
				?>				
			</div>
		</div>
		<?php 
			$rkey = 'ray20u34990373011689645041';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			echo $__prepareCard( $rdata, $rpd );


		?>	
	</div>
	<div class="row">
		<?php
			$rkey = 'ray20u34990388111009722486';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			echo $__prepareCard( $rdata, $rpd );

		?>
		<div class="col-xl-6 col-md-6">
			<div class="row">
				
			<?php
				$rkey = 'ray20u3499040611629357410';
				$rdata = [];
				if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
					$rdata = $rd[ $rkey ];
				}
				$rdata['col'] = '12';
				$rdata[ 'color' ] = 'bg-info';
				$rdata[ 'icon' ] = 'mdi-devices';
				echo $__prepareCard( $rdata, $rpd );


				$rkey = 'ray20u34990355211915527490';
				$rdata = [];
				if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
					$rdata = $rd[ $rkey ];
					$rdata['col'] = '12';
				}
				echo $__prepareCard( $rdata, $rpd );

			?>
			</div>
		</div>
	</div>

	<div class="row">
		<?php
			$rkey = 'ray20u3499039861131113442';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			echo $__prepareCard( $rdata, $rpd );

		?>

		<?php
			$rkey = 'ray20u3499037911117429330';
			$rdata = [];
			if( isset( $rd[ $rkey ] ) && $rd[ $rkey ] ){
				$rdata = $rd[ $rkey ];
			}
			echo $__prepareCard( $rdata, $rpd );

		?>		
	</div>
</div>