<?php

add_shortcode( 'registration_form', 'rcMC_registration_form' );

function rcMC_registration_form() {
    global $wpdb;

    if ( !empty( $_GET['searchbt'] ) ) {
        $search_string = $_GET['searchbt'];
        $search_mode = true;
    } else {
        $search_string = "Search...";
        $search_mode = false;
    }

    // Prepare query to retreive bugs from database
    $bug_query = 'select * from ' . $wpdb->get_blog_prefix();
    $bug_query .= 'ch7_bug_data ';
    $bug_query .= 'where bug_status = 0 ';

    // Add search string in query if present
    if ( $search_mode ) {
        $search_term = '%'. $search_string . '%';
        $bug_query .= "and bug_title like '%s' ";
        $bug_query .= "or bug_description like '%s' ";
    } else {
        $search_term = '';
    }
    
    $bug_query .= 'ORDER by bug_id DESC';
    $bug_items = $wpdb->get_results( $wpdb->prepare( $bug_query,
                                     $search_term, $search_term ),
                                     ARRAY_A );

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
    $output .= '<button type="button" name="submit" id="submit" class="get_submission_results">Sign Up</button>';    
    $output .= '</form><br />';

    $output .= '<a class="get_submission_results">';
    $output .= 'Get submission results';
    $output .= '</a>';

    $output .= '<div class="show_submission_results">'; 
    
    $output .= '<table>';
    
    // Check if any bugs were found
    if ( !empty( $bug_items ) ) {
        $output .= '<tr><th style="width: 80px">ID</th>';
        $output .= '<th style="width: 300px">Title / Desc</th>';
        $output .= '<th>Version</th></tr>';

        // Create row in table for each bug
        foreach ( $bug_items as $bug_item ) {
            $output .= '<tr style="background: #FFF">';
            $output .= '<td>' . $bug_item['bug_id'] . '</td>';
            $output .= '<td>' . $bug_item['bug_title'] . '</td>';
            $output .= '<td>' . $bug_item['bug_version'];
            $output .= '</td></tr>';
            $output .= '<tr><td></td><td colspan="2">';
            $output .= $bug_item['bug_description'];
            $output .= '</td></tr>';
        }
    } else {
        // Message displayed if no bugs are found
        $output .= '<tr style="background: #FFF">';
        $output .= '<td colspan=3>No Bugs to Display</td>';
    }

    $output .= '</table></div><br />';

    $output .= '<script type="text/javascript">';
    
    $nonce = wp_create_nonce( 'rcMC_ajax' );
    
    $output .= 'function replacecontent ( bug_status )' .
               '{ ' .
                
                //assign input data to variables
               ' var firstName = jQuery("#firstName").val();' .    
               ' var lastName = jQuery("#lastName").val();' . 
               ' var email = jQuery("#email").val();' .  
               ' var acknowledge = jQuery("#acknowledge").prop("checked") ? jQuery("#acknowledge").val() : "0";' .
                             
               // print variable to console
               ' console.log("first name is: " + firstName); ' .
               ' console.log("last name is: " + lastName); ' .
               ' console.log("email is: " + email); ' .
               ' console.log("acknowledge is: " + acknowledge); ' .
               
               // call ajax
               '   jQuery.ajax( { ' .
               '    type: "POST",' .
               '    url: ajax_url, ' .
               '    data: { action: "rcMC_buglist_ajax", ' .
               '            _ajax_nonce: "' .  $nonce . '", ' .
               '            bug_status: bug_status }, ' .
               '    success: function ( data ) {' .
               '             jQuery(".show_submission_results").html( data ); ' .
               '             }' .
               '    });' .
               '};';
               
    $output .= 'jQuery( document ).ready( function () {';
    $output .= 'jQuery(".get_submission_results").click( function()
                                        { replacecontent( 1 ); } ';
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
// or logged in users with an action variable set to rcMC_buglist_ajax
add_action( 'wp_ajax_rcMC_buglist_ajax', 'rcMC_buglist_ajax' );
add_action( 'wp_ajax_nopriv_rcMC_buglist_ajax', 'rcMC_buglist_ajax' );

// implement rcMC_buglist_ajax function
function rcMC_buglist_ajax() {
    check_ajax_referer( 'rcMC_ajax' );

    if ( isset( $_POST['bug_status']) && 
         is_numeric( $_POST['bug_status'])) {
             global $wpdb;

             // Prepare query to retrieve bugs from database
             $bug_query = 'SELECT * from ' . $wpdb->get_blog_prefix();
             $bug_query .= 'ch7_bug_data where bug_status = ';
             $bug_query .= intval( $_POST['bug_status'] );
             $bug_query .= ' ORDER BY bug_id DESC';
             
             $bug_items = $wpdb->get_results(
                 $wpdb->prepare( $bug_query ), ARRAY_A );
             
            // Prepare output to be returned to AJAX requestor
            $output = '<div class="show_submission_results"><table>';

            // Check if any bugs were found
            if ( $bug_items ) {
                $output .= '<tr><th style="width:80px">ID</th>';
                $output .= '<th style="width: 300px">';
                $output .= 'Title / Desc </th><th>Version</th></tr>'; 

                // Create a row in table for each bug
                foreach ( $bug_items as $bug_item ) {
                    $output .= '<tr style="background: #FFF">';
                    $output .= '<td>' . $bug_item['bug_id'] . '</td>';
                    $output .= '<td>' . $bug_item['bug_title'] . '</td>';
                    $output .= '<td>' . $bug_item['bug_version'];
                    $output .= '</td></tr>';
                    $output .= '<tr><td colspan="2">';
                    $output .= $bug_item['bug_description'];
                    $output .= '</td></tr>';
                }
            } else {
                // Message displayed if no bugs are found
                $output .= '<tr style="background: #FFF">';
                $output .= '<td colspan="3">No Bugs to Display</td></tr>';
            }
            
            $output .= '</table></div><br />';

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



/*

+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


add_shortcode( 'registration_form', 'registration_form' );

function registration_form() { 

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
    $output .= '<input type="hidden" name="action" value="register_user">';
    $output .= '<input type="submit" name="submit" id="submit" value="Sign up">';
    $output .= '<div id="wait" style="margin-top:15px;">';
    $output .= '<p><img src="' . plugin_dir_path( __DIR__ ) . 'images/ajax-loader.gif" alt="Form is processing..." title="Form is processing..." width="16" height="16">Form is processing...please wait.</p>';
    $output .= '</div>';
    $output .= '</form><br />';

    $output .= '<a class="submitted_data">';
    $output .= 'Get Results';
    $output .= '</a>';

    $output .= '<div class="output_listing">'; 
    
    $output .= '<table>';

    
    $output .= '<script type="text/javascript">';
    $output .= '    alert("hi");';
    $nonce = wp_create_nonce( 'register_ajax' );

    $output .= 'function replacecontent ( first_name )' .
               '{ jQuery.ajax( { ' .
               '    type: "POST",' .
               '    url: ajax_url, ' .
               '    data: { action: "rcMC_register_ajax", ' .
               '            _ajax_nonce: "' .  $nonce . '", ' .
               '            first_name: first_name }, ' .
               '    success: function ( data ) {' .
               '             jQuery(".output_listing").html( data ); ' .
               '             }' .
               '    });' .
               '};';
               
    $output .= 'jQuery( document ).ready( function () {';
    $output .= 'jQuery(".submitted_data").click( function()
                                        { replacecontent( "Ronan" ); } ';
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

// add an ajax hook to the registration form that is called when AJAX
// requests are received from public or logged in users with
// action variable set to rcMC_register_user
add_action( 'wp_ajax_rcMC_register_user', 'rcMC_register_user_ajax');
add_action( 'wp_ajax_nopriv_rcMC_register_user', 'rcMC_register_user_ajax');


// implement rcMC_register_user_ajax function
function rcMC_register_user_ajax() {
    check_ajax_referer( 'register_ajax' );

    if ( isset( $_POST['first_name']) && trim($_POST['first_name']) !="" ) {

        $first_name = "present";
    
    } else {
        $first_name = "not present";
    } 
       
    echo $first_name;

    die();
}

// register a function to be called when scripts are being queued up
add_action( 'wp_enqueue_scripts', 'rcMC_load_jquery' );

// implement rcMC_load_jquery
function rcMC_load_jquery() {
    wp_enqueue_script( 'jquery' );
}

*/