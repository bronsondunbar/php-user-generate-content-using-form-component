
// @codekit-prepend 'lib/jquery.1.12.4.js'

/* We bind a submit function to the form once it has been submitted */

$("form").submit(function (event) {

  /* In order to prevent the page from reloading which is the default action when a form is submitted we add the following line */

  event.preventDefault();

  /* This is where we define our Ajax call to our PHP script which does all the work for us */

  $.ajax({
    url: "create.php",
    method: "POST",
    data: $("#form").serialize(),
    dataType: "json",

    /* Before we send the form data to our PHP script we are going to display our loading screen to show the user that something is happening behind the scenes */

    beforeSend: function (){
      $("body").animate({
          scrollTop: 0 
      }, "fast");

      $(".loader").fadeIn();
      $("body").css("overflow", "hidden");
    },

    /* If there are any syntax issues in our PHP script we will log them in the console */

    error: function (jqXHR, textStatus, errorThrown) {
      console.log(jqXHR);
      console.log(textStatus);
      console.log(errorThrown);
    },

    /* Once our PHP script is done, it will send us a JSON encoded response, this is where we will check what response we received and display the apporiate message */

    complete: function (data) {

      if (data.responseJSON.nameError != undefined) {

        /* If there is an error, we will display it and reset the Google reCAPTCHA */

        $(".message").removeClass("success");
        $(".message").fadeOut(function () {
          $(".message").html(data.responseJSON.nameError).addClass("error").fadeIn();
        });
        grecaptcha.reset();

      } else if (data.responseJSON.emailError != undefined) {

        $(".message").removeClass("success");
        $(".message").fadeOut(function () {
          $(".message").html(data.responseJSON.emailError).addClass("error").fadeIn();
        });
        grecaptcha.reset();

      } else if (data.responseJSON.titleError != undefined) {

        $(".message").removeClass("success");
        $(".message").fadeOut(function () {
          $(".message").html(data.responseJSON.titleError).addClass("error").fadeIn();
        });
        grecaptcha.reset();

      } else if (data.responseJSON.tagsError != undefined) {

        $(".message").removeClass("success");
        $(".message").fadeOut(function () {
          $(".message").html(data.responseJSON.tagsError).addClass("error").fadeIn();
        });
        grecaptcha.reset();

      } else if (data.responseJSON.contentError != undefined) {

        $(".message").removeClass("success");
        $(".message").fadeOut(function () {
          $(".message").html(data.responseJSON.contentError).addClass("error").fadeIn();
        });
        grecaptcha.reset();

      } else if (data.responseJSON.captchaError != undefined) {

        $(".message").removeClass("success");
        $(".message").fadeOut(function () {
          $(".message").html(data.responseJSON.captchaError).addClass("error").fadeIn();
        });
        grecaptcha.reset();

      } else if (data.responseJSON.emailSentError != undefined) {

        $(".message").removeClass("error");
        $(".message").fadeOut(function () {
          $(".message").html(data.responseJSON.emailSentError).addClass("success").fadeIn();
        });
        grecaptcha.reset();

      } else if (data.responseJSON.pageError != undefined) {

        $(".message").removeClass("error");
        $(".message").fadeOut(function () {
          $(".message").html(data.responseJSON.pageError).addClass("error").fadeIn();
        });
        grecaptcha.reset();

      } else if (data.responseJSON.pageSuccess != undefined) {

        $(".message").removeClass("error");
        $(".message").fadeOut(function () {
          $(".message").html(data.responseJSON.pageSuccess).addClass("success").fadeIn();
        });
        grecaptcha.reset();

        /* Reset all form fields after successful submission */

        $("#name").val("");
        $("#email").val("");
        $("#title").val("");
        $("#tags").val("");
        $("#content").val("");
        $("#link").attr("checked", false);

      }

      /* Once we have gone through all the possible responses and displayed the appropriate message we can hide the loading screen */

      $(".loader").fadeOut();
      $("body").css("overflow", "scroll");

    }
  });

});











