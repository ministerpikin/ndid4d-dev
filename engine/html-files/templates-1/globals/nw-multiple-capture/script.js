var nwBudget = {
	data:{},
	fields:{},
	child_fields:{},
	child_tables:[],
	labels:{},
	child_labels:{},
	child_forms:{},
	childValidFields:{},
	form_container:"custom-form-contaner",
	child_form_container:"child-custom-form-container",
	tab_ul_id:"nw-tabs",
	tab_content_id:"nw-tabs-body",
	formSerial:0,
	table_name:'',
	child_table_name:'',
	html_replacement_selector:'',
	init:function(){

		if( html_replacement_selector ){
			nwBudget.html_replacement_selector = html_replacement_selector;
		}

		if( form ){
			var f = nwBudget.generateForm( form, 4, 'parent' );
			$( '#' + nwBudget.form_container ).html( f );
		}
		
		if( default_values ){
			var df = JSON.parse( default_values );

			if( ! $.isEmptyObject( df ) ){
				nwBudget.data[ 'default_values' ] = df;
			}
		}
		
		if( child_forms ){
			var cf = JSON.parse( child_forms );

			if( ! $.isEmptyObject( cf ) ){
				nwBudget.child_forms = cf;
			}
		}

		if( table && child_table ){
			nwBudget.table_name = table;
			var ctb = JSON.parse( child_table );
			// console.log( typeof( ctb ) );

			if( ctb.length > 0 ){
				nwBudget.child_tables = ctb;
			}

			$.fn.cProcessForm.ajax_data = {
				ajax_data: { table_name : nwBudget.table_name, callback : "nwBudget.prepareLabels" },
				form_method: 'post',
				ajax_data_type: 'json',
				ajax_action: 'request_function_output',
				ajax_container: '',
				ajax_get_url: "?action=database_table&todo=get_form_fields_from_table_name2",
			};
			$.fn.cProcessForm.ajax_send();;
		}
		
		// console.log( nwBudget.childValidFields );
	},
	prepareLabels: function(){
		if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.data && $.fn.cProcessForm.returned_ajax_data.data.labels && $.fn.cProcessForm.returned_ajax_data.data.fields ){
			nwBudget.labels = $.fn.cProcessForm.returned_ajax_data.data.labels;
			nwBudget.fields = $.fn.cProcessForm.returned_ajax_data.data.fields;

			if( $.fn.cProcessForm.returned_ajax_data.data.table_label ){
				$( 'a[href="#table-parent"]' ).text( 'Create New '+$.fn.cProcessForm.returned_ajax_data.data.table_label );
			}

			if( nwBudget.child_tables.length > 0 && Object.keys( nwBudget.child_forms ).length > 0 ){
				nwBudget.child_tables_tmp = nwBudget.child_tables;

				$.fn.cProcessForm.ajax_data = {
					ajax_data: { table_name : nwBudget.child_tables[0], callback : "nwBudget.prepareLabelsChild" },
					form_method: 'post',
					ajax_data_type: 'json',
					ajax_action: 'request_function_output',
					ajax_container: '',
					ajax_get_url: "?action=database_table&todo=get_form_fields_from_table_name2",
				};
				$.fn.cProcessForm.ajax_send();

			}
		}
	},
	prepareLabelsChild: function(){
		if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.data && $.fn.cProcessForm.returned_ajax_data.data.labels && $.fn.cProcessForm.returned_ajax_data.data.fields && $.fn.cProcessForm.returned_ajax_data.data.table && $.fn.cProcessForm.returned_ajax_data.data.table_label ){

			var tb = $.fn.cProcessForm.returned_ajax_data.data.table;
			var label = $.fn.cProcessForm.returned_ajax_data.data.table_label;

			nwBudget.child_labels[ tb ] = $.fn.cProcessForm.returned_ajax_data.data.labels;
			nwBudget.child_fields[ tb ] = $.fn.cProcessForm.returned_ajax_data.data.fields;

			if( nwBudget.child_forms[ tb ] ){

				var f = nwBudget.generateForm( nwBudget.child_forms[ tb ], 12, 'child' );

				f = f.outerHTML || new XMLSerializer().serializeToString(f)

				var h = nwBudget.prepareChildView( tb, label, f );

				$( 'ul#' + nwBudget.tab_ul_id ).append( h.tab );
				$( 'div#' + nwBudget.tab_content_id ).append( h.body );
				prepare_new_record_form_new();

				if( $.inArray( tb, nwBudget.child_tables_tmp ) !== -1 ){
					nwBudget.child_tables_tmp.splice( $.inArray( tb, nwBudget.child_tables_tmp ), 1 );
				}

				if( ! $.isEmptyObject( nwBudget.child_tables_tmp ) ){

					$.fn.cProcessForm.ajax_data = {
						ajax_data: { table_name : nwBudget.child_tables_tmp[0], callback : "nwBudget.prepareLabelsChild" },
						form_method: 'post',
						ajax_data_type: 'json',
						ajax_action: 'request_function_output',
						ajax_container: '',
						ajax_get_url: "?action=database_table&todo=get_form_fields_from_table_name2",
					};
					$.fn.cProcessForm.ajax_send();
				}else{
					nwBudget.submitDataForm();
				}
			}
		}
	},
	prepareChildView: function( table, label, form ){

		var tr = '<th>S/N</th>';
		if( nwBudget.childValidFields[ table ] && nwBudget.childValidFields[ table ].length > 0 ){
			$.each( nwBudget.childValidFields[ table ], function( k, v ){
				if( ! v )return;
				if( nwBudget.child_labels[ table ] && nwBudget.child_labels[ table ][ v ] ){
					var lbl = nwBudget.child_labels[ table ][ v ].display_field_label ? nwBudget.child_labels[ table ][ v ].display_field_label : nwBudget.child_labels[ table ][ v ].field_label;
					tr += '<th>'+ lbl +'</th>';
				}
			});
		}
		tr += '<th class="r"></th>';
		var active = '';

		if( $( 'ul#' + nwBudget.tab_ul_id ).children().length == 0 ){
			active = 'active';
		}

		var tab = '<li class="tabbs '+ active +'"><a data-toggle="tab" href="#manage-children-'+ table +'" onclick="">'+ label +'</a></li>';
			   
		var body = '\
		<div class="tab-pane '+ active +'" id="manage-children-'+ table +'">\
			<div class="row">\
				<div class="col-md-3" id="child-custom-form-container-'+ table +'">'+ form +'</div>\
				<div class="col-md-9">\
					<br />\
					<div class="shopping-cart-table">\
						<div class="table-responsive">\
							<table class="table table-striped table-hover bordered">\
								<thead>\
								   <tr id="child-table-head">'+ tr +'</tr>\
								</thead>\
								<tbody id="display-contents-'+ table +'">\
								</tbody>\
								<tfoot>\
								</tfoot>\
							</table>\
						</div>\
					</div>\
				</div>\
			</div>\
		</div>';

		return { tab: tab, body : body };
	},
	generateForm: function( form, col, type ){
		++nwBudget.formSerial;
		if( form ){
			col = col ? col : 4;
			form = decodeURIComponent(form.replace(/\+/g, ' '));

			var f = document.createElement( 'div' );
			f.setAttribute( 'id', 'nwDiv' );
			f.innerHTML = form;

			var tb = $( 'form', f ).attr( 'name' );

			nwBudget.childValidFields[ tb ] = [];

			var DOM = '<div class="row">';

			$( 'div.form-group.control-group', f ).each(function(){
				// var escapedStr = nwBudget.nodeToString( this ).replace( "<" , "&lt;" ).replace( ">" , "&gt;");
				var escapedStr = this.outerHTML || new XMLSerializer().serializeToString(this);
				DOM += '<div class="col-md-'+ col +'">'+ escapedStr +'</div>';

				if( ! $(this).hasClass( 'default-hidden-row' ) ){
					nwBudget.childValidFields[ tb ].push( $(this).find( 'input' ).attr( 'id' ) );
				}
			});

			DOM += '</div>';
			DOM += '<br />';

			$( 'div#bottom-row-container', f ).find( 'input' ).removeAttr( 'id' );
			DOM += '<div id="bottom-row-container">'+ $( 'div#bottom-row-container', f ).html() +'</div>';

			$( 'form > div.form-body', f ).html( DOM );
			form = f;

			switch( type ){
			case 'child':
				// $( 'form', f ).attr( 'id', nwBudget.child_table_name );
				$( 'form', f ).attr( 'nwID', 'nw-child-from' );
				$( 'form', f ).attr( 'action', '' );
				$( 'form', f ).attr( 'class', 'client-form' );
			break;
			case 'parent':
				$( 'form', f ).prepend( '<textarea name="data" class="hyella-data"></textarea>' );
				$( 'form', f ).attr( 'action', $( 'form', f ).attr( 'action' ) + '&html_replacement_selector='+nwBudget.html_replacement_selector );
			break;
			}
		}
		return form;
	},
	refreshCart: function( tb ){
		var html = '';
		var tmp = nwBudget.data.cart_items;

		if( tmp[ tb ] && ! $.isEmptyObject( tmp[ tb ] )  ){
			if( nwBudget.childValidFields[ tb ] && nwBudget.childValidFields[ tb ].length > 0 ){

				var sn = 0;
				$.each( tmp[ tb ], function( k, v ){

					var sid = 'section-'+ v.id;
					
					html += ' <tr id="'+ sid +'" data-key="'+ k +'" type="'+ tb +'">';
						html += '<td>'+ ++sn +'</td>';

						$.each( nwBudget.childValidFields[ tb ], function( dk, dv ){
							if( typeof( v[ dv ] ) == undefined )return;
							html += '<td>'; 
							if( v[ dv + '_tags' ] ){
								var xx = '';
								for( x in v[ dv + '_tags' ] ){
									xx += ( !xx ? v[ dv + '_tags' ][x].text : ', ' + v[ dv + '_tags' ][x].text );
								}
								html += xx;
							}else if( v[ dv + '_name' ] ){
								html += ( v[ dv + '_name' ] ? v[ dv + '_name' ] : '' ) ;
							}else {
								html += ( v[ dv ] ? v[ dv ] : '' ) ;
							}
							html += '</td>';
						});

						html += ' <td class="r"> ';

						html += ' <a href="#" onclick="nwBudget.delete('+ "'"+ sid +"'" +');" title="Remove this Section" class="btn btn-sm dark"> <i class="icon-trash"></i> </a>';

						html += ' <a href="#" onclick="nwBudget.edit('+ "'"+ sid +"'" +');" title="Edit this Section" class="btn btn-sm dark"> <i class="icon-edit"></i> </a>';
						
						html += '</td>';
					html += '</tr>';
					
				});
			}
		}
		
		$( '#display-contents-' + tb ).html( html );
		
		$( 'form[name="'+ nwBudget.table_name +'"]' ).find('textarea[name="data"]')
		.val( JSON.stringify( nwBudget.data ) );

		console.log( nwBudget.data.cart_items );
	},
	submitDataForm: function(){
		
		$("form.client-form")
		.on('submit', function(e){
			e.preventDefault();
			
			var err = "";
			var msg = "";
			
			var data = {};
			$(this)
			.find(".form-control")
			.each(function(){
				var val = $(this).val();
				
				switch( $(this).attr("type") ){
				case "hidden":
				case "text":
					if( $(this).hasClass("select2") ){
						var d = $(this).select2('data');
						
						if( ! $.isEmptyObject( d ) ){
							var n = $(this).attr("name");
							
							if( $(this).attr("tags") && $(this).attr("tags") == "true" ){
								
								data[ n + "_tags" ] = d;
								data[ n + "_text" ] = '';
								
								$.each( d, function( k, v ){
									if( v.text ){
										data[ n + "_text" ] += v.text + ', ';
									}
								} );
								
							}else{
								
								$.each( d, function( k, v ){
									if( k ){
										data[ n + "_" + k ] = v;
									}
								} );
								
							}
						}
					}
				break;
				case "number":
					val = parseFloat( val );
					if( isNaN( val ) )val = 0;
				break;
				case "select":
					var n = $(this).attr("name");
					
					if( $(this).val() ){
						var val = $(this).val().toString();
					}else{
						val = '';
					}
					if( $(this).children('option:selected')[0].hasAttribute( 'key' ) ){
						data[ n + "_key" ] = $(this).children('option:selected').attr( 'key' );
					}
					
					data[ n + "_text" ] = $(this).children('option:selected').text().trim();
				break;
				}
				
				data[ $(this).attr("name") ] = val;
			});

			var id = nwBudget.uniqueKey( 1, 100 );

			var table_name = $(this).attr("name");
			var type = $(this).attr("key");
			
			var refresh = '';
			var increment = 0;
			// console.log( data );
			
			if( $(this)[0].hasAttribute( 'nwID' ) && $(this).attr( 'nwID' ) == 'nw-child-from' && table_name ){

				if( ! nwBudget.data.cart_items ){
					nwBudget.data.cart_items = {};
				}
				
				if( ! nwBudget.data.cart_items[ table_name ] ){
					nwBudget.data.cart_items[ table_name ] = {};
				}
				
				if( $(this)[0].hasAttribute( 'edit-id' ) ){
					id = $(this).attr( 'edit-id' );
					$( this ).removeAttr( 'edit-id' );
				}
				
				increment = parseInt( data["serial"] );
				data["id"] = id;

				nwBudget.data.cart_items[ table_name ][ id ] = data;

			}else{
				err = "Invalid Input";
				msg = "Please try again or contact technical support team";
			}
			
			if( nwBudget.error ){
				err = "You have an error in your formulaes. Please review or reload the form";
			}

			if( err ){
				var data = {theme:'alert-danger', err:err, msg:msg, typ:'jsuerror' };
				nwDisplayNotification.display_notification( data );
				return false;
			}else{
				nwBudget.refreshCart( table_name );
			}
			
			$(this).find( 'textarea[name="formulae"]' ).val('');
			
			if( increment ){
				++increment;
				$(this).find('input[name="serial"]').val(increment);
			}
		});
	},
	delete: function( id, sectionID ){
		console.log( nwBudget.data.cart_items );

		var a = $( 'tr#' + id );
		var type = a.attr( 'type' );

		id = a.attr( 'data-key' );

			// console.log( type );
			// console.log( id );

		if( nwBudget.data.cart_items[ type ][ id ] && ! $.isEmptyObject( nwBudget.data.cart_items[ type ][ id ] ) ){
			delete nwBudget.data.cart_items[ type ][ id ];
		}
		// console.log( nwBudget.data.cart_items );
		nwBudget.refreshCart( type );
		
	},
	edit: function( id, sectionID ){

		var a = $( 'tr#' + id );
		var type;

		type = a.attr( 'type' );
		id = a.attr( 'data-key' );

		if( nwBudget.data.cart_items[ type ] && ! $.isEmptyObject( nwBudget.data.cart_items[ type ] ) ){
			if( nwBudget.data.cart_items[ type ][ id ] && ! $.isEmptyObject( nwBudget.data.cart_items[ type ][ id ] ) ){

				$.each( nwBudget.data.cart_items[ type ][ id ], function( k, v ){
					switch( k ){
					// case 'options':
						// $( 'form#conditional-formulae' ).find( 'select[name="'+ k +'"]' ).find( 'option[value="'+ v +'"]' ).attr( 'selected', 'selected' );	
					// break;
					case 'options':
						$( 'form[name="'+ type +'"]' ).find( 'input[name="'+ k +'"]' ).select2( 'data', nwBudget.data.cart_items[ type ][ id ][ k + '_tags' ] );
					break;
					default:
						$( 'form[name="'+ type +'"]' ).find( 'input[name="'+ k +'"], select[name="'+ k +'"], textarea[name="'+ k +'"]' ).val( v );
					break;
					}
				});
				$( 'form[name="'+ type +'"]' ).attr( 'edit-id', id );
			}
		}

	},
	uniqueKey: function( min, max ){
		var launch_date = new Date();
		return 'd' + launch_date.getTime(); // + 'd' + Math.floor(Math.random() * (max - min + 1) + min);
	},
};
nwBudget.init();