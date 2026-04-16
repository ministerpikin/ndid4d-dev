var nwCustomers = function () {
	return {
		recordItem: {
			id:"",
			name:"",
		},
		activeRefreshForm:'',
		//url:g_site_url + "php/ajax_request_processing_script.php",
		init: function () {
			
			$('#delete-records')
			.on('click', function(e){
				var ids = $('input.delete-checkbox:checked').map(function() {return this.value;}).get().join(',');
				
				if( ids ){
					$("#delete-records-button")
					.attr("override-selected-record", ids )
					.click();
				}else{
					var data = {theme:'alert-info', err:'No Record Selected', msg:'You must select items by clicking on the checkboxes first', typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
				}
				
			});
			
		},
		search: function () {
			if( ! nwCustomers.activeRefreshForm ){
				nwCustomers.activeRefreshForm = "form#customer";
			}
			
			$( nwCustomers.activeRefreshForm ).submit();
		},
		refreshAcitveTab:function(){
			
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
		}
	};
	
}();
nwCustomers.init();