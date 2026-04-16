<style type="text/css">
	#datatable-record-details-popup{
		position:absolute;
		width:25%;
		top:0px;
		right:5;
		display:none;
	}
	#datatable-record-details-popup .portlet-body{
		max-height:500px;
		overflow-y:auto;
	}
	#datatable-record-details-popup table th,
	#datatable-record-details-popup table td{
		font-size:0.7em;
		padding:4px;
	}
	#datatable-record-details-popup table td{
		width:50% !important;
	}
	#datatable-record-details-popup table td:nth-child(1){
		font-weight:bold;
		border-right:1px solid #ddd;
	}
	#record-details-content-container .details-section-container-row.details-edit td{
		background:#333;
		color:#fff;
	}
</style>
<div <?php set_hyella_source_path( __FILE__, 1 ); ?>>
<div id="datatable-record-details-popup" class="portlet box blue">
  <div class="portlet-title">
	 <div class="caption"><i class="icon-cogs"></i><small>Selected Record Details</small></div>
	 <a href="#" class="btn btn-xs blue pull-right" id="hide-selected-record-details-popup" title="Close Selected Record Details Pop-up"><i class="icon-remove"></i></a>
  </div>
  <div class="portlet-body">
	 <div class="table-responsive">
		<table class="table table-striped table-hover">
		<tbody id="record-details-content-container">
		   
		</tbody>
		</table>
	 </div>
  </div>
</div>
</div>
<script type="text/javascript">
(function($) {
    $.fn.nwRecordDetailsSidePane = {
        populateData: function( record_id, multiple_record_id ) {
			//record_id = "85027803446";
			
			if( ! ( $("#datatable-record-details-popup") && $("#datatable-record-details-popup").is(":visible") ) )
				return false;
			
			if( multiple_record_id ){
				var array_of_selected_records = multiple_selected_record_id.split(':::');
				
				var count = array_of_selected_records.length;
				
				details_of_multiple_selected_records = '';
				
				for( var i = 0; i < count; i++ ){
					//Push All Details to display container
					details_of_multiple_selected_records += $( '#main-details-table-' + array_of_selected_records[i] ).html();
					
					$('#record-details-content-container')
					.html( $('#main-details-table-'+record_id).find("tbody").html() );
				}
			}else{
				if( record_id && $("#"+record_id).is(":visible") ){
					var $row = $("#"+record_id).parents("tr");
					
					$('#record-details-content-container')
					.html( $('#main-details-table-'+record_id).find("tbody").html() );
				}
			}
			
			$("#record-details-content-container")
			.parents("table")
			.removeClass( $("#record-details-content-container").parents("table").attr("class-name") )
			.addClass("main-details-table-"+record_id)
			.attr("class-name", "main-details-table-"+record_id );
			
            //$.fn.cProcessForm.handleSubmission( settings.form );
            //$.fn.cProcessForm.populateRecentActivities( settings.recent_activity );
        },
		hideSelectedRecordDetailsPopup: function(){
			$( "#hide-selected-record-details-popup" )
			.on("click", function(e){
				e.preventDefault();
				$("#datatable-record-details-popup")
				.hide();
				
				$('#record-details-content-container')
				.empty();
			});
		},
		showSelectedRecordDetailsPopup: function(){
			$( ".show-selected-record-details-popup" )
			.on("click", function(e){
				e.preventDefault();
				$("#datatable-record-details-popup")
				.show();
				
				var record_id = $("tr.row_selected").find(".datatables-record-id").attr("id");
				if( record_id ){
					$.fn.nwRecordDetailsSidePane.populateData( record_id );
				}
				//if( $() )
				//$.fn.nwRecordDetailsSidePane.hideSelectedRecordDetailsPopup();
			});
		},
    }
	$.fn.nwRecordDetailsSidePane.hideSelectedRecordDetailsPopup();
	$.fn.nwRecordDetailsSidePane.showSelectedRecordDetailsPopup();
}(jQuery));
</script>