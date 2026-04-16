var nwMyexcel = function () {
	return {
		data:{
			tables:{},
		},
		action: ( action ? action : "" ),
		first: 1,
		init: function () {

			$( 'input.sel-all' ).on( 'change', function(){
				var tb = $(this).attr( 'table' );
				if( $(this).is(":checked") ){
					$( 'input[data-table="'+ tb +'"]' ).click();
					// $( 'input[data-table="'+ tb +'"]' ).prop("checked", true );
				}else{
					$( 'input[data-table="'+ tb +'"]' ).click();
					// $( 'input[data-table="'+ tb +'"]' ).prop("checked", false );
				}
			});
			
			var dj = JSON.parse( $("form#export-csv-form").find('textarea[name="data"]').val() );
			if( dj && ! $.isEmptyObject( dj ) ){
				nwMyexcel.data = dj;
			}
			
			nwMyexcel.updateData();
			
			$("form#client-form")
			.submit(function(e){
				e.preventDefault();
				
				nwMyexcel.updateData();

				// if( $(this).find( 'input[name="download_csv"]' ).is( ':checked' ) ){
				// 	$("form#export-csv-form").find( 'input[name="download_csv"]' ).prop( 'checked', true );
				// }
				
				$("form#export-csv-form").submit();
			});
			
			$("input.status-item")
			.change(function(){
				
				var $e = $( '#mod-' + $(this).val() );
				var s = $(this).attr('data-table');
				
				if( $.isEmptyObject( nwMyexcel.data.tables ) ){
					nwMyexcel.data.tables = {};
				}
				
				if( $(this).is(":checked") ){
					if( ! nwMyexcel.data.tables[ s ] || $.isEmptyObject( nwMyexcel.data.tables[ s ] ) ){
						nwMyexcel.data.tables[ s ] = {};
					}
					nwMyexcel.data.tables[s][ $(this).val() ] = 1;
				}else{
					
					if( nwMyexcel.data.tables && nwMyexcel.data.tables[s] && nwMyexcel.data.tables[s][ $(this).val() ] ){
						delete nwMyexcel.data.tables[s][ $(this).val() ];
						
						if( $.isEmptyObject( nwMyexcel.data.tables[s] ) ){
							switch( action ){
							case 'generate_csv_from_mine':
							break;
							default:
								delete nwMyexcel.data.tables[s];
							break;
							}
						}
					}
					
				}
				
				nwMyexcel.updateData();
			}).change();
			
			nwMyexcel.first = 0;
		},
		timer: '',
		updateDataTimer: function(){
			if( nwMyexcel.timer ){
				clearTimeout( nwMyexcel.timer );
			}
			nwMyexcel.timer = setTimeout( nwMyexcel.updateData, 300 );
		},
		updateData: function(){
			nwMyexcel.timer = '';
			$("form#client-form").find(".add-options")
			.each( function(){
				nwMyexcel.data[ $(this).attr("name") ] = $(this).val();
			});
			
			/*
			var a = $("form#client-form").serializeArray();
			var b = [];
			var d = {};
			
			if( ! $.isEmptyObject( a ) ){
				$.each(a, function(k, v){
					switch( v.name ){
					case 'accessible_functions[]':
						b.push( v.value );
					break;
					default:
						var kk = v.name.replace('[]', '');
						if( ! d[ kk ] ){
							d[ kk ] = [];
						}
						d[ kk ].push( v.value );
					break;
					}
				});
			}
			
			d["functions"] = b.join(":::");
			*/
			
			$("form#export-csv-form")
			.find('textarea[name="data"]')
			.val( JSON.stringify( nwMyexcel.data ) );
			//console.log( $("form#client-form").serializeArray() );
			
		},
	};
	
}();
nwMyexcel.init();