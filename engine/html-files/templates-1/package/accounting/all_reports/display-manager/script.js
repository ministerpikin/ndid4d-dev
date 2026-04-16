var nwAppointment = function () {
	return {
		recordItem: {
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
		},
		refreshedForm:'',
		activeRefreshForm:'',
		url:'', //g_site_url + "php/ajax_request_processing_script.php",
		init: function () {			
			
			$('input[name="customer"]')
			.on('change', function(e){
				nwCustomers.activeRefreshForm = $(this).attr('refresh-form');
				nwCustomers.refreshedForm = '';
				nwCustomers.search();
			});
			
		},
		closePopup: function(){
			nwCustomers.refreshAcitveTab();
		},
		submitForm: function(){
			$("form#appointment").submit();
		},
	};
	
}();
nwAppointment.init();