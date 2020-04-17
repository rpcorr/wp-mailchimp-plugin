<?php

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
    <input type="submit" name="submit" id="submit" value="Sign up">
</form>
<?php  }