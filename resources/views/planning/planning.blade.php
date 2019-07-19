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
                  url : '{{ $task->id }}/edit',
              },
              @endif
            @endforeach
          ]
        });
        @foreach($tasks as $task)
          @if($worker->name === $task->operator)
            $('.fc-title').toggleClass('{{ $task->operator }}');
            $('.{{ $task->operator }}').removeClass('fc-title');
            $("#calendar{{ $worker->name }} > div.fc-view-container > div > table > tbody > tr > td > div > div > div > div.fc-content-skeleton > table > tbody > tr > td.fc-event-container > a").addClass("{{ $task->type }}");
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
                title : '{{ $task->date }}',
              },
            @endforeach
          ],
          eventRender: function(){
            //add here your eventRender
          },
        });
        let dateActivity, dateCalendar, dom, i, allDom, myDom, workerBusy, operator, date, dateOperator, oldDateOperator;
        oldDateOperator = [ ];
        @foreach($tasks as $task)
          dateActivity = "{{ $task->date }}";
          operator = "{{ $task->operator }}";
          dateOperator = [ operator, dateActivity ];
          dom = $("#calendarHard > div.fc-view-container > div > table > tbody > tr > td > div > div > div > div.fc-bg > table > tbody > tr > td");
          allDom = $("#calendarHard > div.fc-view-container > div > table > tbody > tr > td > div > div > div > div.fc-bg > table > tbody > tr").children();
          for(i=0;i<dom.length;i++){
            dateCalendar = dom[i].dataset.date;
            //console.log("dateOperator === oldDateOperator", dateOperator != oldDateOperator);
            if(dateActivity === dateCalendar) {
              if(oldDateOperator[0] != dateOperator[0]){
                var insertDom = function insertAtIndex(e) {
                  let index = e+1;
                  $("#calendarHard > div.fc-view-container > div > table > tbody > tr > td > div > div > div > div.fc-bg > table > tbody > tr > td:nth-child(" + (index) + ")").append("<div>great things</div>");
                }
                insertDom(i);
                workerBusy = $("#calendarHard > div.fc-view-container > div > table > tbody > tr > td > div > div > div > div.fc-bg > table > tbody > tr > td:nth-child(" + (i+1) + ")").children().length;
                if({{sizeof($workers)}} - workerBusy === 3)
                $("#calendarHard > div.fc-view-container > div > table > tbody > tr > td > div > div > div > div.fc-bg > table > tbody > tr > td:nth-child(" + (i+1) + ")").toggleClass("noOne");
                if({{sizeof($workers)}} - workerBusy === 2)
                $("#calendarHard > div.fc-view-container > div > table > tbody > tr > td > div > div > div > div.fc-bg > table > tbody > tr > td:nth-child(" + (i+1) + ")").toggleClass("one");
                if({{sizeof($workers)}} - workerBusy === 1)
                $("#calendarHard > div.fc-view-container > div > table > tbody > tr > td > div > div > div > div.fc-bg > table > tbody > tr > td:nth-child(" + (i+1) + ")").toggleClass("two");
                if({{sizeof($workers)}} - workerBusy === 0)
                $("#calendarHard > div.fc-view-container > div > table > tbody > tr > td > div > div > div > div.fc-bg > table > tbody > tr > td:nth-child(" + (i+1) + ")").toggleClass("three");
                console.log("dateOperator - oldDateOperator: ", dateOperator, oldDateOperator);
                oldDateOperator = [ operator, dateActivity ];
              }
            }
          }
        @endforeach
      });
    </script>
  </body>
</html>