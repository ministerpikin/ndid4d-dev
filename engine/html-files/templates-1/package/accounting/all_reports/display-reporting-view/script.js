$('a.pop-up-button').on('click', function(e){
	e.preventDefault();
});

$('.pop-up-button').popover({
	html:true,
	container:'#report-preview-container-id',
	content:function(){
		if( ! $(this).data("insert") ){
			var html = $(this).next('div.pop-up-content').html();
			$(this).data("insert", 1);
			return html;
		}
	},
});
