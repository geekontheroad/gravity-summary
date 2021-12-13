<?php
/**
 * Class that will handle all the communications between PHP and the GF database on one side and Javascript and the front end on the other side.
 * 
 * @access public
 * 
 * @author Johan from Geekontheroad. <https://geekontheroad.com>
 */

class gotrgf_retrieve_summary_fields {

	public static function init() {
        $class = __CLASS__;
        new $class;
    }

    public function __construct() {
         
    }

	/**
	 * This function will check a formID and get all the fields for the summary
	 * 
	 * @return Mixed Json object of an array that contains all the fieldIDs for the summary
	 * 
	 */
	public static function retrieve_gravity_summary_fields () {
		
		if ( isset($_REQUEST) ) {
			//check if the gravity class exists
			if (!class_exists("GFAPI")) {
				return;
			}

			//get form id from request sanitize it and make sure it is valid
			$formID = sanitize_key($_GET["formid"]);
			if(!is_numeric($formID)) {
				return;
			}

			//retrieve form object and make sure it is valid
			$form = GFAPI::get_form( intval($formID) );
			if($form === false) {
				return;
			}

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
				if($field_obj === false) {
					return;
				}
				
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

			//stop here
			die();
			
		}
	}







	/**
	 * returns true if product fields are found
	 * 
	 * @param Int|String $form_id the id of the form 
	 * 
	 * @return Bool        true if any fields are found for this form
	 **/

	public static function product_fields_found($form_id) {
		//check if the gravity class exists
		if (!class_exists("GFAPI")) {
			error_log("Gravity GFAPI not exist in product_field_found method");
			return;
		}

		//sanitize and check if we have a number
		$formID = sanitize_key($form_id);
		if(!is_numeric($formID)) {
			error_log("problem with formID in product_fields_found method");
			return;
		}

		//get form and validate it Return false if anything wrong with $form
		$form = GFAPI::get_form( $form_id );
		if($form === false) {
			error_log("problem with form object in product fields found line 120");
			return false;
		}

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
	 * return a form object as json for ajax handler
	 * 
	 * @return Mixed json object of a gravity form object
	**/

	public static function gravity_summary_retrieve_field_object () {
		if ( isset($_REQUEST) ) {
			//check if the gravity class exists
			if (!class_exists("GFAPI")) {
				error_log("Gravity GFAPI does not exist");
				return;
			}

			//get form id from request
			$formID = sanitize_key( $_GET["formid"] );
			if(is_numeric($formID)) {
				error_log("problem with formID while retrieving field object");
				return;
			}
			
			$fieldID = sanitize_key( $_GET["fieldid"] );
			if(is_numeric($fieldID)) {
				error_log("problem with fieldid while retrieving field object");
				return;
			}
			
			$field_obj = GFAPI::get_field( intval($formID), intval($fieldID) );
			
			if ($field_obj) {
				//return fieldIDS array as json object 
				header('Content-Type: application/json');
				echo json_encode($field_obj);	
				die();
			} else {
				header('Content-Type: application/json');
				echo json_encode("failed");
				die();
			}
		}
	}
			
}
add_action( 'plugins_loaded', array( 'gotrgf_retrieve_summary_fields', 'init' ));