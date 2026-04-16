<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>	
   <!-- BEGIN HEAD -->
   <meta charset="utf-8" />
   <title><?php $pr = get_project_data(); if( isset( $pr["project_title"] ) )echo $pr["project_title"]; ?></title>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta content="width=device-width, initial-scale=1.0" name="viewport" />
   <meta content="" name="description" />
   <meta content="" name="author" />
   <meta name="MobileOptimized" content="320">
	
	<link id="print-css" rel="stylesheet" href="<?php echo $pagepointer; ?>css/print.css" media="print" />
	
	<link rel="shortcut icon" href="<?php echo $pagepointer; ?>favicon.ico">
    <!--<link rel="stylesheet" href="<?php echo $pagepointer; ?>http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">-->
	
    <!-- BEGIN GLOBAL MANDATORY STYLES -->          
	<link href="<?php echo $pagepointer; ?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="<?php echo $pagepointer; ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo $pagepointer; ?>assets/plugins/uniform/css/uniform.default.css" rel="stylesheet">
	<!-- END GLOBAL MANDATORY STYLES -->
    
   <!-- BEGIN PAGE LEVEL PLUGIN STYLES --> 
   <link href="<?php echo $pagepointer; ?>assets/plugins/fuelux/css/tree-metronic.css" rel="stylesheet" type="text/css"/>
   <link rel="stylesheet" type="text/css" href="<?php echo $pagepointer; ?>assets/plugins/select2/select2_metro.css" />
   <link href="<?php echo $pagepointer; ?>assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css"/>
   <!-- END PAGE LEVEL PLUGIN STYLES -->
   
   <!-- BEGIN THEME STYLES --> 
   <link href="<?php echo $pagepointer; ?>assets/css/style-metronic.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $pagepointer; ?>assets/css/style.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $pagepointer; ?>assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $pagepointer; ?>assets/css/plugins.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $pagepointer; ?>assets/css/pages/tasks.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $pagepointer; ?>assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
   <link href="<?php echo $pagepointer; ?>assets/css/custom.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $pagepointer; ?>assets/css/pages/login.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $pagepointer; ?>css/form.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $pagepointer; ?>css/demo_table.css" rel="stylesheet" type="text/css"/>
   
   <link href="<?php echo $pagepointer; ?>css/ui-tree-style.min.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo $pagepointer; ?>js/handsontable.full.min.css" rel="stylesheet" type="text/css"/>
   <!-- END THEME STYLES -->
	
	<link href="<?php echo $pagepointer; ?>css/print.css" rel="stylesheet" type="text/css" id="print-css" media="print" />
	<link href="<?php echo $pagepointer; ?>css/print.css" rel="stylesheet" type="text/css" id="cssprintcss" media="print" />
	 <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="<?php echo $pagepointer; ?>bootstrap-2.3.2/docs/assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $pagepointer; ?>bootstrap-2.3.2/docs/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $pagepointer; ?>bootstrap-2.3.2/docs/assets/ico/apple-touch-icon-114-precomposed.png">
	  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $pagepointer; ?>bootstrap-2.3.2/docs/assets/ico/apple-touch-icon-72-precomposed.png">
					<link rel="apple-touch-icon-precomposed" href="<?php echo $pagepointer; ?>bootstrap-2.3.2/docs/assets/ico/apple-touch-icon-57-precomposed.png">
								   <link rel="shortcut icon" href="<?php echo $pagepointer; ?>bootstrap-2.3.2/docs/assets/ico/favicon.png">
	
	
	<style id="pagepointer"><?php echo $pagepointer; ?></style>