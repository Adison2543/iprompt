@extends('layouts.app')

<!-- Scripts -->
@vite(['resources/css/table.css' , 'resources/js/table.js'])
@section('content')
<body>
<?php $permis = Auth::user()->role ;
      $dpm = Auth::user()->dpm;
?>
    <div class="container">
        <div class="text-center mb-4"><h2>Calendar</h2></div>

        <div class="bg-white p-4" style="position: relative; z-index: 0;">
            <div id='calendar'></div>
        </div>

    </div>

    <!-- Bootstrap Modal -->
    {{-- <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="eventModalBody"></div>
            </div>
        </div>
    </div> --}}

    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog ">
          <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body container " id="eventModalBody"></div>
          </div>
        </div>
      </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'multiMonthYear,dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: {!! json_encode($events) !!},
                eventClick: function(info) {
                    // Display event details in a Bootstrap modal
                    $('#eventModalLabel').text(info.event.id);
                    $('#eventModalBody').html(
                    `
                        <h5 class="text-center fw-bold">${info.event.title}</h5>
                        <div class="row">
                            <div class="col">Party: ${info.event.extendedProps.party}</div>
                            <div class="col">Type: ${info.event.groupId}</div>
                        </div>
                        <div class="row">
                            <div class="col">Start: ${info.event.startStr}</div>
                            <div class="col">End: ${info.event.endStr ? info.event.endStr : info.event.startStr}</div>
                        </div>
                        <p>Note: ${info.event.extendedProps.description}</p>
                    `
                    );
                    $('#eventModal').modal('show');
                    console.log(info.event)
                },
            });

            calendar.setOption('locale', 'th');
            calendar.render();
        });
    </script>
</body>
@endsection