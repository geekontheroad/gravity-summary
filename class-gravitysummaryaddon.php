<?php

GFForms::include_addon_framework();
class GFSummaryAddOn extends GFAddOn {
 
    protected $_version = GF_SUMMARY_ADDON_VERSION;
	protected $_min_gravityforms_version = '1.9';
	protected $_slug = 'gravitysummary';
	protected $_path = 'gravitysummary/gf-summary-addon.php';
	protected $_full_path = __FILE__;
	protected $_title = 'Gravity Forms Summary Add-On';
	protected $_short_title = 'GF Summary Add-On';

	private static $_instance = null;

	/**
	 * Get an instance of this class.
	 *
	 * @return GFSummaryAddOn
	 */
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new GFSummaryAddOn();
		}

		return self::$_instance;
	}
	
	
	// # SCRIPTS & STYLES -----------------------------------------------------------------------------------------------

	/**
	 * Return the scripts which should be enqueued.
	 *
	 * @return array
	 */
	public function scripts() {
		$scripts = array(
			array(
				'handle'  => 'summary_change_js',
				'src'     => $this->get_base_url() . '/js/summary-change.js',
				'version' => $this->_version,
				'deps'    => array( 'jquery' ),
				'enqueue' => array(
					array( 'field_types' => array( 'text', 'radio', 'checkbox', 'select' ) ),
				),
			), array(
				'handle'  => 'settings_page_js',
				'src'     => $this->get_base_url() . '/js/settings-page.js',
				'version' => $this->_version,
				'deps'    => array( 'jquery' ),
				'enqueue' => array(
					array( 'field_types' => array( 'text', 'radio', 'checkbox', 'select' ) ),
				),
			), array(
				'handle'  => 'fields_js',
				'src'     => $this->get_base_url() . '/js/fields.js',
				'version' => $this->_version,
				'deps'    => array( 'jquery' ),
				'enqueue' => array(
					array( 'field_types' => array( 'text', 'radio', 'checkbox', 'select' ) ),
				),
			),

		);

		return array_merge( parent::scripts(), $scripts );
	}

	/**
	 * Return the stylesheets which should be enqueued.
	 *
	 * @return array
	 */
	public function styles() {
		$styles = array(
			array(
				'handle'  => 'gravity_summary_css',
				'src'     => $this->get_base_url() . '/css/gravity-summary-all.css',
				'version' => $this->_version,
				'enqueue' => array(
					array( 'field_types' => array( 'text', 'radio' ) )
				),
			),
		);

		return array_merge( parent::styles(), $styles );
	}

	
	
 
    public function pre_init() {
        parent::pre_init();
        // add tasks or filters here that you want to perform during the class constructor - before WordPress has been completely initialized
		
		
    }
 
    public function init() {
        parent::init();
        // add tasks or filters here that you want to perform both in the backend and frontend and for ajax requests
		
		/** add our retrieve function to admin ajax**/
		add_action( 'wp_ajax_retrieve_gravity_summary_fields', 'retrieve_gravity_summary_fields' );
		add_action( 'wp_ajax_nopriv_retrieve_gravity_summary_fields', 'retrieve_gravity_summary_fields' );
		
		add_action( 'wp_ajax_gravity_summary_retrieve_field_object', 'gravity_summary_retrieve_field_object' );
		add_action( 'wp_ajax_nopriv_gravity_summary_retrieve_field_object', 'gravity_summary_retrieve_field_object' );
		
    }
 
    public function init_admin() {
        parent::init_admin();
        // add tasks or filters here that you want to perform only in admin
		
		/** 
		 * Add field setting
		 **/
		add_action( 'gform_field_standard_settings', 'gotr_live_summary_settings', 10, 2 );
		function gotr_live_summary_settings( $position, $form_id ) {

			//create settings on position 25 (right after Field Label)
			if ( $position == 25 ) {
				?>
				<li class="gotr_live_summary_setting field_setting">
					<input type="checkbox" id="field_summary_setting" onclick="SetFieldProperty('liveSummaryField', this.checked);" />
					<label for="field_summary_setting" style="display:inline;">
						<?php _e("Show in summary", "gravity-live-summary"); ?>
						<?php gform_tooltip("form_field_summary_setting") ?>
					</label>
				</li>
				<?php
			}
		}
		
		
		//Action to inject supporting script to the form editor page
		add_action( 'gform_editor_js', 'editor_script' );
		function editor_script(){
			?>
			<script type='text/javascript'>
				//adding setting to our supported fields
				fieldSettings.text += ', .gotr_live_summary_setting';
				fieldSettings.radio += ', .gotr_live_summary_setting';
				fieldSettings.checkbox += ', .gotr_live_summary_setting';
				fieldSettings.select += ', .gotr_live_summary_setting';
				fieldSettings.number += ', .gotr_live_summary_setting';
				fieldSettings.email += ', .gotr_live_summary_setting';
				fieldSettings.textarea += ', .gotr_live_summary_setting';
				fieldSettings.date += ', .gotr_live_summary_setting';
				fieldSettings.phone += ', .gotr_live_summary_setting';
				fieldSettings.website += ', .gotr_live_summary_setting';
				fieldSettings.name += ', .gotr_live_summary_setting';
				fieldSettings.address += ', .gotr_live_summary_setting';
				fieldSettings.multiselect += ', .gotr_live_summary_setting';
				fieldSettings.username += ', .gotr_live_summary_setting';
				fieldSettings.product += ', .gotr_live_summary_setting';
				fieldSettings.time += ', .gotr_live_summary_setting';
				
				//binding to the load field settings event to initialize the checkbox
				jQuery(document).on('gform_load_field_settings', function(event, field, form){
					jQuery( '#field_summary_setting' ).prop( 'checked', Boolean( rgar( field, 'liveSummaryField' ) ) );
				});
			</script>
			<?php
		}
		
		//Filter to add a new tooltip
		add_filter( 'gform_tooltips', 'add_encryption_tooltips' );
		function add_encryption_tooltips( $tooltips ) {
			$tooltips['form_field_summary_setting'] = "<h6>Live summary</h6>Check this box to include this field in the live summary";
			
			 return $tooltips;
		}
		
		
		/**
		 *
		 * FORM SETTINGS
		 *
		 **/
		
		/**
		 * Add turn on summary setting in the form settings
		 **/
		add_filter( 'gform_form_settings_fields', 'add_gravity_summary_settings', 10, 2 );
		function add_gravity_summary_settings($fields, $form) {
			$fields['form_layout']['fields'][] = array( 
				'type' => 'checkbox', 
				'label' => 'Live Summary',				
				'choices' => array(
                                array(
                                   'label' => 'Turn on summary',
                                   'name'  => 'show_summary',
                                   'tooltip' => 'Turn this on to show a live summary next to the form (or below on mobile). This summary will be updated live as people fill out the form. You can control which fields to show in the field settings of each field.',
                                ),
								array(
                                   'label' => 'Show total in summary',
                                   'name'  => 'show_total',
                                   'default_value' => 1,
									'tooltip' => 'If this setting is turned on and there is at least one product in the form than a form total will be shown at the bottom of the summary.',
                                ),
					),
			
			);
			

			return $fields;
		}
		
		
		

		/**
		 * Save the show summary setting to the form object
		 **/
		add_filter( 'gform_pre_form_settings_save', 'save_show_summary_setting' );
		function save_show_summary_setting($form) {
			$form['show_summary'] = rgpost( 'show_summary' );
			return $form;
		}
		
		/**
		 * save the show total to the form object
		 **/
		add_filter( 'gform_pre_form_settings_save', 'save_show_total_setting' );
		function save_show_total_setting($form) {
			$form['show_total'] = rgpost( 'show_total' );
			return $form;
		}
		
		
		
    }
 
    public function init_frontend() {
        parent::init_frontend();
        // add tasks or filters here that you want to perform only in the front end
		
		add_filter( 'gform_get_form_filter', function ( $form_string, $form ) {
			
			if (!$form['show_summary']) {//setting is turned off so don't add summary markup
				return $form_string;
			}
			
			//add stuff before the form
			$form_string_before = "<div class='form_container'><div class='flex-child first-child'>" . $form_string;
			
			//check if this form has at least one product field in it
			$products = product_fields_found($form["id"]);
			
			//check if the form total setting is switched to on
			$show_total = $form["show_total"];
			
			if ($products == true and $show_total == "on") { // include the total if it has a product field and it is switched on
				$total_string = "<div class='summary_total'><p><strong>Total: </strong> <span class='price_amount'></span></p></div>";
			} else {
				$total_string = "";
			}	
			
			
			//add the rest after the form
			$form_string = $form_string_before . "</div><div class='flex-child second-child'><div class='form_overview_container'><div class='summary_title'><h5><strong>Summary</strong></h5></div><div class='summary_lines'></div>".$total_string."</div></div></div>";
			
			//return the new form
			return $form_string;
		}, 10, 2 );
	}
 
    public function init_ajax() {
        parent::init_ajax();
        // add tasks or filters here that you want to perform only during ajax requests
		
    }
}