var nwReportsBay = {
	data:dd,
	addUrl:add_url,
	backgroundColor : [
        'rgba(255, 99, 132, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(100, 200, 01, 0.2)',
        'rgba(111, 0, 255, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        'rgba(63, 201, 99, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(100, 134, 190, 0.2)',
        'rgba(54, 255, 60, 0.2)',
        'rgba(255, 255, 106, 0.2)'
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
	init:function(){
		if( ! $.isEmptyObject( nwReportsBay.data ) ){
			$.each( nwReportsBay.data, function( k, v ) {
				v.data = JSON.parse( v.data );
				$.ajax({
					url: v.endpoint + nwReportsBay.addUrl,
                    type: 'post',
					// data: "table=es_monitoring&alias=count",
					data: { data : v.data, action : 'execute' },
					success: function( response ){
						response = JSON.parse( response );
						switch( v.type ){
						case 'card':
							if( response && response.data && response.data[0] && response.data[0][ 'count' ] ){
								var card = $("div#"+ v.id +"-card");
								card.find(".widget-numbers").text( response.data[0].count );
							}
						break;
						case 'bar':
						case 'pie':
							// console.log( response );
							if( response && response.data && response.data[0] && response.data[0][ 'count' ] ){
								var lbl = [];
								var ddl = [];
								var backgroundColor = [];
								var borderColor = [];
								var sn = 0;
								$.each( response.data, function( k, v ){

									if( typeof nwReportsBay.backgroundColor[ sn ] == 'undefined' ){
										sn = 0;
									}
									backgroundColor.push( nwReportsBay.backgroundColor[ sn ] );
									borderColor.push( nwReportsBay.borderColor[ sn ] );
									
									// if( ! sn ){
										$.each( v, function( k1, v1 ){
											switch( k1 ){
											case 'count':
												var count = 0;
												if( typeof v.count !== 'undefined' ){
													count = parseFloat( v.count );
												}
												ddl.push( v1 );
											break;
											default:
												lbl.push( v1 );
											break;
											}
										});
									// }
									sn++;
								});

								var dd = {
								  "data": {
								    "type": v.type,
								    "data": {
								      "labels": lbl,
								      "datasets": [
								        {
								          "label": "",
								          "borderWidth": 1,
								          "data": ddl,
								          "backgroundColor": backgroundColor,
								          "borderColor": borderColor
								        }
								      ]
								    },
								    "options": {
								      "scales": {
								        "yAxes": [
								          {
								            "ticks": {
								              "beginAtZero": true
								            }
								          }
								        ]
								      }
								    }
								  },
								  "key": "stores",
								  "table": "assets",
								  "title": v.name,
								  "html_container": v.id + "-canvas"
								};
								
								nwReportsBay.loadChartData( dd );

							}
						break;
						}
						// alert();
					},
				});
			});
		}
	},
	loadChartData:function( e = {} ){
		if( e.html_container &&  e.data && e.data.type && e.data.data && e.data.data.labels && e.data.data.datasets ){
			var ctx = $('#'+e.html_container);
			var myChart = new Chart(ctx, e.data);
		}
	}
};
nwReportsBay.init();