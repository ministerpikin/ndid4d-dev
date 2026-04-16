var nwUsers = function () {
	return {
		recordItem: {
			id:"",
			name:"",
		},
		url:g_site_url + "php/ajax_request_processing_script.php",
		init: function () {
			
			$('input[name="employee"]')
			.on('change', function(e){
				nwUsers.search();
			});
			
			$("#employee-tabs-link")
			.find("a")
			.on('click', function(e){
				$("#employee-tabs-link")
				.find("a").removeClass('bg-green');
				
				$(this).addClass('bg-green');
			});
			
			if( $('input[name="employee"]').val() ){
				nwUsers.search();
			}
			
			//nwUsers.createTreeView();
		},
		search: function () {	
			$("form#employee").submit();
		},
		closePopup: function(){
			nwUsers.refreshAcitveTab();
		},
		refreshAcitveTab2: function(){
			$("#employee-tabs-link").find("a.bg-green").click();
		},
		refreshAcitveTab: function(){
			if( $("#page-sub-content").find("form.refresh-form").is(":visible") ){
				$("#page-sub-content").find("form.refresh-form").submit();
			}else{
				$("#employee-tabs-link").find("a.bg-green").click();
			}
		},
		refreshemployeesList: function(){
			if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.data && $.fn.cProcessForm.returned_ajax_data.data.id ){
				 nwUsers.recordItem = $.fn.cProcessForm.returned_ajax_data.data;
				 
				 $('input[name="employee"]').val( nwUsers.recordItem.id );
				 nwUsers.activateTabs2();
				 nwUsers.search();
			}
		},
		activateTabs: function () {
			if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.data && $.fn.cProcessForm.returned_ajax_data.data.id ){
				 nwUsers.recordItem = $.fn.cProcessForm.returned_ajax_data.data;
				 $('input[name="employee"]').val( nwUsers.recordItem.id );
				 nwUsers.activateTabs2();
			}
		},
		activateTabs2: function () {	
			$(".populate-with-selected")
			.attr("override-selected-record", nwUsers.recordItem.id );
			
			$("input.get-employee-id").val( nwUsers.recordItem.id );
			
			//nwUsers.edit();
			nwUsers.refreshAcitveTab2();
		},
		edit: function () {
			
			$.each( nwUsers.recordItem, function( key, val ){
				if( $("form#employees").find('.form-control[name="'+key+'"]') ){
					$("form#employees").find('.form-control[name="'+key+'"]').val( val );
				}
			} );
			
		},
		emptyNewItem: function(){
			$('#expense-view')
			.find("tr.item-record")
			.removeClass("active");
			
			$("form#employees")
			.find(".custom-single-selected-record-button")
			.attr("override-selected-record", "" );
			
			$("input.get-employee-id").val( "" );
			
			$("form#employees").find(".form-control").val('');
			nwUsers.recordItem = {
				id:"",
				name:"",
				address:"",
				phone:"",
				second_phone:"",
				date_of_birth:"",
				city:"",
				age:"",
				sex:"",
				file_number:"",
				email:"",
				blood_group:"",
				occupation:"",
				next_of_kin_phone:"",
				next_of_kin_name:"",
				next_of_kin_address:"",
				comment:"",
			};
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
		},
		createTreeView: function(){
			$( "#tree-view-container" )
			.on("changed.jstree", function (e, data) {
				if(data.selected.length) {
					
					var d = data.instance.get_node( data.selected[0] ).id;
					var ids = d.split(':::');
					if( ids.length > 2 ){
						
						$.fn.cProcessForm.ajax_data = {
							ajax_data: {filter: "", id:nwUsers.recordItem.id },
							form_method: 'post',
							ajax_data_type: 'json',
							ajax_action: 'request_function_output',
							ajax_container: '',
							ajax_get_url: "?action=" + ids[0] + "&todo=" + ids[1],
						};
						$.fn.cProcessForm.ajax_send();
						
					}
					
				}
			})
			.jstree({
				'core' : {
					'data' : {
						"url" : g_site_url + 'php/ajax_request_processing_script.php?action=employee&todo=get_tree_view',
						"dataType" : "json", // needed only if you do not supply JSON headers
						"data" : function (node) {
							return { "id" : node.id, "method" : node.id };
						}
					}
				}
			});
		},
	};
	
}();
//nwUsers.init();

var nwCart = function () {
	return {
		refreshemployeesList: function(){
			
		}
	};
}();