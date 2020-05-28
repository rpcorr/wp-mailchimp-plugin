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
      console.log('ajax has completed');
      jQuery("#wait").css("display", "none");
    });


    jQuery('#registration-form').on('submit', function (e) {
      e.preventDefault();
      e.stopPropagation();
      console.log('form button has been clicked');
      console.log('ajax_url is ' + ajax_url);

      jQuery.ajax({
        type: "POST",
        url: ajax_url
      });


    });


  });

});
