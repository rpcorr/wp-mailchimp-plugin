jQuery(document).ready(function ($) {
  'use strict';

  /**
   * The file is enqueued from forms/registration-form.php.
   */

  jQuery('#wait').css('display', 'none'); // hide the spinner

  jQuery('#registration-form').submit(function (event) {
    event.preventDefault(); // Prevent the default form submit.

    // show the spinner
    jQuery('#wait').css('display', 'block');

    // serialize the form data
    var ajax_form_data = $('#registrationForm').serialize();

    // add own ajax check as X-Requested-With is not always reliable
    ajax_form_data = ajax_form_data + '&ajaxrequest=true&submit=Sign+Up';

    //assign input data to variables
    var firstName = jQuery('#firstName').val();
    var lastName = jQuery('#lastName').val();
    var email = jQuery('#email').val();
    var acknowledge = jQuery('#acknowledge').prop('checked') ? jQuery('#acknowledge').val() : '0';
    var submit = jQuery('#submit').val();
    
  });
});
