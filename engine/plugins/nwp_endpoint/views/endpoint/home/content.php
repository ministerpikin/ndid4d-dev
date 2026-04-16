<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
	<style>
		#frame-view{
			height: 1100px;
		}
	</style>
	<?php 
		// echo '<pre>';print_r( $data );echo '</pre>';
		$iframe = isset($data['iframe']) && $data['iframe'] ? $data['iframe'] : 0;
		if( isset($data['r']) && $data['r'] ){
			echo '<pre>';print_r( $data['r'] );echo '</pre>';
		}

		if( $iframe ){
			$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
			$host = $_SERVER['HTTP_HOST'];
			$uri = $_SERVER['REQUEST_URI'];

			$uri = explode('?', $uri);
			$uri = $uri[0];

			$url = $protocol . $host . $uri;
			?>
			<iframe id="frame-view" width="100%" src="<?php echo $url .'?'. $iframe ?>"></iframe>
		<?php }
	?>
</div>