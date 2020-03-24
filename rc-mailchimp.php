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

    <form method="post" action="admin-post.php">

        <ol>
            <li>

                <p>This plugin requires the API Key to connect the app to MailChimp and a list to work
                    with.&nbsp;&nbsp;Login into Mail Chimp to retrieve the API Key and the list ID.</p>

                <div style="width:410px; float:left; margin-right: 10px;">
                    <p>The API key can be found under &ldquo;Account&rdquo; &rAarr; Click &ldquo;Extra&rdquo;.<br /></p>
                    <?php echo '<img src="' . $pluginURL . 'images/api-key.jpg" alt="API Key" title="API Key" width="400" height="254" />'; ?>
                    </p>
                </div>

                <div style="float:left;">
                    <p>To locate the List ID Key:</p>
                    <ol>
                        <li>Click "Lists" from the top menu to view your MailChimp lists.</li>
                        <li>Select the list you wish to offer user sign-up.</li>
                        <li>Click "Settings" and then "List name and defaults".</li>
                        <li>Your list id will be at the top of the right column under List ID.</li>
                    </ol>
                    <?php echo '<img src="' . $pluginURL . 'images/list-id.jpg" alt="List ID" title="List ID" width="400" height="187" />'; ?>
                </div>

                <br style="clear:both;">

                <div style="margin-top:15px;">
                    <label for="api_key" style="display:inline-block; width:50px;"><strong>API Key:</strong></label>
                    <input type="text" name="api_key" size="40" maxlength="37" id="api_key" />
                    <br />
                    <label for="list_id" style="display:inline-block; width:50px;"><strong>List ID:</strong></label>
                    <input type="text" name="api_id" size="9" maxlength="10" id="list_id" />
                </div>
            </li>

            <li>
                <p>Would you like to collect the subscriber's name?
                    <input type="radio" name="display_name_field" value="Yes" checked="checked" /> Yes&nbsp;&nbsp;
                    <input type="radio" name="display_name_field" value="No" /> No</p>
            </li>

            <li>Add the following shortcode into a page or post: <strong>[rcMC-add-user-form]</strong></li>
        </ol>

        <input type="submit" value="Submit" />

    </form>
</div>
<?php
}