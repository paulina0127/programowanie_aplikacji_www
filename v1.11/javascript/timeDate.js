function showDate() {
  today = new Date();
  date =
    "" +
    today.getDate() +
    " " +
    today.toLocaleString("default", {
      month: "long",
    }) +
    ", " +
    today.getFullYear();
  document.getElementById("date").innerHTML = date;
}

var timerID = null;
var timerRunning = false;

function showTime() {
  var now = new Date();
  var hours = now.getHours();
  var minutes = now.getMinutes();
  var seconds = now.getSeconds();
  var timeValue = "" + (hours > 12 ? hours - 12 : hours);

  timeValue += (minutes < 10 ? ":0" : ":") + minutes;
  timeValue += (seconds < 10 ? ":0" : ":") + seconds;
  timeValue += hours >= 12 ? " PM" : " AM";

  document.getElementById("time").innerHTML = timeValue;
  timerID = setTimeout("showTime()", 1000);
  timerRunning = true;
}

function stopClock() {
  if (timerRunning) clearTimeout(timerID);
  timerRunning = false;
}

function startClock() {
  stopClock();
  showDate();
  showTime();
}
