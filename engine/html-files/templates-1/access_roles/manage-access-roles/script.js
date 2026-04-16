var nwAccess_roles = function () {
	return {
		data:{
			accessible_functions:{},
			status:{},
			states:{},
			lga:{},
		},
		first: 1,
		init: function () {
			
			var dj = JSON.parse( $("form#access_role-form").find('textarea[name="data"]').val() );
			
			if( dj && ! $.isEmptyObject( dj ) && dj.accessible_functions ){
				if( $.isEmptyObject( dj.accessible_functions ) ){
					dj.accessible_functions = {};
				}
				
				//nwAccess_roles.data = JSON.parse( JSON.stringify( dj ) );
				nwAccess_roles.data = dj;
			}
			
			nwAccess_roles.updateData();
			
			$("form#client-form")
			.submit(function(e){
				e.preventDefault();
				
				nwAccess_roles.updateData();
				
				$("form#access_role-form").submit();
			});
			
			$(".cls-con-lga").hide();
			
			$('input[name="accessible_functions[]"]')
			.change(function(){
				
				if( $(this).is(":checked") ){
					nwAccess_roles.data.accessible_functions[ $(this).val() ] = 1;
				}else{
					
					if( nwAccess_roles.data.accessible_functions[ $(this).val() ] ){
						delete nwAccess_roles.data.accessible_functions[ $(this).val() ];
					}
					
				}
				
				nwAccess_roles.updateDataTimer();
			});
			
			$("input.status-item")
			.change(function(){
				
				var $e = $( '#mod-' + $(this).val() );
				var s = $(this).attr('data-table');
				
				if( $.isEmptyObject( nwAccess_roles.data.status ) ){
					nwAccess_roles.data.status = {};
				}
				
				if( $(this).is(":checked") ){
					if( ! nwAccess_roles.data.status[ s ] ){
						nwAccess_roles.data.status[ s ] = {};
					}
					nwAccess_roles.data.status[s][ $(this).val() ] = 1;
				}else{
					
					if( nwAccess_roles.data.status && nwAccess_roles.data.status[s] && nwAccess_roles.data.status[s][ $(this).val() ] ){
						delete nwAccess_roles.data.status[s][ $(this).val() ];
						
						if( $.isEmptyObject( nwAccess_roles.data.status[s] ) ){
							delete nwAccess_roles.data.status[s];
						}
					}
					
				}
				
				nwAccess_roles.updateData();
			}).change();
			
			$("input.state-item")
			.change(function(){
				
				var $e = $( '#mod-' + $(this).val() );
				
				/*
				//auto select all lga but has bug during modifying access role where all lga is not selected
				$( 'input[value="a-' + $(this).val() + '"]' )
				.prop( "checked", $(this).prop("checked") );
				*/
				
				if( $.isEmptyObject( nwAccess_roles.data.states ) ){
					nwAccess_roles.data.states = {};
				}
				
				if( $(this).is(":checked") ){
					nwAccess_roles.data.states[ $(this).val() ] = 1;
					$e.show();
					
				}else{
					$e.hide();
					
					if( ! nwAccess_roles.first ){
						$e.find('input[type="checkbox"]').prop("checked", false );
					
						if( nwAccess_roles.data.states[ $(this).val() ] ){
							delete nwAccess_roles.data.states[ $(this).val() ];
						}
					}
					
				}
				
				nwAccess_roles.updateData();
			}).change();
			
			$("input.lga-item")
			.change(function(){
				
				var s = $(this).attr('data-state');
				
				var a = 0;
				if( $(this).hasClass('lga-item-all') ){
					a = 1;
				}
				
				
				if( s ){
					
					if( $.isEmptyObject( nwAccess_roles.data.lga ) ){
						nwAccess_roles.data.lga = {};
					}
					
					if( $(this).is(":checked") ){
						if( ! nwAccess_roles.data.lga[ s ] ){
							nwAccess_roles.data.lga[ s ] = {};
						}
						
						nwAccess_roles.data.lga[ s ][ $(this).val() ] = 1;
						
						if( a ){
							nwAccess_roles.data.lga[ s ] = {};
							nwAccess_roles.data.lga[ s ][ $(this).val() ] = 1;
							
							$("input.lga-" + s )
							.prop("checked", true )
							.prop("disabled", true );
						}
					}else{
						
						if( nwAccess_roles.data.lga[s] && nwAccess_roles.data.lga[s][ $(this).val() ] ){
							delete nwAccess_roles.data.lga[s][ $(this).val() ];
						}
						
						if( a ){
							$("input.lga-" + s )
							.prop("disabled", false );
						}
					}
					
					nwAccess_roles.updateDataTimer();
				}else{
					var data = {theme:'alert-danger', err:'Invalid State', msg:'Please try again', typ:'jsuerror' };
					$.fn.cProcessForm.display_notification( data );
				}
				
			}).change();
			
			nwAccess_roles.first = 0;
		},
		timer: '',
		updateDataTimer: function(){
			if( nwAccess_roles.timer ){
				clearTimeout( nwAccess_roles.timer );
			}
			nwAccess_roles.timer = setTimeout( nwAccess_roles.updateData, 300 );
		},
		updateData: function(){
			nwAccess_roles.timer = '';
			//$("form#client-form").
			/*
			var a = $("form#client-form").serializeArray();
			var b = [];
			var d = {};
			
			if( ! $.isEmptyObject( a ) ){
				$.each(a, function(k, v){
					switch( v.name ){
					case 'accessible_functions[]':
						b.push( v.value );
					break;
					default:
						var kk = v.name.replace('[]', '');
						if( ! d[ kk ] ){
							d[ kk ] = [];
						}
						d[ kk ].push( v.value );
					break;
					}
				});
			}
			
			d["functions"] = b.join(":::");
			*/
			
			$("form#access_role-form")
			.find('textarea[name="data"]')
			.val( JSON.stringify( nwAccess_roles.data ) );
			//console.log( $("form#client-form").serializeArray() );
			
		},
	};
	
}();
nwAccess_roles.init();