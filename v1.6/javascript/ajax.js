$(document).ready(function () {
  $("#theme").on("change", function () {
    var theme = $(this).is(":checked");

    $.ajax({
      url: "index.php",
      type: "POST",
      data: { theme: theme },
      success: function (result) {},
    });
  });
});
