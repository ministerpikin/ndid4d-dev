<?php 
	
	//SELECT @i :=0;# Rows: 1
	//UPDATE state_list SET serial_num = ( SELECT @i := @i +1 ) ;# 3715 rows affected.
	if( defined("HYELLA_PACKAGE") && file_exists( dirname( __FILE__ ) ."/package/".HYELLA_PACKAGE."/Database_table_field_names_interpretation_functions.php" ) ){
		include dirname( __FILE__ ) . "/package/".HYELLA_PACKAGE."/Database_table_field_names_interpretation_functions.php";
	}
	
	function audit(){
		return array(
			'audit001' => array(
				
				'field_label' => 'Select Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),'id' => array(
				
				'field_label' => 'Select Database File (*.ela, *.hyella)',
				'form_field' => 'file',
				'required_field' => 'yes',
				'acceptable_files_format' => 'ela:::hyella',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'user_mail' => array(
				
				'field_label' => 'User',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'user_action' => array(
				
				'field_label' => 'Action',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'audit004' => array(
				
				'field_label' => 'Class',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'comment' => array(
				
				'field_label' => 'Comment',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'date' => array(
				
				'field_label' => 'Date',
				'form_field' => 'datetime',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function appsettings(){
		return array(
			'appsettings001' => array(
				
				'field_label' => 'Name of App',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'appsettings002' => array(
				
				'field_label' => 'App Logo',
				'form_field' => 'file',
				'acceptable_files_format' => 'jpg:::jpeg:::png:::bmp:::gif:::tiff',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'appsettings004' => array(
				
				'field_label' => 'Slogan',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'appsettings005' => array(
				
				'field_label' => 'Contact Address',
				'form_field' => 'textarea',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'appsettings006' => array(
				
				'field_label' => 'Contact Phone Number',
				'form_field' => 'textarea',
				
				'display_position' => 'display-in-table-row',
				
				'serial_number' => '',
			),
			'appsettings007' => array(
				
				'field_label' => 'Contact Email Address',
				'form_field' => 'textarea',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'appsettings008' => array(
				
				'field_label' => 'Support Line',
				'form_field' => 'tel',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function general_settings(){
		return array(
			'general_settings001' => array(
				
				'field_label' => 'Key',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings002' => array(
				
				'field_label' => 'Value',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings003' => array(
				
				'field_label' => 'Description',
				'form_field' => 'textarea',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings004' => array(
				
				'field_label' => 'Type',
				'form_field' => 'select',
				'form_field_options' => 'get_form_field_types',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				
				'serial_number' => '',
			),
			'general_settings005' => array(
				
				'field_label' => 'Class',
				'form_field' => 'select',
				'form_field_options' => 'get_class_names',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings006' => array(
				
				'field_label' => 'Country',
				'form_field' => 'select',
				'form_field_options' => 'get_countries_general_settings',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings007' => array(
				
				'field_label' => 'Start Date',
				'form_field' => 'date-5',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'general_settings008' => array(
				
				'field_label' => 'End Date',
				'form_field' => 'date-5',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function access_roles(){
		return array(
			'access_roles001' => array(
				
				'field_label' => 'Access Role',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
				'table' => 'access_roles'
			),
			'access_roles002' => array(
				
				'field_label' => 'Accessible Functions',
				'form_field' => 'multi-select',
				'required_field' => 'yes',
				
				'form_field_options' => 'get_accessible_functions',
				
				//'display_position' => 'more-details',
				'display_position' => 'display-in-table-row',
				
				'serial_number' => '',
				'table' => 'access_roles'
			),
			'access_roles004' => array(
				
				'field_label' => 'Role Type',
				'form_field' => 'select',
				'form_field_options' => 'get_access_role_types',
				
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
				'table' => 'access_roles'
			),
		);
	}
	
	function functions(){
		return array(
			'functions001' => array(
				
				'field_label' => 'Function Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
			'functions002' => array(
				
				'field_label' => 'Module Name',
				'form_field' => 'select',
				'form_field_options' => 'get_modules_in_application',
				
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
			'functions003' => array(
				
				'field_label' => 'Action',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
			'functions004' => array(
				
				'field_label' => 'Class Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				
				'serial_number' => '',
			),
			'functions005' => array(
				
				'field_label' => 'Tooltip',
				'form_field' => 'textarea',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'functions006' => array(
				
				'field_label' => 'Help Topic',
				'form_field' => 'textarea',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function myexcel(){
		return array(
			'myexcel005' => array(
				
				'field_label' => 'Excel File',
				'form_field' => 'file',
				'required_field' => 'no',
				
				'acceptable_files_format' => 'xls',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'myexcel006' => array(
				
				'field_label' => 'Import Table',
				'form_field' => 'text',
				'required_field' => 'no',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'myexcel007' => array(
				
				'field_label' => 'Mapping Options',
				'form_field' => 'select',
				'required_field' => 'no',
				
				'form_field_options' => 'get_import_file_field_mapping_options',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'myexcel008' => array(
				
				'field_label' => 'Import Options',
				'form_field' => 'select',
				'required_field' => 'no',
				
				'form_field_options' => 'get_file_import_options',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'myexcel009' => array(
				
				'field_label' => 'Equating Table Field for Update',
				'form_field' => 'select',
				'required_field' => 'no',
				
				'form_field_options' => 'get_import_table_fields',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	// function users(){
	// 	$return = array(
	// 		'users001' => array(
				
	// 			'field_label' => 'First Name',
	// 			'form_field' => 'text',
	// 			'required_field' => 'yes',
				
	// 			'default_appearance_in_table_fields' => 'show',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users002' => array(
				
	// 			'field_label' => 'Last Name',
	// 			'form_field' => 'text',
	// 			'required_field' => 'yes',
				
	// 			'default_appearance_in_table_fields' => 'show',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users003' => array(
				
	// 			'field_label' => 'Other Names',
	// 			'form_field' => 'text',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users004' => array(
				
	// 			'field_label' => 'Email Address',
	// 			'form_field' => 'email',
	// 			'required_field' => 'yes',
	// 			'placeholder' => 'Email Address',
				
 //                'icon' => '<i class="icon-user"></i>',
                
	// 			'default_appearance_in_table_fields' => 'show',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users005' => array(
				
	// 			'field_label' => 'Phone Number',
	// 			'form_field' => 'text',
	// 			'required_field' => 'yes',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users006' => array(
				
	// 			'field_label' => 'Password',
	// 			'form_field' => 'password',
	// 			'required_field' => 'yes',
	// 			'placeholder' => 'Password',
                
	// 			'icon' => '<i class="icon-lock"></i>',
                
	// 			'do_not_import' => 1,
	// 			'tooltip' => 'Minimum of 4 characters required',
				
	// 			'display_position' => 'do-not-display-in-table',
	// 			'serial_number' => '',
	// 		),
	// 		'users007' => array(
				
	// 			'field_label' => 'Confirm Password',
	// 			'form_field' => 'password',
	// 			'required_field' => 'yes',
				
	// 			'tooltip' => 'Re-type password',
				
	// 			'do_not_import' => 1,
	// 			'display_position' => 'do-not-display-in-table',
	// 			'serial_number' => '',
	// 		),
	// 		'users008' => array(
				
	// 			'field_label' => 'Current Password',
	// 			//'form_field' => 'password',
	// 			'form_field' => 'text',
	// 			'required_field' => 'yes',
				
	// 			'do_not_import' => 1,
	// 			'display_position' => 'do-not-display-in-table',
	// 			'serial_number' => '',
	// 		),
	// 		'users009' => array(
				
	// 			'field_label' => 'Access Role',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_access_roles',
				
	// 			'required_field' => 'yes',
				
	// 			'do_not_import' => 1,
	// 			'default_appearance_in_table_fields' => 'show',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users040' => array(
	// 			'field_label' => 'Staff Category',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_salary_category',
				
	// 			'do_not_import' => 1,
	// 			'display_position' => 'display-in-table-row',
	// 			'default_appearance_in_table_fields' => 'show',
	// 			'serial_number' => '',
	// 		),
	// 		'users046' => array(
	// 			'field_label' => 'Profession',
	// 			'form_field' => 'select',
	// 			'class' => ' select2 ',
	// 			'form_field_options' => 'get_professions_options_value',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users041' => array(
	// 			'field_label' => 'Division',
	// 			'form_field' => 'select',
	// 			'form_field_options_groupons' => 'get_divisions_options_value',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users010' => array(
	// 			'field_label' => 'Department',
				
	// 			//'form_field' => 'select',
	// 			'form_field' => 'calculated',
	// 			//'form_field_options' => 'get_departments',
	// 			//'required_field' => 'yes',
	// 			'class' => ' select2 ',
				
	// 			'attributes' => ' action="?action=departments&todo=get_select2" minlength="0" ',
	// 			'calculations' => array(
	// 				'type' => 'record-details',
	// 				'reference_table' => 'departments',
	// 				'reference_keys' => array( 'name' ),
	// 				'form_field' => 'text',
	// 				'variables' => array( array( 'users010' ) ),
	// 			),
	// 			'placeholder' => 'Department',
				
	// 			//'class' => ' col-lg-6 personal-info ',
				
	// 			'default_appearance_in_table_fields' => 'show',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users020' => array(
				
	// 			'field_label' => 'Grade Level',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_grade_levels',
				
	// 			'default_appearance_in_table_fields' => 'show',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users015' => array(
				
	// 			'field_label' => 'Staff Ref Num',
	// 			'form_field' => 'text',
				
	// 			'default_appearance_in_table_fields' => 'show',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users011' => array(
				
	// 			'field_label' => 'Date of Birth',
	// 			'form_field' => 'date-5',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users012' => array(
				
	// 			'field_label' => 'Sex',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_sex',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users013' => array(
				
	// 			'field_label' => 'Residential Address',
	// 			'form_field' => 'text',
	// 			//'form_field_options' => 'get_job_roles',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users042' => array(
	// 			'field_label' => 'Means of Identification',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_means_of_identification_options_value',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users043' => array(
	// 			'field_label' => 'Specify Other Means of Identification',
	// 			'form_field' => 'text',
				
	// 			'do_not_import' => 1,
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users044' => array(
	// 			'field_label' => 'Identification Number',
	// 			'form_field' => 'text',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users014' => array(
				
	// 			'field_label' => 'Date Employed',
	// 			'form_field' => 'date-5',
	// 			//'form_field_options' => 'get_branch_offices',
                
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users016' => array(
				
	// 			'field_label' => 'Bank Account Number',
	// 			'form_field' => 'text',
                
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users017' => array(
				
	// 			'field_label' => 'Bank Name',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_bank_names',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users018' => array(
				
	// 			'field_label' => 'Photograph',
	// 			'form_field' => 'file',
				
	// 			'do_not_import' => 1,
	// 			'acceptable_files_format' => 'jpg:::jpeg:::png:::bmp:::gif:::tiff',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users019' => array(
				
	// 			'field_label' => 'Push Notification ID',
	// 			'form_field' => 'textarea-unlimited',
				
	// 			'class' => ' personal-info ',
				
	// 			'do_not_import' => 1,
	// 			'acceptable_files_format' => 'jpg:::jpeg:::png:::bmp:::gif:::tiff',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
			
	// 		'users021' => array(
				
	// 			'field_label' => 'Pension Fund Administrator',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_pfa_options_value',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users022' => array(
				
	// 			'field_label' => 'Tax Identification Number',
	// 			'form_field' => 'text',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users023' => array(
				
	// 			'field_label' => 'Serial Number',
	// 			'form_field' => 'number',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users024' => array(
				
	// 			'field_label' => 'Nationality',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_countries',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users025' => array(
				
	// 			'field_label' => 'State',
	// 			'form_field' => 'text',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users026' => array(
				
	// 			'field_label' => 'Local Government Area',
	// 			'form_field' => 'text',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users027' => array(
				
	// 			//'field_label' => 'Current Position',
	// 			'field_label' => 'Designation',
	// 			'form_field' => 'text',
				
	// 			'do_not_import' => 1,
	// 			'display_position' => 'do-not-display-in-table',
	// 			'serial_number' => '',
	// 		),
	// 		'users045' => array(
	// 			//'field_label' => 'Current Position',
	// 			'field_label' => 'Designation',
	// 			'form_field' => 'text',
				
	// 			'do_not_import' => 1,
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users028' => array(
				
	// 			'field_label' => 'Date of Confirmation',
	// 			'form_field' => 'date-5',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users029' => array(
				
	// 			'field_label' => 'File Number',
	// 			'form_field' => 'text',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users030' => array(
				
	// 			'field_label' => 'Highest Qualification',
	// 			'form_field' => 'text',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users031' => array(
				
	// 			'field_label' => 'Status',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_active_inactive',
				
	// 			'do_not_import' => 1,
	// 			'default_appearance_in_table_fields' => 'show',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users032' => array(
				
	// 			'field_label' => 'Reason for Status Change',
	// 			'form_field' => 'text',
				
	// 			'do_not_import' => 1,
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users033' => array(
				
	// 			'field_label' => 'Pension PIN',
	// 			'form_field' => 'text',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users034' => array(
				
	// 			'field_label' => 'Health Insurance',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_health_insurance_options_value',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users035' => array(
				
	// 			'field_label' => 'Health Insurance PIN',
	// 			'form_field' => 'text',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users037' => array(
				
	// 			'field_label' => 'Tax Office Location',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_tax_office_options_value',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users038' => array(
				
	// 			'field_label' => 'Housing Scheme',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_housing_scheme_options_value',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'users039' => array(
				
	// 			'field_label' => 'Housing Scheme Ref. Number',
	// 			'form_field' => 'text',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 	);
		
	// 	switch( HYELLA_PACKAGE ){
	// 	case "catholic":
	// 		$return[ "users047" ][ 'calculations' ] = array(
	// 			'type' => 'record-details',
	// 			'reference_table' => 'parish',
	// 			'reference_keys' => array( 'name', 'address' ),
	// 			'form_field' => 'text',
	// 			'variables' => array( array( "users047" ) ),
	// 		);
	// 		$return[ "users047" ][ 'form_field' ] = 'calculated';
	// 		$return[ "users047" ][ 'attributes' ] = ' action="?action=parish&todo=get_select2"  ';
	// 		$return[ "users047" ][ 'class' ] = ' select2 ';
			
	// 		$return['users047']["field_label"] = 'Parish';
	// 		$return['users047']["required_field"] = 'yes';
	// 		$return['users047']["display_position"] = 'display-in-table-row';
	// 		$return['users047']["default_appearance_in_table_fields"] = 'show';
	// 	break;
	// 	}
		
	// 	$return['users020'][ 'class' ] = ' select2 ';
	// 	$return['users020'][ 'attributes' ] = ' action="?action=grade_level&todo=get_select2" minlength="0" ';
	// 	$return['users020'][ 'form_field' ] = 'calculated';
	// 	$return['users020'][ 'calculations' ] = array(
	// 		'type' => 'record-details',
	// 		'reference_table' => 'grade_level',
	// 		'reference_keys' => array( 'grade_level', 'step' ),
	// 		'form_field' => 'text',
	// 		'variables' => array( array( 'users020' ) ),
	// 	);
		
	// 	if( get_capture_user_passphrase_settings() ){
	// 		$return['users036'] = array(
				
	// 			'field_label' => 'Passphrase',
	// 			'form_field' => 'passphrase',
				
	// 			'do_not_import' => 1,
	// 			'display_position' => 'do-not-display-in-table',
	// 			'serial_number' => '',
	// 		);
	// 	}
		
	// 	if( get_show_human_resource_settings() ){
	// 		$return['users027'][ 'form_field' ] = 'calculated';
	// 		//$return['users027'][ 'class' ] = ' select2 ';
	// 		//$return['users027'][ 'attributes' ] = ' action="?action=users_current_work_history&todo=get_select2" ';
	// 		$return['users027'][ 'calculations' ] = array(
	// 			'type' => 'record-details',
	// 			'reference_table' => 'users_current_work_history',
	// 			'reference_keys' => array( 'position_held' ),
	// 			'form_field' => 'text',
	// 			'variables' => array( array( "users027" ) ),
	// 			//'creation_type' => 'text',
	// 		);
			
	// 		$return['users030'][ 'form_field' ] = 'calculated';
	// 		//$return['users030'][ 'class' ] = ' select2 ';
	// 		//$return['users030'][ 'attributes' ] = ' action="?action=users_educational_history&todo=get_select2" ';
	// 		$return['users030'][ 'calculations' ] = array(
	// 			'type' => 'record-details',
	// 			'reference_table' => 'users_educational_history',
	// 			'reference_keys' => array( 'degree' ),
	// 			'form_field' => 'text',
	// 			'variables' => array( array( "users030" ) ),
	// 			//'creation_type' => 'text',
	// 		);
	// 	}
		
	// 	return $return;
	// }
	
	function reports(){
		return array(
			'reports001' => array(
				
				'field_label' => 'File Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'reports002' => array(
				
				'field_label' => 'File URL',
				'form_field' => 'file',
				'required_field' => 'yes',
				
				//'acceptable_files_format' => 'xls',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'reports003' => array(
				
				'field_label' => 'Reference',
				'form_field' => 'select',
				'required_field' => 'no',
				
				'form_field_options' => 'get_import_file_field_mapping_options',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'reports004' => array(
				
				'field_label' => 'Source',
				'form_field' => 'text',
				'required_field' => 'no',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'reports005' => array(
				
				'field_label' => 'Keywords',
				'form_field' => 'text',
				'required_field' => 'no',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'reports006' => array(
				
				'field_label' => 'Description',
				'form_field' => 'textarea',
				'required_field' => 'no',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function division(){
		return array(
			'division001' => array(
				//dept name
				'field_label' => 'Division Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'division002' => array(
				//desc
				'field_label' => 'Description',
				'form_field' => 'text',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'division003' => array(
				//head
				'field_label' => 'Head of Division',
				'form_field' => 'select',
				'form_field_options' => 'get_website_pages_width',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'division004' => array(
				//Assistant
				'field_label' => 'Assistant Head of Division',
				'form_field' => 'select',
                'form_field_options' => 'get_website_pages_width',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'division005' => array(
				//secretary
				'field_label' => 'Secretary',
				'form_field' => 'select',
                'form_field_options' => 'get_website_pages_width',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function departments(){
		$return = array(
			'departments001' => array(
				//dept name
				'field_label' => 'Department Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'departments002' => array(
				//desc
				'field_label' => 'Description',
				'form_field' => 'textarea',
                
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'departments003' => array(
				//head
				'field_label' => 'Head of Department',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'record-details',
					'reference_table' => 'users_employee_profile',
					'reference_keys' => array( 'firstname', 'lastname' ),
					'form_field' => 'text',
					'variables' => array( array( 'departments003' ) ),
				),
				
				'attributes' => ' action="?action=users_employee_profile&todo=get_select2" ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'class' => ' select2 ',
			),/*
			'departments004' => array(
				//Assistant
				'field_label' => 'Assistant Head of Department',
				'form_field' => 'select',
                'form_field_options' => 'get_website_pages_width',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'departments005' => array(
				//secretary
				'field_label' => 'Secretary',
				'form_field' => 'select',
                'form_field_options' => 'get_website_pages_width',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),*/
			'departments006' => array(
				'field_label' => 'Store',
				
				//'form_field' => 'select',
				//'form_field_options' => 'get_stores',
				'form_field' => 'calculated',
				// 'form_field' => 'multi-select',
				'calculations' => array(
					'type' => 'record-details',
					'reference_table' => 'stores',
					'reference_keys' => array( 'name' ),
					'multiple' => 1,
					'show_in_form' => 1,
					'form_field' => 'text',
					'variables' => array( array( 'departments006' ) ),
				),
				'attributes' => ' tags="true" action="?action=stores&todo=get_select2" minlength="0" ',
				'class' => ' select2 ',
				
				'default_appearance_in_table_fields' => 'hide',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),/*
			'departments007' => array(
				'field_label' => 'Pay Roll Option',
				
				'form_field' => 'multi-select',
				'class' => 'select2',
				
				'form_field_options' => 'get_pay_roll_options',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),*/
		);
		
		switch( get_package_option() ){
		case "catholic":
			$return["departments006"]["field_label"] = 'Parish';
			$return["departments006"]["form_field"] = 'text';
			
			$return["departments006"][ 'calculations' ] = array(
				'type' => 'record-details',
				'reference_table' => 'parish',
				'reference_keys' => array( 'name', 'address' ),
				'form_field' => 'text',
				'variables' => array( array( "departments006" ) ),
			);
			$return["departments006"][ 'form_field' ] = 'calculated';
			$return["departments006"][ 'attributes' ] = ' action="?action=parish&todo=get_select2"  ';
			$return["departments006"][ 'class' ] = ' select2 ';
		break;
		}
		
		return $return;
	}
	
	function units(){
		return array(
			'units001' => array(
				//dept name
				'field_label' => 'Unit Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'units002' => array(
				//desc
				'field_label' => 'Description',
				'form_field' => 'text',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'units003' => array(
				//head
				'field_label' => 'Head of Unit',
				'form_field' => 'select',
				'form_field_options' => 'get_website_pages_width',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'units004' => array(
				//Assistant
				'field_label' => 'Assistant Head of Unit',
				'form_field' => 'select',
                'form_field_options' => 'get_website_pages_width',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'units005' => array(
				//secretary
				'field_label' => 'Secretary',
				'form_field' => 'select',
                'form_field_options' => 'get_website_pages_width',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function job_roles(){
		return array(
			'job_roles001' => array(
				//Job Title
				'field_label' => 'Job Title',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'job_roles002' => array(
				//desc
				'field_label' => 'Description',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function branch_offices(){
		return array(
			'branch_offices001' => array(
				//Branch Name
				'field_label' => 'Branch Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'branch_offices002' => array(
				//address
				'field_label' => 'Street Address',
				'form_field' => 'text',
				
                'default_appearance_in_table_fields' => 'show',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'branch_offices003' => array(
				//city
				'field_label' => 'City',
				'form_field' => 'text',
				'required_field' => 'yes',
                
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'branch_offices004' => array(
				//state
				'field_label' => 'State',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'branch_offices005' => array(
				//country
				'field_label' => 'Country',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function resource_library(){
		return array(
			'resource_library001' => array(
				//pages
				'field_label' => RESOURCE_LIBRARY001,
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'resource_library002' => array(
				//title
				'field_label' => RESOURCE_LIBRARY002,
				'form_field' => 'file',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'resource_library003' => array(
				//content type
				'field_label' => RESOURCE_LIBRARY003,
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}

	function cities_list(){
		return array(
			
			'cities_list001' => array(
				
				'field_label' => "Country",
				'form_field' => 'select',
				'required_field' => 'yes',
				'form_field_options' => 'get_countries',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
            'cities_list002' => array(
				
				'field_label' => "State",
				'form_field' => 'calculated',
				'required_field' => 'yes',
				
				'calculations' => array(
					'type' => 'state-id',
					'form_field' => 'text',
					'variables' => array( array( 'cities_list001', 'cities_list002' ) ),
				),
                
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'cities_list003' => array(
				
				'field_label' => "City",
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),/*
			'cities_list004' => array(
				
				'field_label' => CITIES_LIST004,
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),*/
		);
	}
	
	function expenditure(){
	
		$return = array(
			'expenditure001' => array(
				'field_label' => 'Date',
				'form_field' => 'calculated',
				//'form_field' => 'date-5',
				'required_field' => 'yes',
				
				'calculations' => array(
					'type' => 'expenditure-receipt-num',
					'form_field' => 'date-5',
					'variables' => array( array( 'id' ) ),
				),
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'expenditure002' => array(
				'field_label' => 'Vendor / Source',
				
				//'form_field' => 'select',
				//"form_field_options" => "get_vendors",
				//"form_field_options" => "get_vendors_supplier",
				//'required_field' => 'yes',
				
				'calculations' => array(
					'type' => 'record-details',
					'reference_table' => 'vendors',
					'reference_keys' => array( 'name_of_vendor', 'serial_num' ),
					'form_field' => 'text',
					'variables' => array( array( 'expenditure002' ) ),
				),
				'form_field' => 'calculated',
				'attributes' => ' action="?action=vendors&todo=get_vendors_select2" minlength="0" ',
				'class' => ' select2 allow-clear ',

				'placeholder' => '',

				'default_appearance_in_table_fields' => 'show',

				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure024' => array(
				'field_label' => 'Category',
				'form_field' => 'calculated',
				'calculations' => array(
					'reference_table' => 'banks',
					'reference_keys' => array( 'name' ),
					'type' => 'record-details',
					'form_field' => 'text',
					'variables' => array( array( 'expenditure024' ) ),
				),
				
				'attributes' => ' action="?action=banks&todo=get_select2&type=purchase_category&hide_type=1" minlength="0" ',
				'class' => ' select2 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure003' => array(
				'field_label' => 'Description',
				
				'form_field' => 'textarea',
				'required_field' => 'yes',
				
				'placeholder' => '',
				//'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure009' => array(
				'field_label' => 'Quantity (optional)',
				
				'form_field' => 'decimal',
				'placeholder' => 'Quantity Supplied',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure004' => array(
				'field_label' => 'Amount Due',
				
				'form_field' => 'decimal',
				//'required_field' => 'yes',
				
				'class' => ' col-lg-6 no-x-padding-1 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure005' => array(
				'field_label' => 'Amount Paid',
				
				'form_field' => 'decimal',
				//'required_field' => 'yes',
				
				'class' => ' col-lg-6 no-x-padding-1 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure006' => array(
				'field_label' => 'Balance',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'difference',
					'form_field' => 'decimal',
					'variables' => array( array( 'expenditure004' ), array( 'expenditure005' ) ),
				),
				
				//'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'hide',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure010' => array(
				'field_label' => 'Mode of Payment',
				
				'form_field' => 'select',
				"form_field_options" => "get_payment_method",
				
				'class' => ' col-lg-6 no-x-padding-1 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure011' => array(
				'field_label' => 'Receipt No.',
				
				'form_field' => 'text',
				
				'class' => ' col-lg-6 no-x-padding-1 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure007' => array(
				'field_label' => 'Expense Category',
				
				'form_field' => 'select',
				//'required_field' => 'yes',
				"form_field_options" => "get_types_of_expenditure",
				
				'class' => ' col-lg-6 no-x-padding-1 ',
				
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'expenditure008' => array(
				'field_label' => 'Staff In Charge',
				
				'form_field' => 'select',
				'form_field_options' => 'get_employees',
				
				'placeholder' => 'Staff in Charge',
				'class' => ' col-lg-6 no-x-padding-1 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure012' => array(
				'field_label' => 'REF',
				'form_field' => 'text',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'expenditure013' => array(
				'field_label' => 'Store',
				//'form_field' => 'select',
				//'form_field_options' => 'get_stores',
				
				'calculations' => array(
					'type' => 'record-details',
					'reference_table' => 'stores',
					'reference_keys' => array( 'name' ),
					'form_field' => 'text',
					'variables' => array( array( 'expenditure013' ) ),
				),
				'form_field' => 'calculated',
				'attributes' => ' action="?action=stores&todo=get_active_store" minlength="0" ',
				'class' => ' select2 allow-clear ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure014' => array(
				'field_label' => 'Status',
				'form_field' => 'select',
				'form_field_options' => 'get_expenditure_status',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			/* 'expenditure024' => array(
				'field_label' => 'Reference',
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			), */
			'expenditure015' => array(
				'field_label' => 'Reference Table',
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure016' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure017' => array(
				'field_label' => '% Discount',
				'form_field' => 'decimal',
				
				'class' => ' col-md-6 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure018' => array(
				'field_label' => 'Tax',
				'form_field' => 'decimal',
				
				'class' => ' col-md-6 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure019' => array(
				'field_label' => 'Data',
				'form_field' => 'textarea-unlimited',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'expenditure020' => array(
				'field_label' => 'Vendor Reference',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			
		);
		
		if( get_use_imported_goods_settings() ){
			$return['expenditure021'] = array(
				'field_label' => 'Date of Departure',
				'form_field' => 'date-5',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			);
			
			$return['expenditure022'] = array(
				'field_label' => 'Date of Arrival',
				'form_field' => 'date-5',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			);
			
			$return['expenditure023'] = array(
				'field_label' => 'Date Cleared to Stock',
				'form_field' => 'date-5',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			);
		}
		
		$capture_payment = get_capture_payment_on_purchase_order_settings();
		if( ! $capture_payment ){
			unset( $return['expenditure005'] );
			unset( $return['expenditure006'] );
			unset( $return['expenditure010'] );
		}
		
		if( function_exists("get_compulsory_date_cleared_to_stock_field_purchase_order_settings") ){
			if( get_compulsory_date_cleared_to_stock_field_purchase_order_settings() ){
				$return['expenditure023']['required_field'] = 'yes';
			}
		}
		
		unset( $return['expenditure006'] );
		unset( $return['expenditure005'] );
		
		return $return;
	}
	
	
	function production(){
		return array(
			'production001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'production002' => array(
				'field_label' => 'Quantity',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production003' => array(
				'field_label' => 'Total Cost',
				
				'form_field' => 'currency',
				'required_field' => 'yes',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production004' => array(
				'field_label' => 'Extra Cost',
				'form_field' => 'decimal',
				
				//'class' => ' col-lg-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production005' => array(
				'field_label' => 'Destination Store',
				
				'form_field' => 'select',
				'form_field_options' => 'get_factories',
				//'class' => ' col-lg-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production006' => array(
				'field_label' => 'Source Store',
				
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				
				//'class' => ' col-lg-6 ',
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production007' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				
				//'class' => ' col-lg-6 personal-info ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production008' => array(
				'field_label' => 'Status',
				
				'form_field' => 'select',
				'form_field_options' => 'get_stock_status',
				
				//'class' => ' col-lg-6 personal-info ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production009' => array(
				'field_label' => 'Staff Responsible',
				'form_field' => 'select',
				'form_field_options' => 'get_employees',
				
				//'class' => ' no-x-padding-1 col-lg-6 ',
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'production010' => array(
				'field_label' => 'Reference',
				'form_field' => 'text',
				
				//'class' => ' no-x-padding-1 col-lg-6 ',
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'production011' => array(
				'field_label' => 'Reference Table',
				'form_field' => 'text',
				
				//'class' => ' no-x-padding-1 col-lg-6 ',
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'production012' => array(
				'field_label' => 'Customer',
				'form_field' => 'select',
				'form_field_options' => 'get_customers',
				
				//'class' => ' no-x-padding-1 col-lg-6 ',
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'production013' => array(
				'field_label' => 'Data',
				'form_field' => 'textarea-unlimited',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
		);
	}
	
	function production_items(){
		$return = array(
			'production_items001' => array(
				'field_label' => 'Reference',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'production-ref-num',
					'form_field' => 'text',
					'variables' => array( array( 'production_items001' ) ),
				),
				
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'production_items002' => array(
				'field_label' => 'Item',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'item-details',
					'form_field' => 'text',
					'variables' => array( array( 'production_items002' ) ),
				),
				'required_field' => 'yes',
				
				'attributes' => ' action="?action=items&todo=get_items_select2&type=" ',
				//'form_field' => 'select',
				//'form_field_options' => 'get_items_raw_materials',
				
				'placeholder' => '',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items003' => array(
				'field_label' => 'Cost',
				
				'form_field' => 'currency',
				'class' => ' col-lg-6 ',
				'required_field' => 'yes',
				
				'placeholder' => 'Unit Cost',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items004' => array(
				'field_label' => 'Quantity',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items005' => array(
				'field_label' => 'Extra Cost',
				'form_field' => 'currency',
				
				//'class' => ' col-lg-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items006' => array(
				'field_label' => 'Product Type',
				'form_field' => 'select',
				'form_field_options' => 'get_product_types',
				
				//'class' => ' col-lg-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items007' => array(
				'field_label' => 'Amount Due',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'production-items-amount-due',
					'form_field' => 'currency',
					'variables' => array( array( 'production_items003' ), array( 'production_items004' ) ),
					'extra_cost' => array( array( 'production_items005' ) ),
				),
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items008' => array(
				'field_label' => 'Currency',
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items009' => array(
				'field_label' => 'Opening Stock',
				'form_field' => 'decimal',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items014' => array(
				'field_label' => 'Batch ID',
				
				'form_field' => 'text',
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items015' => array(
				'field_label' => 'Expiry Date',
				
				'form_field' => 'date-5',
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
		
		if( isset( $_SESSION["production_items"][ "template" ] ) ){
			switch( $_SESSION["production_items"][ "template" ] ){
			case "picking_history":
				return production_items__picking_history();
			break;
			}
			unset( $_SESSION["production_items"][ "template" ] );
		}
		
		return $return;
	}
	
	function production_items__picking_history(){
		$return = array(
			'production_items001' => array(
				'field_label' => 'Reference',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'production-ref-num',
					'form_field' => 'text',
					'variables' => array( array( 'production_items001' ) ),
				),
				
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'production_items002' => array(
				'field_label' => 'Item',
				
				'form_field' => 'select',
				'form_field_options' => 'get_items',
				
				'placeholder' => '',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items003' => array(
				'field_label' => 'Opening Stock',
				
				'form_field' => 'calculated',
				'calculations' => array(
					'type' => 'has_value',
					'form_field' => 'decimal',
					'variables' => array( array( 'production_items009' ) ),
				),
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items004' => array(
				'field_label' => 'Quantity Picked',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'production_items005' => array(
				'field_label' => 'Closing Stock',
				
				'form_field' => 'calculated',
				'calculations' => array(
					'type' => 'difference',
					'form_field' => 'decimal',
					'variables' => array( array( 'production_items009' ), array( 'production_items004' ) ),
				),
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
		return $return;
	}
	
	function sales(){
		$package = '';
		if( defined("HYELLA_PACKAGE") ){
			$package = str_replace( "-", "_", HYELLA_PACKAGE );
		}
		if( function_exists( $package."_sales" ) ){
			$f = $package."_sales";
			return $f();
		}
		
		$return = array(
			'sales001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5time',
				'required_field' => 'yes',
				/*
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'sales-receipt-num',
					'form_field' => 'text',
					'variables' => array( array( 'id' ) ),
				),
				*/
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'sales002' => array(
				//'field_label' => 'Quantity',
				'field_label' => 'REF',
				
				//'form_field' => 'decimal',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'sales-receipt-num',
					'form_field' => 'text',
					'variables' => array( array( 'id' ) ),
				),
				'required_field' => 'yes',
				'class' => ' col-md-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales003' => array(
				'field_label' => 'Items Cost',
				
				'form_field' => 'currency',
				'required_field' => 'yes',
				'class' => ' col-md-6 ',
				
				'default_appearance_in_table_fields' => 'hide',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales004' => array(
				'field_label' => 'Discount',
				'form_field' => 'decimal',
				
				//'class' => ' col-md-6 ',
				'default_appearance_in_table_fields' => 'hide',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales005' => array(
				'field_label' => 'Discount Type',
				
				'form_field' => 'select',
				'form_field_options' => 'get_discount_types',
				'placeholder' => '',
				
				//'class' => ' col-md-6 ',
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales006' => array(
				'field_label' => 'Amount Due',
				'form_field' => 'currency',
				/*
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'sales-items-amount-due',
					'form_field' => 'currency',
					'variables' => array( array( 'sales003' ) ),
					'discount' => array( array( 'sales004' ) ),
				),
				*/
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales007' => array(
				'field_label' => 'Amount Paid',
				
				'form_field' => 'currency',
				'placeholder' => '',
				
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),/*
			'sales011' => array(
				'field_label' => 'Outstanding',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'sales-items-amount-due',
					'form_field' => 'currency',
					'variables' => array( array( 'sales003' ) ),
					'discount' => array( array( 'sales004' ), array( 'sales007' ) ),
				),
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),*/
			'sales008' => array(
				'field_label' => 'Payment Method',
				
				'form_field' => 'select',
				'form_field_options' => 'get_payment_method',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales009' => array(
				'field_label' => 'Customer',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'customers',
					'form_field' => 'text',
					'variables' => array( array( 'sales009' ) ),
				),
				
				'attributes' => ' action="?action=customers&todo=get_customers_select2" ',
				'class' => ' no-x-padding-1 select2 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales010' => array(
				'field_label' => 'Store',
				
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				
				//'class' => ' col-md-6 ',
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales013' => array(
				'field_label' => 'Status',
				
				'form_field' => 'select',
				'form_field_options' => 'get_sales_status',
				
				//'class' => ' col-md-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales012' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'placeholder' => '',
				
				//'class' => ' col-md-6 personal-info ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales014' => array(
				'field_label' => 'Staff Responsible',
				
				//'form_field' => 'select',
				//'form_field_options' => 'get_employees',
				
				'form_field' => 'calculated',
				'calculations' => array(
					'type' => 'users',
					'form_field' => 'text',
					'variables' => array( array( 'sales014' ) ),
				),
				'attributes' => ' action="?action=users&todo=get_users_select2" ',
	
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'sales015' => array(
				'field_label' => 'VAT',
				'form_field' => 'decimal',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales016' => array(
				'field_label' => 'Service Charge',
				'form_field' => 'decimal',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales017' => array(
				'field_label' => 'Service Tax',
				'form_field' => 'decimal',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales019' => array(
				'field_label' => 'Surcharge',
				'form_field' => 'decimal',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales7761' => array(
	            "table_name" => "sales",
	            "serial_number" => "175",
	            "field_label" => "Loyalty Point",
	            "display_field_label" => "",
	            "form_field" => "decimal",
	            "form_field_options" => "",
	            "required_field" => "no",
	            "database_objects" => "",
	            "attributes" => "",
	            "class" => "",
	            "default_appearance_in_table_fields" => "hide",
	            "display_position" => "display-in-table-row",
	            "acceptable_files_format" => "",
	            "field_id" => "sales7761",
	            "group" => "",
	            "text" => "Loyalty Point",
	            "field_key" => "sales7761",
	            "table" => "sales",
	            "field_identifier" => "loyalty_point"
			),
			'sales018' => array(
				'field_label' => 'REF',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales020' => array(
				'field_label' => 'Billing Cycle',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales021' => array(
				'field_label' => 'Term 2',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales022' => array(
				'field_label' => 'Data',
				
				'form_field' => 'textarea-unlimited',
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
		);
		
		if( get_use_group_billing_for_sales_settings() ){
			$return['sales023'] = array(
				'field_label' => 'Group',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'customers',
					'form_field' => 'text',
					'variables' => array( array( 'sales023' ) ),
				),
				
				'attributes' => ' action="?action=customers&todo=get_customers_select2" ',
				'class' => ' no-x-padding-1 select2 ',
				'display_position' => 'display-in-table-row',
			);
		}
		
		return $return;
	}
	
	function expenditure_payment(){
		return array(
			'expenditure_payment001' => array(
				'field_label' => 'Ref',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'sales-receipt-num',
					'form_field' => 'text',
					'variables' => array( array( 'expenditure_payment001' ) ),
				),
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'expenditure_payment002' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment003' => array(
				'field_label' => 'Amount Paid',
				
				'form_field' => 'currency',
				'class' => ' col-md-6 ',
				'required_field' => 'yes',
				
				'placeholder' => 'Amount Recd',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment004' => array(
				'field_label' => 'Payment Method',
				
				'form_field' => 'select',
				'form_field_options' => 'get_payment_method',
				'form_field_options_group' => 'get_payment_method_grouped',
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment005' => array(
				'field_label' => 'Staff Responsible',
				'form_field' => 'select',
				'form_field_options' => 'get_employees',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment006' => array(
				'field_label' => 'Comment',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment007' => array(
				'field_label' => 'Reference Table',
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment008' => array(
				'field_label' => 'Vendor',
				'form_field' => 'select',
				'form_field_options' => 'get_vendors',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment009' => array(
				'field_label' => 'Store',
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment010' => array(
				'field_label' => 'REF',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'expenditure_payment011' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function sales_items(){
		if( function_exists( HYELLA_PACKAGE."_sales_items" ) ){
			$f = HYELLA_PACKAGE."_sales_items";
			return $f();
		}
		
		$r = array(
			'sales_items001' => array(
				'field_label' => 'Receipt Num',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'sales-receipt-num',
					'form_field' => 'text',
					'variables' => array( array( 'sales_items001' ) ),
				),
				
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'sales_items002' => array(
				'field_label' => 'Item',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'item-details',
					'form_field' => 'text',
					'variables' => array( array( 'sales_items002' ) ),
				),
				'required_field' => 'yes',
				
				'attributes' => ' action="?action=items&todo=get_items_select2" ',
				//'form_field' => 'select',
				//'form_field_options' => 'get_items',
				//'form_field_options_group' => 'get_items_grouped',
				
				'placeholder' => '',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items003' => array(
				'field_label' => 'Cost',
				
				'form_field' => 'currency',
				'class' => ' col-md-6 ',
				'required_field' => 'yes',
				
				'placeholder' => 'Unit Cost',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items004' => array(
				'field_label' => 'Quantity',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				'class' => ' col-md-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items005' => array(
				'field_label' => 'Discount',
				'form_field' => 'decimal',
				
				//'class' => ' col-md-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),/*
			'sales_items006' => array(
				'field_label' => 'Discount Type',
				
				'form_field' => 'select',
				'form_field_options' => 'get_discount_types',
				'placeholder' => '',
				
				'class' => ' col-md-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),*/
			'sales_items007' => array(
				'field_label' => 'Amount Due',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'sales-items-amount-due',
					'form_field' => 'currency',
					'variables' => array( array( 'sales_items003' ), array( 'sales_items004' ) ),
					'discount' => array( array( 'sales_items005' ) ),
				),
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items008' => array(
				'field_label' => 'Quantity Returned',
				
				'form_field' => 'number',
				'placeholder' => 'Qty. Returned',
				
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items009' => array(
				'field_label' => 'Cost Price',
				
				'form_field' => 'decimal',
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items010' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items012' => array(
				'field_label' => 'Exchange Rate',
				
				'form_field' => 'decimal',
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items014' => array(
				'field_label' => 'Batch ID',
				
				'form_field' => 'text',
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items015' => array(
				'field_label' => 'Expiry Date',
				
				'form_field' => 'date-5',
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items016' => array(
				'field_label' => 'Package Reference',
				
				'form_field' => 'text',
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'sales_items017' => array(
				'field_label' => 'HMO',
				
				'form_field' => 'text',
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
		
		return $r;
	}
	
	function category(){
		if( function_exists( HYELLA_PACKAGE."_category" ) ){
			$f = HYELLA_PACKAGE."_category";
			return $f();
		}
		
		return array(
			'category001' => array(
				'field_label' => 'Name of Category',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'category002' => array(
				'field_label' => 'Type of Category',
				
				'form_field' => 'select',
				'form_field_options' => 'get_product_types',
				//'class' => ' col-md-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function stores(){
		if( function_exists( HYELLA_PACKAGE."_stores" ) ){
			$f = HYELLA_PACKAGE."_stores";
			return $f();
		}
		
		return array(
			'stores001' => array(
				'field_label' => 'Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'stores002' => array(
				'field_label' => 'Location',
				
				'form_field' => 'text',
				'placeholder' => '',
				//'class' => ' col-md-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stores003' => array(
				'field_label' => 'Phone',
				
				'form_field' => 'text',
				'placeholder' => 'Phone Number',
				//'class' => ' col-md-6 personal-info ',
				
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stores004' => array(
				'field_label' => 'Email',
				'form_field' => 'text',
				//'class' => ' col-md-6 personal-info ',
				
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stores005' => array(
				'field_label' => 'Store Options',
				
				'form_field' => 'single_json_data',
				'form_field_options' => 'store_options',
				
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'stores006' => array(
				'field_label' => 'Geo Code',
				
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stores007' => array(
				'field_label' => 'Type',
				
				'form_field' => 'select',
				'form_field_options' => 'get_stores_type',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' selected-type ',
			),
			'stores008' => array(
				'field_label' => 'Parent',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'record-details',
					'form_field' => 'text',		
					'reference_table' => 'stores',
					'reference_keys' => array( 'name' ),
					'variables' => array( array( 'stores008' ) ),
				),
				'attributes' => ' data-params=".selected-type" action="?action=stores&todo=get_select2" minlength="0" ',
				
				'class' => ' select2 allow-clear ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stores009' => array(
				'field_label' => 'Section',
				
				'form_field' => 'select',
				'form_field_options' => 'frontend_tabs_select',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'stores010' => array(
				'field_label' => 'Department',
				
				'form_field' => 'calculated',
				'serial_num' => 1,
							
				'attributes' => ' action="?action=departments&todo=get_select2" minlength="0" ',
				'calculations' => array(
					'type' => 'record-details',
					'reference_table' => 'departments',
					'reference_keys' => array( 'name' ),
					'form_field' => 'text',
					'variables' => array( array( 'stores010' ) ),
				),
				
				'class' => ' select2 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
		);
	}
	
	function auto_discount(){
		$return = array(
			'discount001' => array(
				'field_label' => 'Description',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'discount002' => array(
				'field_label' => 'Type',
				'form_field' => 'select',
				'form_field_options' => 'get_discount_types',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'discount003' => array(
				'field_label' => 'Discount Value',
				
				'form_field' => 'decimal',
				//'required_field' => 'yes',
				
				'placeholder' => '',
				//'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'discount004' => array(
				'field_label' => 'Minimum Sale Value',
				
				'form_field' => 'decimal',
				
				'tooltip' => 'Enter the minimum sale value prior to discount being automatically applied',
				'placeholder' => '',
				//'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'discount005' => array(
				'field_label' => 'Item Discount (Fixed Price)',
				
				'form_field' => 'decimal',
				
				'tooltip' => 'Enter the discount value for item per item basis.<br />Enter Fixed Price Only e.g N50.00 per unit of item',
				'placeholder' => '',
				//'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'discount006' => array(
				'field_label' => 'Minimum Order Quantity',
				
				'form_field' => 'decimal',
				
				'tooltip' => 'Enter the minimum order quantity for line discount',
				'placeholder' => '',
				//'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'discount007' => array(
				'field_label' => 'Item',
				
				'form_field' => 'select',
				'form_field_options' => 'get_items_for_sale',
				
				'tooltip' => 'Enter applicable item for line discount',
				'placeholder' => '',
				'class' => ' select2 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'discount008' => array(
				'field_label' => 'Customer',
				
				'form_field' => 'select',
				'form_field_options' => 'get_customers_with_all',
				
				'tooltip' => 'Enter the customer that benefits from discount',
				'placeholder' => '',
				//'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'discount010' => array(
				'field_label' => 'No of Items Given as Discount',
				
				'form_field' => 'number',
				
				'tooltip' => 'Enter the quantity of items given free as discount',
				'placeholder' => '',
				//'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
		
		if( get_categorized_customers_settings() ){
			$return["discount009"] = array(
				'field_label' => 'Customer Category',
				
				'form_field' => 'select',
				'form_field_options' => 'get_customer_category',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			);
		}
		
		return $return;
	}
	
	function discount(){
		if( function_exists("get_discount_type_settings") && get_discount_type_settings() ){
			return auto_discount();
		}
		
		return array(
			'discount001' => array(
				'field_label' => 'Description',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'discount002' => array(
				'field_label' => 'Type',
				'form_field' => 'select',
				'form_field_options' => 'get_discount_types',
				'form_field_options_group' => 'get_discount_types_grouped',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'discount003' => array(
				'field_label' => 'Discount Value',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'discount004' => array(
				'field_label' => 'Minimum Sale Value',
				
				'form_field' => 'decimal',
				
				'tooltip' => 'Enter the minimum sale value prior to discount being automatically applied',
				'placeholder' => '',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	// function pay_row(){
	// 	$return = array(
	// 		'pay_row001' => array(
	// 			'field_label' => 'Date',
	// 			'form_field' => 'date-5',
	// 			'required_field' => 'yes',
				
	// 			//'class' => ' no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
 //                'default_appearance_in_table_fields' => 'show',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row002' => array(
	// 			'field_label' => 'Staff Name',
				
	// 			'form_field' => 'select',
	// 			'required_field' => 'yes',
				
	// 			'form_field' => 'calculated',
				
	// 			'calculations' => array(
	// 				'type' => 'users',
	// 				'form_field' => 'text',
	// 				'variables' => array( array( 'pay_row002' ) ),
	// 			),
				
	// 			'attributes' => ' action="?action=users&todo=get_users_select2" ',
				
	// 			//'form_field_options' => "get_employees_with_ref",
	// 			//'form_field_options_real' => "get_employees_with_ref",
				
	// 			'placeholder' => 'Staff Name',
	// 			'class' => ' select2 ',
				
	// 			'default_appearance_in_table_fields' => 'show',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row024' => array(
	// 			'field_label' => 'Staff Ref',
				
	// 			'form_field' => 'text',
	// 			'placeholder' => 'Staff Ref',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row027' => array(
	// 			'field_label' => 'Staff Category',
				
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_salary_category',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row055' => array(
	// 			'field_label' => 'Current Position',
	// 			'form_field' => 'text',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row026' => array(
	// 			'field_label' => 'Grade Level',
				
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_grade_levels',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row019' => array(
	// 			'field_label' => 'Currency',
				
	// 			'class' => ' col-lg-6 ',
	// 			'form_field' => 'select',
	// 			'form_field_options' => "get_currencies",
	// 			'default_appearance_in_table_fields' => 'show',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row039' => array(
	// 			'field_label' => 'Exchange Rate of ' . strtoupper( get_default_currency_settings() ),
				
	// 			'class' => ' col-lg-6 ',
	// 			'form_field' => 'decimal',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row020' => array(
	// 			'field_label' => 'No. of Days in Month',
				
	// 			'class' => ' col-lg-6 ',
	// 			'required_field' => 'yes',
	// 			'form_field' => 'decimal',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row029' => array(
	// 			'field_label' => 'No. of Working Days',
				
	// 			'class' => ' col-lg-6 ',
	// 			'form_field' => 'decimal',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row030' => array(
	// 			'field_label' => 'No. of Days on Leave',
				
	// 			'class' => ' col-lg-6 ',
	// 			'form_field' => 'decimal',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row021' => array(
	// 			'field_label' => 'No. of Days Overtime',
				
	// 			'class' => ' col-lg-6 ',
	// 			'form_field' => 'decimal',
	// 			'tooltip' => 'This is used to calculate overtime bonus',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row022' => array(
	// 			'field_label' => 'No. of Days Absent',
				
	// 			'tooltip' => 'This is used to calculate absent deduction',
	// 			'class' => ' col-lg-6 ',
	// 			'form_field' => 'decimal',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row003' => array(
	// 			'field_label' => 'Basic Salary',
				
	// 			'form_field' => 'decimal',
	// 			'required_field' => 'yes',
				
	// 			'placeholder' => 'Basic Salary',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
				
	// 			'default_appearance_in_table_fields' => 'show',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row004' => array(
	// 			'field_label' => 'Bonus',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Bonus',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row005' => array(
	// 			'field_label' => 'Housing',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Housing Allowance',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row006' => array(
	// 			'field_label' => 'Transport',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Transport',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row007' => array(
	// 			'field_label' => 'Utility',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Utility',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row008' => array(
	// 			'field_label' => 'Lunch',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Lunch',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row009' => array(
	// 			'field_label' => 'Overtime',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Overtime Allowance',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row010' => array(
	// 			'field_label' => 'Medical Allowance',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Medical Allowance',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row052' => array(
	// 			'field_label' => 'Added Res. Allowance',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'ARA',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row053' => array(
	// 			'field_label' => 'Hazard Allowance',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Hazard Allowance',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row057' => array(
	// 			'field_label' => 'Inconvenience Allowance',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Inconvenience Allowance',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row058' => array(
	// 			'field_label' => 'Call Allowance',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Call Allowance',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row059' => array(
	// 			'field_label' => 'Arrears',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Arrears',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row060' => array(
	// 			'field_label' => 'Compound Allowance',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Compound Allowance',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row061' => array(
	// 			'field_label' => 'Extra Duty Allowance',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Extra Duty Allowance',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row025' => array(
	// 			'field_label' => 'Leave Allowance',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Leave Allowance',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row023' => array(
	// 			'field_label' => 'Other Allowance',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Other Allowance',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),/*
	// 		'pay_row043' => array(
	// 			'field_label' => 'Other Bonus',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Other Bonus',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),*/
	// 		'pay_row044' => array(
	// 			'field_label' => 'Contribution Earned',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Staff Contribution',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row011' => array(
	// 			'field_label' => 'Paye Deduction',
				
	// 			'form_field' => 'decimal',
	// 			'placeholder' => 'Paye',
				
	// 			'class' => ' col-lg-6 personal-info ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row032' => array(
	// 			'field_label' => 'Gross Pay for Paye',
				
	// 			'form_field' => 'decimal',
	// 			'class' => ' col-lg-6 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row033' => array(
	// 			'field_label' => 'Relief Amount of Gross Pay',
				
	// 			'form_field' => 'decimal',
	// 			'class' => ' col-lg-6 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row034' => array(
	// 			'field_label' => 'Fixed Amount Relief',
				
	// 			'form_field' => 'decimal',
				
	// 			'class' => ' col-lg-6 personal-info ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row012' => array(
	// 			'field_label' => 'Pension (Voluntary)',
				
	// 			'form_field' => 'decimal',
	// 			'placeholder' => 'Pension',
				
	// 			'class' => ' col-lg-6 personal-info ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row028' => array(
	// 			'field_label' => 'Pension (Employee)',
	// 			'form_field' => 'decimal',
				
	// 			'class' => ' col-lg-6 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row031' => array(
	// 			'field_label' => 'Pension (Employer)',
				
	// 			'form_field' => 'decimal',
	// 			'class' => ' col-lg-6 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row013' => array(
	// 			'field_label' => 'Salary Advance',
				
	// 			'form_field' => 'decimal',
	// 			'placeholder' => 'Loans / Salary Advance',
				
	// 			'class' => ' col-lg-6 personal-info ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row014' => array(
	// 			'field_label' => 'Absent Deduction',
				
	// 			'form_field' => 'decimal',
	// 			'placeholder' => 'Absent',
				
	// 			'class' => ' col-lg-6 personal-info ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row054' => array(
	// 			'field_label' => 'Housing (NHF) Deduction',
				
	// 			'form_field' => 'decimal',
	// 			'placeholder' => 'Housing (NHF)',
	// 			'tooltip' => 'National Housing Fund (NHF) Deduction',
				
	// 			'class' => ' col-lg-6 personal-info ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row062' => array(
	// 			'field_label' => 'NMA Deduction',
				
	// 			'form_field' => 'decimal',
	// 			'placeholder' => 'NMA Deduction',
				
	// 			'class' => ' col-lg-6 personal-info ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row063' => array(
	// 			'field_label' => 'Loan Deduction',
				
	// 			'form_field' => 'decimal',
	// 			'placeholder' => 'Loan Deduction',
				
	// 			'class' => ' col-lg-6 personal-info ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row064' => array(
	// 			'field_label' => 'Medical Bill Deduction',
				
	// 			'form_field' => 'decimal',
	// 			'placeholder' => 'Medical Bill',
				
	// 			'class' => ' col-lg-6 personal-info ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row015' => array(
	// 			'field_label' => 'Other Deductions',
				
	// 			'form_field' => 'decimal',
	// 			'placeholder' => 'Other Deductions',
				
	// 			'class' => ' col-lg-6 personal-info ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row045' => array(
	// 			//'field_label' => 'Contribution Deductions',
	// 			'field_label' => 'Cooperative Deductions',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Cooperative Deductions',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row056' => array(
	// 			'field_label' => 'Disciplinary Charges',
	// 			'form_field' => 'decimal',
				
	// 			'placeholder' => 'Disciplinary Charges',
	// 			'class' => ' col-lg-6 no-x-padding-1 ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row041' => array(
	// 			'field_label' => 'ITF Contribution',
				
	// 			'form_field' => 'decimal',
				
	// 			'class' => ' col-lg-6 personal-info ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row042' => array(
	// 			'field_label' => 'NSITF Contribution',
				
	// 			'form_field' => 'decimal',
				
	// 			'class' => ' col-lg-6 personal-info ',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row035' => array(
	// 			'field_label' => 'Gross Pay',
				
	// 			'form_field' => 'decimal',
	// 			'class' => ' col-lg-6 ',
	// 			'default_appearance_in_table_fields' => 'show',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row036' => array(
	// 			'field_label' => 'Total Deductions',
				
	// 			'form_field' => 'decimal',
	// 			'default_appearance_in_table_fields' => 'show',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row016' => array(
				
	// 			'field_label' => 'Net Pay',
	// 			'form_field' => 'calculated',
				
	// 			'calculations' => array(
	// 				'type' => 'addition',
	// 				'form_field' => 'decimal',
	// 				'variables' => array( array( 'pay_row035' ) ),
	// 				'subtrend' => array( array( 'pay_row036' ) ),
	// 			),
				
 //                'default_appearance_in_table_fields' => 'show',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row017' => array(
				
	// 			'field_label' => 'Comment',
	// 			'form_field' => 'textarea',
	// 			'class' => ' clear-both ',
				
 //                'default_appearance_in_table_fields' => 'show',
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row037' => array(
	// 			'field_label' => 'Store',
				
	// 			'class' => ' clear-both ',
				
	// 			'form_field' => 'calculated',
	// 			'calculations' => array(
	// 				'type' => 'record-details',
	// 				'reference_table' => 'stores',
	// 				'reference_keys' => array( 'name', 'address' ),
	// 				'form_field' => 'text',
	// 				'variables' => array( array( 'pay_row037' ) ),
	// 			),
	// 			'attributes' => ' action="?action=stores&todo=get_select2" minlength="0" ',
	// 			'class' => ' select2 ',
				
	// 			'do_not_import' => 1,
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row038' => array(
	// 			'field_label' => 'Department',
				
	// 			'class' => ' clear-both ',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_departments',
	// 			'do_not_import' => 1,
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row040' => array(
	// 			'field_label' => 'Department Settings',
				
	// 			'class' => ' clear-both ',
	// 			'form_field' => 'multi-select',
	// 			'class' => 'select2',
	// 			'form_field_options' => 'get_pay_roll_options',
	// 			'do_not_import' => 1,
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
			
	// 		'pay_row046' => array(
	// 			'field_label' => 'Pension Fund Manager',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_pfa_options_value',
	// 			'do_not_import' => 1,
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row047' => array(
	// 			'field_label' => 'Tax Office Location',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_tax_office_options_value',
	// 			'do_not_import' => 1,
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row048' => array(
	// 			'field_label' => 'Bank',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_bank_names',
	// 			'do_not_import' => 1,
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row049' => array(
	// 			'field_label' => 'Housing Scheme Provider',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_housing_scheme_options_value',
	// 			'do_not_import' => 1,
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row050' => array(
	// 			'field_label' => 'Health Insurance Provider',
	// 			'form_field' => 'select',
	// 			'form_field_options' => 'get_health_insurance_options_value',
	// 			'do_not_import' => 1,
				
	// 			'display_position' => 'display-in-table-row',
	// 			'serial_number' => '',
	// 		),
	// 		'pay_row051' => array(
	// 			'field_label' => 'Data',
	// 			'form_field' => 'textarea-unlimited',
	// 			'do_not_import' => 1,
				
	// 			'display_position' => 'do-not-display-in-table',
	// 			'serial_number' => '',
	// 		),
	// 	);
		
	// 	foreach( $return as $k => & $v ){
	// 		switch( $k ){
	// 		//case "pay_row022":
	// 		//case "pay_row021":
	// 		case "pay_row030":
	// 		case "pay_row029":
	// 		case "pay_row020":
	// 		case "pay_row039":
	// 		case "pay_row019":
	// 		break;
	// 		default:
	// 			if( isset( $v['class'] ) ){
	// 				$v['class'] = str_replace( 'col-lg-6', '', $v['class'] );
	// 			}
	// 		break;
	// 		}
	// 	}
	// 	return $return;
	// }
	
	/*function pay_roll_post(){
		return array(
			'pay_roll_post001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'pay_roll_post002' => array(
				'field_label' => 'End Date',
				
				'form_field' => 'date-5',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post003' => array(
				'field_label' => 'Gross Pay',
				
				'form_field' => 'decimal',
				'placeholder' => 'Staff Ref',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post004' => array(
				'field_label' => 'Total Deductions',
				
				'form_field' => 'decimal',
				'placeholder' => 'Staff Ref',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post005' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'select',
				'form_field_options' => 'get_salary_schedule',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post006' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post007' => array(
				'field_label' => 'Staff Salary',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post008' => array(
				'field_label' => 'Staff Welfare',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post009' => array(
				'field_label' => 'Medical Allowance',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post010' => array(
				'field_label' => 'PAYE Deduction',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post011' => array(
				'field_label' => 'Pension',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post018' => array(
				'field_label' => 'Pension (Employer)',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post012' => array(
				'field_label' => 'Salary Advance',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post013' => array(
				'field_label' => 'Other Deduction',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post016' => array(
				'field_label' => 'Store',
				
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post017' => array(
				'field_label' => 'Reference',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post015' => array(
				'field_label' => 'Payment Posted',
				
				'form_field' => 'text',
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'pay_roll_post019' => array(
				'field_label' => 'Status',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_post020' => array(
				'field_label' => 'Data',
				
				'form_field' => 'textarea-unlimited',
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
		);
	}*/
	
	/*function pay_roll_auto_generate(){
		return array(
			'pay_roll_auto_generate001' => array(
				'field_label' => 'Month to Generate',
				
				'form_field' => 'select',
				'form_field_options' => "get_months_of_year",
				'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_auto_generate010' => array(
				'field_label' => 'Year to Generate',
				
				'form_field' => 'select',
				'form_field_options' => "get_calendar_years",
				'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_auto_generate002' => array(
				'field_label' => 'Generation Type',
				
				'form_field' => 'select',
				'required_field' => 'yes',
				'form_field_options' => "get_salary_generation_type",
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_auto_generate003' => array(
				'field_label' => 'Previous Month for Rollover',
				
				'form_field' => 'select',
				'form_field_options' => "get_months_of_year",
				'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_auto_generate004' => array(
				'field_label' => 'Previous Year for Rollover',
				
				'form_field' => 'select',
				'form_field_options' => "get_calendar_years",
				'class' => ' col-lg-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}*/
	
	/*function pay_roll_split(){
		return array(
			'pay_roll_split002' => array(
				'field_label' => 'Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'pay_roll_split003' => array(
				'field_label' => 'Department',
				
				'form_field' => 'select',
				'form_field_options' => 'get_departments',
				'class' => ' select2 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_split001' => array(
				'field_label' => 'Type',
				'required_field' => 'yes',
				'form_field' => 'select',
				'form_field_options' => 'get_options_type',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_split004' => array(
				'field_label' => 'Staff',
				
				'form_field' => 'select',
				'form_field_options' => 'get_employees',
				'class' => ' select2 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_split005' => array(
				'field_label' => 'Store',
				
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_split006' => array(
				'field_label' => 'Percentage Split',
				
				'form_field' => 'decimal',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_split007' => array(
				'field_label' => 'Expense Account',
				
				'form_field' => 'select',
				//'form_field_options' => 'get_types_of_expenditure_none',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_split008' => array(
				'field_label' => 'Liability Account',
				
				'form_field' => 'select',
				//'form_field_options' => 'get_types_of_expenditure_none',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'pay_roll_split009' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}*/
	
	function banks(){
	
		return array(
			'banks001' => array(
				'field_label' => 'Name',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'banks002' => array(
				'field_label' => 'Location',
				
				'form_field' => 'text',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'banks005' => array(
				'field_label' => 'Type',
				'required_field' => 'yes',
				'form_field' => 'select',
				'class' => ' select2 ',
				'form_field_options' => 'get_options_type',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'banks004' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'banks006' => array(
				'field_label' => 'Serial Number',
				
				'form_field' => 'number',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'banks007' => array(
				'field_label' => 'Value',
				
				'form_field' => 'decimal',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'banks008' => array(
				'field_label' => 'Percentage',
				
				'form_field' => 'decimal',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'banks009' => array(
				'field_label' => 'Account',
				
				'form_field' => 'calculated',
				'calculations' => array(
					'type' => 'account-name',
					'form_field' => 'text',
					'variables' => array( array( 'banks009' ) ),
				),
				'class' => ' select2 ',
				'attributes' => ' action="?action=chart_of_accounts&todo=get_chart_of_accounts_select2" ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function import_items(){
		$package = '';
		
		if( defined("HYELLA_PACKAGE") ){
			$package = str_replace( "-", "_", HYELLA_PACKAGE );
		}
		if( function_exists( $package."_import_items" ) ){
			$f = $package."_import_items";
			return $f();
		}
		$a_ext = 'csv';
		$a_ext2 = 'csv';
		$platform = ( defined("PLATFORM")?PLATFORM:'windows' );
		if( $platform == 'windows' ){
			$a_ext .= ',xls,xlsx';
			$a_ext2 .= ':::xls:::xlsx';
		}
		$return = array(
			'import_items001' => array(
				
				'field_label' => 'Excel File ('.$a_ext.')', 
				'form_field' => 'file',
				'required_field' => 'yes',
				
				'tooltip' => 'NOTE: Upload csv files for the best & fastest outcome',
				'acceptable_files_format' => $a_ext2, //xls:::xlsx:::
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' col-md-12 ',
			),/*
			'import_items002' => array(
				
				'field_label' => 'Operator',
				'form_field' => 'select',
				'required_field' => 'yes',
                'form_field_options' => 'get_operators',
				
				'class' => ' col-md-6 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),*/
			'import_items003' => array(
				'field_label' => 'Import Template',
				'form_field' => 'select',
				'form_field_options' => 'get_database_tables2',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' col-md-12 select2 ',
				
			),/*
			'import_items005' => array(
			
				'field_label' => 'Month',
				'form_field' => 'select',
				'required_field' => 'yes',
				'form_field_options' => 'get_months_of_year',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'import_items006' => array(
				
				'field_label' => 'Department',
				'form_field' => 'select',
				'form_field_options' => 'get_departments',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'import_items004' => array(
				
				'field_label' => 'Departmental Unit',
				'form_field' => 'select',
				'required_field' => 'yes',
				'form_field_options' => 'get_units',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' col-md-6 ',
				
			),
			'import_items007' => array(
				
				'field_label' => 'Budget Code',
				//'form_field' => 'text',
				'form_field' => 'select',
				
				'required_field' => 'yes',
				'form_field_options' => 'get_all_budgets',
				//tie this to existing budgets
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'class' => ' ',
			),*/
			'import_items008' => array(
				
				'field_label' => 'Starting Row',
				'form_field' => 'number',
				
				'serial_number' => 1.8,
				'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),/*
			'import_items009' => array(
				
				'field_label' => 'Pic Code Column',
				'form_field' => 'number',
				
				'attributes' => 'col-label="PIC CODE" ',
				'class' => 'column-select-field',
				'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items010' => array(
				
				'field_label' => 'Description Column',
				'form_field' => 'number',
				
				'attributes' => 'col-label="DESC" ',
				'class' => 'column-select-field',
				'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items011' => array(
				
				'field_label' => 'Weight',
				'form_field' => 'number',
				
				'attributes' => 'col-label="WEIGHT" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items012' => array(
				
				'field_label' => 'Cost Price',
				'form_field' => 'number',
				
				'attributes' => 'col-label="CP" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items013' => array(
				
				'field_label' => 'Selling Price',
				'form_field' => 'number',
				
				'attributes' => 'col-label="SP" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items014' => array(
				
				'field_label' => '% Mark-up',
				'form_field' => 'number',
				
				'attributes' => 'col-label="% MARK UP" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items015' => array(
				
				'field_label' => 'Color',
				'form_field' => 'number',
				
				'attributes' => 'col-label="COLOR" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items016' => array(
				
				'field_label' => 'Category',
				'form_field' => 'number',
				
				'attributes' => 'col-label="CATEGORY" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items030' => array(
				
				'field_label' => 'Vendor',
				'form_field' => 'number',
				
				'attributes' => 'col-label="VENDOR" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items031' => array(
				
				'field_label' => 'Currency',
				'form_field' => 'number',
				
				'attributes' => 'col-label="CUR" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items017' => array(
				
				'field_label' => 'Quantity',
				'form_field' => 'number',
				
				'attributes' => 'col-label="QTY" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items018' => array(
				
				'field_label' => 'BARCODE',
				'form_field' => 'number',
				
				'attributes' => 'col-label="BARCODE" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items019' => array(
				
				'field_label' => 'Unit',
				'form_field' => 'number',
				
				'attributes' => 'col-label="UNIT" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items020' => array(
				
				'field_label' => 'Unit of Measure',
				'form_field' => 'number',
				
				'attributes' => 'col-label="UNIT OF MEASURE" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items021' => array(
				
				'field_label' => 'Approved Budget N\'000 (optional)',
				'form_field' => 'number',
				
				'attributes' => 'col-label="APPR BUDGET N\'000" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items022' => array(
				
				'field_label' => 'Approved Budget $\'000 (optional)',
				'form_field' => 'number',
				
				'attributes' => 'col-label="APPR BUDGET $\'000" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),
			'import_items023' => array(
				
				'field_label' => 'Remarks (optional)',
				'form_field' => 'number',
				
				'attributes' => 'col-label="REMARKS" ',
				'class' => 'column-select-field',
				//'required_field' => 'yes',
				'display_position' => 'display-in-table-row',
			),*/
		);
		
		if( defined("IMPORT_TEMPLATE_TYPE") && IMPORT_TEMPLATE_TYPE && function_exists( IMPORT_TEMPLATE_TYPE ) ){
			$r1 = IMPORT_TEMPLATE_TYPE;
			
			$r = $r1();
			$cls = 'c'.ucwords( $r1 );
			$class = new $cls();
			
			$serial = 8;
			foreach( $class->table_fields as $key => $val ){
				if( isset( $r[ $val ] ) ){
					$add = 0;
					
					if( isset( $r[ $val ][ "do_not_import" ] ) && $r[ $val ][ "do_not_import" ] ){
						
					}else if( isset( $class->basic_data[ "import_template" ] ) ){
						if( isset( $class->basic_data[ "import_template" ]["fields"][ $key ] ) ){
							$add = 1;
						}
					}else{
						$add = 1;
					}
					
					if( $add ){
						++$serial;
						
						$r[ $val ]['attributes'] = ' col-label="'.strtoupper( $r[ $val ]["field_label"] ).'" ';
						$r[ $val ]['form_field'] = 'number';
						$r[ $val ]['class'] = 'column-select-field';
						if( $serial < 10 )$return[ 'import_items00'.$serial ] = $r[ $val ];
						else $return[ 'import_items0'.$serial ] = $r[ $val ];
					}
				}
				
				
			}
		}
		
		return $return;
	}
	
	function items_raw_data_import(){
		for( $x = 1; $x < 41; ++$x ){
			$key = 'items_raw_data_import0';
			if( $x < 10 )$key .= '0'.$x;
			else $key .= $x;
			
			$return[ $key ] = array(
				'field_label' => 'Column '.($x-1),
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			);
		}
		$return['items_raw_data_import041'] = array(
				
				'field_label' => 'Excel Reference ID',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
		);	
		return $return;
	}
	
	function chart_of_accounts(){
		$package = '';
		if( defined("HYELLA_PACKAGE") ){
			$package = str_replace( "-", "_", HYELLA_PACKAGE );
			switch( $package ){
			case 'catholic':
				if( defined("HYELLA_SUB_PACKAGE") ){
					$package = HYELLA_SUB_PACKAGE;
				}
			break;
			}
		}
		
		if( function_exists( $package."_chart_of_accounts" ) ){
			$f = $package."_chart_of_accounts";
			return $f();
		}
		
		$return = array(
			'chart_of_accounts001' => array(
				'field_label' => 'Type of Account',
				
				'form_field' => 'select',
				'form_field_options' => "get_types_of_account",
				'form_field_options_group' => "get_types_of_account_grouped",
				'class' => ' account-type ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'chart_of_accounts002' => array(
				'field_label' => 'Title of Account',
				
				'form_field' => 'text',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'chart_of_accounts003' => array(
				'field_label' => 'Code',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'chart_of_accounts004' => array(
				'field_label' => 'Parent Account',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'record-details',
					'form_field' => 'text',
					'reference_table' => 'chart_of_accounts',
					'reference_keys' => array( 'title', 'code' ),
					'variables' => array( array( 'chart_of_accounts004' ) ),
				),
				'attributes' => ' action="?action=chart_of_accounts&todo=get_chart_of_accounts_select2&parent_only=1" minlength="0" data-params=".account-type" ',
				'class' => ' account-1st-parent select2 allow-clear ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'chart_of_accounts005' => array(
				'field_label' => 'Closing Account',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'record-details',
					'form_field' => 'text',
					'reference_table' => 'chart_of_accounts',
					'reference_keys' => array( 'title', 'code' ),
					'variables' => array( array( 'chart_of_accounts005' ) ),
				),
				'attributes' => ' action="?action=chart_of_accounts&todo=get_chart_of_accounts_select2"  minlength="0" ',
				'class' => ' select2 allow-clear ',
				
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'chart_of_accounts006' => array(
				'field_label' => 'Store',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'record-details',
					'reference_table' => 'stores',
					'reference_keys' => array( 'name' ),
					'form_field' => 'text',
					'variables' => array( array( 'chart_of_accounts006' ) ),
				),
				'attributes' => ' action="?action=stores&todo=get_select2&type=main-store" minlength="0" ',
				'class' => ' select2 ',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'hide',
				'serial_number' => '',
			),
			'chart_of_accounts008' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'select',
				'form_field_options' => "get_currencies",
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'chart_of_accounts007' => array(
				'field_label' => 'Tag',
				
				'form_field' => 'textarea',
				//'class' => ' select2 ',
				//'attributes' => ' action="?action=banks&todo=get_select2" tags="true"  ',
				
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'chart_of_accounts009' => array(
				'field_label' => 'Status',
				
				'form_field' => 'select',
				'form_field_options' => 'get_active_inactive',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
		
		if( function_exists("get_chart_of_accounts_code_settings") ){
			$cd = get_chart_of_accounts_code_settings();
			switch( $cd ){
			case 1:
				$return['chart_of_accounts003']['required_field'] = 'yes';
			break;
			case 2:
				$return['chart_of_accounts003']['note'] = 'Leave blank to auto generate code';
			break;
			}
		}
		
		return $return;
	}
	
	function transactions( $tbf = '' ){
		$return = array(
			'transactions001' => array(
				'field_label' => 'Date',
				
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions002' => array(
				'field_label' => 'Description',
				
				'form_field' => 'text',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions003' => array(
				'field_label' => 'Reference',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions004' => array(
				'field_label' => 'Tag',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions005' => array(
				'field_label' => 'Debit',
				'field_label' => 'Est. Total Amount',
				
				'default_appearance_in_table_fields' => 'show',
				
				'form_field' => 'calculated',
				'form_field' => 'decimal',
				
				'calculations' => array(
					'type' => 'debit-transactions',
					'form_field' => 'text',
					'variables' => array( array( 'id' ) ),
				),
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions010' => array(
				'field_label' => 'Credit',
				
				'form_field' => 'calculated',
				'form_field' => 'decimal',
				'default_appearance_in_table_fields' => 'hide',
				
				'calculations' => array(
					'type' => 'credit-transactions',
					'form_field' => 'text',
					'variables' => array( array( 'id' ) ),
				),
				
				'display_position' => 'display-in-table-row',
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'transactions006' => array(
				'field_label' => 'Status',
				
				'form_field' => 'select',
				'form_field_options' => "get_transaction_status",
				
				//'default_appearance_in_table_fields' => 'show',
				'display_position' => 'do-not-display-in-table',
				//'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions007' => array(
				'field_label' => 'Validated By',
				
				'form_field' => 'calculated',
				'calculations' => array(
					'type' => 'record-details',
					'reference_table' => 'users',
					'reference_keys' => array( 'firstname', 'lastname' ),
					'form_field' => 'text',
					'variables' => array( array( 'transactions007' ) ),
				),
				'attributes' => ' action="?action=users&todo=get_select2"  minlength="0" ',
				'class' => ' select2 ',
				
				//'form_field' => 'select',
				//'form_field_options' => "get_employees",
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'transactions008' => array(
				'field_label' => 'Validated On',
				
				'form_field' => 'date-5',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions009' => array(
				'field_label' => 'Location',
				
				'form_field' => 'calculated',
				'calculations' => array(
					'type' => 'record-details',
					'reference_table' => 'stores',
					'reference_keys' => array( 'name', 'address' ),
					'form_field' => 'text',
					'variables' => array( array( 'transactions009' ) ),
				),
				'attributes' => ' action="?action=stores&todo=get_select2"  minlength="0" ',
				'class' => ' select2 ',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
				
			),
			'transactions011' => array(
				'field_label' => 'REF',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'transactions012' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'textarea',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
		
		switch( $tbf ){
		case "display_validated_trans":
			$return["transactions012"]["default_appearance_in_table_fields"] = "show";
			$return["transactions008"]["default_appearance_in_table_fields"] = "show";
		break;
		case "pre_display_validation":
			$return["transactions007"]["display_position"] = "do-not-display-in-table";
			$return["transactions008"]["display_position"] = "do-not-display-in-table";
		break;
		}
		
		return $return;
	}
	
	function debit_and_credit(){
		$return = array(
			'debit_and_credit001' => array(
				'field_label' => 'Transaction Ref',
				
				'form_field' => 'text',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit002' => array(
				'field_label' => 'Account',
				
				'form_field' => 'calculated',
				'calculations' => array(
					'type' => 'account-name',
					'form_field' => 'text',
					'variables' => array( array( 'debit_and_credit002', 'debit_and_credit005', 'debit_and_credit011' ) ),
				),
				
				//'attributes' => ' action="?action=chart_of_accounts&todo=get_chart_of_accounts_select2&account_type=account_payable" ',
				'attributes' => ' action="?action=chart_of_accounts&todo=get_chart_of_accounts_select2&debit_edit=1" data-params=".selected-account" ',
				'class' => ' select2 selected-account ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'field_key' => 'account',
				'serial_number' => '',
			),
			'debit_and_credit003' => array(
				'field_label' => 'Amount',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit004' => array(
				'field_label' => 'Type',
				
				'form_field' => 'select',
				'form_field_options' => 'get_transaction_type',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'debit_and_credit005' => array(
				'field_label' => 'Acc Type',
				'field_key' => 'acc_type',
				
				'form_field' => 'select',
				'form_field_options' => 'get_types_of_account2',
				'class' => ' selected-account ',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'debit_and_credit006' => array(
				'field_label' => 'REF',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit007' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit008' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit009' => array(
				'field_label' => 'Date',
				
				'form_field' => 'date-5',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'debit_and_credit010' => array(
				'field_label' => 'Store',
				
				'form_field' => 'calculated',
				'calculations' => array(
					'type' => 'record-details',
					'reference_table' => 'stores',
					'reference_keys' => array( 'name', 'address' ),
					'form_field' => 'text',
					'variables' => array( array( 'debit_and_credit010' ) ),
				),
				'attributes' => ' action="?action=stores&todo=get_select2"  minlength="0" ',
				'class' => ' select2 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit011' => array(
				'field_label' => 'Account Source',
				
				//tables list
				'class' => ' selected-account ',
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'field_key' => 'account_source',
			),
			'debit_and_credit014' => array(
				'field_label' => 'Data',
				
				'form_field' => 'textarea-unlimited',
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'debit_and_credit015' => array(
				'field_label' => 'Reference',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit012' => array(
				'field_label' => 'Reference Table',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit013' => array(
				'field_label' => 'Exchange Rate',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit016' => array(
				'field_label' => 'Account2',
				
				'form_field' => 'calculated',
				'attributes' => ' action="?action=chart_of_accounts&todo=get_account2_select2_dc&debit_edit=2" minlength="0" data-params=".selected-account" ',
				'class' => ' select2 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'field_key' => 'account2',
			),
			'debit_and_credit017' => array(
				'field_label' => 'Category',
				
				'form_field' => 'calculated',
				'calculations' => array(
					'reference_table' => 'banks',
					'reference_keys' => array( 'name' ),
					'type' => 'record-details',
					'form_field' => 'text',
					'variables' => array( array( 'debit_and_credit017' ) ),
				),
				
				'attributes' => ' action="?action=banks&todo=get_select2&type=payment_category&hide_type=1" ',
				'class' => ' select2 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit019' => array(
				'field_label' => 'Transaction Reference',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit020' => array(
				'field_label' => 'Reconciled',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit021' => array(
				'field_label' => 'Department',
				//'form_field' => 'select',
				//'form_field_options' => 'get_departments',
				'form_field' => 'calculated',
				'class' => ' select2 ',
				
				'attributes' => ' action="?action=departments&todo=get_select2" minlength="0" ',
				'calculations' => array(
					'type' => 'record-details',
					'reference_table' => 'departments',
					'reference_keys' => array( 'name' ),
					'form_field' => 'text',
					'variables' => array( array( 'debit_and_credit021' ) ),
				),
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit022' => array(
				'field_label' => 'PC %',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit023' => array(
				'field_label' => 'PC Type',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit024' => array(
				'field_label' => 'PC Ref',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit7170' => array(
				'field_label' => 'Status',
				
				'form_field' => 'select',
				'form_field_options' => 'get_transaction_status',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit7171' => array(
				'field_label' => 'Child Reference',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'hide',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'debit_and_credit7172' => array(
				'field_label' => 'Child Reference Table',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'hide',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
		/* 
		//if( get_weight_average_cost_calculation_type_settings() ){
			$return['debit_and_credit016'] = $return['debit_and_credit012'];
			$return['debit_and_credit016']['field_label'] = 'Account2';
		//}
		 */
		return $return;
	}
	
	function transactions_draft( $tbf = '' ){
		$r = transactions( $tbf );
		
		$r[ 'transactions005' ][ 'calculations' ][ 'type' ] = 'debit-draft-transactions';
		$r[ 'transactions010' ][ 'calculations' ][ 'type' ] = 'credit-draft-transactions';
		
		$return = array();
		foreach( $r as $rk => $rv ){
			$return[ str_replace( 'transactions', 'transactions_draft', $rk ) ] = $rv;
		}
		return $return;
	}
	
	function debit_and_credit_draft(){
		$r = debit_and_credit();
		$return = array();
		foreach( $r as $rk => $rv ){
			$return[ str_replace( 'debit_and_credit', 'debit_and_credit_draft', $rk ) ] = $rv;
		}
		return $return;
	}
	
	function customer_deposits(){
		return array(
			'customer_deposits001' => array(
				'field_label' => 'Date',
				
				'form_field' => 'date-5',
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits002' => array(
				'field_label' => 'Customer Name',
				
				'form_field' => 'calculated',
				'calculations' => array(
					'type' => 'customers',
					'form_field' => 'text',
					'variables' => array( array( 'customer_deposits002' ) ),
				),
				
				'attributes' => ' action="?action=customers&todo=get_customers_select2" ',
				'class' => ' no-x-padding-1 select2 ',
				
				'required_field' => 'yes',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits003' => array(
				'field_label' => 'Amount Deposited',
				
				'form_field' => 'decimal',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits004' => array(
				'field_label' => 'Amount Withdrawn',
				
				'form_field' => 'decimal',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits010' => array(
				'field_label' => 'Payment Method',
				
				'form_field' => 'select',
				'form_field_options' => 'get_payment_method',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits005' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'placeholder' => 'Optional Comment',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits006' => array(
				'field_label' => 'Currency',
				
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits007' => array(
				'field_label' => 'Reference Table',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits008' => array(
				'field_label' => 'Reference',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'customer_deposits009' => array(
				'field_label' => 'Store',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function security_question(){
		return array(
			'security_question001' => array(
				'field_label' => 'Security Question',
				'form_field' => 'text',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'security_question002' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'placeholder' => '',
				//'class' => ' col-md-6 personal-info ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function items_items(){
		return array(
			'items_items001' => array(
				'field_label' => 'Parent Item',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'item-details',
					'form_field' => 'text',
					'variables' => array( array( 'items_items001' ) ),
				),
				//'form_field' => 'select',
				'required_field' => 'yes',
				//'form_field_options' => 'get_items',
				
				'attributes' => ' action="?action=items&todo=get_items_select2&type=composite,composite_production" ',
				'class' => ' no-x-padding-1 select2 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'items_items007' => array(
				'field_label' => 'Quantity of Parent Item',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'items_items002' => array(
				'field_label' => 'Sub Item',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'item-details',
					'form_field' => 'text',
					'variables' => array( array( 'items_items002' ) ),
				),
				'required_field' => 'yes',
				
				'attributes' => ' action="?action=items&todo=get_items_select2&type=composite,composite_production&not=1" ',
				
				'class' => ' no-x-padding-1 select2 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'items_items003' => array(
				'field_label' => 'Quantity of Sub Item',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),/*
			'items_items004' => array(
				'field_label' => 'Unit',
				
				'class' => ' col-md-6 ',
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),*/
		);
	}
	
	function stock_request(){
		return array(
			'stock_request001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'stock_request019' => array(
				'field_label' => 'Time',
				'form_field' => 'text',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request021' => array(
				'field_label' => 'Category',
				'form_field' => 'calculated',
				'calculations' => array(
					'reference_table' => 'banks',
					'reference_keys' => array( 'name' ),
					'type' => 'record-details',
					'form_field' => 'text',
					'variables' => array( array( 'stock_request021' ) ),
				),
				
				'attributes' => ' action="?action=banks&todo=get_select2&type=purchase_category&hide_type=1" minlength="0" ',
				'class' => ' select2 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request002' => array(
				'field_label' => 'Description',
				
				'form_field' => 'textarea',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request003' => array(
				'field_label' => 'Request By',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'record-details',
					'reference_table' => 'users',
					'reference_keys' => array( 'firstname', 'lastname' ),
					'form_field' => 'text',
					'variables' => array( array( 'stock_request003' ) ),
				),
				'attributes' => ' action="?action=users&todo=get_users_select2" ',
				'class' => ' select2 ',
				
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'stock_request004' => array(
				'field_label' => 'Sent To (Store)',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'record-details',
					'form_field' => 'text',		
					'reference_table' => 'stores',
					'reference_keys' => array( 'name' ),
					'variables' => array( array( 'stock_request004' ) ),
				),
				'attributes' => ' action="?action=stores&todo=get_select2" minlength="0" ',
				
				'class' => ' select2 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request016' => array(
				'field_label' => 'Request By (Department)',
				
				//'form_field' => 'select',
				//'form_field_options' => 'get_departments',
				'form_field' => 'calculated',
				
				'class' => ' select2 ',
				
				'attributes' => ' action="?action=departments&todo=get_select2" minlength="0" ',
				'calculations' => array(
					'type' => 'record-details',
					'reference_table' => 'departments',
					'reference_keys' => array( 'name' ),
					'form_field' => 'text',
					'variables' => array( array( 'stock_request016' ) ),
				),
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request005' => array(
				'field_label' => 'Status',
				
				'form_field' => 'select',
				'form_field_options' => 'get_stock_request_status',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request006' => array(
				'field_label' => 'Type',
				
				'form_field' => 'select',
				'form_field_options' => 'get_stock_request_types',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request007' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'textarea',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request008' => array(
				'field_label' => 'Reference',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'stock_request009' => array(
				'field_label' => 'Reference Table',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'stock_request010' => array(
				'field_label' => 'Child Reference',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'stock_request011' => array(
				'field_label' => 'Child Reference Table',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'stock_request012' => array(
				'field_label' => 'Currency',
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				
				//'class' => ' no-x-padding-1 col-lg-6 ',
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'stock_request020' => array(
				'field_label' => 'Exchange Rate',
				'form_field' => 'decimal',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request013' => array(
				'field_label' => 'Data',
				'form_field' => 'textarea-unlimited',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'stock_request017' => array(
				'field_label' => 'Sent By',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'record-details',
					'reference_table' => 'users',
					'reference_keys' => array( 'firstname', 'lastname' ),
					'form_field' => 'text',
					'variables' => array( array( 'stock_request017' ) ),
				),
				'attributes' => ' action="?action=users&todo=get_users_select2" ',
				'class' => ' select2 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request014' => array(
				'field_label' => 'Destination (User)',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'users',
					'form_field' => 'text',
					'variables' => array( array( 'stock_request014' ) ),
				),
				'attributes' => ' action="?action=users&todo=get_users_select2" ',
				
				'placeholder' => 'Staff Name',
				'class' => ' select2 ',
				
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request015' => array(
				'field_label' => 'Request By (Store)',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'record-details',
					'form_field' => 'text',		
					'reference_table' => 'stores',
					'reference_keys' => array( 'name' ),
					'variables' => array( array( 'stock_request015' ) ),
				),
				'attributes' => ' action="?action=stores&todo=get_select2" minlength="0" ',
				
				'class' => ' select2 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request018' => array(
				'field_label' => 'Sent To (Department)',
				
				//'form_field' => 'select',
				//'form_field_options' => 'get_departments',
				'form_field' => 'calculated',
				
				'class' => ' select2 ',
				
				'attributes' => ' action="?action=departments&todo=get_select2" minlength="0" ',
				'calculations' => array(
					'type' => 'record-details',
					'reference_table' => 'departments',
					'reference_keys' => array( 'name' ),
					'form_field' => 'text',
					'variables' => array( array( 'stock_request018' ) ),
				),
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function stock_request_items(){
		$return = array(
			'stock_request_items001' => array(
				'field_label' => 'Reference',
				'form_field' => 'text',/*
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'production-ref-num',
					'form_field' => 'text',
					'variables' => array( array( 'stock_request_items001' ) ),
				),
				*/
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'stock_request_items002' => array(
				'field_label' => 'Item',
				
				'form_field' => 'text',
				
				'placeholder' => '',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request_items003' => array(
				'field_label' => 'Stock Level',
				
				'form_field' => 'decimal',
				'class' => ' col-lg-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request_items004' => array(
				'field_label' => 'Quantity',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request_items005' => array(
				'field_label' => 'Quantity Reviewed',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request_items006' => array(
				'field_label' => 'Quantity Approved',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'stock_request_items007' => array(
				'field_label' => 'Quantity Type',
				
				'form_field' => 'decimal',
				'required_field' => 'yes',
				'class' => ' col-lg-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
		
		return $return;
	}
	
	function membership(){
		return array(
			'membership001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'membership002' => array(
				'field_label' => 'Customer',
				
				'default_appearance_in_table_fields' => 'show',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'customers',
					'form_field' => 'text',
					'variables' => array( array( 'membership002' ) ),
				),
				
				'attributes' => ' action="?action=customers&todo=get_customers_select2" ',
				'class' => ' no-x-padding-1 select2 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership003' => array(
				'field_label' => 'Reference No.',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership004' => array(
				'field_label' => 'Plan',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership005' => array(
				'field_label' => 'Access Code',
				
				'form_field' => 'text',
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'membership008' => array(
				'field_label' => 'Expiry Date',
				
				'form_field' => 'date-5',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership009' => array(
				'field_label' => 'Status',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership010' => array(
				'field_label' => 'Reference',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'membership011' => array(
				'field_label' => 'Reference Table',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'membership012' => array(
				'field_label' => 'Staff Responsible',
				'form_field' => 'select',
				'form_field_options' => 'get_employees',
				
				//'class' => ' no-x-padding-1 col-lg-6 ',
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'membership013' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership014' => array(
				'field_label' => 'Store',
				
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function membership_plan(){
		return array(
			'membership_plan001' => array(
				'field_label' => 'Category',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
                'serial_number' => '',
			),
			'membership_plan002' => array(
				'field_label' => 'Description',
				
				'required_field' => 'yes',
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_plan003' => array(
				'field_label' => 'Renewal Period',
				
				'form_field' => 'select',
				'form_field_options' => 'get_renewal_period_type',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_plan004' => array(
				'field_label' => 'Duration per Renewal',
				
				'default_appearance_in_table_fields' => 'show',
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_plan005' => array(
				'field_label' => 'Rate',
				
				'form_field' => 'currency',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_plan006' => array(
				'field_label' => 'Type of Members',
				
				'form_field' => 'decimal',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_plan007' => array(
				'field_label' => 'Max. No. of Members',
				
				'form_field' => 'number',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_plan008' => array(
				'field_label' => 'Status',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_plan009' => array(
				'field_label' => 'Store',
				
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_plan010' => array(
				'field_label' => 'Currency',
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				
				//'class' => ' no-x-padding-1 col-lg-6 ',
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'membership_plan011' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_plan012' => array(
				'field_label' => 'Compulsory Fee',
				
				'form_field' => 'select',
				'form_field_options' => 'get_yes_no',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function attendance(){
		return array(
			'attendance001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'attendance002' => array(
				'field_label' => 'Date Out',
				'form_field' => 'date-5',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'attendance003' => array(
				'field_label' => 'Customer',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'customers',
					'form_field' => 'text',
					'variables' => array( array( 'attendance003' ) ),
				),
				
				'attributes' => ' action="?action=customers&todo=get_customers_select2" ',
				'class' => ' no-x-padding-1 select2 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'attendance004' => array(
				'field_label' => 'Reference',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'attendance005' => array(
				'field_label' => 'Reference Table',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'attendance006' => array(
				'field_label' => 'Child Reference',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'attendance007' => array(
				'field_label' => 'Child Reference Table',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'attendance008' => array(
				'field_label' => 'Staff Responsible',
				
				'form_field' => 'select',
				'form_field_options' => 'get_employees',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'attendance009' => array(
				'field_label' => 'Store',
				
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				
				//'class' => ' col-md-6 ',
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'attendance010' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'placeholder' => '',
				
				//'class' => ' col-md-6 personal-info ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'attendance011' => array(
				'field_label' => 'Photograph',
				'form_field' => 'file',
				'acceptable_files_format' => 'jpg:::jpeg:::png',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'attendance012' => array(
				'field_label' => 'Auth Method',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
	}
	
	function membership_registration(){
		return array(
			'membership_registration001' => array(
				'field_label' => 'Date',
				//'form_field' => 'date-5',
				'required_field' => 'yes',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'membership-registration',
					'form_field' => 'date-5',
					'variables' => array( array( 'id' ) ),
				),
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'membership_registration002' => array(
				'field_label' => 'Plan',
				
				'required_field' => 'yes',
				'class' => ' col-md-6 ',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'membership_plan',
					'form_field' => 'text',
					'variables' => array( array( 'membership_registration002' ) ),
				),
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_registration003' => array(
				'field_label' => 'Staff Responsible',
				
				'form_field' => 'select',
				'form_field_options' => 'get_employees',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_registration004' => array(
				'field_label' => 'Store',
				
				'form_field' => 'select',
				'form_field_options' => 'get_stores',
				
				//'class' => ' col-md-6 ',
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_registration005' => array(
				'field_label' => 'Status',
				
				'form_field' => 'select',
				'form_field_options' => 'get_active_inactive',
				
				//'class' => ' col-md-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),/*
			'membership_registration006' => array(
				'field_label' => 'Amount Due',
				
				'form_field' => 'currency',
				'required_field' => 'yes',
				'class' => ' col-md-6 ',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_registration007' => array(
				'field_label' => 'Discount',
				'form_field' => 'decimal',
				
				//'class' => ' col-md-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_registration008' => array(
				'field_label' => 'Amount Paid',
				
				'form_field' => 'currency',
				'placeholder' => '',
				
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'membership_registration009' => array(
				'field_label' => 'Payment Method',
				
				'form_field' => 'select',
				'form_field_options' => 'get_payment_method',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_registration010' => array(
				'field_label' => 'Duration',
				'form_field' => 'decimal',
				
				//'class' => ' col-md-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),*/
			'membership_registration011' => array(
				'field_label' => 'Expiry Date',
				'form_field' => 'date-5',
				
				//'class' => ' col-md-6 ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_registration012' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'placeholder' => '',
				
				//'class' => ' col-md-6 personal-info ',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_registration013' => array(
				'field_label' => 'Reference',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'membership_registration014' => array(
				'field_label' => 'Reference Table',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'membership_registration015' => array(
				'field_label' => 'Child Reference',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'membership_registration016' => array(
				'field_label' => 'Child Reference Table',
				'form_field' => 'text',
				
				'display_position' => 'display-in-table-row',
                //'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),/*
			'membership_registration017' => array(
				'field_label' => 'Currency',
				'form_field' => 'select',
				'form_field_options' => 'get_currencies',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_registration018' => array(
				'field_label' => 'VAT',
				'form_field' => 'decimal',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_registration019' => array(
				'field_label' => 'Surcharge',
				'form_field' => 'decimal',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),*/
		);
	}
	
	function membership_registration_customers(){
		$return = array(
			'membership_registration_customers001' => array(
				'field_label' => 'Reference',
				'form_field' => 'text',
				
				'attributes' => ' action="?action=membership_registration&todo=search_registration_id" ',
				'class' => ' no-x-padding-1 select2 ',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'membership-registration',
					'form_field' => 'text',
					'reference_table' => 'membership_registration',
					'reference_keys' => array( 'serial_num' ),
					
					'variables' => array( array( 'membership_registration_customers001' ) ),
				),
				
				
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'membership_registration_customers002' => array(
				'field_label' => 'Participant (Registered Members Only)',
				
				//'form_field' => 'text',
				'attributes' => ' action="?action=membership&todo=get_members_option" ',
				'class' => ' no-x-padding-1 select2 ',
				
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'customers',
					'form_field' => 'text',
					'variables' => array( array( 'membership_registration_customers002' ) ),
				),
				
				'placeholder' => '',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_registration_customers003' => array(
				'field_label' => 'Expiry Date',
				
				'form_field' => 'date-5',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_registration_customers004' => array(
				'field_label' => 'Access Code',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_registration_customers005' => array(
				'field_label' => 'Extra',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'membership_registration_customers006' => array(
				'field_label' => 'Comment',
				
				'form_field' => 'text',
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
		);
		if( ! ( function_exists("show_membership_subscription_access_code_in_backend") && show_membership_subscription_access_code_in_backend() ) ){
			unset( $return["membership_registration_customers004"] );
		}
		return $return;
	}
	
	function member_stats(){
		$return = array(
			'member_stats001' => array(
				'field_label' => 'Date',
				'form_field' => 'date-5',
				'required_field' => 'yes',
				
				//'class' => ' no-x-padding-1 ',
				'display_position' => 'display-in-table-row',
                'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
			),
			'member_stats002' => array(
				'field_label' => 'Member',
				
				'default_appearance_in_table_fields' => 'show',
				'form_field' => 'calculated',
				
				'calculations' => array(
					'type' => 'customers',
					'form_field' => 'text',
					'variables' => array( array( 'member_stats002' ) ),
				),
				'attributes' => ' action="?action=customers&todo=get_customers_select2" ',
				'class' => ' no-x-padding-1 select2 ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'member_stats003' => array(
				'field_label' => 'Reference No.',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'member_stats004' => array(
				'field_label' => 'Height (cm)',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'form_field' => 'text',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'member_stats005' => array(
				'field_label' => 'Weight (Kg)',
				
				'form_field' => 'text',
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'member_stats006' => array(
				'field_label' => 'Left Arm',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'form_field' => 'text',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'member_stats007' => array(
				'field_label' => 'Right Arm',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'form_field' => 'text',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'member_stats008' => array(
				'field_label' => 'Bust / Chest',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'form_field' => 'text',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'member_stats009' => array(
				'field_label' => 'Waist',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'form_field' => 'text',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'member_stats010' => array(
				'field_label' => 'Hip Line / Bum',
				
				'display_position' => 'display-in-table-row',
				'default_appearance_in_table_fields' => 'show',
				'form_field' => 'text',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'member_stats011' => array(
				'field_label' => 'Left Thigh',
				
				'display_position' => 'display-in-table-row',
				'form_field' => 'text',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'member_stats012' => array(
				'field_label' => 'Right Thigh',
				
				'display_position' => 'display-in-table-row',
				'form_field' => 'text',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'member_stats013' => array(
				'field_label' => 'Calfs',
				
				'display_position' => 'display-in-table-row',
				'form_field' => 'text',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'member_stats014' => array(
				'field_label' => 'Ankle',
				
				'display_position' => 'display-in-table-row',
				'form_field' => 'text',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'member_stats015' => array(
				'field_label' => 'BMI',
				
				'display_position' => 'display-in-table-row',
				'form_field' => 'text',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'member_stats016' => array(
				'field_label' => 'Fat (%)',
				
				'display_position' => 'display-in-table-row',
				'form_field' => 'text',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'member_stats017' => array(
				'field_label' => 'Water (%)',
				
				'display_position' => 'display-in-table-row',
				'form_field' => 'text',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'member_stats018' => array(
				'field_label' => 'Visceral Fat',
				
				'display_position' => 'display-in-table-row',
				'form_field' => 'text',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'member_stats019' => array(
				'field_label' => 'Blood Pressure',
				
				'display_position' => 'display-in-table-row',
				'form_field' => 'text',
				'serial_number' => '',
				'class' => ' col-md-6 ',
			),
			'member_stats020' => array(
				'field_label' => 'Comment',
				
				'display_position' => 'display-in-table-row',
				'form_field' => 'text',
				'serial_number' => '',
			),
		);
		
		return $return;
	}
	
	if( function_exists( "get_hyella_development_mode" ) && get_hyella_development_mode() ){
		
		function database_table(){
			return array(
				'database_table001' => array(
					'field_label' => 'Table Name',
					'form_field' => 'text',
					'required_field' => 'no',
					
					//'class' => ' no-x-padding-1 ',
					'display_position' => 'display-in-table-row',
					'default_appearance_in_table_fields' => 'show',
					'serial_number' => '',
				),
				 
				'database_table002' => array(
					'field_label' => 'Table Label',
					'form_field' => 'text',
					
					'display_position' => 'display-in-table-row',
					'default_appearance_in_table_fields' => 'show',
					'serial_number' => '',
				),
				 
				'database_table003' => array(
					'field_label' => 'Clone Table Suffix',
					'form_field' => 'text',
					'required_field' => 'no',
					
					//'class' => ' no-x-padding-1 ',
					'display_position' => 'display-in-table-row',
					'default_appearance_in_table_fields' => 'show',
					'serial_number' => '',
				),
				'database_table004' => array(
					'field_label' => 'Data',
					'form_field' => 'textarea-unlimited',
					'required_field' => 'no',
					
					//'class' => ' no-x-padding-1 ',
					'display_position' => 'display-in-table-row',
					'default_appearance_in_table_fields' => 'show',
					'serial_number' => '',
				),
				'database_table005' => array(
					'field_label' => 'Table Package',
					'form_field' => 'text',
					
					'display_position' => 'display-in-table-row',
					'default_appearance_in_table_fields' => 'show',
					'serial_number' => '',
				),
				'database_table006' => array(
					'field_label' => 'Classification',
					'form_field' => 'select',
					'form_field_options' => 'get_table_classification',
					
					'display_position' => 'display-in-table-row',
					'default_appearance_in_table_fields' => 'show',
					'serial_number' => '',
				),
			);
		}

		function database_fields(){
			return array(
				'database_fields001' => array(
					'field_label' => 'Table name',
					
					'form_field' => 'calculated',
					
					'calculations' => array(
						'type' => 'record-details',
						'form_field' => 'text',
						'reference_table' => 'database_table',
						'reference_keys' => array( 'table_label' ),
						'variables' => array( array( 'database_fields001' ) ),
					),
					
					'attributes' => ' action="?action=database_table&todo=get_available_database_tables" ',
					'class' => ' select2 ',
					'required_field' => 'yes',
					
					'display_position' => 'display-in-table-row',
					'default_appearance_in_table_fields' => 'show',
					'serial_number' => 10,
				),
				'database_fields002' => array(
					'field_label' => 'Serial Number',
					'form_field' => 'number',
					
					'display_position' => 'display-in-table-row',
					'default_appearance_in_table_fields' => 'show',
					'serial_number' => 20,
				),/*
				  'database_fields003' => array(
					'field_label' => 'Field Type',
					'form_field' => 'text',
					'required_field' => 'no',
					
					//'class' => ' no-x-padding-1 ',
					'display_position' => 'display-in-table-row',
					'default_appearance_in_table_fields' => 'show',
					'serial_number' => '',
				),
				  'database_fields004' => array(
					'field_label' => 'Field Length',
					'form_field' => 'text',
					'required_field' => 'yes',
					
					'display_position' => 'display-in-table-row',
					'serial_number' => '',
				),*/
				  'database_fields005' => array(
					'field_label' => 'Field Label',
					'form_field' => 'text',
					'required_field' => 'yes',
					
					//'class' => ' no-x-padding-1 ',
					'display_position' => 'display-in-table-row',
					'default_appearance_in_table_fields' => 'show',
					'serial_number' => 30,
				),
				'database_fields019' => array(
					'field_label' => 'Display Field Label',
					'form_field' => 'textarea',
					
					'placeholder' => 'Optional Field Caption',
					'display_position' => 'display-in-table-row',
					'serial_number' => 40,
				),
				  
				'database_fields006' => array(
					'field_label' => 'Form Field Type',
					
					'form_field' => 'select',
					'form_field_options' => 'get_form_fields',
					'required_field' => 'yes',

					'default_appearance_in_table_fields' => 'show',
					'display_position' => 'display-in-table-row',
					'serial_number' => '',
				),
				'database_fields007' => array(
					'field_label' => 'Required Field',
					'form_field' => 'select',
					'form_field_options' => 'get_yes_no',
					
					//'class' => ' no-x-padding-1 ',
					'display_position' => 'display-in-table-row',
					'default_appearance_in_table_fields' => 'show',
					'serial_number' => '',
				),
				  'database_fields008' => array(
					'field_label' => 'Form Field Options',
					'form_field' => 'text',
					'placeholder' => 'For Select Form Field Type Only',
					
					'display_position' => 'display-in-table-row',
					'serial_number' => '',
				),
				'database_fields009' => array(
					'field_label' => 'Data',
					'form_field' => 'textarea-unlimited',
					'required_field' => 'no',
					
					//'class' => ' no-x-padding-1 ',
					'display_position' => 'do-not-display-in-table',
					'serial_number' => '',
				),
				  'database_fields010' => array(
					'field_label' => 'Objects',
					'form_field' => 'calculated',
					
					'placeholder' => 'For FIELD GROUP or HTML Form Field Type(s) Only',
					
					'calculations' => array(
						'type' => 'record-details',
						'form_field' => 'text',
						'multiple' => 1,
						'show_in_form' => 1,
						'reference_table' => 'database_objects',
						'reference_keys' => array( 'object_name' ),
						'variables' => array( array( 'database_fields010' ) ),
					),
					
					'attributes' => ' action="?action=database_objects&todo=get_select2" tags="true" ',
					'class' => ' select2 ',
					
					'display_position' => 'display-in-table-row',
					'serial_number' => '',
				),
				/*
				  'database_fields011' => array(
					'field_label' => 'Calculations Variables',
					'form_field' => 'text',
					'required_field' => 'no',
					
					//'class' => ' no-x-padding-1 ',
					'display_position' => 'display-in-table-row',
					'serial_number' => '',
				),*/
				  'database_fields013' => array(
					'field_label' => 'Attributes',
					'form_field' => 'text',
					'required_field' => 'no',
					
					'display_position' => 'display-in-table-row',
					'serial_number' => '',
				),
				  'database_fields014' => array(
					'field_label' => 'Class',
					'form_field' => 'text',
					'required_field' => 'no',
					
					'display_position' => 'display-in-table-row',
					'serial_number' => '',
				),
				  'database_fields016' => array(
					'field_label' => 'Appearance In Table Fields',
					'form_field' => 'select',
					'form_field_options' => 'get_field_appearance',
					
					'default_appearance_in_table_fields' => 'show',
					'display_position' => 'display-in-table-row',
					'serial_number' => '',
				),
				  'database_fields015' => array(
					'field_label' => 'Display position',
					'form_field' => 'select',
					'form_field_options' => 'get_field_display_options',
					
					//'class' => ' no-x-padding-1 ',
					'display_position' => 'display-in-table-row',
					'serial_number' => '',
				),
				  'database_fields018' => array(
					'field_label' => 'Acceptable Files Format',
					'form_field' => 'text',
					
					//'class' => ' no-x-padding-1 ',
					'display_position' => 'display-in-table-row',
					'serial_number' => '',
				),
				'database_fields017' => array(
					'field_label' => 'Group',
					'form_field' => 'text',
					
					'display_position' => 'display-in-table-row',
					'serial_number' => '',
				),
				
			);
		}
		
	}
	
	
	function notifications(){
		return array(
			'notifications001' => array(
				
				'field_label' => 'Title',
				'form_field' => 'text',
				'required_field' => 'yes',
				'placeholder' => 'Subject of Notification',
				
				'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => 4,
			),
			'notifications002' => array(
				
				'field_label' => 'Trigger Function',
				'form_field' => 'select',
				'required_field' => 'no',
				
				//'acceptable_files_format' => 'xls',
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'notifications003' => array(
				
				'field_label' => 'Target User',
				'form_field' => 'calculated',
				'required_field' => 'no',
				
				'class' => ' select2 ',
				//'class' => ' select2-multi-recipients ',
				//'form_field_options' => "get_users_email_addresses",
				'calculations' => array(
					'type' => 'record-details',
					'reference_table' => 'users',
					'reference_keys' => array( 'firstname', 'lastname' ),
					'form_field' => 'text',
					'variables' => array( array( 'notifications003' ) ),
					'show_in_form' => 1,
				),
				'attributes' => ' action="?action=users&todo=get_users_select2" ',
				
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'notifications004' => array(
				
				'field_label' => 'Type',
				'form_field' => 'text',
				'required_field' => 'no',
				
				//'default_appearance_in_table_fields' => 'show',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'notifications005' => array(
				
				'field_label' => 'Detailed Message',
				'form_field' => 'textarea-unlimited',
				'required_field' => 'no',
				
				'placeholder' => 'Message Content',
                //'default_appearance_in_table_fields' => 'show',
                
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
				'serial_number' => 5,
			),
			'notifications006' => array(
				
				'field_label' => 'Send Email',
				'form_field' => 'select',
				'required_field' => 'no',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'notifications007' => array(
				
				'field_label' => 'Recipients',
				'form_field' => 'calculated',
				'required_field' => 'yes',
				
				'class' => ' select2 ',
				//'class' => ' select2-multi-recipients ',
				//'form_field_options' => "get_users_email_addresses",
				'calculations' => array(
					'type' => 'record-details',
					'reference_table' => 'users',
					'reference_keys' => array( 'firstname', 'lastname' ),
					'form_field' => 'text',
					'variables' => array( array( 'notifications007' ) ),
					'multiple' => 1,
					'show_in_form' => 1,
				),
				'attributes' => ' action="?action=users&todo=get_users_select2" tags="true" ',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => 1,
			),
			'notifications008' => array(
				
				'field_label' => 'Generating Class',
				'form_field' => 'text',
				'required_field' => 'no',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'notifications009' => array(
				
				'field_label' => 'Generating Method',
				'form_field' => 'text',
				'required_field' => 'no',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
			'notifications010' => array(
				
				'field_label' => 'Status',
				'form_field' => 'select',
				'form_field_options' => 'get_notification_states',
				
                'default_appearance_in_table_fields' => 'show',
				'display_position' => 'display-in-table-row',
				'serial_number' => '',
			),
			'notifications011' => array(
				
				'field_label' => 'Data',
				'form_field' => 'textarea-unlimited',
				
				'display_position' => 'do-not-display-in-table',
				'serial_number' => '',
			),
		);
	}
	
?>