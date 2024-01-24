<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <!-- Include Pikaday library -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pikaday@1.8.0/css/pikaday.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.8.0/pikaday.min.js"></script>

</head>

<body class="text-center">

    <section class="py-5">
        <div class="container py-5">
            <!-- ... (content remains unchanged) -->
        </div>
        <div class="container">
            <div class="row">
                <!-- ... (content remains unchanged) -->
                <div class="col-md-6">
                    <div class="card" id="example-1">
                        <div class="card-body shadow p-5" id="example-3">
                            <h2 class="text-center card-title mb-5">Set Your Schedule</h2>
                            <form class="date-select">
                                <div class="form-group mb-3">
                                    <div class="input-group mb-4">
                                        <span class="input-group-text">Select Date</span>
                                        <input id="datepicker" class="form-control form-control-lg" type="text">
                                    </div>
                                </div>
                                <!-- ... (remaining form elements) -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark">
        <!-- ... (footer section remains unchanged) -->
    </footer>

  <!-- Include Pikaday and Moment.js scripts -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
       
            // Get the span and input elements
            var spanElement = document.querySelector('.input-group-text');
            var inputElement = document.getElementById('datepicker');

            // Log the content of the span and input for demonstration
            console.log('Span Content:', spanElement.textContent);
            console.log('Input Value:', inputElement.value);

            // Initialize Pikaday
            var picker = new Pikaday({
                field: inputElement,
                format: 'MM/DD/YYYY', // Adjust the format as per your needs
                onSelect: function () {
                    // Handle date selection
                }
            });
        // Fetch events from the server
        fetch('fetch-events.php')
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(events => {
        console.log('Events:', events);

        // Extract dates from events and highlight on the calendar
        var highlightedDates = events.map(event => moment(event.start).format('MM/DD/YYYY'));
        picker.setDisableWeekends(false);
        picker.setDisableDayFn(function (date) {
            return highlightedDates.indexOf(moment(date).format('MM/DD/YYYY')) === -1;
        });
    })
    .catch(error => {
        console.error('Fetch error:', error);
    });


           
    });
</script>

</body>

</html>
