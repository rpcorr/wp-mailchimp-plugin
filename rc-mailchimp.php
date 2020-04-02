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

// Register a function to be called when WordPress is building the administration pages menu
add_action( 'admin_menu',  'rcMC_settings_menu' );

// Implement the rcMC_settings_menu function.  MailChimp menu will be a submenu of Settings
function rcMC_settings_menu() {
   
    global $options_page;
    
    $options_page = add_options_page( 'MailChimp Configuration', 'RC MailChimp', 'manage_options',
                                      'rcMC-mc', 'rcMC_config_page' ); 
}

function rcMC_config_page() {
    
    //Retrieve plugin configuration options from database
    $options = get_option( 'rcMC_options' );

    //Get the plugin root directory
    $pluginURL = plugin_dir_url(__FILE__);
   
?>
<div id="rcMC-general" class="wrap">
    <h2>Mail Chimp Settings</h2>

    <?php
    //include admin configuration form
    require_once 'forms/admin-config.php';
    ?>

</div>
<?php
}

// Register a function to handle the submission of the configurations form
add_action( 'admin_init', 'rcMC_admin_init' );

function rcMC_admin_init() {
    add_action( 'admin_post_save_rcMC_options' , 'process_rcMC_options' );
 }

 function process_rcMC_options() {

    // Check that nonce field created in configuration form is present
    check_admin_referer( "rcMC" );

    //retrieve form values
    $api_key = $_POST['api_key'];
    $list_id = $_POST['list_id'];

    //check if form values are good for submission
    if (trim($api_key) === "" || trim($api_key) === null  || trim($list_id) === "" || trim($list_id) === null) {
        $message = '2';
    } else {
        
        //Cycle through all text form fields and store their values in the options array
        foreach ( array( 'api_key' ) as $option_name ) {
            if ( isset( $_POST[$option_name] ) ) {
                
                //check to see if field name is empty if so reset to inital value
                if ( sanitize_text_field( trim( $_POST[$option_name] ) ) == "" ) {
                    $options[$option_name] = "0000000000000000000000000000000000000";
                } else {
                    $options[$option_name] = sanitize_text_field( $_POST[$option_name] );	
                }
            }
        }
    
        foreach ( array( 'list_id' ) as $option_name ) {
            if ( isset( $_POST[$option_name] ) ) {
                //check to see if field name is empty if so reset to inital value
                if ( sanitize_text_field( trim( $_POST[$option_name] ) ) == "" ) {
                    $options[$option_name] = "0000000000";
                } else {
                    $options[$option_name] = sanitize_text_field( $_POST[$option_name] );	
                }
            }
        }
    
        foreach ( array( 'display_name_field' ) as $option_name ) {
            if ( isset( $_POST[$option_name] ) ) {
                $options[$option_name] = esc_html( $_POST[$option_name] );
            }
        }

        //Store updated options array to database
        update_option( 'rcMC_options', $options );
        $message = '1';
    }

    // redirect back to MailChimp configuration page with a message.
    wp_redirect( add_query_arg( array( 'page' => 'rcMC-mc', 'message' => $message), admin_url( 'options-general.php' ) ));   
    exit;
 }