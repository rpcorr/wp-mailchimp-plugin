/*
     * The file is enqueued from forms/registration-form.php
*/

jQuery(function () {
  'use strict';
  jQuery(document).ready(function ($) {

    console.log("jQuery is ready");

    jQuery('#wait').css('display', 'none'); // hide the spinner

    jQuery(document).ajaxStart(function () {
      console.log('ajax has started');
      jQuery("#wait").css("display", "block");
    });

    jQuery(document).ajaxComplete(function () {
      //console.log('ajax has completed');
      jQuery("#wait").css("display", "none");
    });


    jQuery('#registration-form').on('submit', function (e) {
      e.preventDefault();
      e.stopPropagation();
      //console.log('form button has been clicked');
      //console.log('ajax_url is ' + ajax_url);
      //console.log('nonce is ' + nonce);

      //assign input data to variables
      var firstName = jQuery('#firstName').val();
      var lastName = jQuery('#lastName').val();
      var email = jQuery('#email').val();
      var acknowledge = jQuery('#acknowledge').prop('checked') ? jQuery('#acknowledge').val() : '0';
      var submit = jQuery('#submit').val();

      //console.log('firstName is: ' + firstName);
      //console.log('lastName is: ' + lastName);
      //console.log('email is: ' + email);
      //console.log('acknowledge is: ' + acknowledge);
      //console.log('submit is: ' + submit);

      jQuery.ajax({
        type: "POST",
        url: ajax_url,
        data: {
          action: "ch8bt_buglist_ajax",
          _ajax_nonce: nonce,
          firstName: firstName,
          lastName: lastName,
          email: email,
          acknowledge: acknowledge
        },
        cache: false,
        success: function (data) {
          jQuery('.form-results').html(data);
          return false;
        }
      });


    });


  });

});
