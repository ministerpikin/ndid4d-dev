<script type="text/javascript">
	if( $('#zero-out-negative-budget') ){
		$('#zero-out-negative-budget')
		.modal('hide');	
	}
	
	if( $("body").hasClass('modal-open') ){
		$("body")
		.removeClass("modal-open");
	}
	
	if( $(".modal-backdrop").is(':visible') ){
		$(".modal-backdrop").remove();
	}
</script>