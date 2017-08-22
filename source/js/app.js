
// @codekit-prepend 'lib/jquery.1.12.4.js'

$("form").submit(function (event) {
  event.preventDefault();

  $.ajax({
    url: "create.php",
    method: "POST",
    data: $("#form").serialize(),
    dataType: "json",

    beforeSend:function (){
      $("body").animate({
          scrollTop: 0 
      }, "fast");

      $(".loader").fadeIn();
      $("body").css("overflow", "hidden");
    },

    complete: function (data) {

      if (data.responseJSON.nameError != undefined) {

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

      $(".loader").fadeOut();
      $("body").css("overflow", "scroll");

    }
  });

});











