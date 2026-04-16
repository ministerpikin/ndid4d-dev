<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<?php //echo '<pre>';print_r( $data );echo '</pre>'; ?>

<div class="row">
	<div class="col-md-6 col-md-offset-3 well"  style="background:#fff;">
		<div class="note note-success">
			<p>Successfully Saved!</p>
		</div>
		<h4><strong><?php if( isset( $data["name"] ) )echo $data["name"]; ?></strong></h4><hr />
		<?php if( isset( $data["form"] ) )echo $data["form"]; ?>
	</div>
</div>

</div>