
/** 
 * code to load setting on settings page 
 **/
jQuery(document).on("gform_load_form_settings", function(event, form){
	jQuery( '#show_summary' ).prop( 'checked', Boolean( rgar( form, 'show_summary' ) ) );
	jQuery( '#show_total' ).prop( 'checked', Boolean( rgar( form, 'show_total' ) ) );
});