<?php
/**
 * This function will check a formID and get all the fields for the summary
 * 
 * @return Mixed Json object of an array that contains all the fieldIDs for the summary
 * 
 */
function retrieve_gravity_summary_fields () {
	
	if ( isset($_REQUEST) ) {
		//get form id from request
		$formID = sanitize_key(strval($_GET["formid"]));
		//retrieve form object
		$form = GFAPI::get_form( intval($formID) );
		//retrieve all the fields of this form
		$fields = $form['fields'];
		//start new array that will store all the valid ids
		$fields_in_summary = array();
		//loop through all ids
		foreach ($fields as $index => $field) {
			//declare some variables
			$id = $field['id'];
			$label = $field['label'];
			$type = $field['type'];
			//get timeformat for time fields, other fields just get empty value
			$timeFormat = $type == "time" ? $field['timeFormat'] : ""; 
			//get datetype for date fields
			$dateType = $type == "date" ? $field['dateType'] : ""; 
			//get the type of product 
			$inputType = $type == "product" ? $field['inputType'] : ""; 
			
			//get current field object
			$field_obj = GFAPI::get_field( $formID, $id );
			
			if ($field_obj) {
				//see if this field has to be in the summary
				$insummary = $field_obj->liveSummaryField;
				if ($insummary) {
					//add to fieldids array that will be returned at the end of this function
					$fields_in_summary[] = array("id"=>$id, "label"=>$label, "type"=>$type, "timeFormat"=>$timeFormat, "dateType"=>$dateType, "inputType"=>$inputType);
				}
			}
			
		}

		//return fieldIDS array as json object 
		header('Content-Type: application/json');
		echo json_encode($fields_in_summary);	

		die();
		
	}
}







/**
 * returns true if product fields are found
 **/

function product_fields_found($form_id) {
	$form = GFAPI::get_form( $form_id );
	$fields = $form['fields'];
	
	if($fields) {
		foreach ($fields as $index => $field) {
			$field_type = $field["type"];
			if ($field_type == "product") {
				return true;
			}
		}
		return false;
	}
}






/**
 * return a form object as json
**/

function gravity_summary_retrieve_field_object () {
	if ( isset($_REQUEST) ) {
		//get form id from request
		$formID = sanitize_key( strval($_GET["formid"]) );
		$fieldID = sanitize_key( strval($_GET["fieldid"]) );
		
		$field_obj = GFAPI::get_field( intval($formID), intval($fieldID) );
		
		if ($field_obj) {
			//return fieldIDS array as json object 
			header('Content-Type: application/json');
			echo json_encode($field_obj);	

			die();
		} else {
			header('Content-Type: application/json');
			echo json_encode("failed");
		}
	}
}
		

