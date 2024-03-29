<!-- Code to display confirmation messages when settings saved or reset -->

<?php
    //Retrieve plugin configuration options from database
    $options = get_option( 'rcMC_options' );

    //check if provided api key and list id are correct
    $mc = new mcMaintenance();

   $mcListExist = $mc->getSpecificList(esc_html( $options['list_id'] ) );
   
    // checks to see if plugin is arleady connected to a MailChimp Account
    if ( !isset( $_GET[ 'message' ]) && $mcListExist['id'] !="" ) { 
    ?>
<div id='message' class='updated fade'>
    <p><strong>Hooray! You are already connected to a subscriber list within your MailChimp Account</strong></p>
</div>
<?php 
        // displays when config page initally loads and API key and List id values are not set at 000000
    } else if ( !isset( $_GET[ 'message' ]) && ($mcListExist['status'] === '401' ) || $mcListExist['title'] === 'API Key Invalid') { ?>

<div id='message' class='error fade'>
    <p><strong>You are currently not connected to a subscriber list with your MailChimp Account</strong><br />Please
        make sure your API key and subscriber list id are correct.
    </p>
</div>
<?php }

    // checks to see if form has been submitted
    else if ( isset( $_GET[ 'message' ] ) && $_GET[ 'message' ] == '1' ) {

        // invalid API Key
        if ($mcListExist['title'] === 'API Key Invalid' || $mcListExist === false) { ?>
<div id='message' class='error fade'>
    <p><strong>Hmmm! Looks like there was a problem connecting to your MailChimp Account</strong><br />Please make sure
        your API key is correct
    </p>
</div>
<?php 
        }

        // checks to see if plugin is connected to a MailChimp Account
        else if ($mcListExist['status'] != '404') { ?>
<div id='message' class='updated fade'>
    <p><strong>Hooray! You are now connected to a subscriber list within your MailChimp Account</strong></p>
</div>
<?php
        } 
        
        // it is known that plugin is connected to a MailChimp Account at this point;
        // but the plugin is not connected to a list
        else { ?>

<div id='message' class='error fade'>
    <p><strong>Hmmm! Looks like you are connected to your MailChimp account. Unfortunately, there was a problem
            connecting to a list within your MailChimp Account.</strong><br />Please make sure your subscriber list id
        is correct.
    </p>
</div>
<?php   }
    } 
    
    // Form was submitted, but API Key nor the List id were provided
    elseif ( isset( $_GET[ 'message' ] ) && $_GET[ 'message' ] != '1' ) { ?>
<div id='message' class='error fade'>
    <p><strong>Uh oh! It looks as if you have not supplied an API Key nor a List ID</strong><br />
        Please provide the API key and a subscriber list id.</p>
</div>
<?php } 

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
                    <?php if ( $options[ 'display_name_field' ]  == "No" ) { echo 'checked="checked"'; } ?> /> No
            </p>
        </li>

        <li>Add the following shortcode into a page or post: <strong>[registration_form]</strong></li>
    </ol>

    <p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes" />
    </p>
</form>