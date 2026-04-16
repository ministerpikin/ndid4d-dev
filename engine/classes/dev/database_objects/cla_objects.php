<?php
	
	function __object_fs_cla_current_health_challenges(){
		return array(
			
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Current Health Challenges",
			),
			"fields" => array(
				"chc1" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								"number_1" => array(
									"field_label" => "1",
									"form_field" => 'text',
								),
								"number_2"  => array(
									"field_label" => "2",
									"form_field" => 'text',
								),
							),
						),
						array(
							"cells" => array(
								"number_3" => array(
									"field_label" => "3",
									"form_field" => 'text',
								),
								"number_4"  => array(
									"field_label" => "4",
									"form_field" => 'text',
								),
							),
						),
					),
				),
			),
		);
	}

	function __object_fs_cla_eating_description(){
		return array(

			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Eating Description",
			),
			"fields" => array(
				
				"eating_description_table" => array(
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
									"id" => "",
									"text" => "",
									"rows_text" => array("Time", "Day 1", "Day 2" ),
								),
								array(
									"id" => "breakfast",
									"text" => "Breakfast",
									"form_field" => "text",
								),
								array(
									"id" => "morning_snack",
									"text" => "Morining Snack",
									"form_field" => "text",
								),
								array(
									"id" => "lunch",
									"text" => "Lunch",
									"form_field" => "text",
								),
								array(
									"id" => "afternoon_snack",
									"text" => "Afternoon Snack",
									"form_field" => "text",
								),
								array(
									"id" => "dinner",
									"text" => "Dinner",
									"form_field" => "text",
								),
								array(
									"id" => "evening_snack",
									"text" => "Evening Snack",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
			),
		);
	}

	function __object_fs_cla_medications_and_conditions(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Medications And Conditions they are for",
			),
			"fields" => array(
				
				"medication_condition_table" => array(
					"form_field" => "table",
					"title" => "",
					"clone" => 1,
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
									"id" => "",
									"text" => "",
									"rows_text" => array("1", "2", "3" ),
								),
								array(
									"id" => "medication",
									"text" => "Medication",
									"form_field" => "text",
								),
								array(
									"id" => "condition",
									"text" => "Condition",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
			),
		);
	}

	function __object_fs_cla_family_health_history(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Family Health History",
			),
			"fields" => array(
				
				"health_history_table" => array(
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
							"cells" => array(
								array(
									"id" => "health_condition_and_duration",
									"text" => "Health Condition And Duration",
									"form_field" => "text",
								),
								array(
									"id" => "relationship",
									"text" => "Relationship ( mother, father, sibling )",
									"form_field" => "text",
								),
								array(
									"id" => "age",
									"text" => "Age",
									"form_field" => "number",
								),
								array(
									"id" => "death_and_cause",
									"text" => "Any Death in the Family? Cause?",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
			),
		);
	}

	function __object_fs_cla_pre_ozone_questionnaire(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "",
			),
			"fields" => array(
				
				"health_history_table" => array(
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 15,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							"cells" => array(
								array(
									"id" => "",
									"text" => "",
									"rows_text" => array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15" ),
								),
								array(
									"id" => "",
									"text" => "",
									"rows_text" => array(
										"Have you ever been diagnosed of Acute Haemolytic Anaemia?",
										"Have you ever been diagnosed of Toxic Hyperthyroidism ?",
										"Have you ever been diagnosed of Thrombocytopenia ?",
										"Have you ever had convultions in the past? Or Are you currently taking any anti-seizures/ anti-convultion medication ",
										"Have you had massive or acute hemorrhage ?",
										"Have you ever been diagnosed of Acute Alcohol Intoxication ?",
										"Have you been diagnosed of acute myocardial infraction or severe cardiovascular instability",
										"Do you feel any kind of pain? and have you been diagnosed of Arthritis?",
										"Have you been diagnosed of Type 1 or Type 2 diabetes ?",
										"Are you currently diagnosed of a viral or bacterial infection ?",
										"Have you been diagnosed of High Blood Pressure or an enlarged heart ?",
										"Are you pregnant ?",
										"Have you ever been diagnosed of Sickle Cell Anaemia ?",
										"Do you currently take any medications for a previous organ transplant?",
										"Do you have any electrical implants/ artificial organs e.g. pacemakers, defibrallators etc that are metallic",
									),
								),
								array(
									"id" => "hh_opt",
									"text" => "Yes/No",
									"form_field" => "select",
									"form_field_options" => "get_no_yes",
									"attributes" => " style='width:100px;' ",
								),
							),
						),
					),
				),
				
			),
		);
	}

	function _cla_database_object_field_source(){
		return array(
			"" => "None",
			"cla_current_health_challenges" => "CLA Current Health Challenges",
			"cla_eating_description" => "CLA Eating Description",
			"cla_medications_and_conditions" => "CLA Medications And Conditions",
			"cla_family_health_history" => "CLA Family Health History",
			"cla_pre_ozone_questionnaire" => "CLA Pre-Ozone Questionnaire"
		);
	}	
?>