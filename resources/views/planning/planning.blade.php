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
          eventMouseover : function(data, event, element) {
            var content = '<h3>'+data.title+'</h3>' + 
                '<p><b>Start:</b> '+data.start+'<br />' + 
                (data.end && '<p><b>End:</b> '+data.end+'</p>' || '');

            $(element).tooltip({
            title: event.title,
            container: "body"
        });
          },
          // put your options and callbacks here
          events: [
            @foreach($tasks as $task)
              @if ($worker->name === $task->operator)
              {
                  title : '{{ $task->operator }} >>> {{ $task->activity }}',
                  start : '{{ $task->date }}',
                  end : '{{ $task->enddate }}',
                  url : '{{ action("PlanningController@edit", $task["id"]) }}',
                  className : '{{ $task->type }}',
              },
              @endif
            @endforeach
          ]
        });
      });
    </script>
    @endforeach

    <div id='calendarHardMor'></div>
    <div id='calendarHardEvn'></div>
    <script>
      let dayDate, oldDayDate, operator, oldOperator, busy;
      $(document).ready(function() {
        $('#calendarHardMor').fullCalendar({
          defaultView: 'basicWeek',
          hiddenDays: [0,6],
          dayRender: function(date,cell){
              console.log("dayRender just entried");
              cell.css('background-color','#03a300');
              busy = 1;
              @foreach($tasksMor as $taskMor)
                if(cell[0].dataset.date === "{{ $taskMor->date }}"){
                  dayDate = "{{ $taskMor->date }}";
                  operator = "{{ $taskMor->operator }}";
                  if(oldDayDate === dayDate){
                    if(oldOperator != operator)
                      busy++;
                  }
                  if(busy===3){
                    console.log("busy = 3");
                    cell.css('background-color','#31ff2e');
                  }
                  if(busy===4){
                    console.log("busy = 4");
                    cell.css('background-color','#fffc2e');
                  }
                  if(busy>=5){
                    console.log("busy : ", busy);
                    cell.css('background-color','red');
                  }
                  oldOperator = operator;
                  oldDayDate = dayDate;
                }
              @endforeach
            }
        });
        $('#calendarHardEvn').fullCalendar({
          defaultView: 'basicWeek',
          hiddenDays: [0,6],
          dayRender: function(date,cell){

          }
        });
      });
    </script>
  </body>
</html>