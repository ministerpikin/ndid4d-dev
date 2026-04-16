// alert();
var nwLabels = {
	data: {},
	ids: '',
	comment_id: '',
	serial: 1,
	init: function(){
		nwLabels.submitDataForm();
		console.log( 'hey' );
		if( ! nwLabels.data.label_data ){
			nwLabels.data.label_data = {};
		}

		if( lbl && ! $.isEmptyObject( lbl ) ){
			nwLabels.data.label_data = Object.assign( {}, lbl );
			
			$.each( nwLabels.data.label_data, function( k, v ){
				nwLabels.data.label_data[ v.id ] = v;
				delete nwLabels.data.label_data[ k ];
			});
		}
		nwLabels.comment_id = coment;


		nwLabels.refreshCart();

	},
	refreshCart: function(){
		var html = '';
		var serial = 0;
		
		if( nwLabels.data.label_data && ! $.isEmptyObject( nwLabels.data.label_data ) ){

			var a = nwLabels.data.label_data;
			$.each( a, function( k, v ){

				var sid = 'section-'+ v.id;
				++serial;
				
				html += ' <tr id="'+ sid +'" data-key="'+ k +'" data-type="pmt-data">';
					html += '<td>'+ serial +'</td>';
					html += '<td>'+ v.name +'</td>';
					html += '<td>'+ v.description +'</td>';
					html += '<td style="background:'+ v.colour +';"></td>';
					html += '<td>'+ v.type +'</td>';

					html += ' <td > ';

					html += ' <a onclick="nwLabels.delete('+ "'"+ sid +"'" +');" title="Add Form to this Section" class="btn btn-sm dark"> <i class="icon-minus"></i> </a>';
					
					html += ' </td> ';
				html += ' </tr> ';

			});
			nwLabels.ids = '';
			$.each( nwLabels.data.label_data, function( k, v ){
				if( nwLabels.ids ){
					nwLabels.ids += ',' + v.id;
				}else{
					nwLabels.ids += v.id;
				}
			});
		}

		$('textarea[name="labels"]')
		.val( nwLabels.ids );
		
		$( '#display-labels' ).html( html );
	},
	submitDataForm: function(){
			
		var err = "";
		var msg = "";
		
		$( 'form.client-form' ).off( 'submit' );

		$( 'form.client-form' )
		.on( 'submit', function(e){
			e.preventDefault();
		
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
									data[ k ] = v;
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
						var val = $(this).val().toString();
					}else{
						val = '';
					}
					
					data[ n + "_text" ] = $(this).children('option:selected').text().trim();
				break;
				}
				
				data[ $(this).attr("name") ] = val;
			});

			var a = $(this).attr( 'id' );

			switch( a ){
				case 'labels':
					if( ! nwLabels.data.label_data ){
						nwLabels.data.label_data = {};
					}
					
					nwLabels.data.label_data[ data[ 'id' ] ] = data;
					
					nwLabels.refreshCart();
				break;
			}
		});
	},
	delete: function( id, sectionID ){

		var a = $( 'tr#' + id );
		id = a.attr( 'data-key' );

		if( nwLabels.data.label_data[ id ] && ! $.isEmptyObject( nwLabels.data.label_data[ id ] ) ){
			delete nwLabels.data.label_data[ id ];
		}

		nwLabels.refreshCart();
	},
	uniqueKey: function( min, max ){
		var launch_date = new Date();
		return 'd' + launch_date.getTime(); // + 'd' + Math.floor(Math.random() * (max - min + 1) + min);
	},
};
nwLabels.init();