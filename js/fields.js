class summary_fields_value {
	
  constructor(form_id, field_id) {
	this.form_id = form_id;  
    this.field_id = field_id;
  }
	
	
	
	
	
  /** check if a field is hidden with conditional logic 
   * Returns false if it is hidden
   * Return true if not hidden by conditional logic
  **/	
  is_not_hidden_by_conditional_logic(field_type, field_producttype) {
	  if (field_producttype == "singleproduct" || field_producttype == "calculation") {
		 var input_id = "#ginput_base_price_" + this.form_id + "_" + this.field_id;
	  } else {
		 var input_id = "#input_" + this.form_id + "_" + this.field_id;
	  }
		  
	  if (jQuery(input_id).is(":disabled")) {
		  //input disabled so return false 
		 return false;
	  } else {
		 // input not disabled so return true
		 return true;
	  }
  }
	  
 
	  
	  
	  

	
  
  /** 
   * Method to check any normal input types 
   * Normal means just a input or select field that has a value
  **/
  normal_input() {
	var input_id = "#input_" + this.form_id + "_" + this.field_id;
    var current_value = jQuery(input_id).val();
	return current_value;
  }
	
	
	
	
 /** 
  * Method to handle radio buttons
  **/
  radio() {
	  var current_value = jQuery("input[name='input_"+this.field_id+"']:checked").val();
	  if (!current_value) {
			return;
		}
	  return current_value;
  }
	
	
	
  /**
   * Method to handle checkboxes
   **/	
  checkbox() {
	  var field_value = "";
         jQuery("#input_"+this.form_id+"_"+this.field_id+" input[type='checkbox']").each(function(i, ob) {
            if (jQuery(ob).is(":checked")) { //loop through each checked checkbox
              if (field_value == "") {
                field_value += jQuery(ob).val();
              } else {
                field_value += "<br> "+jQuery(ob).val();
              }
            }   
        });
	  return field_value;
  }
	
	
	
	
  /**
   * Method to handle advanced name field
   **/	
  name() {
	  //input css id
	  var input_id = "#input_" + this.form_id + "_" + this.field_id;
	  
	  //name specific classes. If these classes are present then the fields are there too and we need to check them. The extra integer is the extra number gravity adds to these special subfields
	  var class_names = [
		  ["has_prefix", 2], 
		  ["has_first_name", 3], 
		  ["has_middle_name", 4], 
		  ["has_last_name", 6], 
		  ["has_suffix", 8],
	  ];
	  
	  //declare new variable to return later
	  var full_name = "";
	  
	  //loop through the classes and get only values from existing fields that are not empty
	  class_names.forEach(function(item, index, arr) {
		  var class_name = item[0]; //name of the class to check
		  var extra_integer = item[1]; // extra integer to add behind the input Id. 
		  
		  if (jQuery(input_id).hasClass(class_name)) {
			  var sub_field_value = jQuery(input_id+"_"+extra_integer).val();
			  if (sub_field_value) {//there is a value so proceed 
				 full_name += jQuery(input_id+"_"+extra_integer).val() + " "; 
			  }
			  
		  } 
	  })
	  //return result
	  return full_name;
	  
  }
	
	
	
	
  /**
   * Method to handle address fields
   **/
  address() {
	  //input css id
	  var input_id = "#input_" + this.form_id + "_" + this.field_id;
	  
	  //name specific classes. If these classes are present then the fields are there too and we need to check them. The extra integer is the extra number gravity adds to these special subfields
	  var class_names = [
		  ["has_street", 1], 
		  ["has_street2", 2], 
		  ["has_city", 3], 
		  ["has_state", 4], 
		  ["has_zip", 5],
		  ["has_country", 6],
	  ];
	  
	  var next_lines = [1,2,4];
	  
	  //declare new variable to return later
	  var full_address = "";
	  
	  //loop through the classes and get only values from existing fields that are not empty
	  class_names.forEach(function(item, index, arr) {
		  var class_name = item[0]; //name of the class to check
		  var extra_integer = item[1]; // extra integer to add behind the input Id. 
		  
		  if (jQuery(input_id).hasClass(class_name)) {
			  var sub_field_value = jQuery(input_id+"_"+extra_integer).val();
			  var subfix = next_lines.includes(extra_integer) ? "<br>" : " "; 
			  if (sub_field_value) {//there is a value so proceed 
				 full_address += jQuery(input_id+"_"+extra_integer).val() + subfix; 
			  }
			  
		  } 
	  })
	  
	  return full_address;
   }
	
	
	
	
	/**
	 * Method to handle multi select fields 
	 **/
	multiselect() {
		//input css id
	  	var input_id = "#input_" + this.form_id + "_" + this.field_id;
		var output = "";
		
		//multi select field returns an array of all selected values 
		var input_values = jQuery(input_id).val();
		
		//loop through the array
		input_values.forEach(function(item, index, arr) {
			output += item + "<br>";
		});
		
		return output;
	}
	
	
	
	
	/**
	 * Method to handle date fields
	 **/
	date(dateType) {
		/**gravity date types are 
		 * 1. datefield (3 input fields)
		 * 2. datedropdown (3 select fields)
		 * 3. datepicker (1 input field)
		 **/
		var input_id = "#input_" + this.form_id + "_" + this.field_id;
		var full_date = "";
		
		if (dateType == "datepicker") {
			full_date = jQuery(input_id).val();
			
		} else {
			var first = jQuery(input_id+"_1").val();
			var second = jQuery(input_id+"_2").val();
			var third = jQuery(input_id+"_3").val();
			
			if (first && second && third) {
				full_date = first + "/" + second + "/" + third;
			} 
			
			
		} 
		
		return full_date;
		
	}
	
	
	
	
	
	
	/**
	 * Method to handle time fields
	 **/
	time(timeFormat) {
		//information of which format is stored in the form object per field as "timeFormat" with either 12 or 24 //subfields are hours -> 1 minutes -> 2 am/pm -> 3
		var input_id = "#input_" + this.form_id + "_" + this.field_id;
		
			
		var full_time = "";
		
		if (timeFormat == "12") {
			var hours = jQuery(input_id+"_1").val();
			var minutes = jQuery(input_id+"_2").val();
			var ampm = jQuery(input_id+"_3").val();
			if (hours && minutes) {
				full_time = hours + ":" + minutes + " " + ampm;
			} 
			
			
		} else if (timeFormat == "24") {
			var hours = jQuery(input_id+"_1").val();
			var minutes = jQuery(input_id+"_2").val();
			if (hours && minutes) {
				full_time = hours + ":" + minutes;
			}
			
			
		}
		
		return full_time;
		
	}
	
	
	
	product(inputType) {
		/** different types are singleproduct, select, radio, price, hidden, calculation **/
		
		if(inputType == "singleproduct" || inputType == "calculation") {
			//id of the single product baseprice
			var single_price_id = "#ginput_base_price_" + this.form_id + "_" + this.field_id;
			//current value of the baseprice
			var single_price = jQuery(single_price_id).val();			
			//quantity field name => we need to use the name as the id is hidden when the quantity is hidden
			var single_price_quantity_name = "input_" + this.field_id + ".3"
			//get current quantity value
			var single_quantity = parseInt(jQuery("input[name='"+single_price_quantity_name+"']").val());
			
			//return quantity and price
			if (single_quantity > 0 ) {
				var single_product_price = single_quantity + " x " + window.gformFormatMoney(single_price);
				return single_product_price;
			}
			
		} else if(inputType == "select") {
			var input_id = "#input_" + this.form_id + "_" + this.field_id;			
			var current_value = jQuery(input_id).val();
			var value_arr = current_value.split("|");
			
			var arr_length = value_arr.length;
			
			var selected_label = value_arr[0];
			var selected_price = value_arr[arr_length-1];
			
			return selected_label + " - " + window.gformFormatMoney(selected_price);
			
		} else if(inputType == "radio") {
			
			var current_value = jQuery("input[name='input_"+this.field_id+"']:checked").val();
			
			if (!current_value) {
				return;
			}
			
			var value_arr = current_value.split("|");
			
			var arr_length = value_arr.length;
			
			var selected_label = value_arr[0];
			var selected_price = value_arr[arr_length-1];
			
			return selected_label + " - " + window.gformFormatMoney(selected_price);
			
		} else if(inputType == "price") {
			var input_id = "#input_" + this.form_id + "_" + this.field_id;
			
			var current_value = jQuery(input_id).val();
			if (!current_value) {
				return;
			}
			
			return "1 x " + window.gformFormatMoney(current_value);
			
		}
		
		
	}
	
}