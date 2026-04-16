var nwCustomers = function () {
	return {
		recordItem: {
			id:"",
			name:"",
		},
		activeRefreshForm:'',
		url:g_site_url + "php/ajax_request_processing_script.php",
		init: function () {
			if( typeof( nwTableFieldsSearch ) == "object" ){
				nwTableFieldsSearch.init();
			}
		},
		search: function () {
			if( ! nwCustomers.activeRefreshForm ){
				nwCustomers.activeRefreshForm = "form#customer";
			}
			
			$( nwCustomers.activeRefreshForm ).submit();
		},
		loadForm:function(){
			
			//setTimeout( function(){
				if( $("form.refresh-form.primary-refresh-form") && $("form.refresh-form.primary-refresh-form").is(":visible") ){
					$("form.refresh-form.primary-refresh-form:visible").submit();
				}else{
					if( $("form.refresh-form") && $("form.refresh-form").is(":visible") ){
						$("form.refresh-form:visible").submit();
					}
				}
			//}, 1000 );
		},
		refreshAcitveTab:function(){
			$("ul.page-sidebar-menu")
			.find("li.active:visible")
			.click();
		},
		closePopup: function(){
			nwCustomers.refreshAcitveTab();
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
		},
		repopCustomer: function(){
			if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.id ){
				 nwCustomers.selected = $.fn.cProcessForm.returned_ajax_data.id;
				 
				 $(".repopulate-cid").attr( "override-selected-record", nwCustomers.selected );
			}
		},
	};
	
}();
//nwCustomers.init();