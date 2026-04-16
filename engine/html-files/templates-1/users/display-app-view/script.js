var nwUsers = function () {
	return {
		recordItem: {
			id:"",
			firstname:"",
			lastname:"",
			phone_number:"",
			email:"",
			role:"",
			staff_number:"",
			password:"",
			confirmpassword:"",
		},
		init: function () {			
			$('#expense-view')
			.find("tr.item-record")
			.not(".activated")
			.on('click', function(e){
				e.preventDefault();
				
				$('#expense-view')
				.find("tr.item-record")
				.removeClass("active");
				
				$(this).addClass("active");
				
				var $item = $(this);
				nwUsers.recordItem.id = $item.attr("id");
				nwUsers.recordItem.firstname = $item.attr("data-firstname");
				nwUsers.recordItem.lastname = $item.attr("data-lastname");
				nwUsers.recordItem.phone_number = $item.attr("data-phone_number");
				nwUsers.recordItem.email = $item.attr("data-email");
				nwUsers.recordItem.role = $item.attr("data-role");
				nwUsers.recordItem.staff_number = $item.attr("data-staff_number");
				
				$("form#users")
				.find(".custom-single-selected-record-button")
				.attr("override-selected-record", nwUsers.recordItem.id );
				
				nwUsers.edit();
				
			})
			.addClass("activated");
			
			$('input[name="password"]')
			.on("blur", function(){
				if( $(this).val() ){
					var x = prompt( "Please confirm password" );
					if( x != $(this).val() ){
						alert("Invalid Password");
						$(this).val('');
					}
				}
			});
			
		},
		showRecentExpensesTab: function () {	
			//$('a[href="#recent-expenses"]').click();
		},
		reClick: function () {	
			$('#expense-view').find("tr.item-record.active").click();
		},
		edit: function () {	
			
			$.each( nwUsers.recordItem, function( key, val ){
				if( $("form#users").find('.form-control[name="'+key+'"]') ){
					$("form#users").find('.form-control[name="'+key+'"]').val( val );
				}
			} );
			
		},
		emptyNewItem: function(){
			$('#expense-view')
			.find("tr.item-record")
			.removeClass("active");
			
			$("form#users")
			.find(".custom-single-selected-record-button")
			.attr("override-selected-record", "" );
			
			$("form#users").find(".form-control").val('');
			nwUsers.recordItem = {
				id:"",
				firstname:"",
				lastname:"",
				phone_number:"",
				email:"",
				role:"",
				staff_number:"",
				password:"",
				confirmpassword:"",
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
		}
	};
	
}();
nwUsers.init();