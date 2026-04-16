var benefitProgram = {
	data: {},
	data_source: '',
	queryStats: '',
	stage: 0,
	init: function(){
		
	},
	queryActions: function( action ){
		if( action ){

			var data = benefitProgram.data ? JSON.stringify( benefitProgram.data ) : '';

			$.fn.cProcessForm.function_click_process = 1;
			$.fn.cProcessForm.ajax_data = {
				ajax_data: { table: action, dataSource: benefitProgram.data_source },
				form_method: 'post',
				ajax_data_type: 'json',
				ajax_action: 'request_function_output',
				ajax_container: '',
				ajax_get_url: "?action=search&todo=get_search_query&html_replacement_selector=view-records",
			};
			$.fn.cProcessForm.ajax_send();
		}
	},
	viewResult: function(){
		if( typeof theme_version != 'undefined' ){
			switch( theme_version ){
			case 'v3':
				var firstTabEl = document.querySelector('#tab-download-csv')
				var firstTab = new bootstrap.Tab(firstTabEl)

				firstTab.show()
			break;
			default:
				$( '#tab-query-result' ).click();
			break;
			}
		}
	
		benefitProgram.stage = 2;
	},
	viewRecords: function(){
		$( '#tab-view-records' ).click();
	},
	viewDownloads: function(){
		if( typeof theme_version != 'undefined' ){
			switch( theme_version ){
			case 'v3':
				var firstTabEl = document.querySelector('#tab-download-csv')
				var firstTab = new bootstrap.Tab(firstTabEl)

				firstTab.show()
			break;
			default:
				$( '#tab-download-csv' ).click();
			break;
			}
		}
	
		benefitProgram.stage = 2;
	},
	createMine: function(){
		/* if( benefitProgram.data && benefitProgram.queryStats ){
			benefitProgram.data[ 'query_stats' ] = benefitProgram.queryStats;
			$( 'form#mines-form' ).find( 'textarea[name="data"]' ).val( JSON.stringify( benefitProgram.data ) );
		}
		$( 'form#mines-form' ).find( 'input[name="dataSource"]' ).val( benefitProgram.data_source ); */
	},
	downloadCSV: function(){
		$( '#tab-download-csv' ).click();
	},
};
benefitProgram.init();