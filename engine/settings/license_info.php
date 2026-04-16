<?php
	//$_POST["mobile_persistent"]["license"] = 1;
	//$_POST["mobile_persistent"]["license"] = 'qwe';
	//$_POST["mobile_persistent"]["license"] = 'asd';
	//$_POST["mobile_persistent"]["license"] = '087050560a88dbc0885f4b3a51e061a6';
	//$_SESSION["license_key"] = 'qwe';
	
	$logged_out = 1;
	
	if( isset( $GLOBALS['provisioning'] ) ){
		$l = $GLOBALS['provisioning'];
	}else if( ( isset( $_POST["mobile_persistent"]["license"] ) && $_POST["mobile_persistent"]["license"] ) ){
		
		//if( isset( $_SESSION["license_key"] ) && $_SESSION["license_key"] ){
			//$lk = $_SESSION["license_key"];
			$lk = $_POST["mobile_persistent"]["license"];
			
			$l = get_license_info( array( "id" => $lk ) );
		//}
	}else if( isset( $_POST["license"] ) && $_POST["license"] ){
		$l = get_license_info( array( "id" => trim( str_replace( "-", "", $_POST["license"] ) ) ) );
	} 
	
	//print_r( $l );exit;
	if( isset( $l[ "database" ] ) && isset( $l[ "password" ] )  && isset( $l[ "host" ] )  && isset( $l[ "user" ] ) ){
		$database_name = $l[ "database" ];
		$database_user = $l[ "user" ];
		$database_user_password = $l[ "password" ];
		$database_host_ip_address = $l[ "host" ];
		
		$GLOBALS["HYELLA_PACKAGE"] = $l[ "package" ];
		$GLOBALS["HYELLA_SUB_PACKAGE"] = isset( $l[ "sub_package" ] ) ? $l[ "sub_package" ] : '';
		
		define( 'HYELLA_PACKAGE' , $l[ "package" ] );
		define( "HYELLA_SUB_PACKAGE", $GLOBALS["HYELLA_SUB_PACKAGE"] );
		
		if( isset( $l["constants"] ) && is_array( $l["constants"] ) && ! empty( $l["constants"] ) ){
			foreach( $l["constants"] as $ck => $cv ){
				if( ! is_array( $cv ) ){
					define( $ck, $cv );
				}
			}
		}
		
		if( isset( $l["plugins"] ) && is_array( $l["plugins"] ) && ! empty( $l["plugins"] ) ){
			$package_config["plugins_loaded"] = $l["plugins"];
		}
		
		if( isset( $l["classes"] ) && is_array( $l["classes"] ) && ! empty( $l["classes"] ) ){
			$nwp_package_class["ajax_request_processing_script"] = $l["classes"];
		}
		
		define( "HYELLA_LYTICS_CONNECTED", 1 );
		$logged_out = 0;
	}
	
	if( $logged_out ){
		define( "HYELLA_LYTICS_SERVER", 1 );
		$database_connection = 1;
	}
	
	function get_license_info( $settings = array() ){
		switch( $settings["id"] ){
		case "087050560a88dbc0885f4b3a51e061a6": //hyella basic - demo
			return array( 
				"id" => "demo",
				"package" => "hospital",
				"sub_package" => "gynae",
				"constants" => array(
					"HYELLA_CLIENT_NAME" => 'DEMO HOSPITAL',
					"HYELLA_MODULE_ACCOUNTING" => 1,
					"HYELLA_APP_ID" => $settings["id"],
				),
				
				"database" => "vine_pison2",
				"password" => "",
				"host" => "localhost",
				"user" => "root",
			);
		break;
		case "asd": //FRSC TEST - for people not using dedicated URL=
			return array( 
				"id" => "demo",
				"package" => "accounting",
				"sub_package" => "cooperative",
				"constants" => array(
					"HYELLA_SINGLE_STORE" => 1,
					"HYELLA_CLIENT_NAME" => 'FRSC COOPERATIVE SOCIETY',
					"HYELLA_MODULE_ACCOUNTING" => 1,
					"HYELLA_APP_ID" => $settings["id"],
				),
				
				"database" => "airport_lokongoma_contribution",
				"password" => "",
				"host" => "localhost",
				"user" => "root",
			);
		break;
		case "qwe": //test
			return array( 
				"id" => "demo",
				"package" => "catholic",
				"sub_package" => "",
				
				"database" => "catholic",
				"password" => "",
				"host" => "localhost",
				"user" => "root",
			);
		break;
		case "21015e10798b8f61fdfc0bcbb7ee4b5e": //mayor enterprise license demo
			return array( 
				"id" => "demo",
				"database" => "hyella_farm_manager_enterprise",
				"password" => "4@ex23ed;+YbpSQ^G!#<VC!9Px}Mb-d-pC2",
				"host" => "localhost",
				"user" => "demobeach",
			);
		break;
		case "7afd2ce762753ef4507d36fd749244c5": //mayor enterprise license basic
			return array( 
				"id" => "demo",
				"database" => "hyella_farm_manager_basic",
				"password" => "4@ex23ed;+YbpSQ^G!#<VC!9Px}Mb-d-pC2",
				"host" => "localhost",
				"user" => "demobeach",
				"project" => "hyella",
			);
		break;
		case "8b4fd2dffca1fa8208730872acb456a8": //mayor basic license - adebisi
			//8b4fd-2dffc-a1fa8-20873-0872a-cb456-a8
			return array( 
				"id" => "demo",
				"database" => "adebisi",
				//"database" => "hyella_farm_manager_basic",
				"password" => "YTQwfZj9DVz>(<^]UMab$^Eq}%FB]_yu",
				//"password" => "",
				"host" => "localhost",
				"user" => "gloriahinubijubi",
				//"user" => "root",
				"package" => "basic",
				"client_name" => "",
			);
		break;
		case "7234f422b820665a7fe1a4620e9d6d33": //royal events center			
			return array( 
				"id" => "demo",
				"database" => "royal_events_center",
				"password" => "u6NB%(v-#G*xQCxXZT}gG)-+wining-3789",
				"host" => "localhost",
				"user" => "gradiopowellhold",
				"clear_cache" => "http://www.royaleventscentre.com/engine/php/ajax_request_processing_script.php?action=audit&todo=clear_cache",
			);
		break;
		case "21015e10798b8f61fdfc0bcbb7ee4b5e": //mayor enterprise license demo
			return array( 
				"id" => "demo",
				"database" => "hyella_farm_manager_enterprise",
				"password" => "4@ex23ed;+YbpSQ^G!#<VC!9Px}Mb-d-pC2",
				"host" => "localhost",
				"user" => "demobeach",
			);
		break;
		case "7afd2ce762753ef4507d36fd749244c5": //mayor enterprise license basic
			return array( 
				"id" => "demo",
				"database" => "hyella_farm_manager_basic",
				"password" => "4@ex23ed;+YbpSQ^G!#<VC!9Px}Mb-d-pC2",
				"host" => "localhost",
				"user" => "demobeach",
			);
		break;
		case "ff9c1cbf823804bf85000a4be5ae1c96": //mayor enterprise license - adebisi
			return array( 
				"id" => "demo",
				"database" => "adebisi",
				"password" => "YTQwfZj9DVz>(<^]UMab$^Eq}%FB]_yu",
				"host" => "localhost",
				"user" => "gloriahinubijubi",
			);
		break;
		case "8ae288e34b4c08efc1e31317b821f068": //kwaala license - coci engineering
			return array( 
				"id" => "demo",
				"database" => "coci_engineering",
				"password" => "CzAE7yySXP@HHm3&UwFf4+HGr8?W",
				"host" => "localhost",
				"user" => "gloriacoci",
			);
		break;
		case "e52e3cc2e94b9b06407a385b080520a2": //kwaala license - palo dynamic ventures limited
			return array( 
				"id" => "demo",
				"database" => "palo_dynamic_ventures",
				"password" => "sdhKFMI8329-sds-KPPWdsd-+sn324m",
				"host" => "localhost",
				"user" => "glorypalo",
			);
		break;
		case "f66bc11c175ddea0d6eec8b254dde77e": //kwaala license - yonki water (hyella's dad)
			return array( 
				"id" => "demo",
				"database" => "yonki_water",
				"password" => "BG2cU7w*E@ng5Gh*hhS$-69Py@NTq_UH",
				"host" => "localhost",
				"user" => "gloriyonki",
			);
		break;
		case "2fe55f5f44044f4b02db59711ffc2a6e": //kwaala license - EXQUISITE LACES n VOILES (TY)
			return array( 
				"id" => "demo",
				"database" => "exquite_laces",
				"password" => "G!#<VC!9Px}Gh*hhS$-69Py<^]UMab$^OP-s9a",
				"host" => "localhost",
				"user" => "gogloriazie",
			);
		break;
		case "f97ccb6a5ea6eb6fea0c2be1bd58a9dd": //kwaala license - ARSENAL VENTURES LAGOS
			return array( 
				"id" => "demo",
				"database" => "arsenal_ventures",
				"password" => "xXZT}gG)-+hhS$-69Py<^]UMab$^jsa^7satGH9",
				"host" => "localhost",
				"user" => "gloautinriaba",
			);
		break;
		case "39dc5c2f0e38c0874a10ae818ca9b240": //kwaala license - TOPSMITH ABUJA
			return array( 
				"id" => "demo",
				"database" => "topsmith",
				"password" => "SQ^G!#<VC!9$^jsa^7satGH9E@ng5Gh*hhS$-6",
				"host" => "localhost",
				"user" => "glotopgoziana",
			);
		break;
		case "39f79a49ca703b26ce112c45152f6221": //kwaala license - YAMUSA SCIENCE ACADEMY
			return array( 
				"id" => "demo",
				"database" => "yamusa",
				"password" => "bpSK^G!9P2cU7w*E@ng5Gh*hh-69Py<^]UMab$",
				"host" => "localhost",
				"user" => "gyamustaita",
			);
		break;
		case "d95deec24bbe36773c3cd71f9d23f2fa": //kwaala license - IBANI GROUP
			return array( 
				"id" => "demo",
				"database" => "ibani_group",
				"password" => "S$-69Py<^]UMab$^OP-s9a4@ex23ed;+YbpS",
				"host" => "localhost",
				"user" => "senogloria",
			);
		break;
		case "50b1235492407c3a0c20ee7887ff81c7": //kwaala license - HARRIS AND DOME NIGERIA LIMITED
			return array( 
				"id" => "demo",
				"database" => "harris_and_dome_limited",
				"password" => "45Py<^]UMab$^jsa^7s@nGFSg5Gh*hhS$-69Py",
				"host" => "localhost",
				"user" => "gozgloriadome",
			);
		break;
		case "03dd013a7cb7433cfcf7287abb03ac4d": //kwaala license - MACBEKS
			return array( 
				"id" => "demo",
				"database" => "macbeks",
				"password" => "7s@nGFSg5G}Gh*hhS$-69Py<^]UMab$^oaa*(S",
				"host" => "localhost",
				"user" => "gloriamackeb",
			);
		break;
		case "0de92290b1d52f8cba65a010cf9e8d83": //kwaala license - ROCK OF AGES
			return array( 
				"id" => "demo",
				"database" => "rockofages",
				"password" => "a$-y<^]UMsatGH9E@ng5ab$^O69PP-s9a$^jsb7",
				"host" => "localhost",
				"user" => "glorockk4ia",
			);
		break;
		case "796720691c41f0af3c377ed21cdc61d6": //kwaala license - DUKE AND BAUER
			return array( 
				"id" => "demo",
				"database" => "duke_and_bauer",
				"password" => "C!9$^jsa^H9E7s@nGFS7g5Gh*hhSsatG9P2c",
				"host" => "localhost",
				"user" => "glodukeriab",
			);
		break;
		case "17dad6d6873f10a2000ae04f1176e3c8": //kwaala license - CATHOLIC LAGOS
			return array( 
				"id" => "demo",
				"database" => "catholic_lagos",
				"password" => "w*E@ng5Gh*hh-6}Gh*hhS$-69Pyg5Nh*hh-6}Bh2",
				"host" => "localhost",
				"user" => "glocatholagos",
			);
		break;
		case "530a259cf174498eb3dba1d7d2cc3ea9": //kwaala license - PIROTTI PROJECTS
			return array( 
				"id" => "demo",
				"database" => "pirotti",
				"password" => "E@ng5ab$^O6Gh*hh-6}Gh*h]UMab$^jsa^5",
				"host" => "localhost",
				"user" => "glopirotria",
			);
		break;
		case "5a08d5a670df90c94a33097b4ee25aa3": //kwaala license - NISA
			return array( 
				"id" => "demo",
				"database" => "nisa_wellness_retreat",
				"password" => "7+^4_YSJCYMuEM!M9UNNHtL_UDLJpyu%xr",
				"host" => "localhost",
				"user" => "glorinisaiart",
			);
		break;
		case "c014c478c750a06b1c4bc7e01f59591f": //kwaala license - bodyrox
			return array( 
				"id" => "demo",
				"database" => "bodyrox",
				"password" => "4cCq476B5eA2a3TC?RHA&$6rY?GLdVmx2N",
				"host" => "localhost",
				"user" => "globodyseno",
			);
		break;
		case "f7acf24c613c4cce60ed7d9b3664c226": //kwaala license - Vine Clinic and Fertility Center
			return array( 
				"id" => "demo",
				"database" => "vineclinic",
				"password" => "TQ7pECeT?CSwsV_Kq-$aX*cmn6q^!KRxrK",
				"host" => "localhost",
				"user" => "vinegloipin",
			);
		break;
		case "4bae21c6555c3c3c428cb7d4aa2055ea": //kwaala license - Zvecan Homes and Estates
			return array( 
				"id" => "demo",
				"database" => "zvecan",
				"password" => "4FKGV*^ECZbtHY?=^HfTSEH*2FY6CrT^6&",
				"host" => "localhost",
				"user" => "peteglomayo",
			);
		break;
		case "678451a8ee3050d547b3bf9fd755247f": //kwaala license - Sa'ad Faas Bureau De Change
			return array( 
				"id" => "demo",
				"database" => "saadfaas",
				"password" => "h%x!%fU&_TvQD@TY5H_#EYz+xXEYK4qWk^",
				"host" => "localhost",
				"user" => "glosalimcarlos",
			);
		break;
		case "021efbd4ffd7edf39354a16890404edc": //kwaala license - -Zara Bella
			return array( 
				"id" => "demo",
				"database" => "zarabella",
				"password" => "@nE@ng5ab$^GFS7g5Gh*hEM!M9UNNHtL",
				"host" => "localhost",
				"user" => "glozarberia",
			);
		break;
		case "ef613044c5fbb5e0192dae7ec8e9a45c": //kwaala license - Qhaftani
			return array( 
				"id" => "demo",
				"database" => "qhaftani",
				"password" => "@nGFSEM!M9UNNE@ng5ab$^HtL7g5Gh*h",
				"host" => "localhost",
				"user" => "glqhaftriani",
			);
		break;
		case "2aa0fc9f12ae4f78ddaffea11d4a6954": //kwaala license - GREENFINGERS LANDSCAPE SERVICES LIMITED
			return array( 
				"id" => "demo",
				"database" => "greenfingers",
				"password" => "EM!M9UNG6}Gh*h]NE@Gh*hh-h*h]ng5a",
				"host" => "localhost",
				"user" => "glogreenfinria",
			);
		break;
		case "af0aa7bb0285a06b3cdce7ab67d76810": //kwaala license - ARCAID
			return array( 
				"id" => "demo",
				"database" => "arcaid",
				"password" => 'YN_Kq-$aE@$^HtL?=^HfTng5abSEVX*cH',
				"host" => "localhost",
				"user" => "gloarcriacaid",
			);
		break;
		case "30cb4b115397ae92036ab3ba6834d860": //kwaala license - TRIPPLE ZERO
			return array( 
				"id" => "demo",
				//"database" => "tripple_zero",
				"database" => "pfarm",
				//"password" => 'sV_Kq-$aX*cmUD-$aX*LJpyn6q^!KR6q^!KxrK',
				"password" => '',
				"host" => "localhost",
				//"user" => "glotripplezerori",
				"user" => "root",
			);
		break;
		case "54b4b03127fc17791dcf6e3cf835cad0": //kwaala license - OBUMEK ALUMINIUM COMPANY
			return array( 
				"id" => "demo",
				"database" => "obumek",
				"password" => 'tHY?=^NE@ng_Kq-$a5ab$^HtLHfTSng5abEH',
				"host" => "localhost",
				"user" => "globumriamek",
			);
		break;
		}
	}
	
?>