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

var nwFilesAttachFiles = function () {
	
    return {
        //main function to initiate the module
        init: function () {
			
        },
		destroy: function () {
			$('#zero-out-negative-budget')
			.modal('hide');
        }

    };

}();