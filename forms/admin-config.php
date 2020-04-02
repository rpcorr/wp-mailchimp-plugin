<!-- Code to display confirmation messages when settings saved or reset -->
<?php if ( isset( $_GET[ 'message' ] ) && $_GET[ 'message' ] == '1' ) { ?>

<div id='message' class='updated fade'>
    <p><strong>Settings Saved</strong></p>
</div>
<?php } elseif ( isset( $_GET[ 'message' ] ) && $_GET[ 'message' ] != '1' ) { ?>
<div id='message' class='error fade'>
    <p><strong>Something went wrong. Settings were not saved.</strong></p>
</div>
<?php  } 

//Retrieve plugin configuration options from database
$options = get_option( 'rcMC_options' );

//assign db api key and list id to variables -->
            
if (esc_html( $options[ 'api_key' ] ) == "0000000000000000000000000000000000000" ) {
    $api_key = "";
} else {
    $api_key = esc_html( $options[ 'api_key' ] );
}

if (esc_html( $options[ 'list_id' ] ) == "0000000000" ) {
    $list_id = "";
} else {
    $list_id = esc_html( $options[ 'list_id' ] );
}

?>

<form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">

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

            <input type="hidden" name="action" value="save_rcMC_options" />

            <!--add security with a hidden referrer field -->
            <?php wp_nonce_field('rcMC'); ?>


            <div style="margin-top:15px;">
                <label for="api_key" style="display:inline-block; width:50px;"><strong>API Key:</strong></label>
                <input type="text" name="api_key" size="40" maxlength="37" id="api_key"
                    value="<?php echo $api_key; ?>" />
                <br />
                <label for="list_id" style="display:inline-block; width:50px;"><strong>List ID:</strong></label>
                <input type="text" name="list_id" size="9" maxlength="10" id="list_id"
                    value="<?php echo $list_id; ?>" />
            </div>
        </li>

        <li>
            <p>Would you like to collect the subscriber's name?
                <input type="radio" name="display_name_field" value="Yes"
                    <?php if ( $options[ 'display_name_field' ]  == "Yes" ) { echo 'checked="checked"'; } ?> />
                Yes&nbsp;&nbsp;
                <input type="radio" name="display_name_field" value="No"
                    <?php if ( $options[ 'display_name_field' ]  == "No" ) { echo 'checked="checked"'; } ?> /> No</p>
        </li>

        <li>Add the following shortcode into a page or post: <strong>[rcMC-add-user-form]</strong></li>
    </ol>

    <p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes" />
    </p>
</form>