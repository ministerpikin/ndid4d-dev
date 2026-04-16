var nwInvoiceEdit = function () {
	return {
		recordItem: {
			id:"",
		},
		investigation:{},
		init: function () {
			nwInvoiceEdit.investigation = JSON.parse( $('textarea[name="items_data"]').val() );
			nwInvoiceEdit.refreshCart( {} );
			
			$('input[name="new_item"]')
			.on("change", function(){
				var html = '';
				
				var v = $(this).val();
				
				if( v ){
					var d = $(this).select2("data");
					
					var launch_date = new Date();
					d.new_id = launch_date.getTime();
					d.just_added = 1;
					
					
					d.selling_price = parseFloat( d.selling_price * 1 );
					if( isNaN( d.selling_price ) )d.selling_price = 0;
					
					d.price = d.selling_price;
					d.quantity = 1;
					d.total = d.selling_price;
					d.discount = 0;
					d.desc = d.description;
					
					nwInvoiceEdit.investigation[ d.new_id ] = d;
					nwInvoiceEdit.refreshCart( {} );
					
					$(this).select2("val", '');
				}
			});
			
			nwInvoiceEdit.submitDataForm();
		},
		showAdvanceEdit: function( id ){
			var error = '';
			if( id ){
				if( nwInvoiceEdit.investigation[ id ] ){
					var p = nwInvoiceEdit.investigation[ id ];
					
					$('#basic-edit-con').hide();
					$('#advance-edit-con').show();
					$('#advance-edit-con').find('form').trigger('reset');
					
					$('#advance-edit-con')
					.find('form')
					.find('.data-form')
					.each(function(){
						if( $(this).attr('type') != 'radio' ){
							if( $(this).attr('name') && p[ $(this).attr('name') ] ){
								$(this).val( p[ $(this).attr('name') ] );
								
								if( $(this).hasClass('select2') ){
									var d = { id: p[ $(this).attr('name') ] };
									if( p[ $(this).attr('name') + '_text' ] ){
										d.text = p[ $(this).attr('name') + '_text' ];
									}else{
										d.text = p[ $(this).attr('name') ];
									}
									$(this).select2('data', d );
								}
							}
						}
					});
					
					$('#advance-edit-con')
					.find('form')
					.find('input[name="new_idx"]')
					.val( id );
					
					if( p.id ){
						$('#advance-edit-con')
						.find('form')
						.find('input[name="service_id"]')
						.val( p.id );
						
						$('#advance-edit-con')
						.find('form')
						.find('input[name="service_qty"]')
						.val( p.quantity );
						
						$(".use-service-id").show();
					}else{
						$(".use-service-id").hide();
					}
					
					$('.advance-desc').html( p.description );
					
					if( p.param_type ){
						$('input#radio-' + p.param_type).prop("checked", true );
						nwInvoiceEdit.changeSearchMode( $('input#radio-' + p.param_type) );
					}else{
						nwInvoiceEdit.changeSearchMode( $('input[name="param_type"]').filter(":checked") );
					}
				}else{
					error = 'Invalid Line Item ID';
				}
			}else{
				$('#basic-edit-con').show();
				$('#advance-edit-con').hide();
				
				//31-dec-22
				var $af = $('#advance-edit-con').find('form');
				$af.trigger('reset');
				if( $af.find("input.select2") ){
					$af.find("input.select2").select2("val", "");
				}
				if( $af.find("input.uploaded-file") ){
					$af.find("input.uploaded-file").val("");
					$af.find(".qq-upload-list").html("");
				}
			}
			
			if( error ){
				var data = {theme:'alert-danger', err:error, msg:error, typ:'jsuerror' };
				nwDisplayNotification.display_notification( data );
			}
		},
		changeSearchMode: function( e ){
			var $me = $(e);
			var val = $me.val();
			//alert(val);
			
			$(".advance-param-form").hide();
			$(".advance-param-form#"+ val ).show();
			
			var pid = $('#advance-edit-con').find('form').find('input[name="new_idx"]').val();
			if( pid && nwInvoiceEdit.investigation[ pid ] ){
				nwInvoiceEdit.investigation[ pid ][ $me.attr("name") ] = val;
			}
			
		},
		advanceEdit: function( id ){
			nwInvoiceEdit.showAdvanceEdit( id );
		},
		removeItem: function( id ){
			if( id && nwInvoiceEdit.investigation[ id ] ){
				delete nwInvoiceEdit.investigation[ id ];
				nwInvoiceEdit.refreshCart( {} );
			}
		},
		total: 0,
		refreshCart: function( opt ){
			var serial = 0;
			var html = '';
			var t = 0;
			var q = 0;
			var testaread = {};
			
			if( ! $.isEmptyObject( nwInvoiceEdit.investigation ) ){
				testaread = typeof opt.testaread !== 'undefined' ? opt.testaread : nwInvoiceEdit.investigation;
				$.each( testaread, function( k, d ){
					++serial;
					t += d.total;
					q += d.quantity;
					
					var hc = '';
					if( d.hmo_id ){
						if( d.hmo_id_text ){
							hc += '<br />' + d.hmo_id_text;
						}else{
							hc += '<br />' + d.hmo_id;
						}
						if( d.percentage_coverage ){
							hc += ' - [' + d.percentage_coverage + '%]';
						}
						if( d.approval_code ){
							hc += ' - [' + d.approval_code + ']';
						}else if( d.hmo_flag ){
							hc += ' - [' + d.hmo_flag + ']';
						}else if( d.requires_code && d.requires_code != 'no' ){
							hc += ' - [<span style="color:red;">Requires Auth Code</span>]';
						}
					}else{
						//31-dec-22
						hc += '<br /><span style="color:red;">Private</span>';
					}
					
					var psty = '<input type="number" class="form-control data-change" step="any" min="0" value="'+ d.price +'" id="price" key="'+ k +'" />';
					if( d.components ){
						psty = nwInvoiceEdit.addComma( ( d.price * 1 ).toFixed(2) );
					}
					
					html += '<tr>';
						html += '<td>'+ serial +'</td>';
						html += '<td>'+ d.description + hc + '</td>';
						
						var hide_qty = 0;
						// console.log( d.type );
						if( typeof( ex_v4 ) !== 'undefined' && ex_v4 == 2 ){
							switch( d.type ){
							case 'purchased_goods':
							case 'raw_materials':
							case 'consignment':
							case 'produced_goods':
							case 'raw_materials_purchased_goods':
							case 'composite':
							case 'composite_production':
							case 'sub_item':
								if( typeof d.just_added == 'undefined' ){
									hide_qty = 1;
									// delete testaread[ k ].just_added;
								}
							break;
							}
						}
						if( d.prevent_qty_edit ){
							hide_qty = 1;
						}

						if( hide_qty ){
							html += '<td align="right"><input type="hidden" class="form-control data-change" step="any" min="0" value="'+ d.quantity +'" id="quantity" key="'+ k +'" />'+ d.quantity +'</td>';
						}else{
							html += '<td align="right"><input type="number" class="form-control data-change" step="any" min="0" value="'+ d.quantity +'" id="quantity" key="'+ k +'" /></td>';
						}

						html += '<td alignx="right">'+ psty +'</td>';
						html += '<td align="right"><input type="number" class="form-control data-change" step="any" min="0" value="'+ d.discount +'" id="discount" key="'+ k +'" /></td>';
						html += '<td align="right" class="total-'+ k +'">'+ nwInvoiceEdit.addComma( d.total.toFixed(2) ) +'</td>';
						
						var del_button = '<a href="#" class="btn btn-xs dark" title="Remove this Item" onclick="nwInvoiceEdit.removeItem('+ "'"+ k +"'" +'); return false;"><i class="icon-trash"></i> </a>';
						if( d.old ){
							del_button = '';
						}
						del_button += '<a href="#" class="btn btn-xs dark" title="Advanced Edit Options" onclick="nwInvoiceEdit.advanceEdit('+ "'"+ k +"'" +'); return false;"><i class="icon-edit"></i> </a>';
						
						html += '<td >'+ del_button +'</td>';
					html += '</tr>';
					
				} );
				
				if( t ){
					html += '<tr>';
						html += '<td colspan="5"><strong>TOTAL</strong></td>';
						html += '<td id="i-total" align="right"><strong>'+ nwInvoiceEdit.addComma( t.toFixed(2) ) +'</strong></td>';
						html += '<td></td>';
					html += '</tr>';
				}
				
				if( opt && opt.skip && opt.total ){
					nwInvoiceEdit.updateTotal( { total : t, total2 : opt.total, id : opt.id } );
				}else{
					$("tbody#line-items-body")
					.html( html );
				
					$('input.data-change')
					.on("change", function(){
						var v = $(this).val();
						var d = $(this).attr("key");
						var id = $(this).attr("id");
						
						if( d && v && testaread[ d ] ){
							
							testaread[ d ][ id ] = parseFloat( v * 1 );
							
							//console.log( testaread[ d ] );
							var total = ( testaread[ d ][ "quantity" ] * testaread[ d ][ "price" ] ) - testaread[ d ][ "discount" ];
							testaread[ d ][ "total" ] = total;
							nwInvoiceEdit.refreshCart( { skip : 1, total : total, id : d, testaread : testaread } );
							// nwInvoiceEdit.updateTotal( { total : t, total2 : total } );
						}
					});
				}
				
				$('input[name="amount_due"]').val( t );
				$('input[name="quantity"]').val( q );
				
				$('textarea[name="items_data"]')
				.val( JSON.stringify( testaread ) );
			}
		},
		updateTotal: function( opt ){
			$( 'td#i-total' ).html( nwInvoiceEdit.addComma( opt.total.toFixed(2) ) );
			$( 'td.total-'+opt.id ).html( nwInvoiceEdit.addComma( opt.total2.toFixed(2) ) );
		},
		submitDataForm: function(){
			
			$("form.client-form")
			.on('submit', function(e){
				e.preventDefault();
				
				var launch_date = new Date();
				var new_id = launch_date.getTime();
				var id = new_id;
				
				var err = "";
				var msg = "";
				
				var data = {};
				$(this)
				.find(".form-control")
				.each(function(){
					var val = $(this).val();
					if( $(this).hasClass("optional-field") && ! $(this).is(":visible") ){
						val = '';
					}
					
					switch( $(this).attr("type") ){
					case "text":
						if( $(this).hasClass("select2") ){
							var d = $(this).select2('data');
							if( ! $.isEmptyObject( d ) ){
								var n = $(this).attr("name");
								
								$.each( d, function( k, v ){
									if( k ){
										if( val ){
											data[ n + "_" + k ] = v;
										}else{
											data[ n + "_" + k ] = '';
										}
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
						if( val ){
							data[ n + "_text" ] = $(this).children('option:selected').text().trim();
						}else{
							data[ n + "_text" ] = '';
						}
					break;
					}
					
					data[ $(this).attr("name") ] = val;
				});
				
				//data["id"] = id;
				//console.log( data );
				var preventSave = false;	//03-jan-23
				
				switch( data["mode"] ){
				case "hmo-form":
					
					if( ! data["new_idx"] ){
						err = "Invalid Bill Line Item Reference";
						msg = err;
					}
					
					if( ! err ){
						//03-jan-23
						if( data[ 'hmo_id' ] ){
							if( ! data[ 'coverage_class' ] ){
								msg = "You must specify the <b>Coverage Class</b>";
							}else if( ! data[ 'coverage_category' ] ){
								msg = "You must specify the <b>Coverage Category</b>";
							}else if( ! data[ 'percentage_coverage' ] ){
								msg = "You must specify the <b>Percentage Coverage</b>";
							}
							if( msg ){
								err = '<b>Incomplete Input</b>';
								msg += ' for services covered by HMO';
								preventSave = true;
							}
						}
					}
					
					if( ! err ){
						if( data["new_idx"] && nwInvoiceEdit.investigation[ data["new_idx"] ] ){
							
							if( ! data["package"] ){
								data["package_units_left"] = '';
								data["package_assigned_id"] = '';
								data["package_reference"] = '';
							}

							if( data[ 'hmo_id_organization' ] ){
								data[ 'hmo_org' ] = data[ 'hmo_id_organization' ];
							}
							
							$.each( data, function( k, v ){
								nwInvoiceEdit.investigation[ data["new_idx"] ][ k ] = v;
							} );
							
						}else{
							err = "Bill Line Item Reference Not Found";
							msg = err;	
						}
					}
					
					if( ! err ){
						nwInvoiceEdit.showAdvanceEdit(0);
					}
				break;
				default:
					err = "Invalid Input";
					msg = "Please try again or contact technical support team";
				break;
				}
				
				if( err ){
					var data = {theme:'alert-danger', err:err, msg:msg, typ:'jsuerror' };
					nwDisplayNotification.display_notification( data );
					if( preventSave ){
						return false;
					}
				}else{
					nwInvoiceEdit.refreshCart( {} );
				}
				
				//console.log( nwInvoiceEdit.cartItems );
				$(this).trigger('reset');
				if( $(this).find("input.select2") ){
					$(this).find("input.select2").select2("val", "");
				}
				if( $(this).find("input.uploaded-file") ){
					$(this).find("input.uploaded-file").val("");
					$(this).find(".qq-upload-list").html("");
				}
			});
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
nwInvoiceEdit.init();