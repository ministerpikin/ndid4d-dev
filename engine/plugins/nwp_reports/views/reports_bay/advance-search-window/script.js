var nwSearch = {
	data: {},
	data_source: '',
	serial: 1,
	query: {},
	fields: {},
	labels: {},
	tables: tables,
	parent_table: parent_table,
	show_update: show_update,
	updateFormID: update_form_id,
	conditions: { "between" : "BETWEEN", "between_relative" : "BETWEEN RELATIVE", "gte_lte" : ">= OR <=", "gt_lt" : "> OR <", "in" : "In", "not_in" : "Not In", "not_empty": "Not Empty", "equal_to" : "Equal To", "not_equal_to" : "Not Equal To", "less_than" : "Less Than", "greater_than" : "Greather Than", "less_than_equal_to" : "Less Than and Equal To", "greater_than_equal_to" : "Greater Than and Equal To", "contains" : "Contains" },
	showCreateMine: function(e){
		//console.log( theme_version );
		if( typeof theme_version != 'undefined' ){
			switch( theme_version ){
			case 'v3':
				var firstTabEl = document.querySelector('#tab-create-mine');
				var firstTab = new bootstrap.Tab(firstTabEl);

				firstTab.show();
			break;
			default:
				$( 'a#tab-create-mine' ).click();
			break;
			}
		}
	},
	loadSaveSearch: function(e){

		var $me = $(e);
		var val = $me.select2( 'data' );

		// $( 'textarea[name="data"]' ).val( decodeURI( val.content ) );
		$( 'textarea[name="data"]' ).val( JSON.stringify( JSON.parse( val.content ) ) );
		nwSearch.dataOnChange();
		// console.log( val );
		// console.log( JSON.parse( val.content ) );

	},
	dataOnChange: function(){
		if( ! nwSearch.disableDataOnChange ){
			nwSearch.init();
		}
	},
	init: function( opt ){

		if( typeof( opt ) == 'undefined' ){
			var opt = {};
		}

		if( dd && ! $.isEmptyObject( dd ) ){
			nwSearch.data = JSON.parse( JSON.stringify( dd ) );
		}
				// console.log( nwSearch.data )

		nwSearch.submitDataForm();
		var table_name = nwSearch.getTableName();
		// console.log( table_name )

		var j1 = $('form#query-execute-form').find('textarea[name="data"]').val();
		
		if( table_name ){
			if( typeof( benefitProgram ) != 'undefined' ){
				benefitProgram.data_source = table_name;
				$( 'form#query-execute-form' ).on( 'submit', function(){
					benefitProgram.data = nwSearch.data;
				});
			}
			nwSearch.data[ 'dataSource' ] = table_name;
			var tbs = { table_name : table_name };

			if( ! $.isEmptyObject( nwSearch.tables ) && nwSearch.tables[ table_name ] )tbs = nwSearch.tables;

			$.each( tbs, function( k, v ){
				// console.log( nwSearch.data )
				// console.log( nwSearch.data.fields )
				if( nwSearch.data && nwSearch.data[ 'fields' ] && nwSearch.data[ 'fields' ][ k ] ){

					if( typeof $.fn.cProcessForm.returned_ajax_data == 'undefined' ){
						$.fn.cProcessForm.returned_ajax_data = {};
					}
					if( typeof $.fn.cProcessForm.returned_ajax_data.data == 'undefined' ){
						$.fn.cProcessForm.returned_ajax_data.data = {};
					}
					$.fn.cProcessForm.returned_ajax_data.data.labels = nwSearch.data[ 'fields' ][ k ];
					$.fn.cProcessForm.returned_ajax_data.data.table = k;
					nwSearch.refresh = 1;
					nwSearch.displayFields();
				}else{
					nwSearch.searchDatabaseTableForForms( k );
				}
			});

			$( 'form#query-execute-form' ).find( 'input[name="table"]' ).val( nwSearch.parent_table );
		
			if( j1 ){
				var j = JSON.parse( j1 );
				if( ! $.isEmptyObject( j ) ){
					var xfields = nwSearch.data.fields ? nwSearch.data.fields : {};

					$.each( j, function( k1, v1 ){
						nwSearch.data[ k1 ] = v1;
					});

					nwSearch.data.fields = xfields;
					var opt = '';
					for( var i = 1; i <= Object.keys(j).length; i++ ){
						opt += '<option value="'+ i +'">'+ i +'</option>';
					};
					// console.log( tables );
					$( 'select[name="sub_query"]' ).html( opt );
					$( 'select[name="sub_query"]' ).html( opt );
				}
			}

			if( plugin )nwSearch.data[ 'plugin' ] = plugin;
			if( additional_info && ! $.isEmptyObject( additional_info ) ){
				nwSearch.data[ 'additional_info' ] = additional_info;
			}
			
			nwSearch.refresh = 1;
			
			// console.log( nwSearch.data.cart_items )
			if( nwSearch.data.cart_items && ! $.isEmptyObject( nwSearch.data.cart_items ) && nwSearch.data.fields ){
				nwSearch.refreshCart( 'create-query-form' );
			}
			if( nwSearch.data.select && ! $.isEmptyObject( nwSearch.data.select ) && nwSearch.data.fields ){
				nwSearch.refreshCart( 'create-select-form' );
			}

			if( parseInt( $('input[name="query_limit"]').val() ) ){
				$('input#use_limit').prop('checked', true).change();
				// nwSearch.changeLimit('', '', 'query-execute-form');
			}
		}

	},
	searchMode:"",
	changeSearchMode: function( e ){
		var $me = $(e);
		var val = $me.val();
		nwSearch.searchMode = val;
		
		$( ".search-mode-form, .shopping-cart-table" ).hide();
		$(".search-mode-form#"+ val ).show();
		switch( val ){
		case 'saved-searches':
			val = 'search-form';
		break;
		}
		$(".shopping-cart-table#"+ val ).show();
		nwSearch.currentForm = '#' + $(".search-mode-form#"+ val ).find("form").attr("id");
		/*
		if( ! nwSearch.editPO ){
			nwSearch.emptyCart();
		}
		*/
	},
	save_search: function( e ){
		var $me = $(e);
		var val = $me.val();
		nwSearch.searchMode = val;
		
		if( $me.is(":checked") ){
			$( 'div#value-save-name' ).show();
		}else{
			$( 'div#value-save-name' ).hide();
			$( 'input[name=save_query]' ).val( '' );
		}

	},
	changeDataSource: function( form ){
		var tb = nwSearch.getTableName( form );

		// console.log( nwSearch.data.fields[ tb ] );
		if( nwSearch.data.fields && nwSearch.data.fields[ tb ] ){
			$.fn.cProcessForm.returned_ajax_data.data = {};
			$.fn.cProcessForm.returned_ajax_data.data.labels = nwSearch.data.fields[ tb ];
			$.fn.cProcessForm.returned_ajax_data.data.table = tb;
			nwSearch.displayFields( form, tb );
		}else{
			nwSearch.searchDatabaseTableForForms( tb );
		}
	},
	showClearSearch: function(){
		$('a#clear-advance-search-button')
		.show();
	},
	hideClearSearch: function(){
		$('a#clear-advance-search-button')
		.hide();
	},
	tableOptions:{},
	getTableName: function( form ){
		if( typeof( form ) == 'undefined' ){
			form = 'create-query-form';
		}
		var table_name;
		if( $( 'form#'+form ).find( 'select[name="table_name"]' ).val() ){
			table_name = $( 'form#'+form ).find( 'select[name="table_name"]' ).val();
			var $o = $( 'form#'+form ).find( 'select[name="table_name"]' ).find('option[value="'+ table_name +'"]');
			
			nwSearch.tableOptions = { "table_name": table_name };
			
			if( $o.attr('data-plugin') ){
				nwSearch.tableOptions["plugin"] = $o.attr('data-plugin');
			}
			
			//@nw5
			if( $o.attr('data-real_table') ){
				nwSearch.tableOptions["real_table"] = $o.attr('data-real_table');
			}
			if( $o.attr('data-db_table') ){
				nwSearch.tableOptions["db_table"] = $o.attr('data-db_table');
			}
			if( $o.attr('data-o_table') ){
				nwSearch.tableOptions["o_table"] = $o.attr('data-o_table');
			}
			if( $o.attr('data-db_table_filter') ){
				nwSearch.tableOptions["db_table_filter"] = $o.attr('data-db_table_filter');
			}
		}
		return table_name;
	},
	runQuery: function(){
		var query = '';
		var data = {};
		var first = 1;

		if( nwSearch.data.cart_items && ! $.isEmptyObject( nwSearch.data.cart_items ) ){
			
			$.each( nwSearch.data.cart_items, function( kData, vData ){

				if( typeof vData != 'undefined' && typeof vData.data != 'undefined' && ! $.isEmptyObject( vData.data ) ){
					var text = '';

					$.each( vData.data, function( k, v ){

						if( v.logical_operator ){
							text += ' ' + v.logical_operator_text + ' ';
						}else{
							err = 'Please specify an operator';	
						}

						if( v.field && v.condition && v.search_value ){
							var s1 = '';
							var s2 = '';
							switch( v.condition ){
							case 'gte_lte':
								s1 = '>=';
								s2 = '<=';
							break;
							case 'gt_lt':
								s1 = '>';
								s2 = '<';
							break;
							}

							if( v.start_date || v.end_date ){
								text += '( `' + v.table_name + '`.`' + v.field + '` '+ s1 +' "' + v.start_date + '" AND `' + v.table_name + '`.`' + v.field + '` '+ s2 +' "' + v.end_date + '" )';
							}else if( v.min && v.max ){
								text += '( `' + v.table_name + '`.`' + v.field + '` '+ s1 +' "' + v.min + '" AND `' + v.table_name + '`.`' + v.field + '` '+ s2 +' "' + v.max + '" )';
							}else{

								var c = '';
								switch( v.condition ){
								case 'equal_to':
									c = '=';
								break;
								case 'not_equal_to':
									c = '<>';
								break;
								case 'less_than':
									c = '<';
								break;
								case 'greater_than':
									c = '>';
								break;
								case 'less_than_equal_to':
									c = '<=';
								break;
								case 'greater_than_equal_to':
									c = '>=';
								break;
								case 'contains':
									c = 'regex';
								break;
								case 'in':
									c += "IN ( ";
								break;	
								case 'not_in':
									c += "NOT IN ( ";
								break;
								case 'not_empty':
									c += "NOT NULL ";
								break;
								case 'between':
									c += "BETWEEN ";
								break;
								}
								text += "`" + v.table_name + "`.`" + v.field + "` " + c + " '" + v.search_value + "' ";
								switch( v.condition ){
								case 'in':
								case 'not_in':
									text += " ) ";
								break;
								}
							}

						}

					});
					if( first ){
						first = 0;
						query +=  '( ' + text + ' ) ';
					}else{
						if( vData.condition ){
							query += vData.condition + ' (' + text + ')';
						}
					}
				}
			});
		}
	},
	getRunQuery: function( query ){
		if( query ){
			//@nw5
			var t = nwSearch.getTableName();
			var params = nwSearch.tableOptions;
			params.table = t;
			params.query = query;
			params.callback = 'nwSearch.showRunQuery';

			$.fn.cProcessForm.function_click_process = 1;
			$.fn.cProcessForm.ajax_data = {
				ajax_data: params,
				form_method: 'post',
				ajax_data_type: 'json',
				ajax_action: 'request_function_output',
				ajax_container: '',
				ajax_get_url: "?action=search&todo=run_search_query",
			};
			$.fn.cProcessForm.ajax_send();

		}
	},
	searchDatabaseTableForForms: function( searchDatabaseTable ){
		//var id = searchDatabaseTable;
		//nwSearch.refreshCart();
		setTimeout(function(){
			nwSearch.tableOptions["callback"] = 'nwSearch.displayFields';
			var xx = nwSearch.tableOptions;
			xx.table_name = searchDatabaseTable;
			
			//nwSearch.data[ 'database_table' ] = $( '#' + searchDatabaseTable ).select2('data')[ 'name' ];
			$.fn.cProcessForm.function_click_process = 1;
			$.fn.cProcessForm.ajax_data = {
				ajax_data: xx,
				form_method: 'post',
				ajax_data_type: 'json',
				ajax_action: 'request_function_output',
				ajax_container: '',
				ajax_get_url: "?action=database_table&todo=get_form_fields_from_table_name",
			};
			$.fn.cProcessForm.ajax_send();
		},500);
	},
	displayFields: function( form, tb ){
		// alert();

		if( typeof( form ) == 'undefined' ){
			form = 'create-query-form';
		}
		
		if( typeof( form ) == 'undefined' ){
			tb = '';
		}
		
		var html = '';
		var dd = {};

		if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.data && $.fn.cProcessForm.returned_ajax_data.data.labels && $.fn.cProcessForm.returned_ajax_data.data.table && ! nwSearch.labels[ $.fn.cProcessForm.returned_ajax_data.data.table ] ){
			nwSearch.labels[ $.fn.cProcessForm.returned_ajax_data.data.table ] = $.fn.cProcessForm.returned_ajax_data.data;
			if( typeof nwSearch.labels[ $.fn.cProcessForm.returned_ajax_data.data.table ].labels != 'undefined' ){
				$.each( nwSearch.labels[ $.fn.cProcessForm.returned_ajax_data.data.table ].labels, function( m,n ){
					if( typeof n.field_identifier != 'undefined' ){
						nwSearch.fields[ n.field_identifier ] = m;
					}
				})
			}
			dd = nwSearch.labels[ $.fn.cProcessForm.returned_ajax_data.data.table ];
		}

		if( $.isEmptyObject( dd ) && tb && nwSearch.labels[ tb ] ){
			dd = nwSearch.labels[ tb ];
		}

		if( dd && dd.labels ){
			// console.log( dd );
			var data = dd;
			if( ! nwSearch.data[ 'fields' ] ){
				nwSearch.data[ 'fields' ] = {};
			}
			nwSearch.data[ 'fields' ][ data.table ] = data.labels;
			html += '<option value="">--Select One--</option>';
			
			var remove = [
				"serial_number",
				"field_type",
				"filed_length",
				// "data",
				"database_objects",
				"class",
				"default_appearance_in_table_fields",
				"display_position",
				"acceptable_files_format",
				"table_name",
				"created_by",
				"modified_by",
				"creation_date",
				"modification_date",
				"serial_num",
				"abbreviation",
				"required_field",
				"reuse" 
			];
			
			// console.log( data.labels );
			$.each( data.labels, function( k, v ){
				if( ! $.isEmptyObject( v ) ){
					
					var fl = typeof v.text == 'undefined' ? v.field_label : v.text;
					
					var properties = v;
					properties.display_field_label = fl;
					
					$.each( remove, function( ik, iv ){
						if( typeof( properties[ iv ] ) !== 'undefined' ){
							delete properties[ iv ];
						}
					} );
					
					if( properties["class"] ){
						properties["class"] += ' form-control ';
					}else{
						properties["class"] = ' form-control ';
					}
					properties["reuse"] = 1;
					var kc = typeof properties.field_identifier != 'undefined' ? properties.field_identifier : k;
					nwSearch.databaseFields[ k ] = properties;

					html += ' <option value="'+ kc +'">'+ fl +'</option> ';
				}
			});

			var extra = { 
				'has_comment' : {
					'text' : 'Has Comment',
					'table' : 'comments',
					'form_field' : 'has_comment',
				},
				'has_attachment' : {
					'text' : 'Has Attachment',
					'table' : 'files',
					'form_field' : 'has_attachment',
				}
			};

			if( add_system_fields ){

				var addExtra = { 
					'id' : {
						'text' : 'ID',
						'form_field' : 'id',
					},
					'creation_date' : {
						'text' : 'Creation Date',
						'form_field' : 'creation_date',
					},
					'modification_date' : {
						'text' : 'Modification Date',
						'form_field' : 'modification_date',
					},
					'created_by' : {
						'text' : 'Created By',
						'form_field' : 'created_by',
					},
					'modified_by' : {
						'text' : 'Modified By',
						'form_field' : 'modified_by',
					}
				};

				$.each( addExtra, function( k, v ){
					v.table = nwSearch.parent_table;
					nwSearch.databaseFields[ k ] = v;

					if( ! ( typeof nwSearch.data.fields[ v.table ] !== 'undefined' && typeof nwSearch.data.fields[ v.table ][ k ] !== 'undefined' ) ){
						nwSearch.data.fields[ v.table ][ k ] = v;
						extra[ k ] = v;
					}
				});

			}

			html += '<option value="">----</option>';
			$.each( extra, function( k, v ){
				html += ' <option value="'+ k +'">'+ v.text +'</option> ';
				if( ! $.isEmptyObject( nwSearch.tables ) ){
					$.each( nwSearch.tables, function( tk, tv ){
						if( nwSearch.data.fields[ tk ] )nwSearch.data.fields[ tk ][ k ] = v;
					});
				}else{
					nwSearch.data.fields[ nwSearch.getTableName( form ) ][ k ] = v;
				}
			});

			if( nwSearch.data.select && ! $.isEmptyObject( nwSearch.data.select ) ){
				html += '<option value="">----</option>';
				$.each( nwSearch.data.select, function( kz, vz ){
					switch( vz.aggregation ){
					case 'sort_asc':
					case 'sort_desc':
					case 'group':
					break;
					default:
						html += ' <option value="'+ kz +'">'+ ( vz.alias ? vz.alias : vz.field ) +'</option> ';
					break;
					}
				});
			}

			$( 'form#'+form ).find( 'select[name="table_name"]' ).children().removeAttr( 'selected' );
			$( 'form#'+form ).find( 'select[name="table_name"]' ).find('option[value="'+ data.table +'"]').attr( 'selected', 'selected' );

			if( $( 'div#build-query form#create-select-form' ).length > 0 ){
				$( 'form#create-select-form' ).find( 'select[name="table_name"]' ).children().removeAttr( 'selected' );
				$( 'form#create-select-form' ).find( 'select[name="table_name"]' ).find('option[value="'+ data.table +'"]').attr( 'selected', 'selected' );
			}

		}

		if( ! $.isEmptyObject( nwSearch.tables ) && Object.keys( nwSearch.data.fields ).length == Object.keys( nwSearch.tables).length && nwSearch.refresh ){
			nwSearch.refreshCart();
			nwSearch.refresh = 0;
		}

		if( html ){
			$( 'form#'+form ).find( 'select[name="field"]' ).html( html );
			$( 'form#'+form ).find( 'select[name="field"]' ).removeAttr( 'disabled' );
			if( $( 'div#build-query form#create-select-form' ).length > 0 ){ 
				$( 'form#create-select-form' ).find( 'select[name="field"]' ).html( html );
				$( 'form#create-select-form' ).find( 'select[name="field"]' ).removeAttr( 'disabled' );
			}
		}

		if( nwSearch.show_update ){

			$( 'form#' + nwSearch.updateFormID ).find( 'select[name="field"]' ).html( html );
			$( 'form#' + nwSearch.updateFormID ).find( 'select[name="field"]' ).removeAttr( 'disabled' );

		}

		if( nwSearch.edit_bool ){
			nwSearch.edit2( {}, form );
		}
		nwSearch.logicalOperator();
	},
	closeModal: function(){
		$( 'button#modal-popup-close' ).click();
	},
	databaseFields: {},
	executeChangeField: function(){

	},
	addNewSubQuery: function(){
		if( nwSearch.data && nwSearch.data.cart_items ){
			var last = parseInt( $( 'select[name="sub_query"]' ).children().last().val() );
			
			if( nwSearch.data.cart_items[ last ] ){
				++last;
				var opt = '';
				for( var i = 1; i <= last; i++ ){
					opt += '<option value="'+ i +'" '+ ( last == i ? 'selected' : '' ) +'>'+ i +'</option>';
				};
				$( 'select[name="sub_query"]' ).html( opt );
			}
			nwSearch.logicalOperator();	
		}
	},
	updateSubQueryCondition: function( key ){
		if( key && $( 'select#condition-' + key ) && $( 'select#condition-' + key ).val() ){
			if( nwSearch.data.cart_items && nwSearch.data.cart_items[ key ] ){
				nwSearch.data.cart_items[ key ][ 'condition' ] = $( 'select#condition-' + key ).val();
				nwSearch.refreshCart();
				// console.log( nwSearch.data.cart_items );
			}
		}
	},
	changeCondition: function( id, table_name, form ){
			// console.log( form );
		if( typeof( form ) == 'undefined' ){
			form = 'create-query-form';
		}
		if( ! id ){
			id = $( 'form#'+form ).find( 'select[name="condition"]' ).val();
		}
		if( ! table_name ){
			table_name = $( 'form#'+form ).find( 'select[name="table_name"]' ).val();
			// table_name = nwSearch.getTableName();
		}

		var type;
		var h = '';
		
		if( id ){
			switch( id ){
			case "between_relative":
				type = 'date-relative';
			break;
			case "between":
				type = 'date-5';
			break;
			}

			var cd = '';
			
			if( type && $('#value-container-' + type ) ){
				if( $('#value-container-' + type + '-' + form ).length ){
					h = $('#value-container-' + type + '-' + form ).html();
				}else{
					h = $('#value-container-' + type ).html();
				}
			}
			
		}

		if( h ){
			$( 'form#' +form).find( '#value-container-'+form )
			.html( h );
		}

		let field = $( 'form#'+form ).find( 'select[name="field"]' ).val();

		if( nwSearch.data && nwSearch.data.fields[ table_name ] && nwSearch.data.fields[ table_name ][ nwSearch.fields[ field ] ] ){
			var f1 = nwSearch.data.fields[ table_name ][ nwSearch.fields[ field ] ].form_field;
			switch( f1.trim() ){
				case 'text':

					switch( id ){
						case 'not_empty':
							$( 'form#' +form).find( '#value-container-'+ form ).find(':input[required="required"]').prop( 'required', false );
							$( 'form#' +form).find( '#value-container-'+ form ).hide();
						break;
						default:
							$( 'form#' +form).find( '#value-container-'+ form ).find(':input[required="required"]').prop( 'required', true );
							$( 'form#' +form).find( '#value-container-'+ form ).show();
						break;
					}
				break;
			}
		}
	},
	changeField: function( id, table_name, form ){
		if( typeof( form ) == 'undefined' ){
			form = 'create-query-form';
		}
		if( ! id ){
			id = $( 'form#'+form ).find( 'select[name="field"]' ).val();
		}
		if( ! table_name ){
			table_name = $( 'form#'+form ).find( 'select[name="table_name"]' ).val();
		}
		var type;
		var h = '';
		var addAttr = {};
		var condition = {};
		var setAttribute = '';
		
		if( nwSearch.fields[ id ] ){
			id = nwSearch.fields[ id ];
		}
		if( nwSearch.data && nwSearch.data.fields[ table_name ] && nwSearch.data.fields[ table_name ][ id ] ){
			
			var f1 = nwSearch.data.fields[ table_name ][ id ];
			var f = f1.form_field;
			
			if( f ){
				switch( f ){
				case "has_comment":
				case "has_attachment":
					type = 'yes-no';
					condition[ 'equal_to' ] = 1;
				break;
				case "number":
				case "decimal":
					type = f;
					condition[ 'between' ] = 1;
				break;
				case "date-5":
				case "date-5time":
				case "datetime":

				case "creation_date":
				case "modification_date":
					type = 'date-5';
					condition[ 'between' ] = 1;
					condition[ 'between_relative' ] = 1;
				break;
				case "file":
					h = 'Invalid search field';
				break;
				case "calculated":
					type = 'select2';
					if( f1.calculations && f1.calculations.action && f1.calculations.todo ){
						addAttr[ 'action' ] = '?action=' + f1.calculations.action;
						addAttr[ 'action' ] += '&todo=' + f1.calculations.todo;
						
						if( f1.calculations.todo2 ){
							addAttr[ 'action' ] += f1.calculations.todo2;
						}
						addAttr[ 'tags' ] = true;
					}else if( f1.attributes ){
						setAttribute = f1.attributes;
						//Tags makes the UI misbehave
						// if ( !setAttribute.includes("tags") ) {
						// 	// setAttribute += ' tags="true" ';
						// }
					}
					condition = { "in" : 1, "not_in" : 1 };
				break;
				case "created_by":
				case "modified_by":
					type = 'select2';
					addAttr[ 'action' ] = '?action=users';
					addAttr[ 'action' ] += '&todo=get_users_select2';
					
					addAttr[ 'tags' ] = true;
					condition = { "in" : 1, "not_in" : 1 };
				break;
				case "multi-select":
				case "checkbox":
				case "radio":
				case "select":
					type = 'select2';
					
					if( f1.form_field_options ){
						addAttr[ 'action' ] = '?action=banks&todo=get_list_for_search&function=' + f1.form_field_options;
						
						if( f1.data && f1.data.form_field_options_source == '2' ){
							addAttr[ 'action' ] += '&source=2';
						}
						
						addAttr[ 'minlength' ] = "0";
						addAttr[ 'tags' ] = true;
					}
					condition = { "in" : 1, "not_in" : 1 };
				break;
				default:
					type = 'default';
					condition[ 'equal_to' ] = 1;
					condition[ 'not_equal_to' ] = 1;
					condition[ 'contains' ] = 1;
					switch( f ){
					case "text":
						condition[ 'in' ] = 1;
						condition[ 'not_in' ] = 1;
						condition[ 'not_empty' ] = 1;
						type = f;
					break;
					}
				break;
				}

				if( condition == 'all' ){
					condition = nwSearch.conditions;
				}
				var cd = '';
				
				switch( form ){
				case 'create-select-form':
					var condn = {
	   					"" : "",
	   					"sum" : "SUM",
	   					"count" : "Count",
	   					"avg" : "Average",
	   					"min" : "Min",
	   					"max" : "Max",
	   					"sort_asc" : "Sort ASC",
	   					"sort_desc" : "Sort DESC",
	   					"group" : "Group",
	   					"group_cumulative": "Group Cumulative",
	   					"percentage" : "Percentage",
	   					"daily_date" : "Daily Date",
	   					"weekly_date" : "Weekly Date",
	   					"monthly_date" : "Monthly Date",
	   					"yearly_date" : "Yearly Date",
	   					"avg_resolve_hours" : "Avg. Resolution Time (Hours)",
	   					"avg_resolve_days" : "Avg. Resolution Time (Days)"
	   				};

					switch( type ){
					case "date-5":
					case "date-5time":
					break;
					default:
						delete condn[ 'daily_date' ];
						delete condn[ 'weekly_date' ];
						delete condn[ 'monthly_date' ];
						delete condn[ 'yearly_date' ];
						delete condn[ 'avg_resolve_hours' ];
						delete condn[ 'avg_resolve_days' ];
					break;
					}

					switch( type ){
					case "date-5":
						delete condn[ 'sum' ];
					break;
					case "number":
					case "decimal":

					break;
					default:
						delete condn[ 'sum' ];
						delete condn[ 'avg' ];
						delete condn[ 'min' ];
						delete condn[ 'max' ];
					break;
					}

					$.each( condn, function( k, v ){
						cd += '<option value="'+ k +'">'+ v +'</option>';
					});
					$( 'form#'+ form +' select[name="aggregation"]' ).html( cd );
				break;
				default:
					if( ! $.isEmptyObject( nwSearch.conditions ) ){
						$.each( condition, function( k, v ){
							if( nwSearch.conditions[ k ] ){
								cd += '<option value="'+ k +'">'+ nwSearch.conditions[ k ] +'</option>';
							}
						});
						$( 'select[name="condition"]' ).html( cd );
					}

					
					if( type && $('#value-container-' + type ) ){
						if( $('#value-container-' + type + '-' + form ).length ){
							h = $('#value-container-' + type + '-' + form ).html();
						}else{
							h = $('#value-container-' + type ).html();
						}
					}
				break;
				}
				
			}

			if( setAttribute ){
				h = h.replace( 'setattribute=""', setAttribute );
			}
		
			$( 'form#' +form).find( '#value-container-'+form )
			.html( h );

			$( 'form#' +form).find( '#value-container-'+ form ).show();
			
			if( ! $.isEmptyObject( addAttr ) ){
				$.each( addAttr, function( k1, v1 ){
					$( 'form#' +form).find( '#value-container-'+form )
					.find('.add-attr')
					.attr( k1, v1 );
				});
			}
			
			if( type ){
				switch( type ){
				case "select2":
					$( 'form#' +form).find( '#value-container-'+form )
					.find('.select2-2')
					.addClass( 'select2' );
				break;
				}
				
				switch( type ){
				case "date-5":
				case "select2":
					$.fn.cProcessForm.activateAjaxForm();
				break;
				}
				
			}
		}
	},
	changeLimit: function( id, table_name, form ){
		if( typeof( form ) == 'undefined' ){
			form = 'create-query-form';
		}
		let limitInput = $( 'form#' +form).find('input[name="query_limit"]');
		if( limitInput.hasClass("hidden") ){
			limitInput.removeClass("hidden");
			limitInput.prop("required", true);
		}else{
			limitInput.addClass("hidden");
			limitInput.prop("required", false);
		}
	},
	refreshCart: function( form ){
		if( typeof( form ) == 'undefined' ){
			form = 'create-query-form';
		}
		var html = '';
		var serial = 0;

		var data = {};
		var first = 1;
		var cart_items = {};
		
		switch( form ){
		case 'create-query-form':
			if( nwSearch.data.cart_items && ! $.isEmptyObject( nwSearch.data.cart_items ) && nwSearch.data.fields ){
				
				$.each( nwSearch.data.cart_items, function( kData, vData ){
					if( typeof vData != 'undefined' && ! $.isEmptyObject( vData ) ){
						if( typeof vData.data != 'undefined' && ! $.isEmptyObject( vData.data ) ){
							
							cart_items[ kData ] = { data: {} };
							
							var h1 = '';
							$.each( vData.data, function( k, v ){
	
								if( typeof( v.id ) == 'undefined' ){
									v.id = k;
								}
	
								var sid = 'section-'+ v.id;
								++serial;
								
								var table_name;
								var field_text;
								var field_identifier;
								var fl = v[ 'field' ];
								if( nwSearch.fields[ fl ] ){
									fl = nwSearch.fields[ fl ];
								}
						
								if( v[ 'table_name' ] && fl && nwSearch.data.fields[ v[ 'table_name' ] ] && nwSearch.data.fields[ v[ 'table_name' ] ][ fl ] && nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'table' ] ){
									table_name = nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'table' ];
									field_text = typeof nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'text' ] !== 'undefined' ? nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'text' ] : nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'display_field_label' ];
									field_identifier = nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'field_identifier' ];
								}
								var tbx = nwSearch.getTableName();
								if( ! table_name && nwSearch.data.fields[ tbx ] && nwSearch.data.fields[ tbx ][ fl ] && nwSearch.data.fields[ tbx ][ fl ][ 'table' ] ){
									table_name = nwSearch.data.fields[ tbx ][ fl ][ 'table' ];
									field_text = typeof nwSearch.data.fields[ tbx ][ fl ][ 'text' ] !== 'undefined' ? nwSearch.data.fields[ tbx ][ fl ][ 'text' ] : nwSearch.data.fields[ tbx ][ fl ][ 'display_field_label' ];
									field_text = !field_text && typeof nwSearch.data.fields[ tbx ][ fl ][ 'field_label' ] !== 'undefined' ? nwSearch.data.fields[ tbx ][ fl ][ 'field_label' ] : field_text;
									field_identifier = nwSearch.data.fields[ tbx ][ fl ][ 'field_identifier' ];
								}
	
								var search_value = '';
								
								
								cart_items[ kData ].data[ serial ] = {
									"table_name": v.table_name,
									"field": field_identifier,
									"condition": v.condition,
								};
								
								if( v.search_key ){
									switch( v.search_key ){
									case "options":
										if( v.options_text )search_value = v.options_text;
										if( !search_value ){
											search_value = v.search_value;
										}
										
										cart_items[ kData ].data[ serial ]["search_value"] = v.search_value;
									break;
									case "end_date":
									case "start_date":
										if( v.start_date || v.end_date ){
											cart_items[ kData ].data[ serial ]["start_date"] = v.start_date;
											cart_items[ kData ].data[ serial ]["end_date"] = v.end_date;
											
											if( v.start_date ){
												search_value += 'From: ' + v.start_date;
											}
											if( v.end_date ){
												search_value += ' To: ' + v.end_date;
											}
										}
									break;
									case "to_value":
									case "from_value":
										var frm = '';
										var to = '';
	
										var date_txtt = {
											"y" : "Year(s)",
											"M" : "Month(s)",
											"w" : "Week(s)",
											"d" : "Day(s)",
											"h" : "Hour(s)",
											"H" : "Hour(s)",
											"m" : "Minute(s)",
											"s" : "Second(s)",
										};
	
										if( v.from_type && v.from_value ){
											cart_items[ kData ].data[ serial ]["from_type"] = v.from_type;
											cart_items[ kData ].data[ serial ]["from_value"] = v.from_value;
											
											frm = v.from_value + ' ' + date_txtt[ v.from_type ] + ' ago';
										}
	
										if( v.to_type && v.to_value ){
											cart_items[ kData ].data[ serial ]["to_type"] = v.to_type;
											cart_items[ kData ].data[ serial ]["to_value"] = v.to_value;
											
											to = 'next ' + v.to_value + ' ' + date_txtt[ v.to_type ];
										}
	
										if( typeof v.invert !== 'undefined' && v.invert ){
											search_value += "! ";
										}
	
										if( frm && to ){
											search_value += "Between "+ frm +" and "+to;
										}else{
											search_value += frm +" "+to;
										}
	
									break;
									case "max":
									case "min":
										if( typeof v.min !== 'undefined' && typeof v.max !== 'undefined' ){
											search_value += 'From: ' + v.min + ' To: ' + v.max;
											
											cart_items[ kData ].data[ serial ]["max"] = v.max;
											cart_items[ kData ].data[ serial ]["min"] = v.min;
										}
									break;
									default:
										if( v.text )search_value = v.text;
										cart_items[ kData ].data[ serial ]["search_value"] = v.search_value;
									break;
									}
								}
								
								if( v.logical_operator ){
									cart_items[ kData ].data[ serial ]["logical_operator"] = v.logical_operator;
								}
	
								var tb_text = table_name;
								if( nwSearch.tables[ table_name ] && nwSearch.tables[ table_name ][ 'text' ] ){
									tb_text = nwSearch.tables[ table_name ][ 'text' ];
								}
	
								var trashIcon = 'icon-trash';
								var editIcon = 'icon-edit';
								if( typeof theme_version != 'undefined' ){
									switch( theme_version ){
									case 'v3':
										trashIcon = 'mdi mdi-trash-can';
										editIcon = 'mdi mdi-grease-pencil';
									break;
									}
								}
								
								h1 += ' <tr id="'+ sid +'" data-key="'+ k +'" data-parent="'+ kData +'" data-type="first-level">';
									h1 += '<td>'+ serial +'</td>';
									h1 += '<td>'+ ( v.logical_operator_text ? v.logical_operator_text : '&nbsp;' ) +'</td>';
									h1 += '<td>'+ tb_text +'</td>';
									h1 += '<td>'+ field_text +'</td>';
									h1 += '<td>'+ v.condition_text +'</td>';
									h1 += '<td>'+ search_value +'</td>';
	
									h1 += ' <td class="r"> ';
	
									h1 += ' <a href="#" onclick="nwSearch.delete('+ "'"+ sid +"', '"+ form +"'" +');" title="Remove this Section" class="btn btn-sm dark"> <i class="'+ trashIcon +'"></i> </a>';
	
									h1 += ' <a href="#" onclick="nwSearch.edit('+ "'"+ sid +"', '"+ form +"'" +');" title="Edit this Section" class="btn btn-sm dark"> <i class="'+ editIcon +'"></i> </a>';
									
									h1 += '</td>';
								h1 += '</tr>';
								
							});
							
							var condition = '&nbsp;';
							if( first ){
								first = 0;
							}else{
								var $se = $('select#select-condition').clone();
								$se.find('option[value="'+ vData.condition +'"]').attr( "selected", "selected" );
								
								condition = '<select onchange="nwSearch.updateSubQueryCondition( '+"'" + kData + "'" +' );" id="condition-'+ kData +'">';
									condition += $se.html();
								condition += '</select>';
								
								html += '<tr><td colspan="7" style="text-align:center;">'+ condition +'</td></tr>';
								
							}
							
							html += '<tr><td colspan="7"><strong>Sub-query: '+ kData +'</strong></td></tr>' + h1;
							
						}
					}
				});
			}
		break;
		case 'update-value-form':

			if( nwSearch.data.update && ! $.isEmptyObject( nwSearch.data.update ) && nwSearch.data.fields ){
				
				$.each( nwSearch.data.update, function( k, v ){

					if( typeof( v.id ) == 'undefined' ){
						v.id = k;
					}

					var sid = 'section-'+ v.id;
					++serial;
					
					var table_name;
					var field_text;
					var fl = v[ 'field' ];
					if( nwSearch.fields[ fl ] ){
						fl = nwSearch.fields[ fl ];
					}
			
					if( v[ 'table_name' ] && fl && nwSearch.data.fields[ v[ 'table_name' ] ] && nwSearch.data.fields[ v[ 'table_name' ] ][ fl ] && nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'table' ] ){
						table_name = nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'table' ];
						field_text = typeof nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'text' ] !== 'undefined' ? nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'text' ] : nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'display_field_label' ];
						field_identifier = nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'field_identifier' ];
					}
					var tbx = nwSearch.getTableName();
					if( ! table_name && nwSearch.data.fields[ tbx ] && nwSearch.data.fields[ tbx ][ fl ] && nwSearch.data.fields[ tbx ][ fl ][ 'table' ] ){
						table_name = nwSearch.data.fields[ tbx ][ fl ][ 'table' ];
						field_text = typeof nwSearch.data.fields[ tbx ][ fl ][ 'text' ] !== 'undefined' ? nwSearch.data.fields[ tbx ][ fl ][ 'text' ] : nwSearch.data.fields[ tbx ][ fl ][ 'display_field_label' ];
						field_identifier = nwSearch.data.fields[ tbx ][ fl ][ 'field_identifier' ];
					}

					var search_value = '';
					
					if( v.search_key ){
						switch( v.search_key ){
						case "options":
							if( v.options_text )search_value = v.options_text;
						break;
						case "end_date":
						case "start_date":
							if( v.start_date && v.end_date ){
								search_value += 'From: ' + v.start_date + ' To: ' + v.end_date;
							}
						break;
						case "max":
						case "min":
							if( v.min && v.max ){
								search_value += 'From: ' + v.min + ' To: ' + v.max;
							}
						break;
						default:
							if( v.text )search_value = v.text;
						break;
						}
					}
					
					var trashIcon = 'icon-trash';
					var editIcon = 'icon-edit';
					if( typeof theme_version != 'undefined' ){
						switch( theme_version ){
						case 'v3':
							trashIcon = 'mdi mdi-trash-can';
							editIcon = 'mdi mdi-grease-pencil';
						break;
						}
					}
					
					html += ' <tr id="'+ sid +'" data-key="'+ k +'" data-type="update-level">';
						html += '<td>'+ serial +'</td>';
						html += '<td>'+ table_name +'</td>';
						html += '<td>'+ field_text +'</td>';
						html += '<td>'+ search_value +'</td>';

						html += ' <td class="r"> ';

						html += ' <a href="#" onclick="nwSearch.delete('+ "'"+ sid +"', '"+ form +"'" +');" title="Remove this Section" class="btn btn-sm dark"> <i class="'+ trashIcon +'"></i> </a>';

						html += ' <a href="#" onclick="nwSearch.edit('+ "'"+ sid +"', '"+ form +"'" +');" title="Edit this Section" class="btn btn-sm dark"> <i class="'+ editIcon +'"></i> </a>';
						
						html += '</td>';
					html += '</tr>';
					
				});
			}
		break;
		case 'create-select-form':

			if( nwSearch.data.select && ! $.isEmptyObject( nwSearch.data.select ) && nwSearch.data.fields ){
				let groupFields = [];
				$.each( nwSearch.data.select, function( k, v ){

					if( typeof( v.id ) == 'undefined' ){
						v.id = k;
					}

					var sid = 'section-'+ v.id;
					++serial;
					
					var table_name;
					var field_text;
					var field_identifier;
					var fl = v[ 'field' ];
					if( nwSearch.fields[ fl ] ){
						fl = nwSearch.fields[ fl ];
					}
			
					if( v[ 'table_name' ] && fl && nwSearch.data.fields[ v[ 'table_name' ] ] && nwSearch.data.fields[ v[ 'table_name' ] ][ fl ] && nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'table' ] ){
						table_name = nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'table' ];
						field_text = typeof nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'text' ] !== 'undefined' ? nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'text' ] : nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'display_field_label' ];
						field_identifier = nwSearch.data.fields[ v[ 'table_name' ] ][ fl ][ 'field_identifier' ];
					}

					var tbx = nwSearch.getTableName();
					if( ! table_name && nwSearch.data.fields[ tbx ] && nwSearch.data.fields[ tbx ][ fl ] && nwSearch.data.fields[ tbx ][ fl ][ 'table' ] ){
						table_name = nwSearch.data.fields[ tbx ][ fl ][ 'table' ];
						field_text = typeof nwSearch.data.fields[ tbx ][ fl ][ 'text' ] !== 'undefined' ? nwSearch.data.fields[ tbx ][ fl ][ 'text' ] : nwSearch.data.fields[ tbx ][ fl ][ 'display_field_label' ];
						field_identifier = nwSearch.data.fields[ tbx ][ fl ][ 'field_identifier' ];
					}

					var search_value = '';
					var group = '';
					if( v.group ){
						group = v.group;
					}

					if( v.aggregation == 'group' ){
						groupFields.push( v['field'] );
					}
					
					var trashIcon = 'icon-trash';
					var editIcon = 'icon-edit';
					if( typeof theme_version != 'undefined' ){
						switch( theme_version ){
						case 'v3':
							trashIcon = 'mdi mdi-trash-can';
							editIcon = 'mdi mdi-grease-pencil';
						break;
						}
					}
					
					if( v.aggregation_text )search_value = v.aggregation_text;
					
					html += ' <tr id="'+ sid +'" data-key="'+ k +'" data-type="select-level">';
						html += '<td>'+ serial +'</td>';
						html += '<td>'+ field_text +'</td>';
						html += '<td>'+ search_value +'</td>';
						html += '<td>'+ v.alias +'</td>';

						html += ' <td class="r"> ';

						html += ' <a href="#" onclick="nwSearch.delete('+ "'"+ sid +"', '"+ form +"'" +');" title="Remove this Section" class="btn btn-sm dark"> <i class="'+ trashIcon +'"></i> </a>';

						html += ' <a href="#" onclick="nwSearch.edit('+ "'"+ sid +"', '"+ form +"'" +');" title="Edit this Section" class="btn btn-sm dark"> <i class="'+ editIcon +'"></i> </a>';
						
						html += '</td>';
					html += '</tr>';
					
				});
				
				if ( groupFields.length >= 2 ) {
					const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
				   	const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl, { trigger: 'hover', placement: 'right' }));
					let options = '';
					const tbx = nwSearch.getTableName();
					
					// Build options for chart axes dropdowns
					groupFields.forEach(field => {
						const fl = nwSearch.fields[field] || 'N/A';
						const fieldText = nwSearch.data.fields[tbx][fl]['field_label'] || 
										nwSearch.data.fields[tbx][fl]['display_field_label'];
						options += `<option value="${field}">${fieldText}</option>`;
					});

					// Configure chart axis dropdowns
					const $chartAxis = $('.chart-axis');
					$chartAxis.prop('required', true).html(options);

					// Set selected values if they exist
					let displayOptions = nwSearch.data.report_display_options || {};
					if( !$.isEmptyObject( displayOptions ) ){
						if (displayOptions.display_label_field) {
							$('select[name=x-axis]').val(displayOptions.display_label_field);
						}
						if (displayOptions.field) {
							$('select[name=y-axis]').val(displayOptions.field); 
						}						
					}

					// Show chart options
					$('#chart_options').show();

					// Handle axis selection changes
					$chartAxis.off('change').on('change', function(e) {
						const xval = $('[name="x-axis"]').val();
						const yval = $('[name="y-axis"]').val();
						const pval = $(this).val();
						if (xval === yval) {
							e.preventDefault();
							this.value = $(this).data('previousValue');
							$.fn.cProcessForm.display_notification({
								typ: 'jsuerror',
								err: '<h4><b>Invalid Configuration</b></h4>', 
								msg: '<p>X and Y axes must use different fields</p>'
							});
							return;
						}
						displayOptions.display_label_field = xval;
						displayOptions.field = yval;
						nwSearch.data.report_display_options = displayOptions;
						nwSearch.disableDataOnChange = 1;
						$( 'form#query-execute-form' ).find('textarea[name="data"]').val( JSON.stringify( nwSearch.data ) );
						nwSearch.disableDataOnChange = 0;
					});

				} else {
					// Hide and reset chart options if not enough fields
					$('#chart_options').hide();
					$('.chart-axis').prop('required', false).empty();
				}				
				nwSearch.displayFields( form, nwSearch.parent_table );
			}
		break;
		}
		
		$( '#'+ form +'-table' ).html( html );
		
		nwSearch.disableDataOnChange = 1;
		$( 'form#query-execute-form' ).find('textarea[name="data"]')
		.val( JSON.stringify( nwSearch.data ) );
		nwSearch.disableDataOnChange = 0;
		
		$( 'div#build-query' ).find('textarea[name="cart_items"]')
		.val( JSON.stringify( { dataSource : nwSearch.data.dataSource, cart_items : cart_items }, undefined, 4 ) );
		
		nwSearch.logicalOperator();
		nwSearch.runQuery();
	},
	delete_bool: 0,
	disableDataOnChange : 0,
	logicalOperator: function( field ){
		var tb = $( 'select[name="sub_query"]' ).val();
		var disable = 0;
		if( typeof field != 'undefined' && nwSearch.data.cart_items && nwSearch.data.cart_items && nwSearch.data.cart_items[ tb ] && ! $.isEmptyObject( nwSearch.data.cart_items[ tb ] ) ){
			if( field && nwSearch.edit_bool && Object.keys( nwSearch.data.cart_items[ tb ].data ).indexOf( field ) == 0 ){
				disable = 1;
				nwSearch.edit_bool = 0;
			}
			if( nwSearch.edit_bool && Object.keys( nwSearch.data.cart_items[ tb ].data ).length == 1 ){
				disable = 1;
				nwSearch.edit_bool = 0;
			}
		}else{
			disable = 1;
			if( nwSearch.data.cart_items && nwSearch.data.cart_items[ tb ] && nwSearch.data.cart_items[ tb ].data ){
				if( Object.keys( nwSearch.data.cart_items[ tb ].data ).length >= 1 ){
					disable = 0;
				}
			}
		}

		if( disable ){
			$( 'form#create-query-form' ).find( '#logical-operator' ).find( 'option[value=""]' ).attr( 'selected', 'selected' );
			$( 'form#create-query-form' ).find( '#logical-operator' ).attr( 'disabled', 'disabled' );
		}else{
			$( 'form#create-query-form' ).find( '#logical-operator' ).removeAttr( 'disabled' );
		}
	},
	submitDataForm: function(){
		
		$("form.client-form").off('submit');

		$("form.client-form")
		.on('submit', function(e){
			e.preventDefault();
			
			var err = "";
			var msg = "";
			
			var data = {};
			$(this)
			.find('.form-control,input[type="checkbox"],input[type="radio"]')
			.each(function(){
				var val = $(this).val();
				
				if( $(this).attr("type2") ){
					switch( $(this).attr("type2") ){
					case "search_value":
						if( ! data["search"] ){
							data["search"] = {};
						}
						
						data["search_key"] = $(this).attr("name");
						data["search_value"] = $(this).val();
						
					break;
					}
				}
				
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
					
					data[ n + "_text" ] = $(this).children('option:selected').text().trim();
				break;
				case "checkbox":
				case "radio":
					if( $(this).is(':checked') ){
						var n = $(this).attr("name");
						val = $(this).val();
						data[ n + "_text" ] = $(this).parent().text().trim();
					}else{
						val = '';
					}
				break;
				}
				
				data[ $(this).attr("name") ] = val;
			});

			var id = nwSearch.uniqueKey( 1, 100 );
			
			var form = $(this).attr("id");
				
			if( data["id"] ){
				id = data["id"];
				$( this ).find( 'input[name="id"]' ).val( '' );
			}
			
			switch( form ){
			case "create-query-form":
				nwSearch.getTableName();
				if( nwSearch.tableOptions["db_table"] ){
					data["db_table"] = nwSearch.tableOptions["db_table"];
				}
				
				if( ! nwSearch.data.cart_items ){
					nwSearch.data.cart_items = {};
				}

				if( ! nwSearch.data.cart_items[ data[ 'sub_query' ] ] ){
					nwSearch.data.cart_items[ data[ 'sub_query' ] ] = {};
				}

				if( ! nwSearch.data.cart_items[ data[ 'sub_query' ] ][ 'data' ] ){
					nwSearch.data.cart_items[ data[ 'sub_query' ] ][ 'data' ] = {};
				}
				
				if( data[ 'sub_query' ] !== '1' ){
					if( ! nwSearch.data.cart_items[ data[ 'sub_query' ] ][ 'condition' ] ){
						nwSearch.data.cart_items[ data[ 'sub_query' ] ][ 'condition' ] = 'AND';
					}
				}else{
					nwSearch.data.cart_items[ data[ 'sub_query' ] ][ 'condition' ] = '';
				}
				
				if( data["id"] ){
					id = data["id"];
					$( this ).find( 'input[name="id"]' ).val( '' );
				}
				
				if( typeof data[ 'search_key' ] != 'undefined' && data[ 'search_key' ] == 'text' ){
					switch( data[ 'condition' ] ){
					case 'in':
					case 'not_in':
					case 'not_empty':
					break;
					default:
						data[ 'text' ] = data[ 'text' ].replace( /(?:\r\n|\r|\n)/g, ' ' );
						data[ 'search_value' ] = data[ 'search_value' ].replace( /(?:\r\n|\r|\n)/g, ' ' );
					break;
					}
				}

				switch( data[ 'field' ] ){
				case 'has_comment':
				case 'has_attachment':
					if( nwSearch.data.fields[ nwSearch.getTableName() ][ data[ 'field' ] ].table ){
						data[ 'table_name' ] = nwSearch.data.fields[ nwSearch.getTableName() ][ data[ 'field' ] ].table;
					}
				break;
				}

				let formField = nwSearch.data && nwSearch.data.fields && nwSearch.data.fields[ data.table_name ] && nwSearch.data.fields[ data.table_name ][ data.field ] && nwSearch.data.fields[ data.table_name ][ data.field ].form_field ? nwSearch.data.fields[ data.table_name ][ data.field ].form_field : '';
				switch( data.condition ){
				case "between":
					switch(formField){
						case 'date':
						case 'date-5':
						case 'date-5time':
							if( ! ( ( typeof data.start_date !== 'undefined' && data.start_date ) || ( typeof data.end_date !== 'undefined' && data.end_date ) ) ){
								msg = 'Please choose either a start date or an end date';
								err = 'Missing Date(s)';
							}else{
								if( typeof data.start_date == 'undefined' ){
									data.start_date = '';
								}
								if( typeof data.end_date == 'undefined' ){
									data.end_date = '';
								}
							}
						break;
						case 'number':
						case 'decimal':
						case 'decimal_long':
							if( ! ( ( typeof data.min !== 'undefined' && data.min ) || ( typeof data.max !== 'undefined' && data.max ) ) ){
								msg = 'Please choose either a minimum or a maximum';
								err = 'Missing Range';
							}else{
								if( typeof data.min == 'undefined' ){
									data.min = 0;
								}
								if( typeof data.max == 'undefined' ){
									data.max = 0;
								}
							}
						break;
					}
				break;
				case "between_relative":
					switch(formField){
						case 'date':
						case 'date-5':
						case 'date-5time':
							if( ( typeof data.from_type !== 'undefined' && data.from_type ) && ! ( typeof data.from_value !== 'undefined' && data.from_value ) ){
								msg = 'Please select a value for the From else deselect the From Type';
								err = 'Missing Date(s)';
							}
							if( ( typeof data.to_type !== 'undefined' && data.to_type ) && ! ( typeof data.to_value !== 'undefined' && data.to_value ) ){
								msg = 'Please select a value for the To else deselect the To Type';
								err = 'Missing Date(s)';
							}
						break;
						case 'number':
						case 'decimal':
						case 'decimal_long':
							if( ! ( ( typeof data.min !== 'undefined' && data.min ) || ( typeof data.max !== 'undefined' && data.max ) ) ){
								msg = 'Please choose either a minimum or a maximum';
								err = 'Missing Range';
							}else{
								if( typeof data.min == 'undefined' ){
									data.min = 0;
								}
								if( typeof data.max == 'undefined' ){
									data.max = 0;
								}
							}
						break;
					}
				break;
				}

				data["id"] = id;
				
			break;
			case 'create-select-form':
				if( ! nwSearch.data.select ){
					nwSearch.data.select = {};
				}

				if( ! nwSearch.data.select[ id ] ){
					nwSearch.data.select[ id ] = {};
				}
			
				data["id"] = id;
				data["table_name"] = nwSearch.parent_table;
			break;
			case 'update-value-form':
				if( ! nwSearch.data.update ){
					nwSearch.data.update = {};
				}

				if( ! nwSearch.data.update[ id ] ){
					nwSearch.data.update[ id ] = {};
				}
			
				data["id"] = id;
			break;
			default:
				err = "Invalid Input";
				msg = "Please try again or contact technical support team";
			break;
			}
			
			if( err ){
				var data = {theme:'alert-danger', err:err, msg:msg, typ:'jsuerror' };
				nwDisplayNotification.display_notification( data );
				return false;
			}else{


				switch( form ){
				case "create-query-form":
					nwSearch.data.cart_items[ data[ 'sub_query' ] ][ 'data' ][ id ] = data;
				break;
				case 'create-select-form':
					nwSearch.data.select[ id ] = data;
				break;
				case 'update-value-form':
					nwSearch.data.update[ id ] = data;
				break;
				}
				

				nwSearch.refreshCart( form );
			}			
		});
	},
	delete: function( id, form ){

		var a = $( 'tr#' + id );
		var type = a.attr( 'data-type' );

		var parent = a.attr( 'data-parent' );
		var child = a.attr( 'data-key' );

		switch( type ){
		case 'first-level':
			if( nwSearch.data.cart_items[ parent ] && ! $.isEmptyObject( nwSearch.data.cart_items[ parent ] ) ){
				if( nwSearch.data.cart_items[ parent ].data && ! $.isEmptyObject( nwSearch.data.cart_items[ parent ].data ) ){
					if( nwSearch.data.cart_items[ parent ].data[ child ] ){
						nwSearch.delete_bool = 1;
						delete nwSearch.data.cart_items[ parent ].data[ child ];
					}
					if( $.isEmptyObject( nwSearch.data.cart_items[ parent ].data ) ){
						delete nwSearch.data.cart_items[ parent ];
					}else if( Object.keys( nwSearch.data.cart_items[ parent ].data ).length == 1 ){
						$.each( nwSearch.data.cart_items[ parent ].data, function( k,v ){
							nwSearch.data.cart_items[ parent ].data[ k ].logical_operator = '';
							nwSearch.data.cart_items[ parent ].data[ k ].logical_operator_text = '';
						});
					}
				}
			}

		break;
		case 'update-level':

			if( nwSearch.data.update && ! $.isEmptyObject( nwSearch.data.update ) ){
				if( nwSearch.data.update[ child ] && ! $.isEmptyObject( nwSearch.data.update[ child ] ) ){
					delete nwSearch.data.update[ child ];
				}
			}

		break;
		case 'select-level':

			if( nwSearch.data.select && ! $.isEmptyObject( nwSearch.data.select ) ){
				if( nwSearch.data.select[ child ] && ! $.isEmptyObject( nwSearch.data.select[ child ] ) ){
					delete nwSearch.data.select[ child ];
				}
			}

		break;
		}
	
		nwSearch.refreshCart( form );
	},
	edit_bool: 0,
	editFields: {},
	edit: function( id, form ){

		if( typeof( form ) == 'undefined' ){
			form = 'create-query-form';
		}

		var a = $( 'tr#' + id );
		var type;
		if( a ){
			type = a.attr( 'data-type' );
		}else{
			type = 'section';
		}

		var parent = a.attr( 'data-parent' );
		var child = a.attr( 'data-key' );

		switch( type ){
		case 'first-level':
			if( nwSearch.data.cart_items[ parent ] && ! $.isEmptyObject( nwSearch.data.cart_items[ parent ] ) ){
				if( nwSearch.data.cart_items[ parent ].data && ! $.isEmptyObject( nwSearch.data.cart_items[ parent ].data ) ){
					if( nwSearch.data.cart_items[ parent ].data[ child ] && ! $.isEmptyObject( nwSearch.data.cart_items[ parent ].data[ child ] ) ){

						var fields = nwSearch.data.cart_items[ parent ].data[ child ];

						nwSearch.edit_bool = 1;
						if( fields.table_name !== $( 'form#'+form ).find( 'select[name="table_name"]' ).val() ){
							nwSearch.editFields = fields;

							$( '#'+form ).find( 'select[name="table_name"]' ).find( 'option[value="'+ fields.table_name +'"]' ).attr( 'selected', 'selected' );
							nwSearch.displayFields( form, fields.table_name );
							// nwSearch.searchDatabaseTableForForms( nwSearch.getTableName( form ) );
						}else{
							nwSearch.edit2( fields, form );
						}
							nwSearch.logicalOperator( child );	

						$( '#'+form ).find( 'id' ).val( id );
					}
				}
			}
		break;
		case 'update-level':
			if( nwSearch.data.update && ! $.isEmptyObject( nwSearch.data.update ) ){
				if( nwSearch.data.update[ child ] && ! $.isEmptyObject( nwSearch.data.update[ child ] ) ){

					var fields = nwSearch.data.update[ child ];

					nwSearch.edit_bool = 1;
					// console.log( fields )
					if( fields.table_name !== $( 'form#'+form ).find( 'select[name="table_name"]' ).val() ){
						nwSearch.editFields = fields;

						$( '#'+form ).find( 'select[name="table_name"]' ).find( 'option[value="'+ fields.table_name +'"]' ).attr( 'selected', 'selected' );
						nwSearch.displayFields( form, fields.table_name );
					}else{
						nwSearch.edit2( fields, form );
					}
						nwSearch.logicalOperator( child );	

					$( '#'+form ).find( 'id' ).val( id );
				}
			}
		break;
		case 'select-level':
			if( nwSearch.data.select && ! $.isEmptyObject( nwSearch.data.select ) ){
				if( nwSearch.data.select[ child ] && ! $.isEmptyObject( nwSearch.data.select[ child ] ) ){

					var fields = nwSearch.data.select[ child ];

					nwSearch.edit_bool = 1;
					// console.log( fields )
					if( fields.table_name !== $( 'form#'+form ).find( 'select[name="table_name"]' ).val() ){
						nwSearch.editFields = fields;

						$( '#'+form ).find( 'select[name="table_name"]' ).find( 'option[value="'+ fields.table_name +'"]' ).attr( 'selected', 'selected' );
						nwSearch.displayFields( form, fields.table_name );
					}else{
						nwSearch.edit2( fields, form );
					}
						nwSearch.logicalOperator( child );	

					$( '#'+form ).find( 'id' ).val( id );
				}
			}
		break;
		}

		nwSearch.changeCondition();	

	},
	edit2: function( fields = {}, form ){
		if( $.isEmptyObject( fields ) )fields = nwSearch.editFields;

		nwSearch.changeField( fields.field, fields.table_name, form );

		// console.log(  )
		$.each( fields, function( k, v ){
			switch( k ){
			case 'field':
			case 'sub_query':
			case 'condition':
			case 'logical_operator':
				$( '#'+form ).find( 'select[name="'+ k +'"]' ).find( 'option[value="'+ v +'"]' ).attr( 'selected', 'selected' );
			break;
			case 'options':
				$( '#'+form ).find( 'input[name="'+ k +'"]' ).select2( 'data', fields[ k + '_tags' ] );
			break;
			default:
				$( '#'+form ).find( 'input[name="'+ k +'"]' ).val( v );
			break;
			}
		});
		nwSearch.editFields = {};
	},
	uniqueKey: function( min, max ){
		var launch_date = new Date();
		return 'd' + launch_date.getTime(); // + 'd' + Math.floor(Math.random() * (max - min + 1) + min);
	},
	activeRefreshForm:'',
	url:g_site_url + "php/ajax_request_processing_script.php",
	search: function () {
		if( ! nwSearch.activeRefreshForm ){
			nwSearch.activeRefreshForm = "form#customer";
		}
		
		$( nwSearch.activeRefreshForm ).submit();
	},
		
};
