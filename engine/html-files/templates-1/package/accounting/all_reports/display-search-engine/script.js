var nwSearchEngine = function () {
	return {
		clearResults: function(){
			$('#search-result-container').html("");
		},
		init: function(){
			$('input[name="asset_barcode"]').focus();
			
			$('input[name="asset"]')
			.on('change', function(){
				$('form#search-engine-form').submit();
			});
			
			$('form#search-engine-form')
			.on('submit', function(){
				setTimeout( function(){
					$('form#search-engine-form').trigger("reset");
					
					$('form#search-engine-form').find(".select2").select2("val", "");
				
					$('input[name="asset_barcode"]').focus();
				}, 300 );
			});
		},
	};
	
}();
nwSearchEngine.init();