(function ($, Drupal, drupalSettings) {

  Drupal.behaviors.MyModuleBehavior = {
      attach: function (context, settings) {
      var testing = drupalSettings.jqform.testing;
      console.log(testing)
      $('body').css('background', testing);
      }
  };

  $.fn.testing = function() {
  console.log("submitted");
      $("#custom-demo-get-user-details").submit();
  };


  $(document).ready(function () {
      var $permanentAdd = $('#same-as-permanent');
      var $tempAdd = $('.form-item-temporary-address');
//on load
      if ($permanentAdd.is(':checked')) {
          $tempAdd.hide();
      }

      $permanentAdd.on('change', function () {
      if ($(this).is(':checked')) {
          $tempAdd.hide();
      } else {
          $tempAdd.show();
      }
      });
  });


  $(document).ready(function () {
      console.log("working");
  });

}(jQuery, Drupal, drupalSettings));


