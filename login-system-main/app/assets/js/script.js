    var calendar;
    var Calendar = FullCalendar.Calendar;
    var events = [];
    $(function() {
        if (!!scheds) {
            Object.keys(scheds).map(k => {
                var row = scheds[k]
              
                events.push({
                    id: row.id,
                    title: row.title,
                    qty_of_person: row.qty_of_person,
                    price_3: row.price_3,
                    price_2: row.price_2, // Replace 'qty_of_person' with 'price'
                    price_1: row.price_1,
                    start: row.start_datetime,
                    end: row.end_datetime,
                });
            })
        }




        var date = new Date()
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear()

        calendar = new Calendar(document.getElementById('calendar'), {
            headerToolbar: {
                left: 'prev,next today',
                right: 'dayGridMonth,dayGridWeek,list',
                center: 'title',
            },
            selectable: true,
            themeSystem: 'bootstrap',
            
            //Random default events
            events: events,
            
            eventClick: function(info) {
                var _details = $('#event-details-modal');
                var id = info.event.id;
                if (!!scheds[id]) {
                    _details.find('#title').text(scheds[id].title);
                    _details.find('#description').text(scheds[id].description);
                    _details.find('#qty_persons').text(scheds[id].qty_of_person);
                    _details.find('#price3').text(parseFloat(scheds[id].price_3).toFixed(2));
                    _details.find('#price2').text(parseFloat(scheds[id].price_2).toFixed(2));
                    _details.find('#price1').text(parseFloat(scheds[id].price_1).toFixed(2));
                    _details.find('#start').text(scheds[id].sdate);
                    _details.find('#end').text(scheds[id].edate);
                    _details.find('#edit,#delete').attr('data-id', id);
                    _details.modal('show');
                } else {
                    alert("Event is undefined");
                }
            },
            
            
            eventDidMount: function(info) {
                // Do Something after events mounted
            },
            editable: true
        });

        calendar.render();

        // Form reset listener
        $('#schedule-form').on('reset', function() {
            $(this).find('input:hidden').val('')
            $(this).find('input:visible').first().focus()
        })

// Edit Button
$('#edit').click(function () {
    var id = $(this).attr('data-id');
    if (!!scheds[id]) {
        var _form = $('#schedule-form');
        _form.find('[name="id"]').val(id);
        _form.find('[name="title"]').val(scheds[id].title);
        _form.find('[name="description"]').val(scheds[id].description);
        _form.find('[name="qty_persons"]').val(scheds[id].qty_of_person);
        // Set the value of the input field based on the price value
        _form.find('[name="price3"]').val(scheds[id].price_3);
        _form.find('[name="price2"]').val(scheds[id].price_2);
        _form.find('[name="price1"]').val(scheds[id].price_1);
        _form.find('[name="start_datetime"]').val(String(scheds[id].start_datetime).replace(" ", "T"));
        _form.find('[name="end_datetime"]').val(String(scheds[id].end_datetime).replace(" ", "T"));

        $('#event-details-modal').modal('hide');
        _form.find('[name="title"]').focus();
    } else {
        alert("Event is undefined");
    }
});





        // Delete Button / Deleting an Event
        $('#delete').click(function() {
            var id = $(this).attr('data-id')
            if (!!scheds[id]) {
                var _conf = confirm("Are you sure to delete this scheduled event?");
                if (_conf === true) {
                    location.href = "./delete_schedule.php?id=" + id;
                }
            } else {
                alert("Event is undefined");
            }
        })
    })