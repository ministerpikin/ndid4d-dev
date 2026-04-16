var nwExpenses = function () {
	return {
		expenseItem: {
			id:"",
			date:0,
			vendor:"",
			description:"",
			amount_due:0,
			amount_paid:0,
			mode_of_payment:"",
			receipt_no:"",
			category_of_expense:"",
			staff_in_charge:"",
		},
		init: function () {			
			$('#expense-view')
			.find("tr.item-expense")
			.not(".activated")
			.on('click', function(e){
				e.preventDefault();
				
				$('#expense-view')
				.find("tr.item-expense")
				.removeClass("active");
				
				$(this).addClass("active");
				
				var $item = $(this);
				nwExpenses.expenseItem.id = $item.attr("id");
				nwExpenses.expenseItem.date = $item.attr("data-date");
				nwExpenses.expenseItem.vendor = $item.attr("data-vendor");
				nwExpenses.expenseItem.description = $item.attr("data-description");
				nwExpenses.expenseItem.amount_due = $item.attr("data-amount_due");
				nwExpenses.expenseItem.amount_paid = $item.attr("data-amount_paid");
				nwExpenses.expenseItem.mode_of_payment = $item.attr("data-mode_of_payment");
				nwExpenses.expenseItem.receipt_no = $item.attr("data-receipt_no");
				nwExpenses.expenseItem.category_of_expense = $item.attr("data-category_of_expense");
				nwExpenses.expenseItem.staff_in_charge = $item.attr("data-staff_in_charge");
				
				nwExpenses.edit();
				
			})
			.addClass("activated");
			
		},
		showRecentExpensesTab: function () {	
			$('a[href="#recent-expenses"]').click();
		},
		reClick: function () {	
			$('#expense-view').find("tr.item-expense.active").click();
		},
		edit: function () {	
			
			$.each( nwExpenses.expenseItem, function( key, val ){
				if( $("form#expenditure").find('.form-control[name="'+key+'"]') ){
					$("form#expenditure").find('.form-control[name="'+key+'"]').val( val );
				}
			} );
			
		},
		emptyNewItem: function(){
			$('#expense-view')
			.find("tr.item-expense")
			.removeClass("active");
				
			$("form#expenditure").find(".form-control").val('');
			nwExpenses.expenseItem = {
				id:"",
				date:0,
				vendor:"",
				description:"",
				amount_due:0,
				amount_paid:0,
				mode_of_payment:"",
				receipt_no:"",
				category_of_expense:"",
				staff_in_charge:"",
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
nwExpenses.init();