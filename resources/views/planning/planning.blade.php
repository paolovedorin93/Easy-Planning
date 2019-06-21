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
  </head>
  <body>

    @if(Auth::user())
    <div>Ciao {{@Auth::user()->name}}</div>
    @endif

    @foreach ($workers as $worker)
    <span>TABELLA DI {{ $worker->name }}</span>
    <div id='calendar{{ $worker->name }}'></div>
    <script>
      $(document).ready(function() {
        // page is now ready, initialize the calendar...
        $('#calendar{{ $worker->name }}').fullCalendar({
          lang: 'it',
          header: {
              left: 'prev,next today',
              center: 'title',
              right: 'month,basicWeek,basicDay',
          },
          defaultView: 'basicWeek',
          hiddenDays: [0,6],
          // put your options and callbacks here
          events: [
            @foreach($tasks as $task)
              @if ($worker->name === $task->operator)
              {
                  title : '{{ $task->operator }} >>> {{ $task->activity }}',
                  start : '{{ $task->date }}',
                  end : '{{ $task->enddate }}',
              },
              @endif
            @endforeach
          ]
        });
        @foreach($tasks as $task)
          @if($worker->name === $task->operator)
            $('.fc-title').toggleClass('{{ $task->operator }}');
            $('.{{ $task->operator }}').removeClass('fc-title');
          @endif
        @endforeach
      });
    </script>
    @endforeach

    <div id='calendarHard'></div>
    <script>
      $(document).ready(function() {
        $('intensity').removeClass('intensity');
        $('#calendarHard').fullCalendar({
          defaultView: 'basicWeek',
          hiddenDays: [0,6],
          events: [
            @foreach($tasks as $task)
              {
                  start : '{{ $task->date }}',
                  end : '{{ $task->enddate }}',
              },
            @endforeach
          ]
        });
        let busyWorker = 0;
        let worker, date, assWorker, day, today, strName;
        
          @for($numDay=0;$numDay<5;$numDay++)
            @if($numDay===0)
              strName = 'mon';
            @endif
            @if($numDay===1)
              strName = 'tue';
            @endif
            @if($numDay===2)
              strName = 'wed';
            @endif
            @if($numDay===3)
              strName = 'thu';
            @endif
            @if($numDay===4)
              strName = 'fri';
            @endif
            console.log("STRNAME: ", strName);
            date = $('#calendarTest1 > div.fc-view-container > div > table > tbody > tr > td > div > div > div > div.fc-bg > table > tbody > tr > .fc-widget-content.fc-'+strName)[0].dataset.date;
            console.log("DATE: ", date);
          @endfor
        
      });
    </script>
  </body>
  
</html>