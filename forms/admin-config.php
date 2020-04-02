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

            <input type="hidden" name="action" value="save_rcMC_options" />

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

    <p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes" />
    </p>
</form>