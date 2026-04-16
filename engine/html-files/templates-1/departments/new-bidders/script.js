$('#zero-out-negative-budget')
.modal('show');

$('#zero-out-negative-budget')
.on('show.bs.modal', function(){
	
})
.on('hide.bs.modal', function(){
	$("#ajax-modal-container")
	.remove();
	
	$("body")
	.removeClass("modal-open");
});

//
$('select.select2-multi-files').select2({
	placeholder: "Files to Share",
	allowClear: true
});
$('select.select2-multi-recipients').select2({
	placeholder: "Recipients",
	allowClear: true
});