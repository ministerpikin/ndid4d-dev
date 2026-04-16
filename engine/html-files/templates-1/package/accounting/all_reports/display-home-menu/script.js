var nwMisHome = function () {
	return {
		recordItem: {
			id:"",
		},
		init: function () {
			nwMisHome.startTreeview('my-tree');
		},
		startTreeview: function ( id ) {
			
			var g_container = '';
			var $a = $('a#' + id );
			
			if( $a.attr("data-action") ){
				
				$("#"+ id +"-con")
				.html('<div id="'+ id +'" class="demo"></div>');
				
				nwTreeView.selector = "#" + id;
				nwTreeView.action = $a.attr("data-action");
				nwTreeView.todo = $a.attr("data-todo");
				
				if( $a.attr("data-container") ){
					g_container = $a.attr("data-container");
				}
				
				nwTreeView.data = { html_replacement_selector:g_container, table: $a.attr("data-table"), development_mode_off:1 };
				
				nwTreeView.activate_tree_view_main();
			}
			
		},
	};
	
}();
nwMisHome.init();