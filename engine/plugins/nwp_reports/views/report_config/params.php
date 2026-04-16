<?php 
$params = json_decode( stripslashes('{
  "constants": {
    "TITLE": "NDID4D M&E REPORTING MIS",
    "DESCRIPTION": "Maybeach Technologies Limited",
    "AUTHOR": "Maybeach Technologies Limited",
    "FAVICON": "assets/images/favicon.ico",
    "LOGO": "config/id4d-and-nimc.png",
    "LOGO": "config/nimc.png",
    "SIGNIN_LOGO": "config/nimc.png",
    "LOGO_WIDTH": "110px",
    "SHOW_SIGNIN_INFO": false,
    "SHOW_SIGNIN_INFO_BACKGROUND_STYLE": "#000",
    "SHOW_SIGNIN_INFO_OPACITY_STYLE": "0.7",
    "SHOW_SIGNIN_INFO_BG_IMAGE": "config/info-bg.jpg",
    "HYELLA_DEFAULT_LOCATION": "main.php",
    "HYELLA_DEFAULT_LOGIN": "index.php",
    "SHOW_SIGNIN_MESSAGESX": [
  		{"message":"Great! Clean code, clean design, easy for customization. Thanks very much!"},
  		{"message":"The theme is really great with an amazing customer support."}
  	],
    "BACKGROUND_STYLE": "linear-gradient(-45deg,#198754 20%,#5f89c9)",
    "SIGNUP_LINK": "",
    "SIGNIN_TITLE_STYLE": "text-align:center;",
    "SIGNIN_OPTIONS": false,
    "SIGNIN_REMEMBER_ME": false,
    "SIGNIN_USERNAME": "Email",
    "SIGNIN_USERNAME_TYPE": "email",
    "SIGNIN_TITLE": "M&E REPORTING MIS",
    "SIGNIN_SUBTITLE": "Monitoring and Evaluation Tool for the NIMS",
    "SIGNIN_RESET_PASSWORD_TITLE": "Reset Password",
    "SIGNUP_POWERED_TITLE": "powered by",
    "SIGNUP_POWERED_BY": [
  		{"logo":"config/id4d-colored.png", "width":"60px" },
  		{"logo":"config/min.png", "width":"60px"}
  	],
    "APP_VERSION_ID": "year-of-app",
    "ENDPOINTX": "/solutions/metadata/engine/",
    "ENDPOINT": "/ndid4d-dev/engine/",
    "ENDPOINT_CUSTOM": 0,
    "NWP_SORT_EDMS_FOLDERS_AND_FILES": 1,
    "HYELLA_NO_MORE_DATA": 1,
    "OTP_ON_SIGNIN_VALIDITY_CHECK_FINGERPRINT": 1
  }
}' ), true );

if( isset( $params["constants"] ) && ! empty( $params["constants"] ) ){
	foreach( $params["constants"] as $pk => $pv ){
		if( isset( $_SERVER[ $pk ] ) && $_SERVER[ $pk ] ){
			$params["constants"][ $pk ] = $_SERVER[ $pk ];
		}
	}
}

?>
