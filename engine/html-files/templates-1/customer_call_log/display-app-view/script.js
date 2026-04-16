var nwCustomers = function () {
	return {
		recordItem: {
			id:"",
			name:"",
		},
		activeRefreshForm:'',
		//url:g_site_url + "php/ajax_request_processing_script.php",
		init: function () {			
			
			$('input.refresh-form')
			.add('select.refresh-form')
			.on('change', function(e){
				nwCustomers.activeRefreshForm = $(this).attr('refresh-form');
				
				$( nwCustomers.activeRefreshForm )
				.find(".populate-with-selected").attr( "override-selected-record", $(this).val() );
				//$("input.get-customer-id").val( $(this).val() );
				nwCustomers.search();
			});
			
			$('input.re-submit-form')
			.add('select.re-submit-form')
			.on('change', function(e){
				nwCustomers.activeRefreshForm = $(this).attr('refresh-form');
				nwCustomers.search();
			});
		},
		search: function () {
			if( ! nwCustomers.activeRefreshForm ){
				nwCustomers.activeRefreshForm = "form#customer";
			}
			
			$( nwCustomers.activeRefreshForm ).submit();
		},
		closePopup: function(){
			nwCustomers.refreshAcitveTab();
		},
		refreshAcitveTab: function(){
			if( $("form.refresh-form").is(":visible") ){
				$("form.refresh-form").submit();
			}
		},
		refreshCustomersList: function(){
			if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.data && $.fn.cProcessForm.returned_ajax_data.data.id ){
				 nwCustomers.recordItem = $.fn.cProcessForm.returned_ajax_data.data;
				 
				 $('input[name="customer"]').val( nwCustomers.recordItem.id );
				 nwCustomers.activateTabs2();
				 nwCustomers.search();
			}
		},
		addComma: function( nStr ){
			nStr += '';
			x = nStr.split('.');
			x1 = x[0];
			x2 = x.length > 1 ? '.' + x[1] : '';
			var rgx = /(\d+)(\d{3})/;
			while (rgx.test(x1)) {
				x1 = x1.replace(rgx, '$1' + ',' + '$2');
			}
			return x1 + x2;
		}
	};
	
}();
nwCustomers.init();

var nwCart = function () {
	return {
		refreshCustomersList: function(){
			
		}
	};
}();