<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <style>
    /* Add your styling for the glowing effect here */
    .ui-state-highlight {
      background-color: #ffd700; /* Yellow background for event day */
      border-radius: 50%;
      box-shadow: 0 0 10px #ffd700; /* Glow effect */
      padding: 10px;
    }

    .calendar {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 5px;
      text-align: center;
    }

    .day {
      padding: 10px;
    }
  </style>
  <title>Event Calendar with Datepicker</title>
</head>
<body>

<input type="text" id="datepicker">

<div class="calendar" id="calendar"></div>

<script>
$(function() {
  // Replace this array with your actual event dates
  var eventDates = ['2023-11-28', '2023-12-05', '2023-12-15'];

  // Get the current month and year
  var month = new Date().getMonth() + 1;
  var year = new Date().getFullYear();

  // Initialize datepicker
  $("#datepicker").datepicker({
    beforeShowDay: function(date) {
      var formattedDate = $.datepicker.formatDate("yy-mm-dd", date);
      // Check if the current date has an event
      if ($.inArray(formattedDate, eventDates) != -1) {
        return [true, 'ui-state-highlight'];
      } else {
        return [true];
      }
    },
    onSelect: function(selectedDate) {
      // Handle date selection if needed
      console.log("Selected date: " + selectedDate);
    },
  });

  // Display calendar
  var calendar = $("#calendar");
  calendar.addClass("calendar");

  var dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
  $.each(dayNames, function(_, name) {
    calendar.append("<div class='day'>" + name + "</div>");
  });

  var daysInMonth = new Date(year, month, 0).getDate();
  var firstDay = new Date(year, month - 1, 1).getDay();

  for (var i = 1; i < firstDay; i++) {
    calendar.append("<div></div>");
  }

  for (var day = 1; day <= daysInMonth; day++) {
    var formattedDate = year + "-" + month + "-" + day;
    if ($.inArray(formattedDate, eventDates) != -1) {
      calendar.append("<div class='ui-state-highlight'>" + day + "</div>");
    } else {
      calendar.append("<div class='day'>" + day + "</div>");
    }
  }
});
</script>

</body>
</html>
