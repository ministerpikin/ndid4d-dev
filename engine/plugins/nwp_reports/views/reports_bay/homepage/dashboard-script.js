var nwReportsBay2 = {
	data:[],
	addUrl:add_url,
	replaceUrl:replace_url,
	continue:true,
	backgroundColor : [
        'rgba(255, 99, 132, 0.7)',
        'rgba(54, 162, 235, 0.7)',
        'rgba(100, 200, 01, 0.7)',
        'rgba(111, 0, 255, 0.7)',
        'rgba(75, 192, 192, 0.7)',
        'rgba(153, 102, 255, 0.7)',
        'rgba(255, 159, 64, 0.7)',
        'rgba(63, 201, 99, 0.7)',
        'rgba(255, 206, 86, 0.7)',
        'rgba(100, 134, 190, 0.7)',
        'rgba(54, 255, 60, 0.7)',
        'rgba(255, 255, 106, 0.7)'
    ],
    borderColor : [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(111, 0, 255, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)',
        'rgba(63, 201, 99, 1)',
        'rgba(100, 200, 01, 1)',
        'rgba(100, 134, 190, 1)',
        'rgba(54, 255, 60, 1)',
        'rgba(255, 255, 106, 1)'
    ],
    bgColor : [ 'primary', 'info', 'success', 'warning', 'danger', 'dark'],
	init: async function( filterState = false ){
		nwReportsBay2.continue = true;

		var reports = $('div[report-card]');
		var index = 0;
	    processNextReport();

	    function processNextReport() {
	      	if( ! nwReportsBay2.continue ){
		      return;
	      	}else if (index < reports.length) {
				var reportID = $(reports[index]).attr('report-card');
				var reportData = $(reports[index]).find( 'textarea#ep-' + reportID ).val();

				reportData = reportData ? JSON.parse( reportData ) : {};

				loadReportBayDashboard( reportData, filterState )
				.then(function() {
					// Move to the next report after a delay
					index++;
					processNextReport()
				}).catch(function(error) {
					// Move to the next report even if there was an error
					index++;
					processNextReport()
				});
			}
      	}	
	},
	makeAjaxRequest: function( reportData, chartExisting ){
  		return new Promise(function( resolve, reject ){
  			var rdd = reportData.data;
			let savedQuery = typeof reportData.saved_query !== 'undefined' && reportData.saved_query && reportData.saved_query.trim() ? JSON.parse(reportData.saved_query) : {};
	        rdd[ 'use_field_keys' ] = 1;

  			var reportID = reportData && reportData.id ? reportData.id : '';

	        if( reportData.endpoint ){
	        	if( addQuery && ! $.isEmptyObject( addQuery ) ){
	        		var cnt = 1;
	        		if( rdd && rdd.query && rdd.query.query_options ){
	        			cnt = ( Object.keys( rdd.query.query_options ).length ) + 1;
	        		}else{
	        			rdd.query = {};
	        			rdd.query.query_options = {};
	        		}

	        		var ptb = '';
	        		if( rdd.table ){
	        			ptb = rdd.table;
	        		}

	        		$.each( addQuery, function( a, b ){
	        			if( b && b.data && ! $.isEmptyObject( b.data ) ){
			        		$.each( b.data, function( x, y ){
			        			b.data[ x ][ "sub_query" ] = cnt.toString();
			        			b.data[ x ][ "table_name" ] = ptb;
			        		})
			        		rdd.query.query_options[ cnt.toString() ] = b;
			        		cnt++;
	        			}
	        		})
	        	}

				// Adding Saved Query to displayed report
				if( savedQuery && ! $.isEmptyObject( savedQuery ) ){
					
					var cnt = 1;
	        		if( rdd && rdd.query && rdd.query.query_options ){
						cnt = ( Object.keys( rdd.query.query_options ).length ) + 1;
	        		}else{
						rdd.query = {};
	        			rdd.query.query_options = {};
	        		}
					
	        		ptb = typeof rdd.table !== 'undefined' ? rdd.table : '';
					if(typeof savedQuery.cart_items !== 'undefined'){
						$.each( savedQuery.cart_items, function( a, b ){
							if( b && b.data && ! $.isEmptyObject( b.data ) ){
								$.each( b.data, function( x, y ){
									b.data[ x ][ "sub_query" ] = cnt.toString();
									b.data[ x ][ "table_name" ] = ptb;
								})
								rdd.query.query_options[ cnt.toString() ] = b;
								cnt++;
							}
						});
					}
					
					if(typeof savedQuery.report_limit !== 'undefined' && savedQuery.report_limit > 0 ){
						rdd.query.limit = savedQuery.report_limit;
					}
				}

	        	if( nwReportsBay2.replaceUrl ){
					reportData.endpoint = nwReportsBay2.replaceUrl;
	        	}
				$.ajax({
					url: reportData.endpoint + nwReportsBay2.addUrl,
		            type: 'post',
					// data: "table=es_monitoring&alias=count",
					data: { data : JSON.stringify( rdd ), action : 'execute' },
					success: function( response ){

						if( response && response.trim() ){
							resolve(response);
							if( typeof response !== 'object' ){
								response = JSON.parse( response );
							}

							nwReportsBay2.execSuccessRequest( response, reportData, chartExisting );
						}else{
							reject( { "message" : "Empty Response" } );
						}
					},
					error: function( error ) {
						// Reject the Promise with the error
						reject( error );
					}
				});
			}else{
				reject( "Report Data has no endpoint - " + reportID );
			}
		});
	},
	allFilter: function(){
		if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.data  ){
			if( $.fn.cProcessForm.returned_ajax_data.close_modal  ){
				$( 'button#modal-popup-close' ).click();
			}
				
			$( 'a.DashboardAllFilter' ).find( 'i' ).removeClass().addClass( 'ri-filter-fill' );
			addQuery = $.fn.cProcessForm.returned_ajax_data.data;

			var h = '';
			if( typeof $.fn.cProcessForm.returned_ajax_data.report !== 'undefined' ){
				h += '\
				<a href="#" action="?action=nwp_reports&todo=execute&nwp_action=reports_bay&nwp_todo='+ ( typeof dashboard_action != 'undefined' && dashboard_action ? dashboard_action : 'display_dashboard' ) +'&report='+ $.fn.cProcessForm.returned_ajax_data.report +'" class="custom-single-selected-record-button page-title-right"  override-selected-record="-" title="Clear All Filter">\
					<i class="ri-delete-bin-fill text-dark" style="font-size: 25px;"></i>\
				</a>';
			}

			if (!$('.dashboard-filter-buttons').find('i.ri-delete-bin-fill').length) {
				$( '.dashboard-filter-buttons' ).append( h );
			}

			nwReportsBay2.init( true );
		}
	},
	singleModify: function(){
		if( nwReportsBay2.singleModifyCalled ){
			return;
		}

		if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.record && $.fn.cProcessForm.returned_ajax_data.record.id ){
			nwReportsBay2.singleModifyCalled = 1;
			
			var r = $.fn.cProcessForm.returned_ajax_data.record;
			var id = r.id;

			var card;
			if( $.fn.cProcessForm.returned_ajax_data ){
				card = $("div[report-card='"+ id +"']");
			}

			if( r && r.parent_report ){
				var iid = r.parent_report;
				if( typeof $.fn.cProcessForm.returned_ajax_data.cache_key !== 'undefined' && card.length ){
					var atr = $("div[report-card='"+ iid +"']").find( 'a#filter-card-' + iid );
					var atr2 = atr.attr( 'action' );
					atr2 += '&cache_key=' + $.fn.cProcessForm.returned_ajax_data.cache_key;
					atr.attr( 'action', atr2 );
				}

				$("textarea#ep-"+ iid ).val( JSON.stringify( r ) );
	    		if( typeof nwReportsBay2.chartInstances[ iid ] !== 'undefined' ){
	    			nwReportsBay2.chartInstances[ id ] = nwReportsBay2.chartInstances[ iid ];
	    			delete nwReportsBay2.chartInstances[ iid ];
	    		}

				$("textarea#ep-"+ iid ).attr( 'id', 'ep-'+id );
				$("div#"+ iid + "_charts" ).attr( 'id', id+'_charts' );
				$("a#filter-card-"+ iid ).attr( 'id', 'filter-card-'+id );
				$("a#export_csv-"+ iid ).attr( 'id', 'export_csv-'+id );
				$("div[report-card='"+ iid +"']").find( 'a.custom-single-selected-record-button' ).attr( 'override-selected-record', id );

				$("div[report-card='"+ iid +"']").attr( 'report-card', id );
				card = $("div[report-card='"+ id +"']");
			}

			var typ = r.type;

			card.find(".c-title").text( r.name );
			card.find(".c-sub-title").text( r.title );

			switch( typ ){
			case 'card':
				
				card.find("h2.main-value").addClass( 'placeholder' ).text( '' );
				card.find("p.sub-value").addClass( 'placeholder' ).text( '' );

			break;
			default:
				card.find( ".card-body" ).addClass( 'placeholder' );
			break;
			}

			$( 'button#modal-popup-close' ).click();
			

			$.fn.cProcessForm.returned_ajax_data.timeout = 500;
			if( typeof $.fn.cProcessForm.returned_ajax_data.record.data !== 'object' ){
				$.fn.cProcessForm.returned_ajax_data.record.data = JSON.parse( $.fn.cProcessForm.returned_ajax_data.record.data );
				loadReportBayDashboard( $.fn.cProcessForm.returned_ajax_data.record, 1 );
			}
			
			setTimeout(function(){
				nwReportsBay2.singleModifyCalled = 0;
			}, 1000)
		}
	},
	singleModifyCalled: 0,
	singleModify2: function(){
		if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.record && $.fn.cProcessForm.returned_ajax_data.record.id ){

			var r = $.fn.cProcessForm.returned_ajax_data.record;
			var id = r.id;
			var card = $("div[report-card='"+ id +"']");
			let existing = 1;
			if( typeof $.fn.cProcessForm.returned_ajax_data.html_replacement_two !== 'undefined' ){
				if( !card.length ){
					if( typeof $.fn.cProcessForm.returned_ajax_data.data.parent_container !== 'undefined' ){
						$("div[report-card='"+ $.fn.cProcessForm.returned_ajax_data.data.parent_container +"']").replaceWith( $.fn.cProcessForm.returned_ajax_data.html_replacement_two );
						existing = 0;
						if( r.parent_report ){
							delete r.parent_report;
						}
					}
				}else{
					card.replaceWith( $.fn.cProcessForm.returned_ajax_data.html_replacement_two );
				}
			}

			var typ = r.type;

			if( existing ){
				card.find(".c-title").text( r.name );
				card.find(".c-sub-title").text( r.title );
				switch( typ ){
					case 'card':
						card.find("h2.main-value").addClass( 'placeholder' ).text( '' );
						card.find("p.sub-value").addClass( 'placeholder' ).text( '' );
					break;
					default:
						card.find( ".card-body" ).addClass( 'placeholder' );
					break;
				}
				if( r.parent_report && $("div[report-card='"+ r.parent_report +"']").length ){
					r.id = r.parent_report;
				}
			}
			
			$( 'button#modal-popup-close' ).click();
			
			loadReportBayDashboard( r, 0);
		}
	},
	singleFilter: function(){
		if( typeof $.fn.cProcessForm.returned_ajax_data.rID == 'undefined' ){
			return;
		}

		var rID = $.fn.cProcessForm.returned_ajax_data.rID;

		var card;
		if( $.fn.cProcessForm.returned_ajax_data ){
			card = $("div[report-card='"+ rID +"']");
		}

		if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.initial_id ){
			var iid = $.fn.cProcessForm.returned_ajax_data.initial_id;
			if( !card.length ){
				card = $("div[report-card='"+ iid +"']");	
			}
			if( typeof $.fn.cProcessForm.returned_ajax_data.cache_key !== 'undefined' && card.length ){
				var atr = $("div[report-card='"+ iid +"']").find( 'a#filter-card-' + iid );
				var atr2 = atr.attr( 'action' );
				atr2 += '&cache_key=' + $.fn.cProcessForm.returned_ajax_data.cache_key;
				atr.attr( 'action', atr2 );
			}

			if( rID != iid ){
				$("textarea#ep-"+ iid ).attr( 'id', 'ep-'+rID );
				$("a#filter-card-"+ iid ).attr( 'id', 'filter-card-'+rID );
				$("a#export_csv-"+ iid ).attr( 'id', 'export_csv-'+rID );
				$("div[report-card='"+ iid +"']").find( 'a.custom-single-selected-record-button' ).attr( 'override-selected-record', rID );

				$("div[report-card='"+ iid +"']").attr( 'report-card', rID );				
			}
		}else{			
			setTimeout(function(){
				if( typeof $.fn.cProcessForm.returned_ajax_data.cache_key !== 'undefined' && card.length ){
					var atr = card.find( 'a#filter-card-' + rID );
					var atr2 = atr.attr( 'action' );
					atr2 += '&cache_key=' + $.fn.cProcessForm.returned_ajax_data.cache_key;
					card.find( 'a#filter-card-' + rID ).attr( 'action', atr2 );
				}
			},200);
		}


		if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.rtype ){
			
			switch( $.fn.cProcessForm.returned_ajax_data.rtype ){
			case 'card':
				
				card.find("h2.main-value").addClass( 'placeholder' ).text( '' );
				card.find("p.sub-value").addClass( 'placeholder' ).text( '' );

			break;
			default:
				card.find( ".card-body" ).addClass( 'placeholder' );
			break;
			}

			if( $.fn.cProcessForm.returned_ajax_data && $.fn.cProcessForm.returned_ajax_data.close_modal ){
				$( 'button#modal-popup-close' ).click();
			}

			$.fn.cProcessForm.returned_ajax_data.timeout = 500;
			setTimeout(function(){
				nwReportsBay2.execSuccessRequest( $.fn.cProcessForm.returned_ajax_data, $.fn.cProcessForm.returned_ajax_data.report, 1 );
			},500);
		}
	},
	chartInstances: {},
	chartInstancesInitialized: {},
	chartFilterActive:{},
	execSuccessRequest: function( response, report, chartExisting ){
		var rdd = report && report.data ? ( typeof report.data !== 'object' ? JSON.parse( report.data ) : report.data ) : {};
		var rtype = report && report.type ? report.type : '';
		var reportID = report && report.id ? report.id : '';
		let mLength = 62;

		var card = $("div[report-card='"+ reportID +"']");

		switch( rtype ){
		case 'card':
		case 'key_value':
			if( response && response.data && typeof response.data[0] !== 'undefined' ){

				switch( rtype ){
				case 'card':

					var value;
					var suffix = '';
					if( typeof response.data[0][ 'count' ] !== 'undefined' ){
						value = response.data[0][ 'count' ];
					}else if( typeof response.data[0][ 'percentage' ] !== 'undefined' ){
						suffix = '%';
						value = response.data[0][ 'percentage' ];
					}else if( typeof response.data[0][ 'average_resolution_days' ] !== 'undefined' ){
						suffix = ' days';
						value = response.data[0][ 'average_resolution_days' ];
					}

					if( typeof nwReportsBay2.chartInstancesInitialized[ reportID ] !== 'undefined' ){
						if( value != nwReportsBay2.chartInstancesInitialized[ reportID ] ){
			    			nwReportsBay2.chartFilterActive[ reportID ] = true;
			    		}else{
			    			nwReportsBay2.chartFilterActive[ reportID ] = false;
			    		}
					}else{
			    		nwReportsBay2.chartFilterActive[ reportID ] = false;
			    		nwReportsBay2.chartInstancesInitialized[ reportID ] = value;
			    		if( typeof response.initial_id !== 'undefined' && typeof nwReportsBay2.chartInstancesInitialized[ response.initial_id ] !== 'undefined' ){
    						if(value != nwReportsBay2.chartInstancesInitialized[ response.initial_id ] ){
    			    			nwReportsBay2.chartFilterActive[ reportID ] = true;
    			    		}
			    		}
					}

					if( typeof value == 'undefined' ){
						value = 0;
					}

					var timeout = 50;
					if( response.timeout ){
						timeout = response.timeout;
					}

					var NT = nwReportsBay2.nw_pretty_number( value, 1 );
					var nnum = typeof  NT.num !== 'undefined' ? NT.num : 0;

					var vl = '<span class="counter-value '+ reportID +'" data-textX="'+ nnum +'" data-target="'+ nnum +'">0</span>' + (typeof  NT.letter !== 'undefined' ? NT.letter : '' );
					vl += suffix;

					card.find("h2.main-value").removeClass( 'placeholder' );
					card.find("p.sub-value").removeClass( 'placeholder' );

					card.find("h2.main-value").html( vl );
					nwReportsBay2.countValue( reportID, timeout );
				break;
				case 'key_value':

					if( typeof response.data[0][ 'key' ] !== 'undefined' && typeof response.data[0][ 'value' ] !== 'undefined' ){
						var t1 = '';
						t1 += '<div class="table-responsive table-card">';
							t1 += '<table class="table align-middle table-borderless table-centered table-nowrapX mb-0">';
								t1 += '<thead class="text-muted table-light">';
									t1 += '<tr>';
										t1 += '<th scope="col" style="width: 62;">KPI</th>';
										t1 += '<th scope="col">Metric</th>';
									t1 += '</tr>';
								t1 += '</thead>';
								t1 += '<tbody>';

									$.each( response.data, function( km, vm ){

										t1 += '<tr>';
											t1 += '<td>';
												t1 += '<a href="javascript:void(0);">'+ vm.key +'</a>';
											t1 += '</td>';
											t1 += '<td>'+ vm.value +'</td>';
										t1 += '</tr>';

									});

								t1 += '</tbody><!-- end tbody -->';
							t1 += '</table><!-- end table -->';
						t1 += '</div><!-- end -->';

						card.find("h2.main-value").removeClass( 'placeholder' );
						card.find("p.sub-value").removeClass( 'placeholder' );
						
						$( "div#"+ reportID +"_charts" ).html( t1 );

					}

				break;
				}

			}else if( response && response.msg ){
				let msg = response.msg.length > mLength ? response.msg.slice(0, mLength) + '...' : response.msg;

				card.find("h2.main-value").removeClass( 'placeholder' );
				card.find("p.sub-value").removeClass( 'placeholder' );

				card.find( ".placeholder" ).removeClass( 'placeholder' );
				card.find("h2.main-value").text( msg );
				
				if( chartExisting ){
					nwReportsBay2.chartFilterActive[ reportID ] = true;
				}else{
					nwReportsBay2.chartFilterActive[ reportID ] = false;
					nwReportsBay2.chartInstancesInitialized[ reportID ] = null;
				}
				if(card.find('.card-body').hasClass('p-0')){
					card.find('.card-body').removeClass('p-0');
				}
			}

			if( chartExisting ){
	    		let filterExists = card.find('.filter-active');
	    		if( typeof nwReportsBay2.chartFilterActive[ reportID ] !== 'undefined' ){
	    			if( !filterExists.length && nwReportsBay2.chartFilterActive[ reportID ] ){
	    				card.find(".card-body").append('<p class=" filter-active badge bg-danger p-1 rounded-pill">Filter Active</p>');
	    			}
	    			if( filterExists.length && !nwReportsBay2.chartFilterActive[ reportID ] ){
	    				filterExists.remove();
	    			}
	    		}					
			}
		break;
		case 'nested_card':
		case 'pie':
		case 'pline':
		case 'pline2':
		case 'bar':
		case 'hbar':
		case 'line':

			if( response && response.data && response.data[0] ){
				var lbl = [];
				var ddl = [];
				var dx = {};
				var backgroundColor = [];
				var borderColor = [];
				var sn = 0;

				var min = 0;
				var max = 0;
				let barReport = false;
				let limit = parseInt(report?.more_data?.query_limit || rdd?.query?.limit || 0);
				let showLimit = false;

				var total = 0;

				var table = '';
				if( rdd && rdd.table ){
					table = rdd.table;
				}

				var group = '';
				let use_groups_default = true;
				if( rdd && rdd.use_groups && rdd.use_groups[ table ] && ! $.isEmptyObject( rdd.use_groups[ table ] ) ){
					showLimit = true;
					$.each( rdd.use_groups[ table ], function( kk, vv ){
						group = kk;
						return;
					});

					if( Object.keys( rdd.use_groups[ table ] ).length > 1 ){
						use_groups_default = false;
						switch( rtype ){
							case 'bar':
							case 'hbar':
								barReport = true;
							break;
						}
					}
				}


				var colourKey = 'backgroundColor';
				switch( rtype ){
					case 'pline':
					case 'pline2':
						colourKey = 'bgColor';
					break;
				}

				if ( !use_groups_default && barReport && rdd && rdd.report_display_options && !$.isEmptyObject( rdd.report_display_options ) ) {
					//Allows bar graphs with multiple bars specified by report_display_options
					let dlbl = rdd.report_display_options.display_label_field ?? '';
					let field = rdd.report_display_options.field ?? '';
					let countKey2 = rdd.report_display_options.count_key ?? 'count';
					
					if ( dlbl && field && countKey2 ) {
						lbl = [ ...new Set( response.data.map(item => item[ dlbl ] ) ) ]; // Create a unique array of labels
						let fieldOptions = [ ...new Set( response.data.map(item => item[ field ] ) ) ]; //Creates a unique array of field Options displayed on the report
						ddl = fieldOptions.map( fieldOption => ({
							name: fieldOption,
							data: lbl.map( labelOption => {
								let entry = response.data.find( item => item[ dlbl ] === labelOption && item[ field ] === fieldOption );
								return entry ? entry[ countKey2 ] : 0;
							})
						}) );
						backgroundColor = response.data.map((item, index) => nwReportsBay2[ colourKey ][ index % nwReportsBay2[ colourKey ].length ])
					}

				}else{

					$.each( response.data, function( k, v ){

						if( typeof nwReportsBay2[ colourKey ][ sn ] == 'undefined' ){
							sn = 0;
						}
						backgroundColor.push( nwReportsBay2[ colourKey ][ sn ] );
						borderColor.push( nwReportsBay2.borderColor[ sn ] );

						if( use_groups_default ){
							$.each( v, function( k1, v1 ){

								switch( k1 ){
								case group:
									lbl.push( v1 );
								break;
								default:
									if( nwReportsBay2.isNumber( v1 ) ){
										if( typeof dx[ k1 ] === 'undefined' ){
											dx[ k1 ] = [];
										}
										var vl = parseFloat( v1 );
										total += vl;

										dx[ k1 ].push( vl );

										if( ! max ){
											min = vl;
											max = vl;
										}

										if( vl > max ){
											max = vl;
										}

										if( vl < min ){
											min = vl;
										}
									}
								break;
								}
							});
						}

						sn++;
					});

					$.each( dx, function( k, v ){
						ddl.push( { data : v, name : k } );
					});
				}

				if( typeof nwReportsBay2.chartInstancesInitialized[ reportID ] !== 'undefined' ){
					if(JSON.stringify(ddl) != JSON.stringify(nwReportsBay2.chartInstancesInitialized[ reportID ] ) ){
		    			nwReportsBay2.chartFilterActive[ reportID ] = true;
		    		}else{
		    			nwReportsBay2.chartFilterActive[ reportID ] = false;
		    		}
				}else{
		    		nwReportsBay2.chartFilterActive[ reportID ] = false;	
		    		nwReportsBay2.chartInstancesInitialized[ reportID ] = ddl;
		    		if( typeof response.initial_id !== 'undefined' && typeof nwReportsBay2.chartInstancesInitialized[ response.initial_id ] !== 'undefined' ){
						if(value != nwReportsBay2.chartInstancesInitialized[ response.initial_id ] ){
			    			nwReportsBay2.chartFilterActive[ reportID ] = true;
			    		}
		    		}
				}

				if( chartExisting ){
		    		let filterExists = card.find('.filter-active');
		    		if( typeof nwReportsBay2.chartFilterActive[ reportID ] !== 'undefined' ){
		    			if( !filterExists.length && nwReportsBay2.chartFilterActive[ reportID ] ){
		    				card.find(".card-body").append('<p class=" filter-active badge bg-danger m-md-3 p-1 rounded-pill">Filter Active</p>');
		    			}
		    			if( filterExists.length &&  !nwReportsBay2.chartFilterActive[ reportID ] ){
		    				filterExists.remove();
		    			}
		    		}					
				}

	    		//last mike change on 26-oct-23
				if( typeof nwReportsBay2.chartInstances[ reportID ] !== 'undefined' ){
		    		nwReportsBay2.chartInstances[ reportID ].destroy();
	    		}

				card.find( ".placeholder" ).removeClass( 'placeholder' );

				switch( rtype ){
					case 'pline':

						if( lbl && ddl[0] && ddl[0][ 'data' ] ){

							var t1 = '';
							var t3 = '';
							var t2 = '<div class="row align-items-center">\
										<div class="col-12">\
											<h2 class="fs-24 mt-5 fw-semibold text-dark text-center">'+ nwReportsBay2.nw_pretty_number( total, 0 ) +'</h2>\
										</div><!-- end col -->\
									</div>';

							$.each( lbl, function( k1, v1 ){
								var p1 = 0;
								var p2 = 0;
								if( ddl[0][ 'data' ][ k1 ] ){
									var x = ( ddl[0][ 'data' ][ k1 ] / total ) * 100;
									p1 = x.toFixed( 0 );
									p2 = x.toFixed( 2 );
								}
								t1 += '<div class="progress-bar bg-'+ backgroundColor[ k1 ] +'" role="progressbar" style="width: '+ p1 +'%" aria-valuenow="'+ p1 +'" aria-valuemin="0" aria-valuemax="100"></div>';

								t3 += '<div class="d-flex mb-2">\
										<div class="flex-grow-1">\
											<p class="text-truncate text-muted fs-14 mb-0"><i class="mdi mdi-circle align-middle text-'+ backgroundColor[ k1 ] +' me-2"></i>'+ v1 +'\
											</p>\
										</div>\
										<div class="flex-shrink-0">\
											<p class="mb-0">'+ p2 +'%</p>\
										</div>\
									</div>';
							});

							t1 = '<div class="col-xl-6 col-md-6">' + t2 + '<div class="mt-3 pt-2"><div class="progress progress-lg rounded-pill">' + t1 + '</div></div></div>';

							t1 += '<div class="col-xl-6 col-md-6"><div class="mt-3 pt-2">' + t3 + '</div></div>';

							$( "div#"+ reportID +"_charts" ).html( '<div class="row">' + t1 + '</div>' );
						}
					break;
					case 'pline2':

						if( lbl && typeof dx.count != 'undefined' ){

							var t1 = '';
							var t3 = '';
							var t2 = '<div class="row align-items-center">\
										<div class="col-6">\
											<h6 class="text-muted text-uppercase fw-semibold text-truncate fs-12 mb-3 c-sub-title">\
												'+ report.title +'</h6>\
											<h4 class="fs- mb-0">'+ nwReportsBay2.nw_pretty_number( total, 0 ) +'</h4>\
										</div><!-- end col -->\
									</div><!-- end row -->';
							var t4 = '';

							var valuss = [];
							var suffix = '';
							if( typeof dx.average_resolution_days !== 'undefined' ){
								valuss = dx.average_resolution_days;
								suffix = 'days';
							}else if( typeof dx.average_resolution_time !== 'undefined' ){
								valuss = dx.average_resolution_time;
								suffix = 'mins';
							}else if( typeof dx.percentage !== 'undefined' ){
								valuss = dx.percentage;
								suffix = '%';
							}else if( typeof dx.count !== 'undefined' ){
								valuss = dx.count;
							}

							if( Object.keys( valuss ).length > 0 ){

								$.each( lbl, function( k1, v1 ){
									var p1 = 0;
									var p2 = 0;

									var x = ( dx.count[ k1 ] / total ) * 100;
									p1 = x.toFixed( 0 );
									
									if( valuss[ k1 ] ){
										x = valuss[ k1 ];
										p2 = x.toFixed( 2 );
									}
									t1 += '<div class="progress-bar bg-'+ backgroundColor[ k1 ] +'" role="progressbar" style="width: '+ p1 +'%" aria-valuenow="'+ p1 +'" aria-valuemin="0" aria-valuemax="100"></div>';

									t3 += '<div class="d-flex mb-2">\
											<div class="flex-grow-1">\
												<p class="text-truncate text-muted fs-14 mb-0"><i class="mdi mdi-circle align-middle text-'+ backgroundColor[ k1 ] +' me-2"></i>'+ v1 +'\
												</p>\
											</div>\
											<div class="flex-shrink-0">\
												<p class="mb-0">'+ p2 + ' ' + suffix +'</p>\
											</div>\
										</div>';
								});

								t4 += t2;

								t4 += '<div class="mt-3 pt-2">';
									t4 += '<div class="progress progress-lg rounded-pill">';
										t4 += t1;
									t4 += '</div>';
								t4 += '</div><!-- end -->';

								t4 += '<div class="mt-3 pt-2">';
									t4 += t3;
								t4 += '</div><!-- end -->';

								if( typeof nwReportsBay2.chartInstances[ reportID ] !== 'undefined' ){
									nwReportsBay2.chartInstances[ reportID ].destroy();
								}
								$( "div#"+ reportID +"_charts" ).html( t4 );

							}
						}
					break;
					case 'nested_card':

						if( lbl && ! $.isEmptyObject( dx ) ){

							var t1 = '';

							var cll = 12 / Object.keys( lbl ).length;
							var valuss = [];
							var suffix = '';
							if( typeof dx.percentage !== 'undefined' ){
								valuss = dx.percentage;
								suffix = '%';
							}else if( typeof dx.count !== 'undefined' ){

								valuss = dx.count;
							}

							var timeout = 50;
							if( response.timeout ){
								timeout = response.timeout;
							}

							$.each( lbl, function( kx, vx ){

								var vll = 0;
								if( valuss && valuss[ kx ] ){
									vll = valuss[ kx ];

									var NT = nwReportsBay2.nw_pretty_number( vll, 1 );
									var vll = typeof  NT.num !== 'undefined' ? NT.num : 0;

									suffix = (typeof  NT.letter !== 'undefined' ? NT.letter : suffix );
									
								}

								t1 += '<div class="col-'+ cll +' col-sm-'+ cll +'">';
									t1 += '<div class="p-3 border border-dashed border-start-0">';
										t1 += '<h5 class="mb-1"><span class="counter-value '+ reportID +'" data-target="'+ vll +'">0</span>'+ suffix;
										t1 += '</h5>';
										t1 += '<p class="text-muted mb-0">'+ vx +'</p>';
									t1 += '</div>';
								t1 += '</div>';

							})

							setTimeout(function(){
								nwReportsBay2.countValue( reportID, timeout );
							}, 100)
							
							$( "div#"+ reportID +"_charts" ).html( '<div class="row g-0 text-center">' + t1 + '</div>' );

						}
					break;
					case 'line':
					case 'hbar':
					case 'bar':
					case 'pie':
						switch( rtype ){
							case 'line':

								var options = {
									chart: {
										// height: 300,
										height: 400,
										type: rtype,
										zoom: {
											enabled: !1
										},
										toolbar: {
											show: !1
										}
									},
									colors: backgroundColor,
									dataLabels: {
										enabled: false
									},
									stroke: {
										width: [3, 3],
										curve: "straight"
									},
									series: ddl,
									labels: lbl,
									title: {
										text: report.name,
										align: "left",
										style: {
											fontWeight: 500
										}
									},
									grid: {
										row: {
											colors: ["transparent", "transparent"],
											opacity: .2
										},
										borderColor: "#f1f1f1"
									},
									markers: {
										style: "inverted",
										size: 6
									},
									xaxis: {
										categories: lbl,
									},
									yaxis: {
										min: min,
										max: max
									},
									legend: {
										position: "top",
										horizontalAlign: "right",
										floating: !0,
										offsetY: -25,
										offsetX: -5
									},
									responsive: [{
										breakpoint: 600,
										options: {
											chart: {
												toolbar: {
													show: !1
												}
											},
											legend: {
												show: !1
											}
										}
									}]
								};

							break;
							case 'pie':
								let p = false;
								options = {
									series: ddl[0][ 'data' ],
									chart: {
										height: 400,
										type: rtype
									},
									labels: lbl,
									legend: {
										position: "bottom"
									},
									dataLabels: {
										enabled: true,
										formatter: function (val, opts) {
										let l = opts.w.config.labels[opts.seriesIndex];
										let v = Number( val ).toFixed(1) + '%';
										if( (l.split(" ").length - 1 > 1) && !p ){
											p = true;
											return v;
										}
										if( p ){
											return v;
										}
										return l;
										},
										dropShadow: {
										enabled: false
										}
									},
									colors: backgroundColor
								}

							break;
							case 'hbar':
							case 'bar':
							var hbar = 0;
							switch( rtype ){
							case 'hbar':
								hbar = !0;
							break;
							}

							options = {
								series: ddl,
								chart: {
									type: 'bar',
									height: 300,
									// height: 400,
									toolbar: { show: !1 }
								},
								plotOptions: {
									bar: {
										borderRadius: 4,
										horizontal: hbar,
										distributed: !0,
										dataLabels: {
											position: "top"
										}
									}
								},
								colors: backgroundColor,
								dataLabels: {
									enabled: 1,
									offsetY: -20,
									style: {
										fontSize: "12px",
										fontWeight: 400,
										colors: ["#adb5bd"]
									}
								},
								legend: { show: !1 },
								grid: { show: !1 },
								xaxis: { categories: lbl  }
							}

							if( hbar ){
								options.dataLabels.offsetY = 0;
								options.dataLabels.offsetX = 20;
							}

							if ( barReport ) {
								options.plotOptions.bar.distributed = false;
								options.legend.show = true;
								options.grid.show = true;
							}

						break;
						}

						let container = document.querySelector( "div#"+ reportID +"_charts" );
						if(!container && typeof response.initial_id !== 'undefined'){
							container = document.querySelector( "div#"+ response.initial_id +"_charts" );
						}
						if(!container){
							return $.fn.cProcessForm.display_notification({ typ: 'jsuerror', theme: 'alert-warning', err: 'Warning', msg: 'There was an error loading chart. Kindly refresh your dashboard' });
						}
						container.innerHTML = '';
						var chart = new ApexCharts(container, options );
						chart.render();
						nwReportsBay2.chartInstances[ reportID ] = chart;
						
						if(!(card.find('.card-body').hasClass('p-0'))){
							card.find('.card-body').addClass('p-0');
						}
					break;
				}

				if(!limit && !showLimit){
					switch(rtype){
						case 'pline':
						case 'pline2':
						case 'line':
						case 'hbar':
						case 'bar':
							showLimit = true;
						break
					}
				}

				if(showLimit && rtype == 'pie'){
					showLimit = false;
				}

				if( showLimit && !limit ){
					limit = 10;
				}
				if(showLimit){
					let title = report.title + (report.title.trim() ? ' - ' : '') + ("Top " + limit + " By Total Volume");
					$('div[report-card="'+ reportID +'"]').find('.c-sub-title').text( title );
				}

			}else if( response && response.msg ){				
				let msg = response.msg.length > mLength ? response.msg.slice(0, mLength) + '...' : response.msg;
	    		if( typeof nwReportsBay2.chartInstances[ reportID ] !== 'undefined' ){
		    		nwReportsBay2.chartInstances[ reportID ].destroy();
	    		}

				card.find( ".placeholder" ).removeClass( 'placeholder' );
				$( "div#"+ reportID +"_charts" ).text( msg );
			}
			
			if(card.find('.card-body').hasClass('p-0')){
				card.find('.card-body').removeClass('p-0');
			}
		break;
		}

		if( reportID && typeof nwReportsBay2.chartFilterActive[ reportID ] !== 'undefined' ){
			let a = $('#export_csv-' + reportID);
			if( a.length ){
				let act = a.attr('action');
				if( nwReportsBay2.chartFilterActive[ reportID ] ){
					if( act && ! act.includes('&filter=1') ){
						a.attr('action', act + '&filter=1');
					}
				}else{
					a.attr('action', act.replace('&filter=1', ''));
				}				
			}
		}
	},
	countValue: function( span, timeout ){
        var e = document.querySelectorAll(".counter-value."+span);
        function s(e) {
            return e.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        e &&
            Array.from(e).forEach(function (n) {
                !(function e() {
                    var t = +n.getAttribute("data-target"),
                        a = +n.innerText,
                        o = t / timeout;
                    o < 1 && (o = 1), a < t ? ((n.innerText = (a + o).toFixed(0)), setTimeout(e, 1)) : (n.innerText = s(t)), s(n.innerText);
                })();
            });
    },
	isNumber: function( value ){
		// First, check if the value is a number (number or float)
		if (typeof value === "number" && !isNaN(value)) {
			return true;
		}

		// Next, check if the value is a string representation of a number
		// using a regular expression
		if (typeof value === "string" && /^\d+(\.\d+)?$/.test(value)) {
			return true;
		}

		// If none of the conditions above are met, return false
		return false;
	},
	nw_pretty_number: function( number = 0, separate ){
		// Convert to a numeric value if the input is a string
		number = parseFloat(number);

		if (isNaN(number)) {
		  return "Invalid number";
		}

		if (Math.abs(number) >= 1000 && Math.abs(number) < 1000000) {
		  // Format thousands (K)
		  var formattedNumber = (number / 1000).toFixed(2);
		  if( separate ){
		  	return { 'num' : formattedNumber.replace(/\.00$/, ""), 'letter' : 'K' }
		  }else{
			  return formattedNumber.replace(/\.00$/, "") + "K";
		  }
		} else if (Math.abs(number) >= 1000000) {
		  // Format millions (M)
		  var formattedNumber = (number / 1000000).toFixed(2);
		  if( separate ){
		  	return { 'num' : formattedNumber.replace(/\.00$/, ""), 'letter' : 'M' }
		  }else{
		  	return formattedNumber.replace(/\.00$/, "") + "M";
		  }
		} else {
		  // Format the number with commas and round to 2 decimal places
			var num = number.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",").replace(/\.00$/, "");
			if( separate ){
				return { 'num' : num }
			}
			return num;
		}
    },
	typeitTimer: '',
	typeitContent: '',
	typeitText: '',
	typeitCount: 0,
	typeit: function( cont1 ){
		var speed = 50;
		
		nwReportsBay2.typeitText = cont1.attr('data-text');
		nwReportsBay2.typeitCount = 0;

		if( typeof nwReportsBay2.typeitText === 'undefined' ){
			return;
		}
		
		if( nwReportsBay2.typeitText ){
			
			cont1.addClass('typed');
			nwReportsBay2.typeitContent = nwReportsBay2.typeitText.split("");
			
			if( nwReportsBay2.typeitTimer ){
				clearInterval( nwReportsBay2.typeitTimer );
			}
			
			nwReportsBay2.typeitTimer = setInterval( function(){
				//console.log('a', 1);
				if( nwReportsBay2.typeitCount < nwReportsBay2.typeitContent.length)
				{		
					cont1.append( nwReportsBay2.typeitContent[ nwReportsBay2.typeitCount ] );
					++nwReportsBay2.typeitCount;
				}else{
					clearInterval( nwReportsBay2.typeitTimer );
					
					if( cont1.not('.typed') ){
						nwReportsBay2.typeit( cont1.not('.typed').filter(":first") );
					}
				}
			}, speed );
		}else{
			cont1.addClass('typed');
			nwReportsBay2.typeit( $('.stats-value').not('.typed').filter(":first") );
		}
	},
	loadChartData:function( e = {} ){
		if( e.html_container &&  e.data && e.data.type && e.data.data && e.data.data.labels && e.data.data.datasets ){
			var ctx = $('#'+e.html_container);
			var myChart = new Chart(ctx, e.data);
		}
	}
};
nwReportsBay2.init();


