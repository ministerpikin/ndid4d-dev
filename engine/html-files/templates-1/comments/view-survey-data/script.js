var nwSurvey = function () {
	return {
		recordItem: {
			id:"",
		},
		diagnosis_data:{},
		data:{
			cart_items:{},
		},
		init: function () {
			
			$('input[name="survey"]')
			.on("change", function(){
				
				var v = $(this).val();
				
				$.fn.cProcessForm.ajax_data = {
					ajax_data: { id: v },
					form_method: 'post',
					ajax_data_type: 'json',
					ajax_action: 'request_function_output',
					ajax_container: '',
					ajax_get_url: "?action=survey&todo=get_survey_info&html_replacement_selector=survey-details",
				};
				$.fn.cProcessForm.ajax_send();
				
				$(this).select2("val", "");
			});
			
		},
		refreshCart: function(){
			var html = '';
			var serial = 0;
			var total_cost = 0;
			var total_line_total = 0;
			var total_discount = 0;
			
			if( ! $.isEmptyObject( nwSurvey.data.cart_items ) ){
				$.each( nwSurvey.data.cart_items, function( key, val ){
					++serial;
					total_cost += val.selling_price;
					
					var line_total = val.selling_price * val.quantity;
					
					total_line_total += line_total;
					
					var _class = '';
					var comment = '';
					if( val.comment ){
						comment = '<br /><small>'+ val.comment +'</small>';
					}
					if( val.flag ){
						_class = 'flag';
					}
					
					
					html += '<tr class="'+ _class +'"><td>'+ serial +'</td><td>' + val.text + comment + '</td><td class="input-cell"><input type="number" step="any" min="0" id="'+ val.id +'" class="form-control selling-price-field" value="'+ val.selling_price.toFixed(2) +'" /></td><td class="input-cell"><input type="number" step="any" min="0" id="'+ val.id +'" class="form-control quantity-field" value="'+ val.quantity +'" /></td><td class="r number">'+ nwSurvey.addComma( line_total.toFixed(2) ) +'</td><td class="r"><button class="btn btn-sm dark" onclick="nwSurvey.removeInvestigation('+ "'"+ val.id +"'" +')"  title="Remove this Item"><i class="icon-trash"></i></button> </td></tr>';
					
				} );
				
				if( total_cost ){
					html += '<tr ><td colspan="5">&nbsp;</td></tr>';
					html += '<tr class="total-row"><td></td><td><strong>TOTAL</strong></td><td class="r number">' + nwSurvey.addComma( total_cost.toFixed(2) ) + '</td><td class="r number"></td><td class="r number"><strong>' + nwSurvey.addComma( total_line_total.toFixed(2) ) + '</strong></td><td></td></tr>';
				}
			}
			
			$('input[name="value_of_package"]')
			.val( total_line_total );
			
			$('textarea[name="data"]')
			.val( JSON.stringify( nwSurvey.data ) );
			
			$("#investigation-table-body-p")
			.html( html );
			
		},
		removeInvestigation: function( id ){
			if( id && nwSurvey.data.cart_items[ id ] ){
				delete nwSurvey.data.cart_items[ id ];
				nwSurvey.refreshCart();
			}
		},
		emptyNewItem: function(){
			
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
nwSurvey.init();