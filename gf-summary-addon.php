<?php
/*
Plugin Name: Live Summary for Gravity Forms
Plugin URI: http://www.geekontheroad.com
Description: Adds a live summary to a gravity form
Version: 1.0.2
Author: Geek on the Road
Text Domain: gravity-live-summary


------------------------------------------------------------------------
Copyright 2020-2021 Geek on the Road OÜ.

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/


define( 'GF_SUMMARY_ADDON_VERSION', '1.0.2' );
 
add_action( 'gform_loaded', array( 'GF_Summary_AddOn_Bootstrap', 'load' ), 5 );
 
class GF_Summary_AddOn_Bootstrap {
 
    public static function load() {
 
        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }

        
 
		//include our class
        require_once( 'class-gravitysummaryaddon.php' );
		
		//require our retrieve function for ajax calls
		require_once( 'retrieve-summary-fields.php' );
 
        GFAddOn::register( 'GFSummaryAddOn' );
    }
 
}
 
function gf_summary_addon() {
	//register new instance of class
    return GFSummaryAddOn::get_instance();
}