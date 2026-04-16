//has been moved to sign-in/custom.plugin.js
/*
var nwBulkDataCapture = function () {
	return {
		cartItemsRemoved:{},
		autoCompleteIndex:{},
		autoCompleteCol2:{},
		autoCompleteCol:{},
		autoCompleteSelection:{},
		cartItems:{},
		unsaved:[],
		initialized:0,
		in_use:0,
		columns: {},
		colHeaders: {},
		tbcontainer: '',
		init: function () {
			//var c = ( global_data );
			//var c = JSON.parse( global_data );
			
		},
		hot:'',
		activateData:{},
		autocompleteSource: function (query, process) {
			
			var sel = nwBulkDataCapture.hot.getSelected();
			// && nwBulkDataCapture.cartItems[ sel[0] ] 
			//console.log( 's0x', nwBulkDataCapture.columns );
			//console.log( 's0x', sel[0][1] );
			//console.log( 's1', nwBulkDataCapture.cartItems[ sel[0][0] ] );
			var rd = [];
			
			//var action = '?action=customers&todo=get_customers_select2';
			var ccol = '';
			var action = '';
			var prop = '';
			if( sel[0] && sel[0][1] && nwBulkDataCapture.columns[ sel[0][1] ] && nwBulkDataCapture.columns[ sel[0][1] ].action ){
				ccol = sel[0][1];
				prop = nwBulkDataCapture.columns[ sel[0][1] ].data;
				action = nwBulkDataCapture.columns[ sel[0][1] ].action;
			}
			
			if( query.indexOf(" - ") >= 0 || query.indexOf(" [") >= 0 ){
				query = '';
				if( sel[0] && typeof( sel[0][0] ) !== 'undefined' ){
					//nwBulkDataCapture.hot.setDataAtRowProp( sel[0][0], prop, '', 'skip' );
					 
					//setTimeout( function(){
					//}, 3000 );
					nwBulkDataCapture.hot.setDataAtRowProp( sel[0][0], prop, '', 'skip' );
					return false;
					
					//	nwBulkDataCapture.hot.setDataAtRowProp( 1, 'account', 'qwerty', 'skip' );
				}
			}
			
			if( sel[0] && typeof( sel[0][0] ) !== 'undefined' ){
				//console.log( 's', nwBulkDataCapture.hot.getDataAtRow( sel[0][0] ) );
				rd = nwBulkDataCapture.hot.getDataAtRow( sel[0][0] );
			}
			
			var surl = $.fn.cProcessForm.requestURL +'php/ajax_request_processing_script.php' + action;
			if( $.fn.cProcessForm.customURL ){
				surl = $.fn.cProcessForm.requestURL + action;
			}
			
			$.ajax({
			  url: surl,
			  type:'post',
			  dataType: 'json',
			  data: {
				r_limit: 16,
				format: 'handsontable',
				row_data: JSON.stringify( rd ),
				term: query
			  },
			  success: function (response) {
				//console.log("response", response);
				var pr = [];
				if( response.items ){
					
					if( ! nwBulkDataCapture.autoCompleteIndex[ ccol ] ){
						nwBulkDataCapture.autoCompleteIndex[ ccol ] = {};
					}
					
					$.each( response.items, function( k, v ){
						if( v.text ){
							var t = '';
							if( v.serial_num ){
								t = v.text;
								//t = v.serial_num + ' - ' + v.text;
							}else{
								t = v.id + ' - ' + v.text;
							}
							//t = v.id + ':::::' + v.text;
							t = t.trim();
							pr.push( t );
							if( v.array ){
								nwBulkDataCapture.autoCompleteIndex[ ccol ][ md5( t ) ] = v;
							}else{
								nwBulkDataCapture.autoCompleteIndex[ ccol ][ md5( t ) ] = v.id;
							}
						}
					} );
				}
				//console.log( 'x', nwBulkDataCapture.autoCompleteIndex );
				//process(JSON.parse(response.data)); // JSON.parse takes string as a argument
				process( pr );
				//process([ "ben", "jason" ]);

			  }
			});
		},
		use_default_functions:1,
		activateHandsontable: function(){
			var p = nwBulkDataCapture.activateData;
			
			var colHeaders = [ 'ID', 'Sample' ];
			var columns = [
				{
				  data: 'id',
				  type: 'numeric',
				  width: 40
				},
				{
				data: 'currencyCode',
				type: 'autocomplete',
				source: nwBulkDataCapture.autocompleteSource,
				strict: true
				},
				
			];

			var data_object = {};

			var hotSettings = {
				// columns: columns,
				autoWrapRow: true,
				allowRemoveRow:false,
				allowInsertRow:false,
				allowInsertColumn:false,
				allowRemoveColumn:false,
				manualRowResize: true,
				manualColumnResize: true,
				rowHeaders: true,
				// colHeaders:colHeaders,
				manualRowMove: false,
				manualColumnMove: false,
				contextMenu: true,
				copyPaste: true,
				columnSorting: true,
				height: 420,
				//width: 806,
				stretchH: 'all',
				// colWidths: [ 1, 1, 200, 50, 100, 100, 100, 80, 70, 150 ],
			};
			

			if( p && p.hotSettings ){
				hotSettings = p.hotSettings;
			}

			hotSettings["contextMenu"] = [ 'undo', 'redo' ];
			
			if( ! ( p && p.hotSettings ) ){
				hotSettings["allowRemoveRow"] = true;
				hotSettings["contextMenu"].push('remove_row');
			}
			if( p && p.remove ){
				hotSettings["allowRemoveRow"] = true;
				hotSettings["contextMenu"].push('remove_row');
			}

			if( p && p.colHeaders && p.columns && p.tbcontainer ){
				hotSettings.colHeaders = p.colHeaders;
				hotSettings.columns = p.columns;
				tbcontainer = p.tbcontainer;

				if( p.no_default_functions )nwBulkDataCapture.use_default_functions = 0;
				if( p.data )data_object = p.data;

			// }else if( typeof( global_columns ) !== 'undefined' ){
			}else if( typeof( global_colHeaders ) !== 'undefined' && typeof( global_columns ) !== 'undefined' && typeof( tbcontainer ) !== 'undefined' ){
				hotSettings.colHeaders = global_colHeaders;
				hotSettings.columns = global_columns;

				if( typeof( global_data ) !== 'undefined' )data_object = global_data;
			}else{
				alert( 'Invalid Handsontable Column Headers/Containers' );
			}
			
			
			//hotSettings.colHeaders = colHeaders;
			//hotSettings.columns = columns;
			$.each(hotSettings.columns, function( k1, v1 ){
				if( v1.type == 'autocomplete' ){
					nwBulkDataCapture.autoCompleteCol[ k1 ] = k1;
					nwBulkDataCapture.autoCompleteCol2[ v1.data ] = k1;
					hotSettings.columns[ k1 ].source = nwBulkDataCapture.autocompleteSource;
				}
			});
			nwBulkDataCapture.colHeaders = hotSettings.colHeaders;
			nwBulkDataCapture.columns = hotSettings.columns;
			nwBulkDataCapture.tbcontainer = tbcontainer;
			
			//console.log( nwBulkDataCapture.columns );
			//console.log( nwBulkDataCapture.colHeaders );
			//console.log( $('#'+tbcontainer).css('width') );
			
			var add_data = 1;
			if( add_data ){
				hotSettings["data"] = data_object;
				//hotSettings["copyPaste"] = false;
			}
				
			// if( nwBulkDataCapture.in_use ){
				//'row_abovex', 'row_belowx',
				
			// }
			
			
			var hotElement = document.querySelector('#'+tbcontainer);
			//var hot = jspreadsheet( document.getElementById( tbcontainer ) , hotSettings);
			var hot = new Handsontable( hotElement , hotSettings);
			
			// var searchFiled = document.getElementById('search_field');
			
			//$('#'+tbcontainer).jexcel( hotSettings );
			
			hot.addHook('beforeRemoveRow', function( index, amount, physicalRows, source ){
				//console.log( index, amount );
				if( nwBulkDataCapture.update ){
					for( var i = index; i < ( index + amount ); i++ ){
						var dx = hot.getDataAtRow( i );
						
						if( dx[0] ){
							if( nwBulkDataCapture.use_default_functions ){
								nwBulkDataCapture.cartItemsRemoved[ dx[0] ] = 1;
								if( nwBulkDataCapture.cartItems[ dx[0] ] ){
									delete nwBulkDataCapture.cartItems[ dx[0] ];
									//console.log( dx );
								}
								if( nwBulkDataCapture.cartItems[ i ] ){
									delete nwBulkDataCapture.cartItems[ i ];
									//console.log( dx );
								}
							}
							nwBulkDataCapture.afterChange( { 'data' : dx, 'type' : 'delete' } );
						}
						
					}
					if( nwBulkDataCapture.use_default_functions ){
						nwBulkDataCapture.refreshCart();
					}
				}
			});
			
			hot.addHook('afterChange', function(src, changes){
				
				//console.log( src, changes );
				if( nwBulkDataCapture.update && changes != 'skip' ){
					//console.log( 'a', nwBulkDataCapture.update );
					for( var i = 0; i < src.length; i++ ){
					//for( var ii = 0, ii < src[i].length, ii++ ){
						var row = src[i][0];
						var col = src[i][1];
						var val = src[i][3];
						
						var dx = hot.getDataAtRow( row );
						if( nwBulkDataCapture.use_default_functions ){
							nwBulkDataCapture.cartItems[ row ] = nwBulkDataCapture.afterChange( { 'data' : dx, 'type' : 'edit', 'row':row, 'col':col } );
						}else{
							nwBulkDataCapture.afterChange( { 'data' : dx, 'type' : 'edit', 'row':row, 'col':col } );
						}
						//console.log( 'data-' + row, dx );
						//console.log( hot.getData() );
					}
					
					if( nwBulkDataCapture.use_default_functions ){
						nwBulkDataCapture.refreshCart();
					}
				}
			}); 
		   
			nwBulkDataCapture.hot = hot;
			
		  //hot.getData()
		},
		update: 1,
		afterChange: function( p ){
			//console.log(nwBulkDataCapture.columns);
			//console.log(p);
			if( p && p.data && p.type && p.type == 'edit' ){
				if( typeof( p.row ) !== 'undefined' && ! $.isEmptyObject( nwBulkDataCapture.autoCompleteCol ) ){
					$.each( nwBulkDataCapture.autoCompleteCol, function( k1, v1 ){
						if( p.data[ k1 ] && nwBulkDataCapture.autoCompleteIndex[ k1 ] && nwBulkDataCapture.autoCompleteIndex[ k1 ][ md5( p.data[ k1 ] ) ] ){
							if( ! nwBulkDataCapture.autoCompleteSelection[ p.row ] ){
								nwBulkDataCapture.autoCompleteSelection[ p.row ] = {};
							}
							nwBulkDataCapture.autoCompleteSelection[ p.row ][ k1 ] = nwBulkDataCapture.autoCompleteIndex[ k1 ][ md5( p.data[ k1 ] ) ];
						}
					});
				}
				return p.data;
			}
		},
		clear: function(){
			nwBulkDataCapture.update = 0;
			nwBulkDataCapture.hot.clear();
			nwBulkDataCapture.cartItems = {};
			nwBulkDataCapture.cartItemsRemoved = {};
			nwBulkDataCapture.refreshCart();
			setTimeout(function(){ nwBulkDataCapture.update = 1; }, 800 );
		},
		autoPopuplateChanges: function(){
			nwBulkDataCapture.cartItems = nwBulkDataCapture.hot.getData();
			nwBulkDataCapture.refreshCart();
		},
		refreshCart: function(){
			var j = {
				columns: nwBulkDataCapture.columns,
				data: nwBulkDataCapture.cartItems,
				deleted: nwBulkDataCapture.cartItemsRemoved,
				auto_sel: nwBulkDataCapture.autoCompleteSelection,
			};
			
			var count = 0;
			$.each( nwBulkDataCapture.cartItems, function( k, v ){
				++count;
			} );
			$.each( nwBulkDataCapture.cartItemsRemoved, function( k, v ){
				++count;
			} );
			
			if( $('#'+nwBulkDataCapture.tbcontainer+'-con') ){
				$('#'+nwBulkDataCapture.tbcontainer+'-con')
				.find(".the-notice-container")
				.text( count + ' unsaved changes' )
				.show();
			}
			
			if( $('form#'+nwBulkDataCapture.tbcontainer+'-form') ){
				$('form#'+nwBulkDataCapture.tbcontainer+'-form')
				.find('textarea[name="data"]')
				.val( JSON.stringify( j ) );
			}
		},
		addComma: function( nStr ){
			nStr = parseFloat( nStr ).toFixed(2);
			
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
*/