async function loadReportBayDashboard( reportData, chartExisting ){
	if( reportData && reportData.data && typeof reportData.data !== 'object' ){
		reportData.data = JSON.parse( reportData.data );
	}

	try {
		// Wait for the Promise to resolve before proceeding to the next iteration
		// console.error( reportData );
		// console.error( chartExisting );
		const response = await nwReportsBay2.makeAjaxRequest( reportData, chartExisting );
		// console.log( 'Received response:', response );
    } catch (error) {
		// console.error( 'Error:', error );
		var reportID = reportData.id;
		if( reportData.type ){
			var errMsg = '';
			if( error && error.message ){
				var errMsg = error.message;
			}else if( error && error.statusText ){
				var errMsg = error.statusText;
			}

			// console.error( error.statusText );
			if( errMsg ){

				var card = $("div[report-card='"+ reportID +"']");

		    	switch( reportData.type ){
		    	case 'card':

	    			card.find("h2.main-value").removeClass( 'placeholder' );
	    			card.find("p.sub-value").removeClass( 'placeholder' );

	    			card.find( ".placeholder" ).removeClass( 'placeholder' );
	    			card.find("h2.main-value").text( errMsg );

		    	break;
		    	case 'pie':
		    	case 'pline':
		    	case 'bar':
		    	case 'hbar':

	    			card.find( ".placeholder" ).removeClass( 'placeholder' );
	    			$( "div#"+ reportID +"_charts" ).text( errMsg );

		    	break;
		    	}
	    	}
    	}
    }
}
