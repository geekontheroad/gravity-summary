/**
 * Gravity Live summary update script
 **/

/**
 * Ajax handler to retrieve all summaryfields when the form loads
 * Places the result in the window.fields
 */
function gotrgf_retrieve_fields(form_id) {
	//var ajaxurl = 'https://'+window.location.host+'/wp-admin/admin-ajax.php';
			   
	jQuery.ajax({
		url: frontendajax.ajaxurl,		
		type: 'GET',
		data: {
			'action':'gotrgf_retrieve_gravity_summary_fields', 
			'formid': form_id
		},
		
		success: function(data) {
			window.fields = data;
			if (window.fields) {
				 gotrgf_gravity_summary_update(formId);
			 }
			
		},
		error: function(data) {
			console.log("An error occured while retrieving the field ids.");
			
		}
	});
}


/**
 * AJAX handler to get a full JSON form object from a PHP function
 **/

function gotrgf_retrieve_one_field(form_id, field_id) {
	//var ajaxurl = 'https://'+window.location.host+'/wp-admin/admin-ajax.php';
			   
	jQuery.ajax({
		url: frontendajax.ajaxurl,	
		type: 'GET',
		data: {
			'action':'gotrgf_gravity_summary_retrieve_field_object', 
			'formid': form_id,
			'fieldid': field_id,
		},
		
		success: function(data) {
			return data;
			
		},
		error: function(data) {
			console.log("An error occured while retrieving the field ids.");
			
		}
	});
}











/**
 * update the summary
 * outputs the summary to the html
 **/
function gotrgf_gravity_summary_update( formId ) {
      
        //ids of the fields we need to show in the summary if they have a value
        var summary_fields = window.fields;
	
        //create output variable
        var output = "";
        
		
        //loop through array of field ids
        jQuery.each( summary_fields, function( index, value ) {
			
			//define field specific variables
		  var field_type = value['type'];	
		  var field_label = value['label'];	
		  var field_timeformat = value['timeFormat'];
		  var field_datetype = value['dateType'];
		  var field_producttype = value['inputType']; //what type of product
			
		 
		  
		var input_html_id = "#field_" + formId + "_" + value['id'];	
		
			
		//initiate class to retrieve value based on field type
        let get_values = new summary_fields_value(formId, value['id']);	
			
		//if (jQuery(input_html_id).is(":visible")) {
		if (get_values.is_not_hidden_by_conditional_logic(field_type, field_producttype)) {
			// only proceed if this field is visible
			
		  
			
		  switch(field_type) {
			  case "text":
				  var field_value = get_values.normal_input();
				  break;
				  
			  case "radio":
				  var field_value = get_values.radio();
				  break;
				  
			  case "checkbox":
				  var field_value = get_values.checkbox();
				  break;
				  
			  case "name":
				  var field_value = get_values.name();
				  break;
				  
			  case "address":
				  var field_value = get_values.address();
				  break;
				  
			  case "multiselect":
				  var field_value = get_values.multiselect();
				  break;
				  
			  case "time":
				  var field_value = get_values.time(field_timeformat);
				  break;
				 
			  case "date":
				  var field_value = get_values.date(field_datetype);
				  break;
				  
			  case "product":
				  var field_value = get_values.product(field_producttype);
				  break;
				  
			  default:
				  var field_value = get_values.normal_input();
				  break;
		  }	
			
		 } // end of visible if
		  
          	if(field_value) { //there is a value and its not a product so output it
              output += "<div class='summary_line'> <div class='line_part_left'> "+field_label+" </div> <div class='line_part_right'> "+field_value+" </div> </div>";  
            } 
			
        });

        if (output == "") { //output still empty so nothing was selected
          output = "<p class='nothing_selected'>Nothing Selected yet</p>";
        }
	
        //output summary to html
        jQuery(".summary_lines").html(output);


  }




/** Form load **/
 jQuery(document).on('gform_post_render', function(event, form_id, current_page){ 
     //load all the fields that need to be in the summary
	 gotrgf_retrieve_fields(form_id); 
 });






/**
 * Load the above summary update script when any input changes or when conditional logic changes
 **/

gform.addAction( 'gform_input_change', function( elem, formId, fieldId ) {
    gotrgf_gravity_summary_update(formId);
}, 10, 3 );

gform.addAction('gform_post_conditional_logic_field_action', function (formId, action, targetId, defaultValues, isInit) {
	gotrgf_gravity_summary_update(formId);
});






/**
 * update the total
 **/

  gform.addFilter( 'gform_product_total', function(total, formId){
          jQuery(".price_amount").html(window.gformFormatMoney(total));
        return total; 
  });