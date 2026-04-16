var nwAll_reports = function () {
	return {
		recordItem: {
			id:"",
		},
		init: function () {
			
			$( 'form#select-report-type-form' )
			.find('input.on-change-submit')
			.change(function(){
				$( 'form#select-report-type-form' ).submit();
			});
			
		},
		showReport: function () {
			
			$( "a#tab2-handle" )
			.click();
			
		},
	};
	
}();
nwAll_reports.init();