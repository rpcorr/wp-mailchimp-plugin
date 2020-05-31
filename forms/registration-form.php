<?php

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
    $output .= '</form>';
    $output .= '<script type="text/javascript">';
    $output .= '    alert("hi");';
    $nonce = wp_create_nonce( 'register_ajax' );
    $output .= '</script>';
}