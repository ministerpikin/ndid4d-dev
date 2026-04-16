<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>

<?php if( isset( $data["items"] ) && ! empty( $data["items"] ) && is_array( $data["items"] ) ){ ?>
<textarea style="width:100%; min-height:400px; border:1px solid #ddd; background:#000; color:#fff; font-size:15px;">
	<?php foreach( $data["items"] as $sval ){ ?>
		
		<div class="col-sm-2 col-md-1 border-right" style="text-align:center;">
			
			<a href="#" class="icon-btn top-bar-icon custom-action-button" href="#" id="<?php echo $sval["id"]; ?>" function-id="<?php echo $sval["id"]; ?>" function-class="<?php echo $sval["table_name"]; ?>" function-name="display_all_records_full_view" module-id="1412705497" module-name="Main Menu" title="Click Here to Manage <?php echo $sval["table_label"]; ?>">
				
				<i class="icon-book"></i>
				<div><?php echo $sval["table_label"]; ?></div>
				
			</a>
			
		</div>
		
	<?php } ?>
</textarea>
<?php } ?>

</div>