<!DOCTYPE html>
<html lang='en'>
  <head>
    <meta charset='utf-8' />

    <link href='../packages/core/main.css' rel='stylesheet' />
    <link href='../packages/daygrid/main.css' rel='stylesheet' />
    <link href="../public/css/aspect.css" rel="stylesheet">
    <link type="javascript" src="../js/script.js" />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />


    <script src='../packages/core/main.js'></script>
    <script src='../packages/daygrid/main.js'></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
    <!-- <script type="text/javascript">
      function printConsole() {
          let date;
          let myDom = $("#calendar > div.fc-view-container > div > table > tbody > tr > td > div > div > div > div.fc-bg > table > tbody > tr > td.fc-day");
          myDom.on('click',function(e){
              date = $(this)[0].dataset.date;
              document.cookie = 'date='+date+'';
          });
      }
    </script> -->

    <!-- <script>

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
          plugins: [ 'dayGrid' ]
        });

        calendar.render();
      });

    </script> -->
  </head>
  <body>

    @if(Auth::user())
        <div>Ciao {{@Auth::user()->name}}</div>
    @endif

    <div id='calendar'></div>
    <script>
      $(document).ready(function() {
        @foreach($workers as $worker)
          $("#calendar > div.fc-view-container > div > table > tbody")
          .append('<p> {{@Auth::user()->name}} </p>');
          $('#calendar')
          .append('');
        @endforeach
      });
    </script>
    <script>
        $(document).ready(function() {
            // page is now ready, initialize the calendar...
            $('#calendar').fullCalendar({
              header: {
                  left: 'prev,next today',
                  center: 'title',
                  right: 'month,basicWeek,basicDay'
              },
              defaultView: 'basicWeek',
              aspectRatio: 1.5,
              // put your options and callbacks here
              // events : [
              //     @foreach($tasks as $task)
              //     {
              //         title : '{{ $task->operator }}',
              //         start : '{{ $task->date }}'
              //     },
              //     @endforeach
              // ]
            })
        });
    </script>
  </body>
  
</html>