<?php

add_shortcode( 'registration_form', 'rcMC_registration_form' );

function rcMC_registration_form() {
    
    // Prepare output to be returned to replace shortcode
    $output = '';

    $output .= '<form method="get" id="registration-form">';

    $output .= '<h2>Be the first to know!</h2>';
    $output .= '<p>Join our mailing list to receive the latest news.</p>';
    $output .= '<p class="errorMessage">Form is incomplete.  Please address the following issue(s):</p>';
    $output .= '<div>';
    $output .= '   <label for="firstName">First Name <span id="firstNameError" class="errorMessage"></span></label>';
    $output .= '   <input type="text" name="firstName" id="firstName" placeholder="First Name" required>';
    $output .= '</div>';
    $output .= '<div>';
    $output .= '   <label for="lastName">Last Name <span id="lastNameError" class="errorMessage"></span></label>';
    $output .= '   <input type="text" name="lastName" id="lastName" placeholder="Last Name" required>';
    $output .= '</div>';
    $output .= '<div>';
    $output .= '   <label for="email">Email Address <span id="emailError" class="errorMessage"></span></label>';
    $output .= '   <input type="email" name="email" id="email" placeholder="name@domain.com" required>';
    $output .= '</div>';
    $output .= '<div>';
    $output .= '   <input type="checkbox" name="acknowledge" id="acknowledge">';
    $output .= '   <label for="acknowledge">I have read and understand the <a href="#">terms of use</a> and <a href="#">privacy
                policy</a></label>';
    $output .= '</div>';
    $output .= '<input type="button" name="submit" id="submit" class="get_submission_results" value="Sign Up" />';    
    $output .= '<div id="wait">';
    $output .= '   <p><img src="' . plugin_dir_url( __DIR__ ) . 'images/ajax-loader.gif" alt="Form is processing..." title="Form is processing..." width="16" height="16" border="0">Form is
currently processing...please wait.</p>';
    $output .= '</div>';
    $output .= '</form>';

    $output .= '<div class="submission-results">'; 
    
    $output .= '</div><br />';

    $output .= '<script type="text/javascript">';
    
    $nonce = wp_create_nonce( 'rcMC_ajax' );
    
    $output .= 'function replaceContent (bError)' .
               '{ ' .
                
                //assign input data to variables
               ' var firstName = jQuery("#firstName").val();' .    
               ' var lastName = jQuery("#lastName").val();' . 
               ' var email = jQuery("#email").val();' .  
               ' var acknowledge = jQuery("#acknowledge").prop("checked") ? jQuery("#acknowledge").val() : "0";' .
               ' var submit = jQuery("#submit").val();' .
               
               // call ajax
               '   jQuery.ajax( { ' .
               '    type: "POST",' .
               '    url: ajax_url, ' .
               '    data: { action: "rcMC_register_user_ajax", ' .
               '            _ajax_nonce: "' .  $nonce . '", ' .
               '            firstName: firstName, ' .
               '            lastName: lastName, ' .
               '            email: email ' .
               '          }, ' .
               '    success: function ( data ) {' .
               '             jQuery(".submission-results").html( data ); ' .
                            
                            // clear form if there are no errors and show results
               '            if (bError === false) { ' .
               '                jQuery("#registration-form")["0"].reset(); ' .
               '                jQuery(".submission-results").css("display", "block"); ' .  
               '            } ' . 
               '          }' .
               '    });' .
               '};';
               
    $output .= 'jQuery( document ).ready( function () {';
        
    // hide the spinner initially
    $output .= 'jQuery("#wait").css("display", "none");';

    // call replaceContent function on button click
    $output .= 'jQuery(".get_submission_results").click( function() { ' .

                // hide the submission results div
                '   jQuery(".submission-results").css("display", "none"); ' .

                     // check for errors
                     
                     //  handle first name errors
               '     if (jQuery.trim(jQuery("#firstName").val()) === "" ) { ' .
                        // add errorMessage class and display firstName error message
               '        jQuery("#firstName").addClass("errorMessage"); ' .
               '        jQuery("#firstNameError").text(" is missing "); ' .
               '        jQuery("#firstName").val("");' .
               '     } else if ( !lettersAndWhiteSpacesOnly( jQuery.trim(jQuery("#firstName").val())) ) {' .
                        // add errorMessage class and display firstName error message when text contains
                        // characters other than letters and whitespaces
               '        jQuery("#firstName").addClass("errorMessage"); ' .
               '        jQuery("#firstNameError").text(" only accepts letters and whitespaces "); ' .                  
               '     } else {' .
                        // remove white spaces from firstName textbox value
               '        jQuery("#firstName").val(jQuery("#firstName").val().replace(/\s+/g," ")); ' .
                        // remove firstName errorMessage class and text
               '        jQuery("#firstName").removeClass("errorMessage");  ' .
               '        jQuery("#firstNameError").text(""); ' .
               '     }' .

                     // handle last name errors
               '     if (jQuery.trim(jQuery("#lastName").val()) === "" ) { ' .
                        // add errorMessage class and display lastName error message when text is missing
               '        jQuery("#lastName").addClass("errorMessage"); ' .
               '        jQuery("#lastNameError").text(" is missing "); ' .
               '        jQuery("#lastName").val("");' .
               '     } else if ( !lettersAndWhiteSpacesOnly( jQuery.trim(jQuery("#lastName").val())) ) {' .
                        // add errorMessage class and display lastName error message when text contains
                        // characters other than letters and whitespaces
               '        jQuery("#lastName").addClass("errorMessage"); ' .
               '        jQuery("#lastNameError").text(" only accepts letters and whitespaces "); ' .
               '     } else {' .
                        // remove white spaces from lastName textbox value
               '        jQuery("#lastName").val(jQuery("#lastName").val().replace(/\s+/g," ")); ' .
                        // remove lastName errorMessage class and text
               '        jQuery("#lastName").removeClass("errorMessage");  ' .
               '        jQuery("#lastNameError").text(""); ' .
               '     }' .

                     // handle email errors
               '     if (jQuery.trim(jQuery("#email").val()) === "" ) { ' .
                        // add errorMessage class and display email error message for empty email value
               '        jQuery("#email").addClass("errorMessage"); ' .
               '        jQuery("#emailError").text(" is missing "); ' .
               '        jQuery("#email").val("");' .
               '     } else if ( !isEmail(jQuery("#email").val().replace(/\s+/g,""))) {' .
                        // remove white spaces from email textbox value
               '        jQuery("#email").val(jQuery("#email").val().replace(/\s+/g,"")); ' .
                        // add errorMessage class and display email error message for invalid email format
               '        jQuery("#email").addClass("errorMessage"); ' .
               '        jQuery("#emailError").text(" does not match required format "); ' .
               '     } else {' .
               
                        // remove white spaces from email textbox value
               '        jQuery("#email").val(jQuery("#email").val().replace(/\s+/g,"")); ' .
                        // remove email errorMessage class and text
               '        jQuery("#email").removeClass("errorMessage");  ' .
               '        jQuery("#emailError").text(""); ' .
               '     }' .
                      
                      // determine value for bError to pass along into replaceContent function
                      // to clear the form or not 
                '     var bError = false;' .

                '     if (jQuery.trim(jQuery("#firstName").val()) === "" ||  '  .
                '         !lettersAndWhiteSpacesOnly( jQuery.trim(jQuery("#firstName").val()))  || '  . 
                '         jQuery.trim(jQuery("#lastName").val()) === ""  || '  . 
                '         !lettersAndWhiteSpacesOnly( jQuery.trim(jQuery("#lastName").val())) || '  . 
                '         !isEmail(jQuery("#email").val().replace(/\s+/g,""))  '  . 
                '     )' .
                '     { ' . 
                           // show generic error message
                '           jQuery("p.errorMessage").css("display", "block"); ' .
                
                '           jQuery(".submission-results").css("display", "none"); ' .  
                '           bError = true; ' . 
                
                '     } else { ' .
                             // hide generic error message
                '            jQuery("p.errorMessage").css("display", "none"); ' .
                '            bError = false; ' . 
                '     } ' .
               '     replaceContent(bError); } ' .
               ');';

    // email validation function
    $output .= 'function isEmail(email) {' .
                '   var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;' .
                '   return regex.test(email); ' .
                '}';

    // text field only accepts letters and whitespaces
    $output .=  'function lettersAndWhiteSpacesOnly(text) { ' .
                '   var regex = /^[a-zA-Z\s]*$/;' .
                '   return regex.test(text); ' .
                '}'; 

    // run when AJAX is poccessing
    $output .= 'jQuery(document).ajaxStart(function () { ' .

                // disable the submit button
               '   jQuery("#submit").attr("disabled", true); ' .
               
               // show the spinner
               '   jQuery("#wait").css("display", "block"); ' .
               
               '});';

    // run when AJAX is completed
    $output .= 'jQuery(document).ajaxComplete(function () { ' .
        
               // enable the submit button
               '   jQuery("#submit").attr("disabled", false); ' .
               
               // hide the spinner
               '   jQuery("#wait").css("display", "none"); ' .
                
               '});';
    
    $output .= '});';
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

    //delay the server-side process by 1 second
    sleep(1);
    
    check_ajax_referer( 'rcMC_ajax' );

    //create a boolean to check if form fields are valid or not
    $bError = false;

    // check first name
    if ( (isset( $_POST['firstName'] ) && trim( $_POST['firstName'] ) === "") ||
            !lettersAndWhiteSpacesOnly( trim( $_POST['firstName'] ))) {
        $bError = true;
    }
    
    // check last name
    if ( isset( $_POST['lastName'] ) && trim( $_POST['lastName'] ) === "" ||
            !lettersAndWhiteSpacesOnly( trim( $_POST['lastName'] ))) {
        $bError = true;
    }
    
    // check email
    if ( isset( $_POST['email'] ) && trim($_POST['email'] ) != "" ) {
        
        if ((filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) == false ) || !isEmailSuffixOK($_POST['email']) ) {
            $bError = true;
        }
    } else if ( isset( $_POST['email'] ) && trim($_POST['email'] ) === "" ) {
        $bError = true;
    }
    
    //check to see if bError changed value
    if ($bError === false) {

        // Prepare output to be returned to AJAX requestor
        
        // create a MailChimp Maintenance object
        $mc = new mcMaintenance();

        //assign data to an array
        $data = array($_POST['email'], $_POST['firstName'], $_POST['lastName']);
    
        // subscribe user to list and return the result
        $output .= $mc->subscribeToList($data);

        echo $output;
    }
    
    die();
}

// register a function to be called when scripts are being queued up
add_action( 'wp_enqueue_scripts', 'rcMC_load_jquery' );

// implement rcMC_load_jquery
function rcMC_load_jquery() {
    wp_enqueue_script( 'jquery' );
}

// isEmailSuffixOK function
function isEmailSuffixOK( $email ) {
    
    // retrieve email suffix length
    $emailSuffixLen = strlen(substr(trim($email), strrpos(trim($email), '.')) ) - 1;
    
    // Determine email suffix length is valid; result must be 2 or 3
    if ( $emailSuffixLen === 2 || $emailSuffixLen === 3 ) {
        return true;
    } else {
        return false;
    }
}

// check for letters and white spaces only
function lettersAndWhiteSpacesOnly( $text ) {
    if (ctype_alpha(str_replace(' ', '', $text)) === true) {
        return true;
    } else {
        return false;
    }
}