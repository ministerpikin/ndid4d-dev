<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<style>
		.blinking-dot{
			height:8px;
			width:8px;
			border-radius: 45px;
			display: inline-block;
			margin-right: 8px;
		}
		.sth p:first{
			margin-bottom: 10px;
		}
		.blinking { animation: blink 1s infinite; } @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; }
	</style>
	<?php 
		if (isset( $data['endpoint'] ) && $data['endpoint']) { ?>
			<div class="row">
				<div class="col">
					<h4>Endpoint Calls </h4>
				</div>
			</div>
			<div class="">
				<div class="row">					
					<?php
						$h = '';
						foreach ( $data['endpoint'] as $edvalue) {
							$bg = '';
							$h .= '<div class="col-md-4">';
								$lc = '';
								$cls = 'warning';
								$bg = 'border-';
								if (isset( $edvalue['status'] ) && $edvalue['status']) {
									switch ($edvalue['status']) {
										case 'active':
											$cls = 'success';
										break;
										case 'in_progress':
											$cls = 'warning';
										break;
										default:
											$cls = 'danger';
											$lc = 'blinking';
										break;
									}
								}
								$cls1 = $bg . $cls;
								$h .= '<div class="border sth card p-3 '. $cls1 .'">';
									$h .= '<h5><b>'. get_name_of_referenced_record( array( 'id' => $edvalue['id'], 'table' => 'endpoint' ) ) .'</b></h5>';
									$h .= '<p><span class="blinking-dot '. $lc .' bg-'. $cls .'"></span><b>'. ucfirst( str_replace(["-", "_"], " ", $edvalue['status'] ) ) .'</b></p>';
									$h .= '<p><b>Last Check:</b> '. date("d-M-Y H:i:s", $edvalue['date'] ) .'</p>';
								$h .= '</div>';
							$h .= '</div>';
						}
						echo $h;
					?>
				</div>
			</div>
			<!-- <br /> -->
			<hr>
			<?php 
		}
	?>
	<div class="row">
		<div class="col">
			<h4><b>Background Tasks</b></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<?php
				$cnd = isset( $data['audit_retention_cache'] ) ? $data['audit_retention_cache'] : 0;
				$stat = $cnd ? "Active" : 'Inactive';
				$time = $cnd ? date('d-M-Y H:i:s', $cnd) : '<b>Never</b>';
				$cls = $cnd ? 'success' : 'danger';
			?>
			<div class="card p-3 border border-<?php echo $cls;?>">
				<h5><b>Audit Trail Maintenance Service</b></h5>
				<p><span class="blinking-dot blinking bg-<?php echo $cls; ?>"></span><?php echo $stat; ?></p>
				<p>Last Run: <?php echo $time; ?></p>
			</div>
		</div>
		<div class="col-md-4">
			<?php
				$cnd = isset( $data['next_clear_endpoint_trail'] ) ? $data['next_clear_endpoint_trail'] : 0;
				$stat = $cnd ? "Active" : 'Inactive';
				$time = $cnd ? date('d-M-Y H:i:s', $cnd) : '<b>Never</b>';
				$cls = $cnd ? 'success' : 'danger';
			?>
			<div class="card p-3 border border-<?php echo $cls;?>">
				<h5><b>Log Maintenance Service</b></h5>
				<p><span class="blinking-dot blinking bg-<?php echo $cls; ?>"></span><?php echo $stat; ?></p>
				<p>Last Run: <?php echo $time; ?></p>
			</div>
		</div>
		<div class="col-md-4">
			<?php
				$cnd = isset( $data['next_clear_endpoint_sr_trail'] ) ? $data['next_clear_endpoint_sr_trail'] : 0;
				$stat = $cnd ? "Active" : 'Inactive';
				$time = $cnd ? date('d-M-Y H:i:s', $cnd) : '<b>Never</b>';
				$cls = $cnd ? 'success' : 'danger';
			?>
			<div class="card p-3 border border-<?php echo $cls;?>">
				<h5><b>Log Backup Maintenance Service</b></h5>
				<p><span class="blinking-dot blinking bg-<?php echo $cls; ?>"></span><?php echo $stat; ?></p>
				<p>Last Run: <?php echo $time; ?></p>
			</div>
		</div>
		<div class="col-md-4">
			<?php
				$cnd = isset( $data['next_clear_logger_trail'] ) ? $data['next_clear_logger_trail'] : 0;
				$stat = $cnd ? "Active" : 'Inactive';
				$time = $cnd ? date('d-M-Y H:i:s', $cnd) : '<b>Never</b>';
				$cls = $cnd ? 'success' : 'danger';
			?>
			<div class="card p-3 border border-<?php echo $cls;?>">
				<h5><b>Data Load Log Maintenance Service</b></h5>
				<p><span class="blinking-dot blinking bg-<?php echo $cls; ?>"></span><?php echo $stat; ?></p>
				<p>Last Run: <?php echo $time; ?></p>
			</div>
		</div>
		<div class="col-md-4">
			<?php
				$cnd = isset( $data['report_auto_generation'][0]['last_sent'] ) ? $data['report_auto_generation'][0]['last_sent'] : 0;
				$stat = $cnd ? "Active" : 'Inactive';
				$time = $cnd ? date('d-M-Y H:i:s', $cnd) : '<b>Never</b>';
				$cls = $cnd ? 'success' : 'danger';
			?>
			<div class="card p-3 border border-<?php echo $cls;?>">
				<h5><b>Report Autogeneration & Reporting Service</b></h5>
				<p><span class="blinking-dot blinking bg-<?php echo $cls; ?>"></span><?php echo $stat; ?></p>
				<p>Last Mail Sent: <?php echo $time; ?></p>
			</div>
		</div>
	</div>
</div>