<?php 

	function __object_fs_op_anaesthetic_record(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Anaesthetic Record",
			),
			"fields" => array(
				
				"drugs_section" => array(
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
									"id" => "drug1",
									"text" => "Drugs",

									"field_label" => "Drugs",
									"form_field" => "calculated",
									"required_field" => "no",
									
									"calculations" => array(
										'type' => 'item-details',
										'form_field' => 'text',
										'variables' => array( array( 'drug1' ) ),
									),
									
									'attributes' => ' action="?action=items&todo=get_items_select2_purchase" minlength="0" style="width: 22em !important;" ',
									"class" => ' select2 ',
									
									// "rows_text" => array( "AHA", "ET/CR/D/R" ),
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
							),
						),
					),
				),
				
				"day1_fields" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						
						array(
							"cells" => array(
								
								"anes_star_time" => array(
									"field_label" => "Anaesthetic Started at",
									"form_field" => "date-5time",
									"placeholder" => "HH:mm",
								),
								
								"in_ot" => array(
									"field_label" => "In OT",
									"form_field" => "text",
								),
								
								"surg_start" => array(
									"field_label" => "Surgery Started",
									"form_field" => "date-5time",
									"placeholder" => "HH:mm",
								),
								
								"surg_finish" => array(
									"field_label" => "Surgery Finished",
									"form_field" => "date-5time",
									"placeholder" => "HH:mm",
								),
								
							),
						),
						
						array(
							"cells" => array(
								
								"anes_finish" => array(
									"field_label" => "Anaesthetic Finished",
									"form_field" => "date-5time",
									"placeholder" => "HH:mm",
								),
								
								"other1" => array(
									"field_label" => "Others 1",
									"form_field" => "text",
								),
								
								"other2" => array(
									"field_label" => "Others 2",
									"form_field" => "text",
								),
								
								"other3" => array(
									"field_label" => "Others 3",
									"form_field" => "text",
								),
								
							),
						),
						
					),
				),
				
				"ecg_rhythm" => array(
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
									"id" => "drug1",
									"text" => " ",
									"rows_text" => array( 
										"ECG Rhythm", 
										"O<sub>2</sub> Saturation % SpO<sup>2<sup>", 
										"N<sub>2</sub>O / O<sub>2</sub> / Air FGF L/min", 
										"EndTidal Co<sub>2</sub>KPa", 
										"Oxygen Fi", 
										"Agent Iso/Sevo/Hal/MAC/Fe", 
										"Airway Pressure Paw/Peep", 
										array(
											"id" => "actx1",
											"text" => "actx1",
											"form_field" => "text",
										), 
										array(
											"id" => "actx2",
											"text" => "actx2",
											"form_field" => "text",
										), 
										array(
											"id" => "actx3",
											"text" => "actx3",
											"form_field" => "text",
										), 
									),
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
							),
						),
					),
				),
				
				"iv_fluid" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"iv_fluids" => array(
									"field_label" => "IV Fluids",

									"id" => "iv_fluids",
									"text" => "Drug Name",
									"form_field" => "calculated",
									"required_field" => "no",
									
									"calculations" => array(
										'type' => 'item-details',
										'form_field' => 'text',
										'variables' => array( array( 'iv_fluids' ) ),
									),
									
									'attributes' => ' action="?action=items&todo=get_items_select2_purchase" minlength="0" tags="true"  ',
									"class" => ' select2 ',
								),
								
								"bd_loss" => array(
									"field_label" => "Blood Loss (record id > 100m/s)",
									"form_field" => "text",
								),

								"ebl" => array(
									"field_label" => "EBL",
									"form_field" => "text",
								),
								
								"u_out" => array(
									"field_label" => "Urine Output",
									"form_field" => "text",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"vascular" => array(
									"field_label" => "Vascular Access site",
									"form_field" => "text",
								),
								
								"pre_i" => array(
									"field_label" => "Pre-Induction",
									"form_field" => "text",
								),
								
								"post_i" => array(
									"field_label" => "Post-Induction",
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


	function __object_fs_op_anaesthetic_record2(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Anaesthetic Record Cont'd",
			),
			"fields" => array(
				
				"tor" => array(
					"title" => "TOURNIQUET",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"pressure" => array(
									"field_label" => "Pressure",
									"form_field" => "text",
									"placeholder" => "mmHg",
								),
								
								"on" => array(
									"field_label" => "On @",
									"form_field" => "date-5time",
								),
								
								"off" => array(
									"field_label" => "Off @",
									"form_field" => "date-5time",
								),

								"tt" => array(
									"field_label" => "Total Time",
									"form_field" => "text",
								),
								
							),
						),

					),
				),

				"limb" => array(
					// "title" => "LIMB",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"limb_parts" => array(
									"field_label" => "LIMB",
									"form_field" => "checkbox",
									"form_field_options" => "get_anes_limbs",
									"placeholder" => "mmHg",
								),
								
							),
						),

					),
				),
				
				"pi" => array(
					// "title" => "Patient Information",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"pii" => array(
									"field_label" => "Patient Information",
									"form_field" => "checkbox",
									"form_field_options" => "get_anes_pi",
								),
								
								"pos" => array(
									"field_label" => "Position",
									"form_field" => "text",
								),
								
								"arms" => array(
									"field_label" => "Arms",
									"form_field" => "text",
								),

								"ifg" => array(
									"field_label" => "Intervenous Fluids Given",
									"form_field" => "text",
								),
								
							),
						),

					),
				),

				"ecg_rhythm" => array(
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
									"id" => "drug1",
									"text" => " ",
									"rows_text" => array( 
										"ECG", 
										"SP O<sub>2</sub>", 
										"EFG", 
										"Et Co<sub>2</sub>",
										"Ei O<sub>2</sub>",
										"Ee", 
										"Paw", 
										7 => array(
											"id" => "ext1",
											"field_label" => "ext1",
											"form_field" => "text",
										), 
										8 => array(
											"id" => "ext2",
											"field_label" => "ext2",
											"form_field" => "text",
										), 
										9 => array(
											"id" => "ext3",
											"field_label" => "ext3",
											"form_field" => "text",
										), 
									),
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
							),
						),
					),
				),
				
				"iv_fluid" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"ivf" => array(
									"field_label" => "IVF",
									"form_field" => "text",
								),
								
								"ebl" => array(
									"field_label" => "EBL",
									"form_field" => "text",
								),

								"uo" => array(
									"field_label" => "UO",
									"form_field" => "text",
								),
								
							),
						),

						array(
							"title" => "Totals",
							"cells" => array(
								
								"t_ivf" => array(
									"field_label" => "Total - IVF",
									"form_field" => "text",
								),
								
								"t_ebl" => array(
									"field_label" => "Total - EBL",
									"form_field" => "text",
								),

								"t_uo" => array(
									"field_label" => "Total - UO",
									"form_field" => "text",
								),
								
							),
						),

					),
				),

				"last" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"lat" => array(
									"field_label" => "LOCAL ANAESTHETIC TECHNIQUE",
									"form_field" => "textarea",
								),
							),
						),

						array(
							"cells" => array(
								"cae" => array(
									"field_label" => "CLINICAL ADVERSE EVENT:<br>Care Form Filled. Write in medical notes",
									"form_field" => "textarea",
								),
							),
						),

					),
				),
				
				
			),
		);
	}
	
	function __object_fs_op_anaesthetic_record3(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Anaesthetic Record Cont'd 2",
			),
			"fields" => array(

				"airway" => array(
					// "title" => "Airway",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"i_opt" => array(
									"field_label" => "Induction Options",
									"form_field" => "checkbox",
									"form_field_options" => "get_anes_induction_options",
								),
								
								"airwy" => array(
									"field_label" => "Airway",
									"form_field" => "checkbox",
									"form_field_options" => "get_anes_airway_options",
								),
								
							),
						),

					),
				),

				"lgrade" => array(
					// "title" => "LIMB",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"limb_parts" => array(
									"field_label" => "Grade",
									"form_field" => "checkbox",
									"form_field_options" => "get_anes_dl_grade",
								),
								
								"dlgy" => array(
									"field_label" => "Direct Laryngoscopy",
									"form_field" => "checkbox",
									"form_field_options" => "get_anes_dl_options",
								),
								
							),
						),

					),
				),
				
				"ddl" => array(
					// "title" => "Patient Information",
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							// 'merge_cells' => 1,
						),
						array(
							"cells" => array(
								
								"omm" => array(
									"field_label" => "Other Monitors NMJ @{field} site Termo Probe @{termo}",
									"form_field" => "text",
									"embed" => 1,
								),
								
								"omm2" => array(
									"field_label" => "{field} site",
									"form_field" => "text",
									"embed" => 1,
									"embed_key" => '{termo}',
									"skip_cell" => 1,
								),
								
								"rspi" => array(
									"field_label" => "Respiration",
									"form_field" => "checkbox",
									"form_field_options" => "get_anes_resipration",
								),
								
							),
						),

					),
				),
				
				"rsp" => array(
					// "title" => "Patient Information",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"rspi2" => array(
									"field_label" => "Mode",
									"form_field" => "checkbox",
									"form_field_options" => "get_anes_mode",
								),
								
								"tv" => array(
									"field_label" => "TV {field}ml <br><br>{qwer}",
									"form_field" => "text",
									"embed" => 1,
								),
								
								"ffreq" => array(
									"field_label" => "Freq. {field} <br><br>{circuit}",
									"form_field" => "text",
									"embed" => 1,
									"skip_cell" => 1,
									"embed_key" => '{qwer}',
								),
								
								"circuit" => array(
									"field_label" => "Circuit {field}",
									"form_field" => "checkbox",
									"form_field_options" => "get_anes_circuit",
									"embed" => 1,
									"embed_key" => '{circuit}',
									"skip_cell" => 1,
								),
								
							),
						),

					),
				),
			),
		);
	}
	
	function __object_fs_op_anaesthetic_handover(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Record of Cleavage Stage Biopsy Table",
			),
			"fields" => array(
				
				"part1" => array(
					//row 1
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
									"id" => "dt",
									"text" => "Date & Time",
									"form_field" => "date-5time",
								),
								array(
									"id" => "drug",
									"text" => "Drug Name",
									"field_label" => "Drugs",
									"form_field" => "calculated",
									"required_field" => "no",
									
									"calculations" => array(
										'type' => 'item-details',
										'form_field' => 'text',
										'variables' => array( array( 'drug' ) ),
									),
									
									'attributes' => ' action="?action=items&todo=get_items_select2_purchase" minlength="0" style="width: 22em !important;" ',
									"class" => ' select2 ',
									
								),
								array(
									"id" => "dose",
									"text" => "Dose",
									"form_field" => "text",
								),
								array(
									"id" => "route",
									"text" => "Route",
									"form_field" => "select",
									"form_field_options" => "get_hospital_route",
								),
								array(
									"id" => "freq",
									"text" => "Freq. (mm/hr)",
									"form_field" => "text",
								),
								array(
									"id" => "max_dose",
									"text" => "Max Dose",
									"form_field" => "text",
								),
							),
						),
					),
				),
				
				"part2" => array(
					//row 1
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
									"id" => "dat",
									"text" => "Date",
									"form_field" => "date-5",
								),
								array(
									"id" => "time",
									"text" => "Time",
									"form_field" => "time",
									"placeholder" => "HH:mm",
								),
								array(
									"id" => "drugan",
									"text" => "Drug Approved Name",
									"field_label" => "Drug Approved Name",
									"form_field" => "calculated",
									"required_field" => "no",
									
									"calculations" => array(
										'type' => 'item-details',
										'form_field' => 'text',
										'variables' => array( array( 'drugan' ) ),
									),
									
									'attributes' => ' action="?action=items&todo=get_items_select2_purchase" minlength="0" style="width: 15m !important;" ',
									"class" => ' select2 ',
									
								),
								array(
									"id" => "dose1",
									"text" => "Dose",
									"form_field" => "text",
								),
								array(
									"id" => "route1",
									"text" => "Route",
									"form_field" => "select",
									"form_field_options" => "get_hospital_route",
								),
								array(
									"id" => "givby",
									"text" => "Given By",
									"field_label" => "Given By",
									"form_field" => "calculated",
									"required_field" => "no",
									
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( 'givby' ) ),
									),
									
									'attributes' => ' action="?action=users&todo=get_users_select2" minlength="0" style="width: 15em !important;" ',
									"class" => ' select2 ',
									
								),
								array(
									"id" => "chkby",
									"text" => "Checked By",
									"field_label" => "Checked By",
									"form_field" => "calculated",
									"required_field" => "no",
									
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( 'chkby' ) ),
									),
									
									'attributes' => ' action="?action=users&todo=get_users_select2" minlength="0" style="width: 15em !important;" ',
									"class" => ' select2 ',
									
								),
							),
						),
					),
				),
				
				"disc" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"iv_fluids" => array(
									"field_label" => "Discharge",
									"form_field" => "checkbox",
									"form_field_options" => "get_handover_discharge",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"oin" => array(
									"field_label" => "Other Instructions",
									"form_field" => "text",
								),
								
							),
						),

					),
				),
				
				"ro" => array(
					"title" => "RECOVERY OBSERVATIONS",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"tin" => array(
									"field_label" => "Time In",
									"form_field" => "time",
									"placeholder" => "HH:mm",
								),
								
								"tot" => array(
									"field_label" => "Time Out",
									"form_field" => "time",
									"placeholder" => "HH:mm",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"vcan" => array(
									"field_label" => "V CANNULA",
									"form_field" => "checkbox",
									"form_field_options" => "get_recovery_observation_options",
								),
								
								"glma" => array(
									"field_label" => "Guedel / LMA",
									"form_field" => "checkbox",
									"form_field_options" => "get_recovery_observation_options",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"rstaff" => array(
									"id" => "rstaff",
									"field_label" => "Recovery Staff",
									"form_field" => "calculated",
									"required_field" => "no",
									
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( 'rstaff' ) ),
									),
									
									'attributes' => ' action="?action=users&todo=get_users_select2" minlength="0" style="width: 22em !important;" ',
									"class" => ' select2 ',
								),
								
								"wstaff" => array(
									"id" => "wstaff",
									"field_label" => "Ward Staff",
									"form_field" => "calculated",
									"required_field" => "no",
									
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( 'wstaff' ) ),
									),
									
									'attributes' => ' action="?action=users&todo=get_users_select2" minlength="0" style="width: 22em !important;" ',
									"class" => ' select2 ',
								),
								
							),
						),

					),
				),
				
				"minr" => array(
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
									"id" => "drug1",
									"text" => " ",
									"rows_text" => array( 
										"Minutes in Recovery", 
										"Time", 
										"Pulse R= REG I-Irreg", 
										"Blood Pressure", 
										"O<sub>2</sub> Saturation %", 
										"Resp. Rate / min", 
										"Pain Score", 
										"Nausea Score", 
										"Colour Score", 
										"Temperature C", 
										"Cound Check", 
									),
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

	function __object_fs_labour1(){
		return array(
			"type" => "field_group",
			"property" => array(
				// "show_name" => 1,
				// "title" => "Anaesthetic Record Cont'd",
			),
			"fields" => array(
				
				"adm" => array(
					"title" => "Admission",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"admitted" => array(
									"field_label" => "Admitted",
									"form_field" => "date-5time",
								),
								
								"amaturity" => array(
									"field_label" => "Maturity",
									"form_field" => "number",
									"placeholder" => "Weeks",
								),
								
								"afh" => array(
									"field_label" => "F.H.",
									"form_field" => "text",
								),

								"adish" => array(
									"field_label" => "Discharged",
									"form_field" => "date-5time",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"radmitted" => array(
									"field_label" => "Re-admitted",
									"form_field" => "date-5time",
								),
								
								"rmaturity" => array(
									"field_label" => "Maturity",
									"form_field" => "number",
									"placeholder" => "Weeks",
								),
								
								"rfh" => array(
									"field_label" => "F.H.",
									"form_field" => "text",
								),

								"rdish" => array(
									"field_label" => "Discharged",
									"form_field" => "date-5time",
								),
								
							),
						),

					),
				),
			),
		);
	}

	function __object_fs_post_natal(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 2,
				"title" => "Post Natal",
			),
			"fields" => array(
				
				"bby" => array(
					"title" => "Baby",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"bwgh" => array(
									"field_label" => "Baby Weight",
									"form_field" => "decimal",
									"placeholder" => "kg",
								),
								
								"compl" => array(
									"field_label" => "Complaints",
									"form_field" => "textarea",
								),
								
								"exmin" => array(
									"field_label" => "Examination",
									"form_field" => "textarea",
								),
								
							),
						),

					),
				),

				"mth" => array(
					"title" => "Mother",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"mwgh" => array(
									"field_label" => "Mother Weight",
									"form_field" => "decimal",
									"placeholder" => "kg",
								),
								
								"boobs" => array(
									"field_label" => "Breast",
									"form_field" => "text",
								),
								
								"mbp" => array(
									"field_label" => "BP",
									"form_field" => "text",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"utr" => array(
									"field_label" => "Uterus",
									"form_field" => "text",
								),
								
								"mns" => array(
									"field_label" => "Menses",
									"form_field" => "text",
								),
								
								"vag" => array(
									"field_label" => "Vagina",
									"form_field" => "text",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"ccomp" => array(
									"field_label" => "Complaints",
									"form_field" => "textarea",
								),
								
								"fpm" => array(
									"field_label" => "Family Planning Method",
									"form_field" => "textarea",
								),
								
								"exmn" => array(
									"field_label" => "Examination",
									"form_field" => "textarea",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"advs" => array(
									"field_label" => "Advised",
									"form_field" => "textarea",
								),
								
								"gnrl" => array(
									"field_label" => "General",
									"form_field" => "textarea",
								),
								
							),
						),

					),
				),

				"cmt" => array(
					"title" => "",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"mwgh" => array(
									"field_label" => "Summary",
									"form_field" => "textarea",
								),

							),
						),
					),
				),
			),
		);
	}

	function __object_fs_summary_of_labour(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 2,
				"title" => "Summary of Labour",
			),
			"fields" => array(
				
				"dil" => array(
					"title" => "Drugs In Labour",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"drugs" => array(
									"id" => "drug1",
									"text" => "Drugs",

									"field_label" => "Drugs",
									"form_field" => "calculated",
									"required_field" => "no",
									
									"calculations" => array(
										'type' => 'item-details',
										'form_field' => 'text',
										'variables' => array( array( 'drugs' ) ),
									),
									
									'attributes' => ' action="?action=items&todo=get_items_select2_purchase" minlength="0" ',
									// 'attributes' => ' action="?action=items&todo=get_items_select2_purchase" minlength="0" style="width: 22em !important;" ',
									"class" => ' select2 ',
									
									// "rows_text" => array( "AHA", "ET/CR/D/R" ),
								),
								
							),
						),

					),
				),

				"onset" => array(
					"title" => "ONSET",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"lcom" => array(
									"field_label" => "Labour Commenced",
									"form_field" => "date-5time",
								),
								
								"onst" => array(
									"field_label" => "Onset",
									"form_field" => "number",
								),
								
								"prsnt" => array(
									"field_label" => "Presentation",
									"form_field" => "text",
								),

								"mcapt" => array(
									"field_label" => "Membranes Reptured",
									"form_field" => "date-5time",
								),
								
							),
						),

					),
				),
				
				"delvry" => array(
					"title" => "DELIVERY",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"efs" => array(
									"field_label" => "End of First Stage",
									"form_field" => "date-5time",
								),
								
								"ibrn" => array(
									"field_label" => "Infant Born",
									"form_field" => "date-5time",
								),
								
								"prsnt2" => array(
									"field_label" => "Presentation",
									"form_field" => "text",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"dol" => array(
									"field_label" => "Duration of Labour",
									"form_field" => "time",
								),
								
								"mod" => array(
									"field_label" => "Method of Delivery",
									"form_field" => "text",
								),
								
								"indc" => array(
									"field_label" => "Indication",
									"form_field" => "text",
								),
								
							),
						),
					),
				),
				
				"Thirdstg" => array(
					"title" => "Third Stage",
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"dr" => array(
									"field_label" => "Duration",
									"form_field" => "text",
								),
								
								"per" => array(
									"field_label" => "Perineum",
									"form_field" => "text",
								),
								
								"mthd" => array(
									"field_label" => "Method",
									"form_field" => "text",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"nos" => array(
									"field_label" => "No. of Sutures",
									"form_field" => "text",
								),
								
								"blbd" => array(
									"field_label" => "Blood Loss (Before Delivery)",
									"form_field" => "text",
								),
								
								"sutby" => array(
									"field_label" => "Sutured By",
									"form_field" => "text",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"blad" => array(
									"field_label" => "Blood Loss (After Delivery)",
									"form_field" => "text",
								),
								
								"acc" => array(
									"field_label" => "Accoucheur",
									"form_field" => "text",
								),
								
								"membr" => array(
									"field_label" => "Membranes",
									"form_field" => "text",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"sist" => array(
									"field_label" => "Sister",
									"form_field" => "text",
								),
								
								"plast" => array(
									"field_label" => "Placenta",
									"form_field" => "text",
								),
								
								"subrb" => array(
									"field_label" => "Sutures Removed By",
									"form_field" => "text",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"pweih" => array(
									"field_label" => "Placenta Weight",
									"form_field" => "text",
								),
								
							),
						),

					),
				),
				
				"anes" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"anes" => array(
									"field_label" => "Anaesthetic",
									"form_field" => "text",
								),
								
								"anesby" => array(
									"id" => "anesby",
									"text" => "Administered By",
									"field_label" => "Administered By",
									"form_field" => "calculated",
									"required_field" => "no",
									
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( 'anes' ) ),
									),
									
									'attributes' => ' action="?action=users&todo=get_users_select2" minlength="0" ',
									"class" => ' select2 ',
									
								),
								
							),
						),

					),
				),
				
			),
		);
	}

	function __object_fs_intravenous_therapy(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 2,
				"title" => "Intravenous Therapy",
			),
			"fields" => array(

				"thinf" => array(
					//row 1
					"title" => "The Infant",
					"form_field" => "table",
					"clone" => 1,
					"no_stack" => 1,
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 8,
								"class" => "heading-row",
								"style" => "font-weight:bold;",
								"row_property" => array(
									0 => array(
										"use_field" => array(
											"form_field" => "select",
											"form_field_options" => "get_sex",
										),
									),
									1 => array(
										"use_field" => array(
											"form_field" => "number",
										),
									),
									2 => array(
										"use_field" => array(
											"form_field" => "text",
										),
									),
									3 => array(
										"placeholder" => "kg",
										"use_field" => array(
											"form_field" => "decimal_long",
										),
									),
									4 => array(
										"placeholder" => "cm",
										"use_field" => array(
											"form_field" => "decimal",
										),
									),
									5 => array(
										"placeholder" => "cm",
										"use_field" => array(
											"form_field" => "decimal",
										),
									),
									6 => array(
										"use_field" => array(
											"form_field" => "text",
										),
									),
									7 => array(
										"use_field" => array(
											"form_field" => "text",
										),
									),
								),
							),
							//cells
							"cells" => array(
								array(
									"id" => "a_b",
									"text" => "",
									"rows_text" => array( 
										"Gender", 
										"Maturity", 
										"Condition", 
										"Birth Weight", 
										"Length", 
										"Head Circumference", 
										"Abnormality", 
										"Examiner", 
									),
								),
								array(
									"id" => "1",
									"text" => "1",
								),
								array(
									"id" => "2",
									"text" => "2",
								),
								array(
									"id" => "3",
									"text" => "3",
								),
							),
						),
					),
				),
				
			),
		);
	}
	
	function __object_fs_apgar_score(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 2,
				"title" => "Apgar Score",
			),
			"fields" => array(
				
				"thinf" => array(
					//row 1
					// "title" => "The Infant",
					"form_field" => "table",
					"clone" => 1,
					"no_stack" => 1,
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 3,
								"class" => "heading-row",
								"style" => "font-weight:bold;",
								"row_property" => array(
									0 => array(
										"use_field" => array(
											"form_field" => "number",
										),
									),
									1 => array(
										"placeholder" => '1 Min',
										"use_field" => array(
											"form_field" => "text",
										),
									),
									2 => array(
										"use_field" => array(
											"form_field" => "select",
											"form_field_options" => "get_apgar_types",
										),
									),
								),
							),
							//cells
							"cells" => array(
								array(
									"id" => "a_b",
									"text" => "",
									"rows_text" => array( 
										"Baby No.", 
										"Time", 
										"Apgar Type", 
									),
								),
								array(
									"id" => "1",
									"text" => "1",
								),
								array(
									"id" => "2",
									"text" => "2",
								),
								array(
									"id" => "3",
									"text" => "3",
								),
								array(
									"id" => "4",
									"text" => "4",
								),
								array(
									"id" => "5",
									"text" => "5",
								),
							),
						),
					),
				),
				
				"lstx" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						array(
							"cells" => array(
								
								"imvk" => array(
									"field_label" => "IM Vitamin K 1mg",
									"form_field" => "text",
								),
								
								"ans" => array(
									"field_label" => "Anus",
									"form_field" => "text",
								),
								
								"fdist" => array(
									"field_label" => "Foetal Distress",
									"form_field" => "text",
								),
								
							),
						),

					),
				),

			),
		);
	}
	
	function _op_database_object_field_source(){
		return array(
			"" => "None",
			"op_anaesthetic_record" => "Anaesthetic Record",
			"op_anaesthetic_record2" => "Anaesthetic Record Cont'd",
			"op_anaesthetic_record3" => "Anaesthetic Record Cont'd 2",
			"op_anaesthetic_handover" => "Anaesthetic Handover",

			"labour1" => "Labour Intro",
			"summary_of_labour" => "Summary of Labour",
			"intravenous_therapy" => "Intravenous Therapy",
			"post_natal" => "Post Natal",
			"apgar_score" => "Apgar Score",
		);
	}	
?>