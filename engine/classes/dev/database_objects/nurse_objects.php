<?php 

	function __object_fs_adult_observation_chart(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Adult Observation Chart",
			),
			"fields" => array(
				
				"scl" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"scale1" => array(
									"field_label" => "",
									"form_field" => "checkbox",
									"form_field_options" => "get_aoc_scale1",
								),
								
								"date1" => array(
									"field_label" => "Date",
									"form_field" => "date-5time",
								),
								
								"datesx" => array(
								),
								
							),
						),

						array(
							"cells" => array(
								
								"scale2" => array(
									"field_label" => "",
									"form_field" => "checkbox",
									"form_field_options" => "get_aoc_scale2",
								),
								
								"date2" => array(
									"field_label" => "Date",
									"form_field" => "date-5time",
								),
								
								"datesx" => array(
								),
								
							),
						),

					),
				),
				
				"neuro" => array(
					//row 1
					"form_field" => "table",
					"title" => "Neurological Obsevations",
					"clone" => 1,
					"no_stack" => 1,
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
									"text" => "Eye Opening",
									"id" => "sp4",
									"rows_text" => array( 
										"Spontaneous 4", 
										"To Speech 3",
										"To Pain 2",
										"None (or eyes closed by swelling[C]) 1",
									),
								),
								array(
									"id" => "as1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "as2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "as3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "as4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "as5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "as6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "as7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "as8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "as9",
									"text" => "9",
									"form_field" => "text",
								),
							),
						),
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
									"text" => "Verbal Response",
									"id" => "vr",
									"rows_text" => array( 
										"Oriented 5", 
										"Confused 4", 
										"Inappropriate 3",
										"Incomprehensible 2",
										"None (or ET or tracheaostomy tube [T]) 1",
									),
								),
								array(
									"id" => "bs1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "bs2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "bs3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "bs4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "bs5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "bs6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "bs7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "bs8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "bs9",
									"text" => "9",
									"form_field" => "text",
								),
							),
						),
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
									"text" => "Best Motor Response",
									"id" => "bmr",
									"rows_text" => array( 
										"Obeying 6", 
										"Localising 5", 
										"Flexion 4", 
										"Abnormal Flexion 3",
										"Extending 2",
										"None 1",
									),
								),
								array(
									"id" => "cs1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "cs2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "cs3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "cs4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "cs5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "cs6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "cs7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "cs8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "cs9",
									"text" => "9",
									"form_field" => "text",
								),
							),
						),
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
								"hide_column_header" => 1,
							),
							//cells
							"cells" => array(
								array(
									"text" => "",
									"id" => "tgcs",
									"rows_text" => array( 
										// 0 => array(
											"Total GCS Score /15"
										// ), 
									),
								),
								array(
									"id" => "ds1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "ds2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "ds3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "ds4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "ds5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "ds6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "ds7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "ds8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "ds9",
									"text" => "9",
									"form_field" => "text",
								),
							),
						),
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
									"text" => "Right Pupils",
									"readonly" => 1,
									"colspan" => 4,
								),
								array(
									"text" => "Left Pupils",
									"readonly" => 1,
									"colspan" => 5,
								),
							),
						),
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
									"text" => "",
									"id" => "rp",
									"rows_text" => array( 
										"Size", 
										"Reaction", 
									),
								),
								array(
									"id" => "es1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "es2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "es3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "es4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "es5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "es6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "es7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "es8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "es9",
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

	function __object_fs_early_warning_score(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Adult Observation Chart",
			),
			"fields" => array(
				
				"earlws" => array(
					//row 1
					"form_field" => "table",
					"clone" => 1,
					"no_stack" => 1,
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 7,
								"class" => "heading-row",
								"style" => "font-weight:bold;",
								"row_property" => array(
									0 => array(
										"placeholder" => '3'
									),
									1 => array(
										"placeholder" => '2'
									),
									2 => array(
										"placeholder" => '1'
									),
									4 => array(
										"placeholder" => '1'
									),
									5 => array(
										"placeholder" => '2'
									),
									6 => array(
										"placeholder" => '3'
									),
								),
							),
							//cells
							"cells" => array(
								array(
									"id" => "a_b",
									"text" => "<h3><strong>A+B</stron3></h4> Respirations Breaths/min",
									"rows_text" => array( 
										">= 25", 
										"21 - 24",
										"18 - 20",
										"15 - 17",
										"12 - 14",
										"9 - 11",
										"<= 8",
									),
								),
								array(
									"id" => "a1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "a2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "a3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "a4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "a5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "a6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "a7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "a8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "a9",
									"text" => "9",
									"form_field" => "text",
								),
							),
						),
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 4,
								"class" => "heading-row",
								"style" => "font-weight:bold;",
								"row_property" => array(
									1 => array(
										"placeholder" => '1'
									),
									2 => array(
										"placeholder" => '2'
									),
									3 => array(
										"placeholder" => '3'
									),
								),
							),
							//cells
							"cells" => array(
								array(
									"id" => "a_bspo",
									"text" => "<h3><strong>A+B</stron3></h4> SpO<sub>2</sub> Scale 1 Oxygen saturation (%)",
									"rows_text" => array( 
										">= 96", 
										"94 - 95",
										"92 - 93",
										"<= 91",
									),
								),
								array(
									"id" => "b1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "b2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "b3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "b4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "b5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "b6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "b7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "b8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "b9",
									"text" => "9",
									"form_field" => "text",
								),
							),
						),
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 8,
								"class" => "heading-row",
								"style" => "font-weight:bold;",
								"row_property" => array(
									0 => array(
										"placeholder" => '3'
									),
									1 => array(
										"placeholder" => '2'
									),
									2 => array(
										"placeholder" => '1'
									),
									5 => array(
										"placeholder" => '1'
									),
									6 => array(
										"placeholder" => '2'
									),
									7 => array(
										"placeholder" => '3'
									),
								),
							),
							//cells
							"cells" => array(
								array(
									"id" => "spo",
									"text" => "SpO<sub>2</sub> Scale 2<sup>1</sup> Oxygen saturation (%)",
									"rows_text" => array( 
										">= 97 on O<sub>2</sub>", 
										"95 - 96 on O<sub>2</sub>", 
										"93 - 94 on O<sub>2</sub>", 
										">= 93 on air",
										"88 - 92",
										"86 - 87",
										"84 - 85",
										"<= 83%",
									),
								),
								array(
									"id" => "c1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "c2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "c3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "c4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "c5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "c6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "c7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "c8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "c9",
									"text" => "9",
									"form_field" => "text",
								),
							),
						),
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 3,
								"class" => "heading-row",
								"style" => "font-weight:bold;",
								"row_property" => array(
									1 => array(
										"placeholder" => '2'
									),
								),
							),
							//cells
							"cells" => array(
								array(
									"id" => "air_oxy",
									"text" => "Air or Oxygen?",
									"rows_text" => array( 
										"A=Air", 
										"O<sub>2</sub> L/min",
										"Device", 
									),
								),
								array(
									"id" => "d1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "d2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "d3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "d4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "d5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "d6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "d7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "d8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "d9",
									"text" => "9",
									"form_field" => "text",
								),
							),
						),
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 14,
								"class" => "heading-row",
								"style" => "font-weight:bold;",
								"row_property" => array(
									0 => array(
										"placeholder" => '3'
									),
									7 => array(
										"placeholder" => '1'
									),
									8 => array(
										"placeholder" => '2'
									),
									9 => array(
										"placeholder" => '3'
									),
									10 => array(
										"placeholder" => '3'
									),
									11 => array(
										"placeholder" => '3'
									),
									12 => array(
										"placeholder" => '3'
									),
									13 => array(
										"placeholder" => '3'
									),
								),
							),
							//cells
							"cells" => array(
								array(
									"id" => "bl",
									"text" => "<h3><strong>C</strong></h3>Blood Pressure mmHg",
									"rows_text" => array( 
										">= 220", 
										"201 - 219", 
										"181 - 200", 
										"161 - 180", 
										"141 - 160", 
										"121 - 140", 
										"111 - 120", 
										"101 - 110", 
										"91 - 100", 
										"81 - 90", 
										"71 - 80", 
										"61 - 70", 
										"51 - 60", 
										"<= 50", 
									),
								),
								array(
									"id" => "e1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "e2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "e3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "e4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "e5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "e6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "e7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "e8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "e9",
									"text" => "9",
									"form_field" => "text",
								),
							),
						),
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 12,
								"class" => "heading-row",
								"style" => "font-weight:bold;",
								"row_property" => array(
									0 => array(
										"placeholder" => '3'
									),
									1 => array(
										"placeholder" => '2'
									),
									2 => array(
										"placeholder" => '2'
									),
									3 => array(
										"placeholder" => '1'
									),
									4 => array(
										"placeholder" => '1'
									),
									9 => array(
										"placeholder" => '1'
									),
									10 => array(
										"placeholder" => '2'
									),
									11 => array(
										"placeholder" => '3'
									),
								),
							),
							//cells
							"cells" => array(
								array(
									"id" => "plbt",
									"text" => "<h3><strong>C</strong></h3>Pulse Beats/min",
									"rows_text" => array( 
										">= 131", 
										"121 - 130", 
										"111 - 120", 
										"101 - 110", 
										"91 - 100", 
										"81 - 90", 
										"71 - 80", 
										"61 - 70", 
										"51 - 60", 
										"41 - 50", 
										"31 - 40", 
										"<= 30", 
									),
								),
								array(
									"id" => "f1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "f2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "f3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "f4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "f5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "f6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "f7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "f8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "f9",
									"text" => "9",
									"form_field" => "text",
								),
							),
						),
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 5,
								"class" => "heading-row",
								"style" => "font-weight:bold;",
								"row_property" => array(
									1 => array(
										"placeholder" => '3'
									),
									2 => array(
										"placeholder" => '3'
									),
									3 => array(
										"placeholder" => '3'
									),
									4 => array(
										"placeholder" => '3'
									),
								),
							),
							//cells
							"cells" => array(
								array(
									"id" => "dconsc",
									"text" => "<h3><strong>D</strong></h3>Conciousness",
									"rows_text" => array( 
										"Alert", 
										"Confussion", 
										"V", 
										"P", 
										"U", 
									),
								),
								array(
									"id" => "g1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "g2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "g3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "g4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "g5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "g6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "g7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "g8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "g9",
									"text" => "9",
									"form_field" => "text",
								),
							),
						),
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 1,
								"class" => "heading-row",
								"style" => "font-weight:bold;",
								"hide_column_header" => 1,
							),
							//cells
							"cells" => array(
								array(
									"id" => "tnews",
									"text" => 'Total NEWS', 
									"rows_text" => array( 
										"Total NEWS", 
									),
								),
								array(
									"id" => "h1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "h2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "h3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "h4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "h5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "h6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "h7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "h8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "h9",
									"text" => "9",
									"form_field" => "text",
								),
							),
						),
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 6,
								"class" => "heading-row",
								"style" => "font-weight:bold;",
								"hide_column_header" => 1,
							),
							//cells
							"cells" => array(
								array(
									"id" => "last_sec",
									"rows_text" => array( 
										"Blood Glucose", 
										"Pain on Movement (1-10)", 
										"Observations Completed By", 
										"Monitoring Frequency", 
										"Escalation of care Y/N", 
										"Registered Nurse Initials", 
									),
								),
								array(
									"id" => "i1",
									"text" => "1",
									"form_field" => "text",
								),
								array(
									"id" => "i2",
									"text" => "2",
									"form_field" => "text",
								),
								array(
									"id" => "i3",
									"text" => "3",
									"form_field" => "text",
								),
								array(
									"id" => "i4",
									"text" => "4",
									"form_field" => "text",
								),
								array(
									"id" => "i5",
									"text" => "5",
									"form_field" => "text",
								),
								array(
									"id" => "i6",
									"text" => "6",
									"form_field" => "text",
								),
								array(
									"id" => "i7",
									"text" => "7",
									"form_field" => "text",
								),
								array(
									"id" => "i8",
									"text" => "8",
									"form_field" => "text",
								),
								array(
									"id" => "i9",
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

	function __object_fs_post_falls_risk_assessment(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "POST FALLS RISK ASSESSMENT",
			),
			"fields" => array(
				
				"hheadr" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"ir" => array(
									"field_label" => "To be completed immediately post fall, the nurse in charge will ensure the form is complete. The *MO and post falls team should be notified as soon as possible post fall",
									"form_field" => "html",
								),
								
							),
						),

					),
				),
				
				"scl" => array(
					// "title" => "Post Falls Protocol",
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"ir" => array(
									"field_label" => "Immediate Response (RESPONDER)<br>A, B, C, D, E assessment.<br>Document immediate action that was taken:",
									"form_field" => "html",
									"form_field_options" => "get_aoc_scale1",
								),
								
							),
						),

					),
				),
				
				"scl2" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"title" => "AVPU assessment",
							"cells" => array(
								
								"alert" => array(
									"field_label" => "A=Alert. Patient was alert and conscious",
									"form_field" => "text",
								),
								
								"verbal" => array(
									"field_label" => "V=Verbal. Patient responded to verbal stimulus",
									"form_field" => "text",
								),
								
								"pain" => array(
									"field_label" => "P=Pain. Patient responded to painful stimulus",
									"form_field" => "text",
								),
								
								"unresponsive" => array(
									"field_label" => "U=Unresponsive. Patient was responsive to anal form of stimulus",
									"form_field" => "text",
								),
								
							),
						),

					),
				),
				
				"cfi_head" => array(
					"title" => "Post Falls Protocol",
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"ir" => array(
									"field_label" => "Ensure careful inspection of the patient with particular attention to the head, limbs, and movement of any obviously injured limbs.<br>
										Clearly identify signs of injury on the body chart using the code below.<br>
										Refer for an urgent medical assessment and imaging if signs of head trauma or fracture.<br>
										Signs of
									",
									"form_field" => "html",
									"form_field_options" => "get_aoc_scale1",
								),
								
							),
						),

					),
				),
				
				"cfi" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"title" => "Signs of:x",
							"cells" => array(
								
								"bruse" => array(
									"field_label" => "Brusing (B)",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
								"lacerations" => array(
									"field_label" => "Lacerations (L)",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
								"Swelling" => array(
									"field_label" => "Swelling (S)",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
							),
						),

						array(
							"title" => "Signs of:x",
							"cells" => array(
								
								"redness" => array(
									"field_label" => "Redness (R)",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
								"abrasions" => array(
									"field_label" => "Abrasions (A)",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
								"limbs" => array(
									"field_label" => "Signs of shortnening of limbs",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
							),
						),

						array(
							"title" => "Signs of:x",
							"cells" => array(
								
								"limbm" => array(
									"field_label" => "Signs of restricted limb movement",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
								"wbear" => array(
									"field_label" => "Signs of inability to weight bear",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
								"apressure" => array(
									"field_label" => "Signs of pain on applying pressure",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
							),
						),

						array(
							"title" => "Signs of:x",
							"cells" => array(
								
								"deformity" => array(
									"field_label" => "Signs of deformity",
									"field_label" => "Lacerations (L)",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
								"hinj" => array(
									"field_label" => "Signs of Head injury",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
								"facej" => array(
									"field_label" => "Signs of Facial Injury",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
							),
						),

					),
				),
				
				"atk" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"atkc" => array(
									"field_label" => "Comments/Actions taken",
									"form_field" => "textarea",
								),
								
							),
						),

					),
				),
				
				"h2sec" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"bruse" => array(
									"field_label" => "<h3>2. (Nursing/Responder) Post-fall Base-line Observations</h3>",
									"form_field" => "html",
								),
								
								"catkk" => array(
									"field_label" => "<h3>Comments/Actions taken</h3>",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"iobs" => array(
									"field_label" => "2.1 Initial Observations:",
									"form_field" => "textarea",
								),
								
								"inews" => array(
									"field_label" => "Initial NEWS: Initial GCS:",
									"form_field" => "textarea",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"idfm" => array(
									"field_label" => "2.21 Identify and Make safe any environmental hazards",
									"form_field" => "textarea",
								),
								
								"iactk" => array(
									"field_label" => "Action taken:",
									"form_field" => "textarea",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"idfm" => array(
									"field_label" => "2.3 Monitor the patient<br>Half hourly observations for 2 hours post fall then hourly over 4-hour period and then 2 hourly up to 24 hours<br>
									Include neuro observations if head injury suspected
									",
									"form_field" => "html",
								),
								
								"iactk" => array(
									"field_label" => "Record ongoing monitoring in the patient's nursing records",
									"form_field" => "textarea",
								),
								
							),
						),

					),
				),
				
				"h3sec" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"h3pdes" => array(
									"field_label" => "3 Please describe the fall that occurred: (Factual, do not use names, identify cause)",
									"form_field" => "textarea",
								),
								
								"h3num" => array(
									"field_label" => "number:",
									"form_field" => "textarea",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"ctri" => array(
									"field_label" => "Could this result in a potential SI?",
									"form_field" => "textarea",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"dch3" => array(
									"field_label" => "Date completed:",
									"form_field" => "date-5",
								),
								
								"tch3" => array(
									"field_label" => "Time Completed:",
									"form_field" => "text",
									"placeholder" => "HH:mm",
								),
								
							),
						),

					),
				),
				
				"h4sec" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"h4ir" => array(
									"field_label" => "4 Informed relevant contacts (RMO to complete part 1/Consultant",
									"form_field" => "html",
								),
								
							),
						),

					),
				),
				
				"h4val" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"h4dti" => array(
									"field_label" => "Date & time informed:",
									"form_field" => "date-5time",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"h4in" => array(
									"field_label" => "4.1 Inform next of kin (with consent) ",
									"form_field" => "textarea",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"h4dt2" => array(
									"field_label" => "Date & time informed:",
									"form_field" => "textarea",
								),
								
							),
						),

					),
				),

				"h5sec" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"h4dti" => array(
									"field_label" => "5. Members of the Post-Falls Team",
									"form_field" => "html",
								),
								
							),
						),

					),
				),
				
				"h5val" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"title" => "Team member informed: (Name and date)",
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"h5nrs" => array(
									"field_label" => "Nursing",
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
								
								"h5wr" => array(
									"field_label" => "Ward Manager",
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
								
							),
						),

						array(
							"cells" => array(
								
								"h5dr" => array(
									"field_label" => "Doctor",
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
								
								"h5phy" => array(
									"field_label" => "Physiotherapist",
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
								
								"h5phr" => array(
									"field_label" => "Pharmacist (as advised by Falls Team)",
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
								
							),
						),

					),
				),
				
				"h6sec" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"h6cp" => array(
									"field_label" => "6 Care plan review (Nurse in charge)",
									"form_field" => "html",
								),
								
							),
						),

					),
				),
				
				"h6val" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"h6rm" => array(
									"field_label" => "Review multifactorial risk assessment and care plan on meditech <br>Detail any changes in falls intervention:",
									"form_field" => "textarea",
								),
								
								"h6dt" => array(
									"field_label" => "Date:",
									"form_field" => "date-5",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"h6rau" => array(
									"field_label" => "Reassess and update mobility status",
									"form_field" => "textarea",
								),
								
								"h6dt2" => array(
									"field_label" => "Date:",
									"form_field" => "date-5",
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

	function __object_fs_bedrail_risk_assessment(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Bedrail risk assessment",
			),
			"fields" => array(
				
				"ir" => array(
					"field_label" => "This Risk Assessment Tool is an aide memoire for staff. It should be used in conjunction with the Bedrails Algorithm (see reverse) and local guidance for the safe use of bedrails and falls prevention. This document does NOT replace the need for clinical judgement.",
					"form_field" => "html",
				),

						/*array(
							"cells" => array(
								
								"rn" => array(
									"field_label" => "Patient Name:",
									"form_field" => "text",
								),
								
								"rdob" => array(
									"field_label" => "DOB:",
									"form_field" => "date-5",
								),
								
							),
						),*/

				"sec1" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"s1ua" => array(
									"field_label" => "Use algorithm on the reverse of this document when completing this Risk Assessment",
									"form_field" => "html",
								),
								
							),
						),

					),
				),
				
				"s1tb" => array(
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
									"id" => "lbl",
									"text" => "Section one",
									"rows_text" => array( 
										"Is the patient at risk of climbing out of bed?",
										"Is the patient agitated or confused?",
										"Does using bedrails present a higher risk to the patients than falling out of bed?",
									),
								),

								array(
									"id" => "y1",
									"unique" => 1,
									"text" => "",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no_na",
								), 
							),
						),
					),
				),
				
				"s1end" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"ir" => array(
									"field_label" => "See Algorithm on reverse for guidance",
									"form_field" => "html",
								),
								
							),
						),

					),
				),

				"s2tb" => array(
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
									"id" => "lbl",
									"text" => "Section two",
									"rows_text" => array( 
										"Has an alternative to bedrails been considered, ie see bedrails algorithm box 2",
										"Is the patient likely to roll, slip or slide from the bed?",
										"Has the patient been consulted regarding the use of bedrails?",
										"Does the patient understand the purpose of bedrails? Consider communication difficulties andphysical/cognitive condition.",
										"Has the decision to use or not use bedrails been discussed with relatives/principal carer?",
										"Has the patient/relatives/principal carer been given a copy of the bedrail information leaflet?",
									),
								),

								array(
									"id" => "y2",
									"unique" => 1,
									"text" => "",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no_na",
								), 
							),
						),
					),
				),
				
				"s2end" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"iytd" => array(
									"field_label" => "If yes, to any of Section two, then bedrails may be appropriate however, consider the following points",
									"form_field" => "html",
								),
								
							),
						),

					),
				),

				"s3tb" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 7,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "lbl",
									"text" => "Section three",
									"rows_text" => array( 
										"Is the patient small in stature?",
										"Does the patient have an unusually large or small head?",
										"When the bedrail is fitted is there a gap between the lower rail and mattress?",
										"Are there large spaces between the lower rail and mattress?",
										"Does the bedrail move away from the side of the mattress when in use?",
										"Will the bedrail fall off the bed?",
										"Will any of above create an entrapment hazard?",
									),
								),

								array(
									"id" => "y3",
									"unique" => 1,
									"text" => "",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no_na",
								), 
							),
						),
					),
				),
				
				"s3end" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"s3trg" => array(
									"field_label" => "If yes to Section three, bedrails are not appropriate",
									"form_field" => "html",
								),
								
							),
						),

					),
				),

				"s4tb" => array(
					//row 1
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 8,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "lbl",
									"text" => "Section four",
									"rows_text" => array( 
										"The gap between the bedrail and the headboard must be less than 60mm or greater than 250mm",
										"Has the bedrail been fitted correctly?",
										"Is the bedrail secure?",
										"Is the bedrail compatible with the bed frame it will be fitted to?",
										"Are the bedrails being used in good working order?",
										"Does the patient have access to a call bell at all times?",
										"If pressure relieving overlay mattress, or air filled mattress in use, are extra height bedrails fitted?",
										"If bariatric bed in use is a compatible extra wide mattress fitted?",
									),
								),

								array(
									"id" => "y4",
									"unique" => 1,
									"unique" => 1,
									"text" => "",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no_na",
								), 
							),
						),
					),
				),
				
				"s5tb" => array(
					//row 1
					"form_field" => "fields_in_table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 8,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								
								"hdtm" => array(
									"field_label" => "Has the decision been made to use bedrails?",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
								"htdm" => array(
									"field_label" => "Date:",
									"form_field" => "date-5",
								),
								
							),
						),

						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 8,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								
								"brck" => array(
									"field_label" => "Bedrails checked by:",
									"id" => "brck",
									"form_field" => "calculated",
									"required_field" => "no",
									
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( 'givby' ) ),
									),
									
									'attributes' => ' action="?action=users&todo=get_users_select2" minlength="0" ',
									"class" => ' select2 ',
								),
								
								"brdat" => array(
									"field_label" => "Date:",
									"form_field" => "date-5",
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

	function __object_fs_falls_risk_assessment_tool(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "FALLS RISK ASSESSMENT TOOL",
			),
			"fields" => array(
				
				"intro" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 18,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;width: 50%;",
						),

						array(
							"cells" => array(
								
								"rf1" => array(
									"field_label" => "RISK FACTOR",
									"form_field" => "html",
								),
								
								"vl1" => array(
									"field_label" => "LEVEL",
									"form_field" => "html",
								),
								
								"rs1" => array(
									"field_label" => "RISK SCORE",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"rf2" => array(
									"field_label" => "<h3><strong>RECENT FALLS</strong></h3>(To score this, complete history of falls, overleaf)",
									"form_field" => "html",
									"rowspan" => "4",
								),
								
								"vl2" => array(
									"field_label" => "none in last 12 months",
									"form_field" => "text",
								),
								
								"rs2" => array(
									"field_label" => "2",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"vl3" => array(
									"field_label" => "one or more between 3 and 12 months ago",
									"form_field" => "text",
								),
								
								"rs3" => array(
									"field_label" => "4",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"vl4" => array(
									"field_label" => "one or more in last 3 months",
									"form_field" => "text",
								),
								
								"rs4" => array(
									"field_label" => "6",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"vl5" => array(
									"field_label" => "one or more in last 3 months whilst inpatient / resident",
									"form_field" => "text",
								),
								
								"rs5" => array(
									"field_label" => "8",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"rf6" => array(
									"field_label" => "<h3><strong>MEDICATIONS</strong></h3><br>(Sedatives, Anti-Depressants, Anti-Parkinson’s, Diuretics, Anti-hypertensives, hypnotics)",
									"form_field" => "html",
									"rowspan" => "4",
								),
								
								"vl6" => array(
									"field_label" => "not taking any of these",
									"form_field" => "text",
								),
								
								"rs6" => array(
									"field_label" => "1",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"vl7" => array(
									"field_label" => "taking one",
									"form_field" => "text",
								),
								
								"rs7" => array(
									"field_label" => "2",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"vl8" => array(
									"field_label" => "taking two",
									"form_field" => "text",
								),
								
								"rs8" => array(
									"field_label" => "3",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"vl9" => array(
									"field_label" => "taking more than two",
									"form_field" => "text",
								),
								
								"rs9" => array(
									"field_label" => "4",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"rf11" => array(
									"field_label" => "<h3><strong>PSYCHOLOGICAL</strong></h3><br>(Anxiety, Depression, Cooperation, Insight or Judgement esp. re mobility)",
									"form_field" => "html",
									"rowspan" => "4",
								),
								
								"vl11" => array(
									"field_label" => "does not appear to have any of these",
									"form_field" => "text",
								),
								
								"rs11" => array(
									"field_label" => "1",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"vl12" => array(
									"field_label" => "appears mildly affected by one or more",
									"form_field" => "text",
								),
								
								"rs12" => array(
									"field_label" => "2",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"vl13" => array(
									"field_label" => "appears moderately affected by one or more",
									"form_field" => "text",
								),
								
								"rs13" => array(
									"field_label" => "3",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"vl14" => array(
									"field_label" => "appears moderately affected by one or more",
									"form_field" => "text",
								),
								
								"rs14" => array(
									"field_label" => "4",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"rf15" => array(
									"field_label" => "<h3><strong>COGNITIVE STATUS</strong></h3><br>(AMTS: Hodkinson Abbreviated, Mental Test Score)",
									"form_field" => "html",
									"rowspan" => "4",
								),
								
								"vl15" => array(
									"field_label" => "AMTS 9 or 10 / 10 OR intact",
									"form_field" => "text",
								),
								
								"rs15" => array(
									"field_label" => "1",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"vl16" => array(
									"field_label" => "AMTS 7-8 mildly impaired",
									"form_field" => "text",
								),
								
								"rs16" => array(
									"field_label" => "2",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"vl17" => array(
									"field_label" => "AMTS 5-6 mod impaired",
									"form_field" => "text",
								),
								
								"rs17" => array(
									"field_label" => "3",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"vl18" => array(
									"field_label" => "AMTS 4 or less severely impaired",
									"form_field" => "text",
								),
								
								"rs18" => array(
									"field_label" => "4",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"vl19" => array(
									"field_label" => "(Low Risk: 5-11 Medium: Risk: 12-15 High Risk: 16-20) <h3><strong>RISK SCORE</strong></h3>",
									"form_field" => "html",
									"colspan" => "2",
								),
								
								"rs19" => array(
									"field_label" => "/20",
									"form_field" => "html",
								),
								
							),
						),

					),
				),
				
				"hrs" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 2,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;width: 50%;",
						),

						array(
							"cells" => array(
								
								"rf10" => array(
									"field_label" => "<h3><strong>Automatic High Risk Status:</strong></h3>(if ticked then circle HIGH risk below)",
									"form_field" => "html",
								),

							),
						),

						array(
							"cells" => array(
								
								"rf21" => array(
									"field_label" => "",
									"form_field" => "checkbox",
									"form_field_options" => "get_arisk_status",
								),
								
							),
						),

					),
				),

				"rst" => array(
					"field_label" => "FALL RISK STATUS:",
					"form_field" => "checkbox",
					"form_field_options" => "get_risk_fall_status",
				),
				
				"rstc" => array(
					"field_label" => "IMPORTANT: IF HIGH, COMMENCE FALL ALERT",
					"form_field" => "html",
				),
				
				"p2" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 11,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;width: 50%;",
						),

						array(
							"cells" => array(
								
								"rf22" => array(
									"field_label" => "<h3><strong>PART 2: RISK FACTOR CHECKLIST</strong></h3>",
									"form_field" => "html",
									"colspan" => "2",
								),

								"yn22" => array(
									"field_label" => "Y/N",
									"form_field" => "html",
								),

							),
						),

						array(
							"cells" => array(
								
								"rfx23" => array(
									"field_label" => "Vision",
									"form_field" => "html",
								),
								
								"rfy23" => array(
									"field_label" => "Reports / observed difficulty seeing - objects / signs / finding way around",
									"form_field" => "html",
								),
								
								"rfz23" => array(
									"field_label" => "",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"rfx24" => array(
									"field_label" => "Mobility",
									"form_field" => "html",
								),
								
								"rfy24" => array(
									"field_label" => "Mobility status unknown or appears unsafe / impulsive / forgets gait aid",
									"form_field" => "html",
								),
								
								"rfz24" => array(
									"id" => "rfz24",
									"unique" => 1,
									"field_label" => "",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"rfx25" => array(
									"field_label" => "Transfers",
									"form_field" => "html",
								),
								
								"rfy25" => array(
									"field_label" => "Transfer status unknown or appears unsafe ie. over-reaches, impulsive",
									"form_field" => "html",
								),
								
								"rfz25" => array(
									"id" => "rfz25",
									"unique" => 1,
									"field_label" => "",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"rfx26" => array(
									"field_label" => "Behaviours",
									"form_field" => "html",
								),
								
								"rfy26" => array(
									"field_label" => "Observed or reported agitation, confusion, disorientation<br>Difficulty following instructions or non-compliant (observed or known)",
									"form_field" => "html",
								),
								
								"rfz26" => array(
									"id" => "rfz26",
									"unique" => 1,
									"field_label" => "",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"rfx27" => array(
									"field_label" => "Activities of Daily Living (A.D.L’s)",
									"form_field" => "html",
									"rowspan" => "3",
								),
								
								"rfy27" => array(
									"field_label" => "Observed risk-taking behaviours, or reported from referrer / previous facility",
									"form_field" => "html",
								),
								
								"rfz27" => array(
									"id" => "rfz27",
									"unique" => 1,
									"field_label" => "",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"rfx28" => array(
									"field_label" => "Observed risk-taking behaviours, or reported from referrer / previous facility",
									"form_field" => "html",
								),
								
								"rfy28" => array(
									"id" => "rfy28",
									"unique" => 1,
									"field_label" => "",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"rfx29" => array(
									"field_label" => "Unsafe footwear / inappropriate clothing",
									"form_field" => "html",
								),
								
								"rfy29" => array(
									"id" => "rfy29",
									"unique" => 1,
									"field_label" => "",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"rfx20" => array(
									"field_label" => "Environment",
									"form_field" => "html",
								),
								
								"rfy20" => array(
									"field_label" => "Difficulties with orientation to environment i.e. areas between bed / bathroom / dining room",
									"form_field" => "html",
								),
								
								"rfz20" => array(
									"id" => "rfz20",
									"unique" => 1,
									"field_label" => "",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"rf21" => array(
									"field_label" => "Nutrition",
									"form_field" => "html",
								),
								
								"rf22" => array(
									"field_label" => "Underweight / low appetite",
									"form_field" => "html",
								),
								
								"rf23" => array(
									"id" => "rf23",
									"unique" => 1,
									"field_label" => "",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"rf24" => array(
									"field_label" => "Continence",
									"form_field" => "html",
								),
								
								"rf25" => array(
									"field_label" => "Reported or known urgency / nocturia / accidents",
									"form_field" => "html",
								),
								
								"rf26" => array(
									"id" => "rf26",
									"unique" => 1,
									"field_label" => "",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
								),
								
							),
						),

					),
				),

				"p2_ctd" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"num_of_rows" => 6,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;width: 50%;",
						),

						array(
							"cells" => array(
								
								"rf27" => array(
									"field_label" => "<h3><strong>HISTORY OF FALLS</strong></h3>Note: For an accurate history, consult patient/resident / family / medical records.",
									"form_field" => "html",
									"colspan" => "4",
								),

							),
						),

						array(
							"cells" => array(
								
								"rf28" => array(
									"field_label" => "<h3><strong>Falls prior to this admission</strong></h3>(home or referring facility) and/or during current stay",
									"form_field" => "checkbox",
									"form_field_options" => "get_empty_checkbox",
									"tooltip" => "If ticked, detail most recent below",
									"colspan" => "4",
								),

							),
						),

						array(
							"cells" => array(
								
								"rf001" => array(
									"field_label" => "<h3><strong>CIRCUMSTANCES OF RECENT FALLS:</strong></h3>Information obtained from",
									"form_field" => "text",
									"colspan" => "4",
								),

							),
						),

						array(
							"cells" => array(
								
								"rf29" => array(
									"field_label" => "Last fall:",
									"form_field" => "html",
								),
								
								"rf30" => array(
									"field_label" => "Time ago",
									"form_field" => "text",
								),
								
								"rf31" => array(
									"id" => "rf31",
									"unique" => 1,
									"field_label" => "",
									"form_field" => "radio",
									"form_field_options" => "get_fall_types",
								),
								
								"rf32" => array(
									"field_label" => "Diziness (Where? / Comment)",
									"form_field" => "textarea",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"rf33" => array(
									"field_label" => "Previous:",
									"form_field" => "html",
								),
								
								"rf34" => array(
									"field_label" => "Time ago",
									"form_field" => "text",
								),
								
								"rf35" => array(
									"id" => "rf35",
									"unique" => 1,
									"field_label" => "",
									"form_field" => "radio",
									"form_field_options" => "get_fall_types",
								),
								
								"rf36" => array(
									"field_label" => "Diziness (Where? / Comment)",
									"form_field" => "textarea",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"rf37" => array(
									"field_label" => "Previous:",
									"form_field" => "html",
								),
								
								"rf38" => array(
									"field_label" => "Time ago",
									"form_field" => "text",
								),
								
								"rf39" => array(
									"id" => "rf39",
									"unique" => 1,
									"field_label" => "",
									"form_field" => "radio",
									"form_field_options" => "get_fall_types",
								),
								
								"rf40" => array(
									"field_label" => "Diziness (Where? / Comment)",
									"form_field" => "textarea",
								),
								
							),
						),

					),
				),
				
				"rst" => array(
					"field_label" => "<h3><strong>PART 3: ACTION PLAN</strong></h3>(for Risk factors identified in Part 1 & 2, list strategies below to manage falls risk. See tips in FRAT PACK)",
					"form_field" => "html",
				),
				
				"parts1" => array(
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
									"text" => "PROBLEM LIST",
									"form_field" => "textarea",
								),
								array(
									"id" => "max_dose",
									"text" => "INTERVENTION STRATEGIES / REFERRALS",
									"form_field" => "textarea",
								),
							),
						),
					),
				),
				
				"end" => array(
					//row 1
					"form_field" => "fields_in_table",
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
								'iacb' => array(
									"id" => "iacb",
									"field_label" => "INITIAL ASSESSMENT COMPLETED BY:",
									"form_field" => "calculated",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "iacb" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',

								),
								array(
									"id" => "d1t",
									"field_label" => "Date of Assessment:",
									"form_field" => "date-5",
								),
							),
						),
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
									"id" => "dxt",
									"field_label" => "PLANNED REVIEW",
									"form_field" => "textarea",
									"colspan" => '2',
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

	function __object_fs_haemodialysis(){
		$rr = array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "INTRADIALYSIS ASSESSMENT",
			),
			"fields" => array(
				
				"health_history_table" => array(
					"form_field" => "table",
					"title" => "",
					"rows" => array(
						array(
							"property" => array(
								"accept_values" => 1,
								"num_of_rows" => 10,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							"cells" => array(
								array(
									"id" => "time",
									"text" => "Time",
									"form_field" => "time",
								),
								array(
									"id" => "bp",
									"text" => "B/P",
									"form_field" => "text",
								),
								array(
									"id" => "pulse",
									"text" => "Pulse",
									"form_field" => "text",
								),
								array(
									"id" => "bpr",
									"text" => "BPR",
									"form_field" => "text",
								),
								array(
									"id" => "ufr",
									"text" => "UFR",
									"form_field" => "text",
								),
								array(
									"id" => "np",
									"text" => "NP",
									"form_field" => "text",
								),
								array(
									"id" => "vp",
									"text" => "VP",
									"form_field" => "text",
								),
								array(
									"id" => "ivf",
									"text" => "IVF",
									"form_field" => "text",
								),
								array(
									"id" => "hep",
									"text" => "HEP/hr",
									"form_field" => "text",
								),
								array(
									"id" => "rem",
									"text" => "Remarks",
									"form_field" => "text",
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
								"num_of_rows" => 2,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "drug1",
									"text" => " ",
									"rows_text" => array( 
										"PRE DIALYSIS", 
										"POST DIALYSIS", 
									),
								),
								array(
									"id" => "1",
									"text" => "NA<sup>+</sup>meq/l",
									"form_field" => "text",
								),
								array(
									"id" => "2",
									"text" => "K<sup>+</sup>meq/l",
									"form_field" => "text",
								),
								array(
									"id" => "3",
									"text" => "CL<sup>+</sup>meq/l",
									"form_field" => "text",
								),
								array(
									"id" => "4",
									"text" => "HCO<sub>3</sub>meq/l",
									"form_field" => "text",
								),
								array(
									"id" => "5",
									"text" => "UREA mg/dl",
									"form_field" => "text",
								),
								array(
									"id" => "6",
									"text" => "CREATINE mg/dl",
									"form_field" => "text",
								),
								array(
									"id" => "7",
									"text" => "RBS mg/dl",
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

		if( defined( 'HYELLA_TABLES_JSON_SUFFIX' ) && HYELLA_TABLES_JSON_SUFFIX ){
			switch( HYELLA_TABLES_JSON_SUFFIX ){
			case '_caroline':
				$rr[ 'fields' ][ 'health_history_table' ][ 'rows' ][0][ 'cells' ][5][ 'text' ] = 'AP';
				$rr[ 'fields' ][ 'health_history_table' ][ 'rows' ][0][ 'cells' ][3][ 'text' ] = 'BFR';
			break;
			}
		}

		return $rr;
	}

	function _an_database_object_field_source(){
		return array(
			"" => "None",
			"adult_observation_chart" => "Adult Observation Chart",
			"early_warning_score" => "Early Warning Score",
			"bedrail_risk_assessment" => "Bedrail Risk Assessment",
			"falls_risk_assessment_tool" => "FALLS RISK ASSESSMENT TOOL",
			"post_falls_risk_assessment" => "POST FALLS RISK ASSESSMENTL",

			"haemodialysis" => "Haemodialysis Report",
		);
	}	
?>