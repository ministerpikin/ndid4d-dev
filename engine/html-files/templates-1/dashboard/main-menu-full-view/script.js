var nwFilesFullView = function () {
	
    return {
        //main function to initiate the module
        init: function () {
			
			nwFilesFullView.clearCurrentFolder();
			
			$("#files-datatable_wrapper")
			.on("dblclick", "tr", function(){
				var record_id = $(this).find('.datatables-record-id').attr('id');
				if( record_id ){
					$("#folder-selector").data( "changed" , 0 );
					nwFilesFullView.openFolder( record_id );
				}
			});
			
			$("#folder-selector")
			.on("change", function(){
				nwFilesFullView.setCurrentFolder();
				
				$(this).data("changed", 1 );
				
				var record_id = $(this).val();
				if( record_id ){
					$("#open-file-folder-button")
					.attr("override-selected-record", record_id );
					
					nwFilesFullView.openFolder( record_id );
				}
			});
			
        },
		openFolder: function ( record_id ) {
			$("#open-file-folder-button")
			.attr("selected-record", record_id )
			.click()
			.attr("selected-record", "" )
			.attr("override-selected-record", "" );
			
			//setTimeout( function(){ $("#open-file-folder-button").attr("selected-record", "" ); }, 1000 );	
        },
		setCurrentFolder: function () {
			$(".use-current-folder")
			.attr("function-id", $("#folder-selector").val() );
        },
		clearCurrentFolder: function () {
			$(".use-current-folder")
			.attr("function-id", 1 );
        },
		updateCurrentFolder: function () {
			if( $("#folder-selector").data("changed") ){
				$("#folder-selector").find("option:first").remove();
				$("#folder-selector").data( "changed" , 0 );
				return false;
			}
			
			//remove duplicates
			$("#folder-selector")
			.find('option')
			.each(function(){
				$("#folder-selector").find('option[value="' + $(this).val() + '"]').not(':first').remove();
			});
			
			var $current = $("#folder-selector").find('option[value="'+$("#folder-selector").val()+'"]');
			var $most_recent = $("#folder-selector").find("option:first");
			
			$most_recent.text( $current.text() + $most_recent.text() );
			
			$("#folder-selector").val( $most_recent.attr("value") );
			
			nwFilesFullView.setCurrentFolder();
        },

    };

}();