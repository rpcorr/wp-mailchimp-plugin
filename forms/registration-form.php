<?php

add_shortcode( 'registration_form', 'rcMC_registration_form' );

function rcMC_registration_form() {
    
    // Prepare output to be returned to replace shortcode
    $output = '';

    $output .= '<form method="get" id="registration-form">';

    $output .= '<h2>Be the first to know!</h2>';
    $output .= '<p>Join our mailing list to receive the latest news.</p>';
    $output .= '<div style="margin-bottom:15px;">';
    $output .= '   <label for="firstName">First Name</label>';
    $output .= '   <input type="text" name="firstName" id="firstName" placeholder="First Name">';
    $output .= '</div>';
    $output .= '<div style="margin-bottom:15px;">';
    $output .= '   <label for="lastName">Last Name</label>';
    $output .= '   <input type="text" name="lastName" id="lastName" placeholder="Last Name">';
    $outout .= '</div>';
    $output .= '<div style="margin-bottom:15px;">';
    $output .= '   <label for="email">Email Address</label>';
    $output .= '   <input type="text" name="email" id="email" placeholder="name@domain.com">';
    $output .= '</div>';
    $output .= '<div style="margin-bottom:15px;">';
    $output .= '   <input type="checkbox" name="acknowledge" id="acknowledge" style="float:left; margin-top: 5px;">';
    $output .= '   <label for="acknowledge">I have read and understand the <a href="#">terms of use</a> and <a href="#">privacy
                policy</a></label>';
    $output .= '</div>';
    $output .= '<input type="button" name="submit" id="submit" class="get_submission_results" value="Sign Up" />';    
    $output .= '<div id="wait" style="margin-top:15px;">';
    $output .= '   <p><img src="' . plugin_dir_url( __DIR__ ) . 'images/ajax-loader.gif" alt="Form is processing..." title="Form is processing..." width="16" height="16" border="0" style="margin-right:5px;">Form is
currently processing...please wait.</p>';
    $output .= '</div>';
    $output .= '</form><br />';

    $output .= '<div class="show_submission_results">'; 
    
    $output .= '</div><br />';

    $output .= '<script type="text/javascript">';
    
    $nonce = wp_create_nonce( 'rcMC_ajax' );
    
    $output .= 'function replaceContent ()' .
               '{ ' .
                
                //assign input data to variables
               ' var firstName = jQuery("#firstName").val();' .    
               ' var lastName = jQuery("#lastName").val();' . 
               ' var email = jQuery("#email").val();' .  
               ' var acknowledge = jQuery("#acknowledge").prop("checked") ? jQuery("#acknowledge").val() : "0";' .
               ' var submit = jQuery("#submit").val();' .
                             
               // print variable to console
               ' console.log("first name is: " + firstName); ' .
               ' console.log("last name is: " + lastName); ' .
               ' console.log("email is: " + email); ' .
               ' console.log("acknowledge is: " + acknowledge); ' .
               ' console.log("submit is: " + submit); ' .
               
               // call ajax
               '   jQuery.ajax( { ' .
               '    type: "POST",' .
               '    url: ajax_url, ' .
               '    data: { action: "rcMC_register_user_ajax", ' .
               '            _ajax_nonce: "' .  $nonce . '", ' .
               '            firstName: firstName }, ' .
               '    success: function ( data ) {' .
               '             jQuery(".show_submission_results").html( data ); ' .
               '             }' .
               '    });' .
               '};';
               
    $output .= 'jQuery( document ).ready( function () {';
    $output .= 'jQuery(".get_submission_results").click( function()
                                        { replaceContent(); } ';
    $output .= ')});';
    $output .= '</script>';

    // Return data prepared to replace shortcode on page/post
    return $output;
}


// register a function to add content to the page header
add_action( 'wp_head', 'rcMC_declare_ajaxurl' );

// implement rcMC_declare_ajaxurl
function rcMC_declare_ajaxurl() { ?>
<script type="text/javascript">
var ajax_url = '<?php echo admin_url( '
admin - ajax.php ' ); ?>';
</script>

<?php
}

// register functions that will be called when AJAX requests are received from public 
// or logged in users with an action variable set to rcMC_register_user_ajax
add_action( 'wp_ajax_rcMC_register_user_ajax', 'rcMC_register_user_ajax' );
add_action( 'wp_ajax_nopriv_rcMC_register_user_ajax', 'rcMC_register_user_ajax' );

// implement rcMC_register_user_ajax function
function rcMC_register_user_ajax() {
    check_ajax_referer( 'rcMC_ajax' );

    // Prepare output to be returned to AJAX requestor
    $output = '<div class="show_submission_results">';
    $output .= "<br/>";
        
        if ( isset( $_POST['firstName']) && trim($_POST['firstName']) !="") {
            $output .= "first name is present " . $_POST['firstName'];
        } else {
            $output .= "first name is not present" . $_POST['firstName'];
        }

    $output .= '</div>';

    echo $output;

    die();
}

// register a function to be called when scripts are being queued up
add_action( 'wp_enqueue_scripts', 'rcMC_load_jquery' );

// implement rcMC_load_jquery
function rcMC_load_jquery() {
    wp_enqueue_script( 'jquery' );
}