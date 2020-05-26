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
  });
});
