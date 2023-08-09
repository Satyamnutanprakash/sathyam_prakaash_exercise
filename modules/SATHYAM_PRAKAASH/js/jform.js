(function (Drupal, drupalSettings) {

  Drupal.behaviors.MyModuleBehavior = {
    attach: function (context, settings) {
      var testing = drupalSettings.sathyam_prakaash.testing;
      console.log(testing);
      document.body.style.background = testing;
    }
  };

  function submitForm() {
    console.log("submitted");
    document.getElementById("modified-form-form-details").submit();
  }

  document.addEventListener("DOMContentLoaded", function () {
    var permanentAdd = document.getElementById("same-as-permanent");
    var tempAdd = document.querySelector(".form-item-temporary-address");

    // On load
    if (permanentAdd.checked) {
      tempAdd.style.display = "none";
    }

    permanentAdd.addEventListener("change", function () {
      if (this.checked) {
        tempAdd.style.display = "none";
      } else {
        tempAdd.style.display = "block";
      }
    });

    console.log("working");
  });

})(Drupal, drupalSettings);
