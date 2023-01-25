$(document).ready(function () {
  $(".color-btn").on({
    mouseover: function () {
      $(this).animate(
        {
          width: "+=" + 30,
        },
        800
      );
    },
    mouseout: function () {
      $(this).animate(
        {
          width: "-=" + 30,
        },
        800
      );
    },
  });
});

$(document).ready(function () {
  $("#time, #date").on("click", function () {
    $(this).animate({
      opacity: 0.4,
      fontSize: "2em",
      duration: 1500,
    });
  });
});
