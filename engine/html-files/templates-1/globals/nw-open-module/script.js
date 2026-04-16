var nwWorkflow = function () {
	return {
		recordItem: {
			id:"",
		},
		newWindowOpen: function ( uk ) {
			let siteTitle = document.title;
			 
			$(window).blur(function() {
			  document.title = 'Modal Window is Open !!!';
			});
			
			$(window).focus(function() {
				setTimeout(function(){
				if( $.fn.cProcessForm.modalWindow == null || $.fn.cProcessForm.modalWindow.closed ){
					
					$(window).off( 'blur' );
					$(window).off( 'focus' );
					document.title = siteTitle;
					if( $('a#reopen-' + uk ).is(":visible") ){
						$('a#reopen-' + uk ).click();
					}else if( $('#' + uk ).is(":visible") ){
						$('#' + uk ).click();
					}
				}else{
					var data = {theme:'note note-warning', err:'Modal Window is Open', msg:'Please close the modal window before you proceed with any action', typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
				}
				}, 400 );
			});
		},
		init: function () {
			$( 'a.open-activity-window' )
			.click(function(){ 
				// console.log( $(this) );
				var unique_key = $(this).attr( 'unique_key' );
				if( no_reolading_tab ){
					$( 'a#tab2-handle-' + unique_key ).click();
				}else{
					$( 'a#tab2-handle-' + unique_key ).addClass( 'skip-processing custom-single-selected-record-button activatedRightClick' ).attr({
						'action' : $( this ).attr( 'action' )+'&nsqad=1',
						'override-selected-record' : $(this).attr( 'override-selected-record' ),
					}).click();

				}
				$( 'a#tab2-handle-' + unique_key ).text( $(this).text() );
				// $( '#tab-2' ).html( '' );
			});
			
			nwWorkflow.loadLatestComment();
			nwWorkflow.submitDataForm();

			if( unique_key ){
				setTimeout(function(){ 
					if( $.fn.cProcessForm.localStore( 'nwp-full-screen', '', {}, 'get' ) ){
						$.fn.cProcessForm.fullScreen( 'control-panel-' + unique_key );
					}
				},50);
			}
			if( click_btn ){
				setTimeout(function(){ 
					$('a[clickme="'+ click_btn +'"]').click();
				}, 400 );
			}
		},
		loadLatestComment: function () {
			if( $('a#latest-comments-container-link') ){
				setTimeout(function(){ 
					$('a#latest-comments-container-link').click();
				}, 1000 );
			}
		},
		openActivityWindow: function () {
			// $('a#tab2-handle').click();
		},
		selectAllFields: function ( t ) {
			
			$('select[name="fields['+ t +'][]"]')
			.find('option')
			.prop('selected', true);
			
			$('select[name="fields['+ t +'][]"]')
			.trigger("change");
		},
		clearAllFields: function ( t ) {
			
			$('select[name="fields['+ t +'][]"]')
			.find('option')
			.prop('selected', false);
			
			$('select[name="fields['+ t +'][]"]')
			.trigger("change");
		},
		selectAllFields2: function ( t ) {
			
			$('select[name="'+ t +'[]"]')
			.find('option')
			.prop('selected', true);
			
			$('select[name="'+ t +'[]"]')
			.trigger("change");
		},
		clearAllFields2: function ( t ) {
			
			$('select[name="'+ t +'[]"]')
			.find('option')
			.prop('selected', false);
			
			$('select[name="'+ t +'[]"]')
			.trigger("change");
		},
		updateTableFields: function () {
			
			var json = JSON.parse( $('textarea#table-fields-json').val() );
			var table = $('form#duplicate-settings-form').find('select[name="table"]').val();
			
			if( json && table && json[ table ] ){
				$('form#duplicate-settings-form')
				.find('select[name="fields"]')
				.html( json[ table ] )
				.trigger("change");
			}
		},
		assignedDataLocation:{},
		assignedData:{},
		activateAssignment: function () {
			nwWorkflow.assignedData = {};
			nwWorkflow.assignedDataLocation = {};
			nwWorkflow.submitDataForm();
		},
		assignRecordsToValidators: function ( id ) {
			//
			//var json = JSON.parse( $('textarea#table-fields-json').val() );
			//var table = $('form#duplicate-settings-form').find('select[name="table"]').val();
			
			if( id && $('#assign-' + id ) ){
				$('form#assign-data-validator-form')
				.show();
				
				var $r = $('#record-' + id );
				
				var $a = $('#assign-' + id );
				var $form = $('form#assign-data-validator-form');
				var dd = { "state":1, "lga":1, "ward":1, "community":1 }
				var title = [];
				
				$form.find('input[name="records"]').val( parseInt( $r.attr('data-count') ) );
				$form.find('input[name="records"]').attr( "max", parseInt( $r.attr('data-count') ) );
				
				$.each( dd, function( k1, v1 ){
					
					if( $a.attr( 'data-' + k1 ) ){
						$form.find('input[name="'+ k1 +'"]').val( $a.attr('data-' + k1 ) );
						
						if( $a.attr( 'data-' + k1 + '-text' ) ){
							$form.find('input[name="'+ k1 +'_text"]').val( $a.attr( 'data-' + k1 + '-text' ) );
							
							title.push( $a.attr( 'data-' + k1 + '-text' ) );
						}
					}
					
				} );
				
				if( title.length > 0 ){
					$form.find('#assign-title').html( title.join(" - ") );
				}
			}
			
		},
		displayValidators: function () {
			//
			//var json = JSON.parse( $('textarea#table-fields-json').val() );
			//var table = $('form#duplicate-settings-form').find('select[name="table"]').val();
			var h = '';
			var colspan = 6;
			var dx = {};
			var hx = '#assigned-records-table';
			var hx2 = '#assign-records-table';
			var total_assign = 0;
			
			if( ! $.isEmptyObject( nwWorkflow.assignedData ) ){
				//console.log( nwWorkflow.assignedData );
				
				$.each( nwWorkflow.assignedData, function( k1, v1 ){
					
					h += '<tr><td colspan="'+ colspan +'"><strong>'+ v1.validator.text +'</strong></td></tr>';
					
					if( v1.data && ! $.isEmptyObject( v1.data ) ){
						$.each( v1.data, function( k2, v2 ){
							h += '<tr><td>'+ v2.state_text +'</td><td>'+ v2.lga_text +'</td><td>'+ v2.ward_text +'</td><td>'+ v2.community_text +'</td><td>'+ v2.records_assigned +'</td><td></td></tr>';
							
							if( ! dx[ v2.state ] ){
								dx[ v2.state ] = {
									records:0,
									state:v2.state,
									data:{},
								};
							}
							
							if( ! dx[ v2.state ][ "data" ][ v2.lga ] ){
								dx[ v2.state ][ "data" ][ v2.lga ] = {
									records:0,
									lga:v2.lga,
									data:{},
								};
							}
							
							if( v2.ward ){
								if( ! dx[ v2.state ][ "data" ][ v2.lga ]["data"][ v2.ward ] ){
									dx[ v2.state ][ "data" ][ v2.lga ]["data"][ v2.ward ] = {
										records:0,
										ward:v2.ward,
										data:{},
									};
								}
								dx[ v2.state ][ "data" ][ v2.lga ]["data"][ v2.ward ]["records"] += v2.records_assigned;
								
								if( v2.community ){
									if( ! dx[ v2.state ][ "data" ][ v2.lga ]["data"][ v2.ward ]["data"][ v2.community ] ){
										dx[ v2.state ][ "data" ][ v2.lga ]["data"][ v2.ward ]["data"][ v2.community ] = {
											records:0,
											community:v2.community,
											data:{},
										};
									}
									
									dx[ v2.state ][ "data" ][ v2.lga ]["data"][ v2.ward ]["data"][ v2.community ]["records"] += v2.records_assigned;
									
									
									var c = parseInt( $( hx2 ).find('td#community-' + v2.community ).parent().attr("data-count") * 1 );
									var c1 = c - dx[ v2.state ][ "data" ][ v2.lga ]["data"][ v2.ward ]["data"][ v2.community ]["records"];
									
									$( hx2 )
									.find('td#community-' + v2.community )
									.html( c1 )
									.parent()
									.attr( "balance", c1 );
									
								}else{
									$( hx2 )
									.find('tr.ward-' + v2.ward )
									.addClass("disabled");
								}
								
								var c = parseInt( $( hx2 ).find('td#ward-' + v2.ward ).parent().attr("data-count") * 1 );
								var c1 = c - dx[ v2.state ][ "data" ][ v2.lga ]["data"][ v2.ward ]["records"];
								
								$( hx2 )
								.find('td#ward-' + v2.ward )
								.attr( "balance", c1 )
								.html( c1 )
								.parent()
								.attr( "balance", c1 );
								
							}else{
								
								$( hx2 )
								.find('tr.lga-' + v2.lga )
								.addClass("disabled");
							}
							
							total_assign += v2.records_assigned;
							dx[ v2.state ]["records"] += v2.records_assigned;
							dx[ v2.state ][ "data" ][ v2.lga ]["records"] += v2.records_assigned;
							
							
							var c = parseInt( $( hx2 ).find('td#lga-' + v2.lga ).parent().attr("data-count") * 1 );
							var c1 = c - dx[ v2.state ][ "data" ][ v2.lga ]["records"];
							
							$( hx2 )
							.find('td#lga-' + v2.lga )
							.html( c1 )
							.parent()
							.attr( "balance", c1 );
						});
					}
					
					h += '<tr><td colspan="'+ colspan +'">&nbsp;</td></tr>';
				} );
				
			}
			
			$( hx ).html( h );
			
			var jx = {};
			jx[ 'assign' ] = nwWorkflow.assignedData;
			jx[ 'location' ] = nwWorkflow.assignedDataLocation;
			
			$('form#assign-to-validator-form')
			.find( 'textarea[name="data"]' )
			.val( JSON.stringify( jx ) );
			
			$('form#assign-to-validator-form')
			.find( 'input[name="total_assign"]' )
			.val( total_assign );
			
			$('form#assign-data-validator-form')
			.hide();
		},
		submitDataForm: function(){
			
			$("form.client-form")
			.on('submit', function(e){
				e.preventDefault();
				
				var err = "";
				var msg = "";
				
				var data = {};
				$(this)
				.find(".form-control, .form-check-input")
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
						
						data[ n + "_text" ] = $(this).children('option:selected').text().trim();
					break;
					case "radio":
						if( ! $(this).is(":checked") ){
							return;
						}
					break;
					}
					
					data[ $(this).attr("name") ] = val;
				});
				console.log( data );
				
				var id = 1;
				
				var form = $(this).attr("id");
				
				var refresh = '';
				var increment = 0;
				var cart_items = {};
				var empty_form_values = 1;
				
				
				switch( form ){
				case "assign-data-validator-form":
					if( ! err ){
						
						if( data["validator_tags"] && ! $.isEmptyObject( data["validator_tags"] ) && data["records"] ){
							
							var k3 = data["state"] + '-' + data["lga"] + '-' + data["ward"] + '-' + data["community"];
							var div = Math.floor( parseInt( data["records"] ) / data["validator_tags"].length );
							var remain = ( parseInt( data["records"] ) % data["validator_tags"].length );
							
							$.each( data["validator_tags"], function( k2, v2 ){
								if( ! nwWorkflow.assignedData[ v2.id ] ){
									nwWorkflow.assignedData[ v2.id ] = { data:{}, validator:v2 };
								}
								
								if( ! nwWorkflow.assignedData[ v2.id ][ "data" ][ k3 ] ){
									nwWorkflow.assignedData[ v2.id ][ "data" ][ k3 ] = {};
								}
								
								nwWorkflow.assignedData[ v2.id ][ "data" ][ k3 ] = JSON.parse( JSON.stringify( data ) );
								nwWorkflow.assignedData[ v2.id ][ "data" ][ k3 ]["records_assigned"] = div + remain;
								remain = 0;
							} );
							
							var edata = { theme:'note note-success alert-success' , err:"Changes Successfully Saved", msg:"Validators has been assigned", typ:'jsuerror' };
							nwDisplayNotification.display_notification( edata );
							
							nwWorkflow.displayValidators();
							
						}else{
							err = "Invalid Validators / Records";
							msg = "Please try again";
						}
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
					return false;
				}
				
				//$(this).trigger('reset');
				
				if( empty_form_values ){
					$(this).find('.form-control').not(".keep-value").val("");
					
					if( $(this).find("input.select2") ){
						$(this).find("input.select2").select2("val", "");
					}
					if( $(this).find("input.uploaded-file") ){
						$(this).find("input.uploaded-file").val("");
						$(this).find(".qq-upload-list").html("");
					}
				}
			});
		},
		removeNotice: function(){
			$('#open-module-notice').remove();
		},
	};
	
}();
nwWorkflow.init();