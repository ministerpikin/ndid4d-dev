<?php 
	function __object_fs_ivf_insemination_injection(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Insemination / Injection",
			),
			"fields" => array(
				
				"fields1" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"embryologist" => array(
									"field_label" => "Embryologist",
									"form_field" => "calculated",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "embryologist" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"witness" => array(
									"field_label" => "Witness",
									"form_field" => "calculated",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "witness" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								"time" => array(
									"field_label" => "Time",
									"form_field" => "time",
									"required_field" => "yes",
									
									"placeholder" => "HH:mm",
								),
								"inc_no" => array(
									"field_label" => "INC No.",
									"form_field" => "text",
								),
								"rig_no" => array(
									"field_label" => "Rig No.",
									"form_field" => "text",
								),
								
							),
						),
					),
				),
				
				"table1" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"clone" => 1,
					"rows" => array(
					
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 2,
								"class" => "sub-heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							"cells" => array(
								array(
									"id" => "ivf_method",
									"text" => "IVF Method",
									"form_field" => "select",
									"options" => "ivf:IVF;icsi:ICSI;imsi:IMSI;",
								),
								array(
									"id" => "ivf_no_eggs",
									"text" => "No. of Eggs",
									"form_field" => "number",
								),
								array(
									"id" => "ivf_hd",
									"text" => "H/D (Source)",
									"form_field" => "text",
								),
								array(
									"id" => "ivf_no_sperm",
									"text" => "No. of Sperm (ul)",
									"form_field" => "decimal",
								),
								array(
									"id" => "imsi_no_eggs1",
									"text" => "No. of Eggs - 1",
									"form_field" => "number",
								),
								array(
									"id" => "imsi_no_eggs2",
									"text" => "No. of Eggs - 2",
									"form_field" => "number",
								),
								array(
									"id" => "imsi_no_eggs3",
									"text" => "No. of Eggs - 3",
									"form_field" => "number",
								),
							),
						),
					),
				),
				
				"team" => array(
					"field_label" => "Team",
					"form_field" => "text",
				),
				"team" => array(
					"field_label" => "Team",
					"form_field" => "text",
				),
				"comment" => array(
					"field_label" => "Comment",
					"form_field" => "textarea",
				),
				
				
			),
		);
	}
	
	function __object_fs_ivf_insemination_injection_day1(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Fertilisation Check (Day 1)",
			),
			"fields" => array(
				
				"day1_fields" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"day1_setup_by" => array(
									"field_label" => "Day 1 Set-up by",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "day1_setup_by" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"embryologist" => array(
									"field_label" => "Embryologist",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "embryologist" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"witness" => array(
									"field_label" => "Witness",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "witness" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								"time" => array(
									"field_label" => "Time",
									"form_field" => "time",
									"required_field" => "yes",
									
									"placeholder" => "HH:mm",
								),
								
							),
						),
					),
				),
				
				"day1_table" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
								"readonly" => 1
							),
							//cells
							"cells" => array(
								array(
									"text" => "",
									"readonly" => 1,
								),
								array(
									"text" => "Fertilized Zygote",
									"readonly" => 1,
									"colspan" => 4,
								),
								array(
									"text" => "Non Fertilized Zygote",
									"readonly" => 1,
									"colspan" => 3,
								),
								array(
									"text" => "Others",
									"readonly" => 1,
								),
							),
						),
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 2,
								"class" => "sub-heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							"cells" => array(
								array(
									"id" => "lbl1",
									"text" => "",
									"rows_text" => array( "IVF", "ICSI" ),
								),
								array(
									"id" => "2pn",
									"text" => "2PN",
									"form_field" => "number",
								),
								array(
									"id" => "3pn",
									"text" => "3PN",
									"form_field" => "number",
								),
								array(
									"id" => "1pn",
									"text" => "1PN",
									"form_field" => "number",
								),
								array(
									"id" => "plus",
									"text" => "+",
									"form_field" => "number",
								),
								array(
									"id" => "mii",
									"text" => "MII",
									"form_field" => "number",
								),
								array(
									"id" => "mi",
									"text" => "MI",
									"form_field" => "number",
								),
								array(
									"id" => "gv",
									"text" => "GV",
									"form_field" => "number",
								),
								array(
									"id" => "others",
									"text" => "Others",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				"day1_comment" => array(
					"field_label" => "Comment",
					"form_field" => "textarea",
				),
				
				"day1_fields2" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"day1_setup_by" => array(
									"field_label" => "Sperm Binding (FTF)",
									"form_field" => "select",
									"form_field_options" => "get_ivf_sperm_binding_options",
								),
								
								"percent_survival" => array(
									"field_label" => "% Sperm Survival",
									"form_field" => "number",
								),
								
								"ftf_confirmed" => array(
									"field_label" => "FTF Confirmed",
									"form_field" => "text",
								),
								
							),
						),
					),
				),
				
			),
		);
	}
	
	function __object_fs_ivf_insemination_injection_day23(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Assessment of Embryo Quality",
			),
			"fields" => array(
				
				"day1_fields" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"day2_setup_by" => array(
									"field_label" => "Set-up by",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "day2_setup_by" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"date" => array(
									"field_label" => "Date:",
									"form_field" => "date-5time",
								),
								
								"embryologist" => array(
									"field_label" => "Embryologist",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "embryologist" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"witness" => array(
									"field_label" => "Witness",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "witness" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								"inc_no" => array(
									"field_label" => "INC No:",
									"form_field" => "text",
								),
								
							),
						),
					),
				),
				
				"day1_fields2" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"day2_aha_by" => array(
									"field_label" => "AHA By",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "day2_aha_by" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"dtt" => array(
									"field_label" => "Date:",
									"form_field" => "date-5time",
								),
								
								"witness2" => array(
									"field_label" => "Witness",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "witness" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								"emb_no" => array(
									"field_label" => "No of Embrayo:",
									"form_field" => "text",
								),
								
							),
						),
					),
				),
				
				"day2_table" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"clone" => 1,
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 5,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "embryo_no",
									"text" => "Embryo / Drop No",
									"rows_text" => array( "IMSI Score", "Cell No", "Even / Uneven", "Grade", "ET/CR/D/R" ),
								),
								array(
									"id" => "1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "9",
									"text" => "9",
									"form_field" => "text",
								),
								array(
									"id" => "10",
									"text" => "10",
									"form_field" => "text",
								),
								array(
									"id" => "11",
									"text" => "11",
									"form_field" => "text",
								),
								array(
									"id" => "12",
									"text" => "12",
									"form_field" => "text",
								),
								array(
									"id" => "13",
									"text" => "13",
									"form_field" => "text",
								),
								array(
									"id" => "14",
									"text" => "14",
									"form_field" => "text",
								),
								array(
									"id" => "15",
									"text" => "15",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
			),
		);
	}
	
	function __object_fs_ivf_grade_quality(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Grade Quality",
			),
			"fields" => array(
				
				"day1_fields" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"aha_by" => array(
									"field_label" => "AHA by",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "aha_by" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"date" => array(
									"field_label" => "Date:",
									"form_field" => "date-5time",
								),
								
								/*"time" => array(
									"field_label" => "Time:",
									"form_field" => "time",
									"placeholder" => "HH:mm",
								),*/
								
								"witness" => array(
									"field_label" => "Witness",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "witness" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
							),
						),
					),
				),
				
				"grade_quality_table" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"clone" => 1,
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 2,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "embryo_no",
									"text" => "Embryo / Drop No",
									"rows_text" => array( "AHA", "ET/CR/D/R" ),
								),
								array(
									"id" => "1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "9",
									"text" => "9",
									"form_field" => "text",
								),
								array(
									"id" => "10",
									"text" => "10",
									"form_field" => "text",
								),
								array(
									"id" => "11",
									"text" => "11",
									"form_field" => "text",
								),
								array(
									"id" => "12",
									"text" => "12",
									"form_field" => "text",
								),
								array(
									"id" => "13",
									"text" => "13",
									"form_field" => "text",
								),
								array(
									"id" => "14",
									"text" => "14",
									"form_field" => "text",
								),
								array(
									"id" => "15",
									"text" => "15",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
			),
		);
	}
	
	function __object_fs_ivf_embryo_transfer(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Embryo Transfer",
			),
			"fields" => array(
				
				"day1_fields" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"date" => array(
									"field_label" => "Date of Transfer:",
									"form_field" => "date-5",
								),
								
								"time" => array(
									"field_label" => "Time:",
									"form_field" => "time",
									"placeholder" => "HH:mm",
								),
								
								"dr" => array(
									"field_label" => "Dr",
									"form_field" => "calculated",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "dr" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"embryologist" => array(
									"field_label" => "Embryologist",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "embryologist" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
							),
						),
					),
				),
				
				"day2_fields" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"date" => array(
									"field_label" => "Type",
									"form_field" => "select",
									"form_field_options" => "get_ivf_embryo_transfer_type_options",
								),
								
								"time" => array(
									"field_label" => "No Transferred",
									"form_field" => "number",
								),
								
								"grade" => array(
									"field_label" => "Embryo Grade",
									"form_field" => "text",
								),
								
							),
						),
					),
				),
				
				"day3_fields" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"difficulty" => array(
									"field_label" => "Ease of Transfer",
									"form_field" => "select",
									"form_field_options" => "get_ivf_embryo_transfer_ease_options",
								),
								
								"transferred_to" => array(
									"field_label" => "Transferred To:",
									"form_field" => "select",
									"form_field_options" => "get_ivf_embryo_transfer_to_options",
								),
								
								"surrogate_no" => array(
									"field_label" => "Surrogate No:",
									"form_field" => "text",
								),
								
							),
						),
					),
				),
				
				"comment" => array(
					"field_label" => "Comment",
					"form_field" => "textarea",
				),
				
				"embryo_transfer_table" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 3,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "sn",
									"text" => "S/No",
									"rows_text" => array( "1", "2", "3" ),
								),
								array(
									"id" => "stage",
									"text" => "Stage",
									"form_field" => "text",
								),
								array(
									"id" => "grade",
									"text" => "Grade",
									"form_field" => "text",
								),
								array(
									"id" => "ah",
									"text" => "AH",
									"form_field" => "text",
								),
								array(
									"id" => "icsi",
									"text" => "ICSI",
									"form_field" => "text",
								),
								array(
									"id" => "ivf",
									"text" => "IVF",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				"f324" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"gang" => array(
									"field_label" => "",
									"form_field" => "checkbox",
									"form_field_options" => "get_uet_tet",
								),
								
								"getanch" => array(
									"field_label" => "Analgesia",
									"form_field" => "checkbox",
									"form_field_options" => "get_analgesia_types",
								),
								
							),
						),
					),
				),
				
			),

		);
	}
	
	function __object_fs_ivf_embryo_freezing(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Embryo Freezing",
			),
			"fields" => array(
				
				"day1_fields" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"date" => array(
									"field_label" => "Date",
									"form_field" => "date-5",
								),
								
								"page" => array(
									"field_label" => "Page",
									"form_field" => "text",
								),
								
								"bank" => array(
									"field_label" => "Bank",
									"form_field" => "text",
								),
								
								"canister" => array(
									"field_label" => "Canister",
									"form_field" => "text",
								),
								
								"cane" => array(
									"field_label" => "Cane",
									"form_field" => "text",
								),
								
							),
						),
					),
				),
				
				"embryo_freezing_table" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 6,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "straw",
									"text" => "Straw",
									"form_field" => "text",
								),
								array(
									"id" => "stage",
									"text" => "Stage",
									"form_field" => "text",
								),
								array(
									"id" => "grade",
									"text" => "Grade",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
			),
		);
	}
	
	function __object_fs_ivf_excess_embryo(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Excess Embryo",
			),
			"fields" => array(
				
				"day1_fields" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"no_frozen" => array(
									"field_label" => "No. Frozen",
									"form_field" => "number",
								),
								
								"no_discarded" => array(
									"field_label" => "No. Discarded",
									"form_field" => "number",
								),
								
								"embryologist" => array(
									"field_label" => "Embryologist",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "embryologist" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"witness" => array(
									"field_label" => "Witness",
									"form_field" => "calculated",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "witness" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
							),
						),
					),
				),
				
				"comment" => array(
					"field_label" => "Comment",
					"form_field" => "textarea",
				),
				
				
			),
		);
	}
	
	function __object_fs_ivf_assisted_hatching(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Assisted Hatching",
			),
			"fields" => array(
				
				"aha1" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"date" => array(
									"field_label" => "Date",
									"form_field" => "date-5",
								),
								
								"time" => array(
									"field_label" => "Time",
									"form_field" => "time",
									"placeholder" => "HH:mm",
								),
								
								"number_of_embryo" => array(
									"field_label" => "No of Embryo",
									"form_field" => "number",
								),
								
							),
						),
						
						array(
							"cells" => array(
								
								"done_by" => array(
									"field_label" => "Done By",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "done_by" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"witness" => array(
									"field_label" => "Witness",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "witness" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
							),
						),
					),
				),
				
				"comment" => array(
					"field_label" => "Comment",
					"form_field" => "textarea",
				),
				
				
			),
		);
	}
	
	function __object_fs_ivf_egg_collection(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Egg Collection",
			),
			"fields" => array(
				
				"day1_fields" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"date" => array(
									"field_label" => "Date:",
									"form_field" => "date-5",
								),
								
								"time" => array(
									"field_label" => "Time:",
									"form_field" => "time",
									"placeholder" => "HH:mm",
								),
								
								"method" => array(
									"field_label" => "Method:",
									"form_field" => "select",
									"form_field_options" => "get_ivf_egg_collection_method_options",
								),
								
							),
						),
						
						array(
							"cells" => array(
								
								"setup_by" => array(
									"field_label" => "Set-up By",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "setup_by" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"embryologist" => array(
									"field_label" => "Embryologist",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "embryologist" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"ocr_dr" => array(
									"field_label" => "OCR Dr",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "ocr_dr" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
							),
						),
						
					),
				),
				
				"egg_collection_table" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 2,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "lbl",
									"text" => "",
									"rows_text" => array( "Left", "Right" ),
								),
								array(
									"id" => "m",
									"text" => "M",
									"form_field" => "number",
								),
								array(
									"id" => "bl",
									"text" => "BL",
									"form_field" => "number",
								),
								array(
									"id" => "im",
									"text" => "IM",
									"form_field" => "number",
								),
								array(
									"id" => "PM",
									"text" => "BL",
									"form_field" => "number",
								),
								array(
									"id" => "no_kept",
									"text" => "No. Kept",
									"form_field" => "number",
								),
								array(
									"id" => "no_donated",
									"text" => "No. Donated",
									"form_field" => "number",
								),
								array(
									"id" => "no_follicle",
									"text" => "No. of Follicle Aspirated",
									"form_field" => "number",
								),
							),
						),
					),
				),
				
				
			),
		);
	}
	
	function __object_fs_ivf_sperm_preparation(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Sperm Preparation",
			),
			"fields" => array(
				
				"day1_fields" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"date" => array(
									"field_label" => "Date:",
									"form_field" => "date-5",
								),
								
								"embryologist" => array(
									"field_label" => "Embryologist",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "embryologist" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"witness" => array(
									"field_label" => "Witness (Pre-prep)",
									"form_field" => "calculated",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "witness" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"witness2" => array(
									"field_label" => "Witness (Post-prep)",
									"form_field" => "calculated",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "witness2" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
							),
						),
						
						array(
							"cells" => array(
								
								"sample_type" => array(
									"field_label" => "Type of Sample",
									"form_field" => "select",
									"form_field_options" => "get_ivf_sample_type_options",
								),
								
								"patient_type" => array(
									"field_label" => "Patient Type",
									"form_field" => "select",
									"form_field_options" => "get_ivf_patient_type_options",
								),
								
								"source" => array(
									"field_label" => "Source",
									"form_field" => "select",
									"form_field_options" => "get_ivf_sperm_source_options",
								),
								
								"method" => array(
									"field_label" => "Method",
									"form_field" => "select",
									"form_field_options" => "get_ivf_sperm_preparation_method_options",
								),
								
							),
						),
						
						array(
							"cells" => array(
								
								"donor_code" => array(
									"field_label" => "Donor Code",
									"form_field" => "text",
								),
								
								"lot_no" => array(
									"field_label" => "Lot No",
									"form_field" => "text",
								),
								
								"sample_date" => array(
									"field_label" => "Sample Date",
									"form_field" => "date-5",
								),
								
								"sp_bank" => array(
									"field_label" => "Sp Bank Updated",
									"form_field" => "text",
								),
								
							),
						),
						
						array(
							"cells" => array(
								
								"abstinence" => array(
									"field_label" => "Abstinence",
									"form_field" => "text",
								),
								
								"prod_time" => array(
									"field_label" => "Prod. Time",
									"form_field" => "time",
									"placeholder" => "HH:mm",
								),
								
								"analysis_time" => array(
									"field_label" => "Analysis Time",
									"form_field" => "time",
									"placeholder" => "HH:mm",
								),
								
								"viscosity" => array(
									"field_label" => "Viscosity",
									"form_field" => "select",
									"form_field_options" => "get_ivf_viscosity_options",
								),
								
							),
						),
						
					),
				),
				
				
				"sperm_prep_table" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 4,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "lbl",
									"text" => "",
									"rows_text" => array( "Initial", "Prep 1", "2nd", "Prep 2" ),
								),
								array(
									"id" => "vol",
									"text" => "Vol (ml)",
									"form_field" => "number",
								),
								array(
									"id" => "cells",
									"text" => "Cells",
									"form_field" => "number",
								),
								array(
									"id" => "density",
									"text" => "Density",
									"form_field" => "number",
								),
								array(
									"id" => "motility",
									"text" => "Motility (%)",
									"form_field" => "number",
								),
								array(
									"id" => "prog",
									"text" => "Prog",
									"form_field" => "text",
								),
								array(
									"id" => "abnormals",
									"text" => "Abnormals (%)",
									"form_field" => "number",
								),
								array(
									"id" => "mar",
									"text" => "Mar",
									"form_field" => "text",
								),
								array(
									"id" => "aggl",
									"text" => "Aggl",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				
				"comment" => array(
					"field_label" => "Comment",
					"form_field" => "textarea",
				),
				
			),
		);
	}
	
	function __object_fs_ivf_icsi_pre_injection(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "ICSI Pre-Injection Dissection",
			),
			"fields" => array(
				"day1_fields" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"date" => array(
									"field_label" => "Date:",
									"form_field" => "date-5",
								),
								
								"time" => array(
									"field_label" => "Time:",
									"form_field" => "time",
									"placeholder" => "HH:mm",
								),
								
								"embryologist" => array(
									"field_label" => "Embryologist",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "embryologist" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
							),
						),
						
					),
				),
				
				"pre_injection_table" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "lbl",
									"text" => "",
									"rows_text" => array( "No" ),
								),
								array(
									"id" => "mii",
									"text" => "MII",
									"form_field" => "number",
								),
								array(
									"id" => "mi",
									"text" => "MI",
									"form_field" => "number",
								),
								array(
									"id" => "gv",
									"text" => "GV",
									"form_field" => "number",
								),
								array(
									"id" => "frag",
									"text" => "FRAG",
									"form_field" => "number",
								),
								array(
									"id" => "abn",
									"text" => "ABN",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				
				"comment" => array(
					"field_label" => "Comment",
					"form_field" => "textarea",
				),
				
			),
		);
	}
	
	function __object_fs_ivf_ovum_recipient_egg_collection_record(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Ovum Recipient - Egg Donated Record",
			),
			"fields" => array(
				
				"egg_collection1" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"date" => array(
									"field_label" => "Date:",
									"form_field" => "date-5",
								),
								
								"time" => array(
									"field_label" => "Time:",
									"form_field" => "time",
									"placeholder" => "HH:mm",
								),
								
								"method" => array(
									"field_label" => "Method:",
									"form_field" => "select",
									"form_field_options" => "get_ivf_egg_collection_method_options",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"flush_batch" => array(
									"field_label" => "Flush Batch",
									"form_field" => "text",
								),
								
								"embryologist" => array(
									"field_label" => "Embryologist",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "embryologist" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"ocr_dr" => array(
									"field_label" => "Dr",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "ocr_dr" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
							),
						),
					),
				),
				
				"egg_collection2" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "post_mat",
									"text" => "Post Mat.",
									"form_field" => "number",
								),
								array(
									"id" => "mature",
									"text" => "Mature",
									"form_field" => "number",
								),
								array(
									"id" => "borderline",
									"text" => "Borderline",
									"form_field" => "number",
								),
								array(
									"id" => "immature",
									"text" => "Immature",
									"form_field" => "number",
								),
								array(
									"id" => "total_ec",
									"text" => "Total",
									"form_field" => "number",
								),
							),
						),
					),
				),
				
				"comment" => array(
					"field_label" => "Comment",
					"form_field" => "textarea",
				),
				
			),
		);
	}
	
	function __object_fs_ivf_record_of_cleavage_stage_biopsy_table(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Record of Cleavage Stage Biopsy Table",
			),
			"fields" => array(
				
				"biopsy_table" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"clone" => 1,
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 10,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "emb_no",
									"text" => "Emb No.",
									"form_field" => "number",
								),
								array(
									"id" => "cell_no_before_biopsy",
									"text" => "Cell No. Before Biopsy",
									"form_field" => "number",
								),
								array(
									"id" => "cell_no_after_biopsy",
									"text" => "Cell No. After Biopsy",
									"form_field" => "number",
								),
								array(
									"id" => "no_of_cells_removed",
									"text" => "No. of Cells Removed",
									"form_field" => "number",
								),
								array(
									"id" => "comments_on_biopsy",
									"text" => "Comments on Biopsy",
									"form_field" => "text",
								),
								array(
									"id" => "d4_embrto_quality",
									"text" => "D4 Embryo Quality",
									"form_field" => "text",
								),
								array(
									"id" => "d5_embrto_quality",
									"text" => "D5 Embryo Quality",
									"form_field" => "text",
								),
								array(
									"id" => "d6_embrto_quality",
									"text" => "D6 Embryo Quality",
									"form_field" => "text",
								),
								array(
									"id" => "embryo_fate",
									"text" => "Embryo Fate",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				"comment" => array(
					"field_label" => "Comment",
					"form_field" => "textarea",
				),
				
			),
		);
	}

	function __object_fs_ivf_treatment_simulation_chart(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Record of Cleavage Stage Biopsy Table",
			),
			"fields" => array(
				
				"biopsy_table" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"clone" => 1,
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"row_text_style" => "width: 10%;",
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "ivf",
									"text" => "IVF",
									"form_field" => "text",
								),
								array(
									"id" => "isci",
									"text" => "ISCI",
									"form_field" => "text",
								),
								array(
									"id" => "er",
									"text" => "ER",
									"form_field" => "text",
								),
								array(
									"id" => "iui",
									"text" => "IUI",
									"form_field" => "text",
								),
								array(
									"id" => "ti",
									"text" => "TI",
									"form_field" => "text",
								),
								array(
									"id" => "fet",
									"text" => "FET",
									"form_field" => "text",
								),
								array(
									"id" => "ss",
									"text" => "S*",
									"form_field" => "text",
								),
								array(
									"id" => "es",
									"text" => "E/S",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				/*"s22x" => array(
					//row 1
					"form_field" => "capture_multiple",
					// "div_class" => "table-scrollable",
					"table_style" => "margin-bottom:unset !important;",
					"total" => 1,
					"title" => "",
					"fields" => array(
						array(
							"id" => "lr",
							"text" => "L/R",
							"form_field" => "select",
							"options" => "l:L;r:R;",
						),
						array(
							"id" => "22",
							"text" => "22",
							"form_field" => "number",
						),
						array(
							"id" => "20_21",
							"text" => "20-21",
							"form_field" => "number",
						),
						array(
							"id" => "18_19",
							"text" => "18-19",
							"form_field" => "number",
						),
						array(
							"id" => "16_17",
							"text" => "16-17",
							"form_field" => "number",
						),
						array(
							"id" => "14_15",
							"text" => "14-15",
							"form_field" => "number",
						),
						array(
							"id" => "12_13",
							"text" => "12-13",
							"form_field" => "number",
						),
						array(
							"id" => "10_11",
							"text" => "10-11",
							"form_field" => "number",
						),
						array(
							"id" => "_10",
							"text" => "<10",
							"form_field" => "number",
						),
						array(
							"id" => "total",
							"text" => "Total",
							"form_field" => "number",
						),
						array(
							"id" => "_endo",
							"text" => "Endo",
							"form_field" => "number",
						),
						array(
							"id" => "e2_level",
							"text" => "E2 LEVEL",
							"form_field" => "number",
						),
						array(
							"id" => "cyStDay",
							"text" => "CY/ST DAY",
							"form_field" => "number",
						),
						array(
							"id" => "gNRHaANT",
							"text" => "GNRHa/ANT",
							"form_field" => "number",
						),
						array(
							"id" => "fshHmg",
							"text" => "FSH/HMG",
							"form_field" => "number",
						),
						array(
							"id" => "group",
							"text" => "Group",
							"form_field" => "select",
							"options" => "1:1;2:2;3:3;4:4;5:5;6:6;7:7;8:8;9:9;10:10;",
						),
					),
				),*/
				
				/*"dtt" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"row_text_style" => "width: 10%;",
								"num_of_rows" => 1,
								"skip_row_header" => 1,
								"class" => "heading-row",
							),
							//cells
							"cells" => array(
								array(
									"id" => "sn",
									"text" => "",
									"rows_text" => array( "Date" ),
								),
								array(
									"id" => "r1",
									"text" => "",
									"form_field" => "date-5",
								),
								array(
									"id" => "r2",
									"text" => "",
									"form_field" => "date-5",
								),
								array(
									"id" => "r3",
									"text" => "",
									"form_field" => "date-5",
								),
								array(
									"id" => "r4",
									"text" => "",
									"form_field" => "date-5",
								),
								array(
									"id" => "r5",
									"text" => "",
									"form_field" => "date-5",
								),
								array(
									"id" => "r6",
									"text" => "",
									"form_field" => "date-5",
								),
							),
						),
					),
				),*/
				
				"dtt" => array(
					//row 1
					"form_field" => "table",
					"table_style" => "margin-bottom:unset !important;",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"skip_row_header" => 1,
								"class" => "heading-row",
								"row_text_style" => "width: 10%;",
								// "field_style" => "padding:unset !important;width: 30px;",
								// "td_cell_style" => "padding:11px 2px 2px 2px !important;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "sn",
									"text" => "",
									"rows_text" => array( "Date" ),
								),
								array(
									"id" => "r1",
									"text" => "",
									"attributes" => ' style="margin-right: -9em !important;" ',
									"td_cell_style" => ' width: 20%;',
									"form_field" => "date-5",
								),
								array(
									"id" => "r2",
									"text" => "",
									"attributes" => ' style="margin-right: -9em !important;" ',
									"td_cell_style" => ' width: 20%;',
									"form_field" => "date-5",
								),
								array(
									"id" => "r3",
									"text" => "",
									"attributes" => ' style="margin-right: -9em !important;" ',
									"td_cell_style" => ' width: 20%;',
									"form_field" => "date-5",
								),
								array(
									"id" => "r4",
									"text" => "",
									"attributes" => ' style="margin-right: -9em !important;" ',
									"td_cell_style" => ' width: 20%;',
									"form_field" => "date-5",
								),
								array(
									"id" => "r5",
									"text" => "",
									"attributes" => ' style="margin-right: -9em !important;" ',
									"td_cell_style" => ' width: 20%;',
									"form_field" => "date-5",
								),
								array(
									"id" => "r6",
									"text" => "",
									"attributes" => ' style="margin-right: -9em !important;" ',
									"td_cell_style" => ' width: 20%;',
									"form_field" => "date-5",
								),
								array(
									"id" => "r7",
									"text" => "",
									"attributes" => ' style="margin-right: -9em !important;" ',
									"td_cell_style" => ' width: 20%;',
									"form_field" => "date-5",
								),
								array(
									"id" => "r8",
									"text" => "",
									"attributes" => ' style="margin-right: -9em !important;" ',
									"td_cell_style" => ' width: 20%;',
									"form_field" => "date-5",
								),
								array(
									"id" => "r9",
									"text" => "",
									"attributes" => ' style="margin-right: -9em !important;" ',
									"td_cell_style" => ' width: 20%;',
									"form_field" => "date-5",
								),
								array(
									"id" => "r10",
									"text" => "",
									"attributes" => ' style="margin-right: -9em !important;" ',
									"td_cell_style" => ' width: 20%;',
									"form_field" => "date-5",
								),
								array(
									"id" => "r11",
									"text" => "",
									"attributes" => ' style="margin-right: -9em !important;" ',
									"td_cell_style" => ' width: 20%;',
									"form_field" => "date-5",
								),
								array(
									"id" => "r12",
									"text" => "",
									"attributes" => ' style="margin-right: -9em !important;" ',
									"td_cell_style" => ' width: 20%;',
									"form_field" => "date-5",
								),
								array(
									"id" => "r13",
									"text" => "",
									"attributes" => ' style="margin-right: -9em !important;" ',
									"td_cell_style" => ' width: 20%;',
									"form_field" => "date-5",
								),
								array(
									"id" => "r14",
									"text" => "",
									"attributes" => ' style="margin-right: -9em !important;" ',
									"td_cell_style" => ' width: 20%;',
									"form_field" => "date-5",
								),
								array(
									"id" => "r15",
									"text" => "",
									"attributes" => ' style="margin-right: -9em !important;" ',
									"td_cell_style" => ' width: 20%;',
									"form_field" => "date-5",
								),
								array(
									"id" => "r16",
									"text" => "",
									"attributes" => ' style="margin-right: -9em !important;" ',
									"td_cell_style" => ' width: 20%;',
									"form_field" => "date-5",
								),
							),
						),
					),
				),
				
				"s22" => array(
					//row 1
					"form_field" => "table",
					// "div_class" => "table-scrollable",
					"table_style" => "margin-bottom:unset !important;",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 9,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
								"row_text_style" => "width: 10%;",
								"field_style" => "padding:unset !important;",
								"td_cell_style" => "padding:unset !important;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "sn",
									"text" => "",
									"rows_text" => array( "Total", ">22", "20-21", "18-19", "16-17", "14-15", "12-13", "10-11", "<10" ),
								),
								array(
									"id" => "r1",
									"text" => "R",
									"form_field" => "text",
								),
								array(
									"id" => "l1",
									"text" => "L",
									"form_field" => "text",
								),
								array(
									"id" => "r2",
									"text" => "R",
									"form_field" => "text",
								),
								array(
									"id" => "l2",
									"text" => "L",
									"form_field" => "text",
								),
								array(
									"id" => "r3",
									"text" => "R",
									"form_field" => "text",
								),
								array(
									"id" => "l3",
									"text" => "L",
									"form_field" => "text",
								),
								array(
									"id" => "r4",
									"text" => "R",
									"form_field" => "text",
								),
								array(
									"id" => "l4",
									"text" => "L",
									"form_field" => "text",
								),
								array(
									"id" => "r5",
									"text" => "R",
									"form_field" => "text",
								),
								array(
									"id" => "l5",
									"text" => "L",
									"form_field" => "text",
								),
								array(
									"id" => "r6",
									"text" => "R",
									"form_field" => "text",
								),
								array(
									"id" => "l6",
									"text" => "L",
									"form_field" => "text",
								),
								array(
									"id" => "r7",
									"text" => "R",
									"form_field" => "text",
								),
								array(
									"id" => "l7",
									"text" => "L",
									"form_field" => "text",
								),
								array(
									"id" => "r8",
									"text" => "R",
									"form_field" => "text",
								),
								array(
									"id" => "l8",
									"text" => "L",
									"form_field" => "text",
								),
								array(
									"id" => "r9",
									"text" => "R",
									"form_field" => "text",
								),
								array(
									"id" => "l9",
									"text" => "L",
									"form_field" => "text",
								),
								array(
									"id" => "r10",
									"text" => "R",
									"form_field" => "text",
								),
								array(
									"id" => "l10",
									"text" => "L",
									"form_field" => "text",
								),
								array(
									"id" => "r11",
									"text" => "R",
									"form_field" => "text",
								),
								array(
									"id" => "l11",
									"text" => "L",
									"form_field" => "text",
								),
								array(
									"id" => "r12",
									"text" => "R",
									"form_field" => "text",
								),
								array(
									"id" => "l12",
									"text" => "L",
									"form_field" => "text",
								),
								array(
									"id" => "r13",
									"text" => "R",
									"form_field" => "text",
								),
								array(
									"id" => "l13",
									"text" => "L",
									"form_field" => "text",
								),
								array(
									"id" => "r14",
									"text" => "R",
									"form_field" => "text",
								),
								array(
									"id" => "l14",
									"text" => "L",
									"form_field" => "text",
								),
								array(
									"id" => "r15",
									"text" => "R",
									"form_field" => "text",
								),
								array(
									"id" => "l15",
									"text" => "L",
									"form_field" => "text",
								),
								array(
									"id" => "r16",
									"text" => "R",
									"form_field" => "text",
								),
								array(
									"id" => "l16",
									"text" => "L",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				"s23" => array(
					//row 1
					"form_field" => "table",
					"table_style" => "margin-bottom:unset !important;",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 2,
								"skip_row_header" => 1,
								"class" => "heading-row",
								"row_text_style" => "width: 10%;",
								// "field_style" => "padding:unset !important;width: 30px;",
								// "td_cell_style" => "padding:11px 2px 2px 2px !important;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "sn",
									"text" => "",
									"rows_text" => array( "ENDO", "E2 LEVEL" ),
								),
								array(
									"id" => "r1",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r2",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r3",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r4",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r5",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r6",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r7",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r8",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r9",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r10",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r11",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r12",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r13",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r14",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r15",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r16",
									"text" => "",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				"s24" => array(
					//row 1
					"form_field" => "table",
					"table_style" => "margin-bottom:unset !important;",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
								"row_text_style" => "width: 10%;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "sn",
									"text" => "",
									"rows_text" => array( "CY/ST DAY" ),
								),
								array(
									"id" => "r1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "r2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "r3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "r4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "r5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "r6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "r7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "r8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "r9",
									"text" => "9",
									"form_field" => "text",
								),
								array(
									"id" => "r10",
									"text" => "10",
									"form_field" => "text",
								),
								array(
									"id" => "r11",
									"text" => "11",
									"form_field" => "text",
								),
								array(
									"id" => "r12",
									"text" => "12",
									"form_field" => "text",
								),
								array(
									"id" => "r13",
									"text" => "13",
									"form_field" => "text",
								),
								array(
									"id" => "r14",
									"text" => "14",
									"form_field" => "text",
								),
								array(
									"id" => "r15",
									"text" => "15",
									"form_field" => "text",
								),
								array(
									"id" => "r16",
									"text" => "16",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				"s25" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 2,
								"skip_row_header" => 1,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
								"row_text_style" => "width: 10%;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "sn",
									"text" => "",
									"rows_text" => array( "GNRHa/ANT", "FSH/HMG" ),
								),
								array(
									"id" => "r1",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r2",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r3",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r4",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r5",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r6",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r7",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r8",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r9",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r10",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r11",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r12",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r13",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r14",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r15",
									"text" => "",
									"form_field" => "text",
								),
								array(
									"id" => "r16",
									"text" => "",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				"last" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"clone" => 1,
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "trigger",
									"text" => "Trigger",
									"form_field" => "checkbox",
									"options" => "hcg:HCG;buserelin:Buserelin;",
								),
								array(
									"id" => "rem",
									"text" => "Remark",
									"form_field" => "textarea",
								),
							),
						),
					),
				),
				
				"ffst" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"row_text_style" => "width: 10%;",
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "tpp",
									"text" => "TELEPHONE (s)",
									"form_field" => "text",
								),
								array(
									"id" => "age",
									"text" => "AGE",
									"form_field" => "date-5time",
								),
								array(
									"id" => "scnn",
									"text" => "CYCLE NO.",
									"form_field" => "text",
								),
								array(
									"id" => "indii",
									"text" => "INDICATOR",
									"form_field" => "text",
								),
							),
						),
					),
				),

				"hrmnsl" => array(
					//row 1
					"form_field" => "table",
					"title" => "Hormonals",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"row_text_style" => "width: 10%;",
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "dth",
									"text" => "Date",
									"form_field" => "date-5time",
								),
								array(
									"id" => "ffh",
									"text" => "FSH",
									"form_field" => "text",
								),
								array(
									"id" => "llh",
									"text" => "LH",
									"form_field" => "text",
								),
								array(
									"id" => "prll",
									"text" => "PROL",
									"form_field" => "text",
								),
								array(
									"id" => "ahhm",
									"text" => "AMH",
									"form_field" => "text",
								),
							),
						),
					),
				),

				"sfa" => array(
					//row 1
					"form_field" => "table",
					"title" => "SFA",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"row_text_style" => "width: 10%;",
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "cont",
									"text" => "COUNT",
									"form_field" => "text",
								),
								array(
									"id" => "mttly",
									"text" => "MOTILITY",
									"form_field" => "text",
								),
								array(
									"id" => "mrplgy",
									"text" => "MORPHOLOGY",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				"sr" => array(
					//row 1
					"form_field" => "table",
					"title" => "SEROLOGY",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"row_text_style" => "width: 10%;",
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "hiv",
									"text" => "HIV",
									"form_field" => "text",
								),
								array(
									"id" => "hpb",
									"text" => "HEP B",
									"form_field" => "text",
								),
								array(
									"id" => "hpbc",
									"text" => "HEP C",
									"form_field" => "text",
								),
								array(
									"id" => "vdrl",
									"text" => "VDRL",
									"form_field" => "text",
								),
								array(
									"id" => "chlamydia",
									"text" => "CHLAMYDIA",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				"pihis" => array(
					//row 1
					"form_field" => "table",
					"title" => "PREVIOUS IVF HISTORY",
					"clone" => 1,
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"row_text_style" => "width: 10%;",
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "myy",
									"text" => "MONTH/YEAR",
									"form_field" => "text",
								),
								array(
									"id" => "puu",
									"text" => "PROCTOL USED",
									"form_field" => "text",
								),
								array(
									"id" => "ecc",
									"text" => "EGGS COLLECTED",
									"form_field" => "text",
								),
								array(
									"id" => "nff",
									"text" => "No. FERTILIZED",
									"form_field" => "text",
								),
								array(
									"id" => "ett",
									"text" => "EMBRYO TRANSFERRED",
									"form_field" => "text",
								),
								array(
									"id" => "otc",
									"text" => "OUTCOME",
									"form_field" => "text",
								),
								array(
									"id" => "frzz",
									"text" => "FROZEN",
									"form_field" => "text",
								),
								array(
									"id" => "rks",
									"text" => "REMARKS",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				"spt" => array(
					//row 1
					"form_field" => "table",
					"title" => "SIMULATION PROTOCOL (CURENT CYCLE)",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"row_text_style" => "width: 10%;",
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "amyy",
									"text" => "MONTH/YEAR",
									"form_field" => "text",
								),
								array(
									"id" => "almp",
									"text" => "LMP",
									"form_field" => "text",
								),
								array(
									"id" => "asdvc",
									"text" => "LP/SP/ANTAGONIST",
									"form_field" => "text",
								),
								array(
									"id" => "dtvv",
									"text" => "SUPREFACT",
									"form_field" => "text",
								),
								array(
									"id" => "zoladex",
									"text" => "ZOLADEX",
									"form_field" => "text",
								),
								array(
									"id" => "pxfsh",
									"text" => "FSH",
									"form_field" => "text",
								),
								array(
									"id" => "amh",
									"text" => "HMG",
									"form_field" => "text",
								),
								array(
									"id" => "rks",
									"text" => "REMARKS",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				"ott" => array(
					//row 1
					"form_field" => "table",
					"title" => "OVULATORY TRIGGER",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"row_text_style" => "width: 10%;",
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "ohcg",
									"text" => "HCG",
									"form_field" => "text",
								),
								array(
									"id" => "cgnr",
									"text" => "GnRHa",
									"form_field" => "text",
								),
								array(
									"id" => "rrlh",
									"text" => "RLH",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				"falll" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"row_text_style" => "width: 10%;",
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "shist",
									"text" => "SURGICAL HISTORY",
									"form_field" => "textarea",
								),
								array(
									"id" => "smhist",
									"text" => "MEDICAL HISTORY",
									"form_field" => "textarea",
								),
								array(
									"id" => "aott",
									"text" => "OTHERS",
									"form_field" => "textarea",
								),
							),
						),
					),
				),
				
			),
		);
	}

	function __object_fs_ivf_oocyte_donot_retrieval(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Record of Cleavage Stage Biopsy Table",
			),
			"fields" => array(
				
				"s22" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
								"row_text_style" => "width: 10%;",
								"row_property" => array(
									9 => array(
										"colspan" => '2'
									),
									11 => array(
										"placeholder" => '1'
									),
								),
							),
							//cells
							"cells" => array(
								array(
									"id" => "sn",
									"text" => "",
									"rows_text" => array( "Hormonal Profile" ),
								),
								array(
									"id" => "r1",
									"text" => "FSH",
									"form_field" => "text",
								),
								array(
									"id" => "l1",
									"text" => "LH",
									"form_field" => "text",
								),
								array(
									"id" => "r2",
									"text" => "PROLACTIN",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				"biopsy_table" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"row_text_style" => "width: 10%;",
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "ivf",
									"text" => "DOWNREGULATION",
									"form_field" => "text",
								),
								array(
									"id" => "iscix",
									"text" => "SIMULATION",
									"form_field" => "text",
								),
								array(
									"id" => "isci",
									"text" => "ATTEMPT NO",
									"form_field" => "number",
								),
								array(
									"id" => "er",
									"text" => "GENOTYPE/BLOOD GROUP",
									"form_field" => "text",
								),
								array(
									"id" => "CHARACTERISTICS",
									"text" => "CHARACTERISTICS",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				"asc3" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"row_text_style" => "width: 10%;",
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "fl",
									"text" => "Follicle 8-12",
									"form_field" => "text",
								),
								array(
									"id" => "f2",
									"text" => "Follicle 13-16",
									"form_field" => "text",
								),
								array(
									"id" => "f3",
									"text" => "Follicle 17-19",
									"form_field" => "text",
								),
								array(
									"id" => "f4",
									"text" => "Follicle >= 20",
									"form_field" => "text",
								),
								array(
									"id" => "iscix",
									"text" => "No of follicles (left)",
									"form_field" => "number",
								),
								array(
									"id" => "isci",
									"text" => "No of follicles (right)",
									"form_field" => "number",
								),
							),
						),
					),
				),
				
				"egg_collection1" => array(
					"title" => "Egg Collection - EC setup by",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"embryologist" => array(
									"field_label" => "Embryologist",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "embryologist" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"ocr_dr" => array(
									"field_label" => "Doctor",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "ocr_dr" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"time" => array(
									"field_label" => "Time",
									"form_field" => "time",
								),
								
								"met" => array(
									"field_label" => "Method",
									"form_field" => "checkbox",
									"options" => "vaginal:Vaginal;abdominal:Abdominal;",
								),
								
								"met2" => array(
									"field_label" => "Total no. of eggs received",
									"form_field" => "number",
								),
								
							),
						),

					),
				),
				
				"s23" => array(
					//row 1
					"form_field" => "table",
					"title" => "<h4><strong>FOR COMPLETION BY TEAM</strong><h4>",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 4,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "sn",
									"text" => "S/N",
									"rows_text" => array( "1", "2", "3", "4" ),
								),
								array(
									"id" => "r1",
									"text" => "RECIPIENT\"S NAME/HOSPITAL NO",
									"form_field" => "text",
								),
								array(
									"id" => "r2",
									"text" => "NO. EGGS ALLOCATED",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				"evb" => array(
					"title" => "EGG ALLOCATION CONFIRMED BY:",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"eb" => array(
									"field_label" => "Embryologist",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "eb" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"drr" => array(
									"field_label" => "Doctor",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "drr" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"rnr" => array(
									"field_label" => "Nurse",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "rnr" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
							),
						),

					),
				),
				
				"evb2" => array(
					"title" => "LAB USE ONLY",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"esb" => array(
									"field_label" => "Eggs Split By:",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "esb" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
								"wb" => array(
									"field_label" => "Witnessed By:",
									"form_field" => "calculated",
									"required_field" => "yes",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "wb" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
							),
						),

					),
				),
				
			),
		);
	}

	function _ivf_database_object_field_source(){
		return array(
			"" => "None",
			"ivf_insemination_injection" => "IVF Insemination / Injection",
			"ivf_insemination_injection_day1" => "IVF Insemination / Injection - Day 1",
			"ivf_insemination_injection_day23" => "IVF Insemination / Injection - Day 2 & 3",
			"ivf_grade_quality" => "IVF Grade Quality - Day 4, 5 & 6",
			"ivf_embryo_transfer" => "Embryo Transfer",
			"ivf_embryo_freezing" => "Embryo Freezing",
			"ivf_excess_embryo" => "Excess Embryo",
			"ivf_assisted_hatching" => "Assisted Hatching",
			
			"ivf_egg_collection" => "Egg Collection",
			"ivf_sperm_preparation" => "Sperm Preparation",
			"ivf_icsi_pre_injection" => "ICSI Pre-Injection Dissection",
			
			"ivf_ovum_recipient_egg_collection_record" => "Ovum Recipient - Egg Collection Record",

			"ivf_record_of_cleavage_stage_biopsy_table" => "Record of Cleavage Stage Biopsy Table",

			"ivf_treatment_simulation_chart" => "Ivf Treatment Simulation Chart",
			"ivf_oocyte_donot_retrieval" => "Ivf Oocyte Donot Retrieval ODR",
		);
	}	
?>