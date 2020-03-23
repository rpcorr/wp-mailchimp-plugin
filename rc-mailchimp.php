<?php
/*
Plugin Name: RC MailChimp
Plugin URI: 
Description: A newsletter signup form that subscribe users directly to RC's MailChimp account
Version: 1.1
Author: Ronan Corr
Author URI: 
License: GPLv2

*///Add a PHP constant to specify an internal version number that will be used
//   throughout the plugin code:
define( "VERSION", "1.1" );

// include MailChimp and custom classes
require_once 'classes/mcMaintenance.php';

// register a function that will be called when WordPress activates the plugin
register_activation_hook(__FILE__, 'rcMC_set_default_options');

function rcMC_set_default_options(){
    if ( get_option( 'rcMC_options' ) === false ) {
        $new_options[ 'api_key' ] = '0000000000000000000000000000000000000';
        $new_options[ 'list_id' ] = '0000000000';
        $new_options[ 'display_name_field' ] = 'Yes';
		$n_options['selected_interests'] = "Nil";
        $new_options[ 'version' ] = VERSION;
        add_option( 'rcMC_options', $new_options );
	} else {
		$existing_options = get_option( 'rcMC_options' );
		if ( $existing_options['version'] < 1.1 ) {
			$existing_options['selected_interests'] = "Nil";
			$existing_options['version'] = VERSION;
			update_option ( 'rcMc_options', $existing_options );
		}
	}
}