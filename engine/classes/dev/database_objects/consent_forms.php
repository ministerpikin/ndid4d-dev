<?php 

	function __object_fs_kidney_transplant(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 2,
				"form_style" => "text-align:center;",
			),
			"fields" => array(
				
				"hheadr" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"title" => "CONSENT FOR KIDNEY TRANSPLANTATION",
							"accept_values" => 1,
							"merge_cells" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"i" => array(
									"field_label" => "I {field}",
									"form_field" => "text",
									"embed" => 1,
								),
								
								"of" => array(
									"field_label" => " of {field}<br>",
									"form_field" => "text",
									"embed" => 1,
								),
								
								// "cpl" => array(
								// 	"field_label" => '(Please write in capital letters)<br>',
								// 	"form_field" => "html",
								// ),
								
								"undego" => array(
									"field_label" => 'being of sound mind hereby consent to undergo the operation of {field} the effect and nature of which has been explained to me.<br>',
									"form_field" => "text",
									"embed" => 1,
								),
								
								"my" => array(
									"field_label" => 'My {field}',
									"form_field" => "text",
									"embed" => 1,
								),
								
								"ehose" => array(
									"field_label" => ' whose name is {field}<br>',
									"form_field" => "text",
									"embed" => 1,
								),
								
								"spl3" => array(
									"field_label" => '(Please state relationship to donor)<br>',
									"form_field" => "html",
								),
								
								"pgcv" => array(
									"field_label" => 'of {field}<br>',
									"form_field" => "text",
									"embed" => 1,
								),
								
								"spl4" => array(
									"field_label" => "(State address as in kidney donor's form)<br>",
									"form_field" => "html",
								),
								
								"fl4" => array(
									"field_label" => '',
									"form_field" => "text",
									"embed" => 1,
								),
								
								"hsof" => array(
									"field_label" => 'has offered to donate one of his/her kidneys to me.',
									"form_field" => "html",
								),
								
								"iund" => array(
									"field_label" => 'I understand that I will gave to take medication to prevent rejection to the, rest of my life',
									"form_field" => "html",
								),
								
								"ial" => array(
									"field_label" => 'I also consent to such further or alternatove operative measires as may be found necessary during the course of this operation and the administration od local or other anaesthetic for anu of the afore-mentioned purpose',
									"form_field" => "html",
								),
								
							),
						),

					),
				),
				
				"hheadr2" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "merge_cells" => 1,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"atkc1x" => array(
									"field_label" => "Patient's Signature <br>{psign}",
									"form_field" => "signature",
									"embed_signature" => '{psign}',
								),
								
								"atkc1" => array(
									"field_label" => "Date:",
									"form_field" => "date-5time",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"atkc2" => array(
									"field_label" => "Witness Signature <br>{wsign}",
									"form_field" => "signature",
									"embed_signature" => '{wsign}',
								),
								
								"atkc3" => array(
									"field_label" => "Date:",
									"form_field" => "date-5time",
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

	function __object_fs_kidney_donation(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 2,
				"title" => "ST. NICHOLAS HOSPITAL",
				"form_style" => "text-align:center;",
			),
			"fields" => array(
				
				"hheadr" => array(
					"form_field" => "fields_in_table",
					"title" => "CONSENT FOR KIDNEY DONATION",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							"merge_cells" => 1,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"i" => array(
									"field_label" => "I {field}",
									"form_field" => "text",
									"embed" => 1,
								),
								
								"of" => array(
									"field_label" => " of {field}<br>",
									"form_field" => "text",
									"embed" => 1,
								),
								
								// "cpl" => array(
								// 	"field_label" => '(Please write in capital letters)<br>',
								// 	"form_field" => "html",
								// ),
								
								"undego" => array(
									"field_label" => 'being of sound mind hereby voluntarily consent to donate one of my kidneys to my {field} ',
									"form_field" => "text",
									"embed" => 1,
								),
								
								"my" => array(
									"field_label" => 'Mr/Mrs./Miss {field}',
									"form_field" => "text",
									"embed" => 1,
								),
								
								"spl3" => array(
									"field_label" => '(Please state relationship to donor)<br>',
									"form_field" => "html",
								),
								
								"pgcv" => array(
									"field_label" => 'of {field}<br>',
									"form_field" => "text",
									"embed" => 1,
								),
								
								"hsof" => array(
									"field_label" => 'I have been informed about the pontential complications of the surgery. I also conesnt to such further or alternative measure as may be found necessary during the course of this operation and the administration of local or other anaesthetic for any of the afore-mentioned purposes',
									"form_field" => "html",
								),
								
								"iund" => array(
									"field_label" => 'I hereby give full and informed consent to donate one of my Kidneys',
									"form_field" => "html",
								),
								
							),
						),

					),
				), 
				
				"hheadr2" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "merge_cells" => 1,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"atkc1x" => array(
									"field_label" => "Patient's Signature <br>{psign}",
									"form_field" => "signature",
									// "embed" => 1,
									"embed_signature" => '{psign}',
								),
								
								"atkc1" => array(
									"field_label" => "Date:",
									"form_field" => "date-5time",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"atkc2" => array(
									"field_label" => "Witness Signature <br>{wsign}",
									"form_field" => "signature",
									"embed_signature" => '{wsign}',
									// "embed" => 1,
								),
								
								"atkc3" => array(
									"field_label" => "Date:",
									"form_field" => "date-5time",
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

	function __object_fs_dnar(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "DO NOT ATTEMPT RESUSCITATION (DNAR) ORDER FORM",
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
									"field_label" => "In the event of cardiac or respiratory arrest no attempts at cardiopulmonary resuscitation (CPR) are intended. All other appropriate treatment and care will be provided - Refer to SNG Do Not Attempt Resuscitate policy",
									"form_field" => "html",
								),
								
							),
						),

					),
				),
				
				"scl" => array(
					"title" => "<h4><strong>Part A</strong></h4><br>Section 1",
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"ir" => array(
									"field_label" => "Does the patient have capacity to make and communicate decisions about CPR?",
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
									"note" => 'if "YES" go to Section 2',
								),
								
								"ir2" => array(
									"field_label" => 'If "NO", are you aware of a valid advance decision refusing CPR which is relevant to the current condition?',
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
									"note" => 'if "YES" go to Section 6',
								),
								
								"ir3" => array(
									"field_label" => 'If "NO", has the patient appointed a legala representative to make decisions on their behalf?',
									"form_field" => "radio",
									"form_field_options" => "get_yes_no",
									"note" => 'if "YES" they must be consulted',
								),
								
							),
						),

					),
				),
				
				"scl2asc" => array(
					"field_label" => "All other decisions must be made in the patient's best interests and comply with the hospital standards. <br>Go to Section 2",
					"form_field" => "html",
				),
				
				"scl2" => array(
					"title" => "Section 2",
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"alert" => array(
									"field_label" => "<br>Summary of the main clinical pronlems and reasons whu CPR would be inappropriate, unsuccessfull or not in the patent's best interests:",
									"form_field" => "textarea",
								),
								
							),
						),

					),
				),
				
				"cfi_head" => array(
					"title" => "Section 3",
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"ir" => array(
									"field_label" => "Summary of communication with patient (or Welfare Attorney). If this decision had not been discussed with the patient or legal representative state the reason why:",
									"form_field" => "textarea",
								),
								
							),
						),

					),
				),
				
				"cfi" => array(
					"title" => "Section 4",
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"bruse" => array(
									"field_label" => "Summary of communication with the patient's relatives or friends:",
									"form_field" => "textarea",
								),
								
							),
						),

					),
				),
				
				"cfi2" => array(
					"title" => "Section 5",
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"bruse" => array(
									"field_label" => "Name of members of multidisciplinary team contributing to this decision:",
									"form_field" => "textarea",
								),
								
							),
						),

					),
				),
				
				"atk" => array(
					"title" => "Section 6",
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"atkc" => array(
									"field_label" => "Healthcare professional recording this DNACPR decision:",
									"form_field" => "html",
									"colspan" => 2,
								),
								
							),
						),

						array(
							"cells" => array(
								
								"atkc1" => array(
									"field_label" => "Name:",
									"form_field" => "text",
								),
								
								"atkc1x" => array(
									"field_label" => "Signature <br>{6sign}",
									"form_field" => "signature",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"atkc2" => array(
									"field_label" => "Position:",
									"form_field" => "text",
								),
								
								"atkc3" => array(
									"field_label" => "Date/Time:",
									"form_field" => "date-5time",
								),
								
							),
						),

					),
				),
				
				
				"h2sec" => array(
					"title" => "<h4><strong>Part B</strong></h4><br>Section 7",
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"bruse" => array(
									"field_label" => "I {field}, request limited emerfency care as herein described.<br>(Print patient's name)",
									"form_field" => "text",
									"embed" => 1,
								),

							),
						),

						array(
							"cells" => array(
								
								"iud" => array(
									"field_label" => "I understand DNAR means that if my heart stops beating of if I stop breathing, no medical procedure to restart breathing or heart functioning will be insitiuted",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"iud2" => array(
									"field_label" => "I understand this decision will <b>not</b> prevent me from obtaining other emergecy medical care by St. Nicholas hospital medical personnel and/or medical are directed by a physican prior to my death",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"iud3" => array(
									"field_label" => "I understand I may revoke this directive at anu time by having this form destroyed",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"iud4" => array(
									"field_label" => "I give permission for this information to be given to St. Nicholas hospital medical personnel, doctors, nurses or other health personnel as necessary to implement this directive.",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"iud5" => array(
									"field_label" => 'I hereby agree to the "Do Not Attempt Resuscitation" (DNAR) order.',
									"form_field" => "html",
								),
								
							),
						),

					),
				),
				
				"h2secxs" => array(
					// "title" => "<h4><strong>Part B</strong></h4><br>Section 7",
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"iobs" => array(
									"field_label" => "{decision-signature}<br>Patient or Legally Recognized Health Care Decesion-maker's Signature",
									"form_field" => "signature",
								),
								
								"inews" => array(
									"field_label" => "Date",
									"form_field" => "date-5time",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"iobs" => array(
									"field_label" => "Legally Recognized Health Care Decesion-maker's Relationship to Patient",
									"form_field" => "text",
									"colspan" => 2,
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
									"field_label" => "By signing this form, the legally recognized health care decesion-maker acknowledges that this request to forego resuscitative measures is consistent with the known desires of, and with the best interest of, the individual who is the subject of the form",
									"form_field" => "html",
								),
								
							),
						),

					),
				),
				
				"h2sec2" => array(
					"title" => "Section 8",
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"sacce" => array(
									"field_label" => "I affirm that this patient or legally recognized health care decision-maker is making an informed decision and that this directive is the expressed wish of the patient of legally recognized health care decision-maker. A copy of this form is in the patient's permanent medical record.",
									"form_field" => "text",
									"colspan" => 2,
								),
								
							),
						),

						array(
							"cells" => array(
								
								"catkkx" => array(
									"field_label" => "In the event of cardiac or respiratory arrest, no chest compressions, assisted ventilations, intubation, defibrillation, or cardiotonic medications are to be initiated",
									"form_field" => "html",
									"colspan" => 2,
								),
								
							),
						),

						array(
							"cells" => array(
								
								"qw" => array(
									"field_label" => "Physican's Signature <br>{physician-signature}",
									"form_field" => "signature",
									"embed_signature" => '{physician-signature}',
								),
								
								"qw1" => array(
									"field_label" => "Date",
									"form_field" => "date-5time",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"qw3" => array(
									"field_label" => "Print Name",
									"form_field" => "text",
								),
								
								"qw4" => array(
									"field_label" => "Telephone",
									"form_field" => "text",
								),
								
							),
						),

					),
				),
				
				"h3sec2" => array(
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
									"field_label" => "<b>Note:</b> Two (2) Part B forms are to be filled. One Part B form is to be kept by the patient and the other attached with filled Part A form to the patient's permanent medical records.",
									"form_field" => "html",
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

	function __object_fs_investigation(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "Patient Agreement to Investigation or Treatment",
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
									"field_label" => "Name of proposed procedure or course of treatment (incude brief explanation if medical term not clear)",
									"form_field" => "html",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"irq" => array(
									"field_label" => "",
									"form_field" => "textarea",
								),
								
							),
						),

					),
				),
				
				"scl" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"ir" => array(
									"field_label" => "<b>Statement of health professional</b> (to be filled in by health professional iwth appopriate knowledge of the proposed procedure)<br><br>I have explained the procedure to the patient. In particular, I have explained: ",
									"form_field" => "html",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"ir2" => array(
									"field_label" => 'The intended benefits {field}',
									"form_field" => "textarea",
									"embed" => 1,
								),
								
							),
						),
						array(
							"cells" => array(
								
								"ir2x" => array(
									"field_label" => 'Serious or frequently occurring risks {field}',
									"form_field" => "textarea",
									"embed" => 1,
								),
								
							),
						),

						array(
							"cells" => array(
								
								"ir3" => array(
									"field_label" => 'Any extra procedures which may become necessary during the procedure',
									"form_field" => "checkbox",
									"form_field_options" => "get_extra_procedure_options",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"ir3x" => array(
									"field_label" => '',
									"form_field" => "textarea",
								),
								
							),
						),

					),
				),
				
				"scl2asc" => array(
					"field_label" => "I have also discussed what the procedure is likely to involve, the benefits and risks of any available alternative treatments (including no treatment) and any particular concerns of this patient",
					"form_field" => "html",
				),
				
				"scl2" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"alert" => array(
									"field_label" => "The following leaflet/tape has been provided",
									"form_field" => "text",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"alert" => array(
									"field_label" => "This procedure will involve",
									"form_field" => "checkbox",
									"form_field_options" => "include_procedure_options",
								),
								
							),
						),

					),
				),
				
				"cfi_head" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"asx" => array(
									"field_label" => "Signed<br>{asigned}",
									"form_field" => "signature",
									"embed_signature" => '{asigned}',
								),
								
								"asx1" => array(
									"field_label" => "Date",
									"form_field" => "date-5time",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"asx3" => array(
									"field_label" => "Name (PRINT)",
									"form_field" => "text",
								),
								
								"asx4" => array(
									"field_label" => "Job Title",
									"form_field" => "text",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"asx4" => array(
									"field_label" => "Contact details (if patient wished to discuss options later)",
									"form_field" => "textarea",
									"colspan" => 2,
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
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"bruse" => array(
									"field_label" => "Statement of interpreter (where appropriate)<br>I have innterpreted the above information to the patient to the best of my ability and in a way in which I believe s/he can understand",
									"form_field" => "textarea",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"f1" => array(
									"field_label" => "Signed<br>{asigned2}",
									"form_field" => "signature",
									"embed_signature" => '{asigned2}',
								),
								
								"f2" => array(
									"field_label" => "Date",
									"form_field" => "date-5time",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"f3" => array(
									"field_label" => "Name (PRINT)",
									"form_field" => "text",
									"colspan" => 2,
								),
								
							),
						),

					),
				),
				
				"cfi2" => array(
					"title" => "Statement of Patient",
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"bruse" => array(
									"field_label" => "Please read this form carefully if your treatment has been planned in advance, you should already have your own copy of page 2 which described the benefits and risks of the proposed treatment. If not, you will be offered a copy now. If you have any further questions, do ask - we are here to help you. You have the right to change your mind at any time before the procedure is performed, including after you have signed this form<br><br>
										I agree to the procedure or course of treatment described on this form.<br><br>
										I understand that I will have the opportunity to discuss the details of anaesthesia with an anaesthetist before the procedure, unless the urgency if my situation prevents this. (This only applies to patients having general or regional anaesthesia)<br><br>
										I understand that any procedure in addition to those described on this form will only be carried out if it is necessary ot save my life or to prevent serious harm to my health<br><br>
										I have been told about additional procedures which may become necessary during my treatment. I have listed below any procedures which I do not wish to be carried out without further discussion
									",
									"form_field" => "html",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"olsit" => array(
									"field_label" => "",
									"form_field" => "textarea",
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
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"atkc" => array(
									"field_label" => "Patient's signature <br>{psign}",
									"form_field" => "signature",
									"embed_signature" => '{psign}',
								),
								
								"psd" => array(
									"field_label" => "Date",
									"form_field" => "date-5time",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"atkc1" => array(
									"field_label" => "Name (PRINT)",
									"form_field" => "text",
									"colspan" => 2,
								),
								
							),
						),

						array(
							"cells" => array(
								
								"atkc1" => array(
									"field_label" => "A witness should sign below if the patient is unable to sign but has indicated his or her consent. Young people/children may also like a parent to sign here (see notes)",
									"form_field" => "html",
									"colspan" => 2,
								),
								
							),
						),

					),
				),
				
				"atkx" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"atkcx" => array(
									"field_label" => "Signature <br>{psign2}",
									"form_field" => "signature",
									"embed_signature" => '{psign2}',
								),
								
								"psd2" => array(
									"field_label" => "Date",
									"form_field" => "date-5time",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"atkc1x" => array(
									"field_label" => "Name (PRINT)",
									"form_field" => "text",
								),
								
								"1324" => array(
									"field_label" => "Relationship to Patient",
									"form_field" => "text",
								),
								
							),
						),

					),
				),
				
				"atk9" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"cct" => array(
									"field_label" => "Confirmation of consent (to be completed by a health professional when the patient is admitted for the procedure. If the patient has signed the form in advance)<br><br>
									On behalf of the team treating the patient, I have confirmed with the patient that s/he has no further questions and wishes the procedure to go ahead
									",
									"form_field" => "html",
								),
								
							),
						),

					),
				),
				
				"asc5" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"atkcx3" => array(
									"field_label" => "Signature <br>{psign3}",
									"form_field" => "signature",
									"embed_signature" => '{psign3}',
								),
								
								"psd23" => array(
									"field_label" => "Date",
									"form_field" => "date-5time",
								),
								
							),
						),

						array(
							"cells" => array(
								
								"atkc1x3" => array(
									"field_label" => "Name (PRINT)",
									"form_field" => "text",
								),
								
								"13243" => array(
									"field_label" => "Job Title",
									"form_field" => "text",
								),
								
							),
						),

					),
				),
				
				
				"h2sec" => array(
					"title" => "Important notes (tick if available)",
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"bsruse" => array(
									"field_label" => "See also advance directive/Living Will (e.g. Jehova's Witness form). Patient has withdrawn consent (ask patient to sign/date here)",
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

	function __object_fs_blood_transfuse(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "SUPPLEMENTARY CONSENT FORM TO EXCLUDE BLOOD TRANSFUSION",
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
									"field_label" => "Doctor with understanding of patient blood management:<br><b>NATURE OF OPERATION< INVESTIGATION, TREATMENT OR CONDITION:</b>",
									"form_field" => "html",
									"colspan" => 2,
								),
								
							),
						),
						array(
							"cells" => array(
								
								"ir1" => array(
									"field_label" => "I confirm that I have explained the operation, investigation, treatment or condition, and the risk of bleeding to {field} {field2}",
									"form_field" => "text",
									"embed" => 1,
									"colspan" => 2,
								),
								
								"ir2" => array(
									"field_label" => " with EMR number {field}. I have discussed any suitable options available which may reduce the risk of requiring blood, and treatments that are available if blood loss does occur. I have discussed with the patient in terms which in my judgment are suited to the understanding of the person named below. I have given this information freely and the patient is under no duress. I further confirm that I have emphasised my clinical judgment of the potential risks to the patient who nonetheless understood and imposed the limitation expressed below. I am satisfied that the patient has capacity to make this decision. I acknowledge that this limited consent will not be over-ridden unless revoked or modified.",
									"form_field" => "text",
									"embed" => 2,
									"embed_key" => '{field2}',
								),
								
							),
						),
						array(
							"cells" => array(
								
								"asx" => array(
									"field_label" => "Signed<br>{asigned}",
									"form_field" => "signature",
									"embed_signature" => '{asigned}',
								),
								
								"asx1" => array(
									"field_label" => "Date",
									"form_field" => "date-5time",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"asx3" => array(
									"field_label" => "Name (PRINT)",
									"form_field" => "text",
								),
								
								"asx4" => array(
									"field_label" => "Job Title",
									"form_field" => "text",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"asx4" => array(
									"field_label" => "An SNH Consent Form should also be completed when applicable e.g. Pre-operative",
									"form_field" => "html",
									"colspan" => 2,
								),
								
							),
						),

					),
				),
				
				"scl" => array(
					"form_field" => "fields_in_table",
					"title" => "<strong>PATIENT</strong> <h6>(this part to be completed by the patient)</h6>",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"ir" => array(
									"field_label" => "I with the above stated details confirm that I have read the words above. I confirm that the doctor named on this form has explained to me the nature and purpose of the procedure proposed, and/or the nature of my condition that may cause me to need blood. I have agreed to the use of non-blood volume expanders and pharmaceuticals that control haemorrhage and/or stimulate the production of red blood cells. I am prepared to accept diagnostic procedures such as blood sampling.",
									"form_field" => "html",
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
								"num_of_rows" => 13,
								"class" => "heading-row",
								"style" => "text-align:center; font-weight:bold;",
							),
							//cells
							"cells" => array(
								array(
									"id" => "drug1",
									"text" => " ",
									"rows_text" => array(
										"Cryoprecipitate", 
										"Albumin", 
										"Immunogloblulins", 
										"Recombinant clotting factors (rVila)",
										"Prothrombin Complex Concentrate (PCC)",
										"Fibrinogen concentrate", 
										"Fibrin glues and sealants", 
										"Erythropoeitin", 
										"Intra-op Blood Salvage", 
										"Post-op Blood Salvage", 
										"Plasmapheresis", 
										"Dialysis", 
										"Other restrictions (specify)", 
									),
								),
								array(
									"id" => "Options",
									"text" => "Options",
									"form_field" => "radio",
									"form_field_options" => "get_accept_decline2",
									"unique" => 2,
								),
							),
						),
					),
				),

				"scl2asc" => array(
					"field_label" => "If required to SAVE MY LIFE: (Please tick as needed)",
					"form_field" => "html",
				),
				
				"scl2" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"a1" => array(
									"field_label" => "Red Cells",
									"form_field" => "radio",
									"form_field_options" => "get_accept_decline",
								),
								
								"a2" => array(
									"field_label" => "Platelets",
									"form_field" => "radio",
									"form_field_options" => "get_accept_decline",
								),
								
								"a3" => array(
									"field_label" => "Fresh Frozen Plasma (FFP)",
									"form_field" => "radio",
									"form_field_options" => "get_accept_decline",
								),
								
							),
						),

					),
				),
				
				"cfi_head" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"asx" => array(
									"field_label" => "I have told the doctor that I am one of Jehovah's Witnesses with firm religious convictions. With full realization of the implications of this position and knowledge that it may pose additional risks to my health or life, I have decided to impose the following restrictions on what the doctors may do in the course of the procedure or when treating my condition. I do so exercising my own choice. My wishes are as detailed below: I confirm the doctor has explained the treatments listed above and I understand them.<br><br>I understand that this limitation of consent will remain in force and bind all those treating me unless and until I expressly revoke it either verbally or in writing, or I am discharged from the hospital.<br><br>I understand that the limitation of my consent described above will be regarded as absolute by all the doctors who treat me, and will not be overridden in any circumstances by a purported consent of a relative or other person or body unless I have given them legal authority to act as my proxy.<br><br>I understand that such refusal will be regarded as remaining in force and binding upon those who care for me even though I may be unconscious or affected by medication or medical condition rendering me incapable of expressing my wishes and that doctors treating me will continue to be bound by my refusal even though they may believe that the treatment is immediately necessary in order to save my life.<br><br>I further consent to any other procedure that may be immediately necessary to save my life or health, but I attach the same restrictions I have described above to the performance of that procedure<br><br>I further understand that details of my treatment and any consequences resulting will not be disclosed to any other person outside of my medical team without my express consent or that of my authorised proxy unless required by law.",
									"form_field" => "html",
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
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"f1" => array(
									"field_label" => "Signed<br>{psign}",
									"form_field" => "signature",
									"embed_signature" => '{psign}',
								),
								
								"f2" => array(
									"field_label" => "Dated",
									"form_field" => "date-5time",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"f3" => array(
									"field_label" => "Patient Name",
									"form_field" => "text",
									"colspan" => 2,
								),
								
							),
						),

					),
				),
				
				"cfi2" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"f1x" => array(
									"field_label" => "A witness should sign if the patient is unable to sign but has indicated his or her consent.",
									"form_field" => "html",
									"colspan" => 2,
								),
								
							),
						),

						array(
							"cells" => array(
								
								"f1x" => array(
									"field_label" => "Witness Signature<br>{wsign}",
									"form_field" => "signature",
									"embed_signature" => '{wsign}',
								),
								
								"f2x" => array(
									"field_label" => "Dated",
									"form_field" => "date-5time",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"f3x" => array(
									"field_label" => "Witness Name",
									"form_field" => "text",
									"colspan" => 2,
								),
								
							),
						),

					),
				),
				
				"brusex" => array(
					"field_label" => "Note: This form is not suitable for patients with impared capacity or children <18yrs",
					"form_field" => "html",
				),
				
				"bruse2" => array(
					"field_label" => "<u>Copies of this document should be inserted into the medical notes and retained by the patient</u>",
					"form_field" => "html",
				),
				
				"comment" => array(
					"field_label" => "Comment",
					"form_field" => "textarea",
				),
				
			),
		);
	}

	function __object_fs_blood_transfuse2(){
		return array(
			"type" => "field_group",
			"property" => array(
				"show_name" => 1,
				"title" => "BLOOD TRANSFUSION CONSENT FORM",
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
									"field_label" => "NAME(S) OF THE PROPOSED BLOOD PRODUCT/COMPONENTS FOR TRANSFUSSION",
									"form_field" => "html",
									"colspan" => 4,
								),
								
							),
						),
						array(
							"cells" => array(
								
								"ir1" => array(
									"field_label" => "Whole Blood",
									"form_field" => "text",
								),
								
								"ir2" => array(
									"field_label" => "Packed Cells",
									"form_field" => "text",
								),
								
								"ir3" => array(
									"field_label" => "Fresh Frozen Plasma",
									"form_field" => "text",
								),
								
								"ir4" => array(
									"field_label" => "Platelet Concentrates",
									"form_field" => "text",
								),
								
							),
						),

					),
				),
				
				"scl" => array(
					"form_field" => "fields_in_table",
					"title" => "<strong>PATIENT</strong> <h6>(this part to be completed by the patient)</h6>",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),
						array(
							"cells" => array(
								
								"ib1" => array(
									"field_label" => "I have been provided with the requisite information, with detailed explanations, and have verbalized understanding: and thereafter I consent, authoring Dr. {field}(Doctor's Name) and his/her team with associates or assistants of his/her choice to perform the proposed transfusion mentioned hereinabove.",
									"embed" => 1,

									"id" => "ib1",
									"form_field" => "calculated",
								
									"calculations" => array(
										'type' => 'record-details',
										'reference_table' => 'users',
										'reference_keys' => array( 'firstname', 'lastname' ),
										'form_field' => 'text',
										'variables' => array( array( "ib1" ) ),
									),
									
									"attributes" => ' action="?action=users&todo=get_users_select2" ',
									"class" => ' select2 ',
								),
								
							),
						),
						array(
							"cells" => array(
								
								"ib2" => array(
									"field_label" => "I have been given relevant information with explanation and understand that transfusion of blood / blood components has certain material risk / complications which include acquiring Hepatitis HIV, Syphilis and malaria parasites and I have been provided with the requisite information about the same. I have also been explained and understand that there are other endefined, unanticipated, unexplained, risks/complications that may occur during Grafter transfusion of blood / blood component.",
									"form_field" => "html",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"ib4" => array(
									"field_label" => "I have been explained to and understand that transfusion of blood/ blood component always have the possibility of reaction even after proper cross matching and checking compatibility.",
									"form_field" => "html",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"ib3" => array(
									"field_label" => "I state that the doctor-in-charge / principal surgeon/principal interventionist has answered all my questions to my saticiacion regarding transtusion of blood / blood component.",
									"form_field" => "html",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"ib5" => array(
									"field_label" => "I have signed this consent voluntarily out of my free will and without any kind of pressure or coercion.",
									"form_field" => "html",
								),
								
							),
						),
						array(
							"cells" => array(
								
								"ib6" => array(
									"field_label" => "The above terms and conditions have been explained to me in the language that understand: {field}(Language or Dialect) that is spoken and understood by me.",
									"form_field" => "text",
									"embed" => 1,
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
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"f2" => array(
									"field_label" => "Patient's/Guardian's Name",
									"form_field" => "text",
								),
								
								"f3" => array(
									"field_label" => "Doctor's Name",
									"form_field" => "text",
									"colspan" => 2,
								),
								
							),
						),
						array(
							"cells" => array(
								
								"f1" => array(
									"field_label" => "Patient's/Guardian's Signature<br>{pgsign}",
									"form_field" => "signature",
									"embed_signature" => '{pgsign}',
								),
								
								"f4" => array(
									"field_label" => "Doctor's Signature<br>{dsign}",
									"form_field" => "signature",
									"embed_signature" => '{dsign}',
								),
							),
						),
						array(
							"cells" => array(
								
								"f5" => array(
									"field_label" => "Date & Time",
									"form_field" => "date-5time",
								),
								
								"f6" => array(
									"field_label" => "Date & Time",
									"form_field" => "date-5time",
								),
							),
						),

					),
				),
				
				"cfi2" => array(
					"form_field" => "fields_in_table",
					"rows" => array(
						"property" => array(
							"accept_values" => 1,
							// "num_of_rows" => 5,
							"class" => "heading-row",
							"style" => "text-align:center; font-weight:bold;",
						),

						array(
							"cells" => array(
								
								"xf2" => array(
									"field_label" => "Witness 1 Name",
									"form_field" => "text",
								),
								
								"xf3" => array(
									"field_label" => "Witness 2 Name",
									"form_field" => "text",
									"colspan" => 2,
								),
								
							),
						),
						array(
							"cells" => array(
								
								"xf1" => array(
									"field_label" => "Witness 1 Signature<br>{w1sign}",
									"form_field" => "signature",
									"embed_signature" => '{w1sign}',
								),
								
								"xf4" => array(
									"field_label" => "Witness 2 Signature<br>{w2sign}",
									"form_field" => "signature",
									"embed_signature" => '{w2sign}',
								),
							),
						),
						array(
							"cells" => array(
								
								"xf5" => array(
									"field_label" => "Date & Time",
									"form_field" => "date-5time",
								),
								
								"xf6" => array(
									"field_label" => "Date & Time",
									"form_field" => "date-5time",
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

	function _cf_database_object_field_source(){
		return array(
			"" => "None",
			"kidney_transplant" => "Kidney Transplantation",
			"kidney_donation" => "Kidney Donation",
			"dnar" => "DNAR Order Form",
			"investigation" => "Investigation Agreement",
			"blood_transfuse" => "Blood Transfussion",
			"blood_transfuse2" => "Blood Transfussion2",
		);
	}	
?>