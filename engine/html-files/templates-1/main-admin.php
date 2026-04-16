<!--DASHBOARD PAGE-->
<?php
    require_once $pagepointer."html-files/templates-1/main-setup-page.php";
?>
<div id="module-popup-menus-menu">
	<!-- Modal -->
	<?php 
		$modal_body = '<div id="popTextEditorContainer"><textarea id="popTextArea"></textarea></div><div id="temp-popTextArea" style="display:none;"></div>';
		$modal_title = "Full Text Editor";
		include dirname( __FILE__ ) . "/globals/modal-box-1.php"; 
	?>
</div>
<!--DASHBOARD PAGE-->