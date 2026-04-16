<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<style type="text/css">
	<?php if( file_exists( dirname( __FILE__ ).'/style.css' ) )include "style.css"; ?>
</style>
<?php 
	$pr = get_project_data();
	$site_url = $pr["domain_name"];
	
	$style = 'background: #A7E862;';
	$style1 = 'color:#777;';

	// echo "<pre>";print_r( $data );echo "</pre>";
	
	$notitlespace = isset( $data["no_title_space"] )?$data["no_title_space"]:0;
?>
<?php if( isset( $data["label"] ) && $data["label"] ){ ?>
<?php if( ! $notitlespace )echo '<br />'; ?>
<h4 style="text-align:center;" class="card-title-1"><strong><?php echo $data["label"]; ?></strong></h4>
<?php if( ! $notitlespace )echo '<br />'; ?>
<?php } ?>
<?php 
	if( isset( $data[ 'form' ] ) && $data[ 'form' ] ){
		echo $data[ 'form' ];
	}else{
		include dirname( dirname( dirname( __FILE__ ) ) ) . "/globals/app-manager-control.php"; 
	}
?>
<br />
<div id="<?php echo $data["table"]; ?>-record-search-result">
<?php if( isset( $data["list"] ) )echo $data["list"]; ?>
<?php //include "form.php"; ?>
</div>

<script type="text/javascript" class="auto-remove">
	var g_site_url = "<?php echo $site_url; ?>";
	<?php if( file_exists( dirname( __FILE__ ).'/script.js' ) )include "script.js"; ?>
</script>
</div>