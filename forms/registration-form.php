<?php

// add an ajax hook to the registration form that is called when AJAX
// requests are received from public or logged in users with
// action variable set to register_user
add_action( 'wp_ajax_register_user', 'register_user');
add_action( 'wp_ajax_nopriv_register_user', 'register_user');

// handles Ajax post requests
function register_user() { ?>

<?php }

add_shortcode( 'registration_form', 'registration_form' );

function registration_form() { ?>

<form id="registration-form" enctype="multipart/form-data" method="post"
    action="<?php echo admin_url('admin-ajax.php'); ?>">
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
</form>
<?php  }