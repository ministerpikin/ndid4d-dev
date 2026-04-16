var nwSurveyticket = {
	data:{},
	preview: 0,
	init: function () {
		$('a#tab2-handle')
		.on("click", function(){
			
			if( nwSurveyticket.submittedDefaultValues ){
				nwSurveyticket.submittedDefaultValues = 0;
			}else{
				$('#tab-1').find('form').find('input[type="submit"]').click();
				
				return false;
			}
		});
	},
	submittedDefaultValues: 0,
	updateDefaultValues: function(){
		if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.data && $.fn.cProcessForm.returned_ajax_data.data.form_data ){
			var form_data = $.fn.cProcessForm.returned_ajax_data.data.form_data;
			
			if( form_data && ! $.isEmptyObject( form_data ) ){
				
				var dd = JSON.parse( $('textarea.default-values-textarea').val() );
				if( !( dd && ! $.isEmptyObject( dd ) ) ){
					dd = {};
				}
				
				dd["form_data"] = form_data;
				
				$('textarea.default-values-textarea').val( JSON.stringify( dd ) );
				
				nwSurveyticket.submittedDefaultValues = 1;
				
				$('a[href="#tab-2"]').click();
			}
		}
	},
};
nwSurveyticket.init();