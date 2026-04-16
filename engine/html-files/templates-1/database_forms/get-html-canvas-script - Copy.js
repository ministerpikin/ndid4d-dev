(function($) {
	$.fn.dragDropCanvas = {
		newTable: false,
		formFieldProperties: {},
		DOMform: '',
		fieldKey: '',
		fieldClass: 'inputs-list login-form field',
		serial_num: 0,
		fieldSerial: 0,
		canvas: $('#droppable'),
		database_table: '',
		form_name: '',
		databaseFields: {},
		init: function(){
			/*
			$( "#accordion" ).accordion({
		      heightStyle: "content"
		    });
			*/
			
			$('.tool').draggable({ 'helper' : 'clone' });

			$.fn.dragDropCanvas.canvas.droppable({
		      drop: function( event, ui ) {
		      	if( ui.helper.context.id != 'column' ){
		      		//console.log( event.toElement.className );
		      		if( event.target.className.search( 'demo-col' ) == -1 ){
			      		if( ui.helper.hasClass( 'databaseTool' ) ){
			      			var id = ui.helper.context.id;

			      			$.fn.dragDropCanvas.formFieldProperties[ id ] = $.fn.dragDropCanvas.databaseFields[ id ];

			      			$.fn.dragDropCanvas.createForm( $.fn.dragDropCanvas.formFieldProperties );
			      		}else{
							var tool = {
								id: $.fn.dragDropCanvas.uniqueKey( 0, 1000 ),
								form_field: ui.helper.context.id,
								unprocessed: 1,
							};
							$.fn.dragDropCanvas.formFieldProperties[ tool.form_field + tool.id ] = tool;
							// $.fn.dragDropCanvas.form.push(tool);
							// console.log($.fn.dragDropCanvas.form);
							var key = tool.form_field + tool.id;
							$.fn.dragDropCanvas.createForm( $.fn.dragDropCanvas.formFieldProperties );
						}
						if( $( '#field-options' )[0].hasAttribute( 'field' ) ){
							$.fn.dragDropCanvas.closeSettingsForm( $( '#field-options' ).attr( 'field' ) );
						}
					}
				}else{
					return;
				}
			  },
		    });
		   
		},
		displayFormData: function(){
			var data = <?php echo $form_data; ?>;
			
			$.fn.dragDropCanvas.formFieldProperties = data;
			$.fn.dragDropCanvas.createForm( $.fn.dragDropCanvas.formFieldProperties );
		},
		displayOptions: function( klass, type ){

			$( 'div.' + klass ).hide().find( 'input, textarea' ).val( '' );

			$( 'div#' + type ).show();
		},
		clear: function(){
			$('form').trigger('reset');
			if( $('form').find("input.select2") ){
				$('form').find("input.select2").select2("val", "");
			}
			if( $('form').find("input.uploaded-file") ){
				$('form').find("input.uploaded-file").val("");
				$('form').find(".qq-upload-list").html("");
			}
			$.fn.dragDropCanvas.formFieldProperties = {};
			$.fn.dragDropCanvas.createForm( $.fn.dragDropCanvas.formFieldProperties );
			$.fn.dragDropCanvas.fieldSerial = 0;

			$( '.tools_from_database' ).html( '' );
		},
		searchDatabaseTableForForms: function( searchDatabaseTable ){
			var id = $( '#' + searchDatabaseTable ).val();

			$.fn.cProcessForm.function_click_process = 1;
			$.fn.cProcessForm.ajax_data = {
				ajax_data: { tableID: id, callback: '$.fn.dragDropCanvas.prepareDraggableListFromDatabase', },
				form_method: 'post',
				ajax_data_type: 'json',
				ajax_action: 'request_function_output',
				ajax_container: '',
				ajax_get_url: "?action=database_table&todo=get_form_fields",
			};
			$.fn.cProcessForm.ajax_send();
		},
		prepareDraggableListFromDatabase: function(){
			// alert();
			var html = '';
			if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.data ){
				//console.log( $.fn.cProcessForm.returned_ajax_data.data );
				var data = $.fn.cProcessForm.returned_ajax_data.data;

				$.each( data, function( k, v ){
					if( ! $.isEmptyObject( v ) ){
						
						var fl = ( ( v.display_field_label ) ? v.display_field_label : v.field_label );
						
						var properties = v;
						properties.display_field_label = fl;
						if( properties["created_by"] ){
							delete properties["created_by"];
						}
						if( properties["modified_by"] ){
							delete properties["modified_by"];
						}
						if( properties["creation_date"] ){
							delete properties["creation_date"];
						}
						if( properties["modification_date"] ){
							delete properties["modification_date"];
						}
						if( properties["serial_num"] ){
							delete properties["serial_num"];
						}
						if( properties["class"] ){
							properties["class"] += ' form-control ';
						}else{
							properties["class"] = ' form-control ';
						}
						properties["reuse"] = 1;
						/*
						var properties = {
							form_field : v.form_field,
							display_field_label : fl,
							value : v.value,
							id: v.id,
							field_label: v.field_label,
							required_field: v.required_field,
							class: 'form-control',
							tooltip: v.tooltip,
							note: v.note,
							attributes: v.attributes,
							placeholder: v.placeholder,
							serial_number: v.serial_number,
							display_position: v.display_position,
							default_appearance_in_table_fields: v.default_appearance_in_table_fields,
							calculations: v.calculations,
						};
						*/

						$.fn.dragDropCanvas.databaseFields[ v.id ] = properties;

						html += ' <li class="databaseTool" id="'+ v.id +'">'+ fl +'</li> ';
					}
				});
			}

			$( '.tools_from_database' ).html( html );

			$('.databaseTool').draggable({ 'helper' : 'clone' });
		},
		createFormField: function( form_field, id, row_id, column_id ){
			$.fn.dragDropCanvas.fieldKey = form_field + id;
			if( $.fn.dragDropCanvas.formFieldProperties[ id ] )$.fn.dragDropCanvas.fieldKey = id;
			var field;

			switch( form_field ){
				case 'column':
					if( !$.fn.dragDropCanvas.formFieldProperties[ row_id ].columns[ $.fn.dragDropCanvas.fieldKey ] ){
						$.fn.dragDropCanvas.formFieldProperties[ row_id ].columns[ $.fn.dragDropCanvas.fieldKey ] = {};
					}

					$.fn.dragDropCanvas.formFieldProperties[ row_id ].columns[ $.fn.dragDropCanvas.fieldKey ].form_field = form_field;
					$.fn.dragDropCanvas.formFieldProperties[ row_id ].columns[ $.fn.dragDropCanvas.fieldKey ].id = $.fn.dragDropCanvas.fieldKey;

		      		$.fn.dragDropCanvas.createForm( $.fn.dragDropCanvas.formFieldProperties );
				break;
				case 'row':
					var new_key = $.fn.dragDropCanvas.fieldKey;//form_field + id;
					fieldProperties = {
						form_field : form_field,
						columns : {},
						id: $.fn.dragDropCanvas.fieldKey,
					};
					// Check if row already exist or it is a new row
					if( $.fn.dragDropCanvas.formFieldProperties[ $.fn.dragDropCanvas.fieldKey ] ){
						if( $.fn.dragDropCanvas.formFieldProperties[ $.fn.dragDropCanvas.fieldKey ].unprocessed ){
							$.fn.dragDropCanvas.formFieldProperties[ new_key ] = fieldProperties;
						}
					}else{
						if( row_id ){
							if( $.fn.dragDropCanvas.formFieldProperties[ row_id ].columns ){
								$.fn.dragDropCanvas.formFieldProperties[ new_key ] = fieldProperties;

								Object.keys( $.fn.dragDropCanvas.formFieldProperties[ row_id ].columns ).forEach((key, value) => {
									var column_key = 'column'+$.fn.dragDropCanvas.uniqueKey( 1, 1000 );
									$.fn.dragDropCanvas.formFieldProperties[ new_key ].columns[ column_key ] = {};

									$.fn.dragDropCanvas.formFieldProperties[ new_key ].columns[ column_key ].id = column_key;
									$.fn.dragDropCanvas.formFieldProperties[ new_key ].columns[ column_key ].form_field = 'column';

									if( $.fn.dragDropCanvas.formFieldProperties[ row_id ].columns[ key ].fields ){
										for( var field in $.fn.dragDropCanvas.formFieldProperties[ row_id ].columns[ key ].fields ){
											var form_field = $.fn.dragDropCanvas.formFieldProperties[ row_id ].columns[ key ].fields[ field ].form_field;
											var fieldKey = $.fn.dragDropCanvas.uniqueKey( 0, 1000 );

											$.fn.dragDropCanvas.createFormField( form_field, fieldKey, new_key, column_key );
										}
										// $.fn.dragDropCanvas.fieldKey = row_id;
									}
								});

								return;	
							}
						}
					}
					var rowID = $.fn.dragDropCanvas.uniqueKey( 1, 1000 );
					field = '<div class="'+form_field+' demo-row ui-widget-header" id="'+$.fn.dragDropCanvas.fieldKey+'"><span class="options" key="'+ rowID +'" serial="'+$.fn.dragDropCanvas.fieldKey+'" id="'+$.fn.dragDropCanvas.fieldKey+'" row="'+$.fn.dragDropCanvas.fieldKey+'"> <a href="#" onclick="$.fn.dragDropCanvas.delete('+ "'"+ rowID +"'" +');" class="btn btn-sm btn-default"><i class="icon-minus" ></i></a> <a href="#" class="btn btn-sm btn-default" onclick="$.fn.dragDropCanvas.duplicate('+ "'"+ rowID +"'" +');"><i class="icon-plus"></i></a></span>';

					var columns = $.fn.dragDropCanvas.formFieldProperties[ $.fn.dragDropCanvas.fieldKey ].columns;
					if( columns ){
						var col_count = Object.keys(columns).length;
						var col = 12 / col_count;
						var icon_show = 0;
						
						Object.keys( columns ).forEach((key, value) => {
							
							var columnID = $.fn.dragDropCanvas.uniqueKey( 1, 1000 );
							
							var icon = '<a href="#" onclick="$.fn.dragDropCanvas.duplicate('+ "'"+ columnID +"'" +');" class="btn btn-sm btn-default"><i class="icon-plus dup_col"></i></a>';
							
							if( icon_show == 0 ){
								icon_show = 1;
							}else{
								icon += ' <a href="#" onclick="$.fn.dragDropCanvas.delete('+ "'"+ columnID +"'" +');" class="btn btn-sm btn-default"><i class="icon-minus"></i></a> ';
							}

							field += '<div class="col-md-'+col+' demo-col ui-widget-header" id="'+columns[ key ].id+'"><span key="'+columnID+'" class="options" row="'+$.fn.dragDropCanvas.fieldKey+'" column="'+key+'">'+icon+'</span>';

							if( columns[ key ].fields ){
								for( var columnField in columns[ key ].fields ){
									var a = columns[ key ].fields[ columnField ];

									var fieldKey = $.fn.dragDropCanvas.uniqueKey(1,1000);
									field += '<div class="hey"><span class="options" field="'+columnField+'" key="'+fieldKey+'" data-type="show" row="'+$.fn.dragDropCanvas.fieldKey+'" column="'+key+'"> <a href="#" onclick="$.fn.dragDropCanvas.fieldOptions('+ "'"+ fieldKey +"'" +');" class="btn btn-sm btn-default pull-right"><i class="icon-chevron-down" ></i></a> <a href="#" onclick="$.fn.dragDropCanvas.delete('+ "'"+ fieldKey +"'" +');" class="btn btn-sm btn-default pull-right"><i class="icon-remove" ></i></a></span><div>';

										field += $.fn.dragDropCanvas.createFormField2( columns[ key ].fields[ columnField ] );

									field += '</div></div>';
								}
							}
							field += '</div>';
							$( $.fn.dragDropCanvas.fieldKey ).addClass( "ui-state-highlight" );
						});

					}
					field += "</div>";
				break;
				default:
					$.fn.dragDropCanvas.fieldSerial += 10;

					var form_field = form_field;
					var value = form_field;
					var id = $.fn.dragDropCanvas.fieldKey;
					var field_label = $.fn.dragDropCanvas.fieldKey;
					var display_field_label = form_field;
					var required_field = 'no';
					var attributes = '';
					var clas = 'form-control';
					var serial_number = $.fn.dragDropCanvas.fieldSerial;
					var tooltip = '';
					var note = '';
					var placeholder = '';
					var display_position = 'yes';
					var default_appearance_in_table_fields = 'yes';
					var calculations = '';
					var other_parameters = '';

					var options = 'optionKey:optionValue;';

					var tag = 'h3';

					if( $.fn.dragDropCanvas.formFieldProperties[ $.fn.dragDropCanvas.fieldKey ] ){
						if( ! $.fn.dragDropCanvas.formFieldProperties[ $.fn.dragDropCanvas.fieldKey ].unprocessed ){
							var a = $.fn.dragDropCanvas.formFieldProperties[ $.fn.dragDropCanvas.fieldKey ];

							form_field = a.form_field;
							value = a.value ? a.value : value ;
							id = a.id ? a.id : id ;
							field_label = a.field_label ? a.field_label : field_label ;
							display_field_label = a.display_field_label ? a.display_field_label : display_field_label ;
							required_field = a.required_field ? a.required_field : required_field ;
							attributes = a.attributes ? a.attributes : attributes ;
							clas = a.class ? a.class : clas ;
							serial_number = a.serial_number ? a.serial_number : serial_number ;
							tooltip = a.tooltip ? a.tooltip : tooltip ;
							note = a.note ? a.note : note ;
							placeholder = a.placeholder ? a.placeholder : placeholder ;
							display_position = a.display_position ? a.display_position : display_position ;
							default_appearance_in_table_fields = a.default_appearance_in_table_fields ? a.default_appearance_in_table_fields : default_appearance_in_table_fields ;
							calculations = a.calculations ? a.calculations : calculations ;
							other_parameters = a.other_parameters ? a.other_parameters : other_parameters ;

							options = a.options ? a.options : options ;

							tag = a.tag ? a.tag : tag ;
						}
					}

					var	fieldProperties = {
						form_field : form_field,
						display_field_label : display_field_label,
						value : value,
						id: $.fn.dragDropCanvas.fieldKey,
						field_label: field_label,
						required_field: required_field,
						class: clas,
						tooltip: tooltip,
						note: note,
						attributes: attributes,
						placeholder: placeholder,
						serial_number: serial_number,
						display_position: display_position,
						default_appearance_in_table_fields: default_appearance_in_table_fields,
						calculations: calculations,
						other_parameters: other_parameters,
					};

					switch( form_field ){
					case 'multi-select':
					case 'select':
					case 'radio':
					case 'checkbox':
						switch( form_field ){
							case 'radio':
							case 'checkbox':
								fieldProperties.class = '';
							break;
							case 'multi-select':
								fieldProperties.attributes = " multiple='multiple' ";
							break;
						}
						fieldProperties.options = options;
					break;
					case 'calculated':
						fieldProperties.calculations = 'type: record-details; form_field: text; multiple: 1; show_in_form: 1; reference_table: database_objects; reference_keys: object_name, ; ';
					break;
					case 'header':

						if( display_field_label == form_field ){
							display_field_label = 'Header';
						}

						fieldProperties = {
							display_field_label : display_field_label,
							form_field : form_field,
							id : id,
							tag : tag,
							class: '',
							attributes: attributes,
							display_position: 'yes',
							default_appearance_in_table_fields: 'yes',
						};
					break;
					default:

						switch( form_field ){
							case 'button':
								delete fieldProperties.display_field_label;
							break;
							case 'file':
								fieldProperties.acceptable_files_format = '';
							break;
						}

					break;
					}
 
					field = $.fn.dragDropCanvas.createFormField2( fieldProperties );

					if( $.fn.dragDropCanvas.formFieldProperties[ $.fn.dragDropCanvas.fieldKey ] ){
						if( $.fn.dragDropCanvas.formFieldProperties[ $.fn.dragDropCanvas.fieldKey ].unprocessed	 ){
							$.fn.dragDropCanvas.formFieldProperties[ $.fn.dragDropCanvas.fieldKey ] = fieldProperties;
						}
					}else{
						if( row_id && column_id ){
							if( ! $.fn.dragDropCanvas.formFieldProperties[ row_id ].columns[ column_id ].fields )
								$.fn.dragDropCanvas.formFieldProperties[ row_id ].columns[ column_id ].fields = {};
							$.fn.dragDropCanvas.formFieldProperties[ row_id ].columns[ column_id ].fields[ $.fn.dragDropCanvas.fieldKey ] = fieldProperties;
						}
					}
				break;
			}

			// console.log( $.fn.dragDropCanvas.formFieldProperties );
			// console.log( $.fn.dragDropCanvas.form );
			return field;
		},
		createFormField2: function( fieldProperties ){
			if( fieldProperties && ! $.isEmptyObject( fieldProperties ) ){
				var field;

				var form_field = fieldProperties.form_field ? fieldProperties.form_field : '';
				var field_label = fieldProperties.field_label ? fieldProperties.field_label : '';
				var value = fieldProperties.value ? fieldProperties.value : '';
				var id = fieldProperties.id ? fieldProperties.id : '';
				var field_label = fieldProperties.field_label ? fieldProperties.field_label : '';
				var display_field_label = fieldProperties.display_field_label ? fieldProperties.display_field_label : '';
				var clas = fieldProperties.class ? fieldProperties.class : '';
				var attributes = fieldProperties.attributes ? fieldProperties.attributes : '';
				var tag = fieldProperties.tag ? fieldProperties.tag : '';
				var placeholder = fieldProperties.placeholder ? fieldProperties.placeholder : '';
				var options = fieldProperties.options ? fieldProperties.options : '';
				var required_field = ( fieldProperties.required_field == 'yes'?'required':'');

				field = ( display_field_label ) ? '<label>'+display_field_label+'</label>' : '';

				switch( fieldProperties.form_field ){
				case 'multi-select':
				case 'select':
				case 'radio':
				case 'checkbox':

					var DOMoptions = {};
					var KeyVal = options.split(";");

					console.log( KeyVal );

					$.each( KeyVal, function( k, v ){
						var key = 'options' + $.fn.dragDropCanvas.uniqueKey( 1, 1000 );

						if( v && v.trim() != '' ){
							var option = v.split( ':' );

							DOMoptions[ option[ 0 ].trim() ] = option[ 1 ].trim();
						}
					});

					switch( fieldProperties.form_field ){
						case 'multi-select':
						case 'select':
							field += '<select id="'+id+'" class="'+ clas +'" placeholder="'+ placeholder +'" '+ attributes +' name="'+field_label+'" '+ required_field +'>';

							for( var option in DOMoptions ){
								field += '<option value="'+ option +'" id="'+option+'">'+DOMoptions[ option ]+'</option>';
							}
							field += '</select>';
						break;
						case 'checkbox':
						case 'radio':
						field += '<div class="radio-listx">';
							for( var option in DOMoptions ){
								field += '<label class="radio-inlinex" style="margin-right:15px;">';
								field += '<input type="'+ form_field +'" id="'+id+'" value="'+ DOMoptions[ option ] +'" class="'+ clas +'" placeholder="'+ placeholder +'" '+ attributes +' name="'+field_label + ( fieldProperties.form_field == 'checkbox' ? '[]' : '' ) + '" '+ required_field +'>';
								field += option + '</label>';
							}
						field += '</div>';
						break;
					}
				break;
				case 'header':

					field = '<'+ tag +' class="'+ clas +'" '+ attributes +' >'+ display_field_label +'</'+ tag +'>';
				break;
				case 'textarea':
				case 'textarea-unlimited':
					field += '<textarea class="'+ clas +'" '+ attributes +' value="'+value+'" placeholder="'+ placeholder +'" type="text" id="'+ id +'" name="'+ field_label +'" '+ required_field +'></textarea>';
				break;
				default:

					switch( fieldProperties.form_field ){
						case 'date-5time':
						case 'date-5':
							form_field = 'date';
						break;
						case 'old-password':
							form_field = 'password';
						break;
						case 'decimal':
							form_field = 'number';
						break;
						case 'currency':
							form_field = 'number';
						break;
					}

					field += '<input type="'+form_field+'" placeholder="'+ placeholder +'" value="'+value+'" id="'+id+'" name="'+field_label+'" class="'+ clas +'" '+ attributes +' '+ required_field +'>';

				break;
				}

				return field;
			}
		},
		createForm: function( tools ){
			// console.log(tools.length);
			$('#droppable').empty();
			var DOMform = '';
			if( tools.length !== 0 ){
				for( var tool in tools ){
					if (tools[ tool ] == null) continue;
					var label = '';
					var field;
					// console.log( tools[ tool ].form_field );
					switch( tools[ tool ].form_field ){
					case 'row':
					case 'column':
					break;
					default:
						field = $.fn.dragDropCanvas.createFormField( tools[ tool ].form_field, tools[ tool ].id );
					break;
					}

					switch( tools[ tool ].form_field ){
					case 'row':
						DOMform += $.fn.dragDropCanvas.createFormField( tools[ tool ].form_field, tools[ tool ].id );
					break;
					default:
						DOMform += '<div class="row ui-state-default" id="row">\
										<div class="col-md-12 col">\
											<div class="hey">\
												<span class="options" id="'+$.fn.dragDropCanvas.fieldKey+'" key="'+ tool +'" field="'+ tool +'" data-type="show">\
													<a href="#" onclick="$.fn.dragDropCanvas.fieldOptions('+ "'"+ tool +"'" +');" class="btn btn-sm btn-default pull-right"><i class="icon-chevron-down"></i></a>\
													<a href="#" onclick="$.fn.dragDropCanvas.delete('+ "'"+ tool +"'" +');" class="btn btn-sm btn-default pull-right"><i class="icon-remove"></i></a>\
												</span>\
												<div>'+field+'</div>\
											</div>\
										</div>\
									</div>';
					break;
					}
					$.fn.dragDropCanvas.fieldKey = '';
				}
				$.fn.dragDropCanvas.DOMform = DOMform;
				$.fn.dragDropCanvas.canvas.append( $.fn.dragDropCanvas.DOMform );

				//console.log( $.fn.dragDropCanvas.formFieldProperties );
				$( 'textarea[name="form_data"]' ).val( JSON.stringify($.fn.dragDropCanvas.formFieldProperties) );
			}

			$( ".demo-col > .hey" ).not( '#edit-field' ).draggable();

			//Make rows droppable for columns
			$( "#column" ).draggable();
		    $( ".demo-row" ).droppable({
		      accept: "#column",
		      classes: {
		        "ui-droppable-active": "ui-state-default"
		      },
		      drop: function( event, ui ) {
		      	$( this ).addClass( "ui-state-highlight" );
		      	var column_key = $.fn.dragDropCanvas.uniqueKey( 1, 1000 );
		      	
		      	$.fn.dragDropCanvas.createFormField( 'column', column_key, event.target.id );
		      }
		    });

		    // Make columns droppable for fields
		    $( ".demo-col" ).droppable({
			  greedy: true,
		      classes: {
		        "ui-droppable-active": "ui-state-default"
		      },
		      drop: function( event, ui ) {
		      	console.log( ui.helper.attr( 'class' ) );
		      	// console.log( $.fn.dragDropCanvas.formFieldProperties );
		      	$( this ).addClass( "ui-state-highlight" );

			      	if( ui.helper.context.id != 'column' ){
		      			var key = $.fn.dragDropCanvas.uniqueKey( 0, 1000 );
		      				
	      				if( ui.helper.hasClass( 'hey' ) ){
	      					var b = ui.helper.find( 'span' ).attr( 'field' );

	      					var a = $('div[id="'+ ui.helper.context.offsetParent.id +'"]').parent().attr( 'id' );

	      					var oldField = $.fn.dragDropCanvas.formFieldProperties[ a ].columns[ ui.helper.context.offsetParent.id ].fields[ b ];

	      					if( !$.fn.dragDropCanvas.formFieldProperties[ event.target.parentElement.id ].columns[ event.target.id ].fields ){
	      						$.fn.dragDropCanvas.formFieldProperties[ event.target.parentElement.id ].columns[ event.target.id ].fields = {};
	      					}

	      					delete $.fn.dragDropCanvas.formFieldProperties[ a ].columns[ ui.helper.context.offsetParent.id ].fields[ b ];
	      					$.fn.dragDropCanvas.formFieldProperties[ event.target.parentElement.id ].columns[ event.target.id ].fields[ b ] = oldField;

	      				}else if( ui.helper.hasClass( 'databaseTool' ) ){
		      	
	      					var id = ui.helper.context.id;

	      					if( ! $.fn.dragDropCanvas.formFieldProperties[ event.target.parentElement.id ].columns[ event.target.id ].fields ){
	      						$.fn.dragDropCanvas.formFieldProperties[ event.target.parentElement.id ].columns[ event.target.id ].fields = {};
	      					}

		      				$.fn.dragDropCanvas.formFieldProperties[ event.target.parentElement.id ].columns[ event.target.id ].fields[ id ] = $.fn.dragDropCanvas.databaseFields[ id ];

			      			$.fn.dragDropCanvas.createForm( $.fn.dragDropCanvas.formFieldProperties );
	      				}else{
	      					$.fn.dragDropCanvas.createFormField( ui.helper.context.id, key, event.target.parentElement.id, event.target.id );
	      				}

				      	$.fn.dragDropCanvas.createForm( $.fn.dragDropCanvas.formFieldProperties );
				      	
				    }
		      },
		        // over: function(event, ui){
		        //     $( "#droppable" ).droppable( "disable" )
		        // },
		        // out: function(event, ui){
		        //     $( "#droppable" ).droppable( "enable" )
		        // }
		    });
		},
		duplicate: function( thiss ){
			thiss = $( 'span[key="'+ thiss +'"]' );

			if( thiss[0].hasAttribute("column") ){
				var column_key = $.fn.dragDropCanvas.uniqueKey( 1, 1000 );
		      	var row = thiss.attr( 'row' );
		      	var column = thiss.attr( 'column' );
		      	$.fn.dragDropCanvas.createFormField( 'column', column_key, row, column );
			}else{

		      	var row_key = $.fn.dragDropCanvas.uniqueKey( 1, 1000 );
		      	var row = thiss.attr( 'row' );
		      	$.fn.dragDropCanvas.createFormField( 'row', row_key, row );
		      	$.fn.dragDropCanvas.createForm( $.fn.dragDropCanvas.formFieldProperties );

			}
		},
		fieldOptions: function( thiss, key ){
			thiss = $( 'span[key="'+ thiss +'"]' );
			if( key )thiss = $( 'span[field="'+ key +'"]' );

			if( thiss.attr( 'data-type' ) == 'show' ){

				thiss.find('i[class="icon-chevron-down"]').attr( 'class', 'icon-chevron-up' );
				thiss.attr( 'data-type', 'hide' );
			    //console.log( $.fn.dragDropCanvas.formFieldProperties );
				var html = '<div class="row" id="edit-field"><fieldset><legend>Field Settings:</legend>';
				var fieldProperties;
				var id;
				var fieldID = thiss.attr( 'field' );
				var rowID = '';
				var columnID = '';

				// Check and get field properties
				if( thiss[0].hasAttribute( 'column' ) ){
					columnID = thiss.attr( 'column' );
					rowID = ( thiss[0].hasAttribute( 'row' ) ? thiss.attr( 'row' ) : '' );
					var fieldProperties = JSON.parse( JSON.stringify( $.fn.dragDropCanvas.formFieldProperties[ rowID ].columns[ columnID ].fields[ fieldID ] ) );

					$( 'form#field-options' ).attr({ 
						'row': rowID,
						'column': columnID,
					});
				}else{
					var fieldProperties = JSON.parse( JSON.stringify( $.fn.dragDropCanvas.formFieldProperties[ fieldID ] ) );
					fieldID = $.fn.dragDropCanvas.formFieldProperties[ fieldID ].id;
				}

				$( '#field-options-container' ).show();
				
				if( fieldProperties.reuse ){
					if( fieldProperties["calculations"] ){
						delete fieldProperties["calculations"];
					}
				}
				
				$.each( fieldProperties, function( k, v ){
					$( '#field-options-container' ).find( '#' + k ).show();
				});

				// console.log( $.fn.dragDropCanvas.formFieldProperties );
				$( 'form#field-options' ).attr( 'field', fieldID );
				$( '#close-field-settings' ).attr( 'onclick', '$.fn.dragDropCanvas.closeSettingsForm('+ "'"+ fieldID +"'" +')' );

				switch( fieldProperties.form_field ){
					case 'button':
						$( '#field-label' ).html( fieldProperties.value );
					break;
					default:
						$( '#field-label' ).html( fieldProperties.display_field_label );
					break;
				}

				$.each( fieldProperties, function( k, v ){
					switch( k ){
						case 'required_field':
						case 'default_appearance_in_table_fields':
						case 'display_position':
							$( 'form#field-options' ).find( 'select[name="'+ k +'"]' ).find( 'option[value="'+ v +'"]' ).attr( 'selected', 'selected' );
						break;
						default:
							$( '#field-options' ).find( 'input[name="'+ k +'"], textarea[name="'+ k +'"]' ).val( v );
						break;
					}
				});

				$.fn.dragDropCanvas.submitFormData( thiss );
			}else{
				$( 'form#field-options' ).trigger('reset');
				
				if( $( 'form#field-options' ).find("input.select2") ){
					$( 'form#field-options' ).find("input.select2").select2("val", "");
				}
				if( $( 'form#field-options' ).find("input.uploaded-file") ){
					$( 'form#field-options' ).find("input.uploaded-file").val("");
					$( 'form#field-options' ).find(".qq-upload-list").html("");
				}

				thiss.find('i[class="icon-chevron-up"]').attr( 'class', 'icon-chevron-down' );
				thiss.attr( 'data-type', 'show' );
				
				$.fn.dragDropCanvas.closeSettingsForm( thiss.attr( 'field' ) );
			};
			
		},
		closeSettingsForm: function( fieldID ){
			var fieldProperties;

			$( 'span[field="'+ fieldID +'"]' ).attr( 'data-type', 'show' ).find('i[class="icon-chevron-up"]').attr( 'class', 'icon-chevron-down' );
			// $( 'span[field="'+ fieldID +'"]' );

			$( '#field-options-container' ).hide();

			if( $.fn.dragDropCanvas.formFieldProperties[ fieldID ] && ! $.isEmptyObject( $.fn.dragDropCanvas.formFieldProperties[ fieldID ] ) ){
				fieldProperties = $.fn.dragDropCanvas.formFieldProperties[ fieldID ];
			}

			if( $( '#field-options' )[0].hasAttribute( 'column' ) )
				fieldProperties = $.fn.dragDropCanvas.formFieldProperties[ $( '#field-options' ).attr( 'row' ) ].columns[ $( '#field-options' ).attr( 'column' ) ].fields[ fieldID ];

			if( fieldProperties && typeof fieldProperties == 'object' ){
				$.each( fieldProperties, function( k, v ){
					$( '#field-options-container' ).find( '#' + k ).hide();
				});
			}
			
			$( 'form#field-options' ).trigger('reset');
				
			if( $( 'form#field-options' ).find("input.select2") ){
				$( 'form#field-options' ).find("input.select2").select2("val", "");
			}
			if( $( 'form#field-options' ).find("input.uploaded-file") ){
				$( 'form#field-options' ).find("input.uploaded-file").val("");
				$( 'form#field-options' ).find(".qq-upload-list").html("");
			}
		},
		submitFormData: function( thiss ){
			$( 'form.client-form' )
			.off('submit')
			.on('submit', function(e){
				e.preventDefault();

				var rowID = '';
				var columnID = '';
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
								
								$.each( d, function( k, v ){
									if( k ){
										data[ n + "_" + k ] = v;
									}
								} );
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
							var val = $(this).val().toString().trim();
						}else{
							val = '';
						}
						
						data[ n + "_text" ] = $(this).children('option:selected').text().trim();
					break;
					}
					
					data[ $(this).attr("name") ] = val;
				});
				
				var form = $(this).attr("id");
				
				switch( form ){
				case "field-options":
					
					// Check and get field properties
					if( thiss[0].hasAttribute( 'column' ) ){
						columnID = thiss.attr( 'column' );
						rowID = ( thiss[0].hasAttribute( 'row' ) ? thiss.attr( 'row' ) : '' );
					}
					
					var fieldID = thiss.attr( 'field' );
					
					if( data["reuse"] ){
						delete data["calculations"];
					}
					
					$.each( data, function( k, v ){
						$( '#field-options-container' ).find( '#' + k ).hide();

						if( ! thiss[0].hasAttribute( 'field' ) ){
							err = fieldID + ' does not exist';
						}else if( thiss[0].hasAttribute( 'column' ) ){
							if( k in $.fn.dragDropCanvas.formFieldProperties[ rowID ].columns[ columnID ].fields[ fieldID ] ){
								$.fn.dragDropCanvas.formFieldProperties[ rowID ].columns[ columnID ].fields[ fieldID ][ k ] = v;
							}
						}else{
							if( k in $.fn.dragDropCanvas.formFieldProperties[ fieldID ] ){
								$.fn.dragDropCanvas.formFieldProperties[ fieldID ][ k ] = v;
							}
						}
					});
					
				break;
				case "field-script":
					var h = '';
					
					if( data["validation_code"] ){
						h = 'var $formfield = ' + JSON.stringify( $.fn.dragDropCanvas.scriptFormField ) + ';' + "\n\n" + data["validation_code"];
					}
					
					$('textarea[name="form_script"]').val( h );
					return false;
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

					thiss.find('i[class="icon-chevron-up"]').attr( 'class', 'icon-chevron-down' );
					thiss.attr( 'data-type', 'show' );

					$.fn.dragDropCanvas.createForm( $.fn.dragDropCanvas.formFieldProperties );
				}
				
				$(this).removeAttr( 'field' );
				if( $(this)[0].hasAttribute( 'column' ) )$(this).removeAttr( 'column' );
				if( $(this)[0].hasAttribute( 'row' ) )$(this).removeAttr( 'row' );
				/* $(this).trigger('reset');
				
				if( $(this).find("input.select2") ){
					$(this).find("input.select2").select2("val", "");
				}
				if( $(this).find("input.uploaded-file") ){
					$(this).find("input.uploaded-file").val("");
					$(this).find(".qq-upload-list").html("");
				} */
				
				$.fn.dragDropCanvas.closeSettingsForm( fieldID );
			});
		},
		uniqueKey: function( min, max ){
			var launch_date = new Date();
			// console.log( launch_date.getTime() );
			++$.fn.dragDropCanvas.serial_num;
			return 'd' + $.fn.dragDropCanvas.serial_num;
			//return 'd' + md5( launch_date.getTime() + '' + Math.random() + '' + $.fn.dragDropCanvas.serial_num );
		},
		delete: function( thiss ){
			thiss = $( 'span[key="'+ thiss +'"]' );

			if( thiss[0].hasAttribute("field") ){
				if( $.fn.dragDropCanvas.formFieldProperties[ thiss.attr( 'field' ) ] )
					delete $.fn.dragDropCanvas.formFieldProperties[ thiss.attr( 'field' ) ];
			}

			if( thiss[0].hasAttribute("row") ){
				if( thiss[0].hasAttribute("column") ){
					if( thiss[0].hasAttribute("field") ){
						delete $.fn.dragDropCanvas.formFieldProperties[ thiss.attr( 'row' ) ].columns[ thiss.attr( 'column' ) ].fields[ thiss.attr( 'field' ) ];
						$.fn.dragDropCanvas.fieldSerial -= 10;
					}else{
						delete $.fn.dragDropCanvas.formFieldProperties[ thiss.attr( 'row' ) ].columns[ thiss.attr( 'column' ) ];
					}
				}else{
					delete $.fn.dragDropCanvas.formFieldProperties[ thiss.attr( 'row' ) ];
				}
			}

			$.fn.dragDropCanvas.createForm( $.fn.dragDropCanvas.formFieldProperties );			
		},
		selectTabelWizard: function( id ){
			$,fn.dragDropCanvas.tableName = $( '#' + id ).val();
		},
		/*Added 10.NOV.2019*/
		scriptFormField: {},
		showScriptWindow: function(){
			$( '#field-script-container' ).show();
			var fd = {};
			
			if( $( 'textarea[name="form_data"]' ).val() ){
				fd = JSON.parse( $( 'textarea[name="form_data"]' ).val() );
			}
			
			var fvar = {};
			
			var fhtml = [];
			var serial = 0;
			
			if( fd && ! $.isEmptyObject( fd ) ){
				$.each( fd, function( k, v ){
					
					if( v.columns && ! $.isEmptyObject( v.columns ) ){
						
						$.each( v.columns, function( kc, vc ){
							if( vc.fields && ! $.isEmptyObject( vc.fields ) ){
								
								$.each( vc.fields, function( kc1, vc1 ){
									++serial;
									var $rt = $.fn.dragDropCanvas.addValidationField( vc1, fvar, serial, fhtml );
									fvar = $rt.fvar;
									fhtml = $rt.fhtml;
								});
								
							}
						});
						
					}else{
						++serial;
						var $rt = $.fn.dragDropCanvas.addValidationField( v, fvar, serial, fhtml );
						fvar = $rt.fvar;
						fhtml = $rt.fhtml;
					}
				} );
				
				$.fn.dragDropCanvas.scriptFormField = fvar;
				
				fhtml[0] = '<strong>"key" : "value"</strong>';
				
				$('#field-id-container')
				.html( 'Form Variable Name = $formfield<br /><br />' + fhtml.join('<br />') );
				
				//h = 'var $formfield = JSON.parse(' + JSON.stringify( fvar ) + ');' + "\n\n";
				
			}
			
			$.fn.dragDropCanvas.submitFormData( $( '#field-script-container' ) );
		},
		addValidationField: function( v, fvar, serial, fhtml ){
			var t = v.field_label;
			
			if( v.field_identifier ){
				t = v.field_identifier;
			}
			
			if( fvar[ t ] ){
				t += serial;
			}
			fvar[ t ] = v.id;
			
			var t2 = v.display_field_label;
			if( v.text ){
				t2 = v.text;
			}
			
			fhtml[serial] = '"' + t + '" : "' + t2 + '"';
			
			return { fhtml: fhtml, fvar:fvar };
		},
		closeSettingsForm2: function(){
			$( '#field-script-container' ).hide();
		},
	};
}(jQuery));
$.fn.dragDropCanvas.init();