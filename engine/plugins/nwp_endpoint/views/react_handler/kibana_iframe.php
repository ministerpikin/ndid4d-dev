<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<?php
		$rhost = defined("KIBANA_HOST") && constant("KIBANA_HOST") ? constant("KIBANA_HOST") : '';
		$rport = defined("KIBANA_PORT") && constant("KIBANA_PORT") ? constant("KIBANA_PORT") : '';
		if ( $rhost && $rport ) {?>
			<iframe src="http://<?php echo $rhost . ":" . $rport; ?>/" style="width:100%; height:80vh; border:none; margin:0; padding:0; overflow:hidden;" frameborder="0"></iframe>
			<?php
		}else{ ?>
			<div class="note note-danger">
				<h4><b>Server Unavailable</b></h4>
				<p>Kibana credentials are currently unavailable</p>
				<p>Kindly reach out to the Technical Team</p>
			</div>
			<?php
		}
	?>
</div>