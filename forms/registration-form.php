<?php

add_shortcode( 'registration_form', 'registration_form' );

function registration_form() { ?>

<?php 
    // create a nonce variable for security purposes
    $nonce = wp_create_nonce( 'register_ajax' ); 
?>
<script type="text/javascript">
// assign PHP nonce variable to a javascript variable
// so it can be read in the ajax call
var nonce = "<?= $nonce ?>";
</script>
<div class="form-results">
    <p>hello</p>
</div>
<form method="get" id="registration-form">
    <h2>Be the first to know!</h2>
    <p>Join our mailing list to receive the latest news.</p>
    <div style="margin-bottom:15px;">
        <label for="firstName">First Name</label>
        <input type="text" name="firstName" id="firstName" placeholder="First Name">
    </div>
    <div style="margin-bottom:15px;">
        <label for="lastName">Last Name</label>
        <input type="text" name="lastName" id="lastName" placeholder="Last Name">
    </div>
    <div style="margin-bottom:15px;">
        <label for="email">Email Address</label>
        <input type="text" name="email" id="email" placeholder="name@domain.com">
    </div>
    <div style="margin-bottom:15px;">
        <input type="checkbox" name="acknowledge" id="acknowledge" style="float:left; margin-top: 5px;">
        <label for="acknowledge">I have read and understand the <a href="#">terms of use</a> and <a href="#">privacy
                policy</a></label>
    </div>
    <input type="hidden" name="action" value="register_user">
    <input type="submit" name="submit" id="submit" value="Sign up">
    <div id="wait" style="margin-top:15px;">
        <p><img src="<?php echo plugin_dir_url( __DIR__ ) ?>images/ajax-loader.gif" alt="Form is processing..."
                title="Form is processing..." width="16" height="16" border="0" style="margin-right:5px;">Form is
            currently processing...please wait.</p>
    </div>
</form>
<?php  }

// add custom jquery script to the registration form
add_action('wp_enqueue_scripts', 'rcMC_enqueue_jquery');

function rcMC_enqueue_jquery() {
    $params = array ( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
    wp_enqueue_script( 'rcMC_ajax_handle', plugin_dir_url( __FILE__ ) . 'js/ajax-handler.js', array( 'jquery' ));               
    wp_localize_script( 'rcMC_ajax_handle', 'params', $params );    
}

// add an ajax hook to the registration form that is called when AJAX
// requests are received from public or logged in users with
// action variable set to register_user
add_action( 'wp_ajax_register_user', 'rcMC_register_user');
add_action( 'wp_ajax_nopriv_register_user', 'rcMC_register_user');

// handles Ajax post requests
function rcMC_register_user() { 
    check_ajax_referer( 'register_ajax' );

    if ( isset( $_POST['firstName']) && trim($_POST['firstName']) != "" ) {
        
        $output = "firstName is present";
        
    } else {
        
        $output = "firstName is NOT present";
    }

    echo $output;

    die();
}

// register a function to be called when scripts are being queued up
add_action( 'wp_enqueue_scripts', 'rcMC_load_jquery' );

// implement rcMC_load_jquery
function rcMC_load_jquery() {
    wp_enqueue_script( 'jquery' );
